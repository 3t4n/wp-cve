<?php
if( !defined( 'WP_UNINSTALL_PLUGIN' ) ){
    die;
}

if( !defined( 'PLUGIN_REVISIONS_PLUGIN_DIR' ) ){
    define( 'PLUGIN_REVISIONS_PLUGIN_DIR',untrailingslashit( dirname( __FILE__ ) ) );
}

if( ! function_exists( 'eos_plugin_revisions_remove_all_versions' ) ){
    require_once  PLUGIN_REVISIONS_PLUGIN_DIR.'/plugversions.php';
}

if( function_exists( 'eos_plugin_revisions_remove_all_versions' ) ){
    eos_plugin_revisions_remove_all_versions();
}

delete_site_option( 'plugin_revisions' );