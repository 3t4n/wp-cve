<?php
/**
 * @link              https://profiles.wordpress.org/mdesignfa/
 * @since             1.0.0
 * @package           persianadminfonts
 * 
 * @wordpress-plugin
 * Plugin Name:      Persian Admin Fonts
 * Plugin URI:       https://landing.mdezign.ir/
 * Description:      تنظیم فونت های زیبای فارسی برای ادمین وردپرس به همراه +10 فونت معروف فارسی    
 * Version:          4.0.30
 * Author:           M_Design
 * License:          GPL-3.0+
 * License URI:      http://www.gnu.org/licenses/gpl-3.0.txt
 * Author URI:       https://www.rtl-theme.com/author/delbarbash/products/
 * Text Domain:      pfmdz
 * Domain Path:      /languages
 */

if (!function_exists('add_action'))
{
    echo "<h3>an error occured! You may not be able to access this plugin via direct URL...</h3>";
    exit();
}
else if (!defined('ABSPATH'))
{
    echo "<h3>an error occured! You may not be able to access this plugin via direct URL...</h3>";
    exit();
}

/*Define Plugins Consts*/
if (!defined('PFMDZ_VERSION')){ define('PFMDZ_VERSION', '4.0.30'); }
if (!defined('persianfontsmdez_PATH')){define('persianfontsmdez_PATH', plugin_dir_path(__FILE__));}
if (!defined('persianfontsmdez_URL')){define('persianfontsmdez_URL', plugin_dir_url(__FILE__));}
if (!defined('PFMDZ_plugin')) { define('PFMDZ_plugin', __FILE__); } //def self plugin
/**/

/*add settings btn*/
if(!function_exists('pfmdz_addsettingsbtn')){
function pfmdz_addsettingsbtn( array $links ) {
    $url = get_admin_url() . "options-general.php?page=persian-fonts-options";
    $settings_link = '<a href="' . $url . '">' .__('Settings', 'pfmdz'). '</a>';
    $links[] = $settings_link;
    return $links;
}
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'pfmdz_addsettingsbtn' );
/**/

/*Translations*/
if (!function_exists('pfmdz_i18n')){
    function pfmdz_i18n() {
        $test = load_plugin_textdomain( 'pfmdz', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }
}
add_action('init', 'pfmdz_i18n');
/**/

/*remove Elem fonts & Google Fonts*/
$remove_elem_fonts = get_option("pfmdz_removeelemfonts");
if($remove_elem_fonts == '1'){
    add_filter( 'elementor/frontend/print_google_fonts', '__return_false' );
    update_option('elementor_disable_typography_schemes', 'yes');
}

$remove_goog_fonts = get_option("pfmdz_removegooglefonts");
if($remove_goog_fonts == '1'){

    add_action( 'admin_enqueue_scripts', function() {

    $all_fonts_to_remove = get_option("pfmdz_googfontstoremove");

    if (is_array($all_fonts_to_remove) || is_object($all_fonts_to_remove)){

    foreach($all_fonts_to_remove as $all_fonts_to_remov){

        $tmp_arr = explode("-", $all_fonts_to_remov);
        array_pop($tmp_arr);
        $tmp_arr2 = implode("-", $tmp_arr);

        wp_deregister_style($tmp_arr2);
	    wp_dequeue_style($tmp_arr2);
        
    }
    }
    }, 999);

}
/**/

/*Core admin class*/
if (is_admin()) {

include_once 'admin'.DIRECTORY_SEPARATOR.'addadminAjax.php';
include_once 'admin'.DIRECTORY_SEPARATOR.'class-admin.php';

}else if (!is_admin()){

include_once 'front'.DIRECTORY_SEPARATOR.'class-front.php';

}/*END of Plugin*/
//close the PHP tag to reduce the blank spaces ?>