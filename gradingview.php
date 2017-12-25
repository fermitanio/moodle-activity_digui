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

if (!$cm = get_coursemodule_from_instance('digui', $diguiid)) {
    print_error('invalidcoursemodule');
}

$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

require_login($course, true, $cm);
//$PAGE->set_url($url);

$context = context_module::instance($cm->id);
require_capability('mod/digui:viewgrade', $context);

if (!$subdigui = digui_subdigui_get_subdigui_by_id($subdiguiid)) {
    print_error('incorrectsubdiguiid', 'digui');
}

$allowedgroups = groups_get_all_groups($cm->course, 0, $cm->groupingid); // any group in grouping
$groupmode = groups_get_activity_groupmode($cm);

// Redirect.
if ($groupmode != NOGROUPS && $allowedgroups) {
    $currentgroup = groups_get_activity_group($cm);
    
    if (!$currentgroup) {
        $currentgroup = digui_groups_get_user_group($diguiid, $subdigui->userid);
    }

    $params = array('diguiid' => $diguiid, 'subdiguiid' => $subdiguiid, 'selectedgroupid' => $currentgroup);
    $url = new moodle_url('/mod/digui/gradingviewbygroups.php', $params);
//    $params = array('diguiid' => $diguiid, 'subdiguiid' => $subdiguiid);
//    $url = new moodle_url('/mod/digui/gradingviewbyusers.php', $params);
    redirect($url);    
}
else {
    $params = array('diguiid' => $diguiid, 'subdiguiid' => $subdiguiid);
    $url = new moodle_url('/mod/digui/gradingviewbyusers.php', $params);
    redirect($url);
}
