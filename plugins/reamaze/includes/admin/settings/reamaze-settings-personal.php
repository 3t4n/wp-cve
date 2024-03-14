<?php
/**
 * Reamaze Settings Personal
 *
 * @author      Reamaze
 * @category    Admin
 * @package     Reamaze/Admin
 * @version     2.3.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Reamaze_Settings_Personal' ) ) :

/**
 * Reamaze_Settings_Personal
 */
class Reamaze_Settings_Personal extends Reamaze_Settings_Page {
	/**
	 * Constructor
	 */
	public function __construct() {
	  $this->id = 'personal';
	  $this->label = __( 'Personal Settings', 'reamaze' );
		parent::__construct();
	}

	/**
   * Get settings array
   *
   * @return array
   */
  public function get_settings() {
    $settings = array(
      array( 'title' => __( 'Personal Settings', 'reamaze' ), 'type' => 'title', 'id' => 'personal-settings-header' ),

      array(
        'title'    => __( 'Reamaze Login', 'reamaze' ),
        'desc'     => __( 'Your Reamaze Login Email, for example login@example.com', 'reamaze' ),
        'id'       => 'reamaze_login_email',
        'type'     => 'email',
        'default'  => '',
        'user_setting' => true
      ),

      array(
        'title'    => __( 'Reamaze API Key', 'reamaze' ),
        'desc'     => __( "Your API Key is used to access Reamaze and perform certain actions (such as converting a comment to a conversation) on your behalf. You can generate an API Key in your Reamaze Admin -> Settings -> API Token.", 'reamaze' ),
        'id'       => 'reamaze_api_key',
        'type'     => 'password',
        'default'  => '',
				'raw'      => true,
        'user_setting' => true
      ),

      array( 'type' => 'sectionend', 'id' => 'widget-settings-header' )
    );

    return $settings;
  }
}

endif;

return new Reamaze_Settings_Personal();
