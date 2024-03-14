<?php

namespace MoSharePointObjectSync\Observer;
use MoSharePointObjectSync\Wrappers\pluginConstants;
use MoSharePointObjectSync\Wrappers\wpWrapper;

class appConfigObserver{

    private static $obj;
    private static $isliveri;

    public static function getObserver(){
        if(!isset(self::$obj)){
            self::$obj = new appConfigObserver();
        }
        return self::$obj;
    }

    public function mo_sps_app_configuration_api_handler(){
        if ( ! check_ajax_referer( 'mo_sps_app_config__nonce','nonce', false ) ) {
            wp_send_json_error( array(
                'err' => 'Permission denied.',
            ) );
            exit;
        }

        $task = sanitize_text_field($_POST['task']);

        switch ($task){
            case 'mo_sps_auto_connection_save_type':{
                $this->mo_sps_auto_connection_save_type($_POST['payload']);
                break;
            }
        }
    }

    private function mo_sps_auto_connection_save_type($payload) {
        $type = $payload['connection_type'];
        wpWrapper::mo_sps_set_option(pluginConstants::CLOUD_CONNECTOR, $type);
        wp_send_json_success('Connection established successfully.');
    }
}