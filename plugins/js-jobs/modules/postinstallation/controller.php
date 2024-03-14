<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSpostinstallationController {

    function __construct() {

        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'stepone');
        if($this->canaddfile()){
            switch ($layout) {
                case 'admin_stepone':
                    JSJOBSincluder::getJSModel('postinstallation')->getConfigurationValues();
                break;
                case 'admin_steptwo':
                    JSJOBSincluder::getJSModel('postinstallation')->getConfigurationValues();
                break;
                case 'admin_stepthree':
                    JSJOBSincluder::getJSModel('postinstallation')->getConfigurationValues();
                break;
                case 'admin_themedemodata':
                    jsjobs::$_data['flag'] = JSJOBSrequest::getVar('flag');
                break;
                case 'admin_demoimporter':
                    JSJOBSincluder::getJSModel('postinstallation')->getListOfDemoVersions();
                break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'postinstallation');
            $module = jsjobslib::jsjobs_str_replace('jsjobs_', '', $module);
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

    function save(){
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'postinstallation') ) {
            die( 'Security check Failed' ); 
        }
        $data = JSJOBSrequest::get('post');
        $url = admin_url("admin.php?page=jsjobs_postinstallation&jsjobslt=steptwo");
        $result = JSJOBSincluder::getJSModel('postinstallation')->storeconfigurations($data);
        if($data['step'] == 2){
            $url = admin_url("admin.php?page=jsjobs_postinstallation&jsjobslt=stepthree");
        }
        if($data['step'] == 3){
            $url = admin_url("admin.php?page=jsjobs_postinstallation&jsjobslt=stepfour");
        }
        wp_redirect($url);
        exit();
    }

    function savesampledata(){
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-sampledata') ) {
            die( 'Security check Failed' ); 
        }
        $data = JSJOBSrequest::get('post');
        $sampledata = $data['sampledata'];
        $temp_data = 0;
        if(isset($data['temp_data'])){
            $temp_data = 1;
            $jsmenu = 0;
            $empmenu = 0;
        }else{
            $jsmenu = $data['jsmenu'];
            $empmenu = $data['empmenu'];
        }
        if(jsjobs::$theme_chk == 1){
            update_option( 'jsjobs_sample_data', 1 );
            $url = admin_url("admin.php?page=jsjobs_postinstallation&jsjobslt=demoimporter");
        }else{
            $url = admin_url("admin.php?page=jsjobs");
        }
        $result = JSJOBSincluder::getJSModel('postinstallation')->installSampleData($sampledata,$jsmenu,$empmenu,$temp_data);
        wp_redirect($url);
        exit();
    }

    function savetemplatesampledata(){
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-template-sampledata') ) {
            die( 'Security check Failed' ); 
        }
        $flag = JSJOBSrequest::getVar('flag');
        $result = JSJOBSincluder::getJSModel('postinstallation')->installSampleDataTemplate($flag);
        $url = admin_url("admin.php?page=jsjobs_postinstallation&jsjobslt=themedemodata&flag=".$result);
        wp_redirect($url);
        exit();
    }

    function importtemplatesampledata(){
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'import-template-sampledata') ) {
            die( 'Security check Failed' ); 
        }
        $flag = JSJOBSrequest::getVar('flag','',0);// zero as default value to avoid problems
        if($flag == 'f'){
            $result = JSJOBSincluder::getJSModel('postinstallation')->importTemplateSampleData($flag);
        }else{
            $result = 0;
        }
        $url = admin_url("admin.php?page=jsjobs_postinstallation&jsjobslt=themedemodata&flag=".$result);
        wp_redirect($url);
        exit();
    }

    function getdemocode(){
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'demo-code') ) {
            die( 'Security check Failed' ); 
        }
        $demoid = JSJOBSrequest::getVar('demoid');
        $foldername = JSJOBSrequest::getVar('foldername');
        $demo_overwrite = JSJOBSrequest::getVar('demo_overwrite');
        $result = JSJOBSincluder::getJSModel('postinstallation')->getDemo($demoid,$foldername,$demo_overwrite);
        $url = admin_url("admin.php?page=jsjobs");
        wp_redirect($url);
        exit();
    }

    function importfreetoprotemplatedata(){
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'import-free-topro-templatedata') ) {
            die( 'Security check Failed' ); 
        }
        if(jsjobs::$theme_chk == 1){// 1 for job manager
            $result = JSJOBSincluder::getJSModel('postinstallation')->installFreeToProData();
        }else{
            $result = JSJOBSincluder::getJSModel('postinstallation')->installFreeToProDataJobHub();
        }
        $url = admin_url("admin.php?page=jsjobs");
        wp_redirect($url);
        exit();
    }
}
$JSJOBSpostinstallationController = new JSJOBSpostinstallationController();
?>
