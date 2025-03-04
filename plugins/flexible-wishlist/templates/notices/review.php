<?php
/**
 * Notice about plugin review.
 *
 * @var string $ajax_url    URL for Admin Ajax.
 * @var string $ajax_action Action for Admin Ajax.
 * @package WPDesk\FlexibleWishlist
 */

?>
<div class="notice notice-success is-dismissible"
	data-notice="fw-admin-notice"
	data-notice-url="<?php echo esc_attr( $ajax_url ); ?>"
	data-notice-action="<?php echo esc_attr( $ajax_action ); ?>"
>
	<h2>
		<?php echo esc_html( __( 'Thanks for using the free version of Flexible Wishlist!', 'flexible-wishlist' ) ); ?>
	</h2>
	<p>
		<?php
		echo wp_kses_post(
			sprintf(
			/* translators: %1$s: dashicon */
				__( 'We are glad that (with our little help %1$s) the shop is now better suited to the needs. We will be grateful for the rating and feedback. It will take less than reading this and it will help us a lot!', 'flexible-wishlist' ),
				'<span class="dashicons dashicons-heart"></span>'
			)
		);
		?>
	</p>
	<div>
		<a href="<?php echo esc_attr( __( 'https://wpde.sk/fw-settings-notice-review-button', 'flexible-wishlist' ) ); ?>"
			target="_blank"
			class="button button-hero button-primary">
			<?php echo esc_html( __( 'Add review', 'flexible-wishlist' ) ); ?>
		</a>
		<button type="button"
			class="button button-hero" data-notice-button>
			<?php echo esc_html( __( 'I added review, do not show again', 'flexible-wishlist' ) ); ?>
		</button>
	</div>
</div>
