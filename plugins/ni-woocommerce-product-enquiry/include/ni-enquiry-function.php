<?php 
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'ni_enquiry_function' ) ) :
	class ni_enquiry_function{ 
		function __construct(){
		}
		function get_product_info($product_id=NULL,$product_type=NULL){
			
			//$data= $this->get_query($product_id,$product_type);
			$product_data = $this->get_all_post_meta($product_id);
			//$this->print_array();
			return $product_data;
		}
		function get_query($product_id=NULL,$product_type=NULL){
			global $wpdb;
			$query = "";
			
			$query .= " SELECT  posts.post_title as product_name
								,price.meta_value as price
								
								FROM {$wpdb->prefix}posts as posts ";
			
			$query .= "  LEFT JOIN  {$wpdb->prefix}postmeta as price ON price.post_id	=	posts.ID ";
			$query .= "  LEFT JOIN  {$wpdb->prefix}postmeta as sku ON sku.post_id	=	posts.ID ";
			
			$query .= " posts.post_type ='product'";
			
			$sql .= " AND price.meta_key ='_price'";
			
			
			
			$results = $wpdb->get_results( $query);
			
			return $results ;	
		}
		function get_all_post_meta($order_id,$is_product = false){
			$order_meta	= get_post_meta($order_id);
			
			$order_meta_new = array();
			if($is_product){
				foreach($order_meta as $omkey => $omvalue){
					$order_meta_new[$omkey] = $omvalue[0];
				}
			}else{
				foreach($order_meta as $omkey => $omvalue){
					$omkey = ltrim($omkey, "_");
					$order_meta_new[$omkey] = $omvalue[0];
				}
			}
			return $order_meta_new;
		}
		function get_product_category_by_id( $product_id ) {
			$cat = "";
			$terms = get_the_terms( $product_id , 'product_cat' );
			if ($terms) {
				foreach($terms  as $k=>$v){
					if (strlen($cat)==0)
						$cat = $v->name;
					else
						$cat .= ",".  $v->name;	
				}
			}
			return $cat;
		}
		
		function ni_send_email2($to, $subject=NULL,$message=NULL,$cc=NULL,$from_email=NULL){
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type:text/html;charset=iso-8859-1' . "\r\n";
			if ($from_email)		
			$headers .= "From: {$from_email}" . "\r\n";	
			if ($cc)	
			$headers .= "Cc: {$cc}" . "\r\n";	
			
			if(@mail($to,$subject,$message,$headers))
				$status = "SUCCESS";
			else
				$status = "FAIL";
			return 	$status;	
		}
		function set_enquiry_count(){
			
			$today_date = date_i18n("Y-m-d");
			
			$count_settings = get_option('ni_enquiry_count_settings', array());
			$total_count = isset($count_settings['total_count']) ? $count_settings['total_count'] : 0;
			
			$daily_count = isset($count_settings['daily_counts']) ? $count_settings['daily_counts'] : array();
			$today_count = isset($daily_count[$today_date]) 	? $daily_count[$today_date] : 0;
			
			$today_count = $today_count + 1;
			$total_count =  $total_count + 1;
			
			unset($count_settings['daily_counts']);
			unset($count_settings['total_count']);
			
			$count_settings['total_count'] = $total_count;
			$count_settings['daily_counts'][$today_date] = $today_count;
			
			update_option('ni_enquiry_count_settings', $count_settings);
		}
		function print_array($ar = NULL,$display = true){
			if($ar){
			$output = "<pre>";
			$output .= print_r($ar,true);
			$output .= "</pre>";
			
			if($display){
				echo $output;
			}else{
				return $output;
			}
			}
		
		}
	}
endif;
?>