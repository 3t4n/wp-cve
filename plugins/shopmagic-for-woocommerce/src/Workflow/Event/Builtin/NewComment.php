<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event\Builtin;

final class NewComment extends \WPDesk\ShopMagic\Workflow\Event\Event {

	public function initialize(): void {
		add_action(
			'comment_post',
			function ( int $comment_id, $comment_approved, array $comment_data ) {
				$this->on_new_comment( $comment_id, $comment_approved, $comment_data );
			},
			10,
			3
		);
	}

	public function on_new_comment( int $comment_id, $comment_approved, array $comment_data ): void {
		$this->trigger_automation();
	}

	public function set_from_json( array $serialized_json ): void {
	}

	public function get_group_slug(): string {
		return 'comment';
	}

	public function get_name(): string {
		return esc_html__( 'New comment', 'shopmagic-for-woocommerce' );
	}

	/**
	 * @return mixed[]
	 */
	public function jsonSerialize(): array {
		return [];
	}
}
