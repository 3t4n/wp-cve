<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<script type="text/template" id="tmpl-wc-szamlazz-modal-grouped-generate">
	<div class="wc-backbone-modal wc-szamlazz-modal-grouped-generate">
		<div class="wc-backbone-modal-content">
			<section class="wc-backbone-modal-main" role="main">
				<header class="wc-backbone-modal-header">
					<h1><?php echo esc_html_e('Create a combined invoice', 'wc-szamlazz'); ?></h1>
					<button class="modal-close modal-close-link dashicons dashicons-no-alt">
						<span class="screen-reader-text"><?php esc_html_e( 'Close modal panel', 'woocommerce' ); ?></span>
					</button>
				</header>
				<# if ( data.orderIds ) { #>
					<article>
						<div class="wc-szamlazz-metabox-messages wc-szamlazz-metabox-messages-success wc-szamlazz-modal-grouped-generate-results" style="display:none;">
							<div class="wc-szamlazz-metabox-messages-content">
								<ul></ul>
							</div>
						</div>
						<div class="wc-szamlazz-modal-grouped-generate-download">
							<a href="#" class="wc-szamlazz-modal-grouped-generate-download-invoice"><span><?php esc_html_e('Invoice', 'wc-szamlazz'); ?></span> <strong></strong></a>
							<a href="#" class="wc-szamlazz-modal-grouped-generate-download-order"><?php esc_html_e('Go to the order', 'wc-szamlazz'); ?></a>
						</div>
						<div class="wc-szamlazz-modal-grouped-generate-form">
							<p><?php esc_html_e('By combining the items in the orders below, you can create a single invoice. Select the order that will be the basis of the combined invoice: it will use the shipping and billing address of this order when creating the invoice.', 'wc-szamlazz'); ?></p>
							{{{ data.orders }}}
							<p>
								<?php esc_html_e('The combined invoice will be displayed on the selected order.', 'wc-szamlazz'); ?>
								<?php if(WC_Szamlazz()->get_option('grouped_invoice_status', 'no') != 'no'): ?>
									<br><?php esc_html_e('The status of the orders above will change to this after the invoice is created:', 'wc-szamlazz'); ?> <strong><?php echo wc_get_order_status_name(WC_Szamlazz()->get_option('grouped_invoice_status')); ?></strong>
								<?php endif; ?>
							</p>
						</div>
					</article>
					<footer>
						<div class="inner">
							<a class="button button-primary button-large" href="#" id="generate_grouped_invoice" data-orders="{{{ data.orderIds }}}" data-nonce="<?php echo wp_create_nonce( "wc_szamlazz_generate_grouped_invoice" ); ?>"><?php esc_html_e( 'Create combined invoice', 'wc-szamlazz' ); ?></a>
						</div>
					</footer>
				<# } else { #>
					<article>
						<p><?php esc_html_e('You need to select at least two orders.', 'wc-szamlazz'); ?></p>
					</article>
				<# } #>
			</section>
		</div>
	</div>
	<div class="wc-backbone-modal-backdrop modal-close"></div>
</script>
