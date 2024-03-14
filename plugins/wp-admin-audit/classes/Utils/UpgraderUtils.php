<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_UpgraderUtils
{
    /**
     * @param Core_Upgrader $coreUpgrade
     */
    public static function getCoreUpgradeDigest($coreUpgrade){
        return self::getGeneralDigest($coreUpgrade);
    }

    /**
     * @param Plugin_Upgrader $pluginUpgrade
     */
    public static function getPluginInstallDigest($pluginUpgrade){
        $pluginInfo = (property_exists($pluginUpgrade, 'new_plugin_data')) ? $pluginUpgrade->new_plugin_data : null; // filled for plugin installations
        $skinObj = (property_exists($pluginUpgrade, 'skin') && $pluginUpgrade->skin) ? $pluginUpgrade->skin : null;
        $skinOptions = ($skinObj && property_exists($skinObj, 'options')) ? $skinObj->options : null;
        $infos = array();
        $futureVersion = null;
        if($pluginInfo && is_array($pluginInfo) && count($pluginInfo)){
            $futureVersion = array_key_exists('Version', $pluginInfo) ? $pluginInfo['Version'] : null;
            $infos[] = WADA_Sensor_Base::getEventInfoElement('Name', array_key_exists('Name', $pluginInfo) ? $pluginInfo['Name'] : null);
            $infos[] = WADA_Sensor_Base::getEventInfoElement('PLUGIN_TITLE', array_key_exists('Title', $pluginInfo) ? $pluginInfo['Title'] : null);
            $infos[] = WADA_Sensor_Base::getEventInfoElement('PLUGIN_URL', array_key_exists('PluginURI', $pluginInfo) ? $pluginInfo['PluginURI'] : null);
            $infos[] = WADA_Sensor_Base::getEventInfoElement('PLUGIN_AUTHOR', array_key_exists('Author', $pluginInfo) ? $pluginInfo['Author'] : null);
        }
        $overwrite = $actionType = $actionTitle = null;
        if($skinObj){
            $overwrite = property_exists($skinObj, 'overwrite') ? $skinObj->overwrite : null;
            $actionType = property_exists($skinObj, 'type') ? $skinObj->type : null;
            $actionTitle = $skinOptions && array_key_exists('title', $skinOptions) ? $skinOptions['title'] : null;
        }
        $res = self::getGeneralDigest($pluginUpgrade);
        $res->infos = array_merge($res->infos, $infos);
        $res->futureVersion = $futureVersion;
        $res->overwrite = $overwrite;
        $res->actionType = $actionType;
        $res->actionTitle = $actionTitle;
        WADA_Log::debug('getPluginInstallDigest res: '.print_r($res, true));
        return $res;
    }
    
    /**
     * @param Plugin_Upgrader $pluginUpgrade
     */
    public static function getPluginUpgradeDigest($pluginUpgrade){
        $pluginInfo = (property_exists($pluginUpgrade, 'skin') && $pluginUpgrade->skin && property_exists($pluginUpgrade->skin, 'plugin_info')) ? $pluginUpgrade->skin->plugin_info : null; // filled for plugin updates
        $infos = array();
        $priorVersion = null;
        if($pluginInfo && is_array($pluginInfo) && count($pluginInfo)){
            $priorVersion = array_key_exists('Version', $pluginInfo) ? $pluginInfo['Version'] : null;
            $infos[] = WADA_Sensor_Base::getEventInfoElement('Name', array_key_exists('Name', $pluginInfo) ? $pluginInfo['Name'] : null);
            $infos[] = WADA_Sensor_Base::getEventInfoElement('PLUGIN_TITLE', array_key_exists('Title', $pluginInfo) ? $pluginInfo['Title'] : null);
            $infos[] = WADA_Sensor_Base::getEventInfoElement('PLUGIN_URL', array_key_exists('PluginURI', $pluginInfo) ? $pluginInfo['PluginURI'] : null);
            $infos[] = WADA_Sensor_Base::getEventInfoElement('PLUGIN_AUTHOR', array_key_exists('Author', $pluginInfo) ? $pluginInfo['Author'] : null);
        }
        $res = self::getGeneralDigest($pluginUpgrade);
        $res->infos = array_merge($res->infos, $infos);
        $res->priorVersion = $priorVersion;
        return $res;
    }

    /**
     * @param Theme_Upgrader $themeUpgrade
     */
    public static function getThemeInstallDigest($themeUpgrade){
        $themeInfo = (property_exists($themeUpgrade, 'new_theme_data')) ? $themeUpgrade->new_theme_data : null; // filled for theme installations
        $skinObj = (property_exists($themeUpgrade, 'skin') && $themeUpgrade->skin) ? $themeUpgrade->skin : null;
        $skinOptions = ($skinObj && property_exists($skinObj, 'options')) ? $skinObj->options : null;
        $infos = array();
        WADA_Log::debug('getThemeInstallDigest themeInfo: '.print_r($themeInfo, true));
        WADA_Log::debug('getThemeInstallDigest skinObj: '.print_r($skinObj, true));
        WADA_Log::debug('getThemeInstallDigest skinOptions: '.print_r($skinOptions, true));
        $futureVersion = null;
        if($themeInfo && is_array($themeInfo) && count($themeInfo)){
            $futureVersion = array_key_exists('Version', $themeInfo) ? $themeInfo['Version'] : null;
            $infos[] = WADA_Sensor_Base::getEventInfoElement('Name', array_key_exists('Name', $themeInfo) ? $themeInfo['Name'] : null);
            $infos[] = WADA_Sensor_Base::getEventInfoElement('THEME_AUTHOR', array_key_exists('Author', $themeInfo) ? $themeInfo['Author'] : null);
            $infos[] = WADA_Sensor_Base::getEventInfoElement('THEME_TEMPLATE', array_key_exists('Template', $themeInfo) ? $themeInfo['Template'] : null);
        }

        $overwrite = $actionType = $actionTitle = null;
        if($skinObj){
            $overwrite = property_exists($skinObj, 'overwrite') ? $skinObj->overwrite : null;
            $actionType = property_exists($skinObj, 'type') ? $skinObj->type : null;
            $actionTitle = $skinOptions && array_key_exists('title', $skinOptions) ? $skinOptions['title'] : null;
        }
        $res = self::getGeneralDigest($themeUpgrade);
        $res->infos = array_merge($res->infos, $infos);
        $res->futureVersion = $futureVersion;
        $res->overwrite = $overwrite;
        $res->actionType = $actionType;
        $res->actionTitle = $actionTitle;
        WADA_Log::debug('getThemeInstallDigest res: '.print_r($res, true));
        return $res;
    }

    /**
     * @param Theme_Upgrader $themeUpgrade
     */
    public static function getThemeUpgradeDigest($themeUpgrade){
        $themeInfo = (property_exists($themeUpgrade, 'skin') && $themeUpgrade->skin && property_exists($themeUpgrade->skin, 'theme_info')) ? $themeUpgrade->skin->theme_info : null;
        $infos = array();
        WADA_Log::debug('getThemeUpgradeDigest themeInfo: '.print_r($themeInfo, true));
        $priorVersion = null;
        if($themeInfo instanceof WP_Theme){
            $priorVersion = $themeInfo->display('Version', false);
            $infos[] = WADA_Sensor_Base::getEventInfoElement('Name', $themeInfo->display('Name', false));
            $infos[] = WADA_Sensor_Base::getEventInfoElement('THEME_URL', $themeInfo->display('ThemeURI', false));
            $infos[] = WADA_Sensor_Base::getEventInfoElement('THEME_AUTHOR', $themeInfo->display('Author', false));
            $infos[] = WADA_Sensor_Base::getEventInfoElement('THEME_STATUS', $themeInfo->display('Status', false));
            WADA_Log::debug('getThemeUpgradeDigest infos: '.print_r($infos, true));
        }

        $res = self::getGeneralDigest($themeUpgrade);
        $res->infos = array_merge($res->infos, $infos);
        $res->priorVersion = $priorVersion;
        WADA_Log::debug('getThemeUpgradeDigest res: '.print_r($res, true));
        return $res;
    }

    /**
     * @param WP_Upgrader $upgrade
     */
    public static function getGeneralDigest($upgrade){
        $infos = array();
        $updateSuccessful = null;
        $resultElement = property_exists($upgrade, 'result') ? $upgrade->result : null;
        $skinObj = property_exists($upgrade, 'skin') ? $upgrade->skin : null;
        $upgradeDestination = $destinationName = null;
        if($resultElement) {
            if ($resultElement instanceof WP_Error) {
                $updateSuccessful = false;
                $infos[] = WADA_Sensor_Base::getEventInfoElement('ERROR_MESSAGES', implode(', ', $resultElement->get_error_messages()));
            } else if (is_array($resultElement)) {
                $updateSuccessful = true;
                $upgradeDestination = array_key_exists('destination', $resultElement) ? $resultElement['destination'] : null;
                $destinationName =  array_key_exists('destination_name', $resultElement) ? $resultElement['destination_name'] : null;
                $infos[] = WADA_Sensor_Base::getEventInfoElement('FILE_SOURCE', array_key_exists('source', $resultElement) ? $resultElement['source'] : null);
                $infos[] = WADA_Sensor_Base::getEventInfoElement('FILE_DESTINATION', $upgradeDestination);
                $infos[] = WADA_Sensor_Base::getEventInfoElement('DESTINATION_NAME', $destinationName);
            }
        }else if($skinObj instanceof WP_Upgrader_Skin){
            if($skinObj->result instanceof WP_Error){
                $updateSuccessful = false;
                $errorMessages = '';
                if(method_exists($skinObj, 'get_error_messages')){
                    $errorMessages = $skinObj->get_error_messages();
                }else{
                    $errorMessages = implode(', ', $skinObj->result->get_error_messages());
                }
                $infos[] = WADA_Sensor_Base::getEventInfoElement('ERROR_MESSAGES', $errorMessages);
            }else{
                $updateSuccessful = true;
            }
        }

        $res = new stdClass();
        $res->updateSuccessful = $updateSuccessful;
        $res->upgradeDestination = $upgradeDestination;
        $res->destinationName = $destinationName;
        $res->infos = $infos;
        return $res;
    }

    public static function getVersionOfPluginInDirectory($pluginPathForSearch){
        WADA_Log::debug('getVersionOfPluginInDirectory');
        if(!$pluginPathForSearch) return null;

        $version = null;
        $allPlugins = get_plugins();
        $pluginPathForSearch = wp_normalize_path($pluginPathForSearch);
        foreach($allPlugins AS $pluginFile => $plugin){
            $pluginDirPath = wp_normalize_path(plugin_dir_path(WP_PLUGIN_DIR . '/' .$pluginFile));
            WADA_Log::debug('search '.$pluginPathForSearch.' in '.$pluginDirPath);
            if($pluginPathForSearch == $pluginDirPath){
                $version = array_key_exists('Version', $plugin) ? $plugin['Version'] : null;
                WADA_Log::debug('getVersionOfPluginInDirectory Found version '.$version);
                break; // stop searching
            }
        }
        return $version;
    }

}
