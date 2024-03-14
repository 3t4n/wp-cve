<?php
/**
 * @var string $url         .
 * @var string $plugin_name .
 * @var string $dismiss_url .
 */
?>

<div class="notice updated is-dismissible js--cart-link-notice" style="background-color: mintcream;">
	<p>
		<?php
		echo wp_kses_post(
			sprintf(
				__( 'Thank you for installing the %1$s%2$s plugin%3$s. Click %4$shere%5$s to create your first Cart Link campaign.', 'cart-link-for-woocommerce' ),
				'<strong>',
				$plugin_name,
				'</strong>',
				'<a href="' . esc_url( $url ) . '">',
				'</a>'
			)
		);
		?>
	</p>

	<button onclick="window.open('<?php echo esc_url( $dismiss_url ); ?>', '_top');"
			class="notice-dismiss">
		<span class="screen-reader-text"></span>
	</button>
</div>
