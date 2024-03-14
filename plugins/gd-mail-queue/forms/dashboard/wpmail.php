<?php

if (!defined('ABSPATH')) { exit; }

$status_labels = array(
    'pause' => __("Paused", "gd-mail-queue"),
    'working' => __("Working", "gd-mail-queue")
);

$status = gdmaq_mailer()->is_paused() ? 'pause' : 'working';

?>
<div class="d4p-group d4p-group-dashboard-card d4p-group-dashboard-queue">
    <h3><?php esc_html_e("WP Mail", "gd-mail-queue"); ?></h3>
    <div class="d4p-group-stats">
        <ul class="d4p-items-list">
            <li>
                <strong><i class="fa fa-flag fa-fw"></i> <?php esc_html_e("WP Mail Status", "gd-mail-queue"); ?></strong>
                <span class="gdmaq-label gdmaq-<?php echo $status; ?>"><?php echo $status_labels[$status]; ?></span>
            </li>
        </ul><div class="d4p-clearfix"></div>
    </div>
    <div class="d4p-group-inner">
        <h4><?php esc_html_e("Overall WP Mail Statistics", "gd-mail-queue"); ?></h4>
        <ul class="d4p-items-list">
            <li>
                <strong><i class="fa fa-reply fa-fw"></i> <?php esc_html_e("Total Sent", "gd-mail-queue"); ?></strong>
                <span><?php echo esc_html(gdmaq_settings()->get_statistics('wp_mail_sent')); ?></span>
            </li>
            <li>
                <strong><i class="fa fa-times fa-fw"></i> <?php esc_html_e("Total Failed", "gd-mail-queue"); ?></strong>
                <span><?php echo esc_html(gdmaq_settings()->get_statistics('wp_mail_failed')); ?></span>
            </li>
        </ul><div class="d4p-clearfix"></div>
    </div>
    <div class="d4p-group-footer">
        <a href="admin.php?page=gd-mail-queue-settings&panel=pause" class="button-primary"><?php esc_html_e("Pause Controls", "gd-mail-queue"); ?></a>
    </div>
</div>
