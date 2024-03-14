<?php
include("../../../../../wp-load.php");
global $wp;
global $wpdb;
global $woocommerce;
$table_name = $wpdb->prefix . 'cwmp_newsletter_send';
$update_send_newsletter = $wpdb->update($table_name, array(
	'open' => '1'
),array('email'=>$_GET['email'],'campanha'=>$_GET['campanha']));