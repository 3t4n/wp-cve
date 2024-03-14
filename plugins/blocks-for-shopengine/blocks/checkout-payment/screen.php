<?php

defined('ABSPATH') || exit;

global $wp;

if(isset($wp->query_vars['order-pay'])) {

	return;
}

if($block->is_editor) {
	wc()->frontend_includes();
	WC()->session = new WC_Session_Handler();
	WC()->session->init();
	WC()->customer = new WC_Customer(get_current_user_id(), true);
	WC()->cart     = new WC_Cart();
}

?>
<div class="shopengine shopengine-widget">
    <div class="shopengine-checkout-payment">
		<?php

		if(!empty(WC()->cart) && !WC()->cart->is_empty()) {

			woocommerce_checkout_payment();

		} elseif($block->is_editor) {
			esc_html_e('Your cart is empty, please add some simple product in cart and then come back to editor to see checkout page.', 'shopengine-gutenberg-addon');
		}

		?>
    </div>
</div>
