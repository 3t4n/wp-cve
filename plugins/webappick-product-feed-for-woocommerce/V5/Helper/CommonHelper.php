<?php
/**
 * @package CTXFeed\V5\Helper
 */

namespace CTXFeed\V5\Helper;


class CommonHelper {//phpcs:ignore

	/**
	 * Clean content for use in XML.
	 * This function processes the given content to make it suitable for XML usage.
	 * It involves converting shortcodes, removing HTML tags, handling invalid UTF-8 characters,
	 * and encoding special characters.
	 *
	 * @param string $content The content to be cleaned.
	 * @return string|null Cleaned content or null if input is empty.
	 * @since 8.0.0
	 */
	public static function clean_content( $content ) {
		if ( empty( $content ) ) {
			return null;
		}

		// Convert Shortcodes.
		$content = \do_shortcode( $content );

		// Remove HTML Tags
		$content = \wp_strip_all_tags( $content );

		// Remove invalid UTF-8 characters
		$content = \wp_check_invalid_utf8( \wp_specialchars_decode( $content ), true );

		// Remove Unconverted Shortcodes.
		$expression = '/\[\/?[a-zA-Z0-9_| -=\'"{}]*\/?\]/';
		$content    = \preg_replace( $expression, '', \strip_shortcodes( $content ) );

		// Remove invalid characters
		$content = \preg_replace( '/[^\x{9}\x{A}\x{D}\x{20}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', '', $content );

		// Remove HTML comments
		$content = \preg_replace( '/<!--(.|\s)*?-->/', '', $content );

		return !empty( $content ) ? \trim( $content ) : $content;
	}

	/**
	 * Retrieves the list of supported product types.
	 * This function provides an array of product types that are supported in the context of the application.
	 *
	 * @return array List of supported product types.
	 */
	public static function supported_product_types() {
		$product_types =  array(
			'simple',
			'variable',
			'variation',
			'grouped',
			'external',
			'composite',
			'bundle',
			'bundled',
			'yith_bundle',
			'yith-composite',
			'subscription',
			'variable-subscription',
			'woosb',
			'woosg',
			'auction',
		);

		return apply_filters( 'ctx_filter_product_types_for_product_query', $product_types );
	}

	/**
	 * Removes all shortcodes from the given content.
	 * This function processes the content to remove WordPress shortcodes, and additional shortcode-like patterns.
	 * It first processes registered shortcodes, then removes any remaining shortcode-like structures.
	 *
	 * @param string $content The content from which to remove shortcodes.
	 * @return string The content with shortcodes removed.
	 */
	public static function remove_shortcodes( $content ) {
		if ( $content === '' ) {
			return '';
		}

		// Process registered shortcodes.
		$content = \strip_shortcodes( \do_shortcode( $content ) );

		// Custom function to strip invalid XML, if necessary.
		$content = self::strip_invalid_xml( $content );

		// More specific regex to target shortcode-like patterns.
		$expression = '/\[\/*[č?a-zA-Z1-90_| -=\'"\{\}]*\/*\]/m';
		$content = \preg_replace( $expression, '', $content );

		return $content;
	}

	/**
	 * Adds UTM parameters to a given URL.
	 * This function appends UTM (Urchin Tracking Module) parameters to a URL for tracking purposes.
	 * Only parameters that are not empty will be added to the URL.
	 *
	 * @param array $utm Associative array of UTM parameters.
	 * @param string $url The URL to which UTM parameters will be added.
	 * @return string The URL with UTM parameters appended.
	 */
	public static function add_utm_parameter( array $utm, $url ) {
		if ( !empty( $utm['utm_source'] ) && !empty( $utm['utm_medium'] ) && !empty( $utm['utm_campaign'] ) ) {
			$utm = \array_map( function( $value ) {
				return \str_replace( ' ', '+', $value );
			}, $utm);

			$url = \add_query_arg( \array_filter( $utm ), $url );
		}

		return $url;
	}

	/**
	 * Checks if the current WooCommerce version meets or exceeds the specified version.
	 * It requires WooCommerce to be installed and activated.
	 *
	 * @param string $version The WooCommerce version to compare against, default '3.0'.
	 * @return bool True if the current version is greater than or equal to the specified version, false otherwise.
	 */
	public static function wc_version_check( $version = '3.0' ) {
		if ( !\function_exists( 'get_plugins') ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugins = \get_plugins();
		$plugin_path = 'woocommerce/woocommerce.php';

		if ( !isset( $plugins[$plugin_path] ) ) {
			return false;
		}

		return \version_compare( $plugins[$plugin_path]['Version'], $version, '>=' );
	}

	/**
	 * Generates a unique option name by appending a numerical suffix if necessary.
	 * This function sanitizes the provided slug, checks against a list of disallowed
	 * names, and ensures uniqueness in the WordPress options table.
	 *
	 * @param string    $slug The base slug to use for the option name.
	 * @param string    $prefix A prefix to be added to the slug (optional).
	 * @param int|null  $option_id An existing option ID to exclude from the check (optional).
	 * @return string   The unique, sanitized option name.
	 */
	public static function unique_option_name( $slug, $prefix = '', $option_id = null ) {
		global $wpdb;

		// Sanitize and prepare slug.
		$slug = \sanitize_key( $slug );

		$slug = \str_replace( ' ', '_', $slug );

		if ( '_' === \substr( $slug, -1 ) ) {
			$slug = \substr( $slug, 0, -1 );
		}

		/** @noinspection SpellCheckingInspection */
		$slug       = \preg_replace( '/[^A-Za-z0-9_]/', '', $slug );

		// List of disallowed slugs.
		$disallowed = array( 'siteurl', 'home', 'blogname', 'blogdescription', 'users_can_register', 'admin_email' );

		if ( $option_id && $option_id > 0 ) {
			$check_sql  = "SELECT option_name FROM $wpdb->options WHERE option_name = %s AND option_id != %d LIMIT 1";
			$name_check = $wpdb->get_var( $wpdb->prepare( $check_sql, $prefix . $slug, $option_id ) ); // phpcs:ignore
		} else {
			$check_sql  = "SELECT option_name FROM $wpdb->options WHERE option_name = %s LIMIT 1";
			$name_check = $wpdb->get_var( $wpdb->prepare( $check_sql, $prefix . $slug ) ); // phpcs:ignore
		}

		// slug found or slug in disallowed list
		if ( $name_check || \in_array( $slug, $disallowed, true ) ) {
			$suffix = 2;

			do {
				$alt_name = \_truncate_post_slug( $slug, 200 - ( \strlen( $suffix ) + 1 ) ) . "-$suffix";

				if ( $option_id && $option_id > 0 ) {
					$name_check = $wpdb->get_var( $wpdb->prepare( $check_sql, $prefix . $alt_name, $option_id ) ); // phpcs:ignore
				} else {
					$name_check = $wpdb->get_var( $wpdb->prepare( $check_sql, $prefix . $alt_name ) ); // phpcs:ignore
				}

				++$suffix;
			} while ( $name_check );

			$slug = $alt_name;
		}

		return $slug;
	}

	/**
	 * Retrieves a list of option names from the WordPress database that start with a given prefix.
	 * This function performs a database query to find all options matching the specified prefix.
	 *
	 * @param string $prefix The prefix to search for in option names.
	 * @return array|object|null Array of objects containing option names or null on failure.
	 */
	public static function get_options( $prefix ) {
		global $wpdb;

		// Ensure the prefix is sanitized to prevent SQL injection.
		$safe_prefix = \esc_sql( $prefix );

		$sql = "SELECT option_name FROM $wpdb->options WHERE option_name LIKE %s";
		$prepared_sql = $wpdb->prepare( $sql, $safe_prefix . '%' );

		// Execute the query and handle potential errors.
		try {
			$results = $wpdb->get_results( $prepared_sql );

			return $results;
		} catch ( \Exception $e ) {
			// Consider logging the error or handling it as per your application's needs.
			return null;
		}
	}

	/**
	 * Extends wp_strip_all_tags to handle WP_Error objects.
	 * This function returns an empty string if the input is a WP_Error object,
	 * otherwise, it strips all HTML tags from the input.
	 *
	 * @param string|\WP_Error $string The input to be processed. Can be a string or WP_Error object.
	 * @return string The processed string without HTML tags, or an empty string if input was a WP_Error.
	 * @since 4.5.10
	 */
	public static function strip_all_tags( $string ) {
		if ( $string instanceof \WP_Error ) {
			return '';
		}

		return \wp_strip_all_tags( $string );
	}

	/**
	 * Remove non supported xml character
	 *
	 * @param string $value
     * @return string
	 * @since 4.5.10
	 * @see   https://stackoverflow.com/questions/3466035/how-to-skip-invalid-characters-in-xml-file-using-php
	 */
	public static function strip_invalid_xml( $value ) {
		$ret = '';

		if ( empty( $value ) ) {
			return $ret;
		}

		if ( \is_int( $value ) || is_float( $value )) {
			$ret = $value;
		} else {
			$length = \strlen( $value );

			for ( $i = 0; $i < $length; $i++ ) {
				$current = \ord( $value[ $i ] );

				if (
					( 0x9 === $current )
					|| ( 0xA === $current )
					|| ( 0xD === $current )
					|| (
						( $current >= 0x20 )
						&& ( $current <= 0xD7FF )
					)
					|| (
						( $current >= 0xE000 )
						&& ( $current <= 0xFFFD )
					)
					|| (
						( $current >= 0x10000 )
						&& ( $current <= 0x10FFFF )
					)
				) {
					$ret .= \chr( $current );
				} else {
					$ret .= '';
				}
			}
		}

		return $ret;
	}

	/**
	 * Returns the parent product ID for a variation product, or the product ID itself if it's not a variation.
	 * This function is useful when needing to identify the main product associated with a variation.
	 *
	 * @param \WC_Product $product The WooCommerce product object.
	 * @return int The ID of the parent product for variations, or the product's own ID.
	 */
	public static function parent_product_id( $product ) {
		if ( !$product instanceof \WC_Product ) {
			// Optionally handle the error if $product is not a valid WC_Product object.
			return 0;
		}

		return $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id();
	}

}
