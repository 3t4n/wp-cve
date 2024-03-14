<?php

function cew_pro_enabled() {
	return defined( 'CEW_PRO_VERSION' );
}

function cew_show_pro_badge() {
	if ( ! cew_pro_enabled() ) {
		load_template( CEW_PATH . '/templates/partials/pro-badge.php', false );
	}
}

function cew_show_pro_overlay() {
	if ( ! cew_pro_enabled() ) {
		load_template( CEW_PATH . '/templates/partials/pro-overlay.php', false );
	}
}

function cew_get_cpt() {
	$args = array(
		'public'   => true,
		'_builtin' => false,
	);

	return get_post_types( $args, 'objects' );
}
