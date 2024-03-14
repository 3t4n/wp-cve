<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event\Builtin\Comment;

use WPDesk\ShopMagic\Workflow\Components\Groups;

final class CommentAdded extends \WPDesk\ShopMagic\Workflow\Event\Event {
	public function get_id(): string {
		return 'shopmagic_comment_added';
	}

	public function initialize(): void {
		add_action( 'comment_post', function ( int $comment_id, $approved ): void {
			$this->on_comment_published( $comment_id, $approved );
		}, 10, 2 );
	}

	private function on_comment_published( int $comment_id, $approved ): void {
		if ( (int) $approved !== 1 ) {
			return;
		}
		$this->resources->set( \WP_Comment::class, get_comment( $comment_id ) );
		$this->trigger_automation();
	}

	public function set_from_json( array $serialized_json ): void {
		$this->resources->set( \WP_Comment::class, get_comment( $serialized_json['comment_id'] ) );
	}

	public function jsonSerialize(): array {
		return [
			'comment_id' => $this->resources->get( \WP_Comment::class )->comment_ID,
		];
	}

	public function get_group_slug(): string {
		return Groups::COMMENT;
	}

	public function get_name(): string {
		return __( 'Comment published', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return esc_html__( 'Run automation when a comment is added to any of your posts. Runs only for approved comments.', 'shopmagic-for-woocommerce' );
	}
}
