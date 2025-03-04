<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Are we being accessed directly ?
if(!defined('SITESEO_VERSION')) {
	exit('Hacking Attempt !');
}

if(is_plugin_active('woocommerce/woocommerce.php')){
	// Measure Purchases
	$purchasesOptions = siteseo_get_service('GoogleAnalyticsOption')->getPurchases();
	
	if (!$purchasesOptions) {
		return;
	}

	if (function_exists('is_order_received_page') && is_order_received_page()) {
		global $wp;
		$order_id = isset($wp->query_vars['order-received']) ? $wp->query_vars['order-received'] : 0;

		if (0 < $order_id && 1 != get_post_meta($order_id, '_siteseo_ga_tracked', true)) {
			$order = wc_get_order($order_id);

			//Check order status

			$status = ['completed', 'processing'];
			$status = apply_filters('siteseo_gtag_ec_status', $status);

			if (method_exists($order, 'get_status') && (in_array($order->get_status(), $status))) {
				$items_purchased = [];
				foreach ($order->get_items() as $item) {
					// Get Product object
					$_product = wc_get_product($item->get_product_id());

					if ( ! is_a($_product, 'WC_Product')) {
						continue;
					}

					// init vars
					$item_id		= $_product->get_id();
					$variation_id   = 0;
					$variation_data = null;
					$categories_js  = null;
					$categories_out = [];
					$variant_js	 = null;

					// Set data
					$items_purchased['id']	   = esc_js($item_id);
					$items_purchased['name']	 = esc_js($item->get_name());
					$items_purchased['quantity'] = (float) esc_js($item->get_quantity());
					$items_purchased['price']	= (float) esc_js($order->get_item_total($item));

					// Categories and Variations
					$categories = get_the_terms($item_id, 'product_cat');
					if ($item->get_variation_id()) {
						$variation_id   = $item->get_variation_id();
						$variation_data = wc_get_product_variation_attributes($variation_id);
					}

					// Variations
					if (is_array($variation_data) && ! empty($variation_data)) {
						$variant_js = esc_js(wc_get_formatted_variation($variation_data, true));
						$categories = get_the_terms($item_id, 'product_cat');
						$item_id	= $variation_id;

						$items_purchased['variant'] = esc_js($variant_js);
					}
					// Categories
					if ($categories) {
						foreach ($categories as $category) {
							$categories_out[] = $category->name;
						}
						$categories_js = esc_js(implode('/', $categories_out));

						$items_purchased['category'] = esc_js($categories_js);
					}

					$final[] = $items_purchased;
				}

				$global_purchase = [
					'transaction_id' => esc_js($order_id),
					'affiliation'	=> esc_js(get_bloginfo('name')),
					'value'		  => (float) esc_js($order->get_total()),
					'currency'	   => esc_js($order->get_currency()),
					'tax'			=> (float) esc_js($order->get_total_tax()),
					'shipping'	   => (float) esc_js($order->get_shipping_total()),
					'items'		  => $final,
				];

				$siteseo_google_analytics_click_event['purchase_tracking'] = 'gtag(\'event\', \'purchase\',';
				$siteseo_google_analytics_click_event['purchase_tracking'] .= wp_json_encode($global_purchase);
				$siteseo_google_analytics_click_event['purchase_tracking'] .= ');';
				$siteseo_google_analytics_click_event['purchase_tracking'] = apply_filters('siteseo_gtag_ec_purchases_ev', $siteseo_google_analytics_click_event['purchase_tracking']);

				update_post_meta($order_id, '_siteseo_ga_tracked', true);
			}
		}
	}
}

if (apply_filters('siteseo_fallback_woocommerce_analytics', false)) {
	if (is_plugin_active('woocommerce/woocommerce.php')) {
		// ADD TO CART
		if (siteseo_get_service('GoogleAnalyticsOption')->getAddToCart()) {
			// Listing page
			add_action('woocommerce_after_shop_loop_item', 'siteseo_loop_add_to_cart');
			function siteseo_loop_add_to_cart() {
				// Get current product
				global $product;

				// Set data
				$items_purchased['id'] = esc_js($product->get_id());
				$items_purchased['name'] = esc_js($product->get_title());
				$items_purchased['list_name'] = esc_js(get_the_title());
				$items_purchased['quantity'] = (float) esc_js(1);
				$items_purchased['price'] = (float) esc_js($product->get_price());

				// Extract categories
				$categories = get_the_terms($product->get_id(), 'product_cat');
				if ($categories) {
					foreach ($categories as $category) {
						$categories_out[] = $category->name;
					}
					$categories_js			   = esc_js(implode('/', $categories_out));
					$items_purchased['category'] = esc_js($categories_js);
				}

				// Echo JS
				$js = "jQuery('.ajax_add_to_cart').unbind().click( function(){
						gtag('event', 'add_to_cart', {'items': [ " . wp_json_encode($items_purchased) . " ]});
					});";

				$js = apply_filters('siteseo_gtag_ec_add_to_cart_archive_ev', $js);

				echo '<script>'.esc_html($js).'</script>';
			}

			// Single
			add_action('woocommerce_after_add_to_cart_button', 'siteseo_single_add_to_cart');
			function siteseo_single_add_to_cart() {
				// Get current product
				global $product;

				// Set data
				$items_purchased['id']		= esc_js($product->get_id());
				$items_purchased['name']	  = esc_js($product->get_title());
				$items_purchased['list_name'] = esc_js(get_the_title());
				$items_purchased['quantity']  = "$( 'input.qty' ).val() ? $( 'input.qty' ).val() : '1'";
				$items_purchased['price']	 = (float) esc_js($product->get_price());

				// Extract categories
				$categories = get_the_terms($product->get_id(), 'product_cat');
				if ($categories) {
					foreach ($categories as $category) {
						$categories_out[] = $category->name;
					}
					$categories_js			   = esc_js(implode('/', $categories_out));
					$items_purchased['category'] = esc_js($categories_js);
				}

				// Echo JS
				$js = "jQuery('.single_add_to_cart_button').click( function(){
					gtag('event', 'add_to_cart', {'items': [ " . wp_json_encode($items_purchased) . " ]});
				});";

				$js = apply_filters('siteseo_gtag_ec_add_to_cart_single_ev', $js);

				echo '<script>'.esc_html($js).'</script>';
			}
		}

		// REMOVE FROM CART
		if (siteseo_get_service('GoogleAnalyticsOption')->getRemoveFromCart()) {
			// Cart page
			add_filter('woocommerce_cart_item_remove_link', 'siteseo_cart_remove_from_cart', 10, 2);
			function siteseo_cart_remove_from_cart($sprintf, $cart_item_key) {
				// Extract cart and get current product data
				global $woocommerce;
				foreach ($woocommerce->cart->get_cart() as $key => $item) {
					if ($key == $cart_item_key) {
						$product					 = wc_get_product($item['product_id']);
						$items_purchased['quantity'] = (float) $item['quantity'];
					}
				}

				// Get current product
				if ($product) {
					// Set data
					$items_purchased['id']		= esc_js($product->get_id());
					$items_purchased['name']	  = esc_js($product->get_title());
					$items_purchased['list_name'] = esc_js(get_the_title());
					$items_purchased['price']	 = (float) esc_js($product->get_price());

					// Extract categories
					$categories = get_the_terms($product->get_id(), 'product_cat');
					if ($categories) {
						foreach ($categories as $category) {
							if (is_object($category) && property_exists($category, 'name')) {
								$categories_out[] = $category->name;
							} elseif (is_array($category) && isset($category['name'])) {
								$categories_out[] = $category['name'];
							}
						}
						$categories_js			   = esc_js(implode('/', $categories_out));
						$items_purchased['category'] = esc_js($categories_js);
					}

					// Return JS
					$sprintf .= "<script>jQuery('.product-remove .remove').unbind().click( function(){
						gtag('event', 'remove_from_cart', {'items': [ " . wp_json_encode($items_purchased) . ' ]});
					});</script>';
				}

				$sprintf = apply_filters('siteseo_gtag_ec_remove_from_cart_ev', $sprintf);

				return $sprintf;
			}
		}

		// UPDATE CART (cart / checkout pages)
		if (siteseo_get_service('GoogleAnalyticsOption')->getAddToCart() && siteseo_get_service('GoogleAnalyticsOption')->getRemoveFromCart()) {
			// Before update
			add_action('woocommerce_cart_actions', 'siteseo_before_update_cart');
			function siteseo_before_update_cart() {
				// Extract cart
				global $woocommerce;
				foreach ($woocommerce->cart->get_cart() as $key => $item) {
					$product = wc_get_product($item['product_id']);
					// Get current product
					if ($product) {
						// Set data
						$items_purchased['id'] = esc_js($product->get_id());
						$items_purchased['name'] = esc_js($product->get_title());
						$items_purchased['list_name'] = esc_js(get_the_title());
						$items_purchased['quantity'] = (float) esc_js($item['quantity']);
						$items_purchased['price'] = (float) esc_js($product->get_price());

						// Extract categories
						$categories = get_the_terms($product->get_id(), 'product_cat');
						if ($categories) {
							foreach ($categories as $category) {
								$categories_out[] = $category->name;
							}
							$categories_js = esc_js(implode('/', $categories_out));
							$items_purchased['category'] = esc_js($categories_js);
						}
					}

					$final[] = $items_purchased;
				}

				// Return JS
				$js = "jQuery('.actions .button').unbind().click( function(){
					gtag('event', 'remove_from_cart', {'items': " . wp_json_encode($final) . "});
				});";

				$js = apply_filters('siteseo_gtag_ec_remove_from_cart_checkout_ev', $js);

				echo '<script>'.esc_html($js). '</script>';
			}
		}
	}
}
