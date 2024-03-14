<?php
if (!defined('ABSPATH')) {
    exit;
}
/***** ****** :::::::::::::::: :::::::::::::::::::::::::::::: 
 *
 * start_date 2021/06  
 * @since 1.0 
 *
 ****************** ::::::::::::::  *******************/

if (!class_exists('WooCommerce')) {
    return;
}

if (!did_action('elementor/loaded')) {
    return;
}

// initiualize templating  
Shop_Ready\extension\templates\Service::register_services();

// // initiualize Grid Stucture
Shop_Ready\extension\elegrid\Service::register_services();
// // initiualize Header Footer
Shop_Ready\extension\header_footer\Service::register_services();
// // initiualize Finder Links Cats
Shop_Ready\extension\elefinder\Service::register_services();

// // initialize WpShorCode Extension  
Shop_Ready\extension\wpshortcode\Service::register_services();

// // initialize Elementor Widgets Extension  
Shop_Ready\extension\elewidgets\Service::register_services();

// // initialize Elementor Template Library 
Shop_Ready\extension\elelibrary\Service::register_services();

// // initialize Wrapper Link
Shop_Ready\extension\elewrapper\Service::register_services();

// // initialize General Widgets
Shop_Ready\extension\generalwidgets\Service::register_services();
// // Sticky Section 
Shop_Ready\extension\sticky_section\Service::register_services();

Shop_Ready\extension\shopajax\Service::register_services();
Shop_Ready\extension\blocks\Service::register_services();