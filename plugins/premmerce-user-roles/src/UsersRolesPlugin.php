<?php namespace Premmerce\UsersRoles;

use Premmerce\SDK\V2\FileManager\FileManager;
use Premmerce\SDK\V2\Plugin\PluginInterface;
use Premmerce\UsersRoles\Admin\Admin;

/**
 * Class UsersRolesPlugin
 * @package Premmerce\UsersRoles
 */
class UsersRolesPlugin implements PluginInterface
{
    const DOMAIN = 'premmerce-users-roles';

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * PluginManager constructor.
     *
     * @param string $mainFile
     *
     */
    public function __construct($mainFile)
    {
        $this->fileManager = new FileManager($mainFile);

        add_action('init', array($this, 'loadTextDomain'));
    }

    /**
     * Run plugin part
     */
    public function run()
    {
        if (is_admin()) {
            new Admin($this->fileManager);
        }
    }

    /**
     * Load plugin translations
     */
    public function loadTextDomain()
    {
        $name = $this->fileManager->getPluginName();
        load_plugin_textdomain('premmerce-users-roles', false, $name . '/languages/');
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
