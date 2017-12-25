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
 * This file contains all necessary code to view a digui
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

$subdiguiid = required_param('subdiguiid', PARAM_INT);
$selectedsubdiguiid = required_param('selectedsubdiguiid', PARAM_INT);
$diguiid = required_param('diguiid', PARAM_INT);
$userid = required_param('uid', PARAM_INT);
$groupid = required_param('group', PARAM_INT);
$pagenum = optional_param('pagenum', 1, PARAM_INT);

if (!$cm = get_coursemodule_from_instance('digui', $diguiid)) {
    print_error('invalidcoursemodule');
}

if (!$digui = digui_get_digui($cm->instance)) {
        print_error('incorrectdiguiid', 'digui');
    }

global $PAGE;

$context = context_module::instance($cm->id);

// Obtener la página común a todos los usuarios.
//if (!$subdigui = digui_subdigui_get_subdigui_by_group($diguiid, 0, 0)) {
//        print_error('incorrectsubdiguiid', 'digui');
//    }

if (!$page = digui_page_get_page_by_pagenum($diguiid, $pagenum)) {
    print_error('incorrectpagenum', 'digui');
}

if (!$subdigui = digui_subdigui_get_subdigui_by_id($subdiguiid)) {
    print_error('incorrectsubdiguiid', 'digui');
}

$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

require_login($course, true, $cm);

$context = context_module::instance($cm->id);
require_capability('mod/digui:viewpage', $context);

// Delete all previous versions saved of the spans.
$pageversion = digui_version_get_current($diguiid, NULL, $pagenum);
digui_span_delete_by_version_greater_than($diguiid, NULL, $pagenum, $pageversion);
digui_span_delete_by_version_less_than($diguiid, NULL, $pagenum, $pageversion);
digui_version_reset($diguiid, NULL, $pagenum);

$diguipage = new page_digui_view($digui, $subdigui, $cm);
$diguipage->set_page($page);

$diguipage->print_header();

//$diguipage->print_content();

//$PAGE->requires->js_init_call('M.mod_digui.init', null, false, $jsmodule);
$PAGE->requires->yui_module('moodle-digui-module', 'M.digui.module.init');
//$PAGE->requires->yui_module('moodle-digui-module', 'M.digui.module.dragStartHandler');
        
digui_print_select_tag($digui, $subdigui, $selectedsubdiguiid);

echo '<br>';

echo '<div style="text-align: center;">';

if (!$selectedsubdigui = digui_subdigui_get_subdigui_by_id($selectedsubdiguiid)) {
    print_error('incorrectsubdiguiid '.$selectedsubdiguiid, 'digui');
}

// Update the time of all user's pages.
digui_version_save_last_time_modification($diguiid, null, $subdiguiid, 0);

echo '<div style="float: left; width: 15%">';

$subdiguis = digui_user_get_users_by_digui($diguiid);

// Print legend with user colors.
foreach ($subdiguis as $subdigui) {
    // Don't count users with userid equal to zero. This user doesn't
    // correspond to physycal user, it's only used for creating 
    // collaborative diguis.
    if ($subdigui->userid != 0) {
        $personalinformation = digui_user_get_user_information($subdigui->userid);
        $spersonalinformation = $personalinformation->username;
        $spersonalinformation .= " (";
        $spersonalinformation .= $personalinformation->firstname;
        $spersonalinformation .= " ";
        $spersonalinformation .= $personalinformation->lastname;
        $spersonalinformation .= ")";
        $color = digui_color_get_color_by_subdiguiid($subdigui->id);
        echo '<div style="height:4em;">';
        echo '<div style="float: left; width:20%;">';
        echo '<span style="float: left; background-color: #'.$color->backcolor.'">&nbsp;&nbsp;&nbsp;&nbsp;</span>';
        echo '</div>';
        echo '<div style="float: left; width:80%; text-align: left;">';
        echo $spersonalinformation;
        echo '</div>';
        echo '</div>';
    }

}

echo '<div style="height:4em;">';
        echo '<div style="float: left; width:20%;">';
        echo '<span style="float: left; background-color: #C0C0C0">&nbsp;&nbsp;&nbsp;&nbsp;</span>';
        echo '</div>';
        echo '<div style="float: left; width:80%; text-align: left;">';
        echo get_string('various', 'digui');
        echo '</div>';
        echo '</div>';
        
echo '</div>';

$pageversion = digui_version_get_current($diguiid, NULL, $pagenum);
    
// An user has been selected. So, show his tags only, and hide the rest.
if ($selectedsubdigui->userid != 0) {

    // Get spans of the selected user only.
    $spans = digui_span_get_by_subdiguiid($diguiid, $selectedsubdiguiid, $pagenum, $pageversion);
    $spans = digui_span_colorize($spans, $selectedsubdiguiid);
    $page->cachedcontent = digui_span_insert($page->cachedcontent, $spans);
    // $page->cachedcontent = digui_span_colorize_ex($page->cachedcontent, $selectedsubdiguiid);
}
// Common user has been selected. So, all tags must be shown.
else {
    $spans = digui_span_get_by_diguiid($diguiid, $pagenum, $pageversion);
    $page->cachedcontent = digui_span_insert($page->cachedcontent, $spans);
}

digui_print_div($page->cachedcontent, false);

echo '<br>';

echo '<div>';
digui_print_view_links('viewc', $digui->numpags, $pagenum, $diguiid, $subdiguiid, $selectedsubdiguiid, $groupid, $userid);
echo '</div>';

echo '<br>';

echo '<div>
<div style="background-color:#ffffff;  border-style: solid; border-color: #EEEEEE ; border-width: 1px; width: 66%; margin: 0 auto; padding: 5px; text-align: justify; font-size:14px;">
'.$page->notes.'
</div>
</div>';

echo '</div>';

// Formulario para la lista desplegable.
echo '<form id="formdata" method="post" action="viewc.php?diguiid='.$diguiid.'&subdiguiid='.$subdiguiid.'&group='.$groupid.'&uid='.$userid.'&pagenum='.$pagenum.'">
<input type="hidden" name="textdiv" id="textdivhidden" />
<input type="hidden" name="selectedsubdiguiid" id="selectedsubdiguiidhidden" />';
echo '</form>';

echo '<input type="hidden" id="subdiguiid" value="'.$subdiguiid.'"/>';

$personalinformation = digui_user_get_user_information($userid);
echo '<input type="hidden" id="username" value="'.$personalinformation->username.'"/>';
echo '<input type="hidden" id="firstname" value="'.$personalinformation->firstname.'"/>';
echo '<input type="hidden" id="lastname" value="'.$personalinformation->lastname.'"/>';

$diguipage->print_footer();