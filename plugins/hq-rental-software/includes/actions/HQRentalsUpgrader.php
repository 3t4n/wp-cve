<?php

namespace HQRentalsPlugin\HQRentalsActions;

class HQRentalsUpgrader
{
    protected $pluginName = 'hq-rental-software/hq-wordpress.php';

    protected $dependencies = array(
        ABSPATH . 'wp-admin/includes/class-wp-upgrader.php',
        ABSPATH . 'wp-admin/includes/misc.php',
        ABSPATH . 'wp-admin/includes/class-wp-upgrader-skin.php',
        ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php',
        ABSPATH . 'wp-admin/includes/file.php'
    );

    public function __construct()
    {
        $this->requireDependencies();
        $this->upgrader = new \Plugin_Upgrader();
    }

    public function upgradePlugin()
    {
        $result = $this->upgrader->upgrade($this->pluginName);
        if ($result) {
            $this->activatePlugin($this->pluginName);
        }
        return $result;
    }

    public function activatePlugin($plugin)
    {
        $current = get_option('active_plugins');
        $plugin = plugin_basename(trim($plugin));
        if (!in_array($plugin, $current)) {
            $current[] = $plugin;
            sort($current);
            do_action('activate_plugin', trim($plugin));
            update_option('active_plugins', $current);
            do_action('activate_' . trim($plugin));
            do_action('activated_plugin', trim($plugin));
        }

        return null;
    }

    protected function requireDependencies()
    {
        foreach ($this->dependencies as $file) {
            if (file_exists($file)) {
                require_once($file);
            }
        }
    }
}
