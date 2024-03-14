<?php 
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

function darkluplite_darkmode_component( $atts, $content= null ) {
	$settings = shortcode_atts( array(
		'switch_style' 	     => '1',
		'switch_alignment' 	 => 'left',
	), $atts );
	
	ob_start();
   
	// Switch style 
	echo '<div class="darkluplite-wbp-switch-wrapper" style="text-align:'.esc_attr($settings['switch_alignment']).'">';
		echo \DarklupLite\Switch_Style::switchStyle( esc_html( $settings['switch_style'] ) );
	echo '</div>';

	$html = ob_get_clean();
	return $html;
  
}
