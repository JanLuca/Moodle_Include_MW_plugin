<?PHP
$definitions = array(
	'wikis' => array(
		'mode' => cache_store::MODE_APPLICATION,
		'simplekeys' => true
	),

	'already_replaced' => array(
		'mode' => cache_store::MODE_REQUEST
	)

	'submit' => array(
		'mode' => cache_store::MODE_REQUEST,
		'simplekeys' => true,
		'persistent' => true
	)
);