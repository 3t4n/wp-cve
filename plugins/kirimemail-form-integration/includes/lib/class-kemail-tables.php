<?php if (!defined('ABSPATH')) {
    exit;
}

abstract class Kemail_Tables
{
    public static function register()
    {
        register_activation_hook(__FILE__, array(self::class, 'ke_wp_plugin_installed'));
        add_action('plugins_loaded', array(self::class, 'ke_wp_plugin_activated'));
    }

    public static function ke_wp_plugin_installed()
    {
        global $wpdb;
        $installed_ver = get_option("ke-wp-version");
        if ($installed_ver !== KIRIMEMAIL_PLUGIN_VERSION) {
            $table_name = $wpdb->prefix . 'ke_page_form';

            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                post_id mediumint NOT NULL,
                widget text NULL,
                bar text NULL,
                PRIMARY KEY  (id),
                UNIQUE INDEX post_id (post_id)
            ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);

            $table_name = $wpdb->prefix . 'ke_page_lp';

            $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                post_id mediumint NOT NULL,
                landing_page text NOT NULL,
                PRIMARY KEY  (id),
                UNIQUE INDEX post_id (post_id)
            ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);

            update_option('ke-wp-version', KIRIMEMAIL_PLUGIN_VERSION, true);
        }
    }

    public static function ke_wp_plugin_activated()
    {
        if (get_site_option('ke-wp-version') != KIRIMEMAIL_PLUGIN_VERSION) {
            self::ke_wp_plugin_installed();
        }
    }
}
