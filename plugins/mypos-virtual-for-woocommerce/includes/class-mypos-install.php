<?php

defined( 'ABSPATH' ) || exit;

/**
 * MyPOS_Install Class.
 */
class MyPOS_Install {

    /**
     * Hook in tabs.
     */
    public static function init() {
        add_action('init', array(__CLASS__, 'install'));
    }

    /**
     * Install MyPOS.
     */
    public static function install() {
        if (!is_blog_installed()) {
            return;
        }

        // Check if we are not already running this routine.
        if ('yes' === get_transient( 'mypos_installing')) {
            return;
        }

        // If we made it till here nothing is running yet, lets set the transient now.
        set_transient( 'mypos_installing', 'yes', MINUTE_IN_SECONDS * 10 );
        mypos_maybe_define_constant('MYPOS_INSTALLING', true);

        MyPOS()->wpdb_table_fix();
        self::create_tables();
        self::verify_base_tables();
        self::setup_environment();

        delete_transient( 'mypos_installing' );
    }

    /**
     * Set up the database tables which the plugin needs to function.
     * WARNING: If you are modifying this method, make sure that its safe to call regardless of the state of database.
     *
     * This is called from `install` method and is executed in-sync when MyPOS is installed or updated. This can also be called optionally from `verify_base_tables`.
     *
     * Tables:
     *      mp_upsells - Table for storing attribute taxonomies - these are user defined
     */
    private static function create_tables() {
        global $wpdb;

        $wpdb->hide_errors();

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        dbDelta(self::get_schema());
    }

    /**
     * Get Table schema.
     *
     * When adding or removing a table, make sure to update the list of tables in MyPOS_Install::get_tables().
     *
     * @return string
     */
    private static function get_schema()
    {
        global $wpdb;

        $collate = '';

        if ($wpdb->has_cap( 'collation')) {
            $collate = $wpdb->get_charset_collate();
        }

        $tables = "CREATE TABLE {$wpdb->prefix}mypos_upsells (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            name varchar(200) NOT NULL,
            base_products longtext NOT NULL,
            recommended_products longtext NOT NULL,
            date_created datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            date_updated datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY  (id),
            UNIQUE KEY session_key (name)
        ) $collate;";

        return $tables;
    }

    /**
     * Check if all the base tables are present.
     *
     * @param bool $modify_notice Whether to modify notice based on if all tables are present.
     * @param bool $execute       Whether to execute get_schema queries as well.
     *
     * @return array List of queries.
     */
    public static function verify_base_tables($modify_notice = true, $execute = false )
    {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        if ($execute) {
            self::create_tables();
        }
        $queries        = dbDelta(self::get_schema(), false);
        $missing_tables = array();

        foreach ($queries as $table_name => $result) {
            if ("Created table $table_name" === $result) {
                $missing_tables[] = $table_name;
            }
        }

        if (0 < count($missing_tables)) {
            update_option( 'mypos_schema_missing_tables', $missing_tables );
        } else {
            delete_option( 'mypos_schema_missing_tables' );
        }

        return $missing_tables;
    }


    /**
     * Setup MyPOS environment - endpoints.
     */
    private static function setup_environment() {
        MyPOS_Auth::add_endpoint();
    }

    /**
     * Return a list of WooCommerce tables. Used to make sure all WC tables are dropped when uninstalling the plugin
     * in a single site or multi site environment.
     *
     * @return array WC tables.
     */
    public static function get_tables() {
        global $wpdb;

        return [
            "{$wpdb->prefix}mypos_upsells",
        ];
    }

    /**
     * Drop MyPOS tables.
     *
     * @return void
     */
    public static function drop_tables() {
        global $wpdb;

        $tables = self::get_tables();

        foreach ( $tables as $table ) {
            $wpdb->query( "DROP TABLE IF EXISTS {$table}" );
        }
    }
}

MyPOS_Install::init();