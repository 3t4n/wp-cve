<?php

if ( class_exists( 'QuadLayers\\WP_Plugin_Table_Links\\Load' ) ) {
	new \QuadLayers\WP_Plugin_Table_Links\Load(
		QUADMENU_PLUGIN_FILE,
		array(
			array(
				'text'   => esc_html__( 'Settings', 'quadmenu' ),
				'url'    => admin_url( 'admin.php?page=' . QUADMENU_PANEL ),
				'target' => '_self',
			),
			array(
				'text' => esc_html__( 'Premium', 'quadmenu' ),
				'url'  => QUADMENU_PURCHASE_URL,
			),
			array(
				'place' => 'row_meta',
				'text'  => esc_html__( 'Support', 'quadmenu' ),
				'url'   => QUADMENU_SUPPORT_URL,
			),
			array(
				'place' => 'row_meta',
				'text'  => esc_html__( 'Documentation', 'quadmenu' ),
				'url'   => QUADMENU_DOCUMENTATION_URL,
			),
		)
	);
}
