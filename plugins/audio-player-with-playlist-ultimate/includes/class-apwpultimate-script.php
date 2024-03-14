<?php
/**
 * Script Class
 * Handles the script and style functionality of plugin
 *
 * @package Audio Player with Playlist Ultimate
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Apwpultimate_Script {

	function __construct() {

		// Action to add script at front side
		add_action( 'wp_enqueue_scripts', array($this, 'apwpultimate_front_style') );

		// Action to add script at front side
		add_action( 'wp_enqueue_scripts', array($this, 'apwpultimate_front_script') );

		// Action to add style in backend
		add_action( 'admin_enqueue_scripts', array($this, 'apwpultimate_admin_style') );

		// Action to add script in backend
		add_action( 'admin_enqueue_scripts', array($this, 'apwpultimate_admin_script') );

		// Action to add custom css in head
		add_action( 'wp_head', array($this, 'apwpultimate_add_custom_css'), 20 );
		
	}

	/**
	 * Function to add style at front side
	 * 
	 * @package Audio Player with Playlist Ultimate
	 * @since 1.0.0
	 */
	function apwpultimate_front_style() {

		// Registring and enqueing button with style ultimate css
		wp_register_style( 'apwpultimate-jplayer-style', APWPULTIMATE_URL.'assets/css/jplayer.blue.monday.min.css', array(), APWPULTIMATE_VERSION );
		wp_enqueue_style( 'apwpultimate-jplayer-style' );

		// Registring and enqueing button with style ultimate css
		wp_register_style( 'apwpultimate-public-style', APWPULTIMATE_URL.'assets/css/apwpultimate-public-style.css', array(), APWPULTIMATE_VERSION );
		wp_enqueue_style( 'apwpultimate-public-style' );

		// Registring and enqueing button with style ultimate css
		wp_register_style( 'apwpultimate-font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), APWPULTIMATE_VERSION );
		wp_enqueue_style( 'apwpultimate-font-awesome' );

	}

	/**
	 * Function to add style at front side
	 * 
	 * @package Audio Player with Playlist Ultimate
	 * @since 1.0.0
	 */
	function apwpultimate_front_script() {

		wp_register_script( 'apwpultimate-public-script', APWPULTIMATE_URL.'assets/js/apwpultimate-public.js', array('jquery'), APWPULTIMATE_VERSION, true );	
		wp_register_script( 'apwpultimate-jplayer-script', APWPULTIMATE_URL.'assets/js/jquery.jplayer.js', array('jquery'), APWPULTIMATE_VERSION, true );
		wp_register_script( 'apwpultimate-jpplaylist-script', APWPULTIMATE_URL.'assets/js/jquery.playlist.js', array('jquery'), APWPULTIMATE_VERSION, true );
		wp_register_script( 'apwpultimate-slimscroll-script', APWPULTIMATE_URL.'assets/js/jquery.slimscroll.js', array('jquery'), APWPULTIMATE_VERSION, true );
	}

	/**
	 * Enqueue admin styles
	 * 
	 * @package Audio Player with Playlist Ultimate
	 * @since 1.0.0
	 */
	function apwpultimate_admin_style( $hook ) {

		global $typenow;

		// Taking pages array
		$pages_arr = array( APWPULTIMATE_POST_TYPE );

		if( in_array($typenow, $pages_arr) ) {
			wp_register_style( 'apwpultimate-admin-style', APWPULTIMATE_URL.'assets/css/apwpultimate-admin-style.css', array(), APWPULTIMATE_VERSION );
			wp_enqueue_style( 'apwpultimate-admin-style' );

			// Enqueu built in style for color picker
			if( wp_style_is( 'wp-color-picker', 'registered' ) ) { // Since WordPress 3.5
				wp_enqueue_style( 'wp-color-picker' );
			}
		}
		
	}

	/**
	 * Enqueue admin script
	 * 
	 * @package Audio Player with Playlist Ultimate
	 * @since 1.0.0
	 */
	function apwpultimate_admin_script( $hook ) {

		global $typenow, $wp_version;
		$new_ui = $wp_version >= '3.5' ? '1' : '0'; // Check wordpress version for older scripts
		// Taking pages array
		$pages_arr = array( APWPULTIMATE_POST_TYPE, APWPULTIMATE_POST_TYPE.'_page_apwppro-pro-settings' );

		if( in_array($typenow, $pages_arr) ) {

			// Enqueu built-in script for color picker
			if( wp_script_is( 'wp-color-picker', 'registered' ) ) { // Since WordPress 3.5
				wp_enqueue_script( 'wp-color-picker' );
			}

			// Registring admin script
			wp_register_script( 'apwpultimate-admin-script', APWPULTIMATE_URL.'assets/js/apwpultimate-admin-script.js', array('jquery'), APWPULTIMATE_VERSION, true );
			wp_enqueue_script( 'apwpultimate-admin-script' );
			wp_localize_script( 'apwpultimate-admin-script', 'ApwpultimateAdmin', array(
																		'new_ui'				=>	$new_ui,
																		'sry_msg'				=> __('Sorry, One entry should be there.', 'audio-player-with-playlist-ultimate'),
																		'code_editor'			=> ( version_compare( $wp_version, '4.9' ) >= 0 ) ? 1 : 0,
																		'syntax_highlighting'	=> ( 'false' === wp_get_current_user()->syntax_highlighting ) ? 0 : 1,
																		'reset_msg'				=> esc_html__( 'Click OK to reset all options. All settings will be lost!', 'audio-player-with-playlist-ultimate' ),
			));
		}

		// If page is plugin setting page then enqueue script
		if( $hook == APWPULTIMATE_POST_TYPE.'_page_apwpultimate-ultimate-settings' && ( version_compare( $wp_version, '4.9' ) >= 0 ) ) {
			// WP CSS Code Editor
			wp_enqueue_code_editor( array(
				'type' 			=> 'text/css',
				'codemirror' 	=> array(
					'indentUnit' 	=> 2,
					'tabSize'		=> 2,
					'lint'			=> false,
				),
			) );
		}
		
	}

	/**
	 * Add custom css to head
	 * 
	 * @package Audio Player with Playlist Ultimate
	 * @since 1.0.0
	 */
	function apwpultimate_add_custom_css() {

		$theme_color 			= apwpultimate_ultimate_get_option('theme_color');
		$playlist_bg_color		= apwpultimate_ultimate_get_option('playlist_bg_color');
		$playlist_font_color	= apwpultimate_ultimate_get_option('playlist_font_color');		
		$audio_title_font_color = apwpultimate_ultimate_get_option('audio_title_font_color');
		$title_bg_color			= apwpultimate_ultimate_get_option('title_bg_color');
		$audio_title_font_size	= apwpultimate_ultimate_get_option('audio_title_font_size');
		$playlist_font_size		= apwpultimate_ultimate_get_option('playlist_font_size');

		$theme_color			= ! empty( $theme_color )				? apwpultimate_ultimate_clean_color($theme_color)				: '#ff6347';
		$playlist_bg_color		= ! empty( $playlist_bg_color )			? apwpultimate_ultimate_clean_color($playlist_bg_color)			: '#f7f7f7';
		$playlist_font_color	= ! empty( $playlist_font_color )		? apwpultimate_ultimate_clean_color($playlist_font_color)		: '#000000';
		$audio_title_font_color = ! empty( $audio_title_font_color )	? apwpultimate_ultimate_clean_color($audio_title_font_color)	: '#ffffff';
		$title_bg_color			= ! empty( $title_bg_color )			? apwpultimate_ultimate_clean_color($title_bg_color)			: '#000000';
		$audio_title_font_size	= ! empty( $audio_title_font_size )		? apwpultimate_ultimate_clean_number($audio_title_font_size)	: 22;
		$playlist_font_size		= ! empty( $playlist_font_size )		? apwpultimate_ultimate_clean_number($playlist_font_size)		: 18;

		// Player Setting css
		$sett_css = '';
		$sett_css .= '.apwp-audio-player-wrp .controller-common, .apwp-audio-player-wrp .jp-play-bar, .apwp-audio-player-wrp .jp-volume-bar-value, .apwp-audio-player-wrp div.jp-type-playlist div.jp-playlist li.jp-playlist-current::before{background-color:'.$theme_color.';}';
		$sett_css .= '.apwp-audio-player-wrp div.jp-type-playlist div.jp-playlist a.jp-playlist-current, .apwp-audio-player-wrp div.jp-type-playlist div.jp-playlist a:hover, .apwp-audio-player-wrp .jp-state-looped .jp-repeat::before, .apwp-audio-player-wrp .playlist-btn.active::before, .apwp-audio-player-wrp .jp-state-shuffled .jp-shuffle::before{color:'.$theme_color.';}';
		$sett_css .= '.apwp-audio-player-wrp .playlist-block, .apwp-audio-player-wrp .playlist-block-style-two, .apwp-audio-player-wrp .jp-playlist.playlist-block{background:'.$playlist_bg_color.';}';
		$sett_css .= 'div.jp-type-playlist div.jp-playlist a{color:'.$playlist_font_color.';}';
		$sett_css .= '.apwp-audio-player-wrp .album-art-block .jp-title{color:'.$audio_title_font_color.';background-color:'.$title_bg_color.',0.33);font-size:'.$audio_title_font_size.'px;}';
		$sett_css .= '.apwp-audio-player-wrp .jp-playlist-item h4{font-size:'.$playlist_font_size.'px;}';
		$sett_css .= '.jp-video .jp-progress .jp-play-bar:before, .jp-type-single .jp-progress .jp-play-bar:before, .jp-volume-bar-value::before,.apwp-jplayer-design-overide .jp-type-single .jp-details, .apwp-jplayer-design-overide .controller-common, .apwp-jplayer-design-overide .jp-play-bar, .jp-volume-bar-value, .apwp-jplayer-design-overide .jp-state-playing .jp-play{background:'.$theme_color.';}';
		$sett_css .= '.apwp-jplayer-design-overide .jp-state-looped .jp-repeat::before, .apwp-jplayer-design-overide .jp-volume-controls button::before{color:'.$theme_color.';}';
		$sett_css .= '.jp-details .jp-title {color:'.$audio_title_font_color.';font-size:'.$audio_title_font_size.'px;}';
		
		// Plugin Setting CSS
		echo '<style type="text/css">' . "\n" .
				wp_strip_all_tags( $sett_css )
			 . "\n" . '</style>' . "\n";

		// Custom CSS
		$custom_css = apwpultimate_ultimate_get_option('custom_css');

		if( ! empty( $custom_css ) ) {
			echo '<style type="text/css">' . "\n" .
					wp_strip_all_tags( $custom_css )
				 . "\n" . '</style>' . "\n";
		}
	}

}

$apwpultimate_script = new Apwpultimate_Script();