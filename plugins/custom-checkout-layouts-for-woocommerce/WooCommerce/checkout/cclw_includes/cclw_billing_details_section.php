<div id="customer_address_details">
      <h3 class="border_html"> <?php esc_html_e('Billing details', 'woocommerce' ); ?></h3>
	<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
			<?php do_action( 'woocommerce_checkout_billing' ); ?>
			<?php do_action( 'woocommerce_checkout_shipping' ); ?>
		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>	
			
	<?php endif; ?>
	
</div>
<?php 
	include_once CCLW_PLUGIN_DIR . 'WooCommerce/checkout/cclw_includes/cclw_additional_fields.php';
?>