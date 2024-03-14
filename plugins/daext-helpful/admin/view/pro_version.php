<?php

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'restricted-blocks' ) );
}

?>

<!-- output -->

<div class="wrap">

    <h2><?php esc_html_e( 'Helpful - Pro Version', 'daext-helpful' ); ?></h2>

    <div id="daext-menu-wrapper">

        <p><?php echo esc_html__( 'For professional users, we distribute a',
					'daext-helpful' ) . ' <a href="https://daext.com/helpful/">' . esc_html__( 'Pro Version',
					'daext-helpful' ) . '</a> ' . esc_html__( 'of this plugin.',
					'daext-helpful' ) . '</p>'; ?>
        <h2><?php esc_html_e( 'Additional Features Included in the Pro Version', 'daext-helpful' ); ?></h2>
        <ul>
            <li><?php echo esc_html__( 'Customize the position of the feedback form','daext-helpful' ); ?></li>
            <li><?php echo esc_html__( 'Display in the front end the number of feedback received','daext-helpful' ); ?></li>
            <li><?php echo esc_html__( 'Ability to export the cumulative posts data in CSV format with a widget included in the','daext-helpful' ) . ' <strong>' . esc_html__('Statistics', 'daext-helpful') . '</strong> ' . esc_html__('menu', 'daext-helpful');; ?></li>
            <li><?php echo esc_html__( 'Ability to export the data of the single feedback in CSV format with a widget included in the','daext-helpful' ) . ' <strong>' . esc_html__('Statistics', 'daext-helpful') . '</strong> ' . esc_html__('menu', 'daext-helpful');; ?></li>
        </ul>
        <h2><?php esc_html_e( 'Additional Benefits of the Pro Version', 'daext-helpful' ); ?></h2>
        <ul>
            <li><?php esc_html_e( '24 hours support provided 7 days a week', 'daext-helpful' ); ?></li>
            <li><?php echo esc_html__( '30 day money back guarantee (more information is available in the',
						'daext-helpful' ) . ' <a href="https://daext.com/refund-policy/">' . esc_html__( 'Refund Policy',
						'daext-helpful' ) . '</a> ' . esc_html__( 'page', 'daext-helpful' ) . ')'; ?></li>
        </ul>
        <h2><?php esc_html_e( 'Get Started', 'daext-helpful' ); ?></h2>
        <p><?php echo esc_html__( 'Download the',
					'daext-helpful' ) . ' <a href="https://daext.com/helpful/">' . esc_html__( 'Pro Version',
					'daext-helpful' ) . '</a> ' . esc_html__( 'now by selecting one of the available licenses.',
					'daext-helpful' ); ?></p>
    </div>

</div>

