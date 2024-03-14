<?php

namespace CTXFeed\V5\Feed;


use CTXFeed\V5\Common\Helper;
use CTXFeed\V5\Helper\FeedHelper;
use CTXFeed\V5\Product\AttributeValueByType;
use \WP_Error;
/**
 * Class Feed
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Feed
 * @author     Ohidul Islam <wahid0003@gmail.com>
 * @link       https://webappick.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 * @category   MyCategory
 */
class Feed {

	/**
	 * @var array
	 */
	private static $feed_lists = [];

	public static function getFeed(  ) {

	}

	public static function makeFeed(  ) {

	}

	public static function updateFeed(  ) {

	}

	/**
	 * Update feed status
	 */
	public static function update_feed_status($feedName, $status) {
		$feedName = isset( $feedName ) ? sanitize_text_field( wp_unslash( $feedName ) ) : false;
		if ( ! empty( $feedName ) ) {
			$feedInfo           = maybe_unserialize( get_option( $feedName ) );
			$feedInfo['status'] = isset( $status ) && 1 === (int) $status ? 1 : 0;

			$feed_slug = str_replace( 'wf_feed_', 'wf_config', $feedName );
			if ( 1 === $feedInfo['status'] ) {
				if ( ! wp_next_scheduled( 'woo_feed_update_single_feed', array( $feed_slug ) ) ) {
					$interval = absint( get_option( 'wf_schedule' ) );
					wp_schedule_event( (time() + $interval), 'woo_feed_corn', 'woo_feed_update_single_feed', array( $feed_slug ) );
				}
			} else {
				wp_clear_scheduled_hook( 'woo_feed_update_single_feed', array( $feed_slug ) );
			}

			update_option( sanitize_text_field( wp_unslash( $feedName ) ), serialize( $feedInfo ), false ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
			return true;
		}

		return false;
	}

	public static function delete_feed($feed_id) {
		$feed_id   = apply_filters('CTXFEED_filter_securing_input', "GET", $feed_id, "text");
		global $wpdb;
		if ( ! is_numeric( $feed_id ) ) {
			$feed_name = Helper::extract_feed_option_name( $feed_id );
		} else {
			$feed_data   = $wpdb->get_row( $wpdb->prepare( "SELECT option_name FROM $wpdb->options WHERE option_id = %d", $feed_id ) ); // phpcs:ignore
			$feed_name   = Helper::extract_feed_option_name( $feed_data->option_name );
		}
		$feedInfo = maybe_unserialize( get_option( 'wf_feed_' . $feed_name ) );
		if ( false !== $feedInfo ) {
			$feedInfo = $feedInfo['feedrules'];
		} else {
			$feedInfo = maybe_unserialize( get_option( 'wf_config' . $feed_name ) );
		}
		$deleted = false;
		$file    = Helper::get_file( $feed_name, $feedInfo['provider'], $feedInfo['feedType'] );
		// delete any leftover
		Helper::unlink_tempFiles( $feedInfo, $feed_name );
		if ( file_exists( $file ) ) {
			// file exists in upload directory
			if ( unlink( $file ) ) { // phpcs:ignore
				delete_option( 'wf_feed_' . $feed_name );
				delete_option( 'wf_config' . $feed_name );
				$deleted = true;
			}
		} else {
			delete_option( 'wf_feed_' . $feed_name );
			delete_option( 'wf_config' . $feed_name );
			$deleted = true;
		}

		// Delete cron schedule.
		$feed_cron_param = 'wf_config' . $feed_name;
		wp_clear_scheduled_hook( 'woo_feed_update_single_feed', array( $feed_cron_param ) );

		return $deleted;

	}

	public static function get_all_feeds( $status = '' ) {
		global $wpdb;
		self::$feed_lists = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->options WHERE option_name LIKE %s ORDER BY option_id DESC;", 'wf_feed_%' ), 'ARRAY_A' );
		//SELECT * FROM wp_options WHERE option_name LIKE '_transient_timeout___woo_feed_cache_%' OR option_name LIKE '_transient___woo_feed_cache_%'

		return FeedHelper::prepare_all_feeds( self::$feed_lists , $status );
	}

	public static function get_single_feed( $option_name ) {
		global $wpdb;
		self::$feed_lists = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->options WHERE option_name LIKE %s ORDER BY option_id DESC;", $option_name ), 'ARRAY_A' );
		//SELECT * FROM wp_options WHERE option_name LIKE '_transient_timeout___woo_feed_cache_%' OR option_name LIKE '_transient___woo_feed_cache_%'

		return FeedHelper::prepare_all_feeds( self::$feed_lists , '' );
	}

	/**
	 * @param string $feed_from Required. Feed name to duplicate from
	 * @param string $new_name Optional. New name for duplicate feed.
	 *                              Default to auto generated slug from the old name prefixed with number.
	 * @param bool $copy_file Optional. Copy the file. Default is true.
	 *
	 * @return bool|WP_Error        WP_Error object on error, true on success.
	 */
	public static function duplicate_feed( $feed_from, $new_name = '', $copy_file = true ) {

		if ( empty( $feed_from ) ) {
			return new WP_Error( 'invalid_feed_name_top_copy_from', esc_html__( 'Invalid Request.', 'woo-feed' ) );
		}
		// normalize the option name.
		$feed_from = Helper::extract_feed_option_name( $feed_from );
		// get the feed data for duplicating.
		$base_feed = maybe_unserialize( get_option( 'wf_feed_' . $feed_from, array() ) );
		// validate the feed data.
		if ( empty( $base_feed ) || ! is_array( $base_feed ) || ! isset( $base_feed['feedrules'] ) || ( isset( $base_feed['feedrules'] ) && empty( $base_feed['feedrules'] ) ) ) {
			return new WP_Error( 'empty_base_feed', esc_html__( 'Feed data is empty. Can\'t duplicate feed.', 'woo-feed' ) );
		}
		$part = '';
		if ( empty( $new_name ) ) {
			// generate a unique slug for duplicate the feed.
			$new_name = FeedHelper::generate_unique_feed_file_name( $feed_from, $base_feed['feedrules']['feedType'], $base_feed['feedrules']['provider'] );
			// example-2 or example-2-2-3
			$part = ' ' . Helper::str_replace_trim( $feed_from . '-', '', $new_name ); // -2-2-3
		} else {
			$new_name = FeedHelper::generate_unique_feed_file_name( $new_name, $base_feed['feedrules']['feedType'], $base_feed['feedrules']['provider'] );
		}

		$new_name = AttributeValueByType::FEED_RULES_OPTION_PREFIX . $new_name;

		// new name for the feed with numeric parts from the unique slug.
		$base_feed['feedrules']['filename'] = $base_feed['feedrules']['filename'] . $part;
		// copy feed config data.
		$saved_feed = FeedHelper::save_feed_config_data( $base_feed['feedrules'], $new_name, false );
		if ( false === $saved_feed ) {
			return new WP_Error( 'unable_to_save_the_duplicate', esc_html__( 'Unable to save the duplicate feed data.', 'woo-feed' ) );
		}


		if ( true === $copy_file ) {
			// copy the data file.
			$original_file = Helper::get_file( $feed_from, $base_feed['feedrules']['provider'], $base_feed['feedrules']['feedType'] );
			$new_file      = Helper::get_file( $new_name, $base_feed['feedrules']['provider'], $base_feed['feedrules']['feedType'] );
			if ( copy( $original_file, $new_file ) ) {
				return true;
			} else {
				return new WP_Error( 'unable_to_copy_file', esc_html__( 'Feed Successfully Duplicated, but unable to generate the data file. Please click the "Regenerate Button"', 'woo-feed' ) );
			}
		}

		return true;
	}

}
