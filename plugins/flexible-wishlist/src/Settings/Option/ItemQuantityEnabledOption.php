<?php

namespace WPDesk\FlexibleWishlist\Settings\Option;

/**
 * {@inheritdoc}
 */
class ItemQuantityEnabledOption extends OptionBase {

	const FIELD_NAME = 'text_item_quantity_enabled';

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
		return __( 'Enable the Quantity column', 'flexible-wishlist' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_type(): string {
		return OptionBase::FIELD_TYPE_TOGGLE;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_value() {
		return '1';
	}
}
