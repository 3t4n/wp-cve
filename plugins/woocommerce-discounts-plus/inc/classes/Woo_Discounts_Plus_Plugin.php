<?php



	if ( !class_exists( 'Woo_Discounts_Plus_Plugin' ) ) {

		class Woo_Discounts_Plus_Plugin extends wdp_core_factory {

			var $wdp_dir;
			var $opts = 6;
			var $discount_love;
			var $discount_love_unset;
			var $plus_discount_calculated = false;
			var $premium_link = 'https://shop.androidbubbles.com/product/woocommerce-discounts-plus';
			var $watch_tutorial = 'http://androidbubble.com/blog/wdp';
			var $s2member = 'http://androidbubble.com/blog/s2member';
			var $contact_developer = 'https://www.androidbubbles.com/contact';
			var $per_product;
			var $wdp_pro = false;
			var $display_dicounted_in_cart = true;
			var $discounted_items = array();
			var $currency = '';
			var $woocommerce_weight_unit;
			var $qty_total = 0;
//			var $fields = array('plus_discount' => array());


			public function __construct() {
				parent::__construct();


				if(isset($_GET['debug'])){
					//pre('applicables: '.wdp_woocommerce_discount_applicable());

					if(function_exists('WC')){
						$chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
						//pre(WC()->session);
						$chosen_shipping = $chosen_methods[0];
						//pre($chosen_shipping);
					}


					//exit;
				}

				if(!wdp_woocommerce_discount_applicable() && !is_admin()){

					return;
				}

				$this->opts = get_option('wcdp_criteria_no', $this->opts);
				$this->opts++;

				global $wdp_pro, $pro_class, $wdp_plugin_basename;


				if($wdp_pro){
					$this->wdp_pro = true;
					//define('WDP_PER_PRODUCT', false);
					//include_once($pro_class);
				}else{
					//define('WDP_PER_PRODUCT', true);
				}


				//pree($wdp_pro);
				//$this->per_product = WDP_PER_PRODUCT;

				$this->current_tab = ( isset( $_GET['tab'] ) ) ? $_GET['tab'] : 'general';

				$this->settings_tabs = array(
					'plus_discount' => __( 'Discounts Plus', "wcdp").($this->wdp_pro?'+':'')
				);



				add_action( 'admin_enqueue_scripts', array( $this, 'wdp_enqueue_scripts_admin' ) );
				add_action( 'wp_head', array( $this, 'wdp_enqueue_scripts' ) );

				add_filter( 'plugin_wdp_links_' . $wdp_plugin_basename, array( $this, 'wdp_links' ) );

				$plugin = $wdp_plugin_basename;
				add_filter("plugin_action_links_$plugin", array($this, 'wdp_plugin_links') );

				add_action( 'woocommerce_settings_tabs', array( $this, 'add_tab' ), 10 );

				// Run these actions when generating the settings tabs.
				foreach ( $this->settings_tabs as $name => $label ) {
//					add_action( 'woocommerce_settings_tabs_' . $name, array( $this, 'settings_tab_action' ), 10 );
//					add_action( 'woocommerce_update_options_' . $name, array( $this, 'save_settings' ), 10 );
				}

				// Add the settings fields to each tab.
				add_action( 'woocommerce_settings_plus_discount', array( $this, 'add_settings_fields' ), 10 );
//				add_action( 'woocommerce_plus_discount_settings', array( $this, 'add_settings_fields' ), 10 );

				//add_action( 'woocommerce_loaded', array( $this, 'woocommerce_loaded' ) );
				add_action( 'plugins_loaded', array( $this, 'woocommerce_loaded' ) );

			}


			public function wpdp_woocommerce_cart_totals_before_shipping(){
				global $wdp_price_num_decimals;
				
				echo '<tr class="wpdp-cart-discount">
					<th>'.get_wcdp_discount_label().' '.$d_percentage.'</th>
					<td data-title="wpdp-discount"><span class="woocommerce-Price-amount amount before_shipping"><span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span>'.round($d_total_discount,$wdp_price_num_decimals).'</span></td>
				</tr>';
			}

			public function wpdp_filter_woocommerce_cart_subtotal( $html_str ){
				


				global $wdp_price_num_decimals, $wdp_cart_total_discount;
				
				$wcp_discount_type = get_option('woocommerce_plus_discount_type');
				
				if($wcp_discount_type == 'cart_amount'){
					return $html_str;
				}

				//if($this->gj_logic()){
					$coeff = 0;
					$d_items = array();
					$plus_discount_type_globally = (get_option( 'woocommerce_plus_discount_type', 'quantity' ));



					if(!empty($this->discount_love)){

						foreach($this->discount_love as $item_id=>$item_arr){
							
							if($item_arr['coeff']>$coeff && $item_arr['coeff']<1){
								$coeff = $item_arr['coeff'];
							}
							
							$orig_price_actual = (array_key_exists('orig_price_actual', $item_arr)?$item_arr['orig_price_actual']:0);
							
							//pree($wdp_cart_total_discount);
							if(wcdp_is_product_valid($item_id)){

								//$wdp_cart_total_discount += (isset($item_arr['disc_amount_flat']) ? $orig_price_actual : $item_arr['orig_price_actual']*(1 - $item_arr['coeff'])*$item_arr['quantity']);
							}
							
							//$wdp_cart_total_discount += (isset($item_arr['disc_amount_flat']) ? $item_arr['disc_amount_flat'] : $orig_price_actual*(1 - $item_arr['coeff'])*$item_arr['quantity']);

							switch($plus_discount_type_globally){
								case 'weight':
									$d_items[$item_id] = array_key_exists('orig_price', $item_arr)?$item_arr['orig_price']*($item_arr['quantity']/$item_arr['base_weight']):0;
									break;
								case 'quantity':
									//orig_price to orig_price_actual 07/06/2019
									$d_items[$item_id] = $orig_price_actual*$item_arr['quantity'];
									break;
							}
						}

					}
					if(!empty($this->discount_love_unset)){

						foreach($this->discount_love_unset as $item_id=>$item_arr){
							
							$d_items[$item_id] = $item_arr['orig_price'] * $item_arr['quantity'];
						}

					}
					
					if($wdp_cart_total_discount == 0){
					
						return $html_str;
					
					}
					//pre($d_items);

					$d_total_amount = array_sum($d_items);
					$html_str = '<span class="woocommerce-Price-amount amount cart_subtotal"><span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span>'.round($d_total_amount, $wdp_price_num_decimals).'</span>';
				//}else{
					//pre($html_str);
				//}*/
				
				

				return $html_str;
			}

			public function wpdp_woocommerce_cart_totals_before_order_total(){

				global $woocommerce, $wdp_price_num_decimals;

				$total_quantity = 0;
				$d_total_amount = 0;
				$d_total_discount = 0;
				$d_percentage = '';
				$q = array();
				
				if($this->gj_logic()){

					$is_flat = (get_option( 'woocommerce_discount_type', '' ) == 'flat');
					$plus_discount_type_globally = (get_option( 'woocommerce_plus_discount_type', 'quantity' ));

					/*if ( sizeof( $woocommerce->cart->cart_contents ) > 0 ) {
						foreach ( $woocommerce->cart->cart_contents as $cart_item_key => $values ) {
							$total_quantity += $values['quantity'];
						}
					}*/


					$coeff = 0;
					$d_items = array();
					foreach($this->discount_love as $item_id=>$item_arr){

						//pre($item_arr);

						if($item_arr['coeff']>$coeff && $item_arr['coeff']<1){
							$coeff = $item_arr['coeff'];
						}


						switch($plus_discount_type_globally){
							case 'weight':
								$d_items[$item_id] =  array_key_exists('orig_price', $item_arr)?$item_arr['orig_price']*($item_arr['quantity']/$item_arr['base_weight']):0;
								//pree($d_items[$item_id]);
								break;
							case 'quantity':
								//orig_price to orig_price_actual 07/06/2019
								$d_items[$item_id] = array_key_exists('orig_price_actual', $item_arr)?$item_arr['orig_price_actual']*$item_arr['quantity']:0;
								//pre($item_arr['quantity']);
								$total_quantity += $item_arr['quantity'];
								break;
						}

					}


					//pree($d_items);
					//pree($coeff);

					if($is_flat){
					}else{
						$d_total_amount = array_sum($d_items);
						//pre($d_total_amount);
						$d_total_discount = ($d_total_amount-($d_total_amount*$coeff));

						//pree($d_total_discount);pree($d_total_amount);

						$dpa = $d_total_amount?($d_total_discount/$d_total_amount):0;
						//pree($dpa);
						$d_percentage = ($dpa!=1?' ('.($dpa*100).'%)':'');
					}

					/*if(in_array($_product->get_id(), $this->discounted_items)){
						$discprice = wdp_get_formatted_price( number_format((float)$_product->get_price(), 2) );
					}else{

						$discprice = wdp_get_formatted_price( number_format((float)($_product->get_price() * $coeff), 2) );
					}*/
					$wdpq = get_option( 'wdp_qd', array() );
					//pre($wdpq);
					//pre($total_quantity);
					//pree($this->opts);
					$q = $d = array();
					for ( $i = 0; $i < $this->opts; $i++ ) {


						if($this->gj_logic()){
							//pre($this->qty_total);
							//pree($wdpq[$i]['q']);
							if(isset($wdpq[$i]) && isset($wdpq[$i]['q']) && $wdpq[$i]['q']>0){
								$qv = $wdpq[$i]['q'];
								$dv = $wdpq[$i]['d'];
								array_push( $q, $qv>0 ? $qv : 0.0 );
								array_push( $d, $dv>0 ? $dv : 0.0 );
								//pre($q);pre($d);
								//pre(count($q));
							}

						}

					}
					//pre($q);
					//pre($q);
					//pre($total_quantity.' > '.max($q));


					
				}else{
					$d_percentage = '';
				}
			
				$special_offer = '';
				if(!empty($q) && $total_quantity>max($q)){
					$special_offer = get_option('wpdp_special_offer', '');
					if($special_offer){
						$special_offer = '<div class="wpdp_special_offer">'.$special_offer.'</div>';
					}
				}				

				
				if($d_percentage){
					//pree($d_total_discount);
					$d_total_discount_value = round($d_total_discount, $wdp_price_num_decimals);
					WC()->session->set('wpdp_total_discount_value', $d_total_discount_value);
					echo '<tr class="wpdp-cart-discount">
						<th>'.get_wcdp_discount_label().' '.$d_percentage.'</th>
						<td data-title="wpdp-discount"><span class="woocommerce-Price-amount amount order_total"><span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span>'.($d_percentage?$d_total_discount_value:'0').'</span>'.$special_offer.'</td>
					</tr>';
					
				}
				
				

			}

			/**
			 * Main processing hooks
			 */
			public function woocommerce_loaded() {

				global $wdp_halt;

				if(!is_admin() && is_user_logged_in()){
					$wdp_get_current_user_role = wdp_get_current_user_role();
					//pree($wdp_get_current_user_role);
					$woocommerce_user_roles = get_option( 'woocommerce_user_roles', array() );
					//pree($woocommerce_user_roles);
					$wdp_halt = (!empty($woocommerce_user_roles) && in_array($wdp_get_current_user_role, $woocommerce_user_roles));
				}

				
				if($wdp_halt)
				return;
				
				
				if ( 
						in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ))) 
					&& 
						get_option( 'woocommerce_enable_plus_discounts', 'yes' ) == 'yes' 
				) {
					$this->currency = get_woocommerce_currency_symbol();
					$this->woocommerce_weight_unit = get_option('woocommerce_weight_unit');
					//if ( get_option( 'woocommerce_enable_plus_discounts', 'yes' ) == 'yes' ) {

					add_action( 'woocommerce_before_calculate_totals', array( $this, 'wdp_before_calculate' ), 10, 1 );
					add_action( 'woocommerce_calculate_totals', array( $this, 'wdp_after_calculate' ), 10, 1 );
					add_action( 'woocommerce_before_cart_table', array( $this, 'before_cart_table' ) );
					add_action( 'woocommerce_single_product_summary', array( $this, 'single_product_summary' ), 45 );
					add_filter( 'woocommerce_cart_item_subtotal', array( $this, 'filter_subtotal_price' ), 10, 2 );
					add_filter( 'woocommerce_checkout_item_subtotal', array( $this, 'filter_subtotal_price' ), 10, 2 );
					add_filter( 'woocommerce_order_formatted_line_subtotal', array( $this, 'filter_subtotal_order_price' ), 10, 3 );
					//if($this->per_product){
					add_filter( 'woocommerce_product_write_panel_tabs', array( $this, 'wdp_product_write_panel_tabs' ) );
					add_filter( 'woocommerce_product_data_panels', array( $this, 'wdp_product_write_panels' ) );
					add_action( 'woocommerce_process_product_meta', array( $this, 'wdp_process_meta' ) );
					//}
					add_filter( 'woocommerce_cart_product_subtotal', array( $this, 'filter_cart_product_subtotal' ), 10, 3 );
					add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'order_update_meta' ) );


					//add_action('woocommerce_cart_totals_before_shipping', array($this, 'wpdp_woocommerce_cart_totals_before_shipping'));
					add_action('woocommerce_cart_totals_before_order_total', array($this, 'wpdp_woocommerce_cart_totals_before_order_total'));
					add_action('woocommerce_review_order_before_shipping', array($this, 'wpdp_woocommerce_cart_totals_before_order_total'));
					
					//add_action( 'woocommerce_cart_totals_after_order_total', 'wdp_discount_after_totals');
					//add_action( 'woocommerce_review_order_after_order_total', 'wdp_discount_after_totals');					

					add_filter( 'woocommerce_cart_subtotal', array($this, 'wpdp_filter_woocommerce_cart_subtotal'), 10, 3 );
					
					add_filter('woocommerce_get_order_item_totals', array($this, 'discount_after_shipping_thank_you'), 10, 3);
					add_action('woocommerce_cart_totals_after_shipping', array($this, 'discount_after_shipping_cart'));
					add_action('woocommerce_review_order_after_shipping', array($this, 'discount_after_shipping_cart'));	

				
					if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0 ) {
						add_filter( 'woocommerce_cart_item_price', array( $this, 'filter_item_price' ), 11, 2 );
						add_filter( 'woocommerce_get_price_html', array( $this, 'filter_item_price_single' ), 10, 2 );
						//add_filter( 'woocommerce_cart_item_price', array( $this, 'filter_item_price' ), 10, 3 );

						add_filter( 'woocommerce_update_cart_validation', array( $this, 'filter_before_calculate' ), 10, 1 );
					} else {
						//add_filter( 'woocommerce_cart_item_price', array( $this, 'filter_item_price' ), 10, 2 );
						add_filter( 'woocommerce_cart_item_price_html', array( $this, 'filter_item_price' ), 10, 2 );
					}



					if($this->wdp_pro && class_exists('Woo_Discounts_Plus_Pro')){
						//$wdpp = new Woo_Discounts_Plus_Pro;
						//add_action( 'woocommerce_before_cart', array($wdpp, 'wdp_need_discount') );
					}else{
						$this->wdp_pro = false;
					}

				}

			}



			
			public function wdp_discount_after_totals() {
			   
			   global $woocommerce;
			   
			   $subTotal = $woocommerce->cart->total;
			
			   $totalamount = (float)$subTotal;
			
			   $codemanhouse_discount = $totalamount * 7/100;
			
			   foreach ( $woocommerce->cart->get_cart() as $cw_cart_key => $values) {
				   $_product = $values['data'];
				   if ( $_product->is_on_sale() ) {
						$regular_price = $_product->get_regular_price();
						$sale_price = $_product->get_sale_price();
						$discount = ($regular_price - $sale_price) * $values['quantity'];
						$codemanhouse_discount += $discount;
					}
			   }
			   if ( $codemanhouse_discount > 0 ) {
					echo '<tr class="cart-discount">
				<th>'. __( '7% Discount', "wcdp" ) .'</th>
				<td data-title=" '.get_wcdp_discount_label().' ">'
						. wc_price( $codemanhouse_discount + $woocommerce->cart->discount_cart ) .'</td>
				</tr>';
				echo '<tr>
				<th>'. __( 'Total Amount', "wcdp" ) .'</th>
				<td data-title=" '. __( 'Total Amount', "wcdp" ) .' ">'
						. wc_price( $totalamount - $codemanhouse_discount ) .'</td>
				</tr>';
				}
			} 

			/**
			 * Add action links under WordPress > Plugins
			 *
			 * @param $links
			 * @return array
			 */
			public function wdp_links( $links ) {

				$settings_slug = 'woocommerce';

				if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0 ) {

					$settings_slug = 'wc-settings';

				}

				$plugin_links = array(
					'<a href="' . admin_url( 'admin.php?page=' . $settings_slug . '&tab=plus_discount' ) . '">' . __( 'Settings', "wcdp" ) . '</a>',
				);

				return array_merge( $plugin_links, $links );
			}

			/**
			 * For given product, and quantity return the price modifying factor (percentage discount) or value to deduct (flat discount).
			 *
			 * @param $product_id
			 * @param $quantity
			 * @param $order
			 * @return float
			 */

			function gj_logic(){

				$ret = false;
				//Pro Feature
				return $ret;
			}

			protected function get_discounted_coeff( $product_id, $quantity, $composite_qty = array() ) {

				global $wdp_price_num_decimals;
				//pre($this->sale_applied());
//				pree($product_id. ' - ' .$quantity);pree($composite_qty);exit;

				$plus_discount_type_globally = (get_option( 'woocommerce_plus_discount_type', 'quantity' ));
				
				//pree($plus_discount_type_globally);
//				pree($plus_discount_type_globally);exit;
				switch($plus_discount_type_globally){
					default:
					case 'quantity':
						$wdpq_prefix = 'wdp_qd_';
						break;
					case 'weight':
						$wdpq_prefix = 'wdp_qdw_';
						break;
				}

//				pree($wdpq_prefix);exit;
				//pree(wc_get_product_ids_on_sale());

				if((in_array($product_id, wc_get_product_ids_on_sale()) && !$this->sale_applied()) || plus_discount_enabled($product_id, true)=='no'){
					$return = 1;
					return $return;
				}
				
				//pree($return);
				//pree($product_id.' - '.$quantity);

				global $s2_enabled, $s2_discounts;

				$wdpq = array();

				$fp = get_option( 'woocommerce_discount_type', '' );

				$s2member_discount = 0;
				//pree($s2_enabled.' && '.$s2_discounts);
				if($s2_enabled && $s2_discounts){
					$s2member_discount = wdp_s2member_discount();
					//pree($s2member_discount);
					$percentage = min( 1.0, max( 0, ( 100.0 - round( $s2member_discount, $wdp_price_num_decimals ) ) / 100.0 ) );
					$return = ($fp=='flat' ? $s2member_discount : $percentage);
					//pree($return);
					//pree($percentage);
					//pree($s2_enabled);exit;
					//pree($s2_discounts);pree($return);
					//return $return;
				}else{

					if($this->gj_logic()){
					}else{

					}

					$this->discounted_items[] = $product_id;
					
					$product_children = wc_get_product($product_id)->get_children();
					
					if(!empty($product_children)){
						$this->discounted_items = array_merge($product_children, $this->discounted_items);
					}
					//pree($this->discounted_items);

					$q = array( 0.0 );
					$d = array( 0.0 );

					$plus_discount_enabled = plus_discount_enabled($product_id, true);
					//pre($product_id.' - '.$plus_discount_enabled);




					switch($plus_discount_enabled){
						case 'default':
							$wdpq = get_option( 'wdp_qd', array() );//array();//
							//pree($wdpq);
							break;
						case 'category_based':
							//pre($product_id);
							$terms = get_the_terms( $product_id, 'product_cat' );
							//pre($terms);
							$dc_cat_id = get_post_meta($product_id, 'dc_cat_id', true);
							//pree($dc_cat_id);

							foreach ($terms as $term) {
								if($term->term_id == $dc_cat_id){
									$product_cat_id = $term->term_id;



									if($product_cat_id>0){
										$e_key = $wdpq_prefix.$product_cat_id;

										//pree($product_id.' - '.$e_key);

										$wdpq_temp = get_option( $e_key, array() );
										//pree($wdpq_temp);//exit;

										if(!empty($wdpq_temp)){
											$wdpq = $wdpq_temp;
										}
									}

								}

							}
							//pre($product_cat_id);



							//pre($wdpq);

							break;
					}

					//pre($wdpq);
					//pree($this->opts);
					for ( $i = 0; $i < $this->opts; $i++ ) {


						if($this->gj_logic()){
							//pre($this->qty_total);
							//pree($wdpq[$i]['q']);
							if(isset($wdpq[$i]) && isset($wdpq[$i]['q']) && $wdpq[$i]['q']>0){
								$qv = $wdpq[$i]['q'];
								$dv = $wdpq[$i]['d'];
								array_push( $q, $qv>0 ? $qv : 0.0 );
								array_push( $d, $dv>0 ? $dv : 0.0 );
								//pre($q);pre($d);
								//pre(count($q));
							}

						}else{

							//if($this->per_product){
							//pree($wdpq);
							switch($plus_discount_enabled){
								case 'default':
								case 'category_based':
									//pree($wdpq[$i]['q']);
									if(isset($wdpq[$i])){
										if($wdpq[$i]['q']>0){
											//pree($wdpq[$i]);
											//pree($q);pree($d);
											//percentage with global
											$qv = $wdpq[$i]['q'];
											$dv = $wdpq[$i]['d'];

											array_push( $q, $qv>0 ? $qv : 0.0 );
											array_push( $d, $dv>0 ? $dv : 0.0 );
											//pre($q);pre($d);
										}else{

										}
									}
									break;
								default:

									$qv = get_post_meta( $product_id, "plus_discount_quantity_$i", true );
									//pree($qv);
									array_push( $q,  $qv);

									//pre($q);

									if ( get_option( 'woocommerce_discount_type', '' ) == 'flat' ) {
										$dsf = get_post_meta( $product_id, "plus_discount_discount_flat_$i", true );
										//pre($i);
										//pre($d);
										//pree($dsf);
										array_push( $d,  $dsf? $dsf : 0.0 );
									} else {
										//percentage with per product
										$dv = get_post_meta( $product_id, "plus_discount_discount_$i", true );
										//pree($dv);


										array_push( $d, $dv?$dv:0.0 );
									}

									break;
							}

						}

						//pree($q);pree($d);
						//pree($quantity);
						//pree($product_id);
						//pree($composite_qty);
						$compare_qty = $quantity;
						//pre($compare_qty);
						//pre($composite_qty);
						if(!empty($composite_qty) && array_key_exists($product_id, $composite_qty)){
							$compare_qty = $composite_qty[$product_id];
						}
						//pree($composite_qty);
						//pre($compare_qty);

						if (!$this->gj_logic() && isset($q[$i]) && $compare_qty >= $q[$i] && $q[$i] > $q[0] ) {
							$q[0] = $q[$i];
							$d[0] = $d[$i];
						}
					}

					//pree($q);pree($d);//exit;

					// for percentage discount convert the resulting discount from % to the multiplying coefficient
					if( $fp == 'flat' ){
						$return = max( 0, $d[0] );
					}else{
						//pre($quantity); pre($composite_qty);
						$qty_considered = 0;
						if($this->gj_logic()){
							$qty_considered = $quantity;

							$dkey_guess = array_search($qty_considered, $q);
							if(!$dkey_guess){
								$q_arr = array();
								foreach($q as $q_item){
									if($q_item<=$qty_considered){
										$q_arr[] = $q_item;
									}
								}
								//pree($q_arr);
								$qty_considered = max($q_arr);
								//pre($qty_considered);
							}
						}else{
							$qty_considered = $quantity;
						}
						//pree($qty_considered);
						$dkey = array_search($qty_considered, $q);
						//pre($dkey);
						//pre($q);
						//pre($d);

						if(isset($d[$dkey])){
							$fm = (100.0 - round( $d[$dkey], $wdp_price_num_decimals ));

							//pre($qty_considered. ' - ' .$dkey. ' - ' .$d[$dkey]. ' - '. $fm );

							$return = min( 1.0, max( 0, ( $fm ) / 100.0 ) );
						}
					}


					//pre($return);

				}
				//$return = round($return, 2);
				//pree($return);
				//pree(get_post_meta( $product_id ));
				//exit;
				//pree($return);
				//pre($this->discounted_items);
				return $return;

			}

			/**
			 * Filter product price so that the discount is visible.
			 *
			 * @param $price
			 * @param $values
			 * @return string
			 */



			public function get_product_discount($product_id){
				//return;
				global $wdp_tiers_status, $wdp_discount_types, $woocommerce_variations_separate, $product_variations_qty, $wdp_price_num_decimals;

				//pree($product_id);
				//pree($wdp_discount_types);


				$disc_price = 0;
				$qty_disc_price = 0;
				$applied_bracket = 0;
				$is_flat = (get_option( 'woocommerce_discount_type', '' ) == 'flat');
				$quantity_ordered = $this->discount_love[$product_id]['quantity'];
				//pre($quantity_ordered);
				$_pf = new WC_Product_Factory();
				$_product = $_pf->get_product($product_id);
				$get_parent_id = ($_product->get_parent_id());

				$composite_quantity = ((!empty($product_variations_qty) && array_key_exists($get_parent_id, $product_variations_qty))?$product_variations_qty[$get_parent_id]:0);


				//pree($product_variations_qty);
				//pree($_product->parent->get_id());//not working



				if ($_product->is_type('variation')) {
					$pda = get_post_meta($_product->get_parent_id());
					$wdp_discount_type = $wdp_discount_types[$_product->get_parent_id()];
					//pree($_product->parent->id);
				}else{
					$pda = get_post_meta( $product_id );
					$wdp_discount_type = $wdp_discount_types[$product_id];
				}


				//pree($pda);
				$plus_discount_arr = array();
				if(!empty($pda)){
					foreach($pda as $k=>$v){
						//pree($k);pree($v);
						$pdq = substr($k, 0, strlen('plus_discount_quantity_'))=='plus_discount_quantity_';
						//pree($pdq);
						if(substr($k, 0, strlen('plus_discount_'))!='plus_discount_'){
							unset($pda[$k]);
						}elseif($pdq){
							$qty = current($v);
							//pre($qty);
							//pree($qty);
							if($qty>0){
								$ku = str_replace('plus_discount_quantity_', 'plus_discount_discount_'.($is_flat?'flat_':''), $k);
								//pree($ku);

								$vu = (isset($pda[$ku])?current($pda[$ku]):0);
								$plus_discount_arr[$qty] = $vu;

								//pree($quantity_ordered.' >= '.$qty.' ? '.$qty.' : '.$applied_bracket);

								$compare_quantity = ($composite_quantity?$composite_quantity:$quantity_ordered);

								$applied_bracket = $compare_quantity>=$qty?$qty:$applied_bracket;

								//pre($applied_bracket.' - '.$ab_inner);
								//$applied_bracket = $ab_inner;//($applied_bracket==0?$ab_inner:$applied_bracket);
							}
						}
					}

				}

				//pree($plus_discount_arr);
				//pree($applied_bracket);
				//pre($qty);
				//pre($this->discount_love);
				//pre($product_id);
				$orig_price = (isset($this->discount_love[$product_id]['orig_price_actual'])?$this->discount_love[$product_id]['orig_price_actual']:$this->discount_love[$product_id]['orig_price']);
				$orig_price = is_numeric($orig_price)?$orig_price:0;

				//pree($orig_price.' - '.$quantity_ordered.' - '.$wdp_price_num_decimals);
				$qty_price = round($orig_price*$quantity_ordered, $wdp_price_num_decimals);
				
				if($is_flat && $this->discount_love[$product_id]['discount_enabled'] == 'yes'){

					$applied_disc = (isset($plus_discount_arr[$applied_bracket])?$plus_discount_arr[$applied_bracket]:0);

				}else{
					$applied_disc = $this->discount_love[$product_id]['coeff'];
				}				

				//$applied_disc = (isset($plus_discount_arr[$applied_bracket])?$plus_discount_arr[$applied_bracket]:0);



				//pree($quantity_ordered.''.'>'.''.$applied_bracket);
				//pre($orig_price.''.'>'.''.$applied_bracket);
				//pre($wdp_tiers_status);
				//pree($applied_bracket.' - '.$quantity_ordered.' - '.$applied_bracket.' - '.$wdp_tiers_status);

				//pree($applied_disc);

				if($applied_bracket>0 && $quantity_ordered>$applied_bracket && $wdp_tiers_status){
					//pree($applied_disc);
					$tiers = floor($quantity_ordered/$applied_bracket);
					//pree($tiers);
					$applied_disc *= $tiers;
					//pree($applied_disc);
				}
				//pre($plus_discount_arr);
				//pre($applied_bracket);
				//pree($applied_disc);
				//pree($is_flat);
				//pree($wdp_discount_type);

				if($is_flat){
					//pre($wdp_discount_type);

					switch($wdp_discount_type){

						case 'weight':

							$disc_price = $orig_price;
							$qty_disc_price = round(($orig_price*$quantity_ordered)-$applied_disc, $wdp_price_num_decimals);

							break;

						default:
						case 'quantity':
							//exit;
							//pre($orig_price.' - '.$applied_disc);
							$disc_price = $orig_price-$applied_disc;
							//pre($disc_price);
							//pree($applied_disc);
							//$qty_disc_price = round($disc_price*$quantity_ordered, 2);
							$qty_disc_price = round(($orig_price*$quantity_ordered)-$applied_disc, $wdp_price_num_decimals);

							//(($this->discount_love[$ac_id]['orig_price']*$values['quantity'])-$flat_discounted);
							//$applied_disc *= $quantity_ordered;
							//$applied_disc = round(($orig_price*$quantity_ordered)-$applied_disc, 2);
							break;

					}


					//pre($disc_price.' | '.$qty_disc_price.' | '.$applied_disc);

				}elseif($qty_price>0){
					$qty_disc_price = ($qty_price-$applied_disc);
					$disc_price = ($qty_disc_price/$quantity_ordered);

					//pree($qty_price.' - '.$applied_disc);
					//pree($qty_disc_price.' - '.$quantity_ordered);
					//pree($disc_price);

				}
				//pre($disc_price);
				//pre($qty_disc_price);

				//disc_price_qty_flat

				//$disc_price = round($disc_price, 2);
				//pre($disc_price.' - '.$qty_disc_price.' - '.$applied_disc);


				$this->discount_love[$product_id]['disc_price'.($is_flat?'_flat':'')] = $disc_price;
				$this->discount_love[$product_id]['disc_price_qty'.($is_flat?'_flat':'')] = $qty_disc_price;
				$this->discount_love[$product_id]['disc_amount'.($is_flat?'_flat':'')] = $applied_disc;

				//pre($product_id);
				//pre($this->discount_love);
//				pree($this->discount_love[$product_id]);



			}

			public function filter_item_price( $price, $values ) {
				//pre($price);
				//pre(__METHOD__);
				//$discprice!=$oldprice && $discprice<$oldprice
				global $wdp_new_price, $wdp_price_num_decimals, $s2_enabled;
//				pree($price);pree($values);exit;
				//pre($this->discounted_items);
				//pre($price);
				//pre($wdp_new_price);
				$discprice_raw = $oldprice_raw = 0;
				
				$is_flat = (get_option( 'woocommerce_discount_type', '' ) == 'flat');
				$_product = $values['data'];
				$ac_id = $this->get_product_id( $_product );
				$this->gather_discount_love();
				$this->get_product_discount($ac_id);
				$coeff = $this->discount_love[$ac_id]['coeff'];

				$this->discount_love[$ac_id]['orig_price_actual'] = (isset($this->discount_love[$ac_id]['orig_price_actual'])?$this->discount_love[$ac_id]['orig_price_actual']:$this->discount_love[$ac_id]['orig_price']);

//				pree($coeff);
//				pree($this->discount_love);exit;
				//pree($values);
				if ( !$values || @!$values['data'] || in_array($_product->get_id(), wc_get_product_ids_on_sale()) || ($coeff==0 && !$is_flat)) {
					return $price;
				}

				if ( $this->coupon_check() ) {
					return $price;
				}

				if (!wcdp_is_product_valid($ac_id)){//$_product->id)) {
					return $price;
				}

				if (!plus_discount_enabled($ac_id)){//$_product->id)) {
					return $price;
				}

				/*if ( ( get_option( 'woocommerce_show_on_item', 'yes' ) == 'no' ) ) {
					return $price;
				}*/
				//pre(get_option( 'woocommerce_discount_type', '' ));
				if ($is_flat) {
					//return $price; // for flat discount this filter has no meaning
				}
				//pre($this->discount_love);
				//pre($this->discount_love[$ac_id]);
				if ( empty( $this->discount_love ) || !isset( $this->discount_love[$ac_id] )
				     || !isset( $this->discount_love[$ac_id]['orig_price'] ) || !isset( $this->discount_love[$ac_id]['coeff'] )
				) {
					$this->gather_discount_love();
				}


				if ( $coeff == 1.0 && !$is_flat) {
					return $price; // no price modification
				}


				//pree($values['quantity']);

				//pree($discprice);

				if ( $is_flat ) {
					$flat_less = $this->discount_love[$ac_id]['flat_less'];
					//pre($flat_less);
					//pree($this->discount_love);
					//exit;
					//pree($this->discount_love);exit;
					//pree($flat_less);exit;
					//pree($this->discount_love[$ac_id]);
					$_regular_price = $this->discount_love[$ac_id]['orig_price'];
					$dprice = $_regular_price-$flat_less;
					$discprice_raw = $dprice;
					$discprice = $oldprice = wdp_get_formatted_price( $discprice_raw );

					//pree($_regular_price.' - '.$dprice.' - '.$discprice);

					if($flat_less>0){
						
						
						$discprice_raw = number_format((float)($this->discount_love[$ac_id]['disc_price_qty_flat']/$this->discount_love[$ac_id]['quantity']), $wdp_price_num_decimals);
						$oldprice_raw = number_format((float)($this->discount_love[$ac_id]['orig_price_actual']), $wdp_price_num_decimals);
						//pre($ac_id);
						//pre($this->discount_love);
						//pre($this->discount_love[$ac_id]);
						$discprice = wdp_get_formatted_price($discprice_raw);
						//wdp_get_formatted_price( round($this->discount_love[$ac_id]['disc_price_flat'], 2) );
						//pre($_product->get_price());
						//pre($this->discount_love[$ac_id]['orig_price_actual']);
						$oldprice = wdp_get_formatted_price($oldprice_raw);
					}
					//pre($discprice);
					//pre($oldprice);
					//pree($discprice.' - Flat');

				}else{
					//$_regular_price = $this->discount_love[$ac_id]['orig_price'];
					//pre($this->discount_love[$ac_id]);
					//pre($_product->get_price().' - '.$coeff);
					//pree($this->discounted_items);
					if(in_array($_product->get_id(), $this->discounted_items)){
						//pre($_product->get_price());
						//$get_price = round($_product->get_price(), 2);
						$discprice_raw = number_format((float)$_product->get_price(), $wdp_price_num_decimals);
						$discprice = wdp_get_formatted_price($discprice_raw);
						//pre('A');
					}else{
						//pre($_product->get_price().' == '.$this->discount_love[$ac_id]['orig_price']);
						$discprice_raw = ($_product->get_price()==$this->discount_love[$ac_id]['orig_price_actual']?( number_format((float)($_product->get_price() * $coeff), 2) ):number_format((float)$this->discount_love[$ac_id]['orig_price_actual'], $wdp_price_num_decimals));
						$discprice = wdp_get_formatted_price($discprice_raw);
						//pre('B');
					}
					$oldprice_raw = number_format((float)($this->discount_love[$ac_id]['orig_price_actual']), $wdp_price_num_decimals);
					$oldprice = wdp_get_formatted_price($oldprice_raw); //orig_price 07/06/2019

					//pre($discprice.' - '.$oldprice);
					//pre($discprice.' - %');
				}

				//pree($discprice. '- Out');

				$old_css = esc_attr( get_option( 'woocommerce_css_old_price', 'color: #777; text-decoration: line-through; margin-right: 4px;' ) );

				$new_css = esc_attr( get_option( 'woocommerce_css_new_price', 'color: #4AB915; font-weight: bold;' ) );

				$np = $oldprice;
				
				
				
				if(($discprice_raw!=$oldprice_raw && $discprice_raw<$oldprice_raw)){//  && is_cart()
					$np = "<span class='discount-info' title='" . sprintf( ' '.__( 'Discounts Plus applied!', "wcdp" ), number_format((float)( 1.0 - $coeff ) * 100.0, $wdp_price_num_decimals ) ) . "'>" .
					      "<span class='old-price' style='$old_css' data-block='liza'>$oldprice</span>" .
					      "<span class='new-price on' style='$new_css'>".$discprice."</span></span>";
				}elseif($s2_enabled){
					$s2member_discount = wdp_s2member_discount();

					$np = "<span class='discount-info'>" .
					      "<span class='new-price on' style='$new_css'>".$s2member_discount.($is_flat?'':'%').' '.__('OFF', 'woocommerce-discounts-plus')."</span></span>";
				}else{
					$np = $oldprice;
				}
				//pree($this->discount_love);
				//pree($wdp_new_price.' - '.$np.' - '.$oldprice.' - '.$discprice.' - '.$_regular_price);
				//pree($np);
				$return = ($wdp_new_price?$np:$oldprice);
				return $return;

			}


			/**
			 * Filter product price so that the discount is visible.
			 *
			 * @param $price
			 * @param $values
			 * @return string
			 */


			public function filter_subtotal_price( $price, $values ) {
				//return $price;

				global $wdp_price_num_decimals, $wdp_cart_total_discount;


				if($this->gj_logic() || !$this->sale_applied()){
					return $price;
				}
				//pre($price);pre($values);
				$is_flat = (get_option( 'woocommerce_discount_type', '' ) == 'flat');

				if ( !$values || !$values['data'] ) {
					return $price;
				}
				if ( $this->coupon_check() ) {
					return $price;
				}
				$_product = $values['data'];
				$ac_id = $this->get_product_id( $_product );
				//pre(__METHOD__);

				if (!plus_discount_enabled($ac_id)){//$_product->id)) {
					return $price;
				}

				if(!wcdp_is_product_valid($ac_id)){
					return $price;
				}
				
				if ( ( get_option( 'woocommerce_show_on_subtotal', 'yes' ) == 'no' ) ) {
					return $price;
				}

				if ( empty( $this->discount_love ) || !isset( $this->discount_love[$ac_id] )
				     || !isset( $this->discount_love[$ac_id]['orig_price'] ) || !isset( $this->discount_love[$ac_id]['coeff'] )
				) {
					$this->gather_discount_love();
				}

				$ac_id = $this->get_product_id( $_product );
				$coeff = $this->discount_love[$ac_id]['coeff'];

				$percent_discounted = '';
				//pree($this->discount_love);exit;
				//pree($coeff);




				if ( ( $is_flat && $coeff == 0 ) || ( get_option( 'woocommerce_discount_type', '' ) == '' && $coeff == 1.0 ) ) {


					return $price; // no price modification
				}

				$new_css = esc_attr( get_option( 'woocommerce_css_new_price', 'color: #4AB915; font-weight: bold;' ) );



				if($is_flat){
					//pree($this->discount_love);
					$flat_discounted = $this->discount_love[$ac_id]['disc_amount_flat'];//($this->discount_love[$ac_id]['disc_amount_flat']!=''?$this->discount_love[$ac_id]['disc_amount_flat']:$this->discount_love[$ac_id]['flat_less']);
					$flat_price = $this->discount_love[$ac_id]['disc_price_qty_flat'];//(($this->discount_love[$ac_id]['orig_price']*$values['quantity'])-$flat_discounted);
					$price = wdp_get_formatted_price($flat_price);
					//pree($flat_discounted.' | '.$flat_price.' | '.$price);
				}else{
					$percent_discounted = round( ( 1 - $coeff ) * 100, $wdp_price_num_decimals );
				}

				//pree($percent_discounted);

				if($is_flat){
					$plus_info = sprintf('%s '.__('With discount', "wcdp" ), ( $is_flat ? wdp_get_formatted_price($flat_discounted) : (  $percent_discounted. "%" ) ) );					
					
					$wdp_cart_total_discount += $flat_discounted;
				}else{
					$plus_info = __('After', "wcdp" ).' '.sprintf('%s '.__('discount', "wcdp" ), ( $is_flat ? wdp_get_formatted_price($flat_discounted) : (  $percent_discounted. "%" ) ) );
					
					$qty = $this->discount_love[$ac_id]['quantity'];
					$dpc = ((($this->discount_love[$ac_id]['orig_price_actual']*$qty)*$percent_discounted)/100);
					$wdp_cart_total_discount += $dpc;//$this->discount_love[$ac_id]['disc_price']
					
					
					
					//$wdp_cart_total_discount += $flat_discounted;
				}
				
				//pree($wdp_cart_total_discount);

				$show_w = "<span class='discount-info' title='$plus_info'>" .
				          "<span>$price</span>" .
				          "<span class='new-price tw' style='$new_css' data-block='loco'> ($plus_info)</span></span>";
						  

				return $show_w;
			}



			/**
			 * Hook to woocommerce_cart_product_subtotal filter.
			 *
			 * @param $subtotal
			 * @param $_product
			 * @param $quantity
			 * @param WC_Cart $cart
			 * @return string
			 */
			public function filter_cart_product_subtotal( $subtotal, $_product, $quantity ) { //cart per line

				global $wdp_price_num_decimals;
				//pre($subtotal);
				//$this->discounted_items[] = 83;
				//return 0;
				//pre($subtotal);pre($_product);pre($quantity);
				//pre($this->discounted_items);
				$ac_id = $this->get_product_id( $_product );



				if(in_array($_product->get_id(), $this->discounted_items))
					return $subtotal;

//			pree($subtotal);exit;

				//pre($ac_id);exit;

				if ( !$_product || !$quantity ) {
					return $subtotal;
				}
				if ( $this->coupon_check() ) {
					return $subtotal;
				}
				//pre(__METHOD__);

				//pre($subtotal);

				if (!plus_discount_enabled($ac_id)){//$_product->id)) {
					return $subtotal;
				}

				//pree($subtotal);

				$coeff = $this->discount_love[$ac_id]['coeff'];
				$is_flat = ( get_option( 'woocommerce_discount_type', '' ) == 'flat' );
				if ($is_flat) {
					//pre($_product->get_price() .' * '. $quantity .' * '. $coeff);
					$newsubtotal = wdp_get_formatted_price(number_format((float)(max( 0, ( $_product->get_price() * $quantity ) - $coeff )), $wdp_price_num_decimals) );
				} else {
					//pre($_product->get_price() .' * '. $quantity .' * '. $coeff);
					//$_product->get_price() to orig_price_actual on 07/06/2019
					$newsubtotal = wdp_get_formatted_price( number_format((float)($this->discount_love[$ac_id]['orig_price_actual'] * $quantity), $wdp_price_num_decimals) );//* $coeff //fm 02/08/2018 as the price has already been multiplied by $coeff and now only qty multiplication required
				}
//			pree($newsubtotal);exit;
				return $newsubtotal;

			}

			public function filter_item_price_single( $price_html, $product ) {

				global $woocommerce, $wdp_new_price_sp, $wdp_new_price_shop;

				if(!is_admin() && ((is_product() && $wdp_new_price_sp) || (is_shop() && $wdp_new_price_shop))){

					$items = $woocommerce->cart->get_cart();

					if(!empty($items)){

						foreach($items as $item => $values) {
							if($product->get_id()==$values['product_id']){
								$_product = wc_get_product( $values['product_id'] );
								$price_html = $this->filter_item_price($_product->get_price(), $values);
							}
						}
					}

				}
				//pree($price_html);
				return $price_html;
			}

			/**
			 * Gather discount information to the array $this->discount_coefs
			 */
			protected function gather_discount_love() {



				global $woocommerce, $wdp_discount_types, $woocommerce_variations_separate, $product_variations_qty;



				$all_qty = array();
				$cart = $woocommerce->cart;
				$this->discount_love = (!empty($this->discount_love)?$this->discount_love:array());

				$is_flat = (get_option( 'woocommerce_discount_type', '' ) == 'flat');
				$plus_discount_type_globally = (get_option( 'woocommerce_plus_discount_type', 'quantity' ));

				if ( sizeof( $cart->cart_contents ) > 0 ) {

					//pre(count($cart->cart_contents));
					//pree($cart->cart_contents);
					//echo count($cart->cart_contents);exit;
					$q = 0;
					foreach ( $cart->cart_contents as $cart_item_key => $values ) { $q++;
						//pree($cart_item_key);
						//pree($values);
						$_product = $values['data'];
						$ac_id = $this->get_product_id( $_product );
						$actual_id = $this->get_actual_id( $_product );
						
						$plus_discount_enabled = plus_discount_enabled($actual_id, true);
						$plus_discount_type = plus_discount_type($actual_id, true); //using actual product ID for global/product settings instead of each variation to avoid complexity
						$plus_discount_type = ($plus_discount_type==$plus_discount_type_globally?$plus_discount_type_globally:'quantity');
						$wdp_discount_types[$actual_id] = $plus_discount_type; //an extra item in this array to cover main/actual product ID
						$wdp_discount_types[$ac_id] = $plus_discount_type;
						
						//pree($actual_id.' - '.$plus_discount_type);

						$quantity = 0;
						//pree($_product instanceof WC_Product_Variation && $_product->parent);exit;


						if ($woocommerce_variations_separate == 'no' && $_product instanceof WC_Product_Variation && $_product->parent ) {
							$parent = $_product->parent;

							$plus_discount_type_inner = plus_discount_type($parent->get_id(), true);
							$plus_discount_type_inner = ($plus_discount_type_inner==$plus_discount_type_inner?$plus_discount_type_globally:'quantity');
							//pree($plus_discount_type_inner);
							$wdp_discount_types[$parent->get_id()] = $plus_discount_type_inner;
							//pree($parent->get_id());
							//pree($ac_id);
							

							//
							//pree($parent->id);
							//pree($cart->cart_contents);exit;
							//echo count($cart->cart_contents);exit;
							//foreach ( $cart->cart_contents as $valuesInner ) { //pree($q);
							//pree($valuesInner);
							//$p = $valuesInner['data'];
							//if ( $p instanceof WC_Product_Variation && $p->parent && $p->parent->id == $parent->id ) {
							//pree($valuesInner['quantity']);//exit;
							//$quantity += $valuesInner['quantity'];
							//$quantity = $valuesInner['quantity'];
							//pree($values);
							$quantity = $values['quantity'];
							//pree($quantity);
							$this->discount_love[$_product->variation_id]['quantity'] = $quantity;

							//pree($_product->get_weight());
							switch($plus_discount_type_inner){
								default:
								case 'quantity':
									$quantity = $values['quantity'];
									$product_variations_qty[$parent->get_id()] += $quantity;
									$all_qty[] = $product_variations_qty[$parent->get_id()];
									break;
								case 'weight':
									$quantity = ($values['quantity']*$_product->get_weight());
									$all_qty[] = $quantity;
									break;
							}

							//pree($this->discount_love);
							//pree($all_qty);
							//pree($quantity);
							//}
							//}

						} else {
							switch($plus_discount_type){
								default:
								case 'quantity':
									$quantity = $values['quantity'];
									
								break;
								case 'weight':
									$quantity = ($values['quantity']*$_product->get_weight());

								break;
							}
							$all_qty[] = $quantity;
							//pree($quantity);
						}

						//pre($all_qty);
						//pree($product_variations_qty);
						$max = max($all_qty);
						//pree($max);
						//pree($this->opts);
						if($max>$this->opts)
							$this->opts = $max;

						//$ac_id = $this->get_product_id( $_product );
						//pre($ac_id);
						//pre($this->discount_love);
						//pree($this->opts);
						//pree($this->qty_total);
						$this->discount_love[$ac_id]['base_weight'] = $_product->get_weight();
						$this->discount_love[$ac_id]['discount_enabled'] = $plus_discount_enabled;
						//pre($this->discount_love[$ac_id]['base_weight']);

						if($this->gj_logic()){
							//pre($this->get_actual_id($_product));
							//pree($this->qty_total);
							//pre($quantity);
							//pree($plus_discount_type);
							switch($plus_discount_type){
								default:
								case 'quantity':
									//pre($this->qty_total);
									$this->discount_love[$ac_id]['coeff'] = $this->get_discounted_coeff( $this->get_actual_id($_product), $this->qty_total );
									//pree($this->get_actual_id($_product) . ' - ' .$this->discount_love[$ac_id]['coeff']);
								break;
								case 'weight':
									//pre($this->qty_total);
									//pre($this->discount_love);
									$qty_updated = $this->qty_total;//($this->discount_love[$ac_id]['base_weight']*$this->qty_total);
									//pre($qty_updated);
									$this->discount_love[$ac_id]['coeff'] = $this->get_discounted_coeff( $this->get_actual_id($_product), $qty_updated );
								break;
							}
							//$_product->get_id()
							//pre($this->discount_love[$ac_id]['coeff']);
						}else{
							//pree($this->get_actual_id($_product).' - '.$quantity);
							//pree($product_variations_qty);
							//pree($this->qty_total);
							//pree($this->get_actual_id($_product).' - '.$quantity.' - '.$product_variations_qty);
							$coeff = $this->get_discounted_coeff( $this->get_actual_id($_product), $quantity, $product_variations_qty); //$_product->get_id();
							//pree($coeff);

							$this->discount_love[$ac_id]['coeff'] = $coeff;
							//pree($this->get_product_id($_product).' - '.$this->get_actual_id($_product).' - '.$this->discount_love[$ac_id]['coeff']);
						}

						//pre($ac_id);
						//pre($this->discount_love[$ac_id]);
						$this->discount_love[$ac_id]['orig_price'] = $_product->get_price();
						$this->discount_love[$ac_id]['orig_price'] = is_numeric($this->discount_love[$ac_id]['orig_price'])?$this->discount_love[$ac_id]['orig_price']:0;

						$this->discount_love[$ac_id]['quantity'] = $quantity;

						//pre($this->discount_love[$ac_id]);
						//pree($is_flat);

						if($is_flat){
							//pree($this->discount_love[$ac_id]);
							//pree($quantity);
							$flat_less = $this->discount_love[$ac_id]['coeff'];
							//pree($flat_less);
						}else{

							$flat_less = ((($quantity*$this->discount_love[$ac_id]['orig_price'])-$this->discount_love[$ac_id]['coeff'])/$quantity);
						}


						$this->discount_love[$ac_id]['flat_less'] = $flat_less;



						//pree($this->discount_love);
					}
					//pree($this->discount_love);

				}

				//pree(max($all_qty));
//				pree($this->discount_love);exit;

			}

			/**
			 * Filter product price so that the discount is visible during order viewing.
			 *
			 * @param $price
			 * @param $values
			 * @return string
			 */
			public function filter_subtotal_order_price( $price, $values, $order ) {
				global $wdp_price_num_decimals;

				//return $price;
				//pre($price);pre($values);pre($order);

				if ( !$values || !$order ) {
					return $price;
				}
				if ( $this->coupon_check() ) {
					return $price;
				}
				//pre($values);
				$_product = wc_get_product( $values['product_id'] );
				//pre(__METHOD__);
				if (!plus_discount_enabled($values['product_id'])) {
					return $price;
				}
				if ( ( get_option( 'woocommerce_show_on_order_subtotal', 'yes' ) == 'no' ) ) {
					return $price;
				}
				$actual_id = $values['product_id'];
				if ( $_product && $_product instanceof WC_Product_Variable && $values['variation_id'] ) {
					$actual_id = $values['variation_id'];
				}
				if(!wcdp_is_product_valid($actual_id)){
					return $price;
				}
				$discount_love = $this->gather_discount_love_from_order( $order->get_id() );
				if ( empty( $discount_love ) ) {
					return $price;
				}
				$discount_type = get_post_meta( $order->get_id(), '_woocommerce_discount_type', true );
				@$coeff = ($discount_type == 'flat' ? $discount_love[$actual_id]['disc_amount_flat'] : $discount_love[$actual_id]['coeff']);
				//@$coeff = $discount_love[$actual_id]['coeff'];
				if ( !$coeff ) {
					return $price;
				}
				//$discount_type = get_post_meta( $order->get_id(), '_woocommerce_discount_type', true );
				if ( ( $discount_type == 'flat' && $coeff == 0 ) || ( $discount_type == '' && $coeff == 1.0 ) ) {
					return $price; // no price modification
				}
				$new_css = esc_attr( get_option( 'woocommerce_css_new_price', 'color: #4AB915; font-weight: bold;' ) );

				if($discount_type == 'flat'){
					$plus_info = sprintf('%s '. __( 'With discount', "wcdp" ), ( $discount_type == 'flat' ? wdp_get_formatted_price($coeff) : ( round( ( 1 - $coeff ) * 100, $wdp_price_num_decimals ) . "%" ) ) );
				}else{
					$plus_info = __( 'After', "wcdp" ).' '.sprintf('%s '. __( 'discount', "wcdp" ), ( $discount_type == 'flat' ? wdp_get_formatted_price($coeff) : ( round( ( 1 - $coeff ) * 100, $wdp_price_num_decimals ) . "%" ) ) );
				}


				return "<span class='discount-info' title='$plus_info'>" .
				       "<span>$price</span>" .
				       "<span class='new-price th' style='$new_css' data-block='teco'> ($plus_info)</span></span>";

			}

			/**
			 * Gather discount information from order.
			 *
			 * @param $order_id
			 * @return array
			 */
			protected function gather_discount_love_from_order( $order_id ) {

				$meta = get_post_meta( $order_id, '_woocommerce_discount_love', true );

				if ( !$meta ) {
					return null;
				}

				$order_discount_love = json_decode( $meta, true );
				//pree($order_discount_love);
				return $order_discount_love;

			}

			/**
			 * Hook to woocommerce_before_calculate_totals action.
			 *
			 * @param WC_Cart $cart
			 */
			//CART TOTAL
			public function wdp_before_calculate( WC_Cart $cart ) {
				
				global $wdp_discount_types, $wdp_price_num_decimals;
				if ( $this->coupon_check() ) {
					return;
				}

				if ($this->plus_discount_calculated) {
					return;
				}

				$this->gather_discount_love();

				if ( sizeof( $cart->cart_contents ) > 0 ) {

					foreach ( $cart->cart_contents as $cart_item_key => $values ) {
						$_product = $values['data'];
						$ac_id = $this->get_product_id( $_product );
						$this->get_product_discount($ac_id);
						//pre(__METHOD__);
						if (!plus_discount_enabled($ac_id)){//$_product->id)) {
							continue;
						}
						if(!wcdp_is_product_valid($ac_id)){
							$this->discount_love_unset[$ac_id] = $this->discount_love[$ac_id];							
							unset($this->discount_love[$ac_id]);							
							continue;
						}
						$wdp_discount_type = $wdp_discount_types[$ac_id];

						$is_flat = (get_option( 'woocommerce_discount_type', '' ) == 'flat');

						$this->discount_love[$ac_id]['orig_price_actual'] = $this->discount_love[$ac_id]['orig_price'];

						if($is_flat){
							//pree($this->discount_love);
							switch($wdp_discount_type){

								case 'weight':


									$row_base_price = $this->discount_love[$ac_id]['disc_price_qty_flat']/$this->discount_love[$ac_id]['quantity'];
									//pree($row_base_price);
								break;

								case 'quantity':
									$coeff = $this->discount_love[$ac_id]['flat_less'];
									$orig_price = $this->discount_love[$ac_id]['orig_price'];
									//pre($this->discount_love);
									$row_base_price = ($this->discount_love[$ac_id]['disc_price_qty_flat']/$this->discount_love[$ac_id]['quantity']);//$orig_price;//-$coeff;//$this->discount_love[$ac_id]['disc_price_flat'];//
									//pre($this->discount_love);
									//pree($row_base_price);//exit;
								break;
							}

							//pree($row_base_price);

						} else {
							//pre($_product->get_price() .' * '. $this->discount_love[$ac_id]['coeff']);
							$row_base_price = $_product->get_price() * $this->discount_love[$ac_id]['coeff'];
						}

						//pre($row_base_price);
						$row_base_price = round($row_base_price,$wdp_price_num_decimals);
						$values['data']->set_price( $row_base_price );
					}

					$this->plus_discount_calculated = true;

				}
				//pree($values);
			}

			public function filter_before_calculate( $res ) {

				global $woocommerce, $wdp_price_num_decimals;
				//pree($res);
				if ($this->plus_discount_calculated) {
					return $res;
				}

				$cart = $woocommerce->cart;

				if ( $this->coupon_check() ) {
					return $res;
				}

				$this->gather_discount_love();

				if ( sizeof( $cart->cart_contents ) > 0 ) {
					//pre(__METHOD__);
					foreach ( $cart->cart_contents as $cart_item_key => $values ) {
						$_product = $values['data'];
						$ac_id = $this->get_product_id( $_product );

						if (!plus_discount_enabled($ac_id)){//$_product->id)) {
							continue;
						}
						$is_flat = ( get_option( 'woocommerce_discount_type', '' ) == 'flat' );
						if ($is_flat) {
							$row_base_price = max( 0, $_product->get_price() - ( $this->discount_love[$ac_id]['coeff'] / $values['quantity'] ) );
						} else {
							$row_base_price = $_product->get_price() * $this->discount_love[$ac_id]['coeff'];
						}

						//pre($row_base_price );
						$row_base_price = round($row_base_price,$wdp_price_num_decimals);
						$values['data']->set_price( $row_base_price );
						//pree($values);
					}

					$this->plus_discount_calculated = true;

				}

				//pree($res);


				return $res;

			}

			/**
			 * @param $product
			 * @return int
			 */
			protected function get_actual_id( $product ) {
				$ret = 0;

				$parent_id = (is_numeric($product)?0:$product->get_parent_id());

				if($parent_id>0){
					$ret = $parent_id;
				}else{
					$ret = $this->get_product_id( $product );
				}
				return $ret;
			}
			protected function get_product_id( $product ) {

				if ( $product instanceof WC_Product_Variation  && !method_exists($product, 'get_id')) {
					return $product->get_variation_id();
				} elseif(method_exists($product, 'get_id')) {
					return $product->get_id();
				}elseif(isset($product->id)){
					return $product->id;
				}else{
					//LEAVING THIS SECTION EMPTY FOR NOW
					return 0;
				}

			}

			/**
			 * Hook to woocommerce_calculate_totals.
			 *
			 * @param WC_Cart $cart
			 */
			public function wdp_after_calculate( WC_Cart $cart ) {

				//pree($cart);
				//return;
				if ( $this->coupon_check() ) {
					return;
				}

				if ( sizeof( $cart->cart_contents ) > 0 ) {
					//pre(__METHOD__);
					foreach ( $cart->cart_contents as $cart_item_key => $values ) {
						$_product = $values['data'];
						$ac_id = $this->get_product_id( $_product );
						//pree(plus_discount_enabled($ac_id));
						if (!plus_discount_enabled($ac_id)){//$_product->id)) {
							continue;
						}
						//pree($values);
						//pree($_product);

						if($this->display_dicounted_in_cart){
						}else{
							$values['data']->set_price( $this->discount_love[$ac_id]['orig_price'] );
						}
						//pre($this->discount_love[$ac_id]['orig_price']);
					}
				}

				//pree($this->discount_love);

			}

			/**
			 * Show discount info in cart.
			 */
			public function before_cart_table() {

				if ( get_option( 'woocommerce_cart_info' ) != '' ) {
					echo "<div class='cart-show-discounts'>";
					echo get_option( 'woocommerce_cart_info' );
					echo "</div>";
				}

			}




			/**
			 * Store discount info in order as well
			 *
			 * @param $order_id
			 */
			public function order_update_meta( $order_id ) {

				update_post_meta( $order_id, "_woocommerce_discount_type", get_option( 'woocommerce_discount_type', '' ) );
				update_post_meta( $order_id, "_woocommerce_discount_love", json_encode( wdp_sanitize_arr_data($this->discount_love) ) );

			}

			/**
			 * Display discount information in Product Detail.
			 */
			public function single_product_summary() {

				global $thepostid, $post;
				if ( !$thepostid ) $thepostid = $post->ID;

				echo "<div class='productinfo-show-discounts'>";
				echo esc_html(get_post_meta( $thepostid, 'plus_discount_text_info', true ));
				echo "</div>";

			}

			/**
			 * Add entry to Product Settings.
			 */
			public function wdp_product_write_panel_tabs() {

				$style = '';

				if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0 ) {
					$style = 'style = "padding: 10px !important"';
				}

				echo '<li class="discounts_plus_tab discounts_plus_options"><a href="#discounts_plus_product_data" '.$style.'><span>' . __( 'Discounts Plus', "wcdp"). ($this->wdp_pro?'+':'') . '</span></a></li>';

			}


			public function wdp_global_panels() {


				//pree($this->opts);
				?>
				<script type="text/javascript">
                    jQuery( document ).ready( function () {
                        var e = jQuery( '#discounts_plus_product_data' );
						<?php
						for($i = 1; $i <= $this->opts; $i++) :
						?>
                        e.find( '.block<?php echo esc_attr($i); ?>' ).hide();
                        e.find( '.options_group<?php echo esc_attr(max($i, 2)); ?>' ).hide();
                        e.find( '#def_disc_criteria<?php echo esc_attr(max($i, 2)); ?>' ).hide();
                        e.find( '#def_disc_criteria<?php echo esc_attr($i); ?>' ).click( function () {
                            /*if ( <?php echo esc_attr($i); ?> == 1 || ( e.find( '#plus_discount_quantity_<?php echo esc_attr(max($i-1, 1)); ?>' ).val() != '' &&
							<?php if ( get_option( 'woocommerce_discount_type', '' ) == 'flat' ) : ?>
							e.find( '#plus_discount_discount_flat_<?php echo esc_attr(max($i-1, 1)); ?>' ).val() != ''
						<?php else: ?>
						e.find( '#plus_discount_discount_<?php echo esc_attr(max($i-1, 1)); ?>' ).val() != ''
						<?php endif; ?>
						) )
						{*/
                            e.find( '.block<?php echo esc_attr($i); ?>' ).show();
                            e.find( '.options_group<?php echo esc_attr(min($i+1, $this->opts)); ?>' ).show();
                            e.find( '#def_disc_criteria<?php echo esc_attr(min($i+1, ($this->opts-1))); ?>' ).show();
                            e.find( '#def_disc_criteria<?php echo esc_attr($i); ?>' ).hide( );
                            e.find( '#delete_discount_line<?php echo esc_attr(min($i+1, $this->opts)); ?>' ).show();
                            e.find( '#delete_discount_line<?php echo esc_attr($i); ?>' ).hide( );
                            /*}
							else
							{
								alert( '<?php _e( 'Please fill in the current line before adding new line.', "wcdp" ); ?>' );
						}*/
                        } );
                        e.find( '#delete_discount_line<?php echo esc_attr(max($i, 1)); ?>' ).hide();
                        e.find( '#delete_discount_line<?php echo esc_attr($i); ?>' ).click( function () {
                            e.find( '.block<?php echo esc_attr(max($i-1, 1)); ?>' ).hide( );
                            e.find( '.options_group<?php echo esc_attr(min($i, $this->opts)); ?>' ).hide( );
                            e.find( '#def_disc_criteria<?php echo esc_attr(min($i, ($this->opts-1))); ?>' ).hide( );
                            e.find( '#def_disc_criteria<?php echo esc_attr(max($i-1, 1)); ?>' ).show();
                            e.find( '#delete_discount_line<?php echo esc_attr(min($i, $this->opts)); ?>' ).hide( );
                            e.find( '#delete_discount_line<?php echo esc_attr(max($i-1, 2)); ?>' ).show();
                            e.find( '#plus_discount_quantity_<?php echo esc_attr(max($i-1, 1)); ?>' ).val( '' );
							<?php
							if ( get_option( 'woocommerce_discount_type', '' ) == 'flat' ) :
							?>
                            e.find( '#plus_discount_discount_flat_<?php echo esc_attr(max($i-1, 1)); ?>' ).val( '' );
							<?php else: ?>
                            e.find( '#plus_discount_discount_<?php echo esc_attr(max($i-1, 1)); ?>' ).val( '' );
							<?php endif; ?>
                        } );
						<?php
						endfor;
						for ($i = 1, $j = 2; $i < $this->opts; $i++, $j++) {
						$cnt = 1;
						if (get_post_meta($thepostid, "plus_discount_quantity_$i", true) || get_post_meta($thepostid, "plus_discount_quantity_$j", true)) {
						?>
                        e.find( '.block<?php echo esc_attr($i); ?>' ).show();
                        e.find( '.options_group<?php echo esc_attr($i); ?>' ).show();
                        e.find( '#def_disc_criteria<?php echo esc_attr($i); ?>' ).hide();
                        e.find( '#delete_discount_line<?php echo esc_attr($i); ?>' ).hide();
                        e.find( '.options_group<?php echo esc_attr(min($i+1, $this->opts)); ?>' ).show();
                        e.find( '#def_disc_criteria<?php echo esc_attr(min($i+1, $this->opts)); ?>' ).show();
                        e.find( '#delete_discount_line<?php echo esc_attr(min($i+1, $this->opts)); ?>' ).show();
						<?php
						$cnt++;
						}
						}
						if ($cnt >= $this->opts) {
						?>e.find( '#def_disc_criteria<?php echo esc_attr($this->opts); ?>' ).show();
						<?php
						}
						?>
                    } );
				</script>

				<div id="discounts_plus_product_data" class="panel woocommerce_options_panel">

					<div class="options_group">
						<?php

						woocommerce_wp_checkbox( array( 'id' => 'plus_discount_enabled', 'value' => plus_discount_enabled($thepostid) ? 'yes': get_post_meta( $thepostid, 'plus_discount_enabled', true ) , 'label' => __( 'Discounts Plus Enabled', "wcdp" ) ) );
						woocommerce_wp_textarea_input( array( 'id' => "plus_discount_text_info", 'label' => __( 'Discounts Plus special offer text in product description', "wcdp" ), 'description' => __( 'Optionally enter Discounts Plus information that will be visible on the product page.', "wcdp" ), 'desc_tip' => 'yes', 'class' => 'fullWidth' ) );
						?>
					</div>

					<?php
					for ( $i = 1;
						$i < $this->opts;
						$i++ ) :
						?>

						<div class="options_group<?php echo esc_attr($i); ?>">
							<a id="def_disc_criteria<?php echo esc_attr($i); ?>" class="button-secondary"
							   href="#block<?php echo esc_attr($i); ?>"><?php _e( 'Define discount criteria', "wcdp" ); ?></a>
							<a id="delete_discount_line<?php echo esc_attr($i); ?>" class="button-secondary"
							   href="#block<?php echo esc_attr($i); ?>"><?php _e( 'Remove discount criteria', "wcdp" ); ?></a>

							<div class="block<?php echo esc_attr($i); ?> <?php echo ( $i % 2 == 0 ) ? 'even' : 'odd' ?>">
								<?php
								woocommerce_wp_text_input( array( 'id' => "plus_discount_quantity_$i", 'label' => __( 'Quantity (min.)', "wcdp" ), 'type' => 'number', 'description' => __( 'Quantity on which the discount criteria will apply?', "wcdp" ), 'custom_attributes' => array(
									'step' => '1',
									'min' => '1'
								) ) );
								if ( get_option( 'woocommerce_discount_type', '' ) == 'flat' ) {
									woocommerce_wp_text_input( array( 'id' => "plus_discount_discount_flat_$i", 'type' => 'number', 'label' => sprintf( __( 'Discount', "wcdp" ).' %s '. $this->currency ), 'description' => sprintf( __( 'Enter the flat discount in', "wcdp" ) .' %s '. $this->currency ), 'custom_attributes' => array(
										'step' => 'any',
										'min' => '0'
									) ) );
								} else {
									woocommerce_wp_text_input( array( 'id' => "plus_discount_discount_$i", 'type' => 'number', 'label' => __( 'Discount', "wcdp" ).' (%) ', 'description' => __( 'Discount percentage (Range: 0 to 100).', "wcdp" ), 'custom_attributes' => array(
										'step' => 'any',
										'min' => '0',
										'max' => '100'
									) ) );
								}
								?>
							</div>
						</div>

					<?php
					endfor;
					?>

					<div class="options_group<?php echo esc_attr($this->opts); ?>">
						<a id="delete_discount_line<?php echo esc_attr($this->opts); ?>" class="button-secondary"
						   href="#block<?php echo esc_attr($this->opts); ?>"><?php _e( 'Remove discount criteria', "wcdp" ); ?></a>
					</div>

					<br/>

				</div>

				<?php
			}
			/**
			 * Add entry content to Product Settings.
			 */
			public function wdp_product_write_panels() {

				global $thepostid, $post, $wdp_pro, $wdp_premium_check;

				//pree($wdp_pro);
				//pree($this->wdp_pro);

                $wc_discount_type = get_option('woocommerce_plus_discount_type', '');
				//pree($wc_discount_type);exit;
                $is_cart_based = $wc_discount_type == 'cart_amount';
				

                if ( !$thepostid ) $thepostid = $post->ID;

				$_product = wc_get_product( $thepostid );
				$variations = (method_exists($_product,'get_available_variations')?$_product->get_available_variations():array());
				//pre($variations);exit;
				?>
				<script type="text/javascript">
                    jQuery( document ).ready( function () {
                        var e = jQuery( '#discounts_plus_product_data' );


						<?php

                            if($is_cart_based):

                            ?>

                            jQuery('input[name="plus_discount_enabled"][value="cart_based"]').prop('checked', true);
                            jQuery('input[name="plus_discount_enabled"]').change();

                            <?php

                            else:

                            ?>

                            //jQuery('input[name="plus_discount_enabled"][value="cart_based"]').prop('disabled', true);
                            jQuery('input[name="plus_discount_enabled"][value="cart_based"]').parent().next('a').show();


                            <?php

                             endif;

						for($i = 1; $i <= $this->opts; $i++) :
						?>
                        e.find( '.block<?php echo esc_attr($i); ?>' ).hide();
                        e.find( '.options_group<?php echo esc_attr(max($i, 2)); ?>' ).hide();
                        e.find( '#def_disc_criteria<?php echo esc_attr(max($i, 2)); ?>' ).hide();

                        <?php if(!$is_cart_based): ?>

                            e.find( '#def_disc_criteria<?php echo esc_attr($i); ?>' ).click( function () {

                                /*if ( <?php echo esc_attr($i); ?> == 1 || ( e.find( '#plus_discount_quantity_<?php echo esc_attr(max($i-1, 1)); ?>' ).val() != '' &&
                                <?php if ( get_option( 'woocommerce_discount_type', '' ) == 'flat' ) : ?>
                                e.find( '#plus_discount_discount_flat_<?php echo esc_attr(max($i-1, 1)); ?>' ).val() != ''
                            <?php else: ?>
                            e.find( '#plus_discount_discount_<?php echo esc_attr(max($i-1, 1)); ?>' ).val() != ''
                            <?php endif; ?>
                            ) )
                            {*/
                                e.find( '.block<?php echo esc_attr($i); ?>' ).show();
                                e.find( '.options_group<?php echo esc_attr(min($i+1, $this->opts)); ?>' ).show();
                                e.find( '#def_disc_criteria<?php echo esc_attr(min($i+1, ($this->opts-1))); ?>' ).show();
                                e.find( '#def_disc_criteria<?php echo esc_attr($i); ?>' ).hide( );
                                e.find( '#delete_discount_line<?php echo esc_attr(min($i+1, $this->opts)); ?>' ).show();
                                e.find( '#delete_discount_line<?php echo esc_attr($i); ?>' ).hide( );
                                /*}
                                else
                                {
                                    alert( '<?php _e( 'Please fill in the current line before adding new line.', "wcdp" ); ?>' );
                            }*/
                            } );

                        <?php endif; ?>

                        e.find( '#delete_discount_line<?php echo esc_attr(max($i, 1)); ?>' ).hide();

                        <?php if(!$is_cart_based): ?>

                            e.find( '#delete_discount_line<?php echo esc_attr($i); ?>' ).click( function () {
                                e.find( '.block<?php echo esc_attr(max($i-1, 1)); ?>' ).hide( );
                                e.find( '.options_group<?php echo esc_attr(min($i, $this->opts)); ?>' ).hide( );
                                e.find( '#def_disc_criteria<?php echo esc_attr(min($i, ($this->opts-1))); ?>' ).hide( );
                                e.find( '#def_disc_criteria<?php echo esc_attr(max($i-1, 1)); ?>' ).show();
                                e.find( '#delete_discount_line<?php echo esc_attr(min($i, $this->opts)); ?>' ).hide( );
                                e.find( '#delete_discount_line<?php echo esc_attr(max($i-1, 2)); ?>' ).show();
                                e.find( '#plus_discount_quantity_<?php echo esc_attr(max($i-1, 1)); ?>' ).val( '' );
                                <?php
                                if ( get_option( 'woocommerce_discount_type', '' ) == 'flat' ) :
                                ?>
                                e.find( '#plus_discount_discount_flat_<?php echo max($i-1, 1); ?>' ).val( '' );
                                <?php else: ?>
                                e.find( '#plus_discount_discount_<?php echo max($i-1, 1); ?>' ).val( '' );
                                <?php endif; ?>
                            } );

						<?php
                        endif;
						endfor;
						$cnt = 1;
						for ($i = 1, $j = 2; $i < $this->opts; $i++, $j++) {
						
						if (get_post_meta($thepostid, "plus_discount_quantity_$i", true) || get_post_meta($thepostid, "plus_discount_quantity_$j", true)) {
						?>
                        e.find( '.block<?php echo esc_attr($i); ?>' ).show();
                        e.find( '.options_group<?php echo esc_attr($i); ?>' ).show();
                        e.find( '#def_disc_criteria<?php echo esc_attr($i); ?>' ).hide();
                        e.find( '#delete_discount_line<?php echo esc_attr($i); ?>' ).hide();
                        e.find( '.options_group<?php echo esc_attr(min($i+1, $this->opts)); ?>' ).show();
                        e.find( '#def_disc_criteria<?php echo esc_attr(min($i+1, $this->opts)); ?>' ).show();
                        e.find( '#delete_discount_line<?php echo esc_attr(min($i+1, $this->opts)); ?>' ).show();
						<?php
						$cnt++;
						}
						}
						if ($cnt >= $this->opts) {
						?>e.find( '#def_disc_criteria<?php echo esc_attr($this->opts); ?>' ).show();
						<?php
						}
						?>
                    } );
				</script>

				<div id="discounts_plus_product_data" class="panel woocommerce_options_panel">

					<div class="options_group">
						<?php
						//woocommerce_wp_checkbox( array( 'id' => 'plus_discount_enabled', 'value' => plus_discount_enabled($thepostid)?'yes':get_post_meta( $thepostid, 'plus_discount_enabled', true ), 'label' => __( 'Discounts Plus enabled', "wcdp" ) ) );
						//echo plus_discount_enabled($thepostid);
						$pf = ($wdp_pro?'':' - '.$wdp_premium_check);
						$cart_base_class = $is_cart_based? ' wcdp_cart_disable' : '';
                        $cart_disabled =  $is_cart_based ? 'disabled = "disabled"' : '';
						
						$plus_discount_enabled = plus_discount_enabled($thepostid, true);
						//pree($plus_discount_enabled);

                        woocommerce_wp_radio(array( 'id' => 'plus_discount_enabled', 'class' => ($wdp_pro?'':'wcdp_disable').$cart_base_class, 'value' => $plus_discount_enabled, 'label' => __( 'Discounts Plus Enabled', "wcdp" ), 'options' => array('default' => __('Default (Global Settings)', "wcdp").$pf, 'category_based' => 'Category based discount criteria'.$pf, 'cart_based' => __('Total cart amount based', "wcdp"), 'yes' => __('Product based (Use criteria defined below)', "wcdp"), 'no' => __('No', "wcdp")) ));
						woocommerce_wp_radio(array( 'id' => 'plus_discount_type', 'class' => ($wdp_pro?'':'wcdp_disabled').$cart_base_class, 'value' => plus_discount_type($thepostid, true), 'label' => __( 'Discounts Plus Type', "wcdp" ), 'options' => array('quantity' => 'Quantity (Default)'.$pf, 'weight' => 'Weight'.$pf) ));
						if(!empty($variations)){
							$plus_discount_excluding = get_post_meta($thepostid, 'plus_discount_excluding', true);
							//pree($plus_discount_excluding);
							$var_options = array('' => 'None');
							foreach($variations as $var_atts){
								$variation = wc_get_product($var_atts['variation_id']);
								$var_atts['sku'] = ($var_atts['sku']!=''?$var_atts['sku'].' - ':'');
								$var_options[$var_atts['variation_id']] = '#'.$var_atts['variation_id'].' - '.$variation->get_title().' - '.$var_atts['sku'].wdp_get_formatted_price($var_atts['display_price']);
							}

							woocommerce_wp_select_multiple(array( 'id' => 'plus_discount_excluding','class' => $cart_base_class, 'value' => $plus_discount_excluding, 'label' => __( 'Excluding Variations', "wcdp" ), 'options' => $var_options ));

						}
						woocommerce_wp_textarea_input( array( 'id' => "plus_discount_text_info", 'label' => __( 'Discounts Plus special offer text in product description', "wcdp" ), 'description' => __( 'Optionally enter Discounts Plus information that will be visible on the product page.', "wcdp" ), 'desc_tip' => 'yes', 'class' => 'fullWidth'.$cart_base_class ) );
						if(plus_discount_enabled($thepostid, true)!='no')
							woocommerce_wp_radio(array( 'id' => 'plus_discount_product_display','class' => $cart_base_class, 'value' => plus_discount_product_display($thepostid, true), 'label' => __( 'Discounts display on product page', "wcdp" ), 'options' => array('yes' => __('Turn ON', "wcdp"), 'no' => __('Turn OFF', "wcdp")) ));
						?>
					</div>

					<?php
					for ( $i = 1;
						$i < $this->opts;
						$i++ ) :

                        $disabled_input = $is_cart_based ? 'disabled' : 'data-empty';
						?>

						<div class="options_group<?php echo esc_attr($i); ?>">
							<a id="def_disc_criteria<?php echo esc_attr($i); ?>" class="button-secondary" <?php echo esc_attr($cart_disabled); ?>
							   href="#block<?php echo esc_attr($i); ?>"><?php _e( 'Define discount criteria', "wcdp" ); ?></a>
							<a id="delete_discount_line<?php echo esc_attr($i); ?>" class="button-secondary" <?php echo esc_attr($cart_disabled); ?>
							   href="#block<?php echo esc_attr($i); ?>"><?php _e( 'Remove discount criteria', "wcdp" ); ?></a>

							<div class="block<?php echo esc_attr($i); ?> <?php echo ( $i % 2 == 0 ) ? 'even' : 'odd' ?>">
								<?php
								woocommerce_wp_text_input( array( 'id' => "plus_discount_quantity_$i", 'label' => __( 'Quantity (min.)', "wcdp" ), 'type' => 'number', 'description' => __( 'Quantity on which the discount criteria will apply?', "wcdp" ), 'custom_attributes' => array(
									'step' => '1',
									'min' => '1',
                                    $disabled_input => $disabled_input,
								) ) );
								if ( get_option( 'woocommerce_discount_type', '' ) == 'flat' ) {
									woocommerce_wp_text_input( array( 'id' => "plus_discount_discount_flat_$i", 'type' => 'number', 'label' => sprintf( __( 'Discount ', "wcdp" ).' %s ', $this->currency ), 'description' => sprintf( __( 'Enter the flat discount in', "wcdp" ).' %s ', $this->currency ), 'custom_attributes' => array(
										'step' => 'any',
										'min' => '0',
                                        $disabled_input => $disabled_input,

                                    ) ) );
								} else {
									woocommerce_wp_text_input( array( 'id' => "plus_discount_discount_$i", 'type' => 'number', 'label' => __( 'Discount', "wcdp" ).' (%) ', 'description' => __( 'Discount percentage (Range: 0 to 100).', "wcdp" ), 'custom_attributes' => array(
										'step' => 'any',
										'min' => '0',
										'max' => '100',
                                        $disabled_input => $disabled_input,

                                    ) ) );
								}
								?>
							</div>
						</div>

					<?php
					endfor;
					?>

					<div class="options_group<?php echo esc_attr($this->opts); ?>">
						<a id="delete_discount_line<?php echo esc_attr($this->opts); ?>" class="button-secondary"
						   href="#block<?php echo esc_attr($this->opts); ?>"><?php _e( 'Remove discount criteria', "wcdp" ); ?></a>
					</div>

					<br/>

				</div>

				<?php
			}

			/**
			 * Enqueue frontend dependencies.
			 */

			public function wdp_enqueue_scripts() {

                global $wdp_dir, $wdp_url;



				$enqueue_script = false;

				if(
					(function_exists('is_cart') && is_cart()) ||
					(function_exists('is_product') && is_product()) ||
					(function_exists('is_checkout') && is_checkout())

				){


					$enqueue_script = true;

				}

				if($enqueue_script){

					wp_enqueue_style( 'woocommercediscounts_plus-style', $wdp_url.'css/style.css', array(), time() );

					wp_enqueue_script( 'jquery' );
					wp_enqueue_script(
						'wdp-scripts',
						$wdp_url.'js/scripts.js',
						array('jquery')
					);

				}

			}
			/**
			 * Enqueue backend dependencies.
			 */
			public function wdp_enqueue_scripts_admin() {

				global $wdpp_obj, $wdp_premium_check, $pagenow, $wdp_dir, $wdp_url, $wcdp_enqueue_scripts, $post;

			
				$is_product_edit = (is_object($post) && isset($post->post_type) && $post->post_type=='product');
				
				$wdp_pages = ((isset($_GET['page']) && $_GET['page'] == 'wc_wcdp') || isset($_GET['tab']) && $_GET['tab'] == 'plus_discount');

				if($wdp_pages){

					wp_enqueue_style( 'wdp-ld-style', $wdp_url.'css/loading.css', array(), time() );
					wp_enqueue_style( 'wdp-bs-style', $wdp_url.'css/bootstrap.min.css', array(), time() );

                }
				if($is_product_edit || $wdp_pages){

					wp_enqueue_script(
						'wdp-bs-scripts',
						$wdp_url.'js/bootstrap.min.js',
						array('jquery'),
						date('Yhi')
					);

                }
				
				//pree($wcdp_enqueue_scripts);



				if($wcdp_enqueue_scripts){


					wp_enqueue_style( 'woocommercediscounts_plus-style-admin', $wdp_url.'css/admin.css', array(), time() );



					wp_enqueue_script(
						'wdp-scripts',
						$wdp_url.'js/admin.js',
						array('jquery'),
						date('Yhi')
					);

					$wdp_obj = array('woocommerce_weight_unit' => $this->woocommerce_weight_unit);
					wp_localize_script( 'wdp-scripts', 'wdp_obj', $wdp_obj );

					$woocommerce_plus_discount_type = get_option( 'woocommerce_plus_discount_type', 'quantity' );
					$woocommerce_discount_type = get_option( 'woocommerce_discount_type', '' );
					
					//pree($post);

					$scripts_array = array(
						'wdp_opts' => $this->opts,
						'sale_applied' => ($wdpp_obj->sale_applied()?'true':'false'),
						'woocommerce_plus_discount_type' => $woocommerce_plus_discount_type,
						'woocommerce_discount_type' => $woocommerce_discount_type,
						'currency' => get_woocommerce_currency_symbol(get_woocommerce_currency()),
						'woocommerce_plus_discount_type_placeholder' => ($woocommerce_plus_discount_type=='weight'?'Weight ('.$this->woocommerce_weight_unit.')':'Qty.'),
						'wdp_premium_check' => $wdp_premium_check,
						'this_url' => admin_url( 'admin.php?page=wc_wcdp' ),
						'wcdp_tab' => ((isset($_GET['t']) && is_numeric($_GET['t']))?wdp_sanitize_arr_data($_GET['t']):'0'),
						'str_info' => __('On Shopping of ', "wcdp").'<span class="amount">aaaa</span>, <span class="discount">dddd</span>'.__(' Discount', "wcdp"),

					);
					if(is_object($post) && $post->post_type='product'){
						//$scripts_array[''] = '';
					}
					
					wp_localize_script( 'wdp-scripts', 'wcdp_obj', $scripts_array );

				}

			}

			/**
			 * Updating post meta.
			 *
			 * @param $post_id
			 */
			public function wdp_process_meta( $post_id ) {

				if ( isset( $_POST['plus_discount_text_info'] ) ) update_post_meta( $post_id, 'plus_discount_text_info', stripslashes( wdp_sanitize_arr_data($_POST['plus_discount_text_info']) ) );

				if ( isset( $_POST['plus_discount_enabled'] ) && $_POST['plus_discount_enabled'] != '' ) {
					update_post_meta( $post_id, 'plus_discount_enabled', stripslashes( wdp_sanitize_arr_data($_POST['plus_discount_enabled']) ) );
					//pree(stripslashes( wdp_sanitize_arr_data($_POST['plus_discount_enabled'])));pree($post_id);exit;
				}
				if ( isset( $_POST['plus_discount_product_display'] ) && $_POST['plus_discount_product_display'] != '' ) {
					update_post_meta( $post_id, 'plus_discount_product_display', stripslashes( wdp_sanitize_arr_data($_POST['plus_discount_product_display']) ) );
				}

				if ( isset( $_POST['plus_discount_excluding'] ) && !empty($_POST['plus_discount_excluding']) ) {
					update_post_meta( $post_id, 'plus_discount_excluding', wdp_sanitize_arr_data($_POST['plus_discount_excluding']) );
					//pree($_POST);exit;
				}
				if ( isset( $_POST['plus_discount_type'] ) && $_POST['plus_discount_type'] != '' ) {
					update_post_meta( $post_id, 'plus_discount_type', stripslashes( wdp_sanitize_arr_data($_POST['plus_discount_type']) ) );
				}


				for ( $i = 1; $i < $this->opts; $i++ ) {
					if ( isset( $_POST["plus_discount_quantity_$i"] ) ) update_post_meta( $post_id, "plus_discount_quantity_$i", stripslashes( wdp_sanitize_arr_data($_POST["plus_discount_quantity_$i"]) ) );
					if ( ( get_option( 'woocommerce_discount_type', '' ) == 'flat' ) ) {
						if ( isset( $_POST["plus_discount_discount_flat_$i"] ) ) update_post_meta( $post_id, "plus_discount_discount_flat_$i", stripslashes( wdp_sanitize_arr_data($_POST["plus_discount_discount_flat_$i"]) ) );
					} else {
						if ( isset( $_POST["plus_discount_discount_$i"] ) ) update_post_meta( $post_id, "plus_discount_discount_$i", stripslashes( wdp_sanitize_arr_data($_POST["plus_discount_discount_$i"]) ) );
					}
				}

			}

			/**
			 * @access public
			 * @return void
			 */
			public function add_tab() {

				$settings_slug = 'woocommerce';

				if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0 ) {

					$settings_slug = 'wc-settings';

				}


				foreach ( $this->settings_tabs as $name => $label ) {
					$class = 'nav-tab';
					if ( $this->current_tab == $name )
						$class .= ' nav-tab-active';
					echo wp_kses_post('<a href="' . admin_url( 'admin.php?page=' . $settings_slug . '&tab=' . $name ) . '" class="' . $class . '">' . $label . '</a>');
				}


			}

			/**
			 * @access public
			 * @return void
			 */
			public function settings_tab_action() {

				global $woocommerce_settings;

				// Determine the current tab in effect.
				$current_tab = $this->get_tab_in_view( current_filter(), 'woocommerce_settings_tabs_' );

				do_action( 'woocommerce_plus_discount_settings' );

				// Display settings for this tab (make sure to add the settings to the tab).
				woocommerce_admin_fields( $woocommerce_settings[$current_tab] );

			}

			/**
			 * Save settings in a single field in the database for each tab's fields (one field per tab).
			 */
			public function save_settings() {

				global $woocommerce_settings;

				// Make sure our settings fields are recognised.
				$this->add_settings_fields();

				$current_tab = $this->get_tab_in_view( current_filter(), 'woocommerce_update_options_' );
				woocommerce_update_options( $woocommerce_settings[$current_tab] );

			}

			/**
			 * Get the tab current in view/processing.
			 */
			public function get_tab_in_view( $current_filter, $filter_base ) {

				return str_replace( $filter_base, '', $current_filter );

			}


			/**
			 * Add settings fields for each tab.
			 */
			public function add_settings_fields() {
				global $woocommerce_settings, $s2_enabled, $wdp_dir;

				// Load the prepared form fields.
//				$this->init_form_fields();
//
//				if ( is_array( $this->fields ) )
//					foreach ( $this->fields as $k => $v )
//						$woocommerce_settings[$k] = $v;

                echo '<table class="form-table"></table>';


                $current_tab = (isset($_GET['tab']) && is_numeric($_GET['tab'])?wdp_sanitize_arr_data($_GET['tab']):'0');


                $js = "jQuery('.nav-tab.nav-tab-active').addClass('wdp');
					jQuery('form#mainform table.form-table').addClass('wdp-tbl');
					jQuery('<div class=\"wdp-guy\"><ul><li class=\"".($this->wdp_pro?'hide':'')."\">".__("Go Premium!", "wcdp")."</li><li>Video Tutorial</li><li class=\"".($s2_enabled?'':'hide')."\">s2member ".__("Plugin", "wcdp")."</li><li>".__("Contact Developer", "wcdp")."</li></ul></div>').insertBefore($('form#mainform table.form-table.wdp-tbl'));
					jQuery('.wdp-guy ul li:nth-child(1)').click(function(){
						window.open('".$this->premium_link."');
					});
					
					jQuery('.wdp-guy ul li:nth-child(2)').click(function(){
						window.open('".$this->watch_tutorial."');
					});
					
					jQuery('.wdp-guy ul li:nth-child(3)').click(function(){
						window.open('".$this->s2member."');
					});
					
					jQuery('.wdp-guy ul li:nth-child(4)').click(function(){
						window.open('".$this->contact_developer."');
					});";

                if($current_tab == 'plus_discount'){

                    $js .= "jQuery('.woocommerce-save-button').hide()";

                    include_once ($wdp_dir."inc/templates/wdp_woo_tab.php");

                }



                $this->run_js($js);
			}

			/**
			 * Return the list of settings.
			 */

			public function wdp_get_default_settings(){

				global $wcdp_data;

				$default_settings =  array(

					array( 'name' => __( 'Discounts Plus', "wcdp").($this->wdp_pro?'+':''), 'type' => 'title', 'desc' => __( 'The following options are specific to product Discounts Plus.', "wcdp" ) . '<br /><br/><strong><i>' . __( 'After changing the settings, it is recommended to clear all sessions in WooCommerce', "wcdp").' &gt; <a href="admin.php?page=wc-status">'.__('System Status', "wcdp").'</a> &gt; <a href="admin.php?page=wc-status&tab=tools">'.__('Tools', "wcdp").'</a>.'. '</i></strong>', 'id' => 'wdpplus_discounts_options' ),

					array(
						'name' => __( 'Discounts Plus globally enabled', "wcdp" ).' ('.__( 'ON', "wcdp" ).'/'.__( 'OFF', "wcdp" ).')',
						'id' => 'woocommerce_enable_plus_discounts',
						'desc' => __( 'This option will be overridden by specific product(on page) settings.', "wcdp" ),
						'std' => 'yes',
						'type' => 'checkbox',
						'default' => 'yes'
					),
					
					array(
						'name' => __( 'Discount Label/Caption', "wcdp" ),
						'id' => 'woocommerce_discount_label',
						'desc' => __( 'This label will appear on cart page, checkout page, thank you page and in HTML emails as well.', "wcdp" ),
						'type' => 'text',
						'default' => __('Discount', "wcdp"),
					),
					

					array(
						'title' => __( 'Do not offer discounts to these user roles (select/unselect)', "wcdp" ),
						'id' => 'woocommerce_user_roles',
						'desc' => sprintf( __( 'Select the user roles which you want to ignore for discounts.', "wcdp"). ' '.__('Multiple selection is possible by holding ctrl key.', "wcdp" )),
						'desc_tip' => true,
						'std' => 'yes',
						'type' => 'multiselect',
						'css' => 'min-width:200px;',
						'options' => wdp_get_user_roles()
					),
					array(
						'title' => __( 'Discount Type', "wcdp" ),
						'id' => 'woocommerce_discount_type',
						'desc' => __( 'Select the type of discount.', "wcdp").' '.__('Percentage Discount deducts amount of', "wcdp").' % '.__('from price while Flat Discount deducts fixed amount in', "wcdp" ).sprintf( ' %s ', $this->currency ),
						'desc_tip' => true,
						'std' => 'yes',
						'type' => 'select',
						'css' => 'min-width:200px;',
						'class' => 'chosen_select',
						'options' => array(
							'' => __( 'Percentage Discount', "wcdp" ),
							'flat' => __( 'Flat Discount', "wcdp" ),
							
						)
					),

					array(
						'name' => __( 'Treat product variations separately', "wcdp" ),
						'id' => 'woocommerce_variations_separate',
						'desc' => __( 'Default will consider product variations as one group by adding their quantities.', "wcdp" ),
						'std' => 'yes',
						'type' => 'checkbox',
						'default' => 'yes'
					),

					array(
						'name' => __( 'No effect if a coupon code is applied', "wcdp" ),
						'id' => 'woocommerce_remove_discount_on_coupon',
						'std' => 'yes',
						'type' => 'checkbox',
						'default' => 'yes'
					),
					array(
						'name' => __( 'Apply discounts on SALE items as well', "wcdp" ),
						'id' => 'woocommerce_discount_on_sale',
						'desc' => 'Including Products and Variations',
						'std' => 'yes',
						'type' => 'checkbox',
						'default' => 'yes'
					),

					array(
						'name' => __( 'Apply same discount on next multiples?', "wcdp" ),
						'id' => 'woocommerce_tiers',
						'desc' => __( 'e.g. Qty. 10 gets $1 discount so Qty. 20 will get discount of $2.', "wcdp" ),
						'std' => 'yes',
						'type' => 'checkbox',
						'default' => 'no'
					),

					array(
						'name' => __( 'Show discount information next to item subtotal price', "wcdp" ),
						'id' => 'woocommerce_show_on_subtotal',
						'std' => 'yes',
						'type' => 'checkbox',
						'default' => 'yes'
					),

					array(
						'name' => __( 'Show discount information next to item subtotal price in order history', "wcdp" ),
						'id' => 'woocommerce_show_on_order_subtotal',
						'desc' => __( 'Includes showing discount in order e-mails and invoices.', "wcdp" ),
						'std' => 'yes',
						'type' => 'checkbox',
						'default' => 'yes'
					),

					array(
						'name' => __( 'Optionally enter information about discounts visible on cart page.', "wcdp" ),
						'id' => 'woocommerce_cart_info',
						'type' => 'textarea',
						'css' => 'width:100%; height: 75px;'
					),

					array(
						'name' => __( 'Show discounted price in cart view', "wcdp" ),
						'id' => 'woocommerce_show_discounted_price',
						'desc' => __( 'Display the changed value of price with a line-through on original price.', "wcdp" ),
						'std' => 'yes',
						'type' => 'checkbox',
						'default' => 'yes'
					),
					array(
						'name' => __( 'Show discounted price on single product page', "wcdp" ),
						'id' => 'woocommerce_show_discounted_price_sp',
						'desc' => __( 'Display the changed value of price with a line-through on original price.', "wcdp" ),
						'std' => 'yes',
						'type' => 'checkbox',
						'default' => ''
					),
					array(
						'name' => __( 'Show discounted price in shop or products list', "wcdp" ),
						'id' => 'woocommerce_show_discounted_price_shop',
						'desc' => __( 'Display the changed value of price with a line-through on original price.', "wcdp" ),
						'std' => 'yes',
						'type' => 'checkbox',
						'default' => ''
					),


					array(
						'name' => __( 'Optionally change the CSS for old price on cart before discounting.', "wcdp" ),
						'id' => 'woocommerce_css_old_price',
						'type' => 'textarea',
						'css' => 'width:100%;',
						'default' => 'color: #777; text-decoration: line-through; margin-right: 4px;'
					),

					array(
						'name' => __( 'Optionally change the CSS for new price on cart after discounting.', "wcdp" ),
						'id' => 'woocommerce_css_new_price',
						'type' => 'textarea',
						'css' => 'width:100%;',
						'default' => 'color: #4AB915; font-weight: bold;'
					),

					array(
						'title' => __( 'Discounts Based On', "wcdp").($this->wdp_pro?'':' - ('.__('Go Premium for this Feature', "wcdp").')'),
						'id' => 'woocommerce_plus_discount_type',
						'desc' => sprintf( __( 'You can offer discounts based on Quantity (Default) and Weight', "wcdp").' (%s) '.__('as well.', "wcdp" ), $this->woocommerce_weight_unit ),
						'desc_tip' => true,
						'std' => 'yes',
						'type' => 'select',
						'css' => 'min-width:200px;',
						'class' => 'chosen_select',
						'options' => array(
							'quantity' => __( 'Quantity (Default)', "wcdp" ),
							'weight' => __('Weight', "wcdp").' '.$this->woocommerce_weight_unit,
                            'cart_amount' => __( 'Total Cart Amount', "wcdp" ),

                        )
					),

					array(
						'title' => __( 'Discount Available Conditionally?', "wcdp").($this->wdp_pro?'':' - ('.__('Go Premium for this Feature', "wcdp").')'),
						'id' => 'woocommerce_plus_discount_condition',
						'desc' => sprintf( __( 'You can offer discounts conditionally, like discounts are available only on store pickup.', "wcdp" ).' '.__('No discounts if shipping required etc.', "wcdp" ), $this->woocommerce_weight_unit ),
						'desc_tip' => true,
						'std' => 'yes',
						'type' => 'select',
						'css' => 'min-width:200px;',
						'class' => 'chosen_select',
						'options' => array(
							'default' => __( 'Default (No conditions)', "wcdp" ),
							'no_shipping' => __( 'No Shipping', "wcdp" ),
							'only_shipping' => __( 'Only Shipping', "wcdp" ),
						)
					),

					array(
						'name' => __( 'Apply discount on shipping decision only', "wcdp" ),
						'id' => 'woocommerce_show_discounts_on_shipping_decision',
						'desc' => __( 'Discount will not be applied on cart or single product page until user will decide store pickup or shipping required.', "wcdp" ),
						'std' => 'yes',
						'type' => 'checkbox',
						'default' => 'no'
					),

				);

				if($this->wdp_pro){
					$gj_logic = wdp_extra_logics('gj_logic');
					$special_offer = wdp_extra_logics('special_offer');
					if(!empty($gj_logic)){
						$default_settings[] = $gj_logic;
						$default_settings[] = $special_offer;
					}
				}

				$default_settings[] = array( 'type' => 'sectionend', 'id' => 'wdpplus_discounts_options' );

				$default_settings[] = array(
					'desc' => ($this->wdp_pro?__('Discount Available Conditionally?', "wcdp").' <a href="'.admin_url().'admin.php?page=wc_wcdp&t=6" target="_blank">'.__('Click here to define error messages', "wcdp").'</a><br />':'').__('If you find the', "wcdp").' <a target="_blank" href="https://wordpress.org/plugins/woocommerce-discounts-plus/screenshots/">'.$wcdp_data['Name'].'</a> '.__('extension useful, please visit our online store for more', "wcdp").' <a target="_blank" href="https://shop.androidbubbles.com/go/">'.__('premium products', "wcdp").'</a>.<br />
					<a class="wdp-optional-wrappers button">'.__('Click here to display optional/layout settings', "wcdp").'</a>',
					'id' => 'woocommerce_plus_discount_notice_text',
					'type' => 'title'
				);

				$default_settings[] = array( 'type' => 'sectionend', 'id' => 'woocommerce_plus_discount_notice_text' );

				return $default_settings;

            }

			/**
			 * Prepare form fields to be used in the various tabs.
			 */

            public function wdp_get_input_ids(){

                $used_inputs = array('checkbox', 'select', 'multiselect', 'textarea', 'text');
                $default_settings = $this->wdp_get_default_settings();
                $inputs_array = array();

                if(!empty($default_settings)){

                    foreach ($default_settings as $setting){

                        $type = isset($setting['type']) ? $setting['type'] : '';

                        if(in_array($type, $used_inputs)){

                            $inputs_array[$setting['id']] = $type;

                        }

                    }
                }

                return $inputs_array;

            }



			/**
			 * Prepare form fields to be used in the various tabs.
			 */
			public function init_form_fields($tr = 'tr') {
				global $woocommerce, $s2_enabled, $wcdp_data, $wdp_dir;


				$default_settings = $this->wdp_get_default_settings();


				// Define settings
				$this->fields['plus_discount'] = apply_filters( 'woocommerce_plus_discount_settings_fields', $default_settings); // End settings



				$js = "
					
					jQuery('#woocommerce_enable_plus_discounts').change(function() {
					
					    
						jQuery('#woocommerce_cart_info, #woocommerce_variations_separate, #woocommerce_discount_type, #woocommerce_css_old_price, #woocommerce_css_new_price, #woocommerce_show_on_item, #woocommerce_show_on_subtotal, #woocommerce_show_on_order_subtotal').closest('$tr').hide();

						if ( jQuery(this).attr('checked') ) {
							//jQuery('#woocommerce_cart_info').closest('$tr').show();
							jQuery('#woocommerce_variations_separate').closest('$tr').show();
							jQuery('#woocommerce_discount_type').closest('$tr').show();
							//jQuery('#woocommerce_css_old_price').closest('$tr').show();
							//jQuery('#woocommerce_css_new_price').closest('$tr').show();
							jQuery('#woocommerce_show_on_item').closest('$tr').show();
							//jQuery('#woocommerce_show_on_subtotal').closest('$tr').show();
							jQuery('#woocommerce_show_on_order_subtotal').closest('$tr').show();
						}

					}).change();
					
					jQuery('.nav-tab.nav-tab-active').addClass('wdp');
					jQuery('form#mainform table.form-table').addClass('wdp-tbl');
					jQuery('<div class=\"wdp-guy\"><ul><li class=\"".($this->wdp_pro?'hide':'')."\">".__("Go Premium!", "wcdp")."</li><li>".__("Video Tutorial", "wcdp")."</li><li class=\"".($s2_enabled?'':'hide')."\">s2member ".__("Plugin", "wcdp")."</li><li>".__("Contact Developer", "wcdp")."</li></ul></div>').insertBefore($('form#mainform table.form-table.wdp-tbl'));
					jQuery('.wdp-guy ul li:nth-child(1)').click(function(){
						window.open('".$this->premium_link."');
					});
					
					jQuery('.wdp-guy ul li:nth-child(2)').click(function(){
						window.open('".$this->watch_tutorial."');
					});
					
					jQuery('.wdp-guy ul li:nth-child(3)').click(function(){
						window.open('".$this->s2member."');
					});
					
					jQuery('.wdp-guy ul li:nth-child(4)').click(function(){
						window.open('".$this->contact_developer."');
					});

				";

				$this->run_js( $js );

			}

			/**
			 * Includes inline JavaScript.
			 *
			 * @param $js
			 */
			protected function run_js( $js ) {

				global $woocommerce;

				if ( function_exists( 'wc_enqueue_js' ) ) {
					wc_enqueue_js( $js );
				} else {
					$woocommerce->add_inline_js( $js );
				}

			}

			/**
			 * @return bool
			 */
			protected function coupon_check() {

				global $woocommerce;

				if ( get_option( 'woocommerce_remove_discount_on_coupon', 'yes' ) == 'no' ) return false;
				return !( empty( $woocommerce->cart->applied_coupons ) );


			}

			protected function sale_applied() {

				if ( get_option( 'woocommerce_discount_on_sale', 'yes' ) == 'yes' ){
					return true;
				}else{
					return false;
				}
			}



			public function wdp_plugin_links($links) {



				$settings_link = '<a href="admin.php?page=wc-settings&tab=plus_discount">'.__('Settings', "wcdp").'</a>';

				if($this->wdp_pro){
					array_unshift($links, $settings_link);
				}else{

					$this->premium_link = '<a href="'.esc_url($this->premium_link).'" title="'.__('Go Premium', "wcdp").'" target="_blank">'.__('Go Premium', "wcdp").'</a>';
					array_unshift($links, $settings_link, $this->premium_link);

				}


				return $links;
			}
			public function get_closest($search, $arr) {
				$closest = null;
				foreach ($arr as $item) {
					if ($search!=$item && $closest === null || abs($search - $closest) > abs($item - $search)) {
						$closest = $item;
					}
				}
				return $closest;
			}

			public function filter_woocommerce_short_description_free( $post_excerpt )   {
				// make filter magic happen here...
				global $wdp_pricing_scale, $wdp_price_num_decimals;

				if($wdp_pricing_scale)
					return;

				//pree(plus_discount_product_display(get_the_ID()));
				$plus_discount_enabled = plus_discount_enabled(get_the_ID(), true);
				//pree($plus_discount_enabled);

				if(is_product() && !in_array(get_the_ID(), wc_get_product_ids_on_sale()) && plus_discount_product_display(get_the_ID())){

					$wdpq = array();



					$meta = get_post_meta(get_the_id());
					//pree($meta);
					$_regular_price = get_post_meta(get_the_id(), '_regular_price', true);
					$_price = get_post_meta(get_the_id(), '_price');
					//pree($_regular_price);
					//pree($_price);
					$is_flat = (get_option( 'woocommerce_discount_type', '' ) == 'flat');
					
					
					//pree($plus_discount_enabled);

					switch($plus_discount_enabled){
						case 'category_based':


							//18/10/2018
							$actual_id = $this->get_actual_id( get_the_ID() );
							$wcdp_cat = get_post_meta(get_the_ID(), 'dc_cat_id', true);
							//pree($wcdp_cat);
							$plus_discount_type_globally = (get_option( 'woocommerce_plus_discount_type', 'quantity' ));
							$plus_discount_type = plus_discount_type($actual_id, true); //using actual product ID for global/product settings instead of each variation to avoid complexity
							$plus_discount_type = ($plus_discount_type==$plus_discount_type_globally?$plus_discount_type_globally:'quantity');

							//pree($plus_discount_type);

							switch($plus_discount_type){
								default:
								case 'quantity':
									$wdpq = get_option( 'wdp_qd_'.$wcdp_cat );
									break;
								case 'weight':
									$wdpq = get_option( 'wdp_qdw_'.$wcdp_cat );
									break;
							}
							//18/10/2018

							break;
						case 'default':
							$wdpq = get_option( 'wdp_qd', array() );



							break;
						case 'yes':

							if(!empty($meta)){
								$r_array = array();
								foreach($meta as $k=>$arr){

									$qd = substr($k, strlen('plus_discount_'), 1);

									$index = substr($k, -1, 1);

									//plus_discount_quantity_1
									//plus_discount_discount_1
									//plus_discount_discount_flat_4

									if(in_array($qd, array('q'))){

										//$arr = get_post_meta( get_the_id(), "plus_discount_quantity_$index" );

									}elseif(in_array($qd, array('d'))){



										if($is_flat){
											$arr = get_post_meta( get_the_id(), "plus_discount_discount_flat_$index" );
										}else{
											$arr = get_post_meta( get_the_id(), "plus_discount_discount_$index" );
										}





									}else{
										$arr = array();
									}

									if(!empty($arr)){
										$val = current($arr);
										if($val>0)
											$wdpq[$index][$qd] = $val;
									}

								}
							}

							break;
					}

					if(!empty($wdpq)){
						$post_excerpt .= '<div class="wdp_price_scale"><h4 class="wsdps">'.get_option('wcdp_pricing_scale_text', __('Pricing Scale', "wcdp").':').'</h4>';
						$post_excerpt .= '<ul class="wsdps"><li><strong>'.get_wcdp_discount_label().'</strong></li>';





						$q_array = array();
						
						$pcntg = 'percentage';

						foreach($wdpq as $dpq){
							
							$dpq['d']= isset($dpq['d'])?$dpq['d']:0;

							if($is_flat){
								$dprice = round(((($dpq['q']*current($_price))-$dpq['d'])/$dpq['q']), $wdp_price_num_decimals);

								$spi = (current($_price)-$dprice);
								//pree($spi);
								$price = wdp_get_formatted_price($dprice).' (Save '.wdp_get_formatted_price($spi).' per item)';
							}else{
								$price = $dpq['d'].$pcntg;//$_regular_price-($_regular_price*($dpq['d']/100));
							}

							//$closest = $this->get_closest($dpq['q'], $dpq);
							//pree($dpq['q'].' - '.$closest);


							if($price!=$pcntg){
								//pree($price);
								$post_excerpt_Arr[$dpq['q']] = '<li><span>%s%s</span><span>'.$price.'</span></li>';
							}
							



						}



						ksort($post_excerpt_Arr);
						//pree($post_excerpt_Arr);
						if(!empty($post_excerpt_Arr)){
							$i = 0;
							$post_excerpt_Arr_keys = array_keys($post_excerpt_Arr);
							foreach($post_excerpt_Arr as $item){ $i++;

								$qty_end = (($post_excerpt_Arr_keys[$i-1])*1);

								$qty_range = ($i>1?($post_excerpt_Arr_keys[$i-1].($qty_end>0?' - '.$qty_end:'')):$post_excerpt_Arr_keys[$i-1]);
								//echo $i.' > '.count($post_excerpt_Arr);
								if($i==count($post_excerpt_Arr))
									$post_excerpt .= sprintf($item, $qty_range, '');
								else
									$post_excerpt .= sprintf($item, $qty_range, '');
							}

							$post_excerpt = str_replace('percentage', '%', $post_excerpt);
							$post_excerpt .= '</ul></div>';
							
						}else{
							$post_excerpt = '';
						}
					}

				}else{

				}
				$wdp_pricing_scale = true;

				return $post_excerpt;
			}


			function is_pro(){
			}
			
			
			function discount_after_shipping_thank_you($total_rows, $order, $tax_display){
			
				//global $wdp_cart_total_discount;
				//$gj_logic_status = get_option( 'gj_logic_status', 'no' );
				/*
				$wcp_discount_type = get_option('woocommerce_plus_discount_type');
				
				if($wcp_discount_type == 'cart_amount'){
					return $total_rows;
				}
				
				$discount_love = $this->gather_discount_love_from_order( $order->get_id() );
				if ( empty( $discount_love ) ) {
					return $total_rows;
				}
				
				
				$coeff = 0;
				$d_items = array();
				$plus_discount_type_globally = (get_option( 'woocommerce_plus_discount_type', 'quantity' ));
				
				if(!empty($discount_love)){
				
					foreach($discount_love as $item_id=>$item_arr){
						if($item_arr['coeff']>$coeff && $item_arr['coeff']<1){
							$coeff = $item_arr['coeff'];
						}
						
	
						if(!$wdp_cart_total_discount){						
							$wdp_cart_total_discount += (isset($item_arr['disc_amount_flat']) ? $item_arr['disc_amount_flat'] : $item_arr['orig_price_actual']*(1 - $item_arr['coeff'])*$item_arr['quantity']);
						}						
						
						switch($plus_discount_type_globally){
							case 'weight':
							$d_items[$item_id] = array_key_exists('orig_price', $item_arr)?$item_arr['orig_price']*($item_arr['quantity']/$item_arr['base_weight']):0;
							break;
							
							case 'quantity':
							//orig_price to orig_price_actual 07/06/2019
							$d_items[$item_id] = array_key_exists('orig_price_actual', $item_arr)?$item_arr['orig_price_actual']*$item_arr['quantity']:0;
							break;
						}
					}
				
				}
				
				if($wdp_cart_total_discount <= 0){
				
					return $total_rows;
				
				}
				
				
				$d_total_amount = array_sum($d_items);
				$total_rows['cart_subtotal']['value'] = wc_price($d_total_amount);
				*/
				$wpdp_total_discount_value = trim(get_post_meta($order->get_id(), '_wpdp_total_discount_value', true));
				$wpdp_total_discount_value = ($wpdp_total_discount_value?$wpdp_total_discount_value:(isset(WC()->session)?WC()->session->get('wpdp_total_discount_value', 0):0));
				//pree(get_post_meta($order->get_id()));
				
				$total_rows = array_slice($total_rows, 0, 3, true) +
				array("wdp_discount" => array('label' => get_wcdp_discount_label().':', 'value' => wc_price($wpdp_total_discount_value))) + //$wdp_cart_total_discount
				array_slice($total_rows, 3, count($total_rows)-3, true);
			
				
				
				return $total_rows;
			
			}
			
			
			function discount_after_shipping_cart(){
			
				global $wdp_cart_total_discount;
				//pree($wdp_cart_total_discount);
				$wcp_discount_type = get_option('woocommerce_plus_discount_type');
				$gj_logic_status = get_option( 'gj_logic_status', 'no' );
				
				if($wcp_discount_type == 'cart_amount' || $wdp_cart_total_discount <= 0){
					return;
				}
			
			
			
				if($gj_logic_status=='no'){
					WC()->session->set('wpdp_total_discount_value', $wdp_cart_total_discount);
			?>
			
	<tr class="woocommerce-shipping-totals shipping">
		<th><?php echo get_wcdp_discount_label(); ?>:</th>
        <td data-title="<?php echo esc_attr(get_wcdp_discount_label()); ?>">
            <?php echo wc_price($wdp_cart_total_discount); ?>
        </td>
	</tr>
			
			<?php
			
				}
			
			}

		}




		//new Woo_Discounts_Plus_Plugin();

	}