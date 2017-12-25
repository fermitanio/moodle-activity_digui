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

require_once '../../config.php';
require_once($CFG->dirroot . '/mod/digui/locallib.php');
require_once($CFG->dirroot . '/mod/digui/pagelib.php');
require_once $CFG->dirroot . '/mod/digui/lib.php';

$diguiid = required_param('diguiid', PARAM_INT);
$subdiguiid = required_param('subdiguiid', PARAM_INT);
$selectedsubdiguiid = optional_param('selectedsubdiguiid', $subdiguiid, PARAM_INT);
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
require_capability('mod/digui:export', $context);

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

if (!empty($action)) {
   // @TODO: Offer option:
   // a. To export all text or only synthesis. 
   // b. To export all pages or only one chosen by user. 
   digui_files_download_file($digui, $subdiguiid, $selectedsubdiguiid);
   exit;
}

$diguipage = new page_digui_export($digui, $subdigui, $cm);
$diguipage->print_header();
// ---------------------------

$PAGE->requires->yui_module('moodle-digui-module', 'M.digui.module.init');
//$mform = new grade_export_form(null, array('includeseparator'=>true, 'publishing' => true));
//$mform = new grade_export_form(null, array('includeseparator'=>true, 'publishing' => true, 'diguiid' => $diguiid, 'subdiguiid' => $subdiguiid));

digui_print_select_tag($digui, $subdigui, $selectedsubdiguiid);

echo '<br>';

echo '<div style="text-align: center;">';

//echo '<form id="formdata" method="post" action="export.php?diguiid='.$diguiid.'&subdiguiid='.$subdiguiid.'&selectedsubdiguiid='.$selectedsubdiguiid.'&action=export">';
//echo '</form>';
//$mform->display();

// Formulario para la lista desplegable.
echo '<form id="formdata" method="post" action="export.php?diguiid='.$diguiid.'&subdiguiid='.$subdiguiid.'">
<input type="hidden" name="selectedsubdiguiid" id="selectedsubdiguiidhidden" />';
echo '</form>';

// Formulario para el botón de guardar.
echo '<form method="post" action="export.php?diguiid='.$diguiid.'&subdiguiid='.$subdiguiid.'&selectedsubdiguiid='.$selectedsubdiguiid.'&action=download">';
echo '<input type="submit" id="Guardar" value="    '.get_string('save', 'digui').'    " title= "'.get_string('save', 'digui').'">';
echo '</form>';

echo '</div>';

//echo $OUTPUT->footer();
$diguipage->print_footer();

// ----------------------------
