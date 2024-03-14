<?php

namespace WPDesk\FlexibleWishlist\Settings\Group;

use WPDesk\FlexibleWishlist\Settings\Option\MenuSelectedOption;

/**
 * {@inheritdoc}
 */
class MenuGroup implements Group {

	/**
	 * {@inheritdoc}
	 */
	public function get_label(): string {
		return __( 'Menu settings', 'flexible-wishlist' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_fields(): array {
		return [
			new MenuSelectedOption(),
		];
	}
}
