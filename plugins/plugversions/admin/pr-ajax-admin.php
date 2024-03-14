<?php
/**
 * It includes the code for the Ajax activities.

 * @package Plugversions
 */

defined( 'PLUGIN_REVISIONS_PLUGIN_DIR' ) || exit; // Exit if not accessed from Plugversions.

add_action( 'wp_ajax_eos_plugin_reviews_restore_version','eos_plugin_reviews_restore_version' );
/**
 * Restore plugin version
 *
 * @since  0.0.1
 */ 
function eos_plugin_reviews_restore_version(){
  if( isset( $_POST['nonce'] ) && isset( $_POST['dir'] ) && isset( $_POST['parent_plugin'] ) && wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ),'plugin_reviews_restore_version' ) ){
    $key = eos_plugin_revision_key();
    if( $key ){
      $time = time();
      $dir = sanitize_text_field( $_POST['dir'] );
      $plugin = sanitize_text_field( $_POST['parent_plugin'] );
      $plugin_data = get_plugin_data( dirname( PLUGIN_REVISIONS_PLUGIN_DIR ) . '/' . $plugin );
      $version = $plugin_data['Version'];
      $plugin_version = dirname( PLUGIN_REVISIONS_PLUGIN_DIR ) . '/pr-' . $key . '-' . sanitize_option( 'upload_path',$version ) . '-ver-' . $time . dirname( $plugin );
      $r1 = rename( dirname( PLUGIN_REVISIONS_PLUGIN_DIR ) . '/' . dirname( $plugin ), $plugin_version );
      $r2 = rename( dirname( PLUGIN_REVISIONS_PLUGIN_DIR ) . '/' . $dir , dirname( PLUGIN_REVISIONS_PLUGIN_DIR ) . '/' . dirname( $plugin ) );
      $r3 = rename( $plugin_version,str_replace( $time,'',$plugin_version ) );
      do_action( 'activate_plugin',$plugin );
      do_action( "activate_{$plugin}" );
      do_action( 'activated_plugin', $plugin );
      echo (bool) ( $r1 && $r2 && $r3 );
    }
  }
  die();
  exit;
}
