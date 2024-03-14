<?php

/* EXIT IF FILE IS CALLED DIRECTLY - */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* PREMIUM PAGE */

add_action( 'admin_menu', 'iprm_add_premium_page_link' );

function iprm_premium_page() {
	ob_start(); ?>
	<div class="iprm_panel" id="iprm_premium">
		<h2><?php _e( 'Premium Service - Launching Soon!', 'iprm_domain' ); ?></h2>
		<div class="iprm_panel_content">
			<p><?php _e( 'For more information and to find out when we launch, please <a href="http://eepurl.com/bhU4SD" target="_blank">enter your email here</a>.', 'iprm_domain' ); ?></p>
		</div>
	</div>
	<?php
	echo ob_get_clean();
}

function iprm_add_premium_page_link() {
	add_submenu_page( 'iprm_main_page', 'Premium', 'Premium', 'manage_options', 'iprm_premium_page', 'iprm_premium_page' );
}
