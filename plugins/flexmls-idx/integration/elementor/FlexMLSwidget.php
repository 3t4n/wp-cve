<?php

  class EL_FMC_widget extends \Elementor\Widget_WordPress {  

    private $categories = [];
    private $title;
  
    public function get_name() {
          return 'fmc-widget-' . $this->get_widget_instance()->id_base;
    }
  
    public function get_categories() {
          return $this->categories;
    }
    
    public function get_icon() {
          return 'flexmls-icon-logo';
    }
    
    public function get_keywords() {
          return [ 'flexmls', 'fms', 'widget' ];
    }

    public function get_title() {
        return $this->title;
    }
  
    public function __construct( $data = [], $args = null,  $cats = []) {
      $this->categories = $cats;
      parent::__construct( $data, $args );
      
      $this->title = $args['widget_title'];
  
    }  
  };

  class EL_FMC_shortcode extends \Elementor\Widget_Base{
    protected $categories = [];

    protected $module_info;
    protected $settings_fmc = [];
  
    public function get_name() {
          return $this->module_info['slug'];
    }

    public function get_title() {
        return $this->module_info['title'];
    }
  
    public function get_categories() {
        return $this->categories;
    }
    
    public function get_icon() {
        return 'flexmls-icon-logo';
    }
    
    public function get_keywords() {
        return [ 'flexmls', 'fms', 'widget' ];
    }
    
    protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->setControlls();

		$this->end_controls_section();

    }
    
    protected function render_hook($settings){
        return $settings;
    }
    
    protected function render() {
        $shortcode_attrs = [];
        foreach ($this->settings_fmc as $attr) {
            $shortcode_attrs[$attr] = $this->get_settings($attr);
        }

        //$component = $this->module_info['component'];

        $shortcode_attrs = $this->render_hook($shortcode_attrs);
        $shortcode = $this->createShortcode($shortcode_attrs);

        if(is_admin()){
            echo do_shortcode($shortcode);
            //echo $shortcode;
        } else {
            echo $shortcode;
        }
        //$component->shortcode($shortcode_attrs); 
        
        //var_dump($shortcode_attrs);		
    }
    
    
    protected function integrationWithElementor(){}

    protected function modify_array($arr, $val = 'value', $label = 'display_text'){
        $options = array();
        foreach ($arr as $data) {
          $options[$data[$val]] = $data[$label];
        };
  
        return $options;
    }

    protected function get_field_name($field_name){
        return 'fmc_shortcode_field_'.$field_name;
    }
      
    protected function get_field_id($field_name){
        return $field_name;
    }

    protected function inits(){
        $className = (string) str_replace('EL_', '', get_class($this));
        $vars = array();

        global $fmc_widgets_integration;
        $info = $fmc_widgets_integration[$className];
        
        $component = new $className();
        $vars = $component->integration_view_vars();         

        $module_info = array(
            "title" => $info['title'],
            'id_base' => $className,
            'slug' => 'fmc-widget-'.strtolower($className),
            "description" => $info['description'],
            "shortcode" => $info['shortcode'],
            'component' => &$component,
            'vars' => $vars,
        );

        $this->module_info = $module_info;

    } 

    protected function createShortcode($params, $params_empty = []){
        $output = '';
        $output .='[' . $this->module_info['shortcode'];
        foreach ($params as $key => $value) {
            if($value != '' || in_array($value, $params_empty)){
                $output .= ' ' . $key . '="' . $value . '"'; 
            }
        }
        $output .= ']';
        return $output;
    }

    public function __construct( $data = [], $args = null,  $cats = [] ) {
        $this->inits();

        if (empty($this->module_info['vars'])) return;  
        
        $this->integrationWithElementor();

        $this->categories = $cats;
    
        parent::__construct( $data, $args );
    }  

  };

