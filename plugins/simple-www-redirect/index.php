<?php
/*
Plugin Name: Simple WWW Redirect
Plugin URI: https://lightplugins.com/plugins/simple-www-redirect/
Description: The plugin specifies whether your domain will include www. Redirects www to non-www or non-www to www.
Author: LightPlugins
Author URI: https://lightplugins.com/
Version: 1.0.2
Text Domain: swr
*/

// don't allow to load this file directly.
if (!defined('ABSPATH')) {
    die('-1');
}


// textdomain
function swr_load_plugin_textdomain() {
    load_plugin_textdomain('swr', false, dirname(plugin_basename(__FILE__)) . '/lang' );
}

add_action( 'plugins_loaded', 'swr_load_plugin_textdomain' );


// gettings the subdomain
function swr_get_subdomain($host){
	return substr_count($host, '.') > 1 ? substr($host, 0, strpos($host, '.')) : '';
}


// Checks if the website supports www
function swr_support_test(){

	// get site url
	$siteurl = get_option("siteurl");
	$home = get_option("home");

	// parsing
	$parsed_url1 = wp_parse_url($siteurl);
	$parsed_url2 = wp_parse_url($home);

	// capability required: manage_options
	if (!current_user_can("manage_options")){
		return __("You are not authorized to perform this operation.", "swr");
	}

	// the IP address doesn't support www.
	if(filter_var($parsed_url1["host"], FILTER_VALIDATE_IP)) {
  		return __("The domain looks like an IP address. IP addresses do not support www.", "swr");
	}

	// localhost does not support www.
	if($parsed_url1["host"] == "localhost"){
		return __("Localhost does not support www. Check this page after you move on a domain.", "swr");
	}

	// getting subdomain siteurl
	$subdomain_site = swr_get_subdomain($parsed_url1["host"]);

	// getting subdomain home
	$subdomain_home = swr_get_subdomain($parsed_url2["host"]);

	// subdomain need to be www or empty.
	if($subdomain_site != "www" && $subdomain_site != ""){
		return __("It looks like the domain already includes a subdomain. www cannot be used in subdomains.", "swr");
	}

	// subdomain need to be www or empty.
	if($subdomain_home != "www" && $subdomain_home != ""){
		return __("It looks like the domain already includes a subdomain. www cannot be used in subdomains.", "swr");
	}

	// No error
	return "";

}


// updating url file
function swr_update_url($type, $method){

	// capability required: manage_options
	if (!current_user_can("manage_options")){
		return false;
	}

	// get site url
	$siteurl = get_option("siteurl");
	$home = get_option("home");

	// parsing
	$parsed_url1 = wp_parse_url($siteurl);
	$parsed_url2 = wp_parse_url($home);

	// htaccess
	$htaccess_file = ABSPATH . ".htaccess";

	// the IP address doesn't support www.
	if(filter_var($siteurl, FILTER_VALIDATE_IP)) {
  		return false;
	}

	// localhost does not support www.
	if($siteurl == "localhost"){
		return false;
	}

	// getting subdomain siteurl
	$subdomain_site = swr_get_subdomain($parsed_url1["host"]);

	// getting subdomain home
	$subdomain_home = swr_get_subdomain($parsed_url2["host"]);

	// subdomain need to be www or empty.
	if($subdomain_site != "www" && $subdomain_site != ""){
		return false;
	}

	// subdomain need to be www or empty.
	if($subdomain_home != "www" && $subdomain_home != ""){
		return false;
	}

	// getting the website protocol
	$protocol = (is_ssl() ? 'https://' : 'http://');

	// force www
	if($type == "www"){

		// redirect rules
		$rules = "RewriteEngine On
RewriteCond %{HTTP_HOST} !^www\. [NC]
RewriteRule ^(.*)$ ".$protocol."www.%{HTTP_HOST}/$1 [R=301,L]";
		
		// add www to siteurl
		if($subdomain_site != "www"){
			update_option("siteurl", str_replace("://", "://www.", $siteurl));
		}

		// add www to home
		if($subdomain_home != "www"){
			update_option("home", str_replace("://", "://www.", $home));
		}

	// force non-www
	}else if($type == "non-www"){

		// redirect rules
		$rules = "RewriteEngine On
RewriteCond %{HTTP_HOST} ^www\.(.*)$ [NC]
RewriteRule ^(.*)$ ".$protocol."%1/$1 [R=301,L]";

		// remove www from siteurl and home
		update_option("siteurl", str_replace("://www.", "://", $siteurl));
		update_option("home", str_replace("://www.", "://", $home));

	}

	// checks if htaccess file is exists
	if (file_exists($htaccess_file)){

		// remove insert
		if($method == "deactivate"){
			insert_with_markers($htaccess_file, "Simple www Redirect", array());

		// insert
		}else if(strlen($rules) > 0){
			insert_with_markers($htaccess_file, "Simple www Redirect", explode("\n", $rules));
		}

	}

}


// the plugin deactivate hook
function swr_deactivate_hook(){

	// delete redirect lines from htaccess file when deactivated
    swr_update_url(null, "deactivate");

}

register_deactivation_hook( __FILE__, 'swr_deactivate_hook' );


// add option page to settings page
function swr_add_option_page() {

	//create new top-level menu
	add_options_page( 'Simple www Redirect',  'Simple www Redirect',  'manage_options',  'simple-www-redirect',  'swr_option_page_content');

}

add_action('admin_menu', 'swr_add_option_page');



// register settings for admin page
function swr_register_plugin_settings() {

	// register the settings
	register_setting( 'swr_plugin_settings', 'swr_force_type' );

}

add_action( 'admin_init', 'swr_register_plugin_settings' );


// add scripts and styles for the plugin's admin page
function swr_admin_scripts($hook) {
    
	if($hook == "settings_page_simple-www-redirect"){
		wp_enqueue_script('swr-admin', plugins_url('js/admin.js', __FILE__));
    	wp_enqueue_style('swr-admin', plugins_url('css/admin.css', __FILE__));
    }
    
}

add_action('admin_enqueue_scripts', 'swr_admin_scripts');



// Hook into options page after save.
function swr_url_update_hook( $old, $value ) {

	// Update domain
    swr_update_url($value);

}

add_action( 'update_option_swr_force_type', 'swr_url_update_hook', 10, 2);
add_action( 'add_option_swr_force_type', 'swr_url_update_hook', 10, 2);



// adds admin page contents
function swr_option_page_content(){ ?>
<div class="wrap">
<h1>Simple www Redirect</h1>
<form method="post" action="options.php">
    <?php

    // settings save api
    settings_fields('swr_plugin_settings');
    do_settings_sections('swr_plugin_settings');

    // getting the type
    $type = get_option('swr_force_type');

	// variables
    $nonwww = "";
    $www = "";

    // specifies which option is active
    if("non-www" == $type){
    	$nonwww = " checked";
    }else if($type == "www"){
    	$www = " checked";
    }

    // get and parse siteurl
    $siteurl = get_option("siteurl");
    $siteurl = str_replace("www.", "", $siteurl);
    $siteurl = wp_parse_url($siteurl);

    // look for errors
    $errors = swr_support_test();
    $error_class = "";

    // is there an error?
    if($errors != ""){
    	$error_class = ' class="swr-has-error"';
    }

    ?>

    <div id="swr-wrap">

    <div id="swr-settings"<?php echo $error_class; ?>>

    	<p id="swr-information"><?php _e('The plugin specifies whether your domain will include www. The plugin basically creates redirect rule and adds to the .htaccess file. Choose Your Style!', 'swr'); ?></p>

		<p class="swr-errors"><?php echo $errors; ?></p>

		<div class="swr-radio first-child<?php echo $www; ?>"><input type="radio" name="swr_force_type" id="www" value="www" <?php echo $www; ?> /><label for="www">www.<?php echo $siteurl["host"]; ?></label></div><div class="swr-radio last-child<?php echo $nonwww; ?>"><input type="radio" name="swr_force_type" id="non-www" value="non-www" <?php echo $nonwww; ?> /><label for="non-www"><?php echo $siteurl["host"]; ?></label></div>

		<?php if($errors == ""){ ?>
		<div class="swr-submit">
    		<input type="submit" class="button button-primary" name="submit" value="<?php _e("Save Changes", "swr"); ?>">
    	</div>
    	<?php } ?>

    </div>

    </div>

    <p class="light-plugins-link">This plugin total is only 6KB.<br />Do you like lightweight plugins? Check <a href="https://www.lightplugins.com/?ref=simple-www-redirect" target="_blank">lightplugins.com</a></p>

</form>
</div>
<?php

}