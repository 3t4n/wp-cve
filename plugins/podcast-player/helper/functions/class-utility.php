<?php
/**
 * Podcast player utility functions.
 *
 * @link       https://www.vedathemes.com
 * @since      3.3.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Helper
 */

namespace Podcast_Player\Helper\Functions;

use Podcast_Player\Helper\Functions\Getters as Get_Fn;
use Podcast_Player\Helper\Functions\Validation as Validation_Fn;
use Podcast_Player\Helper\Store\StoreManager;

/**
 * Podcast player utility functions.
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Helper
 * @author     vedathemes <contact@vedathemes.com>
 */
class Utility {

	/**
	 * Constructor method.
	 *
	 * @since  3.3.0
	 */
	public function __construct() {}

	/**
	 * Convert hex color code to equivalent RGB code.
	 *
	 * @since 3.3.0
	 *
	 * @param string  $hex_color Hexadecimal color value.
	 * @param boolean $as_string Return as string or associative array.
	 * @param string  $sep       String to separate RGB values.
	 * @return string
	 */
	public static function hex_to_rgb( $hex_color, $as_string, $sep = ',' ) {
		$hex_color = preg_replace( '/[^0-9A-Fa-f]/', '', $hex_color );
		$rgb_array = array();
		if ( 6 === strlen( $hex_color ) ) {
			$color_val          = hexdec( $hex_color );
			$rgb_array['red']   = 0xFF & ( $color_val >> 0x10 );
			$rgb_array['green'] = 0xFF & ( $color_val >> 0x8 );
			$rgb_array['blue']  = 0xFF & $color_val;
		} elseif ( 3 === strlen( $hex_color ) ) {
			$rgb_array['red']   = hexdec( str_repeat( substr( $hex_color, 0, 1 ), 2 ) );
			$rgb_array['green'] = hexdec( str_repeat( substr( $hex_color, 1, 1 ), 2 ) );
			$rgb_array['blue']  = hexdec( str_repeat( substr( $hex_color, 2, 1 ), 2 ) );
		} else {
			return false; // Invalid hex color code.
		}
		return $as_string ? implode( $sep, $rgb_array ) : $rgb_array;
	}

	/**
	 * Calculate color contrast.
	 *
	 * The returned value should be bigger than 5 for best readability.
	 *
	 * @link https://www.splitbrain.org/blog/2008-09/18-calculating_color_contrast_with_php
	 *
	 * @since 1.5
	 *
	 * @param int $r1 First color R value.
	 * @param int $g1 First color G value.
	 * @param int $b1 First color B value.
	 * @param int $r2 First color R value.
	 * @param int $g2 First color G value.
	 * @param int $b2 First color B value.
	 * @return float
	 */
	public static function lumdiff( $r1, $g1, $b1, $r2, $g2, $b2 ) {
		$l1 = 0.2126 * pow( $r1 / 255, 2.2 ) + 0.7152 * pow( $g1 / 255, 2.2 ) + 0.0722 * pow( $b1 / 255, 2.2 );
		$l2 = 0.2126 * pow( $r2 / 255, 2.2 ) + 0.7152 * pow( $g2 / 255, 2.2 ) + 0.0722 * pow( $b2 / 255, 2.2 );

		if ( $l1 > $l2 ) {
			return ( $l1 + 0.05 ) / ( $l2 + 0.05 );
		} else {
			return ( $l2 + 0.05 ) / ( $l1 + 0.05 );
		}
	}

	/**
	 * Get multiple columns from an array.
	 *
	 * @since 3.3.0
	 *
	 * @param array $keys     Array keys to be fetched.
	 * @param array $get_from Array from which data needs to be fetched.
	 */
	public static function multi_array_columns( $keys, $get_from ) {
		$keys = array_flip( $keys );
		array_walk(
			$keys,
			function( &$val, $key ) use ( $get_from ) {
				if ( isset( $get_from[ $key ] ) ) {
					$val = $get_from[ $key ];
				} else {
					$val = array();
				}
			}
		);
		return $keys;
	}

	/**
	 * Update feeds and their data in the feed index.
	 *
	 * @since 3.4.0
	 */
	public static function refresh_index() {
		$all_feeds = get_option( 'pp_feed_index' );
		$new       = array();
		$updated   = false;
		if ( $all_feeds && is_array( $all_feeds ) ) {
			foreach ( $all_feeds as $key => $args ) {
				$feed = get_option( 'pp_feed_data_' . $key );
				if ( $feed ) {
					if ( is_array( $args ) && isset( $args['url'] ) && $args['url'] ) {
						$new[ $key ] = $args;
					} else {
						$title       = isset( $feed['title'] ) && $feed['title'] ? $feed['title'] : esc_html__( 'Untitled Feed', 'podcast-player' );
						$url         = isset( $feed['furl'] ) && $feed['furl'] ? $feed['furl'] : '';
						$new[ $key ] = array(
							'title' => $title,
							'url'   => $url,
						);
						$updated     = true;
					}
				}
			}
			if ( $updated || count( $new ) !== count( $all_feeds ) ) {
				update_option( 'pp_feed_index', $new, 'no' );
			}
		}
		return $new;
	}

	/**
	 * Update feeds and their data in the feed index.
	 *
	 * @since 3.4.0
	 */
	public static function refresh_index_new() {
		$all_feeds = get_option( 'pp_feed_index' );
		$new       = array();
		$updated   = false;
		if ( $all_feeds && is_array( $all_feeds ) ) {
			foreach ( $all_feeds as $key => $args ) {
				$store_manager = StoreManager::get_instance();
				$feed = $store_manager->get_podcast( $key );
				if ( $feed ) {
					if ( is_array( $args ) && isset( $args['url'] ) && $args['url'] ) {
						$new[ $key ] = $args;
					} else {
						$feed        = $feed->retrieve();
						$title       = isset( $feed['title'] ) && $feed['title'] ? $feed['title'] : esc_html__( 'Untitled Feed', 'podcast-player' );
						$url         = isset( $feed['furl'] ) && $feed['furl'] ? $feed['furl'] : '';
						$new[ $key ] = array(
							'title' => $title,
							'url'   => $url,
						);
						$updated     = true;
					}
				}
			}
			if ( $updated || count( $new ) !== count( $all_feeds ) ) {
				update_option( 'pp_feed_index', $new, 'no' );
			}
		}
		return $new;
	}

	/**
	 * Upload image to wp upload directory.
	 *
	 * @since 5.1.0
	 *
	 * @param string $url   Image URL.
	 * @param string $title Podcast episode title.
	 */
	public static function upload_image( $url = '', $title = '' ) {
		$url   = esc_url_raw( $url );
		$title = sanitize_text_field( $title );
		if ( ! $url ) {
			return false;
		}

		global $wpdb;

		$fid     = md5( $url );
		$sql     = $wpdb->prepare(
			"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'pp_featured_key' AND meta_value = %s",
			$fid
		);
		$post_id = $wpdb->get_var( $sql );
		$post_id = (int) $post_id;
		if ( $post_id ) {
			return $post_id;
		} else {
			// Require relevant WordPress core files for processing images.
			require_once ABSPATH . 'wp-admin/includes/media.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';
			$post_id = media_sideload_image( $url, 0, $title, 'id' );
			if ( ! is_wp_error( $post_id ) ) {
				add_post_meta( $post_id, 'pp_featured_key', $fid, true );
				return $post_id;
			}
		}
		return false;
	}

	/**
	 * Import podcast episodes as WordPress post.
	 *
	 * @since 5.8.0
	 *
	 * @param string $feed_key Podcast feed key.
	 * @param array  $elist    IDs of episodes to be imported.
	 * @param array  $pp_data  Podcast Specific Data.
	 */
	public static function import_episodes( $feed_key, $elist, $pp_data ) {

		// Episode data to be fetched from the feed.
		$data_fields = array( 'title', 'description', 'date', 'timestamp', 'src', 'featured', 'mediatype', 'categories', 'post_id' );

		// Get required episodes data from the feed.
		$fdata = Get_Fn::get_feed_data( $feed_key, array( 'elist' => $elist ), $data_fields );

		// Return error message if feed data is not proper.
		if ( is_wp_error( $fdata ) ) {
			return $fdata;
		}

		$store_manager = StoreManager::get_instance();
		$custom_data  = $store_manager->get_custom_data( $feed_key );
		if (! $custom_data) {
			$custom_data = self::move_custom_data( $feed_key );
		}

		if ( !$custom_data || !is_array( $custom_data ) ) {
			$custom_data = array();
		}

		// Creating WP posts / post types from feed episodes.
		$post_items = $fdata['items'];
		foreach ( $post_items as $key => $items ) {

			// Return if somehow episode is already imported.
			$pid = isset( $items['post_id'] ) ? absint( $items['post_id'] ) : false;
			if ( $pid && false !== get_post_status( $items['post_id'] ) ) {
				continue;
			}

			if ( isset( $items['timestamp'] ) && $items['timestamp'] ) {
				$date = date( 'Y-m-d H:i:s', $items['timestamp'] );
			} else {
				$date    = strtotime( (string) $items['date'] );
				$date    = date( 'Y-m-d H:i:s', $date );
			}
			$post_id = wp_insert_post(
				apply_filters(
					'pp_import_post_data',
					array(
						'post_author'  => isset( $pp_data['author'] ) ? $pp_data['author'] : 0,
						'post_content' => $items['description'],
						'post_date'    => $date,
						'post_status'  => isset( $pp_data['post_status'] ) ? $pp_data['post_status'] : 'draft',
						'post_title'   => $items['title'],
						'post_type'    => isset( $pp_data['post_type'] ) ? $pp_data['post_type'] : 'post',
					)
				)
			);

			// Return error message if the import generate errors.
			if ( is_wp_error( $post_id ) ) {
				return $post_id;
			}

			// Add post specific information.
			add_post_meta(
				$post_id,
				'pp_import_data',
				array(
					'podkey'  => sanitize_text_field( $feed_key ),
					'episode' => sanitize_text_field( $key ),
					'src'     => esc_url_raw( $items['src'] ),
					'type'    => sanitize_text_field( $items['mediatype'] ),
				)
			);

			// Conditionally import and set post featured image.
			$is_get_img = isset( $pp_data['is_get_img'] ) ? $pp_data['is_get_img'] : false;
			if ( $is_get_img && isset( $items['featured'] ) && $items['featured'] ) {
				$img_id = self::upload_image( $items['featured'], $items['title'] );
				if ( $img_id ) {
					set_post_thumbnail( $post_id, $img_id );
				}
			}

			// Assign terms to the post or post type.
			$taxonomy = isset( $pp_data['taxonomy'] ) ? $pp_data['taxonomy'] : false;
			if ( $taxonomy ) {
				if ( isset( $items['categories'] ) && ! empty( $items['categories'] ) && is_array( $items['categories'] ) ) {
					wp_set_object_terms( $post_id, $items['categories'], $taxonomy );
				}
			}

			if ( isset( $custom_data[ $key ] ) && is_array( $custom_data[ $key ] ) ) {
				$custom_data[ $key ]['post_id'] = $post_id;
			} else {
				$custom_data[ $key ] = array( 'post_id' => $post_id );
			}
		}

		// Saving post_ids against feed episodes.
		$store_manager->update_custom_data( $feed_key, $custom_data );
		return $custom_data;
	}

	/**
	 * Schedule next auto update for the podcast.
	 *
	 * @since 5.8.0
	 *
	 * @param string $feed Podcast feed URL or feed key.
	 */
	public static function schedule_next_auto_update( $feed ) {
		// If valid feed URL is provided, let's convert it to feed key.
		if ( Validation_Fn::is_valid_url( $feed ) ) {
			$feed = md5( $feed );
		}

		// Remove all scheduled updates for the feed.
		wp_clear_scheduled_hook( 'pp_auto_update_podcast', array( $feed ) );

		// Auto update time interval. Have at least 10 minutes time interval.
		$cache_time = absint( Get_Fn::get_plugin_option( 'refresh_interval' ) );
		$cache_time = max( $cache_time, 10 ) * 60;
		$time       = apply_filters( 'podcast_player_auto_update_time_interval', $cache_time, $feed );

		// Short circuit filter.
		$is_update = apply_filters( 'podcast_player_auto_update', $feed );
		if ( $is_update ) {
			wp_schedule_single_event( time() + $time, 'pp_auto_update_podcast', array( $feed ) );
		}
	}

	/**
	 * Move podcast custom data from options table to the post table.
	 *
	 * @since 6.6.0
	 *
	 * @param string $feed Podcast feed URL or feed key.
	 */
	public static function move_custom_data( $feed ) {
		$ckey        = 'pp_feed_data_custom_' . $feed;
		$custom_data = get_option( $ckey );
		if ( ! $custom_data || ! is_array( $custom_data ) ) {
			return false;
		}

		// TODO: Only for compatibility. Remove in next update.
		if ( defined( 'PP_PRO_VERSION' ) && version_compare( PP_PRO_VERSION, '4.8.2', '<' ) ) {
			return $custom_data;
		}
		$store_manager = StoreManager::get_instance();
		$is_updated = $store_manager->update_custom_data( $feed, $custom_data );
		if ($is_updated) {
			delete_option( $ckey );
			delete_option( 'pp_feed_data_' . $feed );
		}
		return $custom_data;
	}
}
