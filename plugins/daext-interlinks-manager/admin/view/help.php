<?php

if ( ! current_user_can( 'edit_posts' ) ) {
	wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'daext-interlinks-manager') );
}

?>

<!-- output -->

<div class="wrap">

    <h2><?php esc_html_e( 'Interlinks Manager - Help', 'daext-interlinks-manager'); ?></h2>

    <div id="daext-menu-wrapper">

        <p><?php esc_html_e( 'Visit the resources below to find your answers or to ask questions directly to the plugin developers.', 'daext-interlinks-manager'); ?></p>
        <ul>
            <li><a href="https://daext.com/support/"><?php esc_html_e( 'Support Conditions', 'daext-interlinks-manager'); ?>
            </li>
            <li><a href="https://daext.com"><?php esc_html_e( 'Developer Website', 'daext-interlinks-manager'); ?></a></li>
            <li><a href="https://daext.com/interlinks-manager/"><?php esc_html_e( 'Pro Version', 'daext-interlinks-manager'); ?></a></li>
            <li>
                <a href="https://wordpress.org/plugins/interlinks-manager/"><?php esc_html_e( 'WordPress.org Plugin Page', 'daext-interlinks-manager'); ?></a></li>
            <li>
                <a href="https://wordpress.org/support/plugin/interlinks-manager/"><?php esc_html_e( 'WordPress.org Support Forum', 'daext-interlinks-manager'); ?></a></li>
        </ul>
        <p>

    </div>

</div>