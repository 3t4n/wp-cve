<?php
/**
 * Public Class
 *
 * Handles the Public side functionality of plugin
 *
 * @package Popup Anything on Click
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class POPUPAOC_Public {

	function __construct() {

		// Render Popup
		add_action( 'wp_footer', array($this, 'popupaoc_render_popup') );
	}

	/**
	 * Function to render popup
	 * 
	 * @since 1.4
	 */
	function popupaoc_render_popup() {

		global $post, $paoc_preview, $paoc_popup_data;

		// If preview is there then simple bypass it
		if( $paoc_preview ) {
			$this->popupaoc_create_popup();
			return false;
		}

		// Taking some data
		$skip_gen_popup	= array();
		$prefix			= POPUPAOC_META_PREFIX;
		$enable			= popupaoc_get_option( 'enable' );
		$enable			= apply_filters('popupaoc_render_popup', $enable, $post );

		// If not globally enable
		if ( ! $enable ) {
			return false;
		}

		/***** Start - Display Welcome Popup *****/
		$welcome_popup			= popupaoc_get_option( 'welcome_popup' );
		$welcome_display_in		= popupaoc_get_option( 'welcome_display_in' );
		$enable_welcome_popup	= popupaoc_check_active( $welcome_display_in );

		// Render Popup
		if( $welcome_popup > 0 && $enable_welcome_popup ) {
			$this->popupaoc_create_popup( $welcome_popup );
		}
		/***** End - Display Welcome Popup *****/

		// Check for Popup( Simple Link, Button & Image )
		if( ! empty( $paoc_popup_data ) ) {

			foreach ( $paoc_popup_data as $popup_id => $popup_data ) {

				$paoc_popup_data[ $popup_id ]['render'] = 1;

				$this->popupaoc_create_popup( $popup_id );
			}

			$paoc_popup_data = ''; // Flushing the global popup data variable
		}
	}

	/**
	 * Function to create nested popup HTML
	 * 
	 * @since 2.0.5
	 */
	function popupaoc_render_nested_popup( $paoc_global_diff = array() ) {

		global $paoc_popup_data;

		// Check for Popup( Simple Link, Button & Image )
		if( ! empty( $paoc_global_diff ) ) {

			foreach ( $paoc_global_diff as $popup_id => $popup_data ) {

				// Continue already rendered popup
				if ( ! empty( $popup_data['render'] ) ) {
					continue;
				}

				$paoc_popup_data[ $popup_id ]['render'] = 1;

				$this->popupaoc_create_popup( $popup_id );
			}
		}
	}

	/**
	 * Function to create popup HTML
	 * 
	 * @since 1.4
	 */
	function popupaoc_create_popup( $popup_id = 0 ) {

		global $paoc_preview, $paoc_design_sett, $paoc_behaviour_sett, $paoc_advance_sett, $paoc_custom_css, $paoc_popup_post, $paoc_popup_data, $popupaoc_options;

		$paoc_popup_data = $paoc_popup_data ? $paoc_popup_data : array();

		// Store popup global data in temp 1 variable before `do_shortcode`
		$paoc_global_temp_1 = $paoc_popup_data;

		// If Popup Preview is there
		if( $paoc_preview == 1 ) {
			$popup_id = isset( $_POST['paoc_preview_popup_id'] ) ? popupaoc_clean_number( $_POST['paoc_preview_popup_id'] ) : $popup_id;
		}

		// Return If no popup ID is there
		if( empty( $popup_id ) ) {
			return false;
		}

		// Query args
		$args = array(
				'post_type'			=> POPUPAOC_POST_TYPE,
				'post__in'			=> array( $popup_id ),
				'post_status'		=> ( ! $paoc_preview ) ? array( 'publish' ) : array( 'any', 'auto-draft', 'inherit', 'trash' ),
				'posts_per_page'	=> 1,
				'no_found_rows'		=> true,
			);

		// WP Query for Popup
		$popup_query = get_posts( $args );

		// If no query post found
		if ( ! $popup_query ) {
			return false;
		}

		// Taking some data
		$animateFrom		= 'top';
		$animateTo			= 'top';
		$prefix				= POPUPAOC_META_PREFIX;
		$cookie_prefix		= popupaoc_get_option( 'cookie_prefix' );
		$behaviour			= popupaoc_get_meta( $popup_id, $prefix.'behaviour' );
		$popup_appear		= popupaoc_get_meta( $popup_id, $prefix.'popup_appear' );
		$design 			= popupaoc_get_meta( $popup_id, $prefix.'design' );
		$content			= popupaoc_get_meta( $popup_id, $prefix.'content' );
		$custom_css 		= popupaoc_get_meta( $popup_id, $prefix.'custom_css' );
		$advance 			= popupaoc_get_meta( $popup_id, $prefix.'advance' );
		$popup_goal			= 'announcement';
		$display_type		= 'modal';

		// Assign value to global var
		$paoc_popup_post		= $popup_query[0];
		$paoc_design_sett		= $design;
		$paoc_behaviour_sett	= $behaviour;
		$paoc_advance_sett		= $advance;
		$paoc_custom_css		= $custom_css;

		// Taking design data
		$template			= 'design-1';
		$width				= isset( $design['width'] )					? $design['width']					: '';
		$height				= isset( $design['height'] )				? $design['height']					: '';
		$effect				= ! empty( $design['effect'] )				? $design['effect']					: 'fadein';
		$loader_color		= ! empty( $design['loader_color'] )		? $design['loader_color']			: '#000000';
		$speed_in			= ! empty( $design['speed_in'] )			? ( $design['speed_in'] * 1000 )	: 0.5;
		$speed_out			= ! empty( $design['speed_out'] )			? ( $design['speed_out'] * 1000 )	: 0.25;
		$fullscreen_popup	= ! empty( $design['fullscreen_popup'] )	? true								: false;

		// Taking Behaviour data
		$open_delay			= ! empty( $behaviour['open_delay'] )		? ( $behaviour['open_delay'] * 1000 )	: 0.3;
		$loader_speed		= ! empty( $behaviour['loader_speed'] )		? ( $behaviour['loader_speed'] * 1000 )	: 1;
		$disappear			= ! empty( $behaviour['disappear'] )		? ( $behaviour['disappear'] )			: 0;
		$enable_loader		= ! empty( $behaviour['enable_loader'] )	? true	: false;
		$hide_close 		= ! empty( $behaviour['hide_close'] )		? true	: false;
		$clsonesc			= ! empty( $behaviour['clsonesc'] )			? true	: false;
		$close_overlay		= ! empty( $behaviour['close_overlay'] )	? true	: false;
		$hide_overlay		= ! empty( $behaviour['hide_overlay'] )		? false	: true;

		$popup_position		= '';
		$type				= 'popup';
		$popup_appear_cls	= str_replace('_', '-', $popup_appear);
		$unique				= popupaoc_get_unique();
		$style				= popupaoc_generate_popup_style( $popup_id );
		$popup_classes		= "paoc-popup-{$popup_id} paoc-popup-{$popup_appear_cls} paoc-popup-{$popup_goal} paoc-popup-{$popup_goal}-{$template} paoc-{$template}";

		// Taking content data
		$main_heading	= isset( $content['main_heading'] )		? $content['main_heading']		: '';
		$sub_heading	= isset( $content['sub_heading'] )		? $content['sub_heading']		: '';
		$cust_close_txt	= isset( $content['cust_close_txt'] )	? $content['cust_close_txt']	: '';
		$security_note	= isset( $content['security_note'] )	? $content['security_note']		: '';

		// Advance Tab Data
		$cookie_expire	= isset( $advance['cookie_expire'] )	? $advance['cookie_expire']	: '';
		$cookie_unit	= ! empty( $advance['cookie_unit'] )	? 'day' 					: 'day';
		$show_credit	= ! empty( $advance['show_credit'] )	? 1							: 0;
		$credit_link	= add_query_arg( array('utm_source' => site_url(), 'utm_medium' => 'popup', 'utm_campaign' => 'credit-link'), POPUPAOC_SITE_LINK );

		// Taking some variable
		$width = ! empty( $fullscreen_popup ) ? '100%' : $width;

		/* Height is there */
		if( $height ) {
			$popup_classes .= " paoc-popup-height";
		}

		// If full screen popup is there
		if( $fullscreen_popup ) {
			$popup_classes .= " paoc-popup-fullscreen";
		}

		/* Display Type : Modal */
		if( $display_type == 'modal' ) {

			$popup_classes	.= " paoc-popup-js";
			$popup_position = ! empty( $design['mn_position'] ) ? $design['mn_position'] : 'center-center';

			$popup_position	= explode('-', $popup_position);
			$positionX		= $popup_position[0];
			$positionY		= $popup_position[1];
		}

		// If disappear value is negative
		if( $disappear < 0 ) {
			$disappear_mode = 'force';
			$disappear		= abs( $disappear );
		} else { // Else disappear value is possitive
			$disappear_mode = 'normal';
		}

		// Creating Popup Configuration
		$data_conf = array(
						'id'				=> $popup_id,
						'popup_type'		=> $popup_appear,
						'display_type'		=> $display_type,
						'disappear'			=> $disappear,
						'disappear_mode'	=> $disappear_mode,
						'open_delay'		=> $open_delay,
						'cookie_prefix'		=> $cookie_prefix,
						'cookie_expire'		=> $cookie_expire,
						'cookie_unit'		=> $cookie_unit,
					);

		// Creating Popup Configuration
		$popup_conf = array();
		$popup_conf['content'] = array(
									'target'		=> "#paoc-popup-{$popup_id}-{$unique}",
									'effect' 		=> $effect,
									'positionX'		=> $positionX,
									'positionY'		=> $positionY,
									'fullscreen'	=> $fullscreen_popup,
									'speedIn'		=> $speed_in,
									'speedOut'		=> $speed_out,
									'close'			=> $clsonesc,
									'animateFrom'	=> $animateFrom,
									'animateTo'		=> $animateTo,
								);
		$popup_conf['loader'] = array(
									'active'	=> $enable_loader,
									'color'		=> $loader_color,
									'speed'		=> $loader_speed,
								);
		$popup_conf['overlay'] = array(
									'active' 	=> $hide_overlay,
									'color'		=> 'rgba(0, 0, 0, 0.5)',
									'close' 	=> $close_overlay,
									'opacity'	=> 1,
								);

		$data_conf	= htmlspecialchars( json_encode( $data_conf ) );
		$popup_conf	= htmlspecialchars( json_encode( $popup_conf ) );

		// Set Data Attribute
		$popup_attr = "data-popup-conf='{$popup_conf}' data-conf='{$data_conf}' data-id='paoc-popup-{$popup_id}'";

		$design_file_path 	= POPUPAOC_DIR . '/templates/' . $popup_goal .'/'. $template . '.php';
		$design_file_path 	= ( file_exists( $design_file_path ) ) ? $design_file_path : '';

		// Enquque Scripts
		wp_enqueue_script('jquery');

		if( empty( $popupaoc_options['add_js'] ) ) {
			wp_enqueue_script('wpos-custombox-legacy-js');
		}

		wp_enqueue_script('wpos-custombox-popup-js');	// Custombox JS
		wp_enqueue_script('popupaoc-public-js');		// Public JS
		popupaoc_enqueue_script();

		// Print Inline Style
		echo "<style type='text/css'>".wp_strip_all_tags( $style['inline'] )."</style>";

		// If Popup Preview is there
		if( $paoc_preview == 1 ) {
			$popup_content = popupaoc_render_popup_content( $_POST['paoc_preview_form_data']['content'] );
		} else {
			$popup_content = popupaoc_render_popup_content( $popup_query[0]->post_content );
		}
		$secondary_content	= isset( $content['secondary_content'] ) ? popupaoc_render_popup_content( $content['secondary_content'] ) : '';

		// Include design html file
		if( $design_file_path ) {
			include( $design_file_path );
		}

		// Store global popup data in temp 2 variable after `do_shortcode`
		$paoc_global_temp_2	= $paoc_popup_data;

		// Temp 1 & Temp 2 array difference
		$paoc_global_diff = array_diff_key( $paoc_global_temp_2, $paoc_global_temp_1 );

		// Render nested popup
		if( ! empty( $paoc_global_diff ) ) {
			$this->popupaoc_render_nested_popup( $paoc_global_diff );
		}

		// Flush some global var
		$paoc_popup_post = '';
	}
}

$popupaoc_public = new POPUPAOC_Public();