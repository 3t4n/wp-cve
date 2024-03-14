<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSAddressdataController {

    function __construct() {

        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'loadaddressdata');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_loadaddressdata':
                    $error = 0;
                    if (isset($_GET['er']))
                        $error = $_GET['er'];
                    break;
            }

            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'loadaddressdata');
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

    function loadaddressdata() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'load-address-data') ) {
             die( 'Security check Failed' ); 
        }
        $result = JSJOBSincluder::getJSModel('addressdata')->loadAddressData();
        $msg = JSJOBSMessages::getMessage($result, 'addressdata');
        $url = wp_nonce_url(admin_url("admin.php?page=jsjobs_addressdata&jsjobslt=loadaddressdata"),"addressdata");
        if ($result == JSJOBS_SAVED) {
            echo '<h1>'.__('Data has been uploaded successfully','js-jobs').'</h1>';
        } else {
            echo '<h1>'.__('Data has not been uploaded','js-jobs').'</h1>';
        }
        echo '<h1><a href="'.esc_url($url).'">'.__('Click here to continue','js-jobs').'</a></h1>';
        ob_flush();
        ob_clean();                                        
        exit();
    }

}

$JSJOBSAddressdata = new JSJOBSAddressdataController();
?>
