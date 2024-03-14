<?php
defined( 'EOS_CARDS_DIR' ) || exit; //exit if file not inclued by the plugin

require EOS_CARDS_DIR . '/admin/cards-options.php';
require EOS_CARDS_DIR . '/admin/cards-metaboxes.php';
if( defined( 'EOS_CARDS_PRO' ) && EOS_CARDS_PRO ){
  //Uncomment when EDD is ready
  // require( EOS_CARDS_DIR.'/admin/oc-license-handler.php' );
  // require( EOS_CARDS_DIR.'/admin/class-oc-edd-license-manager-client-pro.php' );
  require( EOS_CARDS_DIR.'/admin/class-eos-oc-license-manager-client-pro.php' );
}
//Loads the integration functions for page builders
if( function_exists( 'eosb_map' ) ){
  global $pagenow;
  if( ( $pagenow === 'post-new.php' || isset( $_GET['post'] ) && isset( $_GET['action'] ) && $_GET['action'] === 'edit' && is_admin() ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX && is_admin() ) || isset( $_GET['eosb_editable'] ) || isset( $_GET['eosb_action'] ) ){
    require EOS_CARDS_DIR . '/integration/cards-eosb-integration.php';
  }
}
if( class_exists( 'Vc_Manager' ) ){
  require EOS_CARDS_DIR . '/integration/cards-vc-integration.php';
}
