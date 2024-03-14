<?php
/**
 * @package           WpbCommentModeration
 * @subpackage        Role creation.
 * @author            WPBeginner
 * @copyright         2021 WPBeginner
 * @license           GPL-2.0-or-later
 */

namespace WPB\CommentModerationRole\RolesCaps;

use WPB\CommentModerationRole;

/**
 * Kick off role creation.
 *
 * This is run as the plugin is required.
 */
function bootstrap() {
	add_action( 'plugins_loaded', __NAMESPACE__ . '\\maybe_add_role' );
	add_action( 'plugins_loaded', __NAMESPACE__ . '\\maybe_add_cap' );
}

/**
 * Add the moderator role if it does not exist.
 *
 * Creates the moderator role based off the subscriber capabilities
 * and additionally grants them the custom comment moderator capability.
 */
function maybe_add_role() {
	$role_obj = get_role( CommentModerationRole\moderator_role_slug() );
	if ( $role_obj ) {
		// No need to create the role.
		return;
	}

	$subscriber_role = get_role( 'subscriber' );
	if ( ! $subscriber_role ) {
		// No roles yet.
		return;
	}

	add_role(
		CommentModerationRole\moderator_role_slug(),
		CommentModerationRole\moderator_role_name(),
		array_merge(
			$subscriber_role->capabilities,
			array( CommentModerationRole\moderator_cap() => true )
		)
	);
}

/**
 * Add the moderator capability to the role if needed.
 *
 * Checks if the moderator role has the required capability and
 * adds it to the role if it does not.
 */
function maybe_add_cap() {
	$role_obj = get_role( CommentModerationRole\moderator_role_slug() );
	if ( ! $role_obj ) {
		// No role to add cap too.
		return;
	}

	if ( $role_obj->has_cap( CommentModerationRole\moderator_cap() ) ) {
		// The cap is already set.
		return;
	}

	$role_obj->add_cap( CommentModerationRole\moderator_cap() );

	/*
	 * Refresh current users caps if they area moderator.
	 *
	 * This needs to check their role rather than the capability as the
	 * purpose of this function is the add the capability if it is not already
	 * set.
	 */
	if ( is_user_logged_in() && current_user_can( CommentModerationRole\moderator_role_slug() ) ) {
		wp_get_current_user()->get_role_caps();
	}
}
