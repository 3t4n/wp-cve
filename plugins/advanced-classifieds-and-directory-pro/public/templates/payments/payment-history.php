<?php

/**
 * Payment History.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */
?>

<div class="acadp acadp-payment-history">    
	<table>
		<thead>
			<tr>
				<th>
					<?php esc_html_e( 'Order ID', 'advanced-classifieds-and-directory-pro' ); ?>
				</th>
				<th>
					<?php esc_html_e( 'Details', 'advanced-classifieds-and-directory-pro' ); ?>
				</th>
				<th>
					<?php esc_html_e( 'Amount', 'advanced-classifieds-and-directory-pro' ); ?>
				</th>
				<th>
					<?php esc_html_e( 'Type', 'advanced-classifieds-and-directory-pro' ); ?>
				</th>
				<th>
					<?php esc_html_e( 'Transaction ID', 'advanced-classifieds-and-directory-pro' ); ?>
				</th>
				<th>
					<?php esc_html_e( 'Date', 'advanced-classifieds-and-directory-pro' ); ?>
				</th>
				<th>
					<?php esc_html_e( 'Status', 'advanced-classifieds-and-directory-pro' ); ?>
				</th>
			</tr>
		</thead>
	
		<!-- The loop -->
		<?php 
		if ( $acadp_query->have_posts() ) :
			while ( $acadp_query->have_posts() ) : 
				$acadp_query->the_post(); 
				$post_meta = get_post_meta( $post->ID ); 
				?>
				<tr>
					<td>
						<?php 
						printf( 
							'<a href="%s" target="_blank">%d</a>', 
							esc_url( acadp_get_payment_receipt_page_link( $post->ID ) ), 
							$post->ID 
						); 
						?>
					</td>
					<td>
						<?php
						$listing_id = (int) $post_meta['listing_id'][0];
						if ( ! empty( $listing_id ) ) {
							printf( 
								'<a href="%s" class="acadp-underline acadp-font-medium">%s:%d</a>', 
								esc_url( get_permalink( $listing_id ) ), 
								esc_html( get_the_title( $listing_id ) ), 
								$listing_id 
							);
						}

						$order_details = apply_filters( 'acadp_order_details', array(), $post->ID );
						if ( ! empty( $order_details ) ) {
							$icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="12px" height="12px" fill="currentColor" class="acadp-flex-shrink-0">
								<path fill-rule="evenodd" d="M10.21 14.77a.75.75 0 01.02-1.06L14.168 10 10.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
								<path fill-rule="evenodd" d="M4.21 14.77a.75.75 0 01.02-1.06L8.168 10 4.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
							</svg>';

							echo '<ul class="acadp-m-0 acadp-p-0 acadp-list-none">';

							foreach ( $order_details as $order_detail ) {
								echo sprintf(
									'<li class="acadp-m-0 acadp-p-0 acadp-list-none"><div class="acadp-flex acadp-gap-1 acadp-items-center">%s %s</div></li>',
									$icon,
									esc_html( $order_detail['label'] )
								);
							}
						
							if ( isset( $post_meta['featured'] ) ) {
								$featured_listing_settings = get_option( 'acadp_featured_listing_settings' );
								echo sprintf( 
									'<li class="acadp-m-0 acadp-p-0 acadp-list-none"><div class="acadp-flex acadp-gap-1.5 acadp-items-center">%s %s</div></li>',
									$icon,
									esc_html( $featured_listing_settings['label'] )
								);
							}

							echo '</ul>';
						}
						?>
					</td>
					<td>
						<?php
						$amount = acadp_format_payment_amount( $post_meta['amount'][0] );					
						$value = acadp_payment_currency_filter( $amount );

						echo esc_html( $value );
						?>
					</td>
					<td>
						<?php
						$gateway = esc_html( $post_meta['payment_gateway'][0] );

						if ( 'free' == $gateway ) {
							esc_html_e( 'Free Submission', 'advanced-classifieds-and-directory-pro' );
						} else {
							$gateway_settings = get_option( 'acadp_gateway_' . $gateway . '_settings' );				
							if ( ! empty( $gateway_settings['label'] ) ) {
								echo esc_html( $gateway_settings['label'] );
							} else {
								echo esc_html( $gateway );
							}
						}
						?>	
					</td>
					<td>
						<?php 
						if ( isset( $post_meta['transaction_id'] ) ) {
							echo esc_html( $post_meta['transaction_id'][0] );
						} else {
							echo 'N/A';
						}
						?>
					</td>
					<td>
						<?php
						$date = strtotime( $post->post_date );
						echo date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $date );
						?>
					</td>
					<td>
						<?php echo esc_html( acadp_get_payment_status_i18n( $post_meta['payment_status'][0] ) ); ?>
					</td>
				</tr>
			<?php 
			endwhile;
		endif; 
		?>
	</table>
    
	<?php 
	// Share buttons
	include apply_filters( 'acadp_load_template', ACADP_PLUGIN_DIR . 'public/templates/share-buttons.php' ); 
	?>
</div>