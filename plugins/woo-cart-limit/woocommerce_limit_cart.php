<?php

/*

 * Plugin Name: Woocommerce Cart Limit

 * Plugin URI: https://webmantechnologies.com

 * Description: Toolkit for Limit the Cart Quantity, Value and Total.

 * Author: Webman Technologies

 * Text Domain: wmamc-cart-limit

 * Version: 1.2

 * Requires at least: 4.4

 * Tested up to: 5.2.2

 */

defined( 'ABSPATH' ) or exit;



//WC check

$active_plugins = get_option( 'active_plugins', array() );

if( !in_array( 'woocommerce/woocommerce.php',$active_plugins ) ){

	

	require_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 

	deactivate_plugins( plugin_basename( __FILE__ ) );

	if( isset( $_GET['activate'] ))

      unset( $_GET['activate'] );

}



class WMAMC_wc_cartLimit {	

  

	protected static $instance;

	protected $adminpage;

	protected $template;	

	public static $options_set = array(

			  'wmamc_enable_cartlimit',

			  'wmamc_cart_max_quanity',

			  'wmamc_cart_min_quanity',

			  'wmamc_cart_max_total',

			  'wmamc_cart_min_total',

			  'wmamc_cat_max_quantity',
			  
			  'wmamc_cart_min_diff_item',

			  );

	

	public function __construct() {

		

		//session start

		add_action('init', array($this,'WMAMC_register_session') );

		

		if('true' == $this->WMAMC_get_cart_limit_options('wmamc_enable_cartlimit')){

			add_action('init', array($this,'WMAMC_initialize_limits') );

		}

		

		//version check

		add_action( 'admin_init', array( $this, 'WMAMC_woo_version_check' ) );		

		

		//add admin page

		add_action('admin_menu', array($this, 'WMAMC_add_menulink'));

		

		//add script and style

		add_action( 'admin_enqueue_scripts', array( $this, 'WMAMC_wc_export_review_enqueue' ) );

		

		//add and update cart limit options 

		add_action( 'admin_post_wmamc_cart_limitf',array( $this,'WMAMC_cart_list_formsave' ) );

		

		

		//admin settings

		add_action( 'woocommerce_product_options_advanced', array($this,'WMAMC_woocommerce_product_options_advanced'), 10, 0 ); 

		add_action( 'woocommerce_process_product_meta', array($this,'WMAMC_max_qty_save_product_field') );

		

		

	}

	

	public function WMAMC_instance() {

		

		if ( is_null( self::$instance ) ) {

			self::$instance = new self();

		}

		

		return self::$instance;

		

	}



	public function WMAMC_initialize_limits(){

		

		$cart_max_quanity = $this->WMAMC_get_cart_limit_options('wmamc_cart_max_quanity');

		$cart_min_quanity = $this->WMAMC_get_cart_limit_options('wmamc_cart_min_quanity');

		$cart_max_total   = $this->WMAMC_get_cart_limit_options('wmamc_cart_max_total');

		$cart_min_total   = $this->WMAMC_get_cart_limit_options('wmamc_cart_min_total');
		
		$cart_min_diff_item   = $this->WMAMC_get_cart_limit_options('wmamc_cart_min_diff_item');

		

		if('true' == $this->WMAMC_get_cart_limit_options('wmamc_cat_max_quantity')){

			

			//add category product max limit

			add_action( 'product_cat_edit_form_fields', array($this,'wmamc_product_cat_edit_meta_field'), 10, 1 );

			add_action( 'edited_product_cat', array($this,'wmamc_save_taxonomy_custom_meta'), 10, 2 );  

			add_action( 'create_product_cat', array($this,'wmamc_save_taxonomy_custom_meta'), 10, 2 );

			

			//validate category product limit 		

			add_action( 'woocommerce_add_to_cart', array($this,'WMAMC_validate_cat_max_quantity'),15,6 );

			add_action( 'woocommerce_check_cart_items', array($this,'WMAMC_validate1_cat_max_quantity'),15,0 );			

		

		}		



		//validate single product limit 		

		add_action( 'woocommerce_add_to_cart', array($this,'WMAMC_validate_product_max_quantity'),11,6 );

		add_action( 'woocommerce_check_cart_items', array($this,'WMAMC_validate1_product_max_quantity'),11,0 );			

					

		//check max quantity in cart

		if($cart_max_quanity != '' && $cart_max_quanity > 0) {			

			add_action( 'woocommerce_add_to_cart', array($this,'WMAMC_validate_cart_max_quantity'),10,6 );

			add_action( 'woocommerce_check_cart_items', array($this,'WMAMC_validate1_cart_max_quantity'),10,0 );			

		}

		

		//check min quantity in cart

		if($cart_min_quanity != '' && $cart_min_quanity > 0) {						

			add_action( 'woocommerce_check_cart_items', array($this,'WMAMC_validate_cart_min_items'),10,0 );			

		}

		

		//check max value in cart

		if($cart_max_total != '' && $cart_max_total > 0) {						

			add_action( 'woocommerce_check_cart_items', array($this,'WMAMC_validate_cart_max_total'),10,0 );			

		}

		

		//check min value in cart

		if($cart_min_total != '' && $cart_min_total > 0) {						

			add_action( 'woocommerce_check_cart_items', array($this,'WMAMC_validate_cart_min_total'),10,0 );			

		}

		

		//check min value in cart

		if($cart_min_diff_item != '' && $cart_min_diff_item > 0) {						

			add_action( 'woocommerce_check_cart_items', array($this,'WMAMC_validate_cart_min_diff_item'),10,0 );			

		}

			

	}

		

	public function WMAMC_getWcVersion() {

		global $woocommerce; 

		return $woocommerce->version;

	}	

	

	public function WMAMC_woo_version_check() {

			

		global $woocommerce; 

					

		if ( version_compare( $woocommerce->version, '2.4.9', '<=' ) ) {

			add_action( 'admin_notices', array($this,'WMAMC_admin_notice_msg') );

			

			require_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 

			deactivate_plugins( plugin_basename( __FILE__ ) );

			return false;

		}

	}

	

	public function WMAMC_register_session(){

		

		//session_start();

		

	}

	

    public function WMAMC_add_menulink() {



		 $this->adminpage = add_submenu_page(

					'woocommerce',

					__('Add Cart Limit','wmamc-cart-limit'),

					__('Add Cart Limit','wmamc-cart-limit'), 

					'manage_woocommerce',

					'wmamc_cart_limit',

					array($this, 'WMAMC_render_submenu_pages' ),

					'dashicons-format-video'

				);	

	}	

	

	public function WMAMC_render_submenu_pages() {	

	

			$this->template = $this->WMAMC_get_template('cart_limit'); 	

			

	}

		

	public function WMAMC_wc_export_review_enqueue($hook) {	

		

		//add bootstrap to plugin page

		if($hook == "woocommerce_page_wmamc_cart_limit") {           

			wp_enqueue_style( 'bootstrap_wp_admin_css', plugins_url('/assets/css/bootstrap_custom.css', __FILE__),array(),'1' );			

			wp_enqueue_script('bootstrap_wp_admin_js-script',  plugins_url('/assets/js/bootstrap_custom_js.js', __FILE__ ) , array('jquery'), '1', true);

			

		}

		

		wp_enqueue_style('woo_cartlimit_s-style', plugins_url('/assets/css/woo_cartlimit.css', __FILE__ ) );			

		wp_enqueue_script('woo_cartlimit-script',  plugins_url('/assets/js/woo_cartlimit.js', __FILE__ ) , array('jquery'), '', true);

		

		wp_localize_script( 'woo_cartlimit-script', 'plajax', array(

			'ajax_url' => admin_url( 'admin-ajax.php' )

		));

		

		

	}

	

	public function WMAMC_get_plugin_dir(){

		

		 return dirname( __FILE__ );	

		 

	}

	

	public function WMAMC_get_template($template){ 

	

		$template_name = 'template_'.$template.'.php';			

		include  $this->WMAMC_get_plugin_dir().'/template/'.$template_name;

		

	}

	

	public function WMAMC_get_loader() {

		

		$img = plugin_dir_url( __FILE__ ) .'assets/img/loader.gif';

		$html = "<div class='WMAMC_loader' style='display:none;' ><center><img src=".$img." /><label>Refreshing ...</label></center></div>";

		return $html;

		

	}

	

	public function WMAMC_get_msgbox() {

		

		$html = "<div class='msg_box'></div>";

		return $html;

		

	}

		

	public function WMAMC_recursive_sanitize_text_field($array) {

		

		foreach ( $array as $key => &$value ) {

			if ( is_array( $value ) ) {

				$value = WMAMC_recursive_sanitize_text_field($value);

			}

			else {

				$value = sanitize_text_field( $value );

			}

		}



		return $array;

		

	}

	

	public function WMAMC_admin_notice_msg() {		

		

		global $woocommerce;		

		?>

		<div class="notice notice-error is-dismissible">

			<p><?php   _e("<b>Woocommerce Cart Limit is inactive</b>. Woocommerce Cart Limit requires a minimum of WooCommerce v2.5.0","wmamc-cart-limit"); ?></p>

		</div>

		<?php

	}

	

	public function WMAMC_cart_list_formsave(){

		

	  if ( !current_user_can( 'manage_options' ) )

	   {

		  wp_die( 'You are not allowed to be on this page.' );

	   }

	   

	   //Check nonce field

	   $nonce =  sanitize_text_field($_POST['wmamc_cart_limit_nonce'] );

	   

	   if( wp_verify_nonce($nonce,'wmamc_cart_limit_nonce') ){	

		

		if(isset($_POST) && 'wmamc_cart_limitf' == sanitize_text_field($_POST['action']) ){

					

			$enable_cl  = sanitize_text_field($_POST['enable_cart_limit'][0]);

			(sanitize_text_field($_POST['wmamc_cart_max_quanity']) !='') ? $wmamc_cart_max_quanity = (int) sanitize_text_field($_POST['wmamc_cart_max_quanity']) : '';

			(sanitize_text_field($_POST['wmamc_cart_min_quanity']) !='') ? $wmamc_cart_min_quanity = (int) sanitize_text_field($_POST['wmamc_cart_min_quanity']) : '';

			(sanitize_text_field($_POST['wmamc_cart_max_total']) !='')   ? $wmamc_cart_max_total   = (int) sanitize_text_field($_POST['wmamc_cart_max_total']) : '';

			(sanitize_text_field($_POST['wmamc_cart_min_total']) !='')   ? $wmamc_cart_min_total   = (int) sanitize_text_field($_POST['wmamc_cart_min_total']) : '';

			(sanitize_text_field($_POST['wmamc_cat_max_quantity']) !='') ? $wmamc_cat_max_quantity =  sanitize_text_field($_POST['wmamc_cat_max_quantity']) : '';
			
			(sanitize_text_field($_POST['wmamc_cart_min_diff_item']) !='') ? $wmamc_cart_min_diff_item =  sanitize_text_field($_POST['wmamc_cart_min_diff_item']) : '';

			

			if($enable_cl == 'true'){

				update_option('wmamc_enable_cartlimit','true');

			}else{

				update_option('wmamc_enable_cartlimit','false');

			}

			if($wmamc_cat_max_quantity == 'true'){

				update_option('wmamc_cat_max_quantity','true');

			}else{

				update_option('wmamc_cat_max_quantity','false');

			}

			

			update_option('wmamc_cart_max_quanity',$wmamc_cart_max_quanity);

			update_option('wmamc_cart_min_quanity',$wmamc_cart_min_quanity);

			update_option('wmamc_cart_max_total'  ,$wmamc_cart_max_total);

			update_option('wmamc_cart_min_total'  ,$wmamc_cart_min_total);
			
			update_option('wmamc_cart_min_diff_item'  ,$wmamc_cart_min_diff_item);

			

		}

		

	   }

	      

	   wp_redirect(admin_url().'/admin.php?page=wmamc_cart_limit');

		//exit;

	}

	

	public function WMAMC_get_cart_limit_options($option_key){

		

		return  get_option($option_key);

	}

	

	public function WMAMC_validate_cart_max_quantity( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {	

			

		global $woocommerce;		



		$cart_max_quanity = $this->WMAMC_get_cart_limit_options('wmamc_cart_max_quanity');

		

		$cartQty = $woocommerce->cart->get_cart_item_quantities();

		

		$cart_quantity = 0;

		

		foreach ( $cartQty as $key => $value ) {

			$cart_quantity += $value;			

		}

				

		//

		if($cart_max_quanity < $cart_quantity ){	

		

			if(array_key_exists($product_id,$cartQty)){				

				

				$item  = WC()->cart->get_cart_item($cart_item_key);

				$item_q = $item['quantity'];

				$final_q = $item_q - $quantity;			

				WC()->cart->set_quantity($cart_item_key,$final_q);

		

			}else{

				WC()->cart->remove_cart_item($cart_item_key);	

			}

						

			add_filter( 'wc_add_to_cart_message', array($this,'WMAMC_cart_message' ));

			wc_add_notice( sprintf( __( "You can’t have more than $cart_max_quanity items in cart. <a href='%s'>View Cart</a>"), wc_get_cart_url() ), "error" );

		}



	}

	

	public function WMAMC_validate_product_max_quantity( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {	

			

		global $woocommerce;		



		$product_max_quanity = $this->WMAMC_get_product_max_limit($product_id);

		

		$cartQty = $woocommerce->cart->get_cart_item_quantities();

				

		//

		if($product_max_quanity !== false && $product_max_quanity > 0 && $product_max_quanity < $cartQty[$product_id] ){	

		

			if(array_key_exists($product_id,$cartQty)){				

				

				$item  = WC()->cart->get_cart_item($cart_item_key);

				$item_q = $item['quantity'];

				$final_q = $item_q - $quantity;			

				WC()->cart->set_quantity($cart_item_key,$final_q);

		

			}else{

				WC()->cart->remove_cart_item($cart_item_key);	

			}

			

			$product_title = get_the_title($product_id);	

						

			add_filter( 'wc_add_to_cart_message', array($this,'WMAMC_cart_message' ));

			wc_add_notice( sprintf(__( "You can’t have more than $product_max_quanity items of $product_title <a href='%s'>View Cart</a>"), wc_get_cart_url() ), "error" );

		}



	}

	

	public function WMAMC_validate_cat_max_quantity( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {	

			

		global $woocommerce;		

		

		$product_cat_id = array();

		$product_cat_name = array();

		

		//get cart items and filter by category

		$cartQty = $woocommerce->cart->get_cart_item_quantities();

		

		foreach ( $cartQty as $key => $value ) {

	

			$terms = get_the_terms( $key, 'product_cat' );

			

			foreach ($terms as $term) {

				

				$product_cat_id[$term->term_id][]=$value;	

				$product_cat_name[$term->term_id]=$term->name;	

				

			}		

		}

		

		//check and add error

		foreach($product_cat_id as $cat_id=>$product_cat){

				

			$term_meta = get_option( "taxonomy_$cat_id" );

			$cat_limit = $term_meta['max_pcat_qty_cart'];

			

			if($cat_limit < array_sum($product_cat) && $cat_limit != '' && $cat_limit > 0 ){

				

				if(array_key_exists($product_id,$cartQty)){				

				

					$item  = WC()->cart->get_cart_item($cart_item_key);

					$item_q = $item['quantity'];

					$final_q = $item_q - $quantity;			

					WC()->cart->set_quantity($cart_item_key,$final_q);

			

				}else{

					WC()->cart->remove_cart_item($cart_item_key);	

				}

				

				add_filter( 'wc_add_to_cart_message', array($this,'WMAMC_cart_message' ));

				wc_add_notice( sprintf( __("You can’t have more than %s items of '%s' category.   <a href='%s'>View Cart</a>", 'woocommerce'), $cat_limit, $product_cat_name[$cat_id] ,wc_get_cart_url()), 'error' );

			}			

		}



	}

	

	public function WMAMC_validate1_cart_max_quantity( ) { 

		global $woocommerce;

		

		$cart_max_quanity = $this->WMAMC_get_cart_limit_options('wmamc_cart_max_quanity');

		$cartQty = $woocommerce->cart->get_cart_item_quantities();

		

		$cart_quantity = 0;

		foreach ( $cartQty as $key => $value ) {

		

			$cart_quantity += $value;

			

		}

		if($cart_max_quanity < $cart_quantity){				

			wc_add_notice( sprintf( __('You can’t have more than %s items in cart', 'woocommerce'), $cart_max_quanity ), 'error' );

		}		

	

	}

	

	public function WMAMC_validate1_product_max_quantity( ) { 

		global $woocommerce;

		

		$cartQty = $woocommerce->cart->get_cart_item_quantities();

		

		$cart_quantity = 0;

		foreach ( $cartQty as $key => $value ) {

			

			$product_max_quanity = $this->WMAMC_get_product_max_limit($key);

			

			if($product_max_quanity!== false && $product_max_quanity > 0 && $product_max_quanity < $value){								

				wc_add_notice( sprintf( __('You can’t have more than %s items of %s', 'woocommerce'), $product_max_quanity, get_the_title($key) ), 'error' );

			}

			

		}

		

	}

	

	public function WMAMC_validate1_cat_max_quantity( ) { 

		

		global $woocommerce;

			

		$product_cat_id = array();

		$product_cat_name = array();

		$cartQty = $woocommerce->cart->get_cart_item_quantities();

		

		foreach ( $cartQty as $key => $value ) {

	

			$terms = get_the_terms( $key, 'product_cat' );

			

			foreach ($terms as $term) {

				

				$product_cat_id[$term->term_id][]=$value;	

				$product_cat_name[$term->term_id]=$term->name;	

				

			}		

		}

		

		//check category limit and add error

		foreach($product_cat_id as $cat_id=>$product_cat){

				

			$term_meta = get_option( "taxonomy_$cat_id" );

			$cat_limit = $term_meta['max_pcat_qty_cart'];

			

			if($cat_limit < array_sum($product_cat) && $cat_limit != '' && $cat_limit > 0 ){

				wc_add_notice( sprintf( __("You can’t have more than %s items of '%s' category. ", 'woocommerce'), $cat_limit, $product_cat_name[$cat_id] ), 'error' );

			}			

		}



	}

		

	public function WMAMC_validate_cart_min_items( ) { 

		global $woocommerce;

		

		$cart_min_quanity = $this->WMAMC_get_cart_limit_options('wmamc_cart_min_quanity');

		$cartQty = $woocommerce->cart->get_cart_item_quantities();

		

		$cart_quantity = 0;

		foreach ( $cartQty as $key => $value ) {

		

			$cart_quantity += $value;

			

		}

		if($cart_min_quanity > $cart_quantity){				

			wc_add_notice( sprintf( __('You need to buy minimum %s products', 'woocommerce'), $cart_min_quanity ), 'error' );

		}		

	

	}

	

	public function WMAMC_validate_cart_max_total( ) { 

		global $woocommerce;

		

		$cart_max_total = $this->WMAMC_get_cart_limit_options('wmamc_cart_max_total');

		$cartQty = $woocommerce->cart->get_cart_item_quantities();

		

		$cart_subtotal = 0;

		foreach ( WC()->cart->get_cart() as $key => $value ) {		

			$cart_subtotal += $value['line_subtotal'];					

		}

		

		if($cart_max_total < $cart_subtotal){

			$currency = get_option('woocommerce_currency');

			$currency_symbol = get_woocommerce_currency_symbol($currency);

			wc_add_notice( sprintf( __('You need to buy maximum of %s value products', 'woocommerce'), $currency_symbol.$cart_max_total ), 'error' );

		}		

	

	}

	

	public function WMAMC_validate_cart_min_total( ) { 

		global $woocommerce;

		

		$cart_max_total = $this->WMAMC_get_cart_limit_options('wmamc_cart_min_total');

		$cartQty = $woocommerce->cart->get_cart_item_quantities();

		

		$cart_subtotal = 0;

		foreach ( WC()->cart->get_cart() as $key => $value ) {		

			$cart_subtotal += $value['line_subtotal'];					

		}

		

		if($cart_max_total > $cart_subtotal){	

			$currency = get_option('woocommerce_currency');

			$currency_symbol = get_woocommerce_currency_symbol($currency);

			wc_add_notice( sprintf( __('You need to buy minimum of %s value products', 'woocommerce'), $currency_symbol.$cart_max_total ), 'error' );

		}		

	

	}


	public function WMAMC_validate_cart_min_diff_item( ) { 

		global $woocommerce;
		
		$cart_min_quanity = $this->WMAMC_get_cart_limit_options('wmamc_cart_min_diff_item');;
		$cartQty = $woocommerce->cart->get_cart();
		
		$cart_quantity = 0;
		foreach ( $cartQty as $key => $value ) {
		
			$cart_quantity++;
			
		}
		if($cart_min_quanity > $cart_quantity){				
			wc_add_notice( sprintf( __('You need to buy minimum %s different products', 'woocommerce'), $cart_min_quanity ), 'error' );
		}

	}

	

	public function WMAMC_woocommerce_product_options_advanced(  ) { 

		echo '<div class="options_group">';

		woocommerce_wp_text_input( 

			array( 

				'id'          => '_wmamc_max_qty_product_max', 

				'label'       => __( 'Max Quantity Per Order', 'wmamc-cart-limit' ), 

				'placeholder' => '',

				'desc_tip'    => 'true',

				'description' => __( 'Optional. Set a maximum quantity limit allowed per order. Enter a number, 1 or greater.', 'wmamc-cart-limit' ) 

			)

		);

		echo '</div>';

	}

	

	public function WMAMC_max_qty_save_product_field( $post_id ) {

		$val = trim( get_post_meta( $post_id, '_wmamc_max_qty_product_max', true ) );

		$new = (int)sanitize_text_field( $_POST['_wmamc_max_qty_product_max'] );

		if ( $val != $new ) {

			update_post_meta( $post_id, '_wmamc_max_qty_product_max', $new );

		}

	}

	

	public function WMAMC_get_product_max_limit( $product_id ) {

		$qty = get_post_meta( $product_id, '_wmamc_max_qty_product_max', true );

		if ( empty( $qty ) ) {

			// honor the Sold individually setting

			$product = wc_get_product( $product_id );

			$limit = $product->is_sold_individually() ? 1 : false;

		} else {

			$limit = (int) $qty;

		}

		return $limit;

	}

		

	public function wmamc_product_cat_edit_meta_field($term){		

			$t_id = $term->term_id;	

			

			$term_meta = get_option( "taxonomy_$t_id" ); ?>

			<tr class="form-field">

			<th scope="row" valign="top"><label for="term_meta[max_pcat_qty_cart]"><?php _e( 'Max Product Quantity ', 'wmamc-cart-limit' ); ?></label></th>

				<td>

					<input type="number" min="0" name="term_meta[max_pcat_qty_cart]" id="term_meta[max_pcat_qty_cart]" value="<?php echo esc_attr( $term_meta['max_pcat_qty_cart'] ) ? esc_attr( $term_meta['max_pcat_qty_cart'] ) : ''; ?>">

					<p class="description"><?php _e( 'Enter a value for maximum product allowed in cart for this catgory.','wmamc-cart-limit' ); ?></p>

				</td>

			</tr>

		<?php		

	}

	

	public function wmamc_save_taxonomy_custom_meta( $term_id ) {

		if ( isset( $_POST['term_meta'] ) ) {	

		

			$t_id = $term_id;

			$term_meta = get_option( "taxonomy_$t_id" );

			$cat_keys = array_keys( $_POST['term_meta'] );

			

			foreach ( $cat_keys as $key ) {

				

				$term_meta_val = sanitize_text_field($_POST['term_meta'][$key]);

				

				if ( isset ( $_POST['term_meta'][$key] ) ) {

					$term_meta[$key] = $term_meta_val;

				}

			}

			// Save the option array.

			update_option( "taxonomy_$t_id", $term_meta );

		}

	}

	

	public function WMAMC_cart_message() {				

				return '';

	}

	

	

}



function WMAMC_wc_cartLimit() {

	

	return WMAMC_wc_cartLimit::WMAMC_instance();

	

}



WMAMC_wc_cartLimit();

?>