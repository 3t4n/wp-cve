<?php

namespace WPDesk\FlexibleWishlist\Settings\Option;

/**
 * {@inheritdoc}
 */
class TextArchiveTitleOption extends OptionTranslated {

	const FIELD_NAME = 'text_archive_title';

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
		return __( 'Wishlist page title', 'flexible-wishlist' );
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
		return __( 'My wishlists', 'flexible-wishlist' );
	}
}
