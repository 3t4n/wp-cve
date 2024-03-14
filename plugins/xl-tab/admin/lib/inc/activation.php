<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class XL_Tab_Activation { 

  function __construct() {

    register_activation_hook( XLTAB_ROOT_FILE__,  [ $this, 'init' ] );
  }

  function init(){
    $remote = XL_Tab_Library::$plugin_data["remote_site"];
    $end_point = XL_Tab_Library::$plugin_data["all_endpoint"];
    $library_data = json_decode(wp_remote_retrieve_body(wp_remote_get($remote.'wp-json/wp/v2/'.$end_point)), true);
    $library['tab_accordion'] = $library_data;
    update_option( 'xl_tab_library', $library); 
  }
}

new XL_Tab_Activation();





