<tr valign="top" id="acconts-row">
	<th scope="row" class="titledesc">
		<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?><?php echo $this->get_tooltip_html( $data ); // WPCS: XSS ok. ?></label>
	</th>
	<td class="forminp">
		<fieldset>
			<table class="form-table" id="wppay-accounts">
				<tbody>
				<tr valign="top">
					<td>
						<p><b><?php _e( 'Supported currencies', 'pay-wp' ); ?></b></p>
					</td>
				</tr>
				<tr valign="top">
					<td style="padding: 0 10px;">
						<div id="settings-accordion">
							<?php
							if ( isset( $profiles ) && ! empty( $profiles ) ) {
								foreach ( $profiles as $currency => $profile ) {
									//$is_default_profile = array_key_first($default) === $currency;
									$is_default_profile = false;
									$is_test_mode       = false;
									require 'wppay-settings-pos-item.php';
								}
							}
							?>
						</div>
					</td>
				</tr>
				<tr valign="top">
					<td>
						<select name="currency_selector" id="currency-selector">
							<?php
							echo '<option value="">' . __( 'Select currency', 'pay-wp' ) . '</option>';
							foreach ( \WPDesk\GatewayWPPay\WooCommerceGateway\StandardPaymentGateway::SUPPORTED_CURRENCIES as $currency ) {
								echo '<option value="' . esc_attr( $currency ) . '">' . esc_html( $currency ) . '</option>';
							}
							?>
						</select>
						<button class="button button-primary"
								id="add-new-wppay-pos"> <?php _e( 'Add a new currency', 'pay-wp' ); ?> </button>
					</td>
				</tr>
				<tr>
					<td>
						<p><?php _e( 'For each of the above currencies, enter the authentication data - ID and Hash key.', 'pay-wp' ); ?></p>

						<p><?php printf( __( 'You will receive your credentials by email after registration or you will find them in the %sadministration panel%s.', 'pay-wp' ), '<a href="https://wpde.sk/wppay-plugin-panel" target="_blank">', '</a>' ); ?></p>
					</td>
				</tr>
				<tr>
					<td>
						<p>
							<strong><?php printf( __( 'Don\'t have an Autopay account yet? %sGo to the registration form →%s', 'pay-wp' ), '<a href="' . \WPDesk\GatewayWPPay\Plugin::WPPAY_REGISTER_URL . '" target="_blank">', '</a>' ); ?></strong>
						</p>
					</td>
				</tr>
				</tbody>
			</table>
		</fieldset>
	</td>
</tr>
