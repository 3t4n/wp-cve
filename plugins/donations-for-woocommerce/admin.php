<?php
echo('
	<div class="wrap">
		<h2>'.esc_html__('Donations for WooCommerce', 'donations-for-woocommerce').'</h2>
		<h3>'.esc_html__('Usage Instructions', 'donations-for-woocommerce').'</h3>
		<p>'.esc_html__('Simply create a new WooCommerce product for each type of donation you would like to accept. Under Product Data, set the product type to "Donation". Optionally, set the default donation amount in the General section. You\'ll probably also want to ensure that product reviews are disabled in the Advanced section. That\'s all!', 'donations-for-woocommerce').'</p>
		<h3 style="margin-bottom: 0;">'.esc_html__('Settings', 'donations-for-woocommerce').'</h3>
		<form action="" method="post">');
		wp_nonce_field('hm_wcdon_settings');
		echo('<input type="hidden" name="save" value="1" />
			<table class="form-table" style="margin-bottom: 30px;">
				<tr valign="top">
					<th scope="row">
						<label>'.esc_html__('Checkout', 'donations-for-woocommerce').':</label>
					</th>
					<td>
						<label>
							<input type="checkbox" name="disable_cart_amount_field"'.(hm_wcdon_get_option('disable_cart_amount_field') ? ' checked="checked"' : '').' />
							'.esc_html__('Disable donation amount field in cart', 'donations-for-woocommerce').'
						</label>
					</td>
				</tr>				
				<tr valign="top">
					<th scope="row">
						<label>'.esc_html__('Taxes', 'donations-for-woocommerce').':</label>
					</th>
					<td>
						<label>
							<input type="checkbox" name="show_tax_donation_product"'.(hm_wcdon_get_option('show_tax_donation_product') ? ' checked="checked"' : '').' />
							'.esc_html__('Enable tax settings on donation products', 'donations-for-woocommerce').'
						</label>
					</td>
				</tr>			
				<tr valign="top">
					<th scope="row">
						<label>'.esc_html__('Shipping', 'donations-for-woocommerce').':</label>
					</th>
					<td>
						<label>
							<input type="checkbox" name="show_shipping_donation_product"'.(hm_wcdon_get_option('show_shipping_donation_product') ? ' checked="checked"' : '').' />
							'.esc_html__('Make product physical and enable shipping on donation products', 'donations-for-woocommerce').'
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th colspan="2">
						<button type="submit" class="button-primary">'.esc_html__('Save Settings', 'donations-for-woocommerce').'</button>
					</th>
				</tr>
			</table>
		</form>
');
$potent_slug = 'donations-for-woocommerce';
include(__DIR__.'/plugin-credit.php');
echo('
	</div>
');
?>