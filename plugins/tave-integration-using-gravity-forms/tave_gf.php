<?php
/**
 * Táve Integration using Gravity Forms
 *
 * @link https://help.tave.com/en/articles/640287-using-gravity-forms
 * @author Jason Pirkey <jason@tave.com>
 * @copyright Táve 2019
 * @version 1.0.11
 * @since 1.0.0
 * @package Tave_GF
 *
 * @wordpress-plugin
 * Plugin Name:       Táve Integration using Gravity Forms
 * Plugin URI:        http://help.tave.com/getting-started/contact-forms/using-gravity-forms
 * Description:       Integrate Gravity Forms with Táve Studio Manager
 * Version:           1.0.11
 * Author:            Táve
 * Author URI:        https://tave.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tave_gf
 */

if (!defined('WPINC')) {
    die;
}

define('TAVE_GF_ADDON_VERSION', '1.0.11');

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */

class TaveGFAddOnBootstrap
{
    public static function run()
    {
        if (!method_exists('GFForms', 'include_feed_addon_framework')) {
            return;
        }

        require_once 'tave_gf_addon.php';
        GFAddOn::register('TaveGFAddOn');
    }
}


add_action('gform_loaded', array('TaveGFAddOnBootstrap', 'run'), 5);
