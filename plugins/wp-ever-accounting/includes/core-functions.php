<?php
/**
 * EverAccounting Core Functions.
 *
 * General core functions available on both the front-end and admin.
 *
 * @since   1.0.0
 * @package EverAccounting
 */

defined( 'ABSPATH' ) || exit();

// Functions.
require_once EACCOUNTING_ABSPATH . '/includes/ea-account-functions.php';
require_once EACCOUNTING_ABSPATH . '/includes/ea-misc-functions.php';
require_once EACCOUNTING_ABSPATH . '/includes/ea-formatting-functions.php';
require_once EACCOUNTING_ABSPATH . '/includes/ea-form-functions.php';
require_once EACCOUNTING_ABSPATH . '/includes/ea-file-functions.php';
require_once EACCOUNTING_ABSPATH . '/includes/ea-currency-functions.php';
require_once EACCOUNTING_ABSPATH . '/includes/ea-transaction-functions.php';
require_once EACCOUNTING_ABSPATH . '/includes/ea-category-functions.php';
require_once EACCOUNTING_ABSPATH . '/includes/ea-contact-functions.php';
require_once EACCOUNTING_ABSPATH . '/includes/ea-notes-functions.php';
require_once EACCOUNTING_ABSPATH . '/includes/ea-deprecated-functions.php';
require_once EACCOUNTING_ABSPATH . '/includes/ea-item-functions.php';
require_once EACCOUNTING_ABSPATH . '/includes/ea-tax-functions.php';
require_once EACCOUNTING_ABSPATH . '/includes/ea-document-functions.php';
require_once EACCOUNTING_ABSPATH . '/includes/ea-template-functions.php';

/**
 * Get an option
 *
 * Looks to see if the specified setting exists, returns default if not
 *
 * @since 1.1.0
 *
 * @param string $key    Option key.
 * @param bool   $default Default value.
 *
 * @return mixed
 */
function eaccounting_get_option( $key = '', $default = false ) {
	$value = eaccounting()->options->get( $key, $default );
	$value = apply_filters( 'eaccounting_get_option', $value, $key, $default );
	return apply_filters( 'eaccounting_get_option_' . $key, $value, $key, $default );
}

/**
 * Update option.
 *
 * @param string $key Option key.
 * @param mixed  $value Option value.
 * @since 1.1.0
 */
function eaccounting_update_option( $key, $value ) {
	return eaccounting()->settings->set( array( $key => $value ), true );
}

/**
 * Get financial Start
 *
 * @param int    $year Year.
 * @param string $format Format.
 *
 * @since 1.0.2
 * @return string
 */
function eaccounting_get_financial_start( $year = null, $format = 'Y-m-d' ) {
	$financial_start = eaccounting()->settings->get( 'financial_year_start', '01-01' );
	$setting         = explode( '-', $financial_start );
	$day             = ! empty( $setting[0] ) ? $setting[0] : '01';
	$month           = ! empty( $setting[1] ) ? $setting[1] : '01';
	$year            = empty( $year ) ? (int) wp_date( 'Y' ) : absint( $year );

	$financial_year = new \EverAccounting\DateTime();
	$financial_year->setDate( $year, $month, $day );

	return $financial_year->format( $format );
}

/**
 * Get financial end date.
 *
 * @since 1.0.2
 * @param null   $year Year.
 * @param string $format Date format.
 *
 * @throws \Exception Exception.
 * @return string
 */
function eaccounting_get_financial_end( $year = null, $format = 'Y-m-d' ) {
	$dt = new \EverAccounting\DateTime( eaccounting_get_financial_start( $year, 'Y-m-d' ) );
	return $dt->addYear( 1 )->subDay( 1 )->date( $format );
}

/**
 * Instance of money class.
 *
 * For formatting with currency code
 * eaccounting_money( 100000, 'USD', true )->format()
 * For inserting into database
 * eaccounting_money( "$100,000", "USD", false )->getAmount()
 *
 * @since 1.0.2
 *
 * @param mixed  $amount    Amount.
 * @param string $code Currency code.
 * @param bool   $convert   Convert to default currency.
 *
 * @return \EverAccounting\Money|WP_Error
 */
function eaccounting_money( $amount, $code = 'USD', $convert = false ) {
	try {
		return new \EverAccounting\Money( $amount, $code, $convert );
	} catch ( Exception $e ) {
		return new \WP_Error( 'invalid_action', $e->getMessage() );
	}
}

/**
 * Get default currency.
 *
 * @since 1.1.0
 * @return string
 */
function eaccounting_get_default_currency() {
	$currency = eaccounting()->settings->get( 'default_currency', 'USD' );

	return apply_filters( 'eaccounting_default_currency', $currency );
}


/**
 * Format price with currency code & number format
 *
 * @since 1.0.2
 *
 * @param string $amount Amount.
 *
 * @param string $code If not passed will be used default currency.
 *
 * @return string
 */
function eaccounting_format_price( $amount, $code = null ) {
	if ( is_null( $code ) ) {
		$code = eaccounting_get_default_currency();
	}

	$amount = eaccounting_money( $amount, $code, true );
	if ( is_wp_error( $amount ) ) {
		/* translators: %s currency code */
		eaccounting_logger()->log_alert( sprintf( __( 'invalid currency code %s', 'wp-ever-accounting' ), $code ) );

		return '00.00';
	}

	return $amount->format();
}

/**
 * Sanitize price for inserting into database
 *
 * @since 1.0.2
 *
 * @param string $amount Amount.
 *
 * @param string $code If not passed will be used default currency.
 *
 * @return float|int
 */
function eaccounting_sanitize_price( $amount, $code = null ) {
	$amount = eaccounting_money( $amount, $code, false );
	if ( is_wp_error( $amount ) ) {
		/* translators: %s currency code */
		eaccounting_logger()->log_alert( sprintf( __( 'invalid currency code %s', 'wp-ever-accounting' ), $code ) );

		return 0;
	}

	return $amount->get_amount();
}

/**
 * Wrapper for sanitize and formatting.
 * If needs formatting with symbol $get_value = false otherwise true.
 *
 * @since 1.1.0
 *
 * @param string $amount Amount.
 * @param null   $code Currency code.
 * @param false  $get_value Get value.
 *
 * @return float|int|string
 */
function eaccounting_price( $amount, $code = null, $get_value = false ) {
	if ( $get_value ) {
		return eaccounting_sanitize_price( $amount, $code );
	}

	return eaccounting_format_price( $amount, $code );
}

/**
 * Convert price from default to any other currency.
 *
 * @since 1.0.2
 *
 * @param string $amount Amount.
 * @param string $to Convert to currency code.
 * @param string $to_rate Convert to currency rate.
 *
 * @return float|int|string
 */
function eaccounting_price_from_default( $amount, $to, $to_rate ) {
	$default = eaccounting_get_default_currency();
	$money   = eaccounting_money( $amount, $to );
	// No need to convert same currency.
	if ( $default === $to ) {
		return $money->get_amount();
	}

	try {
		$money = $money->multiply( (float) $to_rate );
	} catch ( Exception $e ) {
		return 0;
	}

	return $money->get_amount();
}

/**
 * Convert price from other currency to default currency.
 *
 * @since 1.0.2
 *
 * @param string $amount Amount.
 * @param   string $from    Convert from currency code.
 * @param   string $from_rate Convert from currency rate.
 *
 * @return float|int|string
 */
function eaccounting_price_to_default( $amount, $from, $from_rate ) {
	$default = eaccounting_get_default_currency();
	$money   = eaccounting_money( $amount, $from );
	// No need to convert same currency.
	if ( $default === $from ) {
		return $money->get_amount();
	}

	try {
		$money = $money->divide( (float) $from_rate );
	} catch ( Exception $e ) {
		return 0;
	}

	return $money->get_amount();
}

/**
 * Convert price convert between currency.
 *
 * @since 1.1.0
 *
 * @param  string $amount Amount.
 * @param  string $from  Convert from currency code.
 * @param string $to   Convert to currency code.
 * @param string $from_rate Convert from currency rate.
 * @param string $to_rate Convert to currency rate.
 *
 * @return float|int|string
 */
function eaccounting_price_convert( $amount, $from, $to = null, $from_rate = null, $to_rate = null ) {
	$default = eaccounting_get_default_currency();
	if ( is_null( $to ) ) {
		$to = $default;
	}

	if ( is_null( $from_rate ) ) {
		$from      = eaccounting_get_currency( $from );
		$from_rate = $from->get_rate();
	}
	if ( is_null( $to_rate ) ) {
		$to      = eaccounting_get_currency( $to );
		$to_rate = $to->get_rate();
	}

	if ( $from !== $default ) {
		$amount = eaccounting_price_to_default( $amount, $from, $from_rate );
	}

	return eaccounting_price_from_default( $amount, $to, $to_rate );
}


/**
 * Get payment methods.
 *
 * @since 1.0.2
 * @return array
 */
function eaccounting_get_payment_methods() {
	return apply_filters(
		'eaccounting_payment_methods',
		array(
			'cash'          => esc_html__( 'Cash', 'wp-ever-accounting' ),
			'bank_transfer' => esc_html__( 'Bank Transfer', 'wp-ever-accounting' ),
			'check'         => esc_html__( 'Cheque', 'wp-ever-accounting' ),
		)
	);
}

/**
 * Get the logger of the plugin.
 *
 * @since 1.0.2
 * @return \EverAccounting\Logger
 */
function eaccounting_logger() {
	return eaccounting()->logger;
}

/**
 * Trigger logging cleanup using the logging class.
 *
 * @since 1.0.2
 */
function eaccounting_cleanup_logs() {
	$logger = new \EverAccounting\Logger();
	$logger->clear_expired_logs();
}

/**
 * Define a constant if it is not already defined.
 *
 * @since 1.0.2
 *
 * @param string $name  Constant name.
 * @param mixed  $value Value.
 */
function eaccounting_maybe_define_constant( $name, $value ) {
	if ( ! defined( $name ) ) {
		define( $name, $value );
	}
}

/**
 * Create a collection from the given value.
 *
 * @since 1.0.2
 *
 * @param mixed $items Items.
 *
 * @return \EverAccounting\Collection
 */
function eaccounting_collect( $items ) {
	return new \EverAccounting\Collection( $items );
}


/**
 * Wrapper for _doing_it_wrong().
 *
 * @since  1.1.0
 *
 * @param string $function Function used.
 * @param string $message  Message to log.
 * @param string $version  Version the message was added in.
 */
function eaccounting_doing_it_wrong( $function, $message, $version ) {
	if ( wp_doing_ajax() || defined( 'REST_REQUEST' ) ) {
		do_action( 'doing_it_wrong_run', $function, $message, $version );
	} else {
		_doing_it_wrong( esc_html( $function ), esc_html( $message ), esc_html( $version ) );
	}
}

/**
 * Fetches data stored on disk.
 *
 * @since 1.1.0
 *
 * @param string $key Type of data to fetch.
 *
 * @return mixed Fetched data.
 */
function eaccounting_get_data( $key ) {
	// Try fetching it from the cache.
	$data = wp_cache_get( "eaccounting-data-$key", 'eaccounting-data' );
	if ( $data ) {
		return $data;
	}

	$data = apply_filters( "eaccounting_get_data_$key", include EACCOUNTING_ABSPATH . "/includes/data/$key.php" );
	wp_cache_set( "eaccounting-data-$key", 'eaccounting-data' );

	return $data;
}

/**
 * Send HTML emails from EverAccounting.
 *
 * @param mixed  $to          Receiver.
 * @param mixed  $subject     Subject.
 * @param mixed  $message     Message.
 * @param string $attachments Attachments. (default: "").
 *
 * @return bool
 */
function eaccounting_mail( $to, $subject, $message, $attachments = '' ) {
	return eaccounting()->mailer()->send( $to, $subject, $message, $attachments );
}


/**
 * Based on wp_list_pluck, this calls a method instead of returning a property.
 *
 * @since 1.1.0
 *
 * @param array      $list              List of objects or arrays.
 * @param int|string $callback_or_field Callback method from the object to place instead of the entire object.
 * @param int|string $index_key         Optional. Field from the object to use as keys for the new array.
 *                                      Default null.
 *
 * @return array Array of values.
 */
function eaccounting_list_pluck( $list, $callback_or_field, $index_key = null ) {
	// Use wp_list_pluck if this isn't a callback.
	$first_el = current( $list );
	if ( ! is_object( $first_el ) || ! is_callable( array( $first_el, $callback_or_field ) ) ) {
		return wp_list_pluck( $list, $callback_or_field, $index_key );
	}
	if ( ! $index_key ) {
		/*
		 * This is simple. Could at some point wrap array_column()
		 * if we knew we had an array of arrays.
		 */
		foreach ( $list as $key => $value ) {
			$list[ $key ] = $value->{$callback_or_field}();
		}

		return $list;
	}

	/*
	 * When index_key is not set for a particular item, push the value
	 * to the end of the stack. This is how array_column() behaves.
	 */
	$newlist = array();
	foreach ( $list as $value ) {
		// Get index. @since 3.2.0 this supports a callback.
		if ( is_callable( array( $value, $index_key ) ) ) {
			$newlist[ $value->{$index_key}() ] = $value->{$callback_or_field}();
		} elseif ( isset( $value->$index_key ) ) {
			$newlist[ $value->$index_key ] = $value->{$callback_or_field}();
		} else {
			$newlist[] = $value->{$callback_or_field}();
		}
	}

	return $newlist;
}

/**
 * Sets the last changed time for cache group.
 *
 * @param string $group Cache group.
 *
 * @since 1.1.0
 * @return void
 */
function eaccounting_cache_set_last_changed( $group ) {
	wp_cache_set( 'last_changed', microtime(), $group );
}

/**
 * Get percentage of a full number.
 * what percentage of 3 of 10
 *
 * @param   string $total Total number.
 * @param  string $number Number to get percentage of.
 * @param int    $decimals Number of decimals to return.
 * @since 1.1.0
 *
 * @return float
 */
function eaccounting_get_percentage( $total, $number, $decimals = 2 ) {
	return round( ( $number / $total ) * 100, $decimals );
}


/**
 * Queue some JavaScript code to be output in the footer.
 *
 * @since 1.0.2
 *
 * @param string $code Code.
 * @return void
 */
function eaccounting_enqueue_js( $code ) {
	global $eaccounting_queued_js;

	if ( empty( $eaccounting_queued_js ) ) {
		$eaccounting_queued_js = '';
	}

	$eaccounting_queued_js .= "\n" . $code . "\n";
}

/**
 * Output any queued javascript code in the footer.
 *
 * @since 1.0.2
 * @return void
 */
function eaccounting_print_js() {
	global $eaccounting_queued_js;

	if ( ! empty( $eaccounting_queued_js ) ) {
		// Sanitize.
		$eaccounting_queued_js = wp_check_invalid_utf8( $eaccounting_queued_js );
		$eaccounting_queued_js = preg_replace( '/&#(x)?0*(?(1)27|39);?/i', "'", $eaccounting_queued_js );
		$eaccounting_queued_js = str_replace( "\r", '', $eaccounting_queued_js );

		$js = "<!-- EverAccounting JavaScript -->\n<script type=\"text/javascript\">\njQuery(function($) { $eaccounting_queued_js });\n</script>\n";

		echo apply_filters( 'eaccounting_queued_js', $js );

		unset( $eaccounting_queued_js );
	}
}


/**
 * Get the current user ID.
 *
 * The function is being used for inserting
 * the creator id of object over the plugin.
 *
 * @since 1.0.2
 * @return int|mixed
 */
function eaccounting_get_current_user_id() {
	$user_id = get_current_user_id();
	if ( empty( $user_id ) ) {
		$user = get_user_by( 'email', get_option( 'admin_email' ) );
		if ( $user && in_array( 'administrator', $user->roles, true ) ) {
			$user_id = $user->ID;
		}
	}

	if ( empty( $user_id ) ) {
		$users   = get_users(
			array(
				'role'   => 'administrator',
				'fields' => 'ID',
			)
		);
		$user_id = reset( $users );
	}

	return $user_id;
}

/**
 * Get user full name.
 *
 * @since 1.1.0
 *
 * @param int $user_id User ID.
 *
 * @return string|void
 */
function eaccounting_get_full_name( $user_id ) {
	$unknown = __( 'Unknown User', 'wp-ever-accounting' );
	if ( empty( $user_id ) ) {
		return $unknown;
	}
	$user = get_userdata( absint( $user_id ) );
	if ( empty( $user ) ) {
		return $unknown;
	}
	$name = array_filter( array( $user->first_name, $user->last_name ) );
	if ( empty( $name ) ) {
		return empty( $user->display_name ) ? $unknown : $user->display_name;
	}

	return implode( ' ', $name );
}

/**
 * Init license.
 *
 * @param string $file File name.
 * @param string $item_name name of the item to be initialize license.
 */
function eaccounting_init_license( $file, $item_name ) {
	if ( is_admin() && class_exists( '\EverAccounting\License' ) ) {
		$license = new \EverAccounting\License( $file, $item_name );
	}
}
