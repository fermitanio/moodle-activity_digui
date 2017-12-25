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
 * This file contains all necessary code to edit a digui
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

$diguiid = required_param('diguiid', PARAM_INT);
$subdiguiid = required_param('subdiguiid', PARAM_INT);
$groupid = required_param('group', PARAM_INT);
$userid = required_param('uid', PARAM_INT);
$pagenum = optional_param('pagenum', 1, PARAM_INT);
$wholetext = optional_param('wholetext', '', PARAM_TEXT);
$textarea = optional_param('textarea', '', PARAM_TEXT);
$action = optional_param('action', '', PARAM_TEXT);
$selectionlength = optional_param('selectionlength', -1, PARAM_INT);
$gtcharacters = optional_param('gtcharacters', '', PARAM_TEXT);
$ltcharacters = optional_param('ltcharacters', '', PARAM_TEXT);

if (!$cm = get_coursemodule_from_instance('digui', $diguiid)) {
    print_error('invalidcoursemodule');
}

if (!$digui = digui_get_digui($cm->instance)) {
        print_error('incorrectdiguiid', 'digui');
    }

$user_color = digui_color_get_color_by_subdiguiid($subdiguiid);

$context = context_module::instance($cm->id);
        
if (!$page = digui_page_get_page_by_pagenum($diguiid, $pagenum)) {
    print_error('incorrectpagenum', 'digui');
}

if (!$subdigui = digui_subdigui_get_subdigui_by_id($subdiguiid)) {
    print_error('incorrectsubdiguiid', 'digui');
}

global $DB;
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

require_login($course, true, $cm);

$context = context_module::instance($cm->id);
require_capability('mod/digui:editpage', $context);

// If the session has expired, we must delete all previous saved versions of the 
// spans.
$pageversion = digui_version_get_current($diguiid, $subdiguiid, $pagenum);
if (!empty($SESSION->has_timed_out) && $SESSION->has_timed_out == true) {
    digui_span_delete_by_version_greater_than($diguiid, $subdiguiid, $pagenum, $pageversion);
    digui_span_delete_by_version_less_than($diguiid, $subdiguiid, $pagenum, $pageversion);
    digui_version_reset($diguiid, $subdiguiid, $pagenum);
    $pageversion = 1;
}

// The first time the user see de page, we must delete all previous versions
// saved of the spans.
if (isset($_SERVER['HTTP_REFERER'])) {    
    if (!preg_match('/(.*)editi.php(.*)/s', $_SERVER['HTTP_REFERER'])) { 
        digui_span_delete_by_version_greater_than($diguiid, $subdiguiid, $pagenum, $pageversion);
        digui_span_delete_by_version_less_than($diguiid, $subdiguiid, $pagenum, $pageversion);
        digui_version_reset($diguiid, $subdiguiid, $pagenum);
        $pageversion = 1;
    }
//    echo $_SERVER['HTTP_REFERER'];
}

$diguipage = new page_digui_edit($digui, $subdigui, $cm);
$diguipage->set_pagenum($pagenum);
$diguipage->set_page($page);

$diguipage->print_header();

global $PAGE;
$PAGE->requires->yui_module('moodle-digui-module', 'M.digui.module.init');

// Previamente, el usuario ha presionado el botón "Guardar":
if ($action == "undo") {
    $pageversion--;
}
else if ($action == "redo") {
    $pageversion++;
}
// Previamente, el usuario ha subrayado:
else if ($action == "highlight" && $selectionlength > 0) {

    digui_span_delete_by_version_greater_than($diguiid, $subdiguiid, $pagenum, $pageversion);
    $pageversion = digui_span_new($diguiid, $digui->diguimode, $subdiguiid, $pagenum, $pageversion, $wholetext, $ltcharacters, $gtcharacters);
}
else if ($action == "save") {
    // Function not implemented yet. When the digui is individual, each user
    // has his own note in each page. So, probably we must modify the table 
    // digui_pages in the database.
    digui_page_insert_notes($diguiid, $pagenum, $textarea);
}

digui_version_save_number($diguiid, $subdiguiid, $pagenum, $pageversion);
$maxpageversion = digui_version_get_maximum($diguiid, $pagenum);

echo '<div style="text-align: center;">';
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

// $spans = digui_span_get_by_diguiid($diguiid, $pagenum, $pageversion);
$spans = digui_span_get_by_subdiguiid($diguiid, $subdiguiid, $pagenum, $pageversion);
// The Javascript interpeter modifies the original innertext of the control
// whose identifier is "textdiv". This occurs, for example, in the 
// sendSubrayado function of the helper.js file. So, this means that the
// text in the cachedcontet field of the mdl_digui_pages table, will not be 
// shown as it is, but as a different text, transformed by the Javascript 
// interpeter. This causes malfunction of the span tags insertion, so the
// highlight text will be shown incorrectly. To prevent this functioning,
// we must replace the text in the database cachedcontet field, with the 
// modified text proposed by the Javascript interpeter.
if ($wholetext != '') {
    // There is a function in the helper.js file, called sendSubrayado, 
    // which searched for "<" and ">" characters in the text of digui, and 
    // replaced them with words. Now, we must restore "<" and ">" 
    // characters.
    $wholetext = str_ireplace('openinganglebracket', '<', $wholetext);
    $wholetext = str_ireplace('closinganglebracket', '>', $wholetext);
    // Delete additional tags in the text.
    $wholetext = str_ireplace('&lt;openingtag&gt;', '', $wholetext);
    $wholetext = str_ireplace('&lt;closingtag&gt;', '', $wholetext);
    // Delete all span tags in the text.
    $wholetext = preg_replace( '~<span("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $wholetext);
    $wholetext = str_replace( '</span>', '', $wholetext);

    // Update the page text, with the text proposed by the Javascript 
    // interpeter.
    digui_page_create_page($wholetext, $pagenum, $diguiid);
    if (!$page = digui_page_get_page_by_pagenum($diguiid, $pagenum)) {
        print_error('incorrectpagenum', 'digui');
    }
}
$page->cachedcontent = digui_span_insert($page->cachedcontent, $spans);
digui_print_div($page->cachedcontent, true);

echo '<br>';

echo '<div>';
digui_print_edit_links($digui->numpags, $pagenum, $diguiid, $subdiguiid, $groupid, $userid, $digui->diguimode);
echo '</div>';

echo '<form id="formprueba" method="post" action="nopage.php"></form>';

echo '<br>';

// 
echo '<input type="hidden" name="maxpageversion" id="maxpageversion" value="'.$maxpageversion.'"/>';

// Form for highlighting.
echo '<form id="hform" method="post" onsubmit="sendSubrayado()" action="editi.php?diguiid='.$diguiid.'&subdiguiid='.$subdiguiid.'&group='.$groupid.'&uid='.$userid.'&pagenum='.$pagenum.'&action=highlight">
<input type="hidden" name="wholetext" id="wholetext" />
<input type="hidden" name="ltcharacters" id="ltcharacters" />
<input type="hidden" name="gtcharacters" id="gtcharacters" />
<input type="hidden" name="selectionlength" id="selectionlength" />';
echo '</form>';

// Forms for undo/redo buttons.
echo '<form id="undoform" method="post" style="display:inline;" action="editi.php?diguiid='.$diguiid.'&subdiguiid='.$subdiguiid.'&group='.$groupid.'&uid='.$userid.'&pagenum='.$pagenum.'&action=undo">
<input type="hidden" name="pageversion" id="upageversion" value="'.$pageversion.'"/>
<input type="button" value="'.get_string('undo', 'digui').'" onclick="undoHighlight_ex()">
</form>';

echo '<form id="redoform" method="post" style="display:inline;" action="editi.php?diguiid='.$diguiid.'&subdiguiid='.$subdiguiid.'&group='.$groupid.'&uid='.$userid.'&pagenum='.$pagenum.'&action=redo">
<input type="hidden" name="pageversion" id="rpageversion" value="'.$pageversion.'"/>
<input type="button" value="'.get_string('redo', 'digui').'" onclick="redoHighlight_ex()">
</form>';

echo '<br>';
echo '<br>';
echo '<br>';

// Forms for save button.
echo '<form id="sform" method="post" onsubmit="sendNotes()" action="editi.php?diguiid='.$diguiid.'&subdiguiid='.$subdiguiid.'&group='.$groupid.'&uid='.$userid.'&pagenum='.$pagenum.'&action=save">

<textarea oninput="setSaveButton(true)" onpropertychange="setSaveButton(true)" id="textarea" rows="4" style="background-color:#ffffff;  border-style: solid; border-color: #EEEEEE ; border-width: 1px; width: 66%; margin: 0 auto; padding: 5px; text-align: justify; font-size:14px;">
'.$page->notes.'</textarea>
<input type="hidden" name="textarea" id="textareahidden" />

<div style=" clear: both;">
<input type="submit" id="Guardar" value="    '.get_string('save', 'digui').'    " title= "'.get_string('save', 'digui').'" disabled="disabled">
</div>
</form>';

echo '</div>';

echo '<input type="hidden" id="userbackcolor" value="'.$user_color->backcolor.'"/>';
echo '<input type="hidden" id="userforecolor" value="'.$user_color->forecolor.'"/>';
echo '<input type="hidden" id="subdiguiid" value="'.$subdiguiid.'"/>';

$personalinformation = digui_user_get_user_information($userid);
echo '<input type="hidden" id="username" value="'.$personalinformation->username.'"/>';
echo '<input type="hidden" id="firstname" value="'.$personalinformation->firstname.'"/>';
echo '<input type="hidden" id="lastname" value="'.$personalinformation->lastname.'"/>';

$diguipage->print_footer();
