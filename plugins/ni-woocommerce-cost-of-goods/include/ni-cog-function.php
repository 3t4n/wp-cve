<?php
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'Ni_COG_Function' ) ) { 
	class Ni_COG_Function {
		public function __construct(){
		}
		function get_cog_setting_by_key($key,$default=''){
			$niwoocog_setting = array();	 
			$niwoocog_setting =  get_option('niwoocog_setting',array());
			$ni_cog_setting = '';
		    $ni_cog_setting = sanitize_text_field(isset($niwoocog_setting[$key])?$niwoocog_setting[$key]:$default);
			
			return $ni_cog_setting;
 			 
		}
		function get_product_parent(){
		    global $wpdb;
			$query = "";
			$query = " SELECT ";
			$query .= " posts.post_parent as post_parent ";
			$query .= " FROM  {$wpdb->prefix}posts as posts			";
			$query .= "	WHERE 1 = 1";
			$query .= "	AND posts.post_type  IN ('product_variation') ";
			$query .=" AND posts.post_status='publish'";
			
			$query .= " GROUP BY post_parent ";
			$row = $wpdb->get_results($query);		
			
			$post_parent_array = array();
			foreach($row as $key=>$value){
				$post_parent_array[] = $value->post_parent;
			}
			return $post_parent_array;
		}
		function get_cost_price($product_id = 0){
			
			$ni_cog_meta_key = $this->get_cog_setting_by_key('ni_cog_meta_key','_ni_cost_goods');
			if(empty($ni_cog_meta_key)){
			 	$ni_cog_meta_key = '_ni_cost_goods';	 
		 	}
			
			$cost_price  = get_post_meta( $product_id, $ni_cog_meta_key ,true );	
			$cost_price = wc_format_localized_price( $cost_price );
			
			return $cost_price;
			
		}
		function format_localized_cost_price($cost_price = 0){
			$cost_price = wc_price(wc_format_localized_price( $cost_price ));
			return $cost_price;
		}
		function update_cost_price($product_id = 0, $cost_price = 0){
			
			$ni_cog_meta_key = $this->get_cog_setting_by_key('ni_cog_meta_key','_ni_cost_goods');
			if(empty($ni_cog_meta_key)){
			 	$ni_cog_meta_key = '_ni_cost_goods';	 
		 	}
			
			$cost_price = wc_format_decimal( $cost_price );
			
			update_post_meta( $product_id, $ni_cog_meta_key , $cost_price );	
		}
		function get_sales_year(){
			global $wpdb;
			
			$years  =array();
			
			$rows = array();
			$query = "";
			$query .= " SELECT ";
			$query .= " date_format( posts.post_date, '%Y') as order_year";
				$query .= " FROM {$wpdb->prefix}posts as posts	";
		    $query .= "  WHERE 1 = 1";  
			$query .= " AND	posts.post_type ='shop_order' ";
			$query .= " Order By date_format( posts.post_date, '%Y')  DESC ";
			 
			$rows = $wpdb->get_results($query);
			
			foreach($rows as $key=>$value){
				$years[$value->order_year]  =$value->order_year;
			}
			
			return $years;
		}
		function prettyPrint($ar,$display = true) {
			if($ar){
				$output = "<pre>";
				$output .= print_r($ar,true);
				$output .= "</pre>";
			
			if($display){
				echo balanceTags($output,true);
			}else{
				return $output;
				}
			}
		}
	}
}