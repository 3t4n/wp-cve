<?php
function cwmp_desactivate() {
	wp_clear_scheduled_hook('cwmp_cron_events');
}
function cwmp_activate() {
	add_option( 'cwmp_activate_plugin', 'checkout-woocommerce-mestres-wp' );
	include( 'includes/core/cwmp-reset.php' );
	include( 'includes/core/cwmp-create-cron.php' );
	include( 'includes/core/cwmp-create-database.php' );
}
register_activation_hook( __FILE__, 'cwmp_activate' );
register_deactivation_hook( __FILE__, 'cwmp_desactivate' );

function cwmp_load() {
    if ( is_admin() && get_option( 'cwmp_activate_plugin' ) == 'checkout-woocommerce-mestres-wp' ) {
        delete_option( 'cwmp_activate_plugin' );
    }
}
add_action( 'admin_init', 'cwmp_load' );