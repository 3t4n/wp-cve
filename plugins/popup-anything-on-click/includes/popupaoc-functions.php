<?php
/**
 * Plugin generic functions file
 *
 * @package Popup Anything On Click
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Set Settings Default Option Page
 * 
 * Handles to return all settings value
 *
 * @package Popup Anything On Click
 * @since 1.6.1
 */
function popupaoc_default_settings() {

	global $popupaoc_options;

	$default_options = array(
						'add_js'				=> '',
						'enable'				=> 1,
						'cookie_prefix'			=> 'paoc_popup',
						'welcome_popup'			=> '',
						'welcome_display_in'	=> array(),
					);

	$default_options = apply_filters('popupaoc_default_settings', $default_options );

	// Update default options
	update_option( 'popupaoc_options', $default_options );

	// Overwrite global variable when option is update
	$popupaoc_options = popupaoc_get_settings();
}

/**
 * Get Settings From Option Page
 * 
 * Handles to return all settings value
 * 
 * @package Popup Anything On Click
 * @since 1.6.1
 */
function popupaoc_get_settings() {
	
	$options	= get_option('popupaoc_options');
	$settings	= is_array( $options ) ? $options : array();

	return $settings;
}

/**
 * Get an option
 * Looks to see if the specified setting exists, returns default if not
 * 
 * @package Popup Anything On Click
 * @since 1.6.1
 */
function popupaoc_get_option( $key = '', $default = false ) {

	global $popupaoc_options;

	$value = ! empty( $popupaoc_options[ $key ] ) ? $popupaoc_options[ $key ] : $default;
	$value = apply_filters( 'popupaoc_get_option', $value, $key, $default );

	return apply_filters( 'popupaoc_get_option_' . $key, $value, $key, $default );
}

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 * 
 * @package Popup Anything on Click
 * @since 1.0
 */
function popupaoc_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'popupaoc_clean', $var );
	} else {
		$data = is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		return wp_unslash( $data );
	}
}

/**
 * Sanitize number value and return fallback value if it is blank
 * 
 * @package Popup Anything on Click
 * @since 1.0
 */
function popupaoc_clean_number( $var, $fallback = null, $type = 'int' ) {

	$var = trim( $var );
	$var = is_numeric( $var ) ? $var : 0;

	if ( $type == 'number' ) {
		$data = intval( $var );
	} else if ( $type == 'abs' ) {
		$data = abs( $var );
	} else if ( $type == 'float' ) {
		$data = (float)$var;
	} else {
		$data = absint( $var );
	}

	return ( empty( $data ) && isset( $fallback ) ) ? $fallback : $data;
}

/**
 * Sanitize URL
 * 
 * @package Popup Anything on Click
 * @since 1.0
 */
function popupaoc_clean_url( $url ) {
	return esc_url_raw( trim( $url ) );
}

/**
 * Sanitize Hex Color
 * 
 * @package Popup Anything on Click
 * @since 1.2.2
 */
function popupaoc_clean_color( $color, $fallback = null ) {

	if ( false === strpos( $color, 'rgba' ) ) {
		$data = sanitize_hex_color( $color );
	} else {
		$red	= 0;
		$green	= 0;
		$blue	= 0;
		$alpha	= 0.5;

		// By now we know the string is formatted as an rgba color so we need to further sanitize it.
		$color = str_replace( ' ', '', $color );
		sscanf( $color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
		$data = 'rgba('.$red.','.$green.','.$blue.','.$alpha.')';
	}

	return ( empty( $data ) && $fallback ) ? $fallback : $data;
}

/**
 * Allow Valid Html Tags
 * It will sanitize HTML (strip script and style tags)
 *
 * @package Popup Anything on Click
 * @since 1.0
 */
function popupaoc_clean_html( $data = array() ) {

	if ( is_array( $data ) ) {

		$data = array_map( 'paoc_pro_clean_html', $data );

	} elseif ( is_string( $data ) ) {
		$data = trim( $data );
		$data = wp_filter_post_kses( $data );
	}

	return $data;
}

/**
 * Strip Html Tags
 * It will sanitize text input (strip html tags, and escape characters)
 * 
 * @package Popup Anything on Click
 * @since 1.0
 */
function popupaoc_nohtml_kses( $data = array() ) {

	if ( is_array( $data ) ) {

	$data = array_map('popupaoc_nohtml_kses', $data);

	} elseif ( is_string( $data ) ) {
		$data = trim( $data );
		$data = wp_filter_nohtml_kses( $data );
	}

	return $data;
}

/**
 * Sanitize Multiple HTML class
 * 
 * @package Popup Anything on Click
 * @since 2.0.2
 */
function popupaoc_sanitize_html_classes( $classes, $sep = " " ) {
	$return = "";

	if( $classes && ! is_array( $classes ) ) {
		$classes = explode( $sep, $classes );
	}

	if( ! empty( $classes ) ) {
		foreach( $classes as $class ){
			$return .= sanitize_html_class( $class ) . " ";
		}
		$return = trim( $return );
	}

	return $return;
}

/**
 * Function to add array after specific key
 * 
 * @package Popup Anything on Click
 * @since 1.0
 */
function popupaoc_add_array( &$array, $value, $index, $from_last = false ) {

	if( is_array( $array ) && is_array( $value ) ) {

		if( $from_last ) {
			$total_count    = count( $array );
			$index          = ( ! empty( $total_count ) && ( $total_count > $index ) ) ? ( $total_count - $index ): $index;
		}
		
		$split_arr  = array_splice( $array, max( 0, $index ) );
		$array      = array_merge( $array, $value, $split_arr );
	}
	
	return $array;
}

/**
 * Function to get unique value number
 * 
 * @package Popup Anything on Click
 * @since 1.0
 */
function popupaoc_get_unique() {
	static $unique = 0;
	$unique++;

	return $unique;
}

/**
 * Function to get popup appearance options
 * 
 * @since 2.0
 */
function popupaoc_when_appear_options() {

	$popup_appear = array(
						'page_load'		=> __('Page Load', 'popup-anything-on-click'),
						'simple_link'	=> __('Simple Link', 'popup-anything-on-click'),
						'image'			=> __('Image Click', 'popup-anything-on-click'),
						'button'		=> __('Button Click', 'popup-anything-on-click'),
						'inactivity'	=> __('After X Second of Inactivity (PRO)', 'popup-anything-on-click'),
						'scroll'		=> __('When Page Scroll Down (PRO)', 'popup-anything-on-click'),
						'scroll_up'		=> __('When Page Scroll UP (PRO)', 'popup-anything-on-click'),
						'exit'			=> __('Exit Intent (PRO)', 'popup-anything-on-click'),
						'html_element'	=> __('HTML Element Click (PRO)', 'popup-anything-on-click'),
					);

	return apply_filters('popuppaoc_when_appear_options', $popup_appear );
}

/**
 * When popup goal function
 * 
 * @since 2.0
 */
function popupaoc_popup_goals() {

	$popup_goals = array(
						'announcement'	=>	array(
												'name'	=> __('Announcement', 'popup-anything-on-click'),
												'icon'	=> "dashicons dashicons-megaphone",
											),
						'email-lists'	=>	array(
												'name'	=> __('Collect Lead', 'popup-anything-on-click'),
												'icon'	=> "dashicons dashicons-email-alt",
											),
						'target-url'	=>	array(
												'name'	=> __('Target URL', 'popup-anything-on-click'),
												'icon'	=> "dashicons dashicons-admin-links",
											),
						'phone-calls'	=>	array(
												'name'	=> __('Phone Calls', 'popup-anything-on-click'),
												'icon'	=> "dashicons dashicons-phone",
											),
					);

	return apply_filters('popupaoc_popup_goals', $popup_goals );
}

/**
 * When popup type function
 * 
 * @since 2.0
 */
function popupaoc_popup_types() {

	$popup_types = array(
						'modal'				=>	array(
													'name'	=> __('Modal Popup', 'popup-anything-on-click'),
													'icon'	=> "dashicons dashicons-admin-page",
												),
						'bar'				=>	array(
													'name'	=> __('Bar', 'popup-anything-on-click'),
													'icon'	=> "dashicons dashicons-schedule",
												),
						'push-notification'	=>	array(
													'name'	=> __('Push Notification', 'popup-anything-on-click'),
													'icon'	=> "dashicons dashicons-admin-comments",
												),
						'slide-in'			=>	array(
													'name'	=> __('Slide In', 'popup-anything-on-click'),
													'icon'	=> "dashicons dashicons-align-right",
												),
					);

	return apply_filters('popupaoc_popup_types', $popup_types );
}

/**
 * Function to get popup effects
 * 
 * @package Popup Anything on Click
 * @since 1.0
 */
function popupaoc_popup_effects() {
	$popup_effect = array(
						'fadein'		=> __('Fadein', 'popup-anything-on-click'),
						'slide'			=> __('Slide', 'popup-anything-on-click'),
						'newspaper'		=> __('Newspaper', 'popup-anything-on-click'),
						'superscaled'	=> __('Super Scaled', 'popup-anything-on-click'),
						'corner'		=> __('Corner', 'popup-anything-on-click'),
						'scale'			=> __('Scale', 'popup-anything-on-click'),
						'slidetogether'	=> __('Slide Together', 'popup-anything-on-click'),
					);
	return apply_filters('popupaoc_popup_effects', $popup_effect );
}

/**
 * Function to get modal & push notification popup position options
 * 
 * @since 1.0
 */
function popupaoc_position_options() {

	$position_option = array(
							'left-top'		=> __('Left Top', 'popup-anything-on-click'),
							'left-center'	=> __('Left Center', 'popup-anything-on-click'),
							'left-bottom'	=> __('Left Bottom', 'popup-anything-on-click'),
							'center-top'	=> __('Center Top', 'popup-anything-on-click'),
							'center-center'	=> __('Center Center', 'popup-anything-on-click'),
							'center-bottom'	=> __('Center Bottom', 'popup-anything-on-click'),
							'right-top'		=> __('Right Top', 'popup-anything-on-click'),
							'right-center'	=> __('Right Center', 'popup-anything-on-click'),
							'right-bottom'	=> __('Right Bottom', 'popup-anything-on-click'),
						);
	return apply_filters('popupaoc_position_options', $position_option );
}

/**
 * Function to get time options
 * 
 * @since 2.0
 */
function popupaoc_time_options() {

	$time_options = array(
					'day'		=> __('Days', 'popup-anything-on-click'),
					'hour'		=> __('Hours (PRO)', 'popup-anything-on-click'),
					'minutes'	=> __('Minutes (PRO)', 'popup-anything-on-click'),
				);
	return apply_filters( 'popupaoc_time_options', $time_options );
}

/**
 * Function to enqueue public script at last
 * 
 * @package Popup Anything on Click
 * @since 2.0
 */
function popupaoc_enqueue_script() {

	if( wp_script_is( 'popupaoc-public-js', 'enqueued' ) ) {

		// Dequeue Public JS
		wp_dequeue_script( 'popupaoc-public-js' );

		// Enqueue Public JS
		wp_enqueue_script('popupaoc-public-js');
	}
}

/**
 * Function to display message, norice etc
 * 
 * @package Popup Anything on Click
 * @since 2.0
 */
function popupaoc_display_message( $type = 'update', $msg = '', $echo = 1 ) {

	switch ( $type ) {
		case 'reset':
			$msg = ! empty( $msg ) ? $msg : __( 'All settings reset successfully.', 'popup-anything-on-click');
			$msg_html = '<div id="message" class="updated notice notice-success is-dismissible">
							<p><strong>' . $msg . '</strong></p>
						</div>';
			break;

		case 'error':
			$msg = ! empty( $msg ) ? $msg : __( 'Sorry, Something happened wrong.', 'popup-anything-on-click');
			$msg_html = '<div id="message" class="error notice is-dismissible">
							<p><strong>' . $msg . '</strong></p>
						</div>';
			break;

		default:
			$msg = ! empty( $msg ) ? $msg : __('Your changes saved successfully.', 'popup-anything-on-click');
			$msg_html = '<div id="message" class="updated notice notice-success is-dismissible">
							<p><strong>'. $msg .'</strong></p>
						</div>';
			break;
	}

	if( $echo ) {
		echo wp_kses_post( $msg_html );
	} else {
		return $msg_html;
	}
}

/**
 * Function to get popup preview HTML
 * 
 * @since 2.0
 */
function popupaoc_preview_popup( $args = array() ) {

	$default_args = array(
							'title' 		=> '',
							'preview_link'	=> '',
							'info'			=> '',
						);
	$args = wp_parse_args( $args, $default_args );
?>
	<div class="paoc-popup-modal paoc-cnt-wrap">
		<div class="paoc-popup-modal-act-btn-wrp">
			<span class="paoc-popup-modal-act-btn paoc-popup-modal-info" title="<?php echo esc_attr__("Note: Preview will be displayed according to responsive layout mode. Live preview may display differently when added to your page based on inheritance from some styles.", 'popup-anything-on-click') ."\n\n". esc_attr( $args['info'] ); ?>"><i class="dashicons dashicons-info"></i></span>
			<span class="paoc-popup-modal-act-btn paoc-popup-modal-close paoc-popup-close" title="<?php esc_attr_e('Close', 'popup-anything-on-click'); ?>"><i class="dashicons dashicons-no-alt"></i></span>
		</div>
		<div class="paoc-popup-modal-title-wrp">
			<span class="paoc-popup-modal-title"><?php echo wp_kses_post( $args['title'] ); ?></span>
		</div>
		<div class="paoc-popup-modal-cnt">
			<iframe src="about:blank" data-src="<?php echo esc_url( $args['preview_link'] ); ?>" class="paoc-preview-frame" name="paoc_preview_frame" scrolling="auto" frameborder="0"></iframe>
			<div class="paoc-popup-modal-loader"></div>
		</div>
	</div>
	<div class="paoc-popup-modal-overlay"></div>
<?php
}

/**
 * Popup Preview Data
 * 
 * @since 2.0
 */
function popupaoc_preview_data( $post_data ) {

	$prefix			= POPUPAOC_META_PREFIX;
	$show_credit	= ! empty( $post_data[ $prefix.'advance' ]['show_credit'] ) ? 1 : 0;

	unset( $post_data[ $prefix.'advance'] );

	$post_data[ $prefix.'advance' ]['show_credit']		= $show_credit;
	$post_data[ $prefix.'advance' ]['cookie_expire']	= '';
	$post_data[ $prefix.'popup_appear' ]				= 'page_load';
	$post_data[ $prefix.'behaviour' ]['open_delay']		= '';
	$post_data[ $prefix.'behaviour']['disappear']		= '';

	return $post_data;
}

/**
 * Function to get popup appear meta on suggestion type
 * 
 * @package Popup Anything on Click
 * @since 2.0
 */
function popupaoc_sugg_meta_data( $appear_meta ) {

	$meta_data = array();

	// Page_load, Scroll, Inactivity appear meta
	if( $appear_meta == 'welcome' ) {
		$meta_data	= array(
							'relation' => 'OR',
							array(
								'key'	=> '_aoc_popup_appear',
								'value'	=> 'page_load',
							),
						);
	}

	return apply_filters( 'popupaoc_sugg_meta_data', $meta_data );
}

/**
 * Function to get registered post types
 * 
 * @package Popup Anything on Click
 * @since 2.0
 */
function popupaoc_get_post_types( $args = array(), $exclude_post = array() ) {     

	$post_types			= array();
	$args				= ( ! empty( $args ) && is_array( $args ) ) ? $args : array( 'public' => true );
	$default_post_types	= get_post_types( $args, 'name' );
	$exclude_post		= ! empty( $exclude_post ) ? (array) $exclude_post : array();

	if( ! empty( $default_post_types ) ) {
		foreach ($default_post_types as $post_type_key => $post_data) {
			if( ! in_array( $post_type_key, $exclude_post ) ) {
				$post_types[$post_type_key] = $post_data->label;
			}
		}
	}

	return apply_filters('popupaoc_get_post_types', $post_types );
}

/**
 * Function to display location.
 * 
 * @package Popup Anything on Click
 * @since 2.0
 */
function popupaoc_display_locations( $type = 'all', $all = true, $exclude = array() ) {

	$locations		= array();
	$exclude		= array_merge( array('attachment', 'revision', 'nav_menu_item'), $exclude);
	$all_post_types	= popupaoc_get_post_types();
	$post_types		= array();

	foreach ( $all_post_types as $post_type => $post_data ) {
		if( $all ) {
			$type_label = __( 'All', 'popup-anything-on-click' ) .' '. $post_data;
		} else {
			$type_label = $post_data;
		}

		$locations[ $post_type ] = $type_label;
	}

	if ( 'global' != $type ) {
		
		$glocations = array(
			'is_front_page'	=> __( 'Front Page', 'popup-anything-on-click' ),
			'is_search'		=> __( 'Search Results', 'popup-anything-on-click' ),
			'is_404'		=> __( '404 Error Page', 'popup-anything-on-click' ),
			'is_archive'	=> __( 'All Archives', 'popup-anything-on-click' ),
			'all'			=> __( 'Whole Site', 'popup-anything-on-click' ),
		);

		$locations = array_merge( $locations, $glocations );	
	}

	// Exclude some post type or location
	if( ! empty( $exclude ) ) {
		foreach ($exclude as $location_key) {
			unset( $locations[ $location_key ] );
		}
	}

	return $locations;
}

/**
 * Get post meta
 * If preview is there then get run time post meta
 * 
 * @since 2.0
 */
function popupaoc_get_post_status( $post_id ) {

	global $paoc_preview;

	$post_status = get_post_status( $post_id );

	// If popup preview is there
	if( $paoc_preview && ! empty( $_POST['paoc_preview_form_data'] ) ) {
		$post_status = 'publish';
	}

	return $post_status;
}

/**
 * Get popup default meta data
 * 
 * @since 2.0
 */
function popupaoc_popup_default_meta() {

	$prefix = POPUPAOC_META_PREFIX;

	$default_meta = array(
		'content'			=> __('Primary Content – Primary Content Goes Here.', 'popup-anything-on-click'),
		$prefix.'behaviour'	=> array(
								'close_overlay'	=> 1,
							),
		$prefix.'content'	=> array(
									'main_heading'		=> __('Main Heading Goes Here', 'popup-anything-on-click'),
									'sub_heading'		=> __('Sub Heading Goes Here', 'popup-anything-on-click'),
									'cust_close_txt'	=> __('No, thank you. I do not want.', 'popup-anything-on-click'),
									'security_note'		=> __('100% secure your website.', 'popup-anything-on-click'),
								),
		$prefix.'advance'	=> array(
									'show_credit' => 1,
								),
	);

	return apply_filters( 'popupaoc_popup_default_meta', $default_meta );
}

/**
 * Get post meta
 * If preview is there then get run time post meta
 * 
 * @since 2.1.9
 */
function popupaoc_process_meta( $meta_key, $post_id = 0 ) {

	global $pagenow, $paoc_preview;

	$prefix		= POPUPAOC_META_PREFIX; // Taking metabox prefix
	$meta_data	= '';

	// If popup preview is there
	if( $paoc_preview && ! empty( $_POST['paoc_preview_form_data'] ) ) {
		$postdata = $_POST['paoc_preview_form_data']; // WPCS: input var okay, CSRF ok.
	} else {
		$postdata = $_POST; // WPCS: input var okay, CSRF ok.
	}

	if( "{$prefix}popup_appear" == $meta_key ) {

		$meta_data = isset( $postdata[$prefix.'popup_appear'] ) ? popupaoc_clean( $postdata[$prefix.'popup_appear'] ) : 'page_load';

	} elseif( "{$prefix}behaviour" == $meta_key ) {

		// Behaviour Meta Data
		$meta_data						= array();
		$meta_data['open_delay']		= isset( $postdata[$prefix.'behaviour']['open_delay'] )			? popupaoc_clean_number( $postdata[$prefix.'behaviour']['open_delay'], '', 'abs') 		: '';
		$meta_data['disappear']			= isset( $postdata[$prefix.'behaviour']['disappear'] )			? popupaoc_clean_number( $postdata[$prefix.'behaviour']['disappear'], '', 'float')		: '';
		$meta_data['loader_speed']		= ! empty( $postdata[$prefix.'behaviour']['loader_speed'] )		? popupaoc_clean_number( $postdata[$prefix.'behaviour']['loader_speed'], '', 'abs')		: 1;
		$meta_data['popup_img_id']		= ! empty( $postdata[$prefix.'behaviour']['popup_img_id'] )		? popupaoc_clean_number( $postdata[$prefix.'behaviour']['popup_img_id'] )				: 0;
		$meta_data['image_url']			= isset( $postdata[$prefix.'behaviour']['image_url'] )			? popupaoc_clean_url( $postdata[$prefix.'behaviour']['image_url'] )						: '';
		$meta_data['btn_class']			= isset( $postdata[$prefix.'behaviour']['btn_class'] )			? popupaoc_sanitize_html_classes( $postdata[$prefix.'behaviour']['btn_class'] )			: '';
		$meta_data['btn_text']			= ! empty( $postdata[$prefix.'behaviour']['btn_text'] )			? popupaoc_clean_html( $postdata[$prefix.'behaviour']['btn_text'] )						: '';
		$meta_data['btn_text']			= ! empty( $meta_data['btn_text'] )							? $meta_data['btn_text']															: esc_html__('Click Here!!!', 'popup-anything-on-click');
		$meta_data['link_text']			= ! empty( $postdata[$prefix.'behaviour']['link_text'] )		? popupaoc_clean_html( $postdata[$prefix.'behaviour']['link_text'] )					: '';
		$meta_data['link_text']			= ! empty( $meta_data['link_text'] )						? $meta_data['link_text']															: esc_html__('Click Me!!!', 'popup-anything-on-click');
		$meta_data['image_title']		= ! empty( $postdata[$prefix.'behaviour']['image_title'] )		? 1	: 0;
		$meta_data['image_caption']		= ! empty( $postdata[$prefix.'behaviour']['image_caption'] )	? 1	: 0;
		$meta_data['hide_close']		= ! empty( $postdata[$prefix.'behaviour']['hide_close'] )		? 1	: 0;
		$meta_data['clsonesc']			= ! empty( $postdata[$prefix.'behaviour']['clsonesc'] )		? 1	: 0;
		$meta_data['enable_loader']		= ! empty( $postdata[$prefix.'behaviour']['enable_loader'] )	? 1	: 0;
		$meta_data['hide_overlay']		= ! empty( $postdata[$prefix.'behaviour']['hide_overlay'] )	? 1	: 0;
		$meta_data['close_overlay']		= ! empty( $postdata[$prefix.'behaviour']['close_overlay'] )	? 1	: 0;
		$meta_data['close_overlay']		= ( $meta_data['hide_overlay'] == 1 )						? 0	: $meta_data['close_overlay'];

	} elseif ( "{$prefix}content" == $meta_key ) {

		// Content Meta Data
		$meta_data						= array();
		$meta_data['main_heading']		= isset( $postdata[$prefix.'content']['main_heading'] )			? popupaoc_clean_html( $postdata[$prefix.'content']['main_heading'] )		: '';
		$meta_data['sub_heading']		= isset( $postdata[$prefix.'content']['sub_heading'] )			? popupaoc_clean_html( $postdata[$prefix.'content']['sub_heading'] )		: '';
		$meta_data['cust_close_txt']	= isset( $postdata[$prefix.'content']['cust_close_txt'] )		? popupaoc_clean( $postdata[$prefix.'content']['cust_close_txt'] )			: '';
		$meta_data['security_note']		= isset( $postdata[$prefix.'content']['security_note'] )		? popupaoc_clean( $postdata[$prefix.'content']['security_note'] )			: '';
		$meta_data['secondary_content']	= isset( $postdata[$prefix.'content']['secondary_content'] )	? sanitize_post_field( 'post_excerpt', $postdata[$prefix.'content']['secondary_content'], $post_id, 'db' )	: ''; /* Secondary Content Acts as a Post Excerpt */

	} elseif ( "{$prefix}design" == $meta_key ) {

		// Design Meta Data
		$meta_data						= array();
		$meta_data['template']			= isset( $postdata[$prefix.'design']['template'] )				? popupaoc_clean( $postdata[$prefix.'design']['template'] )						: 'design-1';
		$meta_data['width']				= isset( $postdata[$prefix.'design']['width'] )					? popupaoc_clean( $postdata[$prefix.'design']['width'] )						: '';
		$meta_data['height']			= isset( $postdata[$prefix.'design']['height'] )				? popupaoc_clean_number( $postdata[$prefix.'design']['height'], '' )			: '';
		$meta_data['mn_position']		= isset( $postdata[$prefix.'design']['mn_position'] )			? popupaoc_clean( $postdata[$prefix.'design']['mn_position'] )					: 'center-center';
		$meta_data['effect']			= isset( $postdata[$prefix.'design']['effect'] )				? popupaoc_clean( $postdata[$prefix.'design']['effect'] )						: '';
		$meta_data['speed_in']			= ! empty( $postdata[$prefix.'design']['speed_in'] )			? popupaoc_clean_number( $postdata[$prefix.'design']['speed_in'], '', 'abs' )	: 0.5;
		$meta_data['speed_out']			= ! empty( $postdata[$prefix.'design']['speed_out'] )			? popupaoc_clean_number( $postdata[$prefix.'design']['speed_out'], '', 'abs' )	: 0.25;
		$meta_data['loader_color']		= ! empty( $postdata[$prefix.'design']['loader_color'] )		? popupaoc_clean_color( $postdata[$prefix.'design']['loader_color'] )			: '#000000';
		$meta_data['fullscreen_popup']	= ! empty( $postdata[$prefix.'design']['fullscreen_popup'] )	? 1	: 0;

	} elseif( "{$prefix}advance" == $meta_key ) {

		// Advance Meta Data
		$meta_data					= array();
		$meta_data['show_credit']	= ! empty( $postdata[$prefix.'advance']['show_credit'] )	? 1	: 0;
		$meta_data['cookie_expire']	= ( $postdata[$prefix.'advance']['cookie_expire'] != '' )	? popupaoc_clean_number( $postdata[$prefix.'advance']['cookie_expire'], null )	: '';
		$meta_data['cookie_unit']	= 'day';

	} elseif( "{$prefix}custom_css" == $meta_key ) {

		$meta_data = isset( $postdata[$prefix.'custom_css'] ) ? sanitize_textarea_field( $postdata[$prefix.'custom_css'] ) : '';
	}

	return $meta_data;
}

/**
 * Get post meta
 * If preview is there then get run time post meta
 * 
 * @since 2.0
 */
function popupaoc_get_meta( $post_id, $meta_key, $flag = true ) {

	global $pagenow, $paoc_preview;

	// If popup preview is there sanitize meta on run time
	if( $paoc_preview && ! empty( $_POST['paoc_preview_form_data'] ) ) {

		$post_meta = popupaoc_process_meta( $meta_key, $post_id );

	} else {

		$default_meta	= popupaoc_popup_default_meta();

		$post_meta		= get_post_meta( $post_id, $meta_key, $flag );
		$post_meta		= ( $pagenow == 'post-new.php' && isset( $default_meta[ $meta_key ] ) ) ? $default_meta[ $meta_key ] : $post_meta;
	}

	return $post_meta;
}

/**
 * Function to return wheather popup is active or not.
 * 
 * @since 2.0
 */
function popupaoc_check_active( $glob_locs = array() ) {

	global $post, $paoc_popup_active;

	$prefix 			= POPUPAOC_META_PREFIX;
	$paoc_post_type		= isset( $post->post_type ) ? $post->post_type : '';
	$custom_location	= false;
	$paoc_popup_active	= false;

	// Whole Website
	if( ! empty( $glob_locs['all'] ) ) {
		$paoc_popup_active = true;
	}

	// Post Type Wise
	if( ! empty( $glob_locs[ $paoc_post_type ] ) && is_singular() ) {
		$paoc_popup_active = true;
	}

	// Checking custom locations
	if( is_search() ) {
		$custom_location = "is_search";
	} else if( is_404() ) {
		$custom_location = "is_404";
	} else if( is_archive() ) {
		$custom_location = "is_archive";
	} else if( is_front_page() ) {
		$custom_location = "is_front_page";
	}

	if( $custom_location && ! empty( $glob_locs[ $custom_location ] ) ) {
		$paoc_popup_active = true;
	}

	return $paoc_popup_active;
}

/**
 * Function to render popup content.
 * An alternate solution of apply_filter('the_content')
 *
 * Prioritize the function in a same order apply_filter('the_content') wp-includes/default-filters.php
 * 
 * @since 2.0
 */
function popupaoc_render_popup_content( $popup_content = '' ) {

	if ( empty( $popup_content ) ) {
		return false;
	}

	global $wp_embed;

	$popup_content		= $wp_embed->run_shortcode( $popup_content );
	$popup_content		= $wp_embed->autoembed( $popup_content );
	$popup_content		= wptexturize( $popup_content );
	$popup_content		= wpautop( $popup_content );
	$popup_content		= shortcode_unautop( $popup_content );

	// Since Version 5.5.0
	if ( function_exists('wp_filter_content_tags') ) {
		$popup_content = wp_filter_content_tags( $popup_content );
	}

	// Since Version 5.7.0
	if ( function_exists( 'wp_replace_insecure_home_url' ) ) {
		$popup_content = wp_replace_insecure_home_url( $popup_content );
	}

	$popup_content	= do_shortcode( $popup_content );
	$popup_content	= convert_smilies( $popup_content );
	$popup_content	= str_replace( ']]>', ']]&gt;', $popup_content );

	return $popup_content;
}

/**
 * Function to create popup HTML
 * 
 * @since 2.0
 */
function popupaoc_generate_popup_style( $popup_id = 0 ) {

	global $paoc_design_sett, $paoc_behaviour_sett, $paoc_advance_sett, $paoc_custom_css;

	// If valid post is there
	if( empty( $popup_id ) ) {
		return false;
	}

	// Taking some data
	$style['inline']	= '';
	$prefix				= POPUPAOC_META_PREFIX;
	$design				= empty( $paoc_design_sett )	? get_post_meta( $popup_id, $prefix.'design', true )		: $paoc_design_sett;
	$behaviour			= empty( $paoc_behaviour_sett )	? get_post_meta( $popup_id, $prefix.'behaviour', true )		: $paoc_behaviour_sett;
	$advance			= empty( $paoc_advance_sett )	? get_post_meta( $popup_id, $prefix.'advance', true )		: $paoc_advance_sett;
	$custom_css			= empty( $paoc_custom_css )		? get_post_meta( $popup_id, $prefix.'custom_css', true )	: $paoc_custom_css;

	$popup_width		= isset( $design['width'] ) 			? $design['width']				: '';
	$popup_height		= isset( $design['height'] ) 			? $design['height']				: '';
	$fullscreen_popup	= isset( $design['fullscreen_popup'] ) 	? $design['fullscreen_popup']	: '';
	$hide_overlay		= ! empty( $behaviour['hide_overlay'] )	? 1 : 0;

	// Show Credit
	$show_credit = ! empty( $advance['show_credit'] ) ? 1 : 0;

	// Custom CSS
	$custom_css = isset( $custom_css ) ? $custom_css : '';

	if( $popup_width ) {
		if( ! $hide_overlay ) {
			$style['inline'] .= ".paoc-popup-{$popup_id}{max-width: {$popup_width};}";
		} else {
			$style['inline'] .= ".paoc-cb-popup-{$popup_id}.paoc-hide-overlay.custombox-content {max-width: {$popup_width};}";
		}
	}

	if( $popup_height && ! $fullscreen_popup ) {

		$style['inline'] .= ".paoc-popup-{$popup_id} {height: {$popup_height}px;}";
		$style['inline'] .= ".paoc-popup-{$popup_id} .paoc-popup-inr-wrap{height: 100%;}";
		$style['inline'] .= ".paoc-popup-{$popup_id} .paoc-popup-inr{overflow-y:auto;}";
	}

	// Show Credit
	if( $show_credit ) {
		if( ! $hide_overlay ) {
			$style['inline'] .= ".custombox-y-bottom .paoc-popup-{$popup_id}{margin-bottom: 34px;}";
			$style['inline'] .= ".paoc-popup-{$popup_id}.paoc-inline-popup{margin-bottom: 49px;}";
		}
	}

	if ( $hide_overlay ) {
		if( $show_credit ) {
			$style['inline'] .= ".admin-bar .paoc-popup-{$popup_id}{max-height: calc(100vh - 66px) !important; margin-bottom: 34px;}";
			$style['inline'] .= ".paoc-popup-{$popup_id}{max-height: calc(100vh - 32px) !important; margin-bottom: 34px !important;}";
			$style['inline'] .= ".paoc-popup-{$popup_id}.paoc-inline-popup{margin-bottom: 49px !important;}";
		} else {
			$style['inline'] .= ".admin-bar .paoc-popup-{$popup_id}{max-height: calc(100vh - 32px) !important;}";
		}
	}

	// Custom CSS
	$style['inline'] .= $custom_css;

	return $style;
}