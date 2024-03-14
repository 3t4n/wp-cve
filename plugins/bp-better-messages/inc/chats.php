<?php
defined( 'ABSPATH' ) || exit;

class Better_Messages_Chats
{

    public static function instance()
    {

        // Store the instance locally to avoid private static replication
        static $instance = null;

        // Only run these methods if they haven't been run previously
        if ( null === $instance ) {
            $instance = new Better_Messages_Chats;
            $instance->setup_actions();
        }

        // Always return the instance
        return $instance;

        // The last metroid is in captivity. The galaxy is at peace.
    }

    public function setup_actions(){
        add_action( 'init',      array( $this, 'register_post_type' ) );
        add_action( 'save_post', array( $this, 'save_post' ), 1, 2 );

        add_shortcode( 'bp_better_messages_chat_room', array( $this, 'layout' ) );

        //add_action( 'messages_message_sent', array( $this, 'on_message_sent' ) );

        add_action( "save_post_bpbm-chat", array( $this, 'on_chat_update' ), 10, 3 );
        add_action( 'before_delete_post',  array( $this, 'on_chat_delete' ), 10, 1 );

        add_action( 'better_messages_chat_room_sync_auto_add_users', array( $this, 'sync_auto_add_users'), 10, 1 );

        add_action( 'user_register', array( $this, 'on_user_register' ), 20, 2 );
        add_action( 'add_user_role', array( $this, 'on_user_role_change' ), 20, 2 );
        add_action( 'set_user_role', array( $this, 'on_user_role_change' ), 20, 3 );
        add_action( 'better_messages_guest_registered', array( $this, 'guest_registered' ), 20, 1 );

        add_action( 'rest_api_init',  array( $this, 'rest_api_init' ) );
        add_filter( 'better_messages_rest_thread_item', array( $this, 'rest_thread_item'), 10, 5 );

        add_filter('better_messages_thread_title', array( $this, 'chat_thread_title' ), 10, 3 );
        add_action('better_messages_before_message_send',  array( $this, 'before_message_send' ), 20, 2 );
    }

    function before_message_send( &$args, &$errors ){
        $thread_id = $args['thread_id'];
        $type = Better_Messages()->functions->get_thread_type( $thread_id );
        if( $type !== 'chat-room' ) return;

        $chat_id = Better_Messages()->functions->get_thread_meta( $thread_id, 'chat_id' );
        if( !! $chat_id ) {
            $user_id = (isset($args['sender_id'])) ? $args['sender_id'] : Better_Messages()->functions->get_current_user_id();

            if (!$this->user_can_reply($user_id, $chat_id)) {
                $settings = $this->get_chat_settings($chat_id);
                $errors['not_allowed_to_reply'] = $settings['not_allowed_reply_text'];
            }
        }
    }

    /**
     * @param string $title
     * @param int $thread_id
     * @param BM_Thread $thread
     * @return string
     */
    public function chat_thread_title(string $title, int $thread_id, $thread ){
        $thread_type = Better_Messages()->functions->get_thread_type( $thread_id );
        if( $thread_type !== 'chat-room' ) return $title;

        $chat_id = (int) Better_Messages()->functions->get_thread_meta($thread_id, 'chat_id');
        $chat = get_post( $chat_id );

        if( $chat ){
            return $chat->post_title;
        }

        return $title;
    }

    public function rest_api_init(){
        register_rest_route('better-messages/v1/admin', '/getChatParticipants', array(
            'methods'             => 'POST',
            'callback'            => array($this, 'get_chat_participants'),
            'permission_callback' => array($this, 'user_is_admin'),
        ));

        register_rest_route( 'better-messages/v1', '/chat/(?P<id>\d+)/join', array(
            'methods' => 'POST',
            'callback' => array( $this, 'join_chat' ),
            'permission_callback' => array( Better_Messages_Rest_Api(), 'is_user_authorized' )
        ) );

        register_rest_route( 'better-messages/v1', '/chat/(?P<id>\d+)/leave', array(
            'methods' => 'POST',
            'callback' => array( $this, 'leave_chat' ),
            'permission_callback' => array( Better_Messages_Rest_Api(), 'is_user_authorized' )
        ) );
    }

    public function user_is_admin(){
        return current_user_can('manage_options');
    }

    public function get_chat_participants( WP_REST_Request $request ){
        global $wpdb;

        $chat_id = intval( $request->get_param('chatId') );

        $thread_id = $this->get_chat_thread_id( $chat_id );

        $page   = ( $request->has_param('page') ) ? intval( $request->get_param('page') ) : 1;

        $per_page = 10;

        $offset = 0;

        if( $page > 1 ){
            $offset = ( $page - 1 ) * $per_page;
        }

        $table = bm_get_table('recipients');

        $total_results = (int) $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) 
        FROM `{$table}` `recipients`
        LEFT JOIN " . $wpdb->users . " `users`
            ON `users`.`ID` = `recipients`.`user_id`
        WHERE `thread_id` = %d
        AND (  ( `recipients`.`user_id` >= 0 AND `users`.`ID` IS NOT NULL ) OR ( `recipients`.`user_id` < 0 ) )", $thread_id ) );

        $sql = $wpdb->prepare("
        SELECT `user_id` 
        FROM `{$table}` `recipients`
        LEFT JOIN " . $wpdb->users . " `users`
            ON `users`.`ID` = `recipients`.`user_id`
        WHERE `thread_id` = %d 
        AND (  ( `recipients`.`user_id` >= 0 AND `users`.`ID` IS NOT NULL ) OR ( `recipients`.`user_id` < 0 ) )
        LIMIT {$offset}, {$per_page}", $thread_id );

        $results = $wpdb->get_col( $sql );

        $pages = ceil( $total_results / $per_page );

        $users = [];

        foreach ( $results as $user_id ) {
            $users[] = Better_Messages()->functions->rest_user_item($user_id);
        }

        $result = [
            'total' => $total_results,
            'pages' => $pages,
            'users' => $users
        ];


        return $result;
    }

    public function rest_thread_item( $thread_item, $thread_id, $thread_type, $include_personal, $user_id ){
        if( $thread_type !== 'chat-room'){
            return $thread_item;
        }

        $chat_id = (int) Better_Messages()->functions->get_thread_meta($thread_id, 'chat_id');
        $settings = $this->get_chat_settings( $chat_id );

        $recipients = Better_Messages()->functions->get_recipients( $thread_id );

        $is_participant = isset( $recipients[$user_id] );
        $auto_join = $settings['auto_join'] === '1';

        if( $include_personal && $auto_join && ! $is_participant ){
            $is_participant = $this->add_to_chat( $user_id, $chat_id );
        }

        $moderators =  (array) Better_Messages()->functions->get_moderators( $thread_id );

        if( has_post_thumbnail( $chat_id ) ) {
            $image_id = get_post_thumbnail_id($chat_id);

            if ($image_id) {
                $image_src = wp_get_attachment_image_src($image_id, [100, 100]);
                if ($image_src) {
                    $thread_item['image'] = $image_src[0];
                }
            }
        }

        $thread_item['chatRoom']['id']                   = (int) $chat_id;

        $template =  $settings['template'];
        $thread_item['chatRoom']['template']             = $template;
        $thread_item['chatRoom']['modernLayout']         = $settings['modernLayout'];

        $thread_item['chatRoom']['onlyJoinedCanRead']    = ( $settings['only_joined_can_read'] === '1' );
        $thread_item['chatRoom']['autoJoin']             = $auto_join;
        $thread_item['chatRoom']['enableFiles']          = ( $settings['enable_files'] === '1' );
        $thread_item['chatRoom']['guestAllowed']         = in_array( 'bm-guest', $settings['can_join'] );

        $thread_item['moderators']                       = $moderators;

        $thread_item['chatRoom']['mustJoinMessage']      = $settings['must_join_message'];
        $thread_item['chatRoom']['joinButtonText']       = $settings['join_button_text'];
        $thread_item['chatRoom']['notAllowedText']       = $settings['not_allowed_text'];
        $thread_item['chatRoom']['notAllowedReplyText']  = $settings['not_allowed_reply_text'];
        $thread_item['chatRoom']['mustLoginText']        = $settings['must_login_text'];
        $thread_item['chatRoom']['loginButtonText']      = $settings['login_button_text'];
        $thread_item['chatRoom']['guestButtonText']        = $settings['guest_button_text'];


        if( $include_personal ) {
            $is_moderator = user_can( $user_id, 'manage_options') || Better_Messages()->functions->is_thread_moderator( $thread_id, $user_id ) ;

            $thread_item['chatRoom']['isJoined'] = $is_participant;
            $thread_item['chatRoom']['canJoin']  = $this->user_can_join($user_id, $chat_id);
            $thread_item['chatRoom']['hideParticipants'] = ( ! $is_moderator && $settings['hide_participants'] === '1' );

            if ( ! $is_participant ) {
                $thread_item['isHidden'] = (int) true;
                $thread_item['permissions']['canReply'] = false;
                $thread_item['permissions']['canMinimize'] = false;
                $thread_item['permissions']['canMuteThread'] = false;
                $thread_item['chatRoom']['hideParticipants'] = true;
            } else {
                $can_reply = $this->user_can_reply( $user_id, $chat_id );

                if( $is_moderator ){
                    $thread_item['restricted'] = Better_Messages()->moderation->get_restricted_users( $thread_id );
                }

                $thread_item['permissions']['canReply'] = $can_reply;

                if( ! $can_reply ){
                    if( count($thread_item['permissions']['canReplyMsg']) === 0 ) $thread_item['permissions']['canReplyMsg']['cant_reply_to_chat'] = $settings['not_allowed_reply_text'];
                }
            }
        }

        return $thread_item;
    }

    public function on_user_register( $user_id, $userdata = null ){
        $roles = Better_Messages()->functions->get_user_roles( $user_id );

        if( count($roles) === 0 ) return false;

        global $wpdb;

        $clauses = [];

        foreach( $roles as $role ){
            $clauses[] = $wpdb->prepare("( `postmeta`.`meta_key` = 'bpbm-chat-auto-add' AND `postmeta`.`meta_value` LIKE %s )", '%"' . $role . '"%');
        }

        $chat_ids = $wpdb->get_col("SELECT 
        `posts`.`ID`
        FROM {$wpdb->posts} posts
        INNER JOIN {$wpdb->postmeta} postmeta 
        ON ( `posts`.`ID` = `postmeta`.`post_id` ) 
        WHERE 1=1  
        AND ( " . implode(' OR ', $clauses ) . " ) 
        AND `posts`.`post_type` = 'bpbm-chat' 
        GROUP BY `posts`.ID");

        if( count( $chat_ids ) > 0 ){
            foreach ( $chat_ids as $chat_id ) {
                $this->add_to_chat( $user_id, $chat_id );
            }
        }
    }

    public function guest_registered( $guest_id ){
        global $wpdb;

        $guest_id =  -1 * abs( $guest_id );

        $chat_ids = $wpdb->get_col("SELECT 
        `posts`.`ID`
        FROM {$wpdb->posts} posts
        INNER JOIN {$wpdb->postmeta} postmeta 
        ON ( `posts`.`ID` = `postmeta`.`post_id` ) 
        WHERE 1=1  
        AND `postmeta`.`meta_key` = 'bpbm-chat-auto-add' 
        AND `postmeta`.`meta_value` LIKE '%bm-guest%'
        AND `posts`.`post_type` = 'bpbm-chat'   
        GROUP BY `posts`.ID");

        if( count( $chat_ids ) > 0 ){
            foreach ( $chat_ids as $chat_id ) {
                $this->add_to_chat( $guest_id, $chat_id );
            }
        }
    }

    public function on_user_role_change( $user_id, $role, $old_roles = [] ){
        $this->sync_roles_update( [ $role ] );
    }

    public function on_chat_update( $post_ID, $post, $update ){
        $thread_id = $this->get_chat_thread_id( $post_ID );

        $name = get_the_title( $post_ID );
        global $wpdb;

        $wpdb->update(
            bm_get_table('threads'),
            array(
                'subject'   => $name,
            ),
            array(
                'id' => $thread_id,
            ),
            array( '%s' ), array( '%d' )
        );

        wp_cache_delete( 'thread_' . $thread_id . '_type', 'bm_messages' );
        wp_cache_delete( 'thread_' . $thread_id, 'bm_messages' );
    }

    public function on_chat_delete( $post_ID ){
        $post = get_post( $post_ID );
        if( $post->post_type === 'bpbm-chat' ){
            $thread_id = $this->get_chat_thread_id( $post_ID );
            Better_Messages()->functions->erase_thread( $thread_id );
        }
    }

    public function on_message_sent( $message )
    {
        if( ! isset($message->thread_id) ) return false;

        $thread_id = $message->thread_id;
        $chat_id   = Better_Messages()->functions->get_thread_meta( $thread_id, 'chat_id' );

        if( ! $chat_id ) return false;
        global $wpdb;
        $wpdb->update(bm_get_table('recipients'), ['unread_count' => 0], ['thread_id' => $thread_id], ['%d'], ['%d']);
        Better_Messages()->hooks->clean_thread_cache( $thread_id );

        return true;
    }

    public function leave_chat( WP_REST_Request $request ){
        global $wpdb;

        $user_id = Better_Messages()->functions->get_current_user_id();
        $chat_id = intval($request->get_param('id'));

        $thread_id = $this->get_chat_thread_id( $chat_id );

        $result = false;

        $userIsParticipant = (bool) $wpdb->get_var($wpdb->prepare("
        SELECT COUNT(*) FROM `" . bm_get_table('recipients') . "` WHERE `user_id` = %d AND `thread_id` = %d
        ", $user_id, $thread_id));

        if( $userIsParticipant ) {
            $result = (bool) $wpdb->delete(
                bm_get_table('recipients'),
                array(
                    'user_id'   => $user_id,
                    'thread_id' => $thread_id
                ),
                array( '%d', '%d' )
            );
        }

        Better_Messages()->hooks->clean_thread_cache( $thread_id );

        Better_Messages()->functions->delete_thread_meta( $thread_id, 'auto_add_hash' );

        do_action( 'better_messages_after_chat_left', $thread_id, $chat_id );
        do_action( 'better_messages_thread_updated', $thread_id );
        do_action( 'better_messages_info_changed',   $thread_id );

        $return = Better_Messages()->api->get_threads( [ $thread_id ], false, false );
        $return['result'] = $result;

        return $return;
    }

    public function join_chat( WP_REST_Request $request ){
        $user_id = Better_Messages()->functions->get_current_user_id();
        $chat_id = intval($request->get_param('id'));

        $is_joined = $this->add_to_chat( $user_id, $chat_id );

        $thread_id = $this->get_chat_thread_id( $chat_id );

        $return = Better_Messages()->api->get_threads( [ $thread_id ], false, false );

        $return['result'] = $is_joined;

        return $return;
    }

    public function add_to_chat( $user_id, $chat_id ){
        if( ! $this->user_can_join( $user_id, $chat_id ) ){
            return false;
        }

        $thread_id = $this->get_chat_thread_id( $chat_id );

        $result = Better_Messages()->functions->add_participant_to_thread( $thread_id, $user_id );

        do_action( 'better_messages_after_chat_join', $thread_id, $chat_id );
        do_action( 'better_messages_thread_updated', $thread_id );
        do_action( 'better_messages_info_changed',   $thread_id );

        return $result;
    }

    public function register_post_type(){
        $args = array(
            'public'               => false,
            'labels'               => [
                'name'          => _x( 'Chat Rooms', 'Chat Rooms', 'bp-better-messages' ),
                'singular_name' => _x( 'Chat Room', 'Chat Rooms', 'bp-better-messages' ),
                'add_new'       => _x( 'Create new Chat Room', 'Chat Rooms', 'bp-better-messages' ),
                'add_new_item'  => _x( 'Create new Chat Room', 'Chat Rooms', 'bp-better-messages' ),
                'edit_item'     => _x( 'Edit Chat Room', 'Chat Rooms', 'bp-better-messages' ),
                'new_item'      => _x( 'New Chat Room', 'Chat Rooms', 'bp-better-messages' ),
                'featured_image'        => _x( 'Chat Thumbnail', 'Chat Rooms', 'bp-better-messages' ),
                'set_featured_image'    => _x( 'Set chat thumbnail', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'recipe' ),
                'remove_featured_image' => _x( 'Remove chat thumbnail', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'recipe' ),
                'use_featured_image'    => _x( 'Use as chat thumbnail', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'recipe' ),
            ],
            'publicly_queryable'   => false,
            'show_ui'              => true,
            'show_in_menu'         => 'bp-better-messages',
            'menu_position'        => 1,
            'query_var'            => false,
            'capability_type'      => 'page',
            'has_archive'          => false,
            'hierarchical'         => false,
            'show_in_admin_bar'    => false,
            'show_in_nav_menus'    => false,
            'supports'             => array( 'title', 'thumbnail' ),
            'register_meta_box_cb' => array( $this, 'register_meta_box' )

        );

        register_post_type( 'bpbm-chat', $args );
    }

    public function register_meta_box(){
        add_meta_box(
            'bpbm-chat-settings',
            _x( 'Settings', 'Chat rooms settings page', 'bp-better-messages' ),
            array( $this, 'bpbm_chat_settings' ),
            null,
            'advanced'
        );

        /*add_meta_box(
            'bpbm-chat-participants',
            _x( 'Participants', 'Chat rooms settings page', 'bp-better-messages' ),
            array( $this, 'chat_participants' ),
            null,
            'normal'
        );*/
    }

    public function chat_participants( $post ){
        echo '<div class="bm-chat-participants" data-chat-id="' . intval( $post->ID ) . '">' . __( 'Loading', 'bp-better-messages' ) . '</div>';
    }

    public function get_chat_settings( $chat_id ){
        $defaults = array(
            'only_joined_can_read'            => '0',
            'enable_chat_email_notifications' => '0',
            'can_join'                        => [],
            'can_reply'                       => [],
            'auto_add'                        => [],
            'template'                        => 'default',
            'modernLayout'                    => 'default',
            'auto_join'                       => '0',
            'enable_notifications'            => '0',
            'allow_guests'                    => '0',
            'hide_participants'               => '0',
            'enable_files'                    => '0',
            'hide_from_thread_list'           => '1',
            'must_join_message'               => _x('You need to join this chat room to send messages', 'Chat rooms settings page', 'bp-better-messages'),
            'join_button_text'                => _x('Join chat room', 'Chat rooms settings page', 'bp-better-messages'),
            'not_allowed_text'                => _x('You are not allowed to join this chat room', 'Chat rooms settings page', 'bp-better-messages'),
            'not_allowed_reply_text'          => _x('You are not allowed to reply in this chat room', 'Chat rooms settings page', 'bp-better-messages'),
            'must_login_text'                 => _x('You need to login to website to send messages', 'Chat rooms settings page', 'bp-better-messages'),
            'login_button_text'               => _x('Login', 'Chat rooms settings page', 'bp-better-messages'),
            'guest_button_text'               => _x('Chat as Guest', 'Chat rooms settings page', 'bp-better-messages')
        );

        $args = get_post_meta( $chat_id, 'bpbm-chat-settings', true );


        if( empty($args) || ! is_array($args) ){
            $args = array();
        }

        $result = wp_parse_args( $args, $defaults );

        if( isset($result['allow_guests_chat']) && $result['allow_guests_chat'] === '1' ){
            $result['can_join'][] = 'bm-guest';
            $result['can_reply'][] = 'bm-guest';
        }

        return $result;
    }

    public function save_post( $post_id, $post ){
        if( ! isset($_POST['bpbm_save_chat_nonce']) ){
            return $post->ID;
        }

        //Verify it came from proper authorization.
        if ( ! wp_verify_nonce($_POST['bpbm_save_chat_nonce'], 'bpbm-save-chat-settings-' . $post->ID ) ) {
            return $post->ID;
        }

        //Check if the current user can edit the post
        if ( ! current_user_can( 'manage_options' ) ) {
            return $post->ID;
        }

        if( isset( $_POST['bpbm'] ) && is_array($_POST['bpbm']) ){
            $settings = (array) $_POST['bpbm'];

            if ( ! isset( $settings['only_joined_can_read'] ) ) {
                $settings['only_joined_can_read'] = '0';
            }

            if ( ! isset( $settings['auto_join'] ) ) {
                $settings['auto_join'] = '0';
            }

            if ( ! isset( $settings['hide_participants'] ) ) {
                $settings['hide_participants'] = '0';
            }

            if ( ! isset( $settings['enable_chat_email_notifications'] ) ) {
                $settings['enable_chat_email_notifications'] = '0';
            }

            if ( ! isset( $settings['enable_files'] ) ) {
                $settings['enable_files'] = '0';
            }

            if ( ! isset( $settings['hide_from_thread_list'] ) ) {
                $settings['hide_from_thread_list'] = '0';
            }

            if ( ! isset( $settings['enable_notifications'] ) ) {
                $settings['enable_notifications'] = '0';
            }

            if ( ! isset( $settings['allow_guests'] ) ) {
                $settings['allow_guests'] = '0';
            }

            if ( ! isset( $settings['must_join_message'] ) || empty( $settings['must_join_message'] )  ) {
                $settings['must_join_message'] = _x('You need to join this chat room to send messages', 'Chat rooms settings page', 'bp-better-messages');
            } else {
                $settings['must_join_message'] = wp_kses( $settings['must_join_message'], 'user_description' );
            }

            if ( ! isset( $settings['join_button_text'] ) || empty( $settings['join_button_text'] )  ) {
                $settings['join_button_text'] = _x('Join chat room', 'Chat rooms settings page', 'bp-better-messages');
            } else {
                $settings['join_button_text'] = sanitize_text_field( $settings['join_button_text'] );
            }

            if ( ! isset( $settings['login_button_text'] ) || empty( $settings['login_button_text'] )  ) {
                $settings['login_button_text'] = _x('Login', 'Chat rooms settings page', 'bp-better-messages');
            } else {
                $settings['login_button_text'] = sanitize_text_field( $settings['login_button_text'] );
            }

            if ( ! isset( $settings['guest_button_text'] ) || empty( $settings['guest_button_text'] )  ) {
                $settings['guest_button_text'] = _x('Chat as Guest', 'Chat rooms settings page', 'bp-better-messages');
            } else {
                $settings['guest_button_text'] = sanitize_text_field( $settings['guest_button_text'] );
            }



            if ( ! isset( $settings['must_login_text'] ) || empty( $settings['must_login_text'] )  ) {
                $settings['must_login_text'] =  _x('You need to login to website to send messages', 'Chat rooms settings page', 'bp-better-messages');
            } else {
                $settings['must_login_text'] = wp_kses( $settings['must_login_text'], 'user_description' );
            }

            if ( ! isset( $settings['not_allowed_text'] ) || empty( $settings['not_allowed_text'] )  ) {
                $settings['not_allowed_text'] = _x('You are not allowed to join this chat room', 'Chat rooms settings page', 'bp-better-messages');
            } else {
                $settings['not_allowed_text'] = wp_kses( $settings['not_allowed_text'], 'user_description' );
            }

            if ( ! isset( $settings['not_allowed_reply_text'] ) || empty( $settings['not_allowed_reply_text'] )  ) {
                $settings['not_allowed_reply_text'] = _x('You are not allowed to reply in this chat room', 'Chat rooms settings page', 'bp-better-messages');
            } else {
                $settings['not_allowed_reply_text'] = wp_kses( $settings['not_allowed_reply_text'], 'user_description' );
            }

            update_post_meta( $post->ID, 'bpbm-chat-settings', $settings );

            $thread_id = $this->get_chat_thread_id( $post->ID );

            $notifications_enabled = true;

            if( $settings['hide_from_thread_list'] === '1' ) {
                Better_Messages()->functions->update_thread_meta($thread_id, 'exclude_from_threads_list', '1');
                $notifications_enabled = false;
            } else {
                Better_Messages()->functions->delete_thread_meta($thread_id, 'exclude_from_threads_list');
            }

            if( $settings['enable_notifications'] === '1' ) {
                Better_Messages()->functions->update_thread_meta($thread_id, 'enable_notifications', '1');
            } else {
                Better_Messages()->functions->delete_thread_meta($thread_id, 'enable_notifications');
                $notifications_enabled = false;
            }

            if( ! $notifications_enabled ){
                Better_Messages()->functions->update_thread_meta($thread_id, 'email_disabled', '1');
            } else {
                Better_Messages()->functions->delete_thread_meta( $thread_id, 'email_disabled' );
            }


            if( isset( $settings['auto_add'] ) ) {
                update_post_meta( $post->ID, 'bpbm-chat-auto-add', $settings['auto_add'] );
            } else {
                delete_post_meta( $post->ID, 'bpbm-chat-auto-add' );
            }

            $this->sync_auto_add_users( $post->ID );

            do_action( 'better_messages_thread_updated', $thread_id );
            do_action( 'better_messages_info_changed',   $thread_id );
        }

    }

    public function bpbm_chat_settings( $post ){
        $roles = get_editable_roles();
        if(isset($roles['administrator'])) unset( $roles['administrator'] );

        $roles['bm-guest'] = [
            'name' => _x('Guests', 'Settings page', 'bp-better-messages' )
        ];

        wp_nonce_field( 'bpbm-save-chat-settings-' . $post->ID, 'bpbm_save_chat_nonce' );

        $settings = $this->get_chat_settings( $post->ID ); ?>
        <div class="bm-chat-settings" data-chat-id="<?php echo esc_attr($post->ID); ?>" data-settings="<?php echo esc_attr(json_encode($settings)); ?>" data-roles="<?php echo esc_attr(json_encode($roles)); ?>">
            <p style="text-align: center"><?php _ex( 'Loading',  'WP Admin', 'bp-better-messages' ); ?></p>
        </div>
    <?php
    }

    public function layout( $args ){
        $chat_id = $args['id'];
        $disable_init = isset($args['disable_auto_init']);

        if (defined('WP_DEBUG') && true === WP_DEBUG) {
            // some debug to add later
        } else {
            error_reporting(0);
        }

        $thread_id     = $this->get_chat_thread_id( $chat_id );

        if( ! $thread_id ) return false;

        $chat_settings = $this->get_chat_settings( $chat_id );

        if( ! is_user_logged_in() ){
            $allow_guests = $chat_settings['allow_guests'] === '1';
            if( ! $allow_guests ) {
                return Better_Messages()->functions->render_login_form();
            } else {
                Better_Messages()->enqueue_js();
                Better_Messages()->enqueue_css();

                add_action('wp_footer', array( Better_Messages_Customize(), 'header_output' ), 100);
            }
        }

        $this->sync_auto_add_users( $chat_id );

        global $bpbm_errors;
        $bpbm_errors = [];

        do_action('bp_better_messages_before_chat', $chat_id, $thread_id );

        ob_start();

        if( ! Better_Messages()->functions->is_ajax() && count( $bpbm_errors ) > 0 ) {
            echo '<p class="bpbm-notice">' . implode('</p><p class="bpbm-notice">', $bpbm_errors) . '</p>';
        }

        $initialHeight = (int) apply_filters( 'bp_better_messages_max_height', Better_Messages()->settings['messagesHeight'] );
        $class = 'bp-messages-chat-wrap';
        if( $disable_init ) $class .= ' bm-disable-auto-init';
        echo '<div class="' . $class . '" style="height: ' . $initialHeight . 'px" data-thread-id="' .  esc_attr($thread_id) . '" data-chat-id="'  . esc_attr($chat_id) . '">' . Better_Messages()->functions->container_placeholder() . '</div>';

        $content = ob_get_clean();
        $content = str_replace( 'loading="lazy"', '', $content );

        $content = Better_Messages()->functions->minify_html( $content );

        do_action('bp_better_messages_after_chat', $chat_id, $thread_id);

        return $content;
    }

    public function user_can_join( $user_id, $chat_id ){
        if( user_can( $user_id, 'manage_options') ) return true;

        $settings = $this->get_chat_settings( $chat_id );
        $thread_id = $this->get_chat_thread_id( $chat_id );

        $has_access = false;

        $user_roles = Better_Messages()->functions->get_user_roles($user_id);

        foreach ($user_roles as $role) {
            if (in_array($role, $settings['can_join'])) {
                $has_access = true;
            }
        }

        return apply_filters( 'better_messages_chat_user_can_join', $has_access, $user_id, $chat_id, $thread_id );
    }

    public function user_can_reply( $user_id, $chat_id ){
        if( user_can( $user_id, 'manage_options') ) return true;
        $settings = $this->get_chat_settings( $chat_id );
        $thread_id = $this->get_chat_thread_id( $chat_id );

        $has_access = false;

        $user_roles = Better_Messages()->functions->get_user_roles($user_id);

        foreach ($user_roles as $role) {
            if (in_array($role, $settings['can_reply'])) {
                $has_access = true;
            }
        }

        return Better_Messages()->functions->can_send_message_filter( $has_access, $user_id, $thread_id );
    }

    public function get_chat_thread_id( $chat_id ){
        global $wpdb;

        $thread_id = (int) $wpdb->get_var( $wpdb->prepare( "
        SELECT bm_thread_id 
        FROM `" . bm_get_table('threadsmeta') . "` 
        WHERE `meta_key` = 'chat_id' 
        AND   `meta_value` = %s
        ", $chat_id ) );

        if( $thread_id === 0 ) {
            $thread_id = false;
        } else {
            $messages_count = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*)  FROM `" . bm_get_table('threads') . "` WHERE `id` = %d", $thread_id));

            if( $messages_count === 0 ) {
                $thread_id = false;
            }
        }

        if( ! $thread_id ) {
            $chat = get_post($chat_id);
            if( ! $chat ) return false;

            $wpdb->query( $wpdb->prepare( "
            DELETE 
            FROM `" . bm_get_table('threadsmeta') . "` 
            WHERE `meta_key` = 'chat_id' 
            AND   `meta_value` = %s
            ", $chat_id ) );

            $name = get_the_title( $chat_id );

            $wpdb->insert(
                bm_get_table('threads'),
                array(
                    'subject' => $name,
                    'type'    => 'chat-room'
                )
            );

            $thread_id = $wpdb->insert_id;

            Better_Messages()->functions->update_thread_meta( $thread_id, 'chat_thread', true );
            Better_Messages()->functions->update_thread_meta( $thread_id, 'chat_id', $chat_id );

            wp_cache_delete( 'thread_' . $thread_id . '_type', 'bm_messages' );
            wp_cache_delete( 'thread_' . $thread_id, 'bm_messages' );
        }

        return $thread_id;
    }

    public function sync_roles_update( $roles = [] ){
        if( count( $roles ) === 0 ) return false;

        global $wpdb;

        $clauses = [];

        foreach( $roles as $role ){
            $clauses[] = $wpdb->prepare("( `postmeta`.`meta_key` = 'bpbm-chat-auto-add' AND `postmeta`.`meta_value` LIKE %s )", '%"' . $role . '"%');
        }

        $chat_ids = $wpdb->get_col("SELECT 
        `posts`.`ID`
        FROM {$wpdb->posts} posts
        INNER JOIN {$wpdb->postmeta} postmeta 
        ON ( `posts`.`ID` = `postmeta`.`post_id` ) 
        WHERE 1=1  
        AND ( " . implode(' OR ', $clauses ) . " ) 
        AND `posts`.`post_type` = 'bpbm-chat' 
        GROUP BY `posts`.ID");

        foreach( $chat_ids as $chat_id ){
            if( ! wp_get_scheduled_event( 'better_messages_chat_room_sync_auto_add_users', [ $chat_id ] ) ){
                wp_schedule_single_event( time(), 'better_messages_chat_room_sync_auto_add_users', [ $chat_id ] );
            }
        }
    }

    public function sync_auto_add_users( $chat_id ){
        $thread_id  = $this->get_chat_thread_id( $chat_id );

        if( ! $thread_id ) return false;

        $settings = Better_Messages()->chats->get_chat_settings( $chat_id );

        if( count( $settings['auto_add'] ) === 0 ){
            return false;
        }

        set_time_limit(0);
        ignore_user_abort(true);
        ini_set('memory_limit', '-1');

        global $wpdb;

        $roles = [];
        foreach ( $settings['auto_add'] as $role ){
            $roles[] = $wpdb->prepare('%s', $role );
        }

        $users_hash = $wpdb->get_var("
        SELECT MD5(GROUP_CONCAT(DISTINCT(`user_id`))) as users_hash
        FROM  `" . bm_get_table('roles') . "` 
        WHERE role IN (". implode(',', $roles ) .")
        ORDER BY user_id ASC");

        $thread_hash = Better_Messages()->functions->get_thread_meta( $thread_id, 'auto_add_hash' );

        if( $users_hash === $thread_hash ){
            return false;
        }

        $array = [];

        $sql = $wpdb->prepare("
        SELECT `recipients`.`user_id`
        FROM " . bm_get_table('recipients') . " `recipients`
            LEFT JOIN " . $wpdb->users . " users
                ON `users`.`ID` = `recipients`.`user_id`
        WHERE `recipients`.`thread_id` = %d
        AND (  ( `recipients`.`user_id` >= 0 AND `users`.`ID` IS NOT NULL ) OR ( `recipients`.`user_id` < 0 ) )
        ", $thread_id );


        $not_added_users = $wpdb->get_col("SELECT DISTINCT(`user_id`) as user_id
        FROM  `" . bm_get_table('roles') . "` 
        WHERE role IN (". implode(',', $roles ) .")
        AND `user_id` NOT IN(" . $sql . ")
        ORDER BY user_id ASC");

        if( count( $not_added_users ) === 0 ) {
            Better_Messages()->functions->update_thread_meta( $thread_id, 'auto_add_hash', $users_hash );
            return;
        }

        foreach( $not_added_users as $index => $member ){
            if( $this->user_can_join( $member, $chat_id ) ) {
                $array[] = [
                    $member,
                    $thread_id,
                    0,
                    0,
                ];
            }
        }

        if( count($array) > 0 ) {
            $sql = "INSERT INTO " . bm_get_table('recipients') . "
            (user_id, thread_id, unread_count, is_deleted)
            VALUES ";

            $values = [];
            foreach ($array as $item) {
                $values[] = $wpdb->prepare( "(%d, %d, %d, %d)", $item );
            }

            $sql .= implode( ',', $values );

            $wpdb->query( $sql );
        }

        Better_Messages()->hooks->clean_thread_cache( $thread_id );
        Better_Messages()->functions->update_thread_meta( $thread_id, 'auto_add_hash', $users_hash );

        do_action( 'better_messages_thread_updated', $thread_id );
        do_action( 'better_messages_info_changed',   $thread_id );
    }
}

function Better_Messages_Chats()
{
    return Better_Messages_Chats::instance();
}
