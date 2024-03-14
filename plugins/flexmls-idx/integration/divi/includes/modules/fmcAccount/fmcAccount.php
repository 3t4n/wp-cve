<?php

class FMCD_fmcAccount extends FMCD_module {

    private $additional_fields;

    public function get_fields() {
       extract($this->module_info['vars']);
       $this->additional_fields = $additional_fields;

       return array(
          'shown_fields' => array(
                'label'           => esc_html__( 'Sections to Show', 'fmcd-divi' ),
                'type'            => 'multiple_checkboxes',
                'option_category' => 'basic_option',
                'options'         => $additional_fields,  
                'description'     => esc_html__( '', 'fmcd-divi' ),
                'toggle_slug'     => 'flexmls_basic',
          ),
       );
    }

    public function convert_props(){
        $props = $this->props;
        $props['shown_fields'] = $this->parce_checkbox_group($this->additional_fields, $props['shown_fields']);
        return $props;
    }
}

new FMCD_fmcAccount();