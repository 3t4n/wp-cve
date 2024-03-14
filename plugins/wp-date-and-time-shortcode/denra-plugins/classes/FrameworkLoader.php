<?php

/**
 * Framework Loader
 *
 * Finds the plugin with the highest Framework version and loads it.
 *
 * @author     Denra.com aka SoftShop Ltd <support@denra.com>
 * @copyright  2019 Denra.com aka SoftShop Ltd
 * @license    GPLv2 or later
 * @version    1.1.1
 * @link       https://www.denra.com/
 */

namespace Denra\Plugins;

class FrameworkLoader {
    
    public static $framework_id = 'denra-plugins';
    public static $framework_version = '0.0';
    public static $framework_plugin_id;
    
    // Set plugin activation, deactivation and uninstall hooks
    public static function initHooks($plugin_id, $plugin_data) {
        
        $plugin_class_hooks = $plugin_data['class'] . 'Hooks';
        $plugin_class_hooks_full = '\Denra\Plugins\\' . $plugin_class_hooks;
        $path_plugin_class_hooks = $plugin_data['dir'] . 'plugin/classes/' . $plugin_class_hooks . '.php';
        require_once $path_plugin_class_hooks;
        $plugin_class_hooks_full::$plugin_id = $plugin_id;
        $plugin_class_hooks_full::$plugin_class = $plugin_class_hooks;

        \register_activation_hook($plugin_data['file'], [$plugin_class_hooks_full, 'activate']);
        \register_deactivation_hook($plugin_data['file'], [$plugin_class_hooks_full, 'deactivate']);
        \register_uninstall_hook($plugin_data['file'], [$plugin_class_hooks_full, 'uninstall']);
        
    }
    
    // Framework loading method
    public static function loadFramework() {
        
        global $denra_plugins;
        
        // Find the highest available framework version
        foreach ($denra_plugins['data'] as $plugin_id => $plugin_data) {
            if (version_compare(self::$framework_version, $plugin_data['framework_version']) == -1) {
                self::$framework_version = $plugin_data['framework_version'];
                self::$framework_plugin_id = $plugin_id;
            }
        }
        
        // Load the framework classes
        $path_classes = $denra_plugins['data'][self::$framework_plugin_id]['dir'] . self::$framework_id . '/classes/';
        require_once $path_classes . 'Basic.php';
        require_once $path_classes . 'BasicExtra.php';
        require_once $path_classes . 'Framework.php';
        require_once $path_classes . 'Plugin.php';
        
        $denra_plugins['framework'] = new Framework(self::$framework_id, [
            'framework_plugin_id' => self::$framework_plugin_id
        ]);
        
    }
    
}