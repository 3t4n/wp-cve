<?php
require($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

global $wpdb;
$wpdb->insert($wpdb->prefix .'pdb_paypal_doantion_block', array(
    'donner_name' => $_REQUEST['donner_name'],
    'donner_email' => $_REQUEST['donner_email'],
    'donner_phone' => $_REQUEST['donner_phone'],
));

echo $lastid = $wpdb->insert_id;

