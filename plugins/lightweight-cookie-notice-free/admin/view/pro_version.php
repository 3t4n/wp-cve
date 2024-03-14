<?php

if ( ! current_user_can('manage_options')) {
    wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'daextlwcnf'));
}

?>

<!-- output -->

<div class="wrap">

    <h2><?php esc_html_e('Lightweight Cookie Notice - Pro Version', 'daextlwcnf'); ?></h2>

    <div id="daext-menu-wrapper">

        <p><?php echo esc_html__('For professional users, we distribute a', 'daextlwcnf') . ' <a href="https://daext.com/lightweight-cookie-notice/">' . esc_attr__('Pro Version', 'daextlwcnf') . '</a> ' . esc_attr__('of this plugin.', 'daextlwcnf') . '</p>'; ?>
        <h2><?php esc_html_e('Additional Features Included in the Pro Version', 'daextlwcnf'); ?></h2>
        <ul>
            <li><?php esc_html_e('Let your users set their cookie preferences with toggles', 'daextlwcnf'); ?></li>
            <li><?php esc_html_e('Manage and display lists of cookies', 'daextlwcnf'); ?></li>
            <li><?php esc_html_e('Collect user consent statistics', 'daextlwcnf'); ?></li>
            <li><?php esc_html_e('Import and export menus to backup or migrate the plugin data', 'daextlwcnf'); ?></li>
            <li><?php esc_html_e('A convenient button to reset the cookie consent and preferences', 'daextlwcnf'); ?></li>
            <li><?php esc_html_e('Maintenance menu to perform special actions on the plugin data', 'daextlwcnf'); ?></li>
            <li><?php esc_html_e('Additional options to set custom menu capabilities for all the plugin menus', 'daextlwcnf'); ?></li>
            <li><?php esc_html_e('Other additional advanced options', 'daextlwcnf'); ?></li>
        </ul>
        <h2><?php esc_html_e('Additional Benefits of the Pro Version', 'daextlwcnf'); ?></h2>
        <ul>
            <li><?php esc_html_e('24 hours support provided seven days a week', 'daextlwcnf'); ?></li>
            <li><?php echo esc_html_e('Unlimited future updates (perpetual license)', 'daextlwcnf'); ?></li>
        </ul>
        <h2><?php esc_html_e('Get Started', 'daextlwcnf'); ?></h2>
        <p><?php echo esc_html__('Download the', 'daextlwcnf') . ' <a href="https://daext.com/lightweight-cookie-notice/">' . esc_html__('Pro Version', 'daextlwcnf') . '</a> ' . esc_attr__('now by selecting one of the available licenses.', 'daextlwcnf'); ?></p>
    </div>

</div>