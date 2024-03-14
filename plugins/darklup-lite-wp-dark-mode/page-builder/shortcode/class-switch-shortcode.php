<?php
namespace DarklupLite;
 /**
  * 
  * @package    DarklupLite - WP Dark Mode
  * @version    1.0.0
  * @author     
  * @Websites: 
  *
  */
if( ! defined( 'ABSPATH' ) ) {
    die( DARKLUPLITE_ALERT_MSG );
}

class SwitchShortcode{


	function __construct() {

	 add_shortcode( 'darkluplite_darkmode_switch', [ __CLASS__, 'darkModeSwitch' ] );

	}

  // [bartag foo="foo-value"]
  public static function darkModeSwitch( $atts ) {
    $attr = shortcode_atts( array(
      'style' => 1
    ), $atts );

    ob_start();

    echo \DarklupLite\Switch_Style::switchStyle( $attr['style'] );

    return ob_get_clean();

  }
  

}

new SwitchShortcode();