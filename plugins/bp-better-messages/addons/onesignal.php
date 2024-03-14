<?php

defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages_OneSignal' ) ) {

    class Better_Messages_OneSignal
    {

        public static function instance()
        {

            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_OneSignal();
            }

            return $instance;
        }

        public function __construct(){
            add_action( 'better_messages_send_pushs', array( $this, 'send_pushs' ), 10, 2 );
            add_filter( 'better_messages_3rd_party_push_active', '__return_true' );
            add_filter( 'better_messages_push_active', '__return_false' );
            add_filter( 'better_messages_push_message_in_settings', array( $this, 'push_message_in_settings' ) );
        }

        public function send_pushs( array $user_ids, array $notification ){
            if( ! class_exists('OneSignal_Admin') || ! class_exists('OneSignal') ) return;
            $onesignal_post_url = 'https://onesignal.com/api/v1/notifications';

            $onesignal_wp_settings = OneSignal::get_onesignal_settings();

            if( $onesignal_wp_settings['app_id'] === '' || $onesignal_wp_settings['app_rest_api_key'] === '' ) return;

            $onesignal_auth_key = $onesignal_wp_settings['app_rest_api_key'];

            $image = $notification['icon'];

            $fields = array(
                'include_external_user_ids' => array_map('strval', $user_ids),
                'app_id' => $onesignal_wp_settings['app_id'],
                'chrome_web_icon' => $image,
                'chrome_web_badge' => $image,
                'firefox_icon' => $image,
                'headings' => [ 'en' => stripslashes_deep(wp_specialchars_decode($notification['title'])) ],
                //'isAnyWeb' => true,
                'url' => $notification['data']['url'],
                'contents' => [ 'en' => stripslashes_deep(wp_specialchars_decode($notification['body'])) ],
            );

            $request = array(
                'headers' => array(
                    'content-type' => 'application/json;charset=utf-8',
                    'Authorization' => 'Basic '.$onesignal_auth_key,
                ),
                'body' => wp_json_encode($fields),
                'timeout' => 3,
            );

            OneSignal_Admin::exec_post_request($onesignal_post_url, $request, 1);
        }

        public function push_message_in_settings( $message ){
            $message = '<p style="color: #0c5460;background-color: #d1ecf1;border: 1px solid #d1ecf1;padding: 15px;line-height: 24px;max-width: 550px;">';
            $message .= sprintf(_x('The OneSignal WordPress plugin integration is active and will be used, this option do not need to be enabled. Please follow <a href="%s" target="_blank">this guide</a> to configure integration', 'Settings page', 'bp-better-messages'), 'https://www.better-messages.com/docs/integrations/onesignal/');
            $message .= '</p>';
    
            return $message;
        }

    }
}
