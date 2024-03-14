<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class majesticsupportController {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        $module = MJTC_request::MJTC_getVar('mjsmod', null, 'majesticsupport');
        MJTC_includer::MJTC_include_file($module);
    }

}

$majesticsupportController = new majesticsupportController();
?>
