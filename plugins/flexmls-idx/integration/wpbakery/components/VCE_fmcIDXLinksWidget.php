<?php
if (!defined('ABSPATH')) die('-1');

class VCE_fmcIDXLinksWidget extends VCE_component {
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
              "heading" => 'Title',
              "param_name" => 'title',
              "value" => '',
              'admin_label' => true,
            ),
            array(
                'type' => 'checkbox_group_tag',
                'heading' => 'Saved Search IDX Links to Display',
                'value' => '',
                'param_name' => 'links',
                'options' => $this->api_links_array($api_links),
                'script' => script_path('checkbox_options.js'),
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
                'value' => $this->modify_array($possible_destinations),
                'param_name' => 'destination',
              ),
        );
        
        return $fmc_params;
    }

    protected function api_links_array($links){
        $array = array();
        foreach ($links as $link) {
            $array[$link['LinkId']] = $link['Name'];
        }
        return $array;
    }
}

new VCE_fmcIDXLinksWidget();