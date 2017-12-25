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
 * This file contains all necessary code to grade the students
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
$groupid = required_param('groupid', PARAM_INT);
//$groupid = required_param('groupid', PARAM_INT);
$grade = optional_param('grade', -1, PARAM_FLOAT);
$gradetoprint = optional_param('gradetoprint', -1, PARAM_FLOAT);

if (!$cm = get_coursemodule_from_instance('digui', $diguiid)) {
    print_error('invalidcoursemodule');
}
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

require_login($course, true, $cm);
//$PAGE->set_url($url);

$context = context_module::instance($cm->id);
require_capability('mod/digui:assigngrade', $context);

if (!$digui = digui_get_digui($cm->instance)) {
        print_error('incorrectdiguiid', 'digui');
    }
    
if (!$subdigui = digui_subdigui_get_subdigui_by_id($subdiguiid)) {
    print_error('incorrectsubdiguiid', 'digui');
}

// El usuario ha presionado el botón de guardar.
if ($grade > -1) {
    $gradeobj = new stdClass();
    $gradeobj->rawgrade = $grade;

    $params = array('itemname' => $digui->name);
//    $group = groups_get_group($groupid);
    $users = groups_get_members($groupid);
    foreach ($users as $user) {
        $gradeobj->userid   = $user->id;
        grade_update('mod/digui', $course->id, 'mod', 'digui', $digui->id, 0, $gradeobj, $params);
    }
    
    $params = array('diguiid' => $digui->id, 'subdiguiid' => $subdigui->id, 'selectedgroupid' => $groupid);
    $url = new moodle_url('/mod/digui/gradingviewbygroups.php', $params);
    redirect($url);
}

$diguipage = new page_digui_grading($digui, $subdigui, $cm);
$diguipage->print_header();

echo '<h3 style="margin-left:auto;margin-right:auto;">'.get_string('grade:grade', 'digui').'</h3>';

//$linkurl = new moodle_url('/mod/digui/gradingusergrade.php', array('diguiid' => $diguiid, 'subdiguiid' => $subdiguiid, 'userid' => $userid, 'groupid' => $groupid));
$linkurl = new moodle_url('/mod/digui/gradinggroupgrade.php', array('diguiid' => $diguiid, 'subdiguiid' => $subdiguiid, 'groupid' => $groupid));
$gradeobj = grade_get_grades($course->id, 'mod', 'digui', $digui->id, $userid);
echo '<form method="post" action="'.$linkurl.'">';

// Second column.
if ($gradetoprint > -1) {
    echo '<label for="id_grade">'.get_string('grade:scale', 'digui').'</label><input type="text" name="grade" value = "'.strval($gradetoprint).'" />';
}
else {
    echo '<label for="id_grade">'.get_string('grade:scale', 'digui').'</label><input type="text" name="grade"/>';
}
    

// Formulario para el botón de guardar.
echo '<input type="submit" value="    '.get_string('save', 'digui').'    " title= "'.get_string('save', 'digui').'">
</form>';

// echo $assign->view(optional_param('action', '', PARAM_TEXT));
//$assign->view_submission_page();
$diguipage->print_footer();