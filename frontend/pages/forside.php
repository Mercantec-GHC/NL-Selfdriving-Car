<?php
declare(strict_types=1);

$main ??= new \TRP\MintMarkup\Document('Formula', lang: 'en');
$section = $main->section()->at(['class' => 'formula-shell space-y-8']);

$overview = $section->el('section', ['class' => 'formula-card grid grid-cols-1 lg:grid-cols-12 gap-8']);
$copy = $overview->el('div', ['class' => 'lg:col-span-7 space-y-6']);
$copy->h1('Formula');
$copy->p('Connect to the car and press the buttons to control how it drives.');
$copy->p('The browser sends your commands to the on-board Arduino through the car’s WiFi connection.');

$facts = $copy->el('ul', ['class' => 'grid grid-cols-1 sm:grid-cols-3 gap-3']);
foreach ([
	['Arduino on-board', 'Receives commands and controls the car'],
	['WiFi connection', 'Sends steering commands'],
	['Remote controls', 'Works on phone, tablet and desktop'],
] as [$title, $description]) {
	$item = $facts->el('li', ['class' => 'formula-fact']);
	$item->el('strong')->te($title);
	$item->el('span')->te($description);
}

$visual = $overview->el('div', ['class' => 'lg:col-span-5 formula-visual']);
$visual->img(__ROOT__.'/assets/img/basic/car.png');

$controller = $section->el('section', ['id' => 'controller-section', 'class' => 'formula-card']);
$controller->h2('Drive the car');
$controller->p('Press a button or use the arrow keys to control the car.');

$status = $controller->el('div', ['id' => 'controller-status']);
$status->el('span', [
	'id' => 'conn-badge',
	'data-state' => 'connecting',
])->te('Connecting to the car...');
$status->el('span', ['id' => 'last-cmd'])->te('');

$pad = $controller->el('div', ['id' => 'formula-pad']);

$topRow = $pad->el('div', ['id' => 'formula-pad-top']);
$topRow->el('button', [
	'id' => 'btn-up',
	'type' => 'button',
	'data-command' => 'FORWARD',
	'aria-label' => 'Forward',
])->el('span', ['aria-hidden' => 'true'])->te('▴');

$middleRow = $pad->el('div', ['id' => 'formula-pad-middle']);

$middleRow->el('button', [
	'id' => 'btn-left',
	'type' => 'button',
	'data-command' => 'LEFT',
	'aria-label' => 'Left',
])->el('span', ['aria-hidden' => 'true'])->te('◂');

$middleRow->el('button', [
	'id' => 'btn-right',
	'type' => 'button',
	'data-command' => 'RIGHT',
	'aria-label' => 'Right',
])->el('span', ['aria-hidden' => 'true'])->te('▸');

$bottomRow = $pad->el('div', ['id' => 'formula-pad-bottom']);
$bottomRow->el('button', [
	'id' => 'btn-down',
	'type' => 'button',
	'data-command' => 'REVERSE',
	'aria-label' => 'Reverse',
])->el('span', ['aria-hidden' => 'true'])->te('▾');

$log = $controller->el('div', ['class' => 'formula-log']);
$log->el('strong')->te('LOGS');
$log->el('span', ['id' => 'status-feed'])->te('> [System] Waiting for a WebSocket connection');

$movements = $controller->el('div', ['class' => 'formula-movements']);
$movements->h3('Latest movements');
$movements->el('div', ['id' => 'movement-log']);
