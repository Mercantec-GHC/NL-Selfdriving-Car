<?php
declare(strict_types=1);

spl_autoload_register(function($name){
	$name = explode('\\',$name);
	if($name[0] == 'TRP'){
		array_shift($name);
		$file = __DIR__.'/lib/'.implode('/',$name).'.php';
		if(file_exists($file)){
			require_once $file;
		}
	}
});

require_once __DIR__.'/config.inc.php';
require_once __DIR__.'/design.inc.php';
require_once __DIR__.'/db.inc.php';

\TRP\HealDocument\HealDocument::register_plugin('\TRP\MintMarkup\MintMarkup');
\TRP\MintMarkup\Icon::register_spritesheet(__ROOT__.'/assets/img/icon/svg/spritesheet.svg');
