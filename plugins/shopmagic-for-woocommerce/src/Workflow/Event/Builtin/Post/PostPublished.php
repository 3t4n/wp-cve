<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event\Builtin\Post;

use WPDesk\ShopMagic\Workflow\Event\Builtin\PostCommonEvent;


final class PostPublished extends PostCommonEvent {
	public function get_id(): string {
		return 'shopmagic_post_published';
	}

	public function get_name(): string {
		return __( 'Post Published', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return __(
			'Run automation when a post is published. Runs every time, post changes from draft to published.',
			'shopmagic-for-woocommerce'
		);
	}

	public function initialize(): void {
		add_action(
			'transition_post_status',
			function ( string $new_status, string $old_status, \WP_Post $post ) {
				$this->process_event( $new_status, $old_status, $post );
			},
			10,
			3
		);
	}

	public function process_event( string $new_status, string $old_status, \WP_Post $post ): void {
		$this->resources->set( \WP_Post::class, $post );
		if ( 'publish' !== $new_status ) {
			return;
		}
		if ( 'publish' === $old_status ) {
			return;
		}
		if ( 'post' !== $post->post_type ) {
			return;
		}
		$this->trigger_automation();
	}
}
