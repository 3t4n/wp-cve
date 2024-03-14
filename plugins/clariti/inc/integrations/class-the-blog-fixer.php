<?php
/**
 * Manages Clariti's integration with The Blog Fixer.
 *
 * @package Clariti
 */

namespace Clariti\Integrations;

use Clariti\Notifier;

/**
 * Manages Clariti's integration with The Blog Fixer.
 */
class The_Blog_Fixer {

	/**
	 * Fires after an operation has been performed on a post.
	 *
	 * @param object $po   Post operation object.
	 * @param object $post Post.
	 */
	public static function action_tbf_after_post_operation_execution( $po, $post ) {
		if ( ! empty( $post->ID ) && 'publish' === $post->post_status ) {
			Notifier::send_event_to_clariti(
				'post',
				Notifier::UPDATE_ACTION,
				$post->ID
			);
		}
	}
}
