<?php
if (!defined('ABSPATH')) die('-1');

class VCE_fmcAccount extends VCE_component {
    function __construct() {
        parent::__construct();

        add_action( 'init', array( $this, 'integrateWithVC' ) );
    }

    protected function setParams(){
        if (!is_null($this->vars)){
            extract($this->vars);
        }

        $fmc_params = array(
              array(
                'type' => 'checkbox_group_tag',
                'heading' => 'Sections to Show',
                'value' => '',
                'param_name' => 'shown_fields',
                'options' => $additional_fields,
                'script' => script_path('checkbox_options.js'),
                'admin_label' => true,
              )
        );
        
        return $fmc_params;
    }
}

new VCE_fmcAccount();