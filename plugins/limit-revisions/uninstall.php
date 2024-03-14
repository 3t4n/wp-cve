<?php 

// Check we are using uninstall.php
if( !defined( 'WP_UNINSTALL_PLUGIN' ) ):
  exit();
endif;

// Remove DB information
delete_option( 'cmr_lwr_settings' );