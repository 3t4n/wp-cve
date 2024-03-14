<?php
// create and insert info apikey in mysql
function createTableMipaquete() {
    global $wpdb;
    $prefix=$wpdb->prefix;
    $nameTable = $prefix.'config_apiKey_MiPaquete';
    $query = "SELECT * FROM {$nameTable} WHERE email_user_registred='" . get_option('mpq_email') ."'
    AND development_environment =" . get_option('mpq_enviroment');
    $resultRead = $wpdb->get_results($query);
    if($resultRead == null) {
        $urlGenerateApiKey = getUrlApi() . 'generateapikey';
        $dataGenerateApiKey = array("email" => get_option('mpq_email'), "password" => get_option('mpq_password'));
        $dataStringGenerateApiKey = json_encode($dataGenerateApiKey);
        $chGenerateApiKey = curl_init($urlGenerateApiKey);
        curl_setopt($chGenerateApiKey, CURLOPT_POSTFIELDS, $dataStringGenerateApiKey);
        curl_setopt($chGenerateApiKey, CURLOPT_HTTPHEADER, array('Content-Type:application/json',
            'session-tracker:a0c96ea6-b22d-4fb7-a278-850678d5429c',
        ));
        curl_setopt($chGenerateApiKey, CURLOPT_RETURNTRANSFER, true);
        $resultGenerateApiKey = curl_exec($chGenerateApiKey);
        $resultDataGenerateApiKey = json_decode($resultGenerateApiKey, true);
        $sql = "CREATE TABLE IF NOT EXISTS $nameTable(
            id bigint(20) NOT NULL AUTO_INCREMENT,
            date_creation datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            apikey_config text,
            email_user_registred varchar(150),
            development_environment int(1),
            PRIMARY KEY  (id)
        );";
        require_once(ABSPATH.'wp-admin/includes/upgrade.php');
        $wpdb->query($sql);
        $dataInsertApiKey = array("apikey_config" => $resultDataGenerateApiKey['APIKey'],
        "email_user_registred" => get_option('mpq_email'),
        "development_environment" => get_option('mpq_enviroment'));
        if (count($resultRead) <= 0) {
            $resultInsert = $wpdb->insert($nameTable,$dataInsertApiKey);
        }
    }
}
