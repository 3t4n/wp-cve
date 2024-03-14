<?php

namespace WPDesk\FlexibleWishlist\Settings\Option;

/**
 * {@inheritdoc}
 */
class TextNotLoggedInUserOption extends OptionTranslated {

	const FIELD_NAME = 'text_not_logged_in_user';

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
		return __( 'Message for not logged in users', 'flexible-wishlist' );
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
		/* translators: %1$s: open anchor tag, %2$s: close anchor tag */
		return __( '%1$sLog in%2$s to save your wishlists and access them on all your devices.', 'flexible-wishlist' );
	}
}
