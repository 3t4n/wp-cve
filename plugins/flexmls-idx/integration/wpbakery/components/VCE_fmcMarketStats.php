<?php 
if (!defined('ABSPATH')) die('-1');

class VCE_fmcMarketStats extends VCE_component {
  protected $stat_types;

    function __construct() {
        parent::__construct();

        add_action( 'init', array( $this, 'integrateWithVC' ) );
        //add_action('vc_edit_form_fields_after_render', array($this, 'initLocationSearch'));
        
    }

    protected function setParams(){
        if (!is_null($this->vars)){
                extract($this->vars);
        }

        $this->stat_types = $stat_types;

        $fmc_params = array(
            array(
              "type" => "text_field_tag",
              "heading" => 'Title',
              "param_name" => "title",
              "value" => $title,
              "description" => $title_description,
              'admin_label' => true,
            ),
            array(
              "type" => "text_field_tag",
              "heading" => 'Width',
              "param_name" => "width",
              "value" => $width,
              'class' => 'width100',
              'points' => 'px',
            ),
            array(
              "type" => "text_field_tag",
              "heading" => 'Height',
              "param_name" => "height",
              "value" => $height,
              'class' => 'width100',
              'points' => 'px',
            ),
            array(
              'type' => 'dropdown_tag',
              'heading' => 'Type',
              'value' => $this->modify_array($type_options),
              'param_name' => 'type',
              'class' => 'flexmls_connect__stat_type',
              'description' => 'Which type of chart to display',
              'admin_label' => true,
            ),
            array(
              'type' => 'dropdown_tag',
              'heading' => 'Display',
              'value' => '',
              'param_name' => 'display',
              'display' => 'What statistics to display',
              'multiple' => 'true',
              'class' => 'flexmls_connect__stat_display',
              'uniqid' => $this->dataId,
              'script' => script_path('display_options.js'),
            ),
            array(
              'type' => 'dropdown',
              'heading' => 'Property Type',
              'value' => $this->modify_array($property_type_options),
              'param_name' => 'property_type',
              'admin_label' => true,
            ),
            array(
              'type' => 'location_tag',
              'heading' => 'Location',
              'value' => '',
              'param_name' => 'location',
              'field_slug' => $location_slug,
            ),
            
        );
        return $fmc_params;
    }

    protected function set_display_options($type){
      $display_options = array();

      if (is_array($this->stat_types) && array_key_exists($type, $this->stat_types)) {
        $these_display_options = $this->stat_types[$type];
      }
      else {
        $these_display_options = array();
      }
      foreach ($these_display_options as $opt) {
        $display_options[$opt['value']] = $opt['label'];
      }

      return $display_options;
    }
}

new VCE_fmcMarketStats();