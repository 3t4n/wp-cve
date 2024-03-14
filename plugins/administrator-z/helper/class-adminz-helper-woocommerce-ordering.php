<?php 
namespace Adminz\Helper;

class ADMINZ_Helper_Woocommerce_Ordering{
	function __construct($option) {
		if(isset($option['percent_amount']) and $option['percent_amount'] == 'on'){
			// // Register custom ordering
			add_filter( 'woocommerce_get_catalog_ordering_args', [$this,'zadmin_add_postmeta_ordering_args'] );
			add_filter( 'woocommerce_product_query_meta_query', [$this,'zadmin_woocommerce_product_query_meta_query'] );
			// Add to ordering form
			add_filter( 'woocommerce_default_catalog_orderby_options', [$this,'zadmin_add_new_postmeta_orderby'] );
			add_filter( 'woocommerce_catalog_orderby', [$this,'zadmin_add_new_postmeta_orderby'] );
			// add product meta
			add_action('woocommerce_update_product', [$this,'woo_calc_my_discount']);
		}

		if(isset($option['remove_default']) and $option['remove_default'] == 'on'){
			add_filter( 'woocommerce_default_catalog_orderby_options', [$this,'remove_default'] );
			add_filter( 'woocommerce_catalog_orderby', [$this,'remove_default'] );
		}
		if(isset($option['remove_popular']) and $option['remove_popular'] == 'on'){
			add_filter( 'woocommerce_default_catalog_orderby_options', [$this,'remove_popular'] );
			add_filter( 'woocommerce_catalog_orderby', [$this,'remove_popular'] );
		}
		if(isset($option['remove_rate']) and $option['remove_rate'] == 'on'){
			add_filter( 'woocommerce_default_catalog_orderby_options', [$this,'remove_rate'] );
			add_filter( 'woocommerce_catalog_orderby', [$this,'remove_rate'] );
		}
		if(isset($option['remove_date']) and $option['remove_date'] == 'on'){
			add_filter( 'woocommerce_default_catalog_orderby_options', [$this,'remove_date'] );
			add_filter( 'woocommerce_catalog_orderby', [$this,'remove_date'] );
		}
		if(isset($option['remove_price']) and $option['remove_price'] == 'on'){
			add_filter( 'woocommerce_default_catalog_orderby_options', [$this,'remove_price'] );
			add_filter( 'woocommerce_catalog_orderby', [$this,'remove_price'] );
		}
		if(isset($option['remove_price_desc']) and $option['remove_price_desc'] == 'on'){
			add_filter( 'woocommerce_default_catalog_orderby_options', [$this,'remove_price_desc'] );
			add_filter( 'woocommerce_catalog_orderby', [$this,'remove_price_desc'] );
		}
		
	}		
	function zadmin_woocommerce_product_query_meta_query($meta_query){
		if(isset($_GET['orderby']) and $_GET['orderby'] == '__discount_amount'){
       		if(!isset($meta_query['relation'])){
				$meta_query['relation'] = 'AND';
			}
			$meta_query[] = [
				'key' => '__discount_amount',
				'compare' => 'EXISTS'
			];
			$meta_query[] = [
				'key' => '__discount_amount',
				'compare' => '!=',
				'value' => ''
			];
       	}
		return $meta_query;
	}	
	function zadmin_add_postmeta_ordering_args( $args ) { 
       	if(isset($_GET['orderby']) and $_GET['orderby'] == '__discount_amount'){
       		$args['orderby'] = 'meta_value_num';
       		$args['order'] = 'desc';
           	$args['meta_key'] = '__discount_amount';
       	}
	 	return $args;
	}
	function zadmin_add_new_postmeta_orderby( $sortby ) {
		$sortby['__discount_amount'] = __("Percentage discount",'administrator-z');				
		return $sortby;
	}
	function flatsome_get_percent( $product ) {
		$post_id = $product->get_id();
		$return = false;

		if ( $product->is_type( 'simple' ) || $product->is_type( 'external' ) || $product->is_type( 'variation' ) ) {
			$regular_price  = $product->get_regular_price();
			$sale_price     = $product->get_sale_price();
			if(isset($_POST['_sale_price']) and $_POST['_sale_price'] == ''){
				// no thing
			}else{
				$return = round( ( ( floatval( $regular_price ) - floatval( $sale_price ) ) / floatval( $regular_price ) ) * 100 );
			}
		} elseif ( $product->is_type( 'variable' ) ) {
			if(isset($_POST['variable_sale_price'])){
				$available_variations = $product->get_available_variations();
				$list_sale_percent = [];		

				for ( $i = 0; $i < count( $available_variations ); ++ $i ) {
					if(isset($_POST['variable_sale_price'][$i])){
						$regular_price = $_POST['variable_regular_price'][$i];
						$sale_price    = $_POST['variable_sale_price'][$i];
						if($sale_price){
							$list_sale_percent[] = round( ( ( floatval( $regular_price ) - floatval( $sale_price ) ) / floatval( $regular_price ) ) * 100 );
						}	
					}
				}
				if(!empty($list_sale_percent)){
					$return = max($list_sale_percent);
				}				
			}else{
				return get_post_meta($post_id,'__discount_amount','true');
			}
		}
		return $return;
	}
	function woo_calc_my_discount( $product_id ) {
		$_product = wc_get_product( $product_id );
		$discount = $this->flatsome_get_percent($_product);
		if($discount){			
			update_post_meta( $product_id, '__discount_amount', $discount );	
		}else{
			delete_post_meta( $product_id, '__discount_amount', "");
		}		
	}
	function remove_default($sortby){
		if(isset($sortby['menu_order'])){
			unset($sortby['menu_order']);
		}
		return $sortby;
	}
	function remove_popular($sortby){
		if(isset($sortby['popularity'])){
			unset($sortby['popularity']);
		}
		return $sortby;
	}
	function remove_rate($sortby){
		if(isset($sortby['rating'])){
			unset($sortby['rating']);
		}
		return $sortby;
	}
	function remove_date($sortby){
		if(isset($sortby['date'])){
			unset($sortby['date']);
		}
		return $sortby;
	}
	function remove_price($sortby){
		if(isset($sortby['price'])){
			unset($sortby['price']);
		}
		return $sortby;
	}
	function remove_price_desc($sortby){
		if(isset($sortby['price-desc'])){
			unset($sortby['price-desc']);
		}
		return $sortby;
	}
}
