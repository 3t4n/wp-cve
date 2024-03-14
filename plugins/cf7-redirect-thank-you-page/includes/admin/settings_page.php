<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// admin table
function cf7rl_admin_table() {



	if ( !current_user_can( "manage_options" ) )  {
	wp_die( __( "You do not have sufficient permissions to access this page." ) );
	}



	// save and update options
	if (isset($_POST['update'])) {
	
	if ( empty( $_POST['cf7rl_nonce_field'] ) || !wp_verify_nonce( $_POST['cf7rl_nonce_field'], 'cf7rl_save_settings') ) {
		
		wp_die( __( "You do not have sufficient permissions to access this page." ) );
		
	}
		
		$options['redirect'] = 					sanitize_text_field($_POST['redirect']);
		if (empty($options['redirect'])) { 		$options['redirect'] = '1'; }
		
		update_option("cf7rl_options", $options);
		
		echo "<br /><div class='updated'><p><strong>"; _e("Settings Updated."); echo "</strong></p></div>";
		
	}



	// get options
	$options = get_option('cf7rl_options');
	if (empty($options['redirect'])) { 					$options['redirect'] = '1'; }
	
	
	// tabs
	if (isset($_POST['hidden_tab_value'])) {
		$active_tab =  sanitize_text_field($_POST['hidden_tab_value']);
	} else {
		if (isset($_GET['tab'])) {
			$active_tab = sanitize_text_field($_GET[ 'tab' ]);
		} else {
			$active_tab = '1';
		}
	}

	


	// make page
	$settings_table_output = "";
	$settings_table_output .= "<form method='post'>";

	$settings_table_output .= "<table width='70%'><tr><td>";
	$settings_table_output .= "<div class='wrap'><h2>Contact Form 7 - Redirect & Thank You Page Settings</h2></div><br /></td><td><br />";
	$settings_table_output .= "<input type='submit' name='btn2' class='button-primary' style='font-size: 17px;line-height: 28px;height: 32px;float: right;' value='Save Settings'>";
	$settings_table_output .= "</td></tr></table>";



	$settings_table_output .= "<table width='100%'><tr><td width='70%' valign='top'>";

		$settings_table_output .= "<h2 class='nav-tab-wrapper'>";
			$settings_table_output .= "<a onclick=\"cf7rl_closetabs('1,2,3');cf7rl_newtab('1');\" href='#' id='id1' class=\"nav-tab "; if ($active_tab == '1') { $settings_table_output .= 'nav-tab-active'; } else { ''; } $settings_table_output .= " \">Getting Started</a>";
			$settings_table_output .= "<a onclick=\"cf7rl_closetabs('1,2,3');cf7rl_newtab('2');\" href='#' id='id2' class=\"nav-tab "; if ($active_tab == '2') { $settings_table_output .= 'nav-tab-active'; } else { ''; } $settings_table_output .= " \">Settings</a>";
			$settings_table_output .= "<a onclick=\"cf7rl_closetabs('1,2,3');cf7rl_newtab('3');\" href='#' id='id3' class=\"nav-tab "; if ($active_tab == '3') { $settings_table_output .= 'nav-tab-active'; } else { ''; } $settings_table_output .= " \">Extensions</a>";
		$settings_table_output .= "</h2>";
		$settings_table_output .= "<br />";
		
	
	$settings_table_output .= "</td><td colspan='3'></td></tr><tr><td valign='top'>";



	$settings_table_output .= "<div id='1' style='display:none;border: 1px solid #CCCCCC; "; if ($active_tab == '1') { $settings_table_output .= 'display:block;'; } $settings_table_output .= "'>";
		$settings_table_output .= "<div style='background-color:#E4E4E4;padding:8px;color:#000;font-size:15px;color:#464646;font-weight: 700;border-bottom: 1px solid #CCCCCC;'>";
			$settings_table_output .= "&nbsp; Getting Started";
		$settings_table_output .= "</div>";
		$settings_table_output .= "<div style='background-color:#fff;padding:8px;'>
			
			When go to your <a href='admin.php?page=wpcf7'>list of contact forms</a>, make a new form or edit an existing form, you will see a new tab called 'Redirect & Thank You Page'. On this tab you can
			setup individual settings for how that specific contact form redirects.
			
			<br /><br />
			
			<b>Note:</b> If you experience problems with the form not redirecting correctly, try changing the redirect method setting on the <a href='admin.php?page=cf7rl_admin_table&tab=2'>Settings</a> tab of this page.
			
			<br />";
			
		$settings_table_output .= "</div>";
	$settings_table_output .= "</div>";


	$settings_table_output .= "<div id='2' style='display:none;border: 1px solid #CCCCCC; "; if ($active_tab == '2') { $settings_table_output .= 'display:block;'; } $settings_table_output .= "'>";
		$settings_table_output .= "<div style='background-color:#E4E4E4;padding:8px;color:#000;font-size:15px;color:#464646;font-weight: 700;border-bottom: 1px solid #CCCCCC;'>";
			$settings_table_output .= "&nbsp; Settings";
		$settings_table_output .= "</div>";
		$settings_table_output .= "<div style='background-color:#fff;padding:8px;'>";

			$settings_table_output .= "<table style='width: 100%;'>";

				$settings_table_output .= "<tr><td class='cf7rl_width'><b>Redirect Method: </b></td><td>";

				$settings_table_output .= "<select name='redirect'>";
				$settings_table_output .= "<option "; if ($options['redirect'] == "1") {  $settings_table_output .= "SELECTED"; } $settings_table_output .= " value='1'>Method 1</option>";
				$settings_table_output .= "<option "; if ($options['redirect'] == "2") {  $settings_table_output .= "SELECTED"; } $settings_table_output .= " value='2'>Method 2</option>";
				$settings_table_output .= "</select></td><td>Method 1 is recommend unless your form has problems redirecting. <br /> Method 2 disables <a target='_blank' href='https://contactform7.com/loading-javascript-and-stylesheet-only-when-it-is-necessary/'>WPCF7_LOAD_JS</a> which can be necessary in some situations.</td></tr>";

			$settings_table_output .= "</table>";

		$settings_table_output .= "</div>";
	$settings_table_output .= "</div>";
	
	
	$settings_table_output .= "<div id='3' style='display:none;border: 1px solid #CCCCCC; "; if ($active_tab == '3') { $settings_table_output .= 'display:block;'; } $settings_table_output .= "'>";
		$settings_table_output .= "<div style='background-color:#E4E4E4;padding:8px;color:#000;font-size:15px;color:#464646;font-weight: 700;border-bottom: 1px solid #CCCCCC;'>";
			$settings_table_output .= "&nbsp; Extensions";
		$settings_table_output .= "</div>";
		$settings_table_output .= "<div style='background-color:#fff;padding:8px;'>";
			
			$settings_table_output .= "<table style='width: 100%;'>";
				
				$settings_table_output .= cf7rl_extensions_page();
				
			$settings_table_output .= "</table>";
			
		$settings_table_output .= "</div>";
	$settings_table_output .= "</div>";




	$settings_table_output .= "<input type='hidden' name='update' value='1'>";
	$settings_table_output .= "<input type='hidden' name='hidden_tab_value' id='hidden_tab_value' value='$active_tab'>";
	
	$settings_table_output .= wp_nonce_field('cf7rl_save_settings', 'cf7rl_nonce_field');

$settings_table_output .= "</form>";













	$settings_table_output .= "</td><td width='3%' valign='top'>";

	$settings_table_output .= "</td><td width='24%' valign='top'>";

	
	$settings_table_output .= "<div style='border: 1px solid #CCCCCC;'>";
		
		$settings_table_output .= "<div style='background-color:#E4E4E4;padding:8px;color:#000;font-size:15px;color:#464646;font-weight: 700;border-bottom: 1px solid #CCCCCC;'>";
		$settings_table_output .= "&nbsp; Pro Version";
		$settings_table_output .= "</div>";
		
		$settings_table_output .= "<div style='background-color:#fff;padding:8px;'>";	
			
			$settings_table_output .= "<center><label style='font-size:14pt;'><b>Pro Features: </b></label></center><br />";
			
			$settings_table_output .= "<div class='dashicons dashicons-yes' style='margin-bottom: 6px;'></div> Use mail tags on Thank You Page <br />";
			$settings_table_output .= "<div class='dashicons dashicons-yes' style='margin-bottom: 6px;'></div> Form items like dropdown menus can be used to redirect to different URL's <br />";
			$settings_table_output .= "<div class='dashicons dashicons-yes' style='margin-bottom: 6px;'></div> Support the development of more features <br /><br />";
			
			$settings_table_output .= "<center><a target='_blank' href='https://wpplugin.org/downloads/contact-form-7-redirect-thank-you-page-pro/?utm_source=plugin&utm_medium=cf7rl&utm_campaign=settings_page' class='button-primary' style='font-size: 17px;line-height: 28px;height: 32px;'>Learn More</a></center><br />";
			
		$settings_table_output .= "</div>";
	$settings_table_output .= "</div>";

	
	$settings_table_output = apply_filters('cf7rl_settings_page_license_section',$settings_table_output);
	
	
	$settings_table_output .= "</td><td width='2%' valign='top'>";



	$settings_table_output .= "</td></tr></table>";
	
	echo $settings_table_output;

}
