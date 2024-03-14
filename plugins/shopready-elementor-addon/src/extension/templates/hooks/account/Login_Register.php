<?php
namespace Shop_Ready\extension\templates\hooks\account;
use Shop_Ready\base\Template_Redirect as Mangocube_Template;
/*
* WooCommerece Login_Register
* @since 1.0  
*/

Class Login_Register extends Mangocube_Template{


    public function register(){
       
        $this->set_name('my_account_login_register');

        add_filter( 'body_class' , [ $this , 'set_body_class' ] );
        add_action( $this->get_action_hook() , [ $this, 'dynamic_template' ],10 );
     
    }

    /**
     * not-used
     * | is_renderable_template |
     * @param  [string]  $template
     * @param  [string]  $slug
     * @param  [string]  $name
     * @return boolean | int
    */
    public function is_renderable_template( $template, $slug, $name ){
        
        return true;
    }
    /**
     * not-used
     *  action hook
     * | _notification |
     * 
     * @param  [string]  $template
     * @return boolean | int
    */
    public function _notification($page){
      
    }


    /**
    * | set_body_class |
    * @author     <quomodosoft.com>
    * @since      File available since Release 1.0
    * @param  [string]  $classes
    * @return array | []
    */
    public function set_body_class($classes){
        
        if( shop_ready_is_account_login() ) {
            return array_merge( $classes, array( 'woo-ready-'.\str_replace(['_'],['-'], $this->name) ) );
        }
        return  $classes;
    }


}
