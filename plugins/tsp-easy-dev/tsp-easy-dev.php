<?php
    /*
    Plugin Name: 	LAPDI Easy Dev
    Plugin URI: 	https://letaprodoit.com/apps/plugins/wordpress/easy-dev-for-wordpress/
    Description: 	Easy Dev is a <strong>Framework</strong> for WordPress Plugin development. See <a target="_blank" href="https://lab.letaprodoit.com/public/wiki/wordpress-ed:MainPage">Framework Docs</a> for information and instructions. <a target="_blank" href="https://twitter.com/#bringbackOOD">#bringbackOOD</a>
    Author: 		Let A Pro Do IT!
    Author URI: 	https://letaprodoit.com/
    Version: 		2.0.3
    Text Domain: 	tsped
    Copyright: 		Copyright � 2021 Let A Pro Do IT!, LLC (www.letaprodoit.com). All rights reserved
    License: 		APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
    */

    require_once(ABSPATH . 'wp-admin/includes/plugin.php' );

    /**
     * Every plugin that uses Easy Dev must define a UNIQUE variable that holds the plugin's file name
     *
     * @var string
     */
    @define('TSP_EASY_DEV_FILE', 				__FILE__ );
    /**
     * Every plugin that uses Easy Dev must define a UNIQUE variable that holds the plugin's absolute path
     *
     * @var string
     */
    @define('TSP_EASY_DEV_PATH',				plugin_dir_path( __FILE__ ) );

    global $easy_dev_settings;

    include( TSP_EASY_DEV_PATH . 'TSP_Easy_Dev.autoload.php');

    //--------------------------------------------------------
    // initialize the plugin
    //--------------------------------------------------------

    $easy_dev 								= new TSP_Easy_Dev( TSP_EASY_DEV_FILE , TSP_EASY_DEV_REQ_VERSION );

    // Display the parent page but not the options page for this plugin
    $easy_dev->set_options_handler( new TSP_Easy_Dev_Options_Easy_Dev( $easy_dev_settings ) );

    $easy_dev->add_link ( 'FAQ', 			preg_replace("/\%PLUGIN\%/", TSP_EASY_DEV_NAME, TSP_WORDPRESS_FAQ_URL ));
    $easy_dev->add_link ( 'Rate Me', 		preg_replace("/\%PLUGIN\%/", TSP_EASY_DEV_NAME, TSP_WORDPRESS_RATE_URL ) );
    $easy_dev->add_link ( 'Support', 		preg_replace("/\%PLUGIN\%/", 'wordpress-ed', TSP_LAB_BUG_URL ));

    // SCRIPTS: Queue Admin
    $easy_dev->add_script( TSP_EASY_DEV_ASSETS_JS_URL . 'easy-dev-admin-script.js',  array('jquery','jquery-ui-tabs'), true );
    $easy_dev->add_script( TSP_EASY_DEV_ASSETS_JS_URL . 'easy-dev-global.js',  array('jquery'), true );
    $easy_dev->add_script( TSP_EASY_DEV_VENDOR_URL . 'twbs/bootstrap/dist/js/bootstrap.min.js',  array('jquery'), true );

    // STYLES: Queue Admin
    $easy_dev->add_css( TSP_EASY_DEV_VENDOR_URL . 'fortawesome/font-awesome/css/font-awesome.min.css', true );
    $easy_dev->add_css( TSP_EASY_DEV_VENDOR_URL . 'twbs/bootstrap/dist/css/bootstrap.min.css', true );
    $easy_dev->add_css( TSP_EASY_DEV_VENDOR_URL . 'twbs/bootstrap/dist/css/bootstrap-theme.min.css', true );
    $easy_dev->add_css( TSP_EASY_DEV_ASSETS_CSS_URL . 'easy-dev-style-admin.css', true );

    $easy_dev->set_plugin_icon( TSP_EASY_DEV_ASSETS_IMAGES_URL . 'icon_16.png' );

    $easy_dev->run( TSP_EASY_DEV_FILE );

