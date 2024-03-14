<?php
namespace Shop_Ready\extension\templates\hooks\cart;
use Shop_Ready\base\Page_Layout as Page_Layout;
use Shop_Ready\base\View;
use Elementor\Plugin;
use Elementor\Core\Base\Document;
use Elementor\Utils;
use Shop_Ready\helpers\classes\Elementor_Helper as WReady_Helper;
use Automattic\Jetpack\Constants;
/*
* WooCommerece Checkout Template
* woocommerce->settings->advanced->page setup  
*/

Class Layout extends Page_Layout{

    public $name = null;
    public function register(){
    add_action('init',[$this,'enable_cart_page']); 
    add_action( 'wp_enqueue_scripts', [$this,'remove_default_stylesheet'], 20 );
    add_action( 'admin_bar_menu', [ $this, 'add_menu_in_admin_bar' ], 999 );
   }
   public function enable_cart_page(){

        if( shop_ready_is_elementor_mode() ){
          Constants::set_constant('WOOCOMMERCE_CART',true);
        }
   
   }
   public function add_menu_in_admin_bar( $wp_admin_bar ) {
   
    $document = Plugin::$instance->documents->get( get_the_ID() );
    $cart = shop_ready_templates_config()->get('cart');
    if(isset($cart['id']) && is_numeric($cart['id'])){
    if( $document ){

        $url = $document->get_edit_url();
        $final_url = add_query_arg( [
            'sr_tpl'=>'cart',
            'post' => $cart['id']
            ] , $url) ;
    
        $args = array(
            'id' => 'sready-cart',
            'title' => 'Edit Cart Template', 
            'href' => $final_url,
            'meta' => array(
                'class' => 'sr-cart', 
                'title' => 'Cart'
                )
        );

        $wp_admin_bar->add_node($args);

        }
    }
    return $wp_admin_bar;
  }
   public function remove_default_stylesheet(){
   
        if( !is_cart() ){
            return;
        }

        if( $this->is_tpl_active('cart') && $this->preset_tpl('cart') ){
          
            return;  
        }
    

   }


}
