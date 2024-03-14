<?php

/**
 * Plugin Name: Replace or Remove Google Fonts
 * Plugin URI: https://wordpress.org/plugins/use-bunnyfont-host-google-fonts/
 * Description: Disable and remove google fonts or simply replace all Google Fonts with BunnyFonts (GDPR friendly)
 * Version: 1.5
 * Author: easywpstuff
 * Author URI: https://easywpstuff.com/
 * License: GNU General Public License v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: remove-replace-gf
 * Domain Path: /l10n
**/


// If this file is called directly, abort.
if (!defined("WPINC")){
	die;
}


/**
 * Silent is golden
**/
define("BFH_EXEC",true);


/**
 * Debug
**/
define("BFH_DEBUG",false);


/**
 * Plugin File
**/
define("BFH_FILE",__FILE__);


/**
 * Plugin Path
**/
define("BFH_PATH",dirname(__FILE__));


/**
 * Plugin Base URL
**/
define("BFH_URL",plugins_url("/",__FILE__));


// include option page

require BFH_PATH . "/inc/options.php";
require BFH_PATH . "/vendor/autoload.php";
/**
 * Begins execution of the plugin.
**/

// replace google fonts with bunnyfonts
function bfh_run_bunnyfont( $html ) { 
	$html = str_replace('fonts.googleapis.com', 'fonts.bunny.net', $html);
    $html = preg_replace_callback('/<link[^>]+>/', function($match) {
    // Check if the <link> tag contains crossorigin, fonts.gstatic, and prefetch or preconnect
    if (strpos($match[0], 'crossorigin') !== false
        && strpos($match[0], 'fonts.gstatic') !== false
        && (strpos($match[0], 'prefetch') !== false || strpos($match[0], 'preconnect') !== false)
    ) {
        // Replace fonts.gstatic.com with fonts.bunny.net
        $match[0] = str_replace('fonts.gstatic.com', 'fonts.bunny.net', $match[0]);
    }
    return $match[0];
}, $html);
	return $html;
}

// function to remove google fonts
function bfh_remove_google_fonts($buffer) {
   
   $buffer = preg_replace('/<link\s+[^>]*?href=["\']?(?<url>(?:https?:)?\/\/fonts\.googleapis\.com\/[^"\'>]+)["\']?[^>]*?>/', '', $buffer);
	
   $buffer = preg_replace('/@font-face\s*\{[^\}]*?src:\s*url\([\'"]?(?<url>(?:https?:)?\/\/fonts\.gstatic\.com\/[^\'"]+)[\'"]?\).*?\}/s', '', $buffer);
	
   $buffer = preg_replace('/@import\s+url\([\'"]?(?<url>(?:https?:)?\/\/fonts\.googleapis\.com\/[^\'"]+)[\'"]?\);/', '', $buffer);
	
	$buffer = preg_replace('/<script[^>]*>([^<]*WebFontConfig[^<]*googleapis\.com[^<]*)<\/script>/', '', $buffer);
	
	$buffer = preg_replace('/<link\s+(?:[^>]*\s+)?href=["\']?(?:https?:)?\/\/fonts\.gstatic\.com[^>]*>/', '', $buffer);
	
	$buffer = preg_replace('/<link\s+(?:[^>]*\s+)?href=["\']?(?:https?:)?\/\/fonts\.googleapis\.com[^>]*>/', '', $buffer);
  
  return $buffer;
}

// run this function to do replace and remove
function bfh_remove_google_add_bunny($output) {
	
	$output = bfh_run_bunnyfont( $output );
	$output = bfh_remove_google_fonts( $output );
	
	return $output;
}

// Register the and enqueue the script
function add_remove_gf_script_to_footer() {
	 $options = get_option('bunnyfonts_options');
  if (isset($options['remove_google_fonts_jquery']) && $options['remove_google_fonts_jquery']) {

	  wp_enqueue_script('remove-gf', BFH_URL . 'assets/remove-gf.js', array(), false, true);
  }
}
add_action( 'wp_footer', 'add_remove_gf_script_to_footer' );

// run bunnyfont function

function bfh_choose_ob_start_callback() {
  $options = get_option('bunnyfonts_options');

  // Define the default callback
  $callback = null;

  // Check if the replace font option is enabled
  if (isset($options['replace_google_fonts']) && $options['replace_google_fonts'] && !isset($options['block_google_fonts'])) {
    $callback = 'bfh_run_bunnyfont';
	  add_filter( 'wordpress_prepare_output', 'bfh_run_bunnyfont', 11 );
	  add_filter('groovy_menu_final_output', 'bfh_run_bunnyfont', 11);
  }

  // Check if remove font option is enabled
  if (isset($options['block_google_fonts']) && $options['block_google_fonts'] && !isset($options['replace_google_fonts'])) {
    $callback = 'bfh_remove_google_fonts';
	  add_filter( 'wordpress_prepare_output', 'bfh_remove_google_fonts', 11 );
	  add_filter('groovy_menu_final_output', 'bfh_remove_google_fonts', 11);
  }

  // Check if both options are enabled
  if (isset($options['block_google_fonts']) && $options['block_google_fonts'] && isset($options['replace_google_fonts']) && $options['replace_google_fonts']) {
    $callback = 'bfh_remove_google_add_bunny';
	  add_filter( 'wordpress_prepare_output', 'bfh_remove_google_add_bunny', 11 );
	  add_filter('groovy_menu_final_output', 'bfh_remove_google_add_bunny', 11);
  }

  if ($callback !== null) {
    ob_start($callback);
  }
}

function run_bunnyfont_template_redirect() {
  // Call the function to choose the ob_start callback
  bfh_choose_ob_start_callback();
}

add_action('template_redirect', 'run_bunnyfont_template_redirect', -1000);



register_activation_hook(__FILE__, 'bfh_font_plugin_activate');
add_action('admin_init', 'bfh_font_plugin_redirect');

function bfh_font_plugin_activate() {
    add_option('bfh_font_do_activation_redirect', true);
}

function bfh_font_plugin_redirect() {
    if (get_option('bfh_font_do_activation_redirect', false)) {
        delete_option('bfh_font_do_activation_redirect');
		if(!isset($_GET['activate-multi']))
        {
        wp_safe_redirect(admin_url('options-general.php?page=remove-replace-gf'));
        exit;
		}
    }
}

// added settings link
function bunnyadd_settings_link($links) {
  $settings_link = '<a href="' . admin_url( 'options-general.php?page=remove-replace-gf' ) . '">' . __( 'Settings' ) . '</a>';
  array_unshift( $links, $settings_link );
  return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'bunnyadd_settings_link' );


/**
 * Initialize the plugin tracker
 *
 * @return void
 */
function appsero_init_tracker_use_bunnyfont_host_google_fonts() {

    if ( ! class_exists( 'Appsero\Client' ) ) {
      require_once __DIR__ . '/appsero/src/Client.php';
    }

    $client = new Appsero\Client( '84913d70-971f-41dc-b310-6aed8fcfc989', 'Replace or Remove Google fonts', __FILE__ );

    // Active insights
    $client->insights()->init();

}

appsero_init_tracker_use_bunnyfont_host_google_fonts();