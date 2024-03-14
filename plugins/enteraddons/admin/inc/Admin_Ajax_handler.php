<?php
namespace Enteraddons\Admin;
/**
 * Enteraddons admin
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */


if( !defined( 'WPINC' ) ) {
    die;
}

if( !class_exists('Admin_Ajax_handler') ) {

    class Admin_Ajax_handler {

        private static $instance = null;

        function __construct() {
            add_action( 'wp_ajax_settings_data_save_action',  [$this , 'settings_data_save'] );
        }
        
        public static function getInstance() {
            if( self::$instance == null ) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function settings_data_save() {
            $getData = [];
            // Check user permission
            if( !current_user_can('manage_options') ) {
                return;
            }

            // Verifies the Ajax request
            if( !check_ajax_referer( 'enteraddons-settings-data-save', 'nonce' ) ) {
                wp_send_json_error();
            }
            
            $getPostedData = !empty( $_POST['data'] ) ? $_POST['data'] : '';

            $data = array();
            parse_str( $getPostedData, $data );

            $getData['widgets'] = isset( $data['enteraddons_widgets'] ) && is_array( $data['enteraddons_widgets'] ) ? array_map( 'sanitize_text_field', $data['enteraddons_widgets'] ) : [];

            $getData['integration'] = isset( $data['enteraddons_integration'] ) && is_array( $data['enteraddons_integration'] ) ? array_map( 'sanitize_text_field', $data['enteraddons_integration'] ) : [];
            
            $getData['extensions'] = isset( $data['enteraddons_extensions'] ) && is_array( $data['enteraddons_extensions'] ) ? array_map( 'sanitize_text_field', $data['enteraddons_extensions'] ) : [];
            
            update_option( ENTERADDONS_OPTION_KEY,  $getData );
            wp_send_json_success();

        }

    }

}