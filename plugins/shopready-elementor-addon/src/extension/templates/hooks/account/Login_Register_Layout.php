<?php

namespace Shop_Ready\extension\templates\hooks\account;

use Shop_Ready\base\Page_Layout as Page_Layout;
use Shop_Ready\base\View;

/*
* WooCommerece Account Register Main Template
* woocommerce->settings->advanced->page setup  
*/

Class Login_Register_Layout extends Page_Layout{

    public $name = null;
    public function register(){
   
       add_filter('template_include', [$this, 'render'], 100 );
    }
  
    /**
     * [ is_renderable_template ]
     * @param  [string]  $template
     * @return string
    */
    public function render($template){
        
     
        if( isset($_GET['elementor-preview']) ){
            return $template;
        } 
        
       
        $view = shop_ready_app_config()['views'];
    
        if( $this->_login_register() ){
          
            if( ( $this->template == 'elementor_theme' || $this->template == 'elementor_header_footer' ) && file_exists($view['myaccount_scene'].'/account-login-register/full-width.php') ){
               
                return $view['myaccount_scene'].'/account-login-register/full-width.php';
            }

            if( $this->template == 'elementor_canvas' && file_exists($view['myaccount_scene'].'/account-login-register/offcanvas.php') ){
                return $view['myaccount_scene'].'/account-login-register/offcanvas.php';
            }

        }
   
        return $template;
    }

    public function _login_register(){
       
        $this->template = View::get_page_template_slug();
        
        if( $this->is_account_page() && $this->template && $this->is_tpl_active('my_account_login_register') && $this->preset_tpl('my_account_login_register')){
         
          return true;
        }
       
        return false;
    }

    public function is_account_page(){
     
      return shop_ready_is_account_login();
     
    }


}