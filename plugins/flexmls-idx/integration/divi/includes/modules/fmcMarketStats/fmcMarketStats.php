<?php
class FMCD_fmcMarketStats extends FMCD_module {

    private $stat_types;
    private $type_options;
    private $display_options;

    public function get_fields() {
       extract($this->module_info['vars']);

       $this->stat_types = $stat_types;
       $this->type_options = $type_options;
       $this->display_options = array();

       $location = '';// 'ListingId=19-1333&19-1333';

       $property_type_options = array_merge(['' => 'All'], $property_type_options);

       $fields1 = array(
        'title' => array(
            'label'           => esc_html__( 'Title', 'fmcd-divi' ),
            'type'            => 'text',
            'option_category' => 'basic_option',
            'description'     => esc_html__( $title_description, 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
            'default'         => $title
          ),
          'width_' => array(
            'label'           => esc_html__( 'Width', 'fmcd-divi' ),
            'type'            => 'range',
            'option_category' => 'basic_option',
            'description'     => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
            'default_unit'    => 'px',
            'range_settings'  => array(
                'min'  => '1',
                'max'  => '900',
                'step' => '1',
            ),
            'default'       => $width
          ),
          'height_' => array(
            'label'           => esc_html__( 'Height', 'fmcd-divi' ),
            'type'            => 'range',
            'option_category' => 'basic_option',
            'description'     => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
            'default_unit'    => 'px',
            'range_settings'  => array(
                'min'  => '1',
                'max'  => '900',
                'step' => '1',
            ),
            'default'       => $height 
          ),
          'type' => array(
            'label'           => esc_html__( 'Type', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $type_options,
            'option_category' => 'basic_option',
            'description'     => esc_html__( 'Which type of chart to display', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
            'default' => 'absorption'
          ), 
          'display' => array(
            'label'           => esc_html__( 'Title', 'fmcd-divi' ),
            'type'            => 'hidden',
            'option_category' => 'basic_option',
            'description'     => esc_html__( '', 'fmcd-divi' ),
            'toggle_slug'     => 'flexmls_basic',
          ),
       );

       $fields2 = $this->set_stat_types('display');

       $fields3 = array(
          'property_type' => array(
            'label'           => esc_html__( 'Property Type', 'fmcd-divi' ),
            'type'            => 'select',
            'options'         => $property_type_options,
            'option_category' => 'basic_option',
            'toggle_slug'     => 'flexmls_basic',
          ),
          'location' => array(
            'label'           => esc_html__( 'Location', 'fmcd-divi' ),
            'type'            => 'tiny_mce',
            'option_category' => 'basic_option',
            'toggle_slug'     => 'flexmls_basic',
            'default'         => $location
          ),
       );

       $fields = $fields1 + $fields2 + $fields3;

       return $fields;
    }

    private function set_stat_types($param){
        $types = $this->type_options;
        $stat = $this->stat_types;

        $return = array();
        $i = 0;

        foreach ($types as $val => $label) {
            $types_array = $this->modify_types($stat[$val]);
            $this->display_options[$val] = $types_array['options'];
            $return[$param.'_'.$val] = array(
                'label'           => esc_html__( 'Display', 'fmcd-divi' ),
                'type'            => 'multiple_checkboxes',
                'option_category' => 'basic_option',
                'options'         => $types_array['options'],  
                'description'     => esc_html__( '', 'fmcd-divi' ),
                'toggle_slug'     => 'flexmls_basic',
                'show_if'     => array(
                    'type' => ($val == 'absorption') ? '': $val
                ),
                'default' => $types_array['default']
            );
            $i = $i + 1;
        }

        return $return;
    }

    private function modify_types($types){
        $return = array(
            'options' => array(),
            'default' => array()
        );
        foreach ($types as $data) {            
            $return['options'][$data['value']] = $data['label'];
            if(!empty($data['selected'])){
                $return['default'][] = 'on';//$data['value'];
            } else {
                $return['default'][] = 'off';
            }
        }
        $return['default'] = implode('|', $return['default']);

        return $return;
    }

    public function convert_props(){
        $props = $this->props;
        $types = $this->type_options;
        foreach ($types as $val => $label) {
            if($props['type'] == $val){
                $display = $val;
            } else {
                unset($props['display_'.$val]);
            }
        }

        $props['display'] = $this->parce_checkbox_group($this->display_options[$display], $props['display_'.$display]);
        $props['width'] = rtrim($props['width_'], 'px');
        $props['height'] = rtrim($props['height_'], 'px');;
        unset($props['display_'.$display]);
        unset($props['width_']);
        unset($props['height_']);

        $props['location'] = $this->parse_location_string($props['location']);

        return $props;
    }

    public function convert_fields(){
        $fields_use = $this->get_fields();
        $fields = array();
        foreach ($fields_use as $key => $value) {
            if(strpos($key, 'display_') !== false){
                unset($fields_use[$key]);
            }
            if($key == 'width_') {
                $fields_use['width'] = $fields_use[$key]; 
                unset($fields_use[$key]);
            }
            if($key == 'height_') {
                $fields_use['height'] = $fields_use[$key]; 
                unset($fields_use[$key]);
            }
        }
        return $fields_use;
    }

}

new FMCD_fmcMarketStats;