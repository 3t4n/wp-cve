<?php

/**
 * @var $app \FluentSupport\Framework\Foundation\Application;
 */

add_filter('fluent_support/parse_smartcode_data', function ($string, $data) {
    return (new \FluentSupport\App\Services\Parser\Parser())->parse($string, $data);
}, 10, 2);

add_filter('fluent_support/dashboard_notice', function ($messages) {
    if(defined('FLUENTSUPPORTPRO_PLUGIN_VERSION') && version_compare(FLUENT_SUPPORT_PRO_MIN_VERSION, FLUENTSUPPORTPRO_PLUGIN_VERSION, '>')) {
        $updateUrl = admin_url('plugins.php?s=fluent-support-pro&plugin_status=all&fluentsupport_pro_check_update=' . time());
        $html = '<div class="fs_box fs_dashboard_box"><div class="fs_box_header" style="background-color: #FFEACA">Heads UP! Fluent Support Pro update available</div><div class="fs_box_body" style="padding: 10px 30px;">Fluent Support Pro Plugin needs to be updated. <a href="'.esc_url($updateUrl).'>">Click here to update the plugin</a></div></div>';
        $messages .= $html;
    }
    return $messages;
}, 100);

add_filter('fluent_support/mail_to_customer_header', function ($headers, $data){
    return (new \FluentSupport\App\Hooks\Handlers\EmailNotificationHandler())->getMailerHeaderWithCc($headers, $data);
}, 10, 2);
