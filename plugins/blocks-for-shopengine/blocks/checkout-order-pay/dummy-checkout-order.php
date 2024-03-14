<?php defined('ABSPATH') || exit; ?>
<div class='shopengine-checkout-order-pay'>
	<table class="shop_table">
		<thead>
			<tr>
				<th class="product-name"> <?php esc_html_e('Product', 'shopengine-gutenberg-addon'); ?> </th>
				<th class="product-quantity"> <?php esc_html_e('Qty', 'shopengine-gutenberg-addon'); ?> </th>
				<th class="product-total"> <?php  esc_html_e('Totals', 'shopengine-gutenberg-addon'); ?> </th>
			</tr>
		</thead>
		<tbody>
			<tr class="order_item">
				<td class="product-name"> <?php  esc_html_e(' T-Shirt with Logo', 'shopengine-gutenberg-addon'); ?>
					<ul class="wc-item-meta">
						<li>
							<strong class="wc-item-meta-label">
								<span class="shopengine-partial-payment-product-badge"> <?php  esc_html_e('Partial Payment', 'shopengine-gutenberg-addon'); ?> </span>:
							</strong>
							<p> <?php  esc_html_e('yes', 'shopengine-gutenberg-addon'); ?> </p>
						</li>
						<li><strong class="wc-item-meta-label"> <?php  esc_html_e(' Amount:', 'shopengine-gutenberg-addon'); ?> </strong><?php esc_html_e('60', 'shopengine-gutenberg-addon'); ?>%</li>
					</ul>
				</td>
				<td class="product-quantity"> <strong class="product-quantity">×&nbsp;<?php esc_html_e('1', 'shopengine-gutenberg-addon') ;?></strong></td>
				<td class="product-subtotal"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span><?php esc_html_e('18.00', 'shopengine-gutenberg-addon'); ?></bdi></span></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th scope="row" colspan="2"> <?php esc_html_e('Subtotal:', 'shopengine-gutenberg-addon'); ?> </th>
				<td class="product-total"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span><?php esc_html_e('18.00', 'shopengine-gutenberg-addon'); ?></bdi></span></td>
			</tr>
			<tr>
				<th scope="row" colspan="2"> <?php esc_html_e('Total:', 'shopengine-gutenberg-addon') ?> </th>
				<td class="product-total"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span><?php esc_html_e('18.00', 'shopengine-gutenberg-addon') ;?></bdi></span></td>
			</tr>
			<tr>
				<th scope="row" colspan="2"> <?php esc_html_e('First Installment', 'shopengine-gutenberg-addon'); ?> </th>
				<td class="product-total"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span><?php esc_html_e('10.80', 'shopengine-gutenberg-addon'); ?></bdi></span></td>
			</tr>
			<tr>
				<th scope="row" colspan="2"> <?php esc_html_e('Second Installment', 'shopengine-gutenberg-addon'); ?> </th>
				<td class="product-total"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span><?php esc_html_e('7.20', 'shopengine-gutenberg-addon'); ?></bdi></span></td>
			</tr>
			<tr>
				<th scope="row" colspan="2"> <?php esc_html_e('Due:', 'shopengine-gutenberg-addon'); ?> </th>
				<td class="product-total"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span><?php esc_html_e('18.00', 'shopengine-gutenberg-addon'); ?></bdi></span></td>
			</tr>
		</tfoot>
	</table>
	<div id="payment">
		<ul class="wc_payment_methods payment_methods methods">
			<li class="wc_payment_method payment_method_bacs">
				<input id="payment_method_bacs" type="radio" class="input-radio" name="payment_method" value="bacs" checked="checked" data-order_button_text="">

				<label for="payment_method_bacs"> <?php esc_html_e(' Direct bank transfer', 'shopengine-gutenberg-addon'); ?> </label>
				<div class="payment_box payment_method_bacs" style="">
					<p> <?php esc_html_e(' Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.', 'shopengine-gutenberg-addon'); ?> </p>
				</div>
			</li>
			<li class="wc_payment_method payment_method_cheque">
				<input id="payment_method_cheque" type="radio" class="input-radio" name="payment_method" value="cheque" data-order_button_text="">

				<label for="payment_method_cheque"> <?php esc_html_e('Check payments', 'shopengine-gutenberg-addon'); ?> </label>
				<div class="payment_box payment_method_cheque" style="display: none;">
					<p> <?php esc_html_e('Please send a check to Store Name, Store Street, Store Town, Store State / County, Store Postcode.', 'shopengine-gutenberg-addon'); ?> </p>
				</div>
			</li>
			<li class="wc_payment_method payment_method_cod">
				<input id="payment_method_cod" type="radio" class="input-radio" name="payment_method" value="cod" data-order_button_text="">

				<label for="payment_method_cod"> <?php esc_html_e('Cash on delivery', 'shopengine-gutenberg-addon'); ?> </label>
				<div class="payment_box payment_method_cod" style="display: none;">
					<p> <?php esc_html_e('Pay with cash upon delivery.', 'shopengine-gutenberg-addon'); ?> </p>
				</div>
			</li>
		</ul>
		<div class="form-row">
			<input type="hidden" name="woocommerce_pay" value="1">
			<div class="woocommerce-terms-and-conditions-wrapper">
				<div class="woocommerce-privacy-policy-text">
					<p> <?php esc_html_e('Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our', 'shopengine-gutenberg-addon') ?> <a href="https://shopengine.test/?page_id=3" class="woocommerce-privacy-policy-link" target="_blank"><?php esc_html_e('privacy policy', 'shopengine-gutenberg-addon'); ?></a>.</p>
				</div>
			</div>
			<button type="submit" class="button alt wp-element-button" id="place_order" value="<?php esc_attr_e('Pay for order', 'shopengine-gutenberg-addon'); ?>" data-value="<?php esc_attr_e('Pay for order', 'shopengine-gutenberg-addon'); ?>"> <?php esc_html_e(' Pay for order', 'shopengine-gutenberg-addon'); ?> </button>
			<input type="hidden" id="woocommerce-pay-nonce" name="woocommerce-pay-nonce" value="624549f9b6"><input type="hidden" name="_wp_http_referer" value="/checkout/order-pay/61/?pay_for_order=true&amp;key=wc_order_W5GJKRTlwYypU">
		</div>
	</div>
</div>