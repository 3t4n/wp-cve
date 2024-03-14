<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event\Builtin;

use WPDesk\ShopMagic\Workflow\Components\Groups;
use WPDesk\ShopMagic\Workflow\Event\Event;

abstract class PostCommonEvent extends Event {

	public function get_group_slug(): string {
		return Groups::POST;
	}

	public function get_provided_data_domains(): array {
		return array_merge(
			parent::get_provided_data_domains(),
			[ \WP_Post::class ]
		);
	}

	/**
	 * @return array{post_id: numeric-string|int} Normalized event data required for Queue serialization.
	 */
	public function jsonSerialize(): array {
		return [
			'post_id' => $this->get_post()->ID,
		];
	}

	private function get_post(): \WP_Post {
		return $this->resources->get( \WP_Post::class );
	}

	/**
	 * @param array{post_id: numeric-string} $serialized_json
	 */
	public function set_from_json( array $serialized_json ): void {
		$this->resources->set( \WP_Post::class, get_post( (int) $serialized_json['post_id'] ) );
	}
}
