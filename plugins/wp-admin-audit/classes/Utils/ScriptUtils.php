<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_ScriptUtils
{
    protected static function getPluginBasename(){
        $path = __DIR__.'/../../';
        $path = realpath($path);
        return basename($path);
    }

    protected static function getScriptAssetsBasePath(){
        return (__DIR__.'/../../assets/js');
    }
    protected static function getStyleAssetsBasePath(){
        return (__DIR__.'/../../assets/css');
    }

    public static function loadTabs(){
        require_once(self::getScriptAssetsBasePath().'/tabs.php');
    }

    public static function loadCsvSepUtil(){
        require_once(self::getScriptAssetsBasePath().'/csv-utils.php');
        $script = WADA_Script_Utils_CSV::getScript();
        wp_add_inline_script('wada_utils_csv_script', $script);
    }

    public static function loadSmartWizard(){
        $pluginBasename = self::getPluginBasename();
        $scriptUrl = trailingslashit(plugins_url()).$pluginBasename.'/assets/js/jquery.smartWizard.js';
        $styleUrl = trailingslashit(plugins_url()).$pluginBasename.'/assets/css/smart_wizard_all.css';
        wp_enqueue_script('jquery');
        wp_enqueue_style('wada_smartwizard_style', $styleUrl);
        wp_enqueue_script('wada_smartwizard_script', $scriptUrl, array('jquery'));
    }

    public static function loadSelect2(){
        $pluginBasename = self::getPluginBasename();
        $scriptUrl = trailingslashit(plugins_url()).$pluginBasename.'/assets/js/select2.min.js';
        $styleUrl = trailingslashit(plugins_url()).$pluginBasename.'/assets/css/select2.min.css';
        wp_enqueue_script('jquery');
        wp_enqueue_style('wada_select2_style', $styleUrl);
        wp_enqueue_script('wada_select2_script', $scriptUrl, array('jquery'));
    }

    public static function loadSelectize(){
        $pluginBasename = self::getPluginBasename();
        $scriptUrl = trailingslashit(plugins_url()).$pluginBasename.'/assets/js/selectize.min.js';
        $styleUrl = trailingslashit(plugins_url()).$pluginBasename.'/assets/css/selectize.css';
        wp_enqueue_script('jquery');
        wp_enqueue_style('wada_selectize_style', $styleUrl);
        wp_enqueue_script('wada_selectize_script', $scriptUrl, array('jquery'));
    }

}