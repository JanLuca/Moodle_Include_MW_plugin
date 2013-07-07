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
				$api = trim(strtolower($wiki->api));
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
							$wiki_domains[$domain] = array('index' => $index, 'parts' => $domain_parts, 'lang' => $lang);
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

		$regex = '@\[Include\-(.*?)\s*\](.*?)\[/Include.*?\]@i';

		$styles_wikimedia = '<link rel="stylesheet" href="https://bits.wikimedia.org/de.wikiversity.org/load.php?debug=false&amp;lang=de&amp;modules=ext.wikihiero%7Cmediawiki.legacy.commonPrint%2Cshared%7Cmw.PopUpMediaTransform%7Cskins.vector&amp;only=styles&amp;skin=vector&amp;*" />';
		$styles_wikimedia .= '<style type="text/css">.center{width: auto; text-align: left;} body {font-family: Arial,Verdana,Helvetica,sans-serif; font-size:13px;}</style>';

		if ( preg_match_all( $regex, $text, $matches, PREG_SET_ORDER ) ) {
			$ins_styles_wikimedia = false;

			$already_replaced = cache::make('filter_mediawiki', 'already_replaced');
			$curl = new curl( array( 'cache' => true, 'module_cache' => 'filter_mediawiki' ) );

			foreach( $matches as $match ) {
				$include_code = trim(strtolower($match[1]));
				$title = false;
				$index = false;
				$wiki_lang = '';

				if ( !empty($match[2]) && $include_code == 'mw' ) {
					$match_domains_parts = explode('/', strip_tags($match[2]));

					$domain_parts = false;
					$page_title = null;
					$add_to_title = false;
					$i = 0;
					foreach ( $match_domains_parts as $part_ori ) {
						$part = trim(strtolower($part_ori));

						if ( $add_to_title == 1 ) {
							$title .= '/'.$part_ori;
							continue;
						} elseif ( $add_to_title == 2 ) {
							$next = trim(strtolower($domain_parts['parts'][$i]));
							if ( $part != $next ) {
								$title .= '/'.$part_ori;
								continue;
							}
						} elseif ( is_string($add_to_title) ) {
							if ( ($pos_add_title = strpos($part, $add_to_title)) !== false ) {
								if ( strlen($part) == strlen($part_ori) ) {
									$title .= '/'.substr($part_ori, 0, $pos_add_title);
								} else {
									$needle = substr($part, $pos_add_title);
									$pos_ori = stripos($part_ori, $needle);
									$title .= '/'.substr($part_ori, 0, $pos_ori);
								}
							} else {
								$title .= '/'.$part_ori;
								continue;
							}
						}
						$add_to_title = false;

						if ( empty($part) || $part == 'http:' || $part == 'https:' ) continue;

						if ( $domain_parts === false ) {
							if ( !empty($wiki_domains[$part]) ) {
								$domain_parts = $wiki_domains[$part];
								$wiki_lang = $domain_parts['lang'];
							} else {
								print_error('unknowndomain', 'filter_mediawiki', '', format_text($match[2], FORMAT_HTML));
								continue 2;
							}
						} else {
							$next = trim(strtolower($domain_parts['parts'][$i]));
							if ( $next == $part ) {
								$i++;
								continue;
							} elseif ( ($pos = strpos($next, '$1')) !== false ) {
								$len = strlen($next);
								$len_domain_parts = count($domain_parts['parts']);
								if ( $pos == 0 && $len == 2 ) {
									$title = $part_ori;
									$index = $domain_parts['index'];
									if ( $len_domain_parts == ($i + 1) ) {
										$add_to_title = 1;
									} else {
										$add_to_title = 2;
									}
								} elseif ( $pos == ($len - 2) ) {
									$static = substr($part, 0, $pos);

									if ( $static == substr($next, 0, $pos) ) {
										$index = $domain_parts['index'];
										if ( strlen($part) == strlen($part_ori) ) {
											$title = substr($part_ori, $pos);
											if ( $len_domain_parts == ($i + 1) ) {
												$add_to_title = 1;
											} else {
												$add_to_title = 2;
											}
										} else {
											$pos_ori = stripos($part_ori, substr($part, $pos));
											$title = substr($part_ori, $pos_ori);
											if ( $len_domain_parts == ($i + 1) ) {
												$add_to_title = 1;
											} else {
												$add_to_title = 2;
											}
										}
									} else {
										print_error('unknowndomain', 'filter_mediawiki', '', format_text($match[2], FORMAT_HTML));
										continue 2;
									}
								} else {
									$before = substr($next, 0, $pos);
									$after = substr($next, $pos + 2);

									if ( strpos($part, $before) !== false ) {
										if ( ($after_pos = strpos($part, $after)) !== false ) {
											$index = $domain_parts['index'];
											if ( strlen($part) == strlen($part_ori) ) {
												$title = substr($part_ori, $pos, $after_pos - $pos);
											} else {
												$needle = substr($part, $pos, $after_pos - $pos);
												$pos_ori = stripos($part_ori, $needle);
												$title = substr($part_ori, $pos_ori, strlen($needle));
											}
										} else {
											$index = $domain_parts['index'];
											if ( strlen($part) == strlen($part_ori) ) {
												$title = substr($part_ori, $pos);
											} else {
												$needle = substr($part, $pos);
												$pos_ori = stripos($part_ori, $needle);
												$title = substr($part_ori, $pos_ori);
											}
											$add_to_title = $after;
										}
									} else {
										print_error('unknowndomain', 'filter_mediawiki', '', format_text($match[2], FORMAT_HTML));
										continue 2;
									}
								}

								$i++;
							} else {
								print_error('unknowndomain', 'filter_mediawiki', '', format_text($match[2], FORMAT_HTML));
								continue 2;
							}
						}
					}

					if ( $add_to_title !== false && $add_to_title !== 1 ) {
						print_error('unknowndomain', 'filter_mediawiki', '', format_text($match[2], FORMAT_HTML));
						continue 2;
					}
				}

				if ( $title === false || $index === false ) {
					continue;
				}

				if( $already_replaced->get($wiki_data[$index]['long'].$wiki_lang.$title) !== false ) continue;

				$url = $wiki_data[$index]['api'];
				$url = str_replace( '$lang', $wiki_lang, $url );
				$url = $url.'?action=parse&format=php&prop=text&page=' . $title;

				$page = $curl->get( $url );
				$page = unserialize( $page );

				$page = $page['parse']['text']['*'];

				$type_changes = false;
				switch ( $wiki_data[$index]['type'] ) {
					case 'wikimedia':
						$type_changes = $this->type_changes_wikimedia($wiki_data[$index], $wiki_lang, $title);
						$ins_styles_wikimedia = true;
						break;
					default:
						//$type_changes = $this->type_changes_from_db($wiki_data[$index], $page);
				}

				if ( $type_changes !== false ) {
					$page .= html_writer::empty_tag('hr') . $type_changes['add'];
					$page = str_replace( $type_changes['search'], $type_changes['replace'], $page );
				}

				if ( !$PAGE->user_is_editing() ) {
					$regex_edit = '@\<span class=\"editsection\"\>\[.*?\]\<\/span\>@i';
					$page = preg_replace( $regex_edit, '', $page );
				}

				$text = str_replace( $match[0], $page, $text );

				$already_replaced->set($wiki_data[$index]['long'].$wiki_lang.$title, true);
			}

			if ( $ins_styles_wikimedia ) {
				$text = $styles_wikimedia.$text;
			}
		}

		return $text;
	}

	private function type_changes_wikimedia($data, $lang, $title) {
		$page_url = $data['page'];
		$page_url = str_replace('$lang', $lang, $page_url);
		$page_url = str_replace('$1', $title, $page_url);
		if ( strpos($page_url, '//') == 0 ) {
			$page_url = 'https:' . $page_url;
		}
		$page_link = html_writer::link($page_url, format_text($page_url, FORMAT_HTML));

		$add = get_string( 'wikimedia_isfrom', 'filter_mediawiki', array('page_link' => $page_link,
			'wiki_name' => format_text(ucfirst($data['long']), FORMAT_HTML)) );
		$add .= get_string( 'wikimedia_license', 'filter_mediawiki' );

		$history_link = '<a href="https://'.$lang.'.'.$data['long'].'.org/w/index.php?title='.$title.'&action=history">';
		$add .= get_string( 'wikimedia_authors', 'filter_mediawiki', $history_link );

		$search = array();
		$replace = array();
		$search[] = 'href="/wiki';
		$replace[] = 'href="https://'.$lang.'.'.$data['long'].'.org/wiki';
		$search[] = 'href="/w/';
		$replace[] = 'href="https://'.$lang.'.'.$data['long'].'.org/w/';

		return array('add' => $add, 'search' => $search, 'replace' => $replace);
	}
}
?>