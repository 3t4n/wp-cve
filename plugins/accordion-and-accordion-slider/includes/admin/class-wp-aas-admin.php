<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package accordion-and-accordion-slider
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wp_Aas_Admin {

	function __construct() {
		
		// Action to add metabox
		add_action( 'add_meta_boxes', array( $this, 'wp_aas_post_sett_metabox' ));

		// Action to save metabox
		add_action( 'save_post', array( $this, 'wp_aas_save_metabox_value' ));		

		// Admin Prior Processes
		add_action( 'admin_init', array($this, 'wp_aas_admin_init_process') );

		// Action to add custom column to Gallery listing
		add_filter( 'manage_'.WP_AAS_POST_TYPE.'_posts_columns', array( $this, 'wp_aas_posts_columns' ));

		// Action to add custom column data to Gallery listing
		add_action( 'manage_'.WP_AAS_POST_TYPE.'_posts_custom_column', array( $this, 'wp_aas_post_columns_data' ), 10, 2);

		// Filter to add row data
		add_filter( 'post_row_actions', array( $this, 'wp_aas_add_post_row_data' ), 10, 2);

		// Action to add Attachment Popup HTML
		add_action( 'admin_footer', array( $this,'wp_aas_image_update_popup_html' ));

		// Ajax call to update option
		add_action( 'wp_ajax_wp_aas_get_attachment_edit_form', array( $this, 'wp_aas_get_attachment_edit_form' ));

		// Ajax call to update attachment data
		add_action( 'wp_ajax_wp_aas_save_attachment_data', array( $this, 'wp_aas_save_attachment_data' ));

		// Action to add admin menu
		add_action( 'admin_menu', array( $this, 'wp_aas_register_menu' ), 12 );

	}

	/**
	 * Function to add menu
	 * 
	 * @package Accordion and Accordion Slider
	 * @since 1.0.0
	 */
	function wp_aas_register_menu() {

		// Register plugin premium page
		add_submenu_page( 'edit.php?post_type='.WP_AAS_POST_TYPE, __('How it works, our plugins and offers', 'accordion-and-accordion-slider'), __('How It Works', 'accordion-and-accordion-slider'), 'manage_options', 'wp-aas-designs', array($this, 'wp_aas_designs_page') );

		// Register plugin premium page
		// add_submenu_page( 'edit.php?post_type='.WP_AAS_POST_TYPE, __('Upgrade to PRO - Accordion and Accordion Slider', 'accordion-and-accordion-slider'), '<span style="color:#ff2700">'.__('Upgrade to PRO', 'accordion-and-accordion-slider').'</span>', 'manage_options', 'wp-aas-premium', array($this, 'wp_aas_premium_page') );

		add_submenu_page( 'edit.php?post_type='.WP_AAS_POST_TYPE, __('Upgrade To PRO - Accordion and Accordion Slider', 'accordion-and-accordion-slider'), '<span class="wp-aas-upgrade-pro" style="color:#ff2700">' . __('Upgrade To Premium ', 'accordion-and-accordion-slider') . '</span>', 'manage_options', WP_AAS_PLUGIN_LINK_UPGRADE );
		add_submenu_page( 'edit.php?post_type='.WP_AAS_POST_TYPE, __('Bundle Deal - Accordion and Accordion Slider', 'accordion-and-accordion-slider'), '<span class="wp-aas-upgrade-pro" style="color:#ff2700">' . __('Bundle Deal', 'accordion-and-accordion-slider') . '</span>', 'manage_options', WP_AAS_PLUGIN_BUNDLE_LINK );
	}

	/**
	 * Function to display plugin design HTML
	 * 
	 * @package Accordion and Accordion Slider
	 * @since 1.0.0
	 */
	function wp_aas_designs_page() { 
		include_once( WP_AAS_DIR . '/includes/admin/wp-aas-how-it-work.php' );
	}

	/**
	 * Getting Started Page Html
	 * 
	 * @package Accordion and Accordion Slider
	 * @since 1.0.0
	 */
	// function wp_aas_premium_page() {
	// 	include_once( WP_AAS_DIR . '/includes/admin/settings/premium.php' );
	// }

	/**
	 * Post Settings Metabox
	 * 
	 * @package accordion-and-accordion-slider
	 * @since 1.0.0
	 */
	function wp_aas_post_sett_metabox() {
		
		// Getting all post types
		$all_post_types = array(WP_AAS_POST_TYPE);
	
		add_meta_box( 'wp-aas-post-sett', __( 'Settings', 'accordion-and-accordion-slider' ), array($this, 'wp_aas_post_sett_mb_content'), $all_post_types, 'normal', 'high' );
		
		add_meta_box( 'wp-aas-post-slider-sett', __( 'Accordion Parameter', 'accordion-and-accordion-slider' ), array($this, 'wp_aas_post_slider_sett_mb_content'), $all_post_types, 'normal', 'default' );	
		
	}
	
	/**
	 * Post Settings Metabox HTML
	 * 
	 * @package accordion-and-accordion-slider
	 * @since 1.0.0
	 */
	function wp_aas_post_sett_mb_content() {
		include_once( WP_AAS_DIR .'/includes/admin/metabox/wp-aas-sett-metabox.php');
	}

	/**
	 * Post Settings Metabox HTML
	 * 
	 * @package accordion-and-accordion-slider
	 * @since 1.0.0
	 */
	function wp_aas_post_slider_sett_mb_content() {
		include_once( WP_AAS_DIR .'/includes/admin/metabox/wp-aas-slider-parameter.php');
	}
	
	/**
	 * Function to save metabox values
	 * 
	 * @package accordion-and-accordion-slider
	 * @since 1.0.0
	 */
	function wp_aas_save_metabox_value( $post_id ) {

		global $post_type;

		$registered_posts = array(WP_AAS_POST_TYPE); // Getting registered post types

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )					// Check Autosave
		|| ( ! isset( $_POST['post_ID'] ) || $post_id != $_POST['post_ID'] )	// Check Revision
		|| ( !current_user_can('edit_post', $post_id) )							// Check if user can edit the post.
		|| ( !in_array($post_type, $registered_posts) ) )						// Check if user can edit the post.
		{
		  return $post_id;
		}

		$prefix = WP_AAS_META_PREFIX; // Taking metabox prefix		

		// Taking variables
		$gallery_imgs 				= isset( $_POST['wp_aas_img'] ) 						? array_map( 'intval', (array) $_POST['wp_aas_img'] ) 			: '';

		// Getting Carousel Variables
		$width						= isset( $_POST[$prefix.'width'] )						? wp_aas_clean_number( $_POST[$prefix.'width'] )				: '';
		$height						= isset( $_POST[$prefix.'height'] )						? wp_aas_clean_number( $_POST[$prefix.'height'] )				: '';
		$image_size					= isset( $_POST[$prefix.'image_size'] )					? wp_aas_clean( $_POST[$prefix.'image_size'] )					: 'large';
		$visible_panels				= isset( $_POST[$prefix.'visible_panels'] )				? wp_aas_clean_number( $_POST[$prefix.'visible_panels'] )		: '';
		$orientation				= isset( $_POST[$prefix.'orientation'] )				? wp_aas_clean( $_POST[$prefix.'orientation'] )					: 'horizontal';
		$panel_distance				= isset( $_POST[$prefix.'panel_distance'] )				? wp_aas_clean_number( $_POST[$prefix.'panel_distance'] )		: '';
		$max_openedaccordion_size	= isset( $_POST[$prefix.'max_openedaccordion_size'] )	? wp_aas_clean( $_POST[$prefix.'max_openedaccordion_size'] )	: '';
		$open_panel_on				= isset( $_POST[$prefix.'open_panel_on'] )				? wp_aas_clean( $_POST[$prefix.'open_panel_on'] )				: 'hover';
		$autoplay					= isset( $_POST[$prefix.'autoplay'] )					? wp_aas_clean( $_POST[$prefix.'autoplay'] )					: 'true';
		$mouse_wheel				= isset( $_POST[$prefix.'mouse_wheel'] )				? wp_aas_clean( $_POST[$prefix.'mouse_wheel'] )					: 'false';
		$shadow						= isset( $_POST[$prefix.'shadow'] )						? wp_aas_clean( $_POST[$prefix.'shadow'] )						: 'true';

		// Style option update
		
		update_post_meta($post_id, $prefix.'gallery_id', $gallery_imgs);

		// Updating Carousel settings 
		update_post_meta( $post_id, $prefix.'width', $width );
		update_post_meta( $post_id, $prefix.'height', $height );
		update_post_meta( $post_id, $prefix.'image_size', $image_size );
		update_post_meta( $post_id, $prefix.'visible_panels', $visible_panels );
		update_post_meta( $post_id, $prefix.'shadow', $shadow );
		update_post_meta( $post_id, $prefix.'orientation', $orientation );
		update_post_meta( $post_id, $prefix.'panel_distance', $panel_distance );
		update_post_meta( $post_id, $prefix.'max_openedaccordion_size', $max_openedaccordion_size );
		update_post_meta( $post_id, $prefix.'open_panel_on', $open_panel_on );
		update_post_meta( $post_id, $prefix.'autoplay', $autoplay );
		update_post_meta( $post_id, $prefix.'mouse_wheel', $mouse_wheel );
	}

	/**
	 * Function register setings
	 * 
	 * @since 1.0.0
	 */
	function wp_aas_admin_init_process() {

		// If plugin notice is dismissed
		if( isset( $_GET['message'] ) && 'wp-aas-plugin-notice' == $_GET['message'] ) {
			set_transient( 'wp_aas_install_notice', true, 604800 );
		}

	}

	/**
	 * Add custom column to Post listing page
	 * 
	 * @package accordion-and-accordion-slider
	 * @since 1.0.0
	 */
	function wp_aas_posts_columns( $columns ) {

		$new_columns['wp_aas_shortcode'] 	= esc_html__( 'Shortcode', 'accordion-and-accordion-slider' );
		$new_columns['wp_aas_photos'] 		= esc_html__( 'Number of Photos', 'accordion-and-accordion-slider' );

		$columns = wp_aas_add_array( $columns, $new_columns, 1, true );

		return $columns;
	}

	/**
	 * Add custom column data to Post listing page
	 * 
	 * @package accordion-and-accordion-slider
	 * @since 1.0.0
	 */
	function wp_aas_post_columns_data( $column, $post_id ) {

		global $post;
		// Taking some variables
		$prefix = WP_AAS_META_PREFIX;

		switch ($column) {
			case 'wp_aas_shortcode':
					echo '<div class="wp-aas-shortcode-preview wpos-copy-clipboard">[aas_slider id="'.esc_attr($post_id).'"]</div>';
				break;

			case 'wp_aas_photos':
				$total_photos = get_post_meta($post_id, $prefix.'gallery_id', true);
				echo !empty($total_photos) ? count($total_photos) : '--';
				break;
		}
	}

	/**
	 * Function to add custom quick links at post listing page
	 * 
	 * @package accordion-and-accordion-slider
	 * @since 1.0.0
	 */
	function wp_aas_add_post_row_data( $actions, $post ) {

		if( $post->post_type == WP_AAS_POST_TYPE ) {
			return array_merge( array( 'wpos_id' => esc_html__('ID:', 'accordion-and-accordion-slider') .' '. esc_html( $post->ID ) ), $actions );
		}

		return $actions;
	}

	/**
	 * Image data popup HTML
	 * 
	 * @package accordion-and-accordion-slider
	 * @since 1.0.0
	 */
	function wp_aas_image_update_popup_html() {

		global $post_type, $wpos_upgrade_link_snippet;

		$registered_posts = array(WP_AAS_POST_TYPE); // Getting registered post types

		if( in_array($post_type, $registered_posts) ) {
			include_once( WP_AAS_DIR .'/includes/admin/settings/wp-aas-img-popup.php');
		}

		// Redirect to external page
		if( empty( $wpos_upgrade_link_snippet ) ) {

			$wpos_upgrade_link_snippet = 1;
			?>
			<script type="text/javascript">
				(function ($) {
					$('.wpos-upgrade-pro').parent().attr( { target: '_blank', rel: 'noopener noreferrer' } );
				})(jQuery);
			</script>
		<?php }
	}

	/**
	 * Get attachment edit form
	 * 
	 * @package accordion-and-accordion-slider
	 * @since 1.0.0
	 */
	function wp_aas_get_attachment_edit_form() {

		// Taking some defaults
		$result				= array();
		$result['success']	= 0;
		$result['msg']		= esc_js( __('Sorry, Something happened wrong.', 'accordion-and-accordion-slider') );
		$attachment_id		= ! empty( $_POST['attachment_id'] )	? wp_aas_clean( $_POST['attachment_id'] )	: '';
		$nonce				= ! empty( $_POST['nonce'] )			? wp_aas_clean( $_POST['nonce'] )	: '';

		if( ! empty( $attachment_id ) && wp_verify_nonce( $nonce, 'wp-aas-edit-attachment-data' ) ) {

			$attachment_post = get_post( $attachment_id );

			if( ! empty( $attachment_post )) {

				ob_start();

				// Popup Data File
				include( WP_AAS_DIR . '/includes/admin/settings/wp-aas-img-popup-data.php' );

				$attachment_data = ob_get_clean();

				$result['success']	= 1;
				$result['msg']		= esc_js( __( 'Attachment Found.', 'accordion-and-accordion-slider' ) );
				$result['data']		= $attachment_data;
			}
		}

		echo json_encode($result);
		exit;
	}

	/**
	 * Get attachment edit form
	 * 
	 * @package accordion-and-accordion-slider
	 * @since 1.0.0
	 */
	function wp_aas_save_attachment_data() {

		$prefix				= WP_AAS_META_PREFIX;
		$result				= array();
		$result['success']	= 0;
		$result['msg']		= esc_js( __( 'Sorry, Something happened wrong.', 'accordion-and-accordion-slider' ) );
		$attachment_id		= ! empty( $_POST['attachment_id'] ) 	? wp_aas_clean( $_POST['attachment_id'] )	: '';
		$nonce				= ! empty( $_POST['nonce'] )			? wp_aas_clean( $_POST['nonce'] )			: '';
		$form_data			= parse_str( $_POST['form_data'], $form_data_arr );

		if( ! empty( $attachment_id ) && ! empty( $form_data_arr ) && wp_verify_nonce( $nonce, "wp-aas-save-attachment-data-{$attachment_id}" ) ) {

			// Getting attachment post
			$wp_aas_attachment_post = get_post( $attachment_id );

			// If post type is attachment
			if( isset($wp_aas_attachment_post->post_type) && $wp_aas_attachment_post->post_type == 'attachment' ) {
				$post_args = array(
									'ID'			=> $attachment_id,
									'post_title'	=> ! empty( $form_data_arr['wp_aas_attachment_title'] )	? $form_data_arr['wp_aas_attachment_title'] : $wp_aas_attachment_post->post_name,									
									'post_excerpt'	=> $form_data_arr['wp_aas_attachment_caption'],
								);
				$update = wp_update_post( $post_args, $wp_error );

				if( ! is_wp_error( $update ) ) {

					update_post_meta( $attachment_id, '_wp_attachment_image_alt', wp_aas_clean( $form_data_arr['wp_aas_attachment_alt'] ));
					update_post_meta( $attachment_id, $prefix.'attachment_link', wp_aas_clean_url( $form_data_arr['wp_aas_attachment_link'] ));

					$result['success']	= 1;
					$result['msg']		= esc_js( __( 'Your changes saved successfully.', 'accordion-and-accordion-slider' ));
				}
			}
		}
		echo json_encode( $result );
		exit;
	}
}

$wp_aas_admin = new Wp_Aas_Admin();