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

/**
 * Special class for mediawiki-filter administration.
 */
class admin_setting_filter_mediawiki extends admin_setting {
    /**
     * Calls parent::__construct with specific arguments
     */
    public function __construct() {
        parent::__construct('filter_mediawiki/wikis', get_string( 'filtername', 'filter_mediawiki' ), '', '');
    }

    /**
     * Always returns true, does nothing
     *
     * @return true
     */
    public function get_setting() {
        return true;
    }

    /**
     * Always returns true, does nothing
     *
     * @return true
     */
    public function get_defaultsetting() {
        return true;
    }

    /**
     * Always returns '', does not write anything
     *
     * @return string Always returns ''
     */
    public function write_setting($data) {
        // do not write any setting
        return '';
    }

    /**
     * Builds the XHTML to display the control
     *
     * @param string $data Unused
     * @param string $query
     * @return string
     */
    public function output_html($data, $query='') {
        global $PAGE, $OUTPUT, $DB;
        $url = $PAGE->url;

        // display strings
        $txt = get_strings(array('short', 'long', 'description', 'lang', 'api', 'page', 'type'), 'filter_mediawiki');

        $return = $OUTPUT->heading(get_string('includeablewikis', 'filter_mediawiki'), 3, 'main');
        $return .= $OUTPUT->box_start('generalbox editorsui');

        $table = new html_table();
        $table->head  = array($txt->short, $txt->long, $txt->description, $txt->lang, $txt->api, $txt->page, $txt->type);
        $table->colclasses = array('centeralign', 'centeralign', 'leftalign', 'leftalign', 'leftalign', 'leftalign', 'leftalign');
        $table->id = 'includeablewikis';
        $table->attributes['class'] = 'admintable generaltable';
        $table->data  = array();

		$wikis = $DB->get_records('filter_mediawiki');

        foreach ($wikis as $wiki) {
			$edit_url = $url->out(true, array('sesskey' => sesskey(), 'action' => 'edit', 'id' => $wiki->id));

			$short = html_writer::link($edit_url, htmlspecialchars($wiki->short_name));
			$long = htmlspecialchars($wiki->long_name);
			$description = htmlspecialchars($wiki->description);
			$lang = '---';
			$api = htmlspecialchars($wiki->api);
			$page = htmlspecialchars($wiki->page_url);
			$type = htmlspecialchars($wiki->type);

            $table->data[] = array($short, $long, $description, $lang, $api, $page, $type);
        }
        $return .= html_writer::table($table);
        $return .= $OUTPUT->box_end();
        return highlight($query, $return);
    }
}