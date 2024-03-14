<?php

namespace Shop_Ready\extension\header_footer\base;
use Shop_Ready\helpers\classes\Elementor_Helper;

Abstract class Base_Template {
    
    public $header_template_id = false; 
    public $footer_template_id = false; 
    
    /**
     * Header page override
     * @since 1.0
     */
    public function active_header(){

        $current_page_id = get_the_id();

        if(is_front_page()){
            $current_page_id = get_option( 'page_on_front' );
        }
       
        $page_active = shop_ready_get_page_meta( 'wready_page_header_enable' , $current_page_id ) == 'yes' ? true : false;
        $page_id     = shop_ready_get_page_meta( 'wooready_page_header_template' , $current_page_id );
        $base_id     = shop_ready_gl_get_setting( 'wooready_header_template' );
        $base_header = shop_ready_gl_get_setting( 'wready_enable_header' ) == 'yes' ? true : false;
       
        if( $page_active == 'yes' ){

            return false;
        }
    
        // global settings
        if( $base_header && is_numeric( $base_id ) ){
            $this->header_template_id = $base_id;
        } 
   
        // override page header
        if( is_numeric( $page_id ) ){
            $this->header_template_id = $page_id;
         }
 
		return $this->header_template_id;
	}

    public function header_template() {
      
       Elementor_Helper::display_elementor_content($this->header_template_id); 
    }

    public function footer_template() {
        Elementor_Helper::display_elementor_content($this->footer_template_id); 
    }

    public function active_footer(){
        
        $current_page_id = get_the_id();

        if(is_front_page()){

            $current_page_id = get_option( 'page_on_front' );
        }

        $page_active = shop_ready_get_page_meta('wready_page_footer_enable',$current_page_id) == 'yes' ? true : false;
        $page_id     = shop_ready_get_page_meta('wooready_page_footer_template',$current_page_id);
        $base_id     = shop_ready_gl_get_setting('wooready_footer_template');
        $base_footer = shop_ready_gl_get_setting('wready_enable_footer') == 'yes' ? true : false;

        if($page_active == 'yes' ){
            return false;
        }
      
        // global settings
        if($base_footer && is_numeric($base_id) && !$this->footer_template_id ){
            $this->footer_template_id = $base_id;
        } 

        // override page header
        if(is_numeric( $page_id )){
            $this->footer_template_id = $page_id;
        }

		return $this->footer_template_id;
	}
}
