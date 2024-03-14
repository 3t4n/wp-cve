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

add_action( 'vc_before_init', 'darkluplite_darkmode_switch' );
function darkluplite_darkmode_switch() {

	// vc_map check
	if( function_exists( 'vc_map' ) ) {
		vc_map( array(
		  "name" => esc_html__( "Dark Mode Switch", "darklup-lite" ),
		  "base" => "vc_darkluplite_darkmode_switch",
		  "class" => "",
		  "icon" => "",
		  "category" => esc_html__( "Content", "darklup-lite"),
		  "params" => array(
			
			array(
				"type" => "imageswitch",
				"heading" => esc_html__( "Select Switch Style", "darklup-lite" ),
				"param_name" => "switch_style",
				"value" => '1', //Default Red color
				"description" => esc_html__( "Set section bottom padding", "darklup-lite" ),
				'group' => esc_html__( 'Dark Mode Switch Settings', 'darklup-lite' ),
				'options' => \DarklupLite\Helper::switchDemoImage()
			),
			array(
				"type" => "dropdown",
				"heading" => esc_html__( "Switch Alignment", "darklup-lite" ),
				"param_name" => "switch_alignment",
				"value" => 'left', //Default value
				'group' => esc_html__( 'Dark Mode Switch Settings', 'darklup-lite' ),
				'value' => array(
                    esc_html__( 'Left', 'darklup-lite' ) 	=> 'left',
                    esc_html__( 'Center', 'darklup-lite' ) 	=> 'center',
                    esc_html__( 'Right', 'darklup-lite' ) 	=> 'right',
                )
			),


		  )
		) );
	} // end vc_map Check


}