<?php
/**
 * Reamaze Settings Widget
 *
 * @author      Reamaze
 * @category    Admin
 * @package     Reamaze/Admin
 * @version     2.3.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Reamaze_Settings_Widget' ) ) :

/**
 * Reamaze_Settings_Widget
 */
class Reamaze_Settings_Widget extends Reamaze_Settings_Page {
	/**
	 * Constructor
	 */
	public function __construct() {
	  $this->id = 'widget';
	  $this->label = __( 'Widget', 'reamaze' );
		parent::__construct();
	}

	/**
   * Get settings array
   *
   * @return array
   */
  public function get_settings() {
    $settings = array(
      array(
        'title' => __( 'Widget Settings', 'reamaze' ),
        'type' => 'title',
        'id' => 'widget-settings-header'
      ),

      array(
        'title'    => __( 'Display', 'reamaze' ),
        'id'       => 'reamaze_widget_display',
        'type'     => 'select',
        'default'  => 'all',
        'options'  => array(
          'all'    => __( 'Display Widget on all pages', 'reamaze' ),
          'auth'   => __( 'Display Widget for logged in users only', 'reamaze' ),
          'none'   => __( "Don't display the Widget at all", 'reamaze' )
        )
      ),
    );

    $reamazeAccountId = get_option( 'reamaze_account_id' );

    if ( $reamazeAccountId ) {
      $url = esc_url( "https://" . $reamazeAccountId . ".reamaze.com/admin/settings/embeddables/chats" );
      $desc = sprintf( wp_kses( __( 'Click <a target="_blank" href="%s">here</a> to edit your widget\'s appearance in Re:amaze.', 'reamaze' ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ), $url );
      array_push( $settings, array(
        'desc' => __( $desc, 'reamaze' ),
        'type' => 'title'
      ));
    }

    array_push( $settings, array( 'type' => 'sectionend', 'id' => 'widget-settings-header' ));

    return $settings;
  }
}

endif;

return new Reamaze_Settings_Widget();
