<?php

namespace WPDesk\FlexibleWishlist\Settings\Option;

/**
 * {@inheritdoc}
 */
class TextArchiveUrlOption extends OptionBase {

	const FIELD_NAME = 'text_archive_url';

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
		return __( 'Wishlist page URL', 'flexible-wishlist' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_description() {
		return __( 'Use alphanumeric characters, underscore (_) and dash (-).', 'flexible-wishlist' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_type(): string {
		return OptionBase::FIELD_TYPE_URL;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_value() {
		return 'flexible-wishlist';
	}

	/**
	 * {@inheritdoc}
	 */
	public function parse_value( $raw_value ) {
		$valid_value = trim( preg_replace( '#[^a-z-_]#', '', $raw_value ), '-_' );
		if ( $valid_value === '' ) {
			return $this->get_default_value();
		}
		return $valid_value;
	}
}
