<?php

namespace Element_Ready\Base\CPT;

use Element_Ready\Api\Callbacks\Custom_Taxonomy;

class Portfolio_Category extends Custom_Taxonomy
{
    public $name         = '';
    public $menu         = 'portfolio_category';
    public $textdomain   = '';
    public $posts        = array();
    public $public_quary = false;
    public $slug         = 'portfolio_category';
    public $search       = true;

	public function register() {
        $this->name = esc_html__('Portfolio Categories', 'element-ready-lite');
    	add_action( 'init', array( $this, 'create_taxonomy' ) );
    }

    public function create_taxonomy(){
        $this->init('portfolio_category', esc_html__('Portfolio Category','element-ready-lite'), esc_html__('Portfolio Category','element-ready-lite'), 'portfolio');
        $this->register_taxonomy();
    }
    
}