<?php

require_once plugin_dir_path( __FILE__ ) . 'gs-plugins-common-pages.php';

new GS_Plugins_Common_Pages([
	
	'parent_slug' 	=> 'edit.php?post_type=gs-logo-slider',
	
	'lite_page_title' 	=> __('Lite Plugins by GS Plugins'),
	'pro_page_title' 	=> __('Premium Plugins by GS Plugins'),
	'help_page_title' 	=> __('Support & Documentation by GS Plugins'),

	'lite_page_slug' 	=> 'gs-logo-plugins-lite',
	'pro_page_slug' 	=> 'gs-logo-plugins-premium',
	'help_page_slug' 	=> 'gs-logo-plugins-help',

	'links' => [
		'docs_link' 	=> 'https://docs.gsplugins.com/gs-logo-slider/',
		'rating_link' 	=> 'https://wordpress.org/support/plugin/gs-logo-slider/reviews/#new-post',
		// 'tutorial_link' => 'https://docs.gsplugins.com/gs-logo-slider/',
	]

]);