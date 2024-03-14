<?php
//get ApiKey database
function returnGenerateApiKey() {
    global $wpdb;
    $prefix=$wpdb->prefix;
    $nameTable = $prefix.'config_apiKey_MiPaquete';
    $query = "SELECT * FROM {$nameTable} WHERE email_user_registred='" . get_option('mpq_email') ."'
    AND development_environment =" . get_option('mpq_enviroment');
    $resultDataGenerateApiKey = $wpdb->get_results($query);
    foreach ($resultDataGenerateApiKey as $value) {
        $readApiKey = $value->apikey_config;
    }
    return $readApiKey;
}
