<?php

namespace WPDesk\FlexibleWishlist\Settings\Option;

/**
 * {@inheritdoc}
 */
class IconPositionListOption extends OptionBase {

	const FIELD_NAME         = 'icon_position_list';
	const VALUE_ON_IMAGE     = 'on_image';
	const VALUE_ABOVE_BUTTON = 'add_to_cart_above';
	const VALUE_BELOW_BUTTON = 'add_to_cart_below';
	const VALUE_LEFT_BUTTON  = 'add_to_cart_left';
	const VALUE_RIGHT_BUTTON = 'add_to_cart_right';

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
		return __( 'Position of the icon on the shop page', 'flexible-wishlist' );
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
			self::VALUE_ON_IMAGE     => __( 'Product image overlay', 'flexible-wishlist' ),
			self::VALUE_ABOVE_BUTTON => __( 'Above the Add to Cart button', 'flexible-wishlist' ),
			self::VALUE_BELOW_BUTTON => __( 'Below the Add to Cart button', 'flexible-wishlist' ),
			self::VALUE_LEFT_BUTTON  => __( 'On the left side of the Add to cart button', 'flexible-wishlist' ),
			self::VALUE_RIGHT_BUTTON => __( 'On the right side of the Add to cart button', 'flexible-wishlist' ),
		];
	}
}
