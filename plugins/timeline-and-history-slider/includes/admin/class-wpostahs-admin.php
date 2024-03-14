<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package Timeline and History slider
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Wpostahs_Admin {

	function __construct() {

		// Action to add admin menu
		add_action( 'admin_menu', array( $this, 'wpostahs_register_menu' ), 12 );

		// Action to add metabox
		add_action( 'add_meta_boxes', array( $this, 'wpostahs_post_sett_metabox' ));

		// Admin init process
		add_action( 'admin_init', array( $this, 'wpostahs_admin_init_process' ));

		// Action to add custom column to Timeline listing
		add_filter("manage_wpostahs-slider-category_custom_column", array( $this, 'wpostahs_slider_category_columns' ), 10, 3);

		// Action to add custom column data to Timeline listing
		add_filter("manage_edit-wpostahs-slider-category_columns", array( $this, 'wpostahs_slider_category_manage_columns' ));
	}

	/**
	 * Function to add menu
	 * 
	 * @package Timeline and History slider
	 * @since 1.0.0
	 */
	function wpostahs_register_menu() {

		// How It Work Page
		add_submenu_page( 'edit.php?post_type='.WPOSTAHS_POST_TYPE, __( 'How it works, our plugins and offers', 'timeline-and-history-slider' ), __( 'How It Works', 'timeline-and-history-slider' ), 'manage_options', 'wpostahs-designs', array( $this, 'wpostahs_designs_page' ));

		// Register plugin premium page
		add_submenu_page( 'edit.php?post_type='.WPOSTAHS_POST_TYPE, __( 'Upgrade To Premium -  Timeline and History slider', 'timeline-and-history-slider' ), '<span style="color:#ff2700">'.__( 'Upgrade To Premium', 'timeline-and-history-slider' ).'</span>', 'manage_options', 'wpostahs-premium', array( $this, 'wpostahs_premium_page' ));
	}

	/**
	 * How it Work Page Html
	 * 
	 * @package Timeline and History slider
	 * @since 1.0.0
	 */
	function wpostahs_designs_page() {
		include_once( WPOSTAHS_DIR . '/includes/admin/wpostahs-how-it-work.php' );
	}

	/**
	 * Premium Page Html
	 * 
	 * @package Timeline and History slider
	 * @since 1.0.0
	 */
	function wpostahs_premium_page() {
		include_once( WPOSTAHS_DIR . '/includes/admin/settings/premium.php' );
	}

	/**
	 * Post Settings Metabox
	 * 
	 * @package Timeline and History slider
	 * @since 1.4.4
	 */
	function wpostahs_post_sett_metabox() {

		add_meta_box( 'wpostahs-sett-metabox-pro', __( 'Timeline - Settings', 'timeline-and-history-slider' ), array( $this, 'wpostahs_meta_sett_box_callback_pro' ), WPOSTAHS_POST_TYPE, 'normal', 'high');

		add_meta_box( 'wpostahs-post-metabox-pro', __( 'More Premium - Settings', 'timeline-and-history-slider' ), array( $this, 'wpostahs_post_sett_box_callback_pro' ), WPOSTAHS_POST_TYPE, 'normal', 'high');
	}

	/**
	 * Function to handle 'premium ' metabox HTML
	 * 
	 * @package Timeline and History slider
	 * @since 1.4.4
	 */
	function wpostahs_meta_sett_box_callback_pro( $post ) {
		include_once( WPOSTAHS_DIR .'/includes/admin/metabox/wpostahs-post-sett-metabox-pro.php');
	}

	/**
	 * Function to handle 'premium ' metabox HTML
	 * 
	 * @package Timeline and History slider
	 * @since 1.4.4
	 */
	function wpostahs_post_sett_box_callback_pro( $post ) {
		include_once( WPOSTAHS_DIR .'/includes/admin/metabox/wpostahs-post-setting-metabox-pro.php');
	}

	/**
	 * Admin Prior Process
	 * 
	 * @package Timeline and History slider
	 * @since 1.0.0
	 */
	function wpostahs_admin_init_process() {
		// If plugin notice is dismissed
		if( isset($_GET['message']) && $_GET['message'] == 'wpostahs-plugin-notice' ) {
			set_transient( 'wpostahs_install_notice', true, 604800 );
		}
	}

	/**
	 * Add custom column to Timeline listing page
	 * 
	 * @package Timeline and History slider
	 * @since 1.0.0
	 */
	function wpostahs_slider_category_columns( $ouput, $column_name, $tax_id ) {
		if( $column_name == 'wpostahs_shortcode' ) {
			$ouput .= '[th-slider category="' . esc_attr($tax_id). '"]';
		}
		return $ouput;
	}

	/**
	 * Add custom column data to Timeline listing page
	 * 
	 * @package Timeline and History slider
	 * @since 1.0.0
	 */
	function wpostahs_slider_category_manage_columns($columns) {
		$new_columns['wpostahs_shortcode'] = esc_html__( 'Timeline Shortcode', 'timeline-and-history-slider' );
		$columns = wpostahs_logo_add_array( $columns, $new_columns, 2 );
		return $columns;
	}
}

$wpostahs_Admin = new Wpostahs_Admin();