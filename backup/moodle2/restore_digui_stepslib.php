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
 * @package    mod_digui
 * @subpackage backup-moodle2
 * @copyright 2016 Fernando Martín <fermitanio@hotmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Define all the restore steps that will be used by the restore_digui_activity_task
 */

/**
 * Structure step to restore one digui activity
 */
class restore_digui_activity_structure_step extends restore_activity_structure_step {

    protected function define_structure() {

        $paths = array();
        $userinfo = $this->get_setting_value('userinfo');

        $paths[] = new restore_path_element('digui', '/activity/digui');
        if ($userinfo) {
            $paths[] = new restore_path_element('digui_subdigui', '/activity/digui/subdiguis/subdigui');
            $paths[] = new restore_path_element('digui_page', '/activity/digui/pages/page');
			$paths[] = new restore_path_element('digui_color', '/activity/digui/colors/color');
			$paths[] = new restore_path_element('digui_colors_assignment', '/activity/digui/subdiguis/subdigui/colors_assignments/colors_assignment');
			$paths[] = new restore_path_element('digui_span', '/activity/digui/subdiguis/subdigui/spans/span');
            $paths[] = new restore_path_element('digui_last_user_modif', '/activity/digui/subdiguis/subdigui/last_user_modification/last_user_modification_');
			$paths[] = new restore_path_element('digui_page_version', '/activity/digui/subdiguis/subdigui/page_version/page_version_');
        }

        // Return the paths wrapped into standard activity structure
        return $this->prepare_activity_structure($paths);
    }

    protected function process_digui($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();

        $data->timecreated = $this->apply_date_offset($data->timecreated);
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        // insert the digui record
        $newitemid = $DB->insert_record('digui', $data);
        // immediately after inserting "activity" record, call this
        $this->apply_activity_instance($newitemid);
    }

    protected function process_digui_subdigui($data) {
        global $DB;

        $data = (object) $data;
        $oldid = $data->id;
        $data->diguiid = $this->get_new_parentid('digui');

        // If the groupid is not equal to zero, get the mapping for the group.
        if ((int) $data->groupid !== 0) {
            $data->groupid = $this->get_mappingid('group', $data->groupid);
        }

        // If the userid is not equal to zero, get the mapping for the user.
        if ((int) $data->userid !== 0) {
            $data->userid = $this->get_mappingid('user', $data->userid);
        }

        // If these values are not equal to false then a mapping was successfully made.
        if ($data->groupid !== false && $data->userid !== false) {
            $newitemid = $DB->insert_record('digui_subdiguis', $data);
        } else {
            $newitemid = false;
        }

        $this->set_mapping('digui_subdigui', $oldid, $newitemid, true);
    }

    protected function process_digui_page($data) {
        global $DB;

        $data = (object) $data;
        $oldid = $data->id;
        $data->diguiid = $this->get_new_parentid('digui');
		// $data->diguiid = $this->get_mappingid('digui', $data->diguiid);
		$data->subdiguiid = $this->get_new_parentid('digui_subdigui');
        
        $data->timemodified = $this->apply_date_offset($data->timemodified);
        $data->timecreated = $this->apply_date_offset($data->timecreated);
        $data->timerendered = $this->apply_date_offset($data->timerendered);

        // Check that we were able to get a parentid for this page.
        if ($data->subdiguiid !== false) {
            $newitemid = $DB->insert_record('digui_pages', $data);
        } else {
            $newitemid = false;
        }

        $this->set_mapping('digui_page', $oldid, $newitemid, true);
    }

	protected function process_digui_color($data) {
		global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $newitemid = $DB->insert_record('digui_colors', $data);
        $this->set_mapping('digui_color', $oldid, $newitemid);
	}
	
	
	protected function process_digui_colors_assignment($data) {
		global $DB;

        $data = (object)$data;
        $oldid = $data->id;

		// $data->subdiguiid = $this->get_mappingid('digui_subdigui', $data->subdiguiid);
		$data->subdiguiid = $this->get_new_parentid('digui_subdigui');
		// $data->colorid = $this->get_new_parentid('digui_color');
		$data->colorid = $this->get_mappingid('digui_color', $data->colorid);
		
        $newitemid = $DB->insert_record('digui_colors_assignments', $data);
        $this->set_mapping('digui_color', $oldid, $newitemid);
	}
	
	protected function process_digui_span($data) {
		global $DB;

        $data = (object)$data;
        $oldid = $data->id;

		$data->diguiid = $this->get_mappingid('digui', $data->diguiid);
		$data->subdiguiid = $this->get_mappingid('digui_subdigui', $data->subdiguiid);
		$data->colorid = $this->get_mappingid('digui_color', $data->colorid);
		
        $newitemid = $DB->insert_record('digui_spans', $data);
        $this->set_mapping('digui_span', $oldid, $newitemid);
	}
	
	protected function process_digui_last_user_modif($data) {
		global $DB;

        $data = (object)$data;
        $oldid = $data->id;

		$data->diguiid = $this->get_mappingid('digui', $data->diguiid);
		// $data->subdiguiid = $this->get_mappingid('digui_subdigui', $data->subdiguiid);
		$data->subdiguiid = $this->get_new_parentid('digui_subdigui');
		
        $newitemid = $DB->insert_record('digui_last_user_modification', $data);
        $this->set_mapping('digui_last_user_modif', $oldid, $newitemid);
	}
	
    protected function process_digui_page_version($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
		
		$data->diguiid = $this->get_mappingid('digui', $data->diguiid);
        // $data->subdiguiid = $this->get_mappingid('digui_subdigui', $data->subdiguiid);
		$data->subdiguiid = $this->get_new_parentid('digui_subdigui');

        $newitemid = $DB->insert_record('digui_page_version', $data);
        $this->set_mapping('digui_version', $oldid, $newitemid);
    }
	
    protected function after_execute() {
        // Add digui related files, no need to match by itemname (just internally handled context)
        $this->add_related_files('mod_digui', 'intro', null);
        $this->add_related_files('mod_digui', 'attachments', 'digui_subdigui');
    }
}
