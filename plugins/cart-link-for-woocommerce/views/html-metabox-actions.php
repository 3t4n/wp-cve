<?php
/**
 * @var array<int, string> $pages    .
 * @var Campaign           $campaign .
 */

use IC\Plugin\CartLinkWooCommerce\Campaign\Campaign;

?>
<div class="woocommerce ic-campaign-container">
	<p><?php esc_html_e( 'Define the actions which will be performed once the customer clicks the direct cart link.', 'cart-link-for-woocommerce' ); ?></p>

	<table class="form-table">
		<tbody>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( Campaign::META_CLEAR_CART ); ?>">
					<?php _e( 'Clear cart when visiting this url', 'cart-link-for-woocommerce' ); ?>
					<?php echo wp_kses_post( wc_help_tip( __( 'Tick this checkbox if the current cart content should be cleared and the products the customer added to the cart before using the direct cart link should be removed once it is clicked.', 'cart-link-for-woocommerce' ) ) ); ?>
				</label>
			</th>
			<td class="forminp">
				<label>
					<input
						type="checkbox"
						id="<?php echo esc_attr( Campaign::META_CLEAR_CART ); ?>"
						name="<?php echo esc_attr( Campaign::META_CLEAR_CART ); ?>"
						value="1"
						<?php checked( $campaign->clear_cart() ); ?>
					/>

					<?php _e( 'Clear the cart content', 'cart-link-for-woocommerce' ); ?>
				</label>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( Campaign::META_REDIRECT_TO ); ?>">
					<?php _e( 'Redirect to', 'cart-link-for-woocommerce' ); ?>
				</label>
			</th>
			<td class="forminp">
				<select required
						id="<?php echo esc_attr( Campaign::META_REDIRECT_TO ); ?>"
						name="<?php echo esc_attr( Campaign::META_REDIRECT_TO ); ?>"
						class="wc-page-search">
					<?php foreach ( $pages as $id => $name ): ?>
						<option
							value="<?php echo esc_attr( $id ); ?>"
							<?php selected( $id, $campaign->get_redirect_page_id() ); ?>
						><?php echo esc_html( $name ); ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		</tbody>
	</table>
</div>
