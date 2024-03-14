<?php

/*
 * @author     Saeed Sattar Beglou <saeed.sb@gmail.com>
 * @author     HamidReza Gharahzadeh <hamidprime@gmail.com>
 * @author     Sepehr Najafi <sepehrn249@gmail.com>
 */

/* Check security token */
if (!(defined('STDIN') || (strtolower(php_sapi_name()) == 'cli' && (!isset($_SERVER['REMOTE_ADDR'])
            || empty($_SERVER['REMOTE_ADDR']))))) {
    if (substr(wp_hash(AUTH_KEY . 'ssbhesabfa/webhook'), 0, 10) != $_GET['token']) {
        die('Bad token');
    }
}

$post = file_get_contents('php://input');
$result = json_decode($post);

if (!is_object($result)) {
    die('Invalid request.');
}

if ($result->Password != get_option('ssbhesabfa_webhook_password')) {
    die('Invalid password.');
}

include(dirname(__FILE__) . '/class-ssbhesabfa-webhook.php');
HesabfaLogService::writeLogStr("Hesabfa Webhook Called");
new Ssbhesabfa_Webhook();
