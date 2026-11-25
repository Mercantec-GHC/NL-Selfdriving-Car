<?php
declare(strict_types=1);

function page($current_page, $valid_pages){
	$doc = head();

	$doc->el('body', ['id' => $current_page]);

	navbar($doc, $valid_pages, $current_page);
	
	$main = $doc->main();

	return [$doc, $main];
}


function head() {
	$doc = new \TRP\MintMarkup\Document('TRP Solutions', lang: 'da');
	$doc->meta('viewport', 'width=device-width, initial-scale=1.0');

	// Favicon: Standard
	$doc->link(__ROOT__ .'/assets/img/logo/favicon.ico', 'shortcut icon')->at(['type' => 'image/x-icon', 'sizes' => '64x64']);
	$doc->link(__ROOT__ .'/assets/img/logo/icon57x57.png', 'apple-touch-icon')->at(['type' => 'image/png', 'sizes' => '57x57']);
	$doc->link(__ROOT__ .'/assets/img/logo/icon72x72.png', 'apple-touch-icon')->at(['type' => 'image/png', 'sizes' => '72x72']);
	$doc->link(__ROOT__ .'/assets/img/logo/icon114x114.png', 'apple-touch-icon')->at(['type' => 'image/png', 'sizes' => '114x114']);

	// Favicon: Android “Add to Home Screen”
	$doc->meta('application-name', 'TRP Solutions');
	
	// Favicon: Windows Tile
	$doc->meta('msapplication-TileColor', '#047bb1');
	$doc->meta('msapplication-TileImage', __ROOT__ .'/assets/img/logo/w7square.png');
	$doc->meta('msapplication-square70x70logo', __ROOT__ .'/assets/img/logo/w7tiny.png');
	$doc->meta('msapplication-square150x150logo', __ROOT__ .'/assets/img/logo/w7square.png');
	$doc->meta('msapplication-wide310x150logo', __ROOT__ .'/assets/img/logo/w7wide.png');
	$doc->meta('msapplication-square310x310logo', __ROOT__ .'/assets/img/logo/w7large.png');

	// Tema-farve for Chrome’s toolbar
	$doc->meta('theme-color', '#ffffff');

	// CSS
	$doc->stylesheet(__ROOT__.'/assets/css/styles.css');
	
	// JS
	$doc->script(__ROOT__.'/assets/js/alpinejs.min.js')->at(['defer']);
	$doc->script(__ROOT__ . '/assets/js/formula.js')->at(['defer']);

	return $doc;
}


function navbar($doc, array $valid_pages, ?string $current_page = null){
	$header = $doc->header();

	$nav = $header->el('nav', ['x-data' => '{ showMenu : false }']);
	
	$a = $nav->a(__ROOT__);

	// TRP logo
	$a->img(__ROOT__.'/assets/img/logo/logo.svg')->at([
		'draggable' => 'false', 
		'alt'       => 'Logo',
	]);

	// Burgermenu
	$button = $nav->el('button', [
		'x-on:click'        => 'showMenu = !showMenu',
		'x-on:click.away'   => 'showMenu = false',
		'x-bind:class'      => "{ 'text-gray-900': showMenu, 'text-gray-700': !showMenu }",
		'aria-controls'     => 'navbar-default',
		'aria-expanded'     => 'false',
	]);

	$button->icon('burger_menu');

	// Desktop version
	$desktopWrap = $nav->el('div')->at(['id' => "desktop_menu"]);
	$ul_d = $desktopWrap->el('ul');

	// Mobile version
	$mobileWrap = $nav->el('div', [
		'x-show'                   => 'showMenu',
		'x-cloak'                  => '',
		'x-transition:enter'       => 'transition ease-out duration-300',
		// Minus before 'translate' make dropdown move up opposite of 'translate-y-6'
		'x-transition:enter-start' => 'opacity-0 -translate-y-6',
		'x-transition:enter-end'   => 'opacity-100 translate-y-0',
		'x-transition:leave'       => 'transition ease-in duration-200',
		'x-transition:leave-start' => 'opacity-100 translate-y-0',
		'x-transition:leave-end'   => 'opacity-0 -translate-y-6',
		'id'                       => 'mobile_menu',
		'class'                    => 'absolute top-full left-0 w-full bg-white shadow-lg z-50',
	]);

	$ul_m = $mobileWrap->el('ul');

	foreach($valid_pages as $page_key => $page_title){
		
		$url = $page_key;

		// Mobile
		$li_m = $ul_m->el('li');

		if ($page_key === 'forside'){
			$url = __ROOT__;
		} elseif ($page_key === 'quicksupport'){
			$url = 'https://get.teamviewer.com/trp-solutions';
		}

		$a_m = $li_m->a($url, $page_title)->at(['class' => 'nav-link-mobile']);

		// Active mobile menu item
		if ($current_page !== null && $page_key === $current_page){
			$a_m->at([
				'class' => 'nav-link-mobile--active',
				'aria-current' => 'page',
			]);
		}

		
		// Desktop
		if ($page_key === 'forside') continue;

		$li_d = $ul_d->el('li');

		if ($page_key === 'quicksupport'){
			$a_d = $li_d->a($url)->at(['class' => 'nav-link']);

			$a_d->img(__ROOT__.'/assets/img/icon/svg/teamviewer.svg')->at([
				'draggable' => 'false',
				'alt' => "{$page_key} icon",
			]);

			$a_d->te($page_title);
		}
		else {
			$a_d = $li_d->a($url, $page_title)->at(['class' => 'nav-link']);
		}

		// Active desktop menu item
		if ($current_page !== null && $page_key === $current_page){
			$a_d->at([
				'class' => 'nav-link--active', 
				'aria-current' => 'page',
			]);
		}
	}
}

function error($context, int $error, ?string $error_message = null){
	if (400 <= $error && $error <= 599){
		http_response_code($error);
	}

	$error_message ??= match($error){
		404 => "Not Found",
		500 => "Internal Server Error",
		501 => "Not Implemented",
		default => "Unknown Error",
	};
	$context->p("Error $error: $error_message");
}
