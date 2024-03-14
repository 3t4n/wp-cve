<?php

namespace MoSharePointObjectSync\Observer;

use Error;
use MoSharePointObjectSync\API\Azure;
use MoSharePointObjectSync\Wrappers\pluginConstants;
use MoSharePointObjectSync\Wrappers\wpWrapper;

class documentObserver
{

    private static $obj;

    public static function getObserver()
    {
        if (!isset(self::$obj)) {
            self::$obj = new documentObserver();
        }
        return self::$obj;
    }

    public function mo_sps_doc_embed() {
        if ( ! check_ajax_referer( 'mo_doc_embed__nonce','nonce', false ) ) {
			wp_send_json_error( array(
				'err' => 'Permission denied.',
			) );
			exit;
		}

        $task = sanitize_text_field($_POST['task']);

        switch($task) {
            case 'mo_sps_load_drives': {
                $this->mo_sps_load_all_drives($_POST['payload']);
                break;
            }
            case 'mo_sps_load_drive_docs': {
                $this->mo_sps_load_drive_docs($_POST['payload']);
                break;
            }
            case 'mo_sps_load_folder_docs': {
                $this->mo_sps_load_folder_docs($_POST['payload']);
                break;
            }
            case 'mo_sps_document_search_observer': {
                $this->mo_sps_document_search_observer($_POST['payload']);
                break;
            }
            case 'mo_sps_get_file_download_url': {
                $this->mo_sps_get_file_download_url($_POST['payload']);
                break;
            }
            case 'mo_sps_get_folder_items_using_path': {
                $this->mo_sps_get_folder_items_using_path($_POST['payload']);
                break;
            }
        }
    }

    private function mo_sps_load_drive_docs($payload) {
        $config = wpWrapper::mo_sps_get_option(pluginConstants::APP_CONFIG);
        $drive_id = $payload['drive_id'];
        $drive_name = $payload['drive_name'];
        $breadcrumbs = $payload['breadcrumbs'];
        $is_plugin = $payload['is_plugin'];

        if ($is_plugin == 'y') {
            wpWrapper::mo_sps_set_option(pluginConstants::BREADCRUMBS, $breadcrumbs);
            wpWrapper::mo_sps_delete_option(pluginConstants::SPS_SEL_FOLDER);
        }

        wpWrapper::mo_sps_set_option(pluginConstants::SPS_SEL_DRIVE_NAME, $drive_name);
        wpWrapper::mo_sps_set_option(pluginConstants::SPS_SEL_DRIVE, $drive_id);

        $client = Azure::getClient($config);
        $response = $client->mo_sps_get_drive_docs($drive_id);

        $this->process_docs($response, 'document_sync');
    }

    private function mo_sps_load_folder_docs($payload) {
        $config = wpWrapper::mo_sps_get_option(pluginConstants::APP_CONFIG);
        $drive_id = $payload['drive_id'];
        $folder_id = $payload['folder_id'];
        $breadcrumbs = $payload['breadcrumbs'];
        $is_plugin = $payload['is_plugin'];

        if ($is_plugin == 'y') {
            wpWrapper::mo_sps_set_option(pluginConstants::BREADCRUMBS, $breadcrumbs);
            wpWrapper::mo_sps_set_option(pluginConstants::SPS_SEL_FOLDER, $folder_id);
        }

        $client = Azure::getClient($config);
        $response = $client->mo_sps_get_all_folder_items($drive_id, $folder_id);
        $this->process_docs($response, 'document_sync');
    }

    private function mo_sps_get_folder_items_using_path($payload) {
        $config = wpWrapper::mo_sps_get_option(pluginConstants::APP_CONFIG);
        $folder_path = $payload['folder_path'];
        $folder_id = $payload['folder_id'];
        $breadcrumbs = $payload['breadcrumbs'];
        $is_plugin = $payload['is_plugin'];

        if ($is_plugin == 'y') {
            wpWrapper::mo_sps_set_option(pluginConstants::BREADCRUMBS, $breadcrumbs);
            wpWrapper::mo_sps_set_option(pluginConstants::SPS_SEL_FOLDER, $folder_path);
        }

        $client = Azure::getClient($config);
        $response = $client->mo_sps_get_folder_items_using_path($folder_path);
        $this->process_docs($response, 'document_sync');
    }

    private function mo_sps_load_all_drives($payload) {
        $config = wpWrapper::mo_sps_get_option(pluginConstants::APP_CONFIG);
        $site_id = $payload['site_id'];
        $site_name = $payload['site_name'];
        wpWrapper::mo_sps_set_option(pluginConstants::SPS_SEL_SITE, $site_name);
        wpWrapper::mo_sps_delete_option(pluginConstants::SPS_SEL_DRIVE);
        wpWrapper::mo_sps_delete_option(pluginConstants::SPS_SEL_FOLDER);
        wpWrapper::mo_sps_delete_option(pluginConstants::SPS_SEL_DRIVE_NAME);
        wpWrapper::mo_sps_delete_option(pluginConstants::BREADCRUMBS);

        $client = Azure::getClient($config);
        $default_drive_response = $client->mo_sps_get_default_drive($site_id);

        $response = $client->mo_sps_get_all_drives($site_id);
        $this->process_docs($response, 'drive_sync', $default_drive_response);
    }

    private function process_docs($response, $fc_key, $default_response=null) {
        if($response['status']) {
            wpWrapper::mo_sps_set_feedback_config($fc_key, 'success');
            if($fc_key == 'drive_sync') {
                if($default_response && $default_response['status']) {
                    wpWrapper::mo_sps_set_option(pluginConstants::SPS_SEL_DRIVE, $default_response['data']['id']);
                    wpWrapper::mo_sps_set_option(pluginConstants::SPS_SEL_DRIVE_NAME, $default_response['data']['name']);
                    $response['data']['default_drive'] = $default_response['data']['id'];
                }
                wpWrapper::mo_sps_set_option(pluginConstants::SPS_DRIVES, $response['data']['value']);
            }
            wp_send_json_success($response['data']);
        } else {
            wpWrapper::mo_sps_set_option("error", $response);
            if ($response == "Forbidden") {
                wpWrapper::mo_sps_set_feedback_config($fc_key, $response);
                wp_send_json_error('Forbidden');
            } else if (isset($response['error'])) {

                if ($response['error']) {
                    wpWrapper::mo_sps_set_feedback_config($fc_key, $response['error']);
                    wpWrapper::mo_sps_set_option("error", $response['error']);
                    wp_send_json_error($response['error']);
                } else {
                    wpWrapper::mo_sps_set_feedback_config($fc_key, $response['error_description']);
                    wpWrapper::mo_sps_set_option("error", $response['error_description']);
                    wp_send_json_error($response['error_description']);
                }
            } else {
                wp_send_json_error($response);  
            }
            
        }
    }

    private function mo_sps_document_search_observer($payload) {
        $query_text = $payload['query_text'];
        $drive_id = $payload['drive_id'];
        $folder_id = $payload['folder_id'];

        $config = wpWrapper::mo_sps_get_option(pluginConstants::APP_CONFIG);
        
        $client = Azure::getClient($config);
        $response = $client->mo_sps_search_through_drive_items($drive_id,$query_text);

        if($response['status']){
            wp_send_json_success($response['data']);
        }else{
            $error_code = [
                "Error" => $response['data']['error'],
                "Description" => empty($response['data']['error'])?'':$response['data']['error_description']
            ];

            wp_send_json_error($error_code);
        }
    }

    private function mo_sps_get_file_download_url($payload) {
        $file_id = $payload['file_id'];
        $drive_id = $payload['drive_id'];

        $config = wpWrapper::mo_sps_get_option(pluginConstants::APP_CONFIG);
        $client = Azure::getClient($config);
        $response = $client->mo_sps_get_file_download_url($drive_id, $file_id);

        if($response['status']){
            wp_send_json_success($response['data']);
        }else{
            $error_code = [
                "Error" => $response['data']['error'],
                "Description" => empty($response['data']['error'])?'':$response['data']['error_description']
            ];

            wp_send_json_error($error_code);
        }
    }

}
