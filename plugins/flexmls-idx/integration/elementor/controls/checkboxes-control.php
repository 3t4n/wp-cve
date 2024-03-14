<?php 
class Checkboxes extends \Elementor\Base_Data_Control {
    public function get_type() {
        return 'checkboxes_control';
    }

    public function enqueue() {
		// Styles
		wp_register_style( 'checkboxes', plugins_url('../styles/checkboxes-control.css', __FILE__), [], '1.0.0' );
        wp_enqueue_style( 'checkboxes' );
        
        // Scripts
		wp_register_script( 'checkboxes-control', plugins_url('../scripts/checkboxes-control.js', __FILE__), [ 'jquery' ], '1.0.0', true );
		wp_enqueue_script( 'checkboxes-control' );

    }

    public function content_template() {
        $control_uid = $this->get_control_uid('checkboxes');
        ?>       

        <div class="flexmls_connect__checkboxes_wrapper">
            <label class="flexmls_connect__checkboxes_label">{{data.label}}</label>
            <# 
            var cValue = data.controlValue,
                ids = cValue.split(','),
                checked = '';

            if(data.options){
                _.each(data.options, function(val, key){
                    if(ids.includes(key)){
                        checked = 'checked';
                    }
            #>
            <div>
                <input fmc-field="{{key}}" class="type_cb" fmc-type='checkbox' type='checkbox' name="{{fmcElementor.get_field_name(data.name)}}_{{key}}" id="{{fmcElementor.get_field_id(data.name)}}_{{key}}" {{checked}}/>
                <label for="{{fmcElementor.get_field_id(data.name)}}_{{key}}">{{val}}</label>
            </div>
            <#        
                })
            }
            #>
            <input fmc-field="{{data.name}}" fmc-type='text' type='text' data-setting="{{ data.name }}" class='flexmls_connect__checkboxes_fields'/>
        </div>

       <?php
    }

    protected function get_default_settings() {
		return [
            'options' => [],
		];
	}
}