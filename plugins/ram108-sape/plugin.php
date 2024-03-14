<?php
/*
Plugin Name: [ram108] SAPE Links
Plugin URI: http://wordpress.org/plugins/ram108-sape/
Description: SAPE.RU ссылки для веб-мастеров. Добавляет виджет, макрос [sape] и контекстные ссылки на страницы сайта.
Version: 0.5.6
Author: ram108
Author URI: http://profiles.wordpress.org/ram108
Author Email: plugin@ram108.ru
License: GPL2 or higher
License URI: http://www.gnu.org/licenses/gpl-2.0.html
===========================================================
Copyright 2013-2015 Kirill Borodin plugin@ram108.ru
http://www.ram108.ru/donate
OM SAI RAM
*/

// DEFINE
define( '_RAM108_SAPE', __FILE__ );
define( '_RAM108_SAPE_DIR', dirname( __FILE__ ) );
define( '_RAM108_SAPE_VER', '0.5' );

// LIBRARY
require( _RAM108_SAPE_DIR.'/library/settings.php' );
require( _RAM108_SAPE_DIR.'/library/plugin.php' );

// INCLUDE
require( _RAM108_SAPE_DIR.'/include/ram108_sape.php' );
require( _RAM108_SAPE_DIR.'/include/ram108_sape_widget.php' );
require( _RAM108_SAPE_DIR.'/include/plugin_admin.php' );
