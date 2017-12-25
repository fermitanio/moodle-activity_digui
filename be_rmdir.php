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

 /**
 * Delete a directory and files and subdirectoies in it.
 * 
 * @param string $path, directory to delete
 * @author Brandon Elliott.
 *
 * @license http://directory.fsf.org/wiki/License:X11 MIT
 * 
 */
function be_rmdir($path) {
    // The preg_replace is necessary in order to traverse certain types of folder paths (such as /dir/[[dir2]]/dir3.abc#/)
    // The {,.}* with GLOB_BRACE is necessary to pull all hidden files (have to remove or get "Directory not empty" errors)
    $files = glob(preg_replace('/(\*|\?|\[)/', '[$1]', $path).'/{,.}*', GLOB_BRACE);
    
    if (is_null($files) || count($files) == 0) {
        return;
    }
    
    foreach ($files as $file) {
        if ($file == $path.'/.' || $file == $path.'/..') { continue; } // skip special dir entries
        is_dir($file) ? be_rmdir($file) : unlink($file);
    }
    rmdir($path);
    return;
}