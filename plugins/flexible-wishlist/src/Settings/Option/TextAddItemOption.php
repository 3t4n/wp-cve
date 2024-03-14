<?php

namespace WPDesk\FlexibleWishlist\Settings\Option;

/**
 * {@inheritdoc}
 */
class TextAddItemOption extends OptionTranslated {

	const FIELD_NAME = 'text_add_item';

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
		return __( 'Add to wishlist button', 'flexible-wishlist' );
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
		return __( 'Add to wishlist', 'flexible-wishlist' );
	}
}
