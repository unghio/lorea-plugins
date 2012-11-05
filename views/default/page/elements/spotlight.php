<?php

echo '<div class="spotlight clearfloat">';

echo '<div class="spotlight-column">';

echo elgg_view('page/elements/spotlight/module', array(
	'title' => elgg_echo('news:news'),
	'items' => array(
		'https://n-1.cc/pg/pages/view/9385/' => elgg_echo('news:features'),
	),
));

echo elgg_view('page/elements/spotlight/module', array(
	'title' => elgg_echo('about:lorea'),
	'items' => array(
		'https://lorea.org/' => elgg_echo('about:blog'),
		'https://n-1.cc/g/lorea/' => elgg_echo('about:group'),
		elgg_echo('lorea:sustainability:url') => elgg_echo('lorea:sustainability'),
	),
));

echo '</div><div class="spotlight-column">';

echo elgg_view('page/elements/spotlight/module', array(
	'title' => elgg_echo('contact:contact'),
	'items' => array(
		'https://lists.rhizomatik.net/listinfo/mycelia-community' => elgg_echo('contact:mailing'),
	),
));

echo elgg_view('page/elements/spotlight/module', array(
	'title' => elgg_echo('help:help'),
	'items' => array(
		'https://n-1.cc/pg/faq/' => elgg_echo('help:faq'),
		'https://n-1.cc/dokuwiki/9394' => elgg_echo('help:howto'),
		'https://n-1.cc/g/help/' => elgg_echo('help:group'),
	),
));

echo '</div><div class="spotlight-column">';

echo elgg_view('page/elements/spotlight/module', array(
	'title' => elgg_echo('dev:dev'),
	'items' => array(
		'https://n-1.cc/g/bughunting/' => elgg_echo('dev:bughunting'),
		'https://n-1.cc/g/testers-and-usability/' => elgg_echo('dev:testers'),
		'https://dev.lorea.org/' => elgg_echo('dev:network'),
		'https://gitorious.org/lorea/'=> elgg_echo('dev:repo'),
		
	),
));

echo '</div><div class="spotlight-column">';

$members = get_number_users();
$online  = find_active_users(600, 0, 0, true);
$groups  = elgg_get_entities(array('type' => 'group', 'count' => true, 'limit' => 0));
$pages   = elgg_get_entities(array('type' => 'object', 'subtypes' => array('page', 'page_top', 'etherpad', 'subpad'), 'count' => true, 'limit' => 0));
$blog    = elgg_get_entities(array('type' => 'object', 'subtype' => 'blog', 'count' => true, 'limit' => 0));
$file    = elgg_get_entities(array('type' => 'object', 'subtype' => 'file', 'count' => true, 'limit' => 0));

echo elgg_view('page/elements/spotlight/module', array(
	'title' => elgg_echo('stats:stats'),
	'items' => array(
		'members'        => $members . ' ' . elgg_echo('members'),
		'members/online' => $online . ' ' .  elgg_echo('members:label:online'),
		'groups/all'     => $groups . ' ' .  elgg_echo('item:group'),
		'pages/all'      => $pages . ' ' .   elgg_echo('item:object:page'),
		'blog/all'       => $blog . ' ' .    elgg_echo('item:object:blog'),
		'file/all'       => $file . ' ' .    elgg_echo('item:object:file'),
		//'tidypics/all' => $photos . ' ' .  elgg_echo('item:object:photo'),
	),
));

echo '</div>';
echo '</div>';
