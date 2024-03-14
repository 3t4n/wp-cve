<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSCityController {

    private $_msgkey;

    function __construct() {
        self::handleRequest();
        $this->_msgkey = JSJOBSincluder::getJSModel('city')->getMessagekey();
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'cities');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_cities':
                    $countryid = JSJOBSrequest::getVar('countryid');
                    $stateid = JSJOBSrequest::getVar('stateid');

                    update_option("jsjobs_countryid_for_city",$countryid);
                    update_option("jsjobs_stateid_for_city",$stateid);
                    JSJOBSincluder::getJSModel('city')->getAllStatesCities($countryid, $stateid);
                    break;
                case 'admin_formcity':
                    $nonce = JSJOBSrequest::getVar('_wpnonce');
                    if (! wp_verify_nonce( $nonce, 'formcity') ) {
                        JSJOBSincluder::getJSModel('common')->js_verify_nonce();
                    }
                    $id = JSJOBSrequest::getVar('jsjobsid');
                    JSJOBSincluder::getJSModel('city')->getCitybyId($id);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'city');
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

    function getaddressdatabycityname() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'address-data-by-cityname') ) {
            die( 'Security check Failed' ); 
        }
        $cityname = JSJOBSrequest::getVar('q');
        $result = JSJOBSincluder::getJSModel('city')->getAddressDataByCityName($cityname);
        $json_response = json_encode($result);
        echo wp_kses($json_response, JSJOBS_ALLOWED_TAGS);
        exit();
    }

    function removecity() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-city') ) {
             die( 'Security check Failed' );
        }
        $countryid = get_option( 'jsjobs_countryid_for_city');
        $stateid = get_option( 'jsjobs_stateid_for_city');

        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('city')->deleteCities($ids);
        $msg = JSJOBSMessages::getMessage($result, 'city');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('city');
        $url = admin_url("admin.php?page=jsjobs_city&jsjobslt=cities&countryid=" . $countryid . "&stateid=" . $stateid . '&_wpnonce=' . $nonce );
        wp_redirect($url);
        die();
    }

    function publish() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'publish-city') ) {
             die( 'Security check Failed' );
        }
        $countryid = get_option( 'jsjobs_countryid_for_city');
        $stateid = get_option( 'jsjobs_stateid_for_city');

        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('city')->publishUnpublish($ids, 1); //  for publish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('city');
        $url = admin_url("admin.php?page=jsjobs_city&jsjobslt=cities&countryid=" . $countryid . "&stateid=" . $stateid . '&_wpnonce=' . $nonce );
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function unpublish() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'unpublish-city') ) {
             die( 'Security check Failed' );
        }
        $countryid = get_option( 'jsjobs_countryid_for_city');
        $stateid = get_option( 'jsjobs_stateid_for_city');

        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('city')->publishUnpublish($ids, 0); //  for unpublish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('city');
        $url = admin_url("admin.php?page=jsjobs_city&jsjobslt=cities&countryid=" . $countryid . "&stateid=" . $stateid . '&_wpnonce=' . $nonce );
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function savecity() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-city') ) {
             die( 'Security check Failed' );
        }
        $countryid = get_option( 'jsjobs_countryid_for_city');
        $stateid = get_option( 'jsjobs_stateid_for_city');
        $nonce = wp_create_nonce('city');
        $url = admin_url("admin.php?page=jsjobs_city&jsjobslt=cities&countryid=" . $countryid . "&stateid=" . $stateid . '&_wpnonce=' . $nonce );

        $data = JSJOBSrequest::get('post');
        if ($data['stateid'])
            $stateid = $data['stateid'];
        $result = JSJOBSincluder::getJSModel('city')->storeCity($data, $countryid, $stateid);
        $msg = JSJOBSMessages::getMessage($result, 'city');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

}

$JSJOBSCityController = new JSJOBSCityController();
?>
