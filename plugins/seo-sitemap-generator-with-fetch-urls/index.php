<?php
/*
Plugin Name: SEO Sitemap Generator with fetch urls 
Plugin URI:  http://www.vashikaran.biz/
Description: Easy sitemap generator for search engine indexing and automatic generated fetch urls list.
Version: 1.0
Author: Vashikaranbiz
Author URI: http://www.vashikaran.biz/
License: GPLv2 or later
*/

ob_start();
function sneha_xml_sitemap(){
	
	include "generate_sitemap.php";
	include "urlslist.php";
	
	}
add_action('admin_menu', 'add_manju_xml_admin_panel');
function add_manju_xml_admin_panel()
{
add_menu_page('site_map', 'google Site Map','read','site_map','',plugins_url( 'files/site_map.png', __FILE__ ));
add_submenu_page('site_map', 'google Site Map', 'Site Map ', 'read', 'site_map','sneha_xml_sitemap');
}