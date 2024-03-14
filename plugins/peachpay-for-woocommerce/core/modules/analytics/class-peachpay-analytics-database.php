<?php
/**
 * PeachPay Analytics Database API
 *
 * @phpcs:disable WordPress.Security.NonceVerification.Recommended
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

require_once PEACHPAY_ABSPATH . 'core/error-reporting.php';
require_once PEACHPAY_ABSPATH . 'core/modules/analytics/class-peachpay-analytics-time.php';
require_once PEACHPAY_ABSPATH . 'core/modules/currency-switcher/currency-convert.php';

/**
 * Main analytics database.
 */
class PeachPay_Analytics_Database {
	use PeachPay_Singleton;

	/**
	 * Constructor method. This acts as an action attacher for all important actions.
	 */
	public function __construct() {}

	/**
	 * Pull the cart id from the user cookies.
	 *
	 * @param string $key - A specific element to grab.
	 * @param any    $value (OPTIONAL) - Set the key if this value is sent.
	 */
	public static function session_value( $key, $value = null ) {
		if ( null !== $value ) {
			setcookie( 'peachpay-analytics-session-' . $key, $value );
			return;
		}

		if ( isset( $_COOKIE ) ) {
			return array_key_exists( 'peachpay-analytics-session-' . $key, $_COOKIE ) ? sanitize_text_field( wp_unslash( $_COOKIE[ 'peachpay-analytics-session-' . $key ] ) ) : 0;
		} else {
			return 0;
		}
	}

	/**
	 * A list of the tables in wpdb.
	 *
	 * @var Array
	 */
	private static $tables = array(
		'peachpay_customer_cart_meta'     => 'create_cart_meta_table',
		'peachpay_customer_cart'          => 'create_cart_table',
		'peachpay_customer_cart_contents' => 'create_cart_contents_table',
		'peachpay_analytics_meta'         => 'create_analytics_table',
		'peachpay_analytics_interval'     => 'create_analytics_interval_table',
	);

	/**
	 * Old tables to delete (version 1 analytics should be removed from existing stores).
	 *
	 * @var array $old_tables
	 */
	private static $old_tables = array(
		'peachpay_cart_has_item'      => 1,
		'peachpay_abandonment_emails' => 1,
		'peachpay_carts'              => 1,
	);

	/**
	 * Pull tables name from @var $tables.
	 * - 'create_cart_meta_table'          => 'peachpay_customer_cart_meta'
	 * - 'create_cart_table'               => 'peachpay_customer_cart'
	 * - 'create_cart_contents_table'      => 'peachpay_customer_cart_contents'
	 * - 'create_analytics_interval_table' => 'peachpay_analytics_interval'
	 * - 'create_analytics_table'          => 'peachpay_analytics_meta'
	 *
	 * @param string $table_function References @var $tables value and and then returns key of matching value.
	 */
	public static function get_table_name( $table_function ) {
		foreach ( self::$tables as $key => $table ) {
			if ( $table === $table_function ) {
				return $key;
			}
		}
	}

	// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery

	/**
	 * Checks for table existence.
	 *
	 * @param string $table_name The table to check.
	 *
	 * @return bool true if found, false if not found.
	 */
	public static function check_table_existence( $table_name ) {
		global $wpdb;

		try {
			$is_table_exist = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) );
		} catch ( Exception $e ) {
			// Do no harm. Return false.
			return false;
		}

		return $is_table_exist ? true : false;
	}

	/**
	 * Checks if all our tables are created and creates any that are missing.
	 *
	 * Only done once on very first load of PeachPay plugin
	 */
	public static function create_uninitialized_tables() {
		global $wpdb;

		foreach ( self::$old_tables as $key => $table ) {
			$table_name = $wpdb->prefix . $key;

			// If there is a table, drop it.
			if ( self::check_table_existence( $table_name ) ) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.SchemaChange
				$wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
			}
		}

		foreach ( self::$tables as $key => $table ) {
			// If there is no table, build it.
			if ( ! self::check_table_existence( $wpdb->prefix . $key ) ) {
				self::$table( $key );
			}
		}
	}

	/**
	 *  Create cart meta table for analytics.
	 *
	 * @param string $table_name input for the specific cart name (if changed for namespace issues).
	 */
	private static function create_cart_meta_table( $table_name ) {
		global $wpdb;

		$cart_table      = $wpdb->prefix . $table_name;
		$users_table     = $wpdb->prefix . 'users';
		$charset_collate = $wpdb->get_charset_collate();

		// Cart abandonment tracking db sql command.
		$sql = "CREATE TABLE IF NOT EXISTS {$cart_table} (
			cart_id BIGINT(20) UNSIGNED AUTO_INCREMENT,
			customer_id BIGINT(20) UNSIGNED DEFAULT NULL,
			order_id BIGINT(20) UNSIGNED DEFAULT NULL,

			email VARCHAR(255) DEFAULT NULL,

			payment_method VARCHAR(127) DEFAULT NULL,
			currency VARCHAR(127) DEFAULT NULL,
			browser VARCHAR(127) DEFAULT NULL,
			operating_system VARCHAR(127) DEFAULT NULL,

			PRIMARY KEY (cart_id),
			FOREIGN KEY (`customer_id`) REFERENCES {$users_table}(`ID`)
		) $charset_collate;\n";

		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		try {
			dbDelta( $sql );
		} catch ( Exception $e ) {
			peachpay_notify_error( $e );
		}
	}

	/**
	 * Create cart table for analytics.
	 *
	 * @param string $table_name input for the specific cart name (if changed for namespace issues).
	 */
	private static function create_cart_table( $table_name ) {
		global $wpdb;

		$cart_table      = $wpdb->prefix . $table_name;
		$cart_meta_table = $wpdb->prefix . self::get_table_name( 'create_cart_meta_table' );
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS {$cart_table} (
			cart_id BIGINT(20) UNSIGNED,
			postcode BIGINT(20) UNSIGNED,
			city VARCHAR(255),
			address VARCHAR(255),
			address2 VARCHAR(255),
			state VARCHAR(255),
			country VARCHAR(255),
			first_name VARCHAR(255),
			last_name VARCHAR(255),
			company VARCHAR(255),
			phone VARCHAR(127),
			date_created DATETIME,
			date_updated DATETIME,
			cart_total DOUBLE DEFAULT 0,

			FOREIGN KEY (`cart_id`) REFERENCES {$cart_meta_table}(`cart_id`),
			UNIQUE KEY `unique_cart_data` (`cart_id`)
		) $charset_collate;\n";

		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		try {
			dbDelta( $sql );
		} catch ( Exception $e ) {
			peachpay_notify_error( $e );
		}
	}

	/**
	 * Create cart table contents for analytics
	 *
	 * @param string $table_name input for the specific cart name (if changed for namespace issues).
	 */
	private static function create_cart_contents_table( $table_name ) {
		global $wpdb;

		$cart_table      = $wpdb->prefix . $table_name;
		$cart_meta_table = $wpdb->prefix . self::get_table_name( 'create_cart_meta_table' );
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS {$cart_table} (
			cart_id BIGINT(20) UNSIGNED NOT NULL,
			item_id BIGINT(20) UNSIGNED NOT NULL,
			variation_id BIGINT(20) UNSIGNED DEFAULT NULL,
			qty INTEGER NOT NULL DEFAULT 1,

			FOREIGN KEY (`cart_id`) REFERENCES {$cart_meta_table}(`cart_id`),
			UNIQUE KEY `unique_cart_item` (`cart_id`, `item_id`, `variation_id`)
		) $charset_collate;\n";

		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		try {
			dbDelta( $sql );
		} catch ( Exception $e ) {
			peachpay_notify_error( $e );
		}
	}

	/**
	 * Create analytics table.
	 *
	 * @param string $table_name input for the specific cart name (if changed for namespace issues).
	 */
	private static function create_analytics_table( $table_name ) {
		global $wpdb;

		$analytics_table = $wpdb->prefix . $table_name;
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS {$analytics_table} (
			id BIGINT(20) UNSIGNED AUTO_INCREMENT,
			tab VARCHAR(31) NOT NULL,
			section VARCHAR(31) NOT NULL,
			title VARCHAR(127) NOT NULL,
			currency VARCHAR(3) NOT NULL,
			value DOUBLE,

			PRIMARY KEY (id),
			UNIQUE KEY `unique_analytic` (`tab`, `section`, `title`(31), `currency`)
		) $charset_collate;\n";

		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		try {
			dbDelta( $sql );
		} catch ( Exception $e ) {
			peachpay_notify_error( $e );
		}
	}

	/**
	 *  Create email table for analytics intervals.
	 *
	 * @param string $table_name input for the specific cart name (if changed for namespace issues).
	 */
	private static function create_analytics_interval_table( $table_name ) {
		global $wpdb;

		$analytics_table          = $wpdb->prefix . self::get_table_name( 'create_analytics_table' );
		$analytics_interval_table = $wpdb->prefix . $table_name;
		$charset_collate          = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS {$analytics_interval_table} (
			interval_id BIGINT(20) UNSIGNED NOT NULL,
			interval_order DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			interval_value DOUBLE NOT NULL DEFAULT 0,

			FOREIGN KEY (`interval_id`) REFERENCES {$analytics_table}(`id`)
		) $charset_collate;\n";

		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		try {
			dbDelta( $sql );
		} catch ( Exception $e ) {
			peachpay_notify_error( $e );
		}
	}

	/**
	 * Simple query mechanism to pull specific data from the analytics table.
	 *
	 * @param ARRAY_A $query Associative array that contains all of the params needed to build a query.
	 *  This would have the following:.
	 * @var string tab (REQUIRED) Which tab the analytics are for: payment_methods, device_breakdown, abandoned_carts.
	 * @var string section (REQUIRED) Section of the specific analytic on that page.
	 *  General pattern follows:
	 *  - simple count: name_count
	 *  - interval count: name_interval.
	 * @var string|array title (OPTIONAL) Title is used for selecting even more specifically than a section. There are
	 *  two ways to use this function in particular:
	 *  - a single title such as "PeachPay (Stripe)"
	 *  - an array of titles such as array(
	 *      "PeachPay (Stripe)"
	 *      "PeachPay (Square)"
	 *    )
	 * @var string currency (REQUIRED) What currency to grab analytics for.
	 * @var string index    (OPTIONAL) Optionally choose what the label on returning data should be. Current choices:
	 *  - title (DEFAULT)
	 *  - currency
	 * @var string order_by (OPTIONAL) Optionally order the results specifically. This should follow the following pattern:
	 *  value DESC
	 * or similar.
	 * @var string group_by (OPTIONAL) Optionally group results specifically. This should follow the pattern:
	 *  title, currency
	 * or similar.
	 * @var string sum      (OPTIONAL) Optionally sum results specifically. This should follow the pattern:
	 *  - 1: adds values
	 *  NOTE: only use with count metrics
	 * @var string count    (OPTIONAL) Optionally count results (versus getting real numbers). This should follow the pattern:
	 *  - 1: just count values
	 *
	 * @var string convert  (OPTIONAL) Optionally set whether numbers as there coming back should be converedt.
	 * NOTE: must have a currency set for this.
	 *
	 * The following parameters are specifically for interval tuning:
	 * @var string interval What interval to use.
	 *  Options follow:
	 *  - daily
	 *  - weekly
	 *  - monthly
	 *  - yearly.
	 * @var string time_span What time span to use.
	 *  Options follow:
	 *  - week
	 *  - month
	 *  - year
	 *  - 5year
	 *  - all.
	 * @var string format How to format the interval if selecting for interval.
	 *  - Uses PHP format strings: https://www.w3schools.com/php/func_date_date_format.asp
	 *  - Attempts to guess if interval and format not given.
	 *
	 * Example query for PeachPay (Stripe) order monthly interval over the past year with the format June, 2022:
	 * query_analytics( array(
	 *     'tab'        => "payment_methods",
	 *     'section'    => "order_interval",
	 *     'title'      => "PeachPay (Stripe)",
	 *     'currency'   => "USD",
	 *     'interval'   => "monthly",
	 *     'time_span'  => "year",
	 *     'format'     => "F, Y"
	 *   )
	 * );
	 * .
	 */
	public static function query_analytics( $query ) {
		// check required arguments are there.
		if ( ! array_key_exists( 'tab', (array) $query ) ) {
			return new WP_Error( 'MISSING_PARAM', 'The expected parameter "tab" to query_analytics was not found' );
		} elseif ( ! array_key_exists( 'section', (array) $query ) ) {
			return new WP_Error( 'MISSING_PARAM', 'The expected parameter "section" to query_analytics was not found' );
		}

		// check for if the query contains an interval component:
		$section_title_parts = 0 === strcmp( 'array', $query['section'] ) ? explode( '_', $query['section'][0] ) : explode( '_', $query['section'] );
		$is_interval         = strcmp( 'interval', $section_title_parts[ count( $section_title_parts ) - 1 ] ) === 0;
		if ( $is_interval ) { // check for needed extra params
			$error_title   = '';
			$error_message = '';

			if ( ! array_key_exists( 'interval', (array) $query ) ) {
				$error_title   = 'MISSING_PARAM';
				$error_message = "The expected parameter \"interval\" to query_analytics was not found for the query: <b>{$query["tab"]}</b>, <b>{$query["section"]}</b>.";
			} elseif ( ! array_key_exists( 'time_span', (array) $query ) ) {
				$error_title   = 'MISSING_PARAM';
				$error_message = "The expected parameter \"time_span\" to query_analytics was not found for the query: <b>{$query["tab"]}</b>, <b>{$query["section"]}</b>.";
			} elseif ( ! array_key_exists( 'format', (array) $query ) ) {
				$error_title   = 'MISSING_PARAM';
				$error_message = "The expected parameter \"format\" to query_analytics was not found for the query: <b>{$query["tab"]}</b>, <b>{$query["section"]}</b>.";
			}

			// check that interval and time_span are valid
			if ( ! array_key_exists( $query['interval'], PeachPay_Analytics_Time::$interval_to_date ) ) {
				$error_title   = 'INVALID_PARAM';
				$error_message = "The expected parameter \"interval\" was incorrect (set to <b>{$query["interval"]}</b>) for the query: <b>{$query["tab"]}</b>, <b>{$query["section"]}</b>. Please use one of the following:<ul><li>daily</li><li>weekly</li><li>monthly</li><li>yearly</li></ul>";
			} elseif ( ! array_key_exists( $query['time_span'], PeachPay_Analytics_Time::$interval_to_date ) ) {
				$error_title   = 'INVALID_PARAM';
				$error_message = "The expected parameter \"time_span\" was incorrect (set to <b>{$query["time_span"]}</b>) for the query: <b>{$query["tab"]}</b>, <b>{$query["section"]}</b>. Please use one of the following:<ul><li>week</li><li>month</li><li>year</li><li>5year</li><li>all</li></ul>";
			}

			if ( strlen( $error_title ) ) {
				return array(
					'error' => array(
						'title'   => $error_title,
						'message' => $error_message,
					),
				);
			}
		}

		global $wpdb;

		// check to see if $end_date is needed
		$end_date     = '';
		$dates        = '';
		$dates_length = 0;

		$analytics_table          = $wpdb->prefix . self::get_table_name( 'create_analytics_table' );
		$analytics_interval_table = $wpdb->prefix . self::get_table_name( 'create_analytics_interval_table' );

		if ( ( $is_interval && ! self::check_table_existence( $analytics_interval_table ) ) || ! self::check_table_existence( $analytics_table ) ) {
			if ( $is_interval ) {
				for ( $dates_index = 0; $dates_index < $dates_length; $dates_index++ ) {
					$dates[ $dates_index ] = gmdate( $query['format'], strtotime( $dates[ $dates_index ] ) );
				}
			}

			return $is_interval ? array(
				'labels'   => $dates,
				'datasets' => array(),
			) : array(
				'graph' => array(
					'labels'   => array(),
					'datasets' => array(
						array(
							'data'            => array(),
							'backgroundColor' => array(),
						),
					),
				),
				'value' => array(
					(object) array(
						'title' => null,
						'value' => 0,
					),
				),
			);
		}

		if ( $is_interval ) {
			$analytics_interval_span = PeachPay_Analytics_Time::$time_span_component_connect[ $query['time_span'] ];
			// Compute the date based on the old information in the analytics interval database and use that end date.
			try {
				$end_date = $wpdb->get_var( "SELECT MIN(interval_order) FROM {$analytics_interval_table}{$analytics_interval_span};" );
			} catch ( Exception $e ) {
				peachpay_notify_error( $e );
				$end_date = new DateTime();
			}

			$is_all_time  = 0 === strcmp( 'all', $query['time_span'] );
			$dates        = PeachPay_Analytics_Time::compute_intervals(
				$query['interval'],
				$query['time_span'],
				$end_date,
				$is_all_time
			);
			$dates_length = count( $dates );
		}

		$active_currency_error = 0;

		$currency_convert  = 0;
		$currency          = "AND currency=''";
		$currencies_length = 0;
		if ( array_key_exists( 'currency', (array) $query ) ) {
			if ( 0 === strcmp( '*', $query['currency'] ) ) {
				$currency = '';
			} else {
				$currencies = 0 === strcmp( 'array', gettype( $query['currency'] ) ) ? $query['currency'] : explode( ',', $query['currency'] );

				$currency_prepare_statement = '';
				$currencies_length          = count( $currencies );
				for ( $currency_index = 0; $currency_index < $currencies_length; $currency_index++ ) {
					$currency_prepare_statement = $currency_prepare_statement . ( $currency_index > 0 ? ' OR currency=%s ' : 'currency=%s' );
				}

				// currencies are added from above, so ignore prepare warning
				$currency = $wpdb->prepare( "AND (" . $currency_prepare_statement . ")", $currencies ); // phpcs:ignore
			}

			$currency_convert     = array_key_exists( 'convert', (array) $query ) && $query['convert'];
			$base_currency        = get_option( 'woocommerce_currency', 'USD' );
			$base_currency_symbol = get_woocommerce_currency_symbol( $base_currency );

			if ( $currency_convert ) {
				$update_active_currencies = array();

				// If not, check analytics DB for currencies.
				$currency_options = $wpdb->get_results( "SELECT DISTINCT currency FROM {$analytics_table} WHERE currency!=''" );

				if ( ! ( $currency_options && empty( $currency_options ) ) ) {
					foreach ( $currency_options as $currency_option ) {
						$data = wp_remote_get( peachpay_api_url( peachpay_is_test_mode() ) . "api/v1/getCurrency?from={$base_currency}&to={$currency_option->currency}" );
						if ( is_wp_error( $data ) ) {
							peachpay_notify_error( $data );
							continue;
						}

						$data = json_decode( $data['body'] );

						if ( is_object( $currency_option ) && $currency_option->currency ) {
							$update_active_currencies[ $currency_option->currency ]             = array();
							$update_active_currencies[ $currency_option->currency ]['rate']     = floatval( $data->conversion );
							$update_active_currencies[ $currency_option->currency ]['decimals'] = peachpay_is_zero_decimal_currency( $currency_option->currency ) ? 0 : 2;
						}
					}

					if ( ! array_key_exists( $base_currency, $update_active_currencies ) ) {
						$update_active_currencies[ $base_currency ]             = array();
						$update_active_currencies[ $base_currency ]['rate']     = 1;
						$update_active_currencies[ $base_currency ]['decimals'] = peachpay_is_zero_decimal_currency( $base_currency ) ? 0 : 2;

						array_push( $currency_options, $base_currency );
					}
				} elseif ( $base_currency ) {
					$update_active_currencies[ $base_currency ]             = array();
					$update_active_currencies[ $base_currency ]['rate']     = 1;
					$update_active_currencies[ $base_currency ]['decimals'] = peachpay_is_zero_decimal_currency( $base_currency ) ? 0 : 2;
				}

				if ( empty( $update_active_currencies ) ) {
					peachpay_notify_error( new Exception( 'No active currencies' ) );
					$active_currency_error = 1;
				}
			}
		}

		$order_by_param  = array_key_exists( 'order_by', (array) $query ) && $query['order_by'] ? 'ORDER BY ' . $query['order_by'] : '';
		$group_by_param  = array_key_exists( 'group_by', (array) $query ) && $query['group_by'] ? 'GROUP BY ' . $query['group_by'] : '';
		$sum_results     = array_key_exists( 'sum', (array) $query ) ? $query['sum'] : 0;
		$count_results   = array_key_exists( 'count', (array) $query ) && ! $currency_convert && $query['count'];
		$search_by_title = '';
		if ( array_key_exists( 'title', (array) $query ) ) {
			$titles = 0 === strcmp( 'array', gettype( $query['title'] ) ) ? $query['title'] : array( $query['title'] );

			$title_preparer_statement = '';
			$titles_length            = count( $titles );
			for ( $title_index = 0; $title_index < $titles_length; $title_index++ ) {
				$title_preparer_statement = $title_preparer_statement . ( $title_index > 0 ? ' OR title=%s ' : 'title=%s' );
			}

			// titles are added from above, so ignore prepare warning
			$search_by_title = $wpdb->prepare( "AND (" . $title_preparer_statement . ")", $titles ); // phpcs:ignore
		}

		$count_statement_begin = '';
		$count_statement_end   = '';
		if ( $count_results ) {
			$count_statement_begin = 'SELECT COUNT(*) AS value FROM (';
			$count_statement_end   = ') x';
		}
		try {
			$analytic_meta_data = null;

			if ( ! $active_currency_error ) {
				$analytic_meta_data = $wpdb->get_results(
					$wpdb->prepare(
						"{$count_statement_begin} SELECT id, title, currency, value FROM {$analytics_table} WHERE
						tab=%s AND section=%s {$search_by_title} {$currency} {$group_by_param} {$order_by_param}{$count_statement_end};",
						array(
							$query['tab'],
							$query['section'],
						)
					)
				);
			}
		} catch ( Exception $e ) {
			peachpay_notify_error( $e );
			$analytic_meta_data = null;
		}

		if ( $count_results ) {
			return $analytic_meta_data ? $analytic_meta_data[0]->value : 0;
		}
		if ( ! $analytic_meta_data && $sum_results ) {
			return 0;
		}

		// If the query failed or returning nothing, enter error state (return empty array() for easiest use on frontend).
		// in error state if there is no data (i.e. "count" should return empty array while "interval" should
		// return array( "labels" => array(), "datasets" => array() ))
		if ( ! $analytic_meta_data ) {
			if ( $is_interval ) {
				for ( $dates_index = 0; $dates_index < $dates_length; $dates_index++ ) {
					$dates[ $dates_index ] = gmdate( $query['format'], strtotime( $dates[ $dates_index ] ) );
				}
			}

			return $is_interval ? array(
				'labels'   => $dates,
				'datasets' => array(),
			) : array(
				'graph' => array(
					'labels'   => array(),
					'datasets' => array(
						array(
							'data'            => array(),
							'backgroundColor' => array(),
						),
					),
				),
				'value' => array(
					(object) array(
						'title' => null,
						'value' => 0,
					),
				),
			);
		}

		// Setup the length of the analytics (needed for both next parts)
		$analytic_meta_data_count = count( $analytic_meta_data );

		// Setup color picker component (needed for both next parts)
		$color_picker_interval = 360 / $analytic_meta_data_count;
		$color_picker_index    = 0 + wp_rand( 0, $color_picker_interval );

		$primary_key = 'title';
		if ( array_key_exists( 'index', (array) $query ) && 0 === strcmp( 'currency', $query['index'] ) ) {
			$primary_key = 'currency';
		}

		// If this is solely a count metric, return here.
		if ( ! $is_interval ) {
			if ( $sum_results ) {
				$compute_sum = 0;
				for ( $analytic_index = 0; $analytic_index < $analytic_meta_data_count; $analytic_index++ ) {
					if ( $currency_convert ) {
						$compute_sum += $analytic_meta_data[ $analytic_index ]->value / ( $update_active_currencies && array_key_exists( $analytic_meta_data[ $analytic_index ]->currency, $update_active_currencies ) ?
							$update_active_currencies[ $analytic_meta_data[ $analytic_index ]->currency ]['rate'] : 1 );
					} else {
						$compute_sum += $analytic_meta_data[ $analytic_index ]->value;
					}
				}

				return $currency_convert ? $base_currency_symbol . number_format( $compute_sum, $update_active_currencies[ $base_currency ]['decimals'] )
					: $compute_sum;
			}

			/**
			 * But first, update the structure for use with the graphs:
			 *
			 * Array(
			 *  labels    => array(),
			 *  datasets  => array(
			 *    array(
			 *      data  => aray( 20, 1, 34, ... ),
			 *      backgroundColor => array( ... ),
			 *    ),
			 *  )
			 * )
			 */
			$analytic_count_data = array(
				'labels'   => array(),
				'datasets' => array(
					array(
						'data'            => array(),
						'backgroundColor' => array(),
					),
				),
			);

			$grab_currency_index = 0;
			$currency_index      = array();

			for ( $analytic_index = 0; $analytic_index < $analytic_meta_data_count; $analytic_index++ ) {
				if ( ! isset( $analytic_meta_data[ $analytic_index ] ) || ! is_object( $analytic_meta_data[ $analytic_index ] )
					|| ! property_exists( $analytic_meta_data[ $analytic_index ], 'title' ) ) {
					continue;
				}

				$color_picker_index += $color_picker_interval;

				if ( ! array_key_exists( $analytic_meta_data[ $analytic_index ]->$primary_key, $currency_index ) ) {
					$grab_currency_index = count( $currency_index );

					$currency_index[ $analytic_meta_data[ $analytic_index ]->$primary_key ] = $grab_currency_index;
					$analytic_count_data['labels'][ $grab_currency_index ]                  = $analytic_meta_data[ $analytic_index ]->$primary_key;
				} else {
					$grab_currency_index = $currency_index[ $analytic_meta_data[ $analytic_index ]->$primary_key ];
				}

				if ( $currency_convert && ! property_exists( $analytic_meta_data[ $analytic_index ], 'currency' ) ) {
					continue;
				}

				$currency_rate = isset( $update_active_currencies[ $analytic_meta_data[ $analytic_index ]->currency ] ) &&
					array_key_exists( 'rate', $update_active_currencies[ $analytic_meta_data[ $analytic_index ]->currency ] ) ?
					$update_active_currencies[ $analytic_meta_data[ $analytic_index ]->currency ]['rate'] : 1;

				if ( $currency_convert && array_key_exists( $grab_currency_index, $analytic_count_data['datasets'][0]['data'] ) &&
					array_key_exists( $grab_currency_index, $analytic_count_data['datasets'][0]['backgroundColor'] ) ) {

					$analytic_count_data['datasets'][0]['data'][ $grab_currency_index ] += $analytic_meta_data[ $analytic_index ]->value /
						$update_active_currencies[ $analytic_meta_data[ $analytic_index ]->currency ]['rate'];

					continue;
				}
				$analytic_count_data['datasets'][0]['data'][ $grab_currency_index ]            = $currency_convert ?
					$analytic_meta_data[ $analytic_index ]->value / $update_active_currencies[ $analytic_meta_data[ $analytic_index ]->currency ]['rate'] :
					$analytic_meta_data[ $analytic_index ]->value;
				$analytic_count_data['datasets'][0]['backgroundColor'][ $grab_currency_index ] = "hsl({$color_picker_index} 80% 40%)";
			}
			return array(
				'graph' => $analytic_count_data,
				'value' => $analytic_meta_data,
			);
		}

		$interval_query  = PeachPay_Analytics_Time::$interval_component_connect[ $query['interval'] ];
		$time_span_query = PeachPay_Analytics_Time::time_span_component_connect( $dates[0] );

		$dates_length = count( $dates );

		$currency_index     = array();
		$analytics_interval = array();

		// Otherwise, pull data from the analytics interval table to return instead.
		// Analytic interval data will be date => array ( title => value, ... )
		for ( $grab_data_by_key = 0; $grab_data_by_key < $analytic_meta_data_count; $grab_data_by_key++ ) {
			if ( ! isset( $analytic_meta_data[ $grab_data_by_key ] ) || ! is_object( $analytic_meta_data[ $grab_data_by_key ] ) ||
				! property_exists( $analytic_meta_data[ $grab_data_by_key ], 'id' ) ||
				! property_exists( $analytic_meta_data[ $grab_data_by_key ], 'title' ) ) {
				continue;
			}

			try {
				$interval_data = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT interval_order, SUM(interval_value) AS interval_value FROM {$analytics_interval_table}
						WHERE interval_id=%d{$time_span_query} {$interval_query};",
						$analytic_meta_data[ $grab_data_by_key ]->id
					)
				);
			} catch ( Exception $e ) {
				peachpay_notify_error( $e );
				$interval_data = array();
			}

			$color_picker_index += $color_picker_interval;
			// Go through $interval_data and match with the above pattern ( interval_order => $primary_key => value )
			// Also check for coloring: if over the end of the list, just default to last place in color_mapping
			if ( ! array_key_exists( $analytic_meta_data[ $grab_data_by_key ]->$primary_key, $currency_index ) ) {
				$grab_currency_index = count( $currency_index );

				$currency_index[ $analytic_meta_data[ $grab_data_by_key ]->$primary_key ] = $grab_currency_index;
			} else {
				$grab_currency_index = $currency_index[ $analytic_meta_data[ $grab_data_by_key ]->$primary_key ];
			}
			$analytics_interval[ $grab_currency_index ] = array_key_exists( $grab_currency_index, $analytics_interval ) ?
				$analytics_interval[ $grab_currency_index ] : array(
					'label'           => $analytic_meta_data[ $grab_data_by_key ]->$primary_key,
					'data'            => array(),
					'tension'         => 0.1,
					'borderColor'     => "hsl({$color_picker_index} 80% 40%)",
					'backgroundColor' => "hsl({$color_picker_index} 80% 40%)",
				);

			$interval_data_index = 0;
			for ( $dates_index = 0; $dates_index < $dates_length; $dates_index++ ) {
				if ( array_key_exists( $interval_data_index, $interval_data ) && (
					$dates[ $dates_index ] >= $interval_data[ $interval_data_index ]->interval_order ||
					$dates_index === $dates_length - 1 ) ) {
					$interval_value = $interval_data[ $interval_data_index ]->interval_value;

					if ( array_key_exists( $dates_index, $analytics_interval[ $grab_currency_index ]['data'] ) ) {
						$analytics_interval[ $grab_currency_index ]['data'][ $dates_index ] += $currency_convert && ( $update_active_currencies && array_key_exists( $analytic_meta_data[ $grab_data_by_key ]->currency, $update_active_currencies ) ) ?
							round(
								$interval_value / $update_active_currencies[ $analytic_meta_data[ $grab_data_by_key ]->currency ]['rate'],
								$update_active_currencies[ $analytic_meta_data[ $grab_data_by_key ]->currency ]['decimals']
							) : $interval_value;
					} else {
						$analytics_interval[ $grab_currency_index ]['data'][ $dates_index ] = $currency_convert ?
						round(
							$interval_value / $update_active_currencies[ $analytic_meta_data[ $grab_data_by_key ]->currency ]['rate'],
							$update_active_currencies[ $analytic_meta_data[ $grab_data_by_key ]->currency ]['decimals']
						) : $interval_value;
					}

					++$interval_data_index;
				} elseif ( ! array_key_exists( $dates_index, $analytics_interval[ $grab_currency_index ]['data'] ) ) {
					$analytics_interval[ $grab_currency_index ]['data'][ $dates_index ] = 0;
				}
			}
		}

		// Lastyly, loop through dates and update each date to use the correct format
		for ( $dates_index = 0; $dates_index < $dates_length; $dates_index++ ) {
			$dates[ $dates_index ] = gmdate( $query['format'], strtotime( $dates[ $dates_index ] ) );
		}
		return array(
			'labels'   => $dates,
			'datasets' => $analytics_interval,
		);
	}

	/**
	 * Adds an action for inserting extra entries into the database.
	 *
	 * @param number $number_of_orders_to_insert .
	 */
	public static function attach_order_entries_inserter( $number_of_orders_to_insert = 200 ) {
		global $wpdb;

		for ( $add_entries = 0; $add_entries < $number_of_orders_to_insert; $add_entries++ ) {
			$order = wc_create_order();
			$order->add_product( wc_get_product( 15 ), 2 );
			$order->calculate_totals();
			$order->save();
		}
	}

	/**
	 * Converts woocommerce billing titles to internal titles
	 *
	 * @var array $billing_title_convert.
	 */
	public static $billing_title_convert = array(
		'billing_email'      => 'email',
		'billing_phone'      => 'phone',
		'billing_first_name' => 'first_name',
		'billing_last_name'  => 'last_name',
		'billing_address_1'  => 'address',
		'billing_address_2'  => 'address2',
		'billing_city'       => 'city',
		'billing_state'      => 'state',
		'billing_country'    => 'country',
		'billing_postcode'   => 'postcode',
	);

	/**
	 * Contains all operating sytems.
	 *
	 * @var array $operating_systems_table
	 */
	private static $operating_systems_table = array(
		/* Mac */
		'iphone'        => 'iPhone',
		'ipad'          => 'iPad',
		'mac os'        => 'Mac OS',
		/* Windows / Other */
		'windows phone' => 'Android',
		'windows'       => 'Windows',
		'android'       => 'Android',
		'cros'          => 'Chromium OS',
		'linux'         => 'Linux',
	);
	/**
	 * Contains all browsers.
	 * Note: the order of these matters. Higher up takes priority over lower ones. This is mainly
	 *  for situations like how Chrome browsers display the browser context:
	 * Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36
	 *
	 * @var array $browser_table
	 */
	private static $browser_table = array(
		'silk'     => 'Silk',
		'edg'      => 'Microsoft Edge',
		'edge'     => 'Microsoft Edge',
		'opr'      => 'Opera',
		'presto'   => 'Opera',
		'maxthon'  => 'Maxthon',
		'chrome'   => 'Chrome',
		'waterfox' => 'Waterfox',
		'palemoon' => 'Pale Moon',
		'firefox'  => 'Firefox',
		'safari'   => 'Safari',
		'yahoo'    => 'Yahoo!',
		'iemobile' => 'Internet Explorer',
		'trident'  => 'Internet Explorer',
		'bot'      => 'Crawler / Bot',
	);
	/**
	 * Pulls operating system and browser information from HTTP context.
	 */
	public static function explode_user_agent_information() {
		$user_agent_content = null;
		if ( isset( $_SERVER ) && array_key_exists( 'HTTP_USER_AGENT', $_SERVER ) ) {
			$user_agent_content = sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) );
		}

		if ( ! $user_agent_content ) {
			return;
		}
		$user_agent_content = strtolower( $user_agent_content );

		$user_agent_is_mobile = strpos( $user_agent_content, 'mobile' ) ? 'Mobile ' : '';
		// Find operating system.
		$operating_system = 'Not detected';
		foreach ( self::$operating_systems_table as $os_key => $os_value ) {
			$find_os_key_index = strpos( $user_agent_content, $os_key );
			if ( $find_os_key_index ) {
				$operating_system = $os_value;
				break;
			}
		}
		// Find browser context.
		$browser = 'Not detected';
		foreach ( self::$browser_table as $browser_key => $browser_value ) {
			$find_browser_key_index = strpos( $user_agent_content, $browser_key );
			if ( $find_browser_key_index ) {
				$browser = $browser_value;
				break;
			}
		}

		return array(
			'operating_system' => $operating_system,
			'browser'          => $user_agent_is_mobile . $browser,
		);
	}

	/**
	 * Simple greatest commmon denominator calculator.
	 *
	 * @param null|bool|int|float|string $a - First number.
	 * @param null|bool|int|float|string $b - Second number.
	 */
	private static function gcd( $a, $b ) {
		return ! $b ? $a : self::gcd( $b, $a % $b );
	}

	/**
	 * Simple lowest common multiple calculator (uses gcd).
	 *
	 * @param null|bool|int|float|string $a - First number.
	 * @param null|bool|int|float|string $b - Second number.
	 */
	private static function lcm( $a, $b ) {
		return ( $a * $b ) / self::gcd( $a, $b );
	}

	/**
	 * Takes a string or array parameter and ensures the response is always an array.
	 * This will either copy the parameter through an array or keep an array.
	 *
	 * @param string|int|array $parameter Given parameter to make an array out of.
	 * @param int              $length Expected length of array.
	 * @param int              $copy_type How to copy the parameters into the new array:
	 *  - 0: copies to the end of the list
	 *  - 1: copies all of the same values right next to eachother.
	 */
	private static function correct_analytic_parameters_to_array( $parameter, $length = 1, $copy_type = 0 ) {
		$parameter_length = 1;
		if ( 'array' === gettype( $parameter ) ) {
			$parameter_length = count( $parameter );
		} else {
			$parameter = array( $parameter );
		}

		$lcm            = self::lcm( $length, $parameter_length );
		$parameter_copy = $lcm / $parameter_length;

		// compute the lowest common multiple to use for building the correct length array
		$explode_parameter = array();
		if ( $copy_type ) {
			for ( $segment_parameter_index = 0; $segment_parameter_index < $parameter_length; $segment_parameter_index++ ) {
				for ( $parameter_copy_index = 0; $parameter_copy_index < $parameter_copy; $parameter_copy_index++ ) {
					$explode_parameter[ $segment_parameter_index * $parameter_copy + $parameter_copy_index ] = $parameter[ $segment_parameter_index ];
				}
			}
		} else {
			for ( $segment_parameter_index = 0; $segment_parameter_index < $lcm; $segment_parameter_index++ ) {
				$explode_parameter[ $segment_parameter_index ] = $parameter[ $segment_parameter_index % $parameter_length ];
			}
		}

		return array( $explode_parameter, $lcm );
	}
	/**
	 * Will run updates based on given argument input. Either insert "count" or "interval" and the rest is handled
	 *  based on inputs.
	 *
	 * NOTE: Decides which path (count versus interval) based on section
	 *
	 * Returns 1 on success, null on failure.
	 *
	 * @param array $update Instructions for what updates will look like. Parameters:
	 * - @var string tab             => defines what specific tab to search for (payment_methods, device_breakdown, etc.)
	 * - @var string|array section   => defines what specific section to search for (order_count, order_interval, etc.)
	 *   - NOTE: this can take multiple (though only "order" and "interval") by separating with comma,
	 *       which would look like:
	 *     self::update_analytics(
	 *       array(
	 *         'tab'     => 'payment_methods',
	 *         'section' => array(
	 *            'order_count',
	 *            'order_interval',
	 *         ),
	 *         'title'   => 'peachpay_square_card',
	 *         'value'   => 1,
	 *       )
	 *     );
	 * - @var string|array title     => defines what specific key to search for.
	 * - @var string|array currency  => The specific currency to update
	 * - @var double|array value     => value to input. If array is given, attempts to match each input with the
	 *    inputted 'section'.
	 * - @var date|null date_overide => Forcefully overides date system for inserting test cases.
	 * NOTE: only 'section' and 'value' have options for multiple values.
	 *
	 * @throws Exception On failed DB query.
	 */
	public static function update_analytics( $update ) {
		global $wpdb;

		$cart_analytics_table = $wpdb->prefix . self::get_table_name( 'create_analytics_table' );
		$cart_interval_table  = $wpdb->prefix . self::get_table_name( 'create_analytics_interval_table' );

		// Check existence of cart analytics table before any attempts on updating it.
		if ( ! self::check_table_existence( $cart_analytics_table ) ) {
			return;
		}

		// Compute if the the query is interval or count by pulling the last portion of the section off:
		$separate_sections       = 0 === strcmp( 'array', gettype( $update['section'] ) ) ?
			$update['section'] : explode( ',', $update['section'] );
		$separate_sections_count = count( $separate_sections );

		$largest_count = 0;
		// Segment all other inputs based on # of sections:
		$separate_titles       = self::correct_analytic_parameters_to_array( $update['title'], $separate_sections_count, 1 );
		$largest_count         = $separate_titles[1] > $largest_count ? $separate_titles[1] : $largest_count;
		$separate_titles       = $separate_titles[0];
		$separate_titles_count = count( $separate_titles );

		$separate_currencies       = self::correct_analytic_parameters_to_array( array_key_exists( 'currency', $update ) ? $update['currency'] : null, $separate_sections_count, 1 );
		$largest_count             = $separate_currencies[1] > $largest_count ? $separate_currencies[1] : $largest_count;
		$separate_currencies       = $separate_currencies[0];
		$separate_currencies_count = count( $separate_currencies );

		$separate_values       = self::correct_analytic_parameters_to_array( $update['value'], $separate_sections_count );
		$largest_count         = $separate_values[1] > $largest_count ? $separate_values[1] : $largest_count;
		$separate_values       = $separate_values[0];
		$separate_values_count = count( $separate_values );

		for ( $separate_sections_index = 0; $separate_sections_index < $largest_count; $separate_sections_index++ ) {
			$separate_section  = $separate_sections[ $separate_sections_index % $separate_sections_count ];
			$separate_title    = $separate_titles[ $separate_sections_index % $separate_titles_count ];
			$separate_currency = $separate_currencies[ $separate_sections_index % $separate_currencies_count ];
			$separate_value    = $separate_values[ $separate_sections_index % $separate_values_count ];

			$find_needle_split = explode( '_', $separate_section );
			// Comput if the input is type count or interval (will need to change if different analytics are added in the future)
			$is_count = 0 === strcmp( 'count', $find_needle_split[ count( $find_needle_split ) - 1 ] );
			if ( $is_count ) { // Count.
				try {
					$wpdb->query(
						$wpdb->prepare(
							"INSERT INTO {$cart_analytics_table} (tab, section, title, currency, value)
								VALUES (%s, %s, %s, %s, %f) ON DUPLICATE KEY UPDATE value=TRUNCATE(value+%f, 2);",
							array(
								$update['tab'],
								$separate_section,
								$separate_title,
								$separate_currency,
								$separate_value,
								$separate_value,
							)
						)
					);
				} catch ( Exception $e ) {
					peachpay_notify_error( $e );
				}
			} else { // Interval.
				try {
					$analytic_id = $wpdb->get_var(
						$wpdb->prepare(
							"SELECT id FROM {$cart_analytics_table} WHERE
							tab=%s AND section=%s AND title=%s AND currency=%s;",
							array(
								$update['tab'],
								$separate_section,
								$separate_title,
								$separate_currency,
							)
						)
					);

					// Check if meta analytic needs to be created:
					if ( ! $analytic_id ) {
						$wpdb->query(
							$wpdb->prepare(
								"INSERT INTO {$cart_analytics_table} (tab, section, title, currency) VALUES(
									%s, %s, %s, %s);",
								array(
									$update['tab'],
									$separate_section,
									$separate_title,
									$separate_currency,
								)
							)
						);
						$analytic_id = $wpdb->get_var( 'SELECT LAST_INSERT_ID();' );
					}

					// Check existence of cart interval table.
					if ( ! self::check_table_existence( $cart_interval_table ) ) {
						return;
					}

					$interval_order = $wpdb->get_var(
						$wpdb->prepare(
							"SELECT interval_order FROM {$cart_interval_table} WHERE interval_id=%d ORDER BY interval_order DESC;",
							$analytic_id
						)
					);

					// If either there is no entry at all or the previous entry is over a day old, insert new entry data.
					$one_day_ago            = array_key_exists( 'date_overide', $update ) ?
						strtotime( $update['date_overide'] ) : strtotime( '-1 day' );
					$interval_should_update = $interval_order && $interval_order && $one_day_ago < strtotime( $interval_order );

					$cart_interval_insertion_date = 'NOW()';
					if ( ! $interval_should_update ) {
						$cart_interval_insertion_date = array_key_exists( 'date_overide', $update ) ?
							$wpdb->prepare( '%s', $update['date_overide'] ) : $cart_interval_insertion_date;
					}

					if ( $interval_should_update ) {
						$wpdb->query(
							$wpdb->prepare(
								"UPDATE {$cart_interval_table} SET interval_value=TRUNCATE(interval_value+%f, 2) WHERE interval_id=%d
									ORDER BY interval_order DESC LIMIT 1;",
								array(
									$separate_value,
									$analytic_id,
								)
							)
						);
					} else {
						$wpdb->query(
							$wpdb->prepare(
								"INSERT INTO {$cart_interval_table} (interval_id, interval_order, interval_value)
									VALUES(%d, {$cart_interval_insertion_date}, TRUNCATE(%f, 2));",
								array(
									$analytic_id,
									$separate_value,
								)
							)
						);
					}
				} catch ( Exception $e ) {
					peachpay_notify_error( $e );
				}
			}
		}

		return 1;
	}

	/**
	 * Updates any and all billing information that may come from class-peachpay-routes-manager.php
	 *
	 * @param string $billing_key The specific billing information to update (address, city, etc.).
	 * @param string $billing_value The specific value to update the key with.
	 */
	public static function update_billing( $billing_key, $billing_value ) {
		global $wpdb;

		$cart_meta_table = $wpdb->prefix . self::get_table_name( 'create_cart_meta_table' );
		$cart_table      = $wpdb->prefix . self::get_table_name( 'create_cart_table' );

		// Check existence of cart tables.
		if ( ! ( self::check_table_existence( $cart_meta_table ) && self::check_table_existence( $cart_table ) ) ) {
			return;
		}

		$cart_id = self::session_value( 'cart-id' );
		if ( ! $cart_id ) {
			return;
		}

		try {
			$check_cart_id = $wpdb->get_var( $wpdb->prepare( "SELECT cart_id FROM {$cart_meta_table} WHERE cart_id=%d;", $cart_id ) );
			if ( ! $check_cart_id ) {
				return;
			}

			return $wpdb->query(
				$wpdb->prepare(
					"INSERT INTO {$cart_table} (cart_id, {$billing_key}, date_created, date_updated) VALUES (
						%d, %s, NOW(), NOW())
						ON DUPLICATE KEY UPDATE {$billing_key}=%s, date_updated=NOW();",
					array(
						$cart_id,
						$billing_value,
						$billing_value,
					)
				)
			);
		} catch ( Exception $e ) {
			peachpay_notify_error( $e );
		}
	}

	/**
	 * Drops all PeachPay tables from the local database.
	 */
	public static function drop_tables() {
		global $wpdb;

		// Tables need to be dropped in reverse order to satisfy foreign key constraints.
		$tables_reverse = array_reverse( self::$tables, true );

		foreach ( $tables_reverse as $key => $table ) {
			$table_name = $wpdb->prefix . $key;
			try {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.SchemaChange
				$response = $wpdb->query( "DROP TABLE IF EXISTS $table_name;" );
				if ( ! $response ) {
					return 'There was an error dropping tables. Check your foreign constraints and table add order? Failed on table: ' . $table_name;
				}
			} catch ( Exception $e ) {
				peachpay_notify_error( $e );
			}
		}

		return $response;
	}
}
PeachPay_Analytics_Database::instance();

// phpcs:enable WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery
