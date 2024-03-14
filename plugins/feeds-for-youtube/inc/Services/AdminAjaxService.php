<?php

namespace SmashBalloon\YouTubeFeed\Services;

use Smashballoon\Stubs\Services\ServiceProvider;
use SmashBalloon\YouTubeFeed\Pro\SBY_CPT;
use SmashBalloon\YouTubeFeed\SBY_Settings;
use SmashBalloon\YouTubeFeed\Pro\SBY_Settings_Pro;
use SmashBalloon\YouTubeFeed\Pro\SBY_Display_Elements_Pro;
use SmashBalloon\YouTubeFeed\SBY_Feed;
use SmashBalloon\YouTubeFeed\Pro\SBY_Feed_Pro;
use SmashBalloon\YouTubeFeed\Pro\SBY_Parse_Pro;
use SmashBalloon\YouTubeFeed\Pro\SBY_YT_Details_Query;
use SmashBalloon\YouTubeFeed\Feed_Locator;
use SmashBalloon\YouTubeFeed\SBY_Parse;
use SmashBalloon\YouTubeFeed\SBY_WP_Post;

class AdminAjaxService extends ServiceProvider {

	public function register() {
		add_action( 'wp_ajax_sby_load_more_clicked', [$this, 'sby_get_next_post_set'] );
		add_action( 'wp_ajax_nopriv_sby_load_more_clicked', [$this, 'sby_get_next_post_set'] );
		add_action( 'wp_ajax_sby_live_retrieve', [$this, 'sby_get_live_retrieve'] );
		add_action( 'wp_ajax_nopriv_sby_live_retrieve', [$this, 'sby_get_live_retrieve'] );
		add_action( 'wp_ajax_sby_check_wp_submit', [$this, 'sby_process_wp_posts'] );
		add_action( 'wp_ajax_nopriv_sby_check_wp_submit', [$this, 'sby_process_wp_posts'] );
		add_action( 'wp_ajax_sby_do_locator', [$this, 'sby_do_locator'] );
		add_action( 'wp_ajax_nopriv_sby_do_locator', [$this, 'sby_do_locator'] );
		add_action( 'wp_ajax_sby_add_api_key', [$this, 'sby_api_key'] );
		add_action( 'wp_ajax_sby_other_plugins_modal', [$this, 'sby_other_plugins_modal'] );
		add_action( 'wp_ajax_sby_install_other_plugins', [$this, 'sby_install_addon'] );
		add_action( 'wp_ajax_sby_activate_other_plugins', [$this, 'sby_activate_addon'] );
		add_action( 'wp_ajax_sby_manual_access_token', [$this, 'manual_access_token'] );
	}

	/**
	 * Called after the load more button is clicked using admin-ajax.php
	 */
	public function sby_get_next_post_set() {
		if ( ! isset( $_POST['feed_id'] ) || strpos( $_POST['feed_id'], 'sby' ) === false ) {
			die( 'invalid feed ID');
		}

		$feed_id = sanitize_text_field( $_POST['feed_id'] );
		$atts_raw = isset( $_POST['atts'] ) ? json_decode( stripslashes( $_POST['atts'] ), true ) : array();
		if ( is_array( $atts_raw ) ) {
			array_map( 'sanitize_text_field', $atts_raw );
		} else {
			$atts_raw = array();
		}
		$atts = $atts_raw; // now sanitized

		$offset = isset( $_POST['offset'] ) ? (int)$_POST['offset'] : 0;

		$database_settings = sby_get_database_settings();
		$youtube_feed_settings = sby_is_pro() ? new SBY_Settings_Pro( $atts, $database_settings ) : new SBY_Settings( $atts, $database_settings );

		if ( empty( $database_settings['connected_accounts'] ) && empty( $database_settings['api_key'] ) ) {
			die( 'error no connected account' );
		}

		$youtube_feed_settings->set_feed_type_and_terms();
		$youtube_feed_settings->set_transient_name();
		$transient_name = $youtube_feed_settings->get_transient_name();
		$location = isset( $_POST['location'] ) && in_array( $_POST['location'], array( 'header', 'footer', 'sidebar', 'content' ), true ) ? sanitize_text_field( $_POST['location'] ) : 'unknown';
		$post_id = isset( $_POST['post_id'] ) && $_POST['post_id'] !== 'unknown' ? (int)$_POST['post_id'] : 'unknown';
		$feed_details = array(
			'feed_id' => $feed_id,
			'atts' => $atts,
			'location' => array(
				'post_id' => $post_id,
				'html' => $location
			)
		);

		$this->sby_do_background_tasks( $feed_details );

		$settings = $youtube_feed_settings->get_settings();

		$feed_type_and_terms = $youtube_feed_settings->get_feed_type_and_terms();

		$transient_name = $feed_id;

		$youtube_feed = sby_is_pro() ?  new SBY_Feed_Pro( $transient_name ) : new SBY_Feed( $transient_name );

		if ( $settings['caching_type'] === 'permanent' && empty( $settings['doingModerationMode'] ) ) {
			$youtube_feed->add_report( 'trying to use permanent cache' );
			$youtube_feed->maybe_set_post_data_from_backup();
		} elseif ( $settings['caching_type'] === 'background' ) {
			$youtube_feed->add_report( 'background caching used' );
			if ( $youtube_feed->regular_cache_exists() ) {
				$youtube_feed->add_report( 'setting posts from cache' );
				$youtube_feed->set_post_data_from_cache();
			}

			if ( $youtube_feed->need_posts( $settings['num'], $offset ) && $youtube_feed->can_get_more_posts() ) {
				while ( $youtube_feed->need_posts( $settings['num'], $offset ) && $youtube_feed->can_get_more_posts() ) {
					$youtube_feed->add_remote_posts( $settings, $feed_type_and_terms, $youtube_feed_settings->get_connected_accounts_in_feed() );
				}

				if ( $youtube_feed->need_to_start_cron_job() ) {
					$youtube_feed->add_report( 'needed to start cron job' );
					$to_cache = array(
						'atts' => $atts,
						'last_requested' => time(),
					);

					$youtube_feed->set_cron_cache( $to_cache, $youtube_feed_settings->get_cache_time_in_seconds() );

				} else {
					$youtube_feed->add_report( 'updating last requested and adding to cache' );
					$to_cache = array(
						'last_requested' => time(),
					);

					$youtube_feed->set_cron_cache( $to_cache, $youtube_feed_settings->get_cache_time_in_seconds(), $settings['backup_cache_enabled'] );
				}
			}

		} elseif ( $youtube_feed->regular_cache_exists() ) {
			$youtube_feed->add_report( 'regular cache exists' );
			$youtube_feed->set_post_data_from_cache();

			if ( $youtube_feed->need_posts( $settings['num'], $offset ) && $youtube_feed->can_get_more_posts() ) {
				while ( $youtube_feed->need_posts( $settings['num'], $offset ) && $youtube_feed->can_get_more_posts() ) {
					$youtube_feed->add_remote_posts( $settings, $feed_type_and_terms, $youtube_feed_settings->get_connected_accounts_in_feed() );
				}

				$youtube_feed->add_report( 'adding to cache' );
				$youtube_feed->cache_feed_data( $youtube_feed_settings->get_cache_time_in_seconds(), $settings['backup_cache_enabled'] );
			}


		} else {
			$youtube_feed->add_report( 'no feed cache found' );

			while ( $youtube_feed->need_posts( $settings['num'], $offset ) && $youtube_feed->can_get_more_posts() ) {
				$youtube_feed->add_remote_posts( $settings, $feed_type_and_terms, $youtube_feed_settings->get_connected_accounts_in_feed() );
			}

			if ( $youtube_feed->should_use_backup() ) {
				$youtube_feed->add_report( 'trying to use a backup cache' );
				$youtube_feed->maybe_set_post_data_from_backup();
			} else {
				$youtube_feed->add_report( 'transient gone, adding to cache' );
				$youtube_feed->cache_feed_data( $youtube_feed_settings->get_cache_time_in_seconds(), $settings['backup_cache_enabled'] );
			}
		}

		$settings['feed_avatars'] = array();
		if ( $youtube_feed->need_avatars( $settings ) ) {
			$youtube_feed->set_up_feed_avatars( $youtube_feed_settings->get_connected_accounts_in_feed(), $feed_type_and_terms );
			$settings['feed_avatars'] = $youtube_feed->get_channel_id_avatars();
		}

		$feed_status = array( 'shouldPaginate' => $youtube_feed->should_use_pagination( $settings, $offset ) );

		$feed_status['cacheAll'] = $youtube_feed->do_page_cache_all();

		$return_html = $youtube_feed->get_the_items_html( $settings, $offset, $youtube_feed_settings->get_feed_type_and_terms(), $youtube_feed_settings->get_connected_accounts_in_feed() );

		$post_data = $youtube_feed->get_post_data();
		if ( ($youtube_feed->are_posts_with_no_details() || $youtube_feed->successful_video_api_request_made())
		     && ! empty( $post_data ) ) {
			if ( $settings['storage_process'] === 'page' ) {
				foreach ( $post_data as $post ) {
					$wp_post            = new SBY_WP_Post( $post, $transient_name );
					$sby_video_settings = SBY_CPT::get_sby_cpt_settings();
					$wp_post->update_post( $sby_video_settings['post_status'] );
				}
			} elseif ( $settings['storage_process'] === 'background' ) {
				$feed_status['checkWPPosts'] = true;
				$feed_status['cacheAll']     = true;
			}
		}

		/*if ( $settings['disable_js_image_loading'] || $settings['imageres'] !== 'auto' ) {
			global $sby_posts_manager;
			$post_data = array_slice( $youtube_feed->get_post_data(), $offset, $settings['minnum'] );

			if ( ! $sby_posts_manager->image_resizing_disabled() ) {
				$image_ids = array();
				foreach ( $post_data as $post ) {
					$image_ids[] = SBY_Parse::get_post_id( $post );
				}
				$resized_images = SBY_Feed::get_resized_images_source_set( $image_ids, 0, $feed_id );

				$youtube_feed->set_resized_images( $resized_images );
			}
		}*/

		$return = array(
			'html' => $return_html,
			'feedStatus' => $feed_status,
			'report' => $youtube_feed->get_report(),
			'resizedImages' => array()
			//'resizedImages' => SBY_Feed::get_resized_images_source_set( $youtube_feed->get_image_ids_post_set(), 0, $feed_id )
		);

		//SBY_Feed::update_last_requested( $youtube_feed->get_image_ids_post_set() );

		echo wp_json_encode( $return );

		global $sby_posts_manager;

		$sby_posts_manager->update_successful_ajax_test();

		die();
	}

	public function sby_get_live_retrieve() {
		if ( ! isset( $_POST['feed_id'] ) || strpos( $_POST['feed_id'], 'sby' ) === false ) {
			die( 'invalid feed ID');
		}

		$feed_id = sanitize_text_field( $_POST['feed_id'] );
		$atts_raw = isset( $_POST['atts'] ) ? json_decode( stripslashes( $_POST['atts'] ), true ) : array();
		$video_id = sanitize_text_field( $_POST['video_id'] );
		if ( is_array( $atts_raw ) ) {
			array_map( 'sanitize_text_field', $atts_raw );
		} else {
			$atts_raw = array();
		}
		$atts = $atts_raw; // now sanitized

		if ( isset( $atts['live'] ) ) {
			unset( $atts['live'] );
		}
		$atts['type'] = 'single';
		$atts['single'] = $video_id;
		$offset = 0;

		$database_settings = sby_get_database_settings();
		$youtube_feed_settings = sby_is_pro() ? new SBY_Settings_Pro( $atts, $database_settings ) : new SBY_Settings( $atts, $database_settings );

		if ( empty( $database_settings['connected_accounts'] ) && empty( $database_settings['api_key'] ) ) {
			die( 'error no connected account' );
		}

		$youtube_feed_settings->set_feed_type_and_terms();
		$youtube_feed_settings->set_transient_name( $feed_id );
		$transient_name = $youtube_feed_settings->get_transient_name();

		if ( $transient_name !== $feed_id ) {
			die( 'id does not match' );
		}

		$settings = $youtube_feed_settings->get_settings();

		$feed_type_and_terms = $youtube_feed_settings->get_feed_type_and_terms();

		$youtube_feed = sby_is_pro() ?  new SBY_Feed_Pro( $transient_name ) : new SBY_Feed( $transient_name );
		$youtube_feed->add_remote_posts( $settings, $feed_type_and_terms, $youtube_feed_settings->get_connected_accounts_in_feed() );
		if ( $database_settings['caching_type'] === 'background' ) {
			$to_cache = array(
				'atts' => $atts,
				'last_requested' => time(),
			);
			$youtube_feed->set_cron_cache( $to_cache, $youtube_feed_settings->get_cache_time_in_seconds() );
		} else {
			$youtube_feed->cache_feed_data( $youtube_feed_settings->get_cache_time_in_seconds(), $settings['backup_cache_enabled'] );
		}

		$feed_status = array( 'shouldPaginate' => $youtube_feed->should_use_pagination( $settings, $offset ) );

		$feed_status['cacheAll'] = $youtube_feed->do_page_cache_all();

		$return_html = $youtube_feed->get_the_items_html( $settings, $offset, $youtube_feed_settings->get_feed_type_and_terms(), $youtube_feed_settings->get_connected_accounts_in_feed() );
		$post_data = $youtube_feed->get_post_data();
		if ( ($youtube_feed->are_posts_with_no_details() || $youtube_feed->successful_video_api_request_made())
		     && ! empty( $post_data ) ) {
			if ( $settings['storage_process'] === 'page' ) {
				foreach ( $youtube_feed->get_post_data() as $post ) {
					$wp_post            = new SBY_WP_Post( $post, $transient_name );
					$sby_video_settings = SBY_CPT::get_sby_cpt_settings();
					$wp_post->update_post( $sby_video_settings['post_status'] );
				}
			} elseif ( $settings['storage_process'] === 'background' ) {
				$feed_status['checkWPPosts'] = true;
				$feed_status['cacheAll']     = true;
			}
		}

		$return = array(
			'html' => $return_html,
			'feedStatus' => $feed_status,
			'report' => $youtube_feed->get_report(),
			'resizedImages' => array()
			//'resizedImages' => SBY_Feed::get_resized_images_source_set( $youtube_feed->get_image_ids_post_set(), 0, $feed_id )
		);

		//SBY_Feed::update_last_requested( $youtube_feed->get_image_ids_post_set() );

		echo wp_json_encode( $return );

		global $sby_posts_manager;

		$sby_posts_manager->update_successful_ajax_test();

		die();
	}

	/**
	 * Posts that need resized images are processed after being sent to the server
	 * using AJAX
	 *
	 * @return string
	 */
	public function sby_process_wp_posts() {
		if ( ! isset( $_POST['feed_id'] ) || strpos( $_POST['feed_id'], 'sby' ) === false ) {
			die( 'invalid feed ID');
		}

		$feed_id = sanitize_text_field( $_POST['feed_id'] );

		$atts_raw = isset( $_POST['atts'] ) ? json_decode( stripslashes( $_POST['atts'] ), true ) : array();
		if ( is_array( $atts_raw ) ) {
			array_map( 'sanitize_text_field', $atts_raw );
		} else {
			$atts_raw = array();
		}
		$atts = $atts_raw; // now sanitized
		$location = isset( $_POST['location'] ) && in_array( $_POST['location'], array( 'header', 'footer', 'sidebar', 'content' ), true ) ? sanitize_text_field( $_POST['location'] ) : 'unknown';
		$post_id = isset( $_POST['post_id'] ) && $_POST['post_id'] !== 'unknown' ? (int)$_POST['post_id'] : 'unknown';
		$feed_details = array(
			'feed_id' => $feed_id,
			'atts' => $atts,
			'location' => array(
				'post_id' => $post_id,
				'html' => $location
			)
		);

		$this->sby_do_background_tasks( $feed_details );

		$offset = isset( $_POST['offset'] ) ? (int)$_POST['offset'] : 0;
		$vid_ids = isset( $_POST['posts'] ) && is_array( $_POST['posts'] ) ? $_POST['posts'] : array();

		if ( ! empty( $vid_ids ) ) {
			array_map( 'sanitize_text_field', $vid_ids );
		}

		$cache_all = isset( $_POST['cache_all'] ) ? $_POST['cache_all'] === 'true' : false;

		$info = $this->sby_add_or_update_wp_posts( $vid_ids, $feed_id, $atts, $offset, $cache_all );

		echo wp_json_encode( $info );

		//global $sby_posts_manager;

		//$sby_posts_manager->update_successful_ajax_test();

		die();
	}

	private function sby_add_or_update_wp_posts( $vid_ids, $feed_id, $atts, $offset, $cache_all ) {
		if ( $cache_all ) {
			$database_settings = sby_get_database_settings();
			$youtube_feed_settings = sby_is_pro() ? new SBY_Settings_Pro( $atts, $database_settings ) : new SBY_Settings( $atts, $database_settings );;
			$youtube_feed_settings->set_feed_type_and_terms();
			$youtube_feed_settings->set_transient_name( $feed_id );
			$transient_name = $youtube_feed_settings->get_transient_name();

			$feed_id = $transient_name;
		}

		$database_settings = sby_get_database_settings();
		$sby_settings = sby_is_pro() ? new SBY_Settings_Pro( $atts, $database_settings ) : new SBY_Settings( $atts, $database_settings );;

		$settings = $sby_settings->get_settings();

		$youtube_feed = sby_is_pro() ? new SBY_Feed_Pro( $feed_id ) : new SBY_Feed( $feed_id );
		if ( $youtube_feed->regular_cache_exists() || $feed_id === 'sby_single' ) {
			$youtube_feed->set_post_data_from_cache();

			if ( !$cache_all || $feed_id === 'sby_single'  ) {
				if ( empty( $vid_ids ) || $feed_id !== 'sby_single' ) {
					$posts = array_slice( $youtube_feed->get_post_data(), max( 0, $offset - $settings['num'] ), $settings['num'] );
				} else {
					$posts = $vid_ids;
				}
			} else {
				$posts = $youtube_feed->get_post_data();
			}

			return self::sby_process_post_set_caching( $posts, $feed_id );
		}

		return array();
	}

	public static function sby_process_post_set_caching( $posts, $feed_id ) {

		// if is an array of video ids already, don't need to get them
		if ( isset( $posts[0] ) && SBY_Parse::get_video_id( $posts[0] ) === '' ) {
			$vid_ids = $posts;
		} else {
			$vid_ids = array();
			foreach ( $posts as $post ) {
				$vid_ids[] = SBY_Parse::get_video_id( $post );
				$wp_post = new SBY_WP_Post( $post, $feed_id );
				if ( sby_is_pro() ) {
					$sby_video_settings = SBY_CPT::get_sby_cpt_settings();
					$wp_post->update_post( $sby_video_settings['post_status'] );
				}
			}
		}

		if ( ! sby_is_pro() ) {
			return array();
		}
		
		if ( ! empty( $vid_ids ) ) {
			$details_query = new SBY_YT_Details_Query( array( 'video_ids' => $vid_ids ) );
			$videos_details = $details_query->get_video_details_to_update();

			$updated_details = array();
			foreach ( $videos_details as $video ) {
				$vid_id = SBY_Parse::get_video_id( $video );
				$live_broadcast_type = SBY_Parse_Pro::get_live_broadcast_content( $video );
				$live_streaming_timestamp = SBY_Parse_Pro::get_live_streaming_timestamp( $video );
				$single_updated_details = array(
					"sby_view_count" => SBY_Display_Elements_Pro::escaped_formatted_count_string( SBY_Parse_Pro::get_view_count( $video ), 'views' ),
					"sby_like_count" => SBY_Display_Elements_Pro::escaped_formatted_count_string( SBY_Parse_Pro::get_like_count( $video ), 'likes' ),
					"sby_comment_count" => SBY_Display_Elements_Pro::escaped_formatted_count_string( SBY_Parse_Pro::get_comment_count( $video ), 'comments' ),
					'sby_live_broadcast' => array(
						'broadcast_type' => $live_broadcast_type,
						'live_streaming_string' => SBY_Display_Elements_Pro::escaped_live_streaming_time_string( $video ),
						'live_streaming_date' => SBY_Display_Elements_Pro::format_date( $live_streaming_timestamp, false, true ),
						'live_streaming_timestamp' => $live_streaming_timestamp
					),
					'raw' => array(
						'views' => SBY_Parse_Pro::get_view_count( $video ),
						'likes' => SBY_Parse_Pro::get_like_count( $video ),
						'comments' => SBY_Parse_Pro::get_comment_count( $video )
					)
				);

				$description = SBY_Parse_Pro::get_caption( $video );
				if ( ! empty( $description ) ) {
					$single_updated_details['sby_description'] = sby_esc_html_with_br( $description );
				}
				$post = new SBY_WP_Post( $video, '' );

				$post->update_video_details();

				$updated_details[ $vid_id ] = apply_filters( 'sby_video_details_return', $single_updated_details, $video, $post->get_wp_post_id() );
			}

			return $updated_details;
		}

		return array();
	}

	public function sby_do_locator() {
		if ( ! isset( $_POST['feed_id'] ) || strpos( $_POST['feed_id'], 'sbi' ) === false ) {
			die( 'invalid feed ID');
		}

		$feed_id = sanitize_text_field( $_POST['feed_id'] );

		$atts_raw = isset( $_POST['atts'] ) ? json_decode( stripslashes( $_POST['atts'] ), true ) : array();
		if ( is_array( $atts_raw ) ) {
			array_map( 'sanitize_text_field', $atts_raw );
		} else {
			$atts_raw = array();
		}
		$atts = $atts_raw; // now sanitized

		$location = isset( $_POST['location'] ) && in_array( $_POST['location'], array( 'header', 'footer', 'sidebar', 'content' ), true ) ? sanitize_text_field( $_POST['location'] ) : 'unknown';
		$post_id = isset( $_POST['post_id'] ) && $_POST['post_id'] !== 'unknown' ? (int)$_POST['post_id'] : 'unknown';
		$feed_details = array(
			'feed_id' => $feed_id,
			'atts' => $atts,
			'location' => array(
				'post_id' => $post_id,
				'html' => $location
			)
		);

		$this->sby_do_background_tasks( $feed_details );

		wp_die( 'locating success' );
	}

	/**
	 * AJAX Add API Key
	 *
	 * @since 2.0
	 */
	public function sby_api_key() {
		check_ajax_referer( 'sby-admin' , 'nonce');

		if ( ! sby_current_user_can( 'manage_youtube_feed_options' ) ) {
			wp_send_json_error();
		}
		// get the settings
		$database_settings = sby_get_database_settings();
		// validate the api key
		$api_key = sanitize_text_field( $_POST['api'] );
		$database_settings['api_key'] = $api_key;
		// update the settings
		update_option( 'sby_settings', $database_settings );

		wp_send_json_success();
	}

	/**
	 * AJAX Add Manual Access Token
	 *
	 * @since 2.0
	 */
	public function manual_access_token() {
		check_ajax_referer( 'sby-admin' , 'nonce');

		if ( ! sby_current_user_can( 'manage_youtube_feed_options' ) ) {
			wp_send_json_error();
		}

		if ( isset( $_POST['sby_access_token'] ) ) {
			sby_attempt_connection();
		}
	}


	/**
	 * Get other plugin modal
	 *
	 * @since 2.0
	 */
	public function sby_other_plugins_modal() {
		check_ajax_referer( 'sby-admin' , 'nonce');

		if ( ! sby_current_user_can( 'manage_youtube_feed_options' ) ) {
			wp_send_json_error();
		}

		$plugin = isset( $_POST['plugin'] ) ? sanitize_key( $_POST['plugin'] ) : '';
		$sb_other_plugins = sby_get_installed_plugin_info();
		$plugin = isset( $sb_other_plugins[ $plugin ] ) ? $sb_other_plugins[ $plugin ] : false;
		if ( ! $plugin ) {
			wp_send_json_error();
		}

		// Build the content for modals
		$output = '<div class="sby-fb-popup-inside sby-install-plugin-modal">
		<div class="sby-ip-popup-cls"><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M14 1.41L12.59 0L7 5.59L1.41 0L0 1.41L5.59 7L0 12.59L1.41 14L7 8.41L12.59 14L14 12.59L8.41 7L14 1.41Z" fill="#141B38"></path>
		</svg></div>
		<div class="sby-install-plugin-body sby-fb-fs">
		<div class="sby-install-plugin-header">
		<div class="sb-plugin-image">'. $plugin['svgIcon'] .'</div>
		<div class="sb-plugin-name">
		<h3>'. $plugin['name'] .'<span>Free</span></h3>
		<p><span class="sb-author-logo">
		<svg width="13" height="17" viewBox="0 0 13 17" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path fill-rule="evenodd" clip-rule="evenodd" d="M5.72226 4.70098C4.60111 4.19717 3.43332 3.44477 2.34321 3.09454C2.73052 4.01824 3.05742 5.00234 3.3957 5.97507C2.72098 6.48209 1.93286 6.8757 1.17991 7.30453C1.82065 7.93788 2.72809 8.3045 3.45109 8.85558C2.87196 9.57021 1.73414 10.3129 1.45689 10.9606C2.65579 10.8103 4.05285 10.5668 5.16832 10.5174C5.41343 11.7495 5.53984 13.1002 5.88845 14.2288C6.40758 12.7353 6.87695 11.192 7.49488 9.79727C8.44849 10.1917 9.61069 10.6726 10.5416 10.9052C9.88842 9.98881 9.29237 9.01536 8.71356 8.02465C9.57007 7.40396 10.4364 6.79309 11.2617 6.14122C10.0952 6.03375 8.88647 5.96834 7.66107 5.91968C7.46633 4.65567 7.5175 3.14579 7.21791 1.98667C6.76462 2.93671 6.2297 3.80508 5.72226 4.70098ZM6.27621 15.1705C6.12214 15.8299 6.62974 16.1004 6.55318 16.5C6.052 16.3273 5.67498 16.2386 5.00213 16.3338C5.02318 15.8194 5.48587 15.7466 5.3899 15.1151C-1.78016 14.3 -1.79456 1.34382 5.3345 0.546422C14.2483 -0.450627 14.528 14.9414 6.27621 15.1705Z" fill="#FE544F"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M7.21769 1.98657C7.51728 3.1457 7.46611 4.65557 7.66084 5.91955C8.88625 5.96824 10.0949 6.03362 11.2615 6.14113C10.4362 6.79299 9.56984 7.40386 8.71334 8.02454C9.29215 9.01527 9.8882 9.98869 10.5414 10.9051C9.61046 10.6725 8.44827 10.1916 7.49466 9.79716C6.87673 11.1919 6.40736 12.7352 5.88823 14.2287C5.53962 13.1001 5.41321 11.7494 5.16809 10.5173C4.05262 10.5667 2.65558 10.8102 1.45666 10.9605C1.73392 10.3128 2.87174 9.57012 3.45087 8.85547C2.72786 8.30438 1.82043 7.93778 1.17969 7.30443C1.93264 6.8756 2.72074 6.482 3.39547 5.97494C3.05719 5.00224 2.73031 4.01814 2.34299 3.09445C3.43308 3.44467 4.60089 4.19707 5.72204 4.70088C6.22947 3.80499 6.7644 2.93662 7.21769 1.98657Z" fill="white"></path>
		</svg>
		</span>
		<span class="sb-author-name">'. $plugin['author'] .'</span>
		</p></div></div>
		<div class="sby-install-plugin-content">
		<p>'. $plugin['description'] .'</p>';

		$plugin_install_data = array(
			'step' => 'install',
			'action' => 'sby_install_other_plugins',
			'plugin' => $plugin['plugin'],
			'download_plugin' => $plugin['download_plugin'],
		);
		if ( ! $plugin['installed'] ) {
			$output .= sprintf(
				"<button class='sby-install-plugin-btn sbc-btn-orange' id='sby_install_op_plugin' data-plugin-atts='%s'>%s</button></div></div></div>",
				wp_json_encode( $plugin_install_data ),
				__('Install', 'custom-twitter-feeds')
			);
		}
		if ( $plugin['installed'] && ! $plugin['activated'] ) {
			$plugin_install_data['step'] = 'activate';
			$plugin_install_data['action'] = 'sby_activate_other_plugins';
			$output .= sprintf(
				"<button class='sby-install-plugin-btn sb-ot-installed sbc-btn-orange' id='sby_install_op_plugin' data-plugin-atts='%s'>%s</button></div></div></div>",
				wp_json_encode( $plugin_install_data ),
				__('Activate', 'custom-twitter-feeds')
			);
		}
		if ( $plugin['installed'] && $plugin['activated'] ) {
			$output .= sprintf(
				"<button class='sby-install-plugin-btn sby-btn-orange' id='sby_install_op_plugin' disabled='disabled'>%s</button></div></div></div>",
				__('Plugin installed & activated', 'custom-twitter-feeds')
			);
		}

		wp_send_json_success($output, true);
		wp_die();
	}

	/**
	 * Install Addon or Our Other Plugins
	 *
	 * @since 2.0
	 */
	public function sby_install_addon() {
		require_once trailingslashit( SBY_PLUGIN_DIR ) . 'inc/PluginSilentUpgrader.php';
		require_once trailingslashit( SBY_PLUGIN_DIR ) . 'inc/PluginSilentUpgraderSkin.php';
		// Run a security check.
		check_ajax_referer( 'sby-admin', 'nonce' );

		// Check for permissions.
		if ( ! sby_current_user_can( 'manage_youtube_feed_options' ) ) {
			wp_send_json_error();
		}

		$error = esc_html__( 'Could not install addon. Please download from wpforms.com and install manually.', 'custom-twitter-feeds' );

		if ( empty( $_POST['plugin'] ) ) {
			wp_send_json_error( $error );
		}

		// Set the current screen to avoid undefined notices.
		set_current_screen( 'youtube-feeds-pro' );

		// Prepare variables.
		$url = esc_url_raw(
			add_query_arg(
				array(
					'page' => 'sby-feed-builder',
				),
				admin_url( 'admin.php' )
			)
		);

		$creds = request_filesystem_credentials( $url, '', false, false, null );

		// Check for file system permissions.
		if ( false === $creds ) {
			wp_send_json_error( $error );
		}

		if ( ! WP_Filesystem( $creds ) ) {
			wp_send_json_error( $error );
		}

		/*
		 * We do not need any extra credentials if we have gotten this far, so let's install the plugin.
		 */
		require_once SBY_PLUGIN_DIR . 'inc/class-install-skin.php';

		// Do not allow WordPress to search/download translations, as this will break JS output.
		remove_action( 'upgrader_process_complete', array( 'Language_Pack_Upgrader', 'async_upgrade' ), 20 );

		// Create the plugin upgrader with our custom skin.
		$installer = new \SmashBalloon\YouTubeFeed\PluginSilentUpgrader( new \SmashBalloon\YouTubeFeed\SBY_Install_Skin() );

		// Error check.
		if ( ! method_exists( $installer, 'install' ) || empty( $_POST['plugin'] ) ) {
			wp_send_json_error( $error );
		}

		$installer->install( $_POST['plugin'] ); // phpcs:ignore

		// Flush the cache and return the newly installed plugin basename.
		wp_cache_flush();

		$plugin_basename = $installer->plugin_info();

		if ( $plugin_basename ) {

			$type = 'addon';
			if ( ! empty( $_POST['type'] ) ) {
				$type = sanitize_key( $_POST['type'] );
			}

			// Activate the plugin silently.
			$activated = activate_plugin( $plugin_basename );

			if ( ! is_wp_error( $activated ) ) {
				wp_send_json_success(
					array(
						'msg'          => 'plugin' === $type ? esc_html__( 'Plugin installed & activated.', 'custom-twitter-feeds' ) : esc_html__( 'Addon installed & activated.', 'custom-twitter-feeds' ),
						'is_activated' => true,
						'basename'     => $plugin_basename,
					)
				);
			} else {
				wp_send_json_success(
					array(
						'msg'          => 'plugin' === $type ? esc_html__( 'Plugin installed.', 'custom-twitter-feeds' ) : esc_html__( 'Addon installed.', 'custom-twitter-feeds' ),
						'is_activated' => false,
						'basename'     => $plugin_basename,
					)
				);
			}
		}

		wp_send_json_error( $error );
	}

	/**
	 * Activate our other plugins
	 *
	 * @since 2.0
	 */
	public function sby_activate_addon() {
		require_once trailingslashit( SBY_PLUGIN_DIR ) . 'inc/PluginSilentUpgrader.php';
		require_once trailingslashit( SBY_PLUGIN_DIR ) . 'inc/PluginSilentUpgraderSkin.php';
		require_once SBY_PLUGIN_DIR . 'inc/class-install-skin.php';
		// Run a security check.
		check_ajax_referer( 'sby-admin', 'nonce' );

		// Check for permissions.
		if ( ! sby_current_user_can( 'manage_youtube_feed_options' ) ) {
			wp_send_json_error();
		}

		if ( isset( $_POST['plugin'] ) ) {
			$type = 'addon';
			if ( ! empty( $_POST['type'] ) ) {
				$type = sanitize_key( $_POST['type'] );
			}
			$activate = activate_plugins( $_POST['plugin'] );
			if ( ! is_wp_error( $activate ) ) {
				if ( 'plugin' === $type ) {
					wp_send_json_success( esc_html__( 'Plugin activated.', 'custom-twitter-feeds' ) );
				} else {
					wp_send_json_success( esc_html__( 'Addon activated.', 'custom-twitter-feeds' ) );
				}
			}
		}

		wp_send_json_error( esc_html__( 'Could not activate addon. Please activate from the Plugins page.', 'custom-twitter-feeds' ) );
	}

	private function sby_do_background_tasks( $feed_details ) {
		$locator = new Feed_Locator( $feed_details );
		$locator->add_or_update_entry();
		if ( $locator::should_clear_old_locations() ) {
			$locator::delete_old_locations();
		}
	}
}
