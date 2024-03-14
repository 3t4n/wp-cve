<?php

if ( class_exists( 'QuadLayers\\WP_Plugin_Table_Links\\Load' ) ) {
	new \QuadLayers\WP_Plugin_Table_Links\Load(
		WOOCCM_PLUGIN_FILE,
		array(
			array(
				'text' => esc_html__( 'Settings', 'woocommerce-checkout-manager' ),
				'url'  => admin_url( 'admin.php?page=wc-settings&tab=wooccm' ),
				'target' => '_self',
			),
			array(
				'text' => esc_html__( 'Premium', 'woocommerce-checkout-manager' ),
				'url'  => WOOCCM_PREMIUM_SELL_URL,
			),
			array(
				'place' => 'row_meta',
				'text'  => esc_html__( 'Support', 'woocommerce-checkout-manager' ),
				'url'   => WOOCCM_SUPPORT_URL,
			),
			array(
				'place' => 'row_meta',
				'text'  => esc_html__( 'Documentation', 'woocommerce-checkout-manager' ),
				'url'   => WOOCCM_DOCUMENTATION_URL,
			),
		)
	);
}
