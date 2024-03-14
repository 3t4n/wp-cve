<?php

if ( class_exists( 'QuadLayers\\WP_Plugin_Table_Links\\Load' ) ) {
	new \QuadLayers\WP_Plugin_Table_Links\Load(
		QLSTFT_PLUGIN_FILE,
		array(
			array(
				'text' => esc_html__( 'Settings', 'storefront-footer' ),
				'url'  => admin_url( 'options-general.php?page=storefront-footer' ),
				'target' => '_self',
			),
			array(
				'text' => esc_html__( 'Premium', 'storefront-footer' ),
				'url'  => QLSTFT_PURCHASE_URL,
			),
			array(
				'place' => 'row_meta',
				'text'  => esc_html__( 'Support', 'storefront-footer' ),
				'url'   => QLSTFT_SUPPORT_URL,
			),
		)
	);
}
