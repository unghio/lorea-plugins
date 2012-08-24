<?php
/**
 * Crud function library
 */

/**
 * List crud objects in a group
 *
 * @param int $guid Group entity GUID
 */
function crud_handle_list_page($crud, $guid) {

	elgg_set_page_owner_guid($guid);

	$crud_type = $crud->crud_type;

	$parent = get_entity($guid);
	if ($parent instanceof ElggGroup) {
		$group = $parent;
		$parent = NULL;
	}
	else {
		$group = get_entity($parent->container_guid);
	}

	if (!$group) {
		register_error(elgg_echo('groups:notfound'));
		forward();
	}
	elgg_push_breadcrumb($group->name);

	elgg_register_title_button();

	group_gatekeeper();

	$title = elgg_echo('item:object:' . $crud_type);
	
	$options = array(
		'type' => 'object',
		'subtype' => $crud_type,
		'limit' => 20,
		'order_by' => 'e.last_action desc',
		'container_guid' => $guid,
		'full_view' => false,
	);

	$content = elgg_view($crud->module.'/general', array('entity'=>$guid));

	$content .= elgg_list_entities($options);
	if (!$content) {
		$content = elgg_echo($crud->module.':none');
	}


	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);

	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * Edit or add a crud object
 *
 * @param string $type 'add' or 'edit'
 * @param int    $guid GUID of group or crud object
 */
function crud_handle_edit_page($crud, $type, $guid) {
	gatekeeper();

	$crud_type = $crud->crud_type;

	if ($type == 'add') {
		$parent = get_entity($guid);
		if ($parent instanceof ElggGroup) {
			$group = $parent;
			$parent = NULL;
		}
		else {
			$group = get_entity($parent->container_guid);
		}
		if (!$group) {
			register_error(elgg_echo('groups:notfound'));
			forward();
		}
		elgg_set_page_owner_guid($group->guid);

		// make sure user has permissions to add a crud object to container
		if (!$group->canWriteToContainer(0, 'object', $crud_type)) {
			register_error(elgg_echo('crud:permissions:error'));
			forward($group->getURL());
		}

		$title = elgg_echo($crud_type . ':add');

		elgg_push_breadcrumb($group->name, $crud_type."/owner/$group->guid");
		elgg_push_breadcrumb($title);

		$body_vars = crud_prepare_form_vars($crud, NULL, $parent);
		$content = elgg_view_form('crud/save', array('crud' => $crud), $body_vars);
	} else {
		$entity = get_entity($guid);
		if (!$entity || !$entity->canEdit()) {
			register_error(elgg_echo('groups:notfound'));
			forward();
		}
		$group = $entity->getContainerEntity();
		if (!$group) {
			register_error(elgg_echo('groups:notfound'));
			forward();
		}
		$parent = get_entity($entity->parent_guid);

		$title = elgg_echo($crud_type . ':edit');

		elgg_push_breadcrumb($group->name, $crud_type . "/owner/$group->guid");
		elgg_push_breadcrumb($entity->title, $entity->getURL());
		elgg_push_breadcrumb($title);

		$body_vars = crud_prepare_form_vars($crud, $entity, $parent);
		$content = elgg_view_form('crud/save', array('crud' => $crud), $body_vars);
	}

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * View a crud object
 *
 * @param int $guid GUID of a crud object
 */
function crud_handle_view_page($crud, $guid) {
	// We now have RSS on assemblies
	global $autofeed;
	$autofeed = true;

	$crud_type = $crud->crud_type;

	$group = get_entity($guid);
	if (!$group) {
		register_error(elgg_echo('noaccess'));
		$_SESSION['last_forward_from'] = current_page_url();
		forward('');
	}

	/*$group = $entity->getContainerEntity();
	if (!$group) {
		register_error(elgg_echo('groups:notfound'));
		forward();
	}*/
	$entity = $group;

	if (!empty($crud->children_type)) {
		elgg_set_page_owner_guid($guid);
		elgg_register_title_button($crud->children_type);
	}

	elgg_set_page_owner_guid($group->container_guid);

	group_gatekeeper();

	elgg_push_breadcrumb($group->name, "$crud_type/owner/$group->guid");
	elgg_push_breadcrumb($entity->title);

	$content = elgg_view_entity($entity, array('full_view' => true));
	
	$content .= elgg_view_comments($entity);

	$params = array(
		'content' => $content,
		'title' => $entity->title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($entity->title, $body);
}

/**
 * Prepare crud object form variables
 *
 * @param ElggObject $object Crud object if editing
 * @return array
 */
function crud_prepare_form_vars($crud, $object = NULL, $parent = NULL) {
	$crud_type = $crud->crud_type;
	// input names => defaults
	$values = array(
		'title' => $object->title,
		'description' => $object->description,
		'access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'crud' => $crud,
		'container_guid' => elgg_get_page_owner_guid(),
		'parent_guid' => $parent->guid,
		'guid' => null,
		'entity' => $object,
	);

	if ($object) {
		foreach (array_keys($values) as $field) {
			if (isset($entity->$field)) {
				$values[$field] = $object->$field;
			}
		}
	}

	if (elgg_is_sticky_form($crud_type)) {
		$sticky_values = elgg_get_sticky_values($crud_type);
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form($crud_type);

	return $values;
}

