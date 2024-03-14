<?php
/**
 * The file used to display the "Help" menu in the admin area.
 *
 * @package hreflang-manager-lite
 */

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'hreflang-manager-lite' ) );
}

?>

<!-- output -->

<div class="wrap">

	<h2><?php esc_html_e( 'Hreflang Manager - Help', 'hreflang-manager-lite' ); ?></h2>

	<div id="daext-menu-wrapper">

		<p><?php esc_html_e( 'Visit the resources below to find your answers or to ask questions directly to the plugin developers.', 'hreflang-manager-lite' ); ?></p>
		<ul>
			<li><a href="https://daext.com/doc/hreflang-manager/"><?php esc_html_e( 'Plugin Documentation', 'hreflang-manager-lite' ); ?></a></li>
			<li><a href="https://daext.com/support/"><?php esc_html_e( 'Support Conditions', 'hreflang-manager-lite' ); ?></li>
			<li><a href="https://daext.com"><?php esc_html_e( 'Developer Website', 'hreflang-manager-lite' ); ?></a></li>
			<li><a href="https://daext.com/hreflang-manager/"><?php esc_html_e( 'Pro Version', 'hreflang-manager-lite' ); ?></a></li>
			<li><a href="https://wordpress.org/plugins/hreflang-manager-lite/"><?php esc_html_e( 'WordPress.org Plugin Page', 'hreflang-manager-lite' ); ?></a></li>
			<li><a href="https://wordpress.org/support/plugin/hreflang-manager-lite/"><?php esc_html_e( 'WordPress.org Support Forum', 'hreflang-manager-lite' ); ?></a></li>
		</ul>

	</div>

</div>