<?php
/**
 * Notice about new extension of the Flexible Wishlist plugin.
 *
 * @var string $ajax_url    URL for Admin Ajax.
 * @var string $ajax_action Action for Admin Ajax.
 * @var string $image_url   .
 * @var string $install_url .
 * @package WPDesk\FlexibleWishlist
 */

?>
<div class="notice notice-success is-dismissible"
	data-notice="fw-admin-notice"
	data-notice-url="<?php echo esc_attr( $ajax_url ); ?>"
	data-notice-action="<?php echo esc_attr( $ajax_action ); ?>"
>
	<h2>
		<?php echo esc_html( __( 'Flexible Wishlist PRO - Analytics & Emails is here!', 'flexible-wishlist' ) ); ?>
	</h2>
	<p>
		<?php echo wp_kses_post( __( 'Enrich the Flexible Wishlist functionality with 3 key actions:', 'flexible-wishlist' ) ); ?>
	</p>
	<ul>
		<li>
			<span class="dashicons dashicons-yes"></span>
			<?php echo wp_kses_post( __( 'Track your customers\' wishlists\' content', 'flexible-wishlist' ) ); ?>
		</li>
		<li>
			<span class="dashicons dashicons-yes"></span>
			<?php echo wp_kses_post( __( 'Identify most frequently added products.', 'flexible-wishlist' ) ); ?>
		</li>
		<li>
			<span class="dashicons dashicons-yes"></span>
			<?php echo wp_kses_post( __( 'Create and send out promotional e-mails.', 'flexible-wishlist' ) ); ?>
		</li>
	</ul>
	<p>
		<?php
		echo wp_kses_post(
			sprintf(
				/* translators: %1$s: open anchor tag, %2$s: open anchor tag */
				__( 'It’s just a sneak peak of what you can get with Flexible Wishlist PRO - Analytics & Emails. %1$sRead more >>%2$s', 'flexible-wishlist' ),
				'<a href="' . esc_attr( __( 'https://wpde.sk/fw-settings-notice-analytics-read-more', 'flexible-wishlist' ) ) . '" target="_blank">',
				'</a>'
			)
		);
		?>
	</p>
	<div>
		<a href="<?php echo esc_attr( __( 'https://wpde.sk/fw-settings-notice-analytics-button', 'flexible-wishlist' ) ); ?>"
			target="_blank"
			class="button button-hero button-primary">
			<?php echo esc_html( __( 'Upgrade to PRO &rarr;', 'flexible-wishlist' ) ); ?>
		</a>
		<button type="button"
			class="button button-hero" data-notice-button>
			<?php echo esc_html( __( 'Do not show again', 'flexible-wishlist' ) ); ?>
		</button>
	</div>
</div>
