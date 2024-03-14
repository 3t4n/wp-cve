<?php
defined('ABSPATH') || exit;

if(is_checkout()) {
	return;
}

$editor_mode = $block->is_editor;


if($editor_mode) {
	wc()->frontend_includes();

	\Wpmet\Gutenova\Helper::add_product_in_cart_if_no_cart_found();

	WC()->cart->calculate_totals();
}


if(!empty(WC()->cart->cart_contents)) : ?>


    <div class="shopengine shopengine-widget">
        <div class="shopengine-cart-totals">

            <div class="cart_totals <?php echo (WC()->customer->has_calculated_shipping()) ? 'calculated_shipping' : ''; ?>">

				<?php
				// do_action('woocommerce_before_cart_totals'); cause issue with flatsome theme / add extra markup at the top of the main table
				?>

                <table cellspacing="0" class="shop_table shop_table_responsive">

                    <tr class="cart-subtotal">
                        <th><?php esc_html_e('Subtotal', 'shopengine-gutenberg-addon'); ?></th>
                        <td data-title="<?php esc_attr_e('Subtotal', 'shopengine-gutenberg-addon'); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
                    </tr>

					<?php foreach(WC()->cart->get_coupons() as $code => $coupon) : ?>
                        <tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
                            <th><?php wc_cart_totals_coupon_label($coupon); ?></th>
                            <td data-title="<?php echo esc_attr(wc_cart_totals_coupon_label($coupon, false)); ?>"><?php wc_cart_totals_coupon_html($coupon); ?></td>
                        </tr>
					<?php endforeach; ?>

					<?php

					if(WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>

						<?php do_action('woocommerce_cart_totals_before_shipping'); ?>

						<?php
						// Wc_price overridden due to currency symbol position issue
						add_filter('wc_price', function ($return, $price, $args) {
							$negative        = $price < 0;
							$formatted_price = ($negative ? '-' : '') . sprintf($args['price_format'], '<format class="woocommerce-Price-currencySymbol">' . get_woocommerce_currency_symbol($args['currency']) . '</format>', $price);
							$return          = '<span class="woocommerce-Price-amount amount"><bdi>' . $formatted_price . '</bdi></span>';

							if($args['ex_tax_label'] && wc_tax_enabled()) {
								$return .= ' <small class="woocommerce-Price-taxLabel tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
							}

							return $return;
						},         10, 3);
						?>

						<?php wc_cart_totals_shipping_html(); ?>

						<?php do_action('woocommerce_cart_totals_after_shipping'); ?>

					<?php elseif(WC()->cart->needs_shipping() && 'yes' === get_option('woocommerce_enable_shipping_calc')) : ?>

                        <tr class="shipping">
                            <th><?php esc_html_e('Shipping', 'shopengine-gutenberg-addon'); ?></th>
                            <td data-title="<?php esc_attr_e('Shipping', 'shopengine-gutenberg-addon'); ?>"><?php woocommerce_shipping_calculator(); ?></td>
                        </tr>

					<?php endif; ?>

					<?php foreach(WC()->cart->get_fees() as $fee) : ?>
                        <tr class="fee">
                            <th><?php echo esc_html($fee->name); ?></th>
                            <td data-title="<?php echo esc_attr($fee->name); ?>"><?php wc_cart_totals_fee_html($fee); ?></td>
                        </tr>
					<?php endforeach; ?>

					<?php
					if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) {
						$taxable_address = WC()->customer->get_taxable_address();
						$estimated_text  = '';

						if (WC()->customer->is_customer_outside_base() && !WC()->customer->has_calculated_shipping()) {
							/* translators: %s location. */
							$estimated_text = sprintf(
								' <small>%s</small>',
								/* translators: %s location. */
								esc_html(sprintf(__('(estimated for %s)', 'shopengine-gutenberg-addon'), WC()->countries->estimated_for_prefix($taxable_address[0]) . WC()->countries->countries[$taxable_address[0]]))
							);
						}

						if ('itemized' === get_option('woocommerce_tax_total_display')) {
							foreach (WC()->cart->get_tax_totals() as $code => $tax) {
								?>
								<tr class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
									<th><?php Shopengine_Gutenberg_Addon\Utils\Helper::raw_render(esc_html($tax->label) . $estimated_text); ?></th>
									<td data-title="<?php echo esc_attr($tax->label); ?>"><?php echo wp_kses_post($tax->formatted_amount); ?></td>
								</tr>
								<?php
							}
						} else { ?>
							<tr class="tax-total">
								<th><?php Shopengine_Gutenberg_Addon\Utils\Helper::raw_render(esc_html(WC()->countries->tax_or_vat()) . $estimated_text); ?></th>
								<td data-title="<?php echo esc_attr(WC()->countries->tax_or_vat()); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
							</tr>
							<?php
						}
					}
					?>

					<?php do_action('woocommerce_cart_totals_before_order_total'); ?>

                    <tr class="order-total">
                        <th><?php esc_html_e('Total', 'shopengine-gutenberg-addon'); ?></th>
                        <td data-title="<?php esc_attr_e('Total', 'shopengine-gutenberg-addon'); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
                    </tr>

					<?php do_action('woocommerce_cart_totals_after_order_total'); ?>

                </table>

                <div class="wc-proceed-to-checkout">
					<?php do_action('woocommerce_proceed_to_checkout'); ?>
                </div>

				<?php do_action('woocommerce_after_cart_totals'); ?>

            </div>

        </div>
    </div>

<?php endif; ?>
