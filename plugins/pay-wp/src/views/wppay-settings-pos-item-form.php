            <table class="form-table wppay-api">
                <tbody>
				<tr valign="top" class="pos-settings" data-env="production">
					<th class="titledesc" scope="row">
						<label><?php _e( 'Payment point ID', 'pay-wp' ); ?></label>
					</th>
					<td class="forminp forminp-text">
						<?php
						$key = \WPDesk\GatewayWPPay\WooCommerceGateway\StandardPaymentGateway::ID;
						$params = array(
								'type'  => 'text',
								'label' => '',
								'description'   => __( 'Enter the Autopay ID obtained when creating the payment point.', 'pay-wp' ),
						);

						woocommerce_form_field(
							    $field_key.'['.$currency.']'.'['.$key.']',
								$params,
								$profile[$key] ?? ''
						);
						?>
					</td>
				</tr>
				<tr valign="top" class="pos-settings" data-api="classic" data-env="production">
					<th class="titledesc" scope="row">
						<label><?php _e('HASH key', 'pay-wp'); ?></label>
					</th>

					<td class="forminp forminp-text">
						<?php
						$key = \WPDesk\GatewayWPPay\WooCommerceGateway\StandardPaymentGateway::SETTINGS_FIELD_HASH;
						$params = array(
								'type'  => 'text',
								'label' => '',
								'description'   => __( 'Enter the HASH key system obtained when creating a payment point.','pay-wp' ),
						);
						woocommerce_form_field(
							$field_key.'['.$currency.']'.'['.$key.']',
							$params,
							$profile[$key] ?? ''
						);
						?>
					</td>
				</tr>


                </tbody>
            </table>
