<?php

add_action( 'customize_register', 'icycp_customize_register' );

function icycp_customize_register($wp_customize) {

 
/**
 * Customize Base control class. 
 *
 * @package Consultup
 *
 * @see     WP_Customize_Control
 * @access  public 
 */

/**
 * Class Consultup_Customize_Base_Control
 */
class Consultup_Customize_Base_Control extends WP_Customize_Control {

	/**
	 * Enqueue scripts all controls.
	 */
	public function enqueue() {

		
		// Scripts for nesting panel/section.
		wp_enqueue_script( 'icycp-extend-customizer', plugin_dir_url(__FILE__) . 'assets/js/extend-customizer.js', array( 'jquery' ), false, true );

		wp_enqueue_script( 'icycp-customizer-script', plugin_dir_url(__FILE__) .'assets/js/customizer-section.js', array("jquery"),'', true  );	

		wp_enqueue_style('icycp-extend-customizer', plugin_dir_url(__FILE__) . 'assets/css/customizer.css', false, '1.0.0');

		wp_enqueue_style( 'icycp-controls', plugin_dir_url(__FILE__) . '/inc/consultup/control/css/controls.css' );

	}


	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @see    WP_Customize_Control::to_json()
	 * @access public
	 * @return void
	 */
	public function to_json() {

		parent::to_json();

		$this->json['default'] = $this->setting->default;
		if ( isset( $this->default ) ) {
			$this->json['default'] = $this->default;
		}

		$this->json['id']      = $this->id;
		$this->json['value']   = $this->value();
		$this->json['choices'] = $this->choices;
		$this->json['link']    = $this->get_link();
		$this->json['l10n']    = $this->l10n();

		$this->json['inputAttrs'] = '';
		foreach ( $this->input_attrs as $attr => $value ) {
			$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
		}

	}

	/**
	 * Render content is still called, so be sure to override it with an empty function in your subclass as well.
	 */
	protected function render_content() {
	}

	/**
	 * Renders the Underscore template for this control.
	 *
	 * @see    WP_Customize_Control::print_template()
	 * @access protected
	 * @return void
	 */
	protected function content_template() {
	}

	/**
	 * Returns an array of translation strings.
	 *
	 * @access protected
	 * @return array
	 */
	protected function l10n() {
		return array();
	}

}
}