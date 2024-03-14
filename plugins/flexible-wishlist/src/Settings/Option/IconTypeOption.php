<?php

namespace WPDesk\FlexibleWishlist\Settings\Option;

/**
 * {@inheritdoc}
 */
class IconTypeOption extends OptionBase {

	const FIELD_NAME = 'icon_type';

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
		return __( 'Icon type', 'flexible-wishlist' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_type(): string {
		return OptionBase::FIELD_TYPE_RADIO_PREVIEW;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_value() {
		return 'heart';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_options(): array {
		return [
			'bulb'          => __( 'Bulb', 'flexible-wishlist' ),
			'bookmark'      => __( 'Bookmark', 'flexible-wishlist' ),
			'chef-hat'      => __( 'Chef hat', 'flexible-wishlist' ),
			'crown'         => __( 'Crown', 'flexible-wishlist' ),
			'cube'          => __( 'Cube', 'flexible-wishlist' ),
			'first-aid-kit' => __( 'First Aid Kit', 'flexible-wishlist' ),
			'flag'          => __( 'Flag', 'flexible-wishlist' ),
			'flame'         => __( 'Flame', 'flexible-wishlist' ),
			'flower'        => __( 'Flower', 'flexible-wishlist' ),
			'footprint'     => __( 'Footprint', 'flexible-wishlist' ),
			'gift'          => __( 'Gift', 'flexible-wishlist' ),
			'heart'         => __( 'Heart', 'flexible-wishlist' ),
			'heart-2'       => __( 'Heart 2', 'flexible-wishlist' ),
			'home'          => __( 'Home', 'flexible-wishlist' ),
			'star'          => __( 'Star', 'flexible-wishlist' ),
			'student-cap'   => __( 'Student cap', 'flexible-wishlist' ),
			'pin'           => __( 'Pin', 'flexible-wishlist' ),
			'plus'          => __( 'Plus', 'flexible-wishlist' ),
			'tag'           => __( 'Tag', 'flexible-wishlist' ),
			'toggle'        => __( 'Toggle', 'flexible-wishlist' ),
		];
	}
}
