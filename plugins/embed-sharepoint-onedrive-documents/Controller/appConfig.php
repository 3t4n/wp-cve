<?php

namespace MoSharePointObjectSync\Controller;

use MoSharePointObjectSync\API\Azure;
use MoSharePointObjectSync\Wrappers\pluginConstants;
use MoSharePointObjectSync\Wrappers\wpWrapper;

class appConfig{

    private static $instance;

    public static function getController(){
        if(!isset(self::$instance)){
            $class = __CLASS__;
            self::$instance = new $class;
        }
        return self::$instance;
    }

    public function mo_sps_save_settings(){
        $option = sanitize_text_field($_POST['option']);
        switch ($option){
            case 'mo_sps_azure_config_option':{
                $this->mo_sps_save_azure_config();
                break;
            }
            
            case 'mo_sps_upn_config_option':{
                $this->mo_sps_save_upn_config();
                break;
            }

            case 'mo_sps_profile_config_option':{
                $this->mo_sps_save_profile_config_option();
                break;
            }

            case 'mo_sps_upload_file':{
                $this->mo_sps_upload_files();
                break;
            }
            case 'mo_sps_remove_configured_account':{
                $this->mo_sps_remove_configured_account();
                break;
            }
        }
    }


    private function mo_sps_remove_configured_account(){
        delete_option("mo_sps_test_connection_status");
        delete_option("mo_sps_test_connection_user_details");
        delete_option("mo_sps_refresh_token");
        wpWrapper::mo_sps__show_success_notice(esc_html__("Account Removed Successfully, Please connect via any other account."));
    }

    public function mo_sps_upload_files(){
      
        check_admin_referer('mo_sps_upload_file');
        $config = wpWrapper::mo_sps_get_option(pluginConstants::APP_CONFIG);
        $client = Azure::getClient($config);
        $access_token = $client->mo_sps_send_access_token();

        $file_input = $_POST['getFile'];
        $unique_name = $_POST['displayName'];
        
    }

    private function mo_sps_check_for_empty_or_null(&$input,$arr){
        foreach ($arr as $key){
            if(!isset($_POST[$key]) || empty($_POST[$key])){
                return false;
            }
            if($key == 'folder_path') $input[$key] = sanitize_text_field(rtrim($_POST[$key],'/'));
            else $input[$key] = sanitize_text_field($_POST[$key]);
        }
        return $input;
    }

    private function mo_sps_save_azure_config(){
        check_admin_referer('mo_sps_azure_config_option');
        $input_arr = ['client_id','client_secret','tenant_id'];
        $sanitized_arr = [];
        if(!$this->mo_sps_check_for_empty_or_null($sanitized_arr,$input_arr)){
            wpWrapper::mo_sps__show_error_notice(esc_html__("Input is empty or present in the incorrect format."));
            return;
        }
        
        $sanitized_arr['client_secret'] = wpWrapper::mo_sps_encrypt_data($sanitized_arr['client_secret'],hash("sha256",$sanitized_arr['client_id']));

        $feedback_config = wpWrapper::mo_sps_get_option('mo_sps_feedback_config');
        $feedback_config['client_id'] = $sanitized_arr['client_id'];
        $feedback_config['tenant_id'] = $sanitized_arr['tenant_id'];
        wpWrapper::mo_sps_set_option('mo_sps_feedback_config',$feedback_config);
        wpWrapper::mo_sps_set_option("mo_sps_application_config",$sanitized_arr);
        wpWrapper::mo_sps__show_success_notice(esc_html__("Settings Saved Successfully."));
    }

    private function mo_sps_save_upn_config() {
        check_admin_referer('mo_sps_upn_config_option');
        $config = wpWrapper::mo_sps_get_option(pluginConstants::APP_CONFIG);
        $config['upn_id'] = isset($_POST['upn_id'])?sanitize_text_field($_POST['upn_id']):'';
        wpWrapper::mo_sps_set_option("mo_sps_application_config",$config);
        wpWrapper::mo_sps__show_success_notice(esc_html__("Settings Saved Successfully."));
    }

    private function mo_sps_save_profile_config_option(){

        check_admin_referer('mo_sps_profile_config_option');
        $input_arr = ['user_login','email','first_name','last_name','display_name'];
        $sanitized_arr = [];
        if(!$this->mo_sps_check_for_empty_or_null($sanitized_arr,$input_arr)){
            wpWrapper::mo_sps__show_error_notice(esc_html__("Input is empty or present in the incorrect format."));
            return;
        }
        $feedback_config = wpWrapper::mo_sps_get_option(pluginConstants::FEEDBACK_CONFIG);
        $feedback_config['Profile_mapping'] = $sanitized_arr;
        wpWrapper::mo_sps_set_option('mo_sps_feedback_config',$feedback_config);
        wpWrapper::mo_sps_set_option("mo_sps_profile_mapping",$sanitized_arr);
        wpWrapper::mo_sps__show_success_notice(esc_html__("Settings Saved Successfully."));
    }
}