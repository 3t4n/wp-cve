<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
  exit();

function tayori_delete_plugin() {
  global $wpdb;

  $table_name = $wpdb->prefix . "tayori";
  $wpdb->query( "DROP TABLE IF EXISTS $table_name" );

  delete_option( 'tayori_db_version' );
}

tayori_delete_plugin();
