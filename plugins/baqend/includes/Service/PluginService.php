<?php

namespace Baqend\WordPress\Service;

require_once(ABSPATH . 'wp-admin/includes/plugin.php');

/**
 * Class PluginService created on 13.12.19.
 *
 * @author Kevin Twesten
 * @package Baqend\WordPress\Service
 */
class PluginService {

    /**
     * Returns a list of all active plugins.
     *
     * @return string[] An array of active plugins
     */
    public function get_active_plugins() {
        $apl = get_option( 'active_plugins' );
        $plugins = get_plugins();
        $activated_plugins = array();
        foreach ( $apl as $p ) {
            if ( isset( $plugins[ $p ] ) ) {
                array_push($activated_plugins, $plugins[ $p ] );
            }
        }

        return $activated_plugins;
    }

    /**
     * Checks whether a given plugin is activated.
     *
     * @param string The name of the plugin to be checked.
     * @param bool Whether the name must match completely.
     * @return bool True, if the plugin is active, false otherwise.
     */
    public function is_plugin_active( $plugin_name, $strict = true ) {
        $plugin_list = $this->get_active_plugins();
        $matches = array_filter($plugin_list, function( $plugin ) use ( $plugin_name, $strict ) {
            if ( ! $strict ) {
                return strpos( $plugin['Name'], $plugin_name ) !== false;
            }
            return $plugin['Name'] === $plugin_name;
        });

        return count($matches) > 0;
    }
}
