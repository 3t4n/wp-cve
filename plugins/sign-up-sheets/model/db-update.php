<?php

namespace FDSUS\Model;

use FDSUS\Id;
use FDSUS\Model\Data as Data;
use FDSUS\Controller\Migrate;
use wpdb;

/**
 * Sign-up Sheets Database Update
 *
 * For any database related updates between versions
 */
class DbUpdate
{

    /** @var wpdb  */
    public $wpdb;

    /** @var Data  */
    public $data;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->data = new Data();

        add_action('init', array(&$this, 'check'));
        add_action(Id::PREFIX . '_dbupdate_action', array(&$this, 'asyncUpdate'));
    }

    /**
     * Check if we need to update
     */
    public function check()
    {
        if (Id::version() != get_option('dls_sus_db_version')
            || get_option('dls_sus_db_version_type') != (Id::isPro() ? 'pro' : 'free')
        ) {
            $this->updateDb();
        }
    }

    /**
     * Schedule async update
     */
    public function scheduleAsyncUpdate()
    {
        wp_schedule_single_event(time(), Id::PREFIX . '_dbupdate_action');
    }

    /**
     * Do the migration.  Called by cron process
     */
    public function asyncUpdate()
    {
        if (!FDSUS_DISABLE_MIGRATE_2_0_to_2_1) {
            // Migrate 2.0 to 2.1
            $migrate = new Migrate();
            if (!$migrate->isComplete()) {
                $migrate->run();
            }
        }
    }

    /**
     * Update DB
     */
    private function updateDb()
    {
        // Change deprecated database items
        $this->wpdb->query("SHOW TABLES LIKE '{$this->data->tables['field']['name']}'");
        $fieldTableExists = ($this->wpdb->num_rows > 0) ? true : false;
        $this->wpdb->query("SHOW TABLES LIKE '{$this->wpdb->prefix}dls_sus_signup_fields'");
        $signupFieldTableExists = ($this->wpdb->num_rows > 0) ? true : false;

        if (!$fieldTableExists && $signupFieldTableExists) {
            $this->wpdb->query("RENAME TABLE {$this->wpdb->prefix}dls_sus_signup_fields TO {$this->data->tables['field']['name']}");
        }

        if ($fieldTableExists) {
            $this->wpdb->query("SHOW COLUMNS FROM {$this->data->tables['field']['name']} LIKE 'signup_id'");
            $signup_id_col_exists = ($this->wpdb->num_rows > 0) ? true : false;
            if ($signup_id_col_exists) {
                $this->wpdb->query("ALTER TABLE {$this->data->tables['field']['name']} CHANGE signup_id entity_id INT");
            }
        }

        $sql = '';

        /**
         * Filter for dbdelta sql statements
         *
         * @param string $sql
         *
         * @return string
         * @since 2.2
         */
        $sql = apply_filters('fdsus_dbdelta_sql', $sql);

        if (!empty($sql)) {
            require_once(ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'upgrade.php');
            dbDelta($sql);
        }

        if ($fieldTableExists) {
            $this->wpdb->update(
                $this->data->tables['field']['name'],
                array('entity_type' => 'signup'),
                array('entity_type' => ''),
                array('%s'),
                array('%s')
            );
        }

        if (!FDSUS_DISABLE_MIGRATE_2_0_to_2_1) {
            // Migrate 2.0 to 2.1
            $migrate = new Migrate();
            $status = $migrate->getStatus();
            if (get_option($status['state']) != 'complete') {
                delete_transient(Id::PREFIX . '_migration_timeout_rerun_count');
                $this->scheduleAsyncUpdate();
            }
        }

        // Update capabilities
        $this->data->remove_capabilities();
        $this->data->set_capabilities();

        update_option('dls_sus_db_version', Id::version());
        update_option('dls_sus_db_version_type', Id::isPro() ? 'pro' : 'free');
    }
}
