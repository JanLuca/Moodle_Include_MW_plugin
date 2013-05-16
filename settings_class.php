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

function format_settings_filter_mediawiki($label, $input, $name, $description, $labelfor = null) {
	$return = html_writer::start_div('form-item clearfix');
	$return .= html_writer::start_div('form-label');
	$return .= html_writer::label($label, $labelfor);
	$return .= html_writer::span($name, 'form-shortname');
	$return .= html_writer::end_div();
	$return .= html_writer::div($input, 'form-setting');
	$return .= html_writer::div($description, 'form-description');
	$return .= html_writer::end_div();
	return $return;
}

/**
 * Special class for mediawiki-filter administration.
 */
class admin_setting_filter_mediawiki extends admin_setting {
    /**
     * Calls parent::__construct with specific arguments
     */
    public function __construct() {
		$this->nosave = true;
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
        $txt = get_strings(array('short', 'long', 'description', 'lang', 'api', 'page', 'type', 'add_wiki'), 'filter_mediawiki');
		$strstd = get_strings(array('delete', 'edit'));

        $return = $OUTPUT->heading(get_string('includeablewikis', 'filter_mediawiki'), 3, 'main');
        $return .= $OUTPUT->box_start('generalbox editorsui');

        $table = new html_table();
        $table->head  = array($txt->short, $txt->long, $txt->description, $txt->lang, $txt->api, $txt->page, $txt->type, $strstd->edit);
        $table->colclasses = array('centeralign', 'centeralign', 'leftalign', 'leftalign', 'leftalign', 'leftalign', 'leftalign', 'centeralign');
        $table->id = 'includeablewikis';
        $table->attributes['class'] = 'admintable generaltable';
        $table->data  = array();

		$wikis = $DB->get_records('filter_mediawiki');

        foreach ($wikis as $wiki) {
			$edit_url = $url->out(false, array('sesskey' => sesskey(), 'action' => 'edit', 'id' => $wiki->id));
			$delete_url = $url->out(false, array('sesskey' => sesskey(), 'action' => 'delete', 'id' => $wiki->id));

			$short = html_writer::link($edit_url, format_text($wiki->short_name, FORMAT_HTML));
			$long = format_text($wiki->long_name, FORMAT_HTML);
			$description = format_text($wiki->description, FORMAT_HTML);
			$api = format_text($wiki->api, FORMAT_HTML);
			$page = format_text($wiki->page_url, FORMAT_HTML);
			$type = get_string('type_'.format_text($wiki->type, FORMAT_HTML), 'filter_mediawiki');
			$buttons = html_writer::link($delete_url, html_writer::empty_tag('img', array('src' => $OUTPUT->pix_url('t/delete'),
				'alt' => $strstd->delete, 'class' => 'iconsmall')), array('title' => $strstd->delete));
			$buttons .= ' ';
			$buttons .= html_writer::link($edit_url, html_writer::empty_tag('img', array('src' => $OUTPUT->pix_url('t/edit'),
				'alt' => $strstd->edit, 'class' => 'iconsmall')), array('title' => $strstd->edit));

			$langs = explode(',', $wiki->lang);
			if ( count($langs) >= 5 ) {
				$lang = array();
				for	($i = 0; $i < 5; $i++) {
					$lang[] = $langs[$i];
				}
				$lang = format_text(implode(',', $lang) . ', ...', FORMAT_HTML);
			} else {
				$lang = format_text($wiki->lang, FORMAT_HTML);
			}

            $table->data[] = array($short, $long, $description, $lang, $api, $page, $type, $buttons);
        }
        $return .= html_writer::table($table);

		$return .= html_writer::empty_tag('input', array('type' => 'hidden',
				'name' => 'action', 'value' => 'redirect_add'));
		$return .= html_writer::empty_tag('input', array('type' => 'submit',
			'value' => ' '.$txt->add_wiki.' ', 'class' => 'form-submit'));

        $return .= $OUTPUT->box_end();
        return highlight($query, $return);
    }
}

/**
 * Special class for mediawiki-filter administration.
 */
class admin_setting_filter_mediawiki_wiki extends admin_setting {
	/**
     * Current action of the form (edit, add or delete)
     */
	private $action;

	/**
     * Id of the current wiki
     */
	private $wiki_id;

    /**
     * Calls parent::__construct with specific arguments
     */
    public function __construct($action, $wiki_id = null) {
		$this->nosave = true;
		$this->action = $action;
		$this->wiki_id = $wiki_id;
        parent::__construct('filter_mediawiki/wiki_action', get_string( 'filtername', 'filter_mediawiki' ), '', '');
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
        $txt = get_strings(array('short', 'long', 'description', 'lang', 'api', 'page', 'type',
			'description_short', 'description_long', 'description_description',
			'description_lang', 'description_api', 'description_page', 'description_type',
			'type_wikimedia'), 'filter_mediawiki');

		$return = '';
		$short_input = '';
		$long_input = '';
		$description_input = '';
		$lang_input = '';
		$api_input = '';
		$page_input = '';
		$type_input = '';

		if ( $this->action = 'edit' ) {
			$formated_id = format_text($this->wiki_id, FORMAT_HTML);

			$wiki = $DB->get_record('filter_mediawiki', array('id' => $this->wiki_id));
			if ( $wiki == false ) {
				print_error('unknownid', 'filter_mediawiki', '', $formated_id);
			}

			//$return .= html_writer::start_tag('form', array('method' => 'post', 'action' => $url->out(false,
			//	array('sesskey' => sesskey(), 'action' => 'edit', 'id' => $formated_id, 'submit' => true))));

			$return .= html_writer::input_hidden_params($url, array('sesskey', 'action', 'id', 'submit'));
			$return .= html_writer::empty_tag('input', array('type' => 'hidden',
				'name' => 'action', 'value' => 'edit'));
			$return .= html_writer::empty_tag('input', array('type' => 'hidden',
				'name' => 'id', 'value' => $formated_id));
			$return .= html_writer::empty_tag('input', array('type' => 'hidden',
				'name' => 'submit', 'value' => true));

			$short_input = html_writer::div(html_writer::empty_tag('input', array('type' => 'text',
				'id' => 'filter_mediawiki_short', 'name' => 'filter_mediawiki_short',
				'value' => format_text($wiki->short_name, FORMAT_HTML))), 'form-text defaultsnext');

			$long_input = html_writer::div(html_writer::empty_tag('input', array('type' => 'text',
				'id' => 'filter_mediawiki_long', 'name' => 'filter_mediawiki_long',
				'value' => format_text($wiki->long_name, FORMAT_HTML))), 'form-text defaultsnext');

			$description_input = html_writer::div(html_writer::empty_tag('input', array('type' => 'text',
				'id' => 'filter_mediawiki_description', 'name' => 'filter_mediawiki_description',
				'value' => format_text($wiki->description, FORMAT_HTML))), 'form-text defaultsnext');

			$lang_input = html_writer::div(html_writer::empty_tag('input', array('type' => 'text',
				'id' => 'filter_mediawiki_lang', 'name' => 'filter_mediawiki_lang',
				'value' => format_text($wiki->lang, FORMAT_HTML))), 'form-text defaultsnext');

			$api_input = html_writer::div(html_writer::empty_tag('input', array('type' => 'text',
				'id' => 'filter_mediawiki_api', 'name' => 'filter_mediawiki_api',
				'value' => format_text($wiki->api, FORMAT_HTML))), 'form-text defaultsnext');

			$page_input = html_writer::div(html_writer::empty_tag('input', array('type' => 'text',
				'id' => 'filter_mediawiki_page', 'name' => 'filter_mediawiki_page',
				'value' => format_text($wiki->page_url, FORMAT_HTML))), 'form-text defaultsnext');

			$type_input = html_writer::div(html_writer::select(array('wikimedia' => $txt->type_wikimedia),
				'filter_mediawiki_type', format_text($wiki->type, FORMAT_HTML), '',
				array('id' => 'filter_mediawiki_type')), 'form-select defaultsnext');
		}

		$return .= format_settings_filter_mediawiki($txt->short, $short_input,
			'short_name', $txt->description_short, 'filter_mediawiki_short');

		$return .= format_settings_filter_mediawiki($txt->long, $long_input,
			'long_name', $txt->description_long, 'filter_mediawiki_long');

		$return .= format_settings_filter_mediawiki($txt->description, $description_input,
			'description', $txt->description_description, 'filter_mediawiki_description');

		$return .= format_settings_filter_mediawiki($txt->lang, $lang_input,
			'lang', $txt->description_lang, 'filter_mediawiki_lang');

		$return .= format_settings_filter_mediawiki($txt->api, $api_input,
			'api', $txt->description_api, 'filter_mediawiki_api');

		$return .= format_settings_filter_mediawiki($txt->page, $page_input,
			'page_url', $txt->description_page, 'filter_mediawiki_page');

		$return .= format_settings_filter_mediawiki($txt->type, $type_input,
			'type', $txt->description_type, 'filter_mediawiki_type');

		$return .= html_writer::div(html_writer::empty_tag('input', array('type' => 'submit',
			'value' => get_string('savechanges','admin'), 'class' => 'form-submit')), 'form-buttons');

		$return .= html_writer::end_tag('form');

        return highlight($query, $return);
    }
}

function filter_mediawiki_submit_wiki($action = 'edit', $id = -1) {
	global $DB;

	$record = new stdClass();
	$record->description = required_param('filter_mediawiki_description', PARAM_TEXT);
	$record->short_name = required_param('filter_mediawiki_short', PARAM_ALPHANUMEXT);
	$record->long_name = required_param('filter_mediawiki_long', PARAM_ALPHANUMEXT);
	$record->lang = optional_param('filter_mediawiki_lang', '', PARAM_TEXT);
	$record->api = required_param('filter_mediawiki_api', PARAM_TEXT);
	$record->page_url = required_param('filter_mediawiki_page', PARAM_TEXT);
	$record->type = required_param('filter_mediawiki_type', PARAM_ALPHANUMEXT);

	if ( $action == 'edit' ) {
		if ( ($db_id = $DB->get_field('filter_mediawiki', 'id', array('id' => $id))) !== false ) {
			$record->id = $db_id;
			return $DB->update_record('filter_mediawiki', $record);
		} else {
			print_error('unknownid', 'filter_mediawiki', '', format_text($id, FORMAT_HTML));
		}
	} elseif ( $action == 'add' ) {
		return $DB->insert_record('filter_mediawiki', $record, false);
	} else {
		print_error('unknownaction', 'filter_mediawiki', '', format_text($action, FORMAT_HTML));
	}
}