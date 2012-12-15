<?php
/**
 * Elgg groups plugin language pack
 *
 * @package ElggGroupAlias
 */

$english = array(

	/**
	 * Menu items and titles
	 */
	'groups:alias' => "Group alias",
	'groups:alias:already_taken' => "This group alias is already pointing to another group",
	'groups:alias:registration:usernametooshort' => 'Group alias must be a minimum of %u characters long.',
	'groups:alias:registration:usernametoolong' => "Group alias is too long. It can have a maximum of %u characters.",
	'groups:alias:registration:invalidchars' => "Sorry, your group alias contains the character %s which is invalid. The following characters are invalid: %s",
	'groups:alias:registration:invalidctrlchars' => "Sorry, your group alias contains control or non-printable characters.",
	'groups:alias:changeable' => "Allow changing a group's alias?",
	'groups:alias:changeable:may_break_urls' => 'Warning: this can lead to broken URLs when the alias is well-established.',
);

add_translation("en", $english);
