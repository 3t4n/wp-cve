<?php
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'ewduwcfScheduling' ) ) {
/**
 * Class to handle store scheduling for Ultimate WooCommerce Filters
 *
 * @since 3.3.0
 */
class ewduwcfScheduling {

	public function __construct() {

		add_action( 'woocommerce_is_purchasable', 			array( $this, 'maybe_enable_catalog_mode' ) );

		add_filter( 'woocommerce_loop_add_to_cart_link', 	array( $this, 'maybe_hide_read_more' ) );

		add_filter( 'woocommerce_variable_sale_price_html', array( $this, 'maybe_hide_price' ) );
		add_filter( 'woocommerce_variable_price_html', 		array( $this, 'maybe_hide_price' ) ); 
		add_filter( 'woocommerce_get_price_html', 			array( $this, 'maybe_hide_price' ) );

		add_filter( 'woocommerce_catalog_orderby', 			array( $this, 'maybe_remove_price_from_sorting' ) );
	}

	/**
	 * Disables ordering if the store is currently closed
	 * @since 3.3.0
	 */
	public function maybe_enable_catalog_mode( $open ) {
		
		if ( $this->is_store_open() ) { return $open; }

		return false;
	}

	/**
	 * Hides the 'Read More' text when the store is closed, if enabled
	 * @since 3.3.0
	 */
	public function maybe_hide_read_more( $read_more ) {
		global $ewd_uwcf_controller;

		if ( empty( $ewd_uwcf_controller->settings->get_setting( 'disable-read-more' ) ) ) { return $read_more; }

		if ( $this->is_store_open() ) { return $read_more; }

		return false;
	}

	/**
	 * Hides the product price when the store is closed, if enabled
	 * @since 3.3.0
	 */
	public function maybe_hide_price( $price ) {
		global $ewd_uwcf_controller;

		if ( is_admin() ) { return $price; }

		if ( empty( $ewd_uwcf_controller->settings->get_setting( 'disable-prices' ) ) ) { return $price; }

		if ( $this->is_store_open() ) { return $price; }

		return '';
	}

	/**
	 * Removes the price sorting options if the store is closed, if selected
	 * @since 3.3.0
	 */
	public function maybe_remove_price_from_sorting( $orderby ) {
		global $ewd_uwcf_controller;

		if ( empty( $ewd_uwcf_controller->settings->get_setting( 'disable-prices' ) ) ) { return $orderby; }

		if ( $this->is_store_open() ) { return $orderby; }

		unset( $orderby['price'] );
		unset( $orderby['price-desc'] );

		return $orderby;
	}

	/**
	 * Returns false if ordering is currently disabled, true otherwise 
	 * @since 3.3.0
	 */
	public function is_store_open() {
		global $ewd_uwcf_controller;
	
		// Ordering manually disabled
		if ( $ewd_uwcf_controller->settings->get_setting( 'disable-ordering' ) ) {
	
			return false;
		}
	
		// Scheduling isn't enabled
		if ( empty( $ewd_uwcf_controller->settings->get_setting( 'enable-scheduling' ) ) ) {
	
			return true;
		}
	
		// Start with exceptions
		$schedule_closed = $ewd_uwcf_controller->settings->get_setting( 'schedule-closed');
		$schedule_closed = is_array( $schedule_closed ) ? $schedule_closed : array();
	
		// Check if today is an exception to the rules
		$now = ( new DateTime( 'now', wp_timezone() ) );
	
		foreach ( $schedule_closed as $ids => $closing ) {
			
			if ( array_key_exists( 'date_range', $closing ) ) {
	
				$start = ! empty( $closing['date_range']['start'] )
					? new DateTime( $closing['date_range']['start'], wp_timezone() )
					: new DateTime( 'now', wp_timezone() );
					$start->setTime(0, 0);
	
				$end = !empty( $closing['date_range']['end'] )
					? new DateTime( $closing['date_range']['end'], wp_timezone() )
					: ( new DateTime( 'now', wp_timezone() ) )->add( new DateInterval( 'P10Y' ) );
					$end->setTime(23, 59, 58);
	
				if ( $start < $now && $now < $end ) {
					
					return false;
				}
				else {
					
					continue;
				}
			}
			
			// Not a date range
			$exception = ( new DateTime( $closing['date'], wp_timezone() ) )->setTime(0, 0, 2);
	
			// Does this exception apply to today?
			if ( $exception->format( 'Y-m-d' ) == $now->format( 'Y-m-d' ) ) {
	
				// Closed all day
				if ( ! isset( $closing['time'] ) || $closing['time'] == 'undefined' ) {
					
					return false;
				}
	
				
				if ( isset( $closing['time'] ) and $closing['time']['start'] !== 'undefined' ) {
					
					$open_time = ( new DateTime( $exception->format( 'Y-m-d' ) . ' ' . $closing['time']['start'], wp_timezone() ) )->format( 'U' );
				}
				else {
	
					// Start of the day
					$open_time = ( new DateTime( $exception->format( 'Y-m-d' ), wp_timezone() ) )->format('U');
				}
	
				if ( isset( $closing['time'] ) and $closing['time']['end'] !== 'undefined' ) {
					
					$close_time = ( new DateTime( $exception->format( 'Y-m-d' ) . ' ' . $closing['time']['end'], wp_timezone() ) )->format( 'U' );
				}
				else {
	
					// End of the day
					$close_time = ( new DateTime( $exception->format( 'Y-m-d' ) . ' 23:59:59', wp_timezone() ) )->format( 'U' );
				}
	
				// store is currently open
				if ( $now->format( 'U' ) > $open_time and $now->format( 'U' ) < $close_time ) {
	
					return true;
				}
	
				return false;
			}
		}
	
		// Go through all scheduling rules to see if any match the current time
		$schedule_open = $ewd_uwcf_controller->settings->get_setting( 'schedule-open' );
		$schedule_open = is_array( $schedule_open ) ? $schedule_open : array();
	
		$now = ( new DateTime( 'now', wp_timezone() ) );
	
		// Get any rules which apply to this weekday
		$day_of_week =  strtolower( $now->format( 'l' ) );
	
		foreach ( $schedule_open as $opening ) {
	
			if ( $opening['weekdays'] === 'undefined' ) { continue; }
	
			foreach ( $opening['weekdays'] as $weekday => $value ) {
	
				if ( $weekday == $day_of_week ) {
	
					if ( isset( $opening['time'] ) and $opening['time']['start'] !== 'undefined' ) {
					
						$open_time = ( new DateTime( $now->format( 'Y-m-d' ) . ' ' . $opening['time']['start'], wp_timezone() ) )->format( 'U' );
					}
					else {
	
						// Start of the day
						$open_time = ( new DateTime( $now->format( 'Y-m-d' ) . '00:00:01', wp_timezone() ) )->format('U');
					}
	
					if ( isset( $opening['time'] ) and $opening['time']['end'] !== 'undefined' ) {
					
						$close_time = ( new DateTime( $now->format( 'Y-m-d' ) . ' ' . $opening['time']['end'], wp_timezone() ) )->format( 'U' );
					}
					else {
	
						// End of the day
						$close_time = ( new DateTime( $now->format( 'Y-m-d' ) . ' 23:59:59', wp_timezone() ) )->format( 'U' );
					}
	
					// store is currently open
					if ( $now->format( 'U' ) > $open_time and $now->format( 'U' ) < $close_time ) {
	
						return true;
					}
				}
			}
		}
	
		// Store is closed if no matches found
		return false;
	}
}

}