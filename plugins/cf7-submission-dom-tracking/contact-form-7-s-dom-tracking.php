<?php

/*
Plugin Name: Submission DOM tracking for Contact Form 7
Description: The "on_sent_ok" and its sibling setting "on_submit" of the Contact Form 7 plugin are deprecated and scheduled to be abolished by the end of 2017. The recommended alternative to on_sent_ok is using DOM events (More info: <a href="https://contactform7.com/2017/06/07/on-sent-ok-is-deprecated/" target="_blank">on_sent_ok Is Deprecated</a>). This plugin helps to set these DOM events to track form submissions (Google Analytics event, Google Analytics page view and Facebook pixel). You can also hide the form after correct submission and deregister the style and JavaScript on pages without forms.
Version: 2.0
Author: Apasionados
Author URI: https://apasionados.es/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: cf7sdome
Domain Path: /languages
*/

/*
 * Custom Global Variables
 */
function apa_cf7sdomt_f_global_vars() {
	global $apacf7sdomtglobalforms; // Array with pages that contain a contact form. If empty we asume that all pages contain a contact form. Example of content: array( 69, 'contacto', 'Contactar' );
	if ( ( empty ( get_option( 'apa_cf7sdomt_pages_with_contact_forms' ) ) ) || ( get_option( 'apa_cf7sdomt_pages_with_contact_forms' ) == false ) ) {
		$apacf7sdomtglobalforms = array();
	} else {
		$apacf7sdomtglobalforms = str_replace(', ', ',', get_option( 'apa_cf7sdomt_pages_with_contact_forms' ) );
		$apacf7sdomtglobalforms = explode(',', $apacf7sdomtglobalforms );
	}
	
	global $apacf7sdomtglobal;
	$apacf7sdomtglobal = array(
		'slug'  => basename( get_permalink() ),
		'home'      => $_SERVER["REQUEST_URI"],
	);
	if ( $GLOBALS['apacf7sdomtglobal']['home']  == '/' ) {
		$apacf7sdomtglobal = array(	
			'slug' => '/',
		);
	}
}

/**
 * Deregister Contact Form 7 Styles on pages that have no forms (defined in $apa_cf7sdomt_contact_url_array)
 */
if ( get_option( 'apa_cf7sdomt_deregister_styles' ) === 'show' ) {
	add_action( 'wp_print_styles', 'apa_cf7sdomt_f_deregister_styles', 100 );
}
function apa_cf7sdomt_f_deregister_styles() {
    apa_cf7sdomt_f_global_vars();
	//if ( ! in_array( $GLOBALS['apacf7sdomtglobal']['slug'], $GLOBALS['apacf7sdomtglobalforms'] ) ) {
	if ( ( ! in_array( $GLOBALS['apacf7sdomtglobal']['slug'], $GLOBALS['apacf7sdomtglobalforms'] ) ) && ( !empty ( $GLOBALS['apacf7sdomtglobalforms'] ) ) ) {
        wp_deregister_style( 'contact-form-7' );
    }
}

/**
 * Deregister Contact Form 7 JavaScript on pages that have no forms (defined in $apa_cf7sdomt_contact_url_array)
 */
if ( get_option( 'apa_cf7sdomt_deregister_javascript' ) === 'show' ) {
	add_action( 'wp_print_scripts', 'apa_cf7sdomt_f_deregister_javascript', 100 );
}
function apa_cf7sdomt_f_deregister_javascript() {
    apa_cf7sdomt_f_global_vars();
	if ( ( ! in_array( $GLOBALS['apacf7sdomtglobal']['slug'], $GLOBALS['apacf7sdomtglobalforms'] ) ) && ( !empty ( $GLOBALS['apacf7sdomtglobalforms'] ) ) ) {
        wp_deregister_script( 'contact-form-7' );
    }
}

/**
 * Add Javascript tracking code to the footer on the pages with Contact Forms.
 */
if ( ! function_exists( 'apa_cf7sdomt_f_wp_footer' ) ) {
	add_action( 'wp_footer', 'apa_cf7sdomt_f_wp_footer' );
	function apa_cf7sdomt_f_wp_footer() {
		apa_cf7sdomt_f_global_vars();
		if ( ( in_array( $GLOBALS['apacf7sdomtglobal']['slug'], $GLOBALS['apacf7sdomtglobalforms'] ) ) || ( empty ( $GLOBALS['apacf7sdomtglobalforms'] ) ) ) {
			if ( ( function_exists( 'MonsterInsights' ) ) || defined( 'MONSTERINSIGHTS_VERSION' ) ) {
				$apa_cf7sdomt_Tracker = '__gaTracker';
			} else {
				$apa_cf7sdomt_Tracker = 'ga';
			}
			if ( ( NULL === get_option( 'apa_cf7sdomt_ga_page_view_url', NULL ) ) || ( get_option( 'apa_cf7sdomt_ga_page_view_url' ) == '' ) ) {
				//$apa_cf7sdomt_ga_page_view_url_js = '/contact-form-7-ok/';
				if ( empty($GLOBALS['apacf7sdomtglobal']['home']) ) { $GLOBALS['apacf7sdomtglobal']['home'] = '/'; }
				$apa_cf7sdomt_ga_page_view_url_js = esc_js( $GLOBALS['apacf7sdomtglobal']['home'] . 'ok/' ) ;
			} else {
				$apa_cf7sdomt_ga_page_view_url_js = get_option( 'apa_cf7sdomt_ga_page_view_url' );
			}
			if ( ( NULL === get_option( 'apa_cf7sdomt_ga_event_category', NULL ) ) || ( get_option( 'apa_cf7sdomt_ga_event_category' ) == '' ) ) {
				$apa_cf7sdomt_ga_event_category_js = 'Contact form 7';
			} else {
				$apa_cf7sdomt_ga_event_category_js = get_option( 'apa_cf7sdomt_ga_event_category' );
			}
			if ( ( NULL === get_option( 'apa_cf7sdomt_ga_event_action', NULL ) ) || ( get_option( 'apa_cf7sdomt_ga_event_action' ) == '' ) ) {
				$apa_cf7sdomt_ga_event_action_js = 'sent';
			} else {
				$apa_cf7sdomt_ga_event_action_js = get_option( 'apa_cf7sdomt_ga_event_action' );
			}			
			?>
			<script type="text/javascript">
			document.addEventListener( 'wpcf7mailsent', function( event ) {
				<?php if ( get_option( 'apa_cf7sdomt_hide_form' ) === 'show') { ?>
							document.getElementById('hidecontactform7contactform').style.display = 'none';
				<?php } ?>			
				<?php if ( get_option( 'apa_cf7sdomt_ga_event' ) === 'show') { ?>
					<?php if ( $apa_cf7sdomt_Tracker == '__gaTracker' ) { ?>
					__gaTracker( 'send', 'event', '<?php echo $apa_cf7sdomt_ga_event_category_js; ?>', '<?php echo $apa_cf7sdomt_ga_event_action_js; ?>', '<?php echo esc_js( $GLOBALS['apacf7sdomtglobal']['home'] ); ?>' );
					<?php } else { ?>
					ga( 'send', 'event', '<?php echo $apa_cf7sdomt_ga_event_category_js; ?>', '<?php echo $apa_cf7sdomt_ga_event_action_js; ?>', '<?php echo esc_js( $GLOBALS['apacf7sdomtglobal']['home'] ); ?>' );
					<?php } ?>				
				<?php } ?>
				<?php if ( get_option( 'apa_cf7sdomt_ga_page_view' ) === 'show') { ?>
					<?php if ( $apa_cf7sdomt_Tracker == '__gaTracker' ) { ?>
					__gaTracker( 'send', 'pageview', '<?php echo $apa_cf7sdomt_ga_page_view_url_js; ?>' );
					<?php } else { ?>
					ga( 'send', 'pageview', '<?php echo $apa_cf7sdomt_ga_page_view_url_js; ?>' );
					<?php } ?>				
				<?php } ?>
				<?php if ( get_option( 'apa_cf7sdomt_fb_pixel_lead' ) === 'show') { ?>
					fbq('track', 'Lead');
				<?php } ?>
			}, false );
			</script>
		<?php
		}
	}
}
	
/**
 * Set plugin Page links for the plugins settings page
 */
function apa_cf7sdomt_f_plugin_settings_link($links) {
	unset($links['edit']);
	$support_link   = '<a target="_blank" href="https://apasionados.es/contacto/">' . __('Support', 'apa-cf7sdomt') . '</a>';
	$settings_link = '<a href="admin.php?page=cf7sdome_settings">' . __('Settings', 'apa-cf7sdomt') . '</a>';
	array_unshift( $links, $support_link );
	array_unshift( $links, $settings_link );
	return $links; 
}
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'apa_cf7sdomt_f_plugin_settings_link' );

/**
 * Do some check on plugin activation
  */
function apa_cf7sdomt_f_activation() {
	$plugin_data = get_plugin_data( __FILE__ );
	$plugin_version = $plugin_data['Version'];
	$plugin_name = $plugin_data['Name'];
	if ( version_compare( PHP_VERSION, '5.5', '<' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( '<h1>' . __('Could not activate plugin: PHP version error', 'apa-cf7sdomt' ) . '</h1><h2>PLUGIN: <i>' . $plugin_name . ' ' . $plugin_version . '</i></h2><p><strong>' . __('You are using PHP version', 'apa-cf7sdomt' ) . ' ' . PHP_VERSION . '</strong>. ' . __( 'This plugin has been tested with PHP versions 5.5 and greater.', 'apa-cf7sdomt' ) . '</p><p>' . __('WordPress itself <a href="https://wordpress.org/about/requirements/" target="_blank">recommends using PHP version 7 or greater</a>. Please upgrade your PHP version or contact your Server administrator.', 'apa-cf7sdomt' ) . '</p>', __('Could not activate plugin: PHP version error', 'apa-cf7sdomt' ), array( 'back_link' => true ) );
	}
	if ( !is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
		wp_die( '<h1>' . __('Could not activate plugin: CONTACT FORM 7 plugin is not installed', 'apa-cf7sdomt' ) . '</h1><h2>PLUGIN: <i>' . $plugin_name . ' ' . $plugin_version . '</i></h2><p><strong>' . __('In order to use this plugin the <a href="https://wordpress.org/plugins/contact-form-7/" target="_blank">Contact Form 7</a> plugin must be installed and active.', 'apa-cf7sdomt' ), __('Could not activate plugin: CONTACT FORM 7 plugin is not installed', 'apa-cf7sdomt' ), array( 'back_link' => true ) );		
	}
	if ( NULL === get_option( 'apa_cf7sdomt_ga_page_view', NULL ) ) {
		update_option('apa_cf7sdomt_ga_page_view', 'noshow');
	}
	if ( NULL === get_option( 'apa_cf7sdomt_ga_event', NULL ) ) {
		update_option('apa_cf7sdomt_ga_event', 'noshow');
	}
	if ( NULL === get_option( 'apa_cf7sdomt_fb_pixel_lead', NULL ) ) {
		update_option('apa_cf7sdomt_fb_pixel_lead', 'noshow');
	}
	if ( NULL === get_option( 'apa_cf7sdomt_hide_form', NULL ) ) {
		update_option('apa_cf7sdomt_hide_form', 'noshow');
	}	
	if ( NULL === get_option( 'apa_cf7sdomt_deregister_styles', NULL ) ) {
		update_option('apa_cf7sdomt_deregister_styles', 'noshow');
	}
	if ( NULL === get_option( 'apa_cf7sdomt_deregister_javascript', NULL ) ) {
		update_option('apa_cf7sdomt_deregister_javascript', 'noshow');
	}	
	add_action( 'admin_init', 'apa_cf7sdomt_f_register_settings' );
}
register_activation_hook( __FILE__, 'apa_cf7sdomt_f_activation' );

/**
 * Delete options on plugin uninstall 
 */
function apa_cf7sdomt_f_uninstall() {
	delete_option( 'apa_cf7sdomt_pages_with_contact_forms' );
	delete_option( 'apa_cf7sdomt_ga_page_view' );
	delete_option( 'apa_cf7sdomt_ga_event' );
	delete_option( 'apa_cf7sdomt_fb_pixel_lead' );
	delete_option( 'apa_cf7sdomt_hide_form' );
	delete_option( 'apa_cf7sdomt_deregister_styles' );
	delete_option( 'apa_cf7sdomt_deregister_javascript' );
	delete_option( 'apa_cf7sdomt_ga_page_view_url' );
	delete_option( 'apa_cf7sdomt_ga_event_category'  );
	delete_option( 'apa_cf7sdomt_ga_event_action' );
}
register_uninstall_hook( __FILE__, 'apa_cf7sdomt_f_uninstall' );

/**
 * Add menu to Contact Form 7 menu if Contact Form 7 is active 
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
	// add menu under contact form 7 menu
	add_action( 'admin_menu', 'apa_cf7sdomt_f_admin_menu', 20 );
	function apa_cf7sdomt_f_admin_menu() {
		$addnew = add_submenu_page( 'wpcf7',
			__( 'DOM Tracking', 'contact-form-7' ),
			__( 'DOM Tracking', 'contact-form-7' ),
			'wpcf7_edit_contact_forms', 'cf7sdome_settings',
			'apa_cf7sdomt_f_include_settings_page' );
	}
	add_action( 'admin_init', 'apa_cf7sdomt_f_register_settings' );
}
function apa_cf7sdomt_f_include_settings_page(){
    include(plugin_dir_path(__FILE__) . 'contact-form-7-s-dom-tracking-settings.php');
}

/**
 * Register Options
 */
function apa_cf7sdomt_f_register_settings() {
	register_setting( 'apa-cf7sdomt-settings-group', 'apa_cf7sdomt_pages_with_contact_forms', 'apa_cf7sdomt_sanitize_input' );
	register_setting( 'apa-cf7sdomt-settings-group', 'apa_cf7sdomt_ga_page_view' );
	register_setting( 'apa-cf7sdomt-settings-group', 'apa_cf7sdomt_ga_event' );
	register_setting( 'apa-cf7sdomt-settings-group', 'apa_cf7sdomt_fb_pixel_lead' );
	register_setting( 'apa-cf7sdomt-settings-group', 'apa_cf7sdomt_hide_form' );
	register_setting( 'apa-cf7sdomt-settings-group', 'apa_cf7sdomt_deregister_styles' );
	register_setting( 'apa-cf7sdomt-settings-group', 'apa_cf7sdomt_deregister_javascript' );
	register_setting( 'apa-cf7sdomt-settings-group', 'apa_cf7sdomt_ga_page_view_url', 'apa_cf7sdomt_sanitize_input' );
	register_setting( 'apa-cf7sdomt-settings-group', 'apa_cf7sdomt_ga_event_category', 'apa_cf7sdomt_sanitize_input' );
	register_setting( 'apa-cf7sdomt-settings-group', 'apa_cf7sdomt_ga_event_action', 'apa_cf7sdomt_sanitize_input' );
}
function apa_cf7sdomt_sanitize_input($apa_cf7sdomt_clean_code_admin_form) {
	$apa_cf7sdomt_clean_code_admin_form = sanitize_text_field( $apa_cf7sdomt_clean_code_admin_form );
	return $apa_cf7sdomt_clean_code_admin_form;
}

/**
 * Read translations.
 */
function apa_cf7sdomt_f_init() {
 load_plugin_textdomain( 'apa-cf7sdomt', false,  dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action('plugins_loaded', 'apa_cf7sdomt_f_init');

?>