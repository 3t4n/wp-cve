<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSactivitylogController {

    function __construct() {

        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'activitylogs');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_activitylogs':
                    JSJOBSincluder::getJSModel('activitylog')->getAllActivities();
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'activitylog');
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

$JSJOBSactivitylogController = new JSJOBSactivitylogController();
?>
