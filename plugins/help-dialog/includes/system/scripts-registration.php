<?php

/**  Register JS and CSS files  */

/**
 * FRONT-END pages using our plugin features
 */
function ephd_load_public_resources() {

	ephd_register_public_resources();
	
	ephd_enqueue_public_resources();
}

add_action( 'ephd_enqueue_help_dialog_resources', 'ephd_load_public_resources' );

/**
 * Register for FRONT-END pages using our plugin features
 */
function ephd_register_public_resources() {

	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	wp_register_style( 'ephd-public-styles', Echo_Help_Dialog::$plugin_url . 'css/public-styles' . $suffix . '.css', array(), Echo_Help_Dialog::$version );

	wp_register_style( 'ephd-user-defined-values', false, array(), Echo_Help_Dialog::$version );

	if ( is_rtl() ) {
		wp_register_style( 'ephd-public-styles-rtl', Echo_Help_Dialog::$plugin_url . 'css/public-styles-rtl' . $suffix . '.css', array(), Echo_Help_Dialog::$version );
	}

	wp_register_style( 'ephd-icon-fonts', Echo_Help_Dialog::$plugin_url . 'css/ephd-icon-fonts' . $suffix . '.css', array(), Echo_Help_Dialog::$version );
	wp_register_script( 'ephd-js-cookie', Echo_Help_Dialog::$plugin_url . 'js/lib/js-cookie' . $suffix . '.js', array(), Echo_Help_Dialog::$version );
	wp_register_script( 'ephd-help-dialog-scripts', Echo_Help_Dialog::$plugin_url . 'js/public-help-dialog' . $suffix . '.js', array('jquery', 'ephd-js-cookie'), Echo_Help_Dialog::$version );

	wp_localize_script( 'ephd-help-dialog-scripts', 'ephd_help_dialog_vars', ephd_get_common_admin_script_vars() );
}

/**
 * Queue for FRONT-END pages using our plugin features
 */
function ephd_enqueue_public_resources() {
	wp_enqueue_style( 'ephd-public-styles' );

	wp_enqueue_style( 'ephd-user-defined-values' );
	
	if ( is_rtl() ) {
		wp_enqueue_style( 'ephd-public-styles-rtl' );
	}

	EPHD_Help_Dialog_View::insert_widget_inline_styles();
}

function ephd_enqueue_help_dialog() {
	wp_enqueue_script( 'ephd-help-dialog-scripts' );
}
add_action( 'ephd_enqueue_help_dialog_scripts', 'ephd_enqueue_help_dialog' );


/**************  Admin Pages  *****************/

add_action( 'admin_enqueue_scripts', 'ephd_register_public_resources' );

/**
 * Output the admin-icon.css file for logged in users only.
 * This is to show Help Dialog Icons in the:
 *  - Top Admin Bar ( Help Dialog Icon )
 *  - Left Admin Sidebar ( Help Dialog Icon )
 * For more details, see admin_icon.scss comments.
 */
function ephd_enqueue_admin_icon_resources() {
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_enqueue_style( 'ephd-admin-icon-style', Echo_Help_Dialog::$plugin_url . 'css/admin-icon' . $suffix . '.css' );
}
add_action( 'admin_enqueue_scripts','ephd_enqueue_admin_icon_resources' );

/**
 * ADMIN-PLUGIN MENU PAGES (Plugin settings, reports, lists etc.)
 */
function ephd_load_admin_plugin_pages_resources() {

	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	wp_enqueue_style( 'ephd-admin-plugin-pages-styles', Echo_Help_Dialog::$plugin_url . 'css/admin-plugin-pages' . $suffix . '.css', array(), Echo_Help_Dialog::$version );
	
	if ( is_rtl() ) {
		wp_enqueue_style( 'ephd-admin-plugin-pages-rtl', Echo_Help_Dialog::$plugin_url . 'css/admin-plugin-pages-rtl' . $suffix . '.css', array(), Echo_Help_Dialog::$version );
	}
	
	wp_enqueue_style( 'wp-color-picker' ); //Color picker

	wp_enqueue_script( 'ephd-admin-plugin-pages-scripts', Echo_Help_Dialog::$plugin_url . 'js/admin-plugin-pages' . $suffix . '.js',
					array('jquery', 'jquery-ui-core','jquery-ui-dialog','jquery-effects-core','jquery-effects-bounce', 'jquery-ui-sortable'), Echo_Help_Dialog::$version );
	wp_localize_script( 'ephd-admin-plugin-pages-scripts', 'ephd_vars', ephd_get_common_admin_script_vars() );

	wp_localize_script( 'ephd-admin-plugin-pages-scripts', 'ephd_help_dialog_vars', [
		'nonce' => wp_create_nonce( "_wpnonce_ephd_ajax_action" ),
	] );

	// used by WordPress color picker  ( wpColorPicker() )
	wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n',
			array(
				'clear'            =>   esc_html__( 'Reset', 'help-dialog' ),
				'clearAriaLabel'   =>   esc_html__( 'Reset color', 'help-dialog' ),
				'defaultString'    =>   esc_html__( 'Default', 'help-dialog' ),
				'defaultAriaLabel' =>   esc_html__( 'Select default color', 'help-dialog' ),
				'pick'             =>   '',
				'defaultLabel'     =>   esc_html__( 'Color value', 'help-dialog' ),
			));
	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_style( 'wp-jquery-ui-dialog' );
}

// Help Dialog Configuration page
function ephd_load_admin_help_dialog_config_script() {
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	// For Help Dialog preview
	ephd_register_public_resources();

	wp_enqueue_style( 'ephd-public-styles' );

	if ( is_rtl() ) {
		wp_enqueue_style( 'ephd-public-styles-rtl' );
	}

	wp_enqueue_script( 'ephd-admin-help-dialog-config-scripts', Echo_Help_Dialog::$plugin_url . 'js/admin-help-dialog-config' . $suffix . '.js', array('jquery'), Echo_Help_Dialog::$version );
	wp_localize_script( 'ephd-admin-help-dialog-config-scripts', 'ephd_help_dialog_vars', [
		'nonce' => wp_create_nonce( "_wpnonce_ephd_ajax_action" ),
	] );
}

// Help Dialog Analytics page
function ephd_load_admin_help_dialog_analytics_script() {
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	// For Help Dialog preview
	ephd_register_public_resources();

	wp_enqueue_style( 'ephd-public-styles' );

	if ( is_rtl() ) {
		wp_enqueue_style( 'ephd-public-styles-rtl' );
	}

	wp_enqueue_script( 'ephd-admin-help-dialog-analytics-scripts', Echo_Help_Dialog::$plugin_url . 'js/admin-help-dialog-analytics' . $suffix . '.js', array('jquery'), Echo_Help_Dialog::$version );
	wp_localize_script( 'ephd-admin-help-dialog-analytics-scripts', 'ephd_help_dialog_vars', [
		'nonce' => wp_create_nonce( "_wpnonce_ephd_ajax_action" ),
	] );
}

// Help Dialog Widgets page
function ephd_load_admin_help_dialog_widgets_script() {
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	// For Help Dialog preview
	ephd_register_public_resources();

	wp_enqueue_style( 'ephd-public-styles' );

	if ( is_rtl() ) {
		wp_enqueue_style( 'ephd-public-styles-rtl' );
	}

	EPHD_Help_Dialog_View::insert_widget_inline_styles( [], [], false, true );

	// Help Dialog scripts
	wp_enqueue_script( 'ephd-help-dialog-scripts' );

	// Widgets admin page
	wp_enqueue_script( 'ephd-admin-help-dialog-widgets-scripts', Echo_Help_Dialog::$plugin_url . 'js/admin-help-dialog-widgets' . $suffix . '.js', array('jquery'), Echo_Help_Dialog::$version );
	wp_localize_script( 'ephd-admin-help-dialog-widgets-scripts', 'ephd_help_dialog_vars', ephd_get_common_admin_script_vars() );
}

// Help Dialog FAQs/Articles page
function ephd_load_admin_help_dialog_faqs_articles_script() {
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	// For Help Dialog preview
	ephd_register_public_resources();

	wp_enqueue_style( 'ephd-public-styles' );

	if ( is_rtl() ) {
		wp_enqueue_style( 'ephd-public-styles-rtl' );
	}

	EPHD_Help_Dialog_View::insert_widget_inline_styles( [], [], false, true );

	// Help Dialog scripts
	wp_enqueue_script( 'ephd-help-dialog-scripts' );

	// FAQs/Articles admin page
	wp_enqueue_script( 'ephd-admin-help-dialog-faqs-scripts', Echo_Help_Dialog::$plugin_url . 'js/admin-help-dialog-faqs' . $suffix . '.js', array('jquery', 'jquery-ui-core','jquery-ui-dialog','jquery-effects-core','jquery-effects-bounce', 'jquery-ui-sortable'), Echo_Help_Dialog::$version );
	wp_localize_script( 'ephd-admin-help-dialog-faqs-scripts', 'ephd_help_dialog_vars', ephd_get_common_admin_script_vars() );
}

// Help Dialog Contact Form page
function ephd_load_admin_help_dialog_contact_form_script() {
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_enqueue_script( 'ephd-admin-help-dialog-contact-form-scripts', Echo_Help_Dialog::$plugin_url . 'js/admin-help-dialog-contact-form' . $suffix . '.js', array('jquery'), Echo_Help_Dialog::$version );
	wp_localize_script( 'ephd-admin-help-dialog-contact-form-scripts', 'ephd_help_dialog_vars', ephd_get_common_admin_script_vars() );
}

function ephd_get_common_admin_script_vars() {
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	return array(
		'ajaxurl'                   => admin_url( 'admin-ajax.php', 'relative' ),
		'msg_try_again'             => esc_html__( 'Please try again later.', 'help-dialog' ),
		'error_occurred'            => esc_html__( 'Error occurred', 'help-dialog' ) . ' (11)',
		'not_saved'                 => esc_html__( 'Error occurred', 'help-dialog' ) . ' (12)',
		'unknown_error'             => esc_html__( 'Unknown error', 'help-dialog' ) . ' (13)',
		'reload_try_again'          => esc_html__( 'Please reload the page and try again.', 'help-dialog' ),
		'save_config'               => esc_html__( 'Saving configuration', 'help-dialog' ),
		'input_required'            => esc_html__( 'Input is required', 'help-dialog' ),
		'sending_feedback'          => esc_html__('Sending feedback', 'help-dialog' ),
		'changing_debug'            => esc_html__('Changing debug', 'help-dialog' ),
		'widget_name_required'      => esc_html__( 'Widget name is required', 'help-dialog' ),
		'widget_pages_required'     => esc_html__( 'The widget needs at least one page to display the Help Dialog.', 'help-dialog' ),
		'nonce'                     => wp_create_nonce( "_wpnonce_ephd_ajax_action" ),
		'disabling_help_dialog'     => esc_html__( 'The Help Dialog was disabled.', 'help-dialog' ),
		'admin_pages_title_empty'   => esc_html__( 'No admin pages were selected.', 'help-dialog' ),
		'admin_pages_title'         => esc_html__( 'Display on these admin pages', 'help-dialog' ) . ':',
		'lang'                      => EPHD_Multilang_Utilities::get_current_language(),
		'default_language'          => EPHD_Multilang_Utilities::get_default_language(),
		'empty_default_language'    => esc_html__( 'A question for the default language is required.', 'help-dialog' ),
		'need_help_url'             => esc_js( admin_url( 'admin.php?page=ephd-help-dialog#getting-started' ) ),
		'iframe_styles_url'             => esc_url( Echo_Help_Dialog::$plugin_url . 'css/iframe-styles' . $suffix . '.css' ),
		'article_preview_not_available' => esc_html__( 'The article preview is not available. Press the read more link to read the article.', 'help-dialog' ),
		'public_article_details_styles' => '',  // EPHD_Help_Dialog_View::get_public_article_details_styles(),
		'msg_no_data'                   => esc_html__( 'No data recorded yet for the selected time frame', 'help-dialog' ),
		'active_theme_class'            => 'ephd-help-dialog-active-theme-' . EPHD_Utilities::get_wp_option( 'stylesheet', 'unknown' ),
		'include_pages'                     => esc_html__( 'Include Page', 'help-dialog' ),
		'exclude_pages'                 => esc_html__( 'Exclude Page', 'help-dialog' ),
		'include_posts'                     => esc_html__( 'Include Post', 'help-dialog' ),
		'exclude_posts'                 => esc_html__( 'Exclude Post', 'help-dialog' ),
		'include_cpts'                      => esc_html__( 'Include CPTs', 'help-dialog' ),
		'exclude_cpts'                  => esc_html__( 'Exclude CPTs', 'help-dialog' ),
	);
}

function ephd_load_iframe_styles() {
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	wp_register_style( 'ephd-public-styles', Echo_Help_Dialog::$plugin_url . 'css/public-styles' . $suffix . '.css', array(), Echo_Help_Dialog::$version );
	EPHD_Help_Dialog_View::insert_widget_inline_styles();
	wp_print_styles( array( 'ephd-public-styles' ) );

	if ( is_rtl() ) {
		wp_register_style( 'ephd-public-styles-rtl', Echo_Help_Dialog::$plugin_url . 'css/public-styles-rtl' . $suffix . '.css', array(), Echo_Help_Dialog::$version );
		wp_print_styles( array( 'ephd-public-styles-rtl' ) );
	}

	wp_register_style( 'ephd-icon-fonts', Echo_Help_Dialog::$plugin_url . 'css/ephd-icon-fonts' . $suffix . '.css', array(), Echo_Help_Dialog::$version );
	wp_print_styles( array( 'ephd-icon-fonts' ) );
	wp_register_script( 'ephd-help-dialog-scripts', Echo_Help_Dialog::$plugin_url . 'js/public-help-dialog' . $suffix . '.js', array('jquery'), Echo_Help_Dialog::$version );

	$ephd_vars = array(
		'ajaxurl'                       => admin_url( 'admin-ajax.php', 'relative' ),
		'msg_try_again'                 => esc_html__( 'Please try again later.', 'help-dialog' ),
		'error_occurred'                => esc_html__( 'Error occurred', 'help-dialog' ) . ' (16)',
		'not_saved'                     => esc_html__( 'Error occurred', 'help-dialog' ). ' (6)',
		'unknown_error'                 => esc_html__( 'Unknown error', 'help-dialog' ) . ' (17)',
		'reload_try_again'              => esc_html__( 'Please reload the page and try again.', 'help-dialog' ),
		'save_config'                   => esc_html__( 'Saving configuration', 'help-dialog' ),
		'input_required'                => esc_html__( 'Input is required', 'help-dialog' ),
		'article_preview_not_available' => esc_html__( 'The article preview is not available. Press the read more link to read the article.', 'help-dialog' ),
		'nonce'                         => wp_create_nonce( "_wpnonce_ephd_ajax_action" ),
		'public_article_details_styles' => '',  // EPHD_Help_Dialog_View::get_public_article_details_styles(),
		'admin_pages_title_empty'       => esc_html__( 'No admin pages were selected.', 'help-dialog' ),
		'admin_pages_title'             => esc_html__( 'Display on these admin pages', 'help-dialog' ) . ':',
		'lang'                          => EPHD_Multilang_Utilities::get_current_language()
	);

	wp_localize_script( 'ephd-help-dialog-scripts', 'ephd_help_dialog_vars', $ephd_vars );

	wp_print_scripts( array( 'ephd-help-dialog-scripts' ) );
}
