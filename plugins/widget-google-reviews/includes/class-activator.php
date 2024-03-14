<?php

namespace WP_Rplg_Google_Reviews\Includes;

use WP_Rplg_Google_Reviews\Includes\Core\Database;

class Activator {

    private $database;

    public function __construct(Database $database) {
        $this->database = $database;
    }

    public function options() {
        return array(
            'grw_version',
            'grw_active',
            'grw_google_api_key',
            'grw_language',
            'grw_activation_time',
            'grw_auth_code',
            'grw_debug_mode',
            'grw_feed_ids',
            'grw_do_activation',
            'grw_demand_assets',
            'grw_revupd_cron',
            'grw_revupd_cron_timeout',
            'grw_revupd_cron_log',
            'grw_debug_refresh',
            'grw_rev_notice_hide',
            'rplg_rev_notice_show',
            'grw_rate_us',
        );
    }

    public function register() {
        add_action('init', array($this, 'check_version'));
        add_filter('https_ssl_verify', '__return_false');
        add_filter('block_local_requests', '__return_false');
    }

    public function check_version() {
        if (version_compare(get_option('grw_version'), GRW_VERSION, '<')) {
            $this->activate();
        }
    }

    /**
	 * Activates the plugin on a multisite
	 */
    public function activate() {
        $network_wide = get_option('grw_is_multisite');
        if ($network_wide) {
            $this->activate_multisite();
        } else {
            $this->activate_single_site();
        }
    }

    private function activate_multisite() {
        global $wpdb;

        $site_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

        foreach($site_ids as $site_id) {
            switch_to_blog($site_id);
            $this->activate_single_site();
            restore_current_blog();
        }
    }

    private function activate_single_site() {
        $current_version     = GRW_VERSION;
        $last_active_version = get_option('grw_version');

        if (empty($last_active_version)) {
            $this->first_install();
            update_option('grw_version', $current_version);
            update_option('grw_auth_code', $this->random_str(127));
            update_option('grw_revupd_cron', '1');
        } elseif ($last_active_version !== $current_version) {
            $this->exist_install($current_version, $last_active_version);
            update_option('grw_version', $current_version);
            update_option('grw_revupd_cron', '1');
        }
    }

    private function first_install() {
        $this->database->create();

        add_option('grw_active', '1');
        add_option('grw_google_api_key', '');
    }

    private function exist_install($current_version, $last_active_version) {
        $this->update_db($last_active_version);
    }

    public function update_db($last_active_version) {
        global $wpdb;

        switch($last_active_version) {

            case version_compare($last_active_version, '1.8.2', '<'):
                $wpdb->query("ALTER TABLE " . $wpdb->prefix . Database::BUSINESS_TABLE . " ADD review_count INTEGER");
                $place_ids = $wpdb->get_col("SELECT place_id FROM " . $wpdb->prefix . Database::BUSINESS_TABLE . " WHERE rating > 0 LIMIT 5");
                foreach($place_ids as $place_id) {
                    //TODO: grw_refresh_reviews(array($place_id));
                }

            case version_compare($last_active_version, '1.8.7', '<'):
                $row = $wpdb->get_results(
                    "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS " .
                    "WHERE table_name = '" . $wpdb->prefix . Database::REVIEW_TABLE . "' AND column_name = 'hide'"
                );
                if(empty($row)){
                    $wpdb->query("ALTER TABLE " . $wpdb->prefix . Database::REVIEW_TABLE . " ADD hide VARCHAR(1) DEFAULT '' NOT NULL");
                }

            case version_compare($last_active_version, '2.0.1', '<'):
                $grw_auth_code = get_option('grw_auth_code');
                if (!$grw_auth_code || strlen($grw_auth_code) == 0) {
                    update_option('grw_auth_code', $this->random_str(127));
                }

            case version_compare($last_active_version, '2.1.5', '<'):
                if (!function_exists('drop_index') || !function_exists('dbDelta')) {
                    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                }
                if (!function_exists('maybe_drop_column')) {
                    // Define 'maybe_drop_column' function without including install-helper.php due to error:
                    // Fatal error: Cannot redeclare maybe_create_table()
                    // (previously declared in /wp-admin/install-helper.php:52) in /wp-admin/includes/upgrade.php on line 1616
                    function maybe_drop_column($table_name, $column_name, $drop_ddl) {
                        global $wpdb;
                        foreach ($wpdb->get_col( "DESC $table_name", 0) as $column) {
                            if ($column === $column_name) {
                                $wpdb->query($drop_ddl);
                                foreach ($wpdb->get_col("DESC $table_name", 0) as $column) {
                                    if ($column === $column_name) {
                                        return false;
                                    }
                                }
                            }
                        }
                        return true;
                    }
                }
                if (drop_index($wpdb->prefix . Database::REVIEW_TABLE, 'grp_google_review_hash')) {
                    maybe_drop_column(
                        $wpdb->prefix . Database::REVIEW_TABLE,
                        "hash",
                        "ALTER TABLE " . $wpdb->prefix . Database::REVIEW_TABLE . " DROP COLUMN hash"
                    );
                }
                $sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "grp_google_stats (".
                    "id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,".
                    "google_place_id BIGINT(20) UNSIGNED NOT NULL,".
                    "time INTEGER NOT NULL,".
                    "rating DOUBLE PRECISION,".
                    "review_count INTEGER,".
                    "PRIMARY KEY (`id`),".
                    "INDEX grp_google_place_id (`google_place_id`)".
                    ") " . $wpdb->get_charset_collate() . ";";
                dbDelta($sql);
        }
    }

    /**
	 * Creates the plugin database on a multisite
	 */
    public function create_db() {
        $network_wide = get_option('grw_is_multisite');
        if ($network_wide) {
            $this->create_db_multisite();
        } else {
            $this->create_db_single_site();
        }
    }

    private function create_db_multisite() {
        global $wpdb;

        $site_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

        foreach($site_ids as $site_id) {
            switch_to_blog($site_id);
            $this->create_db_single_site();
            restore_current_blog();
        }
    }

    private function create_db_single_site() {
        $this->database->create();
    }

    /**
	 * Drops the plugin database on a multisite
	 */
    public function drop_db($multisite = false) {
        $network_wide = get_option('grw_is_multisite');
        if ($multisite && $network_wide) {
            $this->drop_db_multisite();
        } else {
            $this->drop_db_single_site();
        }
    }

    private function drop_db_multisite() {
        global $wpdb;

        $site_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

        foreach($site_ids as $site_id) {
            switch_to_blog($site_id);
            $this->drop_db_single_site();
            restore_current_blog();
        }
    }

    private function drop_db_single_site() {
        $this->database->drop();
    }

    /**
	 * Delete all options of the plugin on a multisite
	 */
    public function delete_all_options($multisite = false) {
        $network_wide = get_option('grw_is_multisite');
        if ($multisite && $network_wide) {
            $this->delete_all_options_multisite();
        } else {
            $this->delete_all_options_single_site();
        }
    }

    private function delete_all_options_multisite() {
        global $wpdb;

        $site_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

        foreach($site_ids as $site_id) {
            switch_to_blog($site_id);
            $this->delete_all_options_single_site();
            restore_current_blog();
        }
    }

    private function delete_all_options_single_site() {
        foreach ($this->options() as $opt) {
            delete_option($opt);
        }
    }

    /**
	 * Delete all feeds of the plugin on a multisite
	 */
    public function delete_all_feeds($multisite = false) {
        $network_wide = get_option('grw_is_multisite');
        if ($multisite && $network_wide) {
            $this->delete_all_feeds_multisite();
        } else {
            $this->delete_all_feeds_single_site();
        }
    }

    private function delete_all_feeds_multisite() {
        global $wpdb;

        $site_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

        foreach($site_ids as $site_id) {
            switch_to_blog($site_id);
            $this->delete_all_feeds_single_site();
            restore_current_blog();
        }
    }

    private function delete_all_feeds_single_site() {
        $args = array(
            'post_type'      => Post_Types::FEED_POST_TYPE,
            'post_status'    => array('any', 'trash'),
            'posts_per_page' => -1,
            'fields'         => 'ids',
        );

        $query = new \WP_Query($args);
        $grw_posts = $query->posts;

        if (!empty($grw_posts)) {
            foreach ($grw_posts as $grw_post) {
                wp_delete_post($grw_post, true);
            }
        }
    }

    private function random_str($len) {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charlen = strlen($chars);
        $randstr = '';
        for ($i = 0; $i < $len; $i++) {
            $randstr .= $chars[rand(0, $charlen - 1)];
        }
        return $randstr;
    }

}
