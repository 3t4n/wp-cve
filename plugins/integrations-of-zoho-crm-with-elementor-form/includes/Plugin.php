<?php
namespace FormInteg\IZCRMEF;

/**
 * Main class for the plugin.
 *
 * @since 1.0.0-alpha
 */

use FormInteg\IZCRMEF\Core\Hooks\HookService;
use FormInteg\IZCRMEF\Core\Database\DB;
use FormInteg\IZCRMEF\Admin\Admin_Bar;
use FormInteg\IZCRMEF\Core\Util\Request;
use FormInteg\IZCRMEF\Core\Util\Activation;
use FormInteg\IZCRMEF\Core\Util\Deactivation;
use FormInteg\IZCRMEF\Core\Util\UnInstallation;
use FormInteg\IZCRMEF\Core\Util\Capabilities;
use FormInteg\IZCRMEF\Core\Util\Hooks;
use FormInteg\IZCRMEF\Log\LogHandler;

final class Plugin
{
    /**
     * Main instance of the plugin.
     *
     * @since 1.0.0-alpha
     * @var   Plugin|null
     */
    private static $_instance = null;

    /**
     * Initialize the hooks
     *
     * @return void
     */
    public function initialize()
    {
        Hooks::add('plugins_loaded', [$this, 'init_plugin'], 12);
        (new Activation())->activate();
        (new Deactivation())->register();
        (new UnInstallation())->register();
    }

    public function init_plugin()
    {
        Hooks::add('init', [$this, 'init_classes'], 8);
        Hooks::add('init', [$this, 'integrationlogDelete'], 11);
        Hooks::filter('plugin_action_links_' . plugin_basename(IZCRMEF_PLUGIN_MAIN_FILE), [$this, 'plugin_action_links']);
    }

    /**
     * Instantiate the required classes
     *
     * @return void
     */
    public function init_classes()
    {
        static::update_tables();
        if (Request::Check('admin')) {
            (new Admin_Bar())->register();
        }
        new HookService();
        global $wp_rewrite;
        define('IZCRMEF_API_MAIN', get_site_url() . ($wp_rewrite->permalink_structure ? '/wp-json' : '/?rest_route='));
    }

    /**
     * Plugin action links
     *
     * @param  array $links
     *
     * @return array
     */
    public function plugin_action_links($links)
    {
        $links[] = '<a href="https://docs.bit-integrations.bitapps.pro" target="_blank">' . __('Docs', 'elementor-to-zoho-crm') . '</a>';

        return $links;
    }

    /**
     * Retrieves the main instance of the plugin.
     *
     * @since 1.0.0-alpha
     *
     * @return Plugin Plugin main instance.
     */
    public static function instance()
    {
        return static::$_instance;
    }

    public static function update_tables()
    {
        if (!Capabilities::Check('manage_options')) {
            return;
        }

        $installed_db_version = get_site_option('izcrmef_db_version');
        if (version_compare($installed_db_version, IZCRMEF_DB_VERSION, '<')) {
            DB::migrate();
        }
    }

    public static function integrationlogDelete()
    {
        $option = get_option('izcrmef_app_conf');
        if (isset($option->enable_log_del) && isset($option->day)) {
            LogHandler::logAutoDelte($option->day);
        }
    }

    public static function load()
    {
        if (null !== static::$_instance) {
            return false;
        }
        static::$_instance = new static();
        static::$_instance->initialize();
        return true;
    }
}
