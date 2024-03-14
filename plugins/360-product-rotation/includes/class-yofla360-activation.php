<?php

if (!defined("ABSPATH")) exit;

/**
 * Class YoFLA360Activation
 *
 * Actions when plugin is activated
 *
 */
class YoFLA360Activation {


    public function __construct()
    {
        register_activation_hook( YOFLA_360_PLUGIN_MAIN, array($this,'yofla_360_activation_hook'));
    }


    public function yofla_360_activation_hook() {
        $this->yofla_360_check_products_folder_initialized();
    }

    /**
     * Creates yofla360 folder in uploads, if it already does not exist
     *
     * Checks if settings ini in uploads/yofla360 folder exists, creates if not
     *
     */
    private function yofla_360_check_products_folder_initialized()
    {
        $wp_uploads = wp_upload_dir();
        $products_path = $wp_uploads['basedir'].'/'.YOFLA_360_PRODUCTS_FOLDER.'/';
        $settings_path = $products_path.'settings.ini';
        $settings_source = YOFLA_360_PLUGIN_PATH.'/includes/yofla_3drt/settings.ini';

        if(!file_exists($settings_path)){
            //create directory
            wp_mkdir_p($products_path);

            //copy settings file
            copy($settings_source,$settings_path);
        }
    }




}//class
