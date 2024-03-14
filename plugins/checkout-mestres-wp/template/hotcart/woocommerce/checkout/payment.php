<?php
defined( 'ABSPATH' ) || exit;
	if ( ! wp_doing_ajax() ) {
		do_action( 'woocommerce_review_order_before_payment' );
	}
	global $wpdb;
	echo "
		<script>
		jQuery(document).ready(function($) {
			
	var selected_Id = $('input[name=\"payment_method\"]:checked').attr('id');
	$('label[for=\"'+selected_Id+'\"] .no-active').addClass('hide');
	$('label[for=\"'+selected_Id+'\"]').addClass('selected');
	$('label[for=\"'+selected_Id+'\"] .active').removeClass('hide');
			
			$('.cwmp_woo_cart .title .woocommerce-Price-amount').html('".wc_price(WC()->cart->total)."');
			$('.cwmp_woo_cart .title h2 span').html('(".WC()->cart->get_cart_contents_count().")');
		});
		</script>
	";
	$hash = WC()->session->get_session_cookie();
	$carts_abandoneds = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}cwmp_session_cart WHERE cart = %s",
			$hash[0]
		)
	);
	if(get_option('cwmp_view_active_address')=="S"){
		if($carts_abandoneds[0]->step==1){
		echo "
		<script>
		jQuery(document).ready(function($) {
			$('.cwmp_woo_wrapper .cwmp_woo_form_payment').css('opacity','1');
			$('.section_payment').removeClass('hide');
			var selected_Id = $('input[name=\"payment_method\"]:checked').attr('id');
			$('label[for=\"'+selected_Id+'\"] .no-active').addClass('hide');
			$('label[for=\"'+selected_Id+'\"] .active').removeClass('hide');
			$('label[for=\"'+selected_Id+'\"]').addClass('selected');
		});
		</script>
		";
		}
	}else{
		if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ){
			if($carts_abandoneds[0]->step==2){
				echo "
				<script>
				jQuery(document).ready(function($) {
						$('.cwmp_woo_wrapper .woocommerce-checkout-payment .cwmp_box_method_shipping').css('opacity','1');
						if($('input.shipping_method').attr('type')=='radio'){
							var entrega_escolhida = $('input.shipping_method:checked', '.woocommerce-checkout').attr('id');
						}else{
							var entrega_escolhida = $('input.shipping_method', '.woocommerce-checkout').attr('id');
						}
						var html_entrega = $('label[for=\"' + entrega_escolhida + '\"]').html();
						$('.cwmp_form_method_shipping').removeClass('hide');
						$('#cwmp_step_4 ').removeClass('hide');
						$('.cwmp_form_method_shipping').attr('selected','selected').removeClass('hide');
						
				});
				</script>
				";
			}
			if($carts_abandoneds[0]->step==3){
				echo "
				<script>
				jQuery(document).ready(function($) {
					$('.cwmp_woo_wrapper .woocommerce-checkout-payment .cwmp_box_method_shipping').css('opacity','1');
					$('.cwmp_form_method_shipping').attr('selected','selected').removeClass('hide');
					$('.cwmp_box_method_shipping').addClass('box-success');
					$('.edit_shipping_method').removeClass('hide');
					$('.cwmp_woo_wrapper .cwmp_woo_form_payment').css('opacity','1');
					$('.section_payment').removeClass('hide');
					$('.return_metodo_entrega').removeClass('hide');
					$('.cwmp_form_method_shipping .cwmp_method_shipping').addClass('hide');
					$('.cwmp_form_method_shipping .woocommerce-shipping-methods').addClass('hide');
					$('#cwmp_step_4').addClass('hide');
					if($('input.shipping_method').attr('type')=='radio'){
						var entrega_escolhida = $('input.shipping_method:checked', '.woocommerce-checkout').attr('id');
					}else{
						var entrega_escolhida = $('input.shipping_method', '.woocommerce-checkout').attr('id');
					}
					var selected_Id = $('input[name=\"payment_method\"]:checked').attr('id');
					$('label[for=\"'+selected_Id+'\"] .no-active').addClass('hide');
					$('label[for=\"'+selected_Id+'\"] .active').removeClass('hide');
					$('label[for=\"'+selected_Id+'\"]').addClass('selected');
					$('.cwmp_retorno_shipping_method').removeClass('hide');
					$('.cwmp_retorno_shipping_method div:nth-child(2) strong').html($('.cwmp_form_method_shipping div[selected=\"selected\"] div:nth-child(1) h4').html());
					$('.cwmp_retorno_shipping_method div:nth-child(2) p:nth-child(2)').html($('.cwmp_form_method_shipping div[selected=\"selected\"] div:nth-child(1) p').html());
					$('.cwmp_retorno_shipping_method div:nth-child(2) p:nth-child(3)').html($('.cwmp_form_method_shipping div[selected=\"selected\"] div:nth-child(2) span').html());

				});
				</script>
				";
			}

		}else{
			if($carts_abandoneds[0]->step==2){
			echo "
			<script>
				jQuery(document).ready(function($) {
					$('.cwmp_woo_wrapper .cwmp_woo_form_payment').css('opacity','1');
					$('.section_payment').removeClass('hide');
					var selected_Id = $('input[name=\"payment_method\"]:checked').attr('id');
					$('label[for=\"'+selected_Id+'\"] .no-active').addClass('hide');
					$('label[for=\"'+selected_Id+'\"] .active').removeClass('hide');
					$('label[for=\"'+selected_Id+'\"]').addClass('selected');
				});
			</script>
			";
			}
		}
	}

?>
<div class="woocommerce-checkout-payment">
	<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
	<div class="cwmp_box_method_shipping" style="margin-bottom:20px;">
		<h2>
		   <i class="fa <?php echo get_option('cwmp_checkout_box_icon_frete'); ?>"></i>
		   <span><?php esc_attr_e( 'Shipping', 'checkout-mestres-wp' ); ?></span>
		</h2>
		<p><?php echo esc_attr_e( 'Choose delivery method', 'checkout-mestres-wp'); ?></p>
		<div class="cwmp_retorno_shipping_method hide">
			<div>
				<a href="javascript:void(0)" class="edit_shipping_method">
					<svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M4 15.013L8.413 14.998L18.045 5.45799C18.423 5.07999 18.631 4.57799 18.631 4.04399C18.631 3.50999 18.423 3.00799 18.045 2.62999L16.459 1.04399C15.703 0.287994 14.384 0.291994 13.634 1.04099L4 10.583V15.013ZM15.045 2.45799L16.634 4.04099L15.037 5.62299L13.451 4.03799L15.045 2.45799ZM6 11.417L12.03 5.44399L13.616 7.02999L7.587 13.001L6 13.006V11.417Z" fill="black"/>
					<path d="M2 19H16C17.103 19 18 18.103 18 17V8.332L16 10.332V17H5.158C5.132 17 5.105 17.01 5.079 17.01C5.046 17.01 5.013 17.001 4.979 17H2V3H8.847L10.847 1H2C0.897 1 0 1.897 0 3V17C0 18.103 0.897 19 2 19Z" fill="black"/>
					</svg>
				</a>
			</div>
			<div>
				<p><strong></strong></p>
				<p></p>
				<p></p>
			</div>
		</div>
		<div class="cwmp_form_method_shipping hide">
			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
			<?php wc_cart_totals_shipping_html(); ?>
			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
			<div class="cwmp_mobile hide" id="cwmp_step_4">
				<a href="" class="cwmp_button">
				<?php esc_attr_e( 'Continue', 'checkout-mestres-wp' ); ?>
				</a>
			</div>
		</div>
	</div>
	<?php endif; ?>
	<?php if(get_option('cwmp_activate_order_bump')=="S"){ ?>
			<?php
					global $wpdb;
					global $table_prefix;
					$array_cart = array();
					foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item){
						$array_cart[] = $cart_item['product_id'];
					}
					shuffle($array_cart);
					$get_product = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."cwmp_order_bump WHERE produto LIKE ".$array_cart[0]."");
					$i=1;
					foreach($get_product as $product_bump){
						foreach ( WC()->cart->get_cart() as $cart_item2 ) {
						if($i==1){
							$product2 = json_decode($cart_item2['data']);
								$get_product_bump = wc_get_product($product_bump->bump);
								foreach ( WC()->cart->get_cart() as $cart_item3 ) { $product3 = json_decode($cart_item3['data']); if($product3->id==$product_bump->bump){ $remove = "true"; } }
								?>
								<div class="cart-item bump">
									
									<div class="product-thumbnail"><a href="javascript:void(0)" id='<?php echo $get_product_bump->get_id(); ?>' class='cwmp_add_order_bump'><?php echo $get_product_bump->get_image(); ?></a></div>
									<div class="product-name">
										<div class="bump-chamada"><?php echo $product_bump->chamada; ?></div>
										<a href="javascript:void(0)" id='<?php echo $get_product_bump->get_id(); ?>' class='cwmp_add_order_bump'><h3><?php echo $get_product_bump->get_name(); ?></h3></a>
										<?php
										$discount = ( $get_product_bump->get_price() * $product_bump->valor ) / 100;
										echo "<span class='woocommerce-Price-amount amount'><s>de ".wp_strip_all_tags(wc_price($get_product_bump->get_price()))."</s> por ".wp_strip_all_tags(wc_price($get_product_bump->get_price()-$discount))."</span>";
										?>
									</div>
									<div class="bump-button">
									<?php echo "<a href='javascript:void(0)' id='".$get_product_bump->get_id()."' class='cwmp_add_order_bump'>".__( 'Add to Cart', 'checkout-mestres-wp' )."</a>"; ?>
									<?php echo "<a href='javascript:void(0)' class='cwmp_not_order_bump'>".__( 'No, Thanks', 'checkout-mestres-wp' )."</a>"; ?>
									
									</div>
								</div>
								<?php
						$i++;
						}
					}
					}
			}
			?>
			<div class="cwmp_woo_form_payment">
				<h2>
					<i class="fa <?php echo get_option('cwmp_checkout_box_icon_pagamento'); ?>"></i>
					<?php esc_attr_e( 'Payment', 'checkout-mestres-wp' ); ?>
				</h2>
				<p><?php esc_attr_e( 'Choose your payment method', 'checkout-mestres-wp' ); ?></p>
				<?php do_action( 'woocommerce_checkout_coupon' ); ?>
	<div class="section_payment hide">
	<?php if ( WC()->cart->needs_payment() ) : ?>
		<ul class="wc_payment_methods payment_methods methods">
			<?php
			if ( ! empty( $available_gateways ) ) {
				foreach ( $available_gateways as $gateway ) {
					wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
				}
			} else {
				echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '</li>'; // @codingStandardsIgnoreLine
			}
			?>
		</ul>
	<?php endif; ?>
	<div class="form-row place-order">
		<noscript>
			<?php
			printf( esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ), '<em>', '</em>' );
			?>
			<br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
		</noscript>
		<?php wc_get_template( 'checkout/terms.php' ); ?>
		<?php do_action( 'woocommerce_review_order_before_submit' ); ?>
		<?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt cwmp_button" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine ?>
		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>
		<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
	</div>
	</div>
</div>


</div>
<?php

if ( ! wp_doing_ajax() ) {
	do_action( 'woocommerce_review_order_after_payment' );
}
