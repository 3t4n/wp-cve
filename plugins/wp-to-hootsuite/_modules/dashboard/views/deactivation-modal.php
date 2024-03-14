<?php
/**
 * Deactivation Modal view, displayed when a Plugin is deactivated.
 *
 * @package WPZincDashboardWidget
 * @author WP Zinc
 */

?>
<div id="wpzinc-deactivation-modal-overlay" class="wpzinc-modal-overlay"></div>
<div id="wpzinc-deactivation-modal" class="wpzinc-inline-modal">
	<header>
		<h2 class="title">
			<?php esc_html_e( 'What went wrong?', $this->plugin->name ); // phpcs:ignore WordPress.WP.I18n ?>
		</h2>
	</header>

	<form method="post" action="<?php echo esc_url( admin_url( 'plugins.php' ) ); ?>" id="wpzinc-deactivation-modal-form">
		<ul>
			<?php
			if ( is_array( $reasons ) && count( $reasons ) > 0 ) {
				foreach ( $reasons as $reason => $labels ) {
					?>
					<li>
						<label>
							<span>
								<input type="radio" name="wpzinc-deactivation-reason" value="<?php echo esc_attr( $reason ); ?>" data-placeholder="<?php echo esc_attr( $labels['placeholder'] ); ?>" />
							</span>
							<span><?php echo esc_html( $labels['label'] ); ?></span>
						</label>
					</li>
					<?php
				}
			}
			?>
		</ul>

		<input type="text" name="wpzinc-deactivation-reason-text" placeholder="" class="widefat" />

		<input type="email" name="wpzinc-deactivation-reason-email" placeholder="<?php esc_attr_e( 'Optional: Your email address.', $this->plugin->name ); // phpcs:ignore WordPress.WP.I18n ?>" class="widefat" />
		<small class="wpzinc-deactivation-reason-email">
			<?php
			esc_html_e( 'If you\'d like further discuss the problem / feature, enter your email address above and we\'ll be in touch.  This will *never* be used for any marketing.', $this->plugin->name ); // phpcs:ignore WordPress.WP.I18n
			?>
		</small>

		<input type="submit" name="submit" value="<?php esc_attr_e( 'Deactivate', $this->plugin->name ); // phpcs:ignore WordPress.WP.I18n ?>" class="button" />
	</form>
</div>
