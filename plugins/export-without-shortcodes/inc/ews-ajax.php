<?php
defined( 'EOS_EWS_PLUGIN_DIR' ) || exit; // Exit not accessed by this plugin

add_action( 'wp_ajax_eos_ews_save_options','eos_ews_save_options' );
//Save plugin options
function eos_ews_save_options(){
  if( !isset( $_POST['nonce'] ) || !isset( $_POST['keep_list'] ) || !isset( $_POST['global'] ) || !wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ),'eos_ews_export' ) ){
    die();
    exit;
  }
  $keep_list = array_map( 'eos_ews_sanitize_shortcode_name',explode( PHP_EOL,$_POST['keep_list'] ) );
  $opts = eos_ews_get_option( 'eos_ews_opts' );
  $opts['keep'] = $keep_list;
  $opts['global'] = 'true' === $_POST['global'] ? 'convert' : 'keep';
  eos_ews_update_option( 'eos_ews_opts',$opts );
}

//Sanitize shortcode name
function eos_ews_sanitize_shortcode_name( $name ){
  $name = str_replace( array( '[',']','/' ),'',$name );
  return sanitize_title( $name );
}
