<?php
if (!defined('ABSPATH')) die('-1');

class VCE_fmcSearch extends VCE_component {
    function __construct() {
        parent::__construct();

        add_action( 'init', array( $this, 'integrateWithVC' ) );
    }


    protected function setParams(){

        if (!is_null($this->vars)){
          extract($this->vars);
        }

        $fonts = ['default', 'Arial', 'Verdana', 'Tahoma', 'Times', 'Georgia', 'Garamond'];
        $pp = '';
        $fmc_params = array(
            array(
              "type" => "hidden",
              "heading" => 'mls Allows Sold Searching',
              "param_name" => "mls_allows_sold_searching",
              "value" => $mls_allows_sold_searching,
              'hidden' => true
            ),
            array(
              "type" => "text_field_tag",
              "heading" => 'Title',
              "param_name" => "title",
              "value" => '',
              'admin_label' => true,
            ),
            array(
              'type' => 'select_tag',
              'heading' => 'IDX Link',
              'value' => $idx_links,
              'param_name' => 'link',
              'option_value_attr' => 'LinkId',
              'option_display_attr' => 'Name',
              'default' => $idx_links_default,
              'description' => 'Link used when search is executed',
            ),
            array(
              "type" => "text_field_tag",
              "heading" => 'Submit Button Text',
              "param_name" => "buttontext",
              "value" => '',
              'description' => '(ex. "Search for Homes")',
              'admin_label' => true,
            ),
            array(
              'type' => 'select_tag',
              'heading' => 'Detailed Search',
              'value' => $on_off_options,
              'param_name' => 'detailed_search',
              'option_value_attr' => 'value',
              'option_display_attr' => 'display_text',
            ),
            array(
              'type' => 'select_tag',
              'heading' => 'Send users to',
              'value' => $destination_options,
              'param_name' => 'destination',
              'option_value_attr' => 'value',
              'option_display_attr' => 'display_text',
            ),
            setTitle('Sorting'),
            array(
              'type' => 'select_tag',
              'heading' => 'User Sorting',
              'value' => $on_off_options,
              'param_name' => 'user_sorting',
              'option_value_attr' => 'value',
              'option_display_attr' => 'display_text',
            ),
            setTitle('Filters'),
            array(
              'type' => 'select_tag',
              'heading' => 'Location Search',
              'value' => $on_off_options,
              'param_name' => 'location_search',
              'option_value_attr' => 'value',
              'option_display_attr' => 'display_text',
            ),
            array(
              'type' => 'select_tag',
              'heading' => 'Allow Sold Searching',
              'value' => $on_off_options,
              'param_name' => 'allow_sold_searching',
              'option_value_attr' => 'value',
              'option_display_attr' => 'display_text',
              'dependency' => array(
                'element' => 'mls_allows_sold_searching',
                'not_empty' => true,
              ),
            ),
            array(
              'type' => 'select_tag',
              'heading' => 'Allow Pending Searching',
              'value' => $on_off_options,
              'param_name' => 'allow_pending_searching',
              'option_value_attr' => 'value',
              'option_display_attr' => 'display_text',
              'dependency' => array(
                'element' => 'mls_allows_sold_searching',
                'not_empty' => true,
              ),
            ),
            array(
              'type' => 'select_tag',
              'heading' => 'Property Type',
              'value' => $on_off_options,
              'param_name' => 'property_type_enabled',
              'option_value_attr' => 'value',
              'option_display_attr' => 'display_text',
            ),
            array(
              'type' => 'sortable_list_tag',
              'heading' => 'Property Types',
              'value' => $pp,
              'param_name' => 'property_type',
              'fields_types' => $property_types,
              'collection' => $selected_property_types,
              'button_name' => 'Add Type'
            ),
            array(
              'type' => 'sortable_list_tag',
              'heading' => 'Fields',
              'value' => $pp,
              'param_name' => 'std_fields',
              'fields_types' => $available_fields,
              'collection' => $selected_std_fields,
              'button_name' => 'Add Field'
            ),
            array(
              'type' => 'select_tag',
              'heading' => 'Select a Theme',
              'value' => $theme_options,
              'param_name' => 'theme',
              'option_value_attr' => 'value',
              'option_display_attr' => 'display_text',
              'description' => 'Selecting a theme will override your current layout, style and color settings. The default width of a
              vertical theme is 300px and 730px for horizontal.',
              'admin_label' => true,
            ),
            array(
              'type' => 'select_tag',
              'heading' => 'Default view',
              'value' => $default_view_options,
              'param_name' => 'default_view',
              'option_value_attr' => 'value',
              'option_display_attr' => 'display_text',
              'admin_label' => true,
            ),
            array(
              "type" => "dropdown",
              "heading" => 'Listings per page',
              "param_name" => "listings_per_page",
              "value" => $listings_per_page_options,
            ),
            setTitle('Layout'),
            array(
              'type' => 'select_tag',
              'heading' => 'Orientation',
              'value' => $orientation_options,
              'param_name' => 'orientation',
              'option_value_attr' => 'value',
              'option_display_attr' => 'display_text',
              'admin_label' => true,
            ),
            array(
              "type" => "text_field_tag",
              "heading" => 'Widget Width',
              //"holder" => "div",
              'class' => 'width100',
              'points' => 'px',
              "param_name" => "width",
              "value" => '',
              "size" => 4
            ),
            setTitle('Style'),
            array(
              'type' => 'select_tag',
              'heading' => 'Title Font',
              'value' => $fonts,
              'param_name' => 'title_font',
            ),
            array(
              'type' => 'select_tag',
              'heading' => 'Field Font',
              'value' => $fonts,
              'param_name' => 'field_font',
            ),
            array(
              'type' => 'select_tag',
              'heading' => 'Border Style',
              'value' => $border_style_options,
              'param_name' => 'border_style',
              'option_value_attr' => 'value',
              'option_display_attr' => 'display_text',
            ),
            array(
              'type' => 'select_tag',
              'heading' => 'Widget Drop Shadow',
              'value' => $on_off_options,
              'param_name' => 'widget_drop_shadow',
              'option_value_attr' => 'value',
              'option_display_attr' => 'display_text',
              'class' => "flexmls_connect__setting_enabler_widget_drop_shadow"
            ),
            setTitle('Color'),
            array(
              'type' => 'colorpicker',
              'heading' => 'Background',
              'param_name' => 'background_color',
              'value' => 'FFFFFF'
            ),
            array(
              'type' => 'colorpicker',
              'heading' => 'Title Text',
              'param_name' => 'title_text_color',
              'value' => '000000'
            ),
            array(
              'type' => 'colorpicker',
              'heading' => 'Field Text',
              'param_name' => 'field_text_color',
              'value' => '000000'
            ),
            array(
              'type' => 'colorpicker',
              'heading' => 'Detailed Search',
              'param_name' => 'detailed_search_text_color',
              'value' => '000000'
            ),
            array(
              'type' => 'select_tag',
              'heading' => 'Submit Button',
              'value' => $submit_button_options,
              'param_name' => 'submit_button_shine',
              'option_value_attr' => 'value',
              'option_display_attr' => 'display_text',
            ),
            array(
              'type' => 'colorpicker',
              'heading' => 'Submit Button Background',
              'param_name' => 'submit_button_background',
              'value' => '000000'
            ),
            array(
              'type' => 'colorpicker',
              'heading' => 'Submit Button Text',
              'param_name' => 'submit_button_text_color',
              'value' => 'FFFFFF'
            ),
        );
        return $fmc_params;
    }

}

new VCE_fmcSearch();
