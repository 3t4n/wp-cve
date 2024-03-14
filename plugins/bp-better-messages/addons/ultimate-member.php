<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages_Ultimate_Member' ) ){

    class Better_Messages_Ultimate_Member
    {

        public static function instance()
        {

            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_Ultimate_Member();
            }

            return $instance;
        }

        public function __construct(){
            add_filter( 'um_user_profile_tabs', array( $this, 'um_add_profile_tab' ), 200 );

            add_action( 'um_profile_content_messages_default', array( $this, 'um_content_messages' ), 1 );

            if( Better_Messages()->settings['chatPage'] === '0' ) {
                add_filter('bp_better_messages_page', array($this, 'um_message_page_url'), 10, 2);
            }

            if( Better_Messages()->settings['umProfilePMButton'] === '1' ) {
                add_action('um_profile_navbar', array($this, 'um_profile_message_button'), 5);
            }

            if( Better_Messages()->settings['UMuserListButton'] == '1' ) {
                add_action('um_members_just_after_name_tmpl', array($this, 'um_pm_link'), 10);
                add_action('um_members_list_just_after_actions_tmpl', array( $this, 'um_pm_link' ), 10);
            }

            if(  class_exists('UM_Friends_API') ) {
                add_filter( 'better_messages_friends_active', array($this, 'enabled') );
                add_filter( 'better_messages_get_friends', array($this, 'get_friends'), 10, 2 );
                add_filter( 'better_messages_is_friends', array( $this, 'is_friends' ), 10, 3 );
                if (Better_Messages()->settings['umOnlyFriendsMode'] === '1') {
                    add_filter('better_messages_can_send_message', array($this, 'disable_non_friends_reply'), 10, 3);
                    add_action('better_messages_before_new_thread', array($this, 'disable_start_thread_for_non_friends'), 10, 2);
                    add_filter( 'better_messages_only_friends_mode', array($this, 'enabled') );
                }

                add_filter('better_messages_search_friends', array( $this, 'search_friends'), 10, 3 );
            }

            if( class_exists('UM_Followers_API') ){
                add_filter( 'better_messages_followers_active', array($this, 'enabled') );
                add_filter( 'better_messages_is_followers', array($this, 'is_followers' ), 10, 3);

                if( Better_Messages()->settings['umOnlyFollowersMode'] === '1' ) {
                    add_filter( 'better_messages_only_followers_mode', array($this, 'enabled') );
                    add_filter( 'better_messages_can_send_message',  array($this, 'disable_non_followers_reply'), 10, 3);
                    add_action( 'better_messages_before_new_thread', array($this, 'disable_start_thread_for_non_followers'), 15, 2);
                }
            }

            add_action( 'wp_head', array( $this, 'um_counter_in_profile' ) );

            if( class_exists('UM_Groups') ){
                require_once Better_Messages()->path . 'addons/ultimate-member-groups.php';
                Better_Messages_Ultimate_Member_Groups::instance();
            }

            add_filter( 'bp_better_messages_script_variable', array( $this, 'script_variables' ) );
            add_action( 'um_theme_header_profile_before', array( $this, 'um_theme_header' ), 10 );

            add_action('better_messages_rest_user_item', array( $this, 'um_user_info' ), 20, 3 );
        }

        public function um_user_info( $item, $user_id, $include_personal ){
            if( function_exists('um_user_profile_url') ) {
                $item['url'] = um_user_profile_url($user_id);
            }

            if( function_exists('um_get_user_avatar_url') ) {
                $item['avatar'] = um_get_user_avatar_url($user_id);
            }

            return $item;
        }

        public function um_theme_header(){
            if( ! is_user_logged_in() ) return false;
            ?>

            <div id="bm-um-header" class="header-messenger-box">
                <div class="dropdown msg-drop">

                    <i class="um-msg-tik-ico far fa-envelope dropdown-togglu" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>

                    <span class="um-message-live-count" style="display:none">0</span>
                    <ul class="dropdown-menu msg-drop-menu" aria-labelledby="dropdownMenuButton">
                        <div class="bp-messages-wrap bm-threads-list" style="height:400px"></div>
                    </ul>
                </div>
            </div>
            <script type="text/javascript">
                jQuery(document).trigger("bp-better-messages-init-scrollers");
                jQuery(document).on('bp-better-messages-update-unread', function( event ) {
                    var unread = parseInt(event.detail.unread);
                    var private_messages = jQuery('#bm-um-header .um-message-live-count');

                    if( private_messages.length > 0 ){
                        if( unread > 0 ){
                            private_messages.text(unread);
                            private_messages.show();
                        } else {
                            private_messages.text(0).hide();
                        }
                    }
                });
            </script>
            <style type="text/css">
                .um-msg-tik-ico:hover {
                    color: #bbbbbb;
                }.um-msg-tik-ico {
                     color: #cccccc;
                 }</style>
            <?php
        }

        public function search_friends( $result, $search, $user_id  ){
            $friends = UM()->Friends_API()->api()->friends( $user_id );

            if( !! $friends && count( $friends ) > 0 ) {
                $friend_ids = [];
                foreach($friends as $index => $_users) {
                    if ($user_id === (int)$_users['user_id1']) {
                        $friend_id = (int)$_users['user_id2'];
                    } else {
                        $friend_id = (int)$_users['user_id1'];
                    }

                    $friend_ids[] = $friend_id;
                }

                global $wpdb;

                $sql = $wpdb->prepare("
                SELECT ID FROM `{$wpdb->users}`
                WHERE ( `user_nicename` LIKE %s OR `display_name` LIKE %s )
                AND `ID` IN (" . implode(',', array_map( 'intval', $friend_ids ) ) . ")
                LIMIT 0, 10
                ", '%' . $search . '%', '%' . $search . '%', $user_id);

                $matched_friends = $wpdb->get_col($sql);

                if( count( $matched_friends ) > 0 ){
                    foreach( $matched_friends as $friend ){
                        $result[] = intval( $friend );
                    }
                }
            }

            return $result;
        }


        public function is_friends( $bool, $user_id_1, $user_id_2 ){
            return UM()->Friends_API()->api()->is_friend( $user_id_1, $user_id_2 );
        }

        public function get_friends( $friends, $user_id ){
            $user_id = (int) Better_Messages()->functions->get_current_user_id();
            $friends = UM()->Friends_API()->api()->friends( $user_id );

            $users = [];

            if( !! $friends && count( $friends ) > 0 ) {
                foreach($friends as $index => $_users){
                    if( $user_id === (int) $_users['user_id1'] ) {
                        $friend_id = (int) $_users['user_id2'];
                    } else {
                        $friend_id = (int) $_users['user_id1'];
                    }

                    $user = get_userdata($friend_id);
                    if( ! $user ) continue;

                    $users[] = Better_Messages()->functions->rest_user_item( $user->ID );
                }
            }

            return $users;
        }

        public function enabled(){
            return true;
        }

        public function script_variables( $script_variables ){
            if( Better_Messages()->settings['UMminiGroupsEnable'] === '1' ) {
                $script_variables['miniGroups'] = '1';
            }
            if( Better_Messages()->settings['UMcombinedGroupsEnable'] === '1' ) {
                $script_variables['combinedGroups'] = '1';
            }
            if( Better_Messages()->settings['UMmobileGroupsEnable'] === '1' ) {
                $script_variables['mobileGroups'] = '1';
            }

            if( Better_Messages()->settings['UMminiFriendsEnable'] === '1' ) {
                $script_variables['miniFriends'] = '1';
            }
            if( Better_Messages()->settings['UMcombinedFriendsEnable'] === '1' ) {
                $script_variables['combinedFriends'] = '1';
            }
            if( Better_Messages()->settings['UMmobileFriendsEnable'] === '1' ) {
                $script_variables['mobileFriends'] = '1';
            }

            return $script_variables;
        }

        public function um_counter_in_profile(){
            if( ! is_user_logged_in() ) return false;
            ob_start();

            //<span class="um-tab-notifier">1</span>
            ?>
            <script type="text/javascript">
                jQuery(document).on('bp-better-messages-update-unread', function( event ) {
                    var unread = parseInt(event.detail.unread);
                    var private_messages = jQuery('.um-profile-nav-item.um-profile-nav-messages > a');

                    private_messages.each(function(){
                        var tab = jQuery(this);

                        if( unread > 0 ){
                            var count = tab.find('span.um-tab-notifier');

                            if( count.length === 0 ){
                                tab.append('<span class="um-tab-notifier">' + unread + '</span>');
                            } else {
                                count.text(unread);
                            }
                        } else {
                            tab.find('span.um-tab-notifier').remove();
                        }
                    });
                });
            </script>
            <?php
            $script = ob_get_clean();

            echo Better_Messages()->functions->minify_js( $script );
        }




        public function is_followers($bool, $user_id_1, $user_id_2){
            $followed = UM()->Followers_API()->api()->followed( $user_id_1, $user_id_2 );

            if( ! $followed ) {
                $followed = UM()->Followers_API()->api()->followed( $user_id_1, $user_id_2 );
            }

            return $followed;
        }

        public function disable_start_thread_for_non_followers(&$args, &$errors){
            if( ! class_exists('UM_Followers_API') ) {
                return null;
            }

            if( current_user_can('manage_options' ) ) {
                return null;
            }

            if( count( $errors ) > 0 ) {
                return null;
            }

            $recipients = $args['recipients'];

            if( ! is_array( $recipients ) ) $recipients = [ $recipients ];

            $notFollowed = array();

            foreach($recipients as $recipient){
                $user = get_userdata($recipient);

                $allowed = Better_Messages()->functions->is_followers( Better_Messages()->functions->get_current_user_id(), $user->ID );

                if( ! $allowed ) {
                    $notFollowed[] = Better_Messages()->functions->get_name($user->ID);
                }
            }

            if(count($notFollowed) > 0){
                $message = sprintf(_x('%s need to be followed to start new conversation', 'Ultimate member - follower restriction', 'bp-better-messages'), implode(', ', $notFollowed));
                $errors[] = $message;
            }

        }

        public function disable_non_followers_reply( $allowed, $user_id, $thread_id ){
            if( ! class_exists('UM_Followers_API') ) {
                return $allowed;
            }

            $participants = Better_Messages()->functions->get_participants($thread_id);
            if( count($participants['recipients']) !== 1) return $allowed;

            reset($participants['recipients']);

            $user_id_2 = key($participants['recipients']);
            /**
             * Allow users reply to admins even if not friends
             */
            if( current_user_can('manage_options') || user_can( $user_id_2, 'manage_options' ) ) {
                return $allowed;
            }

            $allowed = Better_Messages()->functions->is_followers( $user_id, $user_id_2 );

            if( ! $allowed ){
                global $bp_better_messages_restrict_send_message;
                $bp_better_messages_restrict_send_message['follow_needed'] = _x('You must follower this user to send messages', 'Ultimate member - follower restriction', 'bp-better-messages');
            }

            return $allowed;
        }

        public function disable_start_thread_for_non_friends(&$args, &$errors){
            if( ! class_exists('UM_Friends_API') ) {
                return null;
            }

            if( current_user_can('manage_options' ) ) {
                return null;
            }

            $recipients = $args['recipients'];

            if( ! is_array( $recipients ) ) $recipients = [ $recipients ];

            $notFriends = array();

            foreach($recipients as $recipient){
                $user = get_userdata($recipient);

                if( ! UM()->Friends_API()->api()->is_friend( Better_Messages()->functions->get_current_user_id(), $user->ID ) ) {
                    $notFriends[] = Better_Messages()->functions->get_name($user->ID);
                }
            }

            if(count($notFriends) > 0){
                $message = sprintf(__('%s not on your friends list', 'bp-better-messages'), implode(', ', $notFriends));
                $errors[] = $message;
            }

        }

        public function disable_non_friends_reply( $allowed, $user_id, $thread_id ){
            if( ! class_exists('UM_Friends_API') ) {
                return $allowed;
            }

            $participants = Better_Messages()->functions->get_participants($thread_id);
            if( count($participants['recipients']) !== 1) return $allowed;
            reset($participants['recipients']);

            $friend_id = key($participants['recipients']);
            /**
             * Allow users reply to admins even if not friends
             */
            if( current_user_can('manage_options') || user_can( $friend_id, 'manage_options' ) ) {
                return $allowed;
            }

            $allowed = UM()->Friends_API()->api()->is_friend( $user_id, $friend_id );

            if( ! $allowed ){
                global $bp_better_messages_restrict_send_message;
                $bp_better_messages_restrict_send_message['friendship_needed'] = __('You must become friends to send messages', 'bp-better-messages');
            }

            return $allowed;
        }

        public function um_message_page_url( $url, $user_id ){
            $um_profile_url = um_user_profile_url( $user_id );
            return add_query_arg( ['profiletab' => 'messages'], $um_profile_url );
        }

        public function um_add_profile_tab( $tabs ) {
            $user_id  = (int) um_profile_id();
            $can_view = is_user_logged_in() && Better_Messages()->functions->get_current_user_id() === $user_id;

            if( $can_view ) {
                $tabs['messages'] = array(
                    'name' => __('Messages', 'bp-better-messages'),
                    'icon' => 'um-faicon-envelope-o',
                    'default_privacy' => 3,
                );
            }

            return $tabs;
        }

        public function um_content_messages( $args ) {
            echo Better_Messages()->functions->get_page( true );
        }


        public function um_pm_link( $args ){
            if ( ! is_user_logged_in() ) return;

            $base_url = Better_Messages()->functions->get_link(Better_Messages()->functions->get_current_user_id());

            $args = [
                'to' => '{{{user.id}}}'
            ];

            if( Better_Messages()->settings['fastStart'] == '1'){
                $args['bm-fast-start'] = '1';
            }

            if( isset( $args['bm-fast-start'] ) ){
                $url = add_query_arg( $args, $base_url );
            } else {
                $url = Better_Messages()->functions->add_hash_arg('new-conversation', $args, $base_url);
            }

            $class      = 'um-members-bpbm-btn';
            $link_class = 'um-button um-alt';
            $link_attr  = '';

            if( Better_Messages()->settings['umForceMiniChat'] === '1' ){
                $link_class .= ' bpbm-pm-button open-mini-chat ';
                $link_attr .= ' data-user-id="{{{user.id}}}"';
            }

            if( doing_action('um_members_list_just_after_actions_tmpl') ){
                $class .= ' um-members-list-footer-button-wrapper bpbm-pm-button';
            }

            echo '<div class="' . $class . '">';
            echo '<a href="' . $url . '" class="' . $link_class . '" target="_self" ' . $link_attr . '><span class="bm-button-text">' . __('Private Message', 'bp-better-messages') . '</span></a>';
            echo '</div>';
        }

        public function um_profile_message_button( $args ){
            if( ! function_exists('um_profile_id') ) return false;
            $user_id = um_profile_id();

            if ( is_user_logged_in() ) {
                if ( Better_Messages()->functions->get_current_user_id() == $user_id ) {
                    return;
                }
            }
            ?>
            <div class="um-messaging-btn">
                <?php
                if( Better_Messages()->settings['umForceMiniChat'] === '1' ){
                    echo do_shortcode( '[better_messages_mini_chat_button text="' . __('Private Message', 'bp-better-messages') . '" target="_self" fast_start="1" user_id="' . $user_id . '"]' );
                } else {
                    echo do_shortcode( '[better_messages_pm_button text="' . __('Private Message', 'bp-better-messages') . '" target="_self" fast_start="1" user_id="' . $user_id . '"]' );
                } ?>
            </div>
            <?php
        }

    }
}
