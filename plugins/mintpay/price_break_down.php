<?php
/**
 * @package Mintpay\price_breakdown
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

// detect conflicting plugins
$conflicting_plugins = []; //consider this as the flag
if (in_array('woo-discount-rules/woo-discount-rules.php', apply_filters('active_plugins', get_option('active_plugins')))){
	$conflicting_plugins = ['woo-discount-rules'];
}

// add jquery
add_action( 'wp_enqueue_scripts', function() {
	// register css
	wp_register_style('my_style', plugins_url('/assets/css/my_style.css', __FILE__));
    wp_enqueue_style('my_style');

	// register js
    wp_register_script('my_script', plugins_url('assets/js/my_script.js', __FILE__), array('jquery'),'1.1', true);
    wp_enqueue_script('my_script');
} );

/**
 * 
 * change price of selected variation product
 */

// method to calculate installment for simple product
function mintpay_calculate_simple_product_installment($product){
	if ($product->get_type() != 'simple') return;

	// check for conflicts with wdr
	$price = mintpay_calculate_simple_product_installment_with_wdr($product);
	return number_format($price / 3, 2, '.', ',');
}

// method to calculate installment for simple product - with woo discount rules
function mintpay_calculate_simple_product_installment_with_wdr($product){
	$price = $product->get_price(); //gets active price
	
	global $conflicting_plugins;
	if (in_array('woo-discount-rules', $conflicting_plugins, false) == false) return $price;

	$quantity = 1;
	$custom_price = 0;
	$return_details = 'discounted_price';
	$manual_request = true;
	$is_cart = false;

	$wdr = apply_filters('advanced_woo_discount_rules_get_product_discount_price_from_custom_price', 
		$price, 
		$product, 
		$quantity, 
		$custom_price, 
		$return_details, 
		$manual_request, 
		$is_cart
	);

	// wdr is not applied on product - returns active price
	if($wdr === false){
		return $price;
	}
	
	// wdr is applied on product - returns discounted price
	return $wdr;
}

// method to calculate installment for variable product
function mintpay_calculate_variable_product_installment($product){
	if ($product->get_type() != 'variable') return;

	// check for conflicts with wdr
	list($min_price, $max_price) = mintpay_calculate_variable_product_installment_with_wdr($product); //this function returns an array

	// if max price == min price return a single price
	if ($min_price === $max_price){
		return number_format($min_price / 3, 2, '.', ',');
	}

	// convert to decimals
	$min_price = number_format($min_price / 3, 2, '.', ',');
	$max_price = number_format($max_price / 3, 2, '.', ',');

	$currency_symbol = get_woocommerce_currency_symbol();

	// if not return the range
	return sprintf("%s - %s%s", $min_price, $currency_symbol, $max_price);
}

// method to calculate installment for variable product - with woo discount rules
function mintpay_calculate_variable_product_installment_with_wdr($product){
	$min_price = $product->get_variation_price('min');
	$max_price = $product->get_variation_price('max');
	
	global $conflicting_plugins;
	if (in_array('woo-discount-rules', $conflicting_plugins, false) == false) return array($min_price, $max_price);

	$quantity = 1;
	$return_details = 'all';
	$manual_request = true;
	$is_cart = false;

	// get min_price
	$wdr_min = apply_filters('advanced_woo_discount_rules_get_product_discount_price_from_custom_price',
		$min_price,
		$product, 
		$quantity, 
		$min_price, 
		$return_details, 
		$manual_request, 
		$is_cart
	);

	// if wdr is applied
	if($wdr_min !== false){
		$min_price = $wdr_min['discounted_price'];
	}

	// get max_price
	$wdr_max = apply_filters('advanced_woo_discount_rules_get_product_discount_price_from_custom_price',
		$max_price,
		$product, 
		$quantity, 
		$max_price, 
		$return_details, 
		$manual_request, 
		$is_cart
	);

	// if wdr is applied
	if($wdr_max !== false){
		$max_price = $wdr_max['discounted_price'];
	}

	return array($min_price, $max_price);
}

// method to calculate installment for a single variant - with woo discount rules
function mintpay_calculate_variant_installment_with_wdr($variant, $product){
	if ($product->get_type() !== 'variable') return 'Error: not a variable product';

	$price = $variant['display_price']; //gets active price

	global $conflicting_plugins;
	if (in_array('woo-discount-rules', $conflicting_plugins) == false) return $price;

	
	$quantity = 1;
	$custom_price = 0;
	$return_details = 'discounted_price';
	$manual_request = true;
	$is_cart = false;
	
	$wdr = apply_filters('advanced_woo_discount_rules_get_product_discount_price_from_custom_price', 
		$price, 
		$variant, 
		$quantity, 
		$custom_price, 
		$return_details, 
		$manual_request, 
		$is_cart
	);

	// wdr is not applied on product - returns active price
	if($wdr === false){
		return $price;
	}
	
	// wdr is applied on product - returns discounted price
	return $wdr;
}

// method to create the sentence for displaying price breakdown - `or 3 X/installments of $123.00 with`
/**
 * !!input formatted price!!
 */
function mintpay_create_sentence($price, $currency_symbol, $wording){
	return sprintf("or 3 %s <b>%s%s</b> with ", $wording, $currency_symbol, $price);
}

// method to display selected single variant product price
add_filter( 'woocommerce_available_variation', 'mintpay_get_variable_product_installment', 10, 3 );
function mintpay_get_variable_product_installment($variation_data, $product, $variation) {
	$mintpay_font_size = '18px';
	$mintpay_wording = 'installments of';
	$mintpay_css_classname = 'mintpay-product-price-installment-in-variation';
	$mintpay_logo_height = '19px';
	$mintpay_logo = '<img style="display: inline-flex; position: relative; cursor: pointer; height: ' . $mintpay_logo_height . '; vertical-align: middle; width: auto;" class="mintpay-logo" src="https://static.mintpay.lk/static/base/logo/logo_w120_h32.png" alt="Mintpay">';

	$currency_symbol = get_woocommerce_currency_symbol();
	
	// get variable products price
	$price = mintpay_calculate_variant_installment_with_wdr($variation_data, $product);
	$price = number_format($price / 3, 2, '.', ',');
	$sentence = mintpay_create_sentence($price, $currency_symbol, $mintpay_wording);

	if( !empty($variation_data['price_html']) ){
		$variation_data['price_html'] .= '<div style="font-size: ' . $mintpay_font_size . '; color: #8e8e8e; line-height: 20px;" class=' . $mintpay_css_classname . '>' . $sentence . $mintpay_logo . '</div><br>';
	}

	return $variation_data;
}

// single function to display monthly installment
add_filter('woocommerce_get_price_html', 'mintpay_display_price_breakdown', 9000000, 2);
function mintpay_display_price_breakdown($default_price, $product)
{
	// display mintpay price breakdown - only in frontend
	if (is_admin() && ! wp_doing_ajax()) return $default_price;

	$mintpay_css_classname = 'product-price-installments-not-in-variation';
	$mintpay_logo_height = '14px';
	$mintpay_logo = '<img style="display: inline-flex; position: relative; cursor: pointer; height: ' . $mintpay_logo_height . '; vertical-align: middle; width: auto;" class="mintpay-logo" src="https://static.mintpay.lk/static/base/logo/logo_w120_h32.png" alt="Mintpay">';
	$mintpay_font_size = '13px';
	$mintpay_wording = "X";

	// get store currency symbol
	$currency_symbol = get_woocommerce_currency_symbol();

	// get current product
	if (wc_get_product() !== null) $product = wc_get_product();

	// filter hook to display certain categories only
	$display = 1;
	$display = apply_filters('mintpay_price_breakdown_display', $display, $product);
	if ($display !== 1) return $default_price;

	// check product type : simple, variation, download, grouped, external etc.
	if ($product != null) $product_type = strtolower(substr(get_class($product), strrpos(get_class($product), "_") + 1));
	// check if product is in single page and NOT in a loop
	global $woocommerce_loop;

	if (is_product() && isset($woocommerce_loop['name']) && $woocommerce_loop['name'] == "" ) {
		$mintpay_css_classname = 'product-price-installments-not-in-variation-single';
		$mintpay_wording = "installments of";
		$mintpay_font_size = "18px";
		$mintpay_logo_height = '19px';
		$mintpay_logo = '<img style="display: inline-flex; position: relative; cursor: pointer; height: ' . $mintpay_logo_height . '; vertical-align: middle; width: auto;" class="mintpay-logo" src="https://static.mintpay.lk/static/base/logo/logo_w120_h32.png" alt="Mintpay">';
	}

	switch ($product_type) {
		case 'variable':
			$price = mintpay_calculate_variable_product_installment($product);
			$sentence = mintpay_create_sentence($price, $currency_symbol, $mintpay_wording);
			break;

		case 'simple':
			$price = mintpay_calculate_simple_product_installment($product);
			$sentence = mintpay_create_sentence($price, $currency_symbol, $mintpay_wording);
			break;
			
		default:
			return $default_price;
	}

		return $default_price . '<br><div style="font-size: ' . $mintpay_font_size . '; color: #8e8e8e; line-height: 20px;" class=' . $mintpay_css_classname . '>' . $sentence . $mintpay_logo . '</div>';
}

// method to display price breakdown in checkout page
add_action('woocommerce_review_order_before_payment', 'mintpay_display_price_breakdown_in_checkout_page');
function mintpay_display_price_breakdown_in_checkout_page(){
	// 
	$cart = WC()->cart;
	if( !$cart->is_empty() ){
		$mintpay_css_classname = 'mintpay-checkout-price-installment';
		$mintpay_font_size = "18px";
		$mintpay_logo_height = '19px';
		$mintpay_logo = '<img style="display: inline-flex; position: relative; cursor: pointer; height: ' . $mintpay_logo_height . '; vertical-align: middle; width: auto;" class="mintpay-logo" src="https://static.mintpay.lk/static/base/logo/logo_w120_h32.png" alt="Mintpay">';
		
		$currency = get_woocommerce_currency_symbol();
		$total = number_format($cart->get_total('raw') / 3, 2, '.', ',');
		
		_e('<div style="padding: 10px 0 10px 0; font-size: ' . esc_attr($mintpay_font_size) . '; color: #8e8e8e; line-height: 20px;" class=' . esc_attr($mintpay_css_classname) . '>Pay in 3 installments of ' . $currency . $total . ' with ' . $mintpay_logo . '</div>');
	}	
}

/*
 * shortcode to display price breakdown
 * 
 * */

// display price taking only product price
function mintpay_display_price_breakdown_only_with_product($product)
{
	$mintpay_css_classname = 'product-price-installments-not-in-variation';
	$mintpay_logo_height = '14px';
	$mintpay_logo = '<img style="display: inline-flex; position: relative; cursor: pointer; height: ' . $mintpay_logo_height . '; vertical-align: middle; width: auto;" class="mintpay-logo" src="https://static.mintpay.lk/static/base/logo/logo_w120_h32.png" alt="Mintpay">';
	$mintpay_font_size = '13px';
	$mintpay_wording = "X";

	// get store currency symbol
	$currency_symbol = get_woocommerce_currency_symbol();

	// get current product
	if (wc_get_product() !== null) $product = wc_get_product();

	// filter hook to display certain categories only
	$display = 1;
	$display = apply_filters('mintpay_price_breakdown_display', $display, $product);
	if ($display !== 1) return;

	// check product type : simple, variation, download, grouped, external etc.
	if ($product != null){
		$product_type = strtolower(substr(get_class($product), strrpos(get_class($product), "_") + 1));
	} else {
		// product is null, so get global product
		global $product;
		$product_type = $product->get_type();
	}

	// check if product is in single page and NOT in a loop
	global $woocommerce_loop;

	if (is_product() && isset($woocommerce_loop['name']) && $woocommerce_loop['name'] == "" ) {
		$mintpay_css_classname = 'product-price-installments-not-in-variation-single';
		$mintpay_wording = "installments of";
		$mintpay_font_size = "18px";
		$mintpay_logo_height = '19px';
		$mintpay_logo = '<img style="display: inline-flex; position: relative; cursor: pointer; height: ' . $mintpay_logo_height . '; vertical-align: middle; width: auto;" class="mintpay-logo" src="https://static.mintpay.lk/static/base/logo/logo_w120_h32.png" alt="Mintpay">';
	}

	switch ($product_type) {
		case 'variable':
			$price = mintpay_calculate_variable_product_installment($product);
			$sentence = mintpay_create_sentence($price, $currency_symbol, $mintpay_wording);
			break;

		case 'simple':
			$price = mintpay_calculate_simple_product_installment($product);
			$sentence = mintpay_create_sentence($price, $currency_symbol, $mintpay_wording);
			break;

		default:
			return "Product type not identified";
	}

		return '<div style="font-size: ' . $mintpay_font_size . '; color: #8e8e8e; line-height: 20px;" class=' . $mintpay_css_classname . '>' . $sentence . $mintpay_logo . '</div>';
}

// short-code to display price
add_shortcode( 'mintpay_price_breakdown', 'woo_product_price_shortcode' ); 
function woo_product_price_shortcode( $atts ) { 
	$atts = shortcode_atts( array( 
		'id' => null 
	), $atts, 'mintpay_price_breakdown' ); 
	global $product;
	if ( ! $product ) { 
		return 'Error retreiving Mintpay Installment: Product Not Found.';
	}
	return mintpay_display_price_breakdown_only_with_product($product);
}