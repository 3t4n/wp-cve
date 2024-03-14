<?php
/*
 * Plugin Name: Disable Everything
 * Description: Disable all unnecessary WordPress features and speed up your website.
 * Version: 0.4.1
 * Author: Dessky Team
 * Author URI: https://dessky.com
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 * Text Domain: disable-everything
 * Domain Path: /languages
 */

// prevent direct access
defined ( 'ABSPATH' ) or die ( 'Forbidden' );

define ( 'DISABLEEVERYTHING_FILE', __FILE__ );

// debug logging if required
function disable_everything_log($message) {
	if (WP_DEBUG === true) {
		if (is_array ( $message ) || is_object ( $message )) {
			error_log ( print_r ( $message, true ) );
		} else {
			error_log ( $message );
		}
	}
}

/* Remove Options */
function disable_everything_init() {
	// Self Pingbacks
	add_action ( 'pre_ping', function (&$links) {
		$home = get_option ( 'home' );
		foreach ( $links as $l => $link ) {
			if (strpos ( $link, $home ) === 0) {
				unset ( $links [$l] );
			}
		}
	} );

	// Block User-Enumeration
	if (disable_everything_check_option ( 'blockuserenumeration' )) {

		// block user-enumeration
		if (! is_admin ()) {
			// default URL format
			if (preg_match ( '/author=([0-9]*)/i', $_SERVER ['QUERY_STRING'] ))
				die ();
			add_filter ( 'redirect_canonical', function ($redirect, $request) {
				// permalink URL format
				if (preg_match ( '/\?author=([0-9]*)(\/*)/i', $request ))
					die ();
				else
					return $redirect;
			}, 10, 2 );
		}
	}
	// Disable Author Archives
	if (disable_everything_check_option ( 'disableauthorarchives' )) {

		// disable author archives
		remove_filter ( 'template_redirect', 'redirect_canonical' );
		add_action ( 'template_redirect', function () {
			if (is_author ()) {
				global $wp_query;
				$wp_query->set_404 ();
				status_header ( 404 );
			} else {
				redirect_canonical ();
			}
		} );
	}
	// Remove Capital P Dangit
	if (disable_everything_check_option ( 'removecapitalpdangit' )) {

		remove_filter ( 'the_title', 'capital_P_dangit', 11 );
		remove_filter ( 'the_content', 'capital_P_dangit', 11 );
		remove_filter ( 'comment_text', 'capital_P_dangit', 31 );
	}
	// Remove screen options and contextual help
	if (disable_everything_check_option ( 'removescreenoptions' )) {
		// Remove help tab
		add_action ( 'admin_head', 'disable_everything_remove_help_tabs' );

		// Remove screen options
		add_filter ( 'screen_options_show_screen', '__return_false' );
	}
	// Remove Howdy
	if (disable_everything_check_option ( 'removehowdy' )) {
		// removes howdy "greeting"
		add_filter ( 'admin_bar_menu', function ($wp_admin_bar) {
			$my_account = $wp_admin_bar->get_node ( 'my-account' );
			$newtitle = 'My Profile';
			$wp_admin_bar->add_node ( array (
					'id' => 'my-account',
					'title' => $newtitle
			) );
		} );
	}

	// Remove items from adminbar
	if (disable_everything_check_option ( 'removeitemsadminbar' )) {

		// removes redundant items from adminbar
		add_action ( 'admin_bar_menu', function ($wp_admin_bar) {
			global $wp_admin_bar;

			/**
			 * * BACKEND **
			 */
			// remove WP logo and subsequent drop-down menu
			$wp_admin_bar->remove_node ( 'wp-logo' );

			// remove View Site text
			$wp_admin_bar->remove_node ( 'view-site' );

			// remove "+ New" drop-down menu
			$wp_admin_bar->remove_node ( 'new-content' );

			// remove Comments
			$wp_admin_bar->remove_node ( 'comments' );

			// remove plugin updates count
			$wp_admin_bar->remove_node ( 'updates' );

			/**
			 * * FRONTEND **
			 */
			// remove Dashboard link
			$wp_admin_bar->remove_node ( 'dashboard' );

			// remove Themes, Widgets, Menus, Header links
			$wp_admin_bar->remove_node ( 'appearance' );
		}, 99 );
	}

	// Clean Dashboard
	if (disable_everything_check_option ( 'cleandashboard' )) {

		add_action ( 'wp_dashboard_setup', function () {
			remove_meta_box ( 'dashboard_quick_press', 'dashboard', 'side' ); // Quick Press widget
			remove_meta_box ( 'dashboard_recent_drafts', 'dashboard', 'side' ); // Recent Drafts
			remove_meta_box ( 'dashboard_primary', 'dashboard', 'side' ); // WordPress.com Blog
			remove_meta_box ( 'dashboard_secondary', 'dashboard', 'side' ); // Other WordPress News
			remove_meta_box ( 'dashboard_incoming_links', 'dashboard', 'normal' ); // Incoming Links
			remove_meta_box ( 'dashboard_plugins', 'dashboard', 'normal' ); // Plugins
			remove_meta_box ( 'dashboard_right_now', 'dashboard', 'normal' ); // Right Now
			remove_meta_box ( 'dashboard_activity', 'dashboard', 'normal' ); // Activity
			remove_meta_box ( 'dashboard_site_health', 'dashboard', 'normal' ); // Site Health
			remove_meta_box ( 'dashboard_php_nag', 'dashboard', 'normal' ); // PHP nag
			remove_action ( 'welcome_panel', 'wp_welcome_panel' ); // Remove Welcome Panel
		} );
	}

	// Emojis
	if (disable_everything_check_option ( 'emojis' )) {
		remove_action ( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action ( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action ( 'wp_print_styles', 'print_emoji_styles' );
		remove_action ( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter ( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter ( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter ( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter ( 'emoji_svg_url', '__return_false' );
		add_filter ( 'tiny_mce_plugins', function ($plugins) {
			return array_diff ( $plugins, array (
					'wpemoji'
			) );
		} );
	}

	// Embed
	if (disable_everything_check_option ( 'embed' )) {
		global $wp;
		$wp->public_query_vars = array_diff ( $wp->public_query_vars, array (
				'embed'
		) );
		remove_action ( 'rest_api_init', 'wp_oembed_register_route' );
		remove_filter ( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
		remove_action ( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action ( 'wp_head', 'wp_oembed_add_host_js' );
		remove_filter ( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );
		add_filter ( 'embed_oembed_discover', '__return_false' );
		add_filter ( 'tiny_mce_plugins', function ($plugins) {
			return array_diff ( $plugins, array (
					'wpembed'
			) );
		} );
		add_filter ( 'rewrite_rules_array', function ($rules) {
			foreach ( $rules as $rule => $rewrite ) {
				if (strpos ( $rewrite, 'embed=true' ) !== false) {
					unset ( $rules [$rule] );
				}
			}
			return $rules;
		} );
	}

	// XML-RPC
	if (disable_everything_check_option ( 'xmlrpc' )) {
		add_filter ( 'xmlrpc_enabled', '__return_false' );
		add_filter ( 'pings_open', '__return_false', 9999 );
		add_filter ( 'wp_headers', function ($headers) {
			unset ( $headers ['X-Pingback'], $headers ['x-pingback'] );
			return $headers;
		} );
	}

	// Generator
	if (disable_everything_check_option ( 'generator' )) {
		remove_action ( 'wp_head', 'wp_generator' );
		add_filter ( 'the_generator', function () {
			return '';
		} );
	}

	// WLW Manifest
	if (disable_everything_check_option ( 'manifest' )) {
		remove_action ( 'wp_head', 'wlwmanifest_link' );
	}

	// RSD Link
	if (disable_everything_check_option ( 'rsdlink' )) {
		remove_action ( 'wp_head', 'rsd_link' );
	}

	// Shortlink
	if (disable_everything_check_option ( 'shortlink' )) {
		remove_action ( 'wp_head', 'wp_shortlink_wp_head' );
		remove_action ( 'template_redirect', 'wp_shortlink_header', 11, 0 );
	}

	// RSS Feeds
	if (disable_everything_check_option ( 'rssfeeds' )) {
		remove_action ( 'wp_head', 'feed_links', 2 );
		remove_action ( 'wp_head', 'feed_links_extra', 3 );
		add_action ( 'template_redirect', function () {
			if (! is_feed () || is_404 ()) {
				return;
			}
			if (isset ( $_GET ['feed'] )) {
				wp_redirect ( esc_url_raw ( remove_query_arg ( 'feed' ) ), 301 );
				exit ();
			}
			if (get_query_var ( 'feed' ) !== 'old') {
				set_query_var ( 'feed', '' );
			}
			redirect_canonical ();
			wp_die ( sprintf ( __ ( "RSS Feeds disabled, please visit the <a href='%s'>homepage</a>!" ), esc_url ( home_url ( '/' ) ) ) );
		}, 1 );
	}

	// REST API
	if (disable_everything_check_option ( 'restapi' ) && ! is_admin ()) {
		remove_action ( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
		remove_action ( 'wp_head', 'rest_output_link_wp_head' );
		remove_action ( 'template_redirect', 'rest_output_link_header', 11, 0 );
		add_filter ( 'rest_authentication_errors', function ($result) {
			if (empty ( $result ) && ! is_admin ()) {
				return new WP_Error ( 'rest_authentication_error', __ ( 'Forbidden', 'disable-everything' ), array (
						'status' => 403
				) );
			}
			return $result;
		}, 20 );
	}

	// Block Library
	if (disable_everything_check_option ( 'blocks' )) {
		add_action ( 'wp_print_styles', function () {
			wp_dequeue_style ( 'wp-block-library' );
		}, 100 );
	}

	// Application Passwords
	if (disable_everything_check_option ( 'applicationpasswords' )) {
		
		// completely disable the new Application Passwords functionality
		add_filter('wp_is_application_passwords_available', '__return_false');
	}
	
	//TODO: Filters and Actions
	// Core Privacy Tools
	if (disable_everything_check_option ( 'coreprivacytools' )) {
		
		// disable the Core Privacy Tools
		// Removes required user's capabilities for core privacy tools by adding the `do_not_allow` capability.
		add_filter( 'map_meta_cap', 'disable_everything_disable_core_privacy_tools', 10, 2 );
		
		/**
		 * Short circuits the option for the privacy policy page to always return 0.
		 *
		 * The option is used by get_privacy_policy_url() among others.
		 */
		add_filter( 'pre_option_wp_page_for_privacy_policy', '__return_zero' );
		
		/**
		 * Removes the default scheduled event used to delete old export files.
		 */
		remove_action( 'init', 'wp_schedule_delete_old_privacy_export_files' );
		
		/**
		 * Removes the hook attached to the default scheduled event for removing
		 * old export files.
		 */
		remove_action( 'wp_privacy_delete_old_export_files', 'wp_privacy_delete_old_export_files' );
		
	}
	// Disable Site Health
	if (disable_everything_check_option ( 'sitehealth' )) {
		
		// completely disable the Site Health
		
		// disable the admin menu
		add_action( 'admin_menu', 'disable_everything_remove_site_health_menu' );
		
		// block site health page screen
		add_action( 'current_screen', 'disable_everything_block_site_health_access' );
		
	}
	// adjacent_posts
	if (disable_everything_check_option ( 'adjacentposts' )) {
		
		// remove the next and previous post links.
		remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
		
	}
	// Version
	if (disable_everything_check_option ( 'version' )) {
		
		// Remove WordPress version var (?ver=) after styles and scripts.
		
		// remove ver= after style and script links.
			add_filter(
					'style_loader_src',
					function ( $src ) {
						if ( strpos( $src, 'ver=' ) ) {
							$src = remove_query_arg( 'ver', $src );
						}
						return $src;
					},
					9999
					);
			add_filter(
					'script_loader_src',
					function ( $src ) {
						if ( strpos( $src, 'ver=' ) ) {
							$src = remove_query_arg( 'ver', $src );
						}
						return $src;
					},
					9999
					);
		
	}
	// dns-prefetch
	if (disable_everything_check_option ( 'dnsprefetch' )) {
		
		// remove s.w.org dns-prefetch.
		remove_action( 'wp_head', 'wp_resource_hints', 2 );
		
	}
	// PDF Thumbnails
	if (disable_everything_check_option ( 'pdfthumbnails' )) {
		
		// completely disable the PDF Thumbnails functionality
		add_filter(
				'fallback_intermediate_image_sizes',
				function() {
					return array();
				}
				);
		
	}
	// Empty Trash
	if (disable_everything_check_option ( 'emptytrash' )) {
		
		// Empty trash sooner.
		if ( ! defined( 'EMPTY_TRASH_DAYS' ) ) {
			define( 'EMPTY_TRASH_DAYS', 7 );
		}
		
	}
	// Plugin and Theme Editor
	if (disable_everything_check_option ( 'pluginandthemeeditor' )) {
		
		// disable the Plugin and Theme Editor
		if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
			define( 'DISALLOW_FILE_EDIT', true ); // phpcs:ignore
		}
		
	}
	// oEmbed
	if (disable_everything_check_option ( 'oembed' )) {
		
		// Remove oEmbed Scripts
		// Since WordPress 4.4, oEmbed is installed and available by default. WordPress assumes you’ll want to easily embed media like tweets and YouTube videos so includes the scripts as standard. If you don’t need oEmbed, you can remove it.
		wp_deregister_script( 'wp-embed' );
		
	}
	// Remote Block Patterns
	if (disable_everything_check_option ( 'remoteblockpatterns' )) {
		
		// Disable Remote Block Patterns
		add_filter( 'should_load_remote_block_patterns', 'disable_everything_disable_remote_patterns_filter' );
	}
}
add_action ( 'init', 'disable_everything_init' );
function disable_everything_wp_enqueue_scripts() {
	// Dashicons
	if (disable_everything_check_option ( 'dashicons' ) && ! is_user_logged_in ()) {
		wp_dequeue_style ( 'dashicons' );
		wp_deregister_style ( 'dashicons' );
	}

	// Heartbeat
	if (disable_everything_check_option ( 'heartbeat' )) {
		global $pagenow;
		if ($pagenow !== 'post.php' && $pagenow !== 'post-new.php') {
			wp_deregister_script ( 'heartbeat' );
		}
	}
}
add_action ( 'wp_enqueue_scripts', 'disable_everything_wp_enqueue_scripts' );

/* Settings */

// check checkbox setting
function disable_everything_check_option($suffix) {
	$settings = get_option ( 'disable_everything_settings' );
	return (isset ( $settings ['disable-everything-options-' . $suffix] ) && $settings ['disable-everything-options-' . $suffix] === "YES");
}

// check checkbox setting
function disable_everything_check_other_setting($suffix) {
	$settings = get_option ( 'disable_everything_settings' );
	return (isset ( $settings ['disable-everything-' . $suffix] ) && $settings ['disable-everything-' . $suffix] === "YES");
}

// add settings page
function disable_everything_menus() {
	add_options_page ( __ ( 'Disable Everything', 'disable-everything' ), __ ( 'Disable Everything', 'disable-everything' ), 'manage_options', 'disable-everything', 'disable_everything_options' );
}

// add the settings
function disable_everything_settings() {
	register_setting ( 'disable-everything', 'disable_everything_settings' );

	add_settings_section ( 'disable-everything-section-options', __ ( 'Options', 'disable-everything' ), 'disable_everything_settings_section', 'disable-everything' );

	/* TODO - Placeholder Settings */
	/**
	 * When pro is not activated
	 */
	if (! function_exists ( 'disable_everything_pro_activated' )) {

		$pro_options = array (
				array (
						'Comments',
						'Disable Comments on Frontend and Admin'
				),
				array (
						'WP Updates',
						'Disables all WordPress updates (core, plugins and themes) and removes update notifications in admin'
				),
				array (
						'Auto-update Email Notifications for Themes and Plugins',
						'Disable auto-update Email Notifications for Themes and Plugins updates Only'
				),
				array (
						'Post Revisions',
						'Disable Post Revisions (for All Post Types)'
				),
				array (
						'Search',
						'Disable WordPress Search on Frontend only'
				),
				array (
						'WP Login Logo and Favicon',
						'Disable WordPress logo on the login page and W favicon (logo is replaced with your site\'s name and new favicon can be added in Customizer)'
				),
				array (
						'Administration Email Verification Prompt',
						'Disables the administration email verification prompt introduced in WordPress 5.3'
				),
				array (
						'Lazy Loading',
						'Disable Lazy Loading (introduced in WP version 5.5)'
				),
				array (
						'Yoast SEO Bloat',
						'Disable Yoast SEO Bloat'
				),
				array (
						'WooCommerce Bloat',
						'Disable WooCommerce Bloat'
				),
				array (
						'Right Click',
						'Disable Right Click (by checking this you will disable right click, view source, cut/copy/paste, text selection, inspect element, image save, image drag & drop. However this will not work if you are logged as Administrator or Editor, in other words you need to be logged out of admin panel in order to see all of these disabled)'
				),
				array (
						'Admin Footer',
						'Disable the admin footer text'
				),
				array (
						'Elementor Bloat',
						'Disable JS and CSS Bloat and Dashboard Notifications for both Elementor and Essential Addons for Elementor plugins. Following are Disabled in Elementor: Google Fonts, Elementor Frontend JS, Elementor Dialog, Swipper, Elementor Waypoints, Font Awesome icons by Elementor.'
				),
				array (
						'Jetpack Promotions',
						'Disable all Jetpack related notices that promote services like the backup services VaultPress, WordPress Apps or Blaze.'
				),
				array (
						'Contact Form 7 Bloat',
						'Remove CF7 scripts and styles from all pages and posts where CF7 is not used'
				),
				array (
						'Autoptimize Toolbar',
						'Disables the Autoptimize Toolbar from the Top Bar in Dashboard'
				),
				array (
						'W3 Total Cache HTML Footer Comments',
						'Disables HTML comments from footer generated by W3 Total Cache plugin'
				)
		);

		$pro_options_cnt = 0;
		$pro_options_cnt = count ( $pro_options );

		for($i = 0; $i < $pro_options_cnt; $i ++) {

			add_settings_field ( 'disable-everything-options-placeholder' . $i, __ ( $pro_options [$i] [0], 'disable-everything' ), 'disable_everything_placeholder', 'disable-everything', 'disable-everything-section-options', array (
					'placeholderlabel' => $pro_options [$i] [1],
					'placeholderid' => $i
			) );
		}
	}

	add_settings_field ( 'disable-everything-options-blockuserenumeration', __ ( 'User-Enumeration', 'disable-everything' ), 'disable_everything_blockuserenumeration', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-disableauthorarchives', __ ( 'Author Archives', 'disable-everything' ), 'disable_everything_disableauthorarchives', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-removecapitalpdangit', __ ( 'capital_P_dangit', 'disable-everything' ), 'disable_everything_removecapitalpdangit', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-removescreenoptions', __ ( 'Screen options and help', 'disable-everything' ), 'disable_everything_removescreenoptions', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-removehowdy', __ ( 'Howdy in adminbar', 'disable-everything' ), 'disable_everything_removehowdy', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-removeitemsadminbar', __ ( 'Navigation items in adminbar', 'disable-everything' ), 'disable_everything_removeitemsadminbar', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-cleandashboard', __ ( 'Clean Dashboard', 'disable-everything' ), 'disable_everything_cleandashboard', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-emojis', __ ( 'Emojis', 'disable-everything' ), 'disable_everything_emojis', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-embed', __ ( 'Embed Objects', 'disable-everything' ), 'disable_everything_embed', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-dashicons', __ ( 'Dashicons', 'disable-everything' ), 'disable_everything_dashicons', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-heartbeat', __ ( 'Heartbeat', 'disable-everything' ), 'disable_everything_heartbeat', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-xmlrpc', __ ( 'XML-RPC + Pingback', 'disable-everything' ), 'disable_everything_xmlrpc', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-generator', __ ( 'Generator', 'disable-everything' ), 'disable_everything_generator', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-manifest', __ ( 'WLW Manifest', 'disable-everything' ), 'disable_everything_manifest', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-rsdlink', __ ( 'Really Simple Discovery', 'disable-everything' ), 'disable_everything_rsdlink', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-shortlink', __ ( 'Short Link', 'disable-everything' ), 'disable_everything_shortlink', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-rssfeeds', __ ( 'RSS Feeds', 'disable-everything' ), 'disable_everything_rssfeeds', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-restapi', __ ( 'REST API', 'disable-everything' ), 'disable_everything_restapi', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-blocks', __ ( 'Block Library', 'disable-everything' ), 'disable_everything_blocks', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-applicationpasswords', __ ( 'Application Passwords', 'disable-everything' ), 'disable_everything_applicationpasswords', 'disable-everything', 'disable-everything-section-options' );
	//TODO: Settings Field
	add_settings_field ( 'disable-everything-options-coreprivacytools', __ ( 'Core Privacy Tools', 'disable-everything' ), 'disable_everything_coreprivacytools', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-sitehealth', __ ( 'Site Health', 'disable-everything' ), 'disable_everything_sitehealth', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-adjacentposts', __ ( 'adjacent_posts', 'disable-everything' ), 'disable_everything_adjacentposts', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-version', __ ( 'Version', 'disable-everything' ), 'disable_everything_version', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-pdfthumbnails', __ ( 'PDF Thumbnails', 'disable-everything' ), 'disable_everything_pdfthumbnails', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-emptytrash', __ ( 'Empty Trash', 'disable-everything' ), 'disable_everything_emptytrash', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-pluginandthemeeditor', __ ( 'Plugin and Theme Editor', 'disable-everything' ), 'disable_everything_pluginandthemeeditor', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-oembed', __ ( 'oEmbed', 'disable-everything' ), 'disable_everything_oembed', 'disable-everything', 'disable-everything-section-options' );
	add_settings_field ( 'disable-everything-options-remoteblockpatterns', __ ( 'Remote Block Patterns', 'disable-everything' ), 'disable_everything_remoteblockpatterns', 'disable-everything', 'disable-everything-section-options' );
}

// allow the settings to be stored
add_filter ( 'whitelist_options', function ($whitelist_options) {
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-blockuserenumeration';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-disableauthorarchives';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-removecapitalpdangit';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-removescreenoptions';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-removehowdy';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-removeitemsadminbar';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-cleandashboard';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-emojis';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-embed';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-dashicons';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-heartbeat';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-generator';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-xmlrpc';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-manifest';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-rsdlink';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-shortlink';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-rssfeeds';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-restapi';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-blocks';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-applicationpasswords';
	//TODO: Storing Settings
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-coreprivacytools';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-sitehealth';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-adjacentposts';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-dnsprefetch';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-pdfthumbnails';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-emptytrash';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-pluginandthemeeditor';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-oembed';
	$whitelist_options ['disable-everything'] [] = 'disable-everything-options-remoteblockpatterns';
	
	return $whitelist_options;
} );

// define output for settings page
function disable_everything_options() {
	echo '<style>#disable-everything-tabs h2{display:none}</style>';
	echo '<div class="widefat">';
	
	//Banner
	if (! function_exists ( 'disable_everything_pro_activated' )) {
	echo '<div class="wrap">';
	echo '<a href="https://syncpostsbetweensites.dessky.info" target="_blank"><img src="' . esc_url( plugins_url( 'assets/img/sync-posts-between-sites.jpg', __FILE__ ) ) . '" /></a>';
	echo '</div>';
	}
	
	echo '<div class="wrap disable-everything-panel">
		<h1>' . __ ( 'Disable Everything', 'disable-everything' ) . '</h1>';

	echo '<hr>';
	echo '  <p class="description">';
	echo __ ( 'This plugin is used to disable all unused options that are slowing down your site. Doing so will improve your website performance.', 'disable-everything' );
	echo '  <br>';
	echo __ ( 'This is NOT a caching plugin, but should play well with any caching plugin you decide to use.', 'disable-everything' );
	echo '  </p>';

	echo '<hr>';
	echo '<h2>' . __ ( 'Server Requirements Check', 'disable-everything' ) . '</h2>
		<p class="description menu-settings" style="margin:0 0 24px 0;">To put it short, if both boxes are in blue you are good. If some is in red you should consider upgrading your hosting to get better performance out of your site.</p>';
	echo '  <div style="margin:0 0 24px 0;">';
	echo '    <a href="https://www.php.net/supported-versions.php" target="_blank" class="update-message notice inline notice-' . disable_everything_badge_php () . ' notice-alt">PHP ' . disable_everything_phpversion () . '</a>';

	$dbtype = null;
	$dbversion = null;
	$badge_mysql = null;
	$badge_maria = null;

	$dbtype = disable_everything_dbtype ();

	$dbversion = disable_everything_dbversion ();

	$badge_mysql = disable_everything_badge_mysql ();

	$badge_maria = disable_everything_badge_maria ();

	if ($dbtype === 'MYSQL') {
		echo ' &nbsp; <a href="https://www.fromdual.com/support-for-mysql-from-oracle" target="_blank" class="update-message notice inline notice-' . $badge_mysql . ' notice-alt">' . $dbtype . ' ' . $dbversion . '</a>';
	} else {
		echo ' &nbsp; <a href="https://www.fromdual.com/support-for-mysql-from-oracle" target="_blank" class="update-message notice inline notice-' . $badge_maria . ' notice-alt">' . $dbtype . ' ' . $dbversion . '</a>';
	}
	echo '  </div>';
	echo '<hr>';
	?>

<select id="opt-all-none" style="width: 80px; height: 32px;">
	<option value="all"><?php _e( 'All', 'disable-everything' ); ?></option>
	<option value="none"><?php _e( 'None', 'disable-everything' ); ?></option>
</select>
<button id="btn-all-none" class="button button-secondary"
	style="width: 80px; height: 32px;"><?php _e( 'Select', 'disable-everything' ); ?></button>

<?php
	echo '<hr>';
	echo '  <form action="options.php" method="post">';

	settings_fields ( 'disable-everything' );

	do_settings_sections ( 'disable-everything' );
	echo '<hr>';
	submit_button ();
	echo '  </form>';

	?>
<script>

		/**
	 	 * The select all/none functionality.
		 */
		let btn = document.getElementById('btn-all-none');
		let opt = document.getElementById('opt-all-none');
		let checkboxes = document.querySelectorAll('.disable-everything-panel input[type="checkbox"]');
		btn.addEventListener('click', function() {
			if (opt.value === 'all') {
				for (let i = 0; i <= checkboxes.length; i += 1) {

					if(checkboxes[i] !== undefined){
						checkboxes[i].checked = true;
					}
				}
			} else {
				for (let i = 0; i <= checkboxes.length; i += 1) {

					if(checkboxes[i] !== undefined){
						checkboxes[i].checked = false;
					}
				}
			}
		});

		</script>
<?php

	// estimated savings
	$reqs = 0;
	$size = 0;
	$tags = 0;
	if (disable_everything_check_option ( 'emojis' )) {
		$reqs += 2;
		$size += 16;
		$tags += 2;
	}
	if (disable_everything_check_option ( 'embed' )) {
		$reqs += 1;
		$size += 6;
		$tags += 1;
	}
	if (disable_everything_check_option ( 'dashicons' )) {
		$reqs += 1;
		$size += 46;
		$tags += 1;
	}
	if (disable_everything_check_option ( 'heartbeat' )) {
		$reqs += 1;
		$size += 6;
		$tags += 1;
	}
	if (disable_everything_check_option ( 'generator' )) {
		$tags += 1;
	}
	if (disable_everything_check_option ( 'xmlrpc' )) {
		$tags += 1;
	}
	if (disable_everything_check_option ( 'manifest' )) {
		$tags += 1;
	}
	if (disable_everything_check_option ( 'rsdlink' )) {
		$tags += 1;
	}
	if (disable_everything_check_option ( 'shortlink' )) {
		$tags += 1;
	}
	if (disable_everything_check_option ( 'rssfeeds' )) {
		$tags += 2;
	}
	if (disable_everything_check_option ( 'restapi' )) {
		$tags += 1;
	}
	if (disable_everything_check_option ( 'blocks' )) {
		$reqs += 1;
		$size += 29;
		$tags += 1;
	}
	echo '<hr>';
	echo '  <h2>' . __ ( 'Estimated Savings', 'disable-everything' ) . '</h2>';
	echo '  <table class="form-table">';
	echo '    <tbody>';
	echo '      <tr>';
	echo '        <th scope="row">' . __ ( 'File Requests', 'disable-everything' ) . '</th>';
	echo '        <td>' . esc_html ( $reqs ) . '</td>';
	echo '      </tr>';
	echo '      <tr>';
	echo '        <th scope="row">' . __ ( 'File Size', 'disable-everything' ) . '</th>';
	echo '        <td>' . esc_html ( ($size >= 1024 ? (number_format ( $size / 1024, 1 )) . 'Mb' : $size . 'kb') ) . '</td>';
	echo '      </tr>';
	echo '      <tr>';
	echo '        <th scope="row">' . __ ( 'HTML Tags', 'disable-everything' ) . '</th>';
	echo '        <td>' . esc_html ( $tags ) . '</td>';
	echo '      </tr>';
	echo '    </tbody>';
	echo '  </table>';
	echo '</div>';
}
function disable_everything_badge_php() {
	$ver = disable_everything_phpversion ();
	$col = "error";
	if (version_compare ( $ver, '7.2', '>=' )) {
		$col = "warning";
	}
	if (version_compare ( $ver, '7.3', '>=' )) {
		$col = "info";
	}
	return $col;
}
function disable_everything_phpversion() {
	return explode ( '-', phpversion () ) [0]; // trim any extra information
}
function disable_everything_dbtype_transient() {
	$dbtype = get_transient ( 'disable_everything_dbtype' );

	if (false === $dbtype) {

		global $wpdb;
		$vers = $wpdb->get_var ( "SELECT VERSION() as mysql_version" );
		if (stripos ( $vers, 'MARIA' ) !== false) {
			$dbtype = 'MARIA';
		}
		$dbtype = 'MYSQL';

		set_transient ( 'disable_everything_dbtype', $dbtype, 604800 ); // expire after 1 week

		return $dbtype;
	} else {
		return $dbtype;
	}
}
function disable_everything_dbversion_transient() {
	$dbversion = get_transient ( 'disable_everything_dbversion' );

	if (false === $dbversion) {

		global $wpdb;
		$vers = $wpdb->get_var ( "SELECT VERSION() as mysql_version" );
		$vers_transient = explode ( '-', $vers ) [0]; // trim any extra information

		set_transient ( 'disable_everything_dbversion', $vers_transient, 604800 ); // expire after 1 week

		return $vers_transient;
	} else {
		return $dbversion;
	}
}
function disable_everything_dbtype() {
	return disable_everything_dbtype_transient ();
}
function disable_everything_dbversion() {
	return disable_everything_dbversion_transient ();
}
function disable_everything_badge_mysql() {
	$ver = disable_everything_dbversion ();
	$col = "error";
	if (version_compare ( $ver, '5.6', '>=' )) {
		$col = "warning";
	}
	if (version_compare ( $ver, '5.7', '>=' )) {
		$col = "info";
	}
	return $col;
}
function disable_everything_badge_maria() {
	$ver = disable_everything_dbversion ();
	$col = "error";
	if (version_compare ( $ver, '10.0', '>=' )) {
		$col = "warning";
	}
	if (version_compare ( $ver, '10.1', '>=' )) {
		$col = "info";
	}
	return $col;
}

/* Help Tab */
function disable_everything_remove_help_tabs() {
	if (is_admin ()) {
		$screen = get_current_screen ();
		$screen->remove_help_tabs ();
	}
}

/**
 * Footer text
 *
 * @since 1.0
 */
function disable_everything_footer_text($text) {
	global $current_screen;
	if (! empty ( $current_screen->id ) && strpos ( $current_screen->id, 'disable-everything' ) !== false) {
		return 'If you like <strong>Disable Everything</strong> please leave us a <a href="https://wordpress.org/support/plugin/disable-everything/reviews?rate=5#new-post" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating to help us spread the word. A huge thanks in advance!';
	}

	return $text;
}
add_filter ( 'admin_footer_text', 'disable_everything_footer_text' );

/**
 * Plugin Text - meta links
 */
if (! function_exists ( 'disable_everything_pro_activated' )) {
	function disable_everything_row_meta($input, $page) {

		// check permissions
		if ($page != plugin_basename ( DISABLEEVERYTHING_FILE )) {
			return $input;
		}

		return array_merge ( $input, array (
				'<a href="https://dessky.com/plugin/disable-everything/" target="_blank" style="font-weight:700;">Order PRO version</a>'
		) );
	}
	add_filter ( 'plugin_row_meta', 'disable_everything_row_meta', 10, 2 );
}

// define output for settings section
function disable_everything_settings_section() {
	// nothing to output
}

/* TODO - Placeholder Checkboxes */
// defined output for settings if PRO is not active
function disable_everything_placeholder($args) {
	echo '<label><input id="disable-everything-options-placeholder' . $args ['placeholderid'] . '" name="disable_everything_settings[disable-everything-options-placeholder' . $args ['placeholderid'] . ']" type="checkbox" value="" disabled > <span style="color:gray">';
	echo __ ( $args ['placeholderlabel'], 'disable-everything' );
	echo '</span> ';

	echo '<small class="update-message notice inline notice-warning notice-alt"><a style="color:orange" href="https://dessky.com/plugin/disable-everything/" target="_blank">Available in PRO version</a></small>';
}

// defined output for settings
function disable_everything_blockuserenumeration() {
	$checked = "";
	if (disable_everything_check_option ( 'blockuserenumeration' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-blockuserenumeration" name="disable_everything_settings[disable-everything-options-blockuserenumeration]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Block User-Enumeration', 'disable-everything' );
}
function disable_everything_disableauthorarchives() {
	$checked = "";
	if (disable_everything_check_option ( 'disableauthorarchives' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-disableauthorarchives" name="disable_everything_settings[disable-everything-options-disableauthorarchives]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Disable Author Archives', 'disable-everything' );
}
function disable_everything_removecapitalpdangit() {
	$checked = "";
	if (disable_everything_check_option ( 'removecapitalpdangit' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-removecapitalpdangit" name="disable_everything_settings[disable-everything-options-removecapitalpdangit]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Changes the incorrect capitalization of Wordpress into WordPress. WordPress uses it to filter the content, the title and comment text.', 'disable-everything' );
}
function disable_everything_removescreenoptions() {
	$checked = "";
	if (disable_everything_check_option ( 'removescreenoptions' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-removescreenoptions" name="disable_everything_settings[disable-everything-options-removescreenoptions]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Disable screen options and contextual help', 'disable-everything' );
}
function disable_everything_removehowdy() {
	$checked = "";
	if (disable_everything_check_option ( 'removehowdy' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-removehowdy" name="disable_everything_settings[disable-everything-options-removehowdy]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Remove Howdy from adminbar', 'disable-everything' );
}
function disable_everything_removeitemsadminbar() {
	$checked = "";
	if (disable_everything_check_option ( 'removeitemsadminbar' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-removeitemsadminbar" name="disable_everything_settings[disable-everything-options-removeitemsadminbar]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Remove reduntant items from adminbar <em>(these items are removed: wp-logo,view-site,new-content,comments,updates,dashboard,appearance)</em>', 'disable-everything' );
}
function disable_everything_cleandashboard() {
	$checked = "";
	if (disable_everything_check_option ( 'cleandashboard' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-cleandashboard" name="disable_everything_settings[disable-everything-options-cleandashboard]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Clean up Dasboard from bloat <em>(improves preformance and saves valuable space)</em>', 'disable-everything' );
}
function disable_everything_emojis() {
	$checked = "";
	if (disable_everything_check_option ( 'emojis' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-emojis" name="disable_everything_settings[disable-everything-options-emojis]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Disable support for emojis in posts <em>(saves at least 1 file request and ~16kb)</em>', 'disable-everything' );
}
function disable_everything_embed() {
	$checked = "";
	if (disable_everything_check_option ( 'embed' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-embed" name="disable_everything_settings[disable-everything-options-embed]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Disable support for embedding objects in posts <em>(saves at least 1 file request and ~6kb)</em>', 'disable-everything' );
}
function disable_everything_dashicons() {
	$checked = "";
	if (disable_everything_check_option ( 'dashicons' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-dashicons" name="disable_everything_settings[disable-everything-options-dashicons]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Disable support for Dashicons <u>when not logged in</u> <em>(saves 1 file request and ~46kb)</em>', 'disable-everything' );
}
function disable_everything_heartbeat() {
	$checked = "";
	if (disable_everything_check_option ( 'heartbeat' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-heartbeat" name="disable_everything_settings[disable-everything-options-heartbeat]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Disable support for auto-save <u>when not editing a page/post</u> <em>(saves 1 file request and ~6kb)</em>', 'disable-everything' );
}
function disable_everything_xmlrpc() {
	$checked = "";
	if (disable_everything_check_option ( 'xmlrpc' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-xmlrpc" name="disable_everything_settings[disable-everything-options-xmlrpc]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Disable support for third-party application access <em>(such as mobile apps)</em>', 'disable-everything' );
}
function disable_everything_generator() {
	$checked = "";
	if (disable_everything_check_option ( 'generator' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-generator" name="disable_everything_settings[disable-everything-options-generator]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Disable the generator tag <em>(includes Wordpress version number)</em>', 'disable-everything' );
}
function disable_everything_manifest() {
	$checked = "";
	if (disable_everything_check_option ( 'manifest' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-manifest" name="disable_everything_settings[disable-everything-options-manifest]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Disable the Windows Live Writer manifest tag <em>(WLW was discontinued in Jan 2017)</em>', 'disable-everything' );
}
function disable_everything_rsdlink() {
	$checked = "";
	if (disable_everything_check_option ( 'rsdlink' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-rsdlink" name="disable_everything_settings[disable-everything-options-rsdlink]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Disable the Really Simple Discovery (RSD) tag <em>(this protocol never became popular)</em>', 'disable-everything' );
}
function disable_everything_shortlink() {
	$checked = "";
	if (disable_everything_check_option ( 'shortlink' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-shortlink" name="disable_everything_settings[disable-everything-options-shortlink]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Disable the Short Link tag <em>(search engines ignore this tag completely)</em>', 'disable-everything' );
}
function disable_everything_rssfeeds() {
	$checked = "";
	if (disable_everything_check_option ( 'rssfeeds' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-rssfeeds" name="disable_everything_settings[disable-everything-options-rssfeeds]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Disable the RSS feed links and disable the feeds <em>(will redirect to the page instead)</em>', 'disable-everything' );
}
function disable_everything_restapi() {
	$checked = "";
	if (disable_everything_check_option ( 'restapi' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-restapi" name="disable_everything_settings[disable-everything-options-restapi]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Disable the REST API links and disable the endpoints <u>when not on admin pages</u>', 'disable-everything' );
}
function disable_everything_blocks() {
	$checked = "";
	if (disable_everything_check_option ( 'blocks' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-blocks" name="disable_everything_settings[disable-everything-options-blocks]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Disable the Gutenberg blocks library if you are using Classic Editor <em>(saves 1 file request and ~29kb)</em>', 'disable-everything' );
}
function disable_everything_applicationpasswords() {
	$checked = "";
	if (disable_everything_check_option ( 'applicationpasswords' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-applicationpasswords" name="disable_everything_settings[disable-everything-options-applicationpasswords]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Completely disable the new Application Passwords functionality (added in WP version 5.6)', 'disable-everything' );
}
//TODO: Custom Function
/**
 * Removes required user's capabilities for core privacy tools by adding the
 * `do_not_allow` capability.
 *
 *  - Disables the feature pointer.
 *  - Removes the Privacy and Export/Erase Personal Data admin menu items.
 *  - Disables the privacy policy guide and update bubbles.
 *
 * @param string[] $caps    Array of the user's capabilities.
 * @param string   $cap     Capability name.
 * @return string[] Array of the user's capabilities.
 */
function disable_everything_disable_core_privacy_tools( $caps, $cap ) {
	switch ( $cap ) {
		case 'export_others_personal_data':
		case 'erase_others_personal_data':
		case 'manage_privacy_options':
			$caps[] = 'do_not_allow';
			break;
	}
	
	return $caps;
}

// disable the admin menu
function disable_everything_remove_site_health_menu() {
	
	remove_submenu_page( 'tools.php', 'site-health.php' );
	
}

// block site health page screen
function disable_everything_block_site_health_access() {
	
	if ( is_admin() ) {
		
		$screen = get_current_screen();
		
		// if screen id is site health
		if ( 'site-health' == $screen->id ) {
			wp_redirect( admin_url() );
			exit;
		}
		
	}
	
}

// Disable Remote Block Patterns
function disable_everything_disable_remote_patterns_filter() {
	return false;
}

//TODO: Check Field Function
function disable_everything_coreprivacytools() {
	$checked = "";
	if (disable_everything_check_option ( 'coreprivacytools' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-coreprivacytools" name="disable_everything_settings[disable-everything-options-coreprivacytools]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Disable the Core Privacy Tools', 'disable-everything' );
}
function disable_everything_sitehealth() {
	$checked = "";
	if (disable_everything_check_option ( 'sitehealth' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-sitehealth" name="disable_everything_settings[disable-everything-options-sitehealth]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Disable the Site Health page', 'disable-everything' );
}
function disable_everything_adjacentposts() {
	$checked = "";
	if (disable_everything_check_option ( 'adjacentposts' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-adjacentposts" name="disable_everything_settings[disable-everything-options-adjacentposts]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Remove the next and previous post links from the header', 'disable-everything' );
}
function disable_everything_version() {
	$checked = "";
	if (disable_everything_check_option ( 'version' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-version" name="disable_everything_settings[disable-everything-options-version]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Remove WordPress version var (?ver=) after styles and scripts.', 'disable-everything' );
}
function disable_everything_dnsprefetch() {
	$checked = "";
	if (disable_everything_check_option ( 'dnsprefetch' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-dnsprefetch" name="disable_everything_settings[disable-everything-options-dnsprefetch]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Removes dns-prefetch links from the header', 'disable-everything' );
}
function disable_everything_pdfthumbnails() {
	$checked = "";
	if (disable_everything_check_option ( 'pdfthumbnails' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-pdfthumbnails" name="disable_everything_settings[disable-everything-options-pdfthumbnails]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'This option disables PDF thumbnails.', 'disable-everything' );
}
function disable_everything_emptytrash() {
	$checked = "";
	if (disable_everything_check_option ( 'emptytrash' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-emptytrash" name="disable_everything_settings[disable-everything-options-emptytrash]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Shorten the time posts are kept in the trash, which is 30 days by default, to 1 week.', 'disable-everything' );
}
function disable_everything_pluginandthemeeditor() {
	$checked = "";
	if (disable_everything_check_option ( 'pluginandthemeeditor' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-pluginandthemeeditor" name="disable_everything_settings[disable-everything-options-pluginandthemeeditor]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Disables the plugins and theme editor.', 'disable-everything' );
}
function disable_everything_oembed() {
	$checked = "";
	if (disable_everything_check_option ( 'oembed' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-oembed" name="disable_everything_settings[disable-everything-options-oembed]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Remove oEmbed Scripts. Since WordPress 4.4, oEmbed is installed and available by default. If you do not need oEmbed, you can remove it.', 'disable-everything' );
}
function disable_everything_remoteblockpatterns() {
	$checked = "";
	if (disable_everything_check_option ( 'remoteblockpatterns' )) {
		$checked = " checked";
	}
	echo '<label><input id="disable-everything-options-remoteblockpatterns" name="disable_everything_settings[disable-everything-options-remoteblockpatterns]" type="checkbox" value="YES"' . $checked . '> ' . __ ( 'Disable Remote Block Patterns. Disable it if you want to improve pattern inserter loading performance or you have privacy concerns regarding loading remote asset.', 'disable-everything' );
}

// add actions
if (is_admin ()) {
	add_action ( 'admin_menu', 'disable_everything_menus' );
	add_action ( 'admin_init', 'disable_everything_settings' );
}

/* Add links to plugins page */

// show settings link
function disable_everything_links($links) {
	$links [] = sprintf ( '<a href="%s">%s</a>', admin_url ( 'options-general.php?page=disable-everything' ), __ ( 'Settings', 'disable-everything' ) );
	return $links;
}

// add actions
if (is_admin ()) {
	add_filter ( 'plugin_action_links_' . plugin_basename ( __FILE__ ), 'disable_everything_links' );
}
