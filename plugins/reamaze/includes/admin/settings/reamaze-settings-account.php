<?php
/**
 * Reamaze Settings Account
 *
 * @author      Reamaze
 * @category    Admin
 * @package     Reamaze/Admin
 * @version     2.3.2
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( !class_exists( 'Reamaze_Settings_Account' ) ) :

/**
 * Reamaze_Settings_Account
 */
class Reamaze_Settings_Account extends Reamaze_Settings_Page {
	/**
	 * Constructor
	 */
	public function __construct() {
	  $this->id = 'account';
	  $this->label = __( 'Account', 'reamaze' );
		parent::__construct();
	}

	/**
   * Get settings array
   *
   * @return array
   */
  public function get_settings() {
    $url = apply_filters( 'reamaze_signup_link', 'https://www.reamaze.com/signup?referrer=wordpress' );

    $settings = array(
      array( 'title' => __( 'Account Settings', 'reamaze' ), 'type' => 'title', 'id' => 'account-settings-header' ),

      array(
        'title'    => __( 'Reamaze Account ID', 'reamaze' ),
        'desc'     => __( 'Your Reamaze Account ID. For example, if your Reamaze subdomain is <b>my-wp.reamaze.com</b>, your account ID is <b>my-wp</b>. <br /><a href="' . $url . '" target="_blank">Need an account? Create one here</a>.', 'reamaze' ),
        'id'       => 'reamaze_account_id',
        'type'     => 'text',
        'default'  => ''
      ),

      array(
        'title'    => __( 'Secret SSO Key', 'reamaze' ),
        'desc'     => __( "Your Reamaze account's secret SSO key (Reamaze Admin -> Settings -> Account IDs and Secret)", 'reamaze' ),
        'id'       => 'reamaze_account_sso_key',
        'type'     => 'password',
        'default'  => ''
      ),

      array( 'type' => 'sectionend', 'id' => 'account-settings-header' )
    );

    return $settings;
  }
}

endif;

return new Reamaze_Settings_Account();
