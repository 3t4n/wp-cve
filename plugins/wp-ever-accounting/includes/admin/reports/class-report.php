<?php
/**
 * Admin Report.
 *
 * Extended by reports to show charts and stats in admin.
 *
 * @version     1.1.0
 * @category    Admin
 * @package     EverAccounting\Admin
 * @author      EverAccounting
 */

namespace EverAccounting\Admin\Report;

use DatePeriod;

defined( 'ABSPATH' ) || exit();

/**
 * Report Class
 *
 * @package EverAccounting\Admin\Report
 */
class Report {
	/**
	 * Get report.
	 *
	 * @param array $args Report arguments.
	 * @since 1.1.0
	 *
	 * @return array|mixed|void
	 */
	public function get_report( $args = array() ) {
	}

	/**
	 * Output report.
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public function output() {
	}

	/**
	 * Get start date.
	 *
	 * @param string $year  Year.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	public function get_start_date( $year = null ) {
		if ( null === $year ) {
			$year = date_i18n( 'Y' );
		}

		return eaccounting_get_financial_start( intval( $year ) );
	}

	/**
	 * Get end date.
	 *
	 * @param string $year Year.
	 *
	 * @since 1.1.0
	 *
	 * @throws \Exception Exception.
	 * @return string
	 */
	public function get_end_date( $year = null ) {
		if ( null === $year ) {
			$year = date_i18n( 'Y' );
		}

		return eaccounting_get_financial_end( intval( $year ) );
	}


	/**
	 * Get months in the financial period.
	 *
	 * @param string $start_date Start date.
	 * @param  string $end_date End date.
	 * @param string $interval Period.
	 * @param string $date_key Date key.
	 * @param string $date_value Date value.
	 *
	 * @since 1.1.0
	 *
	 * @return array
	 */
	public function get_dates_in_period( $start_date, $end_date, $interval = 'M', $date_key = 'Y-m', $date_value = 'M y' ) {
		$dates  = array();
		$period = new DatePeriod(
			new \DateTime( $start_date ),
			new \DateInterval( "P1{$interval}" ),
			new \DateTime( $end_date )
		);
		foreach ( $period as $key => $value ) {
			$dates[ $value->format( $date_key ) ] = $value->format( $date_value );
		}

		return $dates;
	}

	/**
	 * Get range sql.
	 *
	 * @param  string $column    Column.
	 * @param string $start_date Start date.
	 * @param string $end_date    End date.
	 *
	 * @since 1.1.0
	 *
	 * @throws \Exception Exception.
	 * @return array
	 */
	public function get_range_sql( $column, $start_date = null, $end_date = null ) {
		global $wpdb;
		$start_date = empty( $start_date ) ? $this->get_start_date() : $start_date;
		$end_date   = empty( $end_date ) ? $this->get_end_date() : $end_date;
		$start      = strtotime( $start_date );
		$end        = strtotime( $end_date );
		$date       = 'CAST(`' . $column . '` AS DATE)';

		$period = 0;
		while ( ( $start = strtotime( '+1 MONTH', $start ) ) <= $end ) {
			$period ++;
		}

		$sql = array();
		switch ( $period ) {
			case $period < 24:
				$sql = array(
					"DATE_FORMAT(`$column`, '%Y-%m')",
					$wpdb->prepare( "$date BETWEEN %s AND %s", $start_date, $end_date ),
				);
				break;
		}

		return $sql;
	}


	/**
	 * Clear cache and redirect .
	 *
	 * @param string $key Cache key.
	 *
	 * @since 1.1.0
	 */
	public function maybe_clear_cache( $key ) {
		$nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : '';
		if ( ! empty( $_GET['refresh_report'] ) && ! empty( $nonce ) && wp_verify_nonce( $nonce, 'refresh_report' ) ) {
			$this->delete_cache( $key );
			wp_safe_redirect( remove_query_arg( array( 'refresh_report', '_wpnonce' ) ) );
			exit();
		}
	}

	/**
	 * Generate cache key.
	 *
	 * @param string $key Cache key.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	public function generate_cache_key( $key ) {
		if ( ! is_string( $key ) ) {
			$key = maybe_serialize( $key ) . get_called_class();
		}

		return 'eaccounting_cache_report_' . $key;
	}

	/**
	 * Add cache key.
	 *
	 * @param string $key Cache key.
	 * @param string $value Cache value.
	 * @param int    $minute Cache expire.
	 *
	 * @since 1.1.0
	 *
	 * @return bool True on success, false on failure.
	 */
	public function set_cache( $key, $value, $minute = 5 ) {
		if ( ! is_string( $key ) ) {
			$key = $this->generate_cache_key( $key );
		}

		return set_transient( $key, $value, MINUTE_IN_SECONDS * $minute );
	}

	/**
	 * Get cache.
	 *
	 * @param string $key Cache key.
	 *
	 * @since 1.1.0
	 *
	 * @return mixed
	 */
	public function get_cache( $key ) {
		if ( ! is_string( $key ) ) {
			$key = $this->generate_cache_key( $key );
		}

		return get_transient( $key );
	}

	/**
	 * Delete report cache.
	 *
	 * @param string $key Cache key.
	 *
	 * @since 1.1.0
	 *
	 * @return bool|void
	 */
	public function delete_cache( $key ) {
		if ( ! is_string( $key ) ) {
			$key = $this->generate_cache_key( $key );
		}

		return delete_transient( $key );
	}
}
