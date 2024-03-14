<?php

/**
 * Payment Receipt.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */
?>

<div class="acadp acadp-payment-receipt">
	<div class="acadp-wrapper acadp-flex acadp-flex-col acadp-gap-6">
		<div class="acadp-alert acadp-alert-info" role="alert">
			<div class="acadp-flex acadp-gap-4 acadp-items-center">
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20px" height="20px" stroke-width="1.5" stroke="currentColor" class="acadp-flex-shrink-0">
					<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
				</svg>
				<?php esc_html_e( 'Thank you for your order!', 'advanced-classifieds-and-directory-pro' ); ?>
			</div>
		</div>
		
		<?php
		if ( isset( $post_meta['payment_gateway'] ) && 'offline' == $post_meta['payment_gateway'][0] && 'created' == $post_meta['payment_status'][0] ) {
			$settings = get_option('acadp_gateway_offline_settings');
			?>
			<div class="acadp-alert acadp-alert-info" role="alert">
				<div class="acadp-flex acadp-gap-4">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20px" height="20px" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="acadp-flex-shrink-0">
						<circle cx="12" cy="12" r="10"/>
						<path d="M12 16v-4"/>
						<path d="M12 8h.01"/>
					</svg>
					<div class="acadp-offline-payment-instructions">
						<?php echo nl2br( $settings['instructions'] ); ?>
					</div>
				</div>
			</div>
			<?php
		}
		?>
		
		<div class="acadp-order-details acadp-grid acadp-grid-cols-1 acadp-gap-4 md:acadp-grid-cols-2">
			<!-- Order ID -->
			<div class="acadp-order-id">
				<dt class="acadp-m-0 acadp-p-0 acadp-font-bold">
					<?php esc_html_e( 'ORDER', 'advanced-classifieds-and-directory-pro' ); ?> #
				</dt>

				<dd class="acadp-m-0 acadp-p-0">
					<?php echo esc_html( $order->ID ); ?>
				</dd>
			</div>

			<!-- Total Amount -->
			<div class="acadp-total-amount">
				<dt class="acadp-m-0 acadp-p-0 acadp-font-bold">
					<?php esc_html_e( 'Total Amount', 'advanced-classifieds-and-directory-pro' ); ?>
				</dt>

				<dd class="acadp-m-0 acadp-p-0">
					<?php
					if ( isset( $post_meta['amount'] ) ) {
						$amount = acadp_format_payment_amount( $post_meta['amount'][0] );
						$amount = acadp_payment_currency_filter( $amount );
						
						echo esc_html( $amount );
					}
					?>
				</dd>
			</div>

			<!-- Date -->
			<div class="acadp-date">
				<dt class="acadp-m-0 acadp-p-0 acadp-font-bold">
					<?php esc_html_e( 'Date', 'advanced-classifieds-and-directory-pro' ); ?>
				</dt>

				<dd class="acadp-m-0 acadp-p-0">
					<?php
					$date = strtotime( $order->post_date );
					echo date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $date );
					?>
				</dd>
			</div>

			<!-- Payment Method -->
			<div class="acadp-payment-method">
				<dt class="acadp-m-0 acadp-p-0 acadp-font-bold">
					<?php esc_html_e( 'Payment Method', 'advanced-classifieds-and-directory-pro' ); ?>
				</dt>

				<dd class="acadp-m-0 acadp-p-0">
					<?php 
					$gateway = esc_html( $post_meta['payment_gateway'][0] );
					if ( 'free' == $gateway ) {
						esc_html_e( 'Free Submission', 'advanced-classifieds-and-directory-pro' );
					} else {
						$gateway_settings = get_option( 'acadp_gateway_' . $gateway . '_settings' );				
						echo ! empty( $gateway_settings['label'] ) ? esc_html( $gateway_settings['label'] ) : $gateway;
					}
					?>
				</dd>
			</div>

			<!-- Payment Status -->
			<div class="acadp-payment-status">
				<dt class="acadp-m-0 acadp-p-0 acadp-font-bold">
					<?php esc_html_e( 'Payment Status', 'advanced-classifieds-and-directory-pro' ); ?>
				</dt>

				<dd class="acadp-m-0 acadp-p-0">
					<?php 
					$status = isset( $post_meta['payment_status'] ) ? $post_meta['payment_status'][0] : 'created';
					echo esc_html( acadp_get_payment_status_i18n( $status ) );
					?>
				</dd>
			</div>

			<!-- Transaction ID-->
			<div class="acadp-transaction-id">
				<dt class="acadp-m-0 acadp-p-0 acadp-font-bold">
					<?php esc_html_e( 'Transaction ID', 'advanced-classifieds-and-directory-pro' ); ?>
				</dt>

				<dd class="acadp-m-0 acadp-p-0">
					<?php echo isset( $post_meta['transaction_id'] ) ? esc_html( $post_meta['transaction_id'][0] ) : ''; ?>
				</dd>
			</div>
		</div>		

		<div class="acadp-order-items acadp-flex acadp-flex-col acadp-gap-4">
			<div class="acadp-text-xl acadp-font-bold">
				<?php esc_html_e( 'Order Items', 'advanced-classifieds-and-directory-pro' ); ?>
			</div>

			<table class="acadp-table acadp-m-0 acadp-border-0 acadp-w-full">
				<tr class="acadp-border-0">
					<th class="acadp-border acadp-p-3">
						<?php esc_html_e( 'Item Name', 'advanced-classifieds-and-directory-pro' ); ?>
					</th>
					<th class="acadp-border acadp-p-3 acadp-text-right">
						<?php esc_html_e( 'Price', 'advanced-classifieds-and-directory-pro' ); ?>
					</th>
				</tr>
				<?php foreach ( $order_details as $order_detail ) : ?>
					<tr class="acadp-border-0">
						<td class="acadp-border acadp-p-3">
							<div class="acadp-text-lg acadp-font-bold">
								<?php echo esc_html( $order_detail['label'] ); ?>
							</div>

							<?php 
							if ( isset( $order_detail['description'] ) ) {
								echo esc_html( $order_detail['description'] );
							} 
							?>
						</td>
						<td class="acadp-border acadp-p-3 acadp-text-right">
							<?php echo esc_html( acadp_format_payment_amount( $order_detail['price'] ) ); ?>
						</td>
					</tr>
				<?php endforeach; ?>
				<tr class="acadp-border-0">
					<td class="acadp-border acadp-p-3 acadp-align-middle acadp-text-right acadp-font-bold">
						<?php printf( esc_html__( 'Total amount [%s]', 'advanced-classifieds-and-directory-pro' ), acadp_get_payment_currency() ); ?>
					</td>
					<td class="acadp-border acadp-p-3 acadp-text-right">
						<?php echo esc_html( acadp_format_payment_amount( $post_meta['amount'][0] ) ); ?>
					</td>
				</tr>
			</table>
		</div>
		
		<div class="acadp-text-right">
			<a href="<?php echo esc_url( acadp_get_manage_listings_page_link() ); ?>" class="acadp-button acadp-button-primary">
				<?php esc_html_e( 'View all my listings', 'advanced-classifieds-and-directory-pro' ); ?>
			</a>
		</div>
	</div>
</div>