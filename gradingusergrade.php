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
$userid = required_param('userid', PARAM_INT);
$groupid = optional_param('groupid', -1, PARAM_INT);
$grade = optional_param('grade', -1, PARAM_FLOAT);
$returnurl = optional_param('returnurl', '', PARAM_TEXT);
$gradetoprint = optional_param('gradetoprint', -1, PARAM_FLOAT);

//global $PAGE;
//$PAGE->requires->yui_module('moodle-digui-module', 'M.digui.module.init');

if (!$cm = get_coursemodule_from_instance('digui', $diguiid)) {
    print_error('invalidcoursemodule');
}
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

require_login($course, true, $cm);

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
    $gradeobj->userid   = $userid;
    
    // In some circunstances, we must calculate the average of the new grade 
    // and the previous grade, if it exists. This circunstances are the following 
    // three: active user isn't grading himself, the self-assessment mode is 
    // disabled, and the peer-assessment is disabled.
    if ($userid != $subdigui->userid && $digui->selfassessment == 0 && 
            $digui->grademode == 0) {
        $gradeobj->rawgrade = $grade;
    }
    // Calculate the average.
    else {
        $previousgrade = grade_get_grades($course->id, 'mod', 'digui', $diguiid, $userid);
        // The previous grade exists.
        if (isset($previousgrade->items[0]->grades[$userid]) && isset($previousgrade->items[0]->grades[$userid]->grade)) {
            $grades = array();
            $grades[0] = $previousgrade->items[0]->grades[$userid]->grade;
            $grades[1] = $grade;
            $gradeobj->rawgrade = digui_math_average($grades);
        }
        else {
            $gradeobj->rawgrade = $grade;
        }
    }
    
    $params = array('itemname' => $digui->name);
    grade_update('mod/digui', $course->id, 'mod', 'digui', $digui->id, 0, $gradeobj, $params);

    if (strpos($returnurl, '/mod/digui/gradingviewbyusers.php') !== false) {
        $params = array('diguiid' => $digui->id, 'subdiguiid' => $subdigui->id);
        $url = new moodle_url('/mod/digui/gradingviewbyusers.php', $params);
    }
    else {
        $params = array('diguiid' => $digui->id, 'subdiguiid' => $subdigui->id, 'selectedgroupid' => $groupid);
        $url = new moodle_url('/mod/digui/gradingviewbygroups.php', $params);
        
    }
    redirect($url);
}

$diguipage = new page_digui_grading($digui, $subdigui, $cm);
$diguipage->print_header();

$personalinformation = digui_user_get_user_information($userid);
$spersonalinformation = '';
$spersonalinformation .= $personalinformation->firstname;
$spersonalinformation .= " ";
$spersonalinformation .= $personalinformation->lastname;
$spersonalinformation .= "(";
$spersonalinformation .= $personalinformation->email;
$spersonalinformation .= ")";

$urlparams = array('id' => $userid, 'course' => $course->id);
$url = new moodle_url('/user/view.php', $urlparams);
echo '<a href="'.$url.'">'.$spersonalinformation.'</a>';

echo '<br>';
echo '<br>';

echo '<h3 style="margin-left:auto;margin-right:auto;">'.get_string('grade:editionstatus', 'digui').'</h3>';
echo '<table style="width: 100%; background-color: #FFFFFF;">
<tbody><tr>
<td style="border-style: solid; border-color: #EEEEEE; border-width: 1px; width: 40%">'.get_string('grade:editionstatus', 'digui').'</td>';

if (digui_user_get_edition_status_for_user($diguiid, 0, $userid)) {
    $gradingstatus = get_string('grade:editing', 'digui');
}
else {
    $gradingstatus = get_string('grade:notediting', 'digui');
}

        
echo '<td style="border-style: solid; border-color: #EEEEEE; border-width: 1px;">'.$gradingstatus.'</td>';
echo '</tr>
<tr>
<td style="border-style: solid; border-color: #EEEEEE; border-width: 1px;">'.get_string('grade:status', 'digui').'</td>';
if (digui_grading_get_grade_status_for_user($diguiid, $userid)) {
    $editionstatus = get_string('grade:graded', 'digui');
}
else {
    $editionstatus = get_string('grade:notgraded', 'digui');
}
echo '<td style="border-style: solid; border-color: #EEEEEE; border-width: 1px;">'.$editionstatus.'</td>';
echo '</tr>

</tbody>
</table>';

echo '<br>';

echo '<h3 style="margin-left:auto;margin-right:auto;">'.get_string('grade:grade', 'digui').'</h3>';

//$linkurl = new moodle_url('/mod/digui/gradingusergrade.php', array('diguiid' => $diguiid, 'subdiguiid' => $subdiguiid, 'userid' => $userid, 'groupid' => $groupid));
$linkurl = new moodle_url('/mod/digui/gradingusergrade.php', array('diguiid' => $diguiid, 'subdiguiid' => $subdiguiid, 'userid' => $userid, 'returnurl' => $returnurl));
echo '<form method="post" action="'.$linkurl.'">';
if ($gradetoprint > -1) {
    echo '<label for="id_grade">'.get_string('grade:scale', 'digui').'</label><input type="text" name="grade" value = "'.strval($gradetoprint).'" />';
}
else {
    echo '<label for="id_grade">'.get_string('grade:scale', 'digui').'</label><input type="text" name="grade"/>';
}


// 
// Formulario para el botón de guardar.

echo '<input type="submit" value="    '.get_string('save', 'digui').'    " title= "'.get_string('save', 'digui').'">
</form>';

// echo $assign->view(optional_param('action', '', PARAM_TEXT));
//$assign->view_submission_page();
$diguipage->print_footer();