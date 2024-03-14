<?php

namespace Element_Ready\Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Radio_Choose extends \Elementor\Base_Data_Control {

	public function get_type() {
		return 'radioimage';
	}

	public function enqueue() {
		// styles
		wp_register_style( 'radio-image-control',  ELEMENT_READY_ROOT_CSS . 'imgclr.css', [], '1.0.0' );
		wp_register_script( 'radio-image-control',  ELEMENT_READY_ROOT_JS . 'imgclr.js' );
		wp_enqueue_style( 'radio-image-control' );
		// script	
		wp_enqueue_script( 'radio-image-control' );
       
	}

	public function content_template() {
		
		$control_uid = $this->get_control_uid( '{{name}}' );

		?>
			<div class="elementor-control-field">
				<label class="elementor-control-title">{{{ data.label }}}</label>
				<div class="elementor-control-input-wrapper element-ready-radio-img">
					<div class="elementor-image-choices">
						<# _.each( data.options, function( options, value ) { #>
						<div class="image-choose-label-block" 
						style="width:{{ options.width }}">
							<input id="<?php echo esc_attr($control_uid); ?>{{ options.title }}" type="radio" name="elementor-choose-{{ data.name }}-{{ data._cid }}" value="{{ value }}">
							<label class="elementor-image-choices-label" for="<?php echo esc_attr($control_uid); ?>{{ options.title }}" title="{{ options.title }}">
								<img class="imagesmall" src="{{ options.imagesmall }}" alt="{{ options.title }}" />
								<img class="imagelarge" src="{{ options.imagelarge }}" alt="{{ options.title }}" />
								<span class="elementor-screen-only">{{{ options.title }}}</span>
							</label>
						</div>
						<# } ); #>
					</div>
				</div>
			</div>

		<# if ( data.description ) { #>
	    	<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
	
	protected function get_default_settings() {
		return [
			'label_block' => true,
			'options' => []
		];
	}
}
