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
 * Define all the backup steps that will be used by the backup_digui_activity_task
 */

/**
 * Define the complete digui structure for backup, with file and id annotations
 */
class backup_digui_activity_structure_step extends backup_activity_structure_step {

    protected function define_structure() {

        // To know if we are including userinfo
        $userinfo = $this->get_setting_value('userinfo');

        // Define each element separated
        $digui = new backup_nested_element('digui', array('id'), array('course', 'name', 'intro', 'introformat', 'title', 'chapter', 'author1', 'author2', 'editor', 'publisher', 'edition', 'style', 'genre', 'synopsis', 'timecreated', 'timemodified', 'numpags', 'diguimode', 'grademode', 'selfassessment', 'format'));

        $subdiguis = new backup_nested_element('subdiguis');

        $subdigui = new backup_nested_element('subdigui', array('id'), array('groupid', 'userid'));

        $pages = new backup_nested_element('pages');

        $page = new backup_nested_element('page', array('id'), array('pagenum', 'cachedcontent', 'notes', 'timecreated', 'timemodified', 'timerendered'));

		$colors = new backup_nested_element('colors');

        $color = new backup_nested_element('color', array('id'), array('identifier', 'backcolor', 'forecolor'));
		
		$colors_assignments = new backup_nested_element('colors_assignments');

        $colors_assignment = new backup_nested_element('colors_assignment', array('id'), array('colorid'));
		
		$spans = new backup_nested_element('spans');

        $span = new backup_nested_element('span', array('id'), array('pageversion', 'subdiguiids', 'pagenum', 'start', 'end', 'colorid'));
		
		$last_user_modification = new backup_nested_element('last_user_modification');

        $last_user_modification_ = new backup_nested_element('last_user_modification_', array('id'), array('diguiid', 'pagenum', 'timemodified'));
		
        $page_version = new backup_nested_element('page_version');

        $page_version_ = new backup_nested_element('page_version_', array('id'), array('diguiid', 'pagenum', 'pageversion'));

        // Build the tree.
		$digui->add_child($subdiguis);
		$subdiguis->add_child($subdigui);
		
		$subdigui->add_child($colors_assignments);
		$colors_assignments->add_child($colors_assignment);
		
		$subdigui->add_child($last_user_modification);
		$last_user_modification->add_child($last_user_modification_);
		
		$subdigui->add_child($page_version);
		$page_version->add_child($page_version_);
		
		$digui->add_child($pages);
		$pages->add_child($page);
		
		$digui->add_child($spans);
		$spans->add_child($span);
		
		// $digui->add_child($page_version);
		// $page_version->add_child($page_version_);
		
		// $digui->add_child($last_user_modification);
		// $last_user_modification->add_child($last_user_modification_);
		
		$colors->add_child($color);
		// $color->add_child($colors_assignments);
		// $color->add_child($spans);
		
		/*
        $digui->add_child($subdiguis);
		$digui->add_child($pages);
		$digui->add_child($spans);
		$digui->add_child($page_version);
		$digui->add_child($last_user_modification);
        
		$subdiguis->add_child($subdigui);
        $subdigui->add_child($colors_assignments);
		$subdigui->add_child($last_user_modification);
		$subdigui->add_child($page_version);
				
        $pages->add_child($page);
		
		$colors->add_child($color);
		$color->add_child($colors_assignments);
		$color->add_child($spans);
		
		$colors_assignments->add_child($colors_assignment);
		
		$spans->add_child($span);
		
		$last_user_modification->add_child($last_user_modification_);
		
        $page_version->add_child($page_version_);
		*/
		
        // Define sources
        $digui->set_source_table('digui', array('id' => backup::VAR_ACTIVITYID));

        // All these source definitions only happen if we are including user info
        if ($userinfo) {
            $subdigui->set_source_sql('
                SELECT *
                  FROM {digui_subdiguis}
                 WHERE diguiid = ?', array(backup::VAR_PARENTID));

            $page->set_source_table('digui_pages', array('diguiid' => backup::VAR_PARENTID));
			
			$colors_assignment->set_source_table('digui_colors_assignments', array('subdiguiid' => backup::VAR_PARENTID));

			$span->set_source_table('digui_spans', array('diguiid' => backup::VAR_PARENTID));
			
			$last_user_modification_->set_source_table('digui_last_user_modification', array('diguiid' => backup::VAR_PARENTID));
			
            $page_version_->set_source_table('digui_page_version', array('diguiid' => backup::VAR_PARENTID));

			/*
            $tag->set_source_sql('SELECT t.id, t.name, t.rawname
                                    FROM {tag} t
                                    JOIN {tag_instance} ti ON ti.tagid = t.id
                                   WHERE ti.itemtype = ?
                                     AND ti.component = ?
                                     AND ti.itemid = ?', array(
                                         backup_helper::is_sqlparam('digui_pages'),
                                         backup_helper::is_sqlparam('mod_digui'),
                                         backup::VAR_PARENTID));
			*/
        }

        // Define id annotations
        $subdigui->annotate_ids('group', 'groupid');

        $subdigui->annotate_ids('user', 'userid');
		
        // Define file annotations
        $digui->annotate_files('mod_digui', 'intro', null); // This file area hasn't itemid
        $subdigui->annotate_files('mod_digui', 'attachments', 'id'); // This file area hasn't itemid

        // Return the root element (digui), wrapped into standard activity structure
        return $this->prepare_activity_structure($digui);
    }

}
