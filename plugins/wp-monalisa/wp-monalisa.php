<?php
/**
 * Plugin Name: wp-Monalisa
 * Plugin URI: http://www.tuxlog.de/wordpress/2009/wp-monalisa/
 * Description: wp-Monalisa is the plugin that smiles at you like monalisa does. place the smilies of your choice in posts, pages or comments.
 * Version: 6.3
 * Author: Hans Matzen <webmaster at tuxlog dot de>
 * Author URI: http://www.tuxlog.de
 * Text Domain: wp-monalisa
 *
 * @package wp-monalisa
 */

/**
 * Copyright 2009-2024 Hans Matzen  (email : webmaster at tuxlog dot de)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 *
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

// include setup functions.
require_once( 'wpml-setup.php' );
// include autoupdate support.
require_once( 'wpml-autoupdate.php' );
// include functions.
require_once( 'wpml-func.php' );
// admin dialog.
require_once( 'wpml-admin.php' );
// comment form functions.
require_once( 'wpml-comment.php' );
// edit dialog functions.
require_once( 'wpml-edit.php' );

// global vars for emoticon replace in comments and posts.
global $wpml_smilies, $wpml_search;
$wpml_smilies = array();
$wpml_search = '';

// global var for printing imagelist for preload once.
global $wpml_first_preload;
$wpml_first_preload = true;

/**
 * Plugin init funktion.
 */
function wp_monalisa_init() {
	// get translation.
	load_plugin_textdomain( 'wp-monalisa', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

	// optionen einlesen.
	$av = array();
	if ( function_exists( 'is_multisite' ) && is_multisite() ) {
		$av = maybe_unserialize( get_blog_option( get_current_blog_id(), 'wpml-opts' ) );
	} else {
		$av = unserialize( get_option( 'wpml-opts' ) );
	}

	// connect ajax call for import and disable comments metabox in classic editor.
	if ( is_admin() ) {
		add_action( 'wp_ajax_wpml_import_ajax', 'wpml_import_ajax' );
		add_action( 'wp_ajax_wpml_edit_disable_comments_ajax', 'wpml_edit_disable_comments_ajax' );
	}

	// javascript nur auf nicht AMP Seiten laden.
	if ( ! wpml_is_amp() ) {
		if ( array_key_exists( 'wpmlscript2footer', $av ) && '1' == $av['wpmlscript2footer'] ) {
			wp_register_script( 'wpml_script', plugins_url( 'wpml_script.js', __FILE__ ), array( 'jquery' ), '9999', true );
		} else {
			wp_register_script( 'wpml_script', plugins_url( 'wpml_script.js', __FILE__ ), array( 'jquery' ), '9999', false );
		}

		// add list of smilies as inline javascript.
		if ( array_key_exists( 'richeditor', $av ) && '1' == $av['richeditor'] ) {
			$resmilies = wpml_get_richedit_smilies();
			$erg = wp_add_inline_script( 'wpml_script', 'window._wpml_richedit_smilies = ' . json_encode( $resmilies ), 'before' );

			add_filter( 'mce_buttons', 'wpml_tinymce_add_button' );
			add_filter( 'mce_external_plugins', 'wpml_tinymce_add_plugin', 99 );
		}
	}

	// add it to the queue.
	wp_enqueue_script( 'wpml_script' );
}

// add css im header hinzufügen.
add_action( 'wp_enqueue_scripts', 'wpml_css' );
add_action( 'admin_print_styles', 'wpml_css' );

/**
 * Register button to the Gutenberg rich editor.
 */
function wpml_add_button2gutenberg_register() {
	wp_register_script( 'wpml_gutenberg-js', plugins_url( 'wpml_gutenberg.js', __FILE__ ), array( 'wp-rich-text' ), '9999' );
}
add_action( 'init', 'wpml_add_button2gutenberg_register' );

/**
 * Add button to the Gutenberg rich editor.
 */
function wpml_add_button2gutenberg() {
	wp_enqueue_script( 'wpml_gutenberg-js' );
}
add_action( 'enqueue_block_editor_assets', 'wpml_add_button2gutenberg' );


/**
 * Since v4.2. WP comes with emojis not using the classic smilies anymore
 * and because there is no switch to disable emojis this function does it
 */
function wpml_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'wpml_disable_emojis_tinymce' );
}


/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @param array $plugins aray of tinymce plugins.
 */
function wpml_disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}

/**
 * Functions for adding a button and plugin to richeditor.
 *
 * @param array $b array of TinyMCE buttons.
 */
function wpml_tinymce_add_button( $b ) {
	array_push( $b, 'wpml_smiley' );
	return $b;
}

/**
 * Function to add wp-monalisa tinymce plugin to tinymce.
 *
 * @param array $plugins array of plugin sof TinyMCE.
 */
function wpml_tinymce_add_plugin( $plugins ) {
	$plugins['wpml_smiley'] = plugins_url( '/wpml_tinymce_plugin.js', __FILE__ );
	return $plugins; }

/**
 * Functions for wpforo integration.
 *
 * @param array $settings Settings array of wpforo tinymce editor.
 */
function wpml_wpforo_add_tinymce_button( $settings ) {
	// first switch emoticons button with wpml button.
	$tb1 = $settings['tinymce']['toolbar1'];
	$settings['tinymce']['toolbar1'] = str_replace( 'emoticons', 'wpml_smiley', $tb1 );
	// $settings['tinymce']['toolbar1'] .= ',wpml_smiley';.

	// add wpml tinymce plugin to list of external plugins if wpForo
	// $settings['external_plugins']['wpml_smiley'] = plugins_url('/wpml_tinymce_plugin.js',__FILE__);.
	if ( version_compare( WPFORO_VERSION, '2.0.6' ) >= 0 ) {
		$settings['external_plugins'] = array(
			'wpforo_pre_button'         => WPFORO_URL . '/assets/js/tinymce-pre.js',
			'wpforo_link_button'        => WPFORO_URL . '/assets/js/tinymce-link.js',
			'wpforo_spoiler_button'     => WPFORO_URL . '/assets/js/tinymce-spoiler.js',
			'wpforo_source_code_button' => WPFORO_URL . '/assets/js/tinymce-code.js',
			// 'emoticons'                 => WPFORO_URL . '/assets/js/tinymce-emoji.js',
			'wpml_smiley'               => plugins_url( '/wpml_tinymce_plugin.js', __FILE__ ),
		);
	} else {
		$settings['external_plugins'] = array(
			'wpforo_pre_button'         => WPFORO_URL . '/wpf-assets/js/tinymce-pre.js',
			'wpforo_link_button'        => WPFORO_URL . '/wpf-assets/js/tinymce-link.js',
			'wpforo_spoiler_button'     => WPFORO_URL . '/wpf-assets/js/tinymce-spoiler.js',
			'wpforo_source_code_button' => WPFORO_URL . '/wpf-assets/js/tinymce-code.js',
			// 'emoticons'                 => WPFORO_URL . '/wpf-assets/js/tinymce-emoji.js',
			'wpml_smiley'               => plugins_url( '/wpml_tinymce_plugin.js', __FILE__ ),
		);
	}

	return $settings;
}

/**
 * Function to add javascript to wpforo.
 *
 * @param array $p wpforo parameter array.
 */
function wpml_wpforo_emoticons_js( $p ) {
	unset( $p['emoticons'] );
	$p['wpml_smiley'] = plugins_url( '/wpml_tinymce_plugin.js', __FILE__ );
	return $p;
}

/**
 * Add image tag to allowed tags of buddypress editor.
 *
 * @param string $data dummy parameter.
 */
function wpml_bp_allow_tags( $data ) {
	global $allowedtags;
	$allowedtags['img'] = array(
		'src' => array(),
		'alt' => array(),
		'title' => array(),
		'height' => array(),
		'width' => array(),
		'style' => array(),
	);
	return $data;
}

/**
 * Function to integrate other plugins with wp-monalisa.
 */
function wpml_integrate_other_plugins() {
	// show smilies in buddypress and bbpress, wpForo or tinymce
	// optionen einlesen.
	$av = array();
	if ( function_exists( 'is_multisite' ) && is_multisite() ) {
		$av = maybe_unserialize( get_blog_option( get_current_blog_id(), 'wpml-opts' ) );
	} else {
		$av = unserialize( get_option( 'wpml-opts' ) );
	}

	if ( defined( 'BP_VERSION' ) && '1' == $av['wpml4buddypress'] ) {

		// include for function get_plugins.
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		// next line only to be executed with BP <2.3.
		$tap = get_plugins();
		if ( version_compare( $tap['buddypress/bp-loader.php']['Version'], '2.3', '<' ) ) {
			add_filter( 'bp_activity_comment_content', 'wpml_convert_emoticons', 99 );
		}
		add_filter( 'bp_get_activity_action', 'wpml_convert_emoticons', 99 );
		add_filter( 'bp_get_activity_content_body', 'wpml_convert_emoticons', 99 );
		add_filter( 'bp_get_activity_content', 'wpml_convert_emoticons', 99 );
		add_filter( 'bp_get_activity_parent_content', 'wpml_convert_emoticons', 99 );
		add_filter( 'bp_get_activity_latest_update', 'wpml_convert_emoticons', 99 );
		add_filter( 'bp_get_activity_latest_update_excerpt', 'wpml_convert_emoticons', 99 );
		add_filter( 'bp_core_render_message_content', 'wpml_convert_emoticons', 99 );
		add_filter( 'bp_get_the_topic_title', 'wpml_convert_emoticons', 99 );
		add_filter( 'bp_get_the_topic_latest_post_excerpt', 'wpml_convert_emoticons', 99 );
		add_filter( 'bp_get_the_topic_post_content', 'wpml_convert_emoticons', 99 );
		add_filter( 'bp_get_group_description', 'wpml_convert_emoticons', 99 );
		add_filter( 'bp_get_group_description_excerpt', 'wpml_convert_emoticons', 99 );
		add_filter( 'bp_get_message_notice_subject', 'wpml_convert_emoticons', 99 );
		add_filter( 'bp_get_message_notice_text', 'wpml_convert_emoticons', 99 );
		add_filter( 'bp_get_message_thread_subject', 'wpml_convert_emoticons', 99 );
		add_filter( 'bp_get_message_thread_excerpt', 'wpml_convert_emoticons', 99 );
		add_filter( 'bp_get_the_thread_message_content', 'wpml_convert_emoticons', 99 );
		add_filter( 'bp_get_message_thread_content', 'wpml_convert_emoticons', 99 );
		add_filter( 'bp_get_the_profile_field_value', 'wpml_convert_emoticons', 99 );

		// BP Profile Message UX filters.
		$plugins = get_option( 'active_plugins' );
		$required_plugin = 'bp-profile-message-ux/bp-profile-message-ux.php';
		if ( in_array( $required_plugin, $plugins ) ) {
			add_filter( 'bp_get_send_public_message_button', 'wpml_convert_emoticons', 99 );
			add_filter( 'bp_get_send_message_button', 'wpml_convert_emoticons', 99 );
		}

		// add img tag so that smilies can be displayed.
		add_filter( 'init', 'wpml_bp_allow_tags' );
	}

	// for bbpress.
	if ( class_exists( 'bbPress' ) && '1' == $av['wpml4bbpress'] ) {
		add_filter( 'bbp_get_reply_content', 'wpml_convert_emoticons', 99000 );
		add_filter( 'bbp_get_topic_content', 'wpml_convert_emoticons', 99000 );
	}

	// for wpForo.
	if ( defined( 'WPFORO_VERSION' ) && '1' == $av['wpml4wpforo'] ) {
		add_filter( 'wpforo_editor_settings', 'wpml_wpforo_add_tinymce_button', 1001 );
		add_filter( 'wpforo_members_init_fields_tinymce_settings', 'wpml_wpforo_add_tinymce_button', 1001 );
		add_filter( 'mce_external_plugins', 'wpml_wpforo_emoticons_js', 25 );
		add_filter( 'wpforo_content_after', 'wpml_convert_emoticons', 99 );
	}
}


//
// MAIN.
//

// activating deactivating the plugin.
register_activation_hook( __FILE__, 'wp_monalisa_install' );
// uncomment this to loose everything when deactivating the plugin.
register_deactivation_hook( __FILE__, 'wp_monalisa_deinstall' );

// init plugin.
add_action( 'init', 'wp_monalisa_init' );
// add support for other plugins.
add_action( 'init', 'wpml_integrate_other_plugins', 15 );
// add comment support bp_activity_comment_content.
add_action( 'init', 'wpml_comment_init' );

// add option page.
add_action( 'admin_menu', 'wpml_admin_init' );
// add edit dialog support.
add_action( 'admin_menu', 'wpml_edit_init' );

// add filters for smiley replace and make sure we are called last.
add_filter( 'init', 'wpml_map_emoticons', '99' );
add_filter( 'the_content', 'wpml_convert_emoticons', 99 );
add_filter( 'the_excerpt', 'wpml_convert_emoticons', 99 );
add_filter( 'comment_text', 'wpml_convert_emoticons', 99 );

// THE END.
