<?php
namespace Elementor;

class CPM_Media_Button extends Control_Button {

	public function get_type() {
		return 'cpmmediabutton';}
	public function enqueue() {
		wp_enqueue_media();
		wp_enqueue_script( 'cpmp-elementor-control-script', plugin_dir_url( __FILE__ ) . 'elementor.js', array( 'jquery' ), CPMP_VERSION );
	}

	public function content_template() {        ?>
		<div class="elementor-control-field">
			<label class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<button type="button" class="elementor-button elementor-button-{{{ data.button_type }}}" onclick="{{{ data.event }}}">{{{ data.text }}}</button>
			</div>
		</div>
		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
} // End CPM_Media_Button

class CPM_Skin_Select extends Control_Select {

	public function get_type() {
		return 'cpmskinselect';}
	public function enqueue() {
		 wp_enqueue_script( 'cpmp-elementor-control-script', plugin_dir_url( __FILE__ ) . 'elementor.js', array( 'jquery' ), CPMP_VERSION );
	}

	public function content_template() {        ?>
		<div class="elementor-control-field">
			<# if ( data.label ) {#>
				<label for="{{ data.name }}" class="elementor-control-title">{{{ data.label }}}</label>
			<# } #>
			<div class="elementor-control-input-wrapper">
				<select id="{{ data.name }}" onchange="{{{ data.event }}}">
				<#
					var printOptions = function( options ) {
						_.each( options, function( option_title, option_value ) { #>
								<option value="{{ option_value }}">{{{ option_title }}}</option>
						<# } );
					};

					if ( data.groups ) {
						for ( var groupIndex in data.groups ) {
							var groupArgs = data.groups[ groupIndex ];
								if ( groupArgs.options ) { #>
									<optgroup label="{{ groupArgs.label }}">
										<# printOptions( groupArgs.options ) #>
									</optgroup>
								<# } else if ( _.isString( groupArgs ) ) { #>
									<option value="{{ groupIndex }}">{{{ groupArgs }}}</option>
								<# }
						}
					} else {
						printOptions( data.options );
					}
				#>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
} // End CPM_Skin_Select

Plugin::instance()->controls_manager->register( new CPM_Media_Button() );
Plugin::instance()->controls_manager->register( new CPM_Skin_Select() );
