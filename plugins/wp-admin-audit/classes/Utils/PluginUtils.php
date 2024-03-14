<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}
require_once( ABSPATH . 'wp-includes/pluggable.php');
require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
require_once( ABSPATH . 'wp-admin/includes/file.php' );
require_once( ABSPATH . 'wp-admin/includes/misc.php' );
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

if(!class_exists('WADA_Quiet_Upgrader_Skin')){
    /**
     * https://gist.github.com/hansschuijff/a6c4edb4e5162c1c16006d21fa8b9c8f
     * Overwrite the feedback method in the WP_Upgrader_Skin
     * to suppress the normal feedback.
     */
    class WADA_Quiet_Upgrader_Skin extends WP_Upgrader_Skin {
        /*
         * Suppress normal upgrader feedback / output
         */
        public function feedback( $string, ...$args ) {
            /* no output */
        }
    }
}

class WADA_PluginUtils
{
    public static function getAllPlugins($onlyActivePlugins = false){
        if (!function_exists('get_plugins')){
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $allPlugins = get_plugins();
        $activePlugins = get_option('active_plugins');
        //WADA_Log::debug('getAllPlugins activePlugins: '.print_r($activePlugins, true));

        $objArr = array();
        foreach($allPlugins AS $pluginPath => $plugin){
            $plg = (object)$plugin;
            $plg->active = in_array($pluginPath, $activePlugins);
            $plg->pluginPath = $pluginPath;
            if(!$onlyActivePlugins || $plg->active) {
                $objArr[$pluginPath] = $plg;
            }
        }

        return $objArr;
    }

    public static function isPluginInstalled($pluginPath){
        $allPlugins = self::getAllPlugins();
        return array_key_exists($pluginPath, $allPlugins);
    }

    public static function isPluginActive($pluginPath){
        $allPlugins = self::getAllPlugins(true);
        //WADA_Log::debug('isPluginActive all (active) plugins: '.print_r($allPlugins, true));
        $res = array_key_exists($pluginPath, $allPlugins);
        WADA_Log::debug('isPluginActive '.$pluginPath.': '.($res ? 'yes' : 'no'));
        return $res;
    }


    /**
     * https://gist.github.com/hansschuijff/a6c4edb4e5162c1c16006d21fa8b9c8f
     * Activates a given plugin.     *
     * If needed it downloads and/or installs the plugin first.
     *
     * @param string $pluginPath The plugin's basename (containing the plugin's base directory and the bootstrap filename).
     * @return WP_Error|bool
     */
    public static function activatePlugin($pluginPath) {
        $plugin_mainfile = trailingslashit( WP_PLUGIN_DIR ) . $pluginPath;
        /* Nothing to do, when plugin already active.
         *
         * WARNING: When a plugin has been removed by ftp,
         *          WordPress will still consider it active,
         *          until the plugin list has been visited
         *          (and it checks the existence of it).
         */
        if ( is_plugin_active($pluginPath) ) {
            // Make sure the plugin is still there (files could be removed without WordPress noticing)
            $error = validate_plugin( $pluginPath );
            if ( ! is_wp_error( $error ) ) {
                return true;
            }
        }

        // Now we activate, when install has been successful.
        if ( ! self::isPluginInstalledV2( $pluginPath ) ) {
            return new WP_Error('plugin_not_installed', 'Error: Plugin not installed (' . $pluginPath . ')');
        }

        $error = validate_plugin( $pluginPath );
        if ( is_wp_error( $error ) ) {
            return $error;
        }

        $error = activate_plugin( $plugin_mainfile );
        if ( is_wp_error( $error ) ) {
            return $error;
        }

        return true;
    }


    /**
     * https://gist.github.com/hansschuijff/a6c4edb4e5162c1c16006d21fa8b9c8f
     * Install a given plugin.
     *
     * @param  object      $plgObj Plugin object (slug,name,package,version, ...)
     * @return bool|WP_Error
     */
    public static function installPlugin($plgObj) {

        WADA_Log::debug('installPlugin: '.print_r($plgObj, true));

        $skin      = new Plugin_Installer_Skin( array( 'api' => $plgObj ) );
        // Replace new Plugin_Installer_Skin with new WADA_Quiet_Upgrader_Skin when output needs to be suppressed.
        //$skin      = new WADA_Quiet_Upgrader_Skin( array( 'api' => $plg ) );
        $upgrader  = new Plugin_Upgrader( $skin );
        return $upgrader->install( $plgObj->download_link );
    }

    public static function updatePlugin($plgObj, $pluginPath){
        WADA_Log::debug('updatePlugin: '.print_r($plgObj, true));
        $skin      = new Plugin_Installer_Skin( array( 'api' => $plgObj ) );
        // Replace new Plugin_Installer_Skin with new WADA_Quiet_Upgrader_Skin when output needs to be suppressed.
        //$skin      = new WADA_Quiet_Upgrader_Skin( array( 'api' => $plg ) );
        $upgrader  = new Plugin_Upgrader( $skin );
        $upgrader->strings['process_success_specific'] = sprintf(__( 'Successfully updated plugin <strong>%1$s %2$s</strong>.', 'wp-admin-audit' ), $plgObj->name, $plgObj->version);
        return $upgrader->upgrade( $pluginPath );
    }


    /**
     * https://gist.github.com/hansschuijff/a6c4edb4e5162c1c16006d21fa8b9c8f
     * Is plugin installed?
     *
     * Get_plugins() returns an array containing all installed plugins
     * with the plugin basename as key.
     *
     * When you pass the plugin dir to get_plugins(),
     * it will return an empty array if that plugin is not yet installed,
     *
     * When the plugin is installed it will return an array with that plugin's data,
     * using the plugins main filename as key (so not the basename).
     *
     * @param  string  $pluginPath Plugin basename.
     * @return boolean         True when installed, otherwise false.
     */
    public static function isPluginInstalledV2( $pluginPath ) {
        $plugins = get_plugins( '/'.self::getPluginDir( $pluginPath ) );
        if ( ! empty( $plugins ) ) {
            return true;
        }
        return false;
    }

    /**
    * Extracts the plugin's directory (=slug for api) from the plugin basename.
    *
    * @param string $pluginPath Plugin basename.
    * @return string        The directory-part of the plugin basename.
    */
    public static function getPluginDir( $pluginPath ) {
        $chunks = explode( '/', $pluginPath );
        if ( ! is_array( $chunks ) ) {
            $pluginDir = $chunks;
        } else{
            $pluginDir = $chunks[0];
        }
        return $pluginDir;
    }


}
