<?php

namespace MoSharePointObjectSync\Observer;

use MoSharePointObjectSync\API\Azure;
use MoSharePointObjectSync\API\Sharepoint;

use MoSharePointObjectSync\Wrappers\pluginConstants;
use MoSharePointObjectSync\Wrappers\sharepointWrapper;
use MoSharePointObjectSync\Wrappers\wpWrapper;
use MoSharePointObjectSync\API\CustomerMOSPS;

class adminObserver{



    public static $INTEGRATIONS_TITLE = array(

        'WooCommerce'                         =>  'WooCommerce',
        'BuddyPress'                          =>  'BuddyPress',
        'MemberPress'                         =>  'MemberPress',
        'ACF'                                 =>  'ACF',
        'AzureAd'                             =>  'AzureAd',
        'LearnDash'                           =>  'LearnDash',

    );

    private static $obj;

    public static function getObserver(){
        if(!isset(self::$obj)){
            self::$obj = new adminObserver();
        }
        return self::$obj;
    }

    public function mo_sps_admin_observer(){
        if(isset($_REQUEST['mo_shp_code'])) {
            $this->mo_sps_get_auth_code();
        }

        if(isset($_REQUEST['option']) && $_REQUEST['option'] == 'testSPSApp'){
            $config = wpWrapper::mo_sps_get_option(pluginConstants::APP_CONFIG);
            $connector = wpWrapper::mo_sps_get_option(pluginConstants::CLOUD_CONNECTOR);
            $config['app_type'] = $_REQUEST['type'];
            $config['connector'] = $connector;

            wpWrapper::mo_sps_set_option(pluginConstants::APP_CONFIG, $config);
            wpWrapper::mo_sps_delete_option(pluginConstants::SPS_SEL_SITE);
            wpWrapper::mo_sps_delete_option(pluginConstants::SPS_DRIVES);
            wpWrapper::mo_sps_delete_option(pluginConstants::SPS_SEL_DRIVE);
            wpWrapper::mo_sps_delete_option(pluginConstants::SPS_SEL_DRIVE_NAME);
            wpWrapper::mo_sps_delete_option(pluginConstants::SPS_SEL_FOLDER);
            wpWrapper::mo_sps_delete_option(pluginConstants::BREADCRUMBS);

            if($_REQUEST['type'] == 'auto') {
                if(isset($connector) && $connector == 'personal') {
                    $this->mo_sps_connect_to_onedrive_personal();
                } else { 
                    $this->mo_sps_connect_to_sharepoint_and_onedrive();
                }
            } else {
                wpWrapper::mo_sps_delete_option(pluginConstants::SPS_RFTK);
                $client = Azure::getClient($config);
                $client->mo_sps_send_access_token();

                $feedback_config = wpWrapper::mo_sps_get_option(pluginconstants::FEEDBACK_CONFIG);
                $feedback_config['test_configuration'] = 'success';
                wpWrapper::mo_sps_set_option("mo_sps_feedback_config", $feedback_config);

                $response = $client->mo_sps_get_all_sites();
                if($response['status']) {
                    wpWrapper::mo_sps_set_option(pluginConstants::SPS_SITES, $response['data']['value']);
                }

                $this->mo_sps_display_test_attributes();
            }
        }

        if(isset($_REQUEST['option']) && $_REQUEST['option'] == 'mo_sps_site_refresh'){
            $config = wpWrapper::mo_sps_get_option(pluginConstants::APP_CONFIG);

            wpWrapper::mo_sps_delete_option(pluginConstants::SPS_DRIVES);
            wpWrapper::mo_sps_delete_option(pluginConstants::SPS_SEL_DRIVE);
            wpWrapper::mo_sps_delete_option(pluginConstants::SPS_SEL_DRIVE_NAME);
            wpWrapper::mo_sps_delete_option(pluginConstants::SPS_SEL_FOLDER);
            wpWrapper::mo_sps_delete_option(pluginConstants::SPS_SEL_SITE);
            wpWrapper::mo_sps_delete_option(pluginConstants::BREADCRUMBS);

            $client = Azure::getClient($config);
            $response = $client->mo_sps_get_all_sites();

            if($response['status']) {
                $this->mo_sps_load_default_site_and_drive($response);
            }
        }
        
        if((isset($_REQUEST['option']) && $_REQUEST['option'] == 'sps_automatic_app_status')) {

            $this->mo_sps_automatic_connection();
        }

        if(isset($_REQUEST['option']) && $_REQUEST['option'] == 'mo_sps_contact_us_query_option'){
            $submited = $this->mo_sps_send_support_query();
            if(!is_null($submited)){
                if ( $submited == false ) {
                    wpWrapper::mo_sps__show_error_notice(esc_html__("Your query could not be submitted. Please try again."));
                } else {
                    wpWrapper::mo_sps__show_success_notice(esc_html__("Thanks for getting in touch! We shall get back to you shortly."));
                }
            }
        }

        if(isset($_REQUEST['option']) && $_REQUEST['option'] == 'mo_sps_demo_request_option'){
            $submited = $this->mo_sps_send_demo_request_query();
            if(!is_null($submited)){
                if ($submited == false) {
                    wpWrapper::mo_sps__show_error_notice(esc_html__("Your query could not be submitted. Please try again."));
                } 
                else{
                    wpWrapper::mo_sps__show_success_notice(esc_html__("Thanks for getting in touch! We shall get back to you shortly."));
                }
            }
        }

        if(isset($_REQUEST['option']) && $_REQUEST['option'] == 'mo_sps_feedback'){
            $sent = isset($_REQUEST['miniorange_feedback_submit']);
            $skip = isset($_REQUEST['miniorange_skip_feedback']);
            $submited = $this->mo_sps_send_email_alert($skip,$sent);
            if( json_last_error() == JSON_ERROR_NONE) {
                if(is_array( $submited ) && array_key_exists( 'status', $submited ) && $submited['status'] == 'ERROR' ) {
                    wpWrapper::mo_sps__show_error_notice(esc_html__($submited['message']));
                }
                else{
                    if( $submited == false ){
                        wpWrapper::mo_sps__show_error_notice(esc_html__("Error while submitting the query."));
                    }
                }
            }

            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
           
            deactivate_plugins( MO_SPS_PLUGIN_FILE );
            wpWrapper::mo_sps__show_success_notice(esc_html__("Thank you for the feedback."));
            
        }

    }

    private function mo_sps_connect_to_sharepoint() {
        $customer_tenant_id = 'common';
        $mo_client_id       = ( PluginConstants::CID );
        $host = "https://login.microsoftonline.com/".$customer_tenant_id."/oauth2/authorize";
        $state = add_query_arg(array( 'conn' => 'mo_shp_auto' ),home_url());

        $redirect_url = add_query_arg(array(
            'response_type' => 'code',
            'client_id' => $mo_client_id,
            'scope' => 'openid',
            'redirect_uri' => pluginConstants::CONNECT_SERVER_URI,
            'state' => urlencode($state)
        ),$host);
        wp_redirect($redirect_url);
        exit();
    }

    private function mo_sps_automatic_connection() {
        
        $config = wpWrapper::mo_sps_get_option(pluginConstants::APP_CONFIG);
        $apiHandler = Azure::getClient($config);
        $config = $apiHandler->mo_sps_process_tokens_for_auto_connection();
        
        if($config['connector'] != 'personal') {
            $user = $apiHandler->mo_sps_get_my_user();
            if($user['status']) {
                $config['name'] = isset($user['data']['displayName']) ? $user['data']['displayName'] : '';
                $config['upn'] = isset($user['data']['userPrincipalName']) ? $user['data']['userPrincipalName'] : '';
                $config['email'] = isset($user['data']['mail']) ? $user['data']['mail'] : '';
            }
        }

        $config = $apiHandler->mo_sps_process_tokens_for_auto_connection();
        wpWrapper::mo_sps_set_option(pluginConstants::APP_CONFIG, $config);

        $apiHandler = Azure::getClient($config);

        switch($config['connector']){
            case 'personal':
                $this->mo_sps_onedrive_personal_response($apiHandler->mo_sps_get_personal_onedrive());
                break;
            case 'onedrive':
                $this->mo_sps_process_response($apiHandler->mo_sps_get_onedrives());
                break;
            default:
                $this->mo_sps_process_sharepoint_response($apiHandler->mo_sps_get_all_sites());
                break;
        }

    }

    private function mo_sps_process_sharepoint_response($response) {
        if($response['status']) {
            wpWrapper::mo_sps_set_option(pluginConstants::SPS_SITES, $response['data']['value']);
            $this->mo_sps_show_success_message_for_test_connection($response['data']['value']);
        }else {
            $error_code = [
                "Error" => $response['data']['error'],
                "Description" => empty($response['data']['error'])?'':$response['data']['error_description']
            ];
            $this->mo_sps_display_error_message($error_code);
        }
    }

    private function mo_sps_process_response($response) {
        if($response['status']) {
            wpWrapper::mo_sps_set_option(pluginConstants::SPS_DRIVES, $response['data']['value']);
            $this->mo_sps_show_success_message_for_test_connection($response['data']['value']);
        }else {
            $error_code = [
                "Error" => $response['data']['error'],
                "Description" => empty($response['data']['error'])?'':$response['data']['error_description']
            ];
            $this->mo_sps_display_error_message($error_code);
        }
    }

    private function mo_sps_onedrive_personal_response($response) {
        if($response['status']) {
            $driveName = array(
                'name' => 'Personal Onedrive',
            );
            foreach ($response['data']['value'] as &$subarray) {
                $idIndex = array_search('id', array_keys($subarray));
                $subarray = array_merge(array_slice($subarray, 0, $idIndex + 1),$driveName,array_slice($subarray, $idIndex + 1)
                );
            }
            if($response['status']){
                $all_drives = $this->mo_sps_process_drives($response['data']);
                wpWrapper::mo_sps_set_option(pluginConstants::SPS_DRIVES, $all_drives); 
            }else{
                $error_code = [
                    "Error" => $response['data']['error'],
                    "Description" => empty($response['data']['error'])?'':$response['data']['error_description']
                ];
    
                wp_send_json_error($error_code);
            }
            wpWrapper::mo_sps_set_option(pluginConstants::SPS_DRIVES, $response['data']['value']);
            $this->mo_sps_show_success_message_for_test_connection($response['data']['value']);
        }else {
            $error_code = [
                "Error" => $response['data']['error'],
                "Description" => empty($response['data']['error'])?'':$response['data']['error_description']
            ];
            $this->mo_sps_display_error_message($error_code);
        }
    }
    
    public static function mo_sps_process_drives($result){        
        if(!isset($result['value']))
            return [];


        $res = $result['value'];
        $filter = ['id','name','webUrl','driveType'];

        $output = [];

        foreach($res as $key => $drive){
            $temp_output = [];
            foreach($drive as $prop => $val){                
                if(in_array($prop,$filter)){
                        $temp_output[$prop] = $val;
                }
            }

            array_push($output,$temp_output);

        }
        return $output;
    }
    
    private function mo_sps_connect_to_sharepoint_and_onedrive() {
        $customer_tenant_id = 'common';
        $mo_client_id       = ( PluginConstants::CID );
        
        $scope = "openid offline_access user.read mail.read Sites.Read.All";
        $host_url = "https://login.microsoftonline.com/".$customer_tenant_id."/oauth2/v2.0/authorize";
        $url = add_query_arg(array(
            "response_type" => "code",
            "client_id" => $mo_client_id,
            "scope" => $scope,
            "redirect_uri" => pluginConstants::CONNECT_SERVER_URI,
            "state" => add_query_arg(array( 'conn' => 'mo_shp_auto' ),home_url())
        ),$host_url);

        wp_redirect($url);
        exit;
    }

    private function mo_sps_connect_to_onedrive_personal() {
        $customer_tenant_id = 'common';
        $mo_client_id       = ( PluginConstants::CID );
        
        $scope = "openid offline_access onedrive.readwrite";
        $host_url = "https://login.live.com/oauth20_authorize.srf";
        $url = add_query_arg(array(
            "response_type" => "code",
            "client_id" => $mo_client_id,
            "scope" => $scope,
            "redirect_uri" => pluginConstants::CONNECT_SERVER_URI,
            "state" => add_query_arg(array( 'conn' => 'mo_shp_auto' ),home_url())
        ),$host_url);
    
    
        wp_redirect($url);
        exit;
    }

    private function mo_sps_show_success_message_for_test_connection($response){

        $response = isset($response) && !empty($response) ? $response : [];
        update_option('mo_sps_test_connection_status', 'success');
        update_option('mo_sps_test_connection_user_details', $response);
        $connector = wpWrapper::mo_sps_get_option(pluginConstants::CLOUD_CONNECTOR);
        
        echo '<div class="mo_sps_test_connection__success">
            <div class="mo_sps_test_connection__success-heading">
                Success
            </div>';
        
        if($connector == 'personal') {
            echo '<div class="mo_sps_test_connection__success_test_connection-title"><img width="20px" height="20px" style="margin-right:10px;" src="'.esc_url_raw(wpWrapper::mo_sps_get_image_src('checked.png')).'" />'.(count($response) === 0?'No personal onedrive Found':(count($response).' Personal onedrive fetched successfully')).'</div>
            <div class="mo_sps_test_connection__success_test_connection-content">';
                echo '<div class="mo_sps_test_connection__success_test_connection-content-objects">Personal Drive</div>';
        }else if($connector == 'onedrive') {
            echo '<div class="mo_sps_test_connection__success_test_connection-title"><img width="20px" height="20px" style="margin-right:10px;" src="'.esc_url_raw(wpWrapper::mo_sps_get_image_src('checked.png')).'" />'.(count($response) === 0?'No drives Found':(count($response).' SharePoint drives fetched successfully')).'</div>
            <div class="mo_sps_test_connection__success_test_connection-content">';
            foreach($response as $drives){
                echo '<div class="mo_sps_test_connection__success_test_connection-content-objects">'.
                esc_html($drives['name']).
                '</div>';
            }
        } else {
            echo '<div class="mo_sps_test_connection__success_test_connection-title"><img width="20px" height="20px" style="margin-right:10px;" src="'.esc_url_raw(wpWrapper::mo_sps_get_image_src('checked.png')).'" />'.(count($response) === 0?'No Sites Found':(count($response).' SharePoint sites fetched successfully')).'</div>
            <div class="mo_sps_test_connection__success_test_connection-content">';
            foreach($response as $site){
                echo '<div class="mo_sps_test_connection__success_test_connection-content-objects">'.
                esc_html($site['displayName']).
                '</div>';
            }
        }

        echo '</div>';
        $this->load_css();
        die();
    }

    private function mo_sps_get_auth_code() {
        wpWrapper::mo_sps_delete_option( PluginConstants::SPS_RFTK );
        wpWrapper::mo_sps_set_option(pluginConstants::SPSAUTHCODE, $_REQUEST['mo_shp_code']);

        wp_safe_redirect( admin_url( '?option=sps_automatic_app_status' ) );
		exit();
    }

    private function mo_sps_load_default_site_and_drive($site_response) {
        $sites = $site_response['data']['value'];
        wpWrapper::mo_sps_set_option(pluginConstants::SPS_SITES, $sites);
        $config = wpWrapper::mo_sps_get_option(pluginConstants::APP_CONFIG);

        foreach($sites as $site) {
            if($site['displayName'] == 'Communication site') {
                wpWrapper::mo_sps_set_option(pluginConstants::SPS_SEL_SITE, $site['displayName']);
                $client = Azure::getClient($config);
                $drive_response = $client->mo_sps_get_all_drives($site['id']);
                if($drive_response['status']) {
                    $drives = $drive_response['data']['value'];
                    wpWrapper::mo_sps_set_option(pluginConstants::SPS_DRIVES, $drives);
                    foreach($drives as $drive) {
                        if($drive['name'] == 'Documents') {
                            wpWrapper::mo_sps_set_option(pluginConstants::SPS_SEL_DRIVE, $drive['id']);
                            wpWrapper::mo_sps_set_option(pluginConstants::SPS_SEL_DRIVE_NAME, $drive['name']);
                        }
                    }
                }
            }
        }
    }

    private function mo_sps_send_email_alert($isSkipped = false,$isSend=false){
        
        $user = wp_get_current_user();

        $message = 'Plugin Deactivated';
        $deactivate_reasons=array_key_exists('sps_reason',$_POST)? $_POST['sps_reason']:[];
        
        $deactivate_reason_message = array_key_exists( 'query_feedback', $_POST ) ? htmlspecialchars($_POST['query_feedback']) : false;


        if($isSkipped && $deactivate_reason_message==false)
            $deactivate_reason_message = "skipped";
        if($isSend && $deactivate_reason_message==false)
            $deactivate_reason_message = "Send";

        $get_config = '';
        if(isset($_POST['get_reply']))
            $get_config = htmlspecialchars($_POST['get_reply']);

        if(empty($get_config)){
            $get_config = false;
        }else{
            $get_config = true;
        }

        if(is_multisite())
            $multisite_enabled = 'True';
        else
            $multisite_enabled = 'False';

        $message.= ', [Multisite enabled: ' . $multisite_enabled .']';
        
        $message.= ', Feedback : '.$deactivate_reason_message.'';
            
        $email = '';
        $reasons='';
        
        foreach($deactivate_reasons as $reason){
            $reasons.=$reason;
            $reasons.=',';
        }
        
        $reasons=substr($reasons, 0, -1);
        $message.= ', [Reasons :'.$reasons.']';

        if (isset($_POST['query_mail']))
            $email = $_POST['query_mail'];

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $email = get_option('mo_sps_admin_email');
            if(empty($email))
                $email = $user->user_email;
        }
        $phone = get_option( 'mo_sps_admin_phone' );
        $feedback_reasons = new CustomerMOSPS();

        if(!$isSkipped) {
            $response = json_decode( $feedback_reasons->mo_sps_send_email_alert( $email, $phone, $message, $get_config ), true );
            return $response;
        }

    }


    // support form backend code

    private function mo_sps_send_support_query(){
        $email    = sanitize_email($_POST['mo_sps_contact_us_email']);
        $phone    = htmlspecialchars($_POST['mo_sps_contact_us_phone']);
        $query    = htmlspecialchars($_POST['mo_sps_contact_us_query']);

        $query = '[Embed sharepoint onedrive documents plugin] ' . $query;
                
        $customer = new CustomerMOSPS();
  
        $response = $customer->mo_sps_submit_contact_us($email,$phone,$query);

        return $response;
    }

    // demo request backend code

    private function mo_sps_send_demo_request_query(){
        $email    = sanitize_email($_POST['demo_email']);
        $query    = htmlspecialchars($_POST['demo_description']);

        $addons_selected = array();
        $addons = self::$INTEGRATIONS_TITLE;
        foreach($addons as $key => $value){
            if(isset($_POST[$key]) && $_POST[$key] == "true")
                $addons_selected[$key] = $value;
        }
        
        $integrations_selected = implode(', ', array_values($addons_selected));


        $query = '[Demo Request For Embed sharepoint onedrive documents plugin] ' . $query;
                
        $customer = new CustomerMOSPS();
  
        $response = $customer->mo_sps_submit_demo_query($email, "",$query, false,true,$integrations_selected);

        return $response;
    }

    
    
    public function mo_sps_display_error_message($error_code){
        $feedback_config = wpWrapper::mo_sps_get_option(pluginconstants::FEEDBACK_CONFIG);
        $feedback_config['test_configuration'] = 'failed';
        wpWrapper::mo_sps_set_option("mo_sps_feedback_config", $feedback_config);
        ?> 
            

            <div style="width:100%;display:flex;flex-direction:column;justify-content:center;align-items:center;font-size:15px;margin-top:10px;width:100%;">
                
                <div style="width:86%;padding: 15px;text-align: center;background-color:#f2dede;color:#a94442;border: 1px solid #E6B3B2;font-size: 18pt;margin-bottom:20px;">
                    Error
                </div>

                <table class="mo-ms-tab-content-app-config-table" style="border-collapse:collapse;width:90%">
                    <tr>
                        <td style="padding: 30px 5px 30px 5px;border:1px solid #757575;" colspan="2"><h2><span>Test Configuration Failed</span></h2></td>
                    </tr>
                    <?php foreach ($error_code as $key => $value){
                       echo '<tr><td style="padding: 30px 5px 30px 5px;border:1px solid #757575;" class="left-div"><span style="margin-right:10px;"><b>'.esc_html($key).':</b></span></td>
                       <td style="padding: 30px 5px 30px 5px;border:1px solid #757575;" class="right-div"><span>'.esc_html($value).'</span></td></tr>';
                    }?>
                </table>
                <h3 style="margin:20px;">
                    Contact us at <a style="color:#dc143c" href="mailto:samlsupport@xecurify.com">samlsupport@xecurify.com</a>
                </h3>
            </div>
        <?php
        exit();
    }
    private function mo_sps_display_test_attributes($upn=NULL){
        update_option('mo_sps_test_connection_status', 'success');
        ?>
        <div style="width:100%;height:100%;display:flex;align-items:center;flex-direction:column;border:1px solid #eee;padding:10px;">
       
                <div style="width:90%;color: #3c763d;background-color: #dff0d8;padding: 2%;margin-bottom: 20px;text-align: center;border: 1px solid #AEDB9A;font-size: 18pt;">
                    Success
                </div>
			<div style="display:block;text-align:center;margin-bottom:4%;">
                <svg class="animate" width="100" height="100">
                    <filter id="dropshadow" height="">
                    <feGaussianBlur in="SourceAlpha" stdDeviation="3" result="blur"></feGaussianBlur>
                    <feFlood flood-color="rgba(76, 175, 80, 1)" flood-opacity="0.5" result="color"></feFlood>
                    <feComposite in="color" in2="blur" operator="in" result="blur"></feComposite>
                    <feMerge> 
                        <feMergeNode></feMergeNode>
                        <feMergeNode in="SourceGraphic"></feMergeNode>
                    </feMerge>
                    </filter>
				
				    <circle cx="50" cy="50" r="46.5" fill="none" stroke="rgba(76, 175, 80, 0.5)" stroke-width="5"></circle>
				    <path d="M67,93 A46.5,46.5 0,1,0 7,32 L43,67 L88,19" fill="none" stroke="rgba(76, 175, 80, 1)" stroke-width="5" stroke-linecap="round" stroke-dasharray="80 1000" stroke-dashoffset="-220" style="filter:url(#dropshadow)"></path>
			    </svg>
                

                <div style="margin-top:1%;margin-left:2%;color:#3c763d;background-color:#dff0d8;width:90%;">
                    <div style="color: #3c763d;background-color: #dff0d8;padding: 2%;text-align: center;border: 1px solid #AEDB9A;font-size: 18pt;">
                        Connected to your Azure AD/SharePoint application.
                        <?php echo $upn?$upn:''; ?>
                    </div>
                </div>
              </div>
              
                <div style="margin:4%;margin-top:0%;display:flex;text-align:center;">

                <div style="margin-right:20px;">
		        <input class="mo-ms-tab-content-button" style="box-shadow:none!important;height:30px;background-color: #1B9BA1;border-color: #1B9BA1;color: #FFF;cursor: pointer;" type="button" value="Preview Documents / Files" onclick="close_and_redirect_to_document_sync()">
                </div>
                
                </div>
                
              <style>
			  svg.animate path {
			  animation: dash 1.5s linear both;
			  animation-delay: 1s;
			}
			  @keyframes dash {
			  0% { stroke-dashoffset: 210; }
			  75% { stroke-dashoffset: -220; }
			  100% { stroke-dashoffset: -205; }
			}
			</style>
            <script>
                // window.addEventListener('beforeunload', function (event) {
                //     window.opener.location.reload();
                // });
                function close_and_redirect_to_document_sync() {
                    window.opener.redirect_to_document_sync();
                    setTimeout(function() {
                        self.close();
                    }, 1000);
                }  
                
            </script>
            </div>
            </div>
        </div>
        <?php
        $this->load_css();
        exit();
    }

    private function mo_sps_display_fetch_attributes($details){
        ?>

        <div style="display:flex;justify-content:center;align-items:center;flex-direction:column;border:1px solid #eee;padding:10px;">


                <div style="width:90%;color: #3c763d;background-color: #dff0d8;padding: 2%;margin-bottom: 20px;text-align: center;border: 1px solid #AEDB9A;font-size: 18pt;">
                    Success
                </div>
				<div style="display:block;text-align:center;margin-bottom:4%;"><svg class="animate" width="100" height="100">
				<filter id="dropshadow" height="">
				  <feGaussianBlur in="SourceAlpha" stdDeviation="3" result="blur"></feGaussianBlur>
				  <feFlood flood-color="rgba(76, 175, 80, 1)" flood-opacity="0.5" result="color"></feFlood>
				  <feComposite in="color" in2="blur" operator="in" result="blur"></feComposite>
				  <feMerge> 
					<feMergeNode></feMergeNode>
					<feMergeNode in="SourceGraphic"></feMergeNode>
				  </feMerge>
				</filter>
				
				<circle cx="50" cy="50" r="46.5" fill="none" stroke="rgba(76, 175, 80, 0.5)" stroke-width="5"></circle>
				
				<path d="M67,93 A46.5,46.5 0,1,0 7,32 L43,67 L88,19" fill="none" stroke="rgba(76, 175, 80, 1)" stroke-width="5" stroke-linecap="round" stroke-dasharray="80 1000" stroke-dashoffset="-220" style="filter:url(#dropshadow)"></path>
			  </svg>
              
              <script>
                window.onunload = refreshParent;
                function refreshParent() {
                    window.opener.location.reload();
                }
            </script>

            <style>
			  svg.animate path {
			  animation: dash 1.5s linear both;
			  animation-delay: 1s;
			}

			  @keyframes dash {
			  0% { stroke-dashoffset: 210; }
			  75% { stroke-dashoffset: -220; }
			  100% { stroke-dashoffset: -205; }
			}
			</style></div>
                

                <div style="border-top:1px solid #eee;width:95%;"></div>

                <div class="test-container" style="margin-top:10px;background:#fff;">
                    <table class="mo-ms-tab-content-app-config-table">
                        <tr>
                            <td style="text-align:center;" colspan="2">
                                <span><h2>Test Attributes:</h2></span>
                            </td>
                        </tr>
                <?php
                    foreach ($details as $key => $value){
                    if(!is_array($value) && !empty($value)){
                    ?>
                    <tr>
                        <td class="left-div"><span><?php echo esc_html($key);?></span></td>
                        <td class="right-div"><span><?php echo esc_html($value);?></span></td>
                    </tr>
                    <?php
                    }
                 }
                ?>
                    </table>
                </div>
        </div>
                </div>
        
        <?php
        $this->load_css();
        exit();
    }

    private function load_css(){
        ?>
        <style>
            .test-container{
                width: 100%;
                background: #f1f1f1;
                margin-top: -30px;
            }

            .mo-ms-tab-content-app-config-table{
                max-width: 1000px;
                background: white;
                padding: 1em 2em;
                margin: 2em auto;
                border-collapse:collapse;
                border-spacing:0;
                display:table;
                font-size:14pt;
                
            }

            .mo_sps_test_connection__success_test_connection-title{
                display:flex;
                justify-content:flex-start;
                align-items:center;
                margin:10px;
                width:90%;
            }

            .mo_sps_test_connection__success_test_connection-content{
                width:90%;
                display:flex;
                justify-content:flex-start;
                align-items:flex-start;
                align-content: flex-start;
                flex-wrap:wrap;
                height:400px;
                overflow-y:scroll;
            }

            .mo_sps_test_connection__success_test_connection-content::-webkit-scrollbar {
                display: none;
            }

            .mo_sps_test_connection__success_test_connection-content-objects{
                padding:10px;
                background-color:#eee;
                font-size:15px;
                margin:10px;
                border-radius:2px;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .mo-ms-tab-content-app-config-table td.left-div{
                width: 40%;
                word-break: break-all;
                font-weight:bold;
                border:2px solid #949090;
                padding:2%;
            }
            .mo-ms-tab-content-app-config-table td.right-div{
                width: 40%;
                word-break: break-all;
                padding:2%;
                border:2px solid #949090;
                word-wrap:break-word;
            }

            .test-container{
                width: 100%;
                background: #f1f1f1;
                margin-top: -30px;
            }

            .mo_sps_test_connection__success_test_connection-title{
                display:flex;justify-content:flex-start;align-items:center;margin:10px;width:90%;
            }
            .mo_sps_test_connection__success_test_connection-content{
                width:90%;display:flex;justify-content:flex-start;align-items:flex-start;align-content: flex-start;flex-wrap:wrap;overflow-y:scroll;
            }
            .mo_sps_test_connection__success_test_connection-content::-webkit-scrollbar {
                display: none;
            }
            .mo_sps_test_connection__success_test_connection-content-objects{
                padding:10px;background-color:#eee;font-size:15px;margin:10px;border-radius:2px;
                display: flex;justify-content: center;align-items: center;
            }

            .mo_sps_test_connection__error{
                width:100%;display:flex;flex-direction:column;justify-content:center;align-items:center;font-size:15px;margin-top:10px;width:100%;
            }
            .mo_sps_test_connection__error-heading{
                width:86%;padding: 15px;text-align: center;background-color:#f2dede;color:#a94442;border: 1px solid #E6B3B2;font-size: 18pt;margin-bottom:20px;
            }
            .mo_sps_test_connection__error-tableHeading{
                padding: 30px 5px 30px 5px;border:1px solid #757575;
            }
            .mo_sps_test_connection__error-table-colkey{
                padding: 30px 5px 30px 5px;border:1px solid #757575;
            }
            .mo_sps_test_connection__error-table-colvalue{
                padding: 30px 5px 30px 5px;border:1px solid #757575;
            }
            .mo_sps_test_connection__success{
                display:flex;align-items:center;flex-direction:column;border:1px solid #eee;height:36rem;padding:10px;
            }
            .mo_sps_test_connection__success-heading{
                width:90%;color: #3c763d;background-color: #dff0d8;padding: 2%;margin-bottom: 20px;text-align: center;border: 1px solid #AEDB9A;font-size: 18pt;
            }

            .mo-ms-tab-content-app-config-table{
                max-width: 1000px;
                background: white;
                padding: 1em 2em;
                margin: 2em auto;
                border-collapse:collapse;
                border-spacing:0;
                display:table;
                font-size:14pt;
            }

            .mo-ms-tab-content-app-config-table td.left-div {
                width: 40%;
                word-break: break-all;
                font-weight:bold;
                border:2px solid #949090;
                padding:2%;
            }
            .mo-ms-tab-content-app-config-table td.right-div {
                width: 40%;
                word-break: break-all;
                padding:2%;
                border:2px solid #949090;
                word-wrap:break-word;
            }

        </style>
        <?php
    }
}