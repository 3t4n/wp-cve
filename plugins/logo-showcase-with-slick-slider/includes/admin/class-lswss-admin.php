<?php
/**
 * Admin Class
 * Handles the Admin side functionality of plugin
 *
 * @package Logo Showcase with Slick Slider
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Lswss_Admin {

	function __construct() {

		// Action to register admin menu
		add_action( 'admin_menu', array( $this, 'lswss_register_menu' ) );

		// Action to add metabox
		add_action( 'add_meta_boxes', array($this, 'lswss_post_sett_metabox') );

		// Action to save metabox
		add_action( 'save_post_'.LSWSS_POST_TYPE, array($this, 'lswss_save_metabox_value') );

		// Action to add custom column to Gallery listing
		add_filter( 'manage_'.LSWSS_POST_TYPE.'_posts_columns', array($this, 'lswss_post_columns_heading') );

		// Action to add custom column data to Gallery listing
		add_action('manage_'.LSWSS_POST_TYPE.'_posts_custom_column', array($this, 'lswss_post_columns_data'), 10, 2);

		// Action to add Attachment Popup HTML
		add_action( 'admin_footer', array($this, 'lswss_image_update_popup_html') );

		// Ajax call to update option
		add_action( 'wp_ajax_lswss_get_attachment_edit_form', array($this, 'lswss_get_attachment_edit_form') );

		// Ajax call to update attachment data
		add_action( 'wp_ajax_lswss_save_attachment_data', array($this, 'lswss_save_attachment_data') );
	}

	/**
	 * Function to register admin menus
	 * 
	 * @since 1.0
	 */
	function lswss_register_menu() {

		// Style Manager
		add_submenu_page( 'edit.php?post_type='.LSWSS_POST_TYPE, __('Style Manager - Logo Showcase Pro', 'logo-showcase-with-slick-slider'), __('Style Manager', 'logo-showcase-with-slick-slider'), 'manage_options', 'lswssp-styles', array($this, 'lswss_style_manager_page') );
	}

	/**
	 * Plugin Style Manager Page
	 *
	 * @since 1.0
	 */
	function lswss_style_manager_page() {
		include_once( LSWSS_DIR . '/includes/style-manager/class-lswss-styles-list.php' );
	}

	/**
	 * Post Settings Metabox
	 * 
	 * @since 1.0
	 */
	function lswss_post_sett_metabox() {	
		
		// Post Sett Metabox
		add_meta_box( 'lswssp-post-sett', __( 'Logo Showcase with Slick Slider Settings', 'logo-showcase-with-slick-slider' ), array($this, 'lswss_post_sett_mb_content'), LSWSS_POST_TYPE, 'normal', 'high' );

		// Shortcode Metabox
		add_meta_box( 'lswssp-shortcode', __( 'Logo Showcase Shortcode', 'logo-showcase-with-slick-slider' ), array($this, 'lswss_shortcode_mb_content'), LSWSS_POST_TYPE, 'side');
	}

	/**
	 * Post Metabox Settings HTML
	 * 
	 * @since 1.0
	 */
	function lswss_post_sett_mb_content() {
		include_once( LSWSS_DIR .'/includes/admin/metabox/lswss-sett-metabox.php' );
	}

	/**
	 * Meta box to display shortcode hint
	 *
	 * @since 1.0
	*/
	function lswss_shortcode_mb_content( $post) {
		echo "<h3>" .__( 'Shortcode', 'logo-showcase-with-slick-slider'). "</h3>";
		echo "<p>" .__( 'To display Logo Showcase, add the following shortcode to your page or post.', 'logo-showcase-with-slick-slider' ). "</p>";
		echo '<div class="lswssp-shortcode-preview">[slick_logo_carousel id="'.esc_attr( $post->ID ).'"]</div>';
		echo "<h3>" .__( 'Template Code', 'logo-showcase-with-slick-slider'). "</h3>";
		echo "<p>" .__( 'If adding the Logo Showcase to your theme files, add the following template code.', 'logo-showcase-with-slick-slider' ). "</p>";
		echo '<div class="lswssp-shortcode-preview">&lt;?php echo do_shortcode(\'[slick_logo_carousel id="'.esc_attr( $post->ID ).'"]\'); ?&gt;</div>';
	}

	/**
	 * Function to save metabox values
	 * 
	 * @since 1.0
	 */
	function lswss_save_metabox_value( $post_id ) {

		global $post_type;

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )					// Check Autosave
		|| ( ! isset( $_POST['post_ID'] ) || $post_id != $_POST['post_ID'] )	// Check Revision
		|| ( ! current_user_can('edit_post', $post_id) ) )						// Check if user can edit the post.
		{
		  return $post_id;
		}

		// Taking Some Variables
		$prefix	= LSWSS_META_PREFIX; // Taking metabox prefix

		// Getting Post Settings
		$gallery_imgs 	= isset( $_POST['lswss_img']) 				? array_map( 'lswss_clean_number', $_POST['lswss_img'] )	: '';
		$display_type 	= isset( $_POST[$prefix.'display_type'] ) 	? lswss_clean( $_POST[$prefix.'display_type'] )			: 'slider';

		// Grid Settings
		$_POST[$prefix.'sett']['grid']['design']			= isset( $_POST[$prefix.'sett']['grid']['design'] ) 			? lswss_clean( $_POST[$prefix.'sett']['grid']['design'] ) 							: 'design-1';
		$_POST[$prefix.'sett']['grid']['show_title']		= isset( $_POST[$prefix.'sett']['grid']['show_title'] ) 		? lswss_clean( $_POST[$prefix.'sett']['grid']['show_title'] ) 						: 'false';
		$_POST[$prefix.'sett']['grid']['show_desc']			= isset( $_POST[$prefix.'sett']['grid']['show_desc'] ) 			? lswss_clean( $_POST[$prefix.'sett']['grid']['show_desc'] ) 						: 'false';
		$_POST[$prefix.'sett']['grid']['grid']				= isset( $_POST[$prefix.'sett']['grid']['grid'] ) 				? lswss_clean_number( $_POST[$prefix.'sett']['grid']['grid'], 5 )					: 5;
		$_POST[$prefix.'sett']['grid']['link_target']		= isset( $_POST[$prefix.'sett']['grid']['link_target'] ) 		? lswss_clean( $_POST[$prefix.'sett']['grid']['link_target'] ) 						: '_blank';
		$_POST[$prefix.'sett']['grid']['min_height']		= isset( $_POST[$prefix.'sett']['grid']['min_height'] ) 		? lswss_clean_number( $_POST[$prefix.'sett']['grid']['min_height'], '' )			: '';
		$_POST[$prefix.'sett']['grid']['max_height']		= isset( $_POST[$prefix.'sett']['grid']['max_height'] ) 		? lswss_clean_number( $_POST[$prefix.'sett']['grid']['max_height'], 200 )			: 200;
		$_POST[$prefix.'sett']['grid']['ipad']				= isset( $_POST[$prefix.'sett']['grid']['ipad'] ) 				? lswss_clean_number( $_POST[$prefix.'sett']['grid']['ipad'], '' )					: '';
		$_POST[$prefix.'sett']['grid']['tablet']			= isset( $_POST[$prefix.'sett']['grid']['tablet'] ) 			? lswss_clean_number( $_POST[$prefix.'sett']['grid']['tablet'], '' )				: '';
		$_POST[$prefix.'sett']['grid']['mobile']			= isset( $_POST[$prefix.'sett']['grid']['mobile'] ) 			? lswss_clean_number( $_POST[$prefix.'sett']['grid']['mobile'], '' )				: '';

		// Slider Settings
		$_POST[$prefix.'sett']['slider']['design']				= isset( $_POST[$prefix.'sett']['slider']['design'] ) 				? lswss_clean( $_POST[$prefix.'sett']['slider']['design'] ) 						: 'design-1';
		$_POST[$prefix.'sett']['slider']['show_title']			= isset( $_POST[$prefix.'sett']['slider']['show_title'] ) 			? lswss_clean( $_POST[$prefix.'sett']['slider']['show_title'] ) 					: 'false';
		$_POST[$prefix.'sett']['slider']['show_desc']			= isset( $_POST[$prefix.'sett']['slider']['show_desc'] ) 			? lswss_clean( $_POST[$prefix.'sett']['slider']['show_desc'] ) 						: 'false';
		$_POST[$prefix.'sett']['slider']['link_target']			= isset( $_POST[$prefix.'sett']['slider']['link_target'] ) 			? lswss_clean( $_POST[$prefix.'sett']['slider']['link_target'] ) 					: '_blank';
		$_POST[$prefix.'sett']['slider']['min_height']			= isset( $_POST[$prefix.'sett']['slider']['min_height'] ) 			? lswss_clean_number( $_POST[$prefix.'sett']['slider']['min_height'], '' )			: '';
		$_POST[$prefix.'sett']['slider']['max_height']			= isset( $_POST[$prefix.'sett']['slider']['max_height'] ) 			? lswss_clean_number( $_POST[$prefix.'sett']['slider']['max_height'], 200 )			: 200;
		$_POST[$prefix.'sett']['slider']['slides_show']			= isset( $_POST[$prefix.'sett']['slider']['slides_show'] ) 			? lswss_clean_number( $_POST[$prefix.'sett']['slider']['slides_show'], 5 )			: 5;
		$_POST[$prefix.'sett']['slider']['slides_scroll']		= isset( $_POST[$prefix.'sett']['slider']['slides_scroll'] ) 		? lswss_clean_number( $_POST[$prefix.'sett']['slider']['slides_scroll'], 1 ) 		: 1;
		$_POST[$prefix.'sett']['slider']['arrow']				= isset( $_POST[$prefix.'sett']['slider']['arrow'] ) 				? lswss_clean( $_POST[$prefix.'sett']['slider']['arrow'] )							: 'true';
		$_POST[$prefix.'sett']['slider']['dots']				= isset( $_POST[$prefix.'sett']['slider']['dots'] ) 				? lswss_clean( $_POST[$prefix.'sett']['slider']['dots'] )							: 'true';
		$_POST[$prefix.'sett']['slider']['pause_on_hover']		= isset( $_POST[$prefix.'sett']['slider']['pause_on_hover'] ) 		? lswss_clean( $_POST[$prefix.'sett']['slider']['pause_on_hover'] )					: 'true';
		$_POST[$prefix.'sett']['slider']['autoplay']			= isset( $_POST[$prefix.'sett']['slider']['autoplay'] ) 			? lswss_clean( $_POST[$prefix.'sett']['slider']['autoplay'] )						: 'true';
		$_POST[$prefix.'sett']['slider']['autoplay_speed']		= isset( $_POST[$prefix.'sett']['slider']['autoplay_speed'] ) 		? lswss_clean_number( $_POST[$prefix.'sett']['slider']['autoplay_speed'], 3000 )	: 3000;
		$_POST[$prefix.'sett']['slider']['speed']				= isset( $_POST[$prefix.'sett']['slider']['speed'] ) 				? lswss_clean_number( $_POST[$prefix.'sett']['slider']['speed'], 600 )				: 600;
		$_POST[$prefix.'sett']['slider']['loop']				= isset( $_POST[$prefix.'sett']['slider']['loop'] ) 				? lswss_clean( $_POST[$prefix.'sett']['slider']['loop'] )							: 'true';
		$_POST[$prefix.'sett']['slider']['centermode']			= isset( $_POST[$prefix.'sett']['slider']['centermode'] ) 			? lswss_clean( $_POST[$prefix.'sett']['slider']['centermode'] )						: 'false';
		$_POST[$prefix.'sett']['slider']['center_padding']		= isset( $_POST[$prefix.'sett']['slider']['center_padding'] ) 		? lswss_clean_number( $_POST[$prefix.'sett']['slider']['center_padding'], '' )		: '';
		$_POST[$prefix.'sett']['slider']['ipad']				= isset( $_POST[$prefix.'sett']['slider']['ipad'] ) 				? lswss_clean_number( $_POST[$prefix.'sett']['slider']['ipad'], '' )				: '';
		$_POST[$prefix.'sett']['slider']['tablet']				= isset( $_POST[$prefix.'sett']['slider']['tablet'] ) 				? lswss_clean_number( $_POST[$prefix.'sett']['slider']['tablet'], '' )				: '';
		$_POST[$prefix.'sett']['slider']['mobile']				= isset( $_POST[$prefix.'sett']['slider']['mobile'] ) 				? lswss_clean_number( $_POST[$prefix.'sett']['slider']['mobile'], '' )				: '';


		// Tab Location
		$_POST[$prefix.'sett']['tab'] = isset( $_POST[$prefix.'sett']['tab'] ) ? lswss_clean( $_POST[$prefix.'sett']['tab'] ) : '';

		// Update Meta Settings
		update_post_meta( $post_id, $prefix.'gallery_id', $gallery_imgs );
		update_post_meta( $post_id, $prefix.'display_type', $display_type );
		update_post_meta( $post_id, $prefix.'sett', $_POST[$prefix.'sett'] );
	}

	/**
	 * Add custom column to Post listing page
	 * 
	 * @since 1.0
	 */
	function lswss_post_columns_heading( $columns ) {

		$new_columns['lswss_shortcode'] = __('Shortcode', 'logo-showcase-with-slick-slider');

		$columns = lswss_add_array( $columns, $new_columns, 1, true );

		return $columns;
	}

	/**
	 * Add custom column data to Post listing page
	 * 
	 * @since 1.0
	 */
	function lswss_post_columns_data( $column, $post_id ) {

		// Taking some variables
		$prefix = LSWSS_META_PREFIX;

		$slider_style = get_post_meta( $post_id, $prefix.'design_style', true );
		
		switch ( $column ) {
			case 'lswss_shortcode':
				echo '<div class="lswssp-shortcode-preview">[slick_logo_carousel id="'.$post_id.'"]</div>';
				break;
		}
	}

	/**
	 * Include Logo Edit Data Popup File
	 * 
	 * @since 1.0
	 */
	function lswss_image_update_popup_html() {

		global $post_type, $pagenow;

		if( $post_type == LSWSS_POST_TYPE && ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) ) {
			include_once( LSWSS_DIR .'/includes/admin/metabox/popup/lswss-img-popup.php' );
		}
	}

	/**
	 * Get attachment edit form
	 * 
	 * @since 1.0
	 */
	function lswss_get_attachment_edit_form() {

		// Taking some defaults
		$result 		= array(
								'success'	=> 0,
								'msg'		=> __('Sorry, Something happened wrong.', 'logo-showcase-with-slick-slider')
							);
		$attachment_id 	= ! empty( $_POST['attachment_id'] )	? lswss_clean_number( $_POST['attachment_id'] ) : '';
		$nonce			= ! empty( $_POST['nonce'] )			? lswss_clean( $_POST['nonce'] )				: '';

		if( ! empty( $attachment_id ) ) {

			// Check nonce
			if ( ! isset( $nonce ) || ! wp_verify_nonce( $nonce, 'lswss_get_attachment_data_nonce' ) ) {
				wp_send_json( $result );
			}

			// Check current user can edit the attachment or not
			if ( ! current_user_can( 'edit_post', $attachment_id ) ) {
				$result['msg'] = __('Sorry, You are not allowed to edit the attachment.', 'logo-showcase-with-slick-slider');
				wp_send_json( $result );
			}

			$attachment_post = get_post( $_POST['attachment_id'] );

			// If post type is attachment
			if( isset( $attachment_post->post_type ) && $attachment_post->post_type == 'attachment' ) {
				
				ob_start();

				// Popup Data File
				include( LSWSS_DIR . '/includes/admin/metabox/popup/lswss-img-popup-data.php' );

				$attachment_data = ob_get_clean();

				$result['success'] 	= 1;
				$result['msg'] 		= __('Attachment Found.', 'logo-showcase-with-slick-slider');
				$result['data']		= $attachment_data;
			}
		}

		wp_send_json( $result );
	}

	/**
	 * Save attachment data via popup
	 * 
	 * @since 1.0
	 */
	function lswss_save_attachment_data() {

		// Taking some variables
		$prefix 			= LSWSS_META_PREFIX;
		$result 			= array(
								'success'	=> 0,
								'msg'		=> __('Sorry, Something happened wrong.', 'logo-showcase-with-slick-slider')
							);
		$attachment_id 		= ! empty( $_POST['attachment_id'] ) ? lswss_clean_number( $_POST['attachment_id'] ) : '';
		$form_data 			= parse_str( $_POST['form_data'], $form_data_arr );

		if( ! empty( $attachment_id ) && ! empty( $form_data_arr ) ) {

			// Check nonce
			if ( ! isset( $form_data_arr['lswss_nonce'] ) || ! wp_verify_nonce( $form_data_arr['lswss_nonce'], 'lswss_save_attachment_data_nonce_' . $attachment_id ) ) {
				wp_send_json( $result );
			}

			// Check current user can edit the attachment or not
			if ( ! current_user_can( 'edit_post', $attachment_id ) ) {
				$result['msg'] = __('Sorry, You are not allowed to edit the attachment.', 'logo-showcase-with-slick-slider');
				wp_send_json( $result );
			}

			// Getting attachment post
			$lswss_attachment_post	= get_post( $attachment_id );
			$attachment_alt			= isset( $form_data_arr['lswss_attachment_alt'] )	? lswss_clean( $form_data_arr['lswss_attachment_alt'] )			: '';
			$attachment_link		= isset( $form_data_arr['lswss_attachment_link'] )	? lswss_clean_url( $form_data_arr['lswss_attachment_link'] )	: '';

			// If post type is attachment
			if( isset( $lswss_attachment_post->post_type ) && $lswss_attachment_post->post_type == 'attachment' ) {
				
				$post_args = array(
									'ID'			=> $attachment_id,
									'post_title'	=> !empty($form_data_arr['lswss_attachment_title']) ? $form_data_arr['lswss_attachment_title'] : $lswss_attachment_post->post_name,
									'post_content'	=> lswss_clean_html( $form_data_arr['lswss_attachment_desc'] ),
								);
				$update = wp_update_post( $post_args );

				if( ! is_wp_error( $update ) ) {

					update_post_meta( $attachment_id, '_wp_attachment_image_alt', $attachment_alt );
					update_post_meta( $attachment_id, $prefix.'attachment_link', $attachment_link );

					$result['success'] 	= 1;
					$result['msg'] 		= __('Changes saved successfully.', 'logo-showcase-with-slick-slider');
				}
			}
		}

		wp_send_json( $result );
	}
}

$lswss_admin = new lswss_Admin();