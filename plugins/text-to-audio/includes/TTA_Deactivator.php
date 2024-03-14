<?php
namespace TTA;
/**
 * Fired during plugin deactivation
 *
 * @link       http://azizulhasan.com
 * @since      1.0.0
 *
 * @package    TTA
 * @subpackage TTA/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    TTA
 * @subpackage TTA/includes
 * @author     Azizul Hasan <azizulhasan.cr@gmail.com>
 */
class TTA_Deactivator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function deactivate() {
        if(!function_exists('is_plugin_active') ){
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        if(is_plugin_active('text-to-audio-pro/text-to-audio-pro.php')){
            deactivate_plugins(['text-to-audio-pro/text-to-audio-pro.php'], true);
            $url = admin_url( 'plugins.php?deactivate=true' );
            header( "Location: $url" );
            die();
        }
    }

}
