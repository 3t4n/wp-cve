<?php
class FMCD_fmcAgents extends FMCD_module {

    //if( 'fmcAgents' == $class && 'Member' == $me ){

    public function get_fields() {
       extract($this->module_info['vars']);
       $fields = array(
          'title' => array(
            'label'           => esc_html__( 'Title', 'fmcd-divi' ),
            'type'            => 'text',
            'option_category' => 'basic_option',
            'description'     => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
          ),
          'search' => array(
             'label'       => esc_html__( 'Detailed Search', 'fmcd-divi' ),
             'type'        => 'yes_no_button',
             'options'     => $search,
             'option_category' => 'basic_option',
             'default'     => 'on',
             'description'   => esc_html__( '', 'fmcd-divi' ),
             'toggle_slug'     => 'flexmls_basic',
          ),
          'user_type' => array(
             'label'           => esc_html__( 'Title', 'fmcd-divi' ),
             'type'            => 'hidden',
             'option_category' => 'basic_option',
             'description'     => esc_html__( '', 'fmcd-divi' ),
             'toggle_slug'     => 'flexmls_basic',
             'default' => $api_my_account['UserType']
          ),
          'search_type' => array(
             'label'           => esc_html__( 'Show Offices or Agents by default', 'fmcd-divi' ),
             'type'            => 'select',
             'options'         => $search_type,
             'option_category' => 'basic_option',
             'description'     => esc_html__( '', 'fmcd-divi' ),
             'toggle_slug'     => 'flexmls_basic',
             'show_if_not' => array(
                 'user_type' => 'Office'
             ),
          ),
       );
       return $fields;
    }

    public function convert_props(){
        $props = $this->props;
        if($props['search'] == 'on'){
            $props['search'] = 'true';
        } else {
            $props['search'] = 'false';
        }

        unset($props['user_type']);
        return $props;
    }

    public function convert_fields(){
        $fields = $this->get_fields();
        unset($fields['user_type']);
        return $fields;
    }
}

new FMCD_fmcAgents;