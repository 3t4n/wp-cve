<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// COD - Attach Tab to contact form 7
function cf7rzp_editor_panels ( $panels ) {

	$new_page = array(
		'Razorpay' => array(
			'title' => __( 'Razorpay', 'contact-form-7-razorpay' ),
			'callback' => 'cf7rzp_admin_tab'
		)
	);

	$panels = array_merge($panels, $new_page);

	return $panels;

}
add_filter( 'wpcf7_editor_panels', 'cf7rzp_editor_panels' );

function cf7rzp_admin_tab( $cf7 ) {

    $post_id = sanitize_text_field($_GET['post']);

	$activate = 				get_post_meta($post_id, "_cf7rzp_activate", true);
	$name = 					get_post_meta($post_id, "_cf7rzp_item_name", true);
	$price = 					get_post_meta($post_id, "_cf7rzp_item_price", true);
	$id = 						get_post_meta($post_id, "_cf7rzp_item_id", true);

	if ($activate == "1") { $checked = "CHECKED"; } else { $checked = ""; }

	$rzp_tab_output = "";
	$rzp_tab_output .= "<h2>Razorpay Settings</h2>";

	$rzp_tab_output .= "<div class='mail-field'></div>";
	
	$rzp_tab_output .= "<table><tr>";
	
	$rzp_tab_output .= "<td width='195px'><label>Activate Razorpay: </label></td>";
	$rzp_tab_output .= "<td width='250px'><input name='cf7rzp_activate' value='1' type='checkbox' ".esc_attr($checked)."></td></tr>&nbsp;";

    $rzp_tab_output .= "<tr><td>&nbsp;</td></tr>";

	$rzp_tab_output .= "<tr><td>Item Name: </td>";
	$rzp_tab_output .= "<td><input type='text' name='cf7rzp_name' value='".esc_attr($name)."'> </td><td> [ Optional ]</td></tr>";

    $rzp_tab_output .= "<tr><td>&nbsp;</td></tr>";

	$rzp_tab_output .= "<tr><td>Item Price: </td>";
	$rzp_tab_output .= "<td><input type='text' name='cf7rzp_price' value='".esc_attr($price)."'> </td><td> [ In INR ]</td></tr>";

    $rzp_tab_output .= "<tr><td>&nbsp;</td></tr>";

	$rzp_tab_output .= "<tr><td>Item ID: </td>";
	$rzp_tab_output .= "<td><input type='text' name='cf7rzp_id' value='".esc_attr($id)."'> </td><td> [ Optional ]</td></tr>";
	
	$rzp_tab_output = apply_filters("cf7rzp_admin_rzp_tab", $rzp_tab_output, $post_id);

	$rzp_tab_output .= "<input type='hidden' name='cf7rzp_post' value='".esc_attr($post_id)."'>";

	$rzp_tab_output .= "</td></tr></table>";

	echo $rzp_tab_output;

}

// COD - Save contact form 7 razorpay settings 
add_action('wpcf7_after_save', 'cf7rzp_save_settings');

function cf7rzp_save_settings( $cf7 ) {
		
		$post_id = sanitize_text_field($_POST['cf7rzp_post']);
		
		/* if (!empty($_POST['cf7rzp_enable'])) {
			$enable = sanitize_text_field($_POST['cf7rzp_enable']);
			update_post_meta($post_id, "_cf7rzp_enable", $enable);
		} else {
			update_post_meta($post_id, "_cf7rzp_enable", 0);
		} */

        $activate = sanitize_text_field($_POST['cf7rzp_activate']);
		update_post_meta($post_id, "_cf7rzp_activate", $activate);
		
		$name = sanitize_text_field($_POST['cf7rzp_name']);
		update_post_meta($post_id, "_cf7rzp_item_name", $name);
		
		$price = sanitize_text_field($_POST['cf7rzp_price']);
		update_post_meta($post_id, "_cf7rzp_item_price", $price);
		
		$id = sanitize_text_field($_POST['cf7rzp_id']);
		update_post_meta($post_id, "_cf7rzp_item_id", $id);

		do_action("cf7rzp_admin_rzp_tab_save_settings", $post_id, $_POST);
}