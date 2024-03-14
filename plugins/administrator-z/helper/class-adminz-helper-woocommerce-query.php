<?php 
namespace Adminz\Helper;
use Adminz\Admin\Adminz as Adminz;
use Adminz\Admin\ADMINZ_Woocommerce as ADMINZ_Woocommerce;
use Adminz\Admin\ADMINZ_Flatsome as ADMINZ_Flatsome;

class ADMINZ_Helper_Woocommerce_Query{
	function __construct() {		
		if(!class_exists( 'WooCommerce' ) ) return;
		add_filter( 'woocommerce_redirect_single_search_result', '__return_false' );
		add_filter('woocommerce_product_query_tax_query',[$this,'woocommerce_product_query_tax_query']);
		add_filter('woocommerce_product_query_meta_query',[$this,'woocommerce_product_query_meta_query']);
		
	}
	
	function woocommerce_product_query_tax_query($r){
		$tax_arr = ADMINZ_Woocommerce::get_arr_tax();
		if(!empty($tax_arr) and is_array($tax_arr) and !empty($_GET) and is_array($_GET)){

		}
		return $r;
	}

	function woocommerce_product_query_meta_query($meta_query){
		$key_arr = ADMINZ_Woocommerce::get_arr_meta_key('product');		
		// echo "<pre>";print_r($key_arr);echo "</pre>";die;
		if(!empty($key_arr) and is_array($key_arr) and !empty($_GET) and is_array($_GET)){
			// echo "<pre>";print_r($_GET);echo "</pre>";die;
			foreach ($_GET as $key => $value) {
				if(array_key_exists($key, $key_arr) and $_GET[$key]){

					$value = (array)$value;// đảm bảo value là 1 mảng

					if(!isset($meta_query['relation'])){
						$meta_query['relation'] = 'AND';
					}
					
					// Chỉ lấy product có set meta					
					$meta_query[] = [
						'key' => $key,
						'compare' => 'EXISTS'
					];

					// truyền mảng $value vào meta query 
					$meta_query[] = [
						'key' => $key,
						'compare' => 'IN',
						'value' => $value
					];
				}
			}
		}
		
		return $meta_query;
	}
}