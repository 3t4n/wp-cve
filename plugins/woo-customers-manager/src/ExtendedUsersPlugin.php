<?php namespace Premmerce\ExtendedUsers;

use Premmerce\ExtendedUsers\Admin\Admin;
use Premmerce\SDK\V2\FileManager\FileManager;
use Premmerce\SDK\V2\Notifications\AdminNotifier;
use Premmerce\SDK\V2\Plugin\PluginInterface;

/**
 * Class ExtendedUsersPlugin
 *
 * @package Premmerce\ExtendedUsers
 */
class ExtendedUsersPlugin implements PluginInterface
{
    const DOMAIN = 'woo-customers-manager';

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @var AdminNotifier
     */
    private $notifier;

    /**
     * PluginManager constructor.
     *
     * @param $mainFile
     */
    public function __construct($mainFile)
    {
        $this->fileManager = new FileManager($mainFile);
        $this->notifier    = new AdminNotifier();

        add_action('init', array($this, 'loadTextDomain'));
        add_action('admin_init', array($this, 'checkRequirePlugins'));
    }

    /**
     * Run plugin part
     */
    public function run()
    {
        $valid = count($this->validateRequiredPlugins()) === 0;

        if (is_admin() && $valid) {
            new Admin($this->fileManager);
        }
    }

    /**
     * Load plugin translations
     */
    public function loadTextDomain()
    {
        $name = $this->fileManager->getPluginName();
        load_plugin_textdomain('woo-customers-manager', false, $name . '/languages/');
    }

    /**
     * Check required plugins and push notifications
     */
    public function checkRequirePlugins()
    {
        $message = __('The %s plugin requires %s plugin to be active!', 'woo-customers-manager');

        $plugins = $this->validateRequiredPlugins();

        if (count($plugins)) {
            foreach ($plugins as $plugin) {
                $error = sprintf($message, 'WooCommerce Customers Manager', $plugin);
                $this->notifier->push($error, AdminNotifier::ERROR, false);
            }
        }
    }

    /**
     * Validate required plugins
     *
     * @return array
     */
    private function validateRequiredPlugins()
    {
        $plugins = array();

        if (!function_exists('is_plugin_active')) {
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        /**
         * Check if WooCommerce is active
         **/
        if (!(is_plugin_active('woocommerce/woocommerce.php') || is_plugin_active_for_network('woocommerce/woocommerce.php'))) {
            $plugins[] = '<a target="_blank" href="https://wordpress.org/plugins/woocommerce/">WooCommerce</a>';
        }

        return $plugins;
    }

    /**
     * Fired when the plugin is activated
     */
    public function activate()
    {
    }

    /**
     * Fired when the plugin is deactivated
     */
    public function deactivate()
    {
    }

    /**
     * Fired when the plugin is uninstalled
     */
    public static function uninstall()
    {
    }
}
