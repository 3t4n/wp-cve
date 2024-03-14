<?php

class Pi_Edd_Public {

	
	private $plugin_name;

	
	private $version;

	public $default_zone_id;

	public $methods;

	public $delivery_days;

	public $delivery_estimate;

	public $min_max;

	public $estimated_date;

	public $show_product_page;
	public $show_product_loop_page;
	public $show_cart_page;

	public $date_format = 'Y/m/d';

	public $selected_shipping_method = false;

	public $show_range = 0;

	public $delivery_range_estimate = array();

	public $delivery_range_date;

	public $estimated_date_range;

	public $calc_date_format = 'Y/m/d';

	
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->show_range = (int)get_option('pi_general_range',0);
		

		if(pi_edd_pro_check()){ /* This is to support very first pro version as it was based on filters */
			$this->date_format = apply_filters('pi_edd_general_date_format', 'Y/m/d');
		}else{
			$this->date_format = 'Y/m/d';
		}

		add_filter('pi_edd_single_product_position', array($this, 'single_product_position'), 10, 1);
		add_filter('pi_edd_html_product_loop_page', array($this, 'loop_page_html'), 10, 2);
		add_filter('pi_edd_html_cart_page', array($this, 'cart_page_html'), 10, 2);
		add_filter('pi_edd_product_loop_position', array($this, 'loop_position'), 10, 1);

		$this->default_zone_id = get_option('pi_defaul_shipping_zone',0);




		if(pi_edd_pro_check()){
			$this->show_product_page = (int)get_option('pi_show_product_page', 0);
			$this->show_product_loop_page = (int)get_option('pi_show_product_loop_page', 0);
			$this->show_cart_page = (int)get_option('pi_show_cart_page', 0);
		}else{
			$this->show_product_page = 1;
			$this->show_product_loop_page = 1;
			$this->show_cart_page = 1;
		}

		$pi_edd_enable_estimate = get_option('pi_edd_enable_estimate',1);
		
		if(!empty($pi_edd_enable_estimate)){
			add_action('woocommerce_init', array($this, 'initialize'));
		}		


	}

	function initialize(){
		if(!is_admin()):

			$selection_check = pisol_checking::checkUserSelectedClass();
			if($selection_check){
				$this->user_selection();
			}

			if($this->default_zone_id != "" && $this->default_zone_id != 0 ){
				$default_check = pisol_checking::is_Methods((int)$this->default_zone_id);
				if($default_check){
					$this->get_methods();
				}
			}else{
				$default_check = false;
			}

		

		if($selection_check || $default_check){

		if($this->show_product_page == 1 && !is_admin()):
			$single_product_position = apply_filters('pi_edd_single_product_position','woocommerce_before_add_to_cart_button');
			add_action($single_product_position, array($this,'estimate_on_product_page'));
		endif;

		if($this->show_product_loop_page == 1 && !is_admin()):
			$product_loop_position = apply_filters('pi_edd_product_loop_position','woocommerce_after_shop_loop_item_title');
			add_action($product_loop_position, array($this,'estimate_on_product_loop_page'));
		endif;

		if($this->show_cart_page == 1 && !is_admin()):
			add_action('woocommerce_after_cart_item_name', array($this,'estimate_on_cart_page2'),10,2);
			add_filter('woocommerce_checkout_cart_item_quantity', array($this,'estimate_on_cart_page'),10,3);
		endif;

		add_filter('pi_edd_html_product_page', array($this, 'product_page_html'), 10, 2);

		}

		endif; 
	}

	function user_selection(){
		$this->min_max = get_option('pi_edd_min_max','min');
		if( isset(WC()->session) ):
		if(is_array(WC()->session->get( 'chosen_shipping_methods' )) || (isset($_GET['wc-ajax']) && $_GET['wc-ajax'] == 'update_order_review')) {
			if(isset($_POST['shipping_method'][0])){
				$selection  = $_POST['shipping_method'];
			}else{
				$selection = WC()->session->get( 'chosen_shipping_methods' );
			}
			if(isset($selection[0])):
				$val = explode(":",$selection[0]);
				if(isset($val[1])){
					$this->selected_shipping_method = (int)$val[1];
					if($val[0] == "pisol_extended_flat_shipping"){
						/**
						 * This is to support our advanced flat rete shipping plugin
						 */
						$min_days = get_post_meta($val[1], 'min_days',true);
						$days_array[] = (int)($min_days);
						$max_days = get_post_meta($val[1], 'max_days',true);
						$days_array[] = (int)($max_days);
						
						if($min_days =="" && $max_days == ""){
							/** Will like to hide the estimate if the min max is "" for shipping method */
							//$this->disable_estimates();
						}
						
						
					}else{

						$method = WC_Shipping_Zones::get_shipping_method($this->selected_shipping_method );
						$min_days = pi_edd_common::getMin($method->instance_id, $method->id);
						$days_array[] = (int)$min_days;

						$max_days = pi_edd_common::getMax($method->instance_id, $method->id);
						$days_array[] = (int)($max_days);
						if($min_days =="" && $max_days == ""){
							/** Will like to hide the estimate if the min max is "" for shipping method */
							//$this->disable_estimates();
						}
					}

					if($this->min_max == 'min'){
						$this->delivery_estimate = min($days_array);
					}else{
						$this->delivery_estimate = max($days_array);
					}
					
					$this->delivery_range_estimate = array('min'=>(int)$min_days, 'max'=>(int)($max_days));
					
					$this->estimated_date = date($this->calc_date_format, strtotime(' + '.$this->delivery_estimate.' days'));
					
					$min = date($this->calc_date_format, strtotime(' + '.$this->delivery_range_estimate['min'].' days'));
					$max = date($this->calc_date_format, strtotime(' + '.$this->delivery_range_estimate['max'].' days'));
					$this->delivery_range_date = array('min'=> $min, 'max'=>$max);
				}
			endif;
		}
		endif;
		
	}

	function get_methods(){

			
			$this->min_max = get_option('pi_edd_min_max','min');

			if($this->default_zone_id !== 0 && $this->default_zone_id != ""){

				
					if($this->selected_shipping_method == false){
							$zone_obj = new WC_Shipping_Zone($this->default_zone_id);
							$this->methods = $zone_obj->get_shipping_methods(true);
							$this->get_delivery_days();

							if($this->min_max == 'min' && is_array($this->delivery_days)){
								$this->delivery_estimate = min($this->delivery_days);
								$this->delivery_range_estimate = array('min'=>min($this->delivery_days), 'max'=>max($this->delivery_days));
								
							}else{
								$this->delivery_estimate = max($this->delivery_days);
								$this->delivery_range_estimate = array('min'=>min($this->delivery_days), 'max'=>max($this->delivery_days));
							}
					}

					$this->estimated_date = date($this->calc_date_format, strtotime(' + '.$this->delivery_estimate.' days'));
					$min = date($this->calc_date_format, strtotime(' + '.$this->delivery_range_estimate['min'].' days'));
					$max = date($this->calc_date_format, strtotime(' + '.$this->delivery_range_estimate['max'].' days'));
					$this->delivery_range_date = array('min'=> $min, 'max'=>$max);

			}
	}

	function get_delivery_days(){
		$days = array();
		if(is_array($this->methods)) {
			foreach($this->methods as $method){
					/* 4/7/19 */
					$min_max = pi_edd_common::getMinMax($method->instance_id, $method->id);

					$days[]= (int)$min_max['min_days'];
					$min_days = $min_max['min_days'];

					$max_days = (isset($min_max['max_days']) && $min_max['max_days'] != "") ? $min_max['max_days'] : $min_days;
					
					$days[]= (int)($max_days);
					/* 4/7/19 */
			}
		}

		$this->delivery_days = $days;
	}

	function product_preparation_time($product_id = null){
		if($product_id == null){
			global $product;
			$id = $product->get_id();
		}else{
			$id = $product_id;
		}
		$preparation_days = apply_filters('pi_edd_order_preparation_days', 0, $id);
		$preparation_days = $preparation_days != "" ? $preparation_days : 0;
		$estimate = date($this->calc_date_format, strtotime(' + '.$preparation_days.' days', strtotime($this->estimated_date)));
		
		if($this->show_range == 1){
			$from = date($this->calc_date_format, strtotime(' + '.$preparation_days.' days', strtotime($this->delivery_range_date['min'])));
			$to = date($this->calc_date_format, strtotime(' + '.$preparation_days.' days', strtotime($this->delivery_range_date['max'])));
			$from = $this->add_holidays($from);
			$to = $this->add_holidays($to);
			$this->estimated_date_range['min'] = ($from);
			$this->estimated_date_range['max'] = ($to);
			return $estimate = $from.' - '.$to;
		}
		return $this->add_holidays($estimate);
	}

	function add_holidays($date, $today = ""){
		$holidays = get_option("pi_edd_holidays",false);
		
		if($holidays){
			$today = $today != "" ? $today : date('Y/m/d');
			$estimate = date('Y/m/d',strtotime($date));
			$holidays = explode(":",$holidays);

			$count = 0;
			foreach($holidays as $holiday){
				if($today <= $holiday && $holiday <= $estimate){
					$count++;
				}
			}
			
			if($count > 0){
				$date = date($this->calc_date_format, strtotime( ' + '.$count.' days', strtotime($estimate)));
				$date = $this->add_holidays($date, date($this->calc_date_format, strtotime( ' + 1 days', strtotime($estimate))));
			}

			return date($this->date_format,strtotime($date));
		}else{
			return date($this->date_format,strtotime($date));
		}
	}

	function is_virtual_prod($product_id = null){
		if($product_id == null){
			global $product;
			$id = $product->get_id();
		}else{
			$id = $product_id;
		}
		$product_obj = wc_get_product( $id );
		if($product_obj->needs_shipping() ){
			return true;
		}
		return false;
	}

	/**
	 * Show on product page
	 */
	public function estimate_on_product_page(){
		if($this->is_virtual_prod()):
		$estimate = $this->product_preparation_time();
		$html = "<div>Estimated delivery date is ".$estimate."</div>";
		echo apply_filters('pi_edd_html_product_page',$html, $estimate);
		endif;
	}

	public function estimate_on_product_loop_page(){
		if($this->is_virtual_prod()):
		$estimate = $this->product_preparation_time();
		$html = "<div>Estimated delivery date is ".$estimate."</div>";
		echo apply_filters('pi_edd_html_product_loop_page',$html, $estimate);
		endif;
	}

	public function estimate_on_cart_page($link_text, $cart_item, $cart_item_key){
		if($this->is_virtual_prod($cart_item['product_id'])):
			$estimate = $this->product_preparation_time($cart_item['product_id']);
			$html = apply_filters('pi_edd_html_cart_page',"<div>Estimated delivery ".$estimate."</div>",$estimate);
			return $link_text.'<br>'.$html;
		endif;
		return $link_text;
	}

	public function estimate_on_cart_page2( $cart_item, $cart_item_key){
		if($this->is_virtual_prod($cart_item['product_id'])):
			$estimate = $this->product_preparation_time($cart_item['product_id']);
			$html = apply_filters('pi_edd_html_cart_page',"<div>Estimated delivery ".$estimate."</div>",$estimate);
			echo '<br>'.$html;
		endif;
	}


	function product_page_html($html, $estimate){
		if($this->show_range == 0){
			$msg = get_option('pi_product_page_text','Estimated delivery date');
		}else{
			$msg = get_option('pi_product_page_text_range','Estimated delivery between');
		}
		$html = '<div class="pi-edd pi-edd-product">'.$msg.' <span>'.apply_filters('pi_product_page_date_format',$estimate).'</span></div>';
		return $html;
	}

	function single_product_position($position){
		$position = get_option('pi_product_page_position','woocommerce_before_add_to_cart_button');
		return $position;
	}

	function loop_page_html($html, $estimate){
		
		if($this->show_range == 0){
			$msg = get_option('pi_loop_page_text','Estimated delivery date');
		}else{
			$msg = get_option('pi_loop_page_text_range','Estimated delivery between');
		}
		$html = '<div class="pi-edd pi-edd-loop">'.$msg.' <span>'.apply_filters('pi_loop_page_date_format',$estimate).'</span></div>';
		return $html;
	}

	function cart_page_html($html, $estimate){
		
		if($this->show_range == 0){
			$msg = get_option('pi_cart_page_text','Estimated delivery date');
		}else{
			$msg = get_option('pi_cart_page_text_range','Estimated delivery between');
		}
		$html = '<div class="pi-edd pi-edd-cart">'.$msg.' <span>'.apply_filters('pi_cart_page_date_format',$estimate).'</span></div>';
		return $html;
	}

	function loop_position($position){
		$position = get_option('pi_loop_page_position','woocommerce_after_shop_loop_item_title');
		return $position;
	}

	
	public function enqueue_styles() {


		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pi-edd-public.css', array(), $this->version, 'all' );

	}

	
	public function enqueue_scripts() {


		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pi-edd-public.js', array( 'jquery' ), $this->version, false );

	}

	

}
