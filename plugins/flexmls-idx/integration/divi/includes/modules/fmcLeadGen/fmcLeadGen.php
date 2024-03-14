<?php
class FMCD_fmcLeadGen extends FMCD_module {

    protected function integrateWithDivi(){
        $this->use_static = true;

        $className = (string) str_replace('FMCD_', '', get_class($this));
        $vars = array();

        global $fmc_widgets_integration;
        $info = $fmc_widgets_integration[$className];
        
        $component = new \FlexMLS\Shortcodes\LeadGeneration();
        $vars = $component->integration_view_vars(); 
        
        $vars["title_description"] = flexmlsConnect::special_location_tag_text();

        $this->component = $component;

        $module_info = array(
            "name" => $this->setTitle($info),
            'slug' => 'fmcd_'.strtolower($className),
            "description" => $info['description'],
            "shortcode" => $info['shortcode'],
            'vars' => $vars,
        );

        return $module_info;
    } 

    public function get_fields() {
        extract($this->module_info['vars']);

       $on_off_options = array(
           'on' => 'Yes',
           'off' => 'No'
       );

       $fields = array(
          'title' => array(
            'label'           => esc_html__( 'Title', 'fmcd-divi' ),
            'type'            => 'text',
            'option_category' => 'basic_option',
            'description'     => esc_html__( $title_description, 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
            'default' => $title
          ),
          'blurb'     => array(
              'label'           => esc_html__( 'Description', 'fmcd-divi' ),
              'type'            => 'textarea',
              'option_category' => 'basic_option',
              'description'     => esc_html__( 'This text appears below the title', 'fmcd-divi' ),
              'toggle_slug'     => 'flexmls_basic',
          ),
          'success'     => array(
            'label'           => esc_html__( 'Success Message', 'fmcd-divi' ),
            'type'            => 'textarea',
            'option_category' => 'basic_option',
            'description'     => esc_html__( 'This text appears after the user sends the information', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
            'default' => $success_message
          ),
          'buttontext' => array(
             'label'           => esc_html__( 'Button Text', 'fmcd-divi' ),
             'type'            => 'text',
             'option_category' => 'basic_option',
             'description'     => esc_html__( 'Customize the text of the submit button', 'fmcd-divi' ),
             'toggle_slug'     => 'flexmls_basic',
             'default' => $buttontext
          ),
          'use_captcha' => array(
             'label'       => esc_html__( 'Use Captcha?', 'fmcd-divi' ),
             'type'        => 'yes_no_button',
             'options'     => $on_off_options,
             'option_category' => 'basic_option',
             'default'     => ($use_captcha == 0) ? 'off': 'on',
             'description'   => esc_html__( '', 'fmcd-divi' ),
             'toggle_slug'     => 'flexmls_basic',
          ),
       );
       return $fields;
    }

    public function convert_props(){
        $props = $this->props;
        if($props['use_captcha'] == 'on') {
            $props['use_captcha'] = 'yes';
        } else {
            $props['use_captcha'] = 'no';
        }
        
        return $props;
    }
}

new FMCD_fmcLeadGen;