<?php

namespace WPDesk\FlexibleWishlist\Settings\Option;

/**
 * {@inheritdoc}
 */
abstract class OptionTranslated extends OptionBase {

	/**
	 * {@inheritdoc}
	 */
	public function parse_value( $raw_value ) {
		if ( function_exists( 'pll__' ) ) {
			return pll__( $raw_value );
		}
		return __( $raw_value, 'flexible-wishlist' ); // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
	}

	/**
	 * {@inheritdoc}
	 */
	public function init_translation( $raw_value ) {
		if ( function_exists( 'pll_register_string' ) ) {
			pll_register_string( $raw_value, $raw_value, __( 'Flexible Wishlist', 'flexible-wishlist' ) );
		}
		if ( function_exists( 'icl_register_string' ) ) {
			icl_register_string( 'flexible-wishlist', $raw_value, $raw_value );
		}
	}
}
