<?php
class FMCD_fmcSearch extends FMCD_module {

    protected $std_fields;
    protected $property_types;

    public function get_fields() {
       extract($this->module_info['vars']);

       $on_off_options = $this->modify_on_off($on_off_options);

       $this->std_fields = $this->modify_array($available_fields);
       $this->property_types = $property_types;

       $fields = array(
          'title' => array(
            'label'           => esc_html__( 'Title', 'fmcd-divi' ),
            'type'            => 'text',
            'option_category' => 'basic_option',
            'description'     => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_search',
            'sub_toggle'      => 'basic_mls'
          ),
          'type' => array(
            'label'           => esc_html__( 'IDX Link', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $this->modify_array($idx_links, 'LinkId', 'Name'),
            'option_category' => 'basic_option',
            'description'     => esc_html__( 'Link used when search is executed', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_search',
            'sub_toggle'      => 'basic_mls'
          ),
          'buttontext' => array(
            'label'           => esc_html__( 'Submit Button Text', 'fmcd-divi' ),
            'type'            => 'text',
            'option_category' => 'basic_option',
            'description'     => esc_html__( '(ex. "Search for Homes")', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_search',
            'sub_toggle'      => 'basic_mls'
          ),
          'detailed_search' => array(
            'label'       => esc_html__( 'Detailed Search', 'fmcd-divi' ),
            'type'        => 'yes_no_button',
            'options'     => $on_off_options,
            'option_category' => 'basic_option',
            'default'     => 'on',
            'description'   => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_search',
            'sub_toggle'      => 'basic_mls'
          ),
          'destination' => array(
            'label'           => esc_html__( 'Send users to', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $this->modify_array($destination_options),
            'option_category' => 'basic_option',
            'description'     => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_search',
            'sub_toggle'      => 'basic_mls',
            'default' => 'local'
          ),
          'user_sorting' => array(
            'label'       => esc_html__( 'User Sorting', 'fmcd-divi' ),
            'type'        => 'yes_no_button',
            'options'     => $on_off_options,
            'option_category' => 'basic_option',
            'default'     => 'on',
            'description'   => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_search',
            'sub_toggle'      => 'sorting_mls'
          ),
          'location_search' => array(
            'label'       => esc_html__( 'Location Search', 'fmcd-divi' ),
            'type'        => 'yes_no_button',
            'options'     => $on_off_options,
            'option_category' => 'basic_option',
            'default'     => 'on',
            'description'   => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_search',
            'sub_toggle'      => 'filters_mls'
          ),
          'mls_allows_sold_searching' => array(
            'label'           => esc_html__( 'Title', 'fmcd-divi' ),
            'type'            => 'hidden',
            'option_category' => 'basic_option',
            'description'     => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_search',
            'sub_toggle'      => 'filters_mls',
             'default' => $mls_allows_sold_searching
          ),
          'allow_sold_searching' => array(
            'label'       => esc_html__( 'Allow Sold Searching', 'fmcd-divi' ),
            'type'        => 'yes_no_button',
            'options'     => $on_off_options,
            'option_category' => 'basic_option',
            'default'     => 'on',
            'description'   => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_search',
            'sub_toggle'      => 'filters_mls',
            'show_if'     => array(
              'mls_allows_sold_searching' => true
            ),
          ),
          'allow_pending_searching' => array(
            'label'       => esc_html__( 'Allow Pending Searching', 'fmcd-divi' ),
            'type'        => 'yes_no_button',
            'options'     => $on_off_options,
            'option_category' => 'basic_option',
            'default'     => 'on',
            'description'   => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_search',
            'sub_toggle'      => 'filters_mls',
            'show_if'     => array(
              'mls_allows_sold_searching' => true
            ),
          ),
          'property_type_enabled' => array(
            'label'       => esc_html__( 'Property Type', 'fmcd-divi' ),
            'type'            => 'yes_no_button',
            'options'     => $on_off_options,
            'option_category' => 'basic_option',
            'default'     => 'on',
            'description'   => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_search',
            'sub_toggle'      => 'filters_mls',
          ),
          'property_type' => array(
              'label'           => esc_html__( 'Property Types', 'fmcd-divi' ),
              'type'            => 'multiple_checkboxes',
              'option_category' => 'basic_option',
              'options'         => $property_types,
              'description'     => esc_html__( '', 'fmcd-divi' ),
              'toggle_slug'     => 'flexmls_search',
              'sub_toggle'      => 'filters_mls',
              'show_if'     => array(
                'property_type_enabled' => 'on'
              ),
          ),
          'std_fields' => array(
            'label'           => esc_html__( 'Fields', 'fmcd-divi' ),
            'type'            => 'multiple_checkboxes',
            'option_category' => 'basic_option',
            'options'         => $this->std_fields,
            'description'     => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_search',
            'sub_toggle'      => 'filters_mls',
          ),
          'theme' => array(
            'label'           => esc_html__( 'Select a Theme', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $this->modify_array($theme_options),
            'option_category' => 'basic_option',
            'description'     => esc_html__( 'Selecting a theme will override your current layout, style and color settings. The default width of a
            vertical theme is 300px and 730px for horizontal.', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_search',
            'sub_toggle'      => 'filters_mls'
          ),
          'default_view' => array(
            'label'           => esc_html__( 'Default view', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $this->modify_array($default_view_options),
            'option_category' => 'basic_option',
            'description'     => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_search',
            'sub_toggle'      => 'filters_mls'
          ),
          'listings_per_page' => array(
            'label'           => esc_html__( 'Listings per page', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $listings_per_page_options,
            'option_category' => 'basic_option',
            'description'     => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
          ),
          'orientation' => array(
            'label'           => esc_html__( 'Orientation', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $this->modify_array($orientation_options),
            'option_category' => 'basic_option',
            'description'     => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_search',
            'sub_toggle'      => 'layout_mls'
          ),
          'width_' => array(
            'label'           => esc_html__( 'Widget Width', 'fmcd-divi' ),
            'type'            => 'range',
            'option_category' => 'basic_option',
            'description'     => esc_html__( 'Horizontal × Vertical', 'fmcd-divi' ),
            'default_unit'    => 'px',
            'range_settings'  => array(
                'min'  => '1',
                'max'  => '900',
                'step' => '1',
            ),
            'default'       => 450,
            'toggle_slug'     => 'flexmls_search',
            'sub_toggle'      => 'layout_mls',
          ),
          'title_font' => array(
            'label'           => esc_html__( 'Title Font', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $fonts,
            'option_category' => 'basic_option',
            'description'     => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_search',
            'sub_toggle'      => 'style_mls'
          ),
          'field_font' => array(
            'label'           => esc_html__( 'Field Font', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $fonts,
            'option_category' => 'basic_option',
            'description'     => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_search',
            'sub_toggle'      => 'style_mls'
          ),
          'border_style' => array(
            'label'           => esc_html__( 'Border Style', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $this->modify_array($border_style_options),
            'option_category' => 'basic_option',
            'description'     => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_search',
            'sub_toggle'      => 'style_mls'
          ),
          'widget_drop_shadow' => array(
            'label'       => esc_html__( 'Widget Drop Shadow', 'fmcd-divi' ),
            'type'            => 'yes_no_button',
            'options'     => $on_off_options,
            'option_category' => 'basic_option',
            'default'     => 'on',
            'description'   => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_search',
            'sub_toggle'      => 'style_mls'
          ),
          'background_color_' => array(
            'label'             => esc_html__( 'Background', 'fmcd-divi' ),
            'type'              => 'color-alpha',
            'option_category' => 'basic_option',
            'default'           => '#ffffff',
            'toggle_slug'     => 'flexmls_search',
            'sub_toggle'      => 'color_mls',
          ),
          'title_text_color' => array(
            'label'             => esc_html__( 'Title Text', 'fmcd-divi' ),
            'type'              => 'color-alpha',
            'option_category' => 'basic_option',
            'default'           => '#000000',
            'toggle_slug'     => 'flexmls_search',
            'sub_toggle'      => 'color_mls',
          ),
          'field_text_color' => array(
            'label'             => esc_html__( 'Field Text', 'fmcd-divi' ),
            'type'              => 'color-alpha',
            'option_category' => 'basic_option',
            'default'           => '#000000',
            'toggle_slug'     => 'flexmls_search',
            'sub_toggle'      => 'color_mls',
          ),
          'detailed_search_text_color' => array(
            'label'             => esc_html__( 'Detailed Search', 'fmcd-divi' ),
            'type'              => 'color-alpha',
            'option_category' => 'basic_option',
            'default'           => '#000000',
            'toggle_slug'     => 'flexmls_search',
            'sub_toggle'      => 'color_mls',
          ),
          'submit_button_shine' => array(
              'label'           => esc_html__( 'Submit Button', 'fmcd-divi' ),
              'type'            => 'select',
              'options'         => $this->modify_array($submit_button_options),
              'option_category' => 'basic_option',
              'description'     => esc_html__( '', 'fmcd-divi' ),
              'toggle_slug'     => 'flexmls_search',
              'sub_toggle'      => 'color_mls'
            ),
          'submit_button_background' => array(
                'label'             => esc_html__( 'Submit Button Background', 'fmcd-divi' ),
                'type'              => 'color-alpha',
                'option_category' => 'basic_option',
                'default'           => '#000000',
                'toggle_slug'     => 'flexmls_search',
                'sub_toggle'      => 'color_mls',
            ),
            'submit_button_text_color' => array(
                'label'             => esc_html__( 'Submit Button Text', 'fmcd-divi' ),
                'type'              => 'color-alpha',
                'option_category' => 'basic_option',
                'default'           => '#ffffff',
                'toggle_slug'     => 'flexmls_search',
                'sub_toggle'      => 'color_mls',
              ),
       );

       return $fields;
    }

    public function convert_props(){
        $props = $this->props;
        $props['background_color'] = $props['background_color_'];
        $props['width'] = $props['width_'];
        unset($props['background_color_']);
        unset($props['width_']);

        $props['std_fields'] = $this->parce_checkbox_group($this->std_fields, $props['std_fields']);
        $props['property_type'] = $this->parce_checkbox_group($this->property_types, $props['property_type']);

        return $props;
    }

    public function convert_fields(){
        $fields = $this->get_fields();
        $fields['background_color'] = $fields['background_color_'];
        $fields['width'] = $fields['width_'];
        unset($fields['background_color_']);
        unset($fields['width_']);
        return $fields;
    }
}

new FMCD_fmcSearch;
