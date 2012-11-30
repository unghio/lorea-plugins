<?php
/**
 * Elgg Group Alias
 *
 * @package ElggGroupAlias
 */

elgg_register_event_handler('init', 'system', 'group_alias_init');
/**
 * Initialize the group alias plugin.
 *
 */
function group_alias_init() {
	
	// Register group alias library
	$library_path = elgg_get_plugins_path() . 'group_alias/lib/group_alias.php';
	elgg_register_library('elgg:group_alias', $library_path);

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('g', 'group_alias_page_handler');

	// Override URL handlers for groups
	elgg_register_entity_url_handler('group', 'all', 'group_alias_url');

	// Add alias field
	elgg_register_plugin_hook_handler('profile:fields', 'group', 'group_alias_fields_setup');

	// Override some actions
	$action_base = elgg_get_plugins_path() . 'group_alias/actions/groups';
	elgg_register_action("groups/edit", "$action_base/edit.php");

	// Extend the main css view
	elgg_extend_view('css/elgg', 'group_alias/css');
	elgg_extend_view('js/elgg', 'group_alias/js');
	
	// Register tests
	elgg_register_plugin_hook_handler('unit_test', 'system', 'group_alias_test');
	elgg_register_event_handler('upgrade', 'system', 'group_alias_run_upgrades');

}

function group_alias_run_upgrades() {
	if (include_once(elgg_get_plugins_path() . 'upgrade-tools/lib/upgrade_tools.php')) {
		upgrade_module_run('group_alias');
	}

}


function group_alias_test($hook, $type, $value, $params) {
	$value[] = elgg_get_config('pluginspath') . "group_alias/tests/group_alias_test.php";
	return $value;
}

function get_group_from_group_alias($alias){
	return current(elgg_get_entities_from_metadata(array(
		'type' => 'group',
		'metadata_name' => 'alias',
		'metadata_value' => $alias,
		'limit' => 1,
	)));
}

/**
 * Dispatcher for group alias.
 * URLs take the form of
 *  All groups:       g/
 *  Group profile:    g/<alias>
 *  Group Tools:      g/<alias>/<handler> => <handler>/group/<guid>
 *
 * @param array $page
 * @return bool
 */
function group_alias_page_handler($page) {

	elgg_set_context('groups');

	if (!isset($page[0])) {
		groups_page_handler(array('all'), 'groups');
		return true;
	}

	$group = get_group_from_group_alias(str_replace(' ', '+', urldecode($page[0])));

	if($group && !isset($page[1])){
		groups_page_handler(array('profile', $group->guid));

	} elseif($group && isset($page[1])) {
		forward("$page[1]/group/$group->guid");

	} else {
		groups_page_handler($page);
	}

	return true;
}

function group_alias_fields_setup($hook, $type, $return, $params) {
	return array_merge($return, array('alias' => 'group_alias'));
}

/**
 * Override the group url
 * 
 * @param ElggObject $group Group object
 * @return string
 */
function group_alias_url($group) {
	if(!$group->alias){
		return groups_url($group);
	}
	return "g/$group->alias";
}

/**
 * Convert a group name to an alias if it does not exist already.
 * Return the newly, or existing group alias.
 *
 * @param ElggGroup $group Group object
 * @return string
 */
function group_alias_update_from_name($group) {
	if (!empty($group->alias))
		return $group->alias;

	$alias = elgg_get_friendly_title($group->name);
	$alias = preg_replace("/-/", "_", $alias);
	$alias = urldecode($alias);
	// If alias is taken
	$g = get_group_from_group_alias($alias);
	if (elgg_instanceof($g, 'group') && $g->getGUID() != $group->guid){
		$alias .= $group->guid;
	}
	$group->alias = $alias;
	$group->save();
	return $alias;
}
