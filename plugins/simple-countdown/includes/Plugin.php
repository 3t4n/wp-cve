<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR;

defined( 'ABSPATH' ) || exit;

use GPLSCore\GPLS_PLUGIN_WPSCTR\Base;

/**
 * Plugin Class for Activation - Deactivation - Uninstall.
 *
 */
class Plugin extends Base {

    /**
     * Plugin is activated.
     *
     * @return void
     */
    public static function activated() {
        // Activation Custom Code here...
    }

    /**
     * Plugin is Deactivated.
     *
     * @return void
     */
    public static function deactivated() {
        // Deactivation Custom Code here...
    }

    /**
     * Plugin is Uninstalled.
     *
     * @return void
     */
    public static function uninstalled() {
        // Uninstall Custom Code here...
    }
}
