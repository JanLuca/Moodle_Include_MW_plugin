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
$submit = optional_param('submit', false, PARAM_BOOL);
$id = optional_param('id', -1, PARAM_INT);

if ( $submit ) {
	if ( confirm_sesskey() ) {
		if ( $action == 'add' ) {
			if ( filter_mediawiki_submit_wiki('add') ) {
				$settings->add(new admin_setting_filter_mediawiki());
			} else {
				print_error('db_insert_error', 'filter_mediawiki', '', format_text($id, FORMAT_HTML));
			}
		} elseif ( $action == 'delete' && $id >= 0 ) {
			if ( filter_mediawiki_submit_wiki('delete', $id) ) {
				$settings->add(new admin_setting_filter_mediawiki());
			} else {
				print_error('db_delete_error', 'filter_mediawiki', '', format_text($id, FORMAT_HTML));
			}
		} elseif ( $action == 'edit' && $id >= 0 ) {
			if ( filter_mediawiki_submit_wiki('edit', $id) ) {
				$settings->add(new admin_setting_filter_mediawiki());
			} else {
				print_error('db_update_error', 'filter_mediawiki', '', format_text($id, FORMAT_HTML));
			}
		} elseif ( $id < -1 ) {
			print_error('unknownid', 'filter_mediawiki', '', format_text($id, FORMAT_HTML));
		} else {
			print_error('unknownaction', 'filter_mediawiki', '', format_text($action, FORMAT_HTML));
		}
	} else {
		print_error('invalidsesskey');
	}
} elseif ( $ADMIN->fulltree ) {
	if ( $action == 'default' ) {
		$settings->add(new admin_setting_filter_mediawiki());
	} elseif ( confirm_sesskey() ) {
		if ( $action == 'add' ) {
			$settings->add(new admin_setting_filter_mediawiki_wiki('add'));
		} elseif ( $action == 'redirect_add' ){
			$add_url = $PAGE->url->out(false, array('sesskey' => sesskey(), 'action' => 'add'));
			redirect($add_url);
		} elseif ( $action == 'delete' && $id >= 0 ) {
			$settings->add(new admin_setting_filter_mediawiki_wiki('delete', $id));
		} elseif ( $action == 'edit' && $id >= 0 ) {
			$settings->add(new admin_setting_filter_mediawiki_wiki('edit', $id));
		} elseif ( $id < -1 ) {
			print_error('unknownid', 'filter_mediawiki', '', format_text($id, FORMAT_HTML));
		} else {
			print_error('unknownaction', 'filter_mediawiki', '', format_text($action, FORMAT_HTML));
		}
	} else {
		print_error('invalidsesskey');
	}
}
