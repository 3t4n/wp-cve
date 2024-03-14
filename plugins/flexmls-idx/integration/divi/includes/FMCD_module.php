<?php

class FMCD_module extends ET_Builder_Module {

    public $slug       = 'fmcd_';
    public $vb_support = 'off';
    protected $vars;
    protected $module_info;
    protected $component;
    protected $use_static = false;
    
    protected $module_credits = array(
       'module_uri' => 'https://www.fbsidx.com/plugin/',
       'author'     => 'FBS Data',
       'author_uri' => 'https://www.flexmls.com/',
    );
    
    public function init() {
        $this->module_info = $this->integrateWithDivi();
        $this->name = esc_html__( $this->module_info['name'], 'fmcd-divi' );
        $this->slug = $this->module_info['slug'];
    }
    
    public function get_fields() {
       return array();
    }

    public function render( $attrs, $content = null, $render_slug ) {
        $props = array();
        $use_props = $this->convert_props();
        $fields = $this->convert_fields();
        foreach ($fields as $key => $value) {
            $props[$key] = $use_props[$key];
        };

        $component = $this->component;

        if($this->use_static === true){
            return $component::shortcode($props);//print_r($props);//;
        } else {
            return $component->shortcode($props);//print_r($props);//;
        }

    }

    public function convert_props(){
        $props = $this->props;
        return $props;
    }

    public function convert_fields(){
        $fields = $this->get_fields();
        return $fields;
    }

    protected function parse_location_string($prop_location){
        preg_match_all('#{(.+?)}#is', $prop_location, $arr);
        return $arr[1][0];
    }

    public function get_settings_modal_toggles() {
        return array(
          'basic_option' => array(
            'toggles' => array(
              'flexmls_search' => array(
                'priority' => 24,                
                'title' => 'FlexMLS Widget Options',
                'sub_toggles' => array(
                    'basic_mls' => array(
                      'name' => 'Basic',
                      //'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128" enable-background="new 0 0 128 128"><g style="transform: scale(0.4) translate(-180px, -315px);"><path d="m348.71 602.06c-45.253-4.9694-60.54-23.605-52.021-63.415 1.1181-5.225 2.9033-14.45 3.9672-20.5 1.0638-6.05 2.8242-15.275 3.912-20.5 3.0758-14.774 7.9303-37.399 10.621-49.5 4.2048-18.912 4.2078-18.95 1.4114-17.961-6.1222 2.1653-32.95-0.44034-45.358-4.4053-4.9676-1.5874-12.857-4.0436-17.532-5.4582s-11.2-3.7096-14.5-5.0999-7.437-2.5386-9.1933-2.5516c-8.9383-0.0664-8.8343-18.703 0.1714-30.706 1.9879-2.6496 6.3264-8.5071 9.641-13.017 10.756-14.633 23.614-23.129 46.881-30.973 4.125-1.3907 11.721-4.1277 16.881-6.0822 9.3809-3.5536 28.656-4.1115 58.619-1.6966 45.142 3.6382 92.192 35.014 102.58 68.409 12.555 40.347 1.5058 61.959-28.422 55.593-10.792-2.2954-36.499-10.949-44.662-15.035-9.7094-4.8598-11.484-5.2888-9.9396-2.4027 0.67766 1.2662 1.1948 3.6145 1.1492 5.2183-0.0456 1.6039 1.6955 8.823 3.869 16.042 2.1736 7.2195 4.6105 18.076 5.4154 24.126 0.80487 6.05 1.9332 12.575 2.5075 14.5 5.8634 19.656 7.6253 47.835 4.0338 64.515-2.9524 13.712-5.7467 19.121-14.364 27.802-10.294 10.371-21.853 14.615-35.671 13.098zm13.996-18.913c18.645-9.4482 26.833-38.582 19.532-69.502-0.8441-3.575-2.4244-11.675-3.5119-18-3.736-21.73-5.1194-28.253-7.6076-35.868-5.9585-18.237-5.9803-24.637-0.0938-27.488 3.5546-1.7216-1.7392-4.6986-23.565-13.252-5.0878-1.9939-9.7746-3.8312-10.415-4.0829-0.81267-0.31924-1.0124 0.69859-0.66064 3.3667 0.32795 2.4876-0.55996 7.8439-2.5403 15.324-1.6745 6.325-4.3939 18.475-6.0433 27-1.6493 8.525-4.1689 20.9-5.599 27.5s-3.0358 14.025-3.5684 16.5c-0.53252 2.475-1.3869 7.466-1.8986 11.091-0.51169 3.6251-2.3015 13.3-3.9773 21.5-5.6777 27.782-4.7203 32.823 7.7397 40.757 11.443 7.2861 32.844 9.8992 42.209 5.1538zm87.766-145.75c2.2642-2.8106 2.5355-7.75 0.42578-7.75-1.2623 0-2.194 0.88854-2.5913 2.4711-0.89732 3.5752-3.8283 4.7801-9.3847 3.8581-6.09-1.0106-6.1773-1.0083-6.4052 0.17078-0.43771 2.2643 16.205 3.4229 17.955 1.25zm-127.26-23.76c3.2664-6.3166-45.948-13.36-58.825-8.4187-2.5087 0.96267-2.0343 2.3105 1.0687 3.0366 1.5125 0.35393 6.353 1.6662 10.757 2.9161 8.1836 2.3228 21.885 4.288 33.493 4.8041 3.575 0.15893 7.7259 0.37568 9.2243 0.48165 2.1162 0.14967 3.072-0.47975 4.2822-2.8198z" style="fill:#daaf6f" /><path id="path3473" d="m342.71 600.03c-19.461-3.4729-25.772-6.3378-38.984-17.695-8.8988-7.6502-10.792-21.474-5.9958-43.789 1.1114-5.171 3.5752-17.727 5.4752-27.902s4.6259-23.675 6.0574-30c2.9858-13.192 7.9759-36.78 9.5838-45.301 1.2976-6.8771 0.95743-7.4045-3.9339-6.0989-2.5571 0.68252-7.9837 0.61954-17.218-0.19984-14.154-1.2559-16.666-1.6864-23.985-4.1102-2.475-0.81973-9.7298-2.979-16.122-4.7983-6.392-1.8193-13.068-4.0559-14.836-4.9701-1.768-0.91425-6.1456-2.2755-9.728-3.0251-7.8946-1.6518-7.0526-0.62123-7.3509-8.9967-0.40075-11.249-0.23586-11.845 5.7102-20.624 17.856-26.363 30.042-35.483 60.052-44.945 6.7237-2.1198 13.699-4.6591 15.5-5.6429 5.9037-3.2243 49.271-2.3831 66.775 1.2952 40.144 8.4359 64.995 24.496 84.74 54.763 9.981 15.3 13.182 43.085 6.4781 56.226-7.7178 15.128-35.612 11.684-80.33-9.9194-21.242-10.262-27.831-13.21-39.388-17.625-5.5-2.1008-10.788-4.14-11.75-4.5315-2.3824-0.96903-2.2411 1.0111 0.25 3.5022 2.3811 2.3811 2.5002 4.9518 0.53614 11.568-1.6863 5.6808-5.8882 24.599-8.5361 38.432-1.0002 5.225-3.001 14.9-4.4463 21.5-3.3187 15.156-5.2307 24.763-6.0699 30.5-0.36203 2.475-1.9892 11.381-3.616 19.792-3.5455 18.33-4.0823 29.706-1.5818 33.522 5.92 9.0351 24.075 15.686 42.818 15.686 25.17 0 39.592-36.289 30.005-75.5-1.2103-4.95-3.0423-14.625-4.071-21.5-2.2303-14.905-4.0568-22.931-7.5013-32.963-4.0505-11.797-4.4979-20.361-1.1774-22.537 5.298-3.4714 10.803 0.90497 11.949 9.4998 0.33012 2.475 2.2266 9.8528 4.2145 16.395 1.9878 6.5423 4.2116 16.442 4.9418 22 0.73012 5.5577 2.4824 15.055 3.894 21.105 14.614 62.636-8.1583 104.77-52.359 96.886zm109.18-162.42c1.9022-2.2665 4.9544-14.524 4.3567-17.495-0.21747-1.0813-0.94317-6.1436-1.6127-11.25-0.6695-5.106-1.6067-9.5242-2.0826-9.8184-0.47593-0.29414-1.4918-2.0342-2.2575-3.8668-0.76571-1.8326-3.4589-5.8693-5.9848-8.9705-2.526-3.1012-4.5926-6.0748-4.5926-6.608 0-1.5006-13.026-13.915-16.45-15.679-1.6775-0.86375-4.4-2.576-6.05-3.8051-3.21-2.391-25.301-10.469-28.63-10.469-1.0994 0-4.1074-0.85374-6.6844-1.8972-6.0166-2.4362-19.737-5.489-21.547-4.7942-0.76645 0.29411-6.2861-0.12609-12.266-0.93379-5.9798-0.80771-12.672-1.191-14.872-0.85184-2.2 0.33919-7.0546 0.83688-10.788 1.106-3.7334 0.2691-9.8084 1.6316-13.5 3.0278s-10.987 3.7768-16.212 5.2902c-9.0292 2.6154-12.035 3.9777-25.105 11.379-10.33 5.8495-18.562 14.999-25.568 28.418-4.3795 8.388-3.5015 8.5051 18.217 2.4298 8.9841-2.5131 37.733-2.4397 45.956 0.11722 2.75 0.85515 8.2087 2.0352 12.13 2.6222 10.376 1.5532 30.003 8.6097 49.37 17.75 3.025 1.4276 9.775 4.3837 15 6.5691s13.55 5.9252 18.5 8.3107 11.925 5.0704 15.5 5.9663c3.575 0.89599 8.525 2.3215 11 3.1678 10.669 3.648 21.246 3.7724 24.174 0.28419zm-126.52-24.61c2.8597-2.9979 1.9338-3.6241-9.1582-6.1936-24.069-5.5755-58.089-5.4292-56.199 0.24175 0.90825 2.7248 33.497 9.5299 46.199 9.6473 3.85 0.0356 8.8 0.41547 11 0.84418 3.1092 0.6059 4.2227 0.43914 5-0.74884 0.55-0.84059 1.9712-2.5464 3.1582-3.7908z" style="fill:#372c35" /></g></svg>',
                    ),
                    'sorting_mls' => array(
                        'name' => 'Sorting',
                    ),
                    'filters_mls' => array(
                        'name' => 'Filters',
                    ),
                    'layout_mls' => array(
                        'name' => 'Layout',
                    ),
                    'style_mls' => array(
                        'name' => 'Style',
                    ),
                    'color_mls' => array(
                        'name' => 'Color',
                    ),
                ),
                'tabbed_subtoggles' => true,
              ),
              'flexmls_basic' => array(
                'priority' => 23,                
                'title' => 'FlexMLS Widget Options',
              ),
            ),
          ),
        );
      }

    //----------------

    protected function integrateWithDivi(){
        $className = (string) str_replace('FMCD_', '', get_class($this));
        $vars = array();

        global $fmc_widgets_integration;
        $info = $fmc_widgets_integration[$className];
        
        $component = new $className();
        $vars = $component->integration_view_vars(); 

        $this->component = $component;
        /* if(class_exists($className)) {
        } */ 

        $module_info = array(
            "name" => $this->setTitle($info),
            'slug' => 'fmcd_'.strtolower($className),
            "description" => $info['description'],
            "shortcode" => $info['shortcode'],
            'vars' => $vars,
        );

        return $module_info;
    } 
    
    protected function createShortcode($params){
        $output = '';
        $output .='[' . $this->module_info['shortcode'];
        foreach ($params as $key => $value) {
            $otput .= ' ' . $key . '="' . $value . '"'; 
        }
        $output .= ']';
        return $output;
    }

    protected function setTitle($info){
        $title = str_replace('&reg;', '®', $info['title']);
        return $title;
    }

    public function parce_checkbox_group($fields, $props){
        $props_array = explode('|', $props);
        $output = '';
        $i = 0;
        foreach ($fields as $key => $value) {
            if($props_array[$i] === 'on'){
                $output .= $key.',';
            }
            $i = $i + 1;
        };
        return rtrim($output, ',');
    }

    protected function modify_array($arr, $val = 'value', $label = 'display_text'){
        $options = array();
        foreach ($arr as $data) {
          $options[$data[$val]] = $data[$label];
        };
  
        return $options;
    }

    protected function modify_on_off($options){
        $return = array();
        foreach ($options as $data) {
            $return[$data['value']] = esc_html__( $data['display_text'], 'fmcd-divi' );
        }
        return $return;
    }

    protected function deleteItem( &$array, $value ){
        foreach( $array as $key => $val ){
            if( is_array($val) ){
                deleteItem($array[$key], $value);
            }elseif( $val===$value ){
                unset($array[$key]);
            }
        }
    }

    protected function isWidgetAvailable(){
        global $fmc_api;

        $api_property_type_options = $fmc_api->GetPropertyTypes();
        $api_system_info = $fmc_api->GetSystemInfo();
        $Account = new \SparkAPI\Account();
        $api_my_account = $Account->get_my_account();

        if ($api_property_type_options === false || $api_system_info === false || $api_my_account === false) {
            return flexmlsConnect::widget_not_available($fmc_api, true);
        } 
    }
}