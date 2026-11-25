<?php
declare(strict_types=1);

require_once __DIR__.'/header.inc.php';

$valid_pages = [
	'forside'		=>	'Forside',
	// 'formula'		=>	'formula',
];

try {
	$current_page = $_GET['page'] ?? 'forside';

	if(!isset($valid_pages[$current_page])){
		throw new \Exception("'$current_page' Not Found", 404);
	}

	[$doc, $main] = page($current_page, $valid_pages);

	$filename = 'pages/'.$current_page.'.php';
	if(!is_file($filename)){
		throw new \Exception("'$current_page' Not Found", 404);
	} 
	
	require_once $filename;
	
} catch (\Exception $e){
	if(!isset($main)){
		[$doc, $main] = page('Error', $valid_pages);
	}
	error($main, $e->getCode(), $e->getMessage());
}

echo $doc;
