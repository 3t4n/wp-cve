<?php

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_attr__( 'You do not have sufficient permissions to access this page.', 'daext-helpful' ) );
}

?>

<!-- output -->

<div class="wrap">

    <h2><?php esc_html_e( 'Helpful - Help', 'daext-helpful' ); ?></h2>

    <div id="daext-menu-wrapper">

        <p><?php esc_html_e( 'Visit the resources below to find your answers or to ask questions directly to the plugin developers.',
				'daext-helpful' ); ?></p>
        <ul>
            <li><a href="https://daext.com/doc/helpful/"><?php esc_html_e('Plugin Documentation', 'daext-helpful'); ?></a></li>
            <li><a href="https://daext.com/support/"><?php esc_html_e( 'Support Conditions', 'daext-helpful' ); ?></li>
            <li><a href="https://daext.com"><?php esc_html_e( 'Developer Website', 'daext-helpful' ); ?></a></li>
            <li><a href="https://codecanyon.net/item/helpful-article-feedback-plugin-for-wordpress/44150551"><?php esc_html_e('Pro Version', 'daext-helpful'); ?></a></li>
            <li>
                <a href="https://wordpress.org/plugins/daext-helpful/"><?php esc_html_e( 'WordPress.org Plugin Page',
						'daext-helpful' ); ?></a></li>
            <li>
                <a href="https://wordpress.org/support/plugin/daext-helpful/"><?php esc_html_e( 'WordPress.org Support Forum',
						'daext-helpful' ); ?></a></li>
        </ul>
        <p>

    </div>

</div>