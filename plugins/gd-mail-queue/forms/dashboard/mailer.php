<?php

if (!defined('ABSPATH')) { exit; }

$status_labels = array(
    'pause' => __("Paused", "gd-mail-queue"),
    'working' => __("Working", "gd-mail-queue")
);

$status = gdmaq_settings()->get('intercept') && gdmaq_mailer()->allow_intecept() ? 'working' : 'pause';

?>
<div class="d4p-group d4p-group-dashboard-card d4p-group-dashboard-queue">
    <h3><?php esc_html_e("Mailer Core", "gd-mail-queue"); ?></h3>
    <div class="d4p-group-stats">
        <ul class="d4p-items-list">
            <li>
                <strong><i class="fa fa-flag fa-fw"></i> <?php esc_html_e("Intercept Status", "gd-mail-queue"); ?></strong>
                <span class="gdmaq-label gdmaq-<?php echo esc_attr($status); ?>"><?php echo esc_html($status_labels[$status]); ?></span>
            </li>
        </ul><div class="d4p-clearfix"></div>
    </div>
    <div class="d4p-group-inner">
        <h4><?php esc_html_e("Overall Mailer Statistics", "gd-mail-queue"); ?></h4>
        <ul class="d4p-items-list">
            <li>
                <strong><i class="fa fa-reply-all fa-fw"></i> <?php esc_html_e("Emails to Queue Calls", "gd-mail-queue"); ?></strong>
                <span><?php echo esc_html(gdmaq_settings()->get_statistics('mail_to_queue_calls')); ?></span>
            </li>
            <li>
                <strong><i class="fa fa-envelope-o fa-fw"></i> <?php esc_html_e("Emails added to Queue", "gd-mail-queue"); ?></strong>
                <span><?php echo esc_html(gdmaq_settings()->get_statistics('mails_added_to_queue')); ?></span>
            </li>
            <li>
                <strong><i class="fa fa-exchange fa-fw"></i> <?php esc_html_e("Intercepted Emails", "gd-mail-queue"); ?></strong>
                <span><?php echo esc_html(gdmaq_settings()->get_statistics('intercepted_mails')); ?></span>
            </li>
            <li>
                <strong><i class="fa fa-code fa-fw"></i> <?php esc_html_e("HTMLfied Emails", "gd-mail-queue"); ?></strong>
                <span><?php echo esc_html(gdmaq_settings()->get_statistics('total_htmlfy_mails')); ?></span>
            </li>
        </ul><div class="d4p-clearfix"></div>
    </div>
    <div class="d4p-group-footer">
        <a href="admin.php?page=gd-mail-queue-settings&panel=basic" class="button-primary"><?php esc_html_e("Mailer Settings", "gd-mail-queue"); ?></a>
        <a href="admin.php?page=gd-mail-queue-settings&panel=htmlfy" class="button-primary"><?php esc_html_e("HTML Template", "gd-mail-queue"); ?></a>
    </div>
</div>
