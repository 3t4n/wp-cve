<?php

/**
 * Checkout Page.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */
?>

<div class="acadp acadp-checkout acadp-require-js" data-script="checkout">		
	<form id="acadp-checkout-form" class="acadp-flex acadp-flex-col acadp-gap-6" method="post" role="form">
		<?php
		// Status messages
		include apply_filters( 'acadp_load_template', ACADP_PLUGIN_DIR . 'public/templates/status-messages.php' );
		?>

		<div class="acadp-alert acadp-alert-info" role="alert">
			<div class="acadp-flex acadp-gap-4 acadp-items-center">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20px" height="20px" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="acadp-flex-shrink-0">
					<circle cx="12" cy="12" r="10"/>
					<path d="M12 16v-4"/>
					<path d="M12 8h.01"/>
				</svg>
				<?php esc_html_e( 'Please review your order, and click Purchase once you are ready to proceed.', 'advanced-classifieds-and-directory-pro' ); ?>
			</div>
		</div>

		<table id="acadp-checkout-items" class="acadp-table acadp-m-0 acadp-border-0 acadp-w-full">
			<?php foreach ( $options as $option ) : ?>            	
				<?php if ( 'header' == $option['type'] ) : ?>                
					<tr class="acadp-border-0">
						<td colspan="3" class="acadp-border acadp-p-3">
							<div class="acadp-text-lg acadp-font-bold">
								<?php echo esc_html( $option['label'] ); ?>
							</div>

							<?php 
							if ( isset( $option['description'] ) ) {
								echo esc_html( $option['description'] );
							}
							?>
						</td>
					</tr>                
				<?php else : ?>
					<tr class="acadp-border-0">
						<td class="acadp-border acadp-p-3 acadp-text-center">
							<?php
							switch ( $option['type'] ) {
								case 'checkbox' :
									$checked = isset( $option['selected'] ) && 1 == $option['selected'] ? ' checked' : '';

									printf( 
										'<input type="checkbox" name="%s[]" value="%s" class="acadp-form-control acadp-form-control-amount acadp-form-checkbox" data-price="%s" %s/>', 
										esc_attr( $option['name'] ), 
										esc_attr( $option['value'] ), 
										esc_attr( $option['price'] ), 
										$checked 
									);
									break;
								case 'radio' :
									$checked = isset( $option['selected'] ) && 1 == $option['selected'] ? ' checked' : '';

									printf( 
										'<input type="radio" name="%s" value="%s" class="acadp-form-control acadp-form-control-amount acadp-form-radio" data-price="%s" %s/>', 
										esc_attr( $option['name'] ), 
										esc_attr( $option['value'] ), 
										esc_attr( $option['price'] ), 
										$checked 
									);
									break;
							}                    		
							?>
						</td>
						<td class="acadp-border acadp-p-3">
							<?php if ( isset( $option['label'] ) ) : ?>
								<div class="acadp-text-lg acadp-font-bold">
									<?php echo esc_html( $option['label'] ); ?>
								</div>
							<?php endif; ?>

							<?php 
							if ( isset( $option['description'] ) ) {
								echo esc_html( $option['description'] );
							}
							?>
						</td>
						<td class="acadp-border acadp-p-3 acadp-text-right">
							<?php echo esc_html( acadp_format_payment_amount(  $option['price'] ) ); ?> 
						</td>
					</tr>
				<?php endif; ?>           	
			<?php endforeach; ?>    		
			<tr class="acadp-border-0">
				<td colspan="2" class="acadp-border acadp-p-3 acadp-align-middle acadp-text-right acadp-font-bold">
					<?php 
					printf( 
						esc_html__( 'Payable amount [%s]', 'advanced-classifieds-and-directory-pro' ), 
						acadp_get_payment_currency() 
					); 
					?>
				</td>
				<td class="acadp-border acadp-p-3 acadp-align-middle acadp-text-right">
					<div id="acadp-checkout-total-amount"></div>
				</td>
			</tr>
		</table>
		
		<div id="acadp-checkout-payment-gateways" class="acadp-panel">
			<div class="acadp-panel-header">
				<?php esc_html_e( 'Choose payment method', 'advanced-classifieds-and-directory-pro' ); ?>
			</div>
			
			<div class="acadp-panel-body acadp-flex acadp-flex-col acadp-gap-2">
				<?php
				$gateways = acadp_get_payment_gateways();	
				$settings = get_option( 'acadp_gateway_settings' );	
				
				$list = array();
				
				if ( isset( $settings['gateways'] ) ) {	
					foreach ( $gateways as $key => $label ) {			
						if ( in_array( $key, $settings['gateways'] ) ) {			
							$gateway_settings = get_option( 'acadp_gateway_' . $key . '_settings' );
							$label = ! empty( $gateway_settings['label'] ) ? $gateway_settings['label'] : $label;
								
							$html = '<div class="acadp-gateway">';

							$html .= sprintf( 
								'<label class="acadp-flex acadp-gap-1.5 acadp-items-center"><input type="radio" name="payment_gateway" class="acadp-form-control acadp-form-radio" value="%s"%s>%s</label>', 
								$key, 
								( $key == end( $settings['gateways'] ) ? ' checked' : '' ), 
								esc_html( $label )
							);
							
							if ( ! empty( $gateway_settings['description'] ) ) {
								$html .= sprintf(
									'<div class="acadp-text-muted acadp-text-sm">%s</div>',
									esc_html( $gateway_settings['description'] )
								);
							}
							
							$html .= '</div>';
							
							echo $html;
						}			
					}		
				}
				?>
			</div>
		</div>
		
		<div id="acadp-checkout-card-details" hidden></div>
		
		<div id="acadp-checkout-errors" hidden></div>			

		<div class="acadp-button-group acadp-flex acadp-items-center acadp-justify-end acadp-gap-2">
			<?php wp_nonce_field( 'acadp_process_payment', 'acadp_checkout_nonce' ); ?>
			<input type="hidden" name="post_id" value="<?php echo esc_attr( $post_id ); ?>" />

			<a href="<?php echo esc_url( acadp_get_manage_listings_page_link() ); ?>" class="acadp-button acadp-button-secondary">
				<?php esc_html_e( 'Not now', 'advanced-classifieds-and-directory-pro' ); ?>
			</a>

			<input type="submit" class="acadp-button acadp-button-primary acadp-button-submit" value="<?php esc_attr_e( 'Proceed to payment', 'advanced-classifieds-and-directory-pro' ); ?>" />
		</div>
	</form>
</div>