<?php

namespace WPDesk\FlexibleWishlist\Settings\Option;

/**
 * {@inheritdoc}
 */
abstract class OptionBase implements Option {

	const FIELD_TYPE_INPUT          = 'text';
	const FIELD_TYPE_RADIO          = 'radio';
	const FIELD_TYPE_RADIO_PREVIEW  = 'radio-preview';
	const FIELD_TYPE_TOGGLE         = 'checkbox';
	const FIELD_TYPE_MULTI_CHECKBOX = 'multi-checkbox';
	const FIELD_TYPE_URL            = 'url';

	/**
	 * {@inheritdoc}
	 */
	public function get_description() {
		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_options(): array {
		return [];
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_disabled_options(): array {
		return [];
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_value() {
		return ( in_array( $this->get_type(), [ self::FIELD_TYPE_MULTI_CHECKBOX ], true ) ) ? [] : '';
	}

	/**
	 * {@inheritdoc}
	 */
	public function parse_value( $raw_value ) {
		return $raw_value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function init_translation( $raw_value ) {
	}
}
