<?php

namespace IfSo\Services\TriggerPortService;

require_once('trigger-export-service.class.php');
require_once('trigger-import-service.class.php');


class TriggerPortHandler{
    private static $instance;
    protected $export_service;
    protected $import_service;

    private function __construct(){
        $this->export_service = TriggerExportService::get_instance();
        $this->import_service = TriggerImportService::get_instance();
    }

    public static function get_instance(){
        if (NULL == self::$instance)
            self::$instance = new TriggerPortHandler();

        return self::$instance;
    }

    public function handle(){
        //HANDLE IT
        if((current_user_can('administrator'))){
            if(check_admin_referer('trigger-port','_ifsononce')){
                if(isset($_REQUEST['exporttrigger']) && isset($_REQUEST['postid']))
                    $this->export_service->export_trigger($_REQUEST['postid']);
                elseif(isset($_REQUEST['duplicatetrigger'])&& isset($_REQUEST['postid']))
                    $this->import_service->handle(true,$this->export_service->gather_data($_REQUEST['postid'])['dataStr']);
                elseif(isset($_REQUEST['importtrigger'])){
                    $this->import_service->handle();
                }
            }
        }

        wp_die();
    }

}