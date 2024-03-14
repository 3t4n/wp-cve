<?php
/**
 * Cron Helper Functions
 * @package WooFeed
 * @subpackage WooFeed_Helper_Functions
 * @version 1.0.0
 * @since WooFeed 3.3.0
 * @author KD <mhamudul.hk@gmail.com>
 * @copyright WebAppick
 */

use CTXFeed\V5\Common\Helper;
use CTXFeed\V5\Utility\Logs;
use CTXFeed\V5\Utility\Settings;
use CTXFeed\V5\Feed\Feed;
use CTXFeed\V5\Helper\FeedHelper;

if ( ! defined( 'ABSPATH' ) ) {
	die(); // Silence...
}
/** @define "WOO_FEED_FREE_ADMIN_PATH" "./../admin/" */ // phpcs:ignore
// Cron Action.
if ( ! function_exists( 'woo_feed_cron_update_feed' ) ) {
	/**
	 * Scheduled Action Hook
	 * @return void
	 */
	function woo_feed_cron_update_feed() {
		if ( Helper::is_debugging_enabled() ) {
			Logs::delete_log( 'woo-feed-cron' );
			Logs::write_log( 'woo-feed-cron', 'Preparing WooFeed Auto Update' );
			$processed = 0;
		}

		$all_feeds = Feed::get_all_feeds();
		foreach ( $all_feeds as $option ) {
			$feedInfo = $option['option_value'];

			if ( ! isset( $feedInfo['feedrules'] ) || isset( $feedInfo['status'] ) && '0' == $feedInfo['status'] ) {
				continue;
			}
			try {
				Settings::get( 'enable_error_debugging' );
				if ( Helper::is_debugging_enabled() ) {
					$processed ++;
					Logs::delete_log( $feedInfo['feedrules']['filename'] );

					Logs::write_log(  $feedInfo['feedrules']['filename'], sprintf( 'Getting Data for %s feed.', $feedInfo['feedrules']['filename'] ) );
					Logs::write_log( $feedInfo['feedrules']['filename'], 'Generating Feed VIA CRON JOB...' );
					Logs::write_log( $feedInfo['feedrules']['filename'], sprintf( 'Getting Data for %s feed.', $option['option_name'] ) );
					Logs::write_log( $feedInfo['feedrules']['filename'], 'Current Limit is ---.' );
					Logs::write_log( $feedInfo['feedrules']['filename'], 'Feed Config::' . PHP_EOL . print_r( $feedInfo['feedrules'], true ), 'info' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
				}

				$option = FeedHelper::validate_feed( $option );

				$status = FeedHelper::generate_feed( $option );
			} catch ( Exception $e ) {
				$message = 'Error Updating Feed Via CRON Job' . PHP_EOL . 'Caught Exception :: ' . $e->getMessage();
				Logs::write_log( $feedInfo['feedrules']['filename'], $message, 'critical', $e, true );
				Logs::write_fatal_log( $message, $e);
			}
		}
		if ( Helper::is_debugging_enabled() ) {
			Logs::write_log( 'woo-feed-cron', sprintf( 'Total %d Feed Processed', $processed ) );
			Logs::write_log( 'woo-feed-cron', 'WooFeed Auto Update Completed' );
		}
	}

	add_action( 'woo_feed_update', 'woo_feed_cron_update_feed' );
}

// Single Feed Update Cron
if ( ! function_exists( 'woo_feed_cron_update_single_feed' ) ) {
	/**
	 * Scheduled Action Hook
	 *
	 * @param array $feedName
	 *
	 * @return void
	 */
	function woo_feed_cron_update_single_feed( $feedName ) {
		global $wpdb;
		if ( is_array( $feedName ) ) {
			$feedName = $feedName[0];
		}

		if ( Helper::is_debugging_enabled() ) {
			Logs::delete_log( 'woo-feed-cron' );
			Logs::write_log( 'woo-feed-cron', 'Preparing WooFeed Auto Update' );
			$processed = 0;
		}

		$cron_option_name = $feedName;

		$feedName = str_replace( 'wf_config', 'wf_feed_', $feedName );
		// get interval
		$interval = absint( get_option( 'wf_schedule' ) );

		// schedule single feed update
		wp_clear_scheduled_hook( 'woo_feed_update_single_feed', array( $cron_option_name ) );
		if ( ! wp_next_scheduled( 'woo_feed_update_single_feed', array( $feedName ) ) ) {
			wp_schedule_event( time() + $interval, 'woo_feed_corn', 'woo_feed_update_single_feed', array( $feedName ) );
		}

		$results = Feed::get_single_feed( $feedName ); // phpcs:ignore

		if ( ! empty( $results ) ) {
			foreach ( $results as $option ) {
				$feedInfo = $option['option_value'];
				if ( ! isset( $feedInfo['feedrules'] ) || isset( $feedInfo['status'] ) && '0' == $feedInfo['status'] ) {
					continue;
				}
				try {
					if ( Helper::is_debugging_enabled() ) {
						$processed ++;
						Logs::delete_log( $feedInfo['feedrules']['filename'] );
						Logs::write_log(  $feedInfo['feedrules']['filename'], sprintf( 'Getting Data for %s feed.', $feedInfo['feedrules']['filename'] ) );
						Logs::write_log( $feedInfo['feedrules']['filename'], 'Generating Feed VIA CRON JOB...' );
						Logs::write_log( $feedInfo['feedrules']['filename'], sprintf( 'Getting Data for %s feed.', $option['option_name'] ) );
						Logs::write_log( $feedInfo['feedrules']['filename'], 'Current Limit is ---.' );
						Logs::write_log( $feedInfo['feedrules']['filename'], 'Feed Config::' . PHP_EOL . print_r( $feedInfo['feedrules'], true ), 'info' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
					}
					$option = FeedHelper::validate_feed( $option );
					// woo_feed_generate_feed( $feedInfo['feedrules'], $value['option_name'] );
					$status = FeedHelper::generate_feed( $option );
				} catch ( Exception $e ) {
					$message = 'Error Updating Feed Via CRON Job' . PHP_EOL . 'Caught Exception :: ' . $e->getMessage();
					Logs::write_log( $feedInfo['feedrules']['filename'], $message, 'critical', $e, true );
					Logs::write_fatal_log( $message, $e);
				}
			}
			if ( Helper::is_debugging_enabled() ) {
				Logs::write_log( 'woo-feed-cron', sprintf( 'Total %d Feed Processed', $processed ) );
				Logs::write_log( 'woo-feed-cron', 'WooFeed Auto Update Completed' );
			}
		}
	}

	add_action( 'woo_feed_update_single_feed', 'woo_feed_cron_update_single_feed' );
}

add_action( 'woo_feed_before_update_config', function ( $data, $feed_slug ) {
	wp_clear_scheduled_hook( 'woo_feed_update_single_feed', array( $feed_slug ) );
}, 10, 3 );

add_action( 'woo_feed_before_insert_config', function ( $data, $feed_slug ) {
	wp_clear_scheduled_hook( 'woo_feed_update_single_feed', array( $feed_slug ) );
}, 10, 3 );


add_action( 'woo_feed_after_update_config', function ( $data, $feed_slug ) {
	$interval = absint( get_option( 'wf_schedule' ) );
	// Schedule Cron.
	$feedInfo           = maybe_unserialize( get_option( $feed_slug ) );
	if ( 1 === $feedInfo['status'] ) {
		if ( ! wp_next_scheduled( 'woo_feed_update_single_feed', [ $feed_slug ] ) ) {
			wp_schedule_event( (time() + $interval), 'woo_feed_corn', 'woo_feed_update_single_feed', [ $feed_slug ] );
		}
	}else{
		wp_clear_scheduled_hook( 'woo_feed_update_single_feed', array( $feed_slug ) );
	}

}, 10, 3 );

add_action( 'woo_feed_after_insert_config', function ( $data, $feed_slug ) {
	$interval = absint( get_option( 'wf_schedule' ) );
	// Schedule Cron.
	if ( ! wp_next_scheduled( 'woo_feed_update_single_feed', [ $feed_slug ] ) ) {
		wp_schedule_event( (time() + $interval), 'woo_feed_corn', 'woo_feed_update_single_feed', [ $feed_slug ] );
	}

}, 10, 3 );
// End of file cron-helper.php.
