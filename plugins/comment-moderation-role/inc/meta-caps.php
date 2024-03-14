<?php
/**
 * @package           WpbCommentModeration
 * @subpackage        Meta caps.
 * @author            WPBeginner
 * @copyright         2021 WPBeginner
 * @license           GPL-2.0-or-later
 */

namespace WPB\CommentModerationRole\MetaCaps;

use WPB\CommentModerationRole;

/**
 * Kick off alterations to meta caps.
 *
 * Runs as the plugin is required by WP.
 */
function bootstrap() {
	add_filter( 'map_meta_cap', __NAMESPACE__ . '\\filter_map_meta_caps', 10, 4 );
}

/**
 * Modify meta caps for comment related checks.
 *
 * Modifies the meta caps to use the custom primitive instead of edit_posts.
 *
 * @param string[] $caps    Primitive capabilities required of the user.
 * @param string   $cap     Capability being checked.
 * @param int      $user_id The user ID.
 * @param array    $args    Adds context to the capability check, typically
 *                          starting with an object ID.
 * @return string[] Modified primitive caps required of the user.
 */
function filter_map_meta_caps( $caps, $cap, $user_id, $args ) {
	if ( 'edit_comment' !== $cap ) {
		// Only modifying rules for edit_comment.
		return $caps;
	}

	$comment = get_comment( $args[0] );
	if ( ! $comment ) {
		$caps[] = 'do_not_allow';
		return $caps;
	}

	$post = get_post( $comment->comment_post_ID );

	if ( ! $post ) {
		// Orphan comment, do not change.
		return $caps;
	}

	$post_type = get_post_type_object( $post->post_type );
	if ( ! $post_type ) {
		// Unregistered post type, do not change.
		return $caps;
	}

	if ( CommentModerationRole\is_post_publicly_viewable( $post ) ) {
		if ( (int) $post->post_author === $user_id ) {
			/*
			 * The user only needs limited caps if they authored the post.
			 *
			 * This allows low-privileged users to moderate comments on their
			 * own posts once the post has been published at which time they
			 * may lose the `edit_post` meta capability if they do not have
			 * the `edit_published_posts` primitive.
			 *
			 * In addition to permission to read the post, the user needs the
			 * `edit_posts` (plural, primitive) capability for the post type
			 * to determine that they can really be the author of said post.
			 */
			$caps   = map_meta_cap( 'read_post', $user_id, $post->ID );
			$caps[] = $post_type->cap->edit_posts;
			return $caps;
		}

		// Post exists, is a public post: replace caps.
		$caps = array( CommentModerationRole\moderator_cap() );
	}

	return $caps;
}
