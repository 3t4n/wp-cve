<?php

namespace Element_Ready\dashboard;

class Widgets {

    private $notice_url = 'https://plugins.quomodosoft.com/templates/wp-json/element-ready/v1/remote?type=quomodo-notice-element-ready-dashboard';
   
    public function register() {

        add_action( 'wp_dashboard_setup', [ $this , 'add_dashboard_widget' ] );
        add_action( 'admin_enqueue_scripts', [ $this,'add_admin_scripts' ] );
    }

    public function add_dashboard_widget(){

        wp_add_dashboard_widget(
            'element_ready_news_dashboard_widget', 
            esc_html__('Element Ready News','element-ready-lite'),
            [ $this , 'dashboard_widget' ] 
           
        );

    }

    function dashboard_widget() {

        $_data = null;
  
        if ( false === get_transient( 'element_ready_dashboard_widgets_one' ) ) {
            $_data = wp_remote_retrieve_body(wp_remote_get( esc_url_raw( $this->notice_url )) );
            set_transient( 'element_ready_dashboard_widgets_one', $_data, 24 * HOUR_IN_SECONDS );
        }else{
            $_data = get_transient( 'element_ready_dashboard_widgets_one' );
        }
        
        $_data = json_decode($_data,true);
      
        if(!isset($_data['show'])){
          return;
        }
     
        if( is_wp_error( $_data ) ) {
          return false;
        }
    
        if($_data['msg'] == '""'){
          return;
        }
     
        // if the widget is configured and the post is exists
        echo wp_kses_post(base64_decode($_data['msg']));
     
    }
 
    public function add_admin_scripts($handle){

        $screen = get_current_screen(); 
      
        if( isset($screen->id) && $screen->id =='dashboard' && $screen->base =='dashboard' )
        {
          wp_enqueue_style( 'element-ready-admin', esc_url(ELEMENT_READY_ROOT_CSS .'admin.css') );
        }
    }
    

}