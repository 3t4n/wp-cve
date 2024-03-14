<?php

namespace WPPayForm\Database;

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
require_once(WPPAYFORM_DIR.'database/Migrations/OrdersTable.php');
require_once(WPPAYFORM_DIR.'database/Migrations/TransactionsTable.php');
require_once(WPPAYFORM_DIR.'database/Migrations/SubmissionActivity.php');
require_once(WPPAYFORM_DIR.'database/Migrations/MetaTable.php');
require_once(WPPAYFORM_DIR.'database/Migrations/Subscriptions.php');
require_once(WPPAYFORM_DIR.'database/Migrations/SubmissionsTable.php');
require_once(WPPAYFORM_DIR.'database/Migrations/Pages.php');
require_once(WPPAYFORM_DIR.'database/Migrations/MigrateHelper.php');

class DBMigrator
{
    const WPFDBV = WPPAYFORM_DB_VERSION;

    public static function run($network_wide = false)
    {
        global $wpdb;

        if ($network_wide) {
            // Retrieve all site IDs from this network (WordPress >= 4.6 provides easy to use functions for that).
            if (function_exists('get_sites') && function_exists('get_current_network_id')) {
                $site_ids = get_sites(array('fields' => 'ids', 'network_id' => get_current_network_id()));
            } else {
                $site_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs WHERE site_id = $wpdb->siteid;");
            }
            // Install the plugin for all these sites.
            foreach ($site_ids as $site_id) {
                switch_to_blog($site_id);
                self::migrate();
                restore_current_blog();
            }
        } else {
            self::migrate();
        }
    }

    public static function migrate()
    {
        \WPPayForm\Database\Migrations\SubmissionsTable::migrate();
        \WPPayForm\Database\Migrations\OrdersTable::migrate();
        $isTransactionsTable = \WPPayForm\Database\Migrations\TransactionsTable::migrate();
        \WPPayForm\Database\Migrations\SubmissionActivity::migrate();
        $isMetaTable = \WPPayForm\Database\Migrations\MetaTable::migrate();
        $isSubscriptionsTable = \WPPayForm\Database\Migrations\Subscriptions::migrate();
        \WPPayForm\Database\Migrations\Pages::create();

        // migrate form_id in new db table
        \WPPayForm\App\Models\Meta::migrate();
        \WPPayForm\App\Models\ScheduledActions::migrate();
        update_option('wppayform_integration_status', 'yes', 'no');


        if (!$isTransactionsTable || !$isMetaTable || !$isSubscriptionsTable) {
            self::maybeUpgradeDB();
        } else {
            // we are good. It's a new installation
            update_option('WPF_DB_VERSION', self::WPFDBV, false);
        }
    }

    public static function maybeUpgradeDB()
    {
        if (get_option('WPF_DB_VERSION') < self::WPFDBV) {
            // We need to upgrade the database
            self::forceUpgradeDB();
        }
    }

    public static function forceUpgradeDB()
    {
        // We are upgrading the DB forcedly
        \WPPayForm\Database\Migrations\TransactionsTable::migrate(true);
        \WPPayForm\Database\Migrations\MetaTable::migrate(true);
        \WPPayForm\Database\Migrations\Subscriptions::migrate(true);
        update_option('WPF_DB_VERSION', self::WPFDBV, false);
    }
}
