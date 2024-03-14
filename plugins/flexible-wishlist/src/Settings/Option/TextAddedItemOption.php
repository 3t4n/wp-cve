<?php

namespace WPDesk\FlexibleWishlist\Settings\Option;

/**
 * {@inheritdoc}
 */
class TextAddedItemOption extends OptionTranslated {

	const FIELD_NAME = 'text_added_item';

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
		return __( 'Added to wishlist status', 'flexible-wishlist' );
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
		return __( 'Added to wishlist', 'flexible-wishlist' );
	}
}
