<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// hook into contact form 7 form
function cf7rl_editor_panels ( $panels ) {

	$new_page = array(
		'Redirect' => array(
			'title' => __( 'Redirect & Thank You Page', 'cf7rl' ),
			'callback' => 'cf7rl_admin_after_additional_settings'
		)
	);

	$panels = array_merge($panels, $new_page);

	return $panels;

}
add_filter( 'wpcf7_editor_panels', 'cf7rl_editor_panels' );


function cf7rl_admin_after_additional_settings( $cf7 ) {
	
	$post_id = sanitize_text_field($_GET['post']);
	
	$enable = 						get_post_meta($post_id, "_cf7rl_enable", true);
	$cf7rl_redirect_type = 			get_post_meta($post_id, "_cf7rl_redirect_type", true);
	$cf7rl_url = 					get_post_meta($post_id, "_cf7rl_url", true);
	$tab = 							get_post_meta($post_id, "_cf7rl_tab", true);
	$cf7rl_thank_you_page = 		get_post_meta($post_id, "_cf7rl_thank_you_page", true);
	
	if ($enable == "1") { 			$checked = "CHECKED"; } else { 			$checked = ""; }
	if ($tab == "1") { 				$tab = "CHECKED"; } else { 				$tab = ""; }
	

	$admin_table_output = "";
	$admin_table_output .= "<h2>Redirect</h2>";

	$admin_table_output .= "<div class='mail-field'></div>";
	
	$admin_table_output .= "<table class='cf7rl_tabs_table_main'><tr>";
	
	$admin_table_output .= "<td><b>General Settings</b></td></tr>";

	$admin_table_output .= "<td class='cf7rl_tabs_table_title_width'><label>Enable Redirect: </label></td>";
	$admin_table_output .= "<td class='cf7rl_tabs_table_body_width'><input name='cf7rl_enable' value='1' type='checkbox' $checked></td></tr>";
	
	$admin_table_output .= "<td class='cf7rl_tabs_table_title_width'><label>Redirect Type: </label></td>";
	$admin_table_output .= "<td class='cf7rl_tabs_table_body_width'><select id='cf7rl_redirect_type' name='cf7rl_redirect_type'>
	<option  "; if ($cf7rl_redirect_type == 'url') { $admin_table_output .= 'SELECTED'; } $admin_table_output .= " value='url'>URL</option>
	<option  "; if ($cf7rl_redirect_type == 'thank') { $admin_table_output .= 'SELECTED'; } $admin_table_output .= " value='thank'>Thank You Page</option>
	<option  "; if ($cf7rl_redirect_type == 'logic') { $admin_table_output .= 'SELECTED'; } $admin_table_output .= " value='logic'>Link Form Item</option></select></td></tr>";
	
	
	
	// URL redirect
	$admin_table_output .= "<tr class='cf7rl_url cf7rl_redirect_option'><td><br /><b>URL Redirect Settings</b></td></tr>";
	
	$admin_table_output .= "<tr class='cf7rl_url cf7rl_redirect_option'><td>URL: </td>";
	$admin_table_output .= "<td><input type='url' name='cf7rl_url' value='$cf7rl_url'> </td><td> Example: http://www.domain.com</td></tr><tr><td colspan='3'></td></tr>";
	
	$admin_table_output .= "<tr class='cf7rl_url cf7rl_redirect_option'><td class='cf7rl_tabs_table_title_width'><label>Open In New Tab: </label></td>";
	$admin_table_output .= "<td class='cf7rl_tabs_table_body_width'><input name='cf7rl_tab' value='1' type='checkbox' $tab></td></tr>";
	
	
	// Thank you page
	$admin_table_output .= "<tr class='cf7rl_thank cf7rl_redirect_option' style='display:none;'><td><br /><b>Thank You Page Settings</b></td></tr>";
	
	$admin_table_output .= "<tr class='cf7rl_thank cf7rl_redirect_option' style='display:none;'><td valign='top'>Thank You Page Body: </td>";
	$admin_table_output .= "<td> The <a target='_blank' href='https://wpplugin.org/downloads/contact-form-7-redirect-thank-you-page-pro/?utm_source=plugin&utm_medium=cf7rl&utm_campaign=tabs_page'>Pro version</a> allows you to use mail-tags, like [menu-918], in the Thank You Page Body. <br /><textarea name='cf7rl_thank_you_page' cols='100' rows='24'>$cf7rl_thank_you_page</textarea></td></tr>";
	
	
	
	// Link Form Page
	$admin_table_output .= "<tr class='cf7rl_logic cf7rl_redirect_option' style='display:none;'><td><br /><b>Link Form Item</b></td><td colspan='2'>This feature is only avilable in the <a target='_blank' href='https://wpplugin.org/downloads/contact-form-7-redirect-thank-you-page-pro/?utm_source=plugin&utm_medium=cf7rl&utm_campaign=tabs_page'>Pro version</a>.</td></tr>";
	
	$admin_table_output .= "<tr class='cf7rl_logic cf7rl_redirect_option'><td>Form Item: </td>";
	$admin_table_output .= "<td><input type='text' name='cf7rl_logic' value='' disabled> </td><td> Example: [menu-918]. Documenation <a target='_blank' href='https://wpplugin.org/documentation/link-a-form-item-to-a-url/'>here</a>.</td></tr><tr><td colspan='3'></td></tr>";
	
	$admin_table_output .= "<tr class='cf7rl_logic cf7rl_redirect_option'><td class='cf7rl_tabs_table_title_width'><label>Open In New Tab: </label></td>";
	$admin_table_output .= "<td class='cf7rl_tabs_table_body_width'><input name='cf7rl_logic_tab' value='1' type='checkbox' disabled></td></tr>";
	
	
	
	$admin_table_output .= "<input type='hidden' name='cf7rl_post' value='$post_id'>";

	$admin_table_output .= "</td></tr></table>";

	echo $admin_table_output;

}






// hook into contact form 7 admin form save
add_action('wpcf7_after_save', 'cf7rl_save_contact_form');

function cf7rl_save_contact_form( $cf7 ) {
		
		$post_id = sanitize_text_field($_POST['cf7rl_post']);
		
		if (!empty($_POST['cf7rl_enable'])) {
			$enable = sanitize_text_field($_POST['cf7rl_enable']);
			update_post_meta($post_id, "_cf7rl_enable", $enable);
		} else {
			update_post_meta($post_id, "_cf7rl_enable", 0);
		}
		
		if (!empty($_POST['cf7rl_tab'])) {
			$tab = sanitize_text_field($_POST['cf7rl_tab']);
			update_post_meta($post_id, "_cf7rl_tab", $tab);
		} else {
			update_post_meta($post_id, "_cf7rl_tab", 0);
		}
		
		$cf7rl_redirect_type = sanitize_text_field($_POST['cf7rl_redirect_type']);
		update_post_meta($post_id, "_cf7rl_redirect_type", $cf7rl_redirect_type);
		
		$cf7rl_redirect_type = sanitize_text_field($_POST['cf7rl_redirect_type']);
		update_post_meta($post_id, "_cf7rl_redirect_type", $cf7rl_redirect_type);
		
		$cf7rl_url = sanitize_text_field($_POST['cf7rl_url']);
		update_post_meta($post_id, "_cf7rl_url", $cf7rl_url);
		
		$cf7rl_thank_you_page = sanitize_textarea_field($_POST['cf7rl_thank_you_page']);
		update_post_meta($post_id, "_cf7rl_thank_you_page", $cf7rl_thank_you_page);
		
		
}