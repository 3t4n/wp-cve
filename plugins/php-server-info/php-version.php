<?php
/*
Plugin Name: PHP Server Info
Plugin URI: http://catn.com
Description: Plugin to display PHP Info from Admin Menu.
Author: Ray Viljoen
Version: 1.0
*/
add_action('admin_menu', 'php_info');
function php_info() {
	add_menu_page( "PHP Info.", "PHP Info.", "manage_options", "php-info", "php_admin_info" );
}
// PHP options page
function php_admin_info() {
    if (!current_user_can('manage_options'))
    {wp_die( __('You do not have sufficient permissions to access this page.') );}
    
    // SET PLUGIN PATH REGARDLESS OF CONTAINING DIRECTORY
	$plug_path = plugins_url(  '/', __FILE__ );
    
	$phpStyle = <<<PHPSTYLE
	<style type="text/css">
	#php-info-wrapper { padding: 20px; }
	#php-info-wrapper table { padding: 1px; background: #ccc; border: 1px #777 solid; -webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px; }
	#php-info-wrapper td, th {padding:3px; background:#efefef; -webkit-border-radius: 3px;-moz-border-radius: 3px;border-radius: 3px;}
	#php-info-wrapper tr.h img { display:none; }
	#php-info-wrapper tr.h td{ background:none; }
	#php-info-wrapper tr.h { text-align:right;  height: 130px; background: url({$plug_path}php-logo.png) no-repeat 30px center; }
	#php-info-wrapper tr.h h1{ padding-right:50px; }
	</style>
PHPSTYLE;
	echo $phpStyle;
    echo '<div id="php-info-wrapper" class="wrap" >';
	ob_start();
	phpinfo();
	$pinfo = ob_get_contents();
	ob_end_clean();
	$pinfo = preg_replace( '%^.*<body>(.*)</body>.*$%ms','$1',$pinfo);
	$pinfo = preg_replace( '%^.*<title>(.*)</title>.*$%ms','$1',$pinfo);
	echo $pinfo;	
    echo '</div>';
}