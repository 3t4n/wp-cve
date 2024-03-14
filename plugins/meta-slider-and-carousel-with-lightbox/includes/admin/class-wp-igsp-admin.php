<?php
/**
 * Admin Class
 * Handles the Admin side functionality of plugin
 *
 * @package Meta slider and carousel with lightbox
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wp_Igsp_Admin {

	function __construct() {

		// Action to add admin menu
		add_action( 'admin_menu', array($this, 'wp_igsp_register_menu') );

		// Action to add metabox
		add_action( 'add_meta_boxes', array($this, 'wp_igsp_post_sett_metabox') );

		// Action to save metabox
		add_action( 'save_post', array($this, 'wp_igsp_save_metabox_value') );

		// Admin Prior Processes
		add_action( 'admin_init', array($this, 'wp_igsp_admin_init_process') );

		// Action to add custom column to Gallery listing
		add_filter( 'manage_'.WP_IGSP_POST_TYPE.'_posts_columns', array($this, 'wp_igsp_posts_columns') );

		// Action to add custom column data to Gallery listing
		add_action('manage_'.WP_IGSP_POST_TYPE.'_posts_custom_column', array($this, 'wp_igsp_post_columns_data'), 10, 2);

		// Filter to add row data
		add_filter( 'post_row_actions', array($this, 'wp_igsp_add_post_row_data'), 10, 2 );

		// Action to add Attachment Popup HTML
		add_action( 'admin_footer', array($this,'wp_igsp_image_update_popup_html') );

		// Ajax call to update option
		add_action( 'wp_ajax_wp_igsp_get_attachment_edit_form', array($this, 'wp_igsp_get_attachment_edit_form') );

		// Ajax call to update attachment data
		add_action( 'wp_ajax_wp_igsp_save_attachment_data', array($this, 'wp_igsp_save_attachment_data') );
	}

	/**
	 * Function to add menu
	 * 
	 * @since 1.0.0
	 */
	function wp_igsp_register_menu() {

		// How It Work Page
		add_submenu_page( 'edit.php?post_type='.WP_IGSP_POST_TYPE, __('How it works, our plugins and offers', 'meta-slider-and-carousel-with-lightbox'), __('How It Works', 'meta-slider-and-carousel-with-lightbox'), 'manage_options', 'igsp-designs', array($this, 'wp_igsp_designs_page') );

		// Setting page
		add_submenu_page( 'edit.php?post_type='.WP_IGSP_POST_TYPE, __('Overview - Meta slider and carousel with lightbox', 'meta-slider-and-carousel-with-lightbox'), '<span style="color:#2ECC71">'. __('Overview', 'meta-slider-and-carousel-with-lightbox').'</span>', 'manage_options', 'wp-igsp-solutions-features', array($this, 'wp_igsp_solutions_features_page') );

		// Register Premium Feature Page
		add_submenu_page( 'edit.php?post_type='.WP_IGSP_POST_TYPE, __('Upgrade To PRO - Meta slider and carousel with lightbox', 'meta-slider-and-carousel-with-lightbox'), '<span style="color:#ff2700">'.__('Upgrade To PRO', 'meta-slider-and-carousel-with-lightbox').'</span>', 'manage_options', 'wp-igsp-premium', array($this, 'wp_igsp_premium_page') );
	}

	/**
	 * How It Work Page HTML
	 * 
	 * @since 1.0.0
	 */
	function wp_igsp_designs_page() {
		include_once( WP_IGSP_DIR . '/includes/admin/igsp-how-it-work.php' );
	}

	/**
	 * Solutions & Features Page Html
	 * 
	 * @since 2.0.11
	 */
	function wp_igsp_solutions_features_page() {
		include_once( WP_IGSP_DIR . '/includes/admin/settings/solution-features/solutions-features.php' );
	}

	/**
	 * Premium Feature Page HTML
	 * 
	 * @since 1.0.0
	 */
	function wp_igsp_premium_page() {
		//include_once( WP_IGSP_DIR . '/includes/admin/settings/premium.php' );
	}

	/**
	 * Post Settings Metabox
	 * 
	 * @since 1.0.0
	 */
	function wp_igsp_post_sett_metabox() {

		// Getting all post types
		$all_post_types = wp_igsp_get_post_types();

		add_meta_box( 'wp-igsp-post-sett', __( 'Meta Slider and Carousel with Lightbox - Settings', 'meta-slider-and-carousel-with-lightbox' ), array($this, 'wp_igsp_post_sett_mb_content'), $all_post_types, 'normal', 'high' );

		add_meta_box( 'wp-igsp-post-metabox-pro', __('More Premium - Settings', 'meta-slider-and-carousel-with-lightbox'), array($this, 'wp_igsp_post_sett_box_callback_pro'), WP_IGSP_POST_TYPE, 'normal', 'high' );
	}
	
	/**
	 * Post Settings Metabox HTML
	 * 
	 * @since 1.0.0
	 */
	function wp_igsp_post_sett_mb_content() {
		include_once( WP_IGSP_DIR .'/includes/admin/metabox/wp-igsp-sett-metabox.php' );
	}
	
	/**
	 * Function to handle 'premium ' metabox HTML
	 * 
	 * @since 1.5.1
	 */
	function wp_igsp_post_sett_box_callback_pro( $post ) {
		include_once( WP_IGSP_DIR .'/includes/admin/metabox/wp-igsp-post-setting-metabox-pro.php' );
	}

	/**
	 * Function to save metabox values
	 * 
	 * @since 1.0.0
	 */
	function wp_igsp_save_metabox_value( $post_id ) {

		global $post_type;

		$registered_posts = wp_igsp_get_post_types(); // Getting registered post types

		if( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )					// Check Autosave
		|| ( ! isset( $_POST['post_ID'] ) || $post_id != $_POST['post_ID'] )	// Check Revision
		|| ( ! current_user_can( 'edit_post', $post_id ) )						// Check if user can edit the post.
		|| ( ! in_array( $post_type, $registered_posts ) ) )					// Check if user can edit the post.
		{
			return $post_id;
		}

		$prefix = WP_IGSP_META_PREFIX; // Taking metabox prefix

		// Taking variables
		$gallery_imgs = isset( $_POST['wp_igsp_img'] ) ? array_map( 'intval', (array) $_POST['wp_igsp_img'] ) : '';

		update_post_meta( $post_id, '_vdw_gallery_id', $gallery_imgs );
	}

	/**
	 * Function register setings
	 * 
	 * @since 1.0.0
	 */
	function wp_igsp_admin_init_process() {

		global $typenow;

		$current_page = isset( $_REQUEST['page'] ) ? wp_igsp_clean( $_REQUEST['page'] ) : '';

		// If plugin notice is dismissed
		if( isset( $_GET['message'] ) && 'wp-igsp-plugin-notice' == $_GET['message'] ) {
			set_transient( 'wp_igsp_install_notice', true, 604800 );
		}

		// Redirect to external page for upgrade to menu
		if( $typenow == WP_IGSP_POST_TYPE ) {

			if( $current_page == 'wp-igsp-premium' ) {

				$tab_url		= add_query_arg( array( 'post_type' => WP_IGSP_POST_TYPE, 'page' => 'wp-igsp-solutions-features', 'tab' => 'igsp_basic_tabs' ), admin_url('edit.php') );

				wp_redirect( $tab_url );
				exit;
			}
		}

	}

	/**
	 * Add custom column to Post listing page
	 * 
	 * @since 1.0.0
	 */
	function wp_igsp_posts_columns( $columns ) {

		$new_columns['wp_igsp_shortcode']	= esc_html__('Shortcode', 'meta-slider-and-carousel-with-lightbox');
		$new_columns['wp_igsp_photos']		= esc_html__('Number of Photos', 'meta-slider-and-carousel-with-lightbox');

		$columns = wp_igsp_add_array( $columns, $new_columns, 1, true );

		return $columns;
	}

	/**
	 * Add custom column data to Post listing page
	 * 
	 * @since 1.0.0
	 */
	function wp_igsp_post_columns_data( $column, $post_id ) {

		global $post;

		// Taking some variables
		$prefix = WP_IGSP_META_PREFIX;

		switch ($column) {
			case 'wp_igsp_shortcode':

				echo '<div class="wpos-copy-clipboard wp-igsp-shortcode-preview">[meta_gallery_slider id="'.esc_attr( $post_id ).'"]</div> <br/>';
				echo '<div class="wpos-copy-clipboard wp-igsp-shortcode-preview">[meta_gallery_carousel id="'.esc_attr( $post_id ).'"]</div>';
				break;

			case 'wp_igsp_photos':
				$total_photos = get_post_meta( $post_id, '_vdw_gallery_id', true );
				echo ! empty( $total_photos ) ? count( $total_photos ) : '--';
				break;
		}
	}

	/**
	 * Function to add custom quick links at post listing page
	 * 
	 * @since 1.0.0
	 */
	function wp_igsp_add_post_row_data( $actions, $post ) {

		if( $post->post_type == WP_IGSP_POST_TYPE ) {
			return array_merge( array( 'wpos_id' => esc_html__('ID:', 'meta-slider-and-carousel-with-lightbox') .' '. esc_html( $post->ID ) ), $actions );
		}

		return $actions;
	}

	/**
	 * Image data popup HTML
	 * 
	 * @since 1.0.0
	 */
	function wp_igsp_image_update_popup_html() {

		global $post_type;

		$registered_posts = wp_igsp_get_post_types(); // Getting registered post types

		if( in_array( $post_type, $registered_posts ) ) {
			include_once( WP_IGSP_DIR .'/includes/admin/settings/wp-igsp-img-popup.php');
		}
	}

	/**
	 * Get attachment edit form
	 * 
	 * @since 1.0.0
	 */
	function wp_igsp_get_attachment_edit_form() {

		// Taking some defaults
		$result				= array();
		$result['success']	= 0;
		$result['msg']		= esc_js( __('Sorry, Something happened wrong.', 'meta-slider-and-carousel-with-lightbox') );
		$attachment_id		= ! empty( $_POST['attachment_id'] )	? wp_igsp_clean_number( $_POST['attachment_id'] )	: '';
		$nonce				= ! empty( $_POST['nonce'] )			? wp_igsp_clean( $_POST['nonce'] )					: '';

		// If attachment id is exist
		if( ! empty( $attachment_id ) && wp_verify_nonce( $nonce, "wp-igsp-get-attachment-data" ) ) {

			$attachment_post = get_post( $attachment_id );

			if( ! empty( $attachment_post ) ) {

				$attachment_url	= wp_get_attachment_thumb_url( $attachment_id );

				ob_start();

				// Popup Data File
				include( WP_IGSP_DIR . '/includes/admin/settings/wp-igsp-img-popup-data.php' );

				$attachment_data = ob_get_clean();

				$result['success']	= 1;
				$result['msg']		= esc_js( __('Attachment Found.', 'meta-slider-and-carousel-with-lightbox') );
				$result['data']		= $attachment_data;
			}
		}

		wp_send_json( $result );
	}

	/**
	 * Get attachment edit form
	 * 
	 * @since 1.0.0
	 */
	function wp_igsp_save_attachment_data() {

		$prefix				= WP_IGSP_META_PREFIX;
		$result				= array();
		$result['success']	= 0;
		$result['msg']		= esc_js( __('Sorry, Something happened wrong.', 'meta-slider-and-carousel-with-lightbox') );
		$attachment_id		= ! empty( $_POST['attachment_id'] )	? wp_igsp_clean_number( $_POST['attachment_id'] )	: '';
		$nonce				= ! empty( $_POST['nonce'] )			? wp_igsp_clean( $_POST['nonce'] )					: '';
		$form_data			= parse_str( $_POST['form_data'], $form_data_arr );

		if( ! empty( $attachment_id ) && ! empty( $form_data_arr ) && wp_verify_nonce( $nonce, "wp-igsp-save-attachment-data-{$attachment_id}" ) ) {

			// Getting attachment post
			$wp_igsp_attachment_post = get_post( $attachment_id );

			// If post type is attachment
			if( isset( $wp_igsp_attachment_post->post_type ) && $wp_igsp_attachment_post->post_type == 'attachment' ) {

				$post_args = array(
									'ID'			=> $attachment_id,
									'post_title'	=> ! empty( $form_data_arr['wp_igsp_attachment_title'] ) ? $form_data_arr['wp_igsp_attachment_title'] : '',
									'post_excerpt'	=> $form_data_arr['wp_igsp_attachment_caption'],
								);
				$update = wp_update_post( $post_args );

				if( ! is_wp_error( $update ) ) {

					update_post_meta( $attachment_id, '_wp_attachment_image_alt', wp_igsp_clean( $form_data_arr['wp_igsp_attachment_alt'] ) );

					$result['success']	= 1;
					$result['msg']		= esc_js( __('Your changes saved successfully.', 'meta-slider-and-carousel-with-lightbox') );
				}
			}
		}

		wp_send_json( $result );
	}
}

$wp_igsp_admin = new Wp_Igsp_Admin();