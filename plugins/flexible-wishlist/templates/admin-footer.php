<?php
/**
 * @package WPDesk\FlexibleWishlist
 */

?>
<div class="fwSettings">
	<div class="fwSettings__footer">
		<?php
		echo wp_kses_post(
			sprintf(
			/* translators: %$1s: love icon, %$2s: anchor opening tag, %$3s: anchor closing tag, %$4s: anchor opening tag, %$5s: anchor closing tag */
				__( 'Created with %1$s by Sailors from %2$sWP Desk%3$s - if you like our plugin %4$srate us%5$s', 'flexible-wishlist' ),
				'<span class="fwSettings__footerIcon fwSettings__footerIcon--heart"></span>',
				'<a href="' . esc_attr( __( 'https://wpde.sk/fw-settings-footer-wpdesk-link', 'flexible-wishlist' ) ) . '" target="_blank">',
				'</a>',
				'<a href="' . esc_attr( __( 'https://wpde.sk/fw-settings-footer-review-link', 'flexible-wishlist' ) ) . '" target="_blank">',
				'<span class="fwSettings__footerIcon fwSettings__footerIcon--star"></span>
						<span class="fwSettings__footerIcon fwSettings__footerIcon--star"></span>
						<span class="fwSettings__footerIcon fwSettings__footerIcon--star"></span>
						<span class="fwSettings__footerIcon fwSettings__footerIcon--star"></span>
						<span class="fwSettings__footerIcon fwSettings__footerIcon--star"></span>
					</a>'
			)
		);
		?>
	</div>
</div>
