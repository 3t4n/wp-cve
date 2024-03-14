<?php
namespace Thim_EL_Kit\Elementor\Controls;

/**
 * Class Select2
 *
 * @package Thim_EL_Kit\Elementor\Controls
 *
 * It diffirent from Elementor Select2 control because it can sortable value.
 */
class Select2 extends \Elementor\Base_Data_Control {

	public function get_type() {
		return Controls_Manager::SELECT2;
	}

	public function enqueue() {
		wp_enqueue_script( 'thim-ekit-select2-control', THIM_EKIT_PLUGIN_URL . 'inc/elementor/controls/assets/js/select2.js', array( 'jquery', 'jquery-ui-sortable' ), '', true );
	}

	protected function get_default_settings() {
		return array(
			'options'        => array(),
			'multiple'       => false,
			// Select2 library options
			'select2options' => array(),
			// the lockedOptions array can be passed option keys. The passed option keys will be non-deletable.
			'lockedOptions'  => array(),
			// sortable values
			'sortable'       => false,
		);
	}

	public function content_template() {
		?>
		<div class="elementor-control-field">
			<# if ( data.label ) {#>
				<label for="<?php $this->print_control_uid(); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<# } #>
			<div class="elementor-control-input-wrapper elementor-control-unit-5">
				<# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
				<#
				// sort the options by the order of the control value
				if ( data.sortable && data.multiple ) {
					var options = data.options;
					var values = data.controlValue;
					if ( typeof values == 'string' ) {
						values = [values];
					}
					if ( null !== values ) {
						values = _.values( values );
					}
					var sortedOptions = {};
					_.each( values, function( value ) {
						if ( options[value] ) {
							sortedOptions[value] = options[value];
						}
					} );
					data.options = _.extend( sortedOptions, options );
				}
				#>
				<select id="<?php $this->print_control_uid(); ?>" class="elementor-select2 thim-el-kit-select2" type="select2" {{ multiple }} data-setting="{{ data.name }}">
					<# _.each( data.options, function( option_title, option_value ) {
						var value = data.controlValue;
						if ( typeof value == 'string' ) {
							var selected = ( option_value === value ) ? 'selected' : '';
						} else if ( null !== value ) {
							var value = _.values( value );
							var selected = ( -1 !== value.indexOf( option_value ) ) ? 'selected' : '';
						}
						#>
					<option {{ selected }} value="{{ option_value }}">{{{ option_title }}}</option>
					<# } ); #>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}
