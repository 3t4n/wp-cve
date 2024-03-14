<?php
	if (isset($_COOKIE['andreani_notice'])) {
			$_SESSION['andreani_notice'] = $_COOKIE['andreani_notice'];
			add_action( 'admin_notices', 'andreani_admin_notice' );
	}

  add_action( 'wp_footer', 'only_numbers_andreanis');
	function only_numbers_andreanis(){ 
		if ( is_checkout() ) { ?>
 			<script type="text/javascript">
 				jQuery(document).ready(function () {  
				jQuery('#calc_shipping_postcode').attr({ maxLength : 4 });
				jQuery('#billing_postcode').attr({ maxLength : 4 });
				jQuery('#shipping_postcode').attr({ maxLength : 4 });

		          jQuery("#calc_shipping_postcode").keypress(function (e) {
		          if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
		          	return false;
		          }
		          });
		          jQuery("#billing_postcode").keypress(function (e) { 
		          if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) { 
		          return false;
		          }
		          });
		          jQuery("#shipping_postcode").keypress(function (e) {  
		          if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
		          return false;
		          }
		          });
				});
			</script>
		<?php }
	}	//ends only_numbers_andreanis

  

	 /**
	 * Update the order meta with field value
	 */
	add_action( 'woocommerce_checkout_update_order_meta', 'order_sucursal_main_update_order_meta_andreani' );
	function order_sucursal_main_update_order_meta_andreani( $order_id ) {
		session_start();
			$chosen_shipping = json_encode($_SESSION['chosen_shipping'] );
			$params_andreani = json_encode($_SESSION['params_andreani'] );
			update_post_meta( $order_id, '_params_andreani', $params_andreani );
			update_post_meta( $order_id, '_chosen_shipping', $chosen_shipping );
	}

	 /**
	 * Show info at order
	 */
	add_action('add_meta_boxes', 'woocommerce_andreani_box_add_box');

	function woocommerce_andreani_box_add_box() {
		add_meta_box( 'woocommerce-andreani-box', __( 'Andreani - Detalles Envio', 'woocommerce-andreani' ), 'woocommerce_andreani_box_create_box_content', 'shop_order', 'side', 'default' );
	}
	function woocommerce_andreani_box_create_box_content() {
		global $post;
			$site_url = get_site_url();
		  $order = new WC_Order( $post->ID );
			$shipping = $order->get_items( 'shipping' );
		
		  $sucursal_andreani_c = get_post_meta($post->ID, '_sucursal_andreani_c', true);
			
			echo '<div class="andreani-single">';
			echo '<strong>Contrato</strong></br>';
			foreach($shipping as $method){
				echo $method['name'];
			}
			if(!empty($sucursal_andreani_c)){
				$andreani_response = json_decode($sucursal_andreani_c);			
 				echo '</br></br><strong>Dirección</strong></br>'; 
				echo $andreani_response->Direccion .'</br>';
				echo '<strong>Tel.</strong> ' . $andreani_response->Telefono1 . '</br>';
				echo '<strong>Sucursal.</strong> ' . $andreani_response->Sucursal;
			}
			echo '</div>';
	}

	function andreani_admin_notice() {
			?>
			<div class="notice error my-acf-notice is-dismissible" >
					<p><?php print_r($_SESSION['andreani_notice'] ); ?></p>
			</div>

			<?php
	}


?>