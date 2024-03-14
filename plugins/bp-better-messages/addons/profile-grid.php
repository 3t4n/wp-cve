<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages_Profile_Grid' ) ){

    class Better_Messages_Profile_Grid
    {

        public static function instance()
        {

            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_Profile_Grid();
            }

            return $instance;
        }

        public function __construct()
        {
            add_filter('pm_profile_tabs', array( $this, 'profile_tab') );

            add_action('profile_magic_profile_tab_link', array( $this, 'profile_tab_link' ), 10, 5 );
            add_action('profile_magic_profile_tab_content', array( $this, 'profile_tab_content'), 10, 2 );

            add_action('better_messages_location_none', array( $this, 'bm_location_label' ), 10, 1 );
            add_filter( 'bp_core_get_userlink', array( $this, 'member_link' ), 10, 2 );

            if( Better_Messages()->settings['chatPage'] === '0' ) {
                add_filter('bp_better_messages_page', array($this, 'message_page_url'), 10, 2);
            }

            add_filter('better_messages_rest_user_item', array( $this, 'custom_user_meta' ), 20, 3 );

            add_action('admin_init', array( $this, 'admin_init' ) );
        }

        public function get_user_profile_url( $user_id ){
            $pmrequests = new PM_request;
            $url = $pmrequests->pm_get_user_profile_url( $user_id );
            return $url;
        }

        public function member_link($link, $user_id){
            return $this->get_user_profile_url( $user_id );
        }


        function custom_user_meta( $item, $user_id, $include_personal ){
            $user = get_userdata( $user_id );
            if( ! $user ) return $item;

            $pmrequests = new PM_request;

            // Set custom profile URL (if set this to false the user links becomes unclickable)
            $item['url'] = $this->get_user_profile_url( $user_id );

            $avatarid = $pmrequests->profile_magic_get_user_field_value( $user_id, 'pm_user_avatar' );

            if ( isset( $avatarid ) && $avatarid != '' ) {
                $pm_avatar = wp_get_attachment_image_src( $avatarid);
                if ( ! empty( $pm_avatar ) ) {
                    $item['avatar'] = $pm_avatar[0];
                }
            }
            // Set custom name
            //$item['name'] = 'Custom name';

            return $item;
        }

        public function admin_init(){
            remove_action( 'admin_notices', array( Better_Messages()->hooks, 'admin_notice') );
        }

        public function message_page_url( $url, $user_id ){
            $pmrequests = new PM_request;
            return $pmrequests->pm_get_user_profile_url( $user_id );
        }

        public function bm_location_label(){
            return _x('Show in ProfileGrid profile', 'ProfileGrid Integration', 'bp-better-messages');
        }

        public function profile_tab_content( $uid, $primary_gid ){
            if(  (int) $uid === Better_Messages()->functions->get_current_user_id() ) {
                echo '<div id="bm-pg-messages" class="pm-dbfl pg-profile-tab-content bm-pg-messages-tab" style="display: none">';
                echo Better_Messages()->functions->get_page();
                ?>
                <script type="text/javascript">
                    var button = document.getElementById('bm-pg-messages-link');

                    button.addEventListener('click', function(){
                        let clickToOpen = document.querySelector('#bm-pg-messages .bp-messages-mobile-tap')
                        if( clickToOpen ) clickToOpen.click()
                    });

                    document.addEventListener('better-messages-autoscroll', function(){
                        var button = document.getElementById('bm-pg-messages-link')
                        if( button ) button.click();
                    })
                </script>
                <?php
                echo '</div>';
            }
        }

        public function profile_tab_link( $id, $tab, $uid, $gid, $primary_gid ){
            if(  $id === 'bm-messages' && (int) $uid === Better_Messages()->functions->get_current_user_id()  ){
                echo '<li class="pm-profile-tab pm-pad10 bm-message-tab"><a class="pm-dbfl" id="bm-pg-messages-link" href="#bm-pg-messages">'
                    . _x('Messages', 'ProfileGrid Integration', 'bp-better-messages') .
                    " " . do_shortcode('[better_messages_unread_counter hide_when_no_messages="1" preserve_space="0"]') .
                    '</a><div class="pm-border-slide"></div></li>';
            } else {
                echo '<div class="pm-difr pm-pad20">';
                $url = Better_Messages()->functions->private_message_link( $uid );
                echo '<a id="message_user" href="' . $url . '">' . _x('Message', 'ProfileGrid Integration', 'bp-better-messages') . '</a>';
                echo '</div>';
            }
            return $id;
        }

        public function profile_tab( $tabs ){
            $item = [
                'id'     => 'bm-messages',
                'title'  => _x('Messages', 'ProfileGrid Integration', 'bp-better-messages'),
                'status' => '1',
                'class'  => 'bm-message-tab'
            ];

            if( isset( $tabs['pg-groups'] ) ) {
                $tabs = Better_Messages()->functions->array_insert_after('pg-groups', $tabs, 'bm-messages', $item);
            } else {
                $tabs['bm-messages'] = $item;
            }

            return $tabs;
        }
    }
}

