<?php if ( ! defined( 'ABSPATH' ) ) exit;

	if(!function_exists('wdp_sanitize_arr_data')){
		function wdp_sanitize_arr_data( $input ) {
			if(is_array($input)){		
				$new_input = array();	
				foreach ( $input as $key => $val ) {
					$new_input[ $key ] = (is_array($val)?wdp_sanitize_arr_data($val):stripslashes(sanitize_text_field( $val )));
				}			
			}else{
				$new_input = stripslashes(sanitize_text_field($input));			
				if(stripos($new_input, '@') && is_email($new_input)){
					$new_input = sanitize_email($new_input);
				}
				if(stripos($new_input, 'http') || wp_http_validate_url($new_input)){
					$new_input = sanitize_url($new_input);
				}			
			}	
			return $new_input;
		}
	}

	
	function wdp_s2_roles(){
		global $wp_roles;
		$s2_options = &$GLOBALS['WS_PLUGIN__']['s2member']['o'];
		$s2_roles = array();
		if(!empty($wp_roles) && isset($wp_roles->roles) && !empty($wp_roles->roles)){
			foreach($wp_roles->roles as $key=>$arr){
				if(substr($key, 0, strlen('s2member_level'))=='s2member_level'){
					$s2_key = str_replace('s2member_', '', $key).'_label';
					if(array_key_exists($s2_key, $s2_options)){
						$s2_roles[$key] = $s2_options[$s2_key];
					}
				}
			}
		}
		return $s2_roles;
	}
	
	function wdp_admin_init(){
		//if ( !in_array( 's2member/s2member.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) return;
		//pre(get_option( 'active_plugins' ));
		
	}
	
	
	
	if(!function_exists('wdp_s2member_admin_menu')){
		function wdp_s2member_admin_menu() {
			global $wcdp_data;
			
			$menu = apply_filters('ws_plugin__s2member_during_add_admin_options_menu_slug', 'ws-plugin--s2member-start', get_defined_vars());
			add_submenu_page($menu, $wcdp_data['Name'], 'Discounts Plus', 'activate_plugins', 'wdp-s2member-settings', 'wdp_s2member_settings');
			add_submenu_page('woocommerce', __( $wcdp_data['Name'], "wcdp" ), __( 'Discounts Plus', "wcdp" ), 'manage_woocommerce', 'wc_wcdp', 'wc_wcdp_settings' );
			
		}	
	}	
	add_action('admin_menu', 'wdp_s2member_admin_menu');
	
	require_once('s2member-settings.php');
	
	function wdp_s2member_discount(){
		$role = wdp_s2member_access_level();
		$fp = get_option('wdp_'.$role);
		//pree($fp);
		return $fp;
	}
	
	function wdp_s2member_access_level(){
		
		$role = '';
		
		$s2_roles = wdp_s2_roles();
		//pree($s2_roles);
		$user_id = get_current_user_id();
		
		//$wp_capabilities = get_user_meta($user_id, 'wp_capabilities', true);
		$user_meta = get_userdata($user_id);
   		$wp_capabilities = $user_meta->roles;
		
		//pree($user_id);
		//pree($wp_capabilities);
		//pree($s2_roles);
		
		$valid_roles = array();
		
		if(is_array($wp_capabilities) && is_array($s2_roles)){
			$s2_roles = array_keys($s2_roles);
			$valid_roles = array_intersect($wp_capabilities, $s2_roles);
		}
		
		//pree($valid_roles);
		if(!empty($valid_roles)){
			//$valid_roles = array_keys($valid_roles);
			$role = current($valid_roles);
		}
		//pree($role);
		return $role;
	}
	
	function wdp_wp_init(){
		global $wdp_discount_condition;
		
		if(isset($_GET['need_shipping'])){
			wdp_sessions();
			$_SESSION['need_shipping'] = ($_GET['need_shipping']=='true'?1:0);
			session_write_close();
		}elseif(!isset($_SESSION['need_shipping'])){
			wdp_sessions();
			$on_shipping_decision = (get_option( 'woocommerce_show_discounts_on_shipping_decision', 'yes' ) == 'yes');
			$_SESSION['need_shipping'] = (($on_shipping_decision && $wdp_discount_condition!='default')?1:0);
			session_write_close();
		}
		
		
		if(isset($_GET['wdp_s2member_access_level'])){
			wdp_s2member_access_level();
		}
		
			

				
	}
	
	add_action('init', 'wdp_wp_init');
	
	function wdp_get_current_user_role() {
		global $wp_roles;
	
		$current_user = wp_get_current_user();
		$roles = $current_user->roles;
		$role = array_shift( $roles );
	
		return isset( $wp_roles->role_names[ $role ] ) ? $role : FALSE;
	}		
	
	if(!function_exists('wdp_sessions')){
		function wdp_sessions(){
			if (!session_id()){
				ob_start();
				@session_start();
			}
		}
	}
	
	function plus_discount_type($product_id){
		
		$plus_discount_type = get_post_meta($product_id, "plus_discount_type", true );		
		return ($plus_discount_type!='weight'?'quantity':'weight');
	
	}
		
	function plus_discount_product_display($product_id, $actual = false){

		$product_settings = get_post_meta($product_id, "plus_discount_product_display", 'yes' );
		
		if($product_settings=='yes' && plus_discount_enabled($product_id, true)!='no'){
		
		}else{
			$product_settings = 'no';
		}
		
		if(!$actual){
			switch($product_settings){
				case 'yes':
					$product_settings = true;
				break;
				case 'no':
				default:
					$product_settings = false;
				break;				
			}
		}

		return $product_settings;		
	}
	
	function plus_discount_enabled($product_id, $actual = false){
		global $wdpp_obj, $post;
		
		$product_settings = get_post_meta($product_id, "plus_discount_enabled", true );
		$wc_discount_type = get_option('woocommerce_plus_discount_type');
		
		//pree($wc_discount_type);exit;
		
		$is_product = (is_object($post) && $post->post_type='product');
		
		//pree($product_id.'-'.$product_settings);
		//pree($wc_discount_type);
		if($product_settings==''){
			$_product = wc_get_product( $product_id );

			if($_product instanceof WC_Product_Variation){
				//pree($_product->get_price());
				$variation_excluded = get_post_meta($_product->get_id(), "plus_discount_excluding", true );
				$variation_excluded = is_array($variation_excluded)?$variation_excluded:array();
				//pree($variation_settings);
				
				$product_settings = get_post_meta($_product->get_id(), "plus_discount_enabled", true );
				
				if($product_settings!='no' && in_array($product_id, $variation_excluded)){
					$product_settings = 'no';
				}
				

			}
		}elseif($is_product){
			return $product_settings;	
		}
		//pree($product_id.'-'.$product_settings);
		//if it's not yet been touched or maybe it's no
		//pre($product_settings);
		switch($product_settings){
			case 'yes':
				$product_settings = 'yes';
			break;
			case 'no':
				$product_settings = 'no';
			break;		
			default:
			case 'default':			
			
				if($actual){
					$product_settings = ($product_settings!=''?$product_settings:'no');					
				}else{
					$product_settings = get_option( 'woocommerce_enable_plus_discounts', 'no' );
				}
				
			break;		
		}

//		pre($product_settings);
		
		if(!$actual){
			switch($product_settings){
				case 'yes':
					$product_settings = true;
				break;
				case 'no':
					$product_settings = false;
				break;				
			}
		}
		//pree($product_id);
		//pree($product_settings);		
		//$product_settings = 0;
		//return false;

        if($wc_discount_type == 'cart_amount'){

            $product_settings = $actual ? 'no' : false;
        }


		if(class_exists('WC_Memberships_Loader')){
			global $woocommerce;
			if(is_object($woocommerce) && isset($woocommerce->cart) && method_exists($woocommerce->cart, 'get_cart')){
				$items = $woocommerce->cart->get_cart();
				if(!empty($items)){	
					//pree($wdpp_obj->discount_love[$product_id]);
					foreach($items as $item => $values) {
						if($product_id==$values['product_id'] || (isset($values['variation_id']) && $values['variation_id']>0 && $values['variation_id']==$product_id)){
							$_product = wc_get_product( $values['product_id'] );			
							//pree($_product->get_price());						
														
							if(array_key_exists('orig_price', $wdpp_obj->discount_love[$product_id]) && $wdpp_obj->discount_love[$product_id]['orig_price']!=$_product->get_price()){
								$product_settings = false;
							}							
						}
					} 		
				}
			}
		}
		
//		pree($product_settings);
//		exit;
		return $product_settings;
	}
		
	if(!function_exists('woocommerce_wp_select_multiple')){	
		function woocommerce_wp_select_multiple( $field ) {
			global $thepostid, $post;
		
			$thepostid              = empty( $thepostid ) ? $post->ID : $thepostid;
			$field['class']         = isset( $field['class'] ) ? $field['class'] : 'select short';
			$field['style']         = isset( $field['style'] ) ? $field['style'] : '';
			$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
			//$field['value']         = isset( $field['value'] ) ? $field['value'] : get_post_meta( $thepostid, $field['id'], true );
			$field['value']         = isset( $field['value'] ) ? $field['value'] : ( get_post_meta( $thepostid, $field['id'], true ) ? get_post_meta( $thepostid, $field['id'], true ) : array() );
				
			$field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];
			//pree($field);
			// Custom attribute handling
			$custom_attributes = array();
		
			if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {
		
				foreach ( $field['custom_attributes'] as $attribute => $value ){
					$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
				}
			}
		
			echo '<p class="form-field ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '"><label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label><select id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['name'] ) . '[]" class="' . esc_attr( $field['class'] ) . '" style="' . esc_attr( $field['style'] ) . '" ' . implode( ' ', $custom_attributes ) . ' multiple="multiple">';
		
			foreach ( $field['options'] as $key => $value ) {
				//echo '<option value="' . esc_attr( $key ) . '" ' . selected( esc_attr( $field['value'] ), esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
		        echo '<option value="' . esc_attr( $key ) . '" ' . ( is_array($field['value']) && in_array( $key, $field['value'] ) ? 'selected="selected"' : '' ) . '>' . esc_html( $value ) . '</option>';
			}
		
			echo '</select> ';
		
			if ( ! empty( $field['description'] ) ) {
		
				if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) {
					echo wc_help_tip( $field['description'] );
				} else {
					echo '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
				}
			}
			echo '</p>';
		}	
	}

	function wdp_get_product_id($item){
		$product_id = isset($item['product_id'])?$item['product_id']:false;
		if(!$product_id && method_exists($item,'item')){
			$product_id = $item->get_product_id();
		}
		return $product_id;
	}
		
	function wdp_woocommerce_cart_item_name( $product_get_name, $cart_item, $cart_item_key ){
		
		//if(is_cart()){
			ob_start();
			
			$product_id = wdp_get_product_id($cart_item);
			echo wp_kses_post($product_get_name);
			$plus_discount_type = plus_discount_type($product_id, true);
			switch($plus_discount_type){
				case 'weight':
				//pree($product_id);
				$product_id = ($cart_item['variation_id']?$cart_item['variation_id']:$product_id);
				
				$_product = wc_get_product( $product_id );
				
				
	?>
	<div class="plus_discount_type"><?php echo ucwords($plus_discount_type); ?>: <?php echo esc_html($_product->get_weight().get_option( 'woocommerce_weight_unit' )); ?><?php echo ($cart_item['quantity']>1?' <small>x'.esc_html($cart_item['quantity']).'</small>':''); ?></div>	
	<?php		
				break;
			}
			
			$out1 = ob_get_contents();
			
			ob_end_clean();
			
			return $out1;
		//}
	}
	
	add_filter('woocommerce_cart_item_name', 'wdp_woocommerce_cart_item_name', 10, 3);			
		
	add_action( 'woocommerce_before_checkout_form', 'wdp_woocommerce_add_checkout_notice', 11 );
	
	function wdp_woocommerce_add_checkout_notice() {
		
		global $wdp_discount_condition;
	
		
		
		$error_messages = wcdp_get_error_messages();
		switch($wdp_discount_condition){
			case 'no_shipping':
				if(!isset($_SESSION['need_shipping']) || !$_SESSION['need_shipping'])
				wc_print_notice( ( $error_messages['no_shipping'][0] ), 'error' );	
				else
				wc_print_notice( ( $error_messages['no_shipping'][1] ), 'error' );	
			break;
			case 'only_shipping':
				if(isset($_SESSION['need_shipping']) || !$_SESSION['need_shipping'])
				wc_print_notice( ( $error_messages['only_shipping'][0] ), 'error' );	
				elseif(!isset($_SESSION['need_shipping']) || (isset($_SESSION['need_shipping']) && !$_SESSION['need_shipping']))
				wc_print_notice( ( $error_messages['only_shipping'][1] ), 'error' );	
			break;

		}
	}	
	
	function wcdp_get_error_messages(){
		
		$checkout_url = wc_get_checkout_url();		
		$arr = array();
		
		$wcdp_error_messages = get_option('wcdp_dac_error_messages', array());
		//pree($wcdp_error_messages);exit;
		
		$need_shipping_true = (is_admin()?'%s':$checkout_url.'?need_shipping=true');
		$need_shipping_false = (is_admin()?'%s':$checkout_url.'?need_shipping=false');
		
		
		$arr['no_shipping'][0] = sprintf(''.__('Discounts are available only with "pickup from store" option.', "wcdp").' '.__('If you need shipment so discounts will be waved off.', "wcdp").' <br /><a class="button alt" href="%s">'.__('click here for shipping option', "wcdp").'</a>', $need_shipping_true);
		$arr['no_shipping'][1] = sprintf(''.__('Discounts are available only with "pickup from store" option.', "wcdp").' '.__('If you need discount so you need to pickup your order from our store.', "wcdp").' <br /><a class="button alt" href="%s">'.__('click here for discounts', "wcdp").'</a>', $need_shipping_false);
		$arr['only_shipping'][0] = sprintf(''.__('Discounts are available only with shipping.', "wcdp").' '.__('Are you interested in pickup from store?', "wcdp").'<br /><a class="button alt" href="%s">'.__('click here for shipping option', "wcdp").'</a>', $need_shipping_false);
		$arr['only_shipping'][1] = sprintf(''.__('Discounts are available only with shipping.', "wcdp").' '.__('Are you interested in getting discounts?', "wcdp").'<br /><a class="button alt" href="%s">'.__('click here for discounts', "wcdp").'</a>', $need_shipping_true);

		
		if(!empty($wcdp_error_messages)){

			foreach($wcdp_error_messages as $key=>$data){
				if(!empty($data)){
					foreach($data as $k=>$d){
						if(trim($d)!='' && $arr[$key][$k]){
							
							switch($key){
								case 'no_shipping':
									if($k){
										$d = sprintf($d, $need_shipping_false);
									}else{
										$d = sprintf($d, $need_shipping_true);
									}
								break;
								case 'only_shipping':
									if($k){
										$d = sprintf($d, $need_shipping_true);
									}else{
										$d = sprintf($d, $need_shipping_false);
									}
								break;
							}
							
							$arr[$key][$k] = stripslashes($d);
						}
					}
				}				
			}
		}
		
		return $arr;
	}
		
	function wdp_get_formatted_price($price) {
		
		$symbol = get_woocommerce_currency_symbol();
		$currency_pos = get_option( 'woocommerce_currency_pos' );
		$price_format = '%1$s%2$s';
		
		switch ( $currency_pos ) {
		case 'left' :
		  $price_format = '%1$s%2$s';
		break;
		case 'right' :
		  $price_format = '%2$s%1$s';
		break;
		case 'left_space' :
		  $price_format = '%1$s&nbsp;%2$s';
		break;
		case 'right_space' :
		  $price_format = '%2$s&nbsp;%1$s';
		break;
		}
	
		$negative = ($price < 0);
		$formatted_price = ( $negative ? '-' : '' ) . sprintf( $price_format, $symbol, $price );
	  	return $formatted_price;
	}
	 
	function wdp_woocommerce_header_scripts(){
		
		global $wdp_discount_condition;
?>
	<style type="text/css">
	<?php
		if($wdp_discount_condition=='no_shipping' && wdp_woocommerce_discount_applicable()){
?>
			.woocommerce-shipping-fields{
				display:none;	
			}
<?php			
		}
		/*if(get_option('eufdc_billing_off', 0)){
?>
			.woocommerce-billing-fields{
				display:none;	
			}
<?php			
		}
		if(get_option('eufdc_order_comments_off', 0)){
?>

<?php			
		}		*/		
	?>
	</style>
<?php		
	}
	
	add_action('wp_head', 'wdp_woocommerce_header_scripts');
	
	add_filter( 'woocommerce_checkout_fields' , 'wdp_woocommerce_override_checkout_fields' );
	
	function wdp_woocommerce_discount_applicable(){
		
		global $wdp_discount_condition;
		
		$ret = true;
		
		wdp_sessions();
		$on_shipping_decision = (get_option( 'woocommerce_show_discounts_on_shipping_decision', 'yes' ) == 'yes');
		
		$decision = ($wdp_discount_condition!='default' && $on_shipping_decision && !isset($_SESSION['need_shipping']));
		
		if($decision){
			$ret = false;
		}
		
		switch($wdp_discount_condition){
			case 'no_shipping':
				//wdp_sessions();
				
				if(array_key_exists('need_shipping', $_SESSION) && $_SESSION['need_shipping'])
				$ret = false;				
				
			break;
			case 'only_shipping':
				//wdp_sessions();
				
				if(!$_SESSION['need_shipping'])
				$ret = false;				
				
			break;			
		}

		if(isset($_GET['debug'])){
			//wdp_sessions();
			//pre('wdp_discount_condition: '.$wdp_discount_condition);
			//pre('on_shipping_decision: '.$on_shipping_decision);
			//pre('_SESSION need_shipping: '.!isset($_SESSION['need_shipping']));
			//pre('ret: '.$ret);
			//pre('decision: '.$decision);
			//pre($_SESSION);
			//exit;
		}		
				
		//pree($ret);
		session_write_close();
		return $ret;
		
		
	}
	
	function wdp_woocommerce_override_checkout_fields( $fields ) {
		 
		global $wdp_discount_condition;
		
		if($wdp_discount_condition=='no_shipping' && wdp_woocommerce_discount_applicable()){
			unset($fields['shipping']['shipping_first_name']);
			unset($fields['shipping']['shipping_last_name']);
			unset($fields['shipping']['shipping_company']);
			unset($fields['shipping']['shipping_address_1']);
			unset($fields['shipping']['shipping_address_2']);
			unset($fields['shipping']['shipping_city']);
			unset($fields['shipping']['shipping_postcode']);
			unset($fields['shipping']['shipping_country']);
			unset($fields['shipping']['shipping_state']);
			unset($fields['shipping']['shipping_phone']);	
			unset($fields['shipping']['shipping_address_2']);
			unset($fields['shipping']['shipping_postcode']);
			unset($fields['shipping']['shipping_company']);
			unset($fields['shipping']['shipping_last_name']);
			unset($fields['shipping']['shipping_email']);
			unset($fields['shipping']['shipping_city']);	
		}
		
		/*if(get_option('eufdc_billing_off', 0)){
			unset($fields['billing']['billing_first_name']);
			unset($fields['billing']['billing_last_name']);
			unset($fields['billing']['billing_company']);
			unset($fields['billing']['billing_address_1']);
			unset($fields['billing']['billing_address_2']);
			unset($fields['billing']['billing_city']);
			unset($fields['billing']['billing_postcode']);
			unset($fields['billing']['billing_country']);
			unset($fields['billing']['billing_state']);
			unset($fields['billing']['billing_phone']);	
			unset($fields['billing']['billing_address_2']);
			unset($fields['billing']['billing_postcode']);
			unset($fields['billing']['billing_company']);
			unset($fields['billing']['billing_last_name']);
			unset($fields['billing']['billing_email']);
			unset($fields['billing']['billing_city']);
		}
		
		if(get_option('eufdc_order_comments_off', 0))
		unset($fields['order']['order_comments']);
		
		*/
		
		return $fields;
	}	
	
	function wdp_get_user_roles(){
		$ret = array();
		global $wp_roles;
		if(!empty($wp_roles) && isset($wp_roles->roles) && !empty($wp_roles->roles)){
			$ret['default'] = __('Default', "wcdp");
			foreach($wp_roles->roles as $key=>$arr){
				$ret[$key] = $arr['name'];
			}
		}		
		return $ret;
	}	
	
	
	function wc_wcdp_settings(){

		global $wcdp_data, $wdp_pro, $wdpp_obj, $wdp_dir;

		//pree($wcdp_data);
		$pro_settings = $wdp_dir.'inc/settings.php';
		if(file_exists($pro_settings))
		include(realpath($pro_settings));

	}

    function wdp_update_cart_criteria(){

        if(! isset( $_POST['wcdp_cc_field'] ) || ! wp_verify_nonce( $_POST['wcdp_cc_field'], 'wcdp_cc' )

        ) {
            _e('Sorry, your nonce did not verify.');

            exit;

        } else {
			
			

            if(isset($_POST['wd_cart_criteria'])){
				
				$wd_cart_criteria = wdp_sanitize_arr_data($_POST['wd_cart_criteria']);

                foreach($wd_cart_criteria as $k=>$arr){

                    $_POST['wd_cart_criteria'][$k] = array_values($arr);

                }

            }

            //pree($_POST);

            //exit;

            $wd_cart_criteria = array();

            $wd_cart_criteria_post = (isset($_POST['wd_cart_criteria'])?wdp_sanitize_arr_data($_POST['wd_cart_criteria']):array('amount'=>array(),'discount'=>array()));

            if(count($wd_cart_criteria_post['amount'])>0){

                for($q=0; $q<count($wd_cart_criteria_post['amount']); $q++){

                    //pree($wdct['qty'][$q]);

                    $wd_cart_criteria[$q]['amount'] = $wd_cart_criteria_post['amount'][$q];

                    $wd_cart_criteria[$q]['discount'] = $wd_cart_criteria_post['discount'][$q];

                }



            }


          return update_option('wd_cart_criteria', $wd_cart_criteria);

        }

    }

	function wdp_update_global_criteria(){

		
		if(!empty($wdct)){
			
			$wdct = wdp_sanitize_arr_data($_POST['wdct']);
			
			foreach($wdct as $k=>$arr){

				$_POST['wdct'][$k] = array_values($arr);

			}

		}

		//pree($_POST);

		//exit;

		$wdp_qd = array();

		$wdct = (isset($_POST['wdct'])?wdp_sanitize_arr_data($_POST['wdct']):array('qty'=>array(),'dst'=>array()));

		if(count($wdct['qty'])>0){

			for($q=0; $q<count($wdct['qty']); $q++){

				//pree($wdct['qty'][$q]);

				$wdp_qd[$q]['q'] = $wdct['qty'][$q];

				$wdp_qd[$q]['d'] = $wdct['dst'][$q];

			}



		}

		return update_option('wdp_qd', $wdp_qd);

    }

    function wdp_update_general_settings(){

	    global  $wdpp_obj;

	    if(isset($_POST['wpdp_general_save_changes'])){


		    if(! isset( $_POST['wdp_settings_nonce_field'] )

		       || ! wp_verify_nonce( $_POST['wdp_settings_nonce_field'], 'wdp_settings_action' )

		    ) {
			    _e('Sorry, your nonce did not verify.');

			    exit;

		    } else {


			    $wdp_input_ids = $wdpp_obj->wdp_get_input_ids();
			    $wdp_post_array = wdp_sanitize_arr_data($_POST);
			    $input_array_with_values = array();

			    if(!empty($wdp_input_ids)){

				    foreach ($wdp_input_ids as $input_id => $type){

					    if($type == 'checkbox'){

						    if(array_key_exists($input_id, $wdp_post_array)){

							    $input_array_with_values[$input_id] = 'yes';

						    }else{

							    $input_array_with_values[$input_id] = 'no';

						    }

					    }else{

						    if(array_key_exists($input_id, $wdp_post_array)){

							    $input_array_with_values[$input_id] = $wdp_post_array[$input_id];

						    }
					    }

				    }
			    }

			    if(!empty($input_array_with_values)){

				    $updated_array = array();

				    foreach ($input_array_with_values as $option_name => $option_value){

					    $updated_array[$option_name] = update_option($option_name, $option_value);

				    }


				    return in_array(true, array_values($updated_array));
			    }



		    }


	    }

    }
	
	function wdp_settings_posted_pro(){

	    global  $wcdp_settings_saved;
	

		if(is_admin()){

//            pree($_POST);exit;

			$wcdp_settings_saved = wdp_update_general_settings();

            if (!empty($_POST) && isset($_POST['wcdp_submit_cart_criteria'])) {

                $wcdp_settings_saved = wdp_update_cart_criteria();

            }

			//if(count($_POST)>0 && isset($_POST['woocommerce_enable_plus_discounts'])){
				//wdp_update_global_criteria();
			//}


			if (!empty($_POST) && isset($_POST['wcdp_submit_global_criteria'])){

				if(! isset( $_POST['wcdp_cc_field'] ) || ! wp_verify_nonce( $_POST['wcdp_cc_field'], 'wcdp_cc' )

				) {
					_e('Sorry, your nonce did not verify.', 'wcdp');

					exit;

				} else {


					$wcdp_settings_saved = wdp_update_global_criteria();

				}
			}


			if (!empty($_POST) && isset($_POST['wcdp_cats'])){

				

				if(! isset( $_POST['wcdp_cc_field'] ) || ! wp_verify_nonce( $_POST['wcdp_cc_field'], 'wcdp_cc' ) 

			) {

				

				   _e('Sorry, your nonce did not verify.', 'wcdp');

				   exit;

				

				} else {

				

					// process form data
					
					
//					pree($_POST);exit;
                    $update_settings_array = array();
					$wcdp_cats = wdp_sanitize_arr_data($_POST['wcdp_cats']);
					$wcdp_pricing_scale_text = wdp_sanitize_arr_data($_POST['wcdp_pricing_scale_text']);
					$wcdp_criteria_no = wdp_sanitize_arr_data($_POST['wcdp_criteria_no']);
					

					$update_settings_array[] = update_option('wcdp_pricing_scale_text', $wcdp_pricing_scale_text);
					$update_settings_array[] = update_option('wcdp_criteria_no', $wcdp_criteria_no);
					$update_settings_array[] = update_option('wcdp_cats', $wcdp_cats);

					//pree($_POST);exit;
				   

				   if($wcdp_cats>0){

				   		$wdctc_posted = wdp_sanitize_arr_data($_POST['wdctc']);

						if(!empty($wdctc_posted)){

							foreach($wdctc_posted as $k=>$arr){

								$_POST['wdctc'][$k] = array_values($arr);

							}

						}

						//pree($_POST);

						//exit;

						$wdp_qd = array();

						$wdct = (array_key_exists('wdctc', $_POST)?wdp_sanitize_arr_data($_POST['wdctc']):array());

						if(array_key_exists('qty', $wdct) && count($wdct['qty'])>0){

							for($q=0; $q<count($wdct['qty']); $q++){

								//pree($wdct['qty'][$q]);

								$wdp_qd[$q]['q'] = $wdct['qty'][$q];

								$wdp_qd[$q]['d'] = $wdct['dst'][$q];

							}

							

						}

						//pree($wdp_qd);exit;
						$wcdp_cats = is_array($wcdp_cats)?implode('_', $wcdp_cats):$wcdp_cats;


						
						$plus_discount_type_globally = (get_option( 'woocommerce_plus_discount_type', 'quantity' ));
						switch($plus_discount_type_globally){
							default:

								$update_settings_array[] = update_option('wdp_qd_'.$wcdp_cats, $wdp_qd);
							break;
							case 'weight':

                            	$update_settings_array[] = update_option('wdp_qdw_'.$wcdp_cats, $wdp_qd);
							break;
						}

				   }

					$wcdp_settings_saved = in_array(true, $update_settings_array);

				}

			}


			if (!empty($_POST) && isset($_POST['wcdp_dac_error_messages'])){



				if(! isset( $_POST['wcdp_cc_field'] ) || ! wp_verify_nonce( $_POST['wcdp_cc_field'], 'wcdp_cc' )

				) {



					_e('Sorry, your nonce did not verify.');

					exit;



				} else {


					$wcdp_dac_error_messages = wdp_sanitize_arr_data($_POST['wcdp_dac_error_messages']);
					$wcdp_settings_saved = update_option('wcdp_dac_error_messages', $wcdp_dac_error_messages);


				}


			}

		}

	}

    function wdp_settings_posted(){


    }

    function wdp_head(){

        global $product;

        $wdpq = get_option( 'wdp_qd' );
        $wd_cart_criteria = get_option( 'wd_cart_criteria' );
        $ajax_nonce = wp_create_nonce( "wcdp_cc" );
        ?>
        <script type="text/javascript" language="javascript">

            var wdp_qd = {};
            var wd_cart_criteria = {};
            //var wdp_pp = <?php //echo WDP_PER_PRODUCT?1:0; ?>;
            var wdp_security = '<?php echo esc_attr($ajax_nonce); ?>';
            //if(!wdp_pp)
            wdp_qd = jQuery.parseJSON('<?php echo wp_kses_post(json_encode($wdpq)); ?>');
            wd_cart_criteria = jQuery.parseJSON('<?php echo wp_kses_post(json_encode($wd_cart_criteria)); ?>');

        </script>

        <style type="text/css">
            <?php if(
                        (isset($_GET['tab']) && $_GET['tab']=='plus_discount')
                    ||
                        (isset($_GET['page']) && in_array($_GET['page'], array('wdp-s2member-settings')))
                    ): ?>
            div.error{
                display:none;
            }
            <?php endif; ?>
            #wdp_settings h3 {
                padding: 0 12px;
            }
            #wdp_settings .postbox,
            #wdp_settings .postbox .inside,
            #wdp_settings #poststuff{
                float:left;
                overflow:hidden;
                width:100%;
            }
            .s2_roles_and_criteria{
            }
            .s2_roles_and_criteria li label {
                display: inline-block;
                width: 116px;
            }
            .s2_roles_and_criteria li select,
            .s2_roles_and_criteria li input[type="text"]{
                width:200px;
            }
            a[href="admin.php?page=wdp-s2member-settings"] {
                background-color: #d66060;
                color:#fff !important;
                border-top: 2px solid #d66060;
                border-bottom: 2px solid #d66060;
                font-weight:bold;
            }
            a[href="admin.php?page=wdp-s2member-settings"]:hover {
                border-top: 2px solid #fff;
                border-bottom: 2px solid #fff;
                color:#d66060 !important;
            }
            .s2_roles_guide{
                width:98%;
                float:left;
            }
            .s2_roles_guide a{
                text-decoration:none;
            }
            .s2_roles_guide a:hover{
                text-decoration:underline;
            }
            .s2_roles_guide ul{
                width:50%;
                float:left;
                margin-top: 0;
            }

            .s2_roles_guide .s2_roles_guide_right {
                border-left: 1px solid #eee;
                float: right;
                padding: 0 0 0 20px;
                width: 45%;
            }
            .s2_roles_guide .s2_roles_guide_right ol {
                margin:0;
                padding:0;
                list-style:inside decimal;
            }
            .s2_roles_guide .video_tutorials li {
                width: 100%;
            }
            .s2_roles_guide .video_tutorials li strong{
                font-weight: normal;
            }
            .s2_roles_guide .video_tutorials li iframe {
                display: block;
                margin: 0 0 0 19px;
            }
        </style>
        <?php
    }

    function wdp_options_html($options_array, $selected_val){

	    if(!empty($options_array)){

	        foreach ($options_array as $key => $value){

	            $selected = in_array($key, $selected_val) ? 'selected' : '';

	            echo "<option value='$key' $selected>$value</option>";

            }
        }

    }

    function wdp_get_cart_amount_discount_coef(float $cart_total, array $wd_cart_criteria){

        $coef = 0;

        if(!empty($cart_total)){

            foreach ($wd_cart_criteria as $index => $criteria){

                $amount = (float) $criteria['amount'];
                $discount = (float) $criteria['discount'];

                if(
                    is_float($amount)
                    && is_float($discount)
                ){

                    if($cart_total >= $amount){

                        $coef = $discount;
                    }

                }

            }
        }

        return $coef;

    }

    function wdp_get_cart_amount_discount($order){

        $wcp_discount_type = get_option('woocommerce_plus_discount_type');
        $woocommerce_discount_type = get_option('woocommerce_discount_type', '');
        $order_total = $order->get_total('number');
        $coupons = $order->get_coupons();
        $woocommerce_remove_discount_on_coupon = get_option('woocommerce_remove_discount_on_coupon');

        $is_coupons = $coupons && $woocommerce_remove_discount_on_coupon == 'yes';

		if( $wcp_discount_type !== 'cart_amount'){
			return 0;
		}        
		
		$wd_cart_criteria = get_option( 'wd_cart_criteria', array() );
		$wd_cart_criteria = is_array($wd_cart_criteria)?$wd_cart_criteria:array();
		
		
		if(empty($wd_cart_criteria)){		
			return 0;
		}

        $min_discount_limit = min(array_column($wd_cart_criteria, 'amount'));
		
        $total_discount = 0;


        if(
            $wcp_discount_type !== 'cart_amount'
            || !$wd_cart_criteria
            || !is_array($wd_cart_criteria)
            || $order_total < $min_discount_limit
            || $is_coupons
        ){

            return $total_discount;

        }


        $discount_coef = wdp_get_cart_amount_discount_coef($order_total, $wd_cart_criteria);

        if($woocommerce_discount_type == 'flat'){

            $total_discount = $discount_coef;

        }else{

            if($discount_coef <= 100){
                $total_discount = $order_total * ($discount_coef / 100);
            }
        }


        return $total_discount;


    }

	add_action('woocommerce_thankyou', 'wpdp_checkout_order_processed', 10, 1);
	
	function wpdp_checkout_order_processed($order_id){
		
		if ( ! $order_id )
        return;
		
		$wpdp_total_discount_value = WC()->session->get('wpdp_total_discount_value', 0);
		update_post_meta($order_id, '_wpdp_total_discount_value', $wpdp_total_discount_value);
	}
	
    // define the woocommerce_before_calculate_totals callback
    function woocommerce_cart_totals_before_order_total_action( ) {


            $cart = WC()->cart;
            $cart_total = $cart->get_total('number');
			//pree($cart_total);
            $total_discount = wdp_get_cart_amount_discount($cart);
			//pree($total_discount);
            if($total_discount === 0 ){
                return;
            }

            $cart->set_total($cart_total-$total_discount);


        ?>

                    <tr class="wcdp-order-discount">
                        <th><?php echo get_wcdp_discount_label(); ?>:</th>
                        <td data-title="<?php echo esc_attr(get_wcdp_discount_label()); ?>"><?php echo wc_price($total_discount*(-1)) ?></td>
                    </tr>

            <?php

    };
	
	function get_wcdp_discount_label(){
		return get_option( 'woocommerce_discount_label', __('Discount', "wcdp") );
	}

	function woocommerce_checkout_order_processed_action($order_id){

	    $order = new WC_Order($order_id);
	    $order_total = $order->get_total();
        $total_discount = wdp_get_cart_amount_discount($order);


        if($total_discount === 0 ){
            return;
        }

        $items = $order->get_items();
        if(!empty($items)){

            $discount_per_item = $total_discount/sizeof($items);

            foreach ($items as $item_key => $item){
               $item_total = $item->get_total('number');



                $item_total = $item_total - $discount_per_item;



                $item->set_total($item_total);


               $item->calculate_taxes();

               $item->save();
            }

        }



        update_post_meta($order_id, '_wcdp_cart_discount', $total_discount);
        $order->set_total($order_total-$total_discount);
        $order->save();

    }

    function woocommerce_get_order_item_totals_action($total_rows, $this_var, $tax_display){
		
	    $order_id = $this_var->get_id();

	    $discount = get_post_meta($order_id, '_wcdp_cart_discount', true);

	    if($discount && $discount > 0){

	        $total_rows['wcdp_cart_discount'] = array(
	                'label' => __('Discount:', ''),
                    'value' => '-'.wc_price($discount),
            );

	        $order_total = $total_rows['order_total'];
            unset($total_rows['order_total']);
            $total_rows['order_total'] = $order_total;

        }


	    return $total_rows;

    }

    function woocommerce_admin_order_totals_after_tax_action($order_id){


	    $order = new WC_Order($order_id);
	    $discount = get_post_meta($order_id, '_wcdp_cart_discount', true);
        $order->get_coupons();


	    if($discount && $discount > 0){

            ?>

            <tr>
                <td class="label"><?php echo get_wcdp_discount_label(); ?>:</td>
                <td width="1%"></td>
                <td class="discount-total">
                    <?php echo wc_price($discount, array( 'currency' => $order->get_currency() ) ); // WPCS: XSS ok. ?>
                </td>
            </tr>

            <?php

        }

    }

    // add the action
    add_action( 'woocommerce_cart_totals_before_order_total', 'woocommerce_cart_totals_before_order_total_action', 10, 2 );
    add_action( 'woocommerce_review_order_before_order_total', 'woocommerce_cart_totals_before_order_total_action', 10, 2 );
    add_action( 'woocommerce_checkout_order_processed', 'woocommerce_checkout_order_processed_action');
    add_filter( 'woocommerce_get_order_item_totals', 'woocommerce_get_order_item_totals_action', 10, 3);
	
    if(is_admin()){

        add_action( 'woocommerce_admin_order_totals_after_tax', 'woocommerce_admin_order_totals_after_tax_action');

    }



	if(!function_exists('wcdp_is_product_valid')){

		function wcdp_is_product_valid($product_id){

			$product = wc_get_product($product_id);
			
			if(!is_object($product)){ return false; }
			
			$product_type = $product->get_type();
			$product_valid = true;

			

			if($product_type == 'variation'){
				$product_parent = $product->get_parent_id();

				
				$plus_discount_excluding = get_post_meta($product_parent, 'plus_discount_excluding', true);
				$plus_discount_excluding = is_array($plus_discount_excluding) ? $plus_discount_excluding : array();
				$plus_discount_excluding = array_filter($plus_discount_excluding);
	
				if(in_array($product_id, $plus_discount_excluding)){
					$product_valid = false;
				}
				
			}

			return $product_valid;
	
		}
	
	
	}
