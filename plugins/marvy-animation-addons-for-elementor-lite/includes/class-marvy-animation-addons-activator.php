<?php

/**
 * Fired during plugin activation
 *
 * @link       https://iqonic.design/
 * @since      1.7.2
 *
 * @package    Marvy_Animation_Addons
 * @subpackage Marvy_Animation_Addons/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.7.2
 * @package    Marvy_Animation_Addons
 * @subpackage Marvy_Animation_Addons/includes
 * @author     Iqonic Design <hello@iqonic.design>
 */
class Marvy_Animation_Addons_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.7.2
     */
    public static function activate()
    {
        marvy_plugin_activation();
        $defaults = array_fill_keys(array_keys($GLOBALS['marvy_config']['bg-animation']), 1);
        $values = get_option('marvy_option_settings');
        if(!empty($values)){
            $defaults = array_merge($defaults,$values);
            update_option('marvy_option_settings',$defaults);
        }else{
            update_option('marvy_option_settings',$defaults);
        }
        return true;
    }

}