<?php
/**
 * Federated Objects -- Federated Article
 *
 * @package        Lorea
 * @subpackage     FederatedObjects
 *
 * Copyright 2012-2013 Lorea Faeries <federation@lorea.org>
 *
 * This file is part of the FederatedObjects plugin for Elgg.
 *
 * FederatedObjects is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Affero General Public License
 * as published by the Free Software Foundation, either version 3 of
 * the License, or (at your option) any later version.
 *
 * FederatedObjects is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this program. If not, see
 * <http://www.gnu.org/licenses/>.
 */

class FederatedArticle {
	public static function create($params, $entity, $tag) {
		$owner = $params['owner_entity'];
		$entry = $params['entry'];
		$notification = $params['notification'];
		$access_id = ACCESS_PUBLIC;

		// specific fields
		$excerpt = $notification->xpath(array("$tag/atom:summary", "atom:summary"));
		$body = $notification->getBody();

		if (empty($entity)) {
			$access = elgg_set_ignore_access(true);

			$entity = new ElggObject;
			// regular fields
			$entity->owner_guid = $owner->getGUID();
			$entity->access_id = $access_id;
			$entity->subtype = "blog";
			if (isset($params['container_entity'])) {
				$entity->container_guid = $params['container_entity']->getGUID();
			}
			$entity->title = $params['name'];
			$entity->description = $body;
			if ($params['tags'])
				$entity->tags = $params['tags'];

			// blog fields
			$entity->excerpt = $excerpt;
			$entity->status = "published";

			// atom fields
			$entity->atom_id = $params['id'];
			$entity->atom_link = $params['link'];
			$entity->foreign = true;
			if ($entity->save()) {
				if ($notification->getVerb() == 'post') {
   			        	$id = add_to_river('river/object/blog/create','create', $owner->getGUID(), $entity->getGUID());
					AtomRiverMapper::setIDMapping($id, $notification->getID(), $notification->provenance);
			        }
			}

			elgg_set_ignore_access($access);
		}
		return $entity;
	}
	public static function url($object) {
		if ($object->atom_link)
			return $object->atom_link;
		return blog_url_handler($object);
	}
}

