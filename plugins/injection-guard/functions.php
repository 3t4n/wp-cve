<?php require_once('guard.php');



	

	

	//FOR QUICK DEBUGGING



	if(!function_exists('pre')){

	function pre($data){

			if(isset($_GET['debug'])){

				pree($data);

			}

		}	 

	} 	

	if(!function_exists('pree')){

	function pree($data){

				echo '<pre>';

				print_r($data);

				echo '</pre>';	

		

		}	 

	} 







	if(!function_exists('ig_start')){





		function ig_start(){	

				

				$guard_obj = new guard_wordpress;

				$guard_obj->init();

				$guard_obj->update_log();

				$ig_logs = $guard_obj->get_requests_log();

				$ig_blacklisted = $guard_obj->get_blacklisted();

				$uri = $guard_obj->wp_uri_cleaned();

				$aus = $guard_obj->available_uri_strings();

				

				

				if(isset($ig_blacklisted[$uri]))

				{

					$diff = array_intersect($ig_blacklisted[$uri], $aus);

					

					if(!empty($diff)){

						global $wp_query;

						$wp_query->set_404();

						status_header( 404 );

						get_template_part( 404 ); 

						exit();

					}

				}



		}	





	}

	



	if(!function_exists('ig_update')){

		function ig_update(){	

		

			$ret = array('status'=>true);

			if ( 
				! isset( $_POST['ig_nonce'] ) 
				|| ! wp_verify_nonce( $_POST['ig_nonce'], 'ig_nonce_action' ) 
			) {
			
			   print __('Sorry, your nonce did not verify.','injection-guard');
			   exit;
			
			} elseif(is_super_admin()) {

				$val = isset($_POST['val'])?esc_attr($_POST['val']):'';
	
				$type = isset($_POST['type'])?esc_attr($_POST['type']):'';
	
				$uri = isset($_POST['uri_index'])?esc_attr($_POST['uri_index']):'';
	
				
	
				$guard_obj = new guard_wordpress;
	
				
	
				if($type=='whitelist'){
	
					$guard_obj->update_blacklisted($val, $uri, false);
	
				}else{
	
					$guard_obj->update_blacklisted($val, $uri, true);
	
				}
	
				echo json_encode($ret);
				
			}

			exit;

		}

	}

	

	function ig_plugin_links($links) { 

	  $settings_link = '<a href="options-general.php?page=ig_settings">'.__('Settings', 'injection-guard').'</a>'; 

	  $premium_link = '<a href="https://shop.androidbubbles.com/go/" title="'.__('Go Premium', 'injection-guard').'" target="_blank">'.__('Go Premium', 'injection-guard').'</a>'; 

	  array_unshift($links, $settings_link,$premium_link); 

	  return $links; 

	}
	
	function ig_get_ip() 
	{
		$ip      = '';
		$sources = array (
			'REMOTE_ADDR',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_CLIENT_IP',
		);
	
		foreach ( $sources as $source ) {
			if ( isset ( $_SERVER[ $source ] ) )  {
				$ip = $_SERVER[ $source ];
			} elseif ( getenv( $source ) ) {
				$ip = getenv( $source );
			}
		}
	
		return $ip;
	}			
	
	function ig_user_last_login( $user_login, $user ) {
		update_user_meta( $user->ID, 'last_login', time() );
		$ips = get_user_meta( $user->ID, 'ip_logs',  true);
		$ips = is_array($ips)?$ips:array();
		$ips[] = ig_get_ip();
		$ips = array_unique($ips);
		update_user_meta( $user->ID, 'ip_logs', $ips);
		
	}
	add_action( 'wp_login', 'ig_user_last_login', 10, 2 );	
		
	function ig_get_customer_total_order($user_id=0) {
		global $wpdb;
		$customer_orders = get_posts( array(
			'numberposts' => - 1,
			'meta_key'    => '_customer_user',
			'meta_value'  => $user_id?$user_id:get_current_user_id(),
			'post_type'   => array( 'shop_order' ),
			'post_status' => array( 'wc-completed' )
		) );
		//pree($customer_orders);
		$total = 0;
		$products = array();
		if(!empty($customer_orders)){
			foreach ( $customer_orders as $customer_order ) {
				//pree($customer_order->ID);
				$order = wc_get_order( $customer_order );
				//pree($order);
				//pree($order->get_items());
				if(!empty($order) && count($order->get_items())>0){
					foreach ($order->get_items() as $item_id => $item_data) {
						$product = $item_data->get_product();
						if(!empty($product)){
							
							$product_name = $product->get_name();
							$item_quantity = $item_data->get_quantity();
							$permissions_query = $wpdb->prepare( "
									SELECT * FROM {$wpdb->prefix}woocommerce_downloadable_product_permissions
									WHERE order_id = %d ORDER BY product_id
								", $order->id );
							//pree($permissions_query);
							$download_permissions = $wpdb->get_results($permissions_query);
							//pree($download_permissions);
							$download_count = 0;
							if(!empty($download_permissions)){
								$for_download_count = current($download_permissions);
								$download_count = $for_download_count->download_count;
							}
								
							$products[] = array('qty'=>$item_quantity, 'product'=>$product_name, 'download_count'=>$download_count);
						}
					}
				}
				//pree($order);
				$total += $order->get_total();
			}
		}
	
		return array(count($customer_orders), $total, $products);
	}	

	if(!function_exists('ig_update_bulk_backlist')){

		function ig_update_bulk_backlist(){	


			$result_array = array();
			
			if ( 
				! isset( $_POST['ig_nonce'] ) 
				|| ! wp_verify_nonce( $_POST['ig_nonce'], 'ig_nonce_action' ) 
			) {
			
			   print __('Sorry, your nonce did not verify.','injection-guard');
			   exit;
			
			} elseif(is_super_admin()) {
			
			   // process form data

			  

			   $posted_data = sanitize_ig_data($_POST);
				
			   $ig_type = $posted_data['ig_type'];
			   $ig_post_obj = $posted_data['ig_post_obj'];
			   $guard_obj = new guard_wordpress;

			   if(!empty($ig_post_obj)){
				   foreach($ig_post_obj as $uri => $val_array){
					   if(!empty($val_array)){
						   foreach($val_array as $val){

							if($ig_type == 'whitelist'){
   
								$guard_obj->update_blacklisted($val, $uri, false);
				
							}else{
				
								$guard_obj->update_blacklisted($val, $uri, true);
				
							}

						   }
					   }
				   }
			   }

			   
   

			}

			// wp_send_json($result_array);
			exit;

		}

	}