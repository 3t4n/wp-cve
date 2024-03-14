<?php

defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages_BuddyPress' ) ) {

    class Better_Messages_BuddyPress
    {

        public static function instance()
        {

            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_BuddyPress();
            }

            return $instance;
        }

        public function __construct()
        {
            if( function_exists('friends_check_friendship') ) {
                add_filter( 'better_messages_friends_active', array($this, 'enabled') );
                add_filter( 'better_messages_get_friends', array($this, 'get_friends'), 10, 2 );

                if( Better_Messages()->settings['friendsMode'] === '1' ){
                    add_filter( 'better_messages_only_friends_mode', array($this, 'enabled') );
                }
            }

            if( bm_bp_is_active( 'groups' ) && Better_Messages()->settings['enableGroups'] === '1' ){
                add_filter( 'better_messages_groups_active', array($this, 'enabled') );
                add_filter( 'better_messages_get_groups', array($this, 'get_groups'), 10, 2 );
            }

            if( class_exists('BP_Verified_Member') ) {
                add_filter('better_messages_is_verified', array( $this, 'bp_verified_member' ), 10, 2 );
            }

            add_filter('better_messages_search_friends', array( $this, 'search_friends'), 10, 3 );

            add_filter('bp_messages_single_new_message_string_notification', array( $this, 'making_notifications_work'), 10, 6 );

            if( Better_Messages()->settings['bpFallback'] === '1' ) {
                add_action('messages_message_before_save', array( $this, 'fallback_to_messages_new_message' ), 10, 1 );
            }

            if( function_exists('youzify_is_user_account_verified') ){
                add_filter( 'better_messages_is_verified', array( $this, 'youzify_verified_member' ), 10, 2 );
            }

            add_filter('bp_better_messages_display_name', array( $this, 'display_name_override' ), 10, 2 );


            add_action( 'template_redirect', array( $this, 'redirect_standard_component' ) );

            add_action( 'admin_bar_menu', array( $this, 'remove_standard_topbar' ), 999 );

            add_filter( 'bp_nouveau_get_members_buttons',   array( $this, 'pm_link_nouveau' ), 20, 3);

            add_filter( 'bp_get_send_private_message_link', array( $this, 'pm_link' ), 20, 1 );

            add_filter( 'yz_get_send_private_message_url',  array( $this, 'pm_link' ), 20, 1 );

            add_filter( 'bp_get_send_message_button_args',  array( $this, 'pm_link_args'), 20, 1 );

            add_filter( 'bp_nouveau_get_members_buttons',   array( $this, 'bp_nouveau_get_members_buttons'), 10, 3 );

            if( Better_Messages()->settings['userListButton'] == '1' ) {
                add_action('bp_directory_members_actions', array($this, 'pm_link_legacy'), 10);
            }

            add_filter( 'bp_get_message_thread_view_link',  array( $this, 'thread_link' ), 20, 2 );

            // I have 0 idea why this fixes messages link when using Youzer plugin
            add_action( 'bp_ready', array( $this, 'fix_youzer' ) );

            add_action( 'bp_screens', array( $this, 'fix_404' ) );

            add_action( 'bp_core_user_updated_last_activity', array( $this, 'override_last_activity_2' ), 10, 2 );

            /**
             * Youzify button
             */
            add_filter( 'youzify_get_send_message_button', array( $this, 'modify_youzify_button' ), 20 );
        }

        public function modify_youzify_button($args){
            $args['link_href'] = $this->pm_link();
            if( BP_Better_Messages()->settings['bpForceMiniChat'] === '1' && function_exists('bp_displayed_user_id') ) {
                $args['link_class'] .= ' bpbm-pm-button open-mini-chat bm-no-loader bm-no-style';

                $user_id = bp_displayed_user_id();
                if( ! $user_id ){
                    $user_id = preg_replace("/[^0-9]/", "", $args['id'] );
                }
                $args['button_attr']['data-user-id'] = $user_id;
            }

            return $args;
        }

        public function override_last_activity_2($object_id, $meta_value){
            Better_Messages()->users->update_last_activity( $object_id, $meta_value );
        }

        public function fix_404(){
            $slug = Better_Messages()->settings['bpProfileSlug'];
            if ( function_exists('bp_core_no_access') && bm_bp_is_current_component( $slug ) && ! is_user_logged_in() ) {
                bp_core_no_access();
            }
        }

        public function fix_youzer(){
            if( function_exists('bp_nav_menu_get_item_url') ){
                $messages_link = bp_nav_menu_get_item_url( 'messages' );
            }
        }


        public function thread_link( $thread_link, $thread_id )
        {
            $link = add_query_arg([
                'thread_id' => $thread_id
            ], Better_Messages()->functions->get_link());

            return $link;
        }

        public function pm_link_legacy(){
            if( ! is_user_logged_in() ) return false;
            $user_id = BP_Better_Messages()->functions->get_member_id();
            if( Better_Messages()->functions->get_current_user_id() === $user_id ) return false;

            echo '<div class="generic-button bp-better-messages-private-message-link">';

            if( BP_Better_Messages()->settings['bpForceMiniChat'] === '1'
                && function_exists('bp_displayed_user_id') ) {
                echo '<a href="' . $this->pm_link() . '" class="bpbm-pm-button open-mini-chat" data-user-id="' .  $user_id . '"><span class="bm-button-text">' . __('Private Message', 'bp-better-messages') . '</span></a>';
            } else {
                echo '<a href="' . $this->pm_link() . '">' . __('Private Message', 'bp-better-messages') . '</a>';
            }

            echo '</div>';
        }

        public function bp_nouveau_get_members_buttons( $buttons, $user_id, $type ){
            if ( ! is_user_logged_in() ) {
                return $buttons;
            }

            if( BP_Better_Messages()->settings['bpForceMiniChat'] === '1'
                && function_exists('bp_displayed_user_id')
                && isset( $buttons['private_message'] )
            ) {
                $buttons['private_message']['button_attr']['data-user-id'] = bp_displayed_user_id();
                #print_r($buttons['private_message']);
            }

            return $buttons;
        }

        public function pm_link_args($args){
            if ( ! is_user_logged_in() ) {
                return $args;
            }

            $args['link_href'] = $this->pm_link();

            if( BP_Better_Messages()->settings['bpForceMiniChat'] === '1' && function_exists('bp_displayed_user_id') ) {
                $args['link_class'] .= ' bpbm-pm-button open-mini-chat bm-no-loader';
                $args['button_attr']['data-user-id'] = bp_displayed_user_id();
            }

            return $args;
        }

        public function pm_link( $user_id = false )
        {
            if( ! $user_id ) {
                $user_id = Better_Messages()->functions->get_member_id();
            }

            if( Better_Messages()->settings['fastStart'] == '1' ){
                return add_query_arg([
                    'bm-fast-start' => '1',
                    'to' => $user_id
                ], Better_Messages()->functions->get_link( Better_Messages()->functions->get_current_user_id() ));
            } else {
                return Better_Messages()->functions->add_hash_arg('new-conversation', [
                    'to' => $user_id
                ], Better_Messages()->functions->get_link( Better_Messages()->functions->get_current_user_id() ));
            }
        }

        function pm_link_nouveau($buttons, $user_id, $type){
            if( $user_id === Better_Messages()->functions->get_current_user_id() ) {
                return $buttons;
            }

            if( isset( $buttons['private_message'] ) ){
                $buttons['private_message']['button_attr']['href'] = $this->pm_link();
            }

            return $buttons;
        }

        public function remove_standard_topbar( $wp_admin_bar )
        {
            $wp_admin_bar->remove_node( 'my-account-messages' );
        }

        public function redirect_standard_component()
        {
            if( ! function_exists('bp_is_messages_component') ) return;

            if ( bp_is_messages_component() ) {
                $link = Better_Messages()->functions->get_link();

                if( bp_action_variable(0) !== false ){
                    $link = add_query_arg([
                        'thread_id' => bp_action_variable(0)
                    ], Better_Messages()->functions->get_link());
                }

                if( bp_is_current_action('compose') ){
                    $link = Better_Messages()->functions->add_hash_arg('new-conversation', [], Better_Messages()->functions->get_link( Better_Messages()->functions->get_current_user_id() ));
                }

                if(isset($_GET['r'])){
                    $username = sanitize_text_field($_GET['r']);
                    $user    = get_user_by( 'slug', $username );

                    if( $user ) {
                        $link = Better_Messages()->functions->add_hash_arg('new-conversation', [
                            'to' => $user->ID
                        ], Better_Messages()->functions->get_link( Better_Messages()->functions->get_current_user_id() ));
                    }
                }

                wp_redirect( $link );
                exit;
            }
        }

        public function display_name_override( $display_name, $user_id ){
            if( function_exists('bp_core_get_user_displayname') ) {
                return html_entity_decode( bp_core_get_user_displayname( $user_id ) );
            }

            return $display_name;
        }

        public function youzify_verified_member( $is_verified, $user_id ){
            $is_account_verified = youzify_is_user_account_verified( $user_id );

            if( $is_account_verified ){
                $is_verified = true;
            }

            return $is_verified;
        }

        public function fallback_to_messages_new_message( $message ){
            $backtrace = debug_backtrace();

            if( in_array('messages_new_message', array_column($backtrace, 'function') ) ) {
                remove_action('messages_message_before_save', array($this, 'fallback_to_messages_new_message'), 10, 1);

                $args = [
                    'sender_id' => (int)$message->sender_id,
                    'subject'   => $message->subject,
                    'content'   => $message->message
                ];

                if ($message->thread_id) {
                    // Existing threads
                    $args['thread_id'] = $message->thread_id;

                    Better_Messages()->functions->new_message($args);
                    $message->recipients = [];
                } else if (isset($message->recipients) && is_array($message->recipients) && !empty($message->recipients)) {
                    // New threads
                    $recipients = $message->recipients;

                    $bm_recipients = [];
                    foreach ($recipients as $recipient) {
                        $bm_recipients[] = intval($recipient->user_id);
                    }

                    $args['recipients'] = $bm_recipients;

                    Better_Messages()->functions->new_message($args);
                    $message->recipients = [];
                }

                add_action('messages_message_before_save', array($this, 'fallback_to_messages_new_message'), 10, 1);
            }
        }

        public function making_notifications_work( $retval, $total_items, $text, $link, $item_id, $secondary_item_id ){
            $thread_id = bp_notifications_get_meta($item_id, 'thread_id', true);

            if( ! $thread_id ){
                $message = Better_Messages()->functions->get_message( $item_id );
                if( $message ) $thread_id = $message->thread_id;
            }

            if( $thread_id ) {
                $link = Better_Messages()->functions->get_user_messages_url( get_current_user_id(), $thread_id );

                $retval = '<a href="' . $link .'">' . $text .  '</a>';
            }

            return $retval;
        }

        public function search_friends( $result, $search, $user_id  ){
            if( function_exists('friends_get_friend_user_ids') && $user_id > 0 ) {

                $friends = bp_core_get_suggestions(array(
                    'limit' => 10,
                    'only_friends' => true,
                    'term' => $search,
                    'type' => 'members',
                    'exclude' => [ $user_id ]
                ));

                if( count( $friends ) > 0 ) {
                    foreach ( $friends as $friend ){
                        $result[] = intval($friend->user_id);
                    }
                }
            }

            return $result;
        }

        public function bp_verified_member( $is_verified, $user_id ){
            global $bp_verified_member;

            if( $bp_verified_member->is_user_verified( $user_id ) ){
                $is_verified = true;
            }

            return $is_verified;
        }

        public function enabled( $val ){
            return '1';
        }

        public function get_friends( $friends, $user_id ){
            $args = [
                'is_confirmed' => 1
            ];

            $friends = BP_Friends_Friendship::get_friendships( $user_id, $args );

            $users = [];
            if( count( $friends ) > 0 ) {
                foreach ($friends as $friend) {
                    $users[] = Better_Messages()->functions->rest_user_item( ( $friend->friend_user_id == $user_id ) ? $friend->initiator_user_id : $friend->friend_user_id  );
                }
            }

            return $users;
        }

        public function get_groups( $groups, $user_id ){
            $_groups = groups_get_user_groups( $user_id );

            if( count( $_groups['groups'] ) > 0 ) {
                foreach ($_groups['groups'] as $group_id) {
                    $group = new BP_Groups_Group((int)$group_id);
                    if ($group->id === 0) continue;

                    $group_id         = (int) $group->id;
                    $messages_enabled = (int) ( Better_Messages()->groups->is_group_messages_enabled( $group_id ) === 'enabled' );
                    $thread_id        = (int) Better_Messages()->groups->get_group_thread_id( $group->id );

                    $avatar = bp_core_fetch_avatar( array(
                        'item_id'    => $group_id,
                        'avatar_dir' => 'group-avatars',
                        'object'     => 'group',
                        'type'       => 'thumb',
                        'html'       => false
                    ));

                    $group_item = [
                        'group_id'  => $group_id,
                        'name'      => html_entity_decode(esc_attr($group->name)),
                        'messages'  => $messages_enabled,
                        'thread_id' => $thread_id,
                        'image'     => $avatar,
                        'url'       => bp_get_group_permalink( $group )
                    ];

                    $groups[] = $group_item;
                }
            }

            return $groups;
        }
    }
}

