<?php
/*
 * Plugin Name: Font Squirrel (unofficial)
 * Author URI: http://www.1nterval.com
 * Description: Bring fonts from http://www.fontsquirrel.com/ into WordPress
 * Author: Fabien Quatravaux
 * Version: 1.0
*/

defined( 'ABSPATH' ) or die();

foreach ( array( 'controller/class.fontlibrary' ) as $file )
	require_once sprintf( '%s/%s.php', dirname( __FILE__ ), $file );

new FontLibrary();
