<?php
defined( 'ABSPATH' ) || exit;

class TOCHATBE_Install {

    public static function install() {
        do_action( 'tochatbe_before_install' );
        self::register_settings();
        self::create_database();
        update_option( 'tochatbe_plugin_version', TOCHATBE_PLUGIN_VER );
        do_action( 'tochatbe_after_install' );
    }

    public static function register_settings() {
        foreach ( self::default_options() as $option => $value ) {
            $db_options = get_option( $option );
            if ( ! $db_options ) { // Run when install
                update_option( $option, $value );
            } else { // Run when update
                $merged_option = self::parse_args_r( $db_options, $value );
                update_option( $option, $merged_option );
            }
        }
    }

    public static function default_options() {
        return array(
            'tochatbe_appearance_settings' => array(
                'background_color'          => '#075367',
                'text_color'                => '#ffffff',
                'about_message'             => 'Duis porta, ligula rhoncus euismod pretium, nisi tellus eleifend odio, luctus viverra sem dolor id sem. Maecenas a venenatis enim',
                'trigger_btn_text'          => 'How can we help?',
                'custom_offer'              => '',
            ),
            'tochatbe_basic_settings'      => array(
                'location'             => 'br',
                'on_mobile'            => 'yes',
                'on_desktop'           => 'yes',
                'auto_popup_status'    => 'yes',
                'auto_popup_delay'     => '5',
                'filter_by_pages'   => array(
                    'on_all_pages'  => 'yes',
                    'on_front_page' => 'yes',
                    'include_pages' => array(),
                    'exclude_pages' => array(),
                ),
                'schedule'          => array(
                    'monday'    => array(
                        'status' => 'yes',
                        'start'  => '00:00:00',
                        'end'    => '23:59:59',
                    ),
                    'tuesday'   => array(
                        'status' => 'yes',
                        'start'  => '00:00:00',
                        'end'    => '23:59:59',
                    ),
                    'wednesday' => array(
                        'status' => 'yes',
                        'start'  => '00:00:00',
                        'end'    => '23:59:59',
                    ),
                    'thursday'  => array(
                        'status' => 'yes',
                        'start'  => '00:00:00',
                        'end'    => '23:59:59',
                    ),
                    'friday'    => array(
                        'status' => 'yes',
                        'start'  => '00:00:00',
                        'end'    => '23:59:59',
                    ),
                    'saturday'  => array(
                        'status' => 'yes',
                        'start'  => '00:00:00',
                        'end'    => '23:59:59',
                    ),
                    'sunday'    => array(
                        'status' => 'yes',
                        'start'  => '00:00:00',
                        'end'    => '23:59:59',
                    ),
                ),
                'custom_css'           => '',
            ),
            'tochatbe_gdpr_settings'       => array(
                'status'       => 'no',
                'message'      => 'I agree with {policy_page}',
                'privacy_page' => get_option( 'page_on_front' ),
            ),
            'tochatbe_type_and_chat_settings' => array(
                'type_and_chat'             => 'no',
                'type_and_chat_number'      => '9876543210',
                'type_and_chat_placeholder' => 'Write your message',
            ),
            'tochatbe_just_whatsapp_icon_settings' => array(
                'status'           => 'no',
                'number'           => '911234567890',
                'icon_link'        => '',
            ),
            'tochatbe_google_analytics_settings' => array(
                'status'   => 'no',
                'category' => 'Button Clicked',
                'action'   => 'TOCHAT.BE',
                'label'    => 'Support',
            ),
            'tochatbe_facebook_analytics_settings' => array(
                'status'   => 'no',
                'name'     => 'Chat started',
                'label'    => 'Support',
            ),
            'tochatbe_woo_order_button_settings' => array(
                'status'                       => 'no',
                'pre_message_processing_order' => 'Thank you for your order. We are working on it. The estimated time of arrival will be in 48 / 72 hours. Thank you.',
                'pre_message_canceled_order'   => 'Hi, I saw your order was canceled. Can we do anything to help you? I am John Doe by the way. Just reply here and keep whatsapping.',
                'pre_message_completed_order'  => 'Hi, thank you for your order. Please let us know if everything went well. My name is John Doe By the way!',
            ),
        );
    }

    public static function create_database() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        
        $table = $wpdb->prefix . 'tochatbe_log';

        if ( $wpdb->get_var( "SHOW TABLES LIKE '$table'" ) != $table ) {

            $table_sql = "CREATE TABLE $table (
                id BIGINT NOT NULL AUTO_INCREMENT ,
                ip VARCHAR(64) NOT NULL ,
                message VARCHAR(1024) NOT NULL ,
                contacted_to VARCHAR(32) NOT NULL ,
                referral VARCHAR(2048) NOT NULL ,
                device_type VARCHAR(64) NOT NULL ,
                timestamp VARCHAR(64) NOT NULL ,
                PRIMARY KEY (id)
            ) $charset_collate";

            require_once ABSPATH . 'wp-admin/includes/upgrade.php';

            dbDelta( $table_sql );
        }

        if ( $wpdb->get_var( "SHOW COLUMNS FROM $table LIKE 'unix_timestamp'" ) != 'unix_timestamp' ) {
            $wpdb->query( "ALTER TABLE $table ADD unix_timestamp VARCHAR(16) NOT NULL AFTER timestamp" );
        }

        if ( $wpdb->get_var( "SHOW COLUMNS FROM $table LIKE 'user'" ) != 'user' ) {
            $wpdb->query( "ALTER TABLE $table ADD user VARCHAR(64) NOT NULL AFTER contacted_to" );
            $wpdb->query( 
                $wpdb->prepare( 
                    "UPDATE $table
                     SET user = %s",
                     'Guest'
                )
            );
        }
    }

    public static function parse_args_r( &$args, $defaults ) {
        $a      = (array) $args;
        $b      = (array) $defaults;
        $result = $b;
        foreach ( $a as $k => &$v ) {
            if ( is_array( $v ) && isset( $result[$k] ) ) {
                $result[$k] = self::parse_args_r( $v, $result[$k] );
            } else {
                $result[$k] = $v;
            }
        }
        return $result;
    }
}