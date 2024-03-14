<?php

namespace XproElementorAddons\Control;

use Elementor\Base_Data_Control;

class Xpro_Elementor_Image_Selector extends Base_Data_Control {



	const TYPE = 'xpro-image-selector';

	/**
	 * Set control type.
	 */
	public function get_type() {
		return self::TYPE;
	}

	/**
	 * control field markup
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid( '{{ value }}' );
		?>
		<div class="elementor-control-field">
			<label class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-image-selector-wrapper">
				<# _.each( data.options, function( options, value ) { #>
				<input id="<?php echo esc_attr( $control_uid ); ?>" type="radio" name="elementor-image-selector-{{ data.name }}-{{ data._cid }}" value="{{ value }}" data-setting="{{ data.name }}">
				<label class="elementor-image-selector-label tooltip-target" for="<?php echo esc_attr( $control_uid ); ?>" data-tooltip="{{ options.title }}" title="{{ options.title }}">
					<img src="{{ options.url }}" alt="{{ options.title }}">
					<span class="elementor-screen-only">{{{ options.title }}}</span>
				</label>
				<# } ); #>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}

	/**
	 * Set default settings
	 */
	protected function get_default_settings() {
		return array(
			'label_block' => true,
			'toggle'      => true,
			'options'     => array()
		);
	}
}
