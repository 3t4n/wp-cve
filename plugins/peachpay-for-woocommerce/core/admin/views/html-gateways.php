<?php
/**
 * PeachPay gateway table.
 *
 * @var array $gateway_list A list of gateway instances to render.
 *
 * @package PeachPay
 */

defined( 'PEACHPAY_ABSPATH' ) || exit;

foreach ( $gateway_list as $gateway ) : ?>
	<div class="gateway">
		<div class="info flex-col gap-12 w-100">
			<div class="list-item-heading gap-12">
				<?php
				// PHPCS:ignore
				echo $gateway->get_icon();
				?>
				<h4><?php echo esc_html( $gateway->title ); ?></h4>
				<div class="general-status <?php echo esc_attr( ( 'yes' === $gateway->enabled && ! $gateway->needs_setup() ) ? 'active' : 'inactive' ); ?> flex-row gap-8">
					<div class="active">Active</div>
					<div class="inactive">Inactive</div>
				</div>
			</div>
			<div class="location-status <?php echo esc_attr( ( 'yes' === $gateway->enabled && ! $gateway->needs_setup() ) ? '' : 'hide' ); ?>">
				<div class="location <?php echo 'checkout_page_only' !== $gateway->get_option( 'active_locations' ) ? 'active' : ''; ?>">
					Express Checkout
				</div>
				<div class="location <?php echo 'express_checkout_only' !== $gateway->get_option( 'active_locations' ) ? 'active' : ''; ?>">
					Checkout page
				</div>
			</div>
			<div class="description">
				<?php if ( $gateway->needs_setup() ) { ?>
					<span>
						<?php echo esc_html( $gateway->method_description ); ?>
					</span>
				<?php } else { ?>
					<div class="details">
						<?php
						$supported_currencies = $gateway->get_supported_currencies();
						$supported_countries  = $gateway->get_supported_countries();
						$columns              = array(
							array(
								'label'          => 'Currency availability',
								'key'            => $gateway->id . '_currency',
								'data'           => is_array( $supported_currencies ) ? implode( ', ', $supported_currencies ) : '',
								'no-data'        => is_null( $supported_currencies ) || 0 === count( $supported_currencies ),
								'no-restriction' => ! is_array( $supported_currencies ),
							),
							array(
								'label'          => 'Country availability',
								'key'            => $gateway->id . '_country',
								'data'           => is_array( $supported_countries ) ? implode( ', ', $supported_countries ) : '',
								'no-data'        => is_null( $supported_countries ) || 0 === count( $supported_countries ),
								'no-restriction' => ! is_array( $supported_countries ),
							),
							array(
								'label'          => 'Minimum charge',
								'key'            => $gateway->id . '_min',
								'data'           => $gateway->get_minimum_charge(),
								'no-data'        => false,
								'no-restriction' => ! is_numeric( $gateway->get_minimum_charge() ),
							),
							array(
								'label'          => 'Maximum charge',
								'key'            => $gateway->id . '_max',
								'data'           => $gateway->get_maximum_charge(),
								'no-data'        => false,
								'no-restriction' => INF === $gateway->get_maximum_charge(),
							),
						);
						?>
						<?php foreach ( $columns as $column ) { ?>
							<div class="flex-col gap-4">
								<h4><?php echo esc_html( $column['label'] ); ?></h4>
								<div class="see-more-wrap">
									<input type="checkbox" class="see-more-state hide" id="<?php echo esc_attr( $column['key'] ); ?>-list"/>
									<div class="flex-col gap-4" style="flex-direction: column-reverse;">
										<label for="<?php echo esc_attr( $column['key'] ); ?>-list" class="see-more-trigger hide"></label>
										<div class="see-more-target" style="max-height: 50px;">
											<p>
												<?php
												if ( $column['no-restriction'] ) {
													echo esc_html_e( 'Not restricted', 'peachpay-for-woocommerce' );
												} elseif ( $column['no-data'] ) {
													echo esc_html_e( 'Not available', 'peachpay-for-woocommerce' );
												} else {
													echo esc_html( $column['data'] );
												}
												?>
											</p>
											<div class="fade-bottom"></div>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
			<?php
			if ( ( 'Google Pay' === $gateway->title || 'Apple Pay' === $gateway->title ) && ! $gateway->needs_setup() ) {
				$url = 'Google Pay' === $gateway->title ? 'https://help.peachpay.app/en/articles/7993492-google-pay-requirements' : 'https://help.peachpay.app/en/articles/6959457-apple-pay-requirements';
				?>
				<div style="display:flex; align-items: center; gap: 6px; color: #616161; font-size: 14px !important; line-height: 24px;">
					<div style="font-size: 16px;" class="pp-icon-info"></div>
					<span>
						<span>
						<?php
						// PHPCS:ignore
						echo sprintf( __( '%s enabled but not showing? Ensure you meet the', 'peachpay-for-woocommerce' ), $gateway->title );
						?>
						</span>
						<a style="color: #21105d;"
						href="<?php echo esc_url( $url ); ?>"
						target="_blank"
						>
						<?php
						// PHPCS:ignore
						echo sprintf( __( 'requirements for %1s', 'peachpay-for-woocommerce' ), $gateway->title );
						?>
						</a>
					</span>
				</div>
				<?php
			}
			?>

		</div>
		<div class="buttons-container flex-col <?php echo esc_attr( $gateway->needs_setup() ? 'needs-setup' : ( ( 'yes' === $gateway->enabled ) ? 'enabled' : 'disabled' ) ); ?>">
			<a class="setup-button button-primary-outlined-medium" data-heap="<?php echo 'setup_' . esc_html( $gateway->id ); ?>" href="<?php echo esc_url( $gateway->get_settings_url() ); ?>">
				<?php
				esc_html_e( 'Set up', 'peachpay-for-woocommerce' );
				?>
				<span class="arrow-top-right"></span>
			</a>
			<button type="button" class="activate-button button-primary-outlined-medium" data-heap="<?php echo 'activate_' . esc_html( $gateway->id ); ?>" data-id="<?php echo esc_html( $gateway->id ); ?>" tabindex="0">
				<?php
				esc_html_e( 'Activate', 'peachpay-for-woocommerce' );
				?>
				<span class="spinner"></span>
			</button>
			<a class="manage-button <?php echo esc_attr( ( 'yes' === $gateway->enabled ) ? 'button-primary-filled-medium default-filled' : 'button-primary-text-medium default-text' ); ?>" data-heap="<?php echo 'manage_' . esc_html( $gateway->id ); ?>" href="<?php echo esc_url( $gateway->get_settings_url() ); ?>">
				<?php
				esc_html_e( 'Manage', 'peachpay-for-woocommerce' );
				?>
				<span class="arrow-top-right"></span>
			</a>
			<button type="button" class="deactivate-button button-warning-text-medium default-text" data-heap="<?php echo 'deactivate_' . esc_html( $gateway->id ); ?>" data-id="<?php echo esc_html( $gateway->id ); ?>" tabindex="0">
				<?php
				esc_html_e( 'Deactivate', 'peachpay-for-woocommerce' );
				?>
				<span class="spinner"></span>
			</button>
		</div>
	</div>
<?php endforeach; ?>
