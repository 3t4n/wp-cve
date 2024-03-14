<?php

if ( class_exists( 'QuadLayers\\WP_Plugin_Table_Links\\Load' ) ) {
	new \QuadLayers\WP_Plugin_Table_Links\Load(
		QLIGG_PLUGIN_FILE,
		array(
			array(
				'text'   => esc_html__( 'Settings', 'insta-gallery' ),
				'url'    => admin_url( 'admin.php?page=qligg_backend' ),
				'target' => '_self',
			),
			array(
				'text' => esc_html__( 'Premium', 'insta-gallery' ),
				'url'  => QLIGG_PREMIUM_SELL_URL,
			),
			array(
				'place' => 'row_meta',
				'text'  => esc_html__( 'Support', 'insta-gallery' ),
				'url'   => QLIGG_SUPPORT_URL,
			),
			array(
				'place' => 'row_meta',
				'text'  => esc_html__( 'Documentation', 'insta-gallery' ),
				'url'   => QLIGG_DOCUMENTATION_URL,
			),
		)
	);
}
