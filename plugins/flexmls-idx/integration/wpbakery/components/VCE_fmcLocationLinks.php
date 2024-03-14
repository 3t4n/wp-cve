<?php
if (!defined('ABSPATH')) die('-1');

class VCE_fmcLocationLinks extends VCE_component {
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
                'type' => 'select_tag',
                'heading' => 'IDX Link',
                'value' => $api_links,
                'param_name' => 'link',
                'option_value_attr' => 'LinkId', 
                'option_display_attr' => 'Name',
                'default' => '',
                'description' => 'Saved Search IDX link these locations are built upon', 
                'admin_label' => true,             
              ),
              array(
                'type' => 'dropdown_tag',
                'heading' => 'Property Type',
                'value' => $this->modify_array($property_type),
                'param_name' => 'property_type'
            ),
            array(
                'type' => 'location_tag',
                'heading' => 'Location',
                'value' => '',
                'param_name' => 'locations',
                'field_slug' => $location_slug,
                'multiple' => true,
                'admin_label' => true,
            ),
            array(
                "type" => "dropdown",
                "heading" => 'Default view',
                "param_name" => "default_view",
                "value" => $this->modify_array($default_view),
              ),
              array(
                'type' => 'dropdown',
                'heading' => 'Send users to',
                'value' => $this->modify_array($destination),
                'param_name' => 'destination',
              ),
        );
        
        return $fmc_params;
    }
}

new VCE_fmcLocationLinks();