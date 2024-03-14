<?php

include_once(dirname(__FILE__).'/config.inc.php');

include_once(dirname(__FILE__).'/../lib/Perfit.php');

@session_start();

setlocale(LC_ALL, "es_ES");

$perfit = new PerfitSDK\Perfit();
$perfit->apiKey(get_option('api_key_perfit'));

