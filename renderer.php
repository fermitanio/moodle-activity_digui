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
 * Moodle Digui 2.0 Renderer
 *
 * @package   mod-digui
 * @copyright 2010 Dongsheng Cai <dongsheng@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class mod_digui_renderer extends plugin_renderer_base {
    
  public function digui_info() {
        global $PAGE;
        return $this->output->box(format_module_intro('digui', $this->page->activityrecord, $PAGE->cm->id), 'generalbox', 'intro');
    }

    public function tabs($cmid, $tabitems, $options, $digui, $subdigui) {
        $tabs = array();
        $context = context_module::instance($cmid);

        $selected = $options['activetab'];

        // make specific tab linked even it is active
        if (!empty($options['linkedwhenactive'])) {
            $linked = $options['linkedwhenactive'];
        } else {
            $linked = '';
        }

        if (!empty($options['inactivetabs'])) {
            $inactive = $options['inactivetabs'];
        } else {
            $inactive = array();
        }

        foreach ($tabitems as $tab) {
            $taburl = $tab;
            
            if ($tab == 'edit' && !has_capability('mod/digui:editpage', $context)) {
                continue;
            }

            if (($tab == 'view' || $tab == 'map' || $tab == 'history') && !has_capability('mod/digui:viewpage', $context)) {
                continue;
            }

            if ($tab == 'edit' && !has_capability('mod/digui:editpage', $context)) {
                continue;
            }

            // *****
            $usersneedgrading = digui_count_users_editing_need_grading($digui->id);
            if ($tab == 'grading' && $digui->selfassessment == 0 && 
                    $digui->grademode == 0 && $usersneedgrading > 0 && 
                    !has_capability('mod/digui:viewgrade', $context)) {
                continue;
            }
            
            if ($tab == 'view') {
                $value = get_string($tab, 'digui');
                if ($digui->diguimode == 'individual') {
                    $suffix = 'i';
                }
                else {
                    $suffix = 'c';
                }
                
                 $params = array('diguiid' => $digui->id, 
                'subdiguiid' => $subdigui->id, 
                'selectedsubdiguiid' => $subdigui->id, 
                'group' => $subdigui->groupid, 
                'uid' => $subdigui->userid, 
                'pagenum' => 1);
            }
            else if ($tab == 'edit') {
                $value = get_string($tab, 'digui');
                if ($digui->diguimode == 'individual') {
                    $suffix = 'i';
                }
                else {
                    $suffix = 'c';
                }
                    
                $params = array('diguiid' => $digui->id, 
                'subdiguiid' => $subdigui->id, 
                'group' => $subdigui->groupid, 
                'uid' => $subdigui->userid, 
                'pagenum' => 1);
            }
            else if ($tab == 'grading') {
                $value = get_string('grade:grades', 'digui');
                $taburl = 'gradingstatus';
                $suffix = '';
                $params = array('diguiid' => $digui->id, 
                    'subdiguiid' => $subdigui->id, 
                    'pagenum' => 1);
            }
            else if ($tab == 'export') {
                $value = get_string($tab, 'digui');
                $taburl = 'export';
                $suffix = '';
                $params = array('diguiid' => $digui->id, 
                    'subdiguiid' => $subdigui->id, 
                    'selectedsubdiguiid' => $subdigui->id, 
                    'pagenum' => 1);
            }
            
            // $link = new moodle_url('/mod/digui/file'. $tab. '.php', array('pageid' => $pageid));
            $link = new moodle_url('/mod/digui/'. $taburl. $suffix. '.php', $params);
            if ($linked == $tab) {
                $tabs[] = new tabobject($tab, $link, $value, '', true);
            } else {
                $tabs[] = new tabobject($tab, $link, $value);
            }
        }

		global $CFG;
		
		if ($CFG->version > 2012120311) { // Moodle 2.4.11
			return $this->tabtree($tabs, $selected, $inactive);
		}
		else {
			return print_tabs(array($tabs), $selected, $inactive, null, true);
		}
    }
}