<?php

/**
*   @package WooCart
*/

namespace WscInc\Base;

use WscInc\Base\BaseController;

class WooCart extends BaseController {

    public $templates;
    
    // This will register the WSC to product page
    public function register(){
        add_action('wp_head', array($this, 'load_woo_sticky_cart'));
        add_action('wp_enqueue_scripts', array($this,'woo_styles'));
    }

    // Sticky add to cart template will be loaded
    public function load_woo_sticky_cart($template){
        if(is_product()){
            $file = $this->plugin_path . 'templates/cart.php';
            if(file_exists($file)){
                load_template( $file );
            }
            return $template;
        }
        return $template;
    }

    // Default stylesheet will be loaded.
    public function woo_styles(){
        if(is_product()){
            wp_enqueue_style( 'wscstyle', $this->plugin_url . 'assets/woocart.css' );
        }
    }
}