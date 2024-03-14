<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBScontroller {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        $module = JSJOBSrequest::getVar('jsjobsme', null, 'jsjobs');
        JSJOBSincluder::include_file($module);
    }

}

$JSJOBScontroller = new JSJOBScontroller();
?>
