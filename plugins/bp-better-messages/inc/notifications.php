<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages_Notifications' ) ):

    class Better_Messages_Notifications
    {   public static function instance()
        {

            static $instance = null;

            if ( null === $instance ) {
                $instance = new Better_Messages_Notifications();
            }

            return $instance;
        }

        public function __construct()
        {
            add_action( 'init', array( $this, 'register_event' ) );

            $notifications_interval = (int) Better_Messages()->settings['notificationsInterval'];
            if( $notifications_interval > 0 ) {
                add_action( 'bp_send_email', array( $this, 'bp_on_send_email' ), 10, 4 );
                add_action( 'bp_better_messages_send_notifications', array($this, 'notifications_sender'));
                add_filter( 'bp_get_email_args', array( $this, 'suppress_post_type_filters' ), 10, 2 );
            }
        }

        public function suppress_post_type_filters($args, $email_type){
            if( $email_type === 'messages-unread-group' ){
                $args['suppress_filters'] = true;
            }

            return $args;
        }
        public function is_user_emails_enabled( $user_id ){
            $enabled = Better_Messages()->functions->get_user_meta( $user_id, 'notification_messages_new_message', true ) != 'no';
            return apply_filters( 'better_messages_is_user_emails_enabled', $enabled, $user_id );
        }

        public function user_emails_enabled_update( $user_id, $enabled ){
            Better_Messages()->functions->update_user_meta( $user_id, 'notification_messages_new_message', $enabled );
            do_action('better_messages_user_emails_enabled_update', $user_id, $enabled);
        }

        public function user_web_push_enabled( $user_id ){
            return apply_filters( 'better_messages_is_user_web_push_enabled', true, $user_id );
        }

        public function mark_notification_as_read( $target_thread_id, $user_id ){
            if( ! function_exists( 'bp_notifications_delete_notification' ) ) return false;

            global $wpdb;

            $notifications = $wpdb->get_results($wpdb->prepare("
            SELECT * FROM `" . bm_get_table('notifications') . "` 
            WHERE `user_id` = %d
            AND `component_name` = 'messages' 
            AND `component_action` = 'new_message' 
            AND `is_new` = 1 
            ORDER BY `id` DESC", $user_id ));


            $notifications_ids = array();
            foreach($notifications as $notification){
                $thread_id = $wpdb->get_var($wpdb->prepare("SELECT thread_id FROM `" . bm_get_table('messages') . "` WHERE `id` = %d", $notification->item_id));
                if($thread_id === NULL)
                {
                    bp_notifications_delete_notification($notification->id);
                    continue;
                } else {
                    if($thread_id == $target_thread_id) $notifications_ids[] = $notification->id;
                }
            }

            if( count($notifications_ids) > 0){
                $notifications_ids = array_unique($notifications_ids);
                foreach($notifications_ids as $notification_id){
                    BP_Notifications_Notification::update(
                        array( 'is_new' => false ),
                        array( 'id'     => $notification_id )
                    );
                }
            }
        }

        public function register_event()
        {
            $notifications_interval = (int) Better_Messages()->settings['notificationsInterval'];
            if( $notifications_interval > 0 ) {
                if (! wp_next_scheduled('bp_better_messages_send_notifications')) {
                    wp_schedule_event(time(), 'bp_better_messages_notifications', 'bp_better_messages_send_notifications');
                }
            } else {
                if ( wp_next_scheduled('bp_better_messages_send_notifications') ) {
                    wp_unschedule_event( wp_next_scheduled( 'bp_better_messages_send_notifications' ), 'bp_better_messages_send_notifications' );
                }
            }
        }

        public function install_template_if_missing(){
            if( ! function_exists('bp_get_email_post_type') ) return false;
            if( ! apply_filters('bp_better_message_fix_missing_email_template', true ) ) return false;
            if( Better_Messages()->settings['createEmailTemplate'] !== '1' ) return false;

            $defaults = array(
                'post_status' => 'publish',
                'post_type'   => bp_get_email_post_type(),
            );

            $emails = array(
                'messages-unread-group' => array(
                    /* translators: do not remove {} brackets or translate its contents. */
                    'post_title'   => __( '[{{{site.name}}}] You have unread messages: {{subject}}', 'bp-better-messages' ),
                    /* translators: do not remove {} brackets or translate its contents. */
                    'post_content' => __( "You have unread messages: &quot;{{subject}}&quot;\n\n{{{messages.html}}}\n\n<a href=\"{{{thread.url}}}\">Go to the discussion</a> to reply or catch up on the conversation.", 'bp-better-messages' ),
                    /* translators: do not remove {} brackets or translate its contents. */
                    'post_excerpt' => __( "You have unread messages: \"{{subject}}\"\n\n{{messages.raw}}\n\nGo to the discussion to reply or catch up on the conversation: {{{thread.url}}}", 'bp-better-messages' ),
                )
            );

            $descriptions[ 'messages-unread-group' ] = __( 'A member has unread private messages.', 'bp-better-messages' );

            // Add these emails to the database.
            foreach ( $emails as $id => $email ) {
                $post_args = bp_parse_args( $email, $defaults, 'install_email_' . $id );

                $template = $this->get_page_by_title( $post_args[ 'post_title' ], OBJECT, bp_get_email_post_type() );

                if ( $template ){

                    if( $template->post_status === 'publish' ){
                        continue;
                    }
                }

                $post_id = wp_insert_post( $post_args );

                if ( !$post_id ) {
                    continue;
                }

                $tt_ids = wp_set_object_terms( $post_id, $id, bp_get_email_tax_type() );
                foreach ( $tt_ids as $tt_id ) {
                    $term = get_term_by( 'term_taxonomy_id', (int)$tt_id, bp_get_email_tax_type() );
                    wp_update_term( (int)$term->term_id, bp_get_email_tax_type(), array(
                        'description' => $descriptions[ $id ],
                    ) );
                }
            }
        }

        public function  get_page_by_title( $page_title, $output = OBJECT, $post_type = 'page' ) {
            global $wpdb;

            if ( is_array( $post_type ) ) {
                $post_type           = esc_sql( $post_type );
                $post_type_in_string = "'" . implode( "','", $post_type ) . "'";
                $sql                 = $wpdb->prepare(
                    "SELECT ID
                    FROM $wpdb->posts
                    WHERE post_title = %s
                    AND post_type IN ($post_type_in_string)",
                    $page_title
                );
            } else {
                $sql = $wpdb->prepare(
                    "SELECT ID
                        FROM $wpdb->posts
                        WHERE post_title = %s
                        AND post_type = %s",
                    $page_title,
                    $post_type
                );
            }

            $page = $wpdb->get_var( $sql );

            if ( $page ) {
                return get_post( $page, $output );
            }

            return null;
        }

        public function bp_on_send_email(&$email, $email_type, $to, $args){
            if( $email_type !== 'messages-unread-group' ) {
                return false;
            }

            $tokens = $email->get_tokens();

            if( isset( $tokens['subject'] ) ){
                $subject = $tokens['subject'];

                if( $subject === '' ){
                    $email_subject   = $email->get_subject();
                    $email_plaintext = $email->get_content_plaintext();
                    $email_html      = $email->get_content_html();

                    $to_remove = [ '&quot;{{subject}}&quot;', '"{{subject}}"', '{{subject}}' ];

                    foreach ( $to_remove as $str ){
                        $email_subject   = trim(str_replace( $str, '', $email_subject ) );
                        $email_plaintext = trim(str_replace( $str, '', $email_plaintext ) );
                        $email_html      = trim(str_replace( $str, '', $email_html ) );
                    }


                    if(substr($email_subject, -1, 1) === ':'){
                        $email_subject = substr($email_subject, 0, strlen($email_subject) - 1);
                    }

                    $email->set_subject( $email_subject );
                    $email->set_content_plaintext( $email_plaintext );
                    $email->set_content_html( $email_html );
                }
            }
        }

        public function update_last_email( $user_id, $thread_id, $time ){
            global $wpdb;

            $sql = $wpdb->prepare("
            UPDATE `" . bm_get_table('recipients') . "`
            SET last_email = %s
            WHERE thread_id = %d AND user_id = %d", $time, $thread_id, $user_id );

            $wpdb->query( $sql );
        }

        public function notifications_sender()
        {
            global $wpdb;

            set_time_limit(0);

            $this->install_template_if_missing();
            $this->migrate_from_user_meta();

            $minutes = Better_Messages()->settings['notificationsOfflineDelay'];

            if( $minutes > 0 ) {
                $time = gmdate('Y-m-d H:i:s', (strtotime(bp_core_current_time()) - (60 * $minutes)));
            } else {
                $time = gmdate('Y-m-d H:i:s', strtotime(bp_core_current_time()) + 2629800 );
            }

            $select = [];
            $from = [];
            $where = [];
            $group_by = [];
            $having = [];

            $select[] = "`user_index`.`ID` as `user_id`";
            $select[] = "`recipients`.`thread_id`";
            $select[] = "`recipients`.`unread_count`";
            $select[] = "`recipients`.`last_email`";
            $select[] = "(SELECT MAX(m2.date_sent) FROM `" . bm_get_table('messages') . "` `m2` WHERE `m2`.thread_id = `recipients`.thread_id) `last_date`";

            $from[] = "`" . bm_get_table('recipients') . "` `recipients` INNER JOIN `" . bm_get_table('users') . "` as `user_index` ON `recipients`.`user_id` = `user_index`.`ID`";

            $where[] = "`user_index`.`last_activity` < " . $wpdb->prepare('%s', $time);
            $where[] = "AND `recipients`.`unread_count` > 0";
            $where[] = "AND `recipients`.`is_deleted` = 0";
            $where[] = "AND `recipients`.`is_muted` = 0";
            $where[] = "AND `recipients`.`user_id` > 0";
            $where[] = "AND `recipients`.`thread_id` NOT IN( SELECT `bm_thread_id` FROM `" . bm_get_table('threadsmeta') . "` WHERE `meta_key` = 'email_disabled' AND `meta_value` = '1' )";

            $group_by[] = "`user_index`.`ID`";
            $group_by[] = "`recipients`.`thread_id`";

            $having[] = "( `recipients`.`last_email` IS NULL OR `recipients`.`last_email` < `last_date` )";
            $order_by = "`recipients`.`last_email` ASC";
            $limit = "0, 100";

            $select = apply_filters( 'better_messages_notifications_threads_select_sql', $select );
            $from = apply_filters( 'better_messages_notifications_threads_from_sql', $from );
            $where = apply_filters( 'better_messages_notifications_threads_where_sql', $where );
            $group_by = apply_filters( 'better_messages_notifications_threads_group_by_sql', $group_by );
            $having = apply_filters( 'better_messages_notifications_threads_having_sql', $having );
            $order_by = apply_filters( 'better_messages_notifications_threads_order_by_sql', $order_by );
            $limit = apply_filters( 'better_messages_notifications_threads_limit_sql', $limit );

            $sql = apply_filters( 'better_messages_notifications_threads_full_sql', 'SELECT ' . join( ', ', $select ) . ' FROM ' . join( ', ', $from ) . ' WHERE ' . join( ' ', $where ) . ' GROUP BY ' . join( ', ', $group_by ) . ' HAVING ' . join( ' ', $having ) . ' ORDER BY ' .$order_by . ' LIMIT ' . $limit );

            $unread_threads = $wpdb->get_results( $sql );

            while ( is_array( $unread_threads ) && count( $unread_threads ) > 0 ){
                $gmt_offset = get_option('gmt_offset') * 3600;

                foreach ( $unread_threads as $thread ) {
                    $user_id       = $thread->user_id;
                    $thread_id     = $thread->thread_id;
                    $last_notified = $thread->last_email;
                    $last_date     = $thread->last_date;

                    $chat_id = null;

                    $type = Better_Messages()->functions->get_thread_type( $thread_id );

                    if( $type === 'group' ) {
                        if ( Better_Messages()->settings['enableGroupsEmails'] !== '1' ) {
                            $group_id = Better_Messages()->functions->get_thread_meta($thread_id, 'group_id');

                            if (!empty($group_id)) {
                                $this->update_last_email( $user_id, $thread_id, $thread->last_date );
                                continue;
                            }
                        }

                        if ( Better_Messages()->settings['PSenableGroupsEmails'] !== '1' ) {
                            $group_id = Better_Messages()->functions->get_thread_meta($thread_id, 'peepso_group_id');

                            if (!empty($group_id)) {
                                $this->update_last_email( $user_id, $thread_id, $thread->last_date );
                                continue;
                            }
                        }

                        if ( Better_Messages()->settings['UMenableGroupsEmails'] !== '1' ) {
                            $group_id = Better_Messages()->functions->get_thread_meta($thread_id, 'um_group_id');

                            if (!empty($group_id)) {
                                $this->update_last_email( $user_id, $thread_id, $thread->last_date );
                                continue;
                            }
                        }
                    }

                    if( $type === 'chat-room' ) {
                        $chat_id = Better_Messages()->functions->get_thread_meta($thread_id, 'chat_id');

                        if (!empty($chat_id)) {
                            $is_excluded_from_threads_list = Better_Messages()->functions->get_thread_meta($thread_id, 'exclude_from_threads_list');
                            if ($is_excluded_from_threads_list === '1') {
                                Better_Messages()->functions->update_thread_meta($thread_id, 'email_disabled', '1');
                                $this->update_last_email( $user_id, $thread_id, $thread->last_date );
                                continue;
                            }

                            $notifications_enabled = Better_Messages()->functions->get_thread_meta($thread_id, 'enable_notifications');
                            if ($notifications_enabled !== '1') {
                                Better_Messages()->functions->update_thread_meta($thread_id, 'email_disabled', '1');
                                $this->update_last_email( $user_id, $thread_id, $thread->last_date );
                                continue;
                            }
                        }
                    }

                    if ( ! $this->is_user_emails_enabled( $user_id )  ) {
                        $this->update_last_email( $user_id, $thread_id, $thread->last_date );
                        continue;
                    }

                    if ( ! $last_notified || ( $last_date > $last_notified ) ) {

                        if( ! $last_notified ) $last_notified = gmdate('Y-m-d H:i:s', 0 );

                        $ud = get_userdata( $user_id );

                        $query = $wpdb->prepare( "
                            SELECT
                              `messages`.id,
                              `messages`.message,
                              `messages`.sender_id,
                              `threads`.subject,
                              `messages`.date_sent
                            FROM " . bm_get_table('messages') . " as messages
                            LEFT JOIN " . bm_get_table('threads') . " as threads ON
                                threads.id = messages.thread_id
                            LEFT JOIN " . bm_get_table('meta') . " messagesmeta ON
                            ( messagesmeta.`bm_message_id` = `messages`.`id` AND messagesmeta.meta_key = 'bpbm_call_accepted' )
                            WHERE `messages`.`thread_id` = %d
                            AND `messages`.`date_sent` > %s 
                            AND `messages`.message != '<!-- BM-DELETED-MESSAGE -->' 
                            AND `messages`.sender_id != %d 
                            AND `messages`.sender_id != 0 
                            AND ( messagesmeta.meta_id IS NULL )
                            ORDER BY id DESC
                            LIMIT 0, %d
                        ", $thread->thread_id, $last_notified, $user_id, $thread->unread_count );

                        $messages = array_reverse( $wpdb->get_results( $query ) );

                        if ( empty( $messages ) ) {
                            $this->update_last_email( $user_id, $thread_id, gmdate('Y-m-d H:i:s') );
                            continue;
                        }

                        foreach($messages as $index => $message){
                            if( $message->message ){
                                $is_sticker = strpos( $message->message, '<span class="bpbm-sticker">' ) !== false;
                                if( $is_sticker ){
                                    $message->message = __('Sticker', 'bp-better-messages');
                                }

                                $is_gif = strpos( $message->message, '<span class="bpbm-gif">' ) !== false;
                                if( $is_gif ){
                                    $message->message = __('GIF', 'bp-better-messages');
                                }
                            }
                        }

                        $email_overwritten = apply_filters( 'bp_better_messages_overwrite_email', false, $user_id, $thread_id, $messages );

                        if( $email_overwritten === false ) {
                            $messageRaw = '';
                            $messageHtml = '<table style="margin:1rem 0!important;width:100%;table-layout: auto !important;"><tbody>';
                            $last_sender_id = 0;
                            $last_message_id = 0;

                            foreach ($messages as $message) {
                                $bm_user = Better_Messages()->functions->rest_user_item( $message->sender_id, false );

                                $timestamp = strtotime($message->date_sent) + $gmt_offset;
                                $time_format = get_option('time_format');

                                if (gmdate('Ymd') != gmdate('Ymd', $timestamp)) {
                                    $time_format .= ' ' . get_option('date_format');
                                }

                                $time    = apply_filters( 'better_messages_email_notification_time', wp_strip_all_tags( stripslashes(date_i18n($time_format, $timestamp)) ), $message, $user_id );

                                $author  = wp_strip_all_tags(stripslashes(sprintf( __('%s wrote:', 'bp-better-messages'), $bm_user['name'] )));

                                $_message = nl2br(stripslashes($message->message));
                                $_message = str_replace(['<p>', '</p>'], ['<br>', ''], $_message );
                                $_message = Better_Messages()->functions->format_message( $_message, $message->id, 'email', $user_id );
                                $_message = htmlspecialchars_decode(Better_Messages()->functions->strip_all_tags($_message, '<br>'));

                                if ($last_sender_id == 0 || $last_sender_id != $message->sender_id) {
                                    $messageHtml .= '<tr><td colspan="2"><b>' . $author . '</b></td></tr>';
                                    $messageRaw .= "$author\n";
                                }

                                $_message_raw = str_replace(["<br>", "<br/>", '<br />'], "\n", $_message );
                                $messageRaw .= "$time\n$_message_raw\n\n";

                                $messageHtml .= '<tr>';
                                $messageHtml .= '<td style="padding-right: 10px;">' . $_message . '</td>';
                                $messageHtml .= '<td style="width:1px;white-space:nowrap;vertical-align:top;text-align:right;text-overflow:ellipsis;overflow:hidden;"><i>' . $time . '</i></td>';
                                $messageHtml .= '</tr>';

                                $last_sender_id = $message->sender_id;
                                $last_message_id = $message->id;
                            }

                            $messageHtml .= '</tbody></table>';

                            if( Better_Messages()->settings['disableSubject'] === '1' && $type === 'thread' ) {
                                $subject = '';
                            } else {
                                $subject = Better_Messages()->functions->remove_re(sanitize_text_field(stripslashes($messages[0]->subject)));
                                $subject = Better_Messages()->functions->clean_no_subject($subject);
                            }

                            if (function_exists('bp_send_email')) {
                                $sender = get_userdata($message->sender_id);

                                if ( ! is_object($sender) ){
                                    $this->update_last_email( $user_id, $thread_id, $last_date );
                                    continue;
                                }

                                $args = array(
                                    'tokens' =>
                                        apply_filters('bp_better_messages_notification_tokens', array(
                                            'messages.html' => $messageHtml,
                                            'messages.raw' => $messageRaw,
                                            'sender.name' => $bm_user['name'],
                                            'thread.id' => $thread_id,
                                            'thread.url' => esc_url( Better_Messages()->functions->add_hash_arg( 'conversation/' . $thread_id, [], Better_Messages()->functions->get_link($user_id) ) ),
                                            'subject' => $subject,
                                            'unsubscribe' => esc_url(bp_email_get_unsubscribe_link(array(
                                                'user_id' => $user_id,
                                                'notification_type' => 'messages-unread',
                                            )))
                                        ),
                                        $ud, // userdata object of receiver
                                        $sender, // userdata object of sender
                                        $thread_id
                                        ),
                                );

                                bp_send_email('messages-unread-group', $ud, $args);
                            } else {
                                $user = get_userdata($user_id);
                                $thread_url    = esc_url( Better_Messages()->functions->add_hash_arg('conversation/' . $thread_id, [], Better_Messages()->functions->get_link($user_id) ) );

                                if( $subject !== '' ) {
                                    $email_subject = sprintf(_x('You have unread messages: "%s"', 'Email notification header for non BuddyPress websites', 'bp-better-messages'), $subject);
                                } else {
                                    $email_subject = _x('You have unread messages:', 'Email notification header for non BuddyPress websites', 'bp-better-messages');
                                }
                                /**
                                 * Composing Email HTML
                                 */
                                ob_start(); ?>
                                <!doctype html>
                                <html>
                                <head>
                                    <meta name="viewport" content="width=device-width">
                                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                                    <title><?php echo $email_subject; ?></title>
                                    <style>
                                        /* -------------------------------------
                                            INLINED WITH htmlemail.io/inline
                                        ------------------------------------- */
                                        /* -------------------------------------
                                            RESPONSIVE AND MOBILE FRIENDLY STYLES
                                        ------------------------------------- */
                                        @media only screen and (max-width: 620px) {
                                            table[class=body] h1 {
                                                font-size: 28px !important;
                                                margin-bottom: 10px !important;
                                            }
                                            table[class=body] p,
                                            table[class=body] ul,
                                            table[class=body] ol,
                                            table[class=body] td,
                                            table[class=body] span,
                                            table[class=body] a {
                                                font-size: 16px !important;
                                            }
                                            table[class=body] .wrapper,
                                            table[class=body] .article {
                                                padding: 10px !important;
                                            }
                                            table[class=body] .content {
                                                padding: 0 !important;
                                            }
                                            table[class=body] .container {
                                                padding: 0 !important;
                                                width: 100% !important;
                                            }
                                            table[class=body] .main {
                                                border-left-width: 0 !important;
                                                border-radius: 0 !important;
                                                border-right-width: 0 !important;
                                            }
                                            table[class=body] .btn table {
                                                width: 100% !important;
                                            }
                                            table[class=body] .btn a {
                                                width: 100% !important;
                                            }
                                            table[class=body] .img-responsive {
                                                height: auto !important;
                                                max-width: 100% !important;
                                                width: auto !important;
                                            }
                                        }

                                        /* -------------------------------------
                                            PRESERVE THESE STYLES IN THE HEAD
                                        ------------------------------------- */
                                        @media all {
                                            .ExternalClass {
                                                width: 100%;
                                            }
                                            .ExternalClass,
                                            .ExternalClass p,
                                            .ExternalClass span,
                                            .ExternalClass font,
                                            .ExternalClass td,
                                            .ExternalClass div {
                                                line-height: 100%;
                                            }
                                            .apple-link a {
                                                color: inherit !important;
                                                font-family: inherit !important;
                                                font-size: inherit !important;
                                                font-weight: inherit !important;
                                                line-height: inherit !important;
                                                text-decoration: none !important;
                                            }
                                            #MessageViewBody a {
                                                color: inherit;
                                                text-decoration: none;
                                                font-size: inherit;
                                                font-family: inherit;
                                                font-weight: inherit;
                                                line-height: inherit;
                                            }
                                            .btn-primary table td:hover {
                                                background-color: #34495e !important;
                                            }
                                            .btn-primary a:hover {
                                                background-color: #34495e !important;
                                                border-color: #34495e !important;
                                            }
                                        }
                                    </style>
                                </head>
                                <body class="" style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
                                <table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">
                                    <tr>
                                        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
                                        <td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">
                                            <div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">

                                                <!-- START CENTERED WHITE CONTAINER -->
                                                <table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">

                                                    <!-- START MAIN CONTENT AREA -->
                                                    <tr>
                                                        <td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">
                                                            <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                                                                <tr>
                                                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">
                                                                        <p style="font-family: sans-serif; font-size: 16px; font-weight: bold; margin: 0; Margin-bottom: 15px;"><?php echo sprintf(__('Hi %s,', 'bp-better-messages'), $user->display_name); ?></p>
                                                                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;"><?php echo $email_subject; ?></p>
                                                                        <?php echo $messageHtml; ?>
                                                                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0;Margin-top: 20px;Margin-bottom: 15px;"><?php echo sprintf(__('<a href="%s">Go to the discussion</a> to reply or catch up on the conversation.', 'bp-better-messages'), $thread_url); ?></p>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>

                                                    <!-- END MAIN CONTENT AREA -->
                                                </table>

                                                <!-- START FOOTER -->
                                                <div class="footer" style="clear: both; Margin-top: 10px; text-align: center; width: 100%;">
                                                    <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                                                        <tr>
                                                            <td class="content-block" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">
                                                                <span class="apple-link" style="color: #999999; font-size: 12px; text-align: center;"><a href="<?php echo home_url(); ?>"><?php echo get_bloginfo('name');  ?></a></span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <!-- END FOOTER -->

                                                <!-- END CENTERED WHITE CONTAINER -->
                                            </div>
                                        </td>
                                        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
                                    </tr>
                                </table>
                                </body>
                                </html>
                                <?php
                                $content = ob_get_clean();

                                add_filter( 'wp_mail_content_type', array( $this, 'email_content_type' ) );
                                wp_mail( $user->user_email, $email_subject, $content );
                                remove_filter( 'wp_mail_content_type', array( $this, 'email_content_type' ) );
                            }
                        } else {
                            $last_sender_id = 0;
                            $last_message_id = 0;
                            foreach ($messages as $message) {
                                $last_sender_id = $message->sender_id;
                                $last_message_id = $message->id;
                            }
                        }

                        $this->update_last_email( $user_id, $thread_id, $last_date );

                        do_action('better_messages_send_unread_notification', $user_id, $thread_id );

                        if (function_exists('bp_notifications_add_notification')) {
                            if( Better_Messages()->settings['stopBPNotifications'] === '0' ) {
                                if( $type === 'thread' ) {
                                    $notification_id = bp_notifications_add_notification(array(
                                        'user_id'           => $user_id,
                                        'item_id'           => $last_message_id,
                                        'secondary_item_id' => $last_sender_id,
                                        'component_name'    => buddypress()->messages->id,
                                        'component_action'  => 'new_message',
                                        'date_notified'     => bp_core_current_time(),
                                        'is_new'            => 1
                                    ));

                                    bp_notifications_add_meta($notification_id, 'thread_id', $thread_id);
                                }
                            }
                        }

                    }
                }

                $unread_threads = $wpdb->get_results( $sql );
            }
        }

        public function migrate_from_user_meta(){
            set_time_limit(0);
            ignore_user_abort(true);
            global $wpdb;

            $number_of_records = (int) $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->usermeta}` WHERE `meta_key` = 'bp-better-messages-last-notified'");

            if( $number_of_records === 0 ) return;

            $per_page = 200;

            $pages = ceil($number_of_records / $per_page);

            for ($page = 1; $page <= $pages; $page++){
                // code to repeat here
                $offset = ($page - 1) * $per_page;

                $rows = $wpdb->get_results("SELECT user_id, meta_value FROM `{$wpdb->usermeta}` WHERE `meta_key` = 'bp-better-messages-last-notified' ORDER BY user_id LIMIT $offset, $per_page");

                if( count( $rows ) > 0 ){
                    foreach ( $rows as $row ){
                        $user_id = $row->user_id;
                        $threads = unserialize($row->meta_value);

                        if( is_array( $threads ) && count( $threads ) > 0 ){
                            foreach( $threads as $thread_id => $last_id ){
                                $last_time = $wpdb->get_var( $wpdb->prepare(  "SELECT date_sent FROM `" . bm_get_table('messages') . "'` WHERE `thread_id` = %d AND `id` = %d", $thread_id, $last_id ) );
                                if( ! $last_time ) {
                                    $meta_time = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM `" . bm_get_table('meta') . "` WHERE `bm_message_id` = %d AND `meta_key` = 'bm_last_update'", $last_id ) );

                                    if( $meta_time ){
                                        $last_time = gmdate('Y-m-d H:i:s', substr($meta_time, 0, 10));
                                    }
                                }

                                if( ! $last_time ) {
                                    $last_time = gmdate('Y-m-d H:i:s');
                                }

                                $sql = $wpdb->prepare("
                                UPDATE `" . bm_get_table('recipients') . "`
                                SET last_email = %s
                                WHERE thread_id = %d AND user_id = %d
                                ", $last_time, $thread_id, $user_id );

                                $wpdb->query( $sql );
                            }
                        }

                        $sql = $wpdb->prepare("DELETE FROM `{$wpdb->usermeta}` WHERE `meta_key` = 'bp-better-messages-last-notified' AND `user_id` = %d", $user_id);

                        $wpdb->query( $sql );
                    }
                }
            }
        }

        public function email_content_type() {
            return 'text/html';
        }
    }

endif;

function Better_Messages_Notifications()
{
    return Better_Messages_Notifications::instance();
}
