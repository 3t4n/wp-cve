<?php
namespace FormInteg\IZCRMEF\Core\Util;

/**
 * Class handling plugin uninstallation.
 *
 * @since 1.0.0
 * @access private
 * @ignore
 */
final class UnInstallation
{
    /**
     * Registers functionality through WordPress hooks.
     *
     * @since 1.0.0-alpha
     */
    public function register()
    {
        $option = get_option('izcrmef_app_conf');
        if (isset($option->erase_db)) {
            add_action('izcrmef_uninstall', [self::class, 'uninstall']);
        }
    }

    public static function uninstall()
    {
        global $wpdb;
        $columns = ['izcrmef_db_version', 'izcrmef_installed', 'izcrmef_version'];

        $tableArray = [
            $wpdb->prefix . 'izcrmef_flow',
            $wpdb->prefix . 'izcrmef_log',
        ];
        foreach ($tableArray as $tablename) {
            $wpdb->query("DROP TABLE IF EXISTS $tablename");
        }

        $columns = $columns + ['izcrmef_app_conf'];

        foreach ($columns as $column) {
            $wpdb->query("DELETE FROM `{$wpdb->prefix}options` WHERE option_name='$column'");
        }
        $wpdb->query("DELETE FROM `{$wpdb->prefix}options` WHERE `option_name` LIKE '%izcrmef_webhook_%'");
    }
}
