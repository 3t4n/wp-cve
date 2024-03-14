<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSsystemerrorController {

    function __construct() {

        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'systemerrors');

        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_systemerrors':
                    JSJOBSincluder::getJSModel('systemerror')->getSystemErrors();
                    break;

                case 'admin_addsystemerror':
                    $id = JSJOBSrequest::getVar('jssupportticketid', 'get');
                    JSJOBSincluder::getJSModel('systemerror')->getsystemerrorForForm($id);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'systemerror');
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

}

$systemerrorController = new JSJOBSsystemerrorController();
?>
