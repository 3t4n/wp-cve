<?php
/**
 * Shipping Transdirect Call Product Sync API
 *
 * @author      Transdirect
 * @version     7.7.3
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Sync product to user's transdirect account
 *
 * To view product, check in api details in member area of transdirect
 */
class product_sync {

	/**
	 * Send all product to send_to_api function once in hour 
	 *
	 */
	public function sync_all_product() {
		
		$args = array(
		    'post_type' => 'product',
		    'posts_per_page' => -1
		 	);
		$query = new WP_Query( $args );
		$products = $query->get_posts();
		$data = array();
		if ( $query->have_posts() ): 
		   	$data = $this->get_product_data($products);
		endif; 
		wp_reset_postdata();
		
		if(!empty($data)){
			$this->send_to_api($data);
		}
	}

	/**
	 * Send updated product to send_to_api function once any product updated. 
	 *
	 */
	public function sync_updated_product($post_id) {
		$args = array(
			'p'	=> $post_id,
		    'post_type' => 'product',
		 	);
		$query = new WP_Query( $args );
		$products = $query->get_posts();
		$data = array();
		if ( $query->have_posts() ): 
		   	$data = $this->get_product_data($products);
		endif;

		if(!empty($data)){
			$this->send_to_api($data);
		}
	}

	/**
	 * Send Product directly to send_to_api is product data is more then 10 or return product data array.
	 *
	 */
	public function get_product_data($products){
		$data = array();
	   	foreach ($products as $product) {

	   	    $description = strip_tags($product->post_content);
	   	    $description = str_replace(array("\n", "\r","\t"), '', $description);

	   	    $title = strip_tags(get_the_title($product->ID));
	   	    $title = str_replace(array("\n", "\r","\t"), '', $title);

	   	    $product_s = wc_get_product( $product->ID );
	   	    if ($product_s->product_type == 'variable' || $product_s->product_type == 'variation') {
	   	        $variations = $product_s->get_available_variations();
	   	        foreach ($variations as $key => $variation) {
	   	    	    if(isset($variation['sku']) && !empty($variation['sku'])){
	   	        		$tempTitle = '';
	   	              	$tempTitle = $title . '-'.implode('-', $variation['attributes']);
		   	            $data['products'][] = [
		   	                "product_sku" => $variation['sku'],
		   	                "title" => $tempTitle,
		   	                "description" => isset($variation['variation_description'])?strip_tags($variation['variation_description']):'',
		   	            ];
	   	            }
	   	            if(count($data['products']) >= 10){
		   	            $this->send_to_api($data);
		   	            $data = array();
	   	            }
	   	        }
	   	    }else{
	   	        if(get_post_meta($product->ID, '_sku', true)) :
	   	          	$data['products'][] = [
		   	            "product_sku" => get_post_meta($product->ID, '_sku', true),
		   	            "title" => htmlspecialchars_decode($title),
		   	            "description" => htmlspecialchars_decode($description),
	   	          	];
	   	        endif;
	   	        if(count($data['products']) >= 10){
	   	          	$this->send_to_api($data);
	   	          	$data = array();
	   	        }
	   	    }
		}
		return $data; 
	}

	/**
	 * Send product to transdirect api. 
	 *
	 * To view product, check in api details in member area of transdirect 
	 */
	public function send_to_api($products) {

		$api_details = td_getSyncSettingsDetails(true);
		if(isset($api_details->multiwarehouse_enabled) && $api_details->multiwarehouse_enabled != '' && $api_details->multiwarehouse_enabled == 'on') {
			$apiKey = td_get_auth_api_key();
			$args     = td_request_method_headers($apiKey, $products, 'POST');

			$link     = "https://www.transdirect.com.au/api/products";
			$response = wp_remote_retrieve_body(wp_remote_post($link, $args));
			$response = json_decode($response);
		} else {
			$this->td_start_product_cron();
		}

	}

	/**
	 * Activate or Diactivate cron based on member area setting. 
	 *
	 */
	public function td_start_product_cron()
	{
		$api_details = td_getSyncSettingsDetails(true);

		if(isset($api_details->multiwarehouse_enabled) && $api_details->multiwarehouse_enabled != '' && $api_details->multiwarehouse_enabled == 'on') {
			if(!wp_get_schedule('myProductSyncCronjob')){
			    wp_schedule_event( time(), '24hours', 'myProductSyncCronjob' ); 
			    $this->sync_all_product();  
			}
		} else {
			if(wp_get_schedule('myProductSyncCronjob')){
			    wp_clear_scheduled_hook('myProductSyncCronjob');
			}
		}
	}

}