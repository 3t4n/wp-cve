<?php 
if (!defined('ABSPATH')) die('-1');

class VCE_fmcPhotos extends VCE_component {
    function __construct() {
        parent::__construct();

        add_action( 'init', array( $this, 'integrateWithVC' ) );
    }

    protected function makeOptGroup($property_type, $property_sub_type){
      $arr = [];
      foreach ($property_type as $property_code => $v) {
          $arr[$property_code] = array();
          $arr[$property_code][''] = 'All Sub Types';
          if (is_array($property_sub_type)){
            foreach ($property_sub_type as $sub_type){
              if(in_array($property_code, $sub_type['AppliesTo']) and $sub_type['Name'] != "Select One" ){
                $arr[$property_code][$sub_type["Value"]] = $sub_type["Name"];
              }
            }
          }
      }

      return $arr;
    }

    protected $additional_fields_selected = '';

    protected function setParams(){
      if (!is_null($this->vars)){
          extract($this->vars);
      }

        if(isset($agent)){
          $agent = array_merge(array(array('Id' => '', 'Name' => '  - Select One -  ')), $agent);
        } else {
          $agent = [];
        }

        $send_to = array(
            'photo' => 'Large Photo View',
            'detail' => 'Listing Detail'
        );

        if (!is_null($source)){
            $source_options = array_reverse($source);
        }

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
              'type' => 'select_tag',
              'heading' => 'IDX Link',
              'value' => $idx_links,
              'param_name' => 'link',
              'option_value_attr' => 'LinkId', 
              'option_display_attr' => 'Name',
              'default' => '',
              'description' => 'Link used when search is executed', 
              'admin_label' => true,             
            ),
            array(
              "type" => "dropdown",
              "heading" => 'Horizontal',
              "param_name" => "horizontal",
              "value" => $horizontal,
              'edit_field_class' => 'vc_col-sm-6',
              'description' => 'Horizontal × Vertical',
            ),
            array(
                "type" => "dropdown",
                "heading" => 'Vertical',
                "param_name" => "vertical",
                "value" => $vertical,                
                'edit_field_class' => 'vc_col-sm-6',
            ),
            array(
                "type" => "dropdown",
                "heading" => 'Size of Slideshow',
                "param_name" => "image_size",
                "value" => $this->modify_array($image_size),
            ),
            array(
                "type" => "dropdown",
                "heading" => 'Slideshow',
                "param_name" => "auto_rotate",
                "value" => $this->modify_array($auto_rotate),
            ),
            array(
                "type" => "dropdown",
                "heading" => 'Filter by',
                "param_name" => "source",
                "value" => $this->modify_array($source_options),
                'description' => 'Which listings to display'
            ),            
            array(
              "type" => "select_tag",
              "heading" => 'Agent',
              "param_name" => "agent",
              'option_value_attr' => 'Id', 
              'option_display_attr' => 'Name',
              "value" => $agent,
              'dependency' => array(
                'element' => 'source',
                'value' => array('agent'),
              ),
            ),
            array(
              'type' => 'dropdown_tag',
              'heading' => 'Property Type',
              'value' => $this->modify_array($property_type),
              'param_name' => 'property_type',
              "id" => "vce_photo_property_type"
              /* 'dependency' => array(
                'element' => 'source',
                'value' => array('location'),
              ) */
          ),
            array(
              "type" => "dropdown_tag",
              "heading" => 'Property Sub Type',
              "param_name" => "property_sub_type",
              "isgroup" => true,
              "value" => $this->makeOptGroup($property_type, $property_sub_type ),
              "id" => "vce_photo_property_sub_type",
              "dependent_dd" => "vce_photo_property_type",
              'script' => script_path('dependent_dropdowns.js'),
            ),
            array(
                'type' => 'location_tag',
                'heading' => 'Location',
                'value' => '',
                'param_name' => 'location',
                'field_slug' => $location_slug,
                'admin_label' => true,
                'dependency' => array(
                  'element' => 'source',
                  'value' => array('location'),
                ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => 'Display',
                'param_name' => 'display',
                'element' => 'display',
                'value' => $this->modify_array($display),
              ),
              array(
                'type' => 'dropdown',
                'heading' => 'Number of Days',
                'value' => $this->modify_array($days),
                'param_name' => 'days',
                'description' => 'The number of days in the past for display: new listings, open houses, etc.',
                'dependency' => array(
                  'element' => 'display',
                  'value_not_equal_to' => array('all'),
                ),                
              ),
              array(
                'type' => 'dropdown',
                'heading' => 'Sort by',
                'value' => $this->modify_array($sort),
                'param_name' => 'sort',
              ),
              array(
                'type' => 'checkbox_group_tag',
                'heading' => 'Additional Fields to Show',
                'value' => '',
                'param_name' => 'additional_fields',
                'options' => $additional_fields,
                'script' => script_path('checkbox_options.js'),
              ),
              array(
                'type' => 'dropdown',
                'heading' => 'Send users to',
                'value' => $this->modify_array($destination),
                'param_name' => 'destination'
              ),
              array(
                'type' => 'dropdown',
                'heading' => 'When Slideshow Photo Is Clicked Send Users To',
                'value' => $this->modify_array($send_to),
                'param_name' => 'send_to'
              ),
              /* array(
                'type' => 'callback',
                'param_name' => 'fmc_none_param',
                'function' => 'updateCarousel'
              ), */
              //setScripts('fmcPhotos_actions.js')
        );
        
        return $fmc_params;
    }

    protected function set_additional_fields($additional_field_options){
        
    }
}

new VCE_fmcPhotos();