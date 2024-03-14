<?php
/**
 * @var WPDesk\FlexibleWishlist\Settings\Group\Group[] $settings_groups .
 * @var mixed[]                                        $settings_values .
 * @var string                                         $submit_value    .
 * @var string                                         $nonce_key       .
 * @var string                                         $nonce_value     .
 * @package WPDesk\FlexibleWishlist
 */

?>
<div class="wrap">
	<hr class="wp-header-end">
	<form action="" method="post" class="fwSettings">
		<ul class="fwSettings__columns">
			<li class="fwSettings__column">
				<div class="fwSettings__headline">
					<?php echo esc_html__( 'Flexible Wishlist', 'flexible-wishlist' ); ?>
				</div>
			</li>
		</ul>
		<p>
	<?php
	printf(
		__( 'Read about the settings in %1$sthe plugin documentation%2$s', 'flexible-wishlist' ),
		'<a target="_blank" href="' . esc_url( __( 'https://wpde.sk/fw-settings-page-docs', 'flexible-wishlist' ) ) . '" style="color:#be9803;font-weight: bold">',
		' &rarr;</a>'
	);
	?>
		</p>
		<ul class="fwSettings__columns">
			<li class="fwSettings__column">
				<?php if ( isset( $_POST[ $submit_value ] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Missing ?>
					<div class="fwSettings__alert fwSettings__alert--success">
						<?php echo esc_html( __( 'Changes have been successfully saved!', 'flexible-wishlist' ) ); ?>
					</div>
				<?php endif; ?>
			</li>
		</ul>
		<ul class="fwSettings__columns">
			<li class="fwSettings__column fwSettings__column--wide">
				<?php foreach ( $settings_groups as $settings_group ) : ?>
					<div class="fwSettings__widget">
						<div class="fwSettings__widgetTitle">
							<?php echo esc_html( $settings_group->get_label() ); ?>
						</div>
						<div class="fwSettings__widgetContent">
							<?php
							foreach ( $settings_group->get_fields() as $field ) {
								require __DIR__ . '/fields/' . $field->get_type() . '.php';
							}
							?>
						</div>
					</div>
				<?php endforeach; ?>
				<div class="fwSettings__widget fwSettings__widget--clear">
					<input type="hidden" name="<?php echo esc_attr( $nonce_key ); ?>"
						value="<?php echo esc_attr( $nonce_value ); ?>">
					<button type="submit" name="<?php echo esc_attr( $submit_value ); ?>"
						class="fwButton fwButton--bg fwButton--blue fwButton--wide">
						<?php echo esc_html( __( 'Save Changes', 'flexible-wishlist' ) ); ?>
					</button>
				</div>
			</li>
			<?php if ( ! is_plugin_active( 'flexible-wishlist-analytics/flexible-wishlist-analytics.php' ) ) : ?>
				<li class="fwSettings__column">
					<div class="fwSettings__widget">
						<div class="fwSettings__widgetTitle fwSettings__widgetTitle--bg" style="background-color:#FF9743;">
							<?php echo __( 'Get Flexible Wishlist PRO - Analytics & Emails', 'flexible-wishlist' ); //phpcs:ignore ?>
						</div>
						<div class="fwSettings__widgetContent">
							<br>
						<p style="font-weight:600;"><?php esc_html_e( 'Improve the plugin with analytic tools for product wishlists oraz send promotional emails to your customers.', 'flexible-wishlist' ); ?><?php echo '<a target="_blank" href="' . esc_url( __( 'https://wpde.sk/fw-pro-article-link', 'flexible-wishlist' ) ) . '">' . esc_html__( 'Read more &rarr;', 'flexible-wishlist' ) . '</a>'; ?>
							</p>
							<br>
							<ul>
								<li>
									<span class="dashicons dashicons-yes"></span>
									<?php echo esc_html( __( 'Track your customers\' wishlists\' content', 'flexible-wishlist' ) ); ?>
								</li>
								<li>
									<span class="dashicons dashicons-yes"></span>
									<?php echo esc_html( __( 'Identify most frequently added products', 'flexible-wishlist' ) ); ?>
								</li>
								<li>
									<span class="dashicons dashicons-yes"></span>
									<?php echo esc_html( __( 'Create and send out promotional e-mails', 'flexible-wishlist' ) ); ?>
								</li>
							</ul>
							<br>
							<br>
							<a href="<?php echo esc_attr( __( 'https://wpde.sk/fw-settings-widget-upgrade-button', 'flexible-wishlist' ) ); ?>" target="_blank" class="fwButton fwButton--bg" style="background-color:#FF9743;color:white;">
								<?php echo esc_html( __( 'Upgrade to PRO &rarr;', 'flexible-wishlist' ) ); ?>
							</a>
						</div>
					</div>
				</li>
			<?php endif; ?>
		</ul>
	</form>
</div>
