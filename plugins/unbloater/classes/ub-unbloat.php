<?php

Namespace Unbloater;

defined( 'ABSPATH' ) || die();

class Unbloater_Unbloat {
	
	/**
	 * Class constructor
	 */
	public function __construct() {
        $this->do_the_unbloat();
	}
	
	/**
	 * Main unbloat method
	 */
	public function do_the_unbloat() {
		
		/******************************************************************
		********* CORE BACKEND ********************************************
		******************************************************************/
		
		if( Unbloater_Helper::is_option_activated( 'remove_update_available_notice' ) ) {
			add_action( 'admin_head', array( $this, 'remove_update_available_notice' ), 1 );
		}
		
		if( Unbloater_Helper::is_option_activated( 'disable_auto_updates_core' ) ) {
			defined( 'AUTOMATIC_UPDATER_DISABLED' ) || define( 'AUTOMATIC_UPDATER_DISABLED', true );
		}
		
		if( Unbloater_Helper::is_option_activated( 'disable_auto_updates_plugins' ) ) {
			add_filter( 'auto_update_plugin', '__return_false' );
			add_filter( 'plugins_auto_update_enabled', '__return_false' );
		}
		
		if( Unbloater_Helper::is_option_activated( 'disable_auto_updates_themes' ) ) {
			add_filter( 'auto_update_theme', '__return_false' );
			add_filter( 'themes_auto_update_enabled', '__return_false' );
		}
		
		if( Unbloater_Helper::is_option_activated( 'disable_core_upgrade_bundled_items' ) ) {
			defined( 'CORE_UPGRADE_SKIP_NEW_BUNDLED' ) || define( 'CORE_UPGRADE_SKIP_NEW_BUNDLED', true );
		}
		
		if( Unbloater_Helper::is_option_activated( 'disallow_file_edit' ) ) {
			defined( 'DISALLOW_FILE_EDIT' ) || define( 'DISALLOW_FILE_EDIT', true );
		}
		
		if( Unbloater_Helper::is_option_activated( 'limit_post_revisions' ) ) {
			defined( 'WP_POST_REVISIONS' ) || define( 'WP_POST_REVISIONS', 5 );
		}
		
		if( Unbloater_Helper::is_option_activated( 'limit_empty_trash_period' ) ) {
			defined( 'EMPTY_TRASH_DAYS' ) || define( 'EMPTY_TRASH_DAYS', 7 );
		}
		
		if( Unbloater_Helper::is_option_activated( 'limit_application_password_creation' ) ) {
			add_action( 'init', array( $this, 'limit_application_password_creation' ) );
		}
		
		if( Unbloater_Helper::is_option_activated( 'disable_application_passwords' ) ) {
			add_action( 'init', array( $this, 'disable_application_passwords' ) );
		}
		
		if( Unbloater_Helper::is_option_activated( 'disable_admin_email_confirmation' ) ) {
			add_filter( 'admin_email_check_interval', '__return_false' );
		}
		
		if( Unbloater_Helper::is_option_activated( 'disable_xmlrpc' ) ) {
			add_filter( 'xmlrpc_enabled', '__return_false' );
			add_filter( 'xmlrpc_methods', array( $this, 'disable_xmlrpc_methods' ) );
			remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
			if( Unbloater_Helper::is_wp_version_at_least( '4.4' ) ) {
				add_action( 'wp', array( $this, 'remove_x_pingback_header' ), 9999 );
			} else {
				add_filter( 'wp_headers', array( $this, 'disable_xmlrpc_headers' ) );
			}
		}
		
		if( Unbloater_Helper::is_option_activated( 'remove_admin_bar_wordpress_item' ) ) {
			add_action( 'admin_bar_menu', array( $this, 'remove_admin_bar_wordpress_item' ), 999 );
		}
		
		if( Unbloater_Helper::is_option_activated( 'remove_admin_footer' ) ) {
			add_filter( 'admin_footer_text', '__return_false' );
		}
		
		/******************************************************************
		********* CORE FRONTEND *******************************************
		******************************************************************/
		
		if( Unbloater_Helper::is_option_activated( 'remove_generator_tag' ) ) {
			remove_action( 'wp_head', 'wp_generator' );
		}
		
		if( Unbloater_Helper::is_option_activated( 'remove_script_style_version_parameter' ) ) {
			add_filter( 'style_loader_src', array( $this, 'remove_script_style_version_parameter' ), 9999 );
			add_filter( 'script_loader_src', array( $this, 'remove_script_style_version_parameter' ), 9999 );
		}
		
		if( Unbloater_Helper::is_option_activated( 'remove_wlw_manifest_link' ) ) {
			remove_action( 'wp_head', 'wlwmanifest_link' );
		}
		
		if( Unbloater_Helper::is_option_activated( 'remove_rsd_link' ) ) {
			remove_action( 'wp_head', 'rsd_link' );
		}
		
		if( Unbloater_Helper::is_option_activated( 'remove_shortlink' ) ) {
			remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
		}
		
		if( Unbloater_Helper::is_option_activated( 'remove_feed_generator_tag' ) ) {
			remove_action( 'app_head', 'the_generator' );
			remove_action( 'atom_head', 'the_generator' );
			remove_action( 'comments_atom_head', 'the_generator' );
			remove_action( 'commentsrss2_head', 'the_generator' );
			remove_action( 'rdf_header', 'the_generator' );
			remove_action( 'rss_head', 'the_generator' );
			remove_action( 'rss2_head', 'the_generator' );
			remove_action( 'opml_head', 'the_generator' );
		}
		
		if( Unbloater_Helper::is_option_activated( 'remove_feed_links' ) || Unbloater_Helper::is_option_activated( 'disable_feeds' ) ) {
			remove_action( 'wp_head', 'feed_links', 2 );
			remove_action( 'wp_head', 'feed_links_extra', 3 );
		}
		
		if( Unbloater_Helper::is_option_activated( 'disable_feeds' ) ) {
			add_action( 'do_feed', array( $this, 'disable_feed' ), 1 );
			add_action( 'do_feed_rdf', array( $this, 'disable_feed' ), 1 );
			add_action( 'do_feed_rss', array( $this, 'disable_feed' ), 1 );
			add_action( 'do_feed_rss2', array( $this, 'disable_feed' ), 1 );
			add_action( 'do_feed_atom', array( $this, 'disable_feed' ), 1 );
			add_action( 'do_feed_rss2_comments', array( $this, 'disable_feed' ), 1 );
			add_action( 'do_feed_atom_comments', array( $this, 'disable_feed' ), 1 );
		}
		
		if( Unbloater_Helper::is_option_activated( 'remove_wporg_dns_prefetch' ) ) {
			remove_action( 'wp_head', 'wp_resource_hints', 2 );
		}
		
		if( Unbloater_Helper::is_option_activated( 'remove_jquery_migrate' ) ) {
			add_action( 'wp_default_scripts', array( $this, 'remove_jquery_migrate' ) );
		}
		
		if( Unbloater_Helper::is_option_activated( 'disable_emojis' ) ) {
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_action( 'admin_print_styles', 'print_emoji_styles' );
			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
			remove_filter( 'embed_head', 'print_emoji_detection_script' );
			remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
			remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
			remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		}
		
		if( Unbloater_Helper::is_option_activated( 'optimize_comment_js_loading' ) ) {
			add_action( 'wp_print_scripts', array( $this, 'optimize_comment_js_loading' ), 100 );
		}
		
		if( Unbloater_Helper::is_option_activated( 'remove_recent_comments_style' ) ) {
			add_filter( 'show_recent_comments_widget_style', '__return_false' );
		}
		
		if( Unbloater_Helper::is_option_activated( 'disable_comment_hyperlinks' ) ) {
			remove_filter( 'comment_text', 'make_clickable', 9 );
		}
		
		if( Unbloater_Helper::is_option_activated( 'reduce_heartbeat_interval' ) ) {
			add_filter( 'heartbeat_settings', array( $this, 'reduce_heartbeat_interval' ) );
		}
		
		if( Unbloater_Helper::is_option_activated( 'normalize_favicon' ) ) {
			add_filter( 'get_site_icon_url', array( $this, 'normalize_favicon' ), 10, 3 );
		}
		
		if( Unbloater_Helper::is_option_activated( 'normalize_login_logo_url' ) ) {
			add_filter( 'login_headerurl', array( $this, 'normalize_login_logo_url' ) );
		}
		
		if( Unbloater_Helper::is_option_activated( 'normalize_login_logo_title' ) ) {
			add_filter( 'login_headertext', array( $this, 'normalize_login_logo_title' ) );
		}
		
		if( Unbloater_Helper::is_option_activated( 'disable_login_language_dropdown' ) ) {
			add_filter( 'login_display_language_dropdown', '__return_false' );
		}
		
		/******************************************************************
		********* BLOCK EDITOR / GUTENBERG ********************************
		******************************************************************/
		
		if( Unbloater_Helper::is_option_activated( 'block_editor_deactivate_block_directory' ) ) {
			remove_action( 'enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets' );
		}
		
		if( Unbloater_Helper::is_option_activated( 'block_editor_deactivate_core_block_patterns' ) ) {
			remove_theme_support( 'core-block-patterns' );
		}
		
		if( Unbloater_Helper::is_option_activated( 'block_editor_deactivate_template_editor' ) ) {
			remove_theme_support( 'block-templates' );
		}
		
		if( Unbloater_Helper::is_option_activated( 'block_editor_autoclose_welcome_guide' ) ) {
			add_action( 'enqueue_block_editor_assets', array( $this, 'block_editor_autoclose_welcome_guide' ) );
		}
		
		if( Unbloater_Helper::is_option_activated( 'block_editor_autoexit_fullscreen_mode' ) ) {
			add_action( 'enqueue_block_editor_assets', array( $this, 'block_editor_autoexit_fullscreen_mode' ) );
		}
		
		/******************************************************************
		********* ADVANCED CUSTOM FIELDS **********************************
		******************************************************************/
		
		if( Unbloater_Helper::is_option_activated( 'acf_hide_admin' ) ) {
			add_filter( 'acf/settings/show_admin', '__return_false' );
		}
		
		/******************************************************************
		********* AUTOPTIMIZE *********************************************
		******************************************************************/
		
		if( Unbloater_Helper::is_option_activated( 'autoptimize_remove_admin_bar_item' ) ) {
			add_filter( 'autoptimize_filter_toolbar_show', '__return_false' );
		}
		
		if( Unbloater_Helper::is_option_activated( 'autoptimize_remove_imgopt_nag' ) ) {
			add_filter( 'autoptimize_filter_main_imgopt_plug_notice', '__return_empty_string' );
		}
		
		/******************************************************************
		********* RANK MATH ***********************************************
		******************************************************************/
		
		if( Unbloater_Helper::is_option_activated( 'rankmath_remove_admin_bar_item' ) ) {
			add_action( 'admin_bar_menu', array( $this, 'rankmath_remove_admin_bar_item' ), 999 );
		}
		
		if( Unbloater_Helper::is_option_activated( 'rankmath_whitelabel' ) ) {
			add_action( 'rank_math/whitelabel', '__return_true' );
		}
		
		if( Unbloater_Helper::is_option_activated( 'rankmath_remove_sitemap_credit' ) ) {
			add_filter( 'rank_math/sitemap/remove_credit', '__return_true' );
		}
		
		if( Unbloater_Helper::is_option_activated( 'rankmath_remove_link_class' ) ) {
			add_filter( 'rank_math/link/remove_class', '__return_true' );
		}
		
		/******************************************************************
		********* SEARCHWP ************************************************
		******************************************************************/
		
		if( Unbloater_Helper::is_option_activated( 'searchwp_disable_stats_widget' ) ) {
			add_filter( 'searchwp\admin\dashboard_widgets\statistics', '__return_false' );
		}
			
		if( Unbloater_Helper::is_option_activated( 'searchwp_disable_stats_link' ) ) {
			add_filter( 'searchwp\options\dashboard_stats_link', '__return_false' );
		}
		
		if( Unbloater_Helper::is_option_activated( 'searchwp_remove_admin_bar_item' ) ) {
			add_filter( 'searchwp\admin_bar', '__return_false' );
		}
		
		if( Unbloater_Helper::is_option_activated( 'searchwp_move_menu_item_to_bottom' ) ) {
			add_filter( 'searchwp\admin_menu\position', array( $this, 'searchwp_move_menu_item_to_bottom' ) );
		}
		
		if( Unbloater_Helper::is_option_activated( 'searchwp_remove_menu_item' ) ) {
			add_filter( 'searchwp\options\settings_screen', '__return_false' );
		}
		
		/******************************************************************
		********* THE SEO FRAMEWORK ***************************************
		******************************************************************/
		
		if( Unbloater_Helper::is_option_activated( 'autodescription_remove_output_indicator' ) ) {
			add_filter( 'the_seo_framework_indicator', '__return_false' );
		}
		
		if( Unbloater_Helper::is_option_activated( 'autodescription_metabox_context_side' ) ) {
			add_filter( 'the_seo_framework_metabox_context', array( $this, 'autodescription_metabox_context_side' ) );
		}
		
		/******************************************************************
		********* WOOCOMMERCE *********************************************
		******************************************************************/
		
		if( Unbloater_Helper::is_option_activated( 'wc_helper_remove_connection_nag' ) ) {
			add_filter( 'woocommerce_helper_suppress_connect_notice', '__return_true' );
		}
		
		if( Unbloater_Helper::is_option_activated( 'wc_helper_remove_all_admin_nags' ) ) {
			add_filter( 'woocommerce_helper_suppress_admin_notices', '__return_true' );
		}
		
		if( Unbloater_Helper::is_option_activated( 'wc_remove_cart_fragments' ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'wc_remove_cart_fragments' ), 11 );
		}
		
		if( Unbloater_Helper::is_option_activated( 'wc_remove_skyverge_dashboard' ) ) {
			add_action( 'admin_menu', array( $this, 'wc_remove_skyverge_dashboard_menu' ), 99 );
			add_action( 'admin_enqueue_scripts', array( $this, 'wc_remove_skyverge_dashboard_script' ), 20 );
		}
		
		/******************************************************************
		********* YOAST SEO ***********************************************
		******************************************************************/
		
		if( Unbloater_Helper::is_option_activated( 'yoast_seo_remove_html_comments' ) ) {
			add_filter( 'wpseo_debug_markers', '__return_false' );
		}
		
		if( Unbloater_Helper::is_option_activated( 'yoast_seo_remove_admin_bar_item' ) ) {
			add_action( 'admin_bar_menu', array( $this, 'yoast_seo_remove_admin_bar_item' ), 999 );
		}
		
	}
	
	/******************************************************************
	********* CORE BACKEND ********************************************
	******************************************************************/
	
	public function remove_update_available_notice() {
		if( ! current_user_can( 'update_core' ) ) {
			remove_action( 'admin_notices', 'update_nag', 3 );
		}
	}
	
	public function disable_application_passwords() {
		add_filter( 'wp_is_application_passwords_available', '__return_false' );
	}
	
	public function limit_application_password_creation() {
		if( is_admin() && ! current_user_can( 'manage_options' ) ) {
			add_filter( 'wp_is_application_passwords_available', '__return_false' );
		}
	}
	
	public function disable_xmlrpc_headers( $headers ) {
		unset( $headers['X-Pingback'] );
		return $headers;
	}
	
	public function disable_xmlrpc_methods( $methods ) {
		unset( $methods['pingback.ping'] );
		unset( $methods['pingback.extensions.getPingbacks'] );
		return $methods;
	}
	
	public function remove_x_pingback_header() {
		header_remove( 'X-Pingback' );
	}
	
	public function remove_admin_bar_wordpress_item() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'wp-logo' );
	}
	
	/******************************************************************
	********* CORE FRONTEND *******************************************
	******************************************************************/
	
	public function remove_script_style_version_parameter( $src ) {
		if( is_admin() )
			return $src;
		return strpos( $src, 'ver=' ) ? remove_query_arg( 'ver', $src ) : $src;
	}
	
	public function disable_feed() {
		wp_safe_redirect( home_url() );
	}
	
	public function remove_jquery_migrate( $scripts ) {
		if( ! is_admin() && isset( $scripts->registered['jquery'] ) && ! empty( $scripts->registered['jquery']->deps ) ) {
			$scripts->registered['jquery']->deps = array_diff( $scripts->registered['jquery']->deps, array( 'jquery-migrate' ) );
		}
	}
	
	public function optimize_comment_js_loading() {
		if( is_singular() && comments_open() && get_comments_number() > 0 && get_option( 'thread_comments' ) === '1' ) {
			wp_enqueue_script( 'comment-reply' );
		} else {
			wp_dequeue_script( 'comment-reply' );
		}
	}
	
	public function reduce_heartbeat_interval( $settings ) {
		$settings['interval'] = 60;
		return $settings;
	}
	
	public function normalize_favicon( $url, $size, $blog_id ) {
		return includes_url( 'images/w-logo-blue-white-bg.png' ) == $url ? '' : $url;
	}
	
	public function normalize_login_logo_url() {
		return get_bloginfo( 'url' );
	}
	
	public function normalize_login_logo_title() {
		return get_bloginfo( 'name' );
	}
	
	/******************************************************************
	********* BLOCK EDITOR ********************************************
	******************************************************************/
	
	public function block_editor_autoclose_welcome_guide() {
		wp_add_inline_script( "wp-data", "window.addEventListener( 'DOMContentLoaded', function() {
			const selectPost = wp.data.select( 'core/edit-post' );
			const selectPreferences = wp.data.select( 'core/preferences' );
			const isWelcomeGuidePost = selectPost.isFeatureActive( 'welcomeGuide' );
			const isWelcomeGuideWidget = selectPreferences.get( 'core/edit-widgets', 'welcomeGuide' );
			if( isWelcomeGuideWidget ) {
				wp.data.dispatch( 'core/preferences' ).toggle( 'core/edit-widgets', 'welcomeGuide' );
			}
			if( isWelcomeGuidePost ) {
				wp.data.dispatch( 'core/edit-post' ).toggleFeature( 'welcomeGuide' );
			}
		} );" );
	}
	
	public function block_editor_autoexit_fullscreen_mode() {
		wp_add_inline_script( "wp-data", "window.addEventListener( 'DOMContentLoaded', function() {
			const isFullscreenMode = wp.data.select( 'core/edit-post' ).isFeatureActive( 'fullscreenMode' );
			if( isFullscreenMode ) {
				wp.data.dispatch( 'core/edit-post' ).toggleFeature( 'fullscreenMode' );
			}
		} );" );
	}
	
	/******************************************************************
	********* RANK MATH ***********************************************
	******************************************************************/
	
	public function rankmath_remove_admin_bar_item() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'rank-math' );
	}
	
	/******************************************************************
	********* SEARCHWP ************************************************
	******************************************************************/
	
	public function searchwp_move_menu_item_to_bottom() {
		return 98;
	}
	
	/******************************************************************
	********* THE SEO FRAMEWORK ***************************************
	******************************************************************/
	
	public function autodescription_metabox_context_side( $context ) {
		return 'side';
	}
	
	/******************************************************************
	********* WOOCOMMERCE *********************************************
	******************************************************************/
	
	public function wc_remove_cart_fragments() { 
		wp_dequeue_script( 'wc-cart-fragments' ); 
	}
	
	public function wc_remove_skyverge_dashboard_menu() {
		remove_menu_page( 'skyverge' );
	}
	
	public function wc_remove_skyverge_dashboard_script() {
		wp_dequeue_style( 'sv-wordpress-plugin-admin-menus' );
	}
	
	/******************************************************************
	********* WOOCOMMERCE *********************************************
	******************************************************************/
	
	public function yoast_seo_remove_admin_bar_item() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'wpseo-menu' );
	}
	
}
