<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSJsjobsController {

    function __construct() {

        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'controlpanel');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_controlpanel':
                    JSJOBSincluder::getJSModel('jsjobs')->getAdminControlPanelData();
                    break;
                case 'admin_jsjobsstats':
                    JSJOBSincluder::getJSModel('jsjobs')->getJsjobsStats();
                    break;
                case 'info':
                    $id = JSJOBSrequest::getVar('jsjobsid');
                    JSJOBSincluder::getJSModel('announcement')->getAnnouncementDetails($id);
                    break;
                case 'updates':

                    break;
                case 'login':
                    if(JSJOBSincluder::getObjectClass('user')->isguest()){
                        $url = JSJOBSrequest::getVar('jsjobsredirecturl');
                        if(isset($url)){
                            jsjobs::$_data[0]['redirect_url'] = jsjobslib::jsjobs_safe_decoding($url);
                        }else{
                            jsjobs::$_data[0]['redirect_url'] = home_url();
                        }
                    }else{
                        $finalurl = wp_logout_url(get_permalink());
                        jsjobs::$_error_flag = true;
                        if(class_exists('job_manager_Messages')){
                            job_manager_Messages::alreadyLoggedIn($finalurl);
                        }elseif(class_exists('job_hub_Messages')){
                            job_hub_Messages::alreadyLoggedIn($finalurl);
                        }else{
                            JSJOBSLayout::getUserAlreadyLoggedin($finalurl);
                        }
                    }
                    break;
                case 'admin_stepone': //Installation
                    $array = jsjobslib::jsjobs_explode('.', phpversion());
                    $phpversion = $array[0] . '.' . $array[1];
                    // $curlexist = function_exists('curl_version');
                    //$curlversion = curl_version()['version'];
                    jsjobs::$_data[0]['phpversion'] = $phpversion;
                    // jsjobs::$_data[0]['curlversion'] = $curlversion;
                    // jsjobs::$_data[0]['curlexist'] = $curlexist;
                    JSJOBSincluder::getJSModel('jsjobs')->getStepTwoValidate();
                    break;
                case 'admin_steptwo' : //Installation
                    if(get_option( 'jsjobs_versionlist_response', '' ) != ""){
                        jsjobs::$_data['response'] = get_option( 'jsjobs_versionlist_response');
                        delete_option( 'jsjobs_versionlist_response' );
                    }else{
                        jsjobs::$_data['response'] = '';
                    }
                    if(get_option( 'jsjobs_transaction_key', '' ) != ''){
                        jsjobs::$_data['transactionkey'] = get_option( 'jsjobs_transaction_key');
                        delete_option( 'jsjobs_transaction_key' );
                    }else{
                        jsjobs::$_data['transactionkey'] = '';
                    }
                    //JSJOBSincluder::getJSModel('jsjobs')->checkVersion();
                break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'jsjobs');
            $module = jsjobslib::jsjobs_str_replace('jsjobs_', '', $module);
            if($layout=="thankyou"){
                if($module=="" || $module!="jsjobs") $module="jsjobs";
            }
            JSJOBSincluder::include_file($layout, $module);
        }
    }

    function canaddfile() {
        if (isset($_POST['form_request']) && $_POST['form_request'] == 'jsjobs')
            return false;
        elseif (isset($_GET['action']) && $_GET['action'] == 'jsjobtask')
            return false;
        else
            return true;
    }

    function getversionlist() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'version-list') ) {
            die( 'Security check Failed' ); 
        }
        $data =  JSJOBSrequest::get('post');
        $response = JSJOBSincluder::getJSModel('jsjobs')->getmyversionlist($data);
        $response = jsjobslib::jsjobs_safe_encoding($response);
        update_option( 'jsjobs_versionlist_response', $response );
        $url = admin_url("admin.php?page=jsjobs&jsjobslt=steptwo");
        wp_redirect($url);
        die();
    }


}

$JSJOBSJsjobsController = new JSJOBSJsjobsController();
?>
