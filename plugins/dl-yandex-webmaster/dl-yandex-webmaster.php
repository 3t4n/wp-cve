<?php
/*
Plugin Name: DL Yandex Webmaster
Description: Данный плагин позволит вам легко просматривать информацию Яндекс Вебмастер прямо в консоли вашего сайта

Яндекс. Вебмастер — сервис Яндекса для вебмастеров, панель инструментов для оценки индексации сайта и настройки описания сайта в результатах поиска Яндекса. Используется для показа статистики сайта.
Plugin URI: http://dd-l.name/wordpress-plugins/
Version: 0.2
Author: Dyadya Lesha (info@dd-l.name)
Author URI: http://dd-l.name
*/

add_action( 'admin_menu', 'dl_yandex_webmaster_menu' );

function dl_yandex_webmaster_menu(){	
	add_menu_page( 
		'DL Yandex Webmaster',
		'DL Yandex Webmaster',
		'administrator',
		'dl-yandex-webmaster-start',
		'dl_yandex_webmaster_start',
		'dashicons-chart-bar'
		);
}


function dl_yandex_webmaster_start() { 
	if(get_option('dl_yandex_webmaster_host_id') == '') {
		include 'page-install.php'; 
	} else {
		include 'page-dashboard.php';	
	}
}


add_action( 'admin_init', 'dl_yandex_webmaster_register_settings' );

function dl_yandex_webmaster_register_settings() {
	register_setting( 'dl-yandex-webmaster-settings-group', 'dl_yandex_webmaster_token' );
	register_setting( 'dl-yandex-webmaster-settings-group', 'dl_yandex_webmaster_user_id' );
	register_setting( 'dl-yandex-webmaster-settings-group', 'dl_yandex_webmaster_host_id' );
}


register_deactivation_hook( __FILE__, 'dl_yandex_webmaster_plugin_deactivation');

function dl_yandex_webmaster_plugin_deactivation(){
	delete_option( 'dl_yandex_webmaster_token');
	delete_option( 'dl_yandex_webmaster_user_id');
	delete_option( 'dl_yandex_webmaster_host_id');
}