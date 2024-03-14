<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package Blog Designer - Post and Widget
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Bdpw_Admin {

	function __construct() {

		// Action to register admin menu
		add_action( 'admin_menu', array( $this, 'bdpw_register_menu' ) );

		// Admin Init Processes
		add_action( 'admin_init', array( $this, 'bdpw_admin_init_process' ) );

		// Filter to add row action in category table
		add_filter( BDPW_CAT.'_row_actions', array( $this, 'bdpw_add_tax_row_data' ), 10, 2 );
	}

	/**
	 * Function to register admin menus
	 * 
	 * @since 1.0.4
	 */
	function bdpw_register_menu() {

		// Register plugin how it work page
		add_menu_page( __('Blog Designer', 'blog-designer-for-post-and-widget'), __('Blog Designer', 'blog-designer-for-post-and-widget'), 'manage_options', 'bdpw-about',  array($this, 'bdpw_settings_page'), 'dashicons-sticky' );

		// Setting page
		add_submenu_page( 'bdpw-about', __('Overview - blog-designer-for-post-and-widget', 'blog-designer-for-post-and-widget'), '<span style="color:#2ECC71">'. __('Overview', 'blog-designer-for-post-and-widget').'</span>', 'manage_options', 'bdpw-solutions-features', array($this, 'bdpw_solutions_features_page') );

		// Upgrade To PRO page
		add_submenu_page( 'bdpw-about', __('Upgrade To PRO - Blog Designer', 'blog-designer-for-post-and-widget'), '<span style="color:#ff2700">' . __('Upgrade To Premium ', 'blog-designer-for-post-and-widget') . '</span>', 'manage_options', 'bdpw-premium', array($this, 'bdpw_premium_page') );
	}

	/**
	 * Function to display plugin design HTML
	 * 
	 * @since 2.1
	 */
	function bdpw_settings_page() {
		include_once( BDPW_DIR . '/includes/admin/bdpw-how-it-work.php' );
	}

	/**
	 * Function to display plugin design HTML
	 * 
	 * @since 2.1
	 */
	function bdpw_solutions_features_page() {
		include_once( BDPW_DIR . '/includes/admin/settings/solution-features/solutions-features.php' );
	}

	/**
	 * Getting Started Page Html
	 * 
	 * @since 1.0
	 */
	function bdpw_premium_page() {
		//include_once( BDPW_DIR . '/includes/admin/settings/premium.php' );
	}

	/**
	 * Admin prior processes
	 * 
	 * @since 1.4
	 */
	function bdpw_admin_init_process() {

		global $typenow;

		$current_page = isset( $_REQUEST['page'] ) ? esc_attr( $_REQUEST['page'] ) : '';

		// If plugin notice is dismissed
		if( isset( $_GET['message'] ) && 'bdpw-plugin-notice' == $_GET['message'] ) {
			set_transient( 'bdpw_install_notice', true, 604800 );
		}

		// Redirect to external page for upgrade to menu
		if( $current_page == 'bdpw-premium' ) {

			$tab_url	= add_query_arg( array( 'page' => 'bdpw-solutions-features', 'tab' => 'bdpw_basic_tabs' ), admin_url('admin.php') );

			wp_redirect( $tab_url );
			exit;
		}

	}

	/**
	 * Function to add category row action
	 * 
	 * @since 1.0
	 */
	function bdpw_add_tax_row_data( $actions, $tag ) {
		return array_merge( array( 'wpos_id' => esc_html__( 'ID:', 'blog-designer-for-post-and-widget' ) .' '. esc_html( $tag->term_id ) ), $actions );
	}
}

$bdpw_admin = new Bdpw_Admin();