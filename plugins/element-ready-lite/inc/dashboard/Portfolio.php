<?php

namespace Element_Ready\dashboard;

class Portfolio {

    public $links = [ ];

    public function register() {

        $this->links[] = [
            'label' => esc_html__('Category','element-ready-lite'), 
            'url' => 'edit-tags.php?taxonomy=portfolio_category&post_type=portfolio' 
        ];

        $this->links[] = [
            'label' => esc_html__('Tags','element-ready-lite'), 
            'url' => 'edit-tags.php?taxonomy=portfolio_tags&post_type=portfolio' 
        ];

        add_action( 'admin_menu', [$this,'add_page']);
        add_action( 'views_edit-portfolio', [$this,'add_sub_links'], 100); 
        add_action( 'admin_enqueue_scripts', [$this,'add_admin_scripts'] );
    }

    public function add_sub_links($post) {
     
        $screen = get_current_screen(); 
        if( isset($screen->parent_base) && 'element_ready_elements_dashboard_page' === $screen->parent_base && isset($screen->id) && $screen->id =='edit-portfolio' )
        {
            require_once( __DIR__ . '/views/portfolio.php' );     
        }
      
    }

    public function add_page(){

        if( element_ready_get_components_option('portfolio') || element_ready_get_components_option('portfolio_carousel') ){
            add_submenu_page( 'element_ready_elements_dashboard_page', 'Portfolio', 'Portfolio',
            'manage_options', 'edit.php?post_type=portfolio');
        }
    }

    
    public function add_admin_scripts($handle){

        $screen = get_current_screen(); 
        if( isset($screen->id) && $screen->id =='edit-portfolio' && isset($screen->post_type) && $screen->post_type =='portfolio' )
        {
          wp_enqueue_style( 'element-ready-admin', esc_url(ELEMENT_READY_ROOT_CSS .'admin.css') );
        }
    }
    

}