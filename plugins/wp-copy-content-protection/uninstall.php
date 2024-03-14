<?php

 // If uninstall is not called from WordPress then exit
  if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
  }
  else {
    delete_option('wccp_copy_protection_options');
  }
  
?>