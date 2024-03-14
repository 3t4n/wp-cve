<?php
/**
 *HTTP API for interacting with the analytics service via AJAX.
 *Available methods for admin and public use are listed in the handle and public_handle methods respectively
 *
 * @author Nick Martianov
 *
 **/
namespace IfSo\PublicFace\Services\AnalyticsService;

require_once('analytics-service.class.php');


class AnalyticsAjaxHandler {
    private static $instance;
    protected $analytics_service;

    private function __construct() {
        $this->analytics_service =  AnalyticsService::get_instance();
    }

    public static function get_instance() {
        if ( NULL == self::$instance )
            self::$instance = new AnalyticsAjaxHandler();

        return self::$instance;
    }

    public function handle(){
        //HANDLE IT
        $allowed = ((current_user_can('administrator') || current_user_can('editor')) || (!empty($_REQUEST['page_url']) && strpos($_REQUEST['page_url'], admin_url()) === false));
        $refcheck = (!empty($_REQUEST['_ifsononce']) && check_admin_referer('ifso-admin-nonce','_ifsononce')) || (!empty($_REQUEST['nonce']) && check_ajax_referer( 'ifso-nonce', 'nonce' ));

        
        if($allowed && $refcheck && wp_doing_ajax() && isset($_REQUEST['an_action'])  && isset($_REQUEST['postid'])){
            $res = null;
            switch ($_REQUEST['an_action']){
                case 'getField':
                    $res = $this->get_field();
                    break;
                case 'getFields':
                    $res = $this->get_fields();
                    break;
                case 'updateField':
                    $this->update_field();
                    break;
                case 'resetField':
                    $this->reset_field();
                    break;
                case 'resetFields':
                    $this->reset_fields();
                    break;
                case 'incrementField':
                    $this->increment_field();
                    break;
                case 'decrementField':
                    $this->decrement_field();
                    break;
                case 'doConversion':
                    $this->do_conversion();
                    break;
                case 'ajaxViews':
                    $this->ajax_views();
                    break;
                case 'resetAllAnalytics':
                    $this->reset_all_triggers_analytics();
                    break;
            }
            if(!empty($res))
                echo json_encode($res);
        }
        wp_die();
    }

    public function public_handle(){
        //HANDLE IT
        if(check_ajax_referer( 'ifso-nonce', 'nonce' ) && wp_doing_ajax() && isset($_REQUEST['an_action'])){
            $res = null;
            switch ($_REQUEST['an_action']){
                case 'doConversion':
                    $this->do_conversion();
                    break;
                case 'ajaxViews':
                    $this->ajax_views();
                    break;
            }
            if(!empty($res))
                echo json_encode($res);
        }
        wp_die();
    }

    private function get_field(){
        $res = null;
        if(isset($_REQUEST['versionid']) && isset($_REQUEST['field'])){
            if($_REQUEST['versionoid']!='default')
                $res = $this->analytics_service->get_analytics_field($_REQUEST['postid'],$_REQUEST['versionid'],$_REQUEST['field']);
            else
                $res = $this->analytics_service->get_default_analytics_field($_REQUEST['postid'],$_REQUEST['field']);
        }
        return $res;
    }
    private function get_fields(){
        $res = null;
        if(isset($_REQUEST['versionid'])){
            if($_REQUEST['versionid']!='default')
                $res = $this->analytics_service->get_analytics_fields($_REQUEST['postid'],$_REQUEST['versionid'],true);
            else
                $res = $this->analytics_service->get_default_analytics_fields($_REQUEST['postid']);
        }
        else
            $res = $this->analytics_service->get_analytics_fields($_REQUEST['postid'],false,true);
        return $res;
    }
    private function update_field(){
        if(isset($_REQUEST['versionid']) && isset($_REQUEST['field']) && isset($_REQUEST['updval'])){
            if($_REQUEST['versionid']!='default')
                $this->analytics_service->update_analytics_field($_REQUEST['postid'],$_REQUEST['versionid'],$_REQUEST['field'],$_REQUEST['updval']);
            else
                $this->analytics_service->update_default_analytics_field($_REQUEST['postid'],$_REQUEST['field'],$_REQUEST['updval']);
        }
    }
    private function reset_field(){
        if(isset($_REQUEST['versionid']) && isset($_REQUEST['field'])){
            if($_REQUEST['versionid']!='default')
                $this->analytics_service->update_analytics_field($_REQUEST['postid'],$_REQUEST['versionid'],$_REQUEST['field'],0);
            else
                $this->analytics_service->update_default_analytics_field($_REQUEST['postid'],$_REQUEST['field'],0);
        }
    }
    private function reset_fields(){
        if(isset($_REQUEST['postid'])){
            if(isset($_REQUEST['versionid']))
                $this->analytics_service->reset_analytics_fields($_REQUEST['postid'],$_REQUEST['versionid']);
            else
                $this->analytics_service->reset_analytics_fields($_REQUEST['postid']);
        }
    }
    private function increment_field(){
        if(isset($_REQUEST['versionid']) && isset($_REQUEST['field'])){
            if($_REQUEST['versionid']!='default')
                $this->analytics_service->increment_analytics_field($_REQUEST['postid'],$_REQUEST['versionid'],$_REQUEST['field']);
            else
                $this->analytics_service->increment_default_analytics_field($_REQUEST['postid'],$_REQUEST['field']);
        }
    }

    private function decrement_field(){
        if(isset($_REQUEST['versionid']) && isset($_REQUEST['field'])){
            if($_REQUEST['versionid']!='default')
                $this->analytics_service->decrement_analytics_field($_REQUEST['postid'],$_REQUEST['versionid'],$_REQUEST['field']);
            else
                $this->analytics_service->decrement_default_analytics_field($_REQUEST['postid'],$_REQUEST['field']);
        }
    }

    private function do_conversion(){
        if(isset($_REQUEST['viewed_triggers']) && isset($_REQUEST['conversions'])){
            $viewed_triggers = json_decode(stripslashes($_REQUEST['viewed_triggers']),true);
            $conversions = json_decode(stripslashes($_REQUEST['conversions']),true);
            if(is_array($viewed_triggers) && is_array($conversions)){
                foreach ($conversions as $conversion){
                    if(isset($conversion['once_per_time']) && !empty($conversion['name']))
                        $this->analytics_service->do_conversion($viewed_triggers,$conversion['allowed'],$conversion['disallowed'],$conversion['once_per_time'],$conversion['name']);
                    else
                        $this->analytics_service->do_conversion($viewed_triggers,$conversion['allowed'],$conversion['disallowed']);
                }
            }
        }
    }

    private function ajax_views(){
        if(isset($_REQUEST['data']) && !empty($_REQUEST['data'])){
            $data_json = json_decode(stripslashes($_REQUEST['data']),true);
            if(is_array($data_json)){
                foreach($data_json as $postid=>$versionid){
                    if($versionid!=='default') $this->analytics_service->increment_analytics_field($postid,$versionid,'views');
                    else $this->analytics_service->increment_default_analytics_field($postid,'views');
                }
            }
        }
        \IfSo\PublicFace\Helpers\CookieConsent::get_instance()->set_cookie($this->analytics_service->currently_viewing_cookie_name,'',0,'/');
    }

    private function reset_all_triggers_analytics(){
       $this->analytics_service->reset_all_triggers_analytics_fields();
    }


}