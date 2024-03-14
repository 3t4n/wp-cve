<?php

if (!class_exists("crmformsZohoApi")) {

    class zcfaccountApi {

        public $zohocrmurl;

        public function __construct() {
            $configSetting = get_option("zcf_crmformswpbuilder_settings");
            $zcrm_integ_domain_name         = get_option("zcrm_integ_domain_name");
            if($zcrm_integ_domain_name !=''){
                $domain_setup       = "https://www.zohoapis.".$zcrm_integ_domain_name;
            }else{
                $domain_setup       = "https://www.zohoapis.com";
            }

            $this->apiurl = $domain_setup;
            $this->authtoken = $configSetting['authtoken'];

        }
        public function zcfGetModuleFields($module, $methodname, $authkey, $param = "", $recordId = "") {

          require_once( ZCF_BASE_DIR_URI . "includes/crmoauthentication.php");
          zcfcheckAccessToken();
          $this->authtoken = base64_decode(base64_decode(base64_decode($SettingsConfig['authtoken'])));

                $zfformbaseurl = $this->apiurl . "/crm/v2/settings/layouts?module=" . $module;
                $args = array(
                'timeout' => '5',
                'redirection' => '5',
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(
                        'authorization' => 'Zoho-oauthtoken ' . $authkey
                    ),
                'cookies' => array()
                );
               $zfformsresponse =  wp_remote_get( $zfformbaseurl, $args );
               $result_array = json_decode(wp_remote_retrieve_body($zfformsresponse),true);
               global $wpdb;
         $wpdb->query("
                    CREATE TABLE IF NOT EXISTS `zcf_zohocrm_moduleLists` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                          `modulename` varchar(255) NOT NULL,
                          `Layoutname` varchar(255) NOT NULL,
                          `layoutID` varchar(255) NOT NULL,
                           PRIMARY KEY ( id )
                        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8
               ");
               $delete_moduleList = $wpdb->query("delete from zcf_zohocrm_moduleLists where modulename = '$module'");
              foreach($result_array['layouts'] as $value_array){
                $layoutname = $value_array['name'];
                $layoutId   = $value_array['id'];
                $wpdb->insert('zcf_zohocrm_moduleLists', array('modulename' => $module, 'Layoutname' => $layoutname, 'layoutID' => $layoutId));
               }

            return $result_array;
        }

        public function zcfFormDatainsert($modulename, $methodname, $authkey, $xmlData = "", $extraParams = "") {
                require_once( ZCF_BASE_DIR_URI . "includes/crmoauthentication.php");
                zcfcheckAccessToken();
                $requesthosturl = parse_url($_SERVER['HTTP_REFERER']);
                $domainUrl = parse_url(get_site_url());
                $SettingsConfig = get_option("zcf_crmformswpbuilder_settings");


                $this->authtoken = base64_decode(base64_decode(base64_decode($SettingsConfig['authtoken'])));

                $zfformbaseurl = $this->apiurl . "/crm/v2/" . $modulename;
                $args = array(
                'body' => $xmlData,
                'timeout' => '5',
                'redirection' => '5',
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(
                        'authorization' => 'Zoho-oauthtoken ' . $this->authtoken
                    ),
                'cookies' => array()
                );

               $zfformsresponse =  wp_remote_post( $zfformbaseurl, $args );
               $responseresultjson = json_decode(wp_remote_retrieve_body($zfformsresponse),true);
               $this->zcf_SubmitLog($responseresultjson,$xmlData);
               if ($extraParams && is_array($extraParams)) {
                foreach ($extraParams as $field => $path) {
                        $this->zcfInsertattachment($responseresultjson, $authkey, $path, $modulename);
                }
               }
               return $responseresultjson;

        }
        public function zcf_SubmitLog($responseresultjson,$fetchData=""){
            global $wpdb;
            $status = $responseresultjson['data'][0]['code'];
            $desc = serialize($responseresultjson['data'][0]);
            $joinstring = $desc."".$fetchData;
            $wpdb->insert( 'zcf_submitlogs' , array( 'crmsubmitlogStatus' => $status,'crmsubmitlogDescribtion'=>$joinstring) );
        }
        public function zcfInsertattachment($response, $authkey, $path='', $modulename) {
            $id = $response['data'][0]['details']['id'];

          $url = $this->apiurl ."/crm/v2/$modulename/$id/Attachments";
          $path = '@' . $path;
          $post = array("file" => $path);
          $args = array(
              'body'        => $post,
              'timeout' => '5',
              'redirection' => '5',
              'httpversion' => '1.0',
              'blocking' => true,
              'cookies' => array(),
              'accept'        => 'application/json',
              'content-type'  => 'application/binary',
              'headers' => array(
                      'authorization' => 'Zoho-oauthtoken ' . $this->authtoken
              ),
            );
          $result = wp_remote_post(  $url, $args );
        }


        public function zcfGetRecords($modulename, $methodname, $authkey, $selectColumns = "", $xmlData = "", $extraParams = "") {
            $postContent = "scope=crmapi";
            $postContent .= "&authtoken={$authkey}"; //Give your authtoken
            if ($selectColumns == "") {
                $postContent .= "&selectColumns=All";
            } else {
                $postContent .= "&selectColumns={$modulename}( {$selectColumns} )";
            }

            if ($extraParams != "") {
                $postContent .= $extraParams;
            }
        }
        public function zcfGetModules($TFA_authtoken) {
            global $wpdb;
            $zfformbaseurl = $this->apiurl . "/crm/v2/settings/modules";
                $args = array(
                'timeout' => '5',
                'redirection' => '5',
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(
                        'authorization' => 'Zoho-oauthtoken ' . $TFA_authtoken
                    ),
                'cookies' => array()
                );
               $zfformsresponse =  wp_remote_get( $zfformbaseurl, $args );
               $responsedata = json_decode(wp_remote_retrieve_body($zfformsresponse),true);
                if( $responsedata !=''  && $responsedata['modules'][0] !=''){
                 $desc = serialize($responsedata['modules'][0]);
               }elseif($responsedata !=''  && $responsedata['modules'][0] ==''){
                  $desc = serialize($responsedata);
               }
               $wpdb->insert( 'zcf_submitlogs' , array( 'crmsubmitlogStatus' => "moduleSyc",'crmsubmitlogDescribtion'=>$desc) );
                $listModule = array();
                $configcreated_time = date("Y-m-d H:i:s");
                foreach ($responsedata['modules'] as $key => $value) {
                    $api_supported = $value['api_supported'];
                    $plural_label = $value['singular_label'];
                    $api_name = $value['api_name'];
                    $module_name = $value['module_name'];
                    $moduleid = $value['id'];
                    $business_card_field_limit = $value['business_card_field_limit'];
                    $resultarray = $wpdb->get_results($wpdb->prepare("select *from zcf_zohocrm_list_module where api_name=%s and module_name=%s and module_id =%s", $api_name, $module_name, $moduleid));
                    if (count($resultarray) == 0) {
                        $fields = $wpdb->insert('zcf_zohocrm_list_module', array('api_supported' => "$api_supported", 'plural_label' => "$plural_label", 'api_name' => "$api_name", 'module_name' => "$module_name", 'module_id' => "$moduleid", 'business_card_field_limit' => "$business_card_field_limit", 'modifydate' => $configcreated_time));
                    } else {
                        $fields = $wpdb->update('zcf_zohocrm_list_module', array('api_supported' => "$api_supported", 'plural_label' => "$plural_label", 'api_name' => "$api_name", 'module_name' => "$module_name", 'module_id' => "$moduleid", 'business_card_field_limit' => "$business_card_field_limit", 'modifydate' => $configcreated_time), array('api_name' => "$api_name", 'module_id' => "$moduleid"));
                    }
                }

            return true;
        }
        public function zcfgetAssignmentRule($TFA_authtoken, $modulename) {
            global $wpdb;
            $zfformbaseurl = $this->apiurl . "/crm/v2/settings/assignment_rules/" . $modulename;
            $args = array(
                'timeout' => '5',
                'redirection' => '5',
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(
                        'authorization' => 'Zoho-oauthtoken ' . $TFA_authtoken
                    ),
                'cookies' => array()
                );
               $zfformsresponse =  wp_remote_get( $zfformbaseurl, $args );
               $responsedata = json_decode(wp_remote_retrieve_body($zfformsresponse),true);
                $configcreated_time = date("Y-m-d H:i:s");

            if(!empty($responsedata['assignment_rules'])){
              foreach ($responsedata['assignment_rules'] as $key => $value) {
                    $modulename = $value['module'];
                    $assignmentrrule_name = $value['name'];
                    $assignmentrule_ID = $value['id'];
                    $resultarray = $wpdb->get_results($wpdb->prepare("select *from zcf_zohocrm_assignmentrule where assignmentrule_ID =%s", $assignmentrule_ID));
                    if (count($resultarray) == 0) {
                        $fields = $wpdb->insert('zcf_zohocrm_assignmentrule', array('modulename' => "$modulename", 'assignmentrule_ID' => "$assignmentrule_ID", 'assignmentrrule_name' => "$assignmentrrule_name"));
                    } else {
                        $fields = $wpdb->update('zcf_zohocrm_assignmentrule', array('modulename' => "$modulename", 'assignmentrule_ID' => "$assignmentrule_ID", 'assignmentrrule_name' => "$assignmentrrule_name"), array('assignmentrule_ID' => "$assignmentrule_ID"));
                    }
                }
            }

            return true;
        }

        public function zcfGetUserRecord($modulename, $methodname, $authkey, $selectColumns = "", $xmlData = "", $extraParams = "") {
                $zfformbaseurl = $this->apiurl . "/crm/v2/users?type=AllUsers";
                $args = array(
                'body' => $xmlData,
                'timeout' => '5',
                'redirection' => '5',
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(
                        'authorization' => 'Zoho-oauthtoken ' . $authkey
                    ),
                'cookies' => array()
                );
               $zfformsresponse =  wp_remote_get( $zfformbaseurl, $args );
               $result_array = json_decode(wp_remote_retrieve_body($zfformsresponse),true);
            return $result_array;
        }

    }

}
?>
