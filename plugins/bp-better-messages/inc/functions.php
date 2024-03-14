<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages_Functions' ) ):

    class Better_Messages_Functions
    {
        private  $multisite_resolved = null;

        public static function instance()
        {
            static $instance = null;

            if ( null === $instance ) {
                $instance = new Better_Messages_Functions();
            }

            return $instance;
        }

        public function can_send_message_filter( $allowed, $user_id, $thread_id ){
            $legacy = apply_filters('bp_better_messages_can_send_message', $allowed, $user_id, $thread_id );
            return apply_filters('better_messages_can_send_message', $legacy, $user_id, $thread_id );
        }

        public function before_new_thread_filter( &$args, &$errors ){
            do_action_ref_array( 'bp_better_messages_before_new_thread', array( &$args, &$errors ));
            do_action_ref_array( 'better_messages_before_new_thread', array( &$args, &$errors ));
        }

        public function before_message_send_filter( &$args, &$errors ){
            do_action_ref_array( 'bp_better_messages_before_message_send', array( &$args, &$errors ));
            do_action_ref_array( 'better_messages_before_message_send', array( &$args, &$errors ));
        }

        /**
         * @param int $user_id
         * @param string $subject
         * @param string $message
         * @param bool $fast_start
         * @param bool $scrollToContainer
         * @return string
         *
         * @since 2.0.60
         */
        public function create_conversation_link( int $user_id, string $subject = '', string $message = '', bool $fast_start = false, bool $scrollToContainer = true ): string
        {
            $args = [
              'to' => $user_id
            ];

            if( ! empty($subject) ) {
                $args['subject'] = $subject;
            }

            if( ! empty($message) ) {
                $args['message'] = $message;
            }

            if( $scrollToContainer ) {
                $args['scrollToContainer'] = '';
            }

            if( $fast_start ){
                $args['bm-fast-start'] = '1';
                return add_query_arg($args, Better_Messages()->functions->get_link( Better_Messages()->functions->get_current_user_id() ));
            } else {
                return Better_Messages()->functions->add_hash_arg('new-conversation', $args, Better_Messages()->functions->get_link( Better_Messages()->functions->get_current_user_id() ));
            }
        }

        /**
         * @param int $user_id
         * @return string
         *
         * @since 2.0.60
         */
        public function private_message_link(int $user_id ): string
        {
            return $this->create_conversation_link( $user_id, '', '', Better_Messages()->settings['fastStart'] == '1' );
        }

        /**
         * @param int $thread_id
         * @param int $user_id
         * @return string
         *
         * @since 2.0.63
         */
        public function get_user_messages_url( int $user_id, int $thread_id = null ): string {
            if( $thread_id ) {
                return Better_Messages()->functions->add_hash_arg('conversation/' . $thread_id, [
                    'scrollToContainer' => ''
                ], Better_Messages()->functions->get_link($user_id));
            } else {
                return Better_Messages()->functions->add_hash_arg('', [
                    'scrollToContainer' => ''
                ], Better_Messages()->functions->get_link($user_id) );
            }
        }

        /**
         * @param $user_id
         * @param $thread_id
         * @return array|string[]
         */
        public function can_reply_in_conversation($user_id, $thread_id ): array
        {
            $can_send = $this->can_send_message_filter( Better_Messages()->functions->check_access( $thread_id ), $user_id, $thread_id );

            if( $can_send ){
                return [
                    'result' => 'allowed'
                ];
            } else {
                $_errors = [];
                global $bp_better_messages_restrict_send_message;
                if( count($bp_better_messages_restrict_send_message) > 0 ){
                    foreach( $bp_better_messages_restrict_send_message as $error ){
                        $_errors[] = $error;
                    }
                }

                return [
                    'result' => 'not_allowed',
                    'errors' => $_errors
                ];
            }
        }


        /**
         * @param $starter_id
         * @param array $recipients
         * @return array
         */
        public function can_start_conversation($starter_id, array $recipients = [], $uniqueKey = null ): array
        {
            $args = array(
                'sender_id'     => $starter_id,
                'new_thread'    => true,
                'error_type'    => 'wp_error',
                'append_thread' => false,
                'recipients'    => $recipients
            );

            if( $uniqueKey ){
                $args['unique_key'] = $uniqueKey;
                if( Better_Messages()->settings['singleThreadMode'] == '1' ) {
                    remove_action( 'better_messages_before_new_thread', array( Better_Messages_Hooks::instance(), 'disable_start_thread_if_thread_exist' ), 10, 2 );
                }
            }

            Better_Messages()->functions->before_new_thread_filter( $args, $errors );

            if( $uniqueKey ){
                if( Better_Messages()->settings['singleThreadMode'] == '1' ) {
                    add_action( 'better_messages_before_new_thread', array( Better_Messages_Hooks::instance(), 'disable_start_thread_if_thread_exist' ), 10, 2 );
                }
            }

            if( empty( $errors ) ){
                return [
                    'result' => 'new_thread'
                ];
            } else {
                $_errors = [];
                foreach( $errors as $error ){
                    $_errors[] = $error;
                }
                return [
                    'result' => 'not_allowed',
                    'errors' => $_errors
                ];
            }
        }

        public function can_erase_thread( $user_id, $thread_id ){
            $can_erase = false;

            if( user_can( $user_id, 'manage_options' ) ){
                $can_erase = true;
            }

            return apply_filters( 'bp_better_messages_can_erase_thread', $can_erase, $user_id, $thread_id );
        }

        public function can_clear_thread( $user_id, $thread_id ){
            $can_erase = false;

            if( user_can( $user_id, 'manage_options' ) ){
                $can_erase = true;
            }

            return apply_filters( 'bp_better_messages_can_clear_thread', $can_erase, $user_id, $thread_id );
        }

        public function can_invite( $user_id, $thread_id ){
            $type = $this->get_thread_type( $thread_id );
            if( $type === 'chat-room' ) return false;
            if( $type === 'group' ) return false;
            if( $user_id <= 0 ) return false;

            $participants = $this->get_participants( $thread_id );

            $is_moderator = Better_Messages()->functions->is_thread_super_moderator( $user_id, $thread_id );

            if( count($participants['recipients']) > 1 ) {
                $allow_invite = ( Better_Messages()->functions->get_thread_meta($thread_id, 'allow_invite') === 'yes' );

                if ($is_moderator || $allow_invite) {
                    return apply_filters( 'better_messages_can_invite', true, $user_id, $thread_id );
                }
            }

            if( count($participants['recipients']) === 1 ) {
                $allow_invite = (Better_Messages()->settings['privateThreadInvite'] === '1');

                if ( current_user_can('manage_options') || $is_moderator || $allow_invite ) {
                    return apply_filters( 'better_messages_can_invite', true, $user_id, $thread_id );
                }
            }

            return apply_filters( 'better_messages_can_invite', false, $user_id, $thread_id );
        }


        public function can_leave( $user_id, $thread_id ){
            $type = $this->get_thread_type( $thread_id );
            if( $type === 'chat-room' ) return false;
            if( $type === 'group' ) return false;

            $participants = $this->get_participants( $thread_id );

            $is_moderator = Better_Messages()->functions->is_thread_super_moderator( $user_id, $thread_id, false );

            if( count($participants['recipients']) >= 2 ) {
                if( Better_Messages()->settings['allowGroupLeave'] === '1' && ! $is_moderator ) {
                    return true;
                }
            } else {
                return false;
            }
        }

        public function erase_thread( $thread_id ){
            global $wpdb;

            $message_ids = $wpdb->get_col($wpdb->prepare("SELECT id FROM " . bm_get_table('messages') . " WHERE `thread_id` = %d", $thread_id));

            if( count( $message_ids ) > 0 ){
                foreach ( $message_ids as $message_id ) {
                    Better_Messages()->functions->delete_message($message_id, $thread_id, false, 'delete');
                }
            }

            Better_Messages()->hooks->clean_thread_cache( $thread_id );

            $wpdb->query($wpdb->prepare("DELETE FROM " . bm_get_table('threadsmeta') . " WHERE `bm_thread_id` = %d", $thread_id));
            $wpdb->query($wpdb->prepare("DELETE FROM " . bm_get_table('recipients') . " WHERE `thread_id` = %d", $thread_id));
            $wpdb->query($wpdb->prepare("DELETE FROM " . bm_get_table('threads') . " WHERE `id` = %d", $thread_id));

            Better_Messages()->functions->update_thread_meta( $thread_id, 'bm_last_update', Better_Messages()->functions->get_microtime() );

            do_action( 'better_messages_thread_updated', $thread_id );
            do_action( 'better_messages_thread_erased', $thread_id );
        }

        public function clear_thread( $thread_id ){
            // Can be alot of messages!
            set_time_limit(0);

            global $wpdb;

            $message_ids = $wpdb->get_col($wpdb->prepare("SELECT id FROM " . bm_get_table('messages') . " WHERE `thread_id` = %d", $thread_id));

            if( count( $message_ids ) > 0 ){
                foreach ( $message_ids as $message_id ) {
                    Better_Messages()->functions->delete_message( $message_id, $thread_id, false, 'delete' );
                }
            }

            // Updating users unread counters in local db
            $wpdb->prepare(
            "UPDATE " . bm_get_table('recipients') . " 
            SET unread_count = 0
            WHERE thread_id = %d",
            $thread_id);

            do_action( 'better_messages_thread_updated', $thread_id );
            do_action( 'better_messages_thread_cleared', $thread_id );
        }

        public function delete_message( $message_id, $thread_id = false, $process_unread = true, $deleteMethod = null ){
            global $wpdb;


            if( ! $deleteMethod ) {
                $deleteMethod = Better_Messages()->settings['deleteMethod'];
            }

            if( $process_unread ) {
                $decrease_unread = [];
                if ($thread_id === false) {
                    $message = $this->get_message($message_id);
                    if ($message) $thread_id = $message->thread_id;
                }

                if ($thread_id !== false) {
                    $recipients = $this->get_recipients($thread_id);

                    if( $deleteMethod === 'delete' ) {
                        do_action('bp_better_messages_message_deleted', $message_id, array_keys($recipients));
                    }

                    unset($recipients[Better_Messages()->functions->get_current_user_id()]);

                    foreach ($recipients as $recipient) {
                        if ($recipient->unread_count > 0) {
                            $user_unread_messages = $this->get_user_unread_messages($recipient->user_id, $thread_id, $recipient->unread_count);

                            if (in_array($message_id, $user_unread_messages)) {
                                $decrease_unread[] = $recipient->user_id;
                            }
                        }
                    }

                    do_action('better_messages_thread_updated', $thread_id);
                }
            }

            $sql = $wpdb->prepare("SELECT {$wpdb->posts}.ID
                    FROM {$wpdb->posts}
                    INNER JOIN {$wpdb->postmeta}
                    ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )
                    INNER JOIN {$wpdb->postmeta} AS mt1
                    ON ( {$wpdb->posts}.ID = mt1.post_id )
                    WHERE 1=1
                    AND ( ( {$wpdb->postmeta}.meta_key = 'bp-better-messages-attachment'
                            AND {$wpdb->postmeta}.meta_value = '1' )
                        AND ( mt1.meta_key = 'bp-better-messages-message-id'
                            AND mt1.meta_value = %d ) )
                    AND {$wpdb->posts}.post_type = 'attachment'
                    AND (({$wpdb->posts}.post_status = 'inherit'))
                    GROUP BY {$wpdb->posts}.ID
                    ORDER BY {$wpdb->posts}.post_date DESC", $message_id);

            $attachments = $wpdb->get_col( $sql );

            foreach( $attachments as $attachment_id ){
                wp_delete_attachment( $attachment_id, true );
            }

            if( $deleteMethod === 'replace' ) {
                $message = new BM_Messages_Message( $message_id );

                Better_Messages()->functions->update_message([
                    'sender_id'    => $message->sender_id,
                    'thread_id'    => $thread_id,
                    'message_id'   => $message_id,
                    'content'      => '<!-- BM-DELETED-MESSAGE -->',
                    'send_push'    => false,
                    'mobile_push'  => false,
                    'count_unread' => false
                ]);
            } else {
                $sql = $wpdb->prepare("DELETE FROM " . bm_get_table('messages') . " WHERE id = %d", $message_id);
                $wpdb->query($sql);
                $sql = $wpdb->prepare("DELETE FROM " . bm_get_table('meta') . " WHERE bm_message_id = %d", $message_id);
                $wpdb->query($sql);
            }

            if( $process_unread ) {
                if (count($decrease_unread) > 0) {
                    Better_Messages()->functions->decrease_unread($thread_id, $decrease_unread);
                }
            }

            $this->update_message_update_time( $message_id, false, true );

            return true;
        }

        public function get_user_unread_messages( $user_id, $thread_id, $unread_count = false ){
            global $wpdb;

            if( $unread_count === false ){
                $unread_count = $this->get_user_unread_for_thread( $user_id, $thread_id );
            }

            return (array) $wpdb->get_col( $wpdb->prepare("
                SELECT id
                FROM `" . bm_get_table('messages') . "`
                WHERE `thread_id` = %d 
                ORDER BY `date_sent` DESC
                LIMIT 0, %d", $thread_id, $unread_count ) );
        }

        public function get_user_unread_for_thread( $user_id, $thread_id ){
            global $wpdb;

            return $wpdb->get_var( $wpdb->prepare("
                SELECT unread_count 
                FROM `" . bm_get_table('recipients') . "`
                WHERE `user_id` = %d 
                AND `thread_id` = %d", $user_id, $thread_id ) );
        }

        public function decrease_unread( $thread_id, $user_ids = [], $decrease_by = 1 ){
            if( ! is_array( $user_ids ) ) {
                return false;
            }

            $user_ids = array_map('intval', $user_ids);

            if( count( $user_ids ) === 0 ){
                return false;
            }

            global $wpdb;

            $time = Better_Messages()->functions->get_microtime();

            $sql = $wpdb->prepare("UPDATE " . bm_get_table('recipients') . " 
                SET unread_count = GREATEST(unread_count - %d, 0), 
                last_update = %d
                WHERE `thread_id` = %d
                AND `user_id` IN (" . implode(',', $user_ids ) . ")
                ", $decrease_by, $time, $thread_id );

            $wpdb->query( $sql );

            return true;
        }

        public function is_thread_super_moderator($user_id, $thread_id, $include_admin = true ){
            if( $include_admin && user_can( $user_id, 'manage_options') ) {
                return true;
            }

            $type = Better_Messages()->functions->get_thread_type( $thread_id );

            if( $type === 'group' ) {
                if (function_exists('bp_get_user_groups')) {
                    $group_id = Better_Messages()->functions->get_thread_meta($thread_id, 'group_id');
                    if (!!$group_id) {
                        $user_groups = bp_get_user_groups($user_id, array(
                            'is_admin' => null,
                            'is_mod' => null,
                        ));

                        if (isset($user_groups[$group_id])) {
                            if ($user_groups[$group_id]->is_admin || $user_groups[$group_id]->is_mod) {
                                return true;
                            }
                        }

                        return false;
                    }
                }

                if (class_exists('PeepSoGroupsPlugin')) {
                    $group_id = Better_Messages()->functions->get_thread_meta($thread_id, 'peepso_group_id');
                    if (!!$group_id) {
                        return Better_Messages_Peepso_Groups::instance()->user_can_moderate( $group_id, $user_id );
                    }
                }

                if ( class_exists('UM_Groups')) {
                    $group_id = Better_Messages()->functions->get_thread_meta($thread_id, 'um_group_id');
                    if (!!$group_id) {
                        return Better_Messages_Ultimate_Member_Groups::instance()->user_can_moderate( $group_id, $user_id );
                    }
                }
            }

            if( $type === 'thread' ) {
                $participants = Better_Messages()->functions->get_participants( $thread_id );

                if( $participants['count'] > 2 ){
                    global $wpdb;

                    $admin_user = (int) $wpdb->get_var( $wpdb->prepare("
                        SELECT sender_id 
                        FROM `" . bm_get_table('messages') . "` 
                        WHERE `thread_id` = %d 
                        AND   `sender_id` != '0'
                        ORDER BY `" . bm_get_table('messages') . "`.`date_sent` ASC
                        LIMIT 0,1
                    ", $thread_id ));

                    if( intval($user_id) === $admin_user){
                        return true;
                    }
                }
            }

            return false;
        }

        public function is_thread_participant( $user_id, $thread_id, $include_deleted = false ){
            global $wpdb;

            if( ! $include_deleted ) {
                $userIsParticipant = (bool)$wpdb->get_var($wpdb->prepare("
                    SELECT COUNT(*) FROM `" . bm_get_table('recipients') . "` 
                    WHERE `user_id` = %d 
                    AND `thread_id` = %d 
                    AND `is_deleted` = '0'
                ", $user_id, $thread_id));
            } else {
                $userIsParticipant = (bool)$wpdb->get_var($wpdb->prepare("
                    SELECT COUNT(*) FROM `" . bm_get_table('recipients') . "` 
                    WHERE `user_id` = %d 
                    AND `thread_id` = %d 
                ", $user_id, $thread_id));
            }

            if( $userIsParticipant ){
                return true;
            }

            return false;
        }

        public function get_thread_subject($thread_id){
            global $wpdb;

            $subject = $wpdb->get_var( $wpdb->prepare( "
                SELECT subject 
                FROM `" . bm_get_table('threads') . "` 
                WHERE `id` = %d 
                LIMIT 0, 1
            ", $thread_id ) );

            return $this->clean_no_subject( wp_unslash( esc_attr( $subject )) );
        }

        public function change_thread_subject($thread_id, $new_subject){
            global $wpdb;

            $wpdb->update(
                bm_get_table('threads'),
                array( 'subject' => $new_subject ),
                array( 'id' => $thread_id ), array( '%s' ), array( '%d' )
            );

            do_action( 'better_messages_thread_updated', $thread_id );
            do_action( 'better_messages_info_changed', $thread_id );

            return wp_unslash( esc_attr( $new_subject ) );
        }


        /**
         * @param $user_id
         * @param $exclude_threads
         * @return array
         */
        public function get_threads($user_id = false )
        {
            if( $user_id === false ) $user_id = Better_Messages()->functions->get_current_user_id();

            $data = Better_Messages()->api->get_threads( [], false, false, true, true, $user_id );

            if( $data['threads'] ) {
                return $data['threads'];
            }

            return [];
        }

        public function get_thread_message_count($thread_id){
            global $wpdb;

            return $wpdb->get_var( $wpdb->prepare( "
            SELECT COUNT(*)
            FROM  " . bm_get_table('messages') . "
            WHERE `thread_id` = %d
            ", $thread_id ) );
        }

        public function get_message( $message_id ){
            global $wpdb;

            return $wpdb->get_row( $wpdb->prepare( "
            SELECT *
            FROM  " . bm_get_table('messages') . "
            WHERE `id` = %d
            ", $message_id ) );
        }

        public function get_messages( $thread_id, $message = false, $action = 'last_messages', $count = 50 ){
            global $wpdb;

            switch ($action){
                case 'last_messages':
                    $query = $wpdb->prepare( "
                    SELECT id, thread_id, sender_id, message, date_sent
                    FROM  " . bm_get_table('messages') . "
                    WHERE `thread_id` = %d
                    ORDER BY `date_sent` DESC
                    LIMIT 0, %d
                    ", $thread_id, $count );
                    break;
                case 'from_message':
                    $query = $wpdb->prepare( "
                    SELECT id, thread_id, sender_id, message, date_sent
                    FROM  " . bm_get_table('messages') . "
                    WHERE `thread_id` = %d
                    AND   `id` <= %d
                    ORDER BY `date_sent` DESC
                    LIMIT 0, %d
                    ", $thread_id, $message, $count );
                    break;
                case 'to_message':
                    $query = $wpdb->prepare( "
                    SELECT id, thread_id, sender_id, message, date_sent
                    FROM  " . bm_get_table('messages') . "
                    WHERE `thread_id` = %d
                    AND   `id` >= %d
                    ORDER BY `date_sent` DESC
                    ", $thread_id, $message );
                    break;
            }

            $messages = $wpdb->get_results( $query );

            return $messages;
        }

        public function is_message_starred( $message_id, $user_id ){
            if( Better_Messages()->settings['disableFavoriteMessages'] === '0' ) {
                $starred = array_flip( (array) $this->get_message_meta( $message_id, 'starred_by_user', false ) );
                return isset( $starred[ $user_id ] );
            } else {
                return false;
            }
        }

        public function get_recipients( $thread_id = 0, $cache = true ) {
            global $wpdb;

            $thread_id = (int) $thread_id;

            $recipients = false;
            if( $cache ){
                $recipients = wp_cache_get( 'bm_thread_recipients_' . $thread_id, 'bm_messages' );
            }

            if ( false === $recipients ) {
                $recipients = array();
                $sql = $wpdb->prepare("
                SELECT `recipients`.*
                FROM " . bm_get_table('recipients') . " `recipients`
                LEFT JOIN " . $wpdb->users . " users
                    ON `users`.`ID` = `recipients`.`user_id`
                WHERE `recipients`.`thread_id` = %d
                AND (  ( `recipients`.`user_id` >= 0 AND `users`.`ID` IS NOT NULL ) OR ( `recipients`.`user_id` < 0 ) )", $thread_id );

                $results    = $wpdb->get_results( $sql );

                foreach ( (array) $results as $recipient ) {
                    $recipients[ $recipient->user_id ] = $recipient;
                }

                wp_cache_set( 'bm_thread_recipients_' . $thread_id, $recipients, 'bm_messages' );
            }

            // Cast all items from the messages DB table as integers.
            foreach ( (array) $recipients as $key => $data ) {
                $recipients[ $key ] = (object) array_map( 'intval', (array) $data );
            }

            /**
             * Filters the recipients of a message thread.
             *
             * @param array $recipients Array of recipient objects.
             * @param int   $thread_id  ID of the current thread.
             */
            return apply_filters( 'bm_messages_thread_get_recipients', $recipients, $thread_id );
        }


        /**
         * Get all thread user ids including currently logged-in user
         *
         * @param int $thread_id
         * @param boolean $cache
         * @return array
         *
         * @since 2.0.55
         */
        public function get_recipients_ids( int $thread_id, bool $cache = true ): array
        {
            $recipients = $this->get_recipients( $thread_id, $cache );

            if( count ( $recipients ) === 0 ) return [];

            $users_ids = [];
            foreach ( $recipients as $user_id => $_user ){
                $users_ids[] = intval( $user_id );
            }

            return array_unique( $users_ids );
        }

        public function get_participants($thread_id )
        {
            $current_user_id = Better_Messages()->functions->get_current_user_id();
            $recipients = $this->get_recipients( $thread_id );

            $users = [];
            foreach ( $recipients as $user_id => $_user ){
                $users[ $user_id ] = $user_id;
            }

            $participants = array(
                'recipients' => $users,
                'count'      => count($recipients)
            );

            if( isset( $participants['recipients'][$current_user_id]) ) {
                unset($participants['recipients'][$current_user_id]);
            }

            return $participants;
        }

        public function get_displayed_user_id(){
            $current_user_id = Better_Messages()->functions->get_current_user_id();

            if( doing_action('wp_ajax_buddyboss_theme_get_header_unread_messages') ){
                $user_id = $current_user_id;
            }

            if ( ! isset( $user_id ) || $user_id == false ) {
                $user_id = bp_displayed_user_id();
            }

            if ( ! isset( $user_id ) || $user_id == false ) {
                $user_id = $current_user_id;
            }

            return $user_id;
        }

        public function get_link( $user_id = false )
        {
            if( ! is_user_logged_in() && ! wp_doing_cron() ) {
                if( ! is_numeric( Better_Messages()->settings['chatPage'] ) || Better_Messages()->settings['chatPage'] === '0' ) {
                    return apply_filters( 'better_messages_login_url', wp_login_url( add_query_arg([]) ) );
                }
            }

            $current_user_id = $this->get_displayed_user_id();

            $slug = Better_Messages()->settings['bpProfileSlug'];

            if ( $user_id == false ) {
                $user_id = $current_user_id;
            }

            $url_overwritten = apply_filters( 'bp_better_messages_page', null, $user_id );

            if( $url_overwritten !== null ){
                return $url_overwritten;
            }

            if( is_user_logged_in() || wp_doing_cron() ) {
                if (class_exists('AsgarosForum') && Better_Messages()->settings['chatPage'] === 'asgaros-forum') {
                    global $asgarosforum;
                    $link = $asgarosforum->get_link('profile', $user_id) . 'messages/';
                    return $link;
                }

                if (class_exists('WooCommerce') && Better_Messages()->settings['chatPage'] === 'woocommerce') {
                    $link = trailingslashit(get_permalink(get_option('woocommerce_myaccount_page_id'))) . $slug . '/';
                    return $link;
                }
            }

            if( Better_Messages()->settings['chatPage'] !== '0' ){
                return get_permalink( Better_Messages()->settings['chatPage'] );
            }

            if( is_user_logged_in() || wp_doing_cron() ) {
                if (class_exists('BuddyPress') && $user_id !== $current_user_id) {
                    return $this->bp_core_get_user_domain($user_id) . $slug . '/';
                }

                if ( class_exists('BuddyPress') ) {
                    return $this->bp_core_get_user_domain($user_id) . $slug . '/';
                }
            }

            return '';
        }

        public function bp_core_get_user_domain( $user_id )
        {
            if( function_exists('bp_members_get_user_url') ){
                return trailingslashit( bp_members_get_user_url($user_id) );
            } else {
                return trailingslashit( bp_core_get_user_domain($user_id) );
            }
        }

        public function get_starred_count()
        {
            global $wpdb;
            $user_id = Better_Messages()->functions->get_current_user_id();

            return $wpdb->get_var( "
                SELECT
                  COUNT(" . bm_get_table('messages') . ".id) AS count
                FROM " . bm_get_table('meta') . "
                  INNER JOIN " . bm_get_table('messages') . "
                    ON " . bm_get_table('meta') . ".bm_message_id = " . bm_get_table('messages') . ".id
                  INNER JOIN " . bm_get_table('recipients') . "
                    ON " . bm_get_table('recipients') . ".thread_id = " . bm_get_table('messages') . ".thread_id
                WHERE " . bm_get_table('meta') . ".meta_key = 'starred_by_user'
                AND " . bm_get_table('meta') . ".meta_value = $user_id
                AND " . bm_get_table('recipients') . ".is_deleted = 0
                AND " . bm_get_table('recipients') . ".user_id = $user_id
            " );
        }

        public function get_starred_stacks()
        {
            global $wpdb;

            $user_id = Better_Messages()->functions->get_current_user_id();

            $query = $wpdb->prepare( "
                SELECT
                  " . bm_get_table('messages') . ".*
                FROM " . bm_get_table('meta') . "
                  INNER JOIN " . bm_get_table('messages') . "
                    ON " . bm_get_table('meta') . ".bm_message_id = " . bm_get_table('messages') . ".id
                  INNER JOIN " . bm_get_table('recipients') . "
                    ON " . bm_get_table('recipients') . ".thread_id = " . bm_get_table('messages') . ".thread_id
                WHERE " . bm_get_table('meta') . ".meta_key = 'starred_by_user'
                AND " . bm_get_table('meta') . ".meta_value = %d
                AND " . bm_get_table('recipients') . ".is_deleted = 0
                AND " . bm_get_table('recipients') . ".user_id = %d
            ", $user_id, $user_id );

            $messages = $wpdb->get_results( $query );

            $stacks = array();

            $lastUser = 0;
            foreach ( $messages as $index => $message ) {
                if ( $message->sender_id != $lastUser ) {
                    $lastUser = $message->sender_id;

                    $stacks[] = array(
                        'id'        => $message->id,
                        'user_id'   => $message->sender_id,
                        'user'      => get_userdata( $message->sender_id ),
                        'thread_id' => $message->thread_id,
                        'messages'  => array(
                            array(
                                'id'        => $message->id,
                                'message'   => self::format_message( $message->message, $message->id, 'stack', $user_id ),
                                'date'      => $message->date_sent,
                                'timestamp' => strtotime( $message->date_sent ),
                                'stared'    => $this->is_message_starred( $message->id, Better_Messages()->functions->get_current_user_id() )
                            )
                        )
                    );
                } else {
                    $stacks[ count( $stacks ) - 1 ][ 'messages' ][] = array(
                        'id'        => $message->id,
                        'message'   => self::format_message( $message->message, $message->id, 'stack', $user_id ),
                        'date'      => $message->date_sent,
                        'timestamp' => strtotime( $message->date_sent ),
                        'stared'    => $this->is_message_starred( $message->id, Better_Messages()->functions->get_current_user_id() )
                    );
                }
            }

            return $stacks;
        }

        public function get_search_stacks( $search = '' )
        {
            global $wpdb;

            if( empty( trim($search) ) ) return array();

            $user_id = Better_Messages()->functions->get_current_user_id();

            $searchTerm = '%' . sanitize_text_field($search) . '%';

            $query = $wpdb->prepare( "
                SELECT " . bm_get_table('messages') . ".*
                FROM " . bm_get_table('messages') . "
                INNER JOIN " . bm_get_table('recipients') . "
                ON " . bm_get_table('recipients') . ".thread_id = " . bm_get_table('messages') . ".thread_id
                WHERE
                " . bm_get_table('recipients') . ".is_deleted = 0 
                AND " . bm_get_table('recipients') . ".user_id = %d
                AND " . bm_get_table('messages') . ".message LIKE %s
            ", $user_id, $searchTerm );

            $messages = $wpdb->get_results( $query );

            $stacks = array();

            $lastUser = 0;
            foreach ( $messages as $index => $message ) {
                if ( $message->sender_id != $lastUser ) {
                    $lastUser = $message->sender_id;

                    $stacks[] = array(
                        'id'        => $message->id,
                        'user_id'   => $message->sender_id,
                        'user'      => get_userdata( $message->sender_id ),
                        'thread_id' => $message->thread_id,
                        'messages'  => array(
                            array(
                                'id'        => $message->id,
                                'message'   => self::format_message( $message->message, $message->id, 'stack', $user_id ),
                                'date'      => $message->date_sent,
                                'timestamp' => strtotime( $message->date_sent ),
                                'stared'    => $this->is_message_starred( $message->id, Better_Messages()->functions->get_current_user_id() )
                            )
                        )
                    );
                } else {
                    $stacks[ count( $stacks ) - 1 ][ 'messages' ][] = array(
                        'id'        => $message->id,
                        'message'   => self::format_message( $message->message, $message->id, 'stack', $user_id ),
                        'date'      => $message->date_sent,
                        'timestamp' => strtotime( $message->date_sent ),
                        'stared'    => $this->is_message_starred( $message->id, Better_Messages()->functions->get_current_user_id() )
                    );
                }
            }

            return $stacks;
        }

        public function get_formatted_time( $timestamp ){
            $gmt_offset = get_option('gmt_offset') * 3600;
            $time = $timestamp + $gmt_offset;
            $time_format = get_option( 'time_format' );
            if ( gmdate( 'Ymd' ) != gmdate( 'Ymd', $time ) ) {
                $time_format .= ' ' . get_option( 'date_format' );
            }

            $time = wp_strip_all_tags( stripslashes( date_i18n( $time_format, $time ) ) );

            return $time;
        }

        public function format_message( $message = '', $message_id = 0, $context = 'stack', $user_id = false )
        {
            global $processedUrls;

            if ( !isset( $processedUrls ) ) $processedUrls = array();

            $message = apply_filters( 'bp_better_messages_pre_format_message', $message, $message_id, $context, $user_id );

            // Removing slashes
            $message = wp_unslash( $message );

            if ( $context == 'site' ) {
                $message = $this->truncate( $message, 100 );
            } else {
                // New line to html <br>
                $message = nl2br( $message );
            }

            #$message = str_replace( ['[', ']'], ['&#91;', '&#93;'], $message );

            $message = apply_filters( 'bp_better_messages_after_format_message', $message, $message_id, $context, $user_id );

            if ( isset( $processedUrls[ $message_id ] ) && !empty( $processedUrls[ $message_id ] ) ) {
                foreach ( $processedUrls[ $message_id ] as $index => $link ) {

                    $message = str_replace( '%%link_' . ( $index + 1 ) . '%%', $link, $message );
                }
            }

            return $this->clean_string( $message );
        }

        public function filter_message_content( $content ){
            $overwrite = apply_filters('better_messages_filter_message_content_overwrite', '', $content );

            if( $overwrite !== '' ) {
                return $overwrite;
            }

            $content = str_replace(['<br/>', '<br />'], '<br>', $content);

            $allowed_tags = [
                'p', 'b', 'i', 'u', 'strong', 'br', 'strike', 'sub', 'sup', 'span'
            ];

            $content = $this->strip_all_tags( $content, $allowed_tags );


            $allowed_tags = [
                'p', 'b', 'i', 'u', 'strong', 'br', 'strike', 'sub', 'sup'
            ];

            if (substr($content, 0, strlen('<p>')) == '<p>') {
                $content = substr($content, strlen('<p>'));
            }

            if (substr($content, 0 - strlen('</p>') ) == '</p>') {
                $content = substr($content, 0, 0 - strlen('</p>'));
            }

            $content = str_replace(array(' style=""', ' style=\"\"'), '', $content);
            $content = esc_textarea( str_replace('<br>', "\n", $content) );

            foreach( $allowed_tags as $tag ){
                $content = str_replace("&lt;".$tag."&gt;", "<".$tag.">",  $content);
                $content = str_replace("&lt;/".$tag."&gt;", "</".$tag.">", $content);
            }


            $content = trim(str_replace(array("&nbsp;", '&amp;nbsp;'), " ", $content));

            return $content;
        }


        function truncate( $text, $length ) {
            $is_sticker  = strpos( $text, '<span class="bpbm-sticker">', 0 ) === 0;
            $file_icon   = strpos( $text, '<i class="fas fa-file">' );
            $is_file     = $file_icon !== false;
            $bottom_html = false;

            if( $is_file ){
                $bottom_html = substr($text, $file_icon, strlen( $text ) - $file_icon );
                $text = substr($text, 0, $file_icon );
            }

            if( ! $is_sticker && ! $is_file ) {
                $text = strip_tags($text);
            }

            $length = abs( (int) $length );

            if(strlen($text) > $length) {
                $text = preg_replace("/^(.{1,$length})(\s.*|$)/s", '\\1...', $text);
            }

            if( $bottom_html !== false ) {
                if( strlen(trim($text)) > 0 ) {
                    $text .= "<br><br>";
                }
                $text .= $bottom_html;
            }

            return($text);
        }

        public function get_thread_count( $thread_id, $user_id )
        {
            global $wpdb, $bp;

            return $wpdb->get_var( $wpdb->prepare( "
            SELECT unread_count 
            FROM   " . bm_get_table('recipients') . "
            WHERE  `thread_id` = %d
            AND    `user_id`   = %d
            ", $thread_id, $user_id ) );
        }

        public function get_name($user_id){
            $user = get_userdata($user_id);

            if ( is_object( $user ) ) {
                $name = (!empty($user->fullname)) ? $user->fullname : $user->display_name;
            } else {
                $name    = __('Deleted User', 'bp-better-messages');
                $user_id = 0;
            }

            return apply_filters( 'bp_better_messages_display_name', $name, $user_id );
        }


        public function get_rest_avatar($user_id){
            $user = Better_Messages()->functions->rest_user_item( $user_id );

            if( ! $user || ! $user['avatar'] ){
                return Better_Messages()->url . 'assets/images/avatar.png';
            }

            return $user['avatar'];
        }

        public function get_avatar($user_id, $size, $args = array()){
            if( $size === 0 ) return '';

            if( ! Better_Messages()->functions->show_avatars() ) {
                return Better_Messages()->url . 'assets/images/avatar.png';
            }

            $user = get_userdata($user_id);

            if ( is_object( $user ) ) {
                $fullname = (!empty($user->fullname)) ? $user->fullname : $user->display_name;
            } else {
                $fullname = __('Deleted User', 'bp-better-messages');
            }

            $_user_id = ( is_object( $user ) ) ? $user->ID : 0;

            $defaults = array(
                'type'   => 'full',
                'width'  => $size,
                'height' => $size,
                'class'  => 'avatar',
                'html'   => true,
                'id'     => false,
                'alt'    => sprintf( __( 'Profile picture of %s', 'bp-better-messages' ), $fullname )
            );

            $r = wp_parse_args( $args, $defaults );
            $r['class'] .= ' bpbm-avatar-user-id-' . $_user_id;

            extract( $r, EXTR_SKIP );

            $email = ( is_object( $user ) ) ? $user->user_email : '';

            $extra_attr = apply_filters('bp_better_messages_avatar_extra_attr', ' data-size="' . $size . '" data-user-id="' . $_user_id . '"', $_user_id, $size );

            $avatar = apply_filters( 'bp_get_member_avatar',
                bp_core_fetch_avatar(
                    array(
                        'item_id' => $_user_id,
                        'type' => $type,
                        'alt' => $alt,
                        'css_id' => $id,
                        'class' => $class,
                        'width' => $width,
                        'height' => $height,
                        'email' => $email,
                        'html'  => $html,
                        'extra_attr' => $extra_attr
                    )
            ), $r );

            if( strpos($avatar, '//', 0) === 0 ){
                $avatar = 'https://' . substr( $avatar, 2 );
            }

            return str_replace('src="//', 'src="https://', $avatar);
        }


        /**
         * @param int $thread_id
         * @param int $user_id
         * @return bool
         *
         * @since 2.0.55
         */
        public function is_user_participant(int $thread_id, int $user_id ): bool
        {
            if( ! $this->is_conversation_exists( $thread_id ) || ! $this->is_user_exists( $user_id ) ){
                return false;
            }

            global $wpdb;

            return (bool) $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(*) FROM `" . bm_get_table('recipients') . "` 
            WHERE `user_id` = %d 
            AND `thread_id` = %d
            ", $user_id, $thread_id));
        }

        /**
         * Add user to specific conversation
         *
         * @param int $thread_id
         * @param int $user_id
         * @return bool
         */
        public function add_participant_to_thread( int $thread_id, int $user_id ): bool
        {
            if( ! $this->is_conversation_exists( $thread_id ) || ! $this->is_user_exists( $user_id ) ){
                return false;
            }

            global $wpdb;

            $userIsParticipant = $this->is_user_participant( $thread_id, $user_id );

            if( ! $userIsParticipant ) {
                $return = $wpdb->insert(
                    bm_get_table('recipients'),
                    array(
                        'user_id' => $user_id,
                        'thread_id' => $thread_id,
                        'unread_count' => 0,
                        'is_deleted' => 0
                    )
                );

                if( $return ) {
                    do_action('better_messages_thread_updated', $thread_id);
                    do_action('better_messages_info_changed', $thread_id, [ $user_id ] );
                    return true;
                }
            }

            return false;
        }

        /**
         * @param int $thread_id
         * @param int $user_id
         * @return bool
         */
        public function remove_participant_from_thread(int $thread_id, int $user_id ): bool
        {
            if( ! $this->is_conversation_exists( $thread_id ) || ! $this->is_user_exists( $user_id ) ){
                return false;
            }

            $userIsParticipant = $this->is_user_participant( $thread_id, $user_id );
            if( ! $userIsParticipant ) return false;

            global $wpdb;

            $result = $wpdb->delete(
                bm_get_table('recipients'),
                array(
                    'user_id' => $user_id,
                    'thread_id' => $thread_id
                ),
                array( '%d', '%d' )
            );

            if( $result ) {
                do_action('better_messages_thread_updated', $thread_id);
                do_action('better_messages_info_changed', $thread_id);
                do_action('better_messages_participant_removed', $thread_id, $user_id );
                return true;
            }

            return false;
        }

        public function find_existing_threads( $from, $to, $exclude_deleted = null ){
            global $wpdb;

            $query_exclude = "SELECT 
            group_concat(meta_value) as thread_id 
            FROM `{$wpdb->postmeta}` as `postmeta`
            RIGHT JOIN `{$wpdb->posts}` as `posts`
            ON `posts`.`ID` = `postmeta`.`post_id`
            WHERE `posts`.`post_type` = 'bpbm-bulk-report'
            AND `postmeta`.`meta_key` = 'thread_ids'
            AND `postmeta`.`meta_value` REGEXP '^[0-9]+$'";

            $threads_excluded = rtrim($wpdb->get_var($query_exclude), ',');

            if (empty($threads_excluded)) {
                $threads_excluded = '0';
            }

            if( $exclude_deleted === null ){
                $exclude_deleted = Better_Messages()->settings['deletedBehaviour'] !== 'include';
            }

            $exclude_deleted_sql = '';
            if( $exclude_deleted ) $exclude_deleted_sql = 'AND recipients.is_deleted = 0';

            $query_from = $wpdb->prepare("SELECT
                recipients.thread_id
                FROM " . bm_get_table('recipients') . " as recipients
                INNER JOIN " . bm_get_table('threads') . " as threads
                ON recipients.thread_id = threads.id
                LEFT JOIN " . bm_get_table('threadsmeta') . " threadsmeta ON
                    ( threadsmeta.`bm_thread_id` = threads.`id`
                    AND threadsmeta.meta_key = 'unique_tag' )
            WHERE recipients.user_id = %d
            {$exclude_deleted_sql}
            AND threads.type = 'thread'
            AND `threadsmeta`.`meta_value` IS NULL
            AND threads.id NOT IN (" . $threads_excluded . ")", $from);

            $query_to = $wpdb->prepare("SELECT
                recipients.thread_id
                FROM " . bm_get_table('recipients') . " as recipients
                INNER JOIN " . bm_get_table('threads') . " as threads
                ON recipients.thread_id = threads.id
                LEFT JOIN " . bm_get_table('threadsmeta') . " threadsmeta ON
                    ( threadsmeta.`bm_thread_id` = threads.`id`
                    AND threadsmeta.meta_key = 'unique_tag' )
            WHERE recipients.user_id = %d 
            {$exclude_deleted_sql}
            AND threads.type = 'thread'
            AND `threadsmeta`.`meta_value` IS NULL
            AND threads.id NOT IN (" . $threads_excluded . ")", $to);

            $threads_from = $wpdb->get_col($query_from);
            $threads_to = $wpdb->get_col($query_to);

            $threads_between_users = [];
            foreach ( $threads_from as $thread_id ){
                if( in_array( $thread_id, $threads_to )){
                    $threads_between_users[] = intval($thread_id);
                }
            }


            $thread_ids = [];
            if( count( $threads_between_users ) > 0 ) {
                $threads_in = '("' . implode('","', $threads_between_users) . '")';
                $query = "SELECT thread_id, COUNT(*) as count
                FROM " . bm_get_table('recipients') . "
                WHERE " . bm_get_table('recipients') . ".thread_id IN {$threads_in}
                GROUP BY thread_id
                HAVING count = 2";

                $threads = $wpdb->get_results($query);

                if( count($threads) > 0 ){
                    foreach ( $threads as $thread ){
                        $thread_ids[] = intval( $thread->thread_id );
                    }

                    if( ! $exclude_deleted ){
                        // If deleted included, need to sort not deleted to be first
                        $sql = $wpdb->prepare("SELECT thread_id
                        FROM " . bm_get_table('recipients') . " as recipients
                        WHERE recipients.thread_id IN (" . implode( ',', $thread_ids ) . ")
                        AND user_id = %d
                        ORDER BY is_deleted ASC", $from);

                        $thread_ids = $wpdb->get_col( $sql );
                    }
                }

            }

            return $thread_ids;
        }

        /**
         * @param int $to
         * @param int|null $from
         * @param bool $create
         * @param string $subject
         * @return array
         *
         * @since 2.0.64
         */
        public function get_private_conversation_id(int $to, int $from = null, bool $create = true, string $subject = '', $uniqueKey = null): array{
            if( ! Better_Messages()->functions->is_user_authorized() ) {
                return [
                    'result' => 'not_allowed',
                    'errors' => [ _x('Error while creating new conversation', 'Rest API Error', 'bp-better-messages') ]
                ];
            }

            if( ! Better_Messages()->functions->is_user_exists( $to ) ) {
                return [
                    'result' => 'not_allowed',
                    'errors' => [ _x('User does not exists', 'Rest API Error', 'bp-better-messages') ]
                ];
            }

            if( ! $from ) $from = Better_Messages()->functions->get_current_user_id();

            if( ! $uniqueKey ){
                $existing_threads = $this->find_existing_threads( $from, $to );

                if( count( $existing_threads ) > 0 ){
                    return [
                        'result'    => 'thread_found',
                        'thread_id' => (int) $existing_threads[0]
                    ];
                }
            }

            $can_start = $this->can_start_conversation( $from, [ $to ], $uniqueKey );

            if( $can_start['result'] === 'new_thread' && $create ){
                if( ! $uniqueKey ){
                    $new_thread_id = $this->create_new_conversation( [ $from, $to ], $subject );
                } else {
                    $new_thread_id = $this->get_unique_conversation_id( [ $from, $to ], $uniqueKey, $subject );
                }

                if( ! $new_thread_id ){
                    return [
                        'result' => 'not_allowed',
                        'errors' => [ _x('Error while creating new conversation', 'Rest API Error', 'bp-better-messages') ]
                    ];
                }

                return [
                    'result'    => 'thread_created',
                    'thread_id' => (int) $new_thread_id
                ];
            }

            return $can_start;
        }

        /**
         * @param int $to
         * @param int|null $from
         * @param bool $create
         * @return array
         */
        public function get_pm_thread_id( int $to, int $from = null, bool $create = true, string $subject = '' ): array
        {
            return $this->get_private_conversation_id( $to, $from, $create, $subject );
        }


        public function get_unique_pm_thread_id( string $uniqueKey, int $to, int $from = null, bool $create = true, string $subject = '' ): array
        {
            return $this->get_private_conversation_id( $to, $from, $create, $subject, $uniqueKey );
        }


        /**
         * Create new empty conversation
         *
         * @param array $user_ids
         * @param string $subject
         * @return int
         *
         * @since 2.0.55
         */
        public function create_new_conversation( array $user_ids, string $subject = '' ){
            if( ! is_array( $user_ids ) || count( $user_ids ) === 0 ){
                return false;
            }

            global $wpdb;

            if( ! $wpdb->insert( bm_get_table('threads'), [ 'type' => 'thread', 'subject' => trim($subject) ], [ '%s', '%s' ] ) ){
                return false;
            }

            $new_thread_id = (int) $wpdb->insert_id;

            $wpdb->query($wpdb->prepare("DELETE FROM " . bm_get_table('threadsmeta') . " WHERE `bm_thread_id` = %d", $new_thread_id));
            $wpdb->query($wpdb->prepare("DELETE FROM " . bm_get_table('recipients') . " WHERE `thread_id` = %d", $new_thread_id));

            foreach( $user_ids as $user_id ) {
                $wpdb->insert(bm_get_table('recipients'), [
                    'user_id'   =>  $user_id,
                    'thread_id' => $new_thread_id
                ], ['%d', '%d'] );
            }

            do_action( 'bp_better_messages_new_thread_created', $new_thread_id, null );

            return $new_thread_id;
        }

        /**
         * Get existing or creating new unique conversation based on unique key and user ids
         *
         * @param array  $user_ids
         * @param string $unique_key
         * @param string $subject
         * @return int
         *
         * @since 2.0.67
         */
        public function get_unique_conversation_id( array $user_ids, string $unique_key, string $subject = '' ){
            if( ! is_array( $user_ids ) || count( $user_ids ) === 0 ){
                return false;
            }

            global $wpdb;

            $user_ids = array_unique( $user_ids );

            $users_tag  = '|' . implode( '|', $user_ids) . '|';

            $unique_tag = $unique_key . $users_tag;

            $where = [];

            $args = [
                $unique_key . '|%'
            ];

            foreach( $user_ids as $user_id ){
                $where[] = "AND `meta_value` LIKE '%s'";
                $args[] = '%|' . intval( $user_id ) . '|%';
            }

            $thread_id = (int) $wpdb->get_var($wpdb->prepare("
            SELECT thread_meta.bm_thread_id
                FROM " . bm_get_table('threadsmeta') . " thread_meta
            WHERE `meta_key` = 'unique_tag'
            AND `meta_value` LIKE %s
            " . implode(' ', $where) . "
            LIMIT 0, 1", $args) );

            if( $thread_id ){
                $thread = $this->get_thread( $thread_id );

                if( $thread ) {
                    return $thread_id;
                }
            }

            $thread_id = $this->create_new_conversation( $user_ids, $subject );

            $this->update_thread_meta( $thread_id, 'unique_tag', $unique_tag );

            return $thread_id;
        }

        public function get_member_id(){
            $user_id = apply_filters('better_messages_get_member_id', null );

            if( $user_id ){
                return $user_id;
            }

            if( function_exists('bp_get_member_user_id') ) {
                $loop_user_id = bp_get_member_user_id();
                if (!!$loop_user_id) return $loop_user_id;
            }

            $displayed_user_id = bp_displayed_user_id();

            if( !! $displayed_user_id ) return $displayed_user_id;

            if( is_singular() ){
                $author_id = get_the_author_meta('ID');
                if( !! $author_id ) return $author_id;
            }

            return false;
        }

        /**
         * Check if conversation exists
         * @param int $thread_id
         * @return bool
         */
        public function is_conversation_exists(int $thread_id ) : bool {
            $thread = $this->get_thread( $thread_id );

            if( $thread ){
                return true;
            }

            return false;
        }

        /**
         * Check if user exists
         * @param int $user_id
         * @return bool
         */
        public function is_user_exists(int $user_id ) : bool {
            if( $user_id >= 0 ) {
                $user = get_userdata($user_id);
                if ($user) {
                    return true;
                }
            } else {
                $guest_exists = Better_Messages()->guests->is_guest_exists( $user_id );
                if( $guest_exists ){
                    return true;
                }
            }

            return false;
        }


        public function add_user_to_thread(int $thread_id, int $user_id ) : bool{
            return $this->add_participant_to_thread( $thread_id, $user_id );
        }

        public function clean_string( $string )
        {
            $string = str_replace( PHP_EOL, ' ', $string );
            $string = preg_replace( '/[\r\n]+/', "\n", $string );
            $string = preg_replace( '/[ \t]+/', ' ', $string );
            //$string = preg_replace( '<br>', '', $string );

            return trim($string);
        }

        public function clean_site_url( $url )
        {
            $url = strtolower( $url );

            $url = str_replace( '://www.', '://', $url );

            $url = str_replace( array( 'http://', 'https://' ), '', $url );

            return sanitize_text_field( $url );
        }

        public function hex2rgba($color, $opacity = false) {

            $default = 'rgb(0,0,0)';

            //Return default if no color provided
            if(empty($color))
                return $default;

            //Sanitize $color if "#" is provided
            if ($color[0] == '#' ) {
                $color = substr( $color, 1 );
            }

            //Check if color has 6 or 3 characters and get values
            if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
            } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
            } else {
                return $default;
            }

            //Convert hexadec to rgb
            $rgb =  array_map('hexdec', $hex);

            //Check if opacity is set(rgba or rgb)
            if($opacity){
                if(abs($opacity) > 1)
                    $opacity = 1.0;
                $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
            } else {
                $output = 'rgb('.implode(",",$rgb).')';
            }

            //Return rgb(a) color string
            return $output;
        }

        public function get_undeleted_recipients($thread_id){
            $recipients = Better_Messages()->functions->get_recipients( $thread_id );

            $undeleted = [];

            if( count($recipients) > 0 ){
                foreach ( $recipients as $recipient ){
                    if( ! $recipient->is_deleted ){
                        $undeleted[$recipient->user_id] = $recipient;
                    }
                }
            }

            return $undeleted;
        }

        public function can_start_new_conversation( $from, $to ){
            $args = array(
                'sender_id'  => $from,
                'recipients' => $to,
                'subject'    => '',
                'new_thread' => true,
                'date_sent'  => null
            );

            Better_Messages()->functions->before_new_thread_filter( $args, $errors );

            if( ! empty( $errors ) ) {
                return $errors;
            } else {
                return true;
            }
        }

        /**
         * @param int $thread_id
         * @return string
         *
         * @since 2.0.55
         */
        public function get_conversation_layout(int $thread_id ){
            $initialHeight = (int) apply_filters( 'bp_better_messages_max_height', Better_Messages()->settings['messagesHeight'] );
            return '<div class="bp-messages-single-thread-wrap" style="height: ' . $initialHeight . 'px" data-thread-id="' . $thread_id . '">' . Better_Messages()->functions->container_placeholder() . '</div>';
        }

        public function get_page( $disable_admin_mode = false ){
            if (defined('WP_DEBUG') && true === WP_DEBUG) {
                // some debug to add later
            } else {
                error_reporting(0);
            }

            do_action('bp_better_messages_before_generation');

            $path = apply_filters('bp_better_messages_views_path', Better_Messages()->path . '/views/');

            $template = 'layout-index.php';

            ob_start();

            $template = apply_filters( 'bp_better_messages_current_template', $path . $template, $template );

            do_action('bp_better_messages_before_main_template_rendered');

            if($template !== false) {
                Better_Messages()->functions->pre_template_include();
                include($template);
                Better_Messages()->functions->after_template_include();
            }

            do_action('bp_better_messages_after_main_template_rendered');

            if( isset($thread_id) && is_int($thread_id)  && ! isset($_GET['mini']) ){
                Better_Messages()->functions->messages_mark_thread_read( $thread_id );
            }

            $content = ob_get_clean();
            $content = str_replace('loading="lazy"', '', $content);

            $content = Better_Messages()->functions->minify_html( $content );

            do_action('bp_better_messages_after_generation');

            return $content;
        }

        public function get_group_page( $group_id ){
            if (defined('WP_DEBUG') && true === WP_DEBUG) {
                // some debug to add later
            } else {
                error_reporting(0);
            }

            global $bpbm_errors;
            $bpbm_errors = [];
            do_action('bp_better_messages_before_generation');

            $path = apply_filters('bp_better_messages_views_path', Better_Messages()->path . '/views/');

            $thread_id = Better_Messages()->groups->get_group_thread_id( $group_id );
            $is_mini = isset($_GET['mini']);

            $template = 'layout-group.php';

            if( ! current_user_can('manage_options') ) {
                if ( ! BP_Groups_Member::check_is_member(Better_Messages()->functions->get_current_user_id(), $group_id) ) {
                    $thread_id = false;
                    $bpbm_errors[] = __('Access restricted', 'bp-better-messages');

                    if ($is_mini) {
                        wp_send_json($bpbm_errors, 403);
                    }

                    $template = 'layout-index.php';
                }
            }

            ob_start();

            $template = apply_filters( 'bp_better_messages_current_template', $path . $template, $template );


            if( ! $this->is_ajax() && count( $bpbm_errors ) > 0 ) {
                echo '<p class="bpbm-notice">' . implode('</p><p class="bpbm-notice">', $bpbm_errors) . '</p>';
            }

            if($template !== false) {
                $this->pre_template_include();
                include($template);
                $this->after_template_include();
            }

            if( isset($thread_id) && is_int($thread_id)  && ! isset($_GET['mini']) ){
                Better_Messages()->functions->messages_mark_thread_read( $thread_id );
            }

            $content = ob_get_clean();
            $content = str_replace('loading="lazy"', '', $content);

            $content = Better_Messages()->functions->minify_html( $content );

            do_action('bp_better_messages_after_generation');

            return $content;
        }


        public function get_threads_html( $user_id = null, $height = 400 ){
            return $this->get_conversations_layout( $height );
        }

        public function get_conversations_layout( $height = 400 ){
            ob_start();
            echo '<div class="bp-messages-wrap bm-threads-list" style="height:' . $height . 'px"></div>';
            return ob_get_clean();
        }

        public function get_thread_meta( $thread_id, $key = '' ) {
            $retval = get_metadata( 'bm_thread', $thread_id, $key, true );
            return $retval;
        }

        public function update_thread_meta( $thread_id, $meta_key, $meta_value ) {
            $retval = update_metadata( 'bm_thread', $thread_id, $meta_key, $meta_value );
            return $retval;
        }

        public function delete_thread_meta( $thread_id, $meta_key ) {
            $retval = delete_metadata( 'bm_thread', $thread_id, $meta_key);
            return $retval;
        }

        public function add_message_meta( $message_id, $meta_key, $meta_value, $unique = true ) {
            $retval = add_metadata( 'bm_message', $message_id, $meta_key, $meta_value, $unique );
            return $retval;
        }

        public function get_message_meta( $message_id, $key = '',  $single = true ) {
            $retval = get_metadata( 'bm_message', $message_id, $key, $single );
            return $retval;
        }

        public function update_message_meta( $message_id, $meta_key, $meta_value ) {
            $retval = update_metadata( 'bm_message', $message_id, $meta_key, $meta_value );
            return $retval;
        }

        public function delete_message_meta( $message_id, $meta_key, $meta_value ) {
            $retval = delete_metadata( 'bm_message', $message_id, $meta_key, $meta_value );
            return $retval;
        }

        public function get_user_muted_threads( $user_id ){
            if( Better_Messages()->settings['allowMuteThreads'] !== '1' ) {
                return [];
            }

            $muted_threads = [];

            global $wpdb;

            $sql = $wpdb->prepare("SELECT thread_id
            FROM `" . bm_get_table('recipients') . "`
            WHERE `is_muted` = 1 AND `user_id` = %d", $user_id);

            $results = $wpdb->get_col( $sql );

            if( is_array( $results ) && count( $results ) > 0 ) {
                foreach ( $results as $thread_id ){
                    $muted_threads[ (int) $thread_id ] = (int) $thread_id;
                }
            }

            return $muted_threads;
        }


        /**
         * @param int $thread_id
         * @return BM_Thread | false
         */
        public function get_thread(int $thread_id ){
            global $wpdb;

            $thread = wp_cache_get( 'thread_' . $thread_id, 'bm_messages' );

            if( $thread ){
                return $thread;
            }

            $thread = $wpdb->get_row( $wpdb->prepare("SELECT * FROM " . bm_get_table('threads') . " WHERE id = %d", $thread_id ) );

            if( ! $thread ) {
                $thread = false;
            }

            wp_cache_set('thread_' . $thread_id, $thread, 'bm_messages');

            return $thread;
        }

        /**
         * @param int $thread_id
         * @return string
         */
        public function get_thread_type( int $thread_id ): string {
            $thread_type = wp_cache_get( 'thread_' . $thread_id . '_type', 'bm_messages' );

            if( $thread_type ){
                return $thread_type;
            }

            $thread = $this->get_thread( $thread_id );

            $thread_type = 'thread';

            if( $thread->type === 'group' ) {
                if (Better_Messages()->settings['enableGroups'] === '1') {
                    $group_id = Better_Messages()->functions->get_thread_meta($thread_id, 'group_id');

                    if (!!$group_id && bm_bp_is_active('groups')) {
                        if (Better_Messages()->groups->is_group_messages_enabled($group_id) === 'enabled') {
                            $thread_type =  'group';
                        }
                    }
                } else if ( class_exists( 'PeepSoGroupsPlugin' ) &&  Better_Messages()->settings['PSenableGroups'] === '1' ) {
                    $group_id = Better_Messages()->functions->get_thread_meta($thread_id, 'peepso_group_id');

                    if (!!$group_id) {
                        $thread_type = 'group';
                    }
                } else if (function_exists('UM') && Better_Messages()->settings['UMenableGroups'] === '1') {
                    $group_id = Better_Messages()->functions->get_thread_meta($thread_id, 'um_group_id');

                    if (!!$group_id) {
                        if( get_post( $group_id ) ){
                            $thread_type =  'group';
                        }
                    }
                }
            } else {
                $chat_id = Better_Messages()->functions->get_thread_meta($thread_id, 'chat_id');

                if ( ! empty( $chat_id ) && get_post_type( $chat_id ) === 'bpbm-chat' ) {
                    $thread_type = 'chat-room';
                }
            }

            $thread_type = apply_filters( 'better_messages_get_thread_type', $thread_type, $thread_id );

            wp_cache_set('thread_' . $thread_id . '_type', $thread_type, 'bm_messages');

            return $thread_type;
        }

        public function get_thread_title( int $thread_id ){
            $thread = $this->get_thread( $thread_id );
            if( ! $thread ) return "";

            $title = html_entity_decode( $thread->subject );

            if( Better_Messages()->settings['disableSubject'] === '1' && empty( $thread->subject ) ){
                $thread_type = $this->get_thread_type( $thread_id );
                if( $thread_type === 'thread' ){
                    $recipients = Better_Messages()->functions->get_recipients( $thread_id );
                    $title = sprintf(_x('%s Participants', 'Thread Title (when subjects are disabled)', 'bp-better-messages'), count( $recipients) );
                }
            }

            return apply_filters('better_messages_thread_title', $title, $thread_id, $thread );
        }

        public function get_thread_image( $thread_id ){
            $image = "";
            $thread = $this->get_thread( $thread_id );
            if( ! $thread ) return $image;
            return apply_filters('better_messages_thread_image', $image, $thread_id, $thread );
        }

        public function get_thread_url( $thread_id ){
            $url = "";
            $thread = $this->get_thread( $thread_id );
            if( ! $thread ) return $url;

            return apply_filters('better_messages_thread_url', $url, $thread_id, $thread );
        }

        public function is_friends_active(){
            return apply_filters( 'better_messages_friends_active', false );
        }

        public function is_only_friends_mode(){
            if( ! $this->is_friends_active() ) return false;

            return apply_filters( 'better_messages_only_friends_mode', false );
        }

        public function is_friends( $user_id_1, $user_id_2 ){
            if( ! $this->is_friends_active() ) return false;

            if( function_exists('friends_check_friendship') ){
                return friends_check_friendship( $user_id_1, $user_id_2 );
            }

            return apply_filters( 'better_messages_is_friends', false, $user_id_1, $user_id_2 );
        }

        public function is_followers_active(){
            return apply_filters( 'better_messages_followers_active', false );
        }

        public function is_only_followers_mode(){
            if( ! $this->is_followers_active() ) return false;

            return apply_filters( 'better_messages_only_followers_mode', false );
        }

        public function is_groups_active(){
            return apply_filters( 'better_messages_groups_active', false );
        }

        public function is_followers( $user_id_1, $user_id_2 ){
            return apply_filters( 'better_messages_is_followers', false, $user_id_1, $user_id_2 );
        }

        public function is_verified( $user_id ){
            return apply_filters( 'better_messages_is_verified', false, $user_id );
        }

        public function video_calls_active(){
            return Better_Messages()->settings['videoCalls'] === '1';
        }

        public function audio_calls_active(){
            return Better_Messages()->settings['audioCalls'] === '1';
        }

        public function is_calls_only_friends(){
            return $this->is_friends_active() && Better_Messages()->settings['callsLimitFriends'] === '1';
        }

        public function rest_user_item( $user_id, $include_personal = true ){
            $item = [
                'id'         => (string) $user_id,
                'user_id'    => (int) $user_id,
                'name'       => html_entity_decode( Better_Messages()->functions->get_name( $user_id ) ),
                'avatar'     => Better_Messages()->functions->get_avatar( $user_id, 50, ['html' => false] ),
                'url'        => bp_core_get_userlink( $user_id, false, true ),
                'verified'   => (int) $this->is_verified( $user_id ),
                'lastActive' => Better_Messages()->functions->get_last_activity( $user_id )
            ];

            $statuses_enabled = Better_Messages()->settings['userStatuses'] === '1';

            if( $statuses_enabled ) {
                $status   = Better_Messages()->websocket->get_user_status($user_id);
                $statuses = Better_Messages()->websocket->get_all_statuses();

                $item['status'] = [
                    'slug'  => $status,
                    'icon'  => $statuses[$status]['icon'],
                    'label' => Better_Messages()->websocket->get_status_display_name($status)
                ];
            }

            if( $include_personal ){
                $item['isFriend'] = (int) $this->is_friends( Better_Messages()->functions->get_current_user_id(), $user_id );

                if( ! $this->is_calls_only_friends() ) {
                    $item['canVideo'] = (int) $this->video_calls_active();
                    $item['canAudio'] = (int) $this->audio_calls_active();
                } else {
                    $item['canVideo'] = (int) $this->video_calls_active() && $item['isFriend'];
                    $item['canAudio'] = (int) $this->audio_calls_active() && $item['isFriend'];
                }
                // if( Better_Messages()->settings['callsLimitFriends'] === '1' )
            }

            return apply_filters( 'better_messages_rest_user_item', $item, $user_id, $include_personal );
        }

        public function get_message_by_order( $thread_id, $message_number = 1 ){
            global $wpdb;

            $offset = $message_number - 1;
            if( $offset < 0 ) $offset = 0;

            $message_id = (int) $wpdb->get_var($wpdb->prepare("SELECT id
            FROM `" . bm_get_table('messages') . "` 
            WHERE `thread_id` = %d  
            ORDER BY `date_sent` DESC
            LIMIT %d, 1", $thread_id, $offset));

            return $message_id;
        }

        public function get_friends_sorted( $user_id, $count = 'all' ){
            global $wpdb;

            $friends = apply_filters( 'better_messages_get_friends', [], $user_id );

            if( count( $friends ) === 0 ) {
                return [];
            }

            if( empty ( $friends ) ) return [];

            $last_active_users = [];

            foreach ( $friends as $friend ){
                $last_active_users[ $friend['id'] ] = 0;
            }

            $query = "SELECT `ID`, `last_activity` 
            FROM `" . bm_get_table('users') . "` users_index
            WHERE `ID` IN (" . implode( ',', array_keys( $last_active_users ) ) . ") 
            ORDER BY `last_activity` DESC";

            if( $count !== 'all' ) {
                $query .= $wpdb->prepare(' LIMIT 0, %d', $count );
            }

            $last_activity = $wpdb->get_results( $query );


            if ( ! empty ( $last_activity ) ) {
                foreach ($last_activity as $item) {
                    $last_active_users[$item->ID] = strtotime( $item->last_activity );
                }
            }

            arsort($last_active_users);

            if( $count !== 'all' ) {
                $last_active_users = array_slice( $last_active_users, 0, $count, true );
            }

            return $last_active_users;
        }

        public function get_users_sorted( $user_id, $exclude = [], $count = 10 ){
            if( $count === 0 ){
                return [];
            }

            global $wpdb;
            $last_active_users = [];

            $excluded = [];
            $excluded_sql = '';
            if( count( $exclude ) > 0 ) {
                foreach ($exclude as $item) {
                    $excluded[] = (int) $item;
                }

                $excluded_sql = "AND `ID` NOT IN (" . implode( ',', $excluded ) . ")";
            }

            $initial_sql = [];

            if( ! Better_Messages()->guests->guest_access_enabled() ){
                $initial_sql[] = "AND `ID` NOT IN(SELECT DISTINCT(user_id) FROM `" . bm_get_table('roles')  . "` WHERE `role` = 'bm-guest' )";
            }

            $additional_sql_condition = apply_filters('better_messages_search_user_sql_condition', $initial_sql, array_map('intval', $excluded ), '', $user_id );

            $query = $wpdb->prepare("
            SELECT `ID`, `last_activity`
            FROM `" . bm_get_table('users') . "`
            WHERE `ID` != %d 
            " . $excluded_sql . "
            " . implode(' ', $additional_sql_condition ) . "
            ORDER BY `last_activity` DESC", $user_id);

            $query .= $wpdb->prepare(' LIMIT 0, %d', $count );

            $last_activity = $wpdb->get_results( $query );

            if ( ! empty ( $last_activity ) ) {
                foreach ($last_activity as $item) {
                    $last_active_users[$item->ID] = strtotime( $item->last_activity );
                }
            }

            arsort($last_active_users);

            return $last_active_users;
        }

        public function check_this_is_multsite() {
            global $wpmu_version;
            if (function_exists('is_multisite')){
                if (is_multisite()) {
                    return true;
                }
                if (!empty($wpmu_version)){
                    return true;
                }
            }
            return false;
        }

        public function is_ajax(){
            if( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
                return true;
            }

            return false;
        }

        public function minify_js($input) {
            if(trim($input) === "") return $input;
            return preg_replace(
                array(
                    // Remove comment(s)
                    '#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',
                    // Remove white-space(s) outside the string and regex
                    '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',
                    // Remove the last semicolon
                    '#;+\}#',
                    // Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
                    '#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=\:)#i',
                    // --ibid. From `foo['bar']` to `foo.bar`
                    '#([a-z0-9_\)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i'
                ),
                array(
                    '$1',
                    '$1$2',
                    '}',
                    '$1$3',
                    '$1.$3'
                ),
                $input);
        }

        public function minify_css($input) {
            if(trim($input) === "") return $input;
            return preg_replace(
                array(
                    // Remove comment(s)
                    '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
                    // Remove unused white-space(s)
                    '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~]|\s(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
                    // Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
                    '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
                    // Replace `:0 0 0 0` with `:0`
                    '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
                    // Replace `background-position:0` with `background-position:0 0`
                    '#(background-position):0(?=[;\}])#si',
                    // Replace `0.6` with `.6`, but only when preceded by `:`, `,`, `-` or a white-space
                    '#(?<=[\s:,\-])0+\.(\d+)#s',
                    // Minify string value
                    '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
                    '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
                    // Minify HEX color code
                    '#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
                    // Replace `(border|outline):none` with `(border|outline):0`
                    '#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
                    // Remove empty selector(s)
                    '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
                ),
                array(
                    '$1',
                    '$1$2$3$4$5$6$7',
                    '$1',
                    ':0',
                    '$1:0 0',
                    '.$1',
                    '$1$3',
                    '$1$2$4$5',
                    '$1$2$3',
                    '$1:0',
                    '$1$2'
                ),
                $input);
        }

        public function minify_html($input) {
            if(trim($input) === "") return $input;

            return $input;
            // Remove extra white-space(s) between HTML attribute(s)

            $input = preg_replace_callback('#<([^\/\s<>!]+)(?:\s+([^<>]*?)\s*|\s*)(\/?)>#s', function($matches) {
                return '<' . $matches[1] . preg_replace('#([^\s=]+)(\=([\'"]?)(.*?)\3)?(\s+|$)#s', ' $1$2', $matches[2]) . $matches[3] . '>';
            }, str_replace("\r", "", $input));

            // Minify inline CSS declaration(s)
            if(strpos($input, ' style=') !== false) {
                $input = preg_replace_callback('#<([^<]+?)\s+style=([\'"])(.*?)\2(?=[\/\s>])#s', function($matches) {
                    return '<' . $matches[1] . ' style=' . $matches[2] . $this->minify_css($matches[3]) . $matches[2];
                }, $input);
            }
            if(strpos($input, '</style>') !== false) {
                $input = preg_replace_callback('#<style(.*?)>(.*?)</style>#is', function($matches) {
                    return '<style' . $matches[1] .'>'. $this->minify_css($matches[2]) . '</style>';
                }, $input);
            }

            if(strpos($input, '</script>') !== false) {
                $input = preg_replace_callback('#<script(.*?)>(.*?)</script>#is', function($matches) {
                    return '<script' . $matches[1] .'>'. $this->minify_js($matches[2]) . '</script>';
                }, $input);
            }

            return preg_replace(
                array(
                    // t = text
                    // o = tag open
                    // c = tag close
                    // Keep important white-space(s) after self-closing HTML tag(s)
                    '#<(img|input)(>| .*?>)#s',
                    // Remove a line break and two or more white-space(s) between tag(s)
                    '#(<!--.*?-->)|(>)(?:\n*|\s{2,})(<)|^\s*|\s*$#s',
                    '#(<!--.*?-->)|(?<!\>)\s+(<\/.*?>)|(<[^\/]*?>)\s+(?!\<)#s', // t+c || o+t
                    '#(<!--.*?-->)|(<[^\/]*?>)\s+(<[^\/]*?>)|(<\/.*?>)\s+(<\/.*?>)#s', // o+o || c+c
                    '#(<!--.*?-->)|(<\/.*?>)\s+(\s)(?!\<)|(?<!\>)\s+(\s)(<[^\/]*?\/?>)|(<[^\/]*?\/?>)\s+(\s)(?!\<)#s', // c+t || t+o || o+t -- separated by long white-space(s)
                    '#(<!--.*?-->)|(<[^\/]*?>)\s+(<\/.*?>)#s', // empty tag
                    '#<(img|input)(>| .*?>)<\/\1>#s', // reset previous fix
                    '#(&nbsp;)&nbsp;(?![<\s])#', // clean up ...
                    '#(?<=\>)(&nbsp;)(?=\<)#', // --ibid
                    // Remove HTML comment(s) except IE comment(s)
                    '#\s*<!--(?!\[if\s).*?-->\s*|(?<!\>)\n+(?=\<[^!])#s'
                ),
                array(
                    '<$1$2</$1>',
                    '$1$2$3',
                    '$1$2$3',
                    '$1$2$3$4$5',
                    '$1$2$3$4$5$6$7',
                    '$1$2$3',
                    '<$1$2',
                    '$1 ',
                    '$1',
                    ""
                ),
                $input);
        }

        public function license_proposal( $return = false ){
            ob_start();
            if( ! Better_Messages()->functions->can_use_premium_code() ) {
                echo '<a style="font-size: 10px;" href="' .  admin_url('admin.php?page=bp-better-messages-pricing') . '">' . __('Get WebSocket License', 'bp-better-messages') . '</a>';
            } else {
                if( ! bpbm_fs()->is_premium() ){
                    $url = bpbm_fs()->_get_latest_download_local_url();
                    $string = sprintf(__('<a href="%s" target="_blank">Download</a> and install Premium version of plugin to use this feature', 'bp-better-messages'), $url);
                    echo '<span style="display: block;margin: 10px 0;max-width: 200px;padding: 10px;color: #721c24;background-color: #f8d7da;border: 1px solid #f5c6cb;">' . $string . '</span>';
                }
            }

            $html = ob_get_clean();

            if( $return ) {
                return $html;
            } else {
                echo $html;
            }
        }

        public function show_avatars(){
            return ! empty( get_option('show_avatars') );
        }

        function strip_all_tags( $string, $allowed_tags = [], $remove_breaks = false ) {
            $string = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $string );
            $string = strip_tags( $string, $allowed_tags );

            if ( $remove_breaks ) {
                $string = preg_replace( '/[\r\n\t ]+/', ' ', $string );
            }

            return trim( $string );
        }

        public function messages_classes( $thread_id = false, $type = 'thread' ){
            global $bpbmCurrentClass;
            $classes = [];

            if( $type === 'chat-room' ){
                $chat_id       = Better_Messages()->functions->get_thread_meta( $thread_id, 'chat_id' );
                $chat_settings = Better_Messages()->chats->get_chat_settings( $chat_id );

                if( $chat_settings['template'] === 'default' ){
                    $class = 'bpbm-template-' . Better_Messages()->settings['template'];
                } else {
                    $class = 'bpbm-template-' . $chat_settings['template'];
                }

                if( $class === 'bpbm-template-modern' ) {
                    if( $chat_settings['modernLayout'] === 'default' ) {
                        $classes[] = $class . '-' . Better_Messages()->settings['modernLayout'];
                    } else {
                        $classes[] = $class . '-' . $chat_settings['modernLayout'];
                    }
                }

            } else {
                $class = 'bpbm-template-' . Better_Messages()->settings['template'];

                if (Better_Messages()->settings['template'] === 'modern') {
                    $classes[] = $class . '-' . Better_Messages()->settings['modernLayout'];
                }
            }

            $classes[] = $class;

            if( ! is_user_logged_in() ) {
                $classes[] = 'bpbm-not-logged-in';
            }

            $bpbmCurrentClass = implode(' ',  $classes);

            echo $bpbmCurrentClass;
        }

        public function remove_re( $str ){
            $prefix = 're:';

            $str = trim($str);

            while( substr(strtolower($str), 0, strlen($prefix)) == $prefix ) {
                $str = trim(substr($str, strlen($prefix)));
            }

            return trim($str);
        }

        public function clean_no_subject( $subject ){
            if( defined('BP_PLATFORM_VERSION') ){
                $text = __( 'No Subject', 'buddyboss' );
            } else {
                $text = __( 'No Subject', 'buddypress' );
            }

            if( trim( $subject ) === $text ){
                return '';
            } else {
                return $subject;
            }
        }

        /*
         * Inserts a new key/value before the key in the array.
         *
         * @param $key
         *   The key to insert before.
         * @param $array
         *   An array to insert in to.
         * @param $new_key
         *   The key to insert.
         * @param $new_value
         *   An value to insert.
         *
         * @return
         *   The new array if the key exists, FALSE otherwise.
         *
         * @see array_insert_after()
         */
        function array_insert_before($key, array &$array, $new_key, $new_value) {
            if (array_key_exists($key, $array)) {
                $new = array();
                foreach ($array as $k => $value) {
                    if ($k === $key) {
                        $new[$new_key] = $new_value;
                    }
                    $new[$k] = $value;
                }
                return $new;
            }
            return FALSE;
        }

        /*
         * Inserts a new key/value after the key in the array.
         *
         * @param $key
         *   The key to insert after.
         * @param $array
         *   An array to insert in to.
         * @param $new_key
         *   The key to insert.
         * @param $new_value
         *   An value to insert.
         *
         * @return
         *   The new array if the key exists, FALSE otherwise.
         *
         * @see array_insert_before()
         */
        function array_insert_after($key, array &$array, $new_key, $new_value) {
            if (array_key_exists ($key, $array)) {
                $new = array();
                foreach ($array as $k => $value) {
                    $new[$k] = $value;
                    if ($k === $key) {
                        $new[$new_key] = $new_value;
                    }
                }
                return $new;
            }
            return FALSE;
        }

        public function messages_mark_thread_read( $thread_id, $user_id = false ){
            global $wpdb;

            if( $user_id === false ) {
                $user_id = Better_Messages()->functions->get_current_user_id();
            }

            $current_unread = (int) $wpdb->get_var( $wpdb->prepare("SELECT unread_count FROM " . bm_get_table('recipients') . " WHERE user_id = %d AND thread_id = %d", $user_id, $thread_id) );

            if( $current_unread > 0 ){
                $time = Better_Messages()->functions->get_microtime();
                $wpdb->query( $wpdb->prepare( "UPDATE " . bm_get_table('recipients'). " SET unread_count = 0, last_update = %d WHERE user_id = %d AND thread_id = %d", $time, $user_id, $thread_id ) );
            }

            wp_cache_delete( 'thread_recipients_' . $thread_id, 'bp_messages' );
            wp_cache_delete( 'bm_thread_recipients_' . $thread_id, 'bp_messages' );

            wp_cache_delete( $user_id, 'bp_messages_unread_count' );

            $this->clean_thread_notifications( $thread_id, $user_id );

            return true;
        }

        function sanitize_xss($value) {
            return htmlspecialchars(strip_tags($value));
        }

        public function clean_thread_notifications($thread_id, $user_id){
            if ( ! function_exists('bp_notifications_add_notification') ) {
                return false;
            }

            Better_Messages_Notifications()->mark_notification_as_read( $thread_id, $user_id );

        }

        public function can_use_premium_code_premium_only(){
            if( $this->is_network_subsite_and_has_license() ) {
                return true;
            }

            return bpbm_fs()->can_use_premium_code__premium_only();
        }

        public function can_use_premium_code(){
            if( $this->is_network_subsite_and_has_license() ) {
                return true;
            }

            return bpbm_fs()->can_use_premium_code();
        }

        public function is_network_subsite_and_has_license(){
            if( defined('MULTISITE') && defined('SUBDOMAIN_INSTALL') && MULTISITE === true && SUBDOMAIN_INSTALL === false ) {
                if( $this->multisite_resolved !== null ){
                    return $this->multisite_resolved;
                }

                if( is_plugin_active_for_network(basename(Better_Messages()->path) . '/bp-better-messages.php') ) {
                    $network = get_network();
                    $main_site_id = (int)$network->site_id;
                    $main_blog_id = (int)$network->blog_id;
                    if (get_current_blog_id() !== $main_blog_id) {
                        $fs_blog = get_blog_option($main_blog_id, 'fs_accounts', false);
                        if (isset($fs_blog['sites']['bp-better-messages'])) {
                            $site = $fs_blog['sites']['bp-better-messages'];

                            if (isset($site->license_id)) {
                                $license_id = $site->license_id;

                                $fs_network = get_network_option($main_site_id, 'fs_accounts', false);
                                if (isset($fs_network['all_licenses']['1557']) && is_array($fs_network['all_licenses']['1557'])) {
                                    foreach ($fs_network['all_licenses']['1557'] as $_license) {
                                        if ( (int)$license_id === (int)$_license->id ) {
                                            define('BP_BETTER_MESSAGES_FORCE_LICENSE_KEY', $_license->secret_key);
                                            define('BP_BETTER_MESSAGES_FORCE_DOMAIN', $site->url);
                                            $this->multisite_resolved = true;
                                            return true;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $this->multisite_resolved = false;

                return false;
            }

            return false;
        }

        public function render_login_form(){
            ob_start();
            ?>
            <style type="text/css">
                .bm-login-form{
                    background: white;
                    border: 1px solid #ccc;
                    color: black;
                    padding: 15px 25px;
                    margin: 15px auto;
                    width: 100%;
                    max-width: 600px;
                }

                .bm-login-form .bm-login-text{
                    color: black;
                    font-size: 16px;
                    margin: 10px 0 20px;
                    font-weight: bold;
                }

                .bm-login-form form label{
                    display: block;
                    width: 100%;
                    margin-bottom: 10px;
                }
                .bm-login-form form input[type="text"],
                .bm-login-form form input[type="password"]{
                    display: block;
                    width: 100%;
                }
            </style>
            <div class="bm-login-form">
                <?php
                echo '<p class="bm-login-text">' . _x('Login required', 'Login form for unlogged users', 'bp-better-messages') . '</p>';

                wp_login_form([
                    'form_id'  => 'bm-login-form',
                    'redirect' => add_query_arg([])
                ]);
                ?>
            </div>
            <?php return ob_get_clean();
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

        public function pre_template_include(){
            remove_filter( 'the_content', 'convert_smilies', 20 );
        }

        public function after_template_include(){
            #add_filter( 'the_content', 'convert_smilies', 20 );
        }

        public function array_map_recursive($callback, $array)
        {
            $func = function ($item) use (&$func, &$callback) {
                return is_array($item) ? array_map($func, $item) : call_user_func($callback, $item);
            };

            return array_map($func, $array);
        }

        public function archive_thread( $user_id, $thread_id ){
            global $wpdb;
            /**
             * Fires before a message thread is marked as deleted.
             *
             * @since 2.2.0
             * @since 2.7.0 The $user_id parameter was added.
             *
             * @param int $thread_id ID of the thread being deleted.
             * @param int $user_id   ID of the user that the thread is being deleted for.
             */
            do_action( 'bp_messages_thread_before_mark_delete', $thread_id, $user_id );

            $time = Better_Messages()->functions->get_microtime();

            // Mark messages as deleted
            $wpdb->query( $wpdb->prepare( "UPDATE " . bm_get_table('recipients') . " SET is_deleted = 1, last_update = %d WHERE thread_id = %d AND user_id = %d", $time, $thread_id, $user_id ) );

            do_action( 'better_messages_thread_updated', $thread_id );
        }

        public function new_message( $args = '' ) {
            if( is_array($args) && ! is_user_logged_in() ) {
                if ( ! isset($args['sender_id']) || $args['sender_id'] === 0 ) {
                    $args['sender_id'] = Better_Messages()->functions->get_current_user_id();
                }
            }

            // Parse the default arguments.
            $r = bp_parse_args( $args, array(
                'sender_id'        => Better_Messages()->functions->get_current_user_id(),
                'thread_id'        => false,   // False for a new message, thread id for a reply to a thread.
                'recipients'       => array(), // Can be an array of usernames, user_ids or mixed.
                'subject'          => false,
                'content'          => false,
                'send_push'        => true,
                'mobile_push'      => true,
                'count_unread'     => true, // If false - no sound notification will be also played
                'show_on_site'     => true,
                'meta'             => false,
                'notification'     => false,
                'send_global'      => true,
                'bulk_hide'        => false,
                'date_sent'        => bp_core_current_time(),
                'meta_data'        => [],
                'temp_id'          => '',
                'return'           => 'thread_id',
                'error_type'       => 'bool',
                'is_update'        => false
            ), 'bm_new_message' );



            // Bail if no sender or no content.
            if ( empty( $r['sender_id'] ) || empty( $r['content'] ) ) {
                if ( 'wp_error' === $r['error_type'] ) {
                    if ( empty( $r['sender_id'] ) ) {
                        $error_code = 'messages_empty_sender';
                        $feedback   = __( 'Your message was not sent. Please use a valid sender.', 'bp-better-messages' );
                    } else {
                        $error_code = 'messages_empty_content';
                        $feedback   = __( 'Your message was not sent. Please enter some content.', 'bp-better-messages' );
                    }

                    return new WP_Error( $error_code, $feedback );

                } else {
                    return false;
                }
            }

            // Create a new message object.
            $message                = new BM_Messages_Message;
            $message->thread_id     = $r['thread_id'];
            $message->sender_id     = $r['sender_id'];
            $message->subject       = $r['subject'];
            $message->message       = $r['content'];
            $message->date_sent     = $r['date_sent'];
            $message->bulk_hide     = $r['bulk_hide'];
            $message->send_global   = $r['send_global'];
            $message->count_unread = (bool) $r['count_unread'];
            $message->show_on_site = (bool) $r['show_on_site'];
            $message->send_push    = $r['send_push'];
            $message->mobile_push  = $r['mobile_push'];
            $message->meta         = $r['meta'];
            $message->notification = $r['notification'];
            $message->temp_id      = $r['temp_id'];
            $message->is_update    = $r['is_update'];


            $new_thread = false;

            // If we have a thread ID...
            if ( ! empty( $r['thread_id'] ) ) {
                $message->recipients = Better_Messages()->functions->get_recipients( $r['thread_id'] );

                // Strip the sender from the recipient list, and unset them if they are
                // not alone. If they are alone, let them talk to themselves.
                if ( isset( $message->recipients[ $r['sender_id'] ] ) && ( count( $message->recipients ) > 1 ) ) {
                    unset( $message->recipients[ $r['sender_id'] ] );
                }

                // Set a default reply subject if none was sent.
                if ( empty( $message->subject ) && isset( $thread->messages[0] ) ) {
                    $message->subject = sprintf( __( 'Re: %s', 'bp-better-messages' ), $thread->messages[0]->subject );
                }

                // ...otherwise use the recipients passed
            } else {
                if ( empty( $r['recipients'] ) ) {
                    if ( 'wp_error' === $r['error_type'] ) {
                        return new WP_Error( 'message_empty_recipients', _x( 'Please select recipient(s) before sending the message', 'User tried to send message with no recipients', 'bp-better-messages' ));
                    } else {
                        return false;
                    }
                }

                if( ! is_array( $r['recipients'] ) ){
                    $r['recipients'] = [$r['recipients']];
                }
                // Remove duplicates & bail if no recipients.
                $recipient_ids = array_map( 'intval', array_unique( $r['recipients'] ) );

                // Format this to match existing recipients.
                if( ! $message->recipients ) $message->recipients = [];

                foreach ( (array) $recipient_ids as $i => $recipient_id ) {
                    $message->recipients[ $i ]          = new stdClass;
                    $message->recipients[ $i ]->user_id = $recipient_id;
                }

                $new_thread = true;
            }

            if( ! $new_thread ){
                $type = Better_Messages()->functions->get_thread_type( $message->thread_id );

                if( $type === 'chat-room' ) {
                    $chat_id = Better_Messages()->functions->get_thread_meta($message->thread_id, 'chat_id');

                    if (!empty($chat_id)) {
                        $excluded_from_thread_list = Better_Messages()->functions->get_thread_meta($message->thread_id, 'exclude_from_threads_list');
                        if (!empty($excluded_from_thread_list)) {
                            $message->count_unread = false;
                            $message->send_global = false;
                        } else {
                            $notifications_enabled = Better_Messages()->functions->get_thread_meta($message->thread_id, 'enable_notifications');
                            if ($notifications_enabled !== '1') {
                                $message->send_push = false;
                                $message->mobile_push = false;
                            }
                        }
                    }
                }

                    if( $type === 'group' ) {
                        if ( Better_Messages()->settings['enableGroupsPushs'] !== '1' ) {
                            $group_id = Better_Messages()->functions->get_thread_meta($message->thread_id, 'group_id');

                            if ( ! empty($group_id) ) {
                                $message->send_push = false;
                                $message->mobile_push = false;
                            }
                        }

                        if ( Better_Messages()->settings['PSenableGroupsPushs'] !== '1' ) {
                            $group_id = Better_Messages()->functions->get_thread_meta($message->thread_id, 'peepso_group_id');

                            if (!empty($group_id)) {
                                $message->send_push = false;
                                $message->mobile_push = false;
                            }
                        }

                        if ( Better_Messages()->settings['UMenableGroupsPushs'] !== '1' ) {
                            $group_id = Better_Messages()->functions->get_thread_meta($message->thread_id, 'um_group_id');

                            if (!empty($group_id)) {
                                $message->send_push = false;
                                $message->mobile_push = false;
                            }
                        }
                    }
            }

            $message->new_thread = (bool) $new_thread;

            // Bail if message failed to send.
            $send = $message->send();
            if ( false === is_int( $send ) ) {
                if ( 'wp_error' === $r['error_type'] ) {
                    if ( is_wp_error( $send ) ) {
                        return $send;
                    } else {
                        return new WP_Error( 'message_generic_error', __( 'Message was not sent. Please try again.', 'bp-better-messages' ) );
                    }
                }

                return false;
            }

            if( $new_thread ){
                Better_Messages()->functions->delete_all_thread_meta( $message->thread_id );
                Better_Messages()->functions->update_thread_meta( $message->thread_id, 'thread_starter_user_id', $r['sender_id'] );
                Better_Messages()->functions->update_thread_meta( $message->thread_id, 'thread_start_time', time() );
            }

            $this->delete_all_message_meta( $message->id );

            if( ! empty( $r['temp_id'] ) ){
                Better_Messages()->functions->update_message_meta( $message->id, 'bm_tmp_id', $r['temp_id'] );
            }

            $record_new_message_meta = true;

            if( isset($args['meta_data']) && is_array( $args['meta_data'] ) && count( $args['meta_data'] ) > 0 ){
                foreach( $args['meta_data'] as $key => $value ){
                    if( $key === 'bm_created_time' ) $record_new_message_meta = false;
                    Better_Messages()->functions->update_message_meta( $message->id, sanitize_text_field($key), sanitize_text_field($value) );
                }
            }

            Better_Messages()->mentions->process_mentions( $message->thread_id, $message->id, $message->message );

            $this->update_message_update_time( $message->id, $record_new_message_meta, false, $record_new_message_meta );

            do_action( 'better_messages_thread_updated', $message->thread_id );

            /**
             * Fires after a message has been successfully sent.
             *
             * @since 1.1.0
             *
             * @param BP_Messages_Message $message Message object. Passed by reference.
             */

            //do_action_ref_array( 'messages_message_sent', array( &$message ) );
            do_action_ref_array( 'better_messages_message_sent', array( &$message ) );

            if( $r['return'] === 'message_id' ){
                return (int) $message->id;
            }

            if( $r['return'] === 'both' ){
                return [
                    'thread_id'  => (int) $message->thread_id,
                    'message_id' => (int) $message->id
                ];
            }
            // Return the thread ID.
            return (int) $message->thread_id;
        }

        public function update_message_update_time( $message_id, $new_message = false, $deleted_message = false, $last_update = true ){
            $microtime = Better_Messages()->functions->get_microtime();

            if( $last_update ) {
                Better_Messages()->functions->update_message_meta($message_id, 'bm_last_update', $microtime);
            }

            if( $new_message ){
                Better_Messages()->functions->update_message_meta( $message_id, 'bm_created_time', $microtime );
            }

            if( $deleted_message ){
                Better_Messages()->functions->update_message_meta( $message_id, 'bm_deleted_time', $microtime );
            }
        }

        public function update_message( $args = '' ){
            global $wpdb;

            // Parse the default arguments.
            $r = bp_parse_args( $args, array(
                'sender_id'    => Better_Messages()->functions->get_current_user_id(),
                'thread_id'    => false,
                'message_id'   => false,
                'send_push'    => false,
                'mobile_push'  => false,
                'count_unread' => false,
                'notification' => false,
                'show_on_site' => false,
                'subject'      => false,
                'content'      => false,
                'is_update'    => true
            ), 'better_messages_update_message' );

            $message = new BM_Messages_Message( $r['message_id'] );

            if( (int) $r['sender_id'] !== (int) $message->sender_id ) {
                return false;
            }

            $message->recipients = $message->get_recipients();

            $message->message = apply_filters( 'better_messages_message_content_before_save', $r['content'], $message->id );

            $wpdb->update(bm_get_table('messages'), [
                'message' => $message->message
            ], [
                'id' => $message->id
            ], ['%s'], ['%d']);

            $this->update_message_update_time( $message->id );

            do_action( 'better_messages_thread_updated', $message->thread_id );

            Better_Messages()->mentions->process_mentions( $message->thread_id, $message->id, $message->message );

            if( function_exists('Better_Messages_WebSocket') ) {
                $message->count_unread     = $r['count_unread'] ? true : false;
                $message->send_push        = $r['send_push'];
                $message->mobile_push      = $r['mobile_push'];
                $message->show_on_site     = $r['show_on_site'] ? true : false;
                $message->notification     = $r['notification'];
                $message->is_update        = $r['is_update'];

                if( isset( $r['meta'] ) ) {
                    $message->meta = $r['meta'];
                } else {
                    $message->meta = [];
                }

                Better_Messages_WebSocket()->on_message_sent($message);
            }

            return true;
        }

        function delete_all_thread_meta( $thread_id ) {
            global $wpdb;
            $table = bm_get_table('threadsmeta');

            return $wpdb->query($wpdb->prepare("DELETE FROM {$table} WHERE `bm_thread_id` = %d", $thread_id) );
        }

        function delete_all_message_meta( $message_id ) {
            global $wpdb;
            $table = bm_get_table('meta');

            return $wpdb->query($wpdb->prepare("DELETE FROM {$table} WHERE `bm_message_id` = %d", $message_id) );
        }

        public function get_last_activity( $user_id ){
            return Better_Messages()->users->get_last_activity( $user_id );
        }

        public function check_access( $thread_id, $user_id = 0, $acccess_type = 'access' ) {
            if ( empty( $user_id ) ) {
                $user_id = Better_Messages()->functions->get_current_user_id();
            }

            $type = $this->get_thread_type( $thread_id );

            if( $type === 'chat-room' ){
                return $this->check_chat_room_access( $thread_id, $user_id, $acccess_type );
            }

            if( $type === 'group' ){
                return apply_filters( 'better_messages_has_access_to_group_chat', false, $thread_id, $user_id );
            }

            $recipients = $this->get_recipients( $thread_id );

            //if ( isset( $recipients[ $user_id ] ) && 0 == $recipients[ $user_id ]->is_deleted ) {
            if ( isset( $recipients[ $user_id ] ) ) {
                return true;
            } else {
                return null;
            }
        }

        public function get_user_unread_count( $user_id )
        {
            global $wpdb;
            return (int) $wpdb->get_var($wpdb->prepare( "SELECT SUM(unread_count) FROM  " . bm_get_table('recipients') . " WHERE user_id = %d AND is_deleted = 0 ", $user_id ));
        }

        public function check_chat_room_access( $thread_id, $user_id, $type ){
            if( $type === 'reply' ){
                $recipients = $this->get_recipients( $thread_id );

                if ( isset( $recipients[ $user_id ] ) ) {
                    return true;
                } else {
                    return false;
                }
            }

            return true;
        }

        public function user_has_role( $user_id, $roles = [] ){
            if( ! $this->is_valid_user_id( $user_id ) ) {
                return false;
            }

            $user_roles  = $this->get_user_roles( $user_id );

            $has_role = false;

            foreach( $user_roles as $user_role ){
                if( in_array( $user_role, $roles ) ){
                    $has_role = true;
                }
            }

            return $has_role;
        }

        public function check_created_time( $message_id, $date_sent, $created_time ){
            if( ! $created_time ){
                $max_length = strlen( PHP_INT_MAX );
                $created_time = strtotime( $date_sent ) * 10000;

                if( strlen($created_time) > $max_length ){
                    $created_time = (int) substr( $created_time, 0, $max_length );
                }

                Better_Messages()->functions->update_message_meta( $message_id, 'bm_created_time', (int) $created_time );
            }
        }

        public function get_microtime(){
            $max_int = strlen( PHP_INT_MAX );

            $length = 14;

            if( $max_int < $length ) $length = $max_int;

            $microtime = str_replace('.', '', (string) microtime(true)) ;

            if( strlen($microtime) > $length ){
                $microtime = substr( $microtime, 0, $length );
            }

            if( strlen( $microtime ) < $length ){
                $microtime = str_pad($microtime, $length, '0', STR_PAD_RIGHT);
            }

            return (int) $microtime;
        }

        function add_hash_arg( $subpage, ...$args ) {
            if ( is_array( $args[0] ) ) {
                if ( count( $args ) < 2 || false === $args[1] ) {
                    $uri = $_SERVER['REQUEST_URI'];
                } else {
                    $uri = $args[1];
                }
            } else {
                if ( count( $args ) < 3 || false === $args[2] ) {
                    $uri = $_SERVER['REQUEST_URI'];
                } else {
                    $uri = $args[2];
                }
            }

            $frag = strstr( $uri, '#' );

            if ( $frag ) {
                $uri = substr( $uri, 0, -strlen( $frag ) );
            } else {
                $frag = '';
            }

            if ( 0 === stripos( $uri, 'http://' ) ) {
                $protocol = 'http://';
                $uri      = substr( $uri, 7 );
            } elseif ( 0 === stripos( $uri, 'https://' ) ) {
                $protocol = 'https://';
                $uri      = substr( $uri, 8 );
            } else {
                $protocol = '';
            }

            $hashAdded = false;
            if ( strpos( $uri, '?' ) !== false ) {
                list( $base, $query ) = explode( '?', $uri, 2 );
                $base                .= '?';

            } elseif ( $protocol || strpos( $uri, '=' ) === false ) {
                $base  = $uri . '#/' . $subpage . '?&';
                $hashAdded = true;
                $query = '';
            } else {
                $base  = '';
                $query = $uri;
            }

            wp_parse_str( $query, $qs );
            $qs = urlencode_deep( $qs ); // This re-URL-encodes things that were already in the query string.
            $hash_args = [];
            if ( is_array( $args[0] ) ) {
                foreach ( $args[0] as $k => $v ) {
                    $hash_args[ $k ] = $v;
                }
            } else {
                $qs[ $args[0] ] = $args[1];
            }


            $ret = build_query( $qs );
            $ret = trim( $ret, '?' );
            $ret = preg_replace( '#=(&|$)#', '$1', $ret );
            $ret = $protocol . $base . $ret . $frag;
            $ret = rtrim( $ret, '?' );
            $ret = str_replace( '?#', '#', $ret );;

            $hasgArgsAdded = false;
            if( ! $hashAdded ){
                $ret .= '#/' . $subpage;
                $hasgArgsAdded = true;
                if( count( $hash_args ) > 0 ){
                    $ret .= '?&';

                    foreach ( $hash_args as $key => $value ){
                        $ret .= $key . '=' . $value . '&';
                    }
                }
            }

            if( ! $hasgArgsAdded && str_ends_with( $ret, '?&') ){
                if( count( $hash_args ) > 0 ){
                    foreach ( $hash_args as $key => $value ){
                        $ret .= $key . '=' . $value . '&';
                    }
                }
            }

            if( str_ends_with( $ret, '?&') ){
                $ret = substr($ret, 0, -2);
            }

            if( str_ends_with( $ret, '=&') ){
                $ret = substr($ret, 0, -2);
            }

            return $ret;
        }

        public function can_moderate_thread( int $thread_id, int $user_id ){
            if( $this->is_thread_super_moderator( $user_id, $thread_id ) ){
                return true;
            }

            if( $this->is_thread_moderator( $thread_id, $user_id ) ){
                return true;
            }

            return false;
        }

        public function is_thread_moderator( int $thread_id, int $user_id ){
            $type = Better_Messages()->functions->get_thread_type( $thread_id );

            if( $type !== 'chat-room' ) {
                return false;
            }

            $moderators = $this->get_moderators( $thread_id );

            return in_array( ( int ) $user_id, $moderators);
        }

        public function get_moderators( $thread_id ){
            $moderators = $this->get_thread_meta( $thread_id, 'moderators' );
            if( ! is_array( $moderators ) ){
                $moderators = [];
            }

            return (array) array_map( 'intval', array_values($moderators) );
        }

        public function add_moderator( $thread_id, $user_id ){
            $moderators = $this->get_moderators( $thread_id );

            if ( ! in_array( ( int ) $user_id, $moderators) ) {
                $moderators[] = (int) $user_id;
                $this->update_thread_meta( $thread_id, 'moderators', array_unique( $moderators ) );
                do_action( 'better_messages_thread_updated', $thread_id );
                do_action( 'better_messages_info_changed', $thread_id );
            }
        }

        /**
         * @param $thread_id
         * @param $user_id
         * @return string
         */
        public function get_user_thread_url($thread_id, $user_id ): string
        {
            return $this->get_user_messages_url( $user_id, $thread_id );
        }

        public function remove_moderator( $thread_id, $user_id ){
            $moderators = $this->get_moderators( $thread_id );

            if (( $key = array_search( ( int ) $user_id, $moderators ) ) !== false) {
                unset( $moderators[$key] );
                $this->update_thread_meta( $thread_id, 'moderators', array_unique( $moderators ) );
                do_action( 'better_messages_thread_updated', $thread_id );
                do_action( 'better_messages_info_changed', $thread_id );
            }

        }

        /**
         *
         * @return string
         */
        public function plugin_mode(){
            if( class_exists('BuddyPress') ){
                return 'buddypress';
            }


            return 'wordpress';
        }

        public function redirect_to_messages_link( $thread_id = false ){
            $array = ['bm-redirect-to-messages' => ''];

            if( $thread_id ){
                $array['thread-id'] = $thread_id;
            }

            return add_query_arg($array, site_url('/'));
        }

        public static function get_total_threads_for_user( $user_id, $type = 'all' ) {
            global $wpdb;

            $exclude_sender = $type_sql = '';

            if ( $type === 'unread' ) {
                $type_sql = 'AND unread_count != 0';
            } elseif ( $type === 'read' ) {
                $type_sql = 'AND unread_count = 0';
            }

            return (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(thread_id) FROM " . bm_get_table('recipients') . " WHERE user_id = %d AND is_deleted = 0 {$exclude_sender} {$type_sql}", $user_id ) );
        }

        public function get_message_ranges( $message_ids = [] ){
            if( count( $message_ids ) < 2 ) return [];

            sort( $message_ids );

            $min = $message_ids[0];
            $currentRange = 0;
            $preRanges = [];

            foreach ($message_ids as $element) {
                if( $min + 1 < $element ) {
                    $currentRange++;
                }

                $preRanges[$currentRange][] = $element;
                $min = $element;
            }

            $ranges = [];
            $lastMax = false;
            foreach ( $preRanges  as $preRange ) {
                if( ! $lastMax ){
                    $lastMax =  max( $preRange );
                    continue;
                }

                $range = [ $lastMax, min( $preRange ) ];
                $lastMax = max( $preRange );
                $ranges[] = $range;
                //$ranges[] = [min($preRange), max($preRange)];
            }


            return $ranges;
        }

        public function get_missed_message_ids( $thread_id, $message_ids = [] ){
            global $wpdb;

            $ranges = $this->get_message_ranges( $message_ids );

            if( count( $ranges ) === 0 ) return [];

            $args = [ $thread_id ];

            $betweens = [];

            foreach( $ranges as $range ){
                $args[] = $range[0] + 1;
                $args[] = $range[1] - 1;
                $betweens[] = 'id BETWEEN %d AND %d';
            }

            $query = $wpdb->prepare("SELECT id
            FROM " . bm_get_table('messages') . " messages
            WHERE thread_id = %d
            AND (" . implode( ' OR ', $betweens ) . ")", $args );

            return $wpdb->get_col($query);
        }

        public function threads_placeholder( $loop_times = 10 ){
            ob_start();

            echo '<div class="threads-list" style="padding-top:0; margin-top:0;opacity: 0.6; overflow: hidden;">';
            for ($k = 0 ; $k < $loop_times; $k++){
                echo '<div class="thread"><div class="pic" style="top: 0;"><span class="avatar bbpm-avatar"><div class="bm-placeholder-wrapper" style="position: relative; width: 30px; height: 30px; border-radius: 4px; overflow: hidden;"><div class="bm-placeholder"><div class="bm-animated-background"></div></div></div></span></div><div class="info"><h4 class="name"><div class="bm-placeholder-wrapper" style="position: relative; width: 100px; height: 15px; border-radius: 4px; overflow: hidden;"><div class="bm-placeholder"><div class="bm-animated-background"></div></div></div></h4><h4><div class="bm-placeholder-wrapper" style="position: relative; width: 80px; height: 10px; border-radius: 4px; overflow: hidden;"><div class="bm-placeholder"><div class="bm-animated-background"></div></div></div></h4><div class="last-message"><div class="bm-last-message-content"><div class="bm-placeholder-wrapper" style="position: relative; min-width: 150px; max-width: 350px; width: 100%; height: 15px; border-radius: 4px; overflow: hidden;"><div class="bm-placeholder"><div class="bm-animated-background"></div></div></div></div></div></div><div class="time"><span class="time-wrapper"><div class="bm-placeholder-wrapper" style="position: relative; width: 50px; height: 15px; border-radius: 4px; overflow: hidden;"><div class="bm-placeholder"><div class="bm-animated-background"></div></div></div></span></div><div class="actions"><span class="delete"></span></div></div>';
            }
            echo '</div>';

            return ob_get_clean();
        }

        public function container_placeholder(){
            $initialHeight = (int) apply_filters( 'bp_better_messages_max_height', Better_Messages()->settings['messagesHeight'] );
            ob_start();
            ?>
            <div class="bp-messages-wrap" style="height:<?php echo $initialHeight; ?>px">
                <div class="chat-header">
                    <div style="position: relative; width:200px; height: 20px; margin-left: 10px; border-radius: 4px; overflow: hidden">
                        <div class="bm-placeholder"><div class="bm-animated-background"></div></div>
                    </div>
                    <div style="position: relative; width:100px; height: 20px; margin-left: auto;margin-right:10px;border-radius: 4px; overflow: hidden">
                        <div class="bm-placeholder"><div class="bm-animated-background"></div></div>
                    </div>
                </div>
                <?php echo $this->threads_placeholder(); ?>
            </div>
            <?php
            return ob_get_clean();
        }

        public function generateRandomString($length = 25) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

        public function get_rest_api_url($namespace = 'better-messages/v1'){
            return esc_url_raw(get_rest_url(null, '/' . $namespace . '/') );
        }

        public function thread_updated_for_user( int $thread_id, int $user_id, $update_table = true ){
            global $wpdb;

            do_action( 'better_messages_thread_self_update', $thread_id, $user_id );

            if( $update_table ) {
                $wpdb->update(bm_get_table('recipients'),
                    ['last_update' => Better_Messages()->functions->get_microtime()],
                    ['thread_id' => $thread_id, 'user_id' => $user_id],
                    ['%d'], ['%d', '%d']
                );
            }
        }

        public function threads_order_sql(){
            if( Better_Messages()->settings['pinnedThreads'] == '1' ) {
                $sql = "GROUP BY `threads`.`id`, `recipients`.`is_pinned`
                    ORDER BY is_pinned DESC, date_sent DESC";
            } else {
                $sql = "GROUP BY `threads`.`id`
                    ORDER BY date_sent DESC";
            }

            return $sql;
        }

        public function record_message_edit_history( $message_id, $old_message, $edited_by = false ){
            $history = Better_Messages()->functions->get_message_meta( $message_id, 'edit_history' );
            if( ! $history || ! is_array( $history ) ){
                $history = [];
            }

            if( ! $edited_by ){
                $edited_by = Better_Messages()->functions->get_current_user_id();
            }

            $edit_time = time();
            $edit_item = [
                'user_id' => $edited_by,
                'old_message' => $old_message
            ];

            $history[$edit_time] = $edit_item;

            Better_Messages()->functions->update_message_meta( $message_id, 'edit_history', $history );
        }

        public function get_message_last_edit( $message_id ){
            $history = Better_Messages()->functions->get_message_meta( $message_id, 'edit_history' );
            if( ! $history || ! is_array( $history ) ){
                return false;
            }

            $timestamps = array_keys( $history );

            $last_edit =  max($timestamps);

            return date('Y-m-d H:i:s', $last_edit);
        }

        public function get_current_user_id(){
            if( is_user_logged_in() ){
                return apply_filters('better_messages_logged_in_user_id', get_current_user_id());
            } else {
                return apply_filters('better_messages_guest_user_id', 0);
            }
        }

        public function is_valid_user_id( $user_id ){
            if( $user_id > 0 ){
                $user = get_userdata( $user_id );
                if( ! $user ){
                    return false;
                }
            } elseif ( $user_id < 0 ){
                $guest = Better_Messages()->guests->get_guest_user( $user_id );
                if( ! $guest ){
                    return false;
                }
            } else {
                return false;
            }

            return true;
        }

        public function is_user_authorized(){
            return (bool) $this->get_current_user_id() !== 0;
        }

        public function update_user_meta( $user_id, $meta_key, $meta_value, $prev_value = '' ){
            if( $user_id > 0 ){
                return update_user_meta($user_id, $meta_key, $meta_value, $prev_value );
            } else {
                return Better_Messages()->guests->update_guest_meta( $user_id, $meta_key, $meta_value );
            }
        }

        public function get_user_meta( $user_id, $key = '', $single = false ){
            if( $user_id > 0 ){
                return get_user_meta($user_id, $key, $single);
            } else {
                return Better_Messages()->guests->get_guest_meta( $user_id, $key );
            }
        }

        public function delete_user_meta( $user_id, $meta_key, $meta_value = '' ){
            if( $user_id > 0 ){
                return delete_user_meta( $user_id, $meta_key, $meta_value);
            } else {
                return Better_Messages()->guests->delete_guest_meta( $user_id, $meta_key );
            }
        }

        public function get_restrict_to_roles( $user_id ): array
        {
            $restricted_roles = (array) Better_Messages()->settings['restrictRoleBlock'];
            $user_roles       = Better_Messages()->functions->get_user_roles( $user_id );

            $restrict_to = [];

            foreach( $restricted_roles as $restricted_role ){
                if( in_array($restricted_role['from'], $user_roles) ){
                    $restrict_to[ $restricted_role['to'] ] = $restricted_role['message'];
                }
            }

            return $restrict_to;
        }

        public function get_user_roles( $user_id ){
            if( $user_id > 0 ){
                $user             = get_userdata( $user_id );

                if( ! $user ){
                    return [];
                }

                return apply_filters( 'better_messages_get_user_roles', (array) $user->roles, $user_id );
            } else {
                return apply_filters( 'better_messages_get_user_roles', ['bm-guest'], $user_id );
            }
        }

        public function get_user_secret_key( $user_id ){
            if( $user_id <= 0 ){
                return 'unencrypted';
            }

            $secret_key = Better_Messages()->functions->get_user_meta( $user_id, 'bpbm_secret_key', true );

            if( empty($secret_key) ){
                $secret_key = $this->random_string(20);
                Better_Messages()->functions->update_user_meta( $user_id, 'bpbm_secret_key', $secret_key );
            }

            return $secret_key;
        }

        public function is_response_good($response)
        {
            // Check if the request was successful
            if ( is_wp_error($response) ) {
                // Handle the error appropriately
                return new WP_Error('request_failed', 'The network request failed.');
            }

            // Check if the request was authorized
            if (wp_remote_retrieve_response_code($response) == 401) {
                // Handle the authorization error
                return new WP_Error('unauthorized', 'The request was not authorized.');
            }

            return true;
        }

        public function random_string($length): string
        {
            $key = '';
            $keys = array_merge(range(0, 9), range('a', 'z'));

            for ($i = 0; $i < $length; $i++) {
                $key .= $keys[array_rand($keys)];
            }

            return $key;
        }

        public function get_client_ip(){
            $ip = '';

            if ( isset($_SERVER['HTTP_CLIENT_IP']) && ! empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && ! empty($_SERVER['HTTP_X_FORWARDED_FOR'] )) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else if( isset($_SERVER['REMOTE_ADDR']) && ! empty($_SERVER['REMOTE_ADDR'] ) ){
                $ip = $_SERVER['REMOTE_ADDR'];
            }

            return $ip;
        }

        public function get_site_domain()
        {
            $site_url = get_site_url();
            $parse = parse_url($site_url);
            return $parse['host'];
        }
    }

endif;

/**
 * @return Better_Messages_Functions instance | null
 */
function Better_Messages_Functions()
{
    return Better_Messages_Functions::instance();
}

if( ! function_exists('BP_Better_Messages_Functions') ) {
    function BP_Better_Messages_Functions()
    {
        return Better_Messages_Functions();
    }
}

