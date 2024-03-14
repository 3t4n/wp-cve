<?php

/**
 *
 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
 */
class erpPaths {

    // Helpers
    public static $erpFileHelper = 'core/helpers/erpFileHelper.php';
    public static $WP_Admin_Notices = 'core/helpers/WP_Admin_Notices.php';
    // Display
    public static $erpPostData = 'core/display/erpPostData.php';
    public static $erpTheme = 'core/display/erpTheme.php';
    public static $VPluginThemeFactory = 'core/display/VPluginThemeFactory.php';
    public static $erpView = 'core/display/erpView.php';
    // Admin
    public static $easyRelatedPostsAdmin = 'admin/easyRelatedPostsAdmin.php';
    public static $erpActivator = 'admin/erpActivator.php';
    public static $erpWidget = 'admin/erpWidget.php';
    // Includes
    public static $bfiResizer = 'includes/bfi_thumb.php';
    // Options
    public static $erpWidOpts = 'core/options/erpWidOpts.php';
    public static $erpOptions = 'core/options/erpOptions.php';
    public static $erpMainOpts = 'core/options/erpMainOpts.php';
    public static $erpDefaults = 'core/options/erpDefaults.php';
    // Related
    public static $erpQueryFormater = 'core/related/erpQueryFormater.php';
    public static $erpRelated = 'core/related/erpRelated.php';
    public static $erpRelData = 'core/related/erpRelData.php';
    public static $erpRatingSystem = 'core/related/erpRatingSystem.php';
    // Front
    public static $easyRelatedPosts = 'front/easyRelatedPosts.php';
    // Themes
    public static $mainThemesFolder = 'front/views/main';
    public static $widgetThemesFolder = 'front/views/widget';

    public static function requireOnce($path) {
        $fields = get_class_vars(__CLASS__);
        if(in_array($path, $fields)){
            require_once EPR_BASE_PATH . $path;
        } else {
            return new WP_Error('error', 'File '.EPR_BASE_PATH . $path.' is not found in class fields');
        }
    }
    
    public static function getAbsPath($path) {
        $fields = get_class_vars(__CLASS__);
        if(in_array($path, $fields)){
            return EPR_BASE_PATH . $path;
        } else {
            return new WP_Error('error', 'File '. EPR_BASE_PATH . $path.' is not found in class fields');
        }
    }

    public static function getClassFieldNames(){
        return array_keys((array)get_class_vars(__CLASS__));
    }
}
