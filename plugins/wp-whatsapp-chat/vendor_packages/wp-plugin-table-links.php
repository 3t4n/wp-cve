<?php

if ( class_exists( 'QuadLayers\\WP_Plugin_Table_Links\\Load' ) ) {
	new \QuadLayers\WP_Plugin_Table_Links\Load(
		QLWAPP_PLUGIN_FILE,
		array(
			array(
				'text' => esc_html__( 'Settings', 'wp-whatsapp-chat' ),
				'url'  => admin_url( 'admin.php?page=wp-whatsapp-chat' ),
				'target' => '_self',
			),
			array(
				'text' => esc_html__( 'Premium', 'wp-whatsapp-chat' ),
				'url'  => QLWAPP_PREMIUM_SELL_URL,
			),
			array(
				'place' => 'row_meta',
				'text'  => esc_html__( 'Support', 'wp-whatsapp-chat' ),
				'url'   => QLWAPP_SUPPORT_URL,
			),
			array(
				'place' => 'row_meta',
				'text'  => esc_html__( 'Documentation', 'wp-whatsapp-chat' ),
				'url'   => QLWAPP_DOCUMENTATION_URL,
			),
		)
	);
}
