<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package WP Slick Slider and Image Carousel
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpsisac_Admin {

	function __construct() {

		// Action to add admin menu
		add_action( 'admin_menu', array($this, 'wpsisac_register_menu'), 12 );

		// Admin init process
		add_action( 'admin_init', array($this, 'wpsisac_admin_init_process') );

		// Action to add metabox
		add_action( 'add_meta_boxes', array($this, 'wpsisac_post_sett_metabox') );

		// Action to save metabox
		add_action( 'save_post_'.WPSISAC_POST_TYPE, array($this, 'wpsisac_save_metabox_value') );

		// Filter to add row action in category table
		add_filter( 'wpsisac_slider-category_row_actions', array($this, 'wpsisac_add_tax_row_data'), 10, 2 );

		// Action to add custom column to Slider listing
		add_filter( 'manage_'.WPSISAC_POST_TYPE.'_posts_columns', array($this, 'wpsisac_posts_columns') );

		// Action to add custom column data to Slider listing
		add_action('manage_'.WPSISAC_POST_TYPE.'_posts_custom_column', array($this, 'wpsisac_post_columns_data'), 10, 2);
	}

	/**
	 * Function to add menu
	 * 
	 * @since 1.0.0
	 */
	function wpsisac_register_menu() {

		add_submenu_page( 'edit.php?post_type='.WPSISAC_POST_TYPE, __( 'How it works, our plugins and offers', 'wp-slick-slider-and-image-carousel' ), __( 'How It Works', 'wp-slick-slider-and-image-carousel' ), 'manage_options', 'wpsisacm-designs', array( $this, 'wpsisacm_designs_page' ) );

		// Setting page
		add_submenu_page( 'edit.php?post_type='.WPSISAC_POST_TYPE, __( 'Overview - WP Slick Slider and Image Carousel', 'wp-slick-slider-and-image-carousel' ), '<span style="color:#2ECC71">'. __( 'Overview', 'wp-slick-slider-and-image-carousel' ).'</span>', 'manage_options', 'wpsisac-solutions-features', array( $this, 'wpsisac_solutions_features_page' ) );

		// Register plugin premium page
		add_submenu_page( 'edit.php?post_type='.WPSISAC_POST_TYPE, __( 'Upgrade To PRO - WP Slick Slider and Image Carousel', 'wp-slick-slider-and-image-carousel' ), '<span style="color:#ff2700">'.__( 'Upgrade To PRO', 'wp-slick-slider-and-image-carousel' ).'</span>', 'manage_options', 'wpsisac-premium', array( $this, 'wpsisac_premium_page' ) );
	}

	/**
	 * Getting Started Page Html
	 * 
	 * @since 1.0.0
	 */
	function wpsisac_premium_page() {
		//include_once( WPSISAC_DIR . '/includes/admin/settings/premium.php' );
	}

	/**
	 * Getting Started Page Html
	 * 
	 * @since 1.0.0
	 */
	function wpsisac_solutions_features_page(){
		include_once( WPSISAC_DIR . '/includes/admin/settings/solution-features/solutions-features.php' );
	}

	/**
	 * How It Work Page Html
	 * 
	 * @since 1.0
	 */
	function wpsisacm_designs_page() {
		include_once( WPSISAC_DIR . '/includes/admin/wpsisac-how-it-work.php' );
	}

	/**
	 * Function to notification transient
	 * 
	 * @since 1.5
	 */
	function wpsisac_admin_init_process() {

		global $typenow;

		$current_page = isset( $_REQUEST['page'] ) ? wpsisac_clean( $_REQUEST['page'] ) : '';

		// If plugin notice is dismissed
		if( isset($_GET['message']) && $_GET['message'] == 'wpsisac-plugin-notice' ) {
			set_transient( 'wpsisac_install_notice', true, 604800 );
		}

		// Redirect to external page for upgrade to menu
		if( $typenow == WPSISAC_POST_TYPE ) {

			if( $current_page == 'wpsisac-premium' ) {

				$tab_url		= add_query_arg( array( 'post_type' => WPSISAC_POST_TYPE, 'page' => 'wpsisac-solutions-features', 'tab' => 'wpsisac_basic_tabs' ), admin_url('edit.php') );

				wp_redirect( $tab_url );
				exit;
			}
		}

	}

	/**
	 * Post Settings Metabox
	 * 
	 * @since 1.0.0
	 */
	function wpsisac_post_sett_metabox() {
		
		// Slider Settings
		add_meta_box( 'custom-metabox', __( 'WP Slick Slider and Image Carousel - Settings', 'wp-slick-slider-and-image-carousel' ), array( $this, 'wpsisac_post_sett_mb_content' ), WPSISAC_POST_TYPE, 'normal', 'high' );

		// Premium Features
		add_meta_box( 'wpsisac-post-metabox-pro', __( 'More Premium - Settings', 'wp-slick-slider-and-image-carousel' ), array( $this, 'wpsisac_post_sett_box_callback_pro' ), WPSISAC_POST_TYPE, 'normal', 'high' );
	}

	/**
	 * Post Settings Metabox HTML
	 * 
	 * @since 1.2.5
	 */
	function wpsisac_post_sett_mb_content( $post ) {
		include_once( WPSISAC_DIR .'/includes/admin/metabox/wpsisac-post-metabox.php');
	}

	/**
	 * Function to handle 'premium ' metabox HTML
	 * 
	 * @since 2.4.2
	 */
	function wpsisac_post_sett_box_callback_pro( $post ) {		
		include_once( WPSISAC_DIR .'/includes/admin/metabox/wpsisac-post-setting-metabox-pro.php');
	}

	/**
	 * Function to save metabox values
	 * 
	 * @since 1.2.5
	 */
	function wpsisac_save_metabox_value( $post_id ) {

		global $post_type;

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )					// Check Autosave
		|| ( ! isset( $_POST['post_ID'] ) || $post_id != $_POST['post_ID'] )	// Check Revision
		|| ( $post_type !=  WPSISAC_POST_TYPE ) )							// Check if current post type is supported.
		{
			return $post_id;
		}

		// Taking variables
		$read_more_link = isset($_POST['wpsisac_slide_link']) ? wpsisac_get_clean_url( $_POST['wpsisac_slide_link'] )  : '';

		update_post_meta($post_id, 'wpsisac_slide_link', $read_more_link);
	}

	/**
	 * Function to add category row action
	 * 
	 * @since 1.0
	 */
	function wpsisac_add_tax_row_data( $actions, $tag ) {
		return array_merge( array( 'wpos_id' => esc_html__('ID:', 'wp-slick-slider-and-image-carousel') .' '. esc_attr( $tag->term_id ) ), $actions );
	}

	/**
	 * Add custom column to Post listing page
	 * 
	 * @since 3.1.4
	 */
	function wpsisac_posts_columns( $columns ) {

		$new_columns['wpsisac_image'] = esc_html__( 'Image', 'wp-slick-slider-and-image-carousel' );

		$columns = wpsisac_add_array( $columns, $new_columns, 1, true );

		return $columns;
	}

	/**
	 * Add custom column data to Post listing page
	 * 
	 * @since 3.1.4
	 */
	function wpsisac_post_columns_data( $column, $post_id ) {

		global $post;

		switch ( $column ) {

			case 'wpsisac_image':

				echo get_the_post_thumbnail( $post_id, array(50, 50), array('class' => 'wpsisac-slide-img') );
				break;
		}
	}
}

$wpsisac_admin = new Wpsisac_Admin();