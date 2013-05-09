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
 * MediaWiki-filter post db-install hook
 *
 * @package    filter
 * @subpackage mediawiki
 * @copyright  2013 by Jan Luca Naumann
 * @author     Jan Luca Naumann <jan@jans-seite.de>
 * @license    CC-BY-SA 3.0 or later
 */

require_once( __DIR__ . '/wiki_list.php)';

function xmldb_filter_activitynames_install() {
    global $DB, $filter_mediawiki_wiki_list;

	foreach( $filter_mediawiki_wiki_list as $wiki ) {
		$record = new stdClass();
		$record->description = $wiki['short'];
		$record->short_name = $wiki['short'];
		$record->long_name = $wiki['long'];
		$record->lang = $wiki['lang'];
		$record->api = $wiki['api'];
		$record->page_url = $wiki['page'];
		$record->type = $wiki['type'];

		$DB->insert_record('filter_mediawiki', $record, false);
	}
}

