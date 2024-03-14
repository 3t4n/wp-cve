<?php

namespace WPDesk\FlexibleWishlist\Settings\Group;

use WPDesk\FlexibleWishlist\Settings\Option\ActiveSocialIconsOption;
use WPDesk\FlexibleWishlist\Settings\Option\ItemQuantityEnabledOption;

/**
 * {@inheritdoc}
 */
class WishlistPageGroup implements Group {

	/**
	 * {@inheritdoc}
	 */
	public function get_label(): string {
		return __( 'Wishlist page settings', 'flexible-wishlist' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_fields(): array {
		return [
			new ItemQuantityEnabledOption(),
			new ActiveSocialIconsOption(),
		];
	}
}
