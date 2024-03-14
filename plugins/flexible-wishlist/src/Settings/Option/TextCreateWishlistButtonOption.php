<?php

namespace WPDesk\FlexibleWishlist\Settings\Option;

/**
 * {@inheritdoc}
 */
class TextCreateWishlistButtonOption extends OptionTranslated {

	const FIELD_NAME = 'text_create_wishlist_button';

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
		return __( 'Create wishlist button', 'flexible-wishlist' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_type(): string {
		return OptionBase::FIELD_TYPE_INPUT;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_value() {
		return __( 'Create', 'flexible-wishlist' );
	}
}
