<?php

namespace WPDesk\FlexibleWishlist\Settings\Option;

/**
 * {@inheritdoc}
 */
class IconPositionProductOption extends OptionBase {

	const FIELD_NAME         = 'icon_position_product';
	const VALUE_ABOVE_BUTTON = 'add_to_cart_above';
	const VALUE_BELOW_BUTTON = 'add_to_cart_below';

	/**
	 * {@inheritdoc}
	 */
	public function get_name(): string {
		return self::FIELD_NAME;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_label(): string {
		return __( 'Position of the icon on the product page', 'flexible-wishlist' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_type(): string {
		return OptionBase::FIELD_TYPE_RADIO;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_value() {
		return 'add_to_cart_below';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_options(): array {
		return [
			self::VALUE_ABOVE_BUTTON => __( 'Above the Add to Cart button', 'flexible-wishlist' ),
			self::VALUE_BELOW_BUTTON => __( 'Below the Add to Cart button', 'flexible-wishlist' ),
		];
	}
}
