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
 * This contains functions and classes that will be used by scripts in digui module
 *
 * @package mod-digui-1.0
 * @copyrigth 2016 Fernando Martin fermitanio@hotmail.com
 *
 * @author Fernando Martin
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->libdir . '/filelib.php');
require_once($CFG->dirroot . '/mod/digui/lib.php');
require_once($CFG->dirroot . '/mod/digui/be_rmdir.php');
require_once($CFG->dirroot . '/mod/digui/fm_htmlentities_ex.php');
require_once($CFG->dirroot . '/mod/digui/fm_web_scraping.php');

define('digui_REFRESH_CACHE_TIME', 30); // @TODO: To be deleted.
define('FORMAT_CREOLE', '37');
define('FORMAT_Ndigui', '38');
define('NO_VALID_RATE', '-999');
define('IMPROVEMENT', '+');
define('EQUAL', '=');
define('WORST', '-');

define('LOCK_TIMEOUT', 30);

/**
 * Delete the key-th item from array. 
 * @param array $array, array.
 * @param int $key, index.
 * @return array
 */
function digui_array_remove_by_index($array, $key) {
    
    $arraycopy = array();
    for ($i = 0, $j = 0, $k = count($array); $i < $k; $i++) {
        if ($i != $key) {
            $arraycopy[$j] = $array[$i];
            $j++;
        }
    }
    
    return $arraycopy;
}

/**
 * Replaces the first ocurrence of a value, with another value. 
 * @param array $array, array which contains the value to replace.
 * @param object $value, value to be found and be replaced.
 * @return object $replacement, new value to be assigned.
 */
function digui_array_replace_value($array, $value, $replacement)
{
    if (($key = array_search($array, $value)) !== FALSE) {
        $array[$key] = $replacement;
    }
    
    return $array;
}

/**
 * Makes a copy of a given array. 
 * @param array $array, array.
 * @param int $key, index.
 * @return array
 */
function digui_array_copy($array) {
    
    $arraycopy = array();
    foreach ($array as $element) {
        $arraycopy[count($arraycopy)] = $element;
    }

    return $arraycopy;
}

/**
 * Assign a color to new user. A color is used for highlighting digui pages.
 * @param int $diguiid, id of digui instance object
 * @param int $subdiguiid, id of subdigui for which a color will be assigned to. 
 */
function digui_color_assign_highlight_color($digui, $subdiguiid) {
    global $DB;
    
    // Get the group mode being used by digui. 
    $cm = get_coursemodule_from_instance('digui', $digui->id);
    // When two users are in separate groups, we can assign the same color to 
    // them. So, in this case we must count users belonging to the current
    // user's group only.
    if (groups_get_activity_groupmode($cm) == SEPARATEGROUPS) {
        // Get the data about the user, to get the user's group.
        if (!$subdigui = digui_subdigui_get_subdigui_by_id($subdiguiid)) {
            print_error('incorrectsubdiguiid', 'digui');
        }
        // Get the amount of users which are highlighting the digui, and belong
        // to the user's group.
        $assignments = $DB->count_records('digui_subdiguis', array('diguiid' => $digui->id, 'groupid' => $subdigui->groupid));
    }
    // In others cases, count the total amount of users.
    else {
        // Get the amount of users which are highlighting the digui.
        $assignments = $DB->count_records('digui_subdiguis', array('diguiid' => $digui->id));
    }
    
    // All users except the common user (whose user identifier is equal to 0), 
    // can highlight a digui. Therefore, we must discard the common user when
    // we assign a color to a user.
    if ($digui->diguimode == 'collaborative') {
        $assignments--;
    }
            
    $color = $DB->get_record('digui_colors', array('identifier' => $assignments));
    $DB->insert_record('digui_colors_assignments', array('subdiguiid' => $subdiguiid, 'backcolor' => $color->backcolor, 'forecolor' => $color->forecolor));
}

/**
 * If not exists, creates a database table that contains colors which users use 
 * to highlight pages.
 * When a user (user 1) highlights a text already marked by another user (user 
 * 2), the resulting color of the mark, will be the color assigned to the user
 * 1, plus the color assigned to the user 2. In this cases, the resulting color
 * will be a gray tone. 
 * Colors with values equal or higher than 12, are gray tones. A gray tone
 * means that a text has been highlighted at least by two different users.
 * When a user highlights the same text two or more times, the resulting color 
 * will be the same color assigned to that user, a non gray tone.
 */
function digui_color_generate_table_colors() {
    global $DB;
    
    if (!$DB->count_records('digui_colors')) {
        
        //
        // Colors.
        //

        $color = new StdClass();
        $color->identifier = 1;
        $color->backcolor = '006600';
        $color->forecolor = 'ffffff';
        $DB->insert_record('digui_colors', $color); // Verde oscuro, blanco.

        $color->identifier = 2;
        $color->backcolor = '00FF66';
        $color->forecolor = '000000';
        $DB->insert_record('digui_colors', $color); // Verde claro, negro.
        
        $color->identifier = 3;
        $color->backcolor = '3333CC';
        $color->forecolor = 'ffffff';
        $DB->insert_record('digui_colors', $color); // Azul oscuro, blanco.
        
        $color->identifier = 4;
        $color->backcolor = '33FFFF';
        $color->forecolor = '000000';
        $DB->insert_record('digui_colors', $color); // Azul claro, negro.
        
        $color->identifier = 5;
        $color->backcolor = 'FF6600';
        $color->forecolor = '000000';
        $DB->insert_record('digui_colors', $color); // Naranja, negro.
        
        $color->identifier = 6;
        $color->backcolor = 'CCFF00';
        $color->forecolor = '000000';
        $DB->insert_record('digui_colors', $color); // Amarillo, negro.
        
        $color->identifier = 7;
        $color->backcolor = 'FF0000';
        $color->forecolor = '000000';
        $DB->insert_record('digui_colors', $color); // Rojo, negro.
        
        $color->identifier = 8;
        $color->backcolor = '990000';
        $color->forecolor = 'ffffff';
        $DB->insert_record('digui_colors', $color); // Rojo oscuro, blanco.
        
        $color->identifier = 9;
        $color->backcolor = 'FF99FF';
        $color->forecolor = '000000';
        $DB->insert_record('digui_colors', $color); // Rosa claro, negro.
        
        $color->identifier = 10;
        $color->backcolor = 'FF00CC';
        $color->forecolor = 'ffffff';
        $DB->insert_record('digui_colors', $color); // Rosa oscuro, blanco.
        
        $color->identifier = 11;
        $color->backcolor = '000000';
        $color->forecolor = 'ffffff';
        $DB->insert_record('digui_colors', $color); // Negro, blanco.
        
        //
        // Gray scale colors
        //
        
        $color->identifier = 12;
        $color->backcolor = 'E0E0E0';
        $color->forecolor = '000000';
        $DB->insert_record('digui_colors', $color);

        $color->identifier = 13;
        $color->backcolor = 'D0D0D0';
        $color->forecolor = '000000';
        $DB->insert_record('digui_colors', $color);
        
        $color->identifier = 14;
        $color->backcolor = 'C0C0C0';
        $color->forecolor = '000000';
        $DB->insert_record('digui_colors', $color);
        
        $color->identifier = 15;
        $color->backcolor = 'B0B0B0';
        $color->forecolor = '000000';
        $DB->insert_record('digui_colors', $color);
        
        $color->identifier = 16;
        $color->backcolor = 'A0A0A0';
        $color->forecolor = '909090';
        $DB->insert_record('digui_colors', $color);
        
        $color->identifier = 17;
        $color->backcolor = '909090';
        $color->forecolor = 'ffffff';
        $DB->insert_record('digui_colors', $color);
        
        $color->identifier = 18;
        $color->backcolor = '808080';
        $color->forecolor = 'ffffff';
        $DB->insert_record('digui_colors', $color);
        
        $color->identifier = 19;
        $color->backcolor = '707070';
        $color->forecolor = 'ffffff';
        $DB->insert_record('digui_colors', $color);
        
        $color->identifier = 20;
        $color->backcolor = '606060';
        $color->forecolor = 'ffffff';
        $DB->insert_record('digui_colors', $color);
        
        $color->identifier = 21;
        $color->backcolor = '505050';
        $color->forecolor = 'ffffff';
        $DB->insert_record('digui_colors', $color);
        
        $color->identifier = 22;
        $color->backcolor = '404040';
        $color->forecolor = 'ffffff';
        $DB->insert_record('digui_colors', $color);
        
        $color->identifier = 23;
        $color->backcolor = '303030';
        $color->forecolor = 'ffffff';
        $DB->insert_record('digui_colors', $color);
    }
}

/**
 * When a user (user 1) highlights a text already marked by another user (user 
 * 2), the resulting color of the mark, will be the color assigned to the user
 * 1, plus the color assigned to the user 2. In this cases, the resulting color
 * will be a gray tone. 
 * When a user highlights the same text two or more times, the resulting color 
 * will be the same color assigned to that user.
 * @param string $text, text that contains the tags.
 * @param string $color, color of the html tags.
 * @return string, a highlighted text.
 */
function digui_color_get_color_sum($colorid, $subdiguiid, $subdiguiids) {
    
    $subdiguilist = explode(',', $subdiguiids);
    $i = count($subdiguilist);
    if ($i == 0) {
        return -1;
    }
    
    // Colors with values equal or higher than 12, are gray tones. A gray tone
    // means that a text has been highlighted at least by two different users.
    if ($colorid < 12) {
        // It's the first time the user highlights the text. So, the text is
        // highlighted by two different users.
        if (!digui_span_is_included($subdiguiid, $subdiguiids)) {
            return 12;
        }
        // It's not the first time the user highlights the text. So, return
        // the same color, because the text is highlighted by the same user.
        else {
            return $colorid;
        }
    }
    else {
        // It's the first time the user highlights the text. So, the text is
        // highlighted by two different users.
        if (!digui_span_is_included($subdiguiid, $subdiguiids)) {
            return $colorid + 1;
        }
        // It's not the first time the user highlights the text. So, return
        // the same color, because the text is highlighted by the same user.
        else {
            return $colorid;
        }
    }
}

/**
 * Get a color by its identifier.
 * @param int $subdiguiid, id of subdigui.
 * @return object
 */
function digui_color_get_color_by_id($colorid) {
    global $DB;
    
    $color = $DB->get_record('digui_colors', array('identifier' => $colorid));
    return $color;
}

/**
 * Get a color assigned to a user.
 * @param int $subdiguiid, id of subdigui.
 * @return object
 */
function digui_color_get_color_by_subdiguiid($subdiguiid) {
    global $DB;
    
    $colorassignment = $DB->get_record('digui_colors_assignments', array('subdiguiid' => $subdiguiid));
    $color = $DB->get_record('digui_colors', array('backcolor' => $colorassignment->backcolor, 'forecolor' => $colorassignment->forecolor));
    return $color;
}

/**
 * Get a color assigned to a user.
 * @param int $subdiguiid, id of subdigui.
 * @return object
 */
function digui_color_get_color_by_subdiguiids($subdiguiids) {
    global $DB;
    
    $subdiguilist = explode(',', $subdiguiids);
    $i = count($subdiguilist);
    if ($i == 0) {
        return null;
    }
    
    // When the users list has one element.
    if ($i == 1) {
        $color = digui_color_get_color_by_subdiguiid($subdiguilist[0]);
        return $color;
    }
    else {
        // When the users list has more than one element. Minimun value of $i
        // is equal to 2.
        $color = digui_color_get_color_by_id(10 + $i);
        return $color;
    }    
}

/**
* Load a count of users with access to the digui.
*
* @param int $diguiid, id of digui instance object
* @return int number of users with access to the digui.
*/
function digui_count_users_by_digui($diguiid) {
    global $DB;
    
    $cm = get_coursemodule_from_instance('digui', $diguiid);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $context = context_course::instance($course->id);
    
    $users = array();
    $roleid = array(1,2,3,4,5,6,7,8);
    foreach ($roleid as $id) {
        $users = $users + get_role_users($id, $context, true);
    }

    $i = 0;
    foreach ($users as $user) {
        $i++;
    }

//    $allowedgroups = groups_get_all_groups($cm->course, 0, $cm->groupingid); // any group in grouping
//    $i = 0;
//    if ($allowedgroups) {
//        foreach ($allowedgroups as $group) {
//            // Show only groups of current user, unless current user be
//            // teacher or administratror. In this case, list all groups.
//            $users = groups_get_members($group->id);
//            foreach ($users as $user) {
//                $i++;
//            }
//        }
//    }
    return $i;
}

/**
* Load a count of users editing a digui.
*
* @param int $diguiid, id of digui instance object
* @return int number of users editing a digui.
*/
function digui_count_users_editing($diguiid) {
    global $DB;
    
    $cm = get_coursemodule_from_instance('digui', $diguiid);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $context = context_course::instance($course->id);

    // Get a list of users that have edited or are currently editing the
    // digui. Remember that each digui has a user associated.
    $subdiguis = digui_user_get_users_by_digui($diguiid);
    $i = 0;
    foreach ($subdiguis as $subdigui) {
        // Don't count users with userid equal to zero. This user doesn't
        // correspond to physycal user, it's only used for creating 
        // collaborative diguis.
        if ($subdigui->userid != 0 && is_enrolled($context, $subdigui->userid)) {
            $i++;
        }
    }
    return $i;
}

/**
* Load a count of users editing the module that require grading.
*
* @param int $diguiid, id of digui instance object.
* @return int number of users that require grading.
*/
function digui_count_users_editing_need_grading($diguiid) {
    global $DB;
    
    $cm = get_coursemodule_from_instance('digui', $diguiid);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $context = context_course::instance($course->id);
    
    $users = array();
    $roleid = array(1,2,3,4,5,6,7,8);
    foreach ($roleid as $id) {
        $users = $users + get_role_users($id, $context, true);
    }

   
//    $allowedgroups = groups_get_all_groups($cm->course, 0, $cm->groupingid); // any group in grouping
    $i = 0;
//    if ($allowedgroups) {
//        foreach ($allowedgroups as $group) {
//            // Show only groups of current user, unless current user be
//            // teacher or administratror. In this case, list all groups.
//            $users = groups_get_members($group->id);
            foreach ($users as $user) {
                
//                if ((!$subdigui = digui_subdigui_get_subdigui_by_group($diguiid, $group->id, $user->id)) &&
//                    (!$subdigui = digui_subdigui_get_subdigui_by_group($diguiid, 0, $user->id))) {
                if ((!$subdigui = digui_subdigui_get_subdigui_by_user($diguiid, $user->id))) {
                
                    $params = array('iteminstance' => $diguiid, 'userid' => $user->id);
                    $sql = "SELECT *
                    FROM {grade_grades} g
                    JOIN {grade_items} i ON g.itemid = i.id
                    WHERE g.userid =:userid AND i.iteminstance =:iteminstance AND i.itemtype='mod' AND i.itemmodule='digui'";
                    
                    if (!$DB->get_record_sql($sql, $params)) {
                        $i++;
                    }
                }
                else {
                    $params = array('subdiguiid' => $subdigui->id);

                    $sql = "SELECT MAX(timemodified) "
                    . "FROM {digui_last_user_modification} "
                    . "WHERE subdiguiid =:subdiguiid";

                    $timemodifiedpage = $DB->get_field_sql($sql, $params);

                    $params = array('iteminstance' => $diguiid, 'userid' => $subdigui->userid);
                    $sql = "SELECT g.timemodified
                    FROM {grade_grades} g
                    JOIN {grade_items} i ON g.itemid = i.id
                    WHERE g.userid =:userid AND i.iteminstance =:iteminstance AND i.itemtype='mod' AND i.itemmodule='digui'";

                    $timemodifiedgrading = $DB->get_field_sql($sql, $params);

                    if ($timemodifiedpage >= $timemodifiedgrading) {
                        $i++;
                    }
                }
            }
//        }
//    }
    return $i;
}

/**
 * Converts a epub file into an unique html file.
 * 
 * @param string $filepath, path where is the epub file.
 * @param string $destinationpath, path where the function puts the resulting 
 * html file.
 * @param bool $inlinecss, indicates whether the css style of the epub file,
 * will be included in the html file. 
 * @return string, the complete path of the generated html file.
 */
function digui_files_convert_epub_to_html($filepath, $destinationpath, $inlinecss) {
    
    // Extract the epub file to an associative array.
    $files = digui_files_zip_extract($filepath, $destinationpath, false, true);
    
    //
    // Get the correct order in which we must read the html files from the
    // associative array.
    //

    // Search for the opf file (Open Packaging format) in the files array.
    foreach ($files as $key => $value) {
        // The current file has a opf extension.
        if (preg_match('/(.*)\.(opf)/si', $key) == 1) {    
            // Read the opf file (Open Packaging format), and get the html files 
            // linked by it.
            $htmlfilepaths = digui_files_get_html_files_from_opf_file($value);
            break;
        }
    }

    // Extract the file name from its path. For example, from
    // 'dir\file.html', would extract the string 'file.html'.
    $htmlfilenames = Array();
    if (count($htmlfilepaths) > 0) {
        for($i = 0, $j = count($htmlfilepaths); $i < $j; $i++) {
            $filename = preg_replace( '~^(.*)/~si', '', $htmlfilepaths[$i]);
            $htmlfilenames[] = $filename;
        }
    }

    //
    // Merge the html files in one html file.
    // 

    // Get the inner text inside the body tag of the first html file. Regular 
    // expressions are faster than simplexml library, and simplexml library is 
    // faster than DOM, under some circumstances.
    $buffer = $files[$htmlfilenames[0]];
    // preg_replace function replaces $ characters followed by a number,
    // for example $0001. This is a undesired operation, because text
    // files could contain strings with these characters. To avoid this
    // operation, we must escape those $n backreferences. For example,
    // a "$0.95" string will be replaced with "\$0.95" string.
    // See more in http://us1.php.net/manual/en/function.preg-replace.php#103985
    $buffer = preg_replace('/\$(\d)/', '\\\$$1', $buffer);
    preg_match('~<body("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</body>~si', $buffer, $match);
    $htmlbodytext = $match[count($match) - 1];
    
    // Append a <body> tag.
    $htmlbodytext = '<body>' . $htmlbodytext;
    
    // Merge the html files in one html file.
    for($i = 1, $j = count($htmlfilenames); $i < $j; $i++) {
        // Get the body tag of each html file. Regular expressions are faster
        // than simplexml library, and simplexml library is faster than DOM.
        $buffer = $files[$htmlfilenames[$i]];
    
        // preg_replace function replaces $ characters followed by a number,
        // for example $0001. This is a undesired operation, because text
        // files could contain strings with these characters. To avoid this
        // operation, we must escape those $n backreferences. For example,
        // a "$0.95" string will be replaced with "\$0.95" string.
        $buffer = preg_replace('/\$(\d)/', '\\\$$1', $buffer);

        // Get the inner text inside the body tag of the file.
        preg_match('~<body("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</body>~si', $buffer, $match);
        
        // Append the inner text to the body tag of the first html file.
        $htmlbodytext .= $match[count($match) - 1];
    }

    // Add the end of the body tag (</body>).
    $htmlbodytext .= '</body>';

    // Replace the body tag of the first html document, with the html body tag
    // created.
    $buffer = $files[$htmlfilenames[0]];
    $mergedhtmldocument = preg_replace('~<body("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</body>~si', $htmlbodytext, $buffer);

    //
    // Replace non ASCII characteres with its html entities. If we don't do 
    // this, the html document could be shown incorrectly.
    //
    
    $mergedhtmldocument = fm_htmlentities_ex($mergedhtmldocument);
    
    //
    // Convert external style sheets to inline style sheets. In others words,
    // merge the merged html file with its css files.
    //
    
    if ($inlinecss) {
        $cssfilepaths = array();
        foreach ($files as $key => $value) {
            // The current file has a htm, html or xhtml extension.
            if (preg_match('/(.*)\.(htm|html|xhtml)/', strtolower($key)) == 1) {
                $cssfilepathsaux = digui_files_get_css_linked_by_html($value);
                $cssfilepaths = array_merge($cssfilepaths, $cssfilepathsaux);
            }
        }

        // Delete duplicates from array.
        $cssfilepaths = array_unique($cssfilepaths);

        // Get and concatenate the css text of the css files.
        $csstext = '';
        for($i = 0, $j = count($cssfilepaths); $i < $j; $i++) {
            $cssfilename = preg_replace( '~^(.*)/~', '', $cssfilepaths[$i]);
            // $csstext .= file_get_contents($destinationpath . DIRECTORY_SEPARATOR . $cssfilename);
            $csstext .= $files[$cssfilename];
        }

        // Add a style tag to the head tag of the html document.    
        $htmlheadtext = '<head>';
        $htmlheadtext .= '<style>';
        $htmlheadtext .= $csstext;
        $htmlheadtext .= '</style>';

        $htmlheadtext .= '</head>';

        // Replace the head section of the html document, with the new html section.
        $mergedhtmldocument = preg_replace("~<head[^>]*>(.*?)</head>~si", $htmlheadtext, $mergedhtmldocument);
    }
    
    //
    // Exit.
    //
    
    file_put_contents($destinationpath . DIRECTORY_SEPARATOR . 'merged-'. $htmlfilenames[0], $mergedhtmldocument);
    
    return $destinationpath . DIRECTORY_SEPARATOR . 'merged-'. $htmlfilenames[0];
}

/**
* Show a dialog window to save the pages of digui.
* @param object $digui, digui instance object.
* @param int $subdiguiid, id of subdigui.
* @param int $selectedsubdiguiid, id of subdigui selected by user on the page.
* @return bool
*/
function digui_files_download_file($digui, $subdiguiid, $selectedsubdiguiid) {
    digui_files_send_header($digui, $subdiguiid);
    digui_print_txt($digui, $subdiguiid, $selectedsubdiguiid);
}

/**
 * Get the external css style sheets linked by a html file.
 * 
 * @param string $path, path of the html document.
 * @return array, array which contains the paths of the css style sheets.
 *
 */
function digui_files_get_css_linked_by_html($buffer) {
    
    $cssfilepaths = array();
    
    // Load the HTML to a DOM object.
    $doc = new DOMDocument();
    $doc->loadHTML($buffer);

    // Get all links tags from the html document.
    $links = $doc->getElementsByTagName("link");
    if ($links->length > 0) { 
        foreach ($links as $link) {
            // Retrieve the value of the attribute href.
            if ($link->getAttribute('rel') == 'stylesheet') {
                $cssfilepaths[] = $link->getAttribute('href');
            }
        }
    }

    return $cssfilepaths;
}

/**
 * Read an opf file (Open Packaging format), and get the html files linked by 
 * it. An opf file is "an XML Document" and "should use the .opf extension". 
 * See http://www.idpf.org/epub/20/spec/OPF_2.0.1_draft.htm#Section1.2
 * 
 * In a opf file, the manifest tag, "is the required second child of package, 
 * following metadata". 
 * See http://www.idpf.org/epub/30/spec/epub30-publications.html#sec-manifest-elem
 *
 * Within the manifest tag, the item element represents a Publication Resource". 
 * It has three required attributes: id, href and media-type. 
 * See http://www.idpf.org/epub/30/spec/epub30-publications.html#sec-manifest-elem.
 *
 * ePub 2 uses a deprecated and superseed file called ncx file. This file is
 * linked in the opf file. "The NCX must be included as an item in the manifest 
 * (with media-type of application/x-dtbncx+xml)". 
 * See http://www.idpf.org/epub/20/spec/OPF_2.0.1_draft.htm#Section2.4.1.
 *  
 * ePub 3 uses a file called Navigation document. "The EPUB Navigation Document 
 * is identified in the Package Document manifest through the nav property". 
 * See http://www.idpf.org/epub/30/spec/epub30-contentdocs.html#sec-xhtml-nav.
 * 
 * @param string $path, path of the opf document.
 * @return array, array which contains the paths of the html documents.
 */
function digui_files_get_html_files_from_opf_file($buffer) {
    /*
    // It's difficult reading xml files with namespaces, using the SimpleXml 
    // library. So, the first step is remove the namespaces from the xml file.
    $xmlObject = simplexml_load_string($buffer);
    $namespaces = $xmlObject->getDocNamespaces();
    $namespaces = array_keys($namespaces);

    foreach ($namespaces as $namespace) {
        $buffer = str_replace($namespace . ':', '', $buffer);
    }

    // The namespaces have been removed.
    $htmlfilepaths = array();
    $xmlObject = simplexml_load_string($buffer);
    foreach($xmlObject->children() as $item) {

        $tag = $item->getName();
        // Manifest tag, "is the required second child of package, following 
        // metadata". See http://www.idpf.org/epub/30/spec/epub30-publications.html#sec-manifest-elem
        if (strcasecmp($tag, "manifest") == 0) {
            foreach($item->children() as $item2) {
                if ($item2['media-type'] == 'application/xhtml+xml') {
                    $htmlfilepaths[] = strtolower($item2['href']);
                }
            }
        }
    }
    
    return $htmlfilepaths;
     */
    
    // It's difficult reading xml files with namespaces, using the SimpleXml 
    // library or regular expressions. So, the first step is remove the 
    // namespaces from the xml file.
    $xml = simplexml_load_string($buffer);
    $namespaces = $xml->getDocNamespaces();
    $namespaces = array_keys($namespaces);

    // Get the text between the <manifest> tag. Manifest tag, "is the required 
    // second child of package, following metadata". 
    // See http://www.idpf.org/epub/30/spec/epub30-publications.html#sec-manifest-elem
    preg_match('~manifest>(.*?)</~si', $buffer, $match);
    $buffer = $match[count($match) - 1];
    foreach ($namespaces as $namespace) {
        $buffer = str_replace($namespace . ':', '', $buffer);
    }

    // The namespaces have been removed. Now, build a simple xml file, and
    // read it with the simpleXml library.
    $htmlfilepaths = array();
    $buffer = '<?xml version="1.0" encoding="UTF-8"?><root>' . $buffer;
    $buffer .= '</root>';
    $xmlObject = simplexml_load_string($buffer);
    foreach($xmlObject->children() as $item) {
        if ($item['media-type'] == 'application/xhtml+xml') {
            $htmlfilepaths[] = strtolower($item['href']);
        }
    }
    
    return $htmlfilepaths;
}

/**
* Output file headers to initialise the download of the file.
* @param int $diguiid, the instance id of digui.
* @param int $subdiguiid, id of subdigui.
*/
function digui_files_send_header($digui, $subdiguiid) {
        
    global $CFG;
    if (strpos($CFG->wwwroot, 'https://') === FALSE) { //https sites - watch out for IE! KB812935 and KB316431
        header('Cache-Control: max-age=10');
        header('Pragma: ');
    } else { //normal http - prevent caching at all cost
        header('Cache-Control: private, must-revalidate, pre-check=0, post-check=0, max-age=0');
        header('Pragma: no-cache');
    }
    header('Expires: '. gmdate('D, d M Y H:i:s', 0) .' GMT');
    header("Content-Type: application/download\n");
//    header("Content-Disposition: attachment; filename=\"".$filename."\"");
    header("Content-Disposition: attachment; filename=\"Digui.txt\"");
}

/**
 * Extract a compressed file to a directory.
 * 
 * @param string $filepath, directory in the file system where the compressed file 
 * is.
 * @param string $destination, directory in the file system, where the file will
 * be uncompressed. 
 * @param bool $keepdirectories, indicates if the files must be uncompressed in
 * a single directory, or if it must keep the directories tree. In the first 
 * case, file that exists will be overriden by files with the same name.
 */
function digui_files_zip_extract($filepath, $destination, $keepdirectories, $inmemory) {

    // Example: 'C:\dir\file.txt'
    if (($zip = zip_open($filepath)))
    {
        $files = array();
        
        // Retrieves the files in the compressed file.
        while ($zip_entry = zip_read($zip))
        {
            if (zip_entry_open($zip, $zip_entry, "r"))
            {
                // zip_entry_name function retrieves the entire path of a file,
                // including its name. The path is relative. For example: 
                // 'dir\file.txt'.
                $filepath = zip_entry_name($zip_entry);
                // Extract the file name from its path. For example, from
                // 'dir\file.txt', would extract the string 'file.txt'.
                $filename = preg_replace( '~^(.*)/~', '', $filepath);
                // Create a new directory if the file is not in the root, and...
                if (strlen($filename) != strlen($filepath) && $keepdirectories && !$inmemory) {
                    // Extract the directory name from a path. For example, from
                    // 'dir\file.txt', would extract the string 'dir'.
                    $filedir = preg_replace( '~/[^/]*$~', '', $filepath);
                    // ... if the directory does not exists.
                    if (!file_exists($destination . DIRECTORY_SEPARATOR . $filedir)) {
                        mkdir($destination . DIRECTORY_SEPARATOR . $filedir);
                    }
                }
                else {
                    $filedir = '';
                }

                // Read contents from a file in the compressed file.
                $buffer = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                // In order to preserve the characters of language, we must convert the
                // text to UTF 8.
                // $buffer = mb_convert_encoding($buffer, "UTF-8");
                
                // Create a new file in the file system.
                if (strlen($filedir) > 0 && $keepdirectories && !$inmemory) {
                    file_put_contents($destination . DIRECTORY_SEPARATOR . $filedir . DIRECTORY_SEPARATOR . $filename, $buffer);
                    // $fp = fopen($destination . DIRECTORY_SEPARATOR . $filedir . DIRECTORY_SEPARATOR . $filename, "w");
                }
                else {
                    if (!$inmemory) {
                        file_put_contents($destination . DIRECTORY_SEPARATOR . $filename, $buffer);
                    }
                    else {
                        $files[strtolower($filename)] = $buffer;
                    }
                }
                
                zip_entry_close($zip_entry);
            }
        }
        zip_close($zip);
    }
    
    if (!$inmemory) {
        return true;
    }
    
    return $files;
}

/**
 * Get a digui instance
 * @param int $diguiid the instance id of digui
 */
function digui_get_digui($diguiid) {
    global $DB;

    return $DB->get_record('digui', array('id' => $diguiid));
}

/**
* Delete all grades from the gradebook for this digui.
* @param int $courseid, the course id for this digui instance.
* @param int $diguiid, id of digui instance object.
* @return bool
*/
function digui_grading_delete_grades($courseid, $diguiid) {
   global $CFG;

   $result = grade_update('mod/digui',
                          $courseid,
                          'mod',
                          'digui',
                          $diguiid,
                          0,
                          null,
                          array('deleted'=>1));
   return $result == GRADE_UPDATE_OK;
}

/**
 * Retrieve grade information about a user for a given digui.
 * @param int $diguiid the instance id of digui
 * @param int $userid, user id
 */
function digui_grading_get_grade_for_user($diguiid, $userid) {
    global $DB;
    
    $params = array('iteminstance' => $diguiid, 'userid' => $userid);
    $sql = "SELECT g.rawgrade
    FROM {grade_grades} g
    JOIN {grade_items} i ON g.itemid = i.id
    WHERE g.userid =:userid AND i.iteminstance =:iteminstance AND i.itemtype='mod' AND i.itemmodule='digui'";

    $rawgrade = $DB->get_record_sql($sql, $params);
    
    return $rawgrade;
}

/**
 * Check that a user has been graded for a given digui.
 * @param int $diguiid, the instance id of digui.
 * @param int $userid, user id
 */
function digui_grading_get_grade_status_for_user($diguiid, $userid) {
    
    $record = digui_grading_get_grade_for_user($diguiid, $userid);
    if (!isset($record->rawgrade)) {
        return false;
    }
    
    return true;
}

/**
 * Gets one group in current user.
 * @param int $userid, id of user.
 * @return object
 */
function digui_groups_get_users_out_of_groups($groups, $users) {

    foreach ($groups as $group) {
        
        $groupusers = groups_get_members($group->id);
        $groupusersaux = digui_array_copy($groupusers);
        for ($i=0,$j=count($groupusersaux); $i<$j; $i++) {
            for ($k=0,$l=count($users); $k<$l; $k++) {
                $usera = $groupusersaux[$i];
                $userb = $users[$k];
                if ($usera->id == $userb->id) {
                    $users = digui_array_remove_by_index($users, $i);
                    $k--;
                    $l=count($users);
                }
            }
        }
    }
    return $users;
}

/**
 * Gets the id of one group of a user.
 * @param int $userid, id of user.
 * @return object
 */
function digui_groups_get_user_group($diguiid, $userid) {
    
    $cm = get_coursemodule_from_instance('digui', $diguiid);
    $allowedgroups = groups_get_all_groups($cm->course, $userid, $cm->groupingid); // only assigned groups
    if ($allowedgroups) {
        // May happend the first array index starts is 1. We prevent this.
        $allowedgroups = array_values($allowedgroups);
        return $allowedgroups[0]->id;
    }
    else {
        return -1;
    }   
}

function digui_is_html_start_tag($token) {
    // The token is a HTML opening tag.
    if (preg_match('~<("[^"]*"|\'[^\']*\'|[^\'">/])*>~si', $token, $match) == 1) {
        return true;
    }
    
    return false;
}

function digui_is_html_end_tag($token) {    
    // The token is a HTML closing tag.
    if (preg_match('~</[^>]*>~si', $token, $match) == 1) {
        return true;
    }
    return false;
}

function digui_math_average($arr)
{
   if (!is_array($arr)) {
       return false;
   }

   return array_sum($arr)/count($arr);
}

/**
 * Create a text page. This pages is for read only, can't be highlighted by 
 * students.
 * @param string $text, text of the page.
 * @param int $pagenum, page number. 
 * @param int $diguiid, id of digui instance object.
 */
function digui_page_create_page($text, $pagenum, $diguiid) {
    global $DB;
    
    if (!($page = $DB->get_record('digui_pages', array('diguiid' => $diguiid, 'pagenum' => $pagenum)))) {

        $time = time();
        
        $page = (object) array(
            "diguiid" => $diguiid, 
            "cachedcontent" => $text, 
            "notes" => '', 
            "timecreated" => $time, 
            "timemodified" => $time, 
            "timerendered" => $time, 
            "pagenum" => $pagenum);
        
        $pageid = $DB->insert_record('digui_pages', $page);
    }
    else {
        // Update a existing page.
        $page->timemodified = time();
        $page->cachedcontent = $text;
        
        $DB->update_record('digui_pages', $page);
    }
}

/**
 * Create html pages from a epub file. These pages is for read only, can't be 
 * highlighted by students.
 * 
 * This function receives the file uploaded by a user, in the $file variable. 
 * However, we can retrieve that file using some API functions of Moodle:
 * $fs = get_file_storage();
 * $file = $fs->get_file_by_id($file->get_id());    
 * Instead using the get_file_by_id function, we can use get_file_by_hash:
 * $pathnamehash = $fs->get_pathname_hash($file->get_contextid(), $file->get_component(), $file->get_filearea(), $file->get_itemid(), $file->get_filepath(), $file->get_filename());
 * $result = $fs->get_file_by_hash($pathnamehash); // devuelve objetoe stored_file$file = $fs->get_file_by_hash($pathnamehash); // devuelve objetoe stored_file
 *
 * @param object $file, a file uploaded by a user. This file is allocated in
 * memory, and contains the text that will be highlighted by the user.
 * @param int $diguiid, id of digui instance object.
 */
function digui_page_create_epub_pages($file, $diguiid) {
    
    // This directory belongs to the directory tree of Digui.
    $path = 'temp';    
    
    // Delete all files in temp path.
    $files = scandir($path);
    foreach ($files as $key => $value) {
        if ($value != '.' && $value != '..') {
            if (is_dir($path . DIRECTORY_SEPARATOR . $value)) {
                be_rmdir($path . DIRECTORY_SEPARATOR . $value);
            }
            else {
                if (!unlink($path . DIRECTORY_SEPARATOR . $value)) {
                    print_error('Could not delete ' . $path . DIRECTORY_SEPARATOR . $value . ' file.');
                }
            }
        }
    }

    // The file uploaded by the user is allocated in memory. In order to 
    // uncompress this file (because a epub file is a compressed file), we 
    // must write the file in the file system. The epub file is copied to the 
    // temp directory (digui/temp).
    $epubfilename = $file->get_filename();
    $result = $file->copy_content_to($path . DIRECTORY_SEPARATOR . $epubfilename);
    
    // Convert the epub file to a html file.
    $before = microtime(true);
    $htmlfilepath = digui_files_convert_epub_to_html($path . DIRECTORY_SEPARATOR . $epubfilename, $path, false);
    $after = microtime(true);
    $time = $after - $before; // 2'5 segundos

    // Read the html file just created.
    $buffer = file_get_contents($htmlfilepath);
    
    // Create the digui pages. The html file will be chunked in several pages,
    // which be saved in the database.
    $before = microtime(true);
    $numofpages = digui_page_create_html_pages($buffer, $diguiid);
    $after = microtime(true);
    $time = $after - $before; // 18'3 segundos
    
    // Delete all files again in temp path.
    $files = scandir($path);
    foreach ($files as $key => $value) {
        if ($value != '.' && $value != '..') {
            if (is_dir($path . DIRECTORY_SEPARATOR . $value)) {
                be_rmdir($path . DIRECTORY_SEPARATOR . $value);
            }
            else {
                if (!unlink($path . DIRECTORY_SEPARATOR . $value)) {
                    print_error('Could not delete ' . $path . DIRECTORY_SEPARATOR . $value . ' file.');
                }
            }
        }
    }
    
    // Return.
    return $numofpages;
}

/**
 * Create html pages. These pages is for read only, can't be highlighted by 
 * students.
 * @param object $cm, the course module for this digui instance.
 * @param int $diguiid, id of digui instance object.
 */
function digui_page_create_html_pages($buffer, $diguiid) {
    
    // Extract the inner text inside the body tag.
    // preg_match('~<body("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</body>~si', $buffer, $match);
    preg_match('~<body[^>]*>(.*?)</body>~si', $buffer, $match);
    $buffer = $match[count($match) - 1];

    // 
    $before = microtime(true);
    $buffer = strip_non_formatting_tags($buffer);
    $after = microtime(true);
    $time = $after - $before; // X'X segundos

    // This step is necessary. Some errors will ocurre if return carriage and
    // other special characters are read by digui_str_read_token and other
    // functions. For example, browsers render &reg; character as one character
    // only, so the user will can select this character only, but internally the
    // offset are calculated over five characters. This issue will generate 
    // inconsistencies. So, we must erase return carriage and this others 
    // characters.
    $buffer = str_ireplace(array("\r\n", "\r", "\n"), "", $buffer);
    
    // "&lt;span&gt;" is a literal used as control string. It is 
    // inserted at the beginning and at the end of the selected text, to 
    // calculate the start and end position of the selected text.     
    // To avoid confuse "&lt;span&gt;" string with others text identicall to it, 
    // we must delete all ocurrences in the text.
    $buffer = str_ireplace(array('&lt;span&gt;', '&amp;lt;span&amp;gt;'), '', $buffer);
    
    $pagenum = 1;
    $start = 0;
    $numchars = strlen($buffer);
    $stack = array();
    
    while ($start < $numchars) {

        $bufferaux = '';

        // If this is not the first digui_str_read_tags invocation, may be 
        // some html tags were closed suddenly, because the previous invocation 
        // was interrupted after reading 4000 characters. So, we must open
        // this tags again at the next reading. Html tags first in being read, 
        // must be inserted in first place.
        if (count($stack) > 0) {
            for($i = 0, $j = count($stack); $i < $j; $i++) {
                $bufferaux .= $stack[$i];
            }
        }

        // $bufferaux .= digui_str_read_tags($buffer, $stack, $start);
        $bufferaux .= digui_str_read_one_html_page($buffer, $numchars, $stack, $start);
                
        // May be we need to close some html tags. Html tags last in being 
        // read,  must be inserted in first place.
        if (count($stack) > 0) {
            // Build and append a closing tag, for each opening tag non closed
            // properly.
            for($i = count($stack) - 1; $i > -1; $i--) {
                $openingtag = $stack[$i];
                // Extract the first word only, including the opening angle 
                // character (<). For example, if we have "<script src='script.js'>", 
                // the following instruction will extract a "<script" string.
                preg_match('/<[^\s>]+/s', $openingtag, $match);
                $endingtag = $match[0];
                // Insert a forward slash character (/). For example, if we have 
                // a "<script" string, the resulting string will be "</script".
                $endingtag = substr_replace($endingtag, '/', 1, 0);
                // Append an angle character (/) at the end of the tag.
                $endingtag .= '>';
                
                // Append the ending tag.
                $bufferaux .= $endingtag;
            }
        }

        digui_page_create_page($bufferaux, $pagenum, $diguiid);
        // $path = 'D:/Moodle/server/moodle/mod/digui/temp/' . $start . '.txt';
        // file_put_contents($path, $bufferaux);
        $pagenum++;
    }

    return $pagenum - 1;
}

/**
 * Create text pages. These pages is for read only, can't be highlighted by 
 * students.
 * @param object $cm, the course module for this digui instance.
 * @param int $diguiid, id of digui instance object.
 */
function digui_page_create_txt_pages($buffer, $diguiid) {
    
    // This step is necessary. Some errors will ocurre if return carriage and
    // other special characters are read by digui_str_read_token and other
    // functions. For example, browsers render &reg; character as one character
    // only, so the user will can select this character only, but internally the
    // offset are calculated over five characters. This issue will generate 
    // inconsistencies. So, we must erase return carriage and this others 
    // characters.
    $buffer = str_ireplace(array("\r\n", "\r", "\n"), "<br>", $buffer);
            
    $pagenum = 1;
    $start = 0;
    $numchars = strlen($buffer);
    while ($start < $numchars) {
        $bufferaux = digui_str_read_one_txt_page($buffer, $numchars, $start);
        digui_page_create_page($bufferaux, $pagenum, $diguiid);
        $pagenum++;
    }

    return $pagenum - 1;
}

/**
 * Create text pages. This pages is for read only, can't be highlighted by 
 * students.
 * @param object $cm, the course module for this digui instance.
 * @param int $diguiid, id of digui instance object.
 */
function digui_page_create_pages($cm, $diguiid) {
    global $DB;
    
    $context = context_module::instance($cm->id);

    $numofpages = 0;
    $fs = get_file_storage();
    $dir = $fs->get_area_tree($context->id, 'mod_digui', 'attachments', $diguiid);
    foreach ($dir['files'] as $file) {
        $filename = $file->get_filename();
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        if ($ext == "htm" || $ext == "html") {
            $buffer = $file->get_content();
            // Replace non ASCII characteres with its html entities. If we 
            // don't do this, the html document could be shown incorrectly.
            $buffer = fm_htmlentities_ex($buffer);
            // preg_replace function replaces $ characters followed by a number,
            // for example $0001. This is a undesired operation, because text
            // files could contain strings with these characters. To avoid this
            // operation, we must escape those $n backreferences. For example,
            // a "$0.95" string will be replaced with "\$0.95" string. 
            // See more in http://us1.php.net/manual/en/function.preg-replace.php#103985
            $buffer = preg_replace('/\$(\d)/', '\\\$$1', $buffer);
            $numofpages = digui_page_create_html_pages($buffer, $diguiid);
        }
        else if ($ext == "txt") {
            $buffer = $file->get_content();
            // preg_replace function replaces $ characters followed by a number,
            // for example $0001. This is a undesired operation, because text
            // files could contain strings with these characters. To avoid this
            // operation, we must escape those $n backreferences. For example,
            // a "$0.95" string will be replaced with "\$0.95" string.
            // See more in http://us1.php.net/manual/en/function.preg-replace.php#103985
            $buffer = preg_replace('/\$(\d)/', '\\\$$1', $buffer);
            $numofpages = digui_page_create_txt_pages($buffer, $diguiid);
        }
        else if ($ext == "epub") {
            $numofpages = digui_page_create_epub_pages($file, $diguiid);
        }
        else {
            print_error('fileformatnotsuported');
        }
            
    }
    
    return $numofpages;
}

/**
 * Get a digui page by its page number.
 * @param int $subdiguiid, id of subdigui.
 * @param int $pagenum, page number.
 * @return object
 */
function digui_page_get_page_by_pagenum($diguiid, $pagenum) {
    global $DB;
    return $DB->get_record('digui_pages', array('diguiid' => $diguiid, 'pagenum' => $pagenum));
}

/**
 * Get the total number of pages contained in a digui.
 * @param int $subdiguiid, id of subdigui.
 * @param int $pagenum, page number.
 * @return object
 */
function digui_page_get_number_of_pages($diguiid) {
    global $DB;

    $params = array('diguiid' => $diguiid);
    $sql = "SELECT MAX(pagenum) FROM {digui_pages} WHERE diguiid =:diguiid";
    $n = $DB->get_field_sql($sql, $params);
        
    return $n;
}

/**
 * Insert or update a page note, written by a user.
 * Function not implemented yet. When the digui is individual, each user has 
 * his own note in each page. So, probably we must modify the table digui_pages 
 * in the database.
 * 
 * @param int $diguiid, id of digui instance object.
 * @param int $pagenum, page number.
 * @param string $text, text to insert.
 * @return object
 */
function digui_page_insert_notes($diguiid, $pagenum, $text) {
    global $DB;
}

/**
 * Print a HTML element that shows text that current user can highlight.
 * @param string $text, text for highlighting.
 * @param bool $edit, indicate if user can highlight the text on the div.
 */
function digui_print_div($text, $edit) {
    // Only current user can hignlight its text.
    if ($edit) {
        echo '<div id="textdiv" name="nombre" '
        . 'style="cursor: url(pix/cursor.gif), url(pix/cursor.cur), default; overflow: scroll; background-color:#ffffff;  border-style: solid; border-color: #EEEEEE ; border-width: 1px; width: 66%; margin: 0 auto; padding: 5px; text-align: justify; font-size:14px;" '
//        . 'onmouseenter="unselectText(event)" '
//        . 'onmouseup="highlightText()" '
        . 'onmouseup="sendSubrayado()" '
//        . 'onmousedown="addEvent(event)"'
//        . 'onmouseout="unselectText(event)"'
                . '>' .$text.'</div>';
    }
    else {
        echo '<div id="textdiv" style="cursor: text; background-color:#ffffff; overflow: scroll; border-style: solid; border-color: #E0E0E0 ; border-width: 1px; width: 66%; margin: 0 auto; padding: 5px; text-align: justify; font-size:14px;">'.$text.'</div>';
    }
}

/**
 * Print a list of html links to the digui pages of user.
 * @param int $numpags, the total number of pages.
 * @param int $pagenum, current page being shown to the user.
 * @param int $diguiid, the instance id of digui.
 * @param int $subdiguiid, id of subdigui.
 * @param int $groupid
 * @param int $userid
 */
function digui_print_edit_links($numpags, $pagenum, $diguiid, $subdiguiid, $groupid, $userid, $diguimode) {
    global $CFG;
    
    $firstpage = $pagenum;
    if ($pagenum % 10 == 0) {
        $firstpage--;
    }
    
    for (; $firstpage % 10 != 0; $firstpage--);
    $firstpage++;
    
    $lastpage = $pagenum;
    for (; $lastpage % 10 != 0 && $lastpage < $numpags; $lastpage++);

    $previouspage = $firstpage - 10;
    $nextpage = $lastpage + 1;

    if ($pagenum > 10) {
        if ($diguimode == 'collaborative') {
            echo '<A HREF="'.$CFG->wwwroot.'/mod/digui/editc.php?';
        }
        else {
            echo '<A HREF="'.$CFG->wwwroot.'/mod/digui/editi.php?';
        }
        
        echo 'diguiid='.strval($diguiid).
                    '&subdiguiid='.strval($subdiguiid).
                    '&group='.strval($groupid).
                    '&uid='.strval($userid).
        //            '&title='.strval($pagetitleEx).
        //            '&pageid='.strval($pageid).
                    '&pagenum='.$previouspage.'"'.
                    'style="font-size: 1.3em">Anterior</A>';
        echo '<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';;
    }
    
    for ($i = $firstpage; $i < $lastpage + 1; $i++) {
        if ($i == $pagenum) {
            echo '<span style="font-size: 1.3em; font-weight: bold;">'.strval($i).'</span>';
        }
        else {
        
            if ($diguimode == 'collaborative') {
                echo '<A HREF="'.$CFG->wwwroot.'/mod/digui/editc.php?';
            }
            else {
                echo '<A HREF="'.$CFG->wwwroot.'/mod/digui/editi.php?';
            }

            echo  'diguiid='.strval($diguiid).
                    '&subdiguiid='.strval($subdiguiid).
                    '&group='.strval($groupid).
                    '&uid='.strval($userid).
        //            '&title='.strval($pagetitleEx).
        //            '&pageid='.strval($pageid).
                    '&pagenum='.strval($i).'"'.
                    'style="font-size: 1.3em">'.
                    strval($i).'</A>';
        }
        echo '<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';;
    }
    
    $setoften = $numpags;
    for (; $setoften % 10 != 0; $setoften--);
    
    if ($pagenum < $setoften) {
    echo '<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
    
    if ($diguimode == 'collaborative') {
        echo '<A HREF="'.$CFG->wwwroot.'/mod/digui/editc.php?';
    }
    else {
        echo '<A HREF="'.$CFG->wwwroot.'/mod/digui/editi.php?';
    }

    echo 'diguiid='.strval($diguiid).
                    '&subdiguiid='.strval($subdiguiid).
                    '&group='.strval($groupid).
                    '&uid='.strval($userid).
        //            '&title='.strval($pagetitleEx).
        //            '&pageid='.strval($pageid).
                    '&pagenum='.strval($i).'"'.
                    'style="font-size: 1.3em">Siguiente</A>';
    }
}

/**
 * Print a HTML select tag which contains a list of users that has edited or is 
 * currently editing a digui.
 * @param $digui object, digui instance object.
 * @param int $subdiguiid, id of subdigui.
 * @param int $selectedsubdiguiid, id of subdigui selected by user on the page.
 */
function digui_print_select_tag_for_grading($digui, $subdigui, $selectedgroupid) {
    global $DB;

    echo '<div style="text-align: center;">
    
    <select id="selecttag" onchange="sendSelectedGroupId()">';
    
    // Get users out of all groups.
    $cm = get_coursemodule_from_instance('digui', $digui->id);
    
    switch (groups_get_activity_groupmode($cm)) {

        case SEPARATEGROUPS:
            
            $context = context_module::instance($cm->id);

            $aag = has_capability('moodle/site:accessallgroups', $context);
            if ($aag) {
                $allowedgroups = groups_get_all_groups($cm->course, 0, $cm->groupingid); // any group in grouping
            }
            else {
                $allowedgroups = groups_get_all_groups($cm->course, $USER->id, $cm->groupingid); // only assigned groups
            }

//            $activegroup = groups_get_activity_group($cm, true, $allowedgroups);

            if ($allowedgroups) {
                foreach ($allowedgroups as $group) {
                    // Show groups of current user only, unless current user be
                    // teacher or administratror. In this case, list all groups.
                    if ($aag || $subdigui->groupid == $group->id) {
                        if ($selectedgroupid == $group->id) {
                            echo '<option value="'.$group->id.'" selected="selected">'.$group->name.'</option>';
                        }
                        else {
                            echo '<option value="'.$group->id.'">'.$group->name.'</option>';
                        }
//                        $users = groups_get_members($group->id);           
                    }
                }
            }

            $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
            $contextcourse = context_course::instance($course->id);

            $groups = groups_get_all_groups($cm->course, 0, $cm->groupingid); 
            $users = array();
            $roleid = array(1,2,3,4,5,6,7,8);
            foreach ($roleid as $id) {
                $users = $users + get_role_users($id, $contextcourse, true);
            }
            $allusers = digui_array_copy($allusers);
            $users = digui_groups_get_users_out_of_groups($groups, $allusers);

            $useringroup = false;
            foreach ($users as $user) {
                // If the active user is out of all groups, we must print users 
                // in the same situation -out of all groups-.
                if ($user->id == $subdigui->userid) {
                    $useringroup = true;
                }
            }
            
            // If current user is teacher or administratror, or is out of all 
            // groups, print group associatted to these users.
            if (count($users) > 0 && ($aag || $useringroup)) {
                if ($selectedgroupid == -1) {
                    echo '<option value="-1" selected="selected">'.get_string('restofusers', 'digui').'</option>';
                }
                else {
                    echo '<option value="-1">'.get_string('restofusers', 'digui').'</option>';
                }                
            }
            
        break;
    
        CASE VISIBLEGROUPS:
            
            if ($digui->diguimode == 'collaborative') {
                
                $spersonalinformation = get_string('showmarks', 'digui');
                $subdiguiaux = digui_subdigui_get_subdigui_by_group($digui->id, 0, 0);
                $value = $subdiguiaux->id;
                echo '<option value="'.$value.'">'.$spersonalinformation.'</option>';
            }
            $context = context_module::instance($cm->id);

            $aag = has_capability('moodle/site:accessallgroups', $context);
            if ($aag) {
                $allowedgroups = groups_get_all_groups($cm->course, 0, $cm->groupingid); // any group in grouping
            }
            else {
                $allowedgroups = groups_get_all_groups($cm->course, $USER->id, $cm->groupingid); // only assigned groups
            }

            if ($allowedgroups) {
                foreach ($allowedgroups as $group) {
                    // Show groups of current user only, unless current user be
                    // teacher or administratror. In this case, list all groups.
                    if ($selectedgroupid == $group->id) {
                        echo '<option value="'.$group->id.'" selected="selected">'.$group->name.'</option>';
                    }
                    else {
                        echo '<option value="'.$group->id.'">'.$group->name.'</option>';
                    }

//                    $users = groups_get_members($group->id);           
                }
            }
            
            $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
            $contextcourse = context_course::instance($course->id);

            $groups = groups_get_all_groups($cm->course, 0, $cm->groupingid); 
            $users = array();
            $roleid = array(1,2,3,4,5,6,7,8);
            foreach ($roleid as $id) {
                $users = $users + get_role_users($id, $contextcourse, true);
            }
            $allusers = digui_array_copy($allusers);
            $users = digui_groups_get_users_out_of_groups($groups, $allusers);

            // If there are some users out of all groups, print this issue in the drop 
            // down list.
            if (count($users) > 0) {
                if ($selectedgroupid == -1) {
                    echo '<option value="-1" selected="selected">'.get_string('restofusers', 'digui').'</option>';
                }
                else {
                    echo '<option value="-1">'.get_string('restofusers', 'digui').'</option>';
                }
            }
            
        break;
    
        default:
        
        // error
        return;
    }
            
    
    echo "</select></div>";   
}

/**
 * Prints a HTML select tag which contains a list of users that have edited or 
 * are currently editing a digui.
 * @param $digui object, digui instance object.
 * @param int $subdiguiid, id of subdigui.
 * @param int $selectedsubdiguiid, id of subdigui selected by user on the page.
 */
function digui_print_select_tag($digui, $subdigui, $selectedsubdiguiid) {
    global $DB;

    echo '<div style="text-align: center;">
    
    <select id="selecttag" onchange="sendSelectedSubdiguiId()">';
    
    $cm = get_coursemodule_from_instance('digui', $digui->id);
    switch (groups_get_activity_groupmode($cm)) {

        case NOGROUPS:
            // Get a list of users that have edited or are currently editing the
            //digui. Remember that each digui has a user associated.
            $subdiguis = digui_user_get_users_by_digui($digui->id);

            foreach ($subdiguis as $subdiguiaux) {

                // This condition is true only if digui is collaborative.
                if ($subdiguiaux->userid == 0) {
                    $spersonalinformation = get_string('showmarks', 'digui');
                    $value = $subdiguiaux->id;
                }
                else {
                    // Get name and lastname of user.
                    $personalinformation = digui_user_get_user_information($subdiguiaux->userid);

                    $spersonalinformation = $personalinformation->username;
                    $spersonalinformation .= " (";
                    $spersonalinformation .= $personalinformation->firstname;
                    $spersonalinformation .= " ";
                    $spersonalinformation .= $personalinformation->lastname;
                    $spersonalinformation .= ")";
                    $value = $subdiguiaux->id;
                }

                 $option = '<option ';

                // Current user must appear first in the list of users.
                if ($subdiguiaux->id == $selectedsubdiguiid)
                    $option .= 'selected="selected" ';
                                
                // Add an asterisk to the item list, whose user be equal to the 
                // current user.
                if ($subdiguiaux->id == $subdigui->id)
                    $option .= 'value="'.$value.'">* '.$spersonalinformation.'</option>';
                else 
                    $option .= 'value="'.$value.'"> '.$spersonalinformation.'</option>';

                $options[] = $option;
            }

            foreach ($options as $option) 
                echo $option;
            unset($options);

        break;
    
        case SEPARATEGROUPS:
            
            if ($digui->diguimode == 'collaborative') {
                
                $spersonalinformation = get_string('showmarks', 'digui');
                $subdiguiaux = digui_subdigui_get_subdigui_by_group($digui->id, 0, 0);
                $value = $subdiguiaux->id;
                echo '<option value="'.$value.'">'.$spersonalinformation.'</option>';
            }
            $context = context_module::instance($cm->id);

            $aag = has_capability('moodle/site:accessallgroups', $context);
            if ($aag) {
                $allowedgroups = groups_get_all_groups($cm->course, 0, $cm->groupingid); // any group in grouping
            }
            else {
                $allowedgroups = groups_get_all_groups($cm->course, $USER->id, $cm->groupingid); // only assigned groups
            }

            $activegroup = groups_get_activity_group($cm, true, $allowedgroups);

            if ($allowedgroups) {
                foreach ($allowedgroups as $group) {
                    // Show groups of current user only, unless current user be
                    // teacher or administratror. In this case, list all groups.
                    if ($aag || $subdigui->groupid == $group->id) {
                        echo '<optgroup label="'.$group->name.'">';
                        $users = groups_get_members($group->id);
                        foreach ($users as $user) {
                        // Try to get an user with typical permissions.
                        if (($subdiguiaux = digui_subdigui_get_subdigui_by_group($digui->id, $group->id, $user->id))
                        // Try to get an user with admin permissions. 0 means
                        // that user has access to all groups. View the 
                        // documentation about the groups_get_activity_group
                        // function.
                        || ($subdiguiaux = digui_subdigui_get_subdigui_by_group($digui->id, 0, $user->id))) {
                            
                                // Get name and lastname of user.
                                $personalinformation = digui_user_get_user_information($user->id);

                                $spersonalinformation = $personalinformation->username;
                                $spersonalinformation .= " (";
                                $spersonalinformation .= $personalinformation->firstname;
                                $spersonalinformation .= " ";
                                $spersonalinformation .= $personalinformation->lastname;
                                $spersonalinformation .= ")";
                                $value = $subdiguiaux->id;

                                $option = '<option ';

                                // Current user must appear first in the list of 
                                // users.
                                if ($subdiguiaux->id == $selectedsubdiguiid)
                                    $option .= 'selected="selected" ';

                                // Add an asterisk to the item list, whose user 
                                // be equal to the current user.
                                if ($subdigui->userid == $user->id) {
                                    $option .= 'value="'.$value.'">* '.$spersonalinformation.'</option>';
                                }
                                else {
                                    $option .= 'value="'.$value.'"> '.$spersonalinformation.'</option>';
                                }
                                $options[] = $option;
                            }
                        }

                        foreach ($options as $option) 
                            echo $option;
                        unset($options);

                        echo '</optgroup>';
                    }
                }
            }
            
            $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
            $contextcourse = context_course::instance($course->id);

            $groups = groups_get_all_groups($cm->course, 0, $cm->groupingid); 
            $users = array();
            $roleid = array(1,2,3,4,5,6,7,8);
            foreach ($roleid as $id) {
                $users = $users + get_role_users($id, $contextcourse, true);
            }
            $allusers = digui_array_copy($allusers);
            $users = digui_groups_get_users_out_of_groups($groups, $allusers);

            $useringroup = false;
            foreach ($users as $user) {
                // If the active user is out of all groups, we must print users 
                // in the same situation -out of all groups-.
                if ($user->id == $subdigui->userid) {
                    $useringroup = true;
                }
            }
            
            // If current user is teacher or administratror, or is out of all 
            // groups, print group associatted to these users.
            if (count($users) > 0 && ($aag || $useringroup)) {
        
                echo '<optgroup label="'.get_string('restofusers', 'digui').'">';

                foreach ($users as $user) {
                // Try to get an user with admin permissions. 0 means
                // that user has access to all groups. View the 
                // documentation about the groups_get_activity_group
                // function.
                if (($subdiguiaux = digui_subdigui_get_subdigui_by_group($digui->id, 0, $user->id))) {

                        // Get name and lastname of user.
                        $personalinformation = digui_user_get_user_information($user->id);

                        $spersonalinformation = $personalinformation->username;
                        $spersonalinformation .= " (";
                        $spersonalinformation .= $personalinformation->firstname;
                        $spersonalinformation .= " ";
                        $spersonalinformation .= $personalinformation->lastname;
                        $spersonalinformation .= ")";
                        $value = $subdiguiaux->id;

                        $option = '<option ';

                        // Current user must appear first in the list of 
                        // users.
                        if ($subdiguiaux->id == $selectedsubdiguiid)
                            $option .= 'selected="selected" ';

                        // Add an asterisk to the item list, whose user 
                        // be equal to the current user.
                        if ($subdigui->userid == $user->id) {
                            $option .= 'value="'.$value.'">* '.$spersonalinformation.'</option>';
                        }
                        else {
                            $option .= 'value="'.$value.'"> '.$spersonalinformation.'</option>';
                        }
                        $options[] = $option;
                    }
                }

                foreach ($options as $option) 
                    echo $option;
                unset($options);

                echo '</optgroup>';
            }

        break;
    
        CASE VISIBLEGROUPS:
            
            if ($digui->diguimode == 'collaborative') {
                
                $spersonalinformation = get_string('showmarks', 'digui');
                $subdiguiaux = digui_subdigui_get_subdigui_by_group($digui->id, 0, 0);
                $value = $subdiguiaux->id;
                echo '<option value="'.$value.'">'.$spersonalinformation.'</option>';
            }
            $context = context_module::instance($cm->id);

            $aag = has_capability('moodle/site:accessallgroups', $context);
            if ($aag) {
                $allowedgroups = groups_get_all_groups($cm->course, 0, $cm->groupingid); // any group in grouping
            }
            else {
                $allowedgroups = groups_get_all_groups($cm->course, $USER->id, $cm->groupingid); // only assigned groups
            }

            $activegroup = groups_get_activity_group($cm, true, $allowedgroups);

            if ($allowedgroups) {
                foreach ($allowedgroups as $group) {
                    echo '<optgroup label="'.$group->name.'">';
                    $users = groups_get_members($group->id);
                    foreach ($users as $user) {
                        // Try to get an user with typical permissions.
                        if (($subdiguiaux = digui_subdigui_get_subdigui_by_group($digui->id, $group->id, $user->id))
                        // Try to get an user with admin permissions. 0 means
                        // that user has access to all groups. View the 
                        // documentation about the groups_get_activity_group
                        // function.
                        || ($subdiguiaux = digui_subdigui_get_subdigui_by_group($digui->id, 0, $user->id))) {
                            // Get name and lastname of user.
                            $personalinformation = digui_user_get_user_information($user->id);

                            $spersonalinformation = $personalinformation->username;
                            $spersonalinformation .= " (";
                            $spersonalinformation .= $personalinformation->firstname;
                            $spersonalinformation .= " ";
                            $spersonalinformation .= $personalinformation->lastname;
                            $spersonalinformation .= ")";
                            $value = $subdigui->id;

                            $option = '<option ';

                            // Current user must appear first in the list of 
                            // users.
                            if ($subdiguiaux->id == $selectedsubdiguiid)
                                $option .= 'selected="selected" ';

                            // Add an asterisk to the item list, whose user 
                            // be equal to the current user.
                            if ($subdigui->userid == $user->id) {
                                $option .= 'value="'.$value.'">* '.$spersonalinformation.'</option>';
                            }
                            else {
                                $option .= 'value="'.$value.'"> '.$spersonalinformation.'</option>';
                            }
                            $options[] = $option;
                        }
                    }

                    foreach ($options as $option) 
                        echo $option;
                    unset($options);

                    echo '</optgroup>';
                }
            }
            
            $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
            $contextcourse = context_course::instance($course->id);

            $groups = groups_get_all_groups($cm->course, 0, $cm->groupingid); 
            $users = array();
            $roleid = array(1,2,3,4,5,6,7,8);
            foreach ($roleid as $id) {
                $users = $users + get_role_users($id, $contextcourse, true);
            }
            $allusers = digui_array_copy($allusers);
            $users = digui_groups_get_users_out_of_groups($groups, $allusers);
            
            // Print users that are out of all groups.
            if (count($users)) {
        
                echo '<optgroup label="'.get_string('restofusers', 'digui').'">';

                foreach ($users as $user) {
                    // Try to get an user with admin permissions. 0 means
                    // that user has access to all groups. View the 
                    // documentation about the groups_get_activity_group
                    // function.
                    if (($subdiguiaux = digui_subdigui_get_subdigui_by_group($digui->id, 0, $user->id))) {

                        // Get name and lastname of user.
                        $personalinformation = digui_user_get_user_information($user->id);

                        $spersonalinformation = $personalinformation->username;
                        $spersonalinformation .= " (";
                        $spersonalinformation .= $personalinformation->firstname;
                        $spersonalinformation .= " ";
                        $spersonalinformation .= $personalinformation->lastname;
                        $spersonalinformation .= ")";
                        $value = $subdiguiaux->id;

                        $option = '<option ';

                        // Current user must appear first in the list of 
                        // users.
                        if ($subdiguiaux->id == $selectedsubdiguiid)
                            $option .= 'selected="selected" ';

                        // Add an asterisk to the item list, whose user 
                        // be equal to the current user.
                        if ($subdigui->userid == $user->id) {
                            $option .= 'value="'.$value.'">* '.$spersonalinformation.'</option>';
                        }
                        else {
                            $option .= 'value="'.$value.'"> '.$spersonalinformation.'</option>';
                        }
                        $options[] = $option;
                    }
                }

                foreach ($options as $option) 
                    echo $option;
                unset($options);

                echo '</optgroup>';
            }
            
        break;
    
        default:
        
        // error
        return;
    }
            
    
    echo "</select></div>";   
}

/**
 * Echos or returns a text data line by line for displaying.
 * @param $digui object, digui instance object.
 * @param int $subdiguiid, id of subdigui.
 * @param int $selectedsubdiguiid, id of subdigui selected by user on the page.     
 */
function digui_print_txt($digui, $subdiguiid, $selectedsubdiguiid) {
    if (!$subdigui = digui_subdigui_get_subdigui_by_id($subdiguiid)) {
        print_error('incorrectsubdiguiid', 'digui');
    }  

    $userinformation = digui_user_get_user_information($subdigui->userid);
    if ($digui->diguimode == 'collaborative') {
        $author = get_string('various', 'digui');
        $email = $userinformation->email;
    }
    else {
        $author = '';
        $author .= $userinformation->firstname;
        $author = ' ';
        $author .= $userinformation->lastname;
        $email = $userinformation->email;
    }

    if ($digui->diguimode == 'collaborative') {
        if (!$subdigui = digui_subdigui_get_subdigui_by_group($digui->id, 0, 0)) {
            print_error('incorrectsubdiguiid', 'digui');
        }
    }
    
    $numchars = 0;
    $numlines = 0;
    $numpages = 1;
    digui_print_txt_title($digui, $author, $email, $numchars, $numlines, $numpages);
    $numchars = 0;
    digui_print_txt_content($digui->id, $selectedsubdiguiid, $digui->format, $numchars, $numlines, $numpages);    
}

/**
 * Echos or returns a text data line by line for displaying.
 * @param $digui object, digui instance object.
 * @param int $subdiguiid, id of subdigui.
 * @param int $numchars, number of characters written up to now   
 * @param int $numlines, number of lines written up to now.
 * @param int $numpages, number of pages written up to now.     
 */
function digui_print_txt_content($diguiid, $selectedsubdiguiid, $format, & $numchars, & $numlines, & $numpages) {
    global $DB;

    $params = array('diguiid' => $diguiid);
    $sql = "SELECT cachedcontent, notes "
            . "FROM {digui_pages} "
            . "WHERE diguiid=:diguiid "
            . "ORDER BY pagenum ASC";
                
    if (!$selectedsubdigui = digui_subdigui_get_subdigui_by_id($selectedsubdiguiid)) {
        print_error('incorrectsubdiguiid', 'digui');
    }  

    $pages = $DB->get_records_sql($sql, $params);
    // @TODO: separators at the end of page, even though is not necessary. To
    // confirm this, highlight an entire page.
    $pagenum = 1;
    foreach ($pages as $pagebd) {
        
        // The user wants to export the marks of one user.
        if ($selectedsubdigui->userid != 0) {
            $spans = digui_span_get_by_subdiguiid($diguiid, $selectedsubdigui->id, $pagenum, 1);
        }
        // The user wants to export the marks of all users.
        else {
            $spans = digui_span_get_by_diguiid($diguiid, $pagenum, 1);
        }
        
        if (is_null($spans) || count($spans) == 0) {
            $pagenum++;
            continue;
        }
        // 
        $buffer = $pagebd->cachedcontent;

        $buffer = digui_span_insert($buffer, $spans);
        $buffer = digui_span_join($buffer);
        
        // Replace html entities (like &#243;), with corresponding character 
        // (ó, in this example).
        $buffer = fm_entity_decode_ex($buffer);
        // Extract text between <span> tags.
        $buffer = digui_str_summarize_text($buffer);
        // Delete html tags, because the text will be exported to a txt file.
        if ($format == "html" || $format == "epub") {
            $buffer = preg_replace('~<("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $buffer);
        }
        // 
        $page = digui_print_txt_page($buffer, $numchars, $numlines, $numpages);
        $pagenum++;
        
        echo $page;
    }
}

/**
 * Echos or returns a text data line by line for displaying.
 * @param $digui object, digui instance object.
 * @param int $pagenumber, current page number.
 */
function digui_print_txt_header($pagenumber) {
    $word = "";
    $word .= get_string('page', 'digui');
    $word .= $pagenumber;
    $word .= "]";
    $line = "";
    for($i=0; $i < 80 - strlen($word); $i++) {
        $line .= ' ';
    }
    $line .= $word;
    
    echo PHP_EOL;
    echo PHP_EOL;
    echo PHP_EOL;
    echo $line;
    echo PHP_EOL;
    echo PHP_EOL;
}

/**
 * Echos or returns a text data line by line for displaying.
 * @param $digui object, digui instance object.
 * @param string $buffer, text to display.
 * @param int $numchars, number of characters written up to now   
 * @param int $numlines, number of lines written up to now.
 * @param int $numpages, number of pages written up to now.     
 */
function digui_print_txt_page($buffer, & $numchars, & $numlines, & $numpages) {
    $i = 0;
    $word = 'a';
    
    if ($numchars == 0) {
        echo "   ";
    }
    
    while ($word != '') {
         $word = digui_str_read_word($buffer, $i);
         // $newlines = digui_str_count_newlines($word);
         // $numlines += $newlines;
         if ($word == PHP_EOL) {
             $numchars = 0;
             $numlines++;

             if ($numlines > 50) {
                 $numlines = 0;
                 digui_print_txt_header($numpages);
                 $numpages++;
                 echo "   ";
                 echo $word;
             }
             else {
                 echo "   ";
                 echo $word;
             }
         }
         else {
             // 80 minus three characters in "   ".
             if ($numchars + strlen($word) > 77) {

                 $numchars = 0;
                 $numlines++;
                 if ($numlines > 50) {
                     $numlines = 0;
                     digui_print_txt_header($numpages);
                     $numpages++;
                     echo "   ";
                     if (preg_match("/^ *$/", $word) != 1) {
                        echo $word;
                        $numchars += strlen($word);
                     }
                 }
                 else {
                     echo PHP_EOL;
                     echo "   ";
                     if (preg_match("/^ *$/", $word) != 1) {
                        echo $word;
                        $numchars += strlen($word);
                     }
                 }
             }
             else {
                echo $word;
                $numchars += strlen($word);
             }
        }
    }
}

/**
 * Echos or returns a text data line by line for displaying.
 * @param $digui object, digui instance object.
 * @param string $author, user with admin privileges who created the digui. 
 * @param string $email, email of user who created the digui.
 * @param int $numchars, number of characters written up to now   
 * @param int $numlines, number of lines written up to now.
 * @param int $numpages, number of pages written up to now.     
 */
function digui_print_txt_title($digui, $author, $email, & $numchars, & $numlines, & $numpages) {
    
    // Print header name of digui.
    $line = "Digui 1.0";
    $nameh = get_string('diguiname', 'digui');
    for ($i = 0, $j = 80 - (strlen("Digui 1.0") + strlen($nameh)); $i < $j; $i++) {
        $line .= ' ';
    }
    $line .= $nameh;
    echo $line;
    
    echo PHP_EOL;

    // Print name of digui.
    $line = "";
    $name = $digui->name;;
    for ($i = 0, $j = 80 - strlen($name); $i < $j; $i++) {
        $line .= ' ';
    }
    $line .= $name;
    echo $line;
    
    echo PHP_EOL;
    echo PHP_EOL;

    // Print author header/s of digui.
    $line = '';
    $authorh = '';
    $authorh .= get_string('diguiauthors', 'digui');
    for ($i = 0, $j = 80 - strlen($authorh); $i < $j; $i++) {
        $line .= ' ';
    }
    $line .= $authorh;
    echo $line;
    
    echo PHP_EOL;

    // Print author/s of digui.
    $line = '';
    for ($i = 0, $j = 80 - strlen($author); $i < $j; $i++) {
        $line .= ' ';
    }
    $line .= $author;
    echo $line;
    
    // Print email.
    if (!empty($email)) {
        echo PHP_EOL;

        $line = '';
        for ($i = 0, $j = 80 - strlen($email); $i < $j; $i++) {
            $line .= ' ';
        }
        $line .= $email;
        echo $line;
    }
    
    echo PHP_EOL;
    echo PHP_EOL;
    
    // Print date.
    $line = '';
    $time=strtotime(date(DATE_ISO8601));
    $date = "";
    $date .= date("d",$time);
    $date .= "-";
    $date .= date("F",$time);
    $date .= "-";
    $date .= date("Y",$time);
    for ($i = 0, $j = 80 - strlen($date); $i < $j; $i++) {
        $line .= ' ';
    }
    $line .= $date;
    echo $line;
    
    echo PHP_EOL;
    echo PHP_EOL;
    
    if (!empty($digui->intro)) {
        echo PHP_EOL;
        
        // Print header description.
        $line = "";
        $n = 80 - strlen(get_string('diguiintro', 'digui'));
        for ($i = 0, $j = ($n / 2); $i < $j; $i++) {
            $line .= ' ';
        }
        $line .= get_string('diguiintro', 'digui');
        for ($i = 0, $j = ($n / 2); $i < $j; $i++) {
            $line .= ' ';
        }
        echo $line;

        echo PHP_EOL;
        echo PHP_EOL;

        // Print description.
        $numchars = 0;
        //        $numlines = 12;
        //        $numpages = 1;
        digui_print_txt_page($digui->intro, $numchars, $numlines, $numpages);

        echo PHP_EOL;
        echo PHP_EOL;
    }
    
    // Print separator.
    echo "                                   ----------                                   ";
     
    echo PHP_EOL;
    echo PHP_EOL;
    echo PHP_EOL;
    
    // Print header title of book.
    $line = '';
    $titleh = get_string('title', 'digui');
    $line .= $titleh;
    for ($i = 0, $j = 80 - strlen($titleh); $i < $j; $i++) {
        $line .= ' ';
    }

    echo $line;
    
    echo PHP_EOL;
    
    // Print title of book.
    $line = '';
    $title = $digui->title;
    $line .= $title;
    for ($i = 0, $j = 80 - strlen($title); $i < $j; $i++) {
        $line .= ' ';
    }

    echo $line;
    
    echo PHP_EOL;
    echo PHP_EOL;
    
    // Print header author of book.
    if (!empty($digui->author1) || !empty($digui->author2) ) {
        $line = '';
        $authorh = get_string('originalauthors', 'digui');
        $line .= $authorh;
        for ($i = 0, $j = 80 - strlen($authorh); $i < $j; $i++) {
            $line .= ' ';
        }
        
        echo $line;
        echo PHP_EOL;

        // Print original author/s.
        if (!empty($digui->author1)) {
            $line = '';
            $line .= $digui->author1;
            for ($i = 0, $j = 80 - strlen($digui->author1); $i < $j; $i++) {
                $line .= ' ';
            }

            echo $line;
            if (!empty($digui->author2)) {
                echo PHP_EOL;
            }
        }

        if (!empty($digui->author2)) {
            $line = '';
            $line .= $digui->author2;
            for ($i = 0, $j = 80 - strlen($digui->author2); $i < $j; $i++) {
                $line .= ' ';
            }

            echo $line;
        }
    }
    
    if (!empty($digui->author1) || !empty($digui->author2) ) {
        echo PHP_EOL;
        echo PHP_EOL;
        echo PHP_EOL;
    }
    else {
        echo PHP_EOL;
    }
    
    // Print title.
    $line = "";
    $n = 80 - strlen($digui->title);
    for ($i = 0; $i < ($n / 2); $i++) {
        $line .= ' ';
    }
    $line .= strtoupper($digui->title);
    for ($i = 0; $i < ($n / 2); $i++) {
        $line .= ' ';
    }
    echo $line;
    
    echo PHP_EOL;
    echo PHP_EOL;
    
    // Print synopsis.
    if (!empty($digui->synopsis)) {
        echo get_string('synopsis', 'digui');

        echo PHP_EOL;
        echo PHP_EOL;

        $numchars = 0;
        digui_print_txt_page($digui->synopsis, $numchars, $numlines, $numpages);

        echo PHP_EOL;
        echo PHP_EOL;
    }
    
    // Print header of the content.
    echo get_string('content', 'digui');
    
    echo PHP_EOL;
    echo PHP_EOL;
}
            
/**
 * Print a list of html links to the digui pages of user.
 * @param int $prefix, piece of text to create the links.
 * @param int $numpags, the total number of pages.
 * @param int $pagenum, current page being shown to the user.
 * @param int $diguiid, the instance id of digui.
 * @param int $subdiguiid, id of subdigui.
 * @param int $selectedsubdiguiid, id of subdigui selected by user on the page.
 * @param int $groupid
 * @param int $userid
 */
function digui_print_view_links($prefix, $numpags, $pagenum, $diguiid, $subdiguiid, $selectedsubdiguiid, $groupid, $userid) {
    global $CFG;
    
    $firstpage = $pagenum;
    if ($pagenum % 10 == 0) {
        $firstpage--;
    }
    
    for (; $firstpage % 10 != 0; $firstpage--);
    $firstpage++;
    
    $lastpage = $pagenum;
    for (; $lastpage % 10 != 0 && $lastpage < $numpags; $lastpage++);

    $previouspage = $firstpage - 10;
    $nextpage = $lastpage + 1;

    if ($pagenum > 10) {
        echo '<A HREF="'.$CFG->wwwroot.'/mod/digui/'.$prefix.'.php?'.
                        'diguiid='.strval($diguiid).
                        '&subdiguiid='.strval($subdiguiid).
                        '&selectedsubdiguiid='.strval($selectedsubdiguiid).
                        '&group='.strval($groupid).
                        '&uid='.strval($userid).
                        '&pagenum='.$previouspage.'"'.
                        'style="font-size: 1.1em; font-weight: bold;">Anterior</A>';
        echo '<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';;
    }
    
    for ($i = $firstpage; $i < $lastpage + 1; $i++) {
        if ($i == $pagenum) {
            echo '<span style="font-size: 1.3em; font-weight: bold;">'.strval($i).'</span>';
        }
        else {
            echo '<A HREF="'.$CFG->wwwroot.'/mod/digui/'.$prefix.'.php?'.
                    'diguiid='.strval($diguiid).
                    '&subdiguiid='.strval($subdiguiid).
                    '&selectedsubdiguiid='.strval($selectedsubdiguiid).
                    '&group='.strval($groupid).
                    '&uid='.strval($userid).
                    '&pagenum='.strval($i).'"'.
                    'style="font-size: 1.3em">'.
                    strval($i).'</A>';
        }
        echo '<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';;
    }

    $setoften = $numpags;
    for (; $setoften % 10 != 0; $setoften--);
    
    if ($pagenum < $setoften) {
    echo '<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';;
    echo '<A HREF="'.$CFG->wwwroot.'/mod/digui/'.$prefix.'.php?'.
                    'diguiid='.strval($diguiid).
                    '&subdiguiid='.strval($subdiguiid).
                    '&selectedsubdiguiid='.strval($selectedsubdiguiid).
                    '&group='.strval($groupid).
                    '&uid='.strval($userid).
                    '&pagenum='.$nextpage.'"'.
                    'style="font-size: 1.1em; font-weight: bold;">Siguiente</A>';
    }
}

/**
 * Add a new span to the database, and modifies the rest of spans if necessary.
 * The new span has been drawn by the user, using the mouse.
 * 
 * Normally, some spans will be modify if the new span overlaps these spans.
 * 
 * We call "overlapping span", one which is over one or more spans. We call 
 * "overlapped span", one which is under the overlapping span. There is one and 
 * only one overlapping span, every time the digui_span_new function is called.
 * 
 * In the comments of digui_span_new function, we symbolize spans with pair of
 * numbers, like 1 1', where 1 is the starting endpoint of the span, and 1' is 
 * the ending endpoint of the span. In the example 1 1' 2 2', there are
 * two spans, the span (1 1') and the span (2 2'), and they are not overlapped.
 * In the example 1 2 1' 2', the are two spans too, the span (1 1') and the span 
 * (2 2'), and they are overlapped (the common interval is the (2 1')).
 * 
 * The digui_span_new function modifies mdl_digui_spans table only. The rest
 * of tables are not modified.
 * 
 * @param int $diguiid, id of digui instance object.
 * @param string $diguimode, indicates whether each page of the digui can be 
 * modified by several users (collaborative mode), or if this pages can be
 * modified by one user only (individual mode).
 * @param int $subdiguiid, id of subdigui whose user is drawing the new span. 
 * @param int $pagenum, page number where the user is drawing the new span.
 * @param int $pageversion, version of the page is being modified.
 * @param string $text, the text of the page is being modified.
 * @param string $ltcharacters, contains the indexes in the $text variable, 
 * where characters must be replaced by "<" characters.
 * @param string $gtcharacters, contains the indexes in the $text variable, 
 * where characters must be replaced by ">" characters.
 * @return int, the new version of the page is being modified, or the current
 * version of the page if don't need to be modified.
 */
function digui_span_new($diguiid, $diguimode, $subdiguiid, $pagenum, $pageversion, $text, $ltcharacters, $gtcharacters) {
    // Get necessary variables.
    if (!$subdigui = digui_subdigui_get_subdigui_by_id($subdiguiid)) {
        print_error('incorrectsubdiguiid', 'digui');
    }

    if (strlen($text) == 0) {
        return $pageversion;
    }

    // There is a function in the helper.js file, called sendSubrayado, 
    // which searched for "<" and ">" characters in the text of digui, and 
    // replaced them with words. Now, we must restore "<" and ">" 
    // characters.
    $text = str_ireplace('openinganglebracket', '<', $text);
    $text = str_ireplace('closinganglebracket', '>', $text);

    // In javascript script, we inserted '&lt;openingtag&gt;' tag in the text. 
    // "&lt;openingtag&gt;" is a literal used as control string. It is 
    // inserted at the beginning of the selected text, to 
    // calculate the start of the selected text. 
    // However, maybe php replaces '&' character with 'amp;' string. So, we 
    // must replace 'amp;lt;span&amp;gt;' string with the original 
    // '&lt;span&gt;' string.
    $text = str_ireplace('&amp;', '&', $text);
    $text = str_ireplace('&lt;openingtag&gt;', '<openingtag>', $text);
    $text = str_ireplace('&lt;closingtag&gt;', '<closingtag>', $text);
    
    // Calculate the starting and ending positions of the new highlight mark,
    // but discard the characters corresponding to the previous highlight 
    // marks. This characters will be added in the digui_span_insert function.
    $text = preg_replace( '~<span("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $text);
    $text = str_replace( '</span>', '', $text);
    
    $selectionstart = stripos($text, '<openingtag>');
    $selectionend = stripos($text, '<closingtag>') - strlen('<openingtag>');
    
    $nextpageversion = $pageversion + 1;
    $newspanid = digui_span_get_new_id();    
    
    // Retrieve spans from database. If mode is collaborative, some spans could
    // overlapped each others, like when a user marks a text which has been
    // marked already.
    if ($diguimode == 'collaborative') {
        $oldspans = digui_span_get_by_diguiid($diguiid, $pagenum, $pageversion);
    }
    else {
        $oldspans = digui_span_get_by_subdiguiid($diguiid, $subdiguiid, $pagenum, $pageversion);
    }

    // Create the new span is being highlighted.
    $newspan = new stdClass();
    $newspan->pageversion = $nextpageversion;
    $newspan->diguiid = $diguiid;
    $newspan->subdiguiids = $subdiguiid + ''; 
    $newspan->pagenum = $pagenum; 
    $newspan->start = $selectionstart; 
    $newspan->end = $selectionend; 
    $color = digui_color_get_color_by_subdiguiid($subdiguiid);
    // $newspan->colorid = $color->identifier;
    $newspan->id = $newspanid++;    
        
    // Add the new span to the database.
    digui_span_add($newspan);
    
    // The newspan will be deleted from the database, at the end of this 
    // function. So, we must store its id in order to do the deletion.
    $provisionalspanid = $newspan->id;
    $oldspans[] = $newspan;

    // We'll use the substr_replace function to insert the spans in the
    // text, so we need to sort the spans by it position, from min to max. 
    // So, we create an associative array (the keys must be uniques).
    foreach($oldspans as $span) {
        $spansoffsets["start," . $span->id] = $span->start;
        $spansoffsets["end," . $span->id] = $span->end;
    }

    asort($spansoffsets);

    $arraykeys = array_keys($spansoffsets);
    $arrayvalues = array_values($spansoffsets);

    // The asort function may sort the array as show in the next example:
    // 
    // [start,125] => (string) 20,
    // [start,126] => (string) 35,
    // [end,125] => (string) 35,
    // [start,127] => (string) 50,
    // [end,126] => (string) 50,
    // [end,127] => (string) 54,
    // [start,128] => (string) 59,
    // [end,128] => (string) 66
    //
    // You can see that the array values are sorted correctly (right side), but 
    // the keys are not sorted correctly (left side). The next code sort the 
    // array correctly, according the next example:
    // 
    // [start,125] => (string) 20,
    // [end,125] => (string) 35,
    // [start,126] => (string) 35,
    // [end,126] => (string) 50,
    // [start,127] => (string) 50,
    // [end,127] => (string) 54,      
    // [start,128] => (string) 59,
    // [end,128] => (string) 66

    // Sort is necessary only if there are more than one span. Remember 
    // that each span occupies two entries in the spanaux array.
    if (count($spansoffsets) > 2) {
        for ($i = 1, $j = count($spansoffsets) - 2; $i < $j; $i++) {
            // May happend that the end position of a span is equal to the 
            // start position of the next span, like (a,b)(c,d), with b=c.
            if ($arrayvalues[$i] == $arrayvalues[$i+1]) {
                // Retrieve data from two consecutive span. Extract digits 
                // only from string, to get the id of each span.
                preg_match('~[^a-zA-Z,]\d+~si', $arraykeys[$i-1], $match);
                $spanid1 = intval($match[count($match) - 1]);

                preg_match('~[^a-zA-Z,]\d+~si', $arraykeys[$i], $match);
                $spanid2 = intval($match[count($match) - 1]);

                //
                if ($spanid1 != $spanid2) {
                    //
                    $temp = $arrayvalues[$i];
                    $arrayvalues[$i] = $arrayvalues[$i+1];
                    $arrayvalues[$i+1] = $temp;

                    $temp = $arraykeys[$i];
                    $arraykeys[$i] = $arraykeys[$i+1];
                    $arraykeys[$i+1] = $temp;
                }
            }
        }
    }
        
    // Extract digits only from string, to get the id of the first span.
    preg_match('~[^a-zA-Z,]\d+~si', $arraykeys[0], $match);
    $spanid1 = intval($match[count($match) - 1]);
    
    // Extract letters only from string, to get the endpoint type ("start" or 
    // "end") of the next span. For example, if we have "start,01", 
    // preg_match extracts "start".
    preg_match('~^([A-Za-z]+)~si', $arraykeys[0], $match);
    $endpoint1 = $match[count($match) - 1];

    // The first span is always a candidate for a overlapping span.
    $stack = array();
    array_push($stack, $spanid1);
    
    for ($i = 1, $j = count($arraykeys); $i < $j; $i++) {

        // Extract digits only from string, to get the id of the next span.
        preg_match('~[^a-zA-Z,]\d+~si', $arraykeys[$i], $match);
        $spanid2 = $match[count($match) - 1];
        // Convert string to integer.
        $spanid2 = intval($spanid2);

        // Extract letters only from string, to get the endpoint type ("start" 
        // or "end") of the next span. For example, if we have "start,01", 
        // preg_match extracts "start".
        // See http://knowpapa.com/php-regex/.
        preg_match('~^([A-Za-z]+)~si', $arraykeys[$i], $match);
        $endpoint2 = $match[count($match) - 1];
        
        // Example: 1 2 2' 1', and $i variable points to 2. So, $endpoint1 is 
        // equal to "start" (because the 1 is a opening endpoint), and 
        // $endpoint2 is equal to "start" (because the 2 is a opening endpoint 
        // too). 
        // When $endpoint1 == $endpoint2, $i%2 == 1 always. If $i%2 == 0, then
        // there would be two overlapping spans, but this situation is not
        // possible, because there may be one overlapping span only.
        if ($endpoint1 == $endpoint2) {

            $span1 = digui_span_get_by_id($spanid1);
            $span2 = digui_span_get_by_id($spanid2);

            // Create a new span.
            $newspan = new stdClass();
            $newspan->pageversion = $nextpageversion;
            $newspan->diguiid = $diguiid;
            $newspan->pagenum = $pagenum; 
            $newspan->id = $newspanid++;
            
            // Example: 1 2 2' 1', and $i variable points to 2. So, $endpoint1 
            // is equal to "start" (because the 1 is a opening endpoint), and 
            // $endpoint2 is equal to "start" (because the 2 is a opening endpoint 
            // too). Other example: 1 2 1' 2', and $is points to 2.
            if ($endpoint2 == 'start') { 
                $newspan->subdiguiids = $span1->subdiguiids; 
                $newspan->start = $span1->start; 
                $newspan->end = $span2->start; 
                // $newspan->colorid = $span1->colorid;
            }
            // Example: 1 2 2' 1'. If $i variable points to 1', $endpoint1 
            // is equal to "end" (because the 3' is an ending endpoint), and 
            // $endpoint2 is equal to "endt" (because the 1' is an ending 
            // endpoint too). Other example: 1 2 1' 2', ant $i points to 2'.
            else { 
                $newspan->subdiguiids = $span2->subdiguiids; 
                $newspan->start = $span1->end; 
                $newspan->end = $span2->end; 
                // $newspan->colorid = $span2->colorid;
            }
            
            $newspans[] = $newspan;
        }
        // Example: 1 2 2' 1', and $i variable points to 2'. So, $endpoint1 is 
        // equal to "start" (because the 2 is a opening endpoint), and 
        // $endpoint2 is equal to "end" (because the 2' is an ending endpoint). 
        else { // $endpoint1 != $endpoint2
            // Example: 1 1' 2 2', and $i variable points to 2. So, $endpoint1 
            // is equal to "end" (because the 1' is an ending endpoint), and 
            // $endpoint2 is equal to "start" (because the 2 is a starting 
            // endpoint). 
            if ($endpoint2 == 'start') { 
                // Examples: 1 1' 2 2', with $i variable pointing to 2;
                // 1 1' 2 3 2' 3', with $i variable pointing to 2.
                if ($i%2 == 0) {
                    // When $endpoint2 == 'start' and i%2 == 0, we have not to do 
                    // anything. Examples of this:
                }
                // Example: 1 2 2'_3 3' 1'. $i variable points to 3, and the 
                // code below will paint a highlight mark between 2' and 3. The 
                // color of this span, will be the color of the (1 1') span.
                // Another example: 1 2 1'_3 2' 3', and $i variable points to
                // 3, and the code below will paint a highlight mark between 1' 
                // and 3. The color of this span, will be the color of the 
                // (2 2') span.
                else { // finished
                    $span1 = digui_span_get_by_id($spanid1);
                    $span2 = digui_span_get_by_id($spanid2);

                    // Create a new span.
                    $newspan = new stdClass();
                    $newspan->pageversion = $nextpageversion;
                    $newspan->diguiid = $diguiid;
                    $newspan->pagenum = $pagenum; 
                    $newspan->id = $newspanid++;

                    $overlappedspan = digui_span_get_by_id(end($stack));  
                    $newspan->subdiguiids = $overlappedspan->subdiguiids;
                    $newspan->start = $span1->end; 
                    $newspan->end = $span2->start; 
                    // $newspan->colorid = $overlappedspan->colorid;
                            
                    $newspans[] = $newspan;
                }
            }
            // Example: 1 1' 2 2', and $i variable points to 2'. So, $endpoint1 
            // is equal to "start" (because the 2 is a starting endpoint), and 
            // $endpoint2 is equal to "end" (because the 2' is an ending
            // endpoint). 
            // More examples: 1 1', with $i pointing to 1'; 1 1' 2 2', with $i 
            // pointing to 2'; 1 2 2' 1', with $i pointing to 2'; 
            // 1 2 2' 3 3' 1' with $i pointing to 3'; etc. 
            else { // $endpoint1 != $endpoint2 and $endpoint2 == 'end':
                $span1 = digui_span_get_by_id($spanid1);
                $span2 = digui_span_get_by_id($spanid2);

                // Create a new span.
                $newspan = new stdClass();
                $newspan->pageversion = $nextpageversion;
                $newspan->diguiid = $diguiid;
                $newspan->pagenum = $pagenum; 
                $newspan->id = $newspanid++;
                
                // $i%2 == 0 means that there are one span overlapping another 
                // span. For example: 2 1 2' 1', and $i value is 2, so $i 
                // points to 2'. 
                // The code below will paint a highlight mark between 1 and 2'. 
                // The color of this span, will be the color of the (2 2') span 
                // plus the color of the (1 1') span. The $overlappedspan 
                // variable will contains the (2 2') span.
                if ($i%2 == 0) {
                    
                    $newspan->start = $span1->start; 
                    $newspan->end = $span2->end; 
                    
                    // The last span being added, has the bigger id. This span
                    // is the overlapping span, a span that is over others. The
                    // overlapped span is the another one with the smaller id.
                    if ($span1->id > $stack[0]) {
                        $overlappedspan = digui_span_get_by_id($stack[0]);
                        $newspan->subdiguiids = digui_span_include($subdiguiid, $overlappedspan->subdiguiids); 
                        // $newspan->colorid = digui_color_get_color_sum($overlappedspan->colorid, $subdiguiid, $overlappedspan->subdiguiids);
                    }
                    else {
                        $overlappedspan = $span1;
                        $newspan->subdiguiids = digui_span_include($subdiguiid, $overlappedspan->subdiguiids); 
                        // $newspan->colorid = digui_color_get_color_sum($overlappedspan->colorid, $subdiguiid, $overlappedspan->subdiguiids);
                    }
                }
                // $i%2 == 1 means that there are not spans overlapping. For 
                // example: 1 1' 2 2', and $i value is 3, so $i points to 2'. 
                else {
                    $newspan->subdiguiids = $span1->subdiguiids; 
                    $newspan->start = $span1->start;
                    $newspan->end = $span2->end; 
                    // $newspan->colorid = $span1->colorid;
                }
                
                // Add the new span to the spans array.
                $newspans[] = $newspan;
            }
        }

        // Store each opening span to a variable. This spans may overlapp 
        // others spans. 
        $i = $i;
        if ($endpoint2 == 'start') {
            array_push($stack, $spanid2);
        }
        else {
            $key = array_search($spanid2, $stack);
            array_splice($stack, $key, 1);
        }

        $spanid1 = $spanid2;
        $endpoint1 = $endpoint2;
    }
    
    // Delete the new span from the database.
    digui_span_delete_by_id($provisionalspanid);
    
    // Insert new spans in database.
    foreach ($newspans as $span) {
        // $arrayvalues may contain something like this:
        // [0] => 4, [1] => 15, [2] => 18, [3] => 18, [4] => 22, where values
        // at position 2 and 3 are equals. In order to avoid inserting 
        // empty span tags in the database, we must discard them.
        if ($span->start != $span->end) {
            digui_span_add($span);
        }
    }
    
    return $nextpageversion;
}

/**
 * Delete a record from a database table.
 * @param array $spanid, id of the element to delete.
 */

function digui_span_delete_by_id($spanid) {
    global $DB;
    $DB->delete_records('digui_spans', array('id' => $spanid));
}

/**
 * Delete a record from the mdl_digui_spans table.
 * @param array $spanid, id of the element to delete.
 */
function digui_span_delete_by_version_greater_than($diguiid, $subdiguiid = NULL, $pagenum, $pageversion) {
    global $DB;
   
    if (is_null($subdiguiid)) {
        $sqlaux = "diguiid = ? AND pagenum = ? AND pageversion > ?";
        $DB->delete_records_select("digui_spans", $sqlaux, array($diguiid, $pagenum, $pageversion));
    }
    else {
        $spans = digui_span_get_by_diguiid($diguiid, $pagenum, $pageversion);

        if (is_null($spans) || count($spans) == 0) {
            return;
        }

        // May it be one span belong to different users. So, we search the
        // value of $subdiguiid in the subdiguiids field.
        foreach ($spans as $span) {
            if (digui_span_is_included($subdiguiid, $span->subdiguiids) &&
                    $span->diguiid == $diguiid && 
                    $span->pagenum == $pagenum && 
                    $span->pageversion > $pageversion) {
                $sqlaux = "id = ?";
                $DB->delete_records_select("digui_spans", $sqlaux, array($span->id));
            }            
        }        
    }  
}

/**
 * Delete a record from a database table.
 * @param array $spanid, id of the element to delete.
 */
function digui_span_delete_by_version_less_than($diguiid, $subdiguiid = NULL, $pagenum, $pageversion) {
    global $DB;
    
    if (is_null($subdiguiid)) {
        $sqlaux = "diguiid = ? AND pagenum = ? AND pageversion < ?";
        $DB->delete_records_select("digui_spans", $sqlaux, array($diguiid, $pagenum, $pageversion));
    }
    else {
        $spans = digui_span_get_by_diguiid($diguiid, $pagenum, $pageversion);

        if (is_null($spans) || count($spans) == 0) {
            return;
        }

        // May it be one span belong to different users. So, we search the
        // value of $subdiguiid in the subdiguiids field.
        foreach ($spans as $span) {
            if (digui_span_is_included($subdiguiid, $span->subdiguiids) &&
                    $span->diguiid == $diguiid && 
                    $span->pagenum == $pagenum && 
                    $span->pageversion < $pageversion) {
                $sqlaux = "id = ?";
                $DB->delete_records_select("digui_spans", $sqlaux, array($span->id));
            }            
        }        
    }  
}

/**
 * less than $pageversion number.
 * Delete a record from a database table.
 * @param array $spanid, id of the element to delete.
 */
function digui_span_get_by_subdiguiid($diguiid, $subdiguiid, $pagenum, $pageversion) {
    global $DB;

    if ($pageversion < 0) {
        return array();
    }

    $spans = digui_span_get_by_diguiid($diguiid, $pagenum, $pageversion);

    if (is_null($spans) || count($spans) == 0) {
        return null;
    }
    
    // May it be one span belong to different users. So, we search the
    // value of $subdiguiid in the subdiguiids field.
    $spans_aux = array();
    foreach ($spans as $span) {
        if (digui_span_is_included($subdiguiid, $span->subdiguiids)) {
            $spans_aux[] = $span;
        }            
    }
    
    return $spans_aux;
}

/**
 * Delete a record from a database table.
 * @param array $spanid, id of the element to delete.
 */
function digui_span_get_by_diguiid($diguiid, $pagenum, $pageversion) {
    global $DB;

    if ($pageversion < 0) {
        return array();
    }
    
    if ($pageversion == 0) {
        $params = array('diguiid'=>$diguiid, 'pagenum'=>$pagenum);
        $sql = "SELECT * "
            . "FROM {digui_spans} "
            . "WHERE diguiid =:diguiid AND "
            . "pagenum =:pagenum";
        $records = $DB->get_records_sql($sql, $params);
    }
    else {
        $params = array('diguiid'=>$diguiid, 'pagenum'=>$pagenum, 'pageversion'=>$pageversion);
        $sql = "SELECT * "
                . "FROM {digui_spans} "
                . "WHERE diguiid =:diguiid AND "
                . "pagenum =:pagenum AND "
                . "pageversion =:pageversion";
        $records = $DB->get_records_sql($sql, $params);
    }
    
    return $records;
}

/**
 * Delete a record from a database table.
 * @param array $spanid, id of the element to delete.
 */
function digui_span_get_by_id($id) {
    global $DB;

    $params = array('id'=>$id);
    $sql = "SELECT * "
            . "FROM {digui_spans} "
            . "WHERE id =:id";
    $record = $DB->get_record_sql($sql, $params);
    
    return $record;
}

function digui_span_get_new_id() {
    global $DB;

    $sql = "SELECT COUNT(*) FROM {digui_spans}";
    $n = $DB->get_field_sql($sql);
    if ($n == 0) {
        return 100;
    }
    
    // $sql = "SELECT MAX(id) FROM {digui_spans}";
    $sql = "SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'moodle' AND TABLE_NAME = '{digui_spans}'";
    $value = $DB->get_field_sql($sql);
    return $value;
}

/**
 *
 * @param string $text, text that contains characters to convert.
 * @return string, text with characters converted.
 */ 
function digui_span_to_html($span) {  
    $tag = '<span style="background-color: #';
    // $color = digui_color_get_color_by_id($span->colorid);
    $color = digui_color_get_color_by_subdiguiids($span->subdiguiids);
    $tag .= $color->backcolor;
    $tag .= '; color: #';
    $tag .= $color->forecolor;
    $tag .= '" title="';
    $subdiguiids = $span->subdiguiids;
    $subdiguiidlist = explode(',', $subdiguiids);
    $spersonalinformation = '';
    foreach ($subdiguiidlist as $subdiguiid) {
        if (!$subdigui = digui_subdigui_get_subdigui_by_id($subdiguiid)) {
            print_error('incorrectsubdiguiid', 'digui');
        }
        
        $userinformation = digui_user_get_user_information($subdigui->userid);
        $spersonalinformation .= $userinformation->username;
        $spersonalinformation .= " (";
        $spersonalinformation .= $userinformation->firstname;
        $spersonalinformation .= " ";
        $spersonalinformation .= $userinformation->lastname;
        $spersonalinformation .= "); ";        
    }
    
    $tag .= $spersonalinformation;
    // $tag .= '"; ids="';
    // $tag .= $span->subdiguiids;
    $tag .= '" id="';
    $tag .= $span->id;
    $tag .= '">';

    // Moodle Database may contains non ASCII characters (like the 
    // $userinformation->username field), which will form part of the Digui 
    // database from now.
    $tag = fm_htmlentities_ex($tag);
    
    return $tag;
}

/**
 * Add a subdigui id to a list of subdigui ids.
 * @param int $subdiguiid, subdigui id to be added.
 * @param string $subdiguiids, list of subdigui ids.
 * @return string, a list of subdigui ids.
 */
function digui_span_include($subdiguiid, $subdiguiids) {
    if (digui_span_is_included($subdiguiid, $subdiguiids)) {
        return $subdiguiids;
    }
    
    return $subdiguiid .','. $subdiguiids;
}

/**
 * 
 * @param string $text, text that contains the tags.
 * @param string $color, color of the html tags.
 * @return string, a highlighted text.
 */
function digui_span_add($span) {

    global $DB;    
   
    $sql = "SELECT COUNT(*) FROM {digui_spans}";
    $n = $DB->get_field_sql($sql);
    if ($n == 0) {
        $sql = "ALTER TABLE {digui_spans} AUTO_INCREMENT = 100";
        $DB->execute($sql);
    }
    $insertid = $DB->insert_record('digui_spans', $span);
}

/**
 * Search for a subdigui id in a set of subdigui ids.
 * @param int $subdiguiid, id of subdigui to be found. 
 * @param int $subdiguiids, set of subdigui ids
 * @return bool
 */
function digui_span_is_included($subdiguiid, $subdiguiids) {
    $subdiguiidlist = explode(',', $subdiguiids);
    foreach ($subdiguiidlist as $subdiguiidaux) {
        if ($subdiguiid == $subdiguiidaux) {
            return true;
        }
    }
    return false;
}

/*
 * Insert span tags in the text, to make a highlighting effect. 
 * This function has two phases: the first phase, consist on inserting the span
 * tags in the text. The second phase, consist on inserting additional span
 * tags, if one or more span tags englobe some html tags.
 * 
 * @param string $text, text in which insert span tags. 
 * @param array $spans, set of span tags to insert.
 * @return string, the text with span tags inserted.
 */
function digui_span_insert($text, $spans) {
   
    //
    // Phase one: insert span tags in the text.
    //
    if (!is_null($spans) && count($spans) > 0) {

        // We'll use the substr_replace function to insert the spans in the
        // text, so we need to order the spans by it position, from min to max. 
        // So, we create an associative array (the keys must be uniques).
        foreach($spans as $span) {
            $spansaux["start," . $span->id] = $span->start;
            $spansaux["end," . $span->id] = $span->end;
            // $text = digui_str_add_span_tag($text, $span);
        }

        // Order the spans by value (its position).
        asort($spansaux);
        
        // Maybe, the end position of a span, is equal to the start position of 
        // the next span, like (a,b)(c,d), where b=c. In this case, the 
        // substr_replace function insert the spans in inverse (and incorrect) 
        // order, like (a,c)(b,d). To solve it, we must swap the spans involved,
        // b and c.
        $arraykeys = array_keys($spansaux);
        $arrayvalues = array_values($spansaux);
        
        // Sort is necessary only if there are more than one span. Remember 
        // that each span occupies two entries in the spanaux array.
        if (count($spansaux) > 2) {
            for ($i = 1, $j = count($spansaux) - 2; $i < $j; $i++) {
                // May happend that the end position of a span is equal to the 
                // start position of the next span, like (a,b)(c,d), with b=c.
                if ($arrayvalues[$i] == $arrayvalues[$i+1]) {
                    // Retrieve data from two consecutive span. Extract digits 
                    // only from string, to get the id of each span.
                    preg_match('~[^a-zA-Z,]\d+~si', $arraykeys[$i-1], $match);
                    $spanid1 = intval($match[count($match) - 1]);
                    
                    preg_match('~[^a-zA-Z,]\d+~si', $arraykeys[$i], $match);
                    $spanid2 = intval($match[count($match) - 1]);
                    
                    //
                    if ($spanid1 != $spanid2) {
                        //
                        $temp = $arrayvalues[$i];
                        $arrayvalues[$i] = $arrayvalues[$i+1];
                        $arrayvalues[$i+1] = $temp;
                        
                        $temp = $arraykeys[$i];
                        $arraykeys[$i] = $arraykeys[$i+1];
                        $arraykeys[$i+1] = $temp;
                    }
                }
            }
        }
        // foreach($spansaux as $key => $value) {
        $offset = 0;
        for ($i = 0, $j = count($spansaux); $i < $j; $i++) {
            $key = $arraykeys[$i];
            $value = $arrayvalues[$i];
            
            $keyparts = explode(',', $key);
            if ($keyparts[0] == "start") {
                // $span = digui_span_get_by_id($keyparts[1]);
                foreach($spans as $span) {
                    if ($span->id == $keyparts[1]) { 
                        $spantohtml = digui_span_to_html($span);
                        // Some data from database, like names or surnames, 
                        // may contains non ascii characters.
                        $spantohtml = fm_htmlentities_ex($spantohtml);
                        break;
                    }
                }
            }
            else {
                $spantohtml = "</span>";
            }
            
            $text = substr_replace($text, $spantohtml, $value + $offset, 0);
            $offset += strlen($spantohtml);
        }
    }
    
    //
    // Phase two: may happen that a span tag englobe one ore more html tags,
    // for example, <span> de</h1>berímos </span>. In this case, we must insert
    // some additional span tags, like this: 
    // <span> de </span></h1><span>berímos </span>.
    //
    
    $numchars = strlen($text);
    $newtext = '';
    $i = 0;
    while (($token = read_token($text, $numchars, $i)) != '') {
        $newtext .= $token;
        if (preg_match('/<span("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1) {
            $spanaux = $token;
            while (($token = read_token($text, $numchars, $i)) != '</span>' && $token != '') {
                if (digui_is_html_start_tag($token) || digui_is_html_end_tag($token)) {
                    $newtext .= '</span>';
                    $newtext .= $token;
                    $newtext .= $spanaux;
                }
                else {
                    $newtext .= $token;
                }
            }
            // Append the '</span>' string.
            $newtext .= $token;
        }
    }

    //
    // Phase three: delete empty tag spans, like <span style="background-color: #006600; color: #ffffff" title="admin (Admin Usuario); " id="100"></span>.
    //

    $newtext = preg_replace('~<span("[^"]*"|\'[^\']*\'|[^\'">])*></span>~si', '', $newtext);
    
    return $newtext;
}

/**
 * Assign the same color to several spans.
 * 
 * @param array $spans, span tags to be modified.
 * @param int $subdiguiid, user id whose color will be assigned.
 * @return array, spans tags with setted colors.
 */
function digui_span_colorize($spans, $subdiguiid) {
    $newspans = array();
    if (!is_null($spans) && count($spans) > 0) {
        // $color = digui_color_get_color_by_subdiguiid($subdiguiid);
        foreach($spans as $span) {
            $span->subdiguiids = $subdiguiid;
            $newspans[] = $span;
        }
    }
    return $newspans;
}

/**
 * This function does two operations: 
 * - First, delete consecutive span tags from text. For example, the string
 * "<span>This</span><span> text</span>", is transformed in a
 * "<span>This text</span>" string. 
 * - Second, delete span tags which contains blank characters only. For example, 
 * the string "This <span> </span> text", is transformed in a "This text" 
 * string. 
 * 
 * @param array $text, string containing the span tags.
 * @return string, text with modified span tags.
 */
function digui_span_join($text) {
    // Remove consecutive span tags.
    $text = preg_replace('~</span><span("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $text);
    // Remove span tags containing blank characters only.
    // TODO: This step should be done in the digui_span_new function, to avoid
    // inserting this span tags in the database.
    $text = preg_replace('~<span("[^"]*"|\'[^\']*\'|[^\'">])*>\s+</span>~si', '', $text);
    return $text;
}

/**
* Delete elements from array, except the elements whose id is equal to an 
* identifier.
* @param array $spans, array of elements.
* @param int $subdiguiid, identifier.
* @return array.
*/
function digui_span_remove_by_subdiguiid($spans, $subdiguiid) {
    $pattern = '/(.*)'. $subdiguiid .'(.*)/i';
    
    for ($i = 0, $j = count($spans); $i < $j; $i++) {
        
        $span = $spans[$i];
        $subdiguiids = $span->subdiguiids;
        
        if (preg_match($pattern, $subdiguiids) != 1) {
            $spans = digui_array_remove_by_index($spans, $i);
            $j = count($spans);
            $i--;
        }
    }
    
    return $spans;
}

/**
* Load a count of newline characters in a text.
* @param string $text, text where search for.
* @return int number of newline characters found.
*/
function digui_str_count_newlines($text) {

    $poszero = 0;
    $ocurrences = 0;
    while (($pos = strpos($text, PHP_EOL, $poszero)) !== FALSE) {
        $ocurrences++;
        $poszero = $pos;
    }
    
    return $ocurrences;
}

/**
* Read characters from a html document until 4000 characters have been read.
* @param string $text, text to read.
* @param stack, array where we store html tags that need to be closed (for 
* example, <table> needs to be close with </table> tag). A tag need to be 
* closed, when we read a open tag (like <table>), and after that we read 4000
* characters without the tag have been closed. Remember that after reading 4000
* characters, reading is interrupted.
* @param $start, index into text of current read position.
* @return string, 4000 characters read from text.
*/
function digui_str_read_one_html_page($text, $numchars, & $stack, & $start) {
    
    if ($start < 0 || $start > $numchars - 1)
        return "";
    
    // The Finish reading  is equal to the begin plus 4000 characters. 4000 
    // characters because one page contains 4000 characters approximately. 
    $start + 4000 > $numchars - 1 ? $limit = $numchars : $limit =  $start + 4000;
    
    $textaux = '';
    // Force to read at least $limit characters.
    while ($start < $limit && ($token = read_token($text, $numchars, $start)) != '') {

        // The token just read, is a HTML opening tag.
        if (preg_match('~<("[^"]*"|\'[^\']*\'|[^\'">/])*>~si', $token, $match) == 1) {
            // We save every open tag.
            array_push($stack, $token);
        }
        // Instead, the token just read is a HTML closing tag.
        else if (preg_match('~</[^>]*>~si', $token, $match) == 1) {
            array_pop($stack);
        }
        
        $textaux .= $token;
        }
    
    return $textaux;
}

/**
* Read characters from a txt file until 4000 characters have been read.
* @param string $text, text to read.
* @param numchars, amount of characters of $text.
* @param $start, index into text of current read position.
* @return string, 4000 characters read from text.
*/
function digui_str_read_one_txt_page($text, $numchars, & $start) {
    
    if ($start < 0 || $start > $numchars - 1)
        return "";
    
    // The Finish reading  is equal to the begin plus 4000 characters. 4000 
    // characters because one page contains 4000 characters approximately. 
    $start + 4000 > $numchars - 1 ? $limit = $numchars : $limit =  $start + 4000;
    
    // Force to read at least $limit characters.
    $textaux = substr($textaux, $start, $limit);
    
    $start = $limit;
            
    return $textaux;
}

/**
* Read a word from text.
* @param string $buffer, text to read.
* @param string $patron, search pattern defined.
* @param $i, index into text of current read position.
* @return string, a word read from text.
*/
function digui_str_read_word($buffer, & $i) {
    $j = strlen($buffer);
    $token = '';
    
    if ($i >= $j)
        return $token;
    
    if ($buffer[$i] == ' ') {
        while ($i < $j && $buffer[$i] == ' ') {
           $token .= $buffer[$i];
           $i++;
        }
    }
    else if ($buffer[$i] == PHP_EOL) {
        $token .= $buffer[$i];
        $i++;
    }
//    else if ($j - $i >= 5 && $buffer[$i] == '(' && $buffer[$i+1] == '.' && $buffer[$i+2] == '.' && $buffer[$i+3] == '.' && $buffer[$i+4] == ')') {
//        $token = "(...)";
//        $i += 5;
//    }
    else {
        while ($i < $j && $buffer[$i] != ' ' && $buffer[$i] != PHP_EOL) {
           $token .= $buffer[$i];
           $i++;
        }
    }
    return $token;
}

/**
 * Add a new sub digui instance
 * @param int $diguiid
 * @param int $groupid
 * @param int $userid
 * @return int $insertid
 */
function digui_subdigui_add_subdigui($diguiid, $groupid, $userid = 0) {
    global $DB;

    $record = new StdClass();
    $record->diguiid = $diguiid;
    $record->groupid = $groupid;
    $record->userid = $userid;

    $insertid = $DB->insert_record('digui_subdiguis', $record);
    return $insertid;
}

/**
 * Get a sub digui instance by digui id and group id
 * @param int $diguiid, the instance id of digui.
 * @param int $groupid
 * @return object
 */
function digui_subdigui_get_subdigui_by_group($diguiid, $groupid, $userid = 0) {
    global $DB;
    return $DB->get_record('digui_subdiguis', array('diguiid' => $diguiid, 'groupid' => $groupid, 'userid' => $userid));
}

/**
 * Get a sub digui instace by instance id
 * @param int $subdiguiid, id of subdigui.
 * @return object
 */
function digui_subdigui_get_subdigui_by_id($subdiguiid) {
    global $DB;
    return $DB->get_record('digui_subdiguis', array('id' => $subdiguiid));

}

/**
 * Get a sub digui instance by digui id and group id
 * @param int $diguiid, the instance id of digui.
 * @param int $groupid
 * @return object
 */
function digui_subdigui_get_subdigui_by_user($diguiid, $userid) {
    global $DB;
    return $DB->get_record('digui_subdiguis', array('diguiid' => $diguiid, 'userid' => $userid));
}

/**
 * Summarize a text based on HTML tags.
 * @param string $text, text to summarize.
 * @return string, text summarized.
 */
function digui_str_summarize_text($text) {

    $bufferAux = '';
    preg_match_all('~<span("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</span>~si', $text, $match);
    for($i = 0, $j = count($match) - 1, $k = count($match[$j]); $i < $k; $i++) {
        $bufferAux .= $match[$j][$i];
        $bufferAux .= " (...) ";
    }
    return $bufferAux;
}

/**
 * Check that a user has edited or is currently editing a digui.
 * @param int $diguiid the instance id of digui
 * @param int $groupid, group id
 * @param int $userid, user id
 */
function digui_user_get_edition_status_for_user($diguiid, $groupid, $userid) {
    if ((!$subdigui = digui_subdigui_get_subdigui_by_group($diguiid, $groupid, $userid)) &&
        (!$subdigui = digui_subdigui_get_subdigui_by_group($diguiid, 0, $userid)) &&
            (!$subdigui = digui_subdigui_get_subdigui_by_user($diguiid, $userid))) {
        return false;
    }
    
    return true;
}

/**
 * Get users which are editing the digui.
 * @param int $diguiid, the instance id of digui.
 * @return object
 */
function digui_user_get_users_by_digui($diguiid) {
    global $DB;
    
    $params = array('diguiid'=>$diguiid);
    $sql = "SELECT id, userid "
            . "FROM mdl_digui_subdiguis "
            . "WHERE diguiid =:diguiid";
    $subdiguis = $DB->get_records_sql($sql, $params);
    return $subdiguis;
}

/**
 * Retrieve information about an user.
 * @param int $userid.
 * return string, information about an user.
 */
function digui_user_get_user_information($userid) {
    global $DB;

    $params = array('userid' => $userid);
    $sql = "SELECT username, firstname, lastname, email "
            . "FROM mdl_user "
            . "WHERE id =:userid";

    return $DB->get_record_sql($sql, $params);
}

/**
 * Check that user has assigned teacher role, for a given course id.
 * @param string $buffer, text to underline.
 * @param int $userid.
 * @param int $courseid, course id.
 */
function digui_user_is_teacher_role($userid, $courseid) {
    global $DB;
    $teacherrole = $DB->get_record('role', array('shortname'=>'teacher'));
    $editingteacher = $DB->get_record('role', array('shortname'=>'editingteacher'));

    if ($DB->record_exists('role_assignments', array('contextid'=>context_course::instance($courseid)->id, 'roleid'=>$teacherrole->id, 'userid'=>$userid)) ||
        $DB->record_exists('role_assignments', array('contextid'=>context_course::instance($courseid)->id, 'roleid'=>$editingteacher->id, 'userid'=>$userid))) {
        return true;
        }
    return false;
}

/**
 * Get the current page version of a digui page. 
 * Each digui page has a version number. We must set the version number of a 
 * page, every time the user do any modification on the page. This number is 
 * useful to implement de undo/redo funcionalities of digui.
 * Two tables in the database have a version column: the digui_spans table and
 * the digui_page_version. Every time a page is displayed, only span tags whose
 * version is equal to the version in the digui_page_version, are shown. A span
 * tag can have version values higher, equal or lower than the current version 
 * value of the digui_page_version table.
 * @param int $diguiid, id of digui instance object.
 * @param int $subdiguiid, id of subdigui whose registry will be got. 
 * @param int $pagenum, page whose registry will be got.
 */
/*
function digui_version_get_current($diguiid, $subdiguiid = NULL, $pagenum) {
    global $DB;
    
    if (is_null($subdiguiid)) {
        $params = array('diguiid' => $diguiid, 'pagenum' => $pagenum);
        $sql = "SELECT pageversion "
                . "FROM {digui_page_version} "
                . "WHERE diguiid =:diguiid AND pagenum =:pagenum";
    }
    else {
        $params = array('diguiid' => $diguiid, 'subdiguiid' => $subdiguiid, 'pagenum' => $pagenum);
        $sql = "SELECT pageversion "
                . "FROM {digui_page_version} "
                . "WHERE diguiid =:diguiid AND subdiguiid =:subdiguiid AND pagenum =:pagenum";
    }
    
    $pageversion = $DB->get_field_sql($sql, $params);
    return $pageversion ? $pageversion : 0;
}*/
function digui_version_get_current($subdiguiid, $pagenum) {
    global $DB;
    
    $params = array('subdiguiid' => $subdiguiid, 'pagenum' => $pagenum);
    $sql = "SELECT pageversion "
            . "FROM {digui_page_version} "
            . "WHERE subdiguiid =:subdiguiid AND pagenum =:pagenum";

    
    $pageversion = $DB->get_field_sql($sql, $params);
    return $pageversion ? $pageversion : 0;
}

/**
 * Get the maximum page version of a digui page. 
 * Each digui page has a version number. We must set the version number of a 
 * page, every time the user do any modification on the page. This number is 
 * useful to implement de undo/redo funcionalities of digui.
 * Two tables in the database have a version column: the digui_spans table and
 * the digui_page_version. Every time a page is displayed, only span tags whose
 * version is equal to the version in the digui_page_version, are shown. A span
 * tag can have version values higher, equal or lower than the current version 
 * value of the digui_page_version table.
 * @param int $diguiid, id of digui instance object.
 * @param int $pagenum, page whose registry will be got.
 */
function digui_version_get_maximum($subdiguiid, $pagenum) {
    global $DB;

    $params = array('pagenum' => $pagenum, 'subdiguiid' => $subdiguiid);
    $sql = "SELECT MAX(pageversion) "
            . "FROM {digui_spans} "
            . "WHERE pagenum =:pagenum AND subdiguiid =:subdiguiid";

    $pageversion = $DB->get_field_sql($sql, $params);
    return $pageversion ? $pageversion : 0;
}

/**
 * Get the last time when the user modified a page of a digui. 
 * We must register the user activity, in order to establish if a user need to 
 * be graded.
 * @param int $subdiguiid, id of subdigui whose registry will be got. 
 * @param int $pagenum, page whose registry will be got.
 */
function digui_version_get_last_time_modification($subdiguiid, $pagenum = null) {
    global $DB;

    if ($pagenum == null) {
        $params = array('subdiguiid' => $subdiguiid);
        $sql = "SELECT timemodified "
            . "FROM {digui_last_user_modification} "
            . "WHERE subdiguiid =:subdiguiid";
    }
    else {
        $params = array('subdiguiid' => $subdiguiid, 'pagenum' => $pagenum);
        $sql = "SELECT timemodified "
                . "FROM {digui_last_user_modification} "
                . "WHERE subdiguiid =:subdiguiid && pagenum =:pagenum";
    }
    
    return $DB->get_field_sql($sql, $params);
}

/**
 * Set the page version of a digui to 1. 
 * Modifies two tables, digui_spans and digui_page_version tables.
 * Each digui page has a version number. We must set the version number of a 
 * page, every time the user do any modification on the page. This number is 
 * useful to implement de undo/redo funcionalities of digui.
 * Two tables in the database have a version column: the digui_spans table and
 * the digui_page_version. Every time a page is displayed, only span tags whose
 * version is equal to the version in the digui_page_version, are shown. A span
 * tag can have version values higher, equal or lower than the current version 
 * value of the digui_page_version table.
 * @param int $diguiid, id of digui instance object.
 * @param int $subdiguiid, id of subdigui whose registry will be got. 
 * @param int $pagenum, page whose registry will be got.
 */
function digui_version_reset($diguiid, $subdiguiid = NULL, $pagenum) {
    global $DB;
    
    // Reset the version number.
    if (is_null($subdiguiid)) {
        $DB->set_field_select("digui_spans", "pageversion", 1, "diguiid = $diguiid AND pagenum = $pagenum");
        
        $subdiguis = digui_user_get_users_by_digui($diguiid);
        foreach ($subdiguis as $subdigui) {
            $DB->set_field_select("digui_page_version", "pageversion", 1, "subdiguiid = $subdigui->id AND pagenum = $pagenum");
        }
    }
    else {
        $spans = digui_span_get_by_diguiid($diguiid, $pagenum, 0);

        if (is_null($spans) || count($spans) == 0) {
            return;
        }

        // May it be one span belong to different users. So, we search the
        // value of $subdiguiid in the subdiguiids field.
        foreach ($spans as $span) {
            if (digui_span_is_included($subdiguiid, $span->subdiguiids) &&
                    $span->diguiid == $diguiid && 
                    $span->pagenum == $pagenum) {
                $DB->set_field_select("digui_spans", "pageversion", 1, "id = $span->id");
            }            
        }
        
        $DB->set_field_select("digui_page_version", "pageversion", 1, "subdiguiid = $subdiguiid AND pagenum = $pagenum");
        // $DB->set_field_select("digui_page_version", "pageversion", 1, "diguiid = $diguiid AND subdiguiid = $subdiguiid AND pagenum = $pagenum");        
    }
}

/**
 * Save the version page of a digui. 
 * Each digui page has a version number. We must set the version number of a 
 * page, every time the user do any modification on the page. This number is 
 * useful to implement de undo/redo funcionalities of digui.
 * Two tables in the database have a version column: the digui_spans table and
 * the digui_page_version. Every time a page is displayed, only span tags whose
 * version is equal to the version in the digui_page_version, are shown. A span
 * tag can have version values higher, equal or lower than the current version 
 * value of the digui_page_version table.
 * @param int $diguiid, id of digui instance object.
 * @param int $subdiguiid, id of subdigui whose registry will be got. 
 * @param int $pagenum, page whose registry will be got.
 * @param int $pageversion, versio number to insert in the database. 
 */
function digui_version_save_number($diguiid, $subdiguiid = NULL, $pagenum, $pageversion) {
    global $DB;
 
    if (is_null($subdiguiid)) {
        $subdiguis = digui_user_get_users_by_digui($diguiid);
        foreach ($subdiguis as $subdigui) {
            if (!($record = $DB->get_record('digui_page_version', array('subdiguiid' => $subdiguiid, 'pagenum' => $pagenum)))) {
                // Create a new record.
                $record = new StdClass();
                // $record->diguiid = $diguiid;
                // TODO: replace this instruction, with $record->subdiguiid = NULL;
                $record->subdiguiid = $subdigui->id;
                $record->pagenum = $pagenum;
                $record->pageversion = $pageversion;
                $insertid = $DB->insert_record('digui_page_version', $record);
            }
            else {
                // Update a existing record.
                $record->pageversion = $pageversion;
                $DB->update_record('digui_page_version', $record);        
            }
        }
    }
    else {
        if (!($record = $DB->get_record('digui_page_version', array('subdiguiid' => $subdiguiid,'pagenum' => $pagenum)))) {
            // Create a new record.
            $record = new StdClass();
            $record->diguiid = $diguiid;
            $record->subdiguiid = $subdiguiid;
            $record->pagenum = $pagenum;
            $record->pageversion = $pageversion;
            $insertid = $DB->insert_record('digui_page_version', $record);
        }
        else {
            // Update a existing record.
            $record->pageversion = $pageversion;
            $DB->update_record('digui_page_version', $record);        
        }
    }
}

/**
 * Register the last time when the user modified a page of a digui. 
 * We must register the user activity, in order to establish if a user need to 
 * be graded.
 * @param int $diguiid, id of digui instance object.
 * @param int $pagenum, page whose registry will be updated.
 * @param int $subdiguiid, id of subdigui whose registry will be updated. 
 * @param int $time, time to insert in the database. 
 */
function digui_version_save_last_time_modification($diguiid, $pagenum, $subdiguiid, $time) {
    global $DB;
 
    // If a page is not provided, update the time of all user's pages.
    if ($pagenum == null) {
        $sql = "UPDATE {digui_last_user_modification} 
            SET timemodified = ? 
            WHERE subdiguiid = ?";
        
        $DB->execute ($sql, array($time, $diguiid, $subdiguiid));
    }
    // A page is provided, so, update the time of this page only.
    else {
        if (!($record = $DB->get_record('digui_last_user_modification', array('subdiguiid' => $subdiguiid, 'pagenum' => $pagenum)))) {
            // Create a new record.
            $record = new StdClass();
            // $record->diguiid = $diguiid;
            $record->pagenum = $pagenum;
            $record->subdiguiid = $subdiguiid;
            $record->timemodified = $time;
            $insertid = $DB->insert_record('digui_last_user_modification', $record);
        }
        else {
            // Update a existing record.
            $record->subdiguiid = $subdiguiid;
            $record->timemodified = $time;
            $DB->update_record('digui_last_user_modification', $record);        
        }
    }
}