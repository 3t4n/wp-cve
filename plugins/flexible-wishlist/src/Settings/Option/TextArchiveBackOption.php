<?php

namespace WPDesk\FlexibleWishlist\Settings\Option;

/**
 * {@inheritdoc}
 */
class TextArchiveBackOption extends OptionTranslated {

	const FIELD_NAME = 'text_archive_back';

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
		return __( 'Return button', 'flexible-wishlist' );
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
		return __( 'Back to all wishlists', 'flexible-wishlist' );
	}
}
