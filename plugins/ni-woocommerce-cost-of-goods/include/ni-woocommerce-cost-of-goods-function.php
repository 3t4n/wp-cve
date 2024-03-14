<?php
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'Ni_WooCommerce_Cost_Of_Goods_Function' ) ) { 
	include_once("ni-cog-function.php"); 
	class Ni_WooCommerce_Cost_Of_Goods_Function  extends Ni_COG_Function{
		 var $ni_cost_goods ='_ni_cost_goods';
		 public function __construct(){
		 	
			$ni_cog_meta_key = $this->get_cog_setting_by_key("ni_cog_meta_key",'_ni_cost_goods');
			if(empty($ni_cog_meta_key)){
			 	$ni_cog_meta_key = '_ni_cost_goods';	 
		 	}
			$this->ni_cost_goods = $ni_cog_meta_key;
			
			
			/*Simple Proudtc*/ 
			 
			/*Cost of goods for Simple Product*/
			add_action( 'woocommerce_product_options_general_product_data', array(&$this,'ni_add_custom_cost_of_goods_general') );
			
			// Save Fields
			//add_action( 'woocommerce_process_product_meta', array(&$this,'ni_save_custom_cost_of_goods_general' ));
			add_action( 'woocommerce_process_product_meta_simple', array(&$this,'ni_save_custom_cost_of_goods_general' ));
			
			/*End Simple Product*/
			
			/*Variation Product*/
			// Add Variation Settings
			add_action( 'woocommerce_product_after_variable_attributes',  array(&$this,'ni_variation_settings_fields'), 10, 3 );
			// Save Variation Settings
			add_action( 'woocommerce_save_product_variation',  array(&$this,'ni_save_variation_settings_fields'), 10, 2 );

			/*End Product*/
			
			//add_action( 'woocommerce_add_order_item_meta',  array(&$this,'ni_woocommerce_add_order_item_meta'), 10, 3);
			add_action( 'woocommerce_new_order_item',  array(&$this,'ni_woocommerce_add_order_item_meta'), 10, 3);
			
			/*Add COG of goods Columns*/
			add_filter( 'manage_edit-product_columns', array(&$this,'ni_cost_of_goods_field'), 10, 2 );
			/*Add COG of goods Columns Value*/
			add_action( 'manage_product_posts_custom_column', array(&$this,'ni_cost_of_goods_field_value'), 10, 2 );
			add_action( 'admin_print_styles', array(&$this,'in_add_order_ni_cost_goods_column_style') );
			
			
			
			
			
		 }		
		 function ni_add_custom_cost_of_goods_general(){
			global $post;
			$post_id = isset($post->ID) ? $post->ID : 0;			
			//$value = get_post_meta( $post_id, $this->ni_cost_goods, true );
			//$value = $value == "" ? 0 : $value;
			//$value = $value == "Array" ? 0 : $value;
			//$value = $value == "array" ? 0 : $value;
			//$value = $value + 0;
			
			 
			$value = $this->get_cost_price($post_id );
			 
			echo '<div class="show_if_simple">'; 	
				woocommerce_wp_text_input( 
					array( 
						'id'          => $this->ni_cost_goods, 
						'label'       => __( 'Cost Of Goods', 'wooreportcog' ), 
						'placeholder' => __( 'Cost Of Goods', 'wooreportcog' ),
						'desc_tip'    => 'true',
						'description' => __( 'Enter Cost Of Goods here.', 'wooreportcog' ) ,
						'class'		  => "short wc_input_price",
						'value'       => $value
					)
				);
			echo '</div>';	
		 }
		 function ni_save_custom_cost_of_goods_general( $post_id){
		 // Text Field
			$ni_cost_goods = isset($_POST[$this->ni_cost_goods]) ? $_POST[$this->ni_cost_goods] : 0;
			
			if(is_array($ni_cost_goods)){
				error_log("Cost of Goods Array(0001):- ".print_r($ni_cost_goods,true));
				error_log("ni_cost_goods key (0001):- ".print_r($this->ni_cost_goods,true));
				error_log("ni_cost_goods POSTDATA (0001):- ".print_r($_POST,true));				
				return false;
			}
			
			//error_log("Cost of Goods Array(0001):- ".print_r($ni_cost_goods,true));
			
			//$ni_cost_goods = $ni_cost_goods == "Array" ? 0 : $ni_cost_goods;
			//$ni_cost_goods = $ni_cost_goods == "array" ? 0 : $ni_cost_goods;
			//$ni_cost_goods = $ni_cost_goods + 0;
			
			if(!empty($ni_cost_goods)){
				//update_post_meta($post_id,$this->ni_cost_goods, wc_clean(wp_unslash($ni_cost_goods)));
				
				$this->update_cost_price($post_id,$ni_cost_goods);
			}
	
		 }
		 /*Variation*/
		 function ni_variation_settings_fields($loop, $variation_data, $variation ){
			 
			$post_id = isset($variation->ID) ? $variation->ID : 0;			
			//$value = get_post_meta( $post_id, $this->ni_cost_goods, true );
			//$value = $value == "" ? 0 : $value;
			//$value = $value == "Array" ? 0 : $value;
			//$value = $value == "array" ? 0 : $value;
			//$value = $value + 0;
			
			$value = $this->get_cost_price($post_id );
			
			 // Text Field
			echo '<div class="show_if_variable">'; 	 
				woocommerce_wp_text_input( 
					array( 
						'id'          => $this->ni_cost_goods.'[' . $variation->ID . ']', 
						'label'       => __( 'Cost Of Goods', 'wooreportcog' ), 
						'placeholder' => __( 'Cost Of Goods', 'wooreportcog' ), 
						'desc_tip'    => 'true',
						'description' => __( 'Enter Cost Of Goods here.', 'wooreportcog' ) ,
						'value'       => $value,
						'class'		  => "short wc_input_price",
					)
				);
			echo '</div>';		
		 }
		 function ni_save_variation_settings_fields( $post_id ){
			$ni_cost_goods = isset($_POST[$this->ni_cost_goods][$post_id]) ? $_POST[$this->ni_cost_goods][$post_id] : 0;
			if(is_array($ni_cost_goods)){
				error_log("Cost of Goods Array(0002):- ".$ni_cost_goods);
				return false;
			}
			
			//$ni_cost_goods = $ni_cost_goods == "Array" ? 0 : $ni_cost_goods;
			//$ni_cost_goods = $ni_cost_goods == "array" ? 0 : $ni_cost_goods;
			//$ni_cost_goods = $ni_cost_goods + 0;
			
			
			if( ! empty( $ni_cost_goods) ) {
				//update_post_meta( $post_id, $this->ni_cost_goods, esc_attr( $ni_cost_goods ) );
				$this->update_cost_price($post_id,$ni_cost_goods);
			}
		 }
		 function ni_woocommerce_add_order_item_meta($item_id, $values, $cart_item_key){
		 	$ni_cost_goods = 0;
			
			$item_type = WC_Data_Store::load( 'order-item' )->get_order_item_type( $item_id );
			if ($item_type != 'line_item' ){
				return;
			}
			
			
			$product_id = ($values["variation_id"]>0)?$values["variation_id"]:$values["product_id"];
			$ni_cost_goods = get_post_meta($product_id,$this->ni_cost_goods,true);
			if (empty($ni_cost_goods)){
				$ni_cost_goods = 0;
			}
			
			if (is_array($ni_cost_goods)){
				$ni_cost_goods = 0;
			}
			
			$ni_cost_goods = $ni_cost_goods == "" ? 0 : $ni_cost_goods;
			$ni_cost_goods = $ni_cost_goods == "Array" ? 0 : $ni_cost_goods;
			$ni_cost_goods = $ni_cost_goods == "array" ? 0 : $ni_cost_goods;
			$ni_cost_goods = $ni_cost_goods + 0;
		
			wc_update_order_item_meta($item_id, $this->ni_cost_goods, $ni_cost_goods );
		 }
		 function ni_cost_of_goods_field($columns){
			
		 $enable_profit_percentage = $this->get_cog_setting_by_key("enable_profit_percentage",'no');
		 $enable_net_profit = $this->get_cog_setting_by_key("enable_net_profit",'no');
		 $enable_net_profit_margin = $this->get_cog_setting_by_key("enable_net_profit_margin",'no');
		 $enable_product_cost = $this->get_cog_setting_by_key("enable_product_cost",'no');
			
			
			$new_columns = array();
			foreach ($columns as $column_name => $column_info) {
				$new_columns[$column_name] = $column_info;
				if ('price' === $column_name) {
					
					if ($enable_product_cost ==='yes'){
						$new_columns['ni_cost_goods'] = __( 'Ni Cost Price',	'wooreportcog'); 
					}
					if ( $enable_net_profit_margin ==='yes'){
						$new_columns['net_profit_margin'] = __('Net Profit Margin (%)',	'wooreportcog'); 
					}
					if ( $enable_net_profit  ==='yes'){
						$new_columns['net_profit'] = __( 'Net Profit',	'wooreportcog'); 
					}
					if ( $enable_profit_percentage === 'yes'){
						$new_columns['profit_percentage'] = __( 'Profit Percentage (%)',	'wooreportcog'); 
					}
					
				}
			}
			return $new_columns;
		 }
		 function in_add_order_ni_cost_goods_column_style(){
		 	$css = '.widefat .column-order_date, .widefat .column-ni_cost_goods { width: 9%; }';
    		wp_add_inline_style( 'woocommerce_admin_styles', $css );
		 }
		 function get_product_net_profit_margin($postid = 0){
			$cost_price = 0;
			$price = 0;
			$profit = 0;
			$net_profit_margin="";
			$product = wc_get_product($postid );
			/*Variation Product*/
			if( $product->is_type( 'variable' ) ){
				$total_cost  = array();
				$total_price  = array();
				$available_variations = $product->get_available_variations();
				foreach ($available_variations as $key => $value) { 
					$variation_id = isset($value["variation_id"])?$value["variation_id"]:0;
					$cost_price = get_post_meta($variation_id , $this->ni_cost_goods , true );
					$sales_price = get_post_meta($variation_id , '_price' , true );
					
					if (empty($cost_price ) || $cost_price ==''){
						$cost_price  = 0;
					}
					if (is_array($cost_price )){
						$cost_price  = 0;
					}
					$cost_price = 	$cost_price == "" ? 0 :$cost_price;
					
					
					if (empty($sales_price )  || $sales_price ==''){
						$sales_price  = 0;
					}
					
					$sales_price = 	$sales_price == "" ? 0 :$sales_price;
					
					$total_cost[]	 = $cost_price;
					$total_price[]	 = $sales_price;	
					
				}
				if (array_sum($total_cost) > 0 &&  array_sum($total_price)){
					if(count($total_cost) > 0 || count($total_price)){
						$cost_average = array_sum($total_cost)/count($total_cost);
						$price_average = array_sum($total_price)/count($total_price);
						$price_profit = 	$price_average - $cost_average;
						
							
						$net_profit_margin =   "Avg " . round((($price_profit/$price_average)*100),2) . "%";
					}
				}
			}
			else if( $product->is_type( 'simple' ) ){
				/*Simple Product*/
				$cost_price = get_post_meta($postid , $this->ni_cost_goods , true );
				$sales_price = get_post_meta($postid , '_price' , true );
				if (empty($cost_price ) || $cost_price  ==''){
					$cost_price  = 0;
				}
				
				if (empty($sales_price ) || $sales_price  ==''){
					$sales_price  = 0;
				}
				
				//$cost_price = 	$cost_price == "" ? 0 :$cost_price;
				//$sales_price = 	$sales_price == "" ? 0 :$sales_price;
			
				// Ensure $cost_price and $sales_price are numeric
				$cost_price = is_numeric($cost_price) ? $cost_price : 0;
				$sales_price = is_numeric($sales_price) ? $sales_price : 0;
			
			
			
				$price_profit = 	$sales_price - $cost_price;
				
				if ($sales_price>0){
					$net_profit_margin =  round((($price_profit/$sales_price)*100),2) . "%";
				}else{
				$net_profit_margin = 0;
				}
				
			}
			else{
				$net_profit_margin = wc_price( 0);
			}
			return $net_profit_margin;
			
		 }
		 function get_product_net_profit($postid){
			$cost_price = 0;
			$price = 0;
			$profit = 0;
			$net_profit ="";
			$product = wc_get_product($postid );
			/*Variation Product*/
			if( $product->is_type( 'variable' ) ){
				$total_cost  = array();
				$total_price  = array();
				$available_variations = $product->get_available_variations();
				foreach ($available_variations as $key => $value) { 
					$variation_id = isset($value["variation_id"])?$value["variation_id"]:0;
					$cost_price = get_post_meta($variation_id , $this->ni_cost_goods , true );
					$sales_price = get_post_meta($variation_id , '_price' , true );
					
					if (empty($cost_price )|| $cost_price  ==''){
						$cost_price  = 0;
					}
					
					if (is_array($cost_price )){
						$cost_price  = 0;
					}
					
					
					if (empty($sales_price )|| $sales_price  ==''){
						$sales_price  = 0;
					}
					
					if (is_array($sales_price )){
						$sales_price  = 0;
					}
					
					
					$cost_price = 	$cost_price == "" ? 0 :$cost_price;
					$sales_price = 	$sales_price == "" ? 0 :$sales_price;
					
					
					if (empty($sales_price ) || $sales_price  ==''){
						$sales_price  = 0;
					}
					$total_cost[]	 = $cost_price;
					$total_price[]	 = $sales_price;	
					
					
					
				}
				if (array_sum($total_cost) > 0 &&  array_sum($total_price)){
				
					if(count($total_cost) > 0 || count($total_price)){
						$cost_average = array_sum($total_cost)/count($total_cost);
						$price_average = array_sum($total_price)/count($total_price);
						$price_profit = 	$price_average - $cost_average;
							
						$net_profit =   "Avg "  . wc_price( $price_profit)  ;
					}
				}
			}
			else if( $product->is_type( 'simple' ) ){
				/*Simple Product*/
				$cost_price = get_post_meta($postid , $this->ni_cost_goods , true );
				$sales_price = get_post_meta($postid , '_price' , true );
				
				if (empty($cost_price )|| $cost_price  ==''){
					$cost_price  = 0;
				}
				
				if (is_array($cost_price )){
					$cost_price  = 0;
				}
				if (empty($sales_price ) || $sales_price  ==''){
					$sales_price  = 0;
				}
				
				if (is_array($sales_price )){
					$sales_price  = 0;
				}
				
				
				$cost_price = 	$cost_price == "" ? 0 :$cost_price;
				$sales_price = 	$sales_price == "" ? 0 :$sales_price;
					
				$price_profit = 	$sales_price - $cost_price;				
				
				$net_profit =  wc_price($price_profit) ;
			}
			else{
				$net_profit = wc_price( 0);
			}
			return $net_profit;
			
		 }
		 function get_product_profit_percentage($postid){
			$cost_price = 0;
			$price = 0;
			$profit = 0;
			$profit_percentage =0;
			$product = wc_get_product($postid );
			/*Variation Product*/
			if( $product->is_type( 'variable' ) ){
				$total_cost  = array();
				$total_price  = array();
				$available_variations = $product->get_available_variations();
				foreach ($available_variations as $key => $value) { 
					$variation_id = isset($value["variation_id"])?$value["variation_id"]:0;
					$cost_price = get_post_meta($variation_id , $this->ni_cost_goods , true );
					$sales_price = get_post_meta($variation_id , '_price' , true );
					
					if (empty($cost_price ) || $cost_price=='' ){
						$cost_price  = 0;
					}
					
					if (is_array($cost_price )){
						$cost_price  = 0;
					}
					
					$cost_price = 	$cost_price == "" ? 0 :$cost_price;
					
					
					if (empty($sales_price ) || $sales_price=='' ){
						$sales_price  = 0;
					}
					
					if (is_array($sales_price )){
						$sales_price  = 0;
					}
					
					$sales_price = 	$sales_price == "" ? 0 :$sales_price;
					
					
					if (empty($sales_price )){
						$sales_price  = 0;
					}
					$total_cost[]	 = $cost_price;
					$total_price[]	 = $sales_price;	
					
					
					
				}
				
				if (array_sum($total_cost) > 0 &&  array_sum($total_price)){
					$cost_average = array_sum($total_cost)/count($total_cost);
					$price_average = array_sum($total_price)/count($total_price);
					$price_profit = 	$price_average - $cost_average;
					
					if ($cost_average > 0){
						$profit_percentage = $price_profit /$cost_average;
					}else{
						$profit_percentage = 0;
					}

				}
								
				
				
			
					
				$profit_percentage =   "Avg "  . $profit_percentage."%" ;
				
			}
			else if( $product->is_type( 'simple' ) ){
				/*Simple Product*/
				$cost_price = get_post_meta($postid , $this->ni_cost_goods , true );
				$sales_price = get_post_meta($postid , '_price' , true );
				if (empty($cost_price ) || $cost_price=='' ){
					$cost_price  = 0;
				}
				
				if (is_array($cost_price )){
					$cost_price  = 0;
				}
				
				$cost_price = 	$cost_price == "" ? 0 :$cost_price;
				
				
				if (empty($sales_price ) || $sales_price=='' ){
					$sales_price  = 0;
				}
				
				if (is_array($sales_price )){
					$cost_price  = 0;
				}
				
				$sales_price = 	$sales_price == "" ? 0 :$sales_price;
				
				
				$price_profit = 	$sales_price - $cost_price;
				if ($cost_price >0 ){
					$profit_percentage =(($price_profit /$cost_price)*100);	
				}else{
					$profit_percentage = 0;
				}
				
				
				$profit_percentage =  $profit_percentage ."%" ;
			}
			else{
				$profit_percentage = wc_price( 0);
			}
			return $profit_percentage;
			
		 }
		 function get_product_cost_price($postid){
			 
			$product = wc_get_product($postid );
			/*Variation Product*/
			if( $product->is_type( 'variable' ) ){
				$variation_price  = array();
				$available_variations = $product->get_available_variations();
				foreach ($available_variations as $key => $value) { 
					$variation_id = isset($value["variation_id"])?$value["variation_id"]:0;
					$ni_cost_goods_variation = get_post_meta($variation_id , $this->ni_cost_goods , true );
					if ( strlen($ni_cost_goods_variation)>0){
						$variation_price[] =  $ni_cost_goods_variation;
					}
				}
				if (count($variation_price)>0){
					$ni_cost_goods =  $this->format_localized_cost_price(min($variation_price)) . "-"	.   $this->format_localized_cost_price(max($variation_price)) ;
				}else{
					$ni_cost_goods = wc_price( 0);
				}
			}else if( $product->is_type( 'simple' ) ){
				/*Simple Product*/
				$ni_cost_goods =  $this->get_cost_price( $postid );	
				//$ni_cost_goods = wc_price( $ni_cost_goods );
			}
			else{
				$ni_cost_goods = wc_price( 0);
			}
			
			return $ni_cost_goods ;
			
		 }
		 public function ni_cost_of_goods_field_value($column = "", $post_id = 0) {
			// Check if the column is specified
			if (empty($column)) {
				return; // Exit early if the column is not specified
			}
		
			switch ($column) {
				case 'net_profit_margin':
					echo $this->get_product_net_profit_margin($post_id);
					break;
				case 'ni_cost_goods':
					echo $this->get_product_cost_price($post_id);
					break;
				case 'net_profit':
					echo $this->get_product_net_profit($post_id);
					break;
				case 'profit_percentage':
					echo $this->get_product_profit_percentage($post_id);
					break;
				default:
					// Handle default case if necessary
					break;
			}
		}
	}
}