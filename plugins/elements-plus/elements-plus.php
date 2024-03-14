<?php
/**
 * Plugin Name: Elements Plus!
 * Description: Custom elements for the Elementor page builder by CSSIgniter.com
 * Plugin URI: https://cssigniter.com/plugins/elements-plus/
 * Author: The CSSIgniter Team
 * Version: 2.16.2
 * Author URI: https://cssigniter.com/
 * Text Domain: elements-plus
 * Domain Path: /languages
 *
 * Elements Plus! is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Elements Plus! is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Elements Plus!. If not, see <http://www.gnu.org/licenses/>.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

add_action( 'plugins_loaded', 'elements_plus_init' );
function elements_plus_init() {

	define( 'ELEMENTS_PLUS_VERSION', '2.16.2' );
	define( 'ELEMENTS_PLUS_URL', plugins_url( '/', __FILE__ ) );
	define( 'ELEMENTS_PLUS_PATH', plugin_dir_path( __FILE__ ) );

	if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
		add_action( 'admin_notices', 'elements_plus_dependency' );

		return;
	}

	if ( ! version_compare( ELEMENTOR_VERSION, '3.6', '>=' ) ) {
		add_action( 'admin_notices', 'elements_plus_fail_elementor_version' );

		return;
	}

	if ( ! version_compare( PHP_VERSION, '5.4', '>=' ) ) {
		add_action( 'admin_notices', 'elements_plus_fail_php_version' );

		return;
	}

	add_action( 'init', 'elements_plus_load_plugin_textdomain' );

	add_action( 'elementor/init', 'elements_plus_category' );

	require_once ELEMENTS_PLUS_PATH . 'inc/elements-plus-options.php';

	add_action( 'elementor/editor/before_enqueue_scripts', 'elements_plus_add_fonts' );

	add_action( 'elementor/init', 'elements_plus_add_elements' );

	add_action( 'wp_enqueue_scripts', 'elements_plus_scripts' );

	add_action( 'admin_enqueue_scripts', 'elements_plus_admin_styles' );

	add_action( 'elementor/preview/enqueue_styles', 'enqueue_wpforms_styles' );
}

function elements_plus_dependency() {
	$message      = esc_html__( 'Elements Plus! requires the Elementor page builder to be active. Please activate Elementor to continue.', 'elements-plus' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

function elements_plus_fail_elementor_version() {
	$message      = esc_html__( 'Elements Plus! requires Elementor version 3.6+, the plugin is currently NOT ACTIVE.', 'elements-plus' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

function elements_plus_fail_php_version() {
	$message      = esc_html__( 'Elements Plus! requires PHP version 5.4+, the plugin is currently NOT ACTIVE.', 'elements-plus' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

function elements_plus_load_plugin_textdomain() {
	load_plugin_textdomain( 'elements-plus', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

function elements_plus_category() {
	\Elementor\Plugin::instance()->elements_manager->add_category(
		'elements-plus',
		[
			'title' => __( 'Elements Plus!', 'elements-plus' ),
			'icon'  => 'font',
		]
	);
}

function is_audioigniter_active() {
	_deprecated_function( __FUNCTION__, '1.4.0', 'elements_plus_is_audioigniter_active()' );
	return elements_plus_is_audioigniter_active();
}

function elements_plus_is_audioigniter_active() {
	_deprecated_function( __FUNCTION__, '2.2.0', 'elements_plus_is_plugin_active()' );
	return elements_plus_is_plugin_active( 'AudioIgniter' );
}

function elements_plus_is_plugin_active( $class ) {
	return class_exists( $class );
}

function elements_plus_get_video_url_info( $url ) {
	$is_vimeo   = preg_match( '#(?:https?://)?(?:www\.)?vimeo\.com/([A-Za-z0-9\-_]+)#', $url, $vimeo_id );
	$is_youtube = preg_match(
		'~
		# Match non-linked youtube URL in the wild. (Rev:20111012)
		https?://         # Required scheme. Either http or https.
		(?:[0-9A-Z-]+\.)? # Optional subdomain.
		(?:               # Group host alternatives.
		  youtu\.be/      # Either youtu.be,
		| youtube\.com    # or youtube.com followed by
		  \S*             # Allow anything up to VIDEO_ID,
		  [^\w\-\s]       # but char before ID is non-ID char.
		)                 # End host alternatives.
		([\w\-]{11})      # $1: VIDEO_ID is exactly 11 chars.
		(?=[^\w\-]|$)     # Assert next char is non-ID or EOS.
		(?!               # Assert URL is not pre-linked.
		  [?=&+%\w]*      # Allow URL (query) remainder.
		  (?:             # Group pre-linked alternatives.
			[\'"][^<>]*>  # Either inside a start tag,
		  | </a>          # or inside <a> element text contents.
		  )               # End recognized pre-linked alts.
		)                 # End negative lookahead assertion.
		[?=&+%\w-]*        # Consume any URL (query) remainder.
		~ix',
		$url,
		$youtube_id
	);

	$info = array(
		'supported' => false,
		'provider'  => '',
		'video_id'  => '',
	);

	if ( $is_youtube ) {
		$info['supported'] = true;
		$info['provider']  = 'youtube';
		$info['video_id']  = $youtube_id[1];
	} elseif ( $is_vimeo ) {
		$info['supported'] = true;
		$info['provider']  = 'vimeo';
		$info['video_id']  = $vimeo_id[1];
	}

	return $info;
}

function elements_plus_sanitize_settings( $options ) {
	$defaults = array(
		'checkbox_label'               => '',
		'checkbox_dual_input'          => '',
		'checkbox_justified'           => '',
		'checkbox_cta'                 => '',
		'checkbox_maps'                => '',
		'checkbox_audioigniter'        => '',
		'checkbox_video_slider'        => '',
		'checkbox_preloader'           => '',
		'checkbox_tooltip'             => '',
		'checkbox_scheduled'           => '',
		'checkbox_icon'                => '',
		'checkbox_flipclock'           => '',
		'checkbox_image_comparison'    => '',
		'checkbox_image_hover_effects' => '',
		'api_maps'                     => '',
		'checkbox_dual_button'         => '',
		'checkbox_instagram_filters'   => '',
		'checkbox_search'              => '',
		'checkbox_countdown'           => '',
		'checkbox_inline_svg'          => '',
		'checkbox_tilt'                => '',
		'checkbox_tables'              => '',
		'checkbox_wpforms'             => '',
		'checkbox_sticky_videos'       => '',
		'checkbox_cf7'                 => '',
		'checkbox_hotspots'            => '',
		'checkbox_image_accordion'     => '',
		'checkbox_caldera_forms'       => '',
		'checkbox_content_toggle'      => '',
		'checkbox_heading'             => '',
		'checkbox_pricing_list'        => '',
	);

	$options = wp_parse_args( $options, $defaults );

	foreach ( $options as $option => $value ) {
		if ( 'api_maps' === $option ) {
			$options[ $option ] = sanitize_text_field( $value );
		} else {
			$options[ $option ] = intval( $value );
		}
	}

	return $options;
}

function elements_plus_add_fonts() {
	wp_enqueue_style( 'ep-icon', ELEMENTS_PLUS_URL . 'assets/css/ep-icon.css', array(), ELEMENTS_PLUS_VERSION );
	wp_enqueue_style( 'ep-icon-module', ELEMENTS_PLUS_URL . 'assets/css/ep-icon-module.css', array(), ELEMENTS_PLUS_VERSION );
	wp_enqueue_style( 'ep-elementor-styles', ELEMENTS_PLUS_URL . 'assets/css/ep-elementor-styles.css', array(), ELEMENTS_PLUS_VERSION );
}

function elements_plus_add_elements() {

	$options = elements_plus_sanitize_settings( get_option( 'elements_plus_settings' ) );

	if ( $options['checkbox_audioigniter'] && elements_plus_is_plugin_active( 'AudioIgniter' ) ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-audioigniter.php';
	}

	if ( $options['checkbox_dual_input'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-button-plus.php';
	}

	if ( $options['checkbox_caldera_forms'] && elements_plus_is_plugin_active( 'Caldera_Forms' ) ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-caldera-forms.php';
	}

	if ( $options['checkbox_cta'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-cta.php';
	}

	if ( $options['checkbox_cf7'] && elements_plus_is_plugin_active( 'WPCF7' ) ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-cf7.php';
	}

	if ( $options['checkbox_content_toggle'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-content-toggle.php';
	}

	if ( $options['checkbox_countdown'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-countdown.php';
	}

	if ( $options['checkbox_dual_button'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-dual-button.php';
	}

	if ( $options['checkbox_flipclock'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-flipclock.php';
	}

	if ( $options['checkbox_heading'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-heading.php';
	}

	if ( $options['checkbox_justified'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-justified-gallery.php';
	}

	if ( $options['checkbox_maps'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-styled-maps.php';
	}

	if ( $options['checkbox_hotspots'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-hotspots.php';
	}

	if ( $options['checkbox_icon'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-icon-plus.php';
	}

	if ( $options['checkbox_image_accordion'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-image-accordion.php';
	}

	if ( $options['checkbox_image_comparison'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-image-comparison.php';
	}

	if ( $options['checkbox_image_hover_effects'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-image-hover-effects.php';
	}

	if ( $options['checkbox_inline_svg'] && elements_plus_is_plugin_active( 'SafeSvg\safe_svg' ) ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-inline-svg.php';
	}

	if ( $options['checkbox_instagram_filters'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-instagram-filters.php';
	}

	if ( $options['checkbox_label'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-label.php';
	}

	if ( $options['checkbox_preloader'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-preloader.php';
	}

	if ( $options['checkbox_pricing_list'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-pricing-list.php';
	}

	if ( $options['checkbox_scheduled'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-scheduled.php';
	}

	if ( $options['checkbox_search'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-search.php';
	}

	if ( $options['checkbox_sticky_videos'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-sticky-videos.php';
	}

	if ( $options['checkbox_tables'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-tables.php';
	}

	if ( $options['checkbox_tilt'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-tilt.php';
	}

	if ( $options['checkbox_tooltip'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-tooltip.php';
	}

	if ( $options['checkbox_wpforms'] && elements_plus_is_plugin_active( 'WPForms' ) ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-wpforms.php';
	}

	if ( $options['checkbox_video_slider'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-video-slider.php';
	}

}

function elements_plus_scripts() {
	$options             = elements_plus_sanitize_settings( get_option( 'elements_plus_settings' ) );
	$label               = $options['checkbox_label'];
	$button_plus         = $options['checkbox_dual_input'];
	$justified           = $options['checkbox_justified'];
	$maps                = $options['checkbox_maps'];
	$api_key             = $options['api_maps'];
	$cta                 = $options['checkbox_cta'];
	$audioigniter        = $options['checkbox_audioigniter'];
	$video_slider        = $options['checkbox_video_slider'];
	$preloader           = $options['checkbox_preloader'];
	$tooltip             = $options['checkbox_tooltip'];
	$icon                = $options['checkbox_icon'];
	$flipclock           = $options['checkbox_flipclock'];
	$image_comparison    = $options['checkbox_image_comparison'];
	$image_hover_effects = $options['checkbox_image_hover_effects'];
	$dual_button         = $options['checkbox_dual_button'];
	$instagram_filters   = $options['checkbox_instagram_filters'];
	$search              = $options['checkbox_search'];
	$countdown           = $options['checkbox_countdown'];
	$svg                 = $options['checkbox_inline_svg'];
	$tilt                = $options['checkbox_tilt'];
	$tables              = $options['checkbox_tables'];
	$sticky_videos       = $options['checkbox_sticky_videos'];
	$hotspots            = $options['checkbox_hotspots'];
	$image_accordion     = $options['checkbox_image_accordion'];
	$content_toggle      = $options['checkbox_content_toggle'];
	$heading             = $options['checkbox_heading'];
	$pricing_list        = $options['checkbox_pricing_list'];

	if ( 1 === $icon ) {
		wp_enqueue_style( 'ep-icon-module', ELEMENTS_PLUS_URL . 'assets/css/ep-icon-module.css', array(), ELEMENTS_PLUS_VERSION );
	}

	if ( 1 === $justified ) {
		wp_enqueue_style( 'justified-gallery', ELEMENTS_PLUS_URL . 'assets/css/justifiedGallery.min.css', array(), ELEMENTS_PLUS_VERSION );
		wp_enqueue_script( 'justified-gallery', ELEMENTS_PLUS_URL . 'assets/js/jquery.justifiedGallery.min.js', array( 'jquery' ), '3.6.3', true );
	}

	if ( 1 === $video_slider ) {
		wp_enqueue_script( 'ep-fitvids', ELEMENTS_PLUS_URL . 'assets/js/jquery.fitvids.js', array( 'jquery' ), '1.1', true );
		wp_enqueue_script( 'ep-matchHeight', ELEMENTS_PLUS_URL . 'assets/js/jquery.matchHeight.js', array( 'jquery' ), '1.0', true );
		wp_enqueue_script( 'ep-slick', ELEMENTS_PLUS_URL . 'assets/js/slick.min.js', array( 'jquery' ), '1.8.0', true );
		wp_enqueue_style( 'ep-slick', ELEMENTS_PLUS_URL . 'assets/css/slick.css', array(), '1.8.0' );
	}

	if ( $api_key && 1 === $maps ) {
		wp_enqueue_script( 'ep-google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $api_key, array(), ELEMENTS_PLUS_VERSION, false );
	}

	if ( 1 === $audioigniter && elements_plus_is_plugin_active( 'AudioIgniter' ) ) {
		wp_enqueue_script( 'audioigniter' );
	}

	if ( 1 === $flipclock ) {
		wp_enqueue_style( 'flipclock', ELEMENTS_PLUS_URL . 'assets/css/flipclock.css', array(), '1.1.a' );
		wp_enqueue_script( 'flipclock', ELEMENTS_PLUS_URL . 'assets/js/flipclock.min.js', array( 'jquery' ), '1.1.a', true );
	}

	if ( 1 === $image_comparison ) {
		wp_enqueue_style( 'image-comparison', ELEMENTS_PLUS_URL . 'assets/css/twentytwenty.css', array(), '1.0' );
		wp_enqueue_script( 'jquery-imagesLoaded', ELEMENTS_PLUS_URL . 'assets/js/imagesloaded.pkgd.min.js', array( 'jquery' ), '4.1.4', true );
		wp_enqueue_script( 'jquery-event-move', ELEMENTS_PLUS_URL . 'assets/js/jquery.event.move.js', array( 'jquery' ), '2.0.1', true );
		wp_enqueue_script( 'image-comparison', ELEMENTS_PLUS_URL . 'assets/js/jquery.twentytwenty.js', array( 'jquery', 'jquery-event-move' ), '1.0', true );
	}

	if ( 1 === $image_hover_effects ) {
		wp_enqueue_script( 'three', ELEMENTS_PLUS_URL . 'assets/js/three.min.js', array(), '1.0', true );
		wp_enqueue_script( 'jstween', ELEMENTS_PLUS_URL . 'assets/js/jstween.js', array(), ELEMENTS_PLUS_VERSION, true );
		wp_enqueue_script( 'hover', ELEMENTS_PLUS_URL . 'assets/js/hover.js', array(), ELEMENTS_PLUS_VERSION, true );
	}

	if ( 1 === $label || 1 === $button_plus || 1 === $justified || 1 === $cta || 1 === $video_slider || 1 === $preloader || 1 === $tooltip || 1 === $icon || 1 === $flipclock || 1 === $image_hover_effects || 1 === $dual_button ) {
		wp_enqueue_style( 'ep-elements', ELEMENTS_PLUS_URL . 'assets/css/ep-elements.css', array(), ELEMENTS_PLUS_VERSION );
	}

	if ( 1 === $instagram_filters ) {
		wp_enqueue_style( 'ep-instagram-filters', ELEMENTS_PLUS_URL . 'assets/css/instagram-filters.css', array(), ELEMENTS_PLUS_VERSION );
	}

	if ( 1 === $search ) {
		wp_enqueue_style( 'ep-search-style', ELEMENTS_PLUS_URL . 'assets/css/ep-search.css', array(), ELEMENTS_PLUS_VERSION );
		wp_enqueue_script( 'ep-search', ELEMENTS_PLUS_URL . 'assets/js/ep-search.js', array( 'jquery' ), ELEMENTS_PLUS_VERSION, true );

		$vars = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		);
		wp_localize_script( 'ep-search', 'ep_search_vars', $vars );
	}

	if ( 1 === $countdown ) {
		wp_enqueue_script( 'ep-countdown', ELEMENTS_PLUS_URL . 'assets/js/ep-countdown.js', array( 'jquery' ), ELEMENTS_PLUS_VERSION, true );
		wp_enqueue_style( 'ep-countdown-style', ELEMENTS_PLUS_URL . 'assets/css/ep-countdown.css', array(), ELEMENTS_PLUS_VERSION );
	}

	if ( 1 === $tilt ) {
		wp_enqueue_script( 'ep-tilt', ELEMENTS_PLUS_URL . 'assets/js/vanilla-tilt.min.js', null, ELEMENTS_PLUS_VERSION, true );
	}

	if ( 1 === $tables ) {
		wp_enqueue_style( 'ep-tables', ELEMENTS_PLUS_URL . 'assets/css/ep-tables.css', array(), ELEMENTS_PLUS_VERSION );
	}

	if ( 1 === $sticky_videos ) {
		wp_enqueue_style( 'ep-sticky-videos', ELEMENTS_PLUS_URL . 'assets/css/ep-sticky-videos.css', array(), ELEMENTS_PLUS_VERSION );
		wp_enqueue_script( 'ep-sticky-videos', ELEMENTS_PLUS_URL . 'assets/js/ep-sticky-videos.js', array( 'jquery' ), ELEMENTS_PLUS_VERSION, true );
	}

	if ( 1 === $hotspots ) {
		wp_register_style( 'tipso', ELEMENTS_PLUS_URL . 'assets/css/tipso.min.css', array(), ELEMENTS_PLUS_VERSION );
		wp_register_script( 'tipso', ELEMENTS_PLUS_URL . 'assets/js/tipso.min.js', array( 'jquery' ), ELEMENTS_PLUS_VERSION, true );

		wp_register_style( 'ep-hotspots', ELEMENTS_PLUS_URL . 'assets/css/ep-hotspots.css', array(), ELEMENTS_PLUS_VERSION );
		wp_register_script( 'ep-hotspots', ELEMENTS_PLUS_URL . 'assets/js/ep-hotspots.js', array( 'jquery', 'tipso' ), ELEMENTS_PLUS_VERSION, true );
	}

	if ( 1 === $image_accordion ) {
		wp_enqueue_style( 'ep-image-accordion', ELEMENTS_PLUS_URL . 'assets/css/ep-image-accordion.css', array(), ELEMENTS_PLUS_VERSION );
	}

	if ( 1 === $content_toggle ) {
		wp_enqueue_style( 'ep-content-toggle', ELEMENTS_PLUS_URL . 'assets/css/ep-content-toggle.css', array(), ELEMENTS_PLUS_VERSION );
		wp_enqueue_script( 'ep-content-toggle', ELEMENTS_PLUS_URL . 'assets/js/ep-content-toggle.js', array( 'jquery' ), ELEMENTS_PLUS_VERSION, true );
	}

	if ( 1 === $heading ) {
		wp_enqueue_style( 'ep-heading', ELEMENTS_PLUS_URL . 'assets/css/ep-heading.css', array(), ELEMENTS_PLUS_VERSION );
	}

	if ( 1 === $pricing_list ) {
		wp_enqueue_style( 'ep-pricing-list', ELEMENTS_PLUS_URL . 'assets/css/ep-pricing-list.css', array(), ELEMENTS_PLUS_VERSION );
	}

	if ( 1 === $justified || 1 === $maps || 1 === $audioigniter || 1 === $video_slider || 1 === $preloader || 1 === $flipclock || 1 === $image_comparison || 1 === $image_hover_effects ) {
		wp_enqueue_script( 'ep-scripts', ELEMENTS_PLUS_URL . 'assets/js/ep-scripts.js', array( 'jquery' ), ELEMENTS_PLUS_VERSION, true );
	}
}

function elements_plus_admin_styles( $hook ) {
	if ( 'elementor_page_elements_plus' !== $hook ) {
		return;
	}
	wp_enqueue_style( 'custom_wp_admin_css', ELEMENTS_PLUS_URL . 'assets/css/admin-styles.css', array(), ELEMENTS_PLUS_VERSION );
}

function enqueue_wpforms_styles() {
	$options = elements_plus_sanitize_settings( get_option( 'elements_plus_settings' ) );

	if ( false === elements_plus_is_plugin_active( 'WPForms' ) || 0 === $options['checkbox_wpforms'] || false === \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
		return;
	}

	if ( wpforms_setting( 'disable-css', '1' ) == '1' ) {
		wp_enqueue_style(
			'wpforms-css',
			WPFORMS_PLUGIN_URL . 'assets/css/wpforms-full.css',
			array(),
			WPFORMS_VERSION
		);
	} elseif ( wpforms_setting( 'disable-css', '1' ) == '2' ) {
		wp_enqueue_style(
			'wpforms-css',
			WPFORMS_PLUGIN_URL . 'assets/css/wpforms-base.css',
			array(),
			WPFORMS_VERSION
		);
	}

	if ( function_exists( 'wpforms_surveys_polls' ) ) {
		wp_enqueue_style(
			'wpforms-surveys-polls',
			wpforms_surveys_polls()->url . 'assets/css/wpforms-surveys-polls.min.css',
			array(),
			WPFORMS_SURVEYS_POLLS_VERSION
		);
	}
}
