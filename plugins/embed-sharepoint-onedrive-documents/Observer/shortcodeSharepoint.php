<?php
namespace MoSharePointObjectSync\Observer;

use MoSharePointObjectSync\API\Azure;

use MoSharePointObjectSync\Wrappers\pluginConstants;
use MoSharePointObjectSync\Wrappers\sharepointWrapper;
use MoSharePointObjectSync\Wrappers\wpWrapper;
use MoSharePointObjectSync\API\CustomerMOSPS;
use MoSharePointObjectSync\Observer\adminObserver;
use MoSharePointObjectSync\View\documentsSync;


class shortcodeSharepoint{

    private static $obj;
    public $config = [];

    public static function getObserver(){
        if(!isset(self::$obj)){
            self::$obj = new shortcodeSharepoint();
        }
        return self::$obj;
    }
 

    public function mo_sps_shortcode_document_observer($attrs,$content=''){   

        $feedback_config = wpWrapper::mo_sps_get_option(pluginConstants::FEEDBACK_CONFIG);
        $feedback_config['shortcode_embeded'] = 'yes';
        wpWrapper::mo_sps_set_option("mo_sps_feedback_config", $feedback_config);
        
        if(!is_user_logged_in()){
            return "<span style='text-align: center;width: 100%;display: inline-block'>Please <a href='".wp_login_url(get_permalink())."'>login</a> to view the content.</span>";
        }


        $attrs = shortcode_atts([
            'width'=>'100%',
            'height'=>'600px',
             
         ],$attrs,'MO_SPS_SHAREPOINT');        



        $this->config['width'] = $attrs['width'];
        $this->config['height'] = $attrs['height'];

        if( !current_user_can('administrator') ) {
            return $content;
        }
        else{
        wp_enqueue_script('jquery');
        ob_start();
        $document_sync_obj = documentsSync::getView();
        $document_sync_obj->mo_sps_display__tab_shortcode_details($this->config);
        return ob_get_clean();
        }
    }
}