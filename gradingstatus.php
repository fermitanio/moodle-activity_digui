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
 * This file contains all necessary code to see a grader report
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

if (!$cm = get_coursemodule_from_instance('digui', $diguiid)) {
    print_error('invalidcoursemodule');
}
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

require_login($course, true, $cm);

$context = context_module::instance($cm->id);
require_capability('mod/digui:viewgrade', $context);

if (!$digui = digui_get_digui($cm->instance)) {
        print_error('incorrectdiguiid', 'digui');
    }
    
if (!$subdigui = digui_subdigui_get_subdigui_by_id($subdiguiid)) {
    print_error('incorrectsubdiguiid', 'digui');
}

// Delete all previous versions saved of the spans.
// TODO: this loop can be replaced by a faster code.
$numofpages = digui_page_get_number_of_pages($diguiid);
for ($i = 1, $j = $numofpages + 1; $i < $j; $i++) {
    if ($digui->diguimode == 'collaborative') {
        $pageversion = digui_version_get_current($diguiid, NULL, $i);
        digui_span_delete_by_version_greater_than($diguiid, NULL, $i, $pageversion);
        digui_span_delete_by_version_less_than($diguiid, NULL, $i, $pageversion);
        digui_version_reset($diguiid, NULL, $i);
    }
    else {
        $pageversion = digui_version_get_current($diguiid, $subdiguiid, $i);
        digui_span_delete_by_version_greater_than($diguiid, $subdiguiid, $i, $pageversion);
        digui_span_delete_by_version_less_than($diguiid, $subdiguiid, $i, $pageversion);
        digui_version_reset($diguiid, $subdiguiid, $i);    
    }
}

// Update the time of all user's pages.
digui_version_save_last_time_modification($diguiid, null, $subdiguiid, 0);

$diguipage = new page_digui_grading($digui, $subdigui, $cm);
$diguipage->print_header();

$usersparticipating = digui_count_users_by_digui($digui->id);
$usersediting = digui_count_users_editing($digui->id);
$usersneedgrading = digui_count_users_editing_need_grading($digui->id);

echo '<h3 style="margin-left:auto;margin-right:auto;">'.get_string('grade:report', 'digui').'</h3>';
echo '<table style="width: 100%; background-color: #FFFFFF;">
<tbody><tr>
<td style="border-style: solid; border-color: #EEEEEE; border-width: 1px; width: 40%">'.get_string('grade:participants', 'digui').'</td>
<td style="border-style: solid; border-color: #EEEEEE; border-width: 1px;">'.$usersparticipating.'</td>
</tr>
<tr>
<td style="border-style: solid; border-color: #EEEEEE; border-width: 1px;">'.get_string('grade:editing', 'digui').'</td>
<td style="border-style: solid; border-color: #EEEEEE; border-width: 1px;">'.$usersediting.'</td>
</tr>
<tr>
<td style="border-style: solid; border-color: #EEEEEE; border-width: 1px;">'.get_string('grade:needsgrading', 'digui').'</td>
<td style="border-style: solid; border-color: #EEEEEE; border-width: 1px;">'.$usersneedgrading.'</td>
</tr>
</tbody>
</table>';

$link = new moodle_url('/mod/digui/gradingview.php', array('diguiid' => $diguiid, 'subdiguiid' => $subdiguiid));
echo '<div style="text-align: center;"><a style="margin-left:auto;margin-right:auto"; href="'.$link.'">'.get_string('grade:viewgrading', 'digui').'</a></div>';

echo '<br>';
echo '<br>';

echo '<h3 style="margin-left:auto;margin-right:auto;">'.get_string('grade:status', 'digui').'</h3>';
echo '<table style="width: 100%; background-color: #FFFFFF;">
<tbody><tr>
<td style="border-style: solid; border-color: #EEEEEE; border-width: 1px; width: 40%">'.get_string('grade:editionstatus', 'digui').'</td>';

if (digui_user_get_edition_status_for_user($digui->id, $subdigui->groupid, $subdigui->userid)) {
    $gradingstatus = get_string('grade:editing', 'digui');
}
else {
    $gradingstatus = get_string('grade:notediting', 'digui');
}

        
echo '<td style="border-style: solid; border-color: #EEEEEE; border-width: 1px;">'.$gradingstatus.'</td>';
echo '</tr>
<tr>
<td style="border-style: solid; border-color: #EEEEEE; border-width: 1px;">'.get_string('grade:status', 'digui').'</td>';
if (digui_grading_get_grade_status_for_user($digui->id, $subdigui->userid)) {
    $editionstatus = get_string('grade:graded', 'digui');
}
else {
    $editionstatus = get_string('grade:notgraded', 'digui'); 
}
echo '<td style="border-style: solid; border-color: #EEEEEE; border-width: 1px;">'.$editionstatus.'</td>';
echo '</tr>

</tbody>
</table>';

$diguipage->print_footer();