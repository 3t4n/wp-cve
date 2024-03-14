<?php if (!defined('ABSPATH')) { exit; }  ?>
<div class="d4p-group d4p-group-dashboard-card d4p-group-dashboard-queue">
    <h3><?php esc_html_e("Overall Queue Statistics", "gd-mail-queue"); ?></h3>
    <div class="d4p-group-stats">
        <ul class="d4p-items-list">
            <li class="liner">
                <strong><i class="fa fa-reply fa-fw"></i> <?php esc_html_e("Queue Calls", "gd-mail-queue"); ?></strong>
                <span><?php echo esc_html(gdmaq_settings()->get_statistics('total_queue_calls')); ?></span>
            </li>
            <li>
                <strong><i class="fa fa-arrows-h fa-fw"></i> <?php esc_html_e("Queue Run Time", "gd-mail-queue"); ?></strong>
                <span><?php $val = ceil(gdmaq_settings()->get_statistics('total_queue_time')); echo sprintf(_n("%s second", "%s seconds", $val, "gd-mail-queue"), $val); ?></span>
            </li>
        </ul><div class="d4p-clearfix"></div>
    </div>
    <div class="d4p-group-inner">
        <ul class="d4p-items-list" style="margin-top: 0">
            <li>
                <strong><i class="fa fa-envelope-o fa-fw"></i> <?php esc_html_e("Emails Sent", "gd-mail-queue"); ?></strong>
                <span><?php echo esc_html(gdmaq_settings()->get_statistics('total_queue_sent')); ?></span>
            </li>
            <li>
                <strong><i class="fa fa-envelope fa-fw"></i> <?php esc_html_e("Emails Failed", "gd-mail-queue"); ?></strong>
                <span><?php echo esc_html(gdmaq_settings()->get_statistics('total_queue_failed')); ?></span>
            </li>
            <li>
                <strong><i class="fa fa-paperclip fa-fw"></i> <?php esc_html_e("Attachments Sent", "gd-mail-queue"); ?></strong>
                <span><?php echo esc_html(gdmaq_settings()->get_statistics('total_queue_attachments')); ?></span>
            </li>
        </ul><div class="d4p-clearfix"></div>
    </div>
</div>
