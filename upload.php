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
 * Manage files in digui
 *
 * @package   mod-digui-2.0
 * @copyright 2011 Dongsheng Cai <dongsheng@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('lib.php');
require_once('locallib.php');
require_once("$CFG->dirroot/mod/digui/filemanager_form.php");
require_once("$CFG->dirroot/repository/lib.php");
require_once("$CFG->dirroot/mod/digui/pagelib.php");
require_once('pagelib.php');

$diguiid = required_param('diguiid', PARAM_INT);
$groupid = optional_param('group', 0, PARAM_INT);
$uid = optional_param('uid', 0, PARAM_INT);

// Checking digui instance of that subdigui
if (!$digui = digui_get_digui($diguiid)) {
    print_error('incorrectdiguiid', 'digui');
}

// Checking course module instance
if (!$cm = get_coursemodule_from_instance("digui", $diguiid)) {
    print_error('invalidcoursemodule');
}

// Checking course instance
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

$context = context_module::instance($cm->id);

require_login($course, true, $cm);
require_capability('mod/digui:managefiles', $context);

$url = new moodle_url('/mod/digui/upload.php', array('diguiid'=>$diguiid, 'group'=>$groupid, 'uid'=>$uid));
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title(get_string('uploadpages', 'digui'));
$PAGE->set_heading($course->fullname);

$data = new stdClass();
$returnurl = new moodle_url('/course/view.php', array('id'=>$course->id));
$data->returnurl = $returnurl;
//$data->subdiguiid = $subdigui->id;
$data->subdiguiid = $diguiid;
$maxbytes = get_max_upload_file_size($CFG->maxbytes, $COURSE->maxbytes);
$options = array('subdirs'=>0, 'maxbytes'=>$maxbytes, 'maxfiles'=>1, 'accepted_types'=>'*', 'return_types'=>FILE_INTERNAL | FILE_REFERENCE);
//file_prepare_standard_filemanager($data, 'files', $options, $context, 'mod_digui', 'attachments', $subdigui->id);

$mform = new digui_filemanager_form(null, array('data'=>$data, 'options'=>$options));

if ($mform->is_cancelled()) {
    redirect($returnurl);
} else if ($formdata = $mform->get_data()) {

    $formdata = file_postupdate_standard_filemanager($formdata, 'files', $options, $context, 'mod_digui', 'attachments', $diguiid);
    
    // Cuando el módulo es colaborativo, se añade un usuario adicional: un usuario
    // común a todos los usuarios. A este usuario común será asignado las páginas
    // compartidas por todos los usuarios, o sea, las páginas que serán subrayadas
    // por todos los usuarios.
    if ($digui->diguimode == 'collaborative') {
        if (!$subdigui = digui_subdigui_get_subdigui_by_group($diguiid, 0, 0)) {
            $subdiguiid = digui_subdigui_add_subdigui($PAGE->activityrecord->id, 0, 0);
        }
    }
    // --------
    // Añadir páginas originales.
    $draftid = file_get_submitted_draft_itemid('files_filemanager');
    $draftinfo = file_get_draft_area_info($draftid);
    $n = $draftinfo['filecount'];
    if ($n > 0) {

        // Set the file format.
        $fs = get_file_storage();
        $dir = $fs->get_area_tree($context->id, 'mod_digui', 'attachments', $diguiid);
        foreach ($dir['files'] as $file) {
            $filename = $file->get_filename();
            $ext = pathinfo($filename, PATHINFO_EXTENSION);

            if ($ext == "htm" || $ext == "html") {
                $digui->format = "html";
            }
            else if ($ext == "txt") {
                $digui->format = "txt";
            }
            else if ($ext == "epub") {
                $digui->format = "epub";
            }
            else {
                print_error('invalidextensionfile', 'digui');
            }
        }
        
        // Set the rest of parameters.
        $before = microtime(true);
        $digui->numpags = digui_page_create_pages($cm, $diguiid);
        $after = microtime(true);
        $time = $after - $before; // 5'8 segundos

        $digui->instance = $digui->id;
        digui_update_instance($digui);
        }
        
    digui_color_generate_table_colors();
    // --------

    redirect($returnurl);
    }

echo $OUTPUT->header();
echo $OUTPUT->heading($digui->name);
echo $OUTPUT->box(format_module_intro('digui', $digui, $PAGE->cm->id), 'generalbox', 'intro');
echo $OUTPUT->box_start('generalbox');
$mform->display();
echo $OUTPUT->box_end();
echo $OUTPUT->footer();

// ------------------------------------------------------------------

