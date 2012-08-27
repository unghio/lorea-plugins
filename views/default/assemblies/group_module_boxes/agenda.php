<?php
elgg_load_library('elgg:crud');

echo "<b>".elgg_echo("assemblies:agenda")."</b>";

$group = elgg_get_page_owner_entity();

// Get next assembly
$assembly = $vars['next_assembly'];
if (!empty($assembly)) {
	$children = crud_get_children($assembly);
	echo "<ul>";
	foreach ($children as $child) {
		echo "<li>".elgg_view('output/url', array(
        		'href' => "agenda_point/view/$child->guid",
		        'text' => $child->title,
			))."</li>";
	}
	echo "</ul>";

	echo "<hr /><p>".elgg_view('output/url', array(
		'href' => "agenda_point/add/$assembly->guid",
		'text' => ucfirst(elgg_echo('agenda_point:add'))."</p>",
	));


} else {
	echo "<p>".elgg_echo("assemblies:none")."</p>";
}

