<?php 
class FMCD_fmcPhotos extends FMCD_module {

    private $additional_fields;

    public function get_fields() {
       extract($this->module_info['vars']);

       $this->additional_fields = $additional_fields;

       $idx_links_use = array_merge(['default' => '(Use Saved Default)'], $this->modify_array($idx_links, 'LinkId', 'Name'));

       $agent_use = array_merge([''=>'  - Select One -  '], $this->modify_array($agent, 'Id', 'Name'));

       $fields = array(
          'title' => array(
            'label'           => esc_html__( 'Title' , 'fmcd-divi' ),
            'type'            => 'text',
            'option_category' => 'basic_option',
            'description'     => esc_html__( $title_description, 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
            'default'         => $title,
          ),
          'link' => array(
            'label'           => esc_html__( 'IDX Link', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $idx_links_use,
            'option_category' => 'basic_option',
            'description'     => esc_html__( 'Link used when search is executed', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
          ),
          'horizontal' => array(
            'label'           => esc_html__( 'Horizontal', 'fmcd-divi' ),
            'type'            => 'range',
            //'options'         => $this->set_dimentions($horizontal),
            'option_category' => 'basic_option',
            'description'     => esc_html__( 'Horizontal × Vertical', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
            'range_settings'  => array(
              'min'  => '1',
              'max'  => count($horizontal),
              'step' => '1',
            ),
            'default'       => 1
          ),
          'vertical' => array(
            'label'           => esc_html__( 'Vertical', 'fmcd-divi' ),
            'type'            => 'range',
            //'options'         => $this->set_dimentions($vertical),
            'option_category' => 'basic_option',
            'description'     => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
            'range_settings'  => array(
              'min'  => '1',
              'max'  => count($vertical),
              'step' => '1',
            ),
            'default'       => 1
          ),
          'image_size' => array(
            'label'           => esc_html__( 'Size of Slideshow', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $image_size,
            'option_category' => 'basic_option',
            'description'     => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
          ),
          'auto_rotate' => array(
            'label'           => esc_html__( 'Slideshow', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $auto_rotate,
            'option_category' => 'basic_option',
            'description'     => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
          ),
          'source' => array(
            'label'           => esc_html__( 'Filter by', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $source,
            'option_category' => 'basic_option',
            'description'     => esc_html__( 'Which listings to display', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
            'default' => 'location'
          ),
          'property_type' => array(
            'label'           => esc_html__( 'Property Type', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $property_type,
            'option_category' => 'basic_option',
            'description'     => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
            'show_if'     => array(
                'source' => 'location'
            ),
          ),
          'location' => array(
            'label'           => esc_html__( 'Location' , 'fmcd-divi' ),
            'type'            => 'tiny_mce',
            'option_category' => 'basic_option',
            'description'     => esc_html__( $title_description, 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
            'show_if'     => array(
                'source' => 'location'
            ),
          ),
          'agent' => array(
             'label'           => esc_html__( 'Agent', 'fmcd-divi' ),
             'type'            => 'select',
             'options'         => $agent_use,
             'option_category' => 'basic_option',
             'description'     => esc_html__( 'Link used when search is executed', 'fmcd-divi' ),
             'toggle_slug'     => 'flexmls_basic',
             'show_if'     => array(
              'source' => 'agent'
             ),
          ),
          'display' => array(
            'label'           => esc_html__( 'Display', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $display,
            'option_category' => 'basic_option',
            'description'     => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
            'default'         => 'all',            
          ),
          'days' => array(
            'label'           => esc_html__( 'Number of Days', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $this->set_dimentions($days),
            'option_category' => 'basic_option',
            'description'     => esc_html__( 'The number of days in the past for display: new listings, open houses, etc.', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
            'show_if_not'     => array(
                'display' => 'all',
                'source' => 'location'
            ),
          ),
          'sort' => array(
            'label'           => esc_html__( 'Sort by', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $sort,
            'option_category' => 'basic_option',
            'description'     => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
          ),
          'additional_fields' => array(
            'label'           => esc_html__( 'Additional Fields to Show', 'fmcd-divi' ),
            'type'            => 'multiple_checkboxes',
            'option_category' => 'basic_option',
            'options'         => $additional_fields,  
            'description'     => esc_html__( 'aasasd', 'fmcd-divi' ),
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
          'send_to' => array(
            'label'           => esc_html__( 'When Slideshow Photo Is Clicked Send Users To', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $send_to,
            'option_category' => 'basic_option',
            'description'     => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
          ),
       );

       return $fields;
    }

    public function convert_props(){
        $props = $this->props;
        $props['additional_fields'] = $this->parce_checkbox_group($this->additional_fields, $props['additional_fields']);
        $props['location'] = $this->parse_location_string($props['location']);
        return $props;
    }

    private function set_dimentions($arr){
        $return = array();
        foreach ($arr as $key => $value) {
            $return[(string) $key] = (string) $value;
        }

        return $return;
    }
}

new FMCD_fmcPhotos;