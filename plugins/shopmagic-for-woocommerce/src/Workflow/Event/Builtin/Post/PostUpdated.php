<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event\Builtin\Post;

final class PostUpdated extends \WPDesk\ShopMagic\Workflow\Event\Builtin\PostCommonEvent {
	public function get_id(): string {
		return 'shopmagic_post_updated';
	}

	public function initialize(): void {
		add_action(
			'post_updated',
			function ( int $_, \WP_Post $post_after ): void {
				$this->on_post_updated( $post_after );
			},
			10,
			2
		);
	}

	public function on_post_updated( \WP_Post $post_after ): void {
		$this->resources->set( \WP_Post::class, $post_after );
		if ( $post_after->post_type !== 'post' ) {
			return;
		}
		$this->trigger_automation();
	}

	public function get_name(): string {
		return esc_html__( 'Post updated', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return __( 'Run automation when a post is updated. Any change in post (content, title, slug) triggers this automation.', 'shopmagic-for-woocommerce' );
	}
}
