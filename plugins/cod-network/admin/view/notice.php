<?php
/*
 * Notice partials
 */
$shouldActiveWC = network_admin_url('plugins.php?s=WooCommerce&plugin_status=inactive');
$shouldDownloadWc = network_admin_url('plugin-install.php?s=WooCommerce&tab=search&type=term');
$wooCommercePluginName = 'woocommerce';
$installLink = sprintf('<a href="%s">Download %s</a>', esc_url($shouldDownloadWc), $wooCommercePluginName);
$activeLink = sprintf('<a href="%s">Activate %s</a>', esc_url($shouldActiveWC), $wooCommercePluginName);
$setupPluginLink = sprintf('<a href="%s">connect your store with CODNetwork</a>', esc_url(network_admin_url('admin.php?page=cod.network')));
$logo = sprintf('<img src="%s/assets/images/logo.svg" width="150px" style="padding: 15px;">', codn_plugin_dir_path());

if (!isset($noticeType)) {
    return;
}

if ($noticeType == CODN_NOTICE_WC_NOT_INSTALLED) {
    echo sprintf('<div class="notice notice-info is-dismissible" style="border-left-color: #faa51a;">%s<h2 style="display: inline;font-size: 20px;margin-top: 55px;position: absolute;">%s</h2></div>', $logo, sprintf(esc_html('You should %s to start using CODNetwork plugin'), $installLink));
}

if ($noticeType == CODN_NOTICE_WC_DISABLED) {
    echo sprintf('<div class="notice notice-info is-dismissible" style="border-left-color: #faa51a;">%s<h2 style="display: inline;font-size: 20px;margin-top: 55px;position: absolute;">%s</h2></div>', $logo, sprintf(esc_html('You should %s to start using CODNetwork plugin'), $activeLink));
}

if ($noticeType == CODN_NOTICE_SETUP_PLUGIN) {
    echo sprintf('<div class="notice notice-info is-dismissible" style="border-left-color: #faa51a;">%s<h2 style="display: inline;font-size: 20px;margin-top: 55px;position: absolute;">%s</h2></div>', $logo, sprintf(esc_html('WooCommerce is active you can start %s'), $setupPluginLink));
}


