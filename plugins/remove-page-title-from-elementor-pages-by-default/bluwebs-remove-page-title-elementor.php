<?php
/*
Contributors: atdheboshnjaku
Tags: elementor, elementor pro, hello, hello theme, hello elementor, hello elementor theme, elementor theme, header title, header, title
Plugin Name:  Remove Page Title From Elementor Pages by Default
Description:  Remove the page title from all Elementor pages by default while using the Hello theme without the need to manually do it on every single page.
Plugin URI:   https://wordpress.org/plugins/remove-page-title-from-elementor-pages-by-default
Author:       Atdhe Boshnjaku
Version:      1.0.1
Tested up to: 5.9
Stable tag:   1.0.1
Requires PHP: 7.0
Text Domain:  bluwebs-remove-page-title-elementor
License:      GPL v2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.txt
*/

// Disable direct file access
if ( ! defined( 'ABSPATH' ) ) {
    
    exit;
    
}


// Removing the page title automatically from all pages created in the Hello Elementor Theme
function bluwebs_remove_page_title( $return ) {
   return false;
}
add_filter( 'hello_elementor_page_title', 'bluwebs_remove_page_title' );

