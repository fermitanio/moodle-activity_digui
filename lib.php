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
 * This contains functions and classes that will be used by scripts in digui module
 *
 * @package mod-digui-1.0
 * @copyrigth 2016 Fernando Martin fermitanio@hotmail.com
 *
 * @author Fernando Martin
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod.html) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param object $instance An object from the form in mod.html
 * @return int The id of the newly inserted digui record
 **/
function digui_add_instance($digui) {
    global $DB;

    $digui->timemodified = time();
    # May have to add extra stuff in here #

    return $DB->insert_record('digui', $digui);
}

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod.html) this function
 * will update an existing instance with new data.
 *
 * @param object $instance An object from the form in mod.html
 * @return boolean Success/Fail
 **/
function digui_update_instance($digui) {
    global $DB;

    $digui->timemodified = time();
    $digui->id = $digui->instance;
    if (empty($digui->selfassessment)) {
        $digui->selfassessment = 0;
    }
    # May have to add extra stuff in here #

    return $DB->update_record('digui', $digui);
}

/**
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 **/
function digui_delete_instance($id) {
    global $DB;

    if (!$digui = $DB->get_record('digui', array('id' => $id))) {
        return false;
    }

    // Cheacking course module instance
//    if (!$cm = get_coursemodule_from_id('digui', $id)) {
//        print_error('invalidcoursemodule');
//    }

    
    $result = true;

    # Get subdigui information #
    $subdiguis = $DB->get_records('digui_subdiguis', array('diguiid' => $digui->id));

    foreach ($subdiguis as $subdigui) {
//        # Get existing links, and delete them #
//        if (!$DB->delete_records('digui_links', array('subdiguiid' => $subdigui->id), IGNORE_MISSING)) {
//            $result = false;
//        }
        
        # Delete any subdiguis #
        if (!$DB->delete_records('digui_subdiguis', array('id' => $subdigui->id), IGNORE_MISSING)) {
            $result = false;
        }
        
         # Delete ...#
        if (!$DB->delete_records('digui_colors_assignments', array('subdiguiid' => $subdigui->id), IGNORE_MISSING)) {
            $result = false;
        }
            }
    
    // Delete spans.
    if (!$DB->delete_records('digui_spans', array('diguiid' => $digui->id))) {
        $result = false;
    }
 
    // Delete version.
    if (!$DB->delete_records('digui_page_version', array('diguiid' => $digui->id))) {
        $result = false;
    }

    # Delete ...#
    if (!$DB->delete_records('digui_pages', array('diguiid' => $digui->id), IGNORE_MISSING)) {
        $result = false;
    }

    if (!$cm = get_coursemodule_from_instance('digui', $id)) {
        print_error('invalidcoursemodule');
    }
    
    // Checking course instance
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    
    global $CFG;
    require_once($CFG->dirroot . '/mod/digui/locallib.php');    
    
    // Delete items from the gradebook.
    if (! digui_grading_delete_grades($course->id, $digui->id)) {
        $result = false;
    }
        
    # Delete any dependent records here #
    if (!$DB->delete_records('digui', array('id' => $digui->id))) {
        $result = false;
    }

    return $result;
}

//function digui_reset_userdata($data) {
//    global $CFG,$DB;
//    require_once($CFG->dirroot . '/mod/digui/pagelib.php');
//    require_once($CFG->dirroot . '/tag/lib.php');
//
//    $componentstr = get_string('modulenameplural', 'digui');
//    $status = array();
//
//    //get the digui(s) in this course.
//    if (!$diguis = $DB->get_records('digui', array('course' => $data->courseid))) {
//        return false;
//    }
//    $errors = false;
//    foreach ($diguis as $digui) {
//
//        // remove all comments
//        if (!empty($data->reset_digui_comments)) {
//            if (!$cm = get_coursemodule_from_instance('digui', $digui->id)) {
//                continue;
//            }
//            $context = context_module::instance($cm->id);
//            $DB->delete_records_select('comments', "contextid = ? AND commentarea='digui_page'", array($context->id));
//            $status[] = array('component'=>$componentstr, 'item'=>get_string('deleteallcomments'), 'error'=>false);
//        }
//
//        if (!empty($data->reset_digui_tags)) {
//            # Get subdigui information #
//            $subdiguis = $DB->get_records('digui_subdiguis', array('diguiid' => $digui->id));
//
//            foreach ($subdiguis as $subdigui) {
//                if ($pages = $DB->get_records('digui_pages', array('subdiguiid' => $subdigui->id))) {
//                    foreach ($pages as $page) {
//                        $tags = tag_get_tags_array('digui_pages', $page->id);
//                        foreach ($tags as $tagid => $tagname) {
//                            // Delete the related tag_instances related to the digui page.
//                            $errors = tag_delete_instance('digui_pages', $page->id, $tagid);
//                            $status[] = array('component' => $componentstr, 'item' => get_string('tagsdeleted', 'digui'), 'error' => $errors);
//                        }
//                    }
//                }
//            }
//        }
//    }
//    return $status;
//}

//
//function digui_reset_course_form_definition(&$mform) {
//    $mform->addElement('header', 'diguiheader', get_string('modulenameplural', 'digui'));
//    $mform->addElement('advcheckbox', 'reset_digui_tags', get_string('removealldiguitags', 'digui'));
//    $mform->addElement('advcheckbox', 'reset_digui_comments', get_string('deleteallcomments'));
//}

/**
 * Return a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @return null
 * @todo Finish documenting this function
 **/
//function digui_user_outline($course, $user, $mod, $digui) {
//    $return = NULL;
//    return $return;
//}

/**
 * Print a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * @return boolean
 * @todo Finish documenting this function
 **/
//function digui_user_complete($course, $user, $mod, $digui) {
//    return true;
//}

/**
 * Indicates API features that the digui supports.
 *
 * @uses FEATURE_GROUPS
 * @uses FEATURE_GROUPINGS
 * @uses FEATURE_GROUPMEMBERSONLY
 * @uses FEATURE_MOD_INTRO
 * @uses FEATURE_COMPLETION_TRACKS_VIEWS
 * @uses FEATURE_COMPLETION_HAS_RULES
 * @uses FEATURE_GRADE_HAS_GRADE
 * @uses FEATURE_GRADE_OUTCOMES
 * @param string $feature
 * @return mixed True if yes (some features may use other values)
 */
function digui_supports($feature) {
    switch ($feature) {
    case FEATURE_GROUPS:
        return true;
    case FEATURE_GROUPINGS:
        return true;
    case FEATURE_GROUPMEMBERSONLY:
        return true;
    case FEATURE_MOD_INTRO:
        return true;
    case FEATURE_COMPLETION_TRACKS_VIEWS:
        return true;
    case FEATURE_GRADE_HAS_GRADE:
        return true;
    case FEATURE_GRADE_OUTCOMES:
        return false;
    case FEATURE_RATE:
        return false;
    case FEATURE_BACKUP_MOODLE2:
        return true;
    case FEATURE_SHOW_DESCRIPTION:
        return true;

    default:
        return null;
    }
}

/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in digui activities and print it out.
 * Return true if there was output, or false is there was none.
 *
 * @global $CFG
 * @global $DB
 * @uses CONTEXT_MODULE
 * @uses VISIBLEGROUPS
 * @param object $course
 * @param bool $viewfullnames capability
 * @param int $timestart
 * @return boolean
 **/
function digui_print_recent_activity($course, $viewfullnames, $timestart) {
    global $CFG, $DB, $OUTPUT;

    // $usernamefields = get_all_user_name_fields(true, 'u');
//    $sql = "SELECT p.*, w.id as diguiid, sw.groupid, $usernamefields
//            FROM {digui_pages} p
//                JOIN {digui_subdiguis} sw ON sw.id = p.subdiguiid
//                JOIN {digui} w ON w.id = sw.diguiid
//                JOIN {user} u ON u.id = sw.userid
//            WHERE p.timemodified > ? AND w.course = ?
//            ORDER BY p.timemodified ASC";
      $sql = "SELECT p.*, w.id as diguiid 
            FROM {digui_pages} p
                JOIN {digui} w ON w.id = p.diguiid
            WHERE p.timemodified > ? AND w.course = ?
            ORDER BY p.timemodified ASC";
    if (!$pages = $DB->get_records_sql($sql, array($timestart, $course->id))) {
        return false;
    }
    $modinfo = get_fast_modinfo($course);

    $diguis = array();

    $modinfo = get_fast_modinfo($course);

    foreach ($pages as $page) {
        if (!isset($modinfo->instances['digui'][$page->diguiid])) {
            // not visible
            continue;
        }
        $cm = $modinfo->instances['digui'][$page->diguiid];
        if (!$cm->uservisible) {
            continue;
        }
        $context = context_module::instance($cm->id);

        if (!has_capability('mod/digui:viewpage', $context)) {
            continue;
        }

        $groupmode = groups_get_activity_groupmode($cm, $course);

        if ($groupmode) {
            if ($groupmode == SEPARATEGROUPS and !has_capability('mod/digui:manage', $context)) {
                // separate mode
                if (isguestuser()) {
                    // shortcut
                    continue;
                }

                if (is_null($modinfo->groups)) {
                    $modinfo->groups = groups_get_user_groups($course->id); // load all my groups and cache it in modinfo
                    }

                if (!in_array($page->groupid, $modinfo->groups[0])) {
                    continue;
                }
            }
        }
        $diguis[] = $page;
    }
    unset($pages);

    if (!$diguis) {
        return false;
    }
    echo $OUTPUT->heading(get_string("updateddiguipages", 'digui') . ':', 3);
    foreach ($diguis as $digui) {
        $cm = $modinfo->instances['digui'][$digui->diguiid];
        $link = $CFG->wwwroot . '/mod/digui/view.php?pageid=' . $digui->id;
        print_recent_activity_note($digui->timemodified, $digui, $cm->name, $link, false, $viewfullnames);
    }

    return true; //  True if anything was printed, otherwise false
}
/**
 * Function to be run periodically according to the moodle cron
 * This function searches for things that need to be done, such
 * as sending out mail, toggling flags etc ...
 *
 * @uses $CFG
 * @return boolean
 * @todo Finish documenting this function
 **/
//function digui_cron() {
//    global $CFG;
//
//    return true;
//}

/**
 * Must return an array of grades for a given instance of this module,
 * indexed by user.  It also returns a maximum allowed grade.
 *
 * Example:
 *    $return->grades = array of grades;
 *    $return->maxgrade = maximum allowed grade;
 *
 *    return $return;
 *
 * @param int $diguiid ID of an instance of this module
 * @return mixed Null or object with an array of grades and with the maximum grade
 **/
//function digui_grades($diguiid) {
//    return null;
//}

/**
 * This function returns if a scale is being used by one digui
 * it it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $diguiid ID of an instance of this module
 * @return mixed
 * @todo Finish documenting this function
 **/
//function digui_scale_used($diguiid, $scaleid) {
//    $return = false;
//
//    //$rec = get_record("digui","id","$diguiid","scale","-$scaleid");
//    //
//    //if (!empty($rec)  && !empty($scaleid)) {
//    //    $return = true;
//    //}
//
//    return $return;
//}

/**
 * Checks if scale is being used by any instance of digui.
 * This function was added in 1.9
 *
 * This is used to find out if scale used anywhere
 * @param $scaleid int
 * @return boolean True if the scale is used by any digui
 */
//function digui_scale_used_anywhere($scaleid) {
//    global $DB;
//
//    //if ($scaleid and $DB->record_exists('digui', array('grade' => -$scaleid))) {
//    //    return true;
//    //} else {
//    //    return false;
//    //}
//
//    return false;
//}

/**
 * file serving callback
 *
 * @copyright Josep Arus
 * @package  mod_digui
 * @category files
 * @param stdClass $course course object
 * @param stdClass $cm course module object
 * @param stdClass $context context object
 * @param string $filearea file area
 * @param array $args extra arguments
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool false if the file was not found, just send the file otherwise and do not return anything
 */
//function digui_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
//    global $CFG;
//
//    if ($context->contextlevel != CONTEXT_MODULE) {
//        return false;
//    }
//
//    require_login($course, true, $cm);
//
//    require_once($CFG->dirroot . "/mod/digui/locallib.php");
//
//    if ($filearea == 'attachments') {
//        $swid = (int) array_shift($args);
//
//        if (!$subdigui = digui_subdigui_get_subdigui_by_id($swid)) {
//            return false;
//        }
//
//        require_capability('mod/digui:viewpage', $context);
//
//        $relativepath = implode('/', $args);
//
//        $fullpath = "/$context->id/mod_digui/attachments/$swid/$relativepath";
//
//        $fs = get_file_storage();
//        if (!$file = $fs->get_file_by_hash(sha1($fullpath)) or $file->is_directory()) {
//            return false;
//        }
//
//        send_stored_file($file, null, 0, $options);
//    }
//}

/**
 * Returns all other caps used in digui module
 *
 * @return array
 */
//function digui_get_extra_capabilities() {
//    return array('moodle/comment:view', 'moodle/comment:post', 'moodle/comment:delete');
//}

/**
 * Running addtional permission check on plugin, for example, plugins
 * may have switch to turn on/off comments option, this callback will
 * affect UI display, not like pluginname_comment_validate only throw
 * exceptions.
 * Capability check has been done in comment->check_permissions(), we
 * don't need to do it again here.
 *
 * @package  mod_digui
 * @category comment
 *
 * @param stdClass $comment_param {
 *              context  => context the context object
 *              courseid => int course id
 *              cm       => stdClass course module object
 *              commentarea => string comment area
 *              itemid      => int itemid
 * }
 * @return array
 */
//function digui_comment_permissions($comment_param) {
//    return array('post'=>true, 'view'=>true);
//}

/**
 * Validate comment parameter before perform other comments actions
 *
 * @param stdClass $comment_param {
 *              context  => context the context object
 *              courseid => int course id
 *              cm       => stdClass course module object
 *              commentarea => string comment area
 *              itemid      => int itemid
 * }
 *
 * @package  mod_digui
 * @category comment
 *
 * @return boolean
 */
//function digui_comment_validate($comment_param) {
//    global $DB, $CFG;
//    require_once($CFG->dirroot . '/mod/digui/locallib.php');
//    // validate comment area
//    if ($comment_param->commentarea != 'digui_page') {
//        throw new comment_exception('invalidcommentarea');
//    }
//    // validate itemid
//    if (!$record = $DB->get_record('digui_pages', array('id'=>$comment_param->itemid))) {
//        throw new comment_exception('invalidcommentitemid');
//    }
//    if (!$subdigui = digui_subdigui_get_subdigui_by_id($record->subdiguiid)) {
//        throw new comment_exception('invalidsubdiguiid');
//    }
//    if (!$digui = digui_get_digui_from_pageid($comment_param->itemid)) {
//        throw new comment_exception('invalidid', 'data');
//    }
//    if (!$course = $DB->get_record('course', array('id'=>$digui->course))) {
//        throw new comment_exception('coursemisconf');
//    }
//    if (!$cm = get_coursemodule_from_instance('digui', $digui->id, $course->id)) {
//        throw new comment_exception('invalidcoursemodule');
//    }
//    $context = context_module::instance($cm->id);
//    // group access
//    if ($subdigui->groupid) {
//        $groupmode = groups_get_activity_groupmode($cm, $course);
//        if ($groupmode == SEPARATEGROUPS and !has_capability('moodle/site:accessallgroups', $context)) {
//            if (!groups_is_member($subdigui->groupid)) {
//                throw new comment_exception('notmemberofgroup');
//            }
//        }
//    }
//    // validate context id
//    if ($context->id != $comment_param->context->id) {
//        throw new comment_exception('invalidcontext');
//    }
//    // validation for comment deletion
//    if (!empty($comment_param->commentid)) {
//        if ($comment = $DB->get_record('comments', array('id'=>$comment_param->commentid))) {
//            if ($comment->commentarea != 'digui_page') {
//                throw new comment_exception('invalidcommentarea');
//            }
//            if ($comment->contextid != $context->id) {
//                throw new comment_exception('invalidcontext');
//            }
//            if ($comment->itemid != $comment_param->itemid) {
//                throw new comment_exception('invalidcommentitemid');
//            }
//        } else {
//            throw new comment_exception('invalidcommentid');
//        }
//    }
//    return true;
//}

/**
 * Return a list of page types
 * @param string $pagetype current page type
 * @param stdClass $parentcontext Block's parent context
 * @param stdClass $currentcontext Current context of block
 */
//function digui_page_type_list($pagetype, $parentcontext, $currentcontext) {
//    $module_pagetype = array(
//        'mod-digui-*'=>get_string('page-mod-digui-x', 'digui'),
//        'mod-digui-view'=>get_string('page-mod-digui-view', 'digui'),
//        'mod-digui-comments'=>get_string('page-mod-digui-comments', 'digui'),
//        'mod-digui-history'=>get_string('page-mod-digui-history', 'digui'),
//        'mod-digui-map'=>get_string('page-mod-digui-map', 'digui')
//    );
//    return $module_pagetype;
//}