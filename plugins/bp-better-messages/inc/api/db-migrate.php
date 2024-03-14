<?php
if ( !class_exists( 'Better_Messages_Rest_Api_DB_Migrate' ) ):

    class Better_Messages_Rest_Api_DB_Migrate
    {

        private $db_version = 0.8;

        public static function instance()
        {

            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_Rest_Api_DB_Migrate();
            }

            return $instance;
        }

        public function __construct(){
            add_action( 'rest_api_init',  array( $this, 'rest_api_init' ) );

            add_action( 'wp_ajax_bp_messages_admin_import_options', array( $this, 'import_admin_options' ) );
            add_action( 'wp_ajax_bp_messages_admin_export_options', array( $this, 'export_admin_options' ) );
            add_action( 'wp_ajax_better_messages_admin_reset_database', array( $this, 'reset_database' ) );
            add_action( 'wp_ajax_better_messages_admin_convert_database', array( $this, 'convert_database' ) );
            add_action( 'wp_ajax_better_messages_admin_sync_users', array( $this, 'sync_users' ) );
        }

        public function sync_users(){
            $nonce    = $_POST['nonce'];
            if ( ! wp_verify_nonce($nonce, 'bm-sync-users') ){
                exit;
            }

            if( ! current_user_can('manage_options') ){
                exit;
            }

            Better_Messages()->users->sync_all_users();

            wp_send_json("User synchronization is finished");
        }

        public function convert_database(){
            $nonce    = $_POST['nonce'];
            if ( ! wp_verify_nonce($nonce, 'bm-convert-database') ){
                exit;
            }

            if( ! current_user_can('manage_options') ){
                exit;
            }

            $this->update_collate();

            wp_send_json("Database was converted");
        }

        public function reset_database(){
            $nonce    = $_POST['nonce'];
            if ( ! wp_verify_nonce($nonce, 'bm-reset-database') ){
                exit;
            }

            if( ! current_user_can('manage_options') ){
                exit;
            }

            $this->drop_tables();
            $this->delete_bulk_reports();
            $this->first_install();

            $settings = get_option( 'bp-better-chat-settings', array() );
            $settings['updateTime'] = time();
            update_option( 'bp-better-chat-settings', $settings );

            wp_send_json("Database was reset");
        }

        public function export_admin_options(){

            $nonce    = $_POST['nonce'];
            if ( ! wp_verify_nonce($nonce, 'bpbm-import-options') ){
                exit;
            }

            if( ! current_user_can('manage_options') ){
                exit;
            }

            $options = get_option( 'bp-better-chat-settings', array() );
            wp_send_json(base64_encode(json_encode($options)));
        }

        public function import_admin_options(){

            $nonce    = $_POST['nonce'];
            if ( ! wp_verify_nonce($nonce, 'bpbm-import-options') ){
                exit;
            }

            if( ! current_user_can('manage_options') ){
                exit;
            }

            $settings = sanitize_text_field($_POST['settings']);

            $options  = base64_decode( $settings );
            $options  = json_decode( $options, true );

            if( is_null( $options ) ){
                wp_send_json_error('Error to decode data');
            } else {
                update_option( 'bp-better-chat-settings', $options );
                wp_send_json_success('Succesfully imported');
            }
        }


        public function rest_api_init(){
            /*register_rest_route( 'better-messages/v1', '/db/check', array(
                'methods' => 'GET',
                'callback' => array( $this, 'check_db' ),
                'permission_callback' => array( $this, 'has_access' )
            ) );*/
        }

        public function check_db(){
            $db_1_version = get_option('better_messages_db_version', false);
            $db_migrated  = get_option('better_messages_db_migrated', false);

            if( $db_1_version && ! $db_migrated ){
                return [
                    'result' => 'upgrade_required',
                    'from'   => (float) $db_1_version,
                    'to'     => $this->db_version
                ];
            }

            return [
                'result' => 'upgrade_not_required',
            ];
        }

        public function has_access(){
            return current_user_can( 'manage_options' );
        }

        public function get_tables(){
            return [
                bm_get_table('threads'),
                bm_get_table('threadsmeta'),
                bm_get_table('mentions'),
                bm_get_table('messages'),
                bm_get_table('meta'),
                bm_get_table('recipients'),
                bm_get_table('moderation'),
                bm_get_table('guests'),
                bm_get_table('users'),
                bm_get_table('roles'),
            ];
        }

        public function update_collate(){
            global $wpdb;

            $actions = [
                "ALTER TABLE `" . bm_get_table('mentions') ."` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;",
                "ALTER TABLE `" . bm_get_table('messages') ."` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;",
                "ALTER TABLE `" . bm_get_table('meta') ."` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;",
                "ALTER TABLE `" . bm_get_table('recipients') ."` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;",
                "ALTER TABLE `" . bm_get_table('threadsmeta') ."` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;",
                "ALTER TABLE `" . bm_get_table('threads') ."` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;",
                "ALTER TABLE `" . bm_get_table('moderation') ."` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;",
                "ALTER TABLE `" . bm_get_table('guests') ."` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;",
                "ALTER TABLE `" . bm_get_table('users') ."` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;",
                "ALTER TABLE `" . bm_get_table('roles') ."` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;",
            ];

            foreach( $actions as $sql ){
                $wpdb->query( $sql );
            }

            return null;
        }

        public function delete_bulk_reports(){
            global $wpdb;

            $reports = $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE `post_type` = 'bpbm-bulk-report'");

            if( count($reports) > 0 ){
                foreach ( $reports as $report ){
                    wp_delete_post( $report, true );
                }
            }
        }

        public function drop_tables(){
            global $wpdb;
            $drop_tables = $this->get_tables();

            foreach ( $drop_tables as $table ){
                $wpdb->query("DROP TABLE IF EXISTS {$table}");
            }

            delete_option('better_messages_2_db_version');
        }

        public function first_install(){
            set_time_limit(0);
            ignore_user_abort(true);
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

            $sql = [
                "CREATE TABLE `" . bm_get_table('mentions') ."` (
                       `id` bigint(20) NOT NULL AUTO_INCREMENT,
                       `thread_id` bigint(20) NOT NULL,
                       `message_id` bigint(20) NOT NULL,
                       `user_id` bigint(20) NOT NULL,
                       `type` enum('mention','reply','reaction') NOT NULL,
                       PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB;",

                "CREATE TABLE `" . bm_get_table('messages') ."` (
                      `id` bigint(20) NOT NULL AUTO_INCREMENT,
                      `thread_id` bigint(20) NOT NULL,
                      `sender_id` bigint(20) NOT NULL,
                      `message` longtext NOT NULL,
                      `date_sent` datetime NOT NULL,
                      PRIMARY KEY (`id`),
                      KEY `sender_id` (`sender_id`),
                      KEY `thread_id` (`thread_id`)
                    ) ENGINE=InnoDB;",

                "CREATE TABLE `" . bm_get_table('meta') ."` (
                      `meta_id` bigint(20) NOT NULL AUTO_INCREMENT,
                      `bm_message_id` bigint(20) NOT NULL,
                      `meta_key` varchar(255) DEFAULT NULL,
                      `meta_value` longtext,
                      PRIMARY KEY (`meta_id`),
                      KEY `bm_message_id` (`bm_message_id`),
                      KEY `meta_key` (`meta_key`(191))
                    ) ENGINE=InnoDB;",

                "CREATE TABLE `" . bm_get_table('recipients') ."` (
                      `id` bigint(20) NOT NULL AUTO_INCREMENT,
                      `user_id` bigint(20) NOT NULL,
                      `thread_id` bigint(20) NOT NULL,
                      `unread_count` int(10) NOT NULL DEFAULT '0',
                      `last_read` datetime NULL,
                      `last_delivered` datetime NULL,
                      `last_email` datetime NULL,
                      `is_muted` tinyint(1) NOT NULL DEFAULT '0',
                      `is_pinned` tinyint(1) NOT NULL DEFAULT '0',
                      `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
                      `last_update` bigint(20) NOT NULL DEFAULT '0',
                      PRIMARY KEY (`id`),
                      UNIQUE KEY `user_thread` (`user_id`,`thread_id`),
                      KEY `user_id` (`user_id`),
                      KEY `thread_id` (`thread_id`),
                      KEY `is_deleted` (`is_deleted`),
                      KEY `unread_count` (`unread_count`),
                      KEY `is_pinned` (`is_pinned`)
                    ) ENGINE=InnoDB;",

                "CREATE TABLE `" . bm_get_table('threadsmeta') ."` (
                      `meta_id` bigint(20) NOT NULL AUTO_INCREMENT,
                      `bm_thread_id` bigint(20) NOT NULL,
                      `meta_key` varchar(255) DEFAULT NULL,
                      `meta_value` longtext,
                      PRIMARY KEY (`meta_id`),
                      KEY `meta_key` (`meta_key`(191)),
                      KEY `thread_id` (`bm_thread_id`)
                    ) ENGINE=InnoDB;",

                "CREATE TABLE `" . bm_get_table('threads') ."` (
                      `id` bigint(20) NOT NULL AUTO_INCREMENT,
                      `subject` varchar(255) NOT NULL,
                      `type` enum('thread','group','chat-room') NOT NULL DEFAULT 'thread',
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB;",

                "CREATE TABLE `" . bm_get_table('moderation') ."` (
                  `id` bigint(20) NOT NULL AUTO_INCREMENT,
                  `user_id` bigint(20) NOT NULL,
                  `thread_id` bigint(20) NOT NULL,
                  `type` enum('ban','mute') NOT NULL,
                  `expiration` datetime NULL DEFAULT NULL,
                  `admin_id` bigint(20) NOT NULL,
                  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                   PRIMARY KEY (`id`),
                   UNIQUE KEY `user_thread_type` (`user_id`,`thread_id`,`type`)
                ) ENGINE=InnoDB;",

                "CREATE TABLE `" . bm_get_table('guests') . "` (
                 `id` bigint(20) NOT NULL AUTO_INCREMENT,
                 `secret` varchar(30) NOT NULL,
                 `name` varchar(255) NOT NULL,
                 `email` varchar(100) DEFAULT NULL,
                 `ip` varchar(40) NOT NULL,
                 `meta` longtext NOT NULL,
                 `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                 `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                 `deleted_at` datetime DEFAULT NULL,
                 PRIMARY KEY (`id`)
                ) ENGINE=InnoDB;",

                "CREATE TABLE `" . bm_get_table('roles') . "` (
                    `user_id` bigint(20) NOT NULL,
                    `role` varchar(50) NOT NULL,
                    UNIQUE KEY `user_role_unique` (`user_id`,`role`),
                    KEY `roles_index` (`user_id`)
                ) ENGINE=InnoDB;",

                "CREATE TABLE `" . bm_get_table('users') . "` (
                    `ID` bigint(20) NOT NULL,
                    `user_nicename` varchar(50) NOT NULL DEFAULT '',
                    `display_name` varchar(250) NOT NULL DEFAULT '',
                    `nickname` varchar(255) DEFAULT NULL,
                    `first_name` varchar(255) DEFAULT NULL,
                    `last_name` varchar(255) DEFAULT NULL,
                    `last_activity` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
                    `last_changed` bigint(20) DEFAULT NULL,
                     PRIMARY KEY (`ID`),
                    KEY `last_activity_index` (`last_activity`),
                    KEY `last_changed_index` (`last_changed`)
                ) ENGINE=InnoDB;"
            ];

            dbDelta($sql);

            $this->update_collate();

            Better_Messages_Users()->schedule_sync_all_users();

            update_option( 'better_messages_2_db_version', $this->db_version, false );
        }

        public function upgrade( $current_version ){
            set_time_limit(0);
            ignore_user_abort(true);

            global $wpdb;

            $sqls = [
                '0.2' => [
                    "ALTER TABLE `" . bm_get_table('recipients') . "` ADD `is_pinned` TINYINT(1) NOT NULL DEFAULT '0' AFTER `is_muted`;",
                    "ALTER TABLE `" . bm_get_table('recipients') . "` ADD INDEX `is_pinned` (`is_pinned`);",
                    "ALTER TABLE `" . bm_get_table('recipients') . "` DROP INDEX `last_delivered`;",
                    "ALTER TABLE `" . bm_get_table('recipients') . "` DROP INDEX `last_read`;",
                ],
                '0.3' => [
                    function (){
                        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

                        dbDelta(["CREATE TABLE `" . bm_get_table('moderation') ."` (
                          `id` bigint(20) NOT NULL AUTO_INCREMENT,
                          `user_id` bigint(20) NOT NULL,
                          `thread_id` bigint(20) NOT NULL,
                          `type` enum('ban','mute') NOT NULL,
                          `expiration` datetime NULL DEFAULT NULL,
                          `admin_id` bigint(20) NOT NULL,
                          `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                          `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                           PRIMARY KEY (`id`),
                           UNIQUE KEY `user_thread_type` (`user_id`,`thread_id`,`type`)
                        ) ENGINE=InnoDB;"]);
                    }
                ],
                '0.4' => [
                    function (){
                        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
                        global $wpdb;
                        dbDelta(["CREATE TABLE `" . bm_get_table('guests') . "` (
                         `id` bigint(20) NOT NULL AUTO_INCREMENT,
                         `secret` varchar(30) NOT NULL,
                         `name` varchar(255) NOT NULL,
                         `email` varchar(100) DEFAULT NULL,
                         `ip` varchar(40) NOT NULL,
                         `meta` longtext NOT NULL,
                         `last_active` datetime DEFAULT NULL,
                         `last_changed` bigint(20) DEFAULT NULL,
                         `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                         `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                         PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB;"]);
                    }
                ],
                '0.5' => [
                    function(){
                        Better_Messages_Rest_Api_DB_Migrate()->update_collate();
                    }
                ],
                '0.6' => [
                    "ALTER TABLE `" . bm_get_table('guests') . "` ADD `deleted_at` DATETIME NULL DEFAULT NULL AFTER `updated_at`;"
                ],
                '0.7' =>[
                    function (){
                        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
                        dbDelta([
                            "CREATE TABLE `" . bm_get_table('roles') . "` (
                              `user_id` bigint(20) NOT NULL,
                              `role` varchar(50) NOT NULL,
                              UNIQUE KEY `user_role_unique` (`user_id`,`role`),
                              KEY `roles_index` (`user_id`)
                            ) ENGINE=InnoDB;",
                            "CREATE TABLE `" . bm_get_table('users') . "` (
                              `ID` bigint(20) NOT NULL,
                              `user_nicename` varchar(50) NOT NULL DEFAULT '',
                              `display_name` varchar(250) NOT NULL DEFAULT '',
                              `nickname` varchar(255) DEFAULT NULL,
                              `first_name` varchar(255) DEFAULT NULL,
                              `last_name` varchar(255) DEFAULT NULL,
                              `last_activity` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
                              `last_changed` bigint(20) DEFAULT NULL,
                              PRIMARY KEY (`ID`)
                            ) ENGINE=InnoDB;"
                        ]);
                        global $wpdb;

                        $wpdb->query("ALTER TABLE `" . bm_get_table('recipients') . "` ADD `last_email` DATETIME NULL DEFAULT NULL AFTER `last_delivered`;");

                        Better_Messages_Users()->schedule_sync_all_users();

                        // Migrating data from usermeta to new table
                        $wpdb->query("
                        INSERT INTO `" . bm_get_table('users') . "` (ID, last_activity)
                        SELECT `user_id` as `ID`, `meta_value` as `last_activity`
                        FROM  `{$wpdb->usermeta}`
                        WHERE `meta_key` = 'bpbm_last_activity'
                        ON DUPLICATE KEY UPDATE last_activity=last_activity;");

                        $wpdb->query("
                        INSERT INTO `" . bm_get_table('users') . "` ( ID,  last_activity )
                            SELECT (-1 * id) as ID, 
                            last_active as last_activity
                        FROM `" . bm_get_table('guests') . "` `guests`
                            WHERE `deleted_at` IS NULL
                        ON DUPLICATE KEY 
                        UPDATE last_activity = `guests`.`last_active`");

                        // Deleting old user meta to clean up
                        $wpdb->query("DELETE FROM  `{$wpdb->usermeta}` WHERE `meta_key` = 'bpbm_last_activity'");
                        $wpdb->query("ALTER TABLE `" . bm_get_table('guests') . "` DROP `last_active`;");
                    }
                ],
                '0.8' => [
                    "ALTER TABLE `" . bm_get_table('users') . "` ADD INDEX `last_activity_index` (`last_activity`);",
                    "ALTER TABLE `" . bm_get_table('users') . "` ADD INDEX `last_changed_index` (`last_changed`);",
                ]
            ];

            $sql = [];

            foreach ($sqls as $version => $queries) {
                if ($version > $current_version) {
                    foreach ($queries as $query) {
                        $sql[] = $query;
                    }
                }
            }

            if( count( $sql ) > 0 ){
                foreach ( $sql as $query ) {
                    if( is_string( $query ) ) {
                        $wpdb->query($query);
                    }
                    if( is_callable( $query) ) {
                        $query();
                    }
                }

                $this->update_collate();
            }

            update_option( 'better_messages_2_db_version', $this->db_version, false );
        }

        public function install_tables(){
            $db_2_version = get_option( 'better_messages_2_db_version', 0 );

            if( $db_2_version === 0 ){
                $this->first_install();
            } else if( $db_2_version != $this->db_version) {
                $this->upgrade( $db_2_version );
            }
        }

        public function migrations(){
            global $wpdb;

            $db_migrated = get_option('better_messages_db_migrated', false);

            if( ! $db_migrated ) {
                set_time_limit(0);
                require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

                $time = Better_Messages()->functions->get_microtime();

                $count = (int) $wpdb->get_var("SELECT COUNT(*) FROM " . bm_get_table('messages') );

                if( $count === 0 ){
                    $exists = $wpdb->get_var("SHOW TABLES LIKE '" . $wpdb->prefix . "bp_messages_recipients';");

                    if( $exists ) {
                        $wpdb->query("TRUNCATE " . bm_get_table('threads') . ";");
                        $wpdb->query("TRUNCATE " . bm_get_table('recipients') . ";");
                        $wpdb->query("TRUNCATE " . bm_get_table('messages') . ";");
                        $wpdb->query("TRUNCATE " . bm_get_table('threadsmeta') . ";");
                        $wpdb->query("TRUNCATE " . bm_get_table('meta') . ";");


                        $thread_ids = array_map('intval', $wpdb->get_col("SELECT thread_id
                        FROM " . $wpdb->prefix . "bp_messages_recipients recipients
                        GROUP BY thread_id"));

                        foreach ($thread_ids as $thread_id) {
                            $type = $this->get_thread_type($thread_id);
                            $subject = Better_Messages()->functions->remove_re($wpdb->get_var($wpdb->prepare("SELECT subject
                            FROM {$wpdb->prefix}bp_messages_messages
                            WHERE thread_id = %d
                            ORDER BY date_sent DESC
                            LIMIT 0, 1", $thread_id)));

                            $wpdb->insert(bm_get_table('threads'), [
                                'id' => $thread_id,
                                'subject' => $subject,
                                'type' => $type
                            ]);
                        }

                        $wpdb->query($wpdb->prepare("INSERT IGNORE INTO " . bm_get_table('recipients') . "
                        (user_id,thread_id,unread_count,is_deleted, last_update, is_muted)
                        SELECT user_id, thread_id, unread_count, is_deleted, %d, 0
                        FROM " . $wpdb->prefix . "bp_messages_recipients", $time));

                        $wpdb->query("INSERT IGNORE INTO " . bm_get_table('messages') . "
                        (id,thread_id,sender_id,message,date_sent)
                        SELECT id,thread_id, sender_id, message, date_sent
                        FROM " . $wpdb->prefix . "bp_messages_messages
                        WHERE date_sent != '0000-00-00 00:00:00'");

                        $wpdb->query("INSERT IGNORE INTO " . bm_get_table('threadsmeta') . "
                        (bm_thread_id, meta_key, meta_value)
                        SELECT bpbm_threads_id, meta_key, meta_value
                        FROM " . $wpdb->prefix . "bpbm_threadsmeta");

                        $wpdb->query("INSERT IGNORE INTO " . bm_get_table('meta') . "
                        (bm_message_id, meta_key, meta_value)
                        SELECT message_id, meta_key, meta_value
                        FROM " . $wpdb->prefix . "bp_messages_meta");
                    }
                }

                update_option( 'better_messages_db_migrated', true, false );
            }
        }

        public function get_thread_type( $thread_id ){
            global $wpdb;

            if( Better_Messages()->settings['enableGroups'] === '1' ) {
                $group_id = $wpdb->get_var( $wpdb->prepare("SELECT meta_value FROM {$wpdb->prefix}bpbm_threadsmeta WHERE `bpbm_threads_id` = %d AND `meta_key` = 'group_id'", $thread_id ) );
                if ( !! $group_id && bm_bp_is_active('groups') ) {
                    if (Better_Messages()->groups->is_group_messages_enabled($group_id) === 'enabled') {
                        return 'group';
                    }
                }
            }

            if( Better_Messages()->settings['PSenableGroups'] === '1' ) {
                $group_id = $wpdb->get_var( $wpdb->prepare("SELECT meta_value FROM {$wpdb->prefix}bpbm_threadsmeta WHERE `bpbm_threads_id` = %d AND `meta_key` = 'peepso_group_id'", $thread_id ) );

                if ( !! $group_id ){
                    return 'group';
                }
            }

            if( function_exists('UM') && Better_Messages()->settings['UMenableGroups'] === '1' ) {
                $group_id = $wpdb->get_var( $wpdb->prepare("SELECT meta_value FROM {$wpdb->prefix}bpbm_threadsmeta WHERE `bpbm_threads_id` = %d AND `meta_key` = 'um_group_id'", $thread_id ) );


                if ( !! $group_id ){
                    return 'group';
                }
            }

            $chat_id = $wpdb->get_var( $wpdb->prepare("SELECT meta_value FROM {$wpdb->prefix}bpbm_threadsmeta WHERE `bpbm_threads_id` = %d AND `meta_key` = 'chat_id'", $thread_id ) );

            if( ! empty( $chat_id ) ) {
                return 'chat-room';
            }

            return 'thread';
        }
    }


    function Better_Messages_Rest_Api_DB_Migrate(){
        return Better_Messages_Rest_Api_DB_Migrate::instance();
    }
endif;
