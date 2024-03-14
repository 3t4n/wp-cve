<?php

namespace MoSharePointObjectSync\Controller;
use MoSharePointObjectSync\API\CustomerMOSPS;
use MoSharePointObjectSync\Wrappers\wpWrapper;

class accountSetupHandler
{
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

            case 'mo_api_account_registration_setup_option':{
                $this->mo_sps_account_registration_setup();
                break;
            }
            case 'mo_api_remove_account_option':{
                $this->mo_sps_remove_account();
                break;
            }
            case 'mo_api_account_login_setup_option':{
                $this->mo_sps_account_login_setup();
                break;
            }
            case 'mo_api_is_login':{
                $this->mo_sps_is_login();
                break;
            }
            case 'mo_api_is_regis':{
                $this->mo_sps_is_regis();
                break;
            }
        }
    }


    private function mo_sps_account_registration_setup(){

        if (!$this->mo_saml_is_extension_installed('curl')) {
            wpWrapper::mo_sps__show_success_notice("ERROR: PHP cURL extension is not installed or disabled. Login failed.");
            return;
        }
        if (empty($_POST['account_email']) || empty($_POST['account_pwd']) || empty($_POST['confirm_account_pwd'])) {
            wpWrapper::mo_sps__show_error_notice("All the fields are required. Please enter valid entries.");
            return;
        } else if (wpWrapper::mo_api__checkPasswordPattern(wp_strip_all_tags($_POST['account_pwd'])) || wpWrapper::mo_api__checkPasswordPattern(wp_strip_all_tags($_POST['confirm_account_pwd']))) {
            wpWrapper::mo_sps__show_error_notice("Minimum 6 characters should be present. Maximum 15 characters should be present. Only following symbols (!@#.$%^&*-_) should be present.");
            return;
        } else {
            $email = sanitize_email($_POST['account_email']);
            $password = stripslashes(wp_strip_all_tags($_POST['account_pwd']));
            $confirmPassword = stripslashes(wp_strip_all_tags($_POST['confirm_account_pwd']));
        }
        update_option('mo_sps_admin_email', $email);

        if (strcmp($password, $confirmPassword) == 0){
            update_option('mo_sps_admin_password', $password);
            $customer = new CustomerMOSPS();
            $customerExist=json_decode($customer->mo_sps_check_customer(),true);

            if(!is_null($customerExist)){
                if (strcasecmp($customerExist['status'], 'CUSTOMER_NOT_FOUND') == 0){
                    $response = $this->create_mo_customer();
                    if (is_array($response) && array_key_exists('status', $response) && $response['status'] == 'success') {
                        wpWrapper::mo_sps__show_success_notice('Successfully Logged In');

                    }
                    else{
                        wpWrapper::mo_sps__show_error_notice("Something Went wrong Pls try after some time");
                    }

                }
                else{
                    $response = $this->get_mo_current_customer();
                    if (is_array($response) && array_key_exists('status', $response) && $response['status'] == 'success') {
                        wpWrapper::mo_sps__show_success_notice('Successfully Logged In');
	                    // wp_redirect('https://plugins.miniorange.com/microsoft-sharepoint-wordpress-integration#pricing-cards');
                    }

                }
            }

        }
        else{
            wpWrapper::mo_sps__show_error_notice("Password Doesn't match");
        }

    }


    private function mo_sps_account_login_setup(){
        if (!$this->mo_saml_is_extension_installed('curl')) {
            wpWrapper::mo_sps__show_error_notice("ERROR: PHP cURL extension is not installed or disabled. Login failed.");
            return;
        }
        if (empty($_POST['account_email']) || empty($_POST['account_pwd']) ) {
            wpWrapper::mo_sps__show_error_notice("All the fields are required. Please enter valid entries.");
            return;
        }
        else if (wpWrapper::mo_api__checkPasswordPattern(wp_strip_all_tags($_POST['account_pwd']))) {
            wpWrapper::mo_sps__show_error_notice("Minimum 6 characters should be present. Maximum 15 characters should be present. Only following symbols (!@#.$%^&*-_) should be present.");
            return;
        }
        else{
            $email = sanitize_email($_POST['account_email']);
            $password = stripslashes(wp_strip_all_tags($_POST['account_pwd']));
        }
        update_option('mo_sps_admin_email', $email);
        update_option('mo_sps_admin_password',$password);

        $customer = new CustomerMOSPS();
        $content=$customer->mo_sps_get_customer_key();


        if (!$content)
            return;

        $customerKey = json_decode($content, true);
        

        if (json_last_error() == JSON_ERROR_NONE) {
            update_option( 'mo_sps_admin_customer_key', $customerKey['id'] );
            update_option( 'mo_sps_admin_api_key', $customerKey['apiKey'] );
            update_option( 'mo_sps_customer_token', $customerKey['token'] );
            if (!empty($customerKey['phone'])) {
                update_option('mo_sps_admin_phone', $customerKey['phone']);
            }

            wpWrapper::mo_sps__show_success_notice("Successfully Logged In");
			// wp_redirect('https://plugins.miniorange.com/microsoft-sharepoint-wordpress-integration#pricing-cards');
        }
        else{
            wpWrapper::mo_sps__show_error_notice("Invalid username or password. Please try again.");
        }
    }




    private function mo_sps_remove_account(){
        wpWrapper::mo_sps_deactivate();
        delete_option('mo_sps_registration_status');

    }

    private function mo_saml_is_extension_installed($extension_name) {
        if  (in_array  ($extension_name, get_loaded_extensions())) {
            return 1;
        } else
            return 0;
    }

    function create_mo_customer() {
        $customer    = new CustomerMOSPS();
        $customerKey = json_decode( $customer->mo_sps_create_customer(), true );
        if(!is_null($customerKey)){
            $response = array();
            if ( strcasecmp( $customerKey['status'], 'CUSTOMER_USERNAME_ALREADY_EXISTS' ) == 0 ) {
                $api_response = $this->get_mo_current_customer();
                if($api_response && $api_response['status']=='success'){
                    $response['status'] = "success";
                }
                else
                    $response['status'] = "error";

            } else if ( strcasecmp( $customerKey['status'], 'SUCCESS' ) == 0 ) {
                update_option( 'mo_sps_admin_customer_key', $customerKey['id'] );
                update_option( 'mo_sps_admin_api_key', $customerKey['apiKey'] );
                update_option( 'mo_sps_customer_token', $customerKey['token'] );
                delete_option( 'mo_sps_verify_customer' );
                $response['status']="success";
                return $response;
            }
            return $response;
        }
    }


    function get_mo_current_customer() {
        $customer    = new CustomerMOSPS();
        $content     = $customer->mo_sps_get_customer_key();

        if(!is_null($content)){
            $customerKey = json_decode( $content, true );

            $response = array();
            if ( json_last_error() == JSON_ERROR_NONE ) {
                update_option( 'mo_sps_admin_customer_key', $customerKey['id'] );
                update_option( 'mo_sps_admin_api_key', $customerKey['apiKey'] );
                update_option( 'mo_sps_customer_token', $customerKey['token'] );
                delete_option( 'mo_sps_verify_customer');
                $response['status'] = "success";
                return $response;
            } else {
                wpWrapper::mo_sps__show_error_notice('You already have an account with miniOrange. Please enter a valid password.');
                $response['status'] = "error";
                return $response;
            }
        }
    }

    private function mo_sps_is_login(){

        check_admin_referer('mo_api_is_login');
        update_option('mo_sps_registration_status','Login User');

    }

    private function mo_sps_is_regis(){
        check_admin_referer('mo_api_is_regis');
        update_option('mo_sps_registration_status','');
    }

}