<?php 
if ( ! defined( 'ABSPATH' ) ) { exit;}
  if( !class_exists( 'ni_custom_order_status_bulk_action' ) ) {
	class ni_custom_order_status_bulk_action {
		public function __construct(){
			
			
			add_action( 'bulk_actions-edit-shop_order',  array(&$this,'niwoo_bulk_action_order_status' ));	
			add_filter( 'handle_bulk_actions-edit-shop_order', array(&$this,'niwoo_bulk_action_edit_order_status' ), 10, 3 );
			add_action( 'admin_notices', array(&$this,'niwoo_bulk_action_admin_notices' ));	
		}
		function niwoo_bulk_action_order_status( $bulk_actions ){
			 $order_status = $this->get_custom_order_status();			 
			 foreach( $order_status as $key=>$value){
			 	$bulk_actions [$key] = $value;
			 }			 
			 return $bulk_actions;
		}
		function niwoo_bulk_action_edit_order_status( $redirect_to = '', $action = '', $post_ids = ''){
			
			// if an array with order IDs is not presented, exit the function
			if( !isset( $_REQUEST['post'] ) && !is_array( $_REQUEST['post'] ) )
				return;
				
			$order_status = $this->get_custom_order_status();
			if (array_key_exists($action, $order_status)){
				
				$processed_ids = array();
				foreach( $post_ids as $order_id ) {

					$order = new WC_Order( $order_id );
										
					$order_note = '';
					$order->update_status( $action, $order_note, true ); // 
					
					$processed_ids[] = $order_id;
				}
				return $redirect_to = add_query_arg( array(
					'niwoocos_custom_order_status_changed' => '1',
					'niwoocos_current_order_status' => $action,
					'processed_count' => count( $processed_ids ),
					'processed_ids' => implode( ',', $processed_ids ),
				), $redirect_to );
				
			}
			
			return $redirect_to;
			 
			
		}
		
		function niwoo_bulk_action_admin_notices(){
			 if ( empty( $_REQUEST['niwoocos_custom_order_status_changed'] ) ) return; // Exit

				$count = intval( $_REQUEST['processed_count'] );
				
				$current_order_status =  sanitize_text_field($_REQUEST['niwoocos_current_order_status']);
				
				printf('<div id="message" class="updated notice is-dismissable"><p>' . __('%s Order status changed to %s.', '') . '</p></div>', $count,$current_order_status);
				
		}
		function get_custom_order_status(){
			global $wpdb;
			$order_status = array();
			$query = "SELECT
						posts.post_title as post_title
						,slug.meta_value as ni_order_status_slug
						,color.meta_value as ni_order_status_color
						FROM {$wpdb->prefix}posts as posts		
					
						LEFT JOIN  {$wpdb->prefix}postmeta as slug ON slug.post_id=posts.ID 
						LEFT JOIN  {$wpdb->prefix}postmeta as color ON color.post_id=posts.ID 	
						
						WHERE 
								posts.post_type ='ni-order-status' 
								AND posts.post_status ='publish'
								AND slug.meta_key='_ni_order_status_slug'
								AND color.meta_key='_ni_order_status_color'
								
						";
			$rows = $wpdb->get_results( $query);		
			
			foreach($rows as $key=>$value){
				$order_status [$value->ni_order_status_slug] = $value->post_title;
			}			
			return $order_status;
		}
		function pretty_print($arr){
			print("<pre>");
			print_r($arr);
			print("</pre>");	
		}
	}
}
  