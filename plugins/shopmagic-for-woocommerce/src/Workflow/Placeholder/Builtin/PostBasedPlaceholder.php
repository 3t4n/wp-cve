<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin;

use WPDesk\ShopMagic\Workflow\Components\Groups;
use WPDesk\ShopMagic\Workflow\Placeholder\Placeholder;

abstract class PostBasedPlaceholder extends Placeholder {
	public function get_group_slug(): string {
		return Groups::POST;
	}

	public function get_required_data_domains(): array {
		return [ \WP_Post::class ];
	}

	protected function get_post(): \WP_Post {
		return $this->resources->get( \WP_Post::class );
	}
}
