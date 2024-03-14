<?php

/***** ADD OPTIONS *****/

function fca_eoi_add_powerup_2( $array ) {

	$array[] = array( 'custom_css', 'Custom CSS', 'fca_eoi_checkbox_callback', 'fca_eoi_powerup_settings_section', __( 'Add custom CSS inside the form editor.' ,'easy-opt-ins') );	
	return $array;
	
}
add_filter( 'fca_eoi_setting_filter', 'fca_eoi_add_powerup_2' );

$options = get_option( 'fca_eoi_settings' );

if ( !empty ( $options['custom_css'] ) ) {
	new EoiCustomCssBox();
}

class EoiCustomCssBox {

	public function __construct() {
		add_action( 'fca_eoi_powerups',           array( $this, 'show_custom_css_field' ) ); 
		add_action( 'admin_enqueue_scripts',      array( $this, 'enqueue_admin_scripts' ) );
		add_filter( 'fca_eoi_alter_form',         array( $this, 'append_css_to_form' ) , 10 , 2 );
	}

	public function init() {
	}

	public function append_css_to_form( $content , $fca_eoi_meta ) {

		if( $css = K::get_var( 'custom_css', $fca_eoi_meta ) ) {
			$content .= "<style>$css</style>";
		}

		return $content;
	}

	/*
	 * Add fieldset custom css box
	 */
	public function show_custom_css_field( $fca_eoi_meta ) {
		echo '<div class="eoi-custom-css-form" style="width:40.5em;">';
		K::textarea( 'fca_eoi[custom_css]'
			, array(
				'class' => 'fca_eoi_custom_css_textbox',
				'placeholder' => __( 'Enter your custom CSS here...' ),
			)
			, array(
				'value' => K::get_var( 'custom_css', $fca_eoi_meta, '' ),
				'format' => '<label> Custom CSS</label><br />:textarea',
			)
		);
		echo '</div>';
	}

	public function enqueue_admin_scripts() {

		wp_enqueue_code_editor( array( 'type' => 'text/css', 'codemirror' => array( 'autoRefresh' => false, 'lineWrapping' => true ) ) );

	}


}
