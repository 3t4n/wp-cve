<?php
if (!defined('ABSPATH')) die('-1');

class VCE_fmcSearchResults extends VCE_component {
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


    protected function setParams(){
      if (!is_null($this->vars)){
                extract($this->vars);
        }

      if(isset($office_roster) && is_array($office_roster)){
        $agent = array_merge(array(array('Id' => '', 'Name' => '  - Select One -  ')), $office_roster);
      } else {
        $agent = [];
      }
      if(!is_array($api_property_type_options)){
        $api_property_type_options = [];
      }

      $api_property_type_options_use = array_merge(['' => 'All'], $api_property_type_options);

      if(isset($source) && !is_null($source) ){
                $source_options = array_reverse($source);
          }


      if(!empty($status)){
        $status_use = array(
          array(
            "type" => "select_tag",
            "heading" => 'Status',
            "param_name" => "status",
            'option_value_attr' => 'Value',
            'option_display_attr' => 'Name',
            "value" => $status,
            'dependency' => array(
              'element' => 'source',
              'value' => array('location'),
            ),
          )
        );
      } else {
        $status_use = [];
      }

        $fmc_params = array_merge(array(
            array(
              "type" => "text_field_tag",
              "heading" => 'Title',
              "param_name" => "title",
              "value" => '',
              "description" => $special_neighborhood_title_ability,
              'admin_label' => true,
            ),
            array(
              'type' => 'select_tag',
              'heading' => 'Saved Search',
              'value' => $api_links,
              'param_name' => 'link',
              'option_value_attr' => 'LinkId',
              'option_display_attr' => 'Name',
              'default' => '',
              'description' => 'flexmls Saved Search to apply',
            ),
            array(
              'type' => 'dropdown',
              'heading' => 'Filter by',
              'value' => $this->modify_array($source_options),
              'param_name' => 'source',
              'class' => 'flexmls_connect__listing_source',
              'description' => 'Which listings to display',
              //'std' => 'location'
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
              "type" => "dropdown_tag",
              "heading" => 'Property Type',
              "param_name" => "property_type",
              "value" => $this->modify_array($api_property_type_options_use),
              "id" => "vce_property_type"
            ),
            array(
              "type" => "dropdown_tag",
              "heading" => 'Property Sub Type',
              "param_name" => "property_sub_type",
              "isgroup" => true,
              "value" => $this->makeOptGroup($api_property_type_options, $api_property_sub_type_options),
              "id" => "vce_property_sub_type",
              "dependent_dd" => "vce_property_type",
              'script' => script_path('dependent_dropdowns.js'),
            ),
          ), $status_use, array(
            array(
                'type' => 'location_tag',
                'heading' => 'Location',
                'value' => '',
                'param_name' => 'location',
                'field_slug' => $portal_slug,
                'dependency' => array(
                  'element' => 'source',
                  'value' => array('location'),
                ),
            ),
            array(
              "type" => "dropdown",
              "heading" => 'Display',
              "param_name" => "display",
              "value" => $this->modify_array($display_options),
            ),
            array(
              "type" => "dropdown",
              "heading" => 'Number of Days',
              "param_name" => "days",
              "value" => $this->modify_array($display_day_options),
              'dependency' => array(
                'element' => 'display',
                'value_not_equal_to' => array('all'),
              ),
            ),
            array(
              "type" => "dropdown",
              "heading" => 'Default view',
              "param_name" => "default_view",
              "value" => array(
                  array(
                      'value' => 'list',
                      'label' => 'List view'
                  ),
                  array(
                    'value' => 'map',
                    'label' => 'Map view'
                ),
              ),
            ),
            array(
              "type" => "dropdown",
              "heading" => 'Sort by',
              "param_name" => "sort",
              "value" => $this->modify_array($sort_options),
            ),
            array(
              "type" => "dropdown",
              "heading" => 'Listings per page',
              "param_name" => "listings_per_page",
              "value" => $this->modify_array($listings_per_page_options),
            )
          )
        );

        return $fmc_params;
    }
}

new VCE_fmcSearchResults();
