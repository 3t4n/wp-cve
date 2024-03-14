<?php 
namespace Shop_Ready\extension\generalwidgets;

use Shop_Ready\base\elementor\Widget_Settings as Mangocube_Widget_Settings;
use Shop_Ready\base\elementor\style_controls\box\Widget_Box_Style;
use Shop_Ready\base\elementor\style_controls\common\Widget_Common_Style;
use Shop_Ready\base\elementor\style_controls\position\Widget_Style_Position;


Abstract Class Widget_Base extends \Elementor\Widget_Base {
    
    use Widget_Box_Style; 
    use Widget_Common_Style; 
    use Widget_Style_Position; 

    public $config = false;
    public $wrapper_class = true;

    abstract protected function html();

	public function get_categories() {

        $main_addon = [ 'wgenerel' ];

        if( method_exists( $this, 'set_categories' ) && is_array( $this->set_categories() ) ){

           return array_merge($main_addon,$this->set_categories());
        }

        if($this->have_config_key('category') ){
           
          if(is_array($this->config['category'])){
            return array_merge($main_addon,$this->config['category']);
          }elseif(is_string($this->config['category'])){

            return array_push( $main_addon, $this->config['category'] );
          }  
          
        }

		return $main_addon;
	}
 
	public function get_icon() {
        
        if( $this->have_config_key('icon') ){
            return $this->config['icon'];
        }

		return ' eicon-pro-icon';
	}

    public function show_in_panel() {
        
        if( $this->config ){
            return $this->config['show_in_panel'];
        }

		return true;
	}

    public function get_name() {
 
        $slug = $this->get_refined_slug();
        $this->set_shop_config($slug);

		return $slug;
	}

    /*
    * Set Config From config/widgets.php
    * since 1.0
    * return void
    */

    public function set_shop_config($real_slug) {

        $all_configs  =  shop_ready_genwidget_config()->all();
       
        if(is_array($all_configs)){

            $all_configs = array_change_key_case($all_configs,CASE_LOWER);
          
        }

        $this->config = array_key_exists($real_slug,$all_configs)?$all_configs[$real_slug]:false;
        
       unset($all_configs);
    }

    public function get_refined_slug() {

        $slug  = str_replace(['shop_ready\extension\generalwidgets\widgets'],[''],strtolower(get_called_class()) );
        return  strtolower( trim( str_replace( '\\' , '_', $slug ),'_' ) );
    }

    public function get_keywords() {

        if($this->have_config_key('keywords')){
           $keyword = $this->config['keywords']; 
           return is_array( $keyword )? $keyword : [ $keyword ];
        }

        return [ $this->get_title() ];
    }

    public function have_config_key( $key ){
        
        if( !is_array( $this->config ) ){

          return false; 
        }

        if( !array_key_exists( $key, $this->config )){
            return false;        
        }

        return true;
    }

    public function get_title() {

        $key = 'title';

        if($this->have_config_key($key)){
            return $this->config[$key];
        }

		return str_replace(['_','-','.'] ,[' '],$this->get_refined_slug() ) ;
	}

    public function get_script_depends() {

        $key = 'js';

        if($this->have_config_key($key)){

            $asset = $this->config[$key];
            return is_array( $asset ) ? $asset : [ $asset ];
        }

        return [];
    }
  
    public function get_style_depends() {

        $key = 'css';

        if($this->have_config_key($key)){

            $asset = $this->config[$key];
            return is_array( $asset ) ? $asset : [ $asset ];
        }

        return [];
    }

    public function render(){
        
        $this->html();
        
    }

}