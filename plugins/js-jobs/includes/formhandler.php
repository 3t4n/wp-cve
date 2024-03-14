<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSformhandler {

    function __construct() {
        add_action('init', array($this, 'checkFormRequest'));
        add_action('init', array($this, 'checkDeleteRequest'));
    }

    /*
     * Handle Form request
     */

    function checkFormRequest() {
        $formrequest = JSJOBSrequest::getVar('form_request', 'post');
        if ($formrequest == 'jsjobs') {
            //handle the request
            $modulename = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($modulename);
            $module = jsjobslib::jsjobs_str_replace('jsjobs_', '', $module);
            JSJOBSincluder::include_file($module);
            $class = 'JSJOBS' . $module . "Controller";
            $task = JSJOBSrequest::getVar('task');
            $obj = new $class;
            $obj->$task();
        }
    }

    /*
     * Handle Form request
     */

    function checkDeleteRequest() {
        $jsjobs_action = JSJOBSrequest::getVar('action', 'get');
        if ($jsjobs_action == 'jsjobtask') {
            //handle the request
            $modulename = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($modulename);
            $module = jsjobslib::jsjobs_str_replace('jsjobs_', '', $module);
            JSJOBSincluder::include_file($module);
            $class = 'JSJOBS' . $module . "Controller";
            $action = JSJOBSrequest::getVar('task');
            $obj = new $class;
            $obj->$action();
        }
    }

}

$formhandler = new JSJOBSformhandler();
?>
