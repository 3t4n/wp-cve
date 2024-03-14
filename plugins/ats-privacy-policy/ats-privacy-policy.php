<?php
/**
 * Plugin Name: ats privacy policy
 * Plugin URI: https://mihalica.ru/product/plagin-mats-privacy-policy-dobavit-privacy-policy-k-forme-kommentariev/
 * Description: После обновления плагина пройдите в настройки и пропишите тексты ссылок. Плагин ats privacy policy добавит в стандартную форму комментирования WP чекбокс "согласия" с политикой конфиденциальности сайта - privacy policy. Активируйте плагин: в форме комментирования тут же появится чекбокс согласия с политикой конфиденциальности: если не отметить галочкой - комментарий будет невозможен!
 * Simple plugin ats privacy policy allows !! непременно посетите страницу настроек плагина !! (go to the plugin settings <strong>Консоль/Настройки/Privacy Policy</strong> ) - Activate the plugin - in the form of comments will checkbox consent to the privacy policy of the site... A convenient way to set "consent" to the privacy policy on your site!
 * Version: 7.9
 * Author: ATs.M
 * Author URI: https://mihalica.ru/
 * Text Domain: policy-ats
 * License: Conditions:
 */

/*THE COMMENTING FORM - PRIVACY POLICY*/

    require( plugin_dir_path( __FILE__ ) . '/settings.php' );
    require( plugin_dir_path( __FILE__ ) . 'includes/privats/privats.php' );
    require( plugin_dir_path( __FILE__ ) . 'template/blocs.php' );
	
// plugin style 
// add_action( 'admin_menu', 'ats_plugin_admin_styles' );
// function ats_plugin_admin_styles() {
// 	wp_enqueue_style( 'ats-privacy', plugins_url('/includes/css/style-ats.css', __FILE__) );
// }

add_action( 'init', 'ats_plugin_init_styles' );
function ats_plugin_init_styles() {	
	wp_enqueue_style( 'ats-privacy', plugins_url('/includes/css/style-ats.css', __FILE__) );
}
// plugin style

//Link table settings plugins
function plugin_settings_link($links) { 
	$settings_link = '<a href="options-general.php?page=policy-ats.php">Settings/Настройки</a>'; 
	array_unshift( $links, $settings_link ); 
	return $links; 
}

//Link table settings plugins
$plugin_file = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin_file", 'plugin_settings_link' );
//Link table settings plugins
