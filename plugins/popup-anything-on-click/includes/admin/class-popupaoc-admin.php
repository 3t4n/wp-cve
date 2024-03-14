<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package Popup Anything on click
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Popupaoc_Admin {

	function __construct() {

		// Action to add admin menu
		add_action( 'admin_menu', array($this, 'popupaoc_register_menu') );

		// Action to add metabox
		add_action( 'add_meta_boxes', array($this, 'popupaoc_post_sett_metabox') );

		// Action to save metabox
		add_action( 'save_post', array($this, 'popupaoc_save_metabox_value'), 10, 2 );

		// Action to admin notice
		add_action( 'admin_notices', array($this, 'popupaoc_db_updated_notice') );

		// Admin prior process
		add_action( 'admin_init', array($this, 'popupaoc_admin_init_process') );

		// Action to add custom column to Slider listing
		add_filter( 'manage_'.POPUPAOC_POST_TYPE.'_posts_columns', array($this, 'popupaoc_manage_posts_columns') );

		// Action to add custom column data to Slider listing
		add_action('manage_'.POPUPAOC_POST_TYPE.'_posts_custom_column', array($this, 'popupaoc_post_columns_data'), 10, 2);

		// Action to get post suggestion
		add_action( 'wp_ajax_popupaoc_post_title_sugg', array($this, 'popupaoc_post_title_sugg') );

		// Render Popup Preview
		add_action( 'wp', array($this, 'popupaoc_render_popup_preview') );
	}

	/**
	 * Function to add menu
	 * 
	 * @package Popup Anything on Click
	 * @since 1.0.0
	 */
	function popupaoc_register_menu() {

		// Setting page
		add_submenu_page( 'edit.php?post_type='.POPUPAOC_POST_TYPE, __('Settings - Popup Anything On Click', 'popup-anything-on-click'), __('Settings', 'popup-anything-on-click'), 'manage_options', 'popupaoc-settings', array($this, 'popupaoc_settings_page') );

		// Setting page
		add_submenu_page( 'edit.php?post_type='.POPUPAOC_POST_TYPE, __('Overview - Popup Anything On Click', 'popup-anything-on-click'), '<span style="color:#2ECC71">'. __('Overview', 'popup-anything-on-click').'</span>', 'manage_options', 'paoc-solutions-features', array($this, 'popupaoc_solutions_features_page') );

		// Register plugin premium page
		add_submenu_page( 'edit.php?post_type='.POPUPAOC_POST_TYPE, __('Upgrade To PRO - Popup Anything On Click', 'popup-anything-on-click'), '<span style="color:#ff2700">'.__('Upgrade To PRO', 'popup-anything-on-click').'</span>', 'manage_options', 'popupaoc-premium', array($this, 'popupaoc_premium_page') );
	}

	/**
	 * Post Settings Metabox
	 * 
	 * @package Popup Anything on click
	 * @since 1.0.0
	 */
	function popupaoc_post_sett_metabox() {

		// Add metabox in popup posts
		add_meta_box( 'popupaoc-post-sett', __( 'Popup Anything - Settings', 'popup-anything-on-click' ), array($this, 'popupaoc_post_sett_mb_content'), POPUPAOC_POST_TYPE, 'normal', 'high' );

		// Add metabox in popup Report
		add_meta_box( 'paoc-popup-report', __( 'Popup Report', 'popup-anything-on-click' ), array($this, 'popupaoc_report_meta_box_content'), POPUPAOC_POST_TYPE, 'side', 'default' );
	}

	/**
	 * Post Settings Metabox HTML
	 * 
	 * @package Popup Anything on click
	 * @since 1.0.0
	 */
	function popupaoc_post_sett_mb_content() {
		include_once( POPUPAOC_DIR .'/includes/admin/metabox/post-sett-metabox.php');
	}

	/**
	 * Function to handle metabox content
	 * 
	 * @since 2.0
	 */
	function popupaoc_report_meta_box_content() {
		include_once( POPUPAOC_DIR .'/includes/admin/metabox/report-metabox.php');
	}

	/**
	 * Function to save metabox values
	 * 
	 * @package Popup Anything on click
	 * @since 1.0.0
	 */
	function popupaoc_save_metabox_value( $post_id, $post ) {

		global $post_type;

		// Popup Meta
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) // Check Autosave and revision
		|| ( ! isset( $_POST['post_ID'] ) || absint( $_POST['post_ID'] ) != $post_id )	// Check Revision
		|| ( $post_type !=  POPUPAOC_POST_TYPE )										// Check if current post type is supported.
		|| ( ! current_user_can( 'edit_post', $post_id ) ) )
		{
			return $post_id;
		}

		// Getting saved values
		$prefix			= POPUPAOC_META_PREFIX; // Taking metabox prefix
		$display_type	= 'modal';
		$popup_goal		= 'announcement';
		$tab			= isset( $_POST[$prefix.'tab'] )			? popupaoc_clean( $_POST[$prefix.'tab'] )			: '';
		$popup_appear	= isset( $_POST[$prefix.'popup_appear'] )	? popupaoc_clean( $_POST[$prefix.'popup_appear'] )	: 'page_load';

		// Behaviour Settings
		$behaviour = popupaoc_process_meta( "{$prefix}behaviour", $post_id );

		// Content Settings
		$content = popupaoc_process_meta( "{$prefix}content", $post_id );

		// Design Settings
		$design	= popupaoc_process_meta( "{$prefix}design", $post_id );

		// Advance Settings
		$advance = popupaoc_process_meta( "{$prefix}advance", $post_id );

		// Custom CSS Settings
		$custom_css	= popupaoc_process_meta( "{$prefix}custom_css", $post_id );

		// Update Meta
		update_post_meta( $post_id, $prefix.'tab', $tab );
		update_post_meta( $post_id, $prefix.'popup_goal', $popup_goal );
		update_post_meta( $post_id, $prefix.'display_type', $display_type );
		update_post_meta( $post_id, $prefix.'popup_appear', $popup_appear );
		update_post_meta( $post_id, $prefix.'behaviour', $behaviour );
		update_post_meta( $post_id, $prefix.'content', $content );
		update_post_meta( $post_id, $prefix.'design', $design );
		update_post_meta( $post_id, $prefix.'advance', $advance );
		update_post_meta( $post_id, $prefix.'custom_css', $custom_css );
	}

	/**
	 * Function to display DB updated notice
	 * 
	 * @since 2.0
	 */
	function popupaoc_db_updated_notice() {

		if( isset( $_GET['message'] ) && 'popupaoc-db-update' == $_GET['message'] ) { ?>
			<div id="message" class="updated notice notice-success is-dismissible">
				<p><strong><?php esc_html_e('Popup Anything database proccess has been updated.', 'popup-anything-on-click'); ?></strong></p>
			</div>
		<?php }
	}

	/**
	 * Add custom column to Post listing page
	 * 
	 * @package Popup Anything on click
	 * @since 1.0.0
	 */
	function popupaoc_manage_posts_columns( $columns ) {
		
		$new_columns['paoc_popup_goal']		= esc_html__('Goal', 'popup-anything-on-click');
		$new_columns['paoc_display_type']	= esc_html__('Type', 'popup-anything-on-click');
		$new_columns['paoc_popup_appear']	= esc_html__('Appear On', 'popup-anything-on-click');

		$columns = popupaoc_add_array( $columns, $new_columns, 1, true );

		return $columns;
	}

	/**
	 * Add custom column data to Post listing page
	 * 
	 * @package Popup Anything on Click
	 * @since 1.0.0
	 */
	function popupaoc_post_columns_data( $column, $post_id ) {

		$prefix = POPUPAOC_META_PREFIX;

		switch ($column) {
			case 'paoc_popup_goal':
				$popup_goals	= popupaoc_popup_goals();
				$popup_goal		= get_post_meta( $post_id, $prefix.'popup_goal', true );
				$popup_goal		= isset( $popup_goals[ $popup_goal ]['name'] ) ? $popup_goals[ $popup_goal ]['name'] : $popup_goal;

				echo esc_html( $popup_goal );
				break;

			case 'paoc_display_type':
				$popup_types	= popupaoc_popup_types();
				$display_type	= get_post_meta( $post_id, $prefix.'display_type', true );
				$display_type	= isset( $popup_types[ $display_type ]['name'] ) ? $popup_types[ $display_type ]['name'] : $display_type;

				echo esc_html( $display_type );
				break;

			case 'paoc_popup_appear':
				$appear_types	= popupaoc_when_appear_options();
				$popup_appear	= get_post_meta( $post_id, $prefix.'popup_appear', true );
				$popup_appear	= isset( $appear_types[ $popup_appear ] ) ? $appear_types[ $popup_appear ] : $popup_appear;
				
				echo esc_html( $popup_appear );
				break;
		}
	}

	/**
	 * Getting Started Page Html
	 * 
	 * @package Popup Anything on Click
	 * @since 1.0.0
	 */
	function popupaoc_settings_page() {
		include_once( POPUPAOC_DIR . '/includes/admin/settings/settings.php' );
	}

	/**
	 * Solutions & Features Page Html
	 * 
	 * @package Popup Anything on Click
	 * @since 2.0.11
	 */
	function popupaoc_solutions_features_page() {
		include_once( POPUPAOC_DIR . '/includes/admin/settings/solution-features/solutions-features.php' );
	}

	/**
	 * Getting Started Page Html
	 * 
	 * @package Popup Anything on Click
	 * @since 1.0.0
	 */
	function popupaoc_premium_page() {
		//include_once( POPUPAOC_DIR . '/includes/admin/settings/premium.php' );
	}

	/**
	 * Admin Prior Process
	 * 
	 * @package Popup Anything on Click
	 * @since 1.2.2
	 */
	function popupaoc_admin_init_process() {

		global $typenow;

		$current_page = isset( $_REQUEST['page'] ) ? popupaoc_clean( $_REQUEST['page'] ) : '';

		// If plugin notice is dismissed
		if( isset( $_GET['message'] ) && 'popupaoc-plugin-notice' == $_GET['message'] ) {
			set_transient( 'popupaoc_install_notice', true, 604800 );
		}

		// Redirect to external page for upgrade to menu
		if( $typenow == POPUPAOC_POST_TYPE ) {

			if( $current_page == 'popupaoc-premium' ) {

				$tab_url		= add_query_arg( array( 'post_type' => POPUPAOC_POST_TYPE, 'page' => 'paoc-solutions-features', 'tab' => 'paoc_basic_tabs' ), admin_url('edit.php') );

				wp_redirect( $tab_url );
				exit;
			}
		}

	}

	/**
	 * Function to get post suggestion based on search input
	 * 
 	 * @since 2.0
	 */
	function popupaoc_post_title_sugg() {

		$return			= array();
		$prefix			= POPUPAOC_META_PREFIX;
		$post_status	= ! empty( $_GET['post_status'] )	? popupaoc_clean( $_GET['post_status'] )		: 'publish';
		$search			= isset( $_GET['search'] )			? popupaoc_clean( $_GET['search'] )				: '';
		$post_type		= isset( $_GET['post_type'] )		? popupaoc_clean( $_GET['post_type'] )			: 'post';
		$nonce			= isset( $_GET['nonce'] )			? popupaoc_clean( $_GET['nonce'] )				: '';
		$meta_data		= isset( $_GET['meta_data'] )		? popupaoc_clean( $_GET['meta_data'] )			: '';
		$meta_data		= json_decode( $meta_data, true );

		// Verify Nonce
		if( $search && wp_verify_nonce( $nonce, 'paoc-post-title-sugg' ) ) {

			$args = array(
						's'						=> $search,
						'post_type'				=> $post_type,
						'post_status'			=> $post_status,
						'order'					=> 'ASC',
						'orderby'				=> 'title',
						'posts_per_page'		=> 20
					);

			// If number is passed
			if( is_numeric( $search ) ) {
				$args['s'] = false;
				$args['p'] = $search;
			}

			// If meta query is set
			if( $meta_data ) {
				$args['meta_query'] = $meta_data;
			}

			$search_query = get_posts( $args );

			if( $search_query ) :

				foreach ( $search_query as $search_data ) {
					
					$post_title	= ! empty( $search_data->post_title ) ? $search_data->post_title : esc_html__('Post', 'popup-anything-on-click');
					$post_title	= $post_title . " - (#{$search_data->ID})";

					$return[]	= array( $search_data->ID, $post_title );
				}

			endif;
		}

		wp_send_json( $return );
	}

	/**
	 * Function to handle module preview screen
	 * 
	 * @since 2.0
	 */
	function popupaoc_render_popup_preview() {

		if( is_user_logged_in() && isset( $_GET['paoc_preview_nonce'] ) && wp_verify_nonce( $_GET['paoc_preview_nonce'], 'paoc-popup-preview-nonce' ) && isset( $_GET['paoc-popup-preview'] ) && $_GET['paoc-popup-preview'] == 1 && ( isset( $_SERVER['HTTP_REFERER'] ) && (strpos($_SERVER['HTTP_REFERER'], 'post.php') !== false || strpos($_SERVER['HTTP_REFERER'], 'post-new.php') !== false) ) ) {
			
			include_once( POPUPAOC_DIR . '/includes/admin/preview/preview.php' );
			exit;
		}
	}
}

$popupaoc_admin = new Popupaoc_Admin();