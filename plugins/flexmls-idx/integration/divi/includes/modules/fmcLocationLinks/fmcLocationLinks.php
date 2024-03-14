<?php
class FMCD_fmcLocationLinks extends FMCD_module {

    public function get_fields() {
       extract($this->module_info['vars']);

       $property_type = array_merge([''=>'All'], $property_type);

       $fields = array(
          'title' => array(
             'label'           => esc_html__( 'Title', 'fmcd-divi' ),
             'type'            => 'text',
             'option_category' => 'basic_option',
             'description'     => esc_html__( '', 'fmcd-divi' ),
             'toggle_slug'     => 'flexmls_basic',
          ),
        'link' => array(
            'label'           => esc_html__( 'IDX Link', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $this->modify_array($api_links, 'LinkId', 'Name'),
            'option_category' => 'basic_option',
            'description'     => esc_html__( 'Saved Search IDX link these locations are built upon', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
          ),
        'property_type' => array(
           'label'           => esc_html__( 'Property Type', 'fmcd-divi' ),
           'type'            => 'select',
           'options'         => $property_type,
           'option_category' => 'basic_option',
           'description'     => esc_html__( 'Link used when search is executed', 'fmcd-divi' ),
           'toggle_slug'     => 'flexmls_basic',
        ), 
        'locations' => array(
           'label'           => esc_html__( 'Location', 'fmcd-divi' ),
           'type'            => 'tiny_mce',
           'option_category' => 'basic_option',
           'description'     => esc_html__( '', 'fmcd-divi' ),
           'toggle_slug'     => 'flexmls_basic',
        ), 
        'default_view' => array(
           'label'           => esc_html__( 'Default view', 'fmcd-divi' ),
           'type'            => 'select',
           'options'         => $default_view,
           'option_category' => 'basic_option',
           'description'     => esc_html__( '', 'fmcd-divi' ),
           'toggle_slug'     => 'flexmls_basic',
        ),
        'destination' => array(
            'label'           => esc_html__( 'Send users to', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $destination,
            'option_category' => 'basic_option',
            'description'     => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
            'default' => 'local'
         ),
       );
       return $fields;
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

new FMCD_fmcLocationLinks;