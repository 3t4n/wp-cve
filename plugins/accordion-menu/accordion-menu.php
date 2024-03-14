<?php
	/**
	 * @package Accordion-Menu
	 * @author Bruce Drummond
	 * @version 1.1
	 */
	/*
	Plugin Name: Accordion Menu
	Plugin URI: https://wordpress.org/plugins/accordion-menu
	Description: This plugin enables a simple jquery accordion menu for your widgets.
	Author: Bruce Drummond
	Version: 1.1
	Author URI: http://www.bruzed.com/
	License: GPL2
	*/

	function accordion_menu_init(){
		wp_enqueue_script('jquery-accordion', '/wp-content/plugins/accordion-menu/accordion-menu.js', array('jquery'));
	}

	add_action('init', 'accordion_menu_init');

?>