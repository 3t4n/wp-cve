<?php
/**
 * Compatibility class for TranslatePress
 *
 * @package CTXFeed\V5\Compatibility
 */

namespace CTXFeed\V5\Compatibility;

/**
 * Class TranslatePress
 *
 * @package CTXFeed\V5\Compatibility
 */
class TRP_Translate_PressCompatibility {

	/**
	 * @var \TRP_Translation_Render
	 */
	private $translatepress_renderer;

	/**
	 * @var \TRP_Url_Converter
	 */
	private $translatepress_url_converter;

	/**
	 * TranslatePress constructor.
	 */
	public function __construct() {
		/**
		 * TRP_Settings and TRP_Translation_Render must be instantiated here because.
		 * If both class instantiated at AttributeValueByType class then for every attribute will create
		 * a new instance of TRP_Translation_Render which is unnecessary and time/memory consuming.
		 */
		if (
			! class_exists( 'TRP_Settings' )
			|| ! class_exists( 'TRP_Translation_Render' )
			|| ! class_exists( 'TRP_Url_Converter' )
		) {
			include_once WP_PLUGIN_DIR . '/translatepress-multilingual/includes/external-functions.php';
			include_once WP_PLUGIN_DIR . '/translatepress-multilingual/includes/class-settings.php';
			include_once WP_PLUGIN_DIR . '/translatepress-multilingual/includes/class-translation-render.php';
			include_once WP_PLUGIN_DIR . '/translatepress-multilingual/includes/class-url-converter.php';
			include_once WP_PLUGIN_DIR . '/translatepress-multilingual/includes/queries/class-query.php';
		}

		$settings = ( new \TRP_Settings )->get_settings();

		$this->trp_settings = $settings;

		$this->translatepress_renderer      = new \TRP_Translation_Render( $settings );
		$this->translatepress_url_converter = new \TRP_Url_Converter( $settings );


		/**
		 * Apply the filter if any of the attribute is not translated by translatepress.
		 * Just add the attribute filter name in the array applied in the class ProductInfo.php
		 */
		$filters_with_param_3 = apply_filters(
			'woo_feed_translatepress_attributes_filters_list',
			array(
				'woo_feed_filter_product_title',
				'woo_feed_filter_product_parent_title',
				'woo_feed_filter_product_yoast_wpseo_title',
				'woo_feed_filter_product_rank_math_title',
				'woo_feed_filter_product_aioseop_title',
			)
		);

		$filters_description_with_param_3 = apply_filters(
			'woo_feed_translatepress_attributes_filters_list',
			array(
				'woo_feed_filter_product_description',
				'woo_feed_filter_product_description_with_html',
				'woo_feed_filter_product_short_description',
				'woo_feed_filter_product_yoast_wpseo_metadesc',
				'woo_feed_filter_product_rank_math_description',
				'woo_feed_filter_product_aioseop_description',
			)
		);

		foreach ( $filters_with_param_3 as $filter ) {
			add_filter( $filter, array( $this, 'trp_translate_strings' ), 999, 3 );
		}

		foreach ( $filters_description_with_param_3 as $filter ) {
			add_filter( $filter, array( $this, 'trp_translate_strings' ), 999, 3 );
		}

		/**
		 * Apply the filter if any of the attribute is not translated by translatepress.
		 * Just add the attribute filter name in the array applied in the class ProductInfo.php
		 */
		$urls = apply_filters(
			'woo_feed_translatepress_attributes_url_list',
			array(
				'woo_feed_filter_product_yoast_canonical_url',
				'woo_feed_filter_product_rank_math_canonical_url',
				'woo_feed_filter_product_aioseop_canonical_url',
				'woo_feed_filter_product_link',
				'woo_feed_filter_product_parent_link',
				'woo_feed_filter_product_canonical_link',
				'woo_feed_filter_product_ex_link',
				'woo_feed_filter_product_add_to_cart_link',
			)
		);

		foreach ( $urls as $filter ) {
			add_filter( $filter, array( $this, 'get_tp_translate_url' ), 999, 3 );
		}
	}

	/**
	 *  Get the translated string.
	 *
	 * @param string $output The output string.
	 * @param \WC_Product $product The product object.
	 * @param \CTXFeed\V5\Utility\Config $config The config object.
	 *
	 * @return string
	 */
	public function get_tp_translate( $output, $product, $config ) { // phpcs:ignore
		return $this->translate_string( $output, $config, $product );
	}

	/**
	 *  Get the translated string.
	 *
	 * @param string $output The output string.
	 * @param \WC_Product $product The product object.
	 * @param \CTXFeed\V5\Utility\Config $config The config object.
	 *
	 * @return string
	 */
	public function get_tp_translate_for_description( $output, $product, $config ) { // phpcs:ignore
		return $this->translate_desc_string( $output, $config, $product );
	}

	/**
	 * Get the translated string.
	 *
	 * @param string $output The output string.
	 * @param \CTXFeed\V5\Utility\Config $config The config object.
	 * @param \WC_Product $product The product object.
	 *
	 * @return string
	 */
	public function translate_string( $output, $config, $product ) { // phpcs:ignore
		global $wpdb;

		$feed_language = $config->get_feed_language();
		$table_name    = $wpdb->prefix . 'trp_dictionary_' . strtolower( $this->trp_settings['default-language'] ) . '_' . strtolower( $feed_language );

		/**
		 * If the feed language is same as the default language or the table does not exist then return the output.
		 * If the table does not exist then it means the language is not translated.
		 * If the feed language is same as the default language then it means the language is not translated.
		 */
		if (
			! $feed_language
			|| $feed_language === $this->trp_settings['default-language']
			|| ! $this->table_exists( $table_name )
		) {
			return $output;
		}

		if ( $this->translatepress_renderer ) {
			// Remove empty strings.
			$translatable_strings = self::get_translatable_strings( $output );

			$translatable_strings = array_values( $translatable_strings );
			$translated_strings   = array();

			foreach ( $translatable_strings as $string ) { // phpcs:ignore
				// Prepare the SQL query
				// phpcs:ignore
				$query = $wpdb->prepare( // phpcs:ignore
					"SELECT translated, MATCH (original) AGAINST (%s IN NATURAL LANGUAGE MODE) AS score
        FROM {$table_name}
        WHERE `original`= %s AND status != 0 LIMIT 1",
					$string,
					$string
				);
				// phpcs:ignore
				// Execute the query
				$result = $wpdb->get_results( $query ); // phpcs:ignore

				// Check if there is a result and if it has a translated string
				if ( ! count( $result ) || ! isset( $result[0]->translated ) || trim( $result[0]->translated ) === '' ) {
					continue;
				}

				$translated_strings[] = $result[0]->translated;
			}

			// If the translated strings array is not empty then implode the array with space.
			if ( count( $translated_strings ) ) {
				$output = implode( ' ', $translated_strings );
			}
		}

		return $output;
	}

	/**
	 * Get the translated string.
	 *
	 * @param string $output The output string.
	 * @param \CTXFeed\V5\Utility\Config $config The config object.
	 * @param \WC_Product $product The product object.
	 *
	 * @return string
	 */
	public function translate_desc_string( $output, $config, $product ) { // phpcs:ignore
		global $wpdb;
		$feed_language = $config->get_feed_language();
		$table_name    = $wpdb->prefix . 'trp_dictionary_' . strtolower( $this->trp_settings['default-language'] ) . '_' . strtolower( $feed_language );

		/**
		 * If the feed language is same as the default language or the table does not exist then return the output.
		 * If the table does not exist then it means the language is not translated.
		 * If the feed language is same as the default language then it means the language is not translated.
		 */
		if (
			! $feed_language
			|| $feed_language === $this->trp_settings['default-language']
			|| ! $this->table_exists( $table_name )
			|| $output == ''
		) {
			return $output;
		}

		$translated_strings_new = [];
		if ( $this->translatepress_renderer ) {
			// Remove empty strings.
			$product_id                = $product->get_id();
			$trp_original_meta_ids_sql = $wpdb->prepare( // phpcs:ignore
				"SELECT `original_id` FROM `{$wpdb->prefix}trp_original_meta` WHERE `meta_key` = %s AND `meta_value` =  %d ORDER BY `meta_id` ASC",
				'post_parent_id',
				$product_id
			);
			$trp_original_meta_ids     = $wpdb->get_results( $trp_original_meta_ids_sql, ARRAY_A ); // phpcs:ignore

			if ( count( $trp_original_meta_ids ) ) {
				$ids = array();
				foreach ( $trp_original_meta_ids as $value ) {
					array_push( $ids, $value['original_id'] );
				}
				if ( count( $ids ) < 1 ) {
					return $output;
				}
				$ids_str                = implode( ', ', $ids );
				$trp_dictionary_sql     = "SELECT `original`,`translated` FROM {$table_name} WHERE `original_id` IN ({$ids_str})";
				$trp_dictionary_strings = $wpdb->get_results( $trp_dictionary_sql ); // phpcs:ignore

				$translated_strings_new = array();
				foreach ( $trp_dictionary_strings as $key => $string ) {
					if ( $string->translated === '' ) {
						$translated_strings_new[] = $string->original;
					} else {
						$translated_strings_new[] = $string->translated;
					}
				}
			} else {
				$translated_strings_new[] = $this->translate_string( $output, $config, $product );
			}

			// If the translated strings array is not empty then implode the array with space.
			if ( count( $translated_strings_new ) ) {
				$output = implode( ' ', $translated_strings_new );
			}
		}

		return $output;
	}

	/**
	 * Get the translated string.
	 *
	 * @param string $output The output string.
	 * @param \WC_Product $product The product object.
	 * @param \CTXFeed\V5\Utility\Config $config The config object.
	 *
	 * @return string
	 */
	public function trp_translate_strings( $output, $product, $config ) { // phpcs:ignore
		$original_output        = $output;
		$strings                = self::get_translatable_strings( $output );
		$strings                = array_map( 'trp_full_trim', $strings );
		$translated_strings_new = $this->translatepress_renderer->process_strings( array_values( $strings ), $config->get_feed_language() );
		// If the translated strings array is not empty then implode the array with space.
		if ( count( $translated_strings_new ) ) {
			$output = implode( ' ', $translated_strings_new );
			$output = trim( $output );
		}


		if ( $output == '' ) {
			$output = $original_output;
		}

		return $output;

	}


	/**
	 * Get the translated string.
	 *
	 * @param string $output The output string.
	 * @param \WC_Product $product The product object.
	 * @param \CTXFeed\V5\Utility\Config $config The config object.
	 *
	 * @return string
	 */
	public function get_tp_translate_url( $output, $product, $config ) { // phpcs:ignore
		$feed_language = $config->get_feed_language();

		// If the url should be modified then modify the url.
		if ( $this->should_modify_url( $feed_language ) ) {
			$output = $this->get_modified_url( $output, $feed_language );
		}

		return $output;
	}

	/**
	 * Check if the url should be modified.
	 *
	 * @param string $feed_language The feed language.
	 *
	 * @return bool
	 */
	private function should_modify_url( $feed_language ) {
		global $TRP_LANGUAGE;// phpcs:ignore

		return $feed_language !== $TRP_LANGUAGE;// phpcs:ignore
	}

	/**
	 * Get the modified url with the slug if the url is not translated.
	 *
	 * @param string $output The output string.
	 * @param string $feed_language The feed language.
	 *
	 * @return string
	 */
	private function get_modified_url( $output, $feed_language ) {
		$exploded_output = explode( home_url(), $output );
		$slug            = $this->get_url_slug( $feed_language );

		// If the url is not translated then add the slug at the end of the url.
		if ( count( $exploded_output ) > 1 && false === strpos( $exploded_output[1], $slug ) ) {
			$output = home_url() . $this->get_url_slug( $feed_language ) . $exploded_output[1];
		}

		return $output;
	}

	/**
	 * Get the url slug for the feed language.
	 *
	 * @param string $feed_language The feed language.
	 *
	 * @return string
	 */
	private function get_url_slug( $feed_language ) {
		$slug = $this->translatepress_url_converter->get_url_slug( $feed_language );

		if ( $slug ) {
			$slug = '/' . $slug;
		}

		return $slug;
	}

	/**
	 * Check if the table exists.
	 *
	 * @param string $table_name The table name.
	 *
	 * @return bool
	 */
	private function table_exists( $table_name ) { // phpcs:ignore
		global $wpdb;

		return $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) === $table_name; // phpcs:ignore
	}

	private static function get_translatable_strings( $output ) {

		// Split the input string into an array using new lines and HTML entities as separators
		$lines = preg_split( '/(<[^>]+>|\\n)/', $output, - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );

		// Filter out empty lines and trim each line
		$result = array_filter( array_map( 'trim', $lines ) );

		return $result;
	}

}
