<?php

namespace WPDesk\FlexibleWishlist\Settings\Group;

use WPDesk\FlexibleWishlist\Settings\Option\IconPositionListOption;
use WPDesk\FlexibleWishlist\Settings\Option\IconPositionProductOption;
use WPDesk\FlexibleWishlist\Settings\Option\IconTypeOption;

/**
 * {@inheritdoc}
 */
class PopupGroup implements Group {

	/**
	 * {@inheritdoc}
	 */
	public function get_label(): string {
		return __( 'Basic settings', 'flexible-wishlist' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_fields(): array {
		return [
			new IconPositionListOption(),
			new IconPositionProductOption(),
			new IconTypeOption(),
		];
	}
}
