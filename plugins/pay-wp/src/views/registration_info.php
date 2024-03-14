<div>
	<p class="description">
		<strong><?php _e( 'Autopay is a reliable online payment system, created especially for the needs of websites built
        based on WordPress and WooCommerce.', 'pay-wp' ); ?></strong>
	</p>

	<p class="description">
		<?php _e( 'Configuration will take you no more than a few minutes and will not cause any difficulties - even if you don\'t have one
        technical preparation.', 'pay-wp' ); ?>
	</p>

	<div style="float: left; margin-top: 1em; padding: 0 1em; border-left: 4px solid black; font-weight: bold;">
		<p style="font-size: 1.2em;">
			<?php _e( 'At Autopay, you only pay a fixed commission on the transaction - no additional costs, no contracts and formalities.', 'pay-wp' ); ?>
		</p>

		<p style="font-size: 1.1em;">
			<?php printf( __( 'Don\'t have an Autopay account yet? %sGo to the registration form →%s', 'pay-wp' ), '<a href="' . \WPDesk\GatewayWPPay\Plugin::WPPAY_REGISTER_URL . '" target="_blank">', '</a>' ); ?>
		</p>
	</div>
</div>
