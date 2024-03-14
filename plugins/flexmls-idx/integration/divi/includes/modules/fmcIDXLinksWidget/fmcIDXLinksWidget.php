<?php
class FMCD_fmcIDXLinksWidget extends FMCD_module {

    private $api_links;

    public function get_fields() {
       extract($this->module_info['vars']);

       $this->api_links = $this->modify_array($api_links, 'LinkId', 'Name');

       $fields = array(
          'title' => array(
             'label'           => esc_html__( 'Title', 'fmcd-divi' ),
             'type'            => 'text',
             'option_category' => 'basic_option',
             'description'     => esc_html__( '', 'fmcd-divi' ),
             'toggle_slug'     => 'flexmls_basic',
          ),
          'links' => array(
              'label'           => esc_html__( 'Saved Search IDX Links to Display', 'fmcd-divi' ),
              'type'            => 'multiple_checkboxes',
              'option_category' => 'basic_option',
              'options'         => $this->api_links,  
              'description'     => esc_html__( 'Links can be managed inside the flexmls® Web IDX Manager', 'fmcd-divi' ),
              'toggle_slug'     => 'flexmls_basic',
          ),
          'default_view' => array(
             'label'           => esc_html__( 'Default view', 'fmcd-divi' ),
             'type'            => 'select',
             'options'         => $default_view,
             'option_category' => 'basic_option',
             'description'     => esc_html__( 'Link used when search is executed', 'fmcd-divi' ),
             'toggle_slug'     => 'flexmls_basic',
          ),
          'destination' => array(
             'label'           => esc_html__( 'Send users to', 'fmcd-divi' ),
             'type'            => 'select',
             'options'         => $possible_destinations,
             'option_category' => 'basic_option',
             'description'     => esc_html__( 'Link used when search is executed', 'fmcd-divi' ),
             'toggle_slug'     => 'flexmls_basic',
             'default' => 'local'
          ),
       );
       return $fields;
    }

    public function convert_props(){
        $props = $this->props;
        $props['links'] = $this->parce_checkbox_group($this->api_links, $props['links']);
        return $props;
    }
}

new FMCD_fmcIDXLinksWidget;