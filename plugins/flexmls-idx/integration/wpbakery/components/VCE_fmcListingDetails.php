<?php
if (!defined('ABSPATH')) die('-1');

class VCE_fmcListingDetails extends VCE_component {
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
                "type" => "text_field_tag",
                "heading" => $title,
                "param_name" => $param,
                "value" => $value,
                'admin_label' => true,
              ),
        );
        
        return $fmc_params;
    }
}

new VCE_fmcListingDetails();