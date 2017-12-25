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
 * This file contains several classes uses to render the diferent pages
 * of the digui module
 *
 * @package mod-digui-1.0
 * @copyrigth 2016 Fernando Martin fermitanio@hotmail.com
 *
 * @author Fernando Martin
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once($CFG->dirroot . '/tag/lib.php');

/**
 * Class page_digui contains the common code between all pages
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class page_digui {

    /**
     * @var object Current subdigui
     */
    protected $digui;
    
    /**
     * @var object Current subdigui
     */
    protected $subdigui;

    /**
     * @var int Current page
     */
    protected $page;

    /**
     * @var string Current page page number
     */
    protected $pagenum;
    
    /**
     * @var int Current group ID
     */
    protected $gid;

    /**
     * @var object module context object
     */
    protected $modcontext;

    /**
     * @var int Current user ID
     */
    protected $uid;
    /**
     * @var array The tabs set used in digui module
     */
    protected $tabs = array('view' => 'view', 'edit' => 'edit', 'grading' => 'grading', 'export' => 'export');
    /**
     * @var array tabs options
     */
    protected $tabs_options = array();
    /**
     * @var object digui renderer
     */
    protected $diguioutput;
    /**
     * @var stdClass course module.
     */
    protected $cm;

    /**
     * page_digui constructor
     *
     * @param $digui. Current digui
     * @param $subdigui. Current subdigui.
     * @param $cm. Current course_module.
     */
    function __construct($digui, $subdigui, $cm) {
        global $PAGE, $CFG;
        $this->digui = $digui;
        $this->subdigui = $subdigui;
        $this->cm = $cm;
        $this->modcontext = context_module::instance($this->cm->id);

        // initialise digui renderer
        $this->diguioutput = $PAGE->get_renderer('mod_digui');
        $PAGE->set_cacheable(true);
        $PAGE->set_cm($cm);
        $PAGE->set_activity_record($digui);
    }

    /**
     * This method prints the top of the page.
     */
    function print_header() {
        global $OUTPUT, $PAGE, $CFG, $USER, $SESSION;

        $PAGE->set_heading(format_string($PAGE->course->fullname));

        $this->set_url();
        $this->create_navbar();
        $this->setup_tabs();

        echo $OUTPUT->header();
        $digui = $PAGE->activityrecord;
        echo $OUTPUT->heading($digui->name);

        echo $this->diguioutput->digui_info();

        // tabs are associated with pageid, so if page is empty, tabs should be disabled
        if (!empty($this->cm) && !empty($this->tabs)) {
            echo $this->diguioutput->tabs($this->cm->id, $this->tabs, $this->tabs_options, $digui, $this->subdigui);
        }
    }

    /**
     * Setup page tabs, if options is empty, will set up active tab automatically
     * @param array $options, tabs options
     */
    protected function setup_tabs($options = array()) {
        global $CFG, $PAGE;
        $groupmode = groups_get_activity_groupmode($this->cm);

        if (!has_capability('mod/digui:editpage', $PAGE->context)){
            unset($this->tabs['edit']);
        }

//        if ($groupmode and $groupmode == VISIBLEGROUPS) {
//            $currentgroup = groups_get_activity_group($this->cm);
//            $manage = has_capability('mod/digui:manage', $this->modcontext);
//            $edit = has_capability('mod/digui:editpage', $PAGE->context);
//            if (!$manage and !($edit and groups_is_member($currentgroup))) {
//                unset($this->tabs['edit']);
//            }
//        }

        if (empty($options)) {
            $this->tabs_options = array('activetab' => substr(get_class($this), 11));
        } else {
            $this->tabs_options = $options;
        }

    }

    /**
     * This method must be overwritten to print the page content.
     */
    function print_content() {
        throw new coding_exception('Page digui class does not implement method print_content()');
    }

    /**
     * Method to set the current page
     *
     * @param object $page Current page
     */
    function set_page($page) {
        global $PAGE;

        $this->page = $page;
        $PAGE->set_title($this->pagenum);
    }

    function set_pagenum($pagenum) {
        $this->pagenum = $pagenum;
    }
    
    function set_digui($digui) {
        $this->digui = $digui;
    }
    
    /**
     * Method to set current group id
     * @param int $gid Current group id
     */
    function set_gid($gid) {
        $this->gid = $gid;
    }

    /**
     * Method to set current user id
     * @param int $uid Current user id
     */
    function set_uid($uid) {
        $this->uid = $uid;
    }

    /**
     * Method to set the URL of the page.
     * This method must be overwritten by every type of page.
     */
    protected function set_url() {
        throw new coding_exception('Page digui class does not implement method set_url()');
    }

    /**
     * Protected method to create the common items of the navbar in every page type.
     */
    protected function create_navbar() {
        global $PAGE, $CFG;

        $PAGE->navbar->add(format_string($this->pagenum), $CFG->wwwroot . '/mod/digui/view.php?pageid=' . $this->page->id);
    }

    /**
     * This method print the footer of the page.
     */
    function print_footer() {
        global $OUTPUT;
        echo $OUTPUT->footer();
    }
}

/**
 * View a digui page
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_digui_view extends page_digui {

    function print_header() {
        global $OUTPUT;;

        parent::print_header();
        print '<noscript>' . $OUTPUT->box(get_string('javascriptdisabled', 'digui'), 'errorbox') . '</noscript>';
    }   

    function set_url() {
        global $PAGE, $CFG;
        $params = array();

        if (isset($this->cm->id)) {
            $params['id'] = $this->cm->id;
        } else if (!empty($this->page) and $this->page != null) {
            $params['pageid'] = $this->page->id;
        } else if (!empty($this->gid)) {
            $params['diguiid'] = $this->cm->instance;
            $params['group'] = $this->gid;
        } else if (!empty($this->pagenum)) {
            $params['$subdiguiid'] = $this->subdigui->id;
            $params['pagenum'] = $this->pagenum;
        } else {
            print_error(get_string('invalidparameters', 'digui'));
        }
        $PAGE->set_url(new moodle_url($CFG->wwwroot . '/mod/digui/view.php', $params));
    }

    protected function create_navbar() {
        global $PAGE;

        $PAGE->navbar->add(get_string('view', 'digui'));
    }
}

class page_digui_grading extends page_digui {

    function print_header() {
        global $OUTPUT;

        parent::print_header();
        print '<noscript>' . $OUTPUT->box(get_string('javascriptdisabled', 'digui'), 'errorbox') . '</noscript>';
    }   

    function set_url() {
        global $PAGE, $CFG;
        $params = array();

        if (isset($this->digui)) {
            //$params['id'] = $this->cm->id;
            $params['id'] = $this->digui->id;
            $params['sid'] = $this->subdigui->id;
            $params['groupid'] = $this->subdigui->groupid;
            $params['userid'] = $this->subdigui->userid;
        } else {
            print_error(get_string('invalidparameters', 'digui'));
        }
        $PAGE->set_url(new moodle_url($CFG->wwwroot . '/mod/digui/view.php', $params));
    }

    protected function create_navbar() {
        global $PAGE;

        $PAGE->navbar->add(get_string('grade:grades', 'digui'));
    }
}

/**
 * Digui page editing page
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_digui_edit extends page_digui {

    function print_header() {
        global $OUTPUT;
        
        parent::print_header();
        print '<noscript>' . $OUTPUT->box(get_string('javascriptdisabled', 'digui'), 'errorbox') . '</noscript>';
    }

    protected function set_url() {
        global $PAGE, $CFG;

       $params = array();

        if (isset($this->digui)) {
            //$params['id'] = $this->cm->id;
            $params['id'] = $this->digui->id;
            $params['sid'] = $this->subdigui->id;
            $params['groupid'] = $this->subdigui->groupid;
            $params['userid'] = $this->subdigui->userid;
        } else {
            print_error(get_string('invalidparameters', 'digui'));
        }
        
        if ($this->digui->diguimode == 'individual') {
            $PAGE->set_url($CFG->wwwroot . '/mod/digui/editi.php', $params);
        }
        else {
            $PAGE->set_url($CFG->wwwroot . '/mod/digui/editc.php', $params);
        }
    }

    protected function create_navbar() {
        global $PAGE;

        $PAGE->navbar->add(get_string('edit', 'digui'));
    }
}

/**
 * Digui page editing page
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_digui_export extends page_digui {

    function print_header() {
        global $OUTPUT;
        
        parent::print_header();
        print '<noscript>' . $OUTPUT->box(get_string('javascriptdisabled', 'digui'), 'errorbox') . '</noscript>';
    }

    protected function set_url() {
        global $PAGE, $CFG;

        $params = array();

        if (isset($this->digui)) {
            //$params['id'] = $this->cm->id;
            $params['id'] = $this->digui->id;
            $params['sid'] = $this->subdigui->id;
            $params['groupid'] = $this->subdigui->groupid;
            $params['userid'] = $this->subdigui->userid;
        } else {
            print_error(get_string('invalidparameters', 'digui'));
        }
        
        $PAGE->set_url($CFG->wwwroot . '/mod/digui/export.php', $params);
    }

    protected function create_navbar() {
        global $PAGE;

        $PAGE->navbar->add(get_string('export', 'digui'));
    }
}