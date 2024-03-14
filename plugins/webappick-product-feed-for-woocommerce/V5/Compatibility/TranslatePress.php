<?php

namespace CTXFeed\V5\Compatibility;

class TranslatePress {
	public function __construct() {
		$filters_with_param_3 = apply_filters( 'woo_feed_translatepress_attributes_filters_list_param_3', [
			'woo_feed_filter_product_title',
			'woo_feed_filter_product_description',
			'woo_feed_filter_product_parent_title',
			'woo_feed_filter_product_description_with_html',
			'woo_feed_filter_product_short_description',
			'woo_feed_filter_product_parent_link',
			'woo_feed_filter_product_ex_link',
			'woo_feed_filter_product_feature_image',
			'woo_feed_filter_product_add_to_cart_link',
			'woo_feed_filter_product_yoast_wpseo_title',
			'woo_feed_filter_product_yoast_wpseo_metadesc',
			'woo_feed_filter_product_yoast_canonical_url',
			'woo_feed_filter_product_rank_math_title',
			'woo_feed_filter_product_rank_math_description',
			'woo_feed_filter_product_rank_math_canonical_url',
			'woo_feed_filter_product_aioseop_title',
			'woo_feed_filter_product_aioseop_description',
			'woo_feed_filter_product_aioseop_canonical_url',
		] );
		foreach ( $filters_with_param_3 as $filter ) {
			add_filter( $filter, [ $this, 'woo_feed_get_tp_translate_with_param_3' ], 10, 3 );
		}

		$filters_with_param_4 = apply_filters( 'woo_feed_translatepress_attributes_filters_list_param_4', [
			'woo_feed_filter_product_link',
		] );

		foreach ( $filters_with_param_4 as $filter ) {
			add_filter( $filter, [ $this, 'woo_feed_get_tp_translate_with_param_4' ], 10, 4 );
		}
	}

	/**
	 * @param $output
	 * @param $product
	 * @param $config
	 *
	 * @return mixed|string
	 */
	public function woo_feed_get_tp_translate_with_param_3( $output, $product, $config ) {
		return self::translate_string( $output, $config );
	}


	/**
	 * @param $output
	 * @param $product
	 * @param $feedrules
	 * @param $config
	 *
	 * @return mixed|string
	 */
	public function woo_feed_get_tp_translate_with_param_4( $output, $product, $feedrules, $config ) {
		return self::translate_string( $output, $config );
	}

	/**
	 * @param $output
	 * @param $config
	 *
	 * @return mixed|string
	 */
	private static function translate_string( $output, $config ) {
		global $CTX_TRP_RENDERER;
		$feed_language = $config->get_feed_language();
		if ( $feed_language && $CTX_TRP_RENDERER ) {
			// Remove empty strings.
			$translatable_strings = array_filter( explode( PHP_EOL, $output ), function ( $paragragh ) {
				if ( strlen( $paragragh ) > 1 ) {
					return $paragragh;
				}
			} );

			$translated_strings   = [];
			$translatable_strings = array_values( $translatable_strings );
			foreach ( $translatable_strings as $translatable_string ) {
				$translatable_string = trim( $translatable_string );
				$translated_string   = $CTX_TRP_RENDERER->process_strings( [ $translatable_string ], $feed_language );
				if ( count( $translated_string ) ) {
					$translated_strings[] = $translated_string[0];
				}
			}

			if ( count( $translated_strings ) ) {
				$output = implode( ' ', $translated_strings );
			}

			if ( self::should_modify_url( $feed_language ) ) {
				$output = self::get_modified_url( $output, $feed_language );
			}
		}

		return $output;
	}


	/**
	 * @param $feed_language
	 *
	 * @return bool
	 */
	private static function should_modify_url( $feed_language ) {
		global $TRP_LANGUAGE;

		return $feed_language != $TRP_LANGUAGE;
	}

	/**
	 * @param $output
	 * @param $feed_language
	 *
	 * @return mixed|string
	 */
	private static function get_modified_url( $output, $feed_language ) {
		$exploded_output = explode( home_url(), $output );
		if ( count( $exploded_output ) > 1 ) {
			$output = home_url() . self::get_url_slug( $feed_language ) . explode( home_url(), $output )[1];
		}

		return $output;
	}

	/**
	 * @param $feed_language
	 *
	 * @return string
	 */
	private static function get_url_slug( $feed_language ) {
		global $CTX_TRP_Url_Converter;
		$slug = $CTX_TRP_Url_Converter->get_url_slug( $feed_language );

		return $slug ? '/' . $slug : '';
	}

}
