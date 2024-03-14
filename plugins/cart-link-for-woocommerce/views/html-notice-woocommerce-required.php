<?php
/**
 * @var string $plugin_name .
 */
?>
<div class="notice error">
	<p>
		<?php
		echo wp_kses_post(
			sprintf(
				__( 'The &#8220;%1$s&#8221; plugin cannot run without %2$s active. Please install and activate %2$s plugin.', 'cart-link-for-woocommerce' ),
				$plugin_name,
				'WooCommerce'
			)
		);
		?>
	</p>
</div>
