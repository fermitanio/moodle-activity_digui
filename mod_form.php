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
 * This file defines de main digui configuration form
 *
 * @package mod-digui-1.0
 * @copyrigth 2016 Fernando Martin fermitanio@hotmail.com
 *
 * @author Fernando Martin
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once ($CFG->dirroot. '/course/moodleform_mod.php');
require_once ($CFG->dirroot. '/mod/digui/locallib.php');
// require_once($CFG->dirroot . '/course/modlib.php');
require_once($CFG->dirroot . '/lib/datalib.php');

class mod_digui_mod_form extends moodleform_mod {

    protected function definition() {
        global $CFG;
		
		$mform = $this->_form;
        $required = get_string('required');
		
        //
        // Adding the "general" fieldset, where all the common settings are shown.
        //
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field.
        $mform->addElement('text', 'name', get_string('diguiname', 'digui'), array('size' => '64'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', $required, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        
        // Adding the optional "intro" and "introformat" pair of fields
        // $this->add_intro_editor(true, get_string('diguiintro', 'digui'));
		if ($CFG->version > 2013051409) { // Moodle 2.5.9
			// For newer versions of Moodle.
			$this->standard_intro_elements();
		}
		else {
			// For older versions of Moodle.
			$this->add_intro_editor(true, get_string('diguiintro', 'digui'));
		}
//        $mform->addElement('textarea', 'introduction', get_string('diguiintro', 'digui'), array('rows'=>"5", 'cols'=>"64"));
//        $mform->setType('introduction', PARAM_TEXT);
////        $mform->addRule('introduction', $required, 'required', null, 'client');
//        $mform->addRule('introduction', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        $diguimodeoptions = array ('collaborative' => get_string('diguimodecollaborative', 'digui'), 'individual' => get_string('diguimodeindividual', 'digui'));
        // Don't allow changes to the digui type once it is set.
        $diguitype_attr = array();
        if (!empty($this->_instance)) {
            $diguitype_attr['disabled'] = 'disabled';
        }
        $mform->addElement('select', 'diguimode', get_string('diguimode', 'digui'), $diguimodeoptions, $diguitype_attr);
        $mform->addHelpButton('diguimode', 'diguimode', 'digui');

        $attr = array('size' => '20');
        if (!empty($this->_instance)) {
            $attr['disabled'] = 'disabled';
        }

        //
        // Adding the "about" fieldset, where all the book settings are shown.
        //
        $mform->addElement('header', 'about', get_string('about', 'digui'));
        
        // Adding the "title" field.
        $mform->addElement('text', 'title', get_string('title', 'digui'), array('size' => '64'));
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', $required, 'required', null, 'client');
        $mform->addRule('title', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        // Adding the "chapter" field.
        $mform->addElement('text', 'chapter', get_string('chapter', 'digui'), array('size' => '64'));
        $mform->setType('chapter', PARAM_TEXT);
        $mform->addRule('chapter', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        
        // Adding the "author1" field.
        $mform->addElement('text', 'author1', get_string('author1', 'digui'), array('size' => '64'));
        $mform->setType('author1', PARAM_TEXT);
        $mform->addRule('author1', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        // Adding the "author2" field.
        $mform->addElement('text', 'author2', get_string('author2', 'digui'), array('size' => '64'));
        $mform->setType('author2', PARAM_TEXT);
        $mform->addRule('author2', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        // Adding the "editor" field.
        $mform->addElement('text', 'editor', get_string('editor', 'digui'), array('size' => '64'));
        $mform->setType('editor', PARAM_TEXT);
        $mform->addRule('editor', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        // Adding the "publidher" field.
        $mform->addElement('text', 'publisher', get_string('publisher', 'digui'), array('size' => '64'));
        $mform->setType('publisher', PARAM_TEXT);
        $mform->addRule('publisher', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        // Adding the "edition" field.
        $mform->addElement('text', 'edition', get_string('edition', 'digui'), array('size' => '10'));
        $mform->setType('edition', PARAM_TEXT);
        $mform->addRule('edition', get_string('maximumchars', '', 4), 'maxlength', 4, 'client');

        // Adding the "form" field.
        $formoptions = array (
            'empty' => get_string('form:empty', 'digui'), 
            'anthology' => get_string('form:anthology', 'digui'), 
            'biography' => get_string('form:biography', 'digui'), 
            'novel' => get_string('form:novel', 'digui'), 
            'reference' => get_string('form:reference', 'digui'), 
            'textbook' => get_string('form:textbook', 'digui'));
        $mform->addElement('select', 'style', get_string('style', 'digui'), $formoptions);
//        $mform->setType('style', PARAM_TEXT);
        
        // Adding the "genre" field.
        $genreoptions = array (
            'empty' => get_string('genre:empty', 'digui'), 
            'action' => get_string('genre:action', 'digui'), 
            'adventure' => get_string('genre:adventure', 'digui'), 
            'drama' => get_string('genre:drama', 'digui'), 
            'comedy' => get_string('genre:comedy', 'digui'), 
            'historical' => get_string('genre:historical', 'digui'),
            'horror' => get_string('genre:horror', 'digui'),
            'sfiction' => get_string('genre:sfiction', 'digui'));
        $mform->addElement('select', 'genre', get_string('genre', 'digui'), $genreoptions);
//        $mform->setType('genre', PARAM_TEXT);
        
        // Adding the "synopsis" field.
        $mform->addElement('textarea', 'synopsis', get_string('synopsis', 'digui'), array('rows'=>"5", 'cols'=>"64"));
        $mform->setType('synopsis', PARAM_TEXT);
        $mform->addRule('synopsis', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        //
        // Adding the "evaluation" fieldset, where assessment settings are shown.
        //
        $mform->addElement('header', 'grading', get_string('grade:grading', 'digui'));
        
        // Adding the "grade mode" field.
        $formoptions = array (
            '0' => get_string('grade:traditional', 'digui'), 
            '1' => get_string('grade:peer', 'digui'));
        $mform->addElement('select', 'grademode', get_string('grade:type', 'digui'), $formoptions);
        
        // Adding the "self-assessment" field.
        $mform->addElement('checkbox', 'selfassessment', get_string('grade:self', 'digui'));
        
        //
        // Add standard elements, common to all modules.
        //
        $this->standard_coursemodule_elements();

        // Add standard buttons, common to all modules.
        $this->add_action_buttons();
    }
}
