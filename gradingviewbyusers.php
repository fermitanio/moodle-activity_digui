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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This file contains all necessary code to manage grades
 *
 * @package mod-digui-1.0
 * @copyrigth 2016 Fernando Martin fermitanio@hotmail.com
 *
 * @author Fernando Martin
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot . '/mod/digui/locallib.php');
require_once($CFG->dirroot . '/mod/digui/pagelib.php');
require_once($CFG->libdir.'/gradelib.php');

$diguiid = required_param('diguiid', PARAM_INT);
$subdiguiid = required_param('subdiguiid', PARAM_INT);
$selectedsubdiguiid = optional_param('selectedsubdiguiid', -1, PARAM_INT);
$action = optional_param('action', '', PARAM_TEXT);

//$urlparams = array('id' => $diguiid,
//                  'action' => optional_param('action', '', PARAM_TEXT),
//                  'rownum' => optional_param('rownum', 0, PARAM_INT),
//                  'useridlistid' => optional_param('action', 0, PARAM_INT));
//
//$url = new moodle_url('/mod/assign/view.php', $urlparams);

//$cm = get_coursemodule_from_id('digui', $diguiid, 0, false, MUST_EXIST);
if (!$cm = get_coursemodule_from_instance('digui', $diguiid)) {
    print_error('invalidcoursemodule');
}

$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

require_login($course, true, $cm);
//$PAGE->set_url($url);

$context = context_module::instance($cm->id);
require_capability('mod/digui:viewgrade', $context);

if (!$digui = digui_get_digui($cm->instance)) {
        print_error('incorrectdiguiid', 'digui');
    }
    
if (!$subdigui = digui_subdigui_get_subdigui_by_id($subdiguiid)) {
    print_error('incorrectsubdiguiid', 'digui');
}

if (!empty($action)) {
   // @TODO: Offer option:
   // a. To export all text or only synthesis. 
   // b. To export all pages or only one chosen by user. 
   digui_files_download_file($digui, $subdiguiid, $selectedsubdiguiid);
   exit;
}

$diguipage = new page_digui_grading($digui, $subdigui, $cm);
$diguipage->print_header();

echo '<table style="width: 100%; background-color: #FFFFFF;">
 <tr>
    <th style="border-style: solid; border-color: #EEEEEE; border-width: 1px; width: 25%">'.get_string('name', 'digui').'</th>   
    <th style="border-style: solid; border-color: #EEEEEE; border-width: 1px; width: 25%">'.get_string('email', 'digui').'</th>
    <th style="border-style: solid; border-color: #EEEEEE; border-width: 1px; width: 25%">'.get_string('grade:status', 'digui').'</th>
    <th style="border-style: solid; border-color: #EEEEEE; border-width: 1px; width: 25%">'.get_string('grade:grade', 'digui').'</th>
    <th style="border-style: solid; border-color: #EEEEEE; border-width: 1px; width: 25%">'.get_string('pluginname', 'digui').'</th>
 </tr>';

$linkimg = new moodle_url('/mod/digui/pix/gradefeedback.png');

$contextcourse = context_course::instance($course->id);

$users = array();
$roleid = array(1,2,3,4,5,6,7,8);
foreach ($roleid as $id) {
    $users = $users + get_role_users($id, $contextcourse, true);
}


//$allowedgroups = groups_get_all_groups($cm->course, 0, $cm->groupingid); // any group in grouping
//if ($allowedgroups) {
//    foreach ($allowedgroups as $group) {
        // Sólo se listan los grupos a los que pertenece el 
        // usuario actual, salvo que dicho usuario sea 
        // administrador o profesor.
//        $users = groups_get_members($group->id);
        foreach ($users as $user) {
            
            // Add a row.
            echo '<tr>';
            $personalinformation = digui_user_get_user_information($user->id);
            $name = '';
            $name .= $personalinformation->firstname;
            $name .= " ";
            $name .= $personalinformation->lastname;
            
            // First column.
            $linkurl = new moodle_url('/user/view.php', array('id' => $user->id, 'course' => $course->id));
            echo '<td style="border-style: solid; border-color: #EEEEEE; border-width: 1px;"><a href="'.$linkurl.'">'.$name.'</a></td>';                
            
            // Second column.
            echo '<td style="border-style: solid; border-color: #EEEEEE; border-width: 1px;">'.$personalinformation->email.'</td>';

            // Third column.
            $datacell = '<td style="border-style: solid; border-color: #EEEEEE; border-width: 1px;">';
            if (digui_grading_get_grade_status_for_user($digui->id, $user->id)) {
                $datacell .= '<p style="margin:0px">'.get_string('grade:graded', 'digui').'</p>';
            }
            else {
                $datacell .= '<p style="margin:0px">'.get_string('grade:notgraded', 'digui').'</p>';
            }
            
            $datacell .= ' ';
            if (digui_user_get_edition_status_for_user($digui->id, 0, $user->id)) {
                $datacell .= '<p style="margin:0px">'.get_string('grade:editing', 'digui').'</p>';
            }
            else {
                $datacell .= '<p style="margin:0px">'.get_string('grade:notediting', 'digui').'</p>';
            }
            
            $datacell .= '</td>';
            echo $datacell; 
            
            // Fourth column.
            $grade = grade_get_grades($course->id, 'mod', 'digui', $digui->id, $user->id);
            $sgrade = '';
            if (isset($grade->items[0]->grades[$user->id]) && isset($grade->items[0]->grades[$user->id]->grade)) {
                $sgrade = $grade->items[0]->grades[$user->id]->grade;
            }
            else {
                $sgrade = "-";
            }
            
            echo '<td style="border-style: solid; border-color: #EEEEEE; border-width: 1px;">';
            // Active user is teacher, or self-assessment is enabled.
            if ($subdigui->userid == $user->id) {
                if ($digui->selfassessment == 1 ||
                        digui_user_is_teacher_role($subdigui->userid, $course->id)) {
                    $linkurl = new moodle_url('/mod/digui/gradingusergrade.php', array('diguiid' => $diguiid, 'subdiguiid' => $subdiguiid, 'userid' => $user->id, 'returnurl' => '/mod/digui/gradingviewbyusers.php'));
                    echo '<a href="'.$linkurl.'"><img src="'.$linkimg.'" alt="'.get_string('grade:grade', 'digui').'" /></a>';
                }
            }
            // Peer-assessment is enabled.
            else if ($digui->grademode == 1 || digui_user_is_teacher_role($subdigui->userid, $course->id)) {
                $linkurl = new moodle_url('/mod/digui/gradingusergrade.php', array('diguiid' => $diguiid, 'subdiguiid' => $subdiguiid, 'userid' => $user->id, 'returnurl' => '/mod/digui/gradingviewbyusers.php'));
                echo '<a href="'.$linkurl.'"><img src="'.$linkimg.'" alt="'.get_string('grade:grade', 'digui').'" /></a>';
            }
            echo '<p style="margin:0px">' .$sgrade. '</p></td>';
            
            // Fifth column.
            if (digui_user_get_edition_status_for_user($digui->id, 0, $user->id)) {
                $subdiguiaux = digui_subdigui_get_subdigui_by_user($digui->id, $user->id);
                
                $initiala = strtoupper(substr($personalinformation->firstname, 0, 1));
                $initialb = strtoupper(substr($personalinformation->lastname, 0, 1));

                $linkurl = new moodle_url('/mod/digui/gradingviewbyusers.php', array('diguiid' => $diguiid, 'subdiguiid' => $subdiguiid, 'selectedsubdiguiid' => $subdiguiaux->id, 'action' => 'download'));
                echo '<td style="border-style: solid; border-color: #EEEEEE; border-width: 1px;"><a href="'.$linkurl.'">Digui-'.$initiala.''.$initialb.'</a></td>';
            }
            else {
                echo '<td></td>';
            }
            echo '</tr>';
        }
//    }
//}
    
echo '</table>';

$diguipage->print_footer();