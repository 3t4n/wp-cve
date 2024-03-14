<?php
/*
Plugin Name: Better AdSense Targeting
Plugin URI: http://www.chriskdesigns.com/
Description: This plug-in provides the ability to tell Google AdSense if the content in the following section should be used to determine the Ads displayed.
Version: 1.2
Author: Chris Klosowski
Author URI: http://www.chriskdesigns.com/
License: GPL
*/

// Let's add a little action into the plug-in
if ( is_admin() ) { 
	//Adds admin verification
	add_action('admin_menu', 'bat_menu');
	add_action('admin_init', 'register_bat_settings');
	register_deactivation_hook('better-adsense-targeting/better-adsense.php', 'de_register_settings');
} else {
	// non-admin enqueues, actions, and filters
	add_action( 'wp_head', 'bat_pu_header' );
	add_action( 'loop_start', 'bat_pu_loop_start');
	add_action( 'loop_end', 'bat_pu_loop_end' );
	add_action( 'get_sidebar', 'bat_pu_sidebar' );
	add_action( 'wp_footer', 'bat_pu_footer');
	add_shortcode( 'ignore', 'bat_pu_shortag');
}

// Start the Header Section
function bat_pu_header() {
  echo '<!-- Google AdSense Targeting powered by ChrisKDesigns.com -->';
	if (get_option('bat_header') == '1') {
		place_start_code(true);
	} else {
		place_start_code(false);
	}
}

// End the Header Section and Start The Loop Section
function bat_pu_loop_start() {
  if (set_sidebar_static() == NULL) {
	  place_end_code();
	
  	if (get_option('bat_loop') == '1') {
  		place_start_code(true);
  	} else {
  		place_start_code(false);
  	}
  }
}

// End the Loop Section
function bat_pu_loop_end() {
  if (set_sidebar_static() == NULL) {
	  place_end_code();
  }
}

// Start the Sidebar Section
function bat_pu_sidebar() {
  $sidebar_started = set_sidebar_static(true);
  
  if (get_option('bat_sidebar') == '1') {
		place_start_code(true);
	} else {
		place_start_code(false);
	}
}

// End the Sidebar Section and begin the footer
function bat_pu_footer() {
  if (set_sidebar_static() == NULL) {
	  place_end_code();
  }
	
	if (get_option('bat_footer') == '1') {
		place_start_code(true);
	} else {
		place_start_code(false);
	}
}

// Shortcode addition of [ignore] and [/ignore] to use inside a post
function bat_pu_shortag($atts, $content = null){
	return '<!-- google_ad_section_start(weight=ignore) -->'.$content.'<!-- google_ad_section_end -->';
}

// The Business Functions. Where the code is echoed
function place_start_code($useIt) {
	
	if ($useIt) {
		echo '<!-- google_ad_section_start -->';
	} else {
		echo '<!-- google_ad_section_start(weight=ignore) -->';
	}
	
	return;
}

function place_end_code() {
	echo '<!-- google_ad_section_end -->';
	
	return;
}


// The Admin Area
function bat_menu() {
  add_options_page('AdSense Targeting', 'AdSense Targeting', 8, 'better-adsense-targeting', 'bat_menu_options');
}

function bat_menu_options() {
?>
<div class="wrap">
<h2>Better AdSense Targeting for WordPress</h2>
<em>Choose if you would like Google's AdSense to determine the ads based off the content in the following areas.</em>
<?php if (is_plugin_active('w3-total-cache/w3-total-cache.php')) { ?>
<div id="setting-error-settings_updated" class="updated settings-error"><p>W3 Total Cache is active. If you have the HTML Minify option enabled, please add 'google_ad_' to the 'Ignored comment stems' setting</p></div>
<?php } ?>
<form method="post" action="options.php">
<?php wp_nonce_field('bat-pu-options'); ?>

<table class="form-table">

<tr valign="top">
<th scope="row">Header:<br /><span style="font-size: x-small;">Blog Title, Tagline, and Navigation</span></th>
<td>
<select name="bat_header">
	<option <?php if (!get_option('bat_header') || get_option('bat_header') == '1') {?>SELECTED<?php ;}?> value="1">Yes</option>
	<option <?php if (get_option('bat_header') == '0') {?>SELECTED<?php ;}?> value="0">No</option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row">Content:<br /><span style="font-size: x-small;">Your Post or Page Content</span></th>
<td>
<select name="bat_loop">
	<option <?php if (get_option('bat_loop') == '1') {?>SELECTED<?php ;}?> value="1">Yes</option>
	<option <?php if (get_option('bat_loop') == '0') {?>SELECTED<?php ;}?> value="0">No</option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row">Sidebar:<br /><span style="font-size: x-small;">Sidebar content such as Widgets</span></th>
<td>
<select name="bat_sidebar">
	<option <?php if (get_option('bat_sidebar') == '1') {?>SELECTED<?php ;}?> value="1">Yes</option>
	<option <?php if (get_option('bat_sidebar') == '0') {?>SELECTED<?php ;}?> value="0">No</option>
</select>
</td>
</tr>
 
<tr valign="top">
<th scope="row">Footer:<br /><span style="font-size: x-small;">Copyright and possibly widgets</span></th>
<td>
<select name="bat_footer">
	<option <?php if (get_option('bat_footer') == '1') {?>SELECTED<?php ;}?> value="1">Yes</option>
	<option <?php if (get_option('bat_footer') == '0') {?>SELECTED<?php ;}?> value="0">No</option>
</select>
</td>
</tr>

</table>

<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="bat_header, bat_loop, bat_comments, bat_sidebar, bat_footer" />

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
<?php settings_fields( 'bat-pu-options' ); ?>
</form>
<span style="text-align: center;">
<h3>Like This plugin? Consider Donating</h3>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post"> <input name="cmd" type="hidden" value="_s-xclick" /> <input name="hosted_button_id" type="hidden" value="4553653" /> <input alt="PayPal - The safer, easier way to pay online!" name="submit" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" type="image" /> <img src="https://www.paypal.com/en_US/i/scr/pixel.gif" border="0" alt="" width="1" height="1" /></form>
</span>
</div>

<?php

}

// Sets up a static so that the loop comments will not fire after the sidebar has started
function set_sidebar_static($set=false) {
  static $hasRun = NULL;
  
  if ($set) {    
    if ($hasRun == NULL) {
      $hasRun = TRUE;
    }
  }
  
  return $hasRun;
}


// Register those settings!
function register_bat_settings() { // whitelist options
	register_setting( 'bat-pu-options', 'bat_header' );
	register_setting( 'bat-pu-options', 'bat_loop' );
	register_setting( 'bat-pu-options', 'bat_sidebar' );
	register_setting( 'bat-pu-options', 'bat_footer' );
	if (get_option( 'bat_comments' ) === '0' || get_option( 'bat_comments' ) === '1') {
	  delete_option( 'bat_comments' );
	}
}

// Unregister on deactivation
function de_register_settings () {
	delete_option( 'bat_header' );
	delete_option( 'bat_loop' );
	delete_option( 'bat_sidebar' );
	delete_option( 'bat_footer' );
}
?>
