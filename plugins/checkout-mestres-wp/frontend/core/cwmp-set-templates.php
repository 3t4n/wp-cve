<?php
if(esc_html(get_option('cwmp_activate_checkout'))=="S"){
	if(!function_exists('is_checkout')){
		add_filter( 'woocommerce_locate_template', 'cwmp_woo_template_form_billing', 200, 3 );
		function cwmp_woo_template_form_billing( $template, $template_name, $template_path ) {
			if('checkout/form-billing.php' == $template_name ){  
				if(is_checkout()){
					$template = CWMP_PLUGIN_PATH . 'template/hotcart/woocommerce/checkout/form-billing.php'; 
				} 
			}
			return $template;
		}
		add_filter( 'woocommerce_locate_template', 'cwmp_woo_template', 200, 3 );
		function cwmp_woo_template( $template, $template_name, $template_path ) {
			if('checkout/form-checkout.php' == $template_name ){
				if(is_checkout()){
					$template = CWMP_PLUGIN_PATH . 'template/hotcart/woocommerce/checkout/form-checkout.php';   
				}
			}
			return $template;
		}
		add_filter( 'woocommerce_locate_template', 'cwmp_woo_template_coupon', 200, 3 );
		function cwmp_woo_template_coupon( $template, $template_name, $template_path ) {
			if('checkout/form-coupon.php' == $template_name ){         
				if(is_checkout()){
					$template = CWMP_PLUGIN_PATH . 'template/hotcart/woocommerce/checkout/form-coupon.php';   
				}
			}
			return $template;
		}
		add_filter( 'woocommerce_locate_template', 'cwmp_woo_template_login', 200, 3 );
		function cwmp_woo_template_login( $template, $template_name, $template_path ) {
			if('checkout/form-login.php' == $template_name ){         
				if(is_checkout()){
					$template = CWMP_PLUGIN_PATH . 'template/hotcart/woocommerce/checkout/form-login.php';   
				}
			}
			return $template;
		}
		add_filter( 'woocommerce_locate_template', 'cwmp_woo_template_pay', 200, 3 );
		function cwmp_woo_template_pay( $template, $template_name, $template_path ) {
			if('checkout/form-pay.php' == $template_name ){         
				if(is_checkout()){
					$template = CWMP_PLUGIN_PATH . 'template/hotcart/woocommerce/checkout/form-pay.php';   
				}
			}
			return $template;
		}
		add_filter( 'woocommerce_locate_template', 'cwmp_woo_template_shipping', 200, 3 );
		function cwmp_woo_template_shipping( $template, $template_name, $template_path ) {
			if('checkout/form-shipping.php' == $template_name ){         
				if(is_checkout()){
					$template = CWMP_PLUGIN_PATH . 'template/hotcart/woocommerce/checkout/form-shipping.php';   
				}
			}
			return $template;
		}
		add_filter( 'woocommerce_locate_template', 'cwmp_woo_template_payment', 200, 3 );
		function cwmp_woo_template_payment( $template, $template_name, $template_path ) {
			if('checkout/payment.php' == $template_name ){         
				if(is_checkout()){
					$template = CWMP_PLUGIN_PATH . 'template/hotcart/woocommerce/checkout/payment.php';   
				}
			}
			return $template;
		}
		add_filter( 'woocommerce_locate_template', 'cwmp_woo_template_payment_method', 200, 3 );
		function cwmp_woo_template_payment_method( $template, $template_name, $template_path ) {
			if('checkout/payment-method.php' == $template_name ){         
				if(is_checkout()){
					$template = CWMP_PLUGIN_PATH . 'template/hotcart/woocommerce/checkout/payment-method.php';   
				}
			}
			return $template;
		}
		add_filter( 'woocommerce_locate_template', 'cwmp_woo_template_review_order', 200, 3 );
		function cwmp_woo_template_review_order( $template, $template_name, $template_path ) {
			if('checkout/review-order.php' == $template_name ){         
				if(is_checkout()){
					$template = CWMP_PLUGIN_PATH . 'template/hotcart/woocommerce/checkout/review-order.php';   
				}
			}
			return $template;
		}
		add_filter( 'woocommerce_locate_template', 'cwmp_woo_template_terms', 200, 3 );
		function cwmp_woo_template_terms( $template, $template_name, $template_path ) {
			if('checkout/terms.php' == $template_name ){         
				if(is_checkout()){
					$template = CWMP_PLUGIN_PATH . 'template/hotcart/woocommerce/checkout/terms.php';   
				}
			}
			return $template;
		}
		add_filter( 'woocommerce_locate_template', 'cwmp_woo_template_cart_shipping_total', 200, 3 );
		function cwmp_woo_template_cart_shipping_total( $template, $template_name, $template_path ) {
			if('cart/cart-shipping.php' == $template_name ){
				if(is_checkout()){
					$template = CWMP_PLUGIN_PATH . 'template/hotcart/woocommerce/cart/cart-shipping.php';   
				}
			}
			return $template;
		}
		add_filter( 'woocommerce_locate_template', 'cwmp_woo_template_quantity_input', 200, 3 );
		function cwmp_woo_template_quantity_input( $template, $template_name, $template_path ) {
			if('global/quantity-input.php' == $template_name ){         
				if(is_checkout()){
					$template = CWMP_PLUGIN_PATH . 'template/hotcart/woocommerce/global/quantity-input.php';   
				}
			}
			return $template;
		}
		add_filter( 'woocommerce_locate_template', 'cwmp_woo_template_upsell_checkout', 200, 3 );
		function cwmp_woo_template_upsell_checkout( $template, $template_name, $template_path ) {
			if('single-product/up-sells-checkout.php' == $template_name ){         
				if(is_checkout()){
					$template = CWMP_PLUGIN_PATH . 'template/hotcart/woocommerce/single-product/up-sells-checkout.php';   
				}
			}
			return $template;
		}
	}
}


