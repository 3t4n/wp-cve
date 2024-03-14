<?php
/**
 * Tracking functions for reporting plugin usage to the Smash Balloon site for users that have opted in
 *
 * @copyright   Copyright (c) 2018, Chris Christoff
 * @since
 */

namespace SmashBalloon\YouTubeFeed\Admin;

use Smashballoon\Customizer\DB;/**
 * Usage tracking
 *
 * @access public
 * @return void
 *@since  5.6
 */
class SBY_Tracking {

	protected $DB;

	public function __construct( DB $DB) {
		add_action( 'init', array( $this, 'schedule_send' ) );
		add_filter( 'cron_schedules', array( $this, 'add_schedules' ) );
		add_action( 'sby_usage_tracking_cron', array( $this, 'send_checkin' ) );

		$this->DB = $DB;
	}

	private function normalize_and_format( $key, $value ) {
		$normal_bools = array(
			'widthresp',
			'disablemobile',
			'showheader',
			'showdescription',
			'headerchannel',
			'customsearch',
			'showbutton',
			'headeroutside',
			'showsubscribe',
			'backup_cache_enabled',
			'disable_resize',
			'favor_local',
			'disable_js_image_loading',
			'ajax_post_load',
			'ajaxtheme',
			'enqueue_css_in_shortcode',
			'customtemplates',
			'eagerload',

			// pro only
			'usecustomsearch',
			'showlikes',
			'carouselarrows',
			'carouselpag',
			'carouselautoplay',
			'userelative',
			'showsubscribers',
		);
		$custom_text_settings = array(
			'class',
			'buttontext',
			'subscribetext',
			'customheadertext',

			// pro only
			'customdate',
			'subscriberstext',
			'viewstext',
			'agotext',
			'beforedatetext',
			'beforestreamtimetext',
			'minutetext',
			'minutestext',
			'hourstext',
			'thousandstext',
			'millionstext',
			'watchnowtext',
			'linktext',
			'linkurl',
			'custom_css',
			'custom_js'
		);
		$comma_separate_counts_settings = array(
			'channel',
			'playlist',
			'favorites',
			'search',
			'live',
			'single',
			'includewords',
			'excludewords',
			'includewords',
			'hidevideos',
		);
		$defaults = sby_settings_defaults();

		if ( is_array( $value ) ) {
			if ( empty( $value ) ) {
				return 0;
			}
			return count( $value );
			// 0 for anything that might be false, 1 for everything else
		} elseif ( in_array( $key, $normal_bools, true ) ) {
			if ( in_array( $value, array( false, 0, '0', 'false', '' ), true ) ) {
				return 0;
			}
			return 1;

			// if a custom text setting, we just want to know if it's different than the default
		} elseif ( in_array( $key, $custom_text_settings, true ) ) {
			if ( $defaults[ $key ] === $value ) {
				return 0;
			}
			return 1;
		} elseif ( in_array( $key, $comma_separate_counts_settings, true ) ) {
			if ( str_replace( ' ', '', $value ) === '' ) {
				return 0;
			}
			$split_at_comma = explode( ',', $value );
			return count( $split_at_comma );
		}

		return $value;

	}

	private function get_data() {
		$data = array();

		// Retrieve current theme info
		$theme_data    = wp_get_theme();

		$count_b = 1;
		if ( is_multisite() ) {
			if ( function_exists( 'get_blog_count' ) ) {
				$count_b = get_blog_count();
			} else {
				$count_b = 'Not Set';
			}
		}

		$php_version = rtrim( ltrim( sanitize_text_field( phpversion() ) ) );
		$php_version = ! empty( $php_version ) ? substr( $php_version, 0, strpos( $php_version, '.', strpos( $php_version, '.' ) + 1 ) ) : phpversion();

		global $wp_version;
		$data['this_plugin'] = 'yt';
		$data['php_version']   = $php_version;
		$data['mi_version']    = SBYVER;
		$data['wp_version']    = $wp_version;
		$data['server']        = isset( $_SERVER['SERVER_SOFTWARE'] ) ? $_SERVER['SERVER_SOFTWARE'] : '';
		$data['multisite']     = is_multisite();
		$data['url']           = 'f'.home_url();
		$data['themename']     = $theme_data->Name;
		$data['themeversion']  = $theme_data->Version;
		$data['settings']      = array();
		$data['pro']           = sby_is_pro_version() ? '1' : '';
		$data['sites']         = $count_b;
		$data['usagetracking'] = get_option( 'sby_usage_tracking_config', false );
		$num_users = function_exists( 'count_users' ) ? count_users() : 'Not Set';
		$data['usercount']     = is_array( $num_users ) ? $num_users['total_users'] : 1;
		$data['timezoneoffset']= date('P');

		$settings_to_send = array();
		$raw_settings = get_option( 'sby_settings', array() );

		foreach ( $raw_settings as $key => $value ) {
			$combine_arrays = array(
				'include',
				'hoverinclude',
			);

			if ( $key === 'api_key' ) {
				// do not sent
			} elseif ( $key === 'connected_accounts' ) {
				if ( is_array( $raw_settings['connected_accounts'] ) ) {
					$settings_to_send['connected_accounts'] = count( $raw_settings['connected_accounts'] );
				} else {
					$settings_to_send['connected_accounts'] = 0;
				}
			} elseif ( in_array( $key, $combine_arrays, true ) && is_array( $value ) ) {
				foreach ( $value as $item ) {
					$settings_to_send[ $key . '_' . $item ] = 1;
				}
			} else {
				$value = $this->normalize_and_format( $key, $value );
				if ( $value !== false ) {
					$settings_to_send[ $key ] = $value;
				}
			}

		}
		$feeds         = $this->DB->feeds_query();
		$feed_settings = array();

		if ( ! empty( $feeds ) ) {
			//recursive json decode
			$feed_settings = array_map(
				static function( $item ) {
					return json_decode( $item, true );
				},
				json_decode( $feeds[0]['settings'], true )
			);
			//map array values to key => value pairs in the $feed_settings array
			array_walk(
				$feed_settings,
				static function( $value, $key ) use( &$feed_settings) {
					if ( is_array( $value ) ) {
						unset( $feed_settings[ $key ] );
						foreach ( $value as $value_key => $value_item ) {
							$feed_settings[ $key . '_' . $value_key ] = $value_item;
						}
					}
				},
				array()
			);
		}

		$settings_to_send = array_merge( $settings_to_send, $feed_settings );

		global $wpdb;
		$feed_caches = array();

		$results = $wpdb->get_results( "
		SELECT option_name
        FROM $wpdb->options
        WHERE `option_name` LIKE ('%\_transient\_sby\_%')
        AND `option_name` NOT LIKE ('%\_transient\_sby\_header%');", ARRAY_A );

		if ( isset( $results[0] ) ) {
			$feed_caches = $results;
		}
		$settings_to_send['num_found_feed_caches'] = count( $feed_caches );

		$settings_to_send['custom_header_template'] = '' !== locate_template( 'sby/header.php', false, false ) ? 1 : 0;
		$settings_to_send['custom_player_template'] = '' !== locate_template( 'sby/player.php', false, false ) ? 1 : 0;
		$settings_to_send['custom_info_template'] = '' !== locate_template( 'sby/info.php', false, false ) ? 1 : 0;
		$settings_to_send['custom_cta_template'] = '' !== locate_template( 'sby/cta.php', false, false ) ? 1 : 0;
		$settings_to_send['custom_header_generic_template'] = '' !== locate_template( 'sby/header-generic.php', false, false ) ? 1 : 0;
		$settings_to_send['custom_item_template'] = '' !== locate_template( 'sby/item.php', false, false ) ? 1 : 0;
		$settings_to_send['custom_footer_template'] = '' !== locate_template( 'sby/footer.php', false, false ) ? 1 : 0;
		$settings_to_send['custom_feed_template'] = '' !== locate_template( 'sby/feed.php', false, false ) ? 1 : 0;

		$data['settings']      = $settings_to_send;

		// Retrieve current plugin information
		if( ! function_exists( 'get_plugins' ) ) {
			include ABSPATH . '/wp-admin/includes/plugin.php';
		}

		$plugins = get_plugins();
		$active_plugins = get_option( 'active_plugins', array() );
		$plugins_to_send = array();

		foreach ( $plugins as $plugin_path => $plugin ) {
			// If the plugin isn't active, don't show it.
			if ( ! in_array( $plugin_path, $active_plugins ) )
				continue;

			$plugins_to_send[] = $plugin['Name'];
		}

		$data['active_plugins']   = $plugins_to_send;
		$data['locale']           = get_locale();
		if ( isset( $data['settings']['api_key'] ) || is_null( $data['settings']['api_key'] ) ) {
			unset( $data['settings']['api_key'] );
		}
		if ( isset( $data['settings']['access_token'] ) || is_null( $data['settings']['access_token'] ) ) {
			unset( $data['settings']['access_token'] );
		}

		return $data;
	}

	public function send_checkin( $override = false, $ignore_last_checkin = false ) {

		$home_url = trailingslashit( home_url() );

		if ( strpos( $home_url, 'smashballoon.com' ) !== false ) {
			return false;
		}

		if( ! $this->tracking_allowed() && ! $override ) {
			return false;
		}

		// Send a maximum of once per week
		$usage_tracking = get_option( 'sby_usage_tracking', array( 'last_send' => 0, 'enabled' => sby_is_pro_version() ) );
		if ( is_numeric( $usage_tracking['last_send'] ) && $usage_tracking['last_send'] > strtotime( '-1 week' ) && ! $ignore_last_checkin ) {
			return false;
		}

		$request = wp_remote_post( 'https://usage.smashballoon.com/v1/checkin/', array(
			'method'      => 'POST',
			'timeout'     => 5,
			'redirection' => 5,
			'httpversion' => '1.1',
			'blocking'    => false,
			'body'        => $this->get_data(),
			'user-agent'  => 'MI/' . SBYVER . '; ' . get_bloginfo( 'url' )
		) );

		// If we have completed successfully, recheck in 1 week
		$usage_tracking['last_send'] = time();
		update_option( 'sby_usage_tracking', $usage_tracking, false );
		return true;
	}

	private function tracking_allowed() {
		$usage_tracking = get_option( 'sby_usage_tracking', array( 'last_send' => 0, 'enabled' => sby_is_pro_version() ) );
		$tracking_allowed = isset( $usage_tracking['enabled'] ) ? $usage_tracking['enabled'] : sby_is_pro_version();

		return $tracking_allowed;
	}

	public function schedule_send() {
		if ( ! wp_next_scheduled( 'sby_usage_tracking_cron' ) ) {
			$tracking             = array();
			$tracking['day']      = rand( 0, 6  );
			$tracking['hour']     = rand( 0, 23 );
			$tracking['minute']   = rand( 0, 59 );
			$tracking['second']   = rand( 0, 59 );
			$tracking['offset']   = ( $tracking['day']    * DAY_IN_SECONDS    ) +
			                        ( $tracking['hour']   * HOUR_IN_SECONDS   ) +
			                        ( $tracking['minute'] * MINUTE_IN_SECONDS ) +
			                        $tracking['second'];
			$last_sunday = strtotime("next sunday") - (7 * DAY_IN_SECONDS);
			if ( ($last_sunday + $tracking['offset']) > time() + 6 * HOUR_IN_SECONDS ) {
				$tracking['initsend'] = $last_sunday + $tracking['offset'];
			} else {
				$tracking['initsend'] = strtotime("next sunday") + $tracking['offset'];
			}

			wp_schedule_event( $tracking['initsend'], 'weekly', 'sby_usage_tracking_cron' );
			update_option( 'sby_usage_tracking_config', $tracking );
		}
	}

	public function add_schedules( $schedules = array() ) {
		// Adds once weekly to the existing schedules.
		$schedules['weekly'] = array(
			'interval' => 604800,
			'display'  => __( 'Once Weekly', 'youtube-feed' )
		);
		return $schedules;
	}
}