<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages_Ultimate_Member_Groups' ) ){

    class Better_Messages_Ultimate_Member_Groups
    {

        public static function instance()
        {

            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_Ultimate_Member_Groups();
            }

            return $instance;
        }

        public function __construct(){
            if(  Better_Messages()->settings['UMenableGroups'] === '1' ) {
                add_filter('um_groups_tabs', array($this, 'add_group_tab'), 20, 3);
                add_action('um_groups_single_page_content__messages', array($this, 'group_tab_content'));
                add_action('update_post_meta', array( $this, 'catch_members_update' ), 10 , 4);

                add_filter('better_messages_has_access_to_group_chat', array( $this, 'has_access_to_group_chat'), 10, 3 );
                add_filter('better_messages_rest_thread_item', array( $this, 'rest_thread_item'), 10, 3 );
                add_filter( 'better_messages_groups_active', array($this, 'enabled') );
                add_filter( 'better_messages_get_groups', array($this, 'get_groups'), 10, 2 );

                if (Better_Messages()->settings['UMenableGroupsFiles'] === '0') {
                    add_action('bp_better_messages_user_can_upload_files', array($this, 'disable_upload_files'), 10, 3);
                }

                add_filter('better_messages_thread_title', array( $this, 'group_thread_title' ), 10, 3 );
                add_filter('better_messages_thread_image', array( $this, 'group_thread_image' ), 10, 3 );
                add_filter('better_messages_thread_url',   array( $this, 'group_thread_url' ), 10, 3 );
            }
        }

        /**
         * @param string $title
         * @param int $thread_id
         * @param BM_Thread $thread
         * @return string
         */
        public function group_thread_title(string $title, int $thread_id, $thread ){
            $thread_type = Better_Messages()->functions->get_thread_type( $thread_id );
            if( $thread_type !== 'group' ) return $title;

            $group_id = Better_Messages()->functions->get_thread_meta($thread_id, 'um_group_id');
            $group    = get_post( (int) $group_id );
            if( $group ) {
                return $group->post_title;
            } else {
                return $title;
            }
        }

        /**
         * @param string $title
         * @param int $thread_id
         * @param BM_Thread $thread
         * @return string
         */
        public function group_thread_image(string $title, int $thread_id, $thread ){
            $thread_type = Better_Messages()->functions->get_thread_type( $thread_id );
            if( $thread_type !== 'group' ) return $title;

            $group_id = Better_Messages()->functions->get_thread_meta($thread_id, 'um_group_id');

            return $this->get_group_avatar( $group_id );
        }

        /**
         * @param string $title
         * @param int $thread_id
         * @param BM_Thread $thread
         * @return string
         */
        public function group_thread_url(string $title, int $thread_id, $thread ){
            $thread_type = Better_Messages()->functions->get_thread_type( $thread_id );
            if( $thread_type !== 'group' ) return $title;

            $group_id = Better_Messages()->functions->get_thread_meta($thread_id, 'um_group_id');
            $group    = get_post( (int) $group_id );

            return get_permalink( $group->ID );
        }

        public function get_group_avatar( $group_id ){
            $avatar = "https://via.placeholder.com/50x50";

            $group = get_post($group_id);

            if( ! $group ){
                return $avatar;
            }

            $avatar = "https://via.placeholder.com/50x50?text=" . ucfirst( $group->post_title[0] );

            $um_avatar = UM()->Groups()->api()->get_group_image( $group_id, 'default', 50, 50, true );

            if( is_array( $um_avatar ) ){
                $avatar = $um_avatar[0];
            }

            return $avatar;
        }

        public function get_groups( $groups, $user_id ){
            $groups = [];
            $user_groups = UM()->Groups()->member()->get_groups_joined();

            if( count( $user_groups ) > 0 ) {
                foreach ($user_groups as $user_group) {
                    $group_id = $user_group->group_id;
                    $group = get_post($group_id);
                    $thread_id = Better_Messages_Ultimate_Member_Groups::instance()->get_group_thread_id( $group_id );

                    $group_item = [
                        'group_id'  => (int) $user_group->group_id,
                        'name'      => html_entity_decode(esc_html($group->post_title)),
                        'messages'  => (int) ( $this->is_group_messages_enabled( $group_id ) === 'enabled' ),
                        'thread_id' => (int) $thread_id,
                        'image'     => $this->get_group_avatar( $group_id ),
                        'url'       => get_permalink( $group_id )
                    ];

                    $groups[] = $group_item;
                }
            }

            return $groups;
        }

        public function enabled( $val ){
            return '1';
        }

        public function has_access_to_group_chat( $has_access, $thread_id, $user_id ){
            $group_id = Better_Messages()->functions->get_thread_meta($thread_id, 'um_group_id');
            if (!!$group_id) {
                return $this->is_joined( $group_id, $user_id );
            }

            return $has_access;
        }

        public function rest_thread_item( $thread_item, $thread_id, $thread_type ){
            if( $thread_type !== 'group'){
                return $thread_item;
            }

            if( ! $thread_item['image'] ){
                $thread_item['image'] = "https://via.placeholder.com/50x50?text=" . ucfirst( $thread_item['title'][0] );
            }

            return $thread_item;
        }

        public function catch_members_update( $meta_id, $object_id, $meta_key, $_meta_value ){
            $query = 'um_groups_members_count_';

            if( substr($meta_key, 0, strlen($query)) === $query ){
                $group = get_post( $object_id );

                if( $group->post_type === 'um_groups' ) {
                    $this->on_something_changed( $group->ID );
                }
            }

        }

        public function on_something_changed( $group_id, $user_id = false ){
            $thread_id = $this->get_group_thread_id( $group_id );
            $this->sync_thread_members( $thread_id );
        }

        public function disable_upload_files( $can_upload, $user_id, $thread_id ){
            if( Better_Messages()->functions->get_thread_type( $thread_id ) === 'group' ) {
                return false;
            }

            return $can_upload;
        }

        public function add_group_tab( $tabs , $group_id, $param_tab ){
            if( $this->user_has_access( $group_id, Better_Messages()->functions->get_current_user_id() ) ) {
                $tabs['messages'] = [
                    'slug' => 'messages',
                    'name' => _x('Messages', 'Ultimate Member - Group Tab Name', 'bp-better-messages')
                ];
            }

            return $tabs;
        }

        public function group_tab_content( $um_group_id ){
            if( $this->user_has_access( $um_group_id, Better_Messages()->functions->get_current_user_id() ) ) {
                echo $this->get_group_page( $um_group_id );
            }
        }


        public function user_has_access( $group_id, $user_id ){
            $thread_id = $this->get_group_thread_id( $group_id );
            return Better_Messages()->functions->check_access( $thread_id, $user_id );
        }

        public function is_group_messages_enabled( $um_group_id ){
            return 'enabled';
        }

        public function get_group_page( $group_id ){
            if (defined('WP_DEBUG') && true === WP_DEBUG) {
                // some debug to add later
            } else {
                error_reporting(0);
            }

            do_action('bp_better_messages_before_generation');

            $path = apply_filters('bp_better_messages_views_path', Better_Messages()->path . '/views/');

            $thread_id = $this->get_group_thread_id( $group_id );

            $template = 'layout-um-group.php';

            ob_start();

            $template = apply_filters( 'bp_better_messages_current_template', $path . $template, $template );

            if($template !== false) {
                include($template);
            }

            return ob_get_clean();
        }

        public function user_can_moderate( $group_id, $user_id ){
            return UM()->Groups()->api()->can_moderate_posts( $group_id, $user_id );
        }

        public function is_joined( $group_id, $user_id ){
            return UM()->Groups()->api()->has_joined_group( $user_id, $group_id );
        }

        public function get_group_thread_id( $group_id ){
            global $wpdb;

            $thread_id = (int) $wpdb->get_var( $wpdb->prepare( "
            SELECT bm_thread_id 
            FROM `" . bm_get_table('threadsmeta') . "` 
            WHERE `meta_key` = 'um_group_id' 
            AND   `meta_value` = %s
            ", $group_id ) );

            $thread_exist = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*)  FROM `" . bm_get_table('threads') . "` WHERE `id` = %d", $thread_id));

            if( $thread_exist === 0 ){
                $thread_id = false;
            }

            if( ! $thread_id ) {
                $wpdb->query( $wpdb->prepare( "
                DELETE  
                FROM `" . bm_get_table('threadsmeta') . "` 
                WHERE `meta_key` = 'um_group_id' 
                AND   `meta_value` = %s
                ", $group_id ) );

                $group = get_post( $group_id );

                $wpdb->insert(
                    bm_get_table('threads'),
                    array(
                        'subject' => esc_html( $group->post_title ),
                        'type'    => 'group'
                    )
                );

                $thread_id = $wpdb->insert_id;

                Better_Messages()->functions->update_thread_meta( $thread_id, 'um_group_thread', true );
                Better_Messages()->functions->update_thread_meta( $thread_id, 'um_group_id', $group_id );

                $this->sync_thread_members( $thread_id );
            }

            return $thread_id;
        }

        public function get_groups_members( $group_id ){
            global $wpdb;

            $table = "{$wpdb->prefix}um_groups_members";

            $query = $wpdb->prepare("
                SELECT `user_id1` 
                FROM {$table} 
                WHERE `group_id` = %d
                AND `status` = 'approved'
                AND `role` IN ('member', 'moderator', 'admin')
            ", $group_id);

            return $wpdb->get_col( $query );
        }

        public function sync_thread_members( $thread_id ){
            wp_cache_delete( 'thread_recipients_' . $thread_id, 'bp_messages' );
            wp_cache_delete( 'bm_thread_recipients_' . $thread_id, 'bp_messages' );
            $group_id = Better_Messages()->functions->get_thread_meta( $thread_id, 'um_group_id' );

            $members = $this->get_groups_members( $group_id );

            if( count($members) === 0 ) {
                return false;
            }

            global $wpdb;
            $changes   = false;
            $array     = [];
            $user_ids  = [];
            $removed_ids = [];

            /**
             * All users ids in thread
             */
            $recipients = Better_Messages()->functions->get_recipients( $thread_id );

            foreach( $members as $index => $user_id ){
                if( isset( $recipients[$user_id] ) ){
                    unset( $recipients[$user_id] );
                    continue;
                }

                $user_ids[] = $user_id;

                $array[] = [
                    $user_id,
                    $thread_id,
                    0,
                    0,
                ];
            }

            if( count($array) > 0 ) {
                $changes = true;
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

            if( count($recipients) > 0 ) {
                foreach ($recipients as $user_id => $recipient) {
                    $changes = true;
                    global $wpdb;

                    $wpdb->delete( bm_get_table('recipients'), [
                        'thread_id' => $thread_id,
                        'user_id'   => $user_id
                    ], ['%d','%d'] );

                    $removed_ids[] = $user_id;
                }
            }

            Better_Messages()->hooks->clean_thread_cache( $thread_id );

            if( $changes ){
                do_action( 'better_messages_thread_updated', $thread_id );
                do_action( 'better_messages_info_changed', $thread_id );
                do_action( 'better_messages_participants_added', $thread_id, $user_ids );
                do_action( 'better_messages_participants_removed', $thread_id, $removed_ids );
            }

            return true;
        }

    }
}

