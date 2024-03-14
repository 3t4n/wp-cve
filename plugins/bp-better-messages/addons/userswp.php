<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages_UsersWP' ) ){

    class Better_Messages_UsersWP
    {

        public static function instance()
        {

            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_UsersWP();
            }

            return $instance;
        }

        public function __construct()
        {
            add_filter('uwp_get_profile_tabs', array( $this, 'profile_tab' ), 10, 1 );
            add_action('uwp_user_actions',     array( $this, 'profile_pm_button' ), 10, 1 );

            if( Better_Messages()->settings['chatPage'] === '0' ) {
                add_filter('bp_better_messages_page', array($this, 'message_page_url'), 10, 2);
            }

            add_action('better_messages_location_none', array( $this, 'bm_location_label' ), 10, 1 );

            add_filter('better_messages_rest_user_item', array( $this, 'custom_user_meta' ), 20, 3 );

            add_action('admin_init', array( $this, 'admin_init' ) );

            add_action('wp_footer', array( $this, 'lets_integrate_with_js') );
        }

        public function lets_integrate_with_js(){
            if( ! is_user_logged_in() ) return;

            if( ! is_uwp_current_user_profile_page()  ) return;
            ?>
            <script type="text/javascript">
                var profileTab = document.querySelector('#uwp-profile-bm-messages a');
                if( profileTab ){
                    profileTab.innerHTML += ' <span class="bp-better-messages-unread bpbmuc bpbmuc-hide-when-null" data-count="0">0</span>'
                }
            </script>
            <?php
        }

        public function admin_init(){
            remove_action( 'admin_notices', array( Better_Messages()->hooks, 'admin_notice') );
        }

        public function bm_location_label(){
            return _x('Show in UsersWP profile', 'UsersWP Integration', 'bp-better-messages');
        }

        public function message_page_url( $url, $user_id ){
            return uwp_build_profile_tab_url( $user_id, 'bm-messages' );
        }

        function custom_user_meta( $item, $user_id, $include_personal ){
            $user = get_userdata( $user_id );
            if( ! $user ) return $item;

            $item['url'] = uwp_build_profile_tab_url( $user_id );

            return $item;
        }

        public function profile_pm_button( $user ){
            if( ! is_user_logged_in() ){
                return false;
            }

            if( (int) $user->ID === (int) Better_Messages()->functions->get_current_user_id() ){
                return false;
            }

            $link = Better_Messages()->functions->create_conversation_link( $user->ID );

            echo '<a class="bm-userswp-pm-link" href="' . $link . '">' . _x('Private Message', 'UsersWP Integration', 'bp-better-messages') . '</a>';
        }


        public function profile_tab( $tabs ){
            if( ! is_user_logged_in() ) return $tabs;
            if( ! is_uwp_current_user_profile_page() ) return $tabs;

            $tabs[] = [
                'form_type' => 'profile-tabs',
                'sort_order' => 4,
                'tab_icon' => 'fas fa-envelope',
                'tab_layout' => 'profile',
                'tab_type' => 'standard',
                'tab_level' => 0,
                'tab_parent' => 0,
                'tab_privary' => 2,
                'tab_name' => _x('Messages', 'UsersWP Integration', 'bp-better-messages'),
                'tab_key' => 'bm-messages',
                'tab_content_rendered' => Better_Messages()->functions->get_page()
            ];

            return $tabs;
        }
    }
}

