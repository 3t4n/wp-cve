<?php

/**
 * Common functions.
 *
 * @author  Social Boost
 * @package SOCIALBOOST
 * @since   3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Get config details from JSON file
 *
 * @return bool
 */
if(!function_exists('sb_get_app_config')) {
    function sb_get_app_config() {

        $config = array();

        try
        {
            $config_file = SB_PLUGIN_BASE_PATH.'/configs/app.json';    
            if(file_exists($config_file)) {
                $config_json = file_get_contents($config_file);

                if(!empty($config_json))
                    $config = json_decode($config_json, true);
            }
        }
        catch (Exception $e)
        { }

        return $config;
    }
}


/**
 * write config details into JSON file
 *
 * @return bool
 */
if(!function_exists('sb_set_app_config')) {
    function sb_set_app_config($config) {
        try
        {
            $config_json = json_encode($config);
            $config_file = SB_PLUGIN_BASE_PATH.'/configs/app.json';

            if(!is_writable($config_file))
                throw new Exception('Config file is not created. Permission issue');

            if(file_put_contents($config_file, $config_json) == FALSE) {

                throw new Exception('Config file is not created');
            }

            $ret = TRUE;
        } catch (Exception $e) {
            $ret = FALSE;
        }

        return $ret;
    }
}
