<?php

namespace MoSharePointObjectSync\API;

use Error;
use MoSharePointObjectSync\Wrappers\sharepointWrapper;
use MoSharePointObjectSync\Wrappers\wpWrapper;
use MoSharePointObjectSync\Wrappers\pluginConstants;


class Azure
{

    private static $obj;
    private $endpoints;
    private $config;
    private $scope = "https://graph.microsoft.com/.default";
    private $access_token;
    private $handler;

    private function __construct($config)
    {
        $this->config = $config;
        $this->handler = Authorization::getController();
    }

    public static function getClient($config)
    {
        if (!isset(self::$obj)) {
            self::$obj = new Azure($config);
            self::$obj->setEndpoints();
        }
        return self::$obj;
    }

    private function setEndpoints()
    {
        $connector = get_option(pluginConstants::CLOUD_CONNECTOR);
        
        $api_endpoint = ($connector == 'personal') ? 'api.onedrive.com' : 'graph.microsoft.com'; 

        $tenant_id = isset($this->config['tenant_id']) ? $this->config['tenant_id'] : '';
        $this->endpoints['token'] = 'https://login.microsoftonline.com/' . $tenant_id . '/oauth2/v2.0/token';
        $this->endpoints['sps_common_token'] =  'https://login.microsoftonline.com/common/oauth2/v2.0/token';
        $this->endpoints['sites'] = "https://".$api_endpoint."/v1.0/sites?search=*&\$select=id,displayName";
        $this->endpoints['default_site'] = "https://".$api_endpoint."/v1.0/site/root?search=*&\$select=id,displayName";
        $this->endpoints['default_drive'] = "https://".$api_endpoint."/v1.0/sites/%s/drive";
        $this->endpoints['folder_items_by_path'] = "https://".$api_endpoint."/v1.0%s/children";
        $this->endpoints['download_url'] = "https://".$api_endpoint."/v1.0/drives/%s/items/%s";
        $this->endpoints['drives'] = "https://".$api_endpoint."/v1.0/sites/%s/drives";
        $this->endpoints['docs'] = "https://".$api_endpoint."/v1.0/drives/%s/root/children";
        $this->endpoints['folder_items'] = "https://".$api_endpoint."/v1.0/drives/%s/items/%s/children";
        $this->endpoints['file_thumbnails'] = "https://".$api_endpoint."/v1.0/drives/%s/items/%s/thumbnails";
        $this->endpoints['file_preview'] = "https://".$api_endpoint."/v1.0/drives/%s/items/%s/preview";
        $this->endpoints['search_driveitems'] = "https://".$api_endpoint."/v1.0/drives/%s/root/search(q='%s')";
        $this->endpoints['search_folderitems'] = "https://".$api_endpoint."/v1.0/drives/%s/items/%s/search(q='%s')";
        $this->endpoints['upload_items'] = "https://".$api_endpoint."/v1.0/drives/%s/items/%s:/%s:/createUploadSession";
        $this->endpoints['download_items'] = "https://".$api_endpoint."/v1.0/drives/%s/items/%s/content";
        $this->endpoints['file_item'] = "https://".$api_endpoint."/v1.0/drives/%s/items/%s";
        $this->endpoints['lists'] = "https://".$api_endpoint."/v1.0/sites/%s/lists";
        $this->endpoints['list_items'] = "https://".$api_endpoint."/v1.0/sites/%s/lists/%s?expand=columns,items(expand=fields)";
        $this->endpoints['me'] = "https://graph.microsoft.com/v1.0/me";
        $this->endpoints['onedrives'] = "https://graph.microsoft.com/v1.0/me/drives";
        $this->endpoints['sps_personal_onedrive'] = "https://login.live.com/oauth20_token.srf";
        $this->endpoints['personal_drives'] = "https://".$api_endpoint."/v1.0/drives";
    }

    public function mo_sps_access_token_details()
    {
        $config = wpWrapper::mo_sps_get_option(pluginConstants::APP_CONFIG);
        $type = isset($config['app_type']) ? $config['app_type'] : null;

        if ( $type == 'auto') {
            $response = $this->handler->mo_sps_get_access_token_using_authorization_code($this->endpoints, $this->config, $this->scope);
        } else {
            $response = $this->handler->mo_sps_get_access_token_using_client_credentials($this->endpoints, $this->config, $this->scope);
        } 

        if ($response['status']) {
            return true;
        }
    }

    public function mo_sps_send_access_token($send_rftk = false)
    {
        $config = wpWrapper::mo_sps_get_option(pluginConstants::APP_CONFIG);
        $type = isset($config['app_type']) ? $config['app_type'] : null;

	    if ( $type == 'auto') {
            $response = $this->handler->mo_sps_get_access_token_using_authorization_code($this->endpoints, $this->config, $this->scope,$send_rftk);
        } else {
            $response = $this->handler->mo_sps_get_access_token_using_client_credentials($this->endpoints, $this->config, $this->scope);
        } 
        
        if($response['status']) {
            if($send_rftk) {return $response;}
            else {$this->access_token = $response['data'];}
        }

        if ($this->access_token) {
            return $this->access_token;
        }
    }

    
    public function mo_sps_get_onedrives(){
        $access_token = $this->mo_sps_send_access_token();
        if(!$access_token){
            return $this->access_token;
        }

        $args = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        $response = $this->handler->mo_sps_get_request(sprintf($this->endpoints['onedrives']),$args);

        return $response;
    }

    public function mo_sps_get_personal_onedrive() {
        $access_token = $this->mo_sps_send_access_token();
        if(!$access_token){
            return $this->access_token;
        }

        $args = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        $response = $this->handler->mo_sps_get_request(sprintf($this->endpoints['personal_drives']),$args);
        return $response;
    }


    public function mo_sps_process_tokens_for_auto_connection() {
        $response = $this->mo_sps_send_access_token(true);
        $connector = $this->config['connector'];
        if($response['status']) {
            if(isset($response['data']['refresh_token']))
                $this->config['refresh_token'] = $response['data']['refresh_token'];
            
            if($connector == 'personal') {
                if(isset($response['data']['id_token'])) {
                    $jwt_object = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $response['data']['id_token'])[1]))), true);
                    $this->config['email'] = isset($jwt_object['email']) ? $jwt_object['email'] : '';
                }
            } else {
                if(isset($response['data']['access_token'])) {
                    $this->access_token = $response['data']['access_token'];
                } else if (isset($response['data'])){
                    $this->access_token = $response['data'];
                }
            }
        }
        return $this->config;
    }

    public function mo_sps_document_array($ServerRelativeUrl)
    {
        $app = wpWrapper::mo_sps_get_option(pluginConstants::APP_CONFIG);
        $this->access_token = sanitize_text_field($this->handler->mo_sps_get_access_token_using_client_credentials($this->endpoints, $this->config, $this->scope));

        $urlarray = explode("/", $app['site_uri']);
        $end = $urlarray[count($urlarray) - 1];
        if ($app['site_uri'] != '') {

            $second_last = $urlarray[count($urlarray) - 2];
        }


        $args = array(
            'Authorization' => 'Bearer ' . $this->access_token,
            'Accept' => 'application/json; odata=verbose'
        );


        if ((!empty($app['site_uri']) && "https://" . wpWrapper::mo_sps_get_domain_from_url($app['site_uri']) == "https://" . wpWrapper::mo_sps_get_domain_from_url($app['admin_uri'])) || empty($app['site_uri'])) {
            $ServerRelativeUrl = $ServerRelativeUrl;
        } else {

            $ServerRelativeUrl = '/' . $second_last . '/' . $end . $ServerRelativeUrl;
        }

        if (!empty($app['admin_uri'])) {
            $ServerRelativeUrl = wpWrapper::mo_urlencode($ServerRelativeUrl);

            $url = !empty($app['site_uri']) ? "https://" . wpWrapper::mo_sps_get_domain_from_url($app['site_uri']) . "/_api/web/GetFolderByServerRelativePath(decodedUrl='$ServerRelativeUrl')/?" . '$expand=files,folders' . '&$expand=Editor/Id' : "https://" . wpWrapper::mo_sps_get_domain_from_url($app['admin_uri']) . "/_api/web/GetFolderByServerRelativePath(decodedUrl='$ServerRelativeUrl')/?" . '$expand=files,folders' . '&$expand=Editor/Id';

            $response = $this->handler->mo_sps_get_request($url, $args, true);
        } else {
            $response = '';
        }

        return $response;
    }

    public function mo_sps_get_all_sites(){
        $access_token = $this->mo_sps_send_access_token();
        if(!$access_token){
            return $this->access_token;
        }

        $args = [
            'Authorization' => 'Bearer ' . $access_token,
        ];

        $response = $this->handler->mo_sps_get_request($this->endpoints['sites'],$args);
        return $response;
    }

    public function mo_sps_get_all_drives($site_id){
        $access_token = $this->mo_sps_send_access_token();
        if(!$access_token){
            return $this->access_token;
        }

        $args = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        $response = $this->handler->mo_sps_get_request(sprintf($this->endpoints['drives'],$site_id),$args);

        return $response;
    }

    public function mo_sps_get_default_drive($site_id){
        $access_token = $this->mo_sps_send_access_token();
        if(!$access_token){
            return $this->access_token;
        }

        $args = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        $response = $this->handler->mo_sps_get_request(sprintf($this->endpoints['default_drive'],$site_id),$args);

        return $response;
    }

    public function mo_sps_get_drive_docs($drive_id){
        $access_token = $this->mo_sps_send_access_token();
        if(!$access_token){
            return $this->access_token;
        }

        $args = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        $response = $this->handler->mo_sps_get_request(sprintf($this->endpoints['docs'],$drive_id),$args);

        return $response;
    }

    public function mo_sps_get_all_folder_items($drive_id, $folder_id){
        $access_token = $this->mo_sps_send_access_token();
        if(!$access_token){
            return $this->access_token;
        }

        $args = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        $response = $this->handler->mo_sps_get_request(sprintf($this->endpoints['folder_items'],$drive_id,$folder_id),$args);
        return $response;
    }

    public function mo_sps_search_through_drive_items($drive_id, $query_text) {
        $access_token = $this->mo_sps_send_access_token();
        if(!$access_token){
            return $this->access_token;
        }

        $args = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        $response = $this->handler->mo_sps_get_request(sprintf($this->endpoints['search_driveitems'],$drive_id,$query_text),$args);

        return $response;
    }

    public function mo_sps_get_file_download_url($drive_id, $file_id) {
        $access_token = $this->mo_sps_send_access_token();
        if(!$access_token){
            return $this->access_token;
        }

        $args = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        $response = $this->handler->mo_sps_get_request(sprintf($this->endpoints['download_url'],$drive_id,$file_id),$args);

        return $response;
    }

    public function mo_sps_get_my_user(){
        $access_token = $this->mo_sps_send_access_token();
        if(!$access_token){
            return $this->access_token;
        }

        $args = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        $response = $this->handler->mo_sps_get_request(sprintf($this->endpoints['me']),$args);

        return $response;
    }

    public function mo_sps_get_folder_items_using_path($item_path) {
        $access_token = $this->mo_sps_send_access_token();
        if(!$access_token){
            return $this->access_token;
        }

        $args = [
            'Authorization' => 'Bearer '.$access_token,
        ];
        $url = sprintf($this->endpoints['folder_items_by_path'], $item_path);
        $response = $this->handler->mo_sps_get_request($url,$args);

        return $response;
    }

}
