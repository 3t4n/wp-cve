<?php 
namespace Shop_Ready\base;
use Shop_Ready\base\config\Template_Settings;

Abstract Class Template_Redirect extends Template_Settings {

    public $name = null;
    public $body_class = [];
    
    public $filter_hook = 'mangocube_fl_tpl_';
    public $action_hook = 'mangocube_act_tpl_';

    abstract protected function register();
    abstract protected function is_renderable_template($template, $slug, $name);
    abstract protected function set_body_class($classes);
  
    public function set_path($path){
        $this->path = $path;    
    }
 
    public function set_name($name){
          $this->name = $name;
    }
    
    public function get_name(){
        return $this->name;
    }

    /**
    * | get_filter_hook |
    * @author     <quomodosoft.com>
    * @since      File available since Release 1.0
    * @_usage_   pre-ready-template 
    * @return string | template_hook_name 
    */
    public function get_filter_hook(){

        return $this->filter_hook.$this->name;
    }

    /**
    * | get_action_hook |
    * @author     <quomodosoft.com>
    * @since      File available since Release 1.0
    * @_usage_   pre-ready-template 
    * @return string | template_hook_name 
    */
    public function get_action_hook(){

        return $this->action_hook.$this->name;
    }


    /**
    * | get_template |
    * @author     <quomodosoft.com>
    * @since      File available since Release 1.0
    * @param  [string]  $template
    * @param  [string]  $slug
    * @param  [string]  $name
    * @return string | wp-template-path.php
    */
    public function get_template($template, $slug, $name) {
     
       
	    if( $this->is_tpl_active() && $this->preset_tpl() ){
           
            if( View::is_template_file_exist($this->name) ){
               
                if( $this->is_renderable_template($template, $slug, $name) ){
                    return View::get_template_file($this->get_filter_hook());
                }
             
            }
        }
        
		return $template;
	}

   
   
  
}