<?php

class MemberSpace_Verify_Requirements {

  public $failures = array();

  /**
   * Check if the plugin's requirements are met
   *
   * @since 2.0.0
   *
   * @return boolean True if requirements met, False if not.
   */

  public function verify() {
    $this->verify_requirements();

    // Bail early if plugin meets requirements.
    if ( !count( $this->failures ) ) {
      return true;
    }

    // Add a dashboard notice.
    add_action( 'all_admin_notices', array( $this, 'print_failure_notice' ) );

    // Didn't meet the requirements.
    return false;
  }

  public function print_failure_notice() {
    include_once( plugin_dir_path( __DIR__ ) . 'admin/partials/requirements-not-met-notification-bar.php' );
  }

  /**
   * Check that all plugin requirements are met.
   *
   * @since 2.0.0
   *
   * @return boolean True if requirements are met.
   */
  public function verify_requirements() {
    $this->failures = array();

    if ( version_compare( phpversion(), MEMBERSPACE_PLUGIN_MIN_PHP_VERSION, '<' ) ) {
      array_push($this->failures, _x('PHP version should be at least ', 'plugin activation error', 'memberspace') . MEMBERSPACE_PLUGIN_MIN_PHP_VERSION);
    }
  }
}
