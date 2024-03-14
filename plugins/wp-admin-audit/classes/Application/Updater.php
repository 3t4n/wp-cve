<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Updater
{
    public static function getUpdateStatus(){
        $update_plugins = get_site_transient( 'update_plugins' );
        $pluginSlug = basename(realpath(__DIR__.'/../../'));
        $wadaSlug = $pluginSlug.'/'.$pluginSlug.'.php';
        $updateInfo = $htmlIcon = $newVersionNumber = '';
        $updateFound = false;
        if($update_plugins){
            if(property_exists($update_plugins, 'response')
                && array_key_exists($wadaSlug, $update_plugins->response)
                && property_exists($update_plugins->response[$wadaSlug], 'new_version')
            ){
                $updateFound = true;
                $newVersionNumber = $update_plugins->response[$wadaSlug]->new_version;
                $htmlIcon = '<span class="dashicons dashicons-bell"></span>';
                $updateInfo = sprintf(__('new version available: %s', 'wp-admin-audit'), $newVersionNumber);
            }else{
                if(property_exists($update_plugins, 'no_update')
                    && array_key_exists($wadaSlug, $update_plugins->no_update)
                ) {
                    $htmlIcon = '<span class="dashicons dashicons-yes"></span>';
                    $updateInfo = __('latest version', 'wp-admin-audit');
                }
            }
        }

        $updateStatus = new stdClass();
        $updateStatus->updateFound = $updateFound;
        $updateStatus->newVersionNumber = $newVersionNumber;
        $updateStatus->updateInfoText = $updateInfo;
        $updateStatus->htmlIcon = $htmlIcon;

        return $updateStatus;
    }
    
}