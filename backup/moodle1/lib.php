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
 * Provides support for the conversion of moodle1 backup to the moodle2 format
 * Based off of a template @ http://docs.moodle.org/dev/Backup_1.9_conversion_for_developers
 *
 * @package    mod_digui
 * @copyright  2016 Fernando Martín <fermitanio@hotmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Digui conversion handler
 */
class moodle1_mod_digui_handler extends moodle1_mod_handler {

    /** @var string initial content for creating first page from the optional 1.9 digui file */
    protected $initialcontent;

    /** @var string */
    protected $initialcontentfilename;

    /** @var bool initial content page already exists */
    protected $needinitpage = false;

    /** @var array a data element transfer buffer, can be used for transfer of data between xml path levels. */
    protected $databuf = array();

    /** @var moodle1_file_manager */
    protected $fileman = null;

    /** @var int cmid */
    protected $moduleid = null;

    /**
     * Declare the paths in moodle.xml we are able to convert
     *
     * The method returns list of {@link convert_path} instances.
     * For each path returned, the corresponding conversion method must be
     * defined.
     *
     * Note that the path /MOODLE_BACKUP/COURSE/MODULES/MOD/DIGUI does not
     * actually exist in the file. The last element with the module name was
     * appended by the moodle1_converter class.
     *
     * @return array of {@link convert_path} instances
     */
    public function get_paths() {
        return array(
            new convert_path(
                'digui', '/MOODLE_BACKUP/COURSE/MODULES/MOD/DIGUI',
                array(
                    'newfields' => array(
                        'format' => 'html', //1.9 migrations default to html
                        'timecreated' => time(), //2.x time of creation since theres no 1.9 time of creation
                    ),
                    'renamefields' => array(
                        'summary' => 'intro',
                        /* 'format' => 'introformat', */
                        'wtype' => 'diguimode'
                    ),
                    'dropfields' => array(
                        'pagename', 'scaleid', 'ediguiprinttitle', 'htmlmode', 'ediguiacceptbinary', 'disablecamelcase',
                        'setpageflags', 'strippages', 'removepages', 'revertchanges'
                    )
                )
            ),
            new convert_path(
                'digui_entries', '/MOODLE_BACKUP/COURSE/MODULES/MOD/DIGUI/ENTRIES',
                array(
                    'newfields' => array(
                        'synonyms' => '0',
                        'links' => 'collaborative',
                    ),
                    'dropfields' => array(
                        'pagename' ,'timemodified'
                    )
                )
            ),
            new convert_path(
                'digui_entry', '/MOODLE_BACKUP/COURSE/MODULES/MOD/DIGUI/ENTRIES/ENTRY'
            ),
            new convert_path(
                'digui_pages', '/MOODLE_BACKUP/COURSE/MODULES/MOD/DIGUI/ENTRIES/ENTRY/PAGES'
            ),
            new convert_path(
                'digui_entry_page', '/MOODLE_BACKUP/COURSE/MODULES/MOD/DIGUI/ENTRIES/ENTRY/PAGES/PAGE',
                array(
                    'newfields' => array(
                        'cachedcontent' => '**reparse needed**',
                        'timerendered' => '0',
                        'readonly' => '0',
                        'tags' => ''
                    ),
                    'renamefields' => array(
                        'pagename' => 'title',
                        'created' => 'timecreated',
                        'lastmodified' => 'timemodified',
                        'hits' => 'pageviews'
                    ),
                    'dropfields' => array(
                        'version', 'flags', 'author', 'refs', //refs will be reparsed during rendering
                        'meta'
                    )
                )
            )
        );
    }

    /**
     * This is executed every time we have one /MOODLE_BACKUP/COURSE/MODULES/MOD/DIGUI
     * data available
     */
    public function process_digui($data) {
        global $CFG;    // We need to check a config setting.

        if (!empty($data['initialcontent'])) {
            //convert file in <INITIALCONTENT>filename</INITIALCONTENT> into a subdigui page if no entry created.
            $temppath = $this->converter->get_tempdir_path();
            $this->initialcontent = file_get_contents($temppath.'/course_files/'.$data['initialcontent']);
            $this->initialcontentfilename = $data['initialcontent'];
            $this->needinitpage = true;
        }
        unset($data['initialcontent']);
        if ($data['diguimode'] !== 'group') {
            $data['diguimode'] = 'individual';
            //@todo need to create extra subdiguis due to individual diguimode?
            //this would then need to reference the users in the course that is being restored.(some parent class API needed)
        } else {
            $data['diguimode'] = 'collaborative';
        }

        if (empty($data['name'])) {
            $data['name'] = 'Digui';
        }
        // get the course module id and context id
        $instanceid     = $data['id'];
        $cminfo         = $this->get_cminfo($instanceid);
        $this->moduleid = $cminfo['id'];
        $contextid      = $this->converter->get_contextid(CONTEXT_MODULE, $this->moduleid);

        // get a fresh new file manager for this instance
        $this->fileman = $this->converter->get_file_manager($contextid, 'mod_digui');

        // convert course files embedded into the intro
        $this->fileman->filearea = 'intro';
        $this->fileman->itemid   = 0;
        $data['intro'] = moodle1_converter::migrate_referenced_files($data['intro'], $this->fileman);

        // convert the introformat if necessary
        if ($CFG->texteditors !== 'textarea') {
            $data['intro'] = text_to_html($data['intro'], false, false, true);
            $data['introformat'] = FORMAT_HTML;
        }

        // we now have all information needed to start writing into the file
        $this->open_xml_writer("activities/digui_{$this->moduleid}/digui.xml");
        $this->xmlwriter->begin_tag('activity', array('id' => $instanceid, 'moduleid' => $this->moduleid,
            'modulename' => 'digui', 'contextid' => $contextid));
        $this->xmlwriter->begin_tag('digui', array('id' => $instanceid));

        foreach ($data as $field => $value) {
            if ($field <> 'id') {
                $this->xmlwriter->full_tag($field, $value);
            }
        }

        return $data;
    }

    public function on_digui_entries_start() {
        $this->xmlwriter->begin_tag('subdiguis');
        $this->needinitpage = false; //backup has entries, so the initial_content file has been stored as a page in 1.9.
    }

    public function on_digui_entries_end() {
        $this->xmlwriter->end_tag('subdiguis');
    }

    public function process_digui_entry($data) {
        $this->xmlwriter->begin_tag('subdigui', array('id' => $data['id']));
        unset($data['id']);

        unset($data['pagename']);
        unset($data['timemodified']);

        foreach ($data as $field => $value) {
            $this->xmlwriter->full_tag($field, $value);
        }
    }

    public function on_digui_entry_end() {
        $this->xmlwriter->end_tag('subdigui');
    }

    public function on_digui_pages_start() {
        $this->xmlwriter->begin_tag('pages');
    }

    public function on_digui_pages_end() {
        $this->xmlwriter->end_tag('pages');
    }

    public function process_digui_entry_page($data) {
        // assimilate data to create later in extra virtual path page/versions/version/
        $this->databuf['id'] = $this->converter->get_nextid();
        $this->databuf['content'] = $data['content'];
        unset($data['content']);
        $this->databuf['contentformat'] = 'html';
        $this->databuf['version'] = 0;
        $this->databuf['timecreated'] = $data['timecreated']; //do not unset, is reused
        $this->databuf['userid'] = $data['userid']; //do not unset, is reused

        // process page data (user data and also the one that is from <initialcontent>
        $this->xmlwriter->begin_tag('page', array('id' => $data['id']));
        unset($data['id']); // we already write it as attribute, do not repeat it as child element
        foreach ($data as $field => $value) {
            $this->xmlwriter->full_tag($field, $value);
        }

        // process page content as a version.
        $this->xmlwriter->begin_tag('versions');
        $this->write_xml('version', $this->databuf, array('/version/id')); //version id from get_nextid()
        $this->xmlwriter->end_tag('versions');
    }
    public function on_digui_entry_page_end() {
        $this->xmlwriter->end_tag('page');
    }

    /**
     * This is executed when we reach the closing </MOD> tag of our 'digui' path
     */
    public function on_digui_end() {
        global $USER;

        //check if the initial content needs to be created (and if a page is already there for it)
        if ($this->initialcontentfilename && $this->needinitpage) {
            //contruct (synthetic - not for cooking) a full path for creating entries/entry/pages/page
            $data_entry = array(
                'id'        => $this->converter->get_nextid(), //creating the first entry
                'groupid'   => 0,
                'userid'    => 0,
                'synonyms'  => '',
                'links'     => ''
            );
            $data_page = array(
                'id'            => $this->converter->get_nextid(), //just creating the first page in the digui
                'title'         => $this->initialcontentfilename,
                'content'       => $this->initialcontent,
                'userid'        => $USER->id,
                'timecreated'   => time(),
                'timemodified'  => 0,
                'pageviews'     => 0,
                'cachedcontent' => '**reparse needed**',
                'timerendered'  => 0,
                'readonly'      => 0,
                'tags'          => ''
            );
            //create xml with constructed page data (from initial_content file).
            $this->on_digui_entries_start();
            $this->process_digui_entry($data_entry);
            $this->on_digui_pages_start();
            $this->process_digui_entry_page($data_page);
            $this->on_digui_entry_page_end();
            $this->on_digui_pages_end();
            $this->on_digui_entry_end();
            $this->on_digui_entries_end();
        }

        //close digui.xml
        $this->xmlwriter->end_tag('digui');
        $this->xmlwriter->end_tag('activity');
        $this->close_xml_writer();

        // write inforef.xml
        $this->open_xml_writer("activities/digui_{$this->moduleid}/inforef.xml");
        $this->xmlwriter->begin_tag('inforef');
        $this->xmlwriter->begin_tag('fileref');
        foreach ($this->fileman->get_fileids() as $fileid) {
            $this->write_xml('file', array('id' => $fileid));
        }
        $this->xmlwriter->end_tag('fileref');
        $this->xmlwriter->end_tag('inforef');
        $this->close_xml_writer();
    }
}
