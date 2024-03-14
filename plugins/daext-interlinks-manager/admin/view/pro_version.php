<?php

if ( ! current_user_can( 'edit_posts' ) ) {
	wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'daext-interlinks-manager') );
}

?>

<!-- output -->

<div class="wrap">

    <h2><?php esc_html_e( 'Interlinks Manager - Pro Version', 'daext-interlinks-manager'); ?></h2>

    <div id="daext-menu-wrapper">

        <p><?php echo esc_html__( 'For professional users, we distribute a', 'daext-interlinks-manager') . ' <a href="https://daext.com/interlinks-manager/">' . esc_attr__( 'Pro Version', 'daext-interlinks-manager') . '</a> ' . esc_attr__( 'of this plugin.', 'daext-interlinks-manager') . '</p>'; ?>
        <h2><?php esc_html_e( 'Additional Features Included in the Pro Version', 'daext-interlinks-manager'); ?></h2>
        <ul>
            <li><?php echo esc_html__( 'Ability to export in CSV format the data available in the', 'daext-interlinks-manager') . ' <strong>' . esc_html__( 'Dashboard', 'daext-interlinks-manager') . '</strong> ' . esc_html__( 'menu', 'daext-interlinks-manager'); ?></li>
            <li><?php echo esc_html__( 'Ability to export in CSV format the data available in the', 'daext-interlinks-manager') . ' <strong>' . esc_html__( 'Juice', 'daext-interlinks-manager') . '</strong> ' . esc_html__( 'menu', 'daext-interlinks-manager'); ?></li>
            <li><?php echo '<strong>' . esc_html__( 'Hits', 'daext-interlinks-manager') . '</strong> ' . esc_html__( 'menu to generate a report of all the visits generated from the clicks on the internal links', 'daext-interlinks-manager'); ?></li>
            <li><?php echo esc_html__( 'Ability to export in CSV format the data available in the', 'daext-interlinks-manager') . ' <strong>' . esc_html__( 'Hits', 'daext-interlinks-manager') . '</strong> ' . esc_html__( 'menu', 'daext-interlinks-manager'); ?></li>
            <li><?php echo '<strong>' . esc_html__( 'Interlinks Suggestions', 'daext-interlinks-manager') . '</strong> ' . esc_html__( 'meta box to receive relevant internal links suggestions', 'daext-interlinks-manager'); ?></li>
            <li><?php echo '<strong>' . esc_html__( 'AIL', 'daext-interlinks-manager') . '</strong> ' . esc_html__( 'menu to generate automatic internal links from a list of defined keywords', 'daext-interlinks-manager'); ?></li>
            <li><?php echo '<strong>' . esc_html__( 'Categories', 'daext-interlinks-manager') . '</strong> ' . esc_html__( 'and', 'daext-interlinks-manager') . ' <strong>' . esc_html__( 'Term Groups', 'daext-interlinks-manager') . '</strong> ' . esc_html__( 'menu to apply the automatic internal links only on posts with specific characteristics', 'daext-interlinks-manager'); ?></li>
            <li><?php echo '<strong>' . esc_html__( 'Wizard', 'daext-interlinks-manager') . '</strong> ' . esc_html__( 'menu to bulk upload the automatic internal links with an embedded spreadsheet editor', 'daext-interlinks-manager'); ?></li>
            <li><?php echo '<strong>' . esc_html__( 'Import', 'interlinks-manager' ) . '</strong> ' . esc_html__( 'and', 'daext-interlinks-manager') . ' <strong>' . esc_html__( 'Export', 'daext-interlinks-manager') . '</strong> ' . esc_html__( 'menus to create a backup of the plugin data or move the plugin data between different WordPress installations', 'daext-interlinks-manager'); ?></li>
            <li><?php echo '<strong>' . esc_html__( 'Maintenance', 'daext-interlinks-manager') . '</strong> ' . esc_html__( 'menu to perform bulk operations on the plugin data', 'daext-interlinks-manager'); ?></li>
            <li><?php esc_html_e( 'Additional options to customize the algorithm used to generate the internal links suggestions', 'daext-interlinks-manager'); ?></li>
            <li><?php esc_html_e( 'Additional options to customize the application of the automatic internal links', 'daext-interlinks-manager'); ?></li>
            <li><?php esc_html_e( 'Additional options to set custom menu capabilities for all the plugin menus', 'daext-interlinks-manager'); ?></li>
            <li><?php esc_html_e( 'Additional options to customize the pagination system of the plugin', 'daext-interlinks-manager'); ?></li>
        </ul>
        <h2><?php esc_html_e( 'Additional Benefits of the Pro Version', 'daext-interlinks-manager'); ?></h2>
        <ul>
            <li><?php esc_html_e( '24 hours support provided 7 days a week', 'daext-interlinks-manager'); ?></li>
            <li><?php echo esc_html__( '30 day money back guarantee (more information is available in the', 'daext-interlinks-manager') . ' <a href="https://daext.com/refund-policy/">' . esc_html__( 'Refund Policy', 'daext-interlinks-manager') . '</a> ' . esc_html__( 'page', 'daext-interlinks-manager') . ')'; ?></li>
        </ul>
        <h2><?php esc_html_e( 'Get Started', 'daext-interlinks-manager'); ?></h2>
        <p><?php echo esc_html__( 'Download the', 'daext-interlinks-manager') . ' <a href="https://daext.com/interlinks-manager/">' . esc_html__( 'Pro Version', 'daext-interlinks-manager') . '</a> ' . esc_html__( 'now by selecting one of the available licenses.', 'daext-interlinks-manager'); ?></p>
    </div>

</div>

