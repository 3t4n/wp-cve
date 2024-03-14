<?php

if( !defined( 'ABSPATH' ) ) { exit; }

function rs_eucc_setup_menu() {
	add_menu_page(
		RS_EUCC__PLUGIN_NAME,
		RS_EUCC__PLUGIN_SHORT_NAME,
		RS_EUCC__ADMIN_REQCAP,
		RS_EUCC__ADMIN_PAGE,
		RS_EUCC__ADMIN_FUNC,
		RS_EUCC__PLUGIN_ICON,
		RS_EUCC__PLUGIN_MENU_POS
	);
}