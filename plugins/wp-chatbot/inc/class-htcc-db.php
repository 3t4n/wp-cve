<?php
/**
 * Database - values, default values .. 
 * plugin details
 * plugin settings - options page
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'HTCC_db' ) ) :

class HTCC_db {


    /**
     * Add plugin Details to db - wp_options table
     * Add plugin version to db - useful while updating plugin
     * 
     * @uses class-htcc-register -> activate()
     * @return void
     */
    public static function db_plugin_details() {

        // plugin details 
        $plugin_details = array(
            'version' => HTCC_VERSION,
        );

        // Always use update_option - override new values .. don't preseve already existing values
        update_option( 'htcc_plugin_details', $plugin_details );
    }




    /**
     * options page - default values.
     * 
     * @uses class-htcc-register -> activate()
     * @return void
     */
    public static function db_default_values() {

        /**
         * plugin details 
         * name: htcc_options
         * @key enable - 1, means true. show the button.
         * 
         * greeting_dialog_display  -  show, hide, fade
         * greeting_dialog_delay  -  number in seconds with in quotes
         * 
         * 

         * 
         * checkbox
         *  is_sdk_after_page_load - if checked sdk will load after page load
         *  is_sdk_4_seconds  - if both checked - after page loaded, load sdk after 4 seconds
         */
		$defpage_value =array (
			'mobilemonkey_token' =>'',
			'htcc_fb_js_src' =>''
		);

        $values = array(
            // 'enable' => '1', Deprecated
            'fb_page_id' => '',
            'fb_app_id' => '',
            'log_events' => 'no',
            'fb_sdk_lang' => 'English',

            'fb_color' => '',
            'fb_greeting_login' => '',
            'fb_greeting_logout' => '',
            
            'list_hideon_pages' => '',
            'list_hideon_cat' => '',
            'shortcode' => 'chatbot',
			'greeting_dialog_display' => '',
			'greeting_dialog_delay' => '',
			'ref' => '',
        );

        $db_values = get_option( 'htcc_options', array() );
        $update_values = array_merge($values, $db_values);
        update_option('htcc_options', $update_values);
		foreach ($defpage_value as $key=>$value){
			update_option($key, $value);
		}
    }




}

endif; // END class_exists check