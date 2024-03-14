<?php

if ( ! current_user_can('manage_options')) {
    wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'daextlwcnf'));
}

?>

<!-- output -->

<div class="wrap">

    <h2><?php esc_html_e('Lightweight Cookie Notice - Help', 'daextlwcnf'); ?></h2>

    <div id="daext-menu-wrapper">

        <p><?php esc_html_e('Visit the resources below to find your answers or to ask questions directly to the plugin developers.', 'daextlwcnf'); ?></p>
        <ul>
            <li><a href="https://daext.com/doc/lightweight-cookie-notice/"><?php esc_html_e('Plugin Documentation', 'daextlwcnf'); ?></a></li>
            <li><a href="https://daext.com/support/"><?php esc_html_e('Support Conditions', 'daextlwcnf'); ?></li>
            <li><a href="https://daext.com"><?php esc_html_e('Developer Website', 'daextlwcnf'); ?></a></li>
            <li><a href="https://daext.com/lightweight-cookie-notice/"><?php esc_html_e('Pro Version', 'daextlwcnf'); ?></a></li>
            <li><a href="https://wordpress.org/plugins/lightweight-cookie-notice-free/"><?php esc_html_e('WordPress.org Plugin Page', 'daextlwcnf'); ?></a></li>
            <li><a href="https://wordpress.org/support/plugin/lightweight-cookie-notice-free/"><?php esc_html_e('WordPress.org Support Forum', 'daextlwcnf'); ?></a></li>
        </ul>

    </div>