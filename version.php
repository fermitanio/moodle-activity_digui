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
 * Code fragment to define the version of digui
 * This fragment is called by moodle_needs_upgrading() and /admin/index.php
 *
 * @package mod-digui-1.0
 * @copyrigth 2016 Fernando Martin fermitanio@hotmail.com
 * @author Fernando Martin
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @see https://docs.moodle.org/dev/version.php
 */


defined('MOODLE_INTERNAL') || die();

global $CFG;

if ($CFG->version > 2012120311) {          // Moodle 2.4.11
	// For newer versions of Moodle.
	$plugin->component = 'mod_digui';      // Full name of the plugin (used for diagnostics)
	$plugin->cron      = 0;
	$plugin->maturity  = MATURITY_ALPHA;
	$plugin->release   = 'version 1.0';
	$plugin->requires  = 2012120311;       // Requires this Moodle version: Moodle 2.4.11.
	$plugin->version   = 2016052301;       // The current module version: Moodle 3.1.1.
}
else {
	// For older versions of Moodle.
	$module->component = 'mod_digui';      // Full name of the plugin (used for diagnostics)
	$module->cron      = 0;
	$module->maturity  = MATURITY_ALPHA;
	$module->release   = 'version 1.0';
	$module->requires  = 2012120311;       // Requires this Moodle version: Moodle 2.4.11.
	$module->version   = 2016052301;       // The current module version: Moodle 3.1.1.
}
