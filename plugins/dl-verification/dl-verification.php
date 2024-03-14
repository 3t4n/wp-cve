<?php
/*
Plugin Name: DL Verification
Description: Позволяет добавить код отследивания и код счетчика на ваш сайт
Yandex webmaster, Google webmaster, Mail webmaster, Bing webmaster, Yandex Metrika, Google Analytics, Рейтинг Mail, LiveInternet
Plugin URI: http://dd-l.name/wordpress-plugins/dl-verification/
Version: 1.2
Author: Dyadya Lesha (info@dd-l.name)
Author URI: http://dd-l.name
*/


// Регистрируем и добавляем меню настроек плагина
function dl_verification_menu() {
	add_menu_page(
		'dl-verification',
		'DL Verification',
		'administrator',
		'dl-verification/options-page.php',
		'',
		'dashicons-post-status'
	);
	add_action( 'admin_init', 'dl_verification_register_settings' );
}

add_action('admin_menu', 'dl_verification_menu');


// Добавляем допалнительную ссылку настроек на страницу всех плагинов
function dl_verification_settings_link($links) {
  $settings_link = '<a href="admin.php?page=dl-verification/options-page.php">Настроить</a>';
  array_unshift($links, $settings_link);
  return $links;
}
$plugin = plugin_basename(__FILE__);

add_filter("plugin_action_links_$plugin", 'dl_verification_settings_link' );


// Регистрируем настройки
function dl_verification_register_settings() {
	register_setting( 'dl-settings-group', 'YandexVerification' );
	register_setting( 'dl-settings-group', 'GooglVerification' );
	register_setting( 'dl-settings-group', 'BingVerification' );
	register_setting( 'dl-settings-group', 'MailVerification' );	
	register_setting( 'dl-settings-group', 'MailTop' );
	register_setting( 'dl-settings-group', 'YandexMetrika' );
	register_setting( 'dl-settings-group', 'GoogleAnalytics' );
	register_setting( 'dl-settings-group', 'LiveInternetVerification' );
	register_setting( 'dl-settings-group', 'LiveInternetIMGVerification' );
}


function dl_verification_head_code() {
	if(get_option('YandexVerification')) echo '<meta name="yandex-verification" content="' . get_option('YandexVerification') . '" />';
	if(get_option('GooglVerification')) echo '<meta name="google-site-verification" content="' . get_option('GooglVerification') . '" />';
	if(get_option('BingVerification')) echo '<meta name="msvalidate.01" content="' . get_option('BingVerification') . '" />';
	if(get_option('MailVerification')) echo '<meta name="wmail-verification" content="' . get_option('MailVerification') . '" />';
}

add_action("wp_head", "dl_verification_head_code");


function dl_verification_footer_code() {
	echo get_option('YandexMetrika');
	echo get_option('GoogleAnalytics');
	echo get_option('MailTop');
	echo get_option('LiveInternetVerification');
}

add_action("wp_footer", "dl_verification_footer_code");

function dl_liveinternet_shortcode( $atts ){
 return get_option('LiveInternetIMGVerification');
}
add_shortcode( 'dlliveinternet', 'dl_liveinternet_shortcode' );