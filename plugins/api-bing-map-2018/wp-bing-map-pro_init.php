<?php

if( !defined('ABSPATH') ) die('No Access to this page');

require_once( 'includes/BingMapPro_Plugin.php');

class BingMapPro_Plugin_init{
    public static function BingMapPro_init( $file ){

        global $BMP_PLUGIN_VERSION;
        $aPlugin = new BingMapPro_Plugin\BingMapPro_Plugin();
        
        $aPlugin->bmp_check_capabilities();

        if( $aPlugin->getVersionSaved() != $BMP_PLUGIN_VERSION ){
            $aPlugin->setVersionSaved( $BMP_PLUGIN_VERSION );

            $aPlugin->install();

        }else{
            $aPlugin->upgrade();
        }

        $aPlugin->addActionsAndFilters();

        if( ! $file ){
            $file = __FILE__;
        }
    

        register_activation_hook( $file, array( &$aPlugin, 'activate') );

        register_deactivation_hook( $file, array( &$aPlugin, 'deactivate') );        
        
    }
}