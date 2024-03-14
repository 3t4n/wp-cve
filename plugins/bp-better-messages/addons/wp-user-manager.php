<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages_WP_User_Manager' ) ){

    class Better_Messages_WP_User_Manager
    {

        public static function instance()
        {

            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_WP_User_Manager();
            }

            return $instance;
        }

        public function __construct()
        {
            add_filter( 'wpum_get_registered_profile_tabs', array( $this, 'profile_tab'), 10, 1 );
            add_action( 'better_messages_location_none', array( $this, 'bm_location_label' ), 10, 1 );

            if( Better_Messages()->settings['chatPage'] === '0' ) {
                add_filter('bp_better_messages_page', array($this, 'message_page_url'), 10, 2);
            }

            add_filter( 'wpum_profile_page_content_messages', array( $this, 'profile_tab_content' ), 1, 2 );
            add_action( 'admin_init', array( $this, 'admin_init' ) );

            add_action( 'wpum_after_profile', array( $this, 'lets_integrate_with_js' ) );


            add_filter('better_messages_rest_user_item', array( $this, 'custom_user_meta' ), 20, 3 );
            add_filter( 'bp_core_get_userlink', array( $this, 'member_link' ), 10, 2 );

        }

        public function member_link($link, $user_id){
            $user = get_userdata( $user_id );
            if( ! $user ) return $link;
            return wpum_get_profile_url( $user );
        }

        function custom_user_meta( $item, $user_id, $include_personal ){
            $user = get_userdata( $user_id );
            if( ! $user ) return $item;

            // Set custom profile URL (if set this to false the user links becomes unclickable)
            $item['url'] = wpum_get_profile_url( $user );

            return $item;
        }


        public function message_page_url( $url, $user_id ){
            return wpum_get_profile_tab_url( get_userdata($user_id), 'messages' ) ;
        }

        public function lets_integrate_with_js(){
            if( ! is_user_logged_in() ) return;

            $displayed_user_id = (int) wpum_get_queried_user_id() ;

            if( $displayed_user_id !== (int) Better_Messages()->functions->get_current_user_id() ) {
                $link = Better_Messages()->functions->create_conversation_link( $displayed_user_id );
                ?>
                <script type="text/javascript">
                    var header = document.getElementById('header-name-container');
                    if( header ){
                        var link = document.createElement('a');
                        link.id = 'wpum-profile-bm-pm-link';
                        link.href = "<?php echo $link; ?>";
                        link.innerText = '<?php _ex('Private Message', 'WP User Manager Integration', 'bp-better-messages'); ?>';
                        header.prepend(link);
                    }
                </script>
                <?php
            } else { ?>
                <script type="text/javascript">
                    var profileTab = document.querySelector('#wpum-profile #profile-navigation .tab-messages');
                    if( profileTab ){
                    profileTab.innerHTML += ' <span class="bp-better-messages-unread bpbmuc bpbmuc-hide-when-null" data-count="0">0</span>'
                    }
                </script>
            <?php }
        }

        public function profile_tab_content( $data, $active_tab ){
            if( (int) $data->user->ID === (int) Better_Messages()->functions->get_current_user_id() ) {
                echo Better_Messages()->functions->get_page();
            }
        }

        public function profile_tab( $tabs ){
            $slug = 'messages';

            if( wpum_is_own_profile() ) {
                $tabs[$slug] = array(
                    'name' => esc_html_x('Messages', 'WP User Manager Integration', 'bp-better-messages'),
                    'priority' => 3,
                );
            }

            return $tabs;
        }

        public function bm_location_label(){
            return _x('Show in WP User Manager profile', 'WP User Manager Integration', 'bp-better-messages');
        }

        public function admin_init(){
            remove_action( 'admin_notices', array( Better_Messages()->hooks, 'admin_notice') );
        }
    }
}

