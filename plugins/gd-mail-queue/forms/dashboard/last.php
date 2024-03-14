<?php if (!defined('ABSPATH')) { exit; }  ?>
<div class="d4p-group d4p-group-dashboard-card d4p-group-dashboard-queue">
    <h3><?php esc_html_e("Last Queue Information", "gd-mail-queue"); ?></h3>
    <div class="d4p-group-stats">
        <ul class="d4p-items-list">
            <li class="liner">
                <strong><i class="fa fa-clock-o fa-fw"></i> <?php esc_html_e("Queue Started", "gd-mail-queue"); ?></strong>
                <span><?php $val = gdmaq_settings()->get_statistics('last_queue_started'); echo $val == 0 ? esc_html__("Never", "gd-mail-queue") : date('Y-m-d H:i:s', gdmaq()->datetime->timestamp_gmt_to_local($val)); ?></span>
            </li>
            <li>
                <strong><i class="fa fa-arrows-h fa-fw"></i> <?php esc_html_e("Queue Run Time", "gd-mail-queue"); ?></strong>
                <span><?php $val = ceil(gdmaq_settings()->get_statistics('last_queue_time')); echo sprintf(_n("%s second", "%s seconds", $val, "gd-mail-queue"), $val); ?></span>
            </li>
        </ul><div class="d4p-clearfix"></div>
    </div>
    <div class="d4p-group-inner">
        <ul class="d4p-items-list" style="margin-top: 0">
            <li>
                <strong><i class="fa fa-envelope-o fa-fw"></i> <?php esc_html_e("Emails Sent", "gd-mail-queue"); ?></strong>
                <span><?php echo esc_html(gdmaq_settings()->get_statistics('last_queue_sent')); ?></span>
            </li>
            <li>
                <strong><i class="fa fa-envelope fa-fw"></i> <?php esc_html_e("Emails Failed", "gd-mail-queue"); ?></strong>
                <span><?php echo esc_html(gdmaq_settings()->get_statistics('last_queue_failed')); ?></span>
            </li>
            <li>
                <strong><i class="fa fa-paperclip fa-fw"></i> <?php esc_html_e("Attachments Sent", "gd-mail-queue"); ?></strong>
                <span><?php echo esc_html(gdmaq_settings()->get_statistics('last_queue_attachments')); ?></span>
            </li>
        </ul><div class="d4p-clearfix"></div>
    </div>
</div>
