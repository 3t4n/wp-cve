<?php
/*
Plugin Name: NextGEN Gallery ColorBoxer
Description: NextGEN Gallery ColorBoxer automatically integrates the cool ColorBox lightbox effect with your NextGEN galleries, and only loads ColorBox's scripts and styles when a gallery shortcode is present, improving your site's page load speed.
Author: Mark Jeldi
Version: 1.0

Author URI: http://www.markstechnologynews.com

Copyright 2012 Mark Jeldi | Helpful Media | mark@helpfulmedia.co.uk

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/



/********************************************************************************
* global variables
********************************************************************************/

$nggcb_options = get_option('nextgen_gallery_colorboxer_settings');

define( 'NGGCB_VERSION', '1.0' );
define( 'NGGCB_COLORBOX_VERSION', '1.3.19' );
define( 'NGGCB_JQUERY_VERSION', '1.7.1' );



/********************************************************************************
* includes
********************************************************************************/

include('nextgen-gallery-colorboxer-functions.php'); // plugin functionality
include('nextgen-gallery-colorboxer-options.php'); // plugin options page
include('nextgen-gallery-colorboxer-scripts-and-styles.php'); // script and stylesheet include functions



/********************************************************************************
* add options page
********************************************************************************/

// call our stylesheet
function nggcb_load_styles() {
	wp_enqueue_style('nggcb_styles', plugin_dir_url( __FILE__ ) . 'css/nextgen-gallery-colorboxer-options.css');
}

// attach the above wp_enqueue_style so our stylesheet only loads on the options page we're building
function nggcb_add_options_page() {
	$nggcb_options_page = add_options_page('NextGEN Gallery ColorBoxer', 'NextGEN Gallery ColorBoxer', 'manage_options', 'nextgen_gallery_colorboxer_options', 'nggcb_options_page');
	add_action('admin_print_styles-' . $nggcb_options_page, 'nggcb_load_styles');
}

// create options page complete with attached css file and link in admin menu. 
add_action('admin_menu', 'nggcb_add_options_page');



/********************************************************************************
* save settings
********************************************************************************/

// create our settings in the options table
function nggcb_register_settings() {
	register_setting('nextgen_gallery_colorboxer_settings_group', 'nextgen_gallery_colorboxer_settings');
}
add_action('admin_init', 'nggcb_register_settings');



/**************************************************
* add settings & donate links on plugins page
**************************************************/

function nextgen_colorboxer_settings_link($links, $file) {
	if ($file == plugin_basename(__FILE__)) {
		$links[] = '<a href="'.admin_url('options-general.php?page=nextgen_gallery_colorboxer_options').'">Settings</a>';
		$links[] = '<a href="http://wordpress.org/support/plugin/nextgen-gallery-colorboxer">Support Forum</a>';
		$links[] = '<a href="http://wordpress.org/extend/plugins/nextgen-gallery-colorboxer">Rate this plugin</a>';
		$links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=HRACRNYABWT7G">Donate</a>';
	}
	return $links;
}
add_filter('plugin_row_meta', 'nextgen_colorboxer_settings_link', 10, 2);



/**********************************************************************
* define default option settings on first activation
**********************************************************************/

function nggcb_add_default_values() {
	
	// global $nggcb_options doesn't work here. Maybe too early?
	$nggcb_options = get_option('nextgen_gallery_colorboxer_settings'); 
    	
    if (!is_array($nggcb_options)) {  // set defaults for new users only
		
		$nggcb_default_values = array(
				"colorbox_opacity" => "0.85",
				"colorbox_transition" => "elastic",
				"do_redirect" => "yes",
				"show_thank_you_message" => "yes"
				);
				
		update_option('nextgen_gallery_colorboxer_settings', $nggcb_default_values);
		
	}
}
register_activation_hook(__FILE__, 'nggcb_add_default_values');



/********************************************************************************
* redirect users to settings page on first activation
********************************************************************************/

function nggcb_redirect_to_settings() {

    global $nggcb_options;
		
	if (isset($nggcb_options['do_redirect']) && ($nggcb_options['do_redirect'] == 'yes')) {
        	        	
        wp_redirect(admin_url('options-general.php?page=nextgen_gallery_colorboxer_options', __FILE__));
			
		// we only want to redirect to the settings page on first activation
		// so we'll update the value of "do_redirect" to "no"

		$nggcb_options['do_redirect'] = 'done'; // amend value in array
		update_option('nextgen_gallery_colorboxer_settings', $nggcb_options); // update option array

	}		
}
add_action('admin_init', 'nggcb_redirect_to_settings');



/********************************************************************************
* display thank you message on first activation
********************************************************************************/

function nggcb_thanks_for_downloading() {

	global $nggcb_options;
	
	if (isset($_GET['page']) && $_GET['page'] == 'nextgen_gallery_colorboxer_options') {

    	if (isset($nggcb_options['show_thank_you_message']) && ($nggcb_options['show_thank_you_message'] == 'yes')) {
        	        	
			echo '
			<div id="message" class="updated">
			<p>Thanks for downloading NextGEN Gallery ColorBoxer!<br />
			Your galleries will now display automatically with the default ColorBox styling, or you can customize its appearance with the options below...</p>
			</div>
			';
			
			// we only want to show this message once on first activation
			// so we'll update the value of "show_thank_you_message" to "done"

			$nggcb_options['show_thank_you_message'] = 'done'; // amend value in array
			update_option('nextgen_gallery_colorboxer_settings', $nggcb_options); // update option array
	
		}
	}		
}
add_action('admin_notices', 'nggcb_thanks_for_downloading');



/********************************************************************************
* Fix for colorbox on IE6 & IE8
* Microsoft.AlphaImageLoader CSS requires absolute file paths.
* We'll run a regex (on activation and update) to write in the correct urls.
********************************************************************************/

function nggcb_colorbox_stylesheet_regex() {
	
	global $nggcb_options;
	
	if (is_admin()) {
	
		if (!isset($nggcb_options['version']) ||
		isset($nggcb_options['version']) && $nggcb_options['version'] != NGGCB_VERSION) {


			$nggcb_css_filename = WP_PLUGIN_DIR."/nextgen-gallery-colorboxer/colorbox/1/colorbox.css";
			$nggcb_image_path = plugins_url( 'colorbox/1/' , __FILE__);
			$nggcb_data = file_get_contents($nggcb_css_filename);

			// the regex
			$nggcb_patterns = '/url\((.*?)images\/ie6\//';
			$nggcb_replacements = 'url(' . $nggcb_image_path . 'images/ie6/';
			$nggcb_update_css = preg_replace($nggcb_patterns, $nggcb_replacements, $nggcb_data);

			// update css
			if (is_writable($nggcb_css_filename)) {

				if (!$handle = fopen($nggcb_css_filename, 'w+')) {
				add_action( 'admin_notices', 'nggcb_file_not_writable_error' );
				exit;
    			}

    			if (fwrite($handle, $nggcb_update_css) === FALSE) {
    			add_action( 'admin_notices', 'nggcb_file_not_writable_error' );
				exit;
				}

			// we only want to run this regex on first activation or after auto-update
			// so we'll insert a "version" option to check against
			
			$nggcb_options['version'] = NGGCB_VERSION; // insert field or update value in array
			update_option('nextgen_gallery_colorboxer_settings', $nggcb_options); // update option array

			fclose($handle);


			} else {
	
				add_action( 'admin_notices', 'nggcb_file_not_writable_error' );
			
			}
		}
	}
}
add_action('admin_init', 'nggcb_colorbox_stylesheet_regex');

			
function nggcb_file_not_writable_error() {
	global $pagenow;
	
	// admin error message
	
    if (($pagenow == 'plugins.php' ||
	isset($_GET['page']) && $_GET['page'] == 'nextgen_gallery_colorboxer_options')) {
	
	$nggcb_css_filename = WP_PLUGIN_DIR."/nextgen-gallery-colorboxer/colorbox/1/colorbox.css"; // global doesn't seem to work here
			
	echo '<div class="error"><p>The file ' . $nggcb_css_filename . ' is not writable. Please change its permissions to 766.</p></div>';
			
	}
}



/********************************************************************************
* automatic colorbox installation
* saves original values on Gallery --> Options --> Effects page
* updates ngg_options with **class="mycolorbox" rel="%GALLERY_NAME%"**
* reverts to previous values on deactivation
********************************************************************************/

function nggcb_colorbox_auto_install() {
	
	global $nggcb_options;
	$nggcb_nextgen_options = get_option('ngg_options');
	
	if (is_admin()) {
	
		if (!isset($nggcb_options['original_nextgen_thumbEffect']) || ($nggcb_options['original_nextgen_thumbEffect'] == 'none')) {
			
			// capture original values for nextgen thumbEffect and thumbCode
				
			$nggcb_options['original_nextgen_thumbEffect'] = $nggcb_nextgen_options['thumbEffect']; // insert field or update value in array
			$nggcb_options['original_nextgen_thumbCode'] = $nggcb_nextgen_options['thumbCode']; // insert field or update value in array
			update_option('nextgen_gallery_colorboxer_settings', $nggcb_options); // update option array
		}
			

		if (!isset($nggcb_options['auto_colorbox_install']) || ($nggcb_options['auto_colorbox_install'] != 'installed')) {
				
			// update nextgen for colorbox integration
			$nggcb_nextgen_options['thumbEffect'] = 'custom'; // insert field or update value in array
			$nggcb_nextgen_options['thumbCode'] = 'class=\"mycolorbox\" rel=\"%GALLERY_NAME%\"'; // insert field or update value in array
			update_option('ngg_options', $nggcb_nextgen_options); // update option array
			
			// set an option so we only run the install once
			$nggcb_options['auto_colorbox_install'] = 'installed'; // insert field or update value in array
			update_option('nextgen_gallery_colorboxer_settings', $nggcb_options); // update option array
			
		}
	

			
		if (($nggcb_nextgen_options['thumbEffect'] != 'custom') ||
		($nggcb_nextgen_options['thumbCode'] != 'class=\"mycolorbox\" rel=\"%GALLERY_NAME%\"')) {	
	
			// if nextgen's effects settings are accidentally changed while colorboxer is activated
			// update colorbox integration and show notification message

			$nggcb_nextgen_options['thumbEffect'] = 'custom'; // insert field or update value in array
			$nggcb_nextgen_options['thumbCode'] = 'class=\"mycolorbox\" rel=\"%GALLERY_NAME%\"'; // insert field or update value in array
			update_option('ngg_options', $nggcb_nextgen_options); // update option array
			
			add_action('admin_notices', 'nggcb_please_deactivate_colorboxer');
				
		}
		

	}
}
add_action('admin_init', 'nggcb_colorbox_auto_install');


function nggcb_please_deactivate_colorboxer() {
    	
	echo '
	<div id="message" class="updated">
	<p>
	To use a different gallery effect, please deactivate the NextGEN Gallery ColorBoxer plugin 
	and return to 
	<a href="' . admin_url( 'admin.php?page=nggallery-options#effects' , __FILE__) . '" target="_blank">
	Gallery --> Options --> Effects</a> to make your changes.
	</p>
	</div>
	';
	
}


function nggcb_colorbox_auto_uninstall() {
	global $nggcb_options;
	$nggcb_nextgen_options = get_option('ngg_options');
	
	if (is_admin()) {
		
		if (isset($nggcb_options['original_nextgen_thumbEffect']) && isset($nggcb_options['original_nextgen_thumbCode'])) {

			// switch nextgen back to original values on deactivation
			$nggcb_nextgen_options['thumbEffect'] = $nggcb_options['original_nextgen_thumbEffect']; // insert field or update value in array
			$nggcb_nextgen_options['thumbCode'] = $nggcb_options['original_nextgen_thumbCode']; // insert field or update value in array
			update_option('ngg_options', $nggcb_nextgen_options); // update option array
						
		}
	
		// empty our settings so we can run again on reactivation
		$nggcb_options['original_nextgen_thumbEffect'] = 'none';
		$nggcb_options['original_nextgen_thumbCode'] = 'none';
		$nggcb_options['auto_colorbox_install'] = 'uninstalled';
		update_option('nextgen_gallery_colorboxer_settings', $nggcb_options); // update option array
	
	}
}
register_deactivation_hook(__FILE__, 'nggcb_colorbox_auto_uninstall');