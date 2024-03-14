<?php

namespace Element_Ready\Base\CPT;
use Element_Ready\Api\Callbacks\Custom_Post;
/**
 * @package  Element Ready
 */
class Portfolio extends Custom_Post
{
    
    public $name         = '';
    public $menu         = 'Portfolio';
    public $textdomain   = '';
    public $posts        = array();
    public $public_quary = true;
    public $slug         = 'portfolio';
    public $search       = true;

    public function element_ready_get_components_option($key = false){
        $this->name = esc_html__('Portfolio','element-ready-lite');
        $option = get_option('element_ready_components');
       
        if($option == false){
            return false;
        }
        
        return isset($option[$key]) && $option[$key] == 'on' ?true:false;
    }
    
    

	public function register() {

        if( $this->element_ready_get_components_option('portfolio') || $this->element_ready_get_components_option('portfolio_carousel') ){
            $this->textdomain = 'element-ready-lite';
            $this->posts      = array();
            add_action( 'init', array( $this, 'create_post_type' ) );
        }
        
    }

    public function create_post_type(){

        $this->init( 'portfolio', $this->name, $this->menu, array( 'menu_icon' => 'dashicons-portfolio',
            'supports'            => array( 'title','thumbnail','editor','revisions','page-attributes' ),
            'rewrite'             => array( 'slug' => $this->slug ),
            'exclude_from_search' => $this->search,
            'has_archive'         => false,                                               // Set to false hides Archive Pages
            'publicly_queryable'  => $this->public_quary,
            'show_in_menu'        => false
        )
       );
       $this->register_custom_post();
    }

}