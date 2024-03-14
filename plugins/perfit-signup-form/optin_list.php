<?php

include(dirname(__FILE__) . '/includes/loader.php');

if (!get_option('api_key_perfit')) {
    include(dirname(__FILE__) . '/tpl/login.php');
    die();
}
// Obtengo los optins
$optins = $perfit->optins->params(array('fields' => 'subscriptions'))->limit(1000)->get();

if ($optins->error->type == 'UNAUTHORIZED') {
    delete_option("api_key_perfit");
    Header('Location: ' . $_SERVER['PHP_SELF']);
    die();
}

include(dirname(__FILE__) . '/tpl/list.php');

?>