<?php
$string['filtername'] = 'MediaWiki-Filter';

// Settings: Wiki
$string['includeablewikis'] = 'Wikis from that pages can be included';
$string['short'] = 'Short name';
$string['long'] = 'Long name';
$string['description'] = 'Description';
$string['lang'] = 'Possible languages';
$string['api'] = 'API url';
$string['page'] = 'Page url';
$string['type'] = 'Wiki type';
$string['add_wiki'] = 'Add new wiki';

// Settings-description: Wiki
$string['description_short'] = 'Short wiki name';
$string['description_long'] = 'Long wiki name';
$string['description_description'] = 'Description for the wiki';
$string['description_lang'] = 'Possible comma-seperated languages for that wiki. Can be empty if there is only one language/wiki url.';
$string['description_api'] = 'URL to wiki\'s API. You can use the variable $lang in the url to show where the language code should be. Example: https://$lang.wikiversity.org/w/api.php';
$string['description_page'] = 'URL to wiki\'s pages (article path). You can use the variable $lang in the url to show where the language code will be and $1 as alias for the page name. When both http and https are supported the url have to start with "//". Example: //$lang.wikiversity.org/wiki/$1';
$string['description_type'] = 'Type of the wiki. For an overview see the table with the supported types on the overview page.';

// Types
$string['type_wikimedia'] = 'Wikimedia-Wiki';

// Errors
$string['unknownaction'] = 'The action {$a} is unknown.';
$string['unknownid'] = 'The id {$a} is unknown.';
$string['alreadyexists'] = 'The wiki with the short name "{$a->short}" and/or the long name "{$a->long}" exists already.';
$string['db_insert_error'] = 'Error when inserting new database record.';
$string['db_delete_error'] = 'Error when deleting database record with id {$a}.';
$string['db_update_error'] = 'Error when updating database record with id {$a}.';

// Info-strings for type wikimedia
$string['wikimedia_isfrom'] = 'The preceding text is included from {$a->wiki_name}. The original page you can find under {$a->page_link}. ';
$string['wikimedia_license'] = 'The text was released under the <a href="https://en.wikipedia.org/wiki/Wikipedia:Text_of_Creative_Commons_Attribution-ShareAlike_3.0_Unported_License">CC-BY-SA 3.0 License</a> and the <a href="https://en.wikipedia.org/wiki/Wikipedia:Text_of_the_GNU_Free_Documentation_License">GFDL</a>. ' .
	'For more information see <a href="https://wikimediafoundation.org/wiki/Terms_of_Use">"Wikimedia Terms of Use"</a>. ';
$string['wikimedia_authors'] = 'For a list of the authors see {$a}here</a>. For information about the license of the included images click on them.';