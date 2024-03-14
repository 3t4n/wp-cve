<?php
    /*

    Plugin Name: WPHindi - Type in Hindi in WordPress
    Description: Type in Hindi inside WordPress. Hello -> हेलो. WPHindi helps you save time by letting you type inside Classic Editor and Gutenberg in Hindi.
    Author: Zozuk
    Author URI: https://www.zozuk.com
    Version: 2.3.1
    Requires at least: 5.0

    */
    include(plugin_dir_path(__FILE__).'constant.php');
    include(plugin_dir_path(__FILE__).'class/zozuk-transliterator.php');
    include(plugin_dir_path(__FILE__).'class/wphindi-deactivation-feedback.php');

    add_action( 'admin_enqueue_scripts', function($hook){
        new Zozuk_Transliterator($hook);
        new WPHindi_Deactivation_Feedback($hook);
    });

    /*
        Enqueue Required FrontEnd Scripts.
    */
    add_action( 'wp_enqueue_scripts', function($hook){
        wp_enqueue_style('wphindi-frontend',
            plugin_dir_url(__FILE__).'/assets/css/wphindi-frontend.css',
            null,
            WPHINDI_VERSION
        );
    });