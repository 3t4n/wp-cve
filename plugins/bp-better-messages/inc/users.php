<?php
defined( 'ABSPATH' ) || exit;

class Better_Messages_Users
{

    public $users_table;

    public $roles_table;

    public static function instance()
    {
        // Store the instance locally to avoid private static replication
        static $instance = null;

        // Only run these methods if they haven't been run previously
        if (null === $instance) {
            $instance = new Better_Messages_Users;
        }

        // Always return the instance
        return $instance;

        // The last metroid is in captivity. The galaxy is at peace.
    }

    public function __construct()
    {
        $this->users_table = bm_get_table('users');
        $this->roles_table = bm_get_table('roles');


        add_action( 'rest_api_init',  array( $this, 'rest_api_init' ) );

        add_action( 'add_user_role',    array($this, 'role_changed'), 10, 2 );
        add_action( 'remove_user_role', array($this, 'role_changed'), 10, 2 );
        add_action( 'set_user_role',    array($this, 'role_changed'), 10, 3 );
        add_action( 'profile_update',   array($this, 'user_changed'), 10, 3 );
        add_action( 'user_register',    array($this, 'user_changed'), 10, 2 );

        add_action( 'updated_user_meta', array( $this, 'on_usermeta_update'), 10, 4 );

        add_action( 'deleted_user',     array($this, 'user_deleted'), 10, 3 );

        add_action( 'better_messages_guest_registered', array( $this, 'guest_updated'), 10, 1 );
        add_action( 'better_messages_guest_updated', array( $this, 'guest_updated'), 10, 1 );
        add_action( 'better_messages_guest_deleted',    array( $this, 'guest_deleted' ), 20, 1 );

        add_action( 'better_messages_sync_user_index', array( $this, 'sync_all_users'), 10, 0 );
        add_action( 'better_messages_sync_user_index_weekly', array( $this, 'sync_all_users'), 10, 0 );

        add_action( 'admin_init', array( $this, 'register_weekly_worker' ) );
    }

    public function rest_api_init(){
        register_rest_route( 'better-messages/v1', '/getUsers', array(
            'methods' => 'POST',
            'callback' => array( $this, 'get_users' ),
            'permission_callback' => array( Better_Messages_Rest_Api(), 'is_user_authorized' )
        ) );
    }

    public function get_users( WP_REST_Request $request ){
        $current_user_id = Better_Messages()->functions->get_current_user_id();

        global $wpdb;

        $total = (int) $wpdb->get_var($wpdb->prepare( "
        SELECT COUNT(*)
        FROM `" . bm_get_table('users') . "`
        WHERE `ID` != %d
        ", $current_user_id ));

        $result = [
            'users' => [],
            'total' => $total
        ];

        $sql = $wpdb->prepare( "
        SELECT ID
        FROM `" . bm_get_table('users') . "`
        WHERE `ID` != %d
        ORDER BY `last_activity` DESC
        LIMIT 0, 40
        ", $current_user_id );

        $users = $wpdb->get_col( $sql );

        if( count( $users ) > 0 ){
            foreach ( $users as $user_id ) {
                $user = Better_Messages()->functions->rest_user_item( $user_id );
                $user['isContact'] = 1;
                $result['users'][] = $user;
            }
        }

        return $result;
    }

    function register_weekly_worker(){
        if ( ! wp_next_scheduled( 'better_messages_sync_user_index_weekly' ) ) {
            wp_schedule_event( time() + 604800, 'weekly', 'better_messages_sync_user_index_weekly' );
        }
    }

    public function schedule_sync_all_users(){
        if( ! wp_get_scheduled_event( 'better_messages_sync_user_index' ) ){
            wp_schedule_single_event( time(), 'better_messages_sync_user_index' );

            if( $event = wp_next_scheduled( 'better_messages_sync_user_index_weekly' ) ){
                wp_unschedule_event($event, 'better_messages_sync_user_index_weekly');
            }

            $this->register_weekly_worker();
        }
    }

    public function sync_all_users(){
        update_option( 'bm_sync_user_roles_index_start', time(), false );
        ignore_user_abort(true);
        set_time_limit(0);
        ini_set('memory_limit', -1);

        global $wpdb;

        $wpdb->query("
            INSERT INTO `{$this->users_table}` ( ID, user_nicename, display_name, first_name, last_name, nickname )
            SELECT 
            `users`.`ID` as `ID`, 
            `users`.`user_nicename` as `user_nicename`, 
            `users`.`display_name` as `display_name`, 
            `first_name_meta`.`meta_value` as `first_name`,
            `last_name_meta`.`meta_value` as `last_name`,
            `nickname_meta`.`meta_value` as `nickname`
            FROM `{$wpdb->users}` `users`
        LEFT JOIN `{$wpdb->usermeta}` `first_name_meta`
            ON `first_name_meta`.`user_id` = `users`.`ID`
            AND `first_name_meta`.`meta_key` = 'first_name'
        LEFT JOIN `{$wpdb->usermeta}` `last_name_meta`
            ON `last_name_meta`.`user_id` = `users`.`ID`
            AND `last_name_meta`.`meta_key` = 'last_name'
        LEFT JOIN `{$wpdb->usermeta}` `nickname_meta`
            ON `nickname_meta`.`user_id` = `users`.`ID`
            AND `nickname_meta`.`meta_key` = 'nickname'
        ON DUPLICATE KEY 
        UPDATE user_nicename = `users`.`user_nicename`, display_name = `users`.`display_name`, first_name = `first_name_meta`.`meta_value`, last_name = `last_name_meta`.`meta_value`, nickname = `nickname_meta`.`meta_value`;");

        $wpdb->query("INSERT INTO `{$this->users_table}` ( ID,  display_name )
        SELECT (-1 * id) as ID, name as display_name
        FROM `" . bm_get_table('guests') . "` `guests`
        WHERE `deleted_at` IS NULL
        ON DUPLICATE KEY 
        UPDATE display_name = `guests`.`name`");

        $number_of_users = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->users}");

        $per_page = 200;

        $pages = ceil($number_of_users / $per_page);

        for ($page = 1; $page <= $pages; $page++){
            // code to repeat here
            $offset = ($page - 1) * $per_page;
            $user_ids = $wpdb->get_col("SELECT ID FROM `{$wpdb->users}` ORDER BY ID LIMIT $offset, $per_page");

            if( count( $user_ids ) > 0 ){
                foreach ( $user_ids as $user_id ){
                    $this->sync_user_role_index( $user_id );
                }
            }
        }

        $wpdb->query("DELETE FROM `{$this->users_table}` WHERE ID > 0 AND ID NOT IN (SELECT ID FROM `{$wpdb->users}`)");
        $wpdb->query("DELETE FROM `{$this->roles_table}` WHERE user_id > 0 AND user_id NOT IN (SELECT ID FROM `{$wpdb->users}`)");

        $wpdb->query("
        INSERT INTO `{$this->roles_table}` (user_id, role)
        SELECT (-1 * id) as user_id, 'bm-guest' as role
        FROM `" . bm_get_table('guests') . "` 
        ON DUPLICATE KEY UPDATE role=role;");

        $wpdb->query( "DELETE FROM `{$this->users_table}` WHERE ID < 0 AND ID NOT IN (SELECT (-1 * id) FROM `" . bm_get_table('guests' ) . "` WHERE `deleted_at` IS NULL )" );
        $wpdb->query( "DELETE FROM `{$this->roles_table}` WHERE user_id < 0 AND user_id NOT IN (SELECT (-1 * id) FROM `" . bm_get_table('guests' ) . "` WHERE `deleted_at` IS NULL)" );

        update_option( 'bm_sync_user_roles_index_finish', time(), false );
    }


    public function user_changed( $user_id, $not_user_id = null, $not_used_2 = null ){
        $this->sync_user_data( $user_id );
        $this->sync_user_role_index( $user_id );
    }

    public function user_deleted( $user_id, $not_user_id = null, $not_used_2 = null ){
        global $wpdb;
        $wpdb->query( $wpdb->prepare( "DELETE FROM `{$this->users_table}` WHERE `ID` = %d", $user_id ) );
        $wpdb->query( $wpdb->prepare( "DELETE FROM `{$this->roles_table}` WHERE user_id = %d", $user_id ) );
    }

    public function role_changed( $user_id, $not_user_id = null, $not_used_2 = null ){
        $this->sync_user_role_index( $user_id );
    }

    public function guest_updated($guest_id ){
        $guest_id = -1 * abs( $guest_id );
        $this->sync_user_data( $guest_id );
        $this->sync_user_role_index( $guest_id );
    }

    public function guest_deleted( $guest_id ){
        global $wpdb;
        $wpdb->query( $wpdb->prepare( "DELETE FROM `{$this->users_table}` WHERE ID = %d", -1 * abs( $guest_id ) ) );
        $wpdb->query( $wpdb->prepare( "DELETE FROM `{$this->roles_table}` WHERE user_id = %d", -1 * abs( $guest_id ) ) );
    }

    public function on_usermeta_update($meta_id, $user_id, $meta_key, $_meta_value)
    {
        global $wpdb;

        switch ( $meta_key ){
            case 'first_name':
                $wpdb->query( $wpdb->prepare("
                    INSERT INTO `{$this->users_table}` ( ID, first_name )
                    VALUES( %d, %s )
                    ON DUPLICATE KEY 
                    UPDATE first_name = %s", $user_id, $_meta_value, $_meta_value ) );
                break;
            case 'last_name':
                $wpdb->query( $wpdb->prepare("
                    INSERT INTO `{$this->users_table}` ( ID, last_name )
                    VALUES( %d, %s )
                    ON DUPLICATE KEY 
                    UPDATE last_name = %s", $user_id, $_meta_value, $_meta_value ) );
                break;
            case 'nickname':
                $wpdb->query( $wpdb->prepare("
                    INSERT INTO `{$this->users_table}` ( ID, nickname )
                    VALUES( %d, %s )
                    ON DUPLICATE KEY 
                    UPDATE nickname = %s", $user_id, $_meta_value, $_meta_value ) );
                break;
        }
    }

    public function sync_user_data( $user_id ){
        global $wpdb;

        if( $user_id > 0 ){
            $sql = $wpdb->prepare("
            INSERT INTO `{$this->users_table}` ( ID, user_nicename, display_name, first_name, last_name, nickname )
            SELECT 
                `users`.`ID` as `ID`, 
                `users`.`user_nicename` as `user_nicename`, 
                `users`.`display_name` as `display_name`, 
                `first_name_meta`.`meta_value` as `first_name`,
                `last_name_meta`.`meta_value` as `last_name`,
                `nickname_meta`.`meta_value` as `nickname`
            FROM `{$wpdb->users}` `users`
            LEFT JOIN `{$wpdb->usermeta}` `first_name_meta`
                ON `first_name_meta`.`user_id` = `users`.`ID`
                AND `first_name_meta`.`meta_key` = 'first_name'
            LEFT JOIN `{$wpdb->usermeta}` `last_name_meta`
                ON `last_name_meta`.`user_id` = `users`.`ID`
                AND `last_name_meta`.`meta_key` = 'last_name'
            LEFT JOIN `{$wpdb->usermeta}` `nickname_meta`
                ON `nickname_meta`.`user_id` = `users`.`ID`
                AND `nickname_meta`.`meta_key` = 'nickname'
            WHERE `users`.`ID` = %d
            ON DUPLICATE KEY 
            UPDATE user_nicename = `users`.`user_nicename`, display_name = `users`.`display_name`, first_name = `first_name_meta`.`meta_value`, last_name = `last_name_meta`.`meta_value`, nickname = `nickname_meta`.`meta_value`;", $user_id);

            $wpdb->query( $sql );
        } else {
            $wpdb->query($wpdb->prepare("
            INSERT INTO `{$this->users_table}` ( ID,  display_name )
                SELECT (-1 * id) as ID, name as display_name
            FROM `" . bm_get_table('guests') . "` `guests`
                WHERE `deleted_at` IS NULL
                AND `id` = %d
            ON DUPLICATE KEY 
            UPDATE display_name = `guests`.`name`", $user_id * -1) );
        }
    }

    public function update_last_changed( $user_id ){
        global $wpdb;

        $microtime = Better_Messages()->functions->get_microtime();

        $sql = $wpdb->prepare("
            INSERT INTO `{$this->users_table}` 
                ( ID, last_changed )
            VALUES ( %d, %d )
            ON DUPLICATE KEY UPDATE last_changed = %d
        ", $user_id, $microtime, $microtime );

        $wpdb->query( $sql );
    }

    public function get_last_activity( $user_id ){
        $last_activity_cache = wp_cache_get( 'last_active_' . $user_id, 'bm_messages' );

        if( $last_activity_cache ){
            return $last_activity_cache;
        }

        global $wpdb;

        $last_activity = $wpdb->get_var( $wpdb->prepare("SELECT last_activity FROM `{$this->users_table}` WHERE `ID` = %d", $user_id ) );

        if( ! $last_activity ) {
            $time = gmdate( 'Y-m-d H:i:s',  0 );
            wp_cache_set('last_active_' . $user_id, $time, 'bm_messages' );
            return $time;
        } else {
            wp_cache_set('last_active_' . $user_id, $last_activity, 'bm_messages' );
            return $last_activity;
        }
    }

    public function update_last_activity( $user_id, $time = null ){
        global $wpdb;

        if( ! $time ) $time = gmdate( 'Y-m-d H:i:s' );

        $wpdb->query( $wpdb->prepare("INSERT INTO `{$this->users_table}` (ID, last_activity)
        VALUES (%d, %s)
        ON DUPLICATE KEY UPDATE last_activity=%s;", $user_id, $time, $time ) );

        wp_cache_set('last_active_' . $user_id, $time, 'bm_messages' );
    }

    public function sync_user_role_index( $user_id ){
        global $wpdb;

        $roles = Better_Messages()->functions->get_user_roles( $user_id );

        $table = bm_get_table('roles');

        $values = [];

        $roles_sql = [];
        if( count( $roles ) > 0 ) {
            foreach ($roles as $role) {
                $roles_sql[] = $wpdb->prepare("%s", $role);
                $values[] = $wpdb->prepare( "(%d, %s)", $user_id, $role );
            }
        }

        if( count( $values ) > 0 ){
            $sql = "INSERT INTO `{$table}` (user_id, role)
            VALUES " . implode( ',', $values ) . "
            ON DUPLICATE KEY UPDATE role=role;";

            $wpdb->query( $sql );
        }

        if( count( $roles ) > 0 ){
            $sql = $wpdb->prepare( "DELETE FROM `{$table}` WHERE user_id = %d AND role NOT IN (" . implode(',', $roles_sql) . ")", $user_id );
            $wpdb->query( $sql );
        } else {
            $sql = $wpdb->prepare( "DELETE FROM `{$table}` WHERE user_id = %d", $user_id );
            $wpdb->query( $sql );
        }
    }
}

function Better_Messages_Users()
{
    return Better_Messages_Users::instance();
}
