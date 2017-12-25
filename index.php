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
 * This page lists all the instances of digui in a particular course
 *
 * @package mod-digui-1.0
 * @copyrigth 2016 Fernando Martin fermitanio@hotmail.com
 *
 * @author Fernando Martin
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('lib.php');

$id = required_param('id', PARAM_INT); // course
$PAGE->set_url('/mod/digui/index.php', array('id' => $id));

if (!$course = $DB->get_record('course', array('id' => $id))) {
    print_error('invalidcourseid');
}

require_login($course, true);
$PAGE->set_pagelayout('incourse');
$context = context_course::instance($course->id);

add_to_log($course->id, 'digui', 'view', "index.php?id=".$id, "");

/// Get all required stringsdigui
$strdiguis = get_string("modulenameplural", "digui");
$strdigui = get_string("modulename", "digui");

/// Print the header
$PAGE->navbar->add($strdiguis, "index.php?id=$course->id");
$PAGE->set_title($strdiguis);
$PAGE->set_heading($course->fullname);
echo $OUTPUT->header();
echo $OUTPUT->heading($strdiguis);

/// Get all the appropriate data
if (!$diguis = get_all_instances_in_course("digui", $course)) {
    notice("There are no diguis", "../../course/view.php?id=$course->id");
    die;
}

$usesections = course_format_uses_sections($course->format);

/// Print the list of instances (your module will probably extend this)

$timenow = time();
$strname = get_string("name");
$table = new html_table();

if ($usesections) {
    $strsectionname = get_string('sectionname', 'format_' . $course->format);
    $table->head = array($strsectionname, $strname);
} else {
    $table->head = array($strname);
}

foreach ($diguis as $digui) {
    $linkcss = null;
    if (!$digui->visible) {
        $linkcss = array('class' => 'dimmed');
    }
    $link = html_writer::link(new moodle_url('/mod/digui/view.php', array('id' => $digui->coursemodule)), $digui->name, $linkcss);

    if ($usesections) {
        $table->data[] = array(get_section_name($course, $digui->section), $link);
    } else {
        $table->data[] = array($link);
    }
}

echo html_writer::table($table);

/// Finish the page
echo $OUTPUT->footer();
