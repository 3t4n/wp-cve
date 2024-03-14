<?php

namespace Briefcase;


class Wooconfiglt{
	
	private static $_instance = null;
   

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
	
	public function __construct() {
		// Main Woo Filters
		
			add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'menu_cart_icon_bew' ) );
			add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'mini_cart_icon_bew' ) );
	}
	
	/**
		 * Add menu cart item to the Woo fragments so it updates with AJAX
		 *
		 * @since 1.1.0
		 */
	public function menu_cart_icon_bew( $fragments ) {
				
			
			ob_start();
			$this->bew_woomenucartbew();
			$fragments['a.woo-menucart'] = ob_get_clean();

			return $fragments;
	}
	
	public function mini_cart_icon_bew( $fragments ) {
			
			global $woocommerce;
			
			ob_start(); ?>
			<div class="cart-dropdown">
			<?php woocommerce_mini_cart() ?>
			</div>
			<?php
			$fragments['div.cart-dropdown'] = ob_get_clean();

			return $fragments;
	}
	
		
	public function bew_woomenucartbew() {
		
											
			$url = WC()->cart->get_cart_url();
			$count = WC()->cart->cart_contents_count;
			// Menu Cart WooCommerce
					if( class_exists( 'WooCommerce' ) ) { ?>
										
						<a class="woo-menucart versionupdate" href=" <?php echo $url?>" title="View your shopping cart">				
						<i class=""></i> 
						<span class="woo-cart-quantity"><?php echo $count ?></span>				
						</a>
											
						<?php }
				
		
		
		}	
	 	
}
Wooconfiglt::instance();
