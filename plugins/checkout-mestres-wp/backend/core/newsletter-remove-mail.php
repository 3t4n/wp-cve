<?php
include("../../../../../wp-load.php");
global $wp;
global $wpdb;
global $woocommerce;
$table_name = $wpdb->prefix . 'cwmp_newsletter_remove';
$update_send_newsletter = $wpdb->insert($table_name,array('email'=>$_GET['email'],'campanha'=>$_GET['campanha']));
echo "E-mail removido com sucesso!";