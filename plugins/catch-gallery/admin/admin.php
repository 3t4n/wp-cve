<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to add the admin-facing aspects of the plugin.
 *
 * @link       catchplugins.com
 * @since      1.0.0
 *
 * @package    Catch_Gallery
 * @subpackage Catch_Gallery/admin
 */

if ( ! function_exists( 'catch_gallery_add_plugin_settings_menu' ) ) :
function catch_gallery_add_plugin_settings_menu() {
	add_menu_page(
		esc_html__( 'Catch Gallery', 'catch-gallery' ), //page title
		esc_html__( 'Catch Gallery', 'catch-gallery' ), //menu title
		'edit_posts', //capability needed
		'catch-gallery', //menu slug (and page query url)
		'catch_gallery_settings',
		'dashicons-format-gallery',
		'99.01564'
	);
}
endif; // catch_gallery_add_plugin_settings_menu
add_action( 'admin_menu', 'catch_gallery_add_plugin_settings_menu' );


if ( ! function_exists( 'catch_gallery_settings' ) ) :
function catch_gallery_settings() {
	$child_theme = false;
	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'catch-gallery' ) );
	}

	require_once plugin_dir_path( __FILE__ ) . 'catch-gallery-display.php';
}
endif; // catch_gallery_settings

if ( ! function_exists( 'catch_gallery_enqueue_styles' ) ) :
	/**
	 * Enqueue Admin CSS
	 */
	function catch_gallery_enqueue_styles() {
		if( isset( $_GET['page'] ) && 'catch-gallery' == $_GET['page'] ) {
			wp_enqueue_style( 'catch-gallery-dashboard', plugin_dir_url( __FILE__ ) . 'css/admin-dashboard.css', array(), CATCH_GALLERY_VERSION, 'all' );

			wp_enqueue_script( 'minHeight', plugin_dir_url( __FILE__ ) . 'js/jquery.matchHeight.min.js', array( 'jquery' ), CATCH_GALLERY_VERSION, false );

			wp_enqueue_script( 'catch-gallery-dashboard', plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery','jquery-ui-tooltip' ), CATCH_GALLERY_VERSION, false );
		}
	}
endif; // catch_gallery_enqueue_styles
add_action( 'admin_enqueue_scripts', 'catch_gallery_enqueue_styles' );

if( ! function_exists( 'catch_gallery_register_settings' ) ):
/**
 * Catch gallery: register_settings
 * Catch gallery Register Settings
 */
function catch_gallery_register_settings() {
	register_setting(
		'catch-gallery-group',
		'catch_gallery_options',
		'catch_gallery_sanitize_callback'
	);
}
endif;
add_action( 'admin_init', 'catch_gallery_register_settings' );

if( ! function_exists( 'catch_gallery_sanitize_checkbox' ) ):
function catch_gallery_sanitize_checkbox( $checked ) {
	// Boolean check.
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}
endif;

if( ! function_exists( 'catch_gallery_sanitize_callback' ) ):
/**
 *Catch gallery: sanitize_callback
 * Catch gallery Sanitization function callback
 *
 * @param array $input Input data for sanitization.
 */
function catch_gallery_sanitize_callback( $input ) {
	$defaults = catch_gallery_default_options();

	if ( isset( $input['reset'] ) && $input['reset'] ) {
		//If reset, restore defaults
		return $defaults;
	}

	// Verify the nonce before proceeding.
    if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    	|| ( ! isset( $_POST['catch_gallery_nounce'] )
    	|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['catch_gallery_nounce'] ) ), basename( __FILE__ ) ) )
    	|| ( ! check_admin_referer( basename( __FILE__ ), 'catch_gallery_nounce' ) ) ) {
    	if ( $input ) {

			if ( isset( $input['carousel_enable'] ) && $input['carousel_enable'] ) {
				$input['carousel_enable'] = catch_gallery_sanitize_checkbox( $input['carousel_enable'] );
			}

			if ( isset( $input['carousel_background_color'] ) && $input['carousel_background_color'] ) {
				$input['carousel_background_color'] = sanitize_key( $input['carousel_background_color'] );
			}

			if ( isset( $input['carousel_display_exif'] ) && $input['carousel_display_exif'] ) {
				$input['carousel_display_exif'] = catch_gallery_sanitize_checkbox( $input['carousel_display_exif'] );
			}

			if ( isset( $input['comments_display'] ) && $input['comments_display'] ) {
				$input['comments_display'] = catch_gallery_sanitize_checkbox( $input['comments_display'] );
			}

			if ( isset( $input['fullsize_display'] ) && $input['fullsize_display'] ) {
				$input['fullsize_display'] = catch_gallery_sanitize_checkbox( $input['fullsize_display'] );
			}
		}

		return $input;
    } // End if().
    return 'Invalid Nonce';
}
endif;

if ( ! function_exists( 'catch_gallery_action_links' ) ) :
/**
 * Catch_IDs: catch_gallery_action_links
 * Catch_IDs Settings Link function callback
 *
 * @param arrray $links Link url.
 *
 * @param arrray $file File name.
 */
function catch_gallery_action_links( $links, $file ) {
	if ( $file === 'catch-gallery/catch-gallery.php' ) {
		$settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=catch-gallery' ) ) . '">' . esc_html__( 'Settings', 'catch-gallery' ) . '</a>';

		array_unshift( $links, $settings_link );
	}
	return $links;
}
endif; // catch_gallery_action_links
add_filter( 'plugin_action_links', 'catch_gallery_action_links', 10, 2 );