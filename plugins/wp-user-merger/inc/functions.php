<?php

use Elementor\Modules\WpCli\Update;

if ( ! defined( 'ABSPATH' ) ) exit; 

	if(!function_exists('wpus_pre')){
	function wpus_pre($data){
			if(isset($_GET['debug'])){
				wpus_pree($data);
			}
		}	 
	} 	
	if(!function_exists('wpus_pree')){
	function wpus_pree($data){
				echo '<pre>';
				print_r($data);
				echo '</pre>';	
		
		}	 
	} 
	function sanitize_wpus_data( $input ) {

		if(is_array($input)){
		
			$new_input = array();
	
			foreach ( $input as $key => $val ) {
				$new_input[ $key ] = (is_array($val)?sanitize_wpus_data($val):sanitize_text_field( $val ));
			}
			
		}else{
			$new_input = sanitize_text_field($input);
			if(stripos($new_input, '@') && is_email($new_input)){
				$new_input = sanitize_email($new_input);
			}

			if(stripos($new_input, 'http') || wp_http_validate_url($new_input)){
				$new_input = esc_url($new_input);
			}
		}
		

		
		return $new_input;
	}		
	function wpus_admin_menu()
	{
		global $wpus_data;		
		$title = str_replace(array('WooCommerce', 'WordPress'), array('WC', 'WP'), $wpus_data['Name']);
		add_submenu_page('users.php', $title, $title, 'manage_options', 'wpus_merger', 'wpus_merger' );
	}
	function wpus_merger(){ 
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'wp-user-merger' ) );
		}
		global $wpdb; 
		include('wpus_settings.php');	
	}
	//add_action( 'wp_enqueue_scripts', 'wpus_enqueue_scripts' );
	add_action( 'admin_enqueue_scripts', 'wpus_enqueue_scripts' );
	
	function wpus_enqueue_scripts() 
	{
		global $wpsu_options;

		$wpsu_user_searchable = array_key_exists('user_searchable', $wpsu_options) && $wpsu_options['user_searchable'];


		$translation_array = array(

			'this_url' => admin_url( 'users.php?page=wpus_merger' ),
			'wpsu_tab' => (isset($_GET['t'])?$_GET['t']:'0'),
			'wpsu_nonce' => wp_create_nonce('wpsu_nonce_action'),
			'searching' => __('Searching users....', 'wp-user-merger'),
			'need_character' => __('At least 3 characters required', 'wp-user-merger'),
			'placeholder' => __('Search user to merge', 'wp-user-merger'),
			'user_searchable' => $wpsu_user_searchable,

		);
		
		if(is_admin()){
			if(isset($_GET['page']) && in_array($_GET['page'], array('wpus_merger'))){
				
				wp_enqueue_style('fontawesome', plugins_url('css/fontawesome.min.css', dirname(__FILE__)));
				wp_enqueue_style('wpsu-slimselect-css', plugins_url('css/slimselect.min.css', dirname(__FILE__)) );
				wp_enqueue_script('wpsu-slimselect-scripts', plugins_url('js/slimselect.min.js', dirname(__FILE__)), array( 'jquery' ), date('Ym'), true );
	
				wp_enqueue_script('magnific-popup', plugins_url('js/jquery.magnific-popup.min.js', dirname(__FILE__)), array( 'jquery' ), date('Ym'), true );
				wp_enqueue_style('magnific-popup', plugins_url('css/magnific-popup.css', dirname(__FILE__)), array(), date('Ym'));
				
				wp_enqueue_script('wpus-scripts', plugins_url('js/admin-scripts.js', dirname(__FILE__)), array( 'jquery' ), date('Ymdhi'), true );
				
	
				wp_enqueue_script('bootstrap', plugins_url('js/bootstrap.min.js', dirname(__FILE__)), array( 'jquery' ), date('Ym'), true );
				wp_enqueue_style('bootstrap', plugins_url('css/bootstrap.min.css', dirname(__FILE__)), array(), date('Ym'));
				

				wp_localize_script('wpus-scripts', 'wpsu_obj', $translation_array);


			}
			if(
					((isset($_GET['page']) && in_array($_GET['page'], array('wpus_merger'))) 
				|| 
				(isset($_GET['post_type']) && in_array($_GET['post_type'], array('shop_order'))))
			){
				wp_enqueue_style('wpus-style', plugins_url('css/admin-style.css', dirname(__FILE__)), array(), time(), 'all');
			}
		}
				
	}
		
	if(!function_exists('wpus_delete_user')){
		function wpus_delete_user($user_id) {
			$args = array (
				'numberposts' => -1,
				'post_type' => 'any',
				'author' => $user_id
			);
			// get all posts by this user: posts, pages, attachments, etc..
			$user_posts = get_posts($args);
	
			if (empty($user_posts)) return;
	
				// delete all the user posts
				foreach ($user_posts as $user_post) {
					wp_delete_post($user_post->ID, true);
				}
		}	
	}	
	
	function wpus_plugin_links($links) { 

		global $wpus_premium_link, $wpus_pro;


		$settings_link = '<a href="users.php?page=wpus_merger">'.__('Settings', 'wp-user-merger').'</a>';

		
		if($wpus_pro){
			array_unshift($links, $settings_link); 
		}else{
			 
			$wpus_premium_link = '<a href="'.esc_url($wpus_premium_link).'" title="'.__('Go Premium', 'wp-user-merger').'" target="_blank">'.__('Go Premium', 'wp-user-merger').'</a>'; 
			array_unshift($links, $settings_link, $wpus_premium_link); 
		
		}
				
		
		return $links; 
	}	

	function wpus_get_order_ids_by_user($user_id=0){
		
       
	    /*$args = array(
            'numberposts' => -1,
            'post_type' => 'any',
            'post_status' => 'any',
            'fields' => 'ids',
            'meta_query' => array(
                array(
                    'key' => '_customer_user',
                    'value' => $user_id,
                    'compare' => '='
                )
            )
        );*/
		
        //$all_posts = get_posts($args);
		$all_posts = array();
		
		if($user_id){
			global $wpdb;
			
			$postmeta_query = "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_customer_user' AND meta_value='".esc_sql($user_id)."'";
			$all_postmeta = $wpdb->get_results($postmeta_query);
			$post_ids = array();
			if(!empty($all_postmeta)){
				foreach($all_postmeta as $all_postmeta_row){
					$post_ids[] = $all_postmeta_row->post_id;
				}
			}
			
			$posts_query = "SELECT ID, post_type FROM $wpdb->posts WHERE post_author='".esc_sql($user_id)."'";
			if(!empty($post_ids)){
				$posts_query .= ' OR ID IN('.implode(',', $post_ids).')';
			}
			//wpus_pree($posts_query);
			$all_posts = $wpdb->get_results($posts_query);
		}
		
		//wpus_pree($all_posts);exit;
		return $all_posts;

    }

	function wpus_reassign_shop_orders($order_ids=array(), $reassign_user=0){
        global $wpdb;

		if(empty($order_ids)){ return; }
		if(!$reassign_user){ return; }


        if(!empty($order_ids)){
			
			$order_ids_only = array();
			foreach($order_ids as $order_id_obj){
				$order_ids_only[] = $order_id_obj->ID;
			}

            $order_ids_str = implode(', ', $order_ids_only);
            $customer_query = "UPDATE $wpdb->postmeta SET `meta_value` = '".esc_sql($reassign_user)."' WHERE `meta_key` = '_customer_user' AND `post_id` IN ($order_ids_str)";            
			$post_author_query = "UPDATE $wpdb->posts SET `post_author` = '".esc_sql($reassign_user)."' WHERE `ID` IN ($order_ids_str)";
			//wpus_pree($customer_query);wpus_pree($post_author_query);
            $wpdb->query($customer_query);            
			$wpdb->query($post_author_query);
			//wpus_pree($reassign_user);
			$reassign_user_obj = new WP_User($reassign_user);
			//wpus_pree($reassign_user_obj);
			if(is_object($reassign_user_obj)){
				$reassign_user_email = trim($reassign_user_obj->data->user_email);
				if($reassign_user_email!=''){
					$email_query = "UPDATE $wpdb->postmeta SET `meta_value` = '".esc_sql($reassign_user_email)."' WHERE `meta_key` = '_billing_email' AND `post_id` IN ($order_ids_str)";
					//wpus_pree($email_query);
					$wpdb->query($email_query);
				}
			}

        }//exit;

    }	


	if(!function_exists('wpus_check_billing_address')){
		function wpus_check_billing_address($customer_billing){


			return (!empty($customer_billing) && isset($customer_billing['first_name']) && $customer_billing['first_name'] && isset($customer_billing['email']) && $customer_billing['email']);


		}

	}

	if(!function_exists('wpus_set_orders_billing_shipping')){
		function wpus_set_orders_billing_shipping($order_ids_to_assign, $customer_billing, $customer_shipping){

			if(!empty($order_ids_to_assign)){

				
				foreach($order_ids_to_assign as $order_obj){
					
					if($order_obj->post_type!='shop_order'){ continue; }
					
					$order_id = $order_obj->ID;
					
					if(wpus_check_billing_address($customer_billing)){

						$new_order = new WC_Order($order_id);

						foreach ($customer_billing as $billing_key => $billing_val) {
							# code...
							
							$function_name = 'set_billing_'.$billing_key;
							$new_order->$function_name($billing_val);
							
						}

						if(!empty($customer_shipping)){

							foreach ($customer_shipping as $shipping_key => $shipping_val) {
								# code...
								
								$function_name = 'set_shipping_'.$shipping_key;
								$new_order->$function_name($shipping_val);
								
							}

						}

						$new_order->save();
					}

				}


			}


		}
	}


	if(!function_exists('wpus_reassign_order_billing_shipping')){

		function wpus_reassign_order_billing_shipping($order_ids_to_assign, $reasign_user_id){

				if(!empty($order_ids_to_assign) && $reasign_user_id && class_exists('WC_Customer')){

					$wc_customer = new WC_Customer($reasign_user_id);
					$customer_billing = $wc_customer->get_billing();
					$customer_shipping = $wc_customer->get_shipping();
					wpus_set_orders_billing_shipping($order_ids_to_assign, $customer_billing, $customer_shipping);	

				}

		}

	}

	add_action('wp_ajax_wpsu_update_options', 'wpsu_update_options');

	if(!function_exists('wpsu_update_options')){
		function wpsu_update_options(){

			$params = array();
			parse_str($_POST['wpsu_fields'], $params);		
			$params = sanitize_wpus_data($params);

			$result_array = array(
				'status' => false,
			);


			if(!isset($params['wpsu_nonce']) || !wp_verify_nonce($params['wpsu_nonce'], 'wpsu_nonce_action')){
				_e('Sorry, your nonce did not verify.', 'wp-user-merger');
				exit;
			}else{

				$wpsu_options = (array_key_exists('wpsu_options', $params) ? $params['wpsu_options'] : array()); 
				$result_array['status'] = update_option('wpsu_options', $wpsu_options);

			}


			wp_send_json($result_array);

		}
	}

	add_action('wp_ajax_wpsu_get_users_list', 'wpsu_get_users_list');

	if(!function_exists('wpsu_get_users_list')){
		function wpsu_get_users_list(){

			$result = array('status' => false);

			
			if(!empty($_POST) && isset($_POST['wpsu_user_search_string'])){

				if(!isset($_POST['wpsu_nonce']) || !wp_verify_nonce($_POST['wpsu_nonce'], 'wpsu_nonce_action')){

					_e('Sorry, your nonce did not verify.', 'wp-user-merger');

				}else{			
						
					global $wpdb;

					$search_query_string = array_key_exists('wpsu_user_search_string', $_POST)?sanitize_wpus_data($_POST['wpsu_user_search_string']):'';
					$q = 'SELECT 
								ID 
						FROM 
							'.$wpdb->users.' 
						WHERE 
								user_login LIKE "%'.$search_query_string.'%" 
							OR
								user_nicename LIKE "%'.$search_query_string.'%" 
							OR
								user_email LIKE "%'.$search_query_string.'%" 
							OR
								user_url LIKE "%'.$search_query_string.'%" 
							OR
								display_name LIKE "%'.$search_query_string.'%" 
					';
					
					$result['query_1'] = $q;
					$users = array();

					
					$user_ids = $wpdb->get_results($q);
					if(!empty($user_ids)){
						$data = array();
						$user_ids_arr = array();
						
						foreach($user_ids as $user_id_obj){
							$user_ids_arr[] = $user_id_obj->ID;
						}
						if(empty($user_ids_arr)){
							$q = 'SELECT 
										user_id 
								FROM 
									'.$wpdb->usermeta.' 
								WHERE 
										meta_value LIKE "%'.$search_query_string.'%" 
									AND
										user_id NOT IN ('.implode(', ', $user_ids_arr).')
							';
							
							$result['query_2'] = $q;
							$user_ids = $wpdb->get_results($q);
							foreach($user_ids as $user_id_obj){
								$user_ids_arr[] = $user_id_obj->user_id;
							}
						}
						$args = array(
							'blog_id'      => $GLOBALS['blog_id'],
							'role'         => '',
							'role__in'     => array(),
							'role__not_in' => array(),
							'meta_key'     => '',
							'meta_value'   => '',
							'meta_compare' => '',
							'meta_query'   => array(),
							'date_query'   => array(),        
							'include'      => $user_ids_arr,
							'exclude'      => array(),
							'orderby'      => 'login',
							'order'        => 'ASC',
							'offset'       => '',
							'search'       => '',
							'number'       => '',
							'count_total'  => false,
							'fields'       => 'all',
							'who'          => '',
						 ); 
						$users = get_users( $args );
						foreach($users as $index => $user){
							
							$data[] = array(
								'text' => $user->data->user_email.' ('.$user->data->display_name.' - ID: '.$user->data->ID.')',
								'value' => $user->data->ID,
							);

						}

						

						$result['status'] = true;
						$result['data'] = $data;

					}					

				}

			}


			wp_send_json($result);

		}
	}
	
	add_action('wp_ajax_wpsu_get_user_assets', 'wpsu_get_user_assets');
	
	if(!function_exists('wpsu_get_user_assets')){
		function wpsu_get_user_assets(){
			$result = array('status' => false, 'msg'=>'', 'total'=>0);

			
			if(!empty($_POST) && isset($_POST['wpsu_user_id'])){

				if(!isset($_POST['wpsu_nonce']) || !wp_verify_nonce($_POST['wpsu_nonce'], 'wpsu_nonce_action')){

					$result['msg'] = __('Sorry, your nonce did not verify.', 'wp-user-merger');

				}else{			
						
					global $wpdb;
					$wpsu_user_id =  array_key_exists('wpsu_user_id', $_POST)?sanitize_wpus_data($_POST['wpsu_user_id']):'';
					$wpsu_user_id = (is_numeric($wpsu_user_id)?$wpsu_user_id:0);
					
					if($wpsu_user_id){
					
						$q = 'SELECT 
									p.post_type, 
									COUNT(*) as total FROM '.$wpdb->posts.' p, 
									'.$wpdb->users.' u 
							WHERE 
									p.post_author = u.ID 
								AND 
									p.post_author='.esc_sql($wpsu_user_id).' 
							GROUP BY 
								p.post_type 
							ORDER BY 
								total 
							DESC';
						//wpus_pree($q);exit;
						$result['query_1'] = $q;
						
						$post_types = $wpdb->get_results($q);
						if(!empty($post_types)){
							$result['status'] = true;
							$result['data'] = array();
							foreach($post_types as $post_type){
								$result['data'][$wpdb->posts.' > '.$post_type->post_type] = $post_type->total;
							}
							//wpus_pree($result);exit;
							$q = 'SELECT 
										um.meta_key, 
										um.meta_value									
									FROM 
										'.$wpdb->usermeta.' um
								WHERE 
										um.user_id = '.esc_sql($wpsu_user_id).' 
								ORDER BY 
									um.meta_key 
								DESC';
							
							$result['query_2'] = $q;
							
							$meta_data = $wpdb->get_results($q);
							if(!empty($meta_data)){
								foreach($meta_data as $metadata){
									$result['data'][$metadata->meta_key] = $metadata->meta_value;
								}
							}
								
						}
						
						$result['total'] = ((isset($result['data']) && (is_array($result['data']) || is_object($result['data'])))?count($result['data']):0);
						
					}
				}
			}
			
			wp_send_json($result);
		}
	}
	
	add_action('admin_init', 'wpum_admin_init');
	
	if(!function_exists('wpum_admin_init')){
		function wpum_admin_init(){
			$file = basename($_SERVER['SCRIPT_FILENAME']);
			switch($file){
				case 'user-edit.php':
					if($_GET['user_id'] && $_GET['user_id']>0){
						global $wpdb;
						
						$user_id = sanitize_wpus_data($_GET['user_id']);
						$user_id = (is_numeric($user_id)?$user_id:0);
						
						$general_meta_keys = array(
													'_learndash_woocommerce_enrolled_courses_access_counter',
													'learndash_last_known_page',
													'_sfwd-course_progress',
													'_sfwd-quizzes',
													'_groups_buckets',
													'_groups_product_groups'
											);
						$course_meta_keys = array(
													'learndash_last_known_course_',
													'learndash_group_users_',
													'learndash_course_expired_',
													'course_completed_'
											);
						
						if($user_id){
											
							if(!empty($general_meta_keys)){
								foreach($general_meta_keys as $gkey){
									$gval = get_user_meta($user_id, $gkey, true);
									wpus_pre('<h1>'.$gkey.'</h1>');wpus_pre($gval);
								}							
							}
												
							if(!empty($course_meta_keys)){
								foreach($course_meta_keys as $ckey){
									$cq = "SELECT meta_key, meta_value FROM $wpdb->usermeta WHERE user_id='".esc_sql($user_id)."' AND meta_key LIKE '$ckey%'";
									wpus_pre($cq);
									$cvals = $wpdb->get_results($cq);
									
									wpus_pre('<h1>'.$ckey.'</h1>');
									
									if(!empty($cvals)){
										foreach($cvals as $cval){
											$meta_key = $cval->meta_key;
											$meta_key = explode('_', $meta_key);
											$meta_key = end($meta_key);
											wpus_pre('<h2>'.$meta_key.'</h2>');
											wpus_pre($cval);
										}
									}
								}							
							}		
							
						}
												
					}
				break;
			}
		}
	}
	if(!function_exists('wpus_save_merged_data')){
    
        function wpus_save_merged_data($user_id, $delete_user_id, $post_array){
    
            $deleted_user = new WP_User($delete_user_id);
            $merged_user = new WP_User($user_id);

   
            $deleted_user = serialize($deleted_user);
            $merged_user = serialize($merged_user);
    
            if(in_array($delete_user_id,$post_array)){
                $reasign_user_id = $user_id;
            }
    
    
            /*$args = array(
    
                'author' => $reasign_user_id,
                'fields' => 'ids',
                'numberposts' => -1,
                'post_type' => 'any',
            );*/
    
    
            $merged_array = array(
    
                'deleted_user' =>  maybe_serialize($deleted_user),
                'merged_user' => maybe_serialize($merged_user),
                'deleted_user_meta' =>  maybe_serialize(get_user_meta($delete_user_id)),
                'merged_user_meta' => maybe_serialize(get_user_meta($user_id)),
                'deleted_user_id' => $delete_user_id,
                'merged_user_id' => $user_id,
                'posts_array' => array(),
                'shop_order' => array(),
                'post_deleted' => false,
    
            );
    
            if($reasign_user_id != null){
                
                $merged_array['shop_order'] = wpus_get_order_ids_by_user($delete_user_id);
               // $merged_array['posts_array'] = get_posts($args);
            }
            else {
                $merged_array['post_deleted'] = true;
            }
    
            update_user_meta($user_id, '_user_merged_array', $merged_array);
    
        }
    }
	
	function wpus_before_delete_user_callback($delete_id=0, $merged_id=0){
		
	}
	add_action('wpus_before_delete_user', 'wpus_before_delete_user_callback', 10, 2);