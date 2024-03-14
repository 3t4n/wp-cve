<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_ProductOptions_Controller_Product {


	public function __construct() {
    add_action('woocommerce_before_add_to_cart_button', array($this, 'display_options_on_product_page'));								  				
	}


	public function display_options_on_product_page() { 
    include_once(Pektsekye_PO()->getPluginPath() . 'Block/Product/Options.php');
    $block = new Pektsekye_ProductOptions_Block_Product_Options();
  
    echo $block->toHtml();
  }
  

}
