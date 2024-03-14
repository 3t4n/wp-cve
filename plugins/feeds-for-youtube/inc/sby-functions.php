<?php

use SmashBalloon\YouTubeFeed\Pro\SBY_Parse_Pro;
use SmashBalloon\YouTubeFeed\Pro\SBY_Settings_Pro;
use SmashBalloon\YouTubeFeed\SBY_Parse;
use SmashBalloon\YouTubeFeed\SBY_Display_Elements;
use SmashBalloon\YouTubeFeed\Helpers\Util;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function sby_is_pro() {
	return Util::isPro();
}

/**
 * Check if license is in inactive state
 * 
 * @since 2.0.2
 */
function sby_license_inactive_state() {
	return empty( Util::get_license_key() );
}

/**
 * Check if license is expired and need to limit the Pro function and should display notices
 * 
 * @since 2.0.2
 */
function sby_license_notices_active() {
	if ( empty( Util::get_license_key() ) ) {
		return true;
	}
	return Util::expiredLicenseWithGracePeriodEnded();
}

function sby_json_encode( $thing ) {
	if ( function_exists( 'wp_json_encode' ) ) {
		return wp_json_encode( $thing );
	} else {
		return json_encode( $thing );
	}
}

function sby_clear_cache() {
	//Delete all transients
	global $wpdb;
	$table_name = $wpdb->prefix . "options";
	$wpdb->query( "
        DELETE
        FROM $table_name
        WHERE `option_name` LIKE ('%\_transient\_sby\_%')
        " );
	$wpdb->query( "
        DELETE
        FROM $table_name
        WHERE `option_name` LIKE ('%\_transient\_timeout\_sby\_%')
        " );
	$wpdb->query( "
        DELETE
        FROM $table_name
        WHERE `option_name` LIKE ('%\_transient\_&sby\_%')
        " );
	$wpdb->query( "
        DELETE
        FROM $table_name
        WHERE `option_name` LIKE ('%\_transient\_timeout\_&sby\_%')
        " );
	$wpdb->query( "
        DELETE
        FROM $table_name
        WHERE `option_name` LIKE ('%\_transient\_\$sby\_%')
        " );
	$wpdb->query( "
        DELETE
        FROM $table_name
        WHERE `option_name` LIKE ('%\_transient\_timeout\_\$sby\_%')
        " );

	sby_clear_page_caches();
}
add_action( 'sby_settings_after_configure_save', 'sby_clear_cache' );

/**
 * When certain events occur, page caches need to
 * clear or errors occur or changes will not be seen
 */
function sby_clear_page_caches() {
	if ( isset( $GLOBALS['wp_fastest_cache'] ) && method_exists( $GLOBALS['wp_fastest_cache'], 'deleteCache' ) ){
		/* Clear WP fastest cache*/
		$GLOBALS['wp_fastest_cache']->deleteCache();
	}

	if ( function_exists( 'wp_cache_clear_cache' ) ) {
		wp_cache_clear_cache();
	}

	if ( class_exists('W3_Plugin_TotalCacheAdmin') ) {
		$plugin_totalcacheadmin = & w3_instance('W3_Plugin_TotalCacheAdmin');

		$plugin_totalcacheadmin->flush_all();
	}

	if ( function_exists( 'rocket_clean_domain' ) ) {
		rocket_clean_domain();
	}

	if ( class_exists( 'autoptimizeCache' ) ) {
		/* Clear autoptimize */
		autoptimizeCache::clearall();
	}
}



function sby_update_or_connect_account( $args ) {
	global $sby_settings;
	$account_id = $args['channel_id'];
	$sby_settings['connected_accounts'][ $account_id ] = array(
		'access_token' => $args['access_token'],
		'refresh_token' => $args['refresh_token'],
		'channel_id' => $args['channel_id'],
		'username' => $args['username'],
		'is_valid' => true,
		'last_checked' => time(),
		'profile_picture' => $args['profile_picture'],
		'privacy' => $args['privacy'],
		'expires' => $args['expires']
	);

	update_option( 'sby_settings', $sby_settings );

	return $sby_settings['connected_accounts'][ $account_id ];
}

function sby_get_first_connected_account() {
	global $sby_settings;
	$an_account = array();

	if ( ! empty( $sby_settings['api_key'] ) ) {
		$an_account = array(
			'access_token' => '',
			'refresh_token' => '',
			'channel_id' => '',
			'username' => '',
			'is_valid' => true,
			'last_checked' => '',
			'profile_picture' => '',
			'privacy' => '',
			'expires' => '2574196927',
			'api_key' => $sby_settings['api_key']
		);
	} else {
		$connected_accounts = $sby_settings['connected_accounts'];
		foreach ( $connected_accounts as $account ) {
			if ( empty( $an_account ) ) {
				$an_account = $account;
			}
		}
	}

	if ( empty( $an_account ) ) {
		$an_account = array( 'rss_only' => true );
	}

	return $an_account;
}

function sby_get_feed_template_part( $part, $settings = array() ) {
	$file = '';

	$using_custom_templates_in_theme = apply_filters( 'sby_use_theme_templates', $settings['customtemplates'] );
	$generic_path = trailingslashit( SBY_PLUGIN_DIR ) . 'templates/';

	if ( $using_custom_templates_in_theme ) {
		$custom_header_template = locate_template( 'sby/header.php', false, false );
		$custom_header_generic_template = locate_template( 'sby/header-generic.php', false, false );
		$custom_player_template = locate_template( 'sby/player.php', false, false );
		$custom_item_template = locate_template( 'sby/item.php', false, false );
		$custom_footer_template = locate_template( 'sby/footer.php', false, false );
		$custom_feed_template = locate_template( 'sby/feed.php', false, false );
		$custom_info_template = locate_template( 'sby/info.php', false, false );
		$custom_cta_template = locate_template( 'sby/cta.php', false, false );
		$custom_shortcode_template = locate_template( 'sby/shortcode-content.php', false, false );
		$form_template = locate_template( 'sby/form.php', false, false );
		$results_template = locate_template( 'sby/results.php', false, false );
		$result_template = locate_template( 'sby/result.php', false, false );
	} else {
		$custom_header_template = false;
		$custom_header_generic_template = false;
		$custom_player_template = false;
		$custom_item_template = false;
		$custom_footer_template = false;
		$custom_feed_template = false;
		$custom_info_template = false;
		$custom_cta_template = false;
		$custom_shortcode_template = false;
		$form_template = false;
		$results_template = false;
		$result_template = false;
	}

	if ( $part === 'header' ) {
		if ( isset( $settings['generic_header'] ) ) {
			if ( $custom_header_generic_template ) {
				$file = $custom_header_generic_template;
			} else {
				$file = $generic_path . 'header-generic.php';
			}
		} else {
			if ( $custom_header_template ) {
				$file = $custom_header_template;
			} else {
				$file = $generic_path . 'header.php';
			}
		}
	} elseif ( $part === 'header-text' ) {
		$file = $generic_path . 'header-text.php';
	} elseif ( $part === 'header-generic' ) {
		if ( $custom_header_generic_template ) {
			$file = $custom_header_generic_template;
		} else {
			$file = $generic_path . 'header-generic.php';
		}
	} elseif ( $part === 'player' ) {
		if ( $custom_player_template ) {
			$file = $custom_player_template;
		} else {
			$file = $generic_path . 'player.php';
		}
	} elseif ( $part === 'item' ) {
		if ( $custom_item_template ) {
			$file = $custom_item_template;
		} else {
			$file = $generic_path . 'item.php';
		}
	} elseif ( $part === 'footer' ) {
		if ( $custom_footer_template ) {
			$file = $custom_footer_template;
		} else {
			$file = $generic_path . 'footer.php';
		}
	} elseif ( $part === 'feed' ) {
		if ( $custom_feed_template ) {
			$file = $custom_feed_template;
		} else {
			$file = $generic_path . 'feed.php';
		}
	} elseif ( $part === 'info' ) {
		if ( $custom_info_template ) {
			$file = $custom_info_template;
		} else {
			$file = $generic_path . 'info.php';
		}
	} elseif ( $part === 'player-info' ) {
		if ( $custom_info_template ) {
			$file = $custom_info_template;
		} else {
			$file = $generic_path . 'player-info.php';
		}
	} elseif ( $part === 'cta' ) {
		if ( $custom_cta_template ) {
			$file = $custom_cta_template;
		} else {
			$file = $generic_path . 'cta.php';
		}
	} elseif ( $part === 'shortcode-content' ) {
		if ( $custom_shortcode_template ) {
			$file = $custom_shortcode_template;
		} else {
			$file = $generic_path . 'single/shortcode-content.php';
		}
	} elseif ( $part === 'form' ) {
		if ( $form_template ) {
			$file = $form_template;
		} else {
			$file = $generic_path . 'search/form.php';
		}
	} elseif ( $part === 'results' ) {
		if ( $results_template ) {
			$file = $results_template;
		} else {
			$file = $generic_path . 'search/results.php';
		}
	} elseif ( $part === 'result' ) {
		if ( $result_template ) {
			$file = $result_template;
		} else {
			$file = $generic_path . 'search/result.php';
		}
	}

	return $file;
}

/**
 * Get the settings in the database with defaults
 *
 * @return array
 */
function sby_get_database_settings() {
	global $sby_settings;

	$defaults = sby_settings_defaults();

	if ( $sby_settings === null ) {
		$sby_settings = get_option('sby_settings', []);
	}

	return array_merge( $defaults, $sby_settings );
}

function sby_get_channel_id_from_channel_name( $channel_name ) {
	$channel_ids = get_option( 'sby_channel_ids', array() );

	if ( isset( $channel_ids[ strtolower( $channel_name ) ] ) ) {
		return $channel_ids[ strtolower( $channel_name ) ];
	}

	return false;
}

function sby_set_channel_id_from_channel_name( $channel_name, $channel_id ) {
	$channel_ids = get_option( 'sby_channel_ids', array() );

	$channel_ids[ strtolower( $channel_name ) ] = $channel_id;

	update_option( 'sby_channel_ids', $channel_ids, false );
}

function sby_icon( $icon, $class = '' ) {
	$class = ! empty( $class ) ? ' ' . trim( $class ) : '';
	if ( $icon === SBY_SLUG ) {
		return '<svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="youtube" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="svg-inline--fa fa-youtube fa-w-18'.$class.'"><path fill="currentColor" d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z" class=""></path></svg>';
	} else {
		return '<i aria-hidden="true" role="img" class="fab fa-youtube"></i>';
	}
}

/**
 * Print custom palette color styles for the front end
 *
 * @since 2.0
 */
function sby_maybe_palette_styles( $feed_id, $posts, $settings ) {
	if ( sby_doing_customizer( $settings ) ) {
		return;
	}
	$feed_container = '#sb_youtube_' . esc_attr( preg_replace( "/[^A-Za-z0-9 ]/", '', $feed_id ) );

	$custom_palette_class = trim(SBY_Display_Elements::get_palette_class( $settings ));
	if ( SBY_Display_Elements::palette_type( $settings ) !== 'custom' ) {
	    return;
    }

	$feed_selector = '.' . $custom_palette_class;
	$header_selector = '.' . trim(SBY_Display_Elements::get_palette_class( $settings, '_header' ));
	$custom_colors = array(
		'bg1' => $settings['custombgcolor1'],
        'text1' => $settings['customtextcolor1'],
        'text2' => $settings['customtextcolor2'],
        'link1' => $settings['customlinkcolor1'],
        'button1' => $settings['custombuttoncolor1'],
        'button2' => $settings['custombuttoncolor2']
    );
	?>
	<style type="text/css">
	<?php if ( ! empty( $custom_colors['bg1'] ) ) : ?>
		<?php echo $feed_container ?>.sb_youtube.sby_palette_custom {
			background: <?php echo esc_html( $custom_colors['bg1'] ); ?>;
		}
    <?php endif; ?>
	<?php if ( ! empty( $custom_colors['text1'] ) ) : ?>
		<?php echo $feed_container ?>.sb_youtube.sby_palette_custom .sby_video_title{
			color: <?php echo esc_html( $custom_colors['text1'] ); ?>;
		}
    <?php endif; ?>
	<?php if ( ! empty( $custom_colors['text2'] ) ) : ?>
		<?php echo $feed_container ?>.sb_youtube.sby_palette_custom .sby_info .sby_meta{
			color: <?php echo esc_html( $custom_colors['text2'] ); ?>;
		}
    <?php endif; ?>
	<?php if ( ! empty( $custom_colors['link1'] ) ) : ?>
		<?php echo $feed_container ?>.sb_youtube.sby_palette_custom .sb_youtube_header .sby_header_text .sby_bio,
		<?php echo $feed_container ?>.sb_youtube.sby_palette_custom .sb_youtube_header .sby_header_text h3,
		<?php echo $feed_container ?>.sb_youtube.sby_palette_custom .sb_youtube_header .sby_header_text .sby_subscribers{
			color: <?php echo esc_html( $custom_colors['link1'] ); ?>;
		}
    <?php endif; ?>
	<?php if ( ! empty( $custom_colors['button1'] ) ) : ?>
		<?php echo $feed_container ?>.sb_youtube.sby_palette_custom .sby_follow_btn a {
			background: <?php echo esc_html( $custom_colors['button1'] ); ?>;
		}
    <?php endif; ?>
	<?php if ( ! empty( $custom_colors['button2'] ) ) : ?>
		<?php echo $feed_container ?>.sb_youtube.sby_palette_custom .sby_footer .sby_load_btn {
			background: <?php echo esc_html( $custom_colors['button2'] ); ?>;
		}
    <?php endif; ?>
    </style>
	<?php
}
add_action( 'sby_after_feed', 'sby_maybe_palette_styles', 1, 3 );

/**
 * Get feed styles to display on the feed front end
 *
 * @since 2.0
 */
function sby_custom_feed_styles( $feed_id, $posts, $settings ) {
	$feed_selector = '#sb_youtube_' . esc_attr( preg_replace( "/[^A-Za-z0-9 ]/", '', $feed_id ) );
	if ( sby_doing_customizer( $settings ) ) {
		return;
	}

	echo '<style type="text/css">';

	if ( isset( $settings['buttonhovercolor'] ) && !empty( $settings['buttonhovercolor'] ) ) {
		echo $feed_selector . ' .sby_load_btn:hover { background: ' . $settings['buttonhovercolor'] . ' !important}';
	}
	if ( isset( $settings['customheadertextcolor'] ) && !empty( $settings['customheadertextcolor'] ) ) {
		echo $feed_selector . ' .sby-header-type-text { color: ' . $settings['customheadertextcolor'] . ' !important}';
	}
	if ( isset( $settings['descriptiontextsize'] ) && !empty( $settings['descriptiontextsize'] ) ) {
		echo $feed_selector . ' .sby_caption_wrap .sby_caption { font-size: ' . $settings['descriptiontextsize'] . ' !important}';
	}
	if ( isset( $settings['subscribehovercolor'] ) && !empty( $settings['subscribehovercolor'] ) ) {
		echo $feed_selector . ' .sby_follow_btn a:hover { box-shadow:inset 0 0 10px 20px ' . $settings['subscribehovercolor'] . ' !important}';
	}
	if ( isset( $settings['boxedbgcolor'] ) && !empty( $settings['boxedbgcolor'] ) ) {
		echo $feed_selector . '[data-videostyle=boxed] .sby_items_wrap .sby_item .sby_inner_item { background-color: ' . $settings['boxedbgcolor'] . ' !important}';
	}
	if ( isset( $settings['videocardstyle'] ) && $settings['videocardstyle'] == 'boxed' &&
		( isset( $settings['boxborderradius'] ) && !empty( $settings['boxborderradius'] ) )
	) {
		echo $feed_selector . '[data-videostyle=boxed] .sby_items_wrap .sby_item .sby_inner_item { border-radius: ' . $settings['boxborderradius'] . 'px!important}';
		if ( sby_is_pro() ) {
			echo $feed_selector . sprintf(' .sby_video_thumbnail { border-radius: %spx %spx 0 0 !important}', $settings['boxborderradius'], $settings['boxborderradius']);
		} else {
			echo $feed_selector . sprintf(' .sby_video_thumbnail { border-radius: %spx !important}', $settings['boxborderradius']);
		}
	}
	if ( isset( $settings['videodescriptioncolor'] ) && !empty( $settings['videodescriptioncolor'] ) ) {
		echo $feed_selector . ' .sby_item_caption_wrap { color: ' . $settings['videodescriptioncolor'] . ' !important}';
	}
	if ( isset( $settings['videotitlecolor'] ) && !empty( $settings['videotitlecolor'] ) ) {
		echo $feed_selector . ' .sby_video_title { color: ' . $settings['videotitlecolor'] . ' !important}';
	}
	if ( isset( $settings['videouserecolor'] ) && !empty( $settings['videouserecolor'] ) ) {
		echo $feed_selector . ' .sby_meta span.sby_username_wrap { color: ' . $settings['videouserecolor'] . ' !important}';
	}
	if ( isset( $settings['videocountdowncolor'] ) && !empty( $settings['videocountdowncolor'] ) ) {
		echo $feed_selector . ' .sby_ls_message_wrap .sby_ls_message { background-color: ' . $settings['videocountdowncolor'] . ' !important}';
	}
	if ( isset( $settings['videoviewsecolor'] ) && !empty( $settings['videoviewsecolor'] ) ) {
		echo $feed_selector . ' .sby_meta span.sby_view_count_wrap { color: ' . $settings['videoviewsecolor'] . ' !important}';
	}
	if ( isset( $settings['videostatscolor'] ) && !empty( $settings['videostatscolor'] ) ) {
		echo $feed_selector . ' .sby_info .sby_stats { color: ' . $settings['videostatscolor'] . ' !important}';
	}
	if ( isset( $settings['subscribelinkcolorbg'] ) && !empty( $settings['subscribelinkcolorbg'] ) ) {
		echo $feed_selector . '.sbc-channel-subscribe-btn button, '. $feed_selector .' .sby-player-info .sby-channel-info-bar .sby-channel-subscribe-btn a, .sby_lb-dataContainer .sby_lb-data .sby-lb-channel-header .sby-lb-subscribe-btn { background: ' . $settings['subscribelinkcolorbg'] . ' !important}';
	}
	if ( isset( $settings['subscribebtnprimarycolor'] ) && !empty( $settings['subscribebtnprimarycolor'] ) ) {
		echo $feed_selector . ' .sby-video-header-info h5, '. $feed_selector .' .sby-channel-info-bar .sby-channel-name { color: ' . $settings['subscribebtnprimarycolor'] . ' !important}';
	}
	if ( isset( $settings['subscribebtnsecondarycolor'] ) && !empty( $settings['subscribebtnsecondarycolor'] ) ) {
		echo $feed_selector . ' .sby-channel-info-bar .sby-channel-subscriber-count, '. $feed_selector .' .sby-video-header-info .sby-video-header-meta { color: ' . $settings['subscribebtnsecondarycolor'] . ' !important}';
	}
	if ( isset( $settings['subscribebtntextcolor'] ) && !empty( $settings['subscribebtntextcolor'] ) ) {
		echo $feed_selector . '.sbc-channel-subscribe-btn button, '. $feed_selector .' .sby-player-info .sby-channel-info-bar .sby-channel-subscribe-btn a, .sby_lb-dataContainer .sby_lb-data .sby-lb-channel-header .sby-lb-subscribe-btn{ color: ' . $settings['subscribebtntextcolor'] . ' !important}';
	}

	echo '</style>';
}

add_action( 'sby_after_feed', 'sby_custom_feed_styles', 1, 3 );

/**
 * @param $a
 * @param $b
 *
 * @return false|int
 */
function sby_date_sort( $a, $b ) {
	$time_stamp_a = SBY_Parse::get_timestamp( $a );
	$time_stamp_b = SBY_Parse::get_timestamp( $b );

	if ( isset( $time_stamp_a ) ) {
		return $time_stamp_b - $time_stamp_a;
	} else {
		return rand ( -1, 1 );
	}
}

function sby_scheduled_start_sort( $a, $b ) {
	$time_stamp_a = SBY_Parse_Pro::get_actual_start_timestamp( $a );
	$time_stamp_b = SBY_Parse_Pro::get_actual_start_timestamp( $b );

	$flag_a = false;
	$flag_b = false;
	if ( empty( $time_stamp_a ) ) { // if hasn't started
		$time_stamp_a = SBY_Parse_Pro::get_scheduled_start_timestamp( $a );
		if ( ! empty( $time_stamp_a ) ) { // if it's still scheduled to play
			if ( $time_stamp_a > time() - 1 * DAY_IN_SECONDS ) { // if its isn't a day passed the scheduled stream time
				$time_stamp_a = $time_stamp_a + 30 * DAY_IN_SECONDS; // try to make it the first in line since it's upcoming
				$flag_a = true;
			}
		}
	} else { // has already started
		$actual_end_timestamp_a = SBY_Parse_Pro::get_actual_end_timestamp( $a ); // get the time it ended

		if ( $actual_end_timestamp_a === 0 ) { // started but hasn't ended! show it first, it's streaming now
			$time_stamp_a = $time_stamp_a + 1000 * DAY_IN_SECONDS;
		}
	}

	if ( empty( $time_stamp_b ) ) {
		$time_stamp_b = SBY_Parse_Pro::get_scheduled_start_timestamp( $b );
		if ( ! empty( $time_stamp_b ) ) {
			if ( $time_stamp_b > time() - 1 * DAY_IN_SECONDS ) {
				$time_stamp_b = $time_stamp_b + 30 * DAY_IN_SECONDS;
				$flag_b = true;
			}

		}
	} else {
		$actual_end_timestamp_b = SBY_Parse_Pro::get_actual_end_timestamp( $b );

		if ( $actual_end_timestamp_b === 0 ) {
			$time_stamp_b = $time_stamp_b + 1000 * DAY_IN_SECONDS;
		}
	}

	if ( empty( $time_stamp_a ) ) {
		$time_stamp_a = SBY_Parse_Pro::get_timestamp( $a );
	}
	if ( empty( $time_stamp_b ) ) {
		$time_stamp_b = SBY_Parse_Pro::get_timestamp( $b );
	}

	if ( $flag_a && $flag_b ) { //reverse the order if comparing two upcoming
		return $time_stamp_a - $time_stamp_b;
	}

	return $time_stamp_b - $time_stamp_a;
}

/**
 * @param $a
 * @param $b
 *
 * @return false|int
 */
function sby_rand_sort( $a, $b ) {
	return rand ( -1, 1 );
}

/**
 * Converts a hex code to RGB so opacity can be
 * applied more easily
 *
 * @param $hex
 *
 * @return string
 */
function sby_hextorgb( $hex ) {
	// allows someone to use rgb in shortcode
	if ( strpos( $hex, ',' ) !== false ) {
		return $hex;
	}

	$hex = str_replace( '#', '', $hex );

	if ( strlen( $hex ) === 3 ) {
		$r = hexdec( substr( $hex,0,1 ).substr( $hex,0,1 ) );
		$g = hexdec( substr( $hex,1,1 ).substr( $hex,1,1 ) );
		$b = hexdec( substr( $hex,2,1 ).substr( $hex,2,1 ) );
	} else {
		$r = hexdec( substr( $hex,0,2 ) );
		$g = hexdec( substr( $hex,2,2 ) );
		$b = hexdec( substr( $hex,4,2 ) );
	}
	$rgb = array( $r, $g, $b );

	return implode( ',', $rgb ); // returns the rgb values separated by commas
}

function sby_get_utc_offset() {
	return get_option( 'gmt_offset', 0 ) * HOUR_IN_SECONDS;
}

function sby_is_pro_version() {
	return sby_is_pro();
}

function sby_strip_after_hash( $string ) {
	$string_array = explode( '#', $string );
	$finished_string = $string_array[0];

	return $finished_string;
}

function sby_esc_html_with_br( $text ) {
	return str_replace( array( '&lt;br /&gt;', '&lt;br&gt;' ), '<br>', esc_html( nl2br( $text ) ) );
}

function sby_esc_attr_with_br( $text ) {
	return str_replace( array( '&lt;br /&gt;', '&lt;br&gt;' ), '&lt;br /&gt;', esc_attr( nl2br( $text ) ) );
}

function sby_maybe_shorten_text( $string, $feed_settings ) {

	$limit = isset( $feed_settings['textlength'] ) ? $feed_settings['textlength'] : 120;

	if ( strlen( $string ) <= $limit ) {
		return $string;
	}

	$string = str_replace( '<br />', "\n\r", $string );

	$parts = preg_split( '/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE );
	$parts_count = count( $parts );

	$length = 0;
	$last_part = 0;
	for ( ; $last_part < $parts_count; ++$last_part ) {
		$length += strlen( $parts[ $last_part ] );
		if ( $length > $limit ) { break; }
	}

	$last_part = $last_part !== 0 ? $last_part - 1 : 0;
	$parts[ $last_part ] = $parts[ $last_part ] . '...';

	$return_parts = array_slice( $parts, 0, $last_part + 1 );

	$return = implode( ' ', $return_parts );

	return $return;
}

function sby_doing_openssl() {
	return extension_loaded( 'openssl' );
}

function sby_doing_customizer( $settings ) {
    return ! empty( $settings['customizer'] ) && $settings['customizer'] == true;
}

/**
 * YouTube Currect User Capability Check
 *
 * @since 2.0
 */
function sby_current_user_can( $cap ) {
	if ( $cap === 'manage_youtube_feed_options' ) {
		$cap = current_user_can( 'manage_youtube_feed_options' ) ? 'manage_youtube_feed_options' : 'manage_options';
	}
	$cap = apply_filters( 'sby_settings_pages_capability', $cap );

	return current_user_can( $cap );
}

/**
 * Check should add free plugin submenu for the free version
 * 
 * @since 2.0
 */
function sby_should_add_free_plugin_submenu( $plugin ) {
	if ( sby_is_pro() && !empty( Util::get_license_key() ) ) {
		return;
	}

	if ( $plugin === 'facebook' && !is_plugin_active( 'custom-facebook-feed/custom-facebook-feed.php' ) && !is_plugin_active( 'custom-facebook-feed-pro/custom-facebook-feed.php' ) ) {
		return true;
	}

	if ( $plugin === 'instagram' && !is_plugin_active( 'instagram-feed/instagram-feed.php' ) && !is_plugin_active( 'instagram-feed-pro/instagram-feed.php' ) ) {
		return true;
	}

	if ( $plugin === 'twitter' && !is_plugin_active( 'custom-twitter-feeds/custom-twitter-feed.php' ) && !is_plugin_active( 'custom-twitter-feeds-pro/custom-twitter-feed.php' ) ) {
		return true;
	}

	return;
}


/**
 * Get other active Smash Balloon plugins info
 * 
 * @since 2.0
 */
function sby_get_active_plugins_info() {
	// get the WordPress's core list of installed plugins
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$installed_plugins = get_plugins();

	$is_facebook_installed = false;
	$facebook_plugin = 'custom-facebook-feed/custom-facebook-feed.php';
	if ( isset( $installed_plugins['custom-facebook-feed-pro/custom-facebook-feed.php'] ) ) {
		$is_facebook_installed = true;
		$facebook_plugin = 'custom-facebook-feed-pro/custom-facebook-feed.php';
	} else if ( isset( $installed_plugins['custom-facebook-feed/custom-facebook-feed.php'] ) ) {
		$is_facebook_installed = true;
	}

	$is_instagram_installed = false;
	$instagram_plugin = 'instagram-feed/instagram-feed.php';
	if ( isset( $installed_plugins['instagram-feed-pro/instagram-feed.php'] ) ) {
		$is_instagram_installed = true;
		$instagram_plugin = 'instagram-feed-pro/instagram-feed.php';
	} else if ( isset( $installed_plugins['instagram-feed/instagram-feed.php'] ) ) {
		$is_instagram_installed = true;
	}

	$is_twitter_installed = false;
	$twitter_plugin = 'custom-twitter-feeds/custom-twitter-feed.php';
	if ( isset( $installed_plugins['custom-twitter-feeds-pro/custom-twitter-feed.php'] ) ) {
		$is_twitter_installed = true;
		$twitter_plugin = 'custom-twitter-feeds-pro/custom-twitter-feed.php';
	} else if ( isset( $installed_plugins['custom-twitter-feeds/custom-twitter-feed.php'] ) ) {
		$is_twitter_installed = true;
	}

	$is_youtube_installed = false;
	$youtube_plugin       = 'feeds-for-youtube/youtube-feed.php';
	if ( isset( $installed_plugins['youtube-feed-pro/youtube-feed-pro.php'] ) ) {
		$is_youtube_installed = true;
		$youtube_plugin       = 'youtube-feed-pro/youtube-feed-pro.php';
	} elseif ( isset( $installed_plugins['feeds-for-youtube/youtube-feed.php'] ) ) {
		$is_youtube_installed = true;
	}

	$is_social_wall_installed = isset( $installed_plugins['social-wall/social-wall.php'] ) ? true : false;
	$social_wall_plugin = 'social-wall/social-wall.php';


	return array(
		'is_facebook_installed' => $is_facebook_installed,
		'is_instagram_installed' => $is_instagram_installed,
		'is_twitter_installed' => $is_twitter_installed,
		'is_youtube_installed' => $is_youtube_installed,
		'is_social_wall_installed' => $is_social_wall_installed,
		'facebook_plugin' => $facebook_plugin,
		'instagram_plugin' => $instagram_plugin,
		'twitter_plugin' => $twitter_plugin,
		'youtube_plugin' => $youtube_plugin,
		'social_wall_plugin' => $social_wall_plugin,
		'installed_plugins' => $installed_plugins
	);
}

/**
 * Get plugin info
 *
 * @since 2.0
 */
function sby_get_installed_plugin_info() {

	$sb_other_plugins = sby_get_active_plugins_info();

	return array(
		'facebook' => array(
			'displayName' => __( 'Facebook', 'custom-twitter-feeds' ),
			'name' => __( 'Facebook Feed', 'custom-twitter-feeds' ),
			'author' => __( 'By Smash Balloon', 'custom-twitter-feeds' ),
			'description' => __('To display a Facebook feed, our Facebook plugin is required. </br> It provides a clean and beautiful way to add your Facebook posts to your website. Grab your visitors attention and keep them engaged with your site longer.', 'custom-twitter-feeds'),
			'dashboard_permalink' => admin_url( 'admin.php?page=cff-feed-builder' ),
			'svgIcon' => '<svg viewBox="0 0 14 15"  width="36" height="36"><path d="M7.00016 0.860001C3.3335 0.860001 0.333496 3.85333 0.333496 7.54C0.333496 10.8733 2.7735 13.64 5.96016 14.14V9.47333H4.26683V7.54H5.96016V6.06667C5.96016 4.39333 6.9535 3.47333 8.48016 3.47333C9.20683 3.47333 9.96683 3.6 9.96683 3.6V5.24667H9.12683C8.30016 5.24667 8.04016 5.76 8.04016 6.28667V7.54H9.8935L9.5935 9.47333H8.04016V14.14C9.61112 13.8919 11.0416 13.0903 12.0734 11.88C13.1053 10.6697 13.6704 9.13043 13.6668 7.54C13.6668 3.85333 10.6668 0.860001 7.00016 0.860001Z" fill="rgb(0, 107, 250)"/></svg>',
			'installed' => isset( $sb_other_plugins['is_facebook_installed'] ) && $sb_other_plugins['is_facebook_installed'] == true,
			'activated' => is_plugin_active( $sb_other_plugins['facebook_plugin'] ),
			'plugin' => $sb_other_plugins['facebook_plugin'],
			'download_plugin' => 'https://downloads.wordpress.org/plugin/custom-facebook-feed.zip',
		),
		'instagram' => array(
			'displayName' => __( 'Instagram', 'custom-twitter-feeds' ),
			'name' => __( 'Instagram Feed', 'custom-twitter-feeds' ),
			'author' => __( 'By Smash Balloon', 'custom-twitter-feeds' ),
			'description' => __('To display an Instagram feed, our Instagram plugin is required. </br> It provides a clean and beautiful way to add your Instagram posts to your website. Grab your visitors attention and keep them engaged with your site longer.', 'custom-twitter-feeds'),
			'dashboard_permalink' => admin_url( 'admin.php?page=sb-instagram-feed' ),
			'svgIcon' => '<svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 9.91406C13.5 9.91406 9.91406 13.5703 9.91406 18C9.91406 22.5 13.5 26.0859 18 26.0859C22.4297 26.0859 26.0859 22.5 26.0859 18C26.0859 13.5703 22.4297 9.91406 18 9.91406ZM18 23.2734C15.1172 23.2734 12.7266 20.9531 12.7266 18C12.7266 15.1172 15.0469 12.7969 18 12.7969C20.8828 12.7969 23.2031 15.1172 23.2031 18C23.2031 20.9531 20.8828 23.2734 18 23.2734ZM28.2656 9.63281C28.2656 8.57812 27.4219 7.73438 26.3672 7.73438C25.3125 7.73438 24.4688 8.57812 24.4688 9.63281C24.4688 10.6875 25.3125 11.5312 26.3672 11.5312C27.4219 11.5312 28.2656 10.6875 28.2656 9.63281ZM33.6094 11.5312C33.4688 9 32.9062 6.75 31.0781 4.92188C29.25 3.09375 27 2.53125 24.4688 2.39062C21.8672 2.25 14.0625 2.25 11.4609 2.39062C8.92969 2.53125 6.75 3.09375 4.85156 4.92188C3.02344 6.75 2.46094 9 2.32031 11.5312C2.17969 14.1328 2.17969 21.9375 2.32031 24.5391C2.46094 27.0703 3.02344 29.25 4.85156 31.1484C6.75 32.9766 8.92969 33.5391 11.4609 33.6797C14.0625 33.8203 21.8672 33.8203 24.4688 33.6797C27 33.5391 29.25 32.9766 31.0781 31.1484C32.9062 29.25 33.4688 27.0703 33.6094 24.5391C33.75 21.9375 33.75 14.1328 33.6094 11.5312ZM30.2344 27.2812C29.7422 28.6875 28.6172 29.7422 27.2812 30.3047C25.1719 31.1484 20.25 30.9375 18 30.9375C15.6797 30.9375 10.7578 31.1484 8.71875 30.3047C7.3125 29.7422 6.25781 28.6875 5.69531 27.2812C4.85156 25.2422 5.0625 20.3203 5.0625 18C5.0625 15.75 4.85156 10.8281 5.69531 8.71875C6.25781 7.38281 7.3125 6.32812 8.71875 5.76562C10.7578 4.92188 15.6797 5.13281 18 5.13281C20.25 5.13281 25.1719 4.92188 27.2812 5.76562C28.6172 6.25781 29.6719 7.38281 30.2344 8.71875C31.0781 10.8281 30.8672 15.75 30.8672 18C30.8672 20.3203 31.0781 25.2422 30.2344 27.2812Z" fill="url(#paint0_linear)"/><defs><linearGradient id="paint0_linear" x1="13.4367" y1="62.5289" x2="79.7836" y2="-5.19609" gradientUnits="userSpaceOnUse"><stop stop-color="white"/><stop offset="0.147864" stop-color="#F6640E"/><stop offset="0.443974" stop-color="#BA03A7"/><stop offset="0.733337" stop-color="#6A01B9"/><stop offset="1" stop-color="#6B01B9"/></linearGradient></defs></svg>',
			'installed' => isset( $sb_other_plugins['is_instagram_installed'] ) && $sb_other_plugins['is_instagram_installed'] == true,
			'activated' => is_plugin_active( $sb_other_plugins['instagram_plugin'] ),
			'plugin' => $sb_other_plugins['instagram_plugin'],
			'download_plugin' => 'https://downloads.wordpress.org/plugin/instagram-feed.zip',
		),
		'twitter' => array(
			'displayName' => __( 'Twitter', 'instagram-feed' ),
			'name' => __( 'Twitter Feed', 'instagram-feed' ),
			'author' => __( 'By Smash Balloon', 'instagram-feed' ),
			'description' => __('Custom Twitter Feeds is a highly customizable way to display tweets from your Twitter account. Promote your latest content and update your site content automatically.', 'instagram-feed'),
			'dashboard_permalink' => admin_url( 'admin.php?page=ctf-feed-builder' ),
			'svgIcon' => '<svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M33.6905 9C32.5355 9.525 31.2905 9.87 30.0005 10.035C31.3205 9.24 32.3405 7.98 32.8205 6.465C31.5755 7.215 30.1955 7.74 28.7405 8.04C27.5555 6.75 25.8905 6 26.0005 6C20.4755 6 17.5955 8.88 17.5955 12.435C17.5955 12.945 17.6555 13.44 17.7605 13.905C12.4205 13.635 7.66555 11.07 4.50055 7.185C3.94555 8.13 3.63055 9.24 3.63055 10.41C3.63055 12.645 4.75555 14.625 6.49555 15.75C5.43055 15.75 4.44055 15.45 3.57055 15V15.045C3.57055 18.165 5.79055 20.775 8.73055 21.36C7.78664 21.6183 6.79569 21.6543 5.83555 21.465C6.24296 22.7437 7.04085 23.8626 8.11707 24.6644C9.19329 25.4662 10.4937 25.9105 11.8355 25.935C9.56099 27.7357 6.74154 28.709 3.84055 28.695C3.33055 28.695 2.82055 28.665 2.31055 28.605C5.16055 30.435 8.55055 31.5 12.1805 31.5C26.0005 31.5 30.4955 21.69 30.4955 13.185C30.4955 12.9 30.4955 12.63 30.4805 12.345C31.7405 11.445 32.8205 10.305 33.6905 9Z" fill="#1B90EF"/></svg>',
			'installed' => isset( $sb_other_plugins['is_twitter_installed'] ) && $sb_other_plugins['is_twitter_installed'] == true,
			'activated' => is_plugin_active( $sb_other_plugins['twitter_plugin'] ),
			'plugin' => $sb_other_plugins['twitter_plugin'],
			'download_plugin' => 'https://downloads.wordpress.org/plugin/custom-twitter-feeds.zip',
		),
	);
}

/**
 * Set the customizer feeds table
 *
 * @since 2.0
 */
function sb_customizer_feeds_table() {
	global $wpdb;

	return $wpdb->prefix . 'sby_feeds';
}
add_action('sb_customizer_feeds_table', 'sb_customizer_feeds_table');

function sby_get_account_and_feed_info() {
	$yt_atts = array();
	$yt_database_settings = sby_get_database_settings();

	$youtube_feed_settings = new SBY_Settings_Pro( $yt_atts, $yt_database_settings );

	$youtube_feed_settings->set_feed_type_and_terms();
	$yt_feed_type_and_terms = $youtube_feed_settings->get_feed_type_and_terms();

	$type_and_terms = array();
	$terms_array = array();
	foreach ( $yt_feed_type_and_terms as $key => $values ) {
		if ( empty( $type_and_terms['type'] ) ) {
			$type_and_terms['type'] = $key;
			$type_and_terms['term_label'] = '';
			foreach ( $values as $value ) {
				$terms_array[] = $value['term'];
			}
		}

	}

	$type_and_terms['terms'] =  $terms_array;

	$return['type_and_terms'] = $type_and_terms;
	$return['connected_accounts'] = sby_get_first_connected_account();
	if ( isset( $return['connected_accounts']['api_key'] ) ) {
		$return['available_types'] = array(
			'channels' => array(
				'label' => 'Channel',
				'shortcode' => 'channel',
				'term_shortcode' => 'channel',
				'input' => 'text',
				'instructions' => __( 'Any channel ID', 'youtube-feed' )
			),
			'playlist' => array(
				'label' => 'Playlist',
				'shortcode' => 'playlist',
				'term_shortcode' => 'playlist',
				'input' => 'text',
				'instructions' => __( 'Any playlist ID', 'youtube-feed' )
			),
			'favorites' => array(
				'label' => 'Favorites',
				'shortcode' => 'favorites',
				'term_shortcode' => 'channel',
				'input' => 'text',
				'instructions' => __( 'Any channel ID', 'youtube-feed' )
			),
			'search' => array(
				'label' => 'Search',
				'shortcode' => 'search',
				'term_shortcode' => 'search',
				'input' => 'text',
				'instructions' => __( 'A search term', 'youtube-feed' )
			),
			'livestream' => array(
				'label' => 'Live Stream',
				'shortcode' => 'livestream',
				'term_shortcode' => 'channel',
				'input' => 'text',
				'instructions' => __( 'Any channel ID', 'youtube-feed' )
			),
			'single' => array(
				'label' => 'Single',
				'shortcode' => 'single',
				'term_shortcode' => 'single',
				'input' => 'text',
				'instructions' => __( 'Video IDs (separated by comma)', 'youtube-feed' )
			),
		);
	} else {
		$return['available_types'] = array(
			'channels' => array(
				'label' => 'Channel',
				'shortcode' => 'channel',
				'term_shortcode' => 'channel',
				'input' => 'text',
				'instructions' => __( 'Any channel ID', 'youtube-feed' )
			),
			'feed' => array(
				'label' => 'Feeds',
				'shortcode' => 'feed',
				'term_shortcode' => 'feed',
				'input' => 'text',
				'instructions' => __( 'Feed ID', 'youtube-feed' )
			)
		);
	}

	$return['settings'] = array(
		'type' => 'type'
	);

	$channel_ids_names = array();

	global $sby_settings;
	$connected_accounts = $sby_settings['connected_accounts'];

	foreach ( $connected_accounts as $connected_account ) {
		if ( ! empty( $connected_account['username'] ) && ! empty( $connected_account['channel_id'] ) ) {
			$channel_ids_names[ $connected_account['channel_id'] ] = $connected_account['username'];
		}
	}

	$return['channel_ids_names'] = $channel_ids_names;
	return $return;
}


function sby_maybe_clear_cache_using_cron() {
	if ( sby_is_pro() ) {
		return;
	}
	global $sby_settings;
	$sby_doing_cron_clear = isset( $sby_settings['cronclear'] ) ? $sby_settings['cronclear'] : false;

	if ( $sby_doing_cron_clear ) {
		sby_clear_cache();
	}
}
add_action( 'sby_cron_job', 'sby_maybe_clear_cache_using_cron' );

/**
 * This will automatically upload featured image to YouTube CPT posts
 * 
 * @since 2.1
 */
if ( !function_exists( 'sby_upload_featured_image' ) ) {

	function sby_native_upload_featured_image( $post_id, $api_data ) {
		global $sby_settings;

		if ( has_post_thumbnail( $post_id ) || ! $sby_settings[ 'save_featured_images' ] ) {
			return;
		}

		$file = SBY_Parse::get_media_url( $api_data );
		$filename = SBY_Parse::get_video_id( $api_data );
		$title = SBY_Parse::get_video_title( $api_data );
		$uploaddir = wp_upload_dir();
		$uploadfile = $uploaddir['path'] . '/' . $filename . '.jpg';

		$contents = file_get_contents( $file );
		$savefile = fopen( $uploadfile, 'w' );
		$success = fwrite( $savefile, $contents );
		fclose( $savefile );

		if ( $success ) {
			// if succesfull insert the new file into the media library (create a new attachment post type).
			$wp_filetype = wp_check_filetype( $uploadfile, null );

			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_parent'    => $post_id,
				'post_title'     => 'Image for ' . preg_replace( '/\.[^.]+$/', '', $title ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			);

			$attachment_id = wp_insert_attachment( $attachment, $uploadfile, $post_id );

			if ( ! is_wp_error( $attachment_id ) ) {
				// if attachment post was successfully created, insert it as a thumbnail to the post $post_id.
				require_once( ABSPATH . "wp-admin" . '/includes/image.php' );

				$attachment_data = wp_generate_attachment_metadata( $attachment_id, $uploadfile );

				wp_update_attachment_metadata( $attachment_id,  $attachment_data );
				set_post_thumbnail( $post_id, $attachment_id );
			}
		}
	}

	add_action( 'sby_after_insert_video_post', 'sby_native_upload_featured_image', 10, 2 );
	add_action( 'sby_after_update_video_post', 'sby_native_upload_featured_image', 10, 2 );
}


/**
 * Get the UTM Campaign name according to the plugin version
 *
 * @since 2.0
 */
function sby_utm_campaign() {
	if ( sby_is_pro() ) {
		return 'youtube-pro';
	}
	return 'youtube-free';
}
