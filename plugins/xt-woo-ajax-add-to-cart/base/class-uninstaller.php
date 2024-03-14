<?php

/**
 * Fired during plugin uninstall
 *
 * @link       http://xplodedthemes.com
 * @since      1.0.0
 *
 * @package    XT_WOOATC
 * @subpackage XT_WOOATC/includes
 */

/**
 * Fired during plugin uninstall.
 *
 * This class defines all code necessary to run during the plugin's uninstall.
 *
 * @since      1.0.0
 * @package    XT_WOOATC
 * @subpackage XT_WOOATC/includes
 * @author     XplodedThemes 
 */
class XT_WOOATC_Uninstaller {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function uninstall() {

        do_action('xt_wooatc_uninstall');
    }

}

XT_WOOATC_Uninstaller::uninstall();