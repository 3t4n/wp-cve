<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages_User_Config' ) ):

    class Better_Messages_User_Config
    {
        public static function instance()
        {
            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_User_Config();
            }

            return $instance;
        }

        public function __construct(){
            add_action( 'rest_api_init',  array( $this, 'rest_api_init' ) );
        }

        public function rest_api_init(){
            register_rest_route( 'better-messages/v1', '/userSettings', array(
                'methods' => 'GET',
                'callback' => array( $this, 'user_settings' ),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'is_user_authorized' )
            ) );

            register_rest_route( 'better-messages/v1', '/userSettings/save', array(
                'methods' => 'POST',
                'callback' => array( $this, 'user_settings_save' ),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'is_user_authorized' )
            ) );
        }

        public function user_settings( WP_REST_Request $request ){
            return $this->get_user_settings();
        }

        public function get_who_can_start_options(){
            $options = [
                'everyone' => _x('Everyone', 'User settings', 'bp-better-messages'),
            ];

            $friends_active   = Better_Messages()->functions->is_friends_active();
            $followers_active = Better_Messages()->functions->is_followers_active();

            $only_friends     = Better_Messages()->functions->is_only_friends_mode();
            $only_followers   = Better_Messages()->functions->is_only_followers_mode();

            if( $followers_active ) {
                if( $only_followers ){
                    $options = [];
                }

                $options['only_followers'] = _x('Allow Followers', 'User settings', 'bp-better-messages');
            }

            if( $friends_active && ! $only_followers ) {
                if ( $only_friends ) {
                    $options = [];
                }

                $options['only_friends'] = _x('Allow Friends', 'User settings', 'bp-better-messages');
            }

            $options['nobody'] = _x('Nobody', 'User settings', 'bp-better-messages');

            return $options;
        }

        public function get_who_can_start_value( $user_id ){
            $options = $this->get_who_can_start_options();

            $default = 'everyone';

            $friends_active   = Better_Messages()->functions->is_friends_active();
            $followers_active = Better_Messages()->functions->is_followers_active();

            /*if( $friends_active && $followers_active ){
                $default = 'only_friends,only_followers';
            } else if( $friends_active ) {
                $default = 'only_friends';
            } else if( $followers_active ) {
                $default = 'only_followers';
            }*/

            if( Better_Messages()->functions->is_only_friends_mode() ){
                $default = 'only_friends';
            } else if( Better_Messages()->functions->is_only_followers_mode()  ){
                $default = 'only_followers';
            }

            $current = Better_Messages()->functions->get_user_meta($user_id, 'bpbm_who_can_start_conversations', true);


            if( ! empty( $current ) ){
                $current = explode( ',', $current );
            } else {
                $current = explode(',', $default );
            }

            foreach( $current as $i => $item ){
                if( ! isset( $options[$item] ) ){
                    unset( $current[ $i ] );
                }
            }

            if( empty( $current ) ){
                $current = [ $default ];
            }

            return $current;
        }

        public function get_user_settings(){
            $user_id  = Better_Messages()->functions->get_current_user_id();

            $settings = [];

            if( Better_Messages()->settings['allowUsersRestictNewThreads'] === '1' ) {
                $options = $this->get_who_can_start_options();
                $current = $this->get_who_can_start_value( Better_Messages()->functions->get_current_user_id() );

                $setting_option = [];
                foreach( $options as $key => $value ){
                    $setting_option[] = [
                        'id' => $key,
                        'label' => $value,
                        'value' => $key,
                        'checked' => in_array($key, $current),
                        'desc' => ''
                    ];
                }

                $settings[] = [
                    'id' => 'who_can_start_conversations',
                    'title' => _x('Who can start private conversations with you?', 'User settings', 'bp-better-messages'),
                    'type' => 'checkboxes_radio',
                    'options' => $setting_option
                ];
            }

            $notifications_options = [];

            $notifications_interval = (int) Better_Messages()->settings['notificationsInterval'];
            if( $user_id > 0 && $notifications_interval > 0 ) {
                $notifications_options[] = [
                        'id'      => 'email_notifications',
                        'label'   => _x('Enable notifications via email', 'User settings', 'bp-better-messages'),
                        'value'   => 'yes',
                        'checked' => Better_Messages()->notifications->is_user_emails_enabled( $user_id ),
                        'desc'    => _x('When enabled, you will receive notifications about new messages via email when you are offline.', 'User settings', 'bp-better-messages')
                ];
            }

            if( $user_id > 0 ) {
                $notifications_options[] = [
                    'id' => 'sound_notifications',
                    'label' => _x('Disable new message sound notification', 'User settings', 'bp-better-messages'),
                    'value' => 'yes',
                    'checked' => (Better_Messages()->functions->get_user_meta($user_id, 'bpbm_disable_sound_notification', true) === 'yes'),
                    'desc' => _x('When enabled, you will not hear a sound when a new message is received.', 'User settings', 'bp-better-messages')
                ];
            }

            $notifications = false;

            if( count($notifications_options) > 0 ) {
                $notifications = [
                    'id' => 'notifications',
                    'title' => _x('Notifications', 'User settings', 'bp-better-messages'),
                    'type' => 'checkboxes',
                    'options' => $notifications_options
                ];
            }

            if( $user_id > 0 && $notifications && Better_Messages()->settings['enablePushNotifications'] === '1' ){
                $notifications['options'][] = [
                    'id'    => 'push_notifications',
                    'label' => _x('Browser push notifications', 'User settings', 'bp-better-messages'),
                    'desc'  => _x('When enabled, you will receive messages notifications even if browser is closed.', 'User settings', 'bp-better-messages')
                ];
            }

            if( is_array($notifications) ){
                $settings[] = $notifications;
            }

            return apply_filters('better_messages_user_config', $settings);
        }

        public function user_settings_save( WP_REST_Request $request ){
            $user_id = Better_Messages()->functions->get_current_user_id();

            $option  = sanitize_text_field( $request->get_param('option') );
            $value   = sanitize_text_field( $request->get_param('value') );

            $message = _x('Saved successfully', 'User settings', 'bp-better-messages');

            switch( $option ){
                case 'email_notifications':
                    $new_value = ( $value === 'false' ) ? 'no' : 'yes';
                    Better_Messages()->notifications->user_emails_enabled_update( $user_id, $new_value );
                    break;
                case 'who_can_start_conversations':
                    Better_Messages()->functions->update_user_meta( $user_id, 'bpbm_who_can_start_conversations', $value );
                    break;
                case 'sound_notifications':
                    $new_value = ( $value === 'false' ) ? 'no' : 'yes';
                    Better_Messages()->functions->update_user_meta( $user_id, 'bpbm_disable_sound_notification', $new_value );
                    break;
                case 'online_status':
                    Better_Messages()->functions->update_user_meta( $user_id, 'bpbm_online_status', $value );
                    Better_Messages()->users->update_last_changed( $user_id );
                    if( class_exists('Better_Messages_WebSocket') ){
                        $message = Better_Messages()->functions->rest_user_item( $user_id );
                    }
                    break;
            }

            return [
                'message' => $message
            ];
        }
    }

    function Better_Messages_User_Config(){
        return Better_Messages_User_Config::instance();
    }

endif;
