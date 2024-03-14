<?php defined('ABSPATH') || exit; ?>

<div class="shopengine shopengine-widget">
    <div class="shopengine-checkout-shipping-methods">
        <table class="shopengine_woocommerce_shipping_methods">
			<?php

            // todo - for now need following line to prevent "....Call to undefined function wc_get_cart_item_data_hash()..." error in editor...
			wc()->frontend_includes();

			if(empty(WC()->cart->cart_contents)) {

				WC()->session = new \WC_Session_Handler();
				WC()->session->init();
				WC()->customer = new \WC_Customer(get_current_user_id(), true);
				WC()->cart     = new \WC_Cart();
			}

			WC()->cart->calculate_totals();

            $show_shipping_method = WC()->cart && WC()->cart->needs_shipping() && WC()->cart->show_shipping();


            if(!$show_shipping_method && $block->is_editor) {

                // todo - add some hardcoded html............
                esc_html_e('Some hardcoded html for editor only for checkout shipping method widget!.', 'shopengine-gutenberg-addon');
            }

			if($show_shipping_method) :
				?>
				<?php do_action('woocommerce_review_order_before_shipping'); ?>

				<?php wc_cart_totals_shipping_html(); ?>

				<?php do_action('woocommerce_review_order_after_shipping'); ?>

			<?php endif;?>
        </table>
    </div>
</div>
