<?php
/**
* WP Post Disclaimer* 
* Copyright(c) 2019 Krunal Prajapati
**/
if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') ) :
	exit; // Exit if accessed directly.
endif; //Endif

	//Delete Set Option
	delete_option('wppd_plugin_version');
	//Delete Plugin Options
	delete_option('wppd_options');