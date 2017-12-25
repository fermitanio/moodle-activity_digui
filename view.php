<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * This file is the entry point to the assign module.
 *
 * @package mod-digui-1.0
 * @copyrigth 2016 Fernando Martin fermitanio@hotmail.com
 *
 * @author Fernando Martin
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot . '/mod/digui/lib.php');
require_once($CFG->dirroot . '/mod/digui/locallib.php');
require_once($CFG->dirroot . '/mod/digui/pagelib.php');

$cmid = optional_param('id', 0, PARAM_INT); // Course Module ID

/*
 *
 * URL params: id -> course module id
 *
 */
if ($cmid) {
    // Cheacking course module instance
    if (!$cm = get_coursemodule_from_id('digui', $cmid)) {
        print_error('invalidcoursemodule');
    }

    // Checking course instance
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

    require_login($course, true, $cm);

    // Checking digui instance
    if (!$digui = digui_get_digui($cm->instance)) {
        print_error('incorrectdiguiid', 'digui');
    }
    $PAGE->set_cm($cm);

    // Getting current group id
    $currentgroup = groups_get_activity_group($cm);

    // Getting current user id
    $userid = $USER->id;
    
    // Getting first page, or if it does not exists, redirecting to upload file.
    if (!$DB->get_record('digui_pages', array('diguiid' => $digui->id, 'pagenum' => 1))) {
        $params = array('diguiid' => $digui->id, 'group' => $currentgroup, 'uid' => $userid);
        $url = new moodle_url('/mod/digui/upload.php', $params);
        redirect($url);
    }

    // Is the user registered in the digui? In other words, get the subdigui 
    // corresponding to that digui, group and user.
    if (!($subdigui = digui_subdigui_get_subdigui_by_group($digui->id, $currentgroup, $userid))) {

        // It's the first time this user uses this digui. So, this user is not
        // registered in the digui, and we must register him in the user list.
        if (!$subdigui = digui_subdigui_get_subdigui_by_user($digui->id, $userid)) {
            
            // Up to eleven different users can be registered in the same digui.
            // Future Digui versions could add support for more users.
            $usersediting = digui_count_users_editing($digui->id);
            if ($usersediting == 11) {
                print_error('exceeded the maximum allowed number of users (11 users)', 'digui');
            }
            
            // Register the new user in the digui.
            $subdiguiid = digui_subdigui_add_subdigui($digui->id, $currentgroup, $userid);
            // Assign a color to the new user.
            digui_color_assign_highlight_color($digui, $subdiguiid);
        }
        // This user is already registered in this digui, but not this user is
        // not in the current group ($currentgroup variable). This may happends,
        // for example, if a user is in group A, and he registered in the digui.
        // After that, this user registered in group B, and entered in the digui
        // again. In this cases, we must not registered this user two times 
        // (because then would be two user accounts with the same user, one
        // for the user in group A and another for the user in group B). We
        // must delete the previous user account (corresponding to user in group
        // A), and register the user again in the digui, but being in the group
        // B. In addition, since the previous account could have associated
        // information (for example, if the user has edited the digui), we must
        // export this information to the new account, before deleting the
        // previous account.
        else {
            global $DB;
    
            // Register the user in the digui again. By now, the same user is
            // duplicated, because he is registered in two user accounts. 
            $newsubdiguiid = digui_subdigui_add_subdigui($digui->id, $currentgroup, $userid);
            
            // Update the tables in the database, where appears the old 
            // information about the previous user account.
            $prevsubdiguiid = $subdigui->id;
            $result = true;
            
            $sql = "UPDATE {digui_colors_assignments} 
            SET subdiguiid = ? 
            WHERE subdiguiid = ?";
            if (!$DB->execute($sql, array($newsubdiguiid, $prevsubdiguiid))) {
                $result = false;
            }
            
            // Update the digui_spans table.
            $sql = "SELECT * FROM {digui_spans} 
            WHERE subdiguiids REGEXP '[[:<:]]" . $prevsubdiguiid . "[[:>:]]'= 1";
            $spans = $DB->get_records_sql($sql);

            $sql = "UPDATE {digui_spans} 
            SET subdiguiids = ? 
            WHERE id = ?";

            if (!is_null($spans) && count($spans) > 0) {
                foreach ($spans as $span) {
                    $subdiguiids = preg_replace('~\b' . $prevsubdiguiid . '\b~si', $newsubdiguiid, $span->subdiguiids);
                    if (!$DB->execute($sql, array($subdiguiids, $span->id))) {
                        $result = false;
                    }
                }
            }
            
            /*
            // But before the updating, get the span tags of the current user...
            $spans = array();
            $numofpages = digui_page_get_number_of_pages($digui->id);
            for ($i = 1; $i < $numofpages + 1; $i++) {
                $spans[] = digui_span_get_by_subdiguiid($digui->id, $prevsubdiguiid, $i, 0);
            }

            // ...And do the updating.
            foreach ($spans as $span) {
                if (digui_span_is_included($prevsubdiguiid, $span->subdiguiids)) {
                    $subdiguiids = explode(',', $span->subdiguiids);
                    $subdiguiids = digui_array_replace_value($subdiguiids, $prevsubdiguiid, $newsubdiguiid);
                    
                    if (!$DB->execute($sql, array($newsubdiguiid, $span->id, $prevsubdiguiid))) {
                    $result = false;
                    }
                }
            }
            */
            
            $sql = "UPDATE {digui_last_user_modification} 
            SET subdiguiid = ? 
            WHERE diguiid = ? AND subdiguiid = ?";
            if (!$DB->execute($sql, array($newsubdiguiid, $digui->id, $prevsubdiguiid))) {
                $result = false;
            }
            
            $sql = "UPDATE {digui_page_version} 
            SET subdiguiid = ? 
            WHERE diguiid = ? AND subdiguiid = ?";
            if (!$DB->execute($sql, array($newsubdiguiid, $digui->id, $prevsubdiguiid))) {
                $result = false;
            }
            
            if (!$result) {
                // Undo previous operations and exit.
                $DB->delete_records('digui_subdiguis', array('id' => $newsubdiguiid));
                print_error('invalidquery');
            }
            // Delete the previous user account. With this operation, we prevent
            // that same user is registered two or more time in the same digui. 
            $DB->delete_records('digui_subdiguis', array('id' => $prevsubdiguiid));
            
            $subdiguiid = $newsubdiguiid;   
        }
    }
    // This user is already registered in the digui, and is in the current group.
    else {
        $subdiguiid = $subdigui->id;
    }

    /*

    // Getting the subdigui corresponding to that digui, group and user.
    if (!$subdigui = digui_subdigui_get_subdigui_by_group($digui->id, $currentgroup, $userid)) {

        // Some times, current group is not zero and active user isn't assigned
        // to current group. This behavior of moodle isn't desired for digui
        // purposes, so we must prevent it for don't creating incorrect subdiguis.
        if (!$subdigui = digui_subdigui_get_subdigui_by_group($digui->id, 0, $userid)) {
            $usersediting = digui_count_users_editing($digui->id);
            if ($usersediting == 12) {
                print_error('exceeded the maximum allowed number of users (12 users)', 'digui');
            }

            // It's the first time that user enter to the module. So, as this user
            // doesn't appear in the database, we must add it to the database.
            if (groups_is_member($currentgroup, $userid)) {
                $subdiguiid = digui_subdigui_add_subdigui($digui->id, $currentgroup, $userid);
            }
            else {
                $subdiguiid = digui_subdigui_add_subdigui($digui->id, 0, $userid);
            }
            // Asignar, al usuario actual, un color de subrayado.
            digui_color_assign_highlight_color($digui, $subdiguiid);
        }
        else if (!groups_is_member($currentgroup, $userid)) {
            $subdiguiid = $subdigui->id;   
        }
        else {
            print_error('incorrectsubdiguiid', 'digui');
        }
    }
    else {
        $subdiguiid = $subdigui->id;
    }
         
    */
    
    // La primera vez que un usuario (llamémoslo u1) entra al módulo, no puede 
    // elegir previamente ningún usuario del listado (llamémoslo u2). Por tanto, 
    // se debe cumplir que u1 = u2.
    $params = array('diguiid' => $digui->id, 'subdiguiid' => $subdiguiid, 'selectedsubdiguiid' => $subdiguiid, 'group' => $currentgroup, 'uid' => $userid, 'pagenum' => 1);
    if ($digui->diguimode == 'individual') {
        $url = new moodle_url('/mod/digui/viewi.php', $params);
    }
    else {
        $url = new moodle_url('/mod/digui/viewc.php', $params);
    }
    redirect($url);

} else {
    print_error('incorrectparameters');
}