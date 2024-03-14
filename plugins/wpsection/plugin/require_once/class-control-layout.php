<?php

// This code is for custom controll 
add_action( 'elementor/controls/controls_registered', function() {
		
class Elementor_Layout_Control extends \Elementor\Base_Data_Control {

    public function get_type() {
        return 'elementor-layout-control';
    }

    protected function get_default_settings() {
        return [
            'label_block' => true,
            'rows' => 3,
            'layoutcontrol_options' => [],
		];
		print_r( 'layoutcontrol_options' ); exit( 'asdf' );
    }

    public function content_template() {
        $control_uid = $this->get_control_uid();
        ?>
  <div class="wps_control">
        <div class="elementor-control-field">
            <label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper">
                <#
                if ( data.options ) {
                    _.each( data.options, function( value, key ) {
                        var selected = '';
                        if(data.controlValue == key){
                            selected = 'selected';
                        }
                #>
                <div class="radio-image-item {{ selected }}">
                    <input id="{{ data.name }}-{{ key }}" type="radio" class="field-radio-image" value="{{ key }}" name="{{ data.name }}" data-setting="{{ data.name }}" {{ selected }} />
                    <label for="{{ data.name }}-{{ key }}">
                        <img src="{{ value.image }}" alt="{{ value.label }}">
                    </label>
                </div>
                <#
                    });
                }
                #>
            </div>
        </div>
    </div>				
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }

}

	\Elementor\Plugin::instance()->controls_manager->register_control('elementor-layout-control', new \Elementor_Layout_Control);

});
	
	




