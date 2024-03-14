<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages_wpForo' ) ){

    class Better_Messages_wpForo
    {

        public static function instance()
        {

            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_wpForo();
            }

            return $instance;
        }

        public function __construct()
        {
            add_action('wpforo_profile_head_right', array( $this, 'profile_pm_button' ), 9);

            add_filter('better_messages_rest_user_item', array( $this, 'user_meta' ), 20, 3 );

            /*
            if( Better_Messages()->settings['chatPage'] === '0' ) {
                add_filter('wpforo_init_member_templates', array( $this, 'profile_tab'), 10, 1 );
                add_action( 'admin_init', array( $this, 'admin_init' ) );
                add_filter('bp_better_messages_page', array($this, 'message_page_url'), 10, 2);
            }*/

        }


        public function message_page_url( $url, $user_id ){

            return '';
        }

        public function admin_init(){
            remove_action( 'admin_notices', array( Better_Messages()->hooks, 'admin_notice') );
        }

        public function profile_tab( $templates ){

            $templates['messages'] = [
                'type' => 'callback',
                'key' => 'messages',
                'ico' => '<i class="fas fa-envelope"></i>',
                'title' =>  _x('Messages', 'wpForo Integration', 'bp-better-messages'),
                'is_default' => false,
                'status' => 1,
                'can' => 1,
                'callback_for_can'   => function(){
                    $user = wpforo_get_current_object_user();

                    if( ! $user ) return true;

                    if( (int) $user['userid'] === Better_Messages()->functions->get_current_user_id() ){
                        return true;
                    }

                    return false;
                },
                'callback_for_page' => function(){
                    echo Better_Messages()->functions->get_page();
                }
            ];

            return $templates;
        }

        public function user_meta( $item, $user_id, $include_personal ){
            $user = WPF()->member->get_member( $user_id );
            // Set custom profile URL (if set this to false the user links becomes unclickable)

            if( $user ) {
                $item['url'] = $user['profile_url'];

                if ( ! empty($user['avatar']) ) {
                    $item['avatar'] = $user['avatar'];
                }
            }
            // Set custom name
            #$item['name'] = 'Custom name';

            return $item;
        }

        public function profile_pm_button( $user ){
            if( ! is_user_logged_in() ) return;

            $displayed_user_id = (int) $user['userid'];
            $current_user_id   = (int) Better_Messages()->functions->get_current_user_id();

            if( $displayed_user_id === $current_user_id ) return;

            $link = Better_Messages()->functions->create_conversation_link( $displayed_user_id );
            echo '<a href="' . $link . '" class="bpbm-pm-button bm-wpforo-btn">' . _x('Private Message', 'wpForo Integration', 'bp-better-messages') . '</a>';
        }
    }
}

