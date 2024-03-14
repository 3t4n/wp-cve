<?php

/**
 * @package WP Product Feed Manager/Data/Functions
 * @version 2.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Converts a string containing a date-time stamp as stored in the metadata to a date time string
 * that can be used in a feed file
 *
 * @param string $date_stamp The timestamp that needs to be converted to a string that can be stored in a feed file
 *
 * @return string    A string containing the time or an empty string if the $date_stamp is empty
 * @since 1.1.0
 *
 */
function wppfm_convert_price_date_to_feed_format( $date_stamp ) {
	if ( $date_stamp ) {
		return gmdate( 'Y-m-d\TH:iO', $date_stamp );
	} else {
		return '';
	}
}

/**
 * After a channel has been updated this function decreases the 'wppfm_channels_to_update' option with one
 *
 * @since 1.4.1
 */
function wppfm_decrease_update_ready_channels() {
	$old = get_option( 'wppfm_channels_to_update' );

	if ( $old > 0 ) {
		update_option( 'wppfm_channels_to_update', $old - 1 );
	} else {
		update_option( 'wppfm_channels_to_update', 0 );
	}
}

/**
 * Checks the current database version and updates it if required
 *
 * @since 2.4.0
 */
function wppfm_check_db_version() {
	$db_management = new WPPFM_Database_Management();
	$db_management->verify_db_version();
}

/**
 * Checks if a specific source key is money related key or not
 *
 * @param string $key The source key to be checked
 *
 * @return boolean    True if the source key is money related, false if not
 * @since 1.1.0
 *
 */
function wppfm_meta_key_is_money( $key ) {
	// money keys
	$special_price_keys = array(
		'_max_variation_price',
		'_max_variation_regular_price',
		'_max_variation_sale_price',
		'_min_variation_price',
		'_min_variation_regular_price',
		'_min_variation_sale_price',
		'_regular_price',
		'_regular_price_with_tax',
		'_regular_price_without_tax',
		'_sale_price',
		'_sale_price_with_tax',
		'_sale_price_without_tax',
		'_max_group_price',
		'_min_group_price',
		'regular_price',
		'sale_price',
	);

	return in_array( $key, $special_price_keys, true );
}

/**
 * Takes a value and formats it to a money value using the WooCommerce thousands separator, decimal separator and number of decimals values
 *
 * @param string $money_value The money value to be formatted
 * @param string $feed_language Selected Language in WPML add-on, leave empty if no exchange rate correction is required @since 1.9.0
 * @param string $feed_currency Selected currency in WOOCS add-on, leave empty if no correction is required @since 2.28.0.
 *
 * @return string    A formatted money value
 * @since 1.9.0 added WPML support
 * @since 2.28.0 Switched to the formal wc functions to get the separator and number of decimals values.
 * @since 2.28.0 Added support for the WooCommerce Currency Switcher plugin.
 * @since 2.31.0 Added the wppfm_feed_price_thousands_separator, wppfm_feed_price_decimal_separator and wppfm_feed_price_decimals filters.
 * @since 2.36.1 Return an empty string if the $money_value parameter is an empty string. This is required to allow filtering feed attributes on "is empty" parameters.
 *
 * @since 1.1.0
 */
function wppfm_prep_money_values( $money_value, $feed_language = '', $feed_currency = '' ) {

	if ( '' === $money_value ) {
		return $money_value;
	}

	$thousand_separator = apply_filters( 'wppfm_feed_price_thousands_separator', wc_get_price_thousand_separator() );

	if ( ! is_float( $money_value ) ) {
		$val         = wppfm_number_format_parse( $money_value );
		$money_value = floatval( $val );
	}

	if ( has_filter( 'wppfm_woocs_exchange_money_values' ) ) { // WOOCS Support.
		$money_value = apply_filters( 'wppfm_woocs_exchange_money_values', $money_value, $feed_currency );
	}

	if ( has_filter( 'wppfm_wpml_exchange_money_values' ) ) { // WPML Support.
		return apply_filters( 'wppfm_wpml_exchange_money_values', $money_value, $feed_language );
	} else {
		$decimal_point   = apply_filters( 'wppfm_feed_price_decimal_separator', wc_get_price_decimal_separator() );
		$number_decimals = apply_filters( 'wppfm_feed_price_decimals', wc_get_price_decimals() );

		// To prevent Google Merchant Centre to interpret a thousand separator as a decimal separator we need to remove
		// the thousand separator if the decimals setting in WC is 0 and a period is used as decimal separator. Eg 1.452 would be interpreted by Google as 1,452.
		// @since 2.11.0
		if ( 0 === $number_decimals && '.' === $thousand_separator ) {
			$thousand_separator = '';
		}

		return number_format( $money_value, $number_decimals, $decimal_point, $thousand_separator );
	}
}

/**
 * Checks if there are invalid backups
 *
 * @return boolean true if there are no backups or these backups are current
 * @since 1.8.0
 *
 */
function wppfm_check_backup_status() {
	if ( ! WPPFM_Db_Management::invalid_backup_exist() ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Checks a folder given by $path for sql files and returns their names including the path
 *
 * @param $path
 * @since 2.6.0
 *
 * @return array
 */
function wppfm_list_sql_files( $path ) {
	$files = array();

	if ( is_dir( $path ) ) {
		$handle = opendir( $path );
		if ( $handle ) {
			while ( false !== ( $name = readdir( $handle ) ) ) { // phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.FoundInWhileCondition
				if ( preg_match( '/[a-zA-Z0-9-_ ]{2,}[.](sql)$/', $name ) ) {
					$files[] = $path . '/' . $name;
				}
			}
		}
	}

	return $files;
}

/**
 * Forces the database to load and update and adds the auto update cron event if it does not exist
 *
 * @return boolean
 * @since 1.9.0
 *
 */
function wppfm_reinitiate_plugin() {
	wppfm_check_feed_update_schedule();

	// remakes the database
	$db = new WPPFM_Database_Management();
	$db->force_reinitiate_db();

	$plugin_prefixes = apply_filters( 'wppfm_edd_plugin_prefix_list', array( 'wppfm' ) );

	// resets the license nr
	foreach ( $plugin_prefixes as $plugin_prefix ) {
		delete_option( $plugin_prefix . '_lic_status' );
		delete_option( $plugin_prefix . '_lic_status_date' );
		delete_option( $plugin_prefix . '_lic_key' );
		delete_option( $plugin_prefix . '_lic_expires' );
		delete_option( $plugin_prefix . '_license_notice_suppressed' );
	}

	// reset the keyed options
	WPPFM_Db_Management::clean_options_table();

	do_action( 'wppfm_plugin_reinitialized' );

	return true;
}

/**
 * Checks if the feed update schedule is registered. If it's missing it will reactivate it again.
 *
 * @since 2.20.0
 */
function wppfm_check_feed_update_schedule() {
	if ( ! wp_get_schedule( 'wppfm_feed_update_schedule' ) ) {
		// add the schedule cron
		wp_schedule_event( time(), 'hourly', 'wppfm_feed_update_schedule' );
	}
}

/**
 * Recursively implodes an array
 *
 * @since 2.8.0
 *
 * @param array|string $array
 * @param string $glue
 * @param bool $include_keys
 * @param bool $trim_all
 *
 * @return string
 */
function wppfm_recursive_implode( $array, $glue = ',', $include_keys = false, $trim_all = true ) {
	$glued_string = '';

	// @since 2.41.0
	if ( ! is_array( $array ) ) {
		$array = json_decode( $array, true );
	}

	// @since 2.41.0
	if ( ! $array ) {
		return '';
	}

	// Recursively iterates array and adds key/value to glued string
	array_walk_recursive(
		$array,
		function ( $value, $key ) use ( $glue, $include_keys, &$glued_string ) {
			$include_keys and $glued_string .= $key . ' => ';
			$glued_string                   .= $value . $glue;
		}
	);

	// Removes last $glue from string
	if ( strlen( $glue ) > 0 && $glued_string ) {
		$glued_string = substr( $glued_string, 0, - strlen( $glue ) );
	}

	// Trim ALL whitespace
	if ( $trim_all && $glued_string ) {
		preg_replace( '/(\s)/ixsm', '', $glued_string );
	}

	return $glued_string;
}

function wppfm_clear_feed_process_data() {
	WPPFM_Feed_Controller::clear_feed_queue();
	WPPFM_Feed_Controller::set_feed_processing_flag();
	WPPFM_Db_Management::clean_options_table();
	WPPFM_Db_Management::reset_status_of_failed_feeds();

	do_action( 'wppfm_feed_process_data_cleared' );

	return true;
}

/**
 * Takes a string with spaces and capital letters and converts it to a string with dashes and lower case letters
 *
 * @param $original_string
 *
 * @return string
 */
function wppfm_convert_string_with_spaces_to_lower_case_string_with_dashes( $original_string ) {
	return strtolower( str_replace( ' ', '-', $original_string ) );
}

function wppfm_convert_string_with_dashes_to_upper_case_string_with_spaces( $original_string ) {
	return ucwords( str_replace( '-', ' ', $original_string ) );
}

/**
 * Converts any number string to a string with a number that has no thousands separator
 * and a period as decimal separator
 *
 * @param string $number_string
 *
 * @since 2.28.0 Switched to the formal wc functions to get the separator and number of decimals values.
 *
 * @return string
 */
function wppfm_number_format_parse( $number_string ) {
	$decimal_separator  = wc_get_price_decimal_separator();
	$thousand_separator = wc_get_price_thousand_separator();

	// convert a number string that is an actual standard number format whilst the woocommerce options are not standard
	// to the woocommerce standard. This sometimes happens with meta values
	if ( ! empty( $decimal_separator ) && strpos( $number_string, $decimal_separator ) === false ) {
		$number_string = ! empty( $thousand_separator ) && strpos( $number_string, $thousand_separator ) === false ? $number_string : str_replace( $thousand_separator, $decimal_separator, $number_string );
	}

	$no_thousands_sep = str_replace( $thousand_separator, '', $number_string );

	return '.' !== $decimal_separator ? str_replace( $decimal_separator, '.', $no_thousands_sep ) : $no_thousands_sep;
}

/**
 * returns the path to the feed file including feed name and extension
 *
 * @param string $feed_name
 *
 * @return string
 */
function wppfm_get_file_path( $feed_name ) {
	$forbidden_name_chars = wppfm_forbidden_file_name_characters();
	$feed_name            = str_replace( $forbidden_name_chars, '-', $feed_name );

	// previous to plugin version 1.3.0 feeds where stored in the plugins but after that version they are stored in the upload folder
	if ( file_exists( WP_PLUGIN_DIR . '/wp-product-feed-manager-support/feeds/' . $feed_name ) ) {
		return WP_PLUGIN_DIR . '/wp-product-feed-manager-support/feeds/' . $feed_name;
	} elseif ( file_exists( WPPFM_FEEDS_DIR . '/' . $feed_name ) ) {
		return WPPFM_FEEDS_DIR . '/' . $feed_name;
	} else { // as of version 1.5.0 all spaces in new filenames are replaced by a dash
		return WPPFM_FEEDS_DIR . '/' . $feed_name;
	}
}

/**
 * Returns the url of the feed file including feed name and extension.
 *
 * @param   string  $feed_name  Name of the feed file.
 *
 * @return  string  URL to the feed file.
 */
function wppfm_get_file_url( $feed_name ) {
	$forbidden_name_chars = wppfm_forbidden_file_name_characters();
	$feed_name            = str_replace( $forbidden_name_chars, '-', $feed_name );

	// previous to plugin version 1.3.0 feeds where stored in the plugins but after that version they are stored in the upload folder
	if ( file_exists( WP_PLUGIN_DIR . '/wp-product-feed-manager-support/feeds/' . $feed_name ) ) {
		$file_url = plugins_url() . '/wp-product-feed-manager-support/feeds/' . $feed_name;
	} else { // as of version 1.5.0 all spaces in new filenames are replaced by a dash
		$file_url = WPPFM_UPLOADS_URL . '/wppfm-feeds/' . $feed_name;
	}

	return apply_filters( 'wppfm_feed_url', $file_url, $feed_name );
}

/**
 * @return array with forbidden characters
 */
function wppfm_forbidden_file_name_characters() {
	return array( ' ', '<', '>', ':', '?', ',', "'", '{', '}', '#' ); // characters that are not allowed in a feed file name
}

/**
 * For backward compatibility, the old feed statuses are converted to all lowercase and without spaces
 *
 * @param array $list
 *
 * @since 2.1.0
 *
 * @noinspection PhpParameterByRefIsNotUsedAsReferenceInspection
 */
function wppfm_correct_old_feeds_list_status( &$list ) {
	for ( $i = 0; $i < count( $list ); $i ++ ) {
		$list[ $i ]->status = strtolower( str_replace( ' ', '_', $list[ $i ]->status ) );
	}
}

/**
 * Checks if the WooCommerce plugin is installed and active
 *
 * @return boolean true if WooCommerce is installed and active, false if not
 * @since 2.3.0
 */
function wppfm_wc_installed_and_active() {
	return is_plugin_active( 'woocommerce/woocommerce.php' ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' );
}

/**
 * Checks if the WooCommerce plugin has the minimal required version
 *
 * @return boolean true if WooCommerce version is at least 3.0.0
 * @since 2.3.0
 */
function wppfm_wc_min_version_required() {
	// To prevent several PHP Warnings if the WC folder name has been changed whilst the plugin is still registered.
	// @since 2.11.0.
	if ( ! file_exists( WPPFM_PLUGIN_DIR . '../woocommerce/woocommerce.php' ) ) {
		return false;
	}

	$wc_version = get_plugin_data( WPPFM_PLUGIN_DIR . '../woocommerce/woocommerce.php' )['Version'];

	return version_compare( $wc_version, WPPFM_MIN_REQUIRED_WC_VERSION, '>=' );
}
