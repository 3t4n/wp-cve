<?php

namespace MoSharePointObjectSync\Controller;

class adminController{
    private static $instance;

    public static function getController(){
        if(!isset(self::$instance)){
            $class = __CLASS__;
            self::$instance = new $class;
        }
        return self::$instance;
    }

    public function mo_sps_admin_controller(){
        if(!current_user_can('administrator') || !isset($_POST['mo_sps_tab']) || !isset($_POST['option'])){
            return;
        }

        $tabSwitch = sanitize_text_field($_POST['mo_sps_tab']);
        $handler = self::getController();
        switch ($tabSwitch){
            case 'app_config':{
                $handler = appConfig::getController();
                break;
            }
            case 'account_setup':{
                $handler = accountSetupHandler::getController();
                break;
            }
            case 'wp_sync_users':{
                $handler = appConfig::getController();
                break;
            }
        }
        $handler->mo_sps_save_settings();
    }

    private function mo_sps_save_settings(){
        echo esc_html_e("It seems class is incomplete. Please check if you've installed the plugin correctly.");
    }
}
