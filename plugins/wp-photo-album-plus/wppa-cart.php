<?php
/* wppa-cart.php
* Package: wp-photo-album-plus
*
* Contains the interface to SCABN
* Version 8.2.05.000
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

// Displays the 'add to cart' button on photo description.
// Contains both the visual data and the form submission
// for adding items to the cart.
// $thumb MUST contain the current photo info
function wppa_add_to_cart( $atts ) {
global $post;
global $wppa_session;

	if ( ! class_exists( 'wfCart' ) ) {
		wppa_echo( __( 'Plugin <i>Simple Cart and BuyNow</i> must be activated to use this featue.', 'wp-photo-album-plus' ) );
		return '';
	}

	$thumb = wppa( 'current_photo' );

	extract( shortcode_atts( array(
		'name'  		=> wppa_get_photo_name( strval( intval( $thumb['id'] ) ) ),
		'price' 		=> '0.01',
		'qty_field' 	=> '',
		'b_title'		=> __('Buy now', 'wp-photo-album-plus' ),
		'options'		=> '',
		'options_name' 	=> '',
		'qty_field' 	=> '',
		'fshipping'		=> '',
		'weight'		=> ''
	), $atts ) );

	$cart = $wppa_session['wfcart']; // load the cart from the session
	$scabn_options = wppa_get_option('scabn_options');
	$currency = apply_filters('scabn_display_currency_symbol',NULL);

	// Slideshow?
	if ( wppa( 'is_slide' ) ) {
		$action_url = wppa_get_slide_callback_url( $thumb['id'] );
		$item_url 	= $action_url . '&wppa-single=1';
		$action_url = wppa_convert_to_pretty( $action_url );
		$item_url 	= wppa_convert_to_pretty( $item_url );
	}
	// Thumbnail vieuw?
	else {
		$action_url = wppa_get_thumb_callback_url();
		if ( ! strpos( $action_url, '&amp;' ) ) $action_url = str_replace( '&', '&amp;', $action_url );
		$item_url 	= wppa_get_slide_callback_url( $thumb['id'] ) . '&wppa-single=1';
		$action_url = wppa_convert_to_pretty( $action_url );
		$item_url 	= wppa_convert_to_pretty( $item_url );
	}

	$action_url = wppa_convert_from_pretty( $action_url );

	$output  = '
	<div class="wppa-addtocart">
		<form method="post" class="wppa-cartform '.$name.'" action="'.$action_url.'">
			'.wp_nonce_field( 'add_to_cart', 'scabn-add', false, false ).'
			<input type="hidden" value="add_item" name="action" />
			<input type="hidden" class="item_url" value="'.$item_url.'" name="item_url" />
			<input type="hidden" value="'.$cart->random().'" name="randomid" />
			<input type="hidden" value="'.$name.'" name="item_id" />
			<input type="hidden" class="item_name" value="'.$name.'" name="item_name" />
			<input type="hidden" class="item_price" value="'.$price.'" name="item_price" />';
	if ( $fshipping ) $output .= '
			<input type="hidden" class="item_shipping" value="'.$fshipping.'" name="item_shipping" />';
	if ( $weight ) $output .= '
			<input type="hidden" class="item_weight" value="'.$weight.'" name="item_weight" />';
	if ( $options ) {
		if ( $options_name ) {
			$output .= $options_name.': ';
		}
		$output .= '
			<input type="hidden" value="'.$options_name.'" name="item_options_name" class="item_options_name" />';
		$item_options = explode(',',$options);
		$output .= '
			<select style="max-width:200px; margin:0" name="item_options" class="item_options" >';
		foreach ( $item_options as $option ){
			$info = explode(':',$option);
			if ( count($info) == 1 ) {
				$output .= '
					<option value="'.$info[0].'">'.$info[0].' ('.$currency.number_format($price,2).')</option>';
			} else {
				$output .= '
					<option value="'.$info[0].':'.$info[1].'">'.$info[0].' ('.$currency.number_format($info[1],2).')</option>';
			}
		}
		$output .= '
			</select>';
	} else {
		$output .= sprintf(__('Unit Price: %s each', 'wp-photo-album-plus' ), $currency.number_format($price,2)).' ';
	}

	if ( $qty_field ) {
		$output .= __('Qty:', 'wp-photo-album-plus' ).' <input type="text" style="max-width:50px; margin:0;" class="item_qty" value="1" size="2" name="item_qty" />';
	} else {
		$output .= '<input type="hidden" class="item_qty" value="1" size="2" name="item_qty" />';
	}

	$output .= '
			<input type="submit" id="'.$name.'" class="add" name="add" value="'.$b_title.'"/>
		</form>
	</div>';

	return $output;
}

if ( wppa_get_option('wppa_use_scabn', 'no') == 'yes' ) add_shortcode('cart', 'wppa_add_to_cart');
