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
 * Class for including pages from MediaWiki
 *
 * @package    filter
 * @subpackage mediawiki
 * @copyright  2013 by Jan Luca Naumann
 * @author     Jan Luca Naumann <jan@jans-seite.de>
 * @license    CC-BY-SA 3.0 or later
 */

class filter_mediawiki extends moodle_text_filter {
	public function filter($text, array $options = array()) {
		global $PAGE, $DB;

		$wikis_cache = cache::make('filter_mediawiki', 'wikis');

		if ( ($short = $wikis_cache->get('short')) === false ||
			($long = $wikis_cache->get('long')) === false ||
			($wiki_data = $wikis_cache->get('data')) === false ||
			($wiki_domains = $wikis_cache->get('domains')) === false ) {
			$wikis = $DB->get_records('filter_mediawiki');

			$short = array();
			$long = array();
			$wiki_domains = array();
			$wiki_data = array();
			foreach ( $wikis as $wiki ) {
				// Use strtolower so we can use "==" case-insensitive
				$short_name = trim(strtolower($wiki->short_name));
				$long_name = trim(strtolower($wiki->long_name));
				$langs_str = trim(strtolower($wiki->lang));
				$api = trim($wiki->api);
				$page_url = trim(strtolower($wiki->page_url));
				$type = trim(strtolower($wiki->type));

				$index = (array_push($wiki_data, array('short' => $short_name, 'long' => $long_name, 'lang' => $langs_str,
					'api' => $api, 'page' => $page_url, 'type' => $type))) - 1;
				$short[$short_name] = $index;
				$long[$long_name] = $index;

				$langs = explode(',', $langs_str);
				$langs = array_map('trim', $langs);
				$domain_parts = explode('/', $page_url);
				$i = 0;
				while($i < 5) {
					$i++;
					$part = trim(strtolower(array_shift($domain_parts)));
					if ( empty($part) || $part == 'http:' || $part == 'https:' ) continue;

					if ( strpos($part, '$lang') !== false ) {
						foreach ( $langs as $lang ) {
							$domain = str_replace('$lang', $lang, $part);
							$wiki_domains[$domain] = array('index' => $index, 'parts' => $domain_parts);
						}
					} else {
						$wiki_domains[$part] = array('index' => $index, 'parts' => $domain_parts);
					}
					break;
				}
			}

			$wikis_cache->set_many(array('short' => $short, 'long' => $long, 'data' => $wiki_data,
				'domains' => $wiki_domains));
		}

		$regex = '@\[Include\-(.*?)\]((.*?)\[/Include.*?\])?@i';

		$already_replaced = cache::make('filter_mediawiki', 'already_replaced');

		$styles_wikimedia = '<link rel="stylesheet" href="https://bits.wikimedia.org/de.wikiversity.org/load.php?debug=false&amp;lang=de&amp;modules=ext.wikihiero%7Cmediawiki.legacy.commonPrint%2Cshared%7Cmw.PopUpMediaTransform%7Cskins.vector&amp;only=styles&amp;skin=vector&amp;*" />';
		$styles_wikimedia .= '<style type="text/css">.center{width: auto; text-align: left;} body {font-family: Arial,Verdana,Helvetica,sans-serif; font-size:13px;}</style>';

		if ( preg_match_all( $regex, $text, $matches, PREG_SET_ORDER ) ) {
			$ins_styles_wikimedia = false;

			foreach( $matches as $match ) {
				if ( !empty($match[2]) ) {
					$match_domains_parts = explode('/', strip_tags($match[2]));

					$domain_parts = false;
					foreach ( $match_domains_parts as $part ) {
						$part = trim(strtolower($part));
						if ( empty($part) || $part == 'http:' || $part == 'https:' ) continue;

						if ( $domain_parts === false ) {
							if ( !empty($wiki_domains[$part]) ) {
								$domain_parts = $wiki_domains[$part];
							} else {
								print_error('db_delete_error', 'filter_mediawiki', '', format_text($id, FORMAT_HTML));
								break;
							}
						}
					}
				}

				if( !empty( $already_replaced[$wv_lang.$title] ) ) continue;

				$url = $api_url.'?action=parse&format=php&prop=text&page=' . $title;

				$curl = new curl( array( 'cache' => true, 'module_cache' => 'filter_mediawiki' ) );

				$page = $curl->get( $url );
				$page = unserialize( $page );

				$page = $page['parse']['text']['*'];

				$add_link = '<a href="https://' . $wv_lang . '.wikiversity.org/wiki/' . $title . '">https://' . $wv_lang . '.wikiversity.org/wiki/' . $title . '</a>';
				$add = get_string( 'isfrom', 'filter_wikiversity', $add_link );
				$add .= get_string( 'license', 'filter_wikiversity' );

				$add_link = '<a href="https://' . $wv_lang . '.wikiversity.org/w/index.php?title=' . $title . '&action=history">';
				$add .= get_string( 'authors', 'filter_wikiversity', $add_link );

				$page .= '<hr />' . $add;

				$page = str_replace( 'href="/wiki', 'href="https://' . $wv_lang . '.wikiversity.org/wiki', $page );
				$page = str_replace( 'href="/w/', 'href="https://' . $wv_lang . '.wikiversity.org/w/', $page );

				if ( !$PAGE->user_is_editing() ) {
					$regex_edit = '@\<span class=\"editsection\"\>\[.*?\]\<\/span\>@i';
					$page = preg_replace( $regex_edit, '', $page );
				}

				$text = str_replace( $match[0], $page, $text );

				$already_replaced[$wv_lang.$title] = true;
			}

			if ( $ins_styles_wikimedia ) {
				$text = $styles_wikimedia.$text;
			}
		}

		return $text;
	}
}
?>