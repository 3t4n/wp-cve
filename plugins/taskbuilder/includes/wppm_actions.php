<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
if ( ! class_exists( 'WPPM_Actions' ) ) :
  
final class WPPM_Actions {
  // constructor
  public function __construct() {
      add_action( 'init', array( $this, 'wppm_load_actions') );
  }

  // Load actions
  function wppm_load_actions() {
    add_action( 'wppm_cron_daily', array($this,'wppm_check_cron_attachment') );
  }
  
  function wppm_check_cron_attachment(){
    include WPPM_ABSPATH.'includes/actions/wppm_check_cron_attachment.php';
  }

}
endif;

new WPPM_Actions();