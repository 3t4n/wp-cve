<?php
class FMCD_fmcSearchResults extends FMCD_module {

    public function get_fields() {
       extract($this->module_info['vars']);
       $location = '';

      if(isset($office_roster)){
        $agent = array_merge([''=>'  - Select One -  '], $this->modify_array($office_roster, 'Id', 'Name'));
      } else {
        $agent = [];
      }

       $fields = array(
          'title' => array(
               'label'           => esc_html__( 'Title', 'fmcd-divi' ),
               'type'            => 'text',
               'option_category' => 'basic_option',
               'description'     => esc_html__( $special_neighborhood_title_ability, 'fmcd-divi' ),
               'toggle_slug'     => 'flexmls_basic',
          ),
          'link' => array(
             'label'           => esc_html__( 'Saved Search', 'fmcd-divi' ),
             'type'            => 'select',
             'options'         => $this->modify_array($api_links, 'LinkId', 'Name'),
             'option_category' => 'basic_option',
             'description'     => esc_html__( 'flexmls Saved Search to apply', 'fmcd-divi' ),
             'toggle_slug'     => 'flexmls_basic',
          ),
          'source' => array(
             'label'           => esc_html__( 'Filter by', 'fmcd-divi' ),
             'type'            => 'select',
             'options'         => $source_options,
             'option_category' => 'basic_option',
             'description'     => esc_html__( 'Link used when search is executed', 'fmcd-divi' ),
             'toggle_slug'     => 'flexmls_basic',
             'default' => 'location'
          ),
          'property_type' => array(
            'label'           => esc_html__( 'Property Type', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $api_property_type_options,
            'option_category' => 'basic_option',
            'description'     => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
            'show_if'     => array(
                'source' => 'location'
            ),
          ),
          'agent' => array(
            'label'           => esc_html__( 'Agent', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $agent,
            'option_category' => 'basic_option',
            'description'     => esc_html__( 'Link used when search is executed', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
            'show_if'     => array(
             'source' => 'agent'
            ),
         ),
         'property_sub_type' => array(
          'label'           => esc_html__( 'Property Sub Type', 'fmcd-divi' ),
          'type'            => 'text',
          'option_category' => 'basic_option',
          'description'     => esc_html__( '', 'fmcd-divi' ),
          'toggle_slug'     => 'flexmls_basic',
          'disable' => true,
          'default' => 'All Sub Types'
         ),
         'locations' => array(
          'label'           => esc_html__( 'Location' , 'fmcd-divi' ),
          'type'            => 'tiny_mce',
          'option_category' => 'basic_option',
          'description'     => esc_html__( '', 'fmcd-divi' ),
          'toggle_slug'     => 'flexmls_basic',
          'default'         => $location,
          'show_if'     => array(
              'source' => 'location'
          ),
        ),
        'display' => array(
            'label'           => esc_html__( 'Display', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $display_options,
            'option_category' => 'basic_option',
            'description'     => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
            'default'         => 'all',
          ),
          'days' => array(
            'label'           => esc_html__( 'Number of Days', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $this->set_dimentions($display_day_options),
            'option_category' => 'basic_option',
            'description'     => esc_html__( 'The number of days in the past for display: new listings, open houses, etc.', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
            'show_if_not'     => array(
                'display' => 'all',
            ),
          ),
          'default_view' => array(
             'label'           => esc_html__( 'Default view', 'fmcd-divi' ),
             'type'            => 'select',
             'options'         => array(
                 'list' => 'List View',
                 'map' => 'Map view'
             ),
             'option_category' => 'basic_option',
             'description'     => esc_html__( '', 'fmcd-divi' ),
             'toggle_slug'     => 'flexmls_basic',
          ),
          'sort' => array(
            'label'           => esc_html__( 'Sort by', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $sort_options,
            'option_category' => 'basic_option',
            'description'     => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
          ),
          'listings_per_page' => array(
            'label'           => esc_html__( 'Listings per page', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $listings_per_page_options,
            'option_category' => 'basic_option',
            'description'     => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
          )
       );
       return $fields;
    }

    private function set_dimentions($arr){
        $return = array();
        foreach ($arr as $key => $value) {
            $return[(string) $key] = (string) $value;
        }

        return $return;
    }

    public function convert_props(){
      $props = $this->props;
      $props['location'] = $this->parse_location_string($props['locations']);
      unset($props['locations']);

      return $props;
    }

    public function convert_fields(){
      $fields_use = $this->get_fields();
      $fields = array();
      foreach ($fields_use as $key => $value) {
          if($key == 'locations') {
              $fields_use['location'] = $fields_use[$key];
              unset($fields_use[$key]);
          }
      }
      return $fields_use;
  }
}

new FMCD_fmcSearchResults;
