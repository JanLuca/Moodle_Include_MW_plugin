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
 * @package    filter
 * @subpackage mediawiki
 * @copyright  2013 by Jan Luca Naumann
 * @author     Jan Luca Naumann <jan@jans-seite.de>
 * @license    CC-BY-SA 3.0 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once __DIR__ . '/settings_class.php';

$action = optional_param('action', 'default', PARAM_ALPHANUMEXT);

if ($ADMIN->fulltree) {
	if ( $action == 'default' ) {
		$settings->add(new admin_setting_filter_mediawiki());
	} elseif ( $action == 'add' ) {

	} elseif ( $action == 'delete' ) {

	}

    //$settings->add(new admin_setting_configtextarea('filter_censor_badwords', get_string('badwordslist','admin'),
    //               get_string('badwordsconfig', 'admin').'<br />'.get_string('badwordsdefault', 'admin'), ''));
}
