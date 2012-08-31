<?php
/**
 * Edit a task list
 *
 * @package ElggTasks
 */

gatekeeper();

$tasklist_guid = (int)get_input('guid');
$tasklist = get_entity($tasklist_guid);
if (!$tasklist) {
	
}

$container = $tasklist->getContainerEntity();
if (!$container) {
	
}

elgg_set_page_owner_guid($container->getGUID());

elgg_push_breadcrumb($tasklist->title, $tasklist->getURL());
elgg_push_breadcrumb(elgg_echo('edit'));

$title = elgg_echo("tasks:lists:edit");

if ($tasklist->canEdit()) {
	
	$vars = array_merge(
		elgg_get_config('tasklists'),
		array(
			'guid' => true,
			'container_guid' => true,
			'list_guid' => true,
		)
	);
	foreach ($vars as $name => $dummy) {
		if (isset($tasklist->$name)) {
			$vars[$name] = $tasklist->$name;
		} else {
			$vars[$name] = false;
		}
	}

	$content = elgg_view_form('tasklists/edit', array(), $vars);

} else {
	$content = elgg_echo("tasks:lists:noaccess");
}

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
