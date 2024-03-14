<?php

if (!defined('ABSPATH')) { exit; }

$status_labels = array(
    'pause' => __("Paused", "gd-mail-queue"),
    'working' => __("Working", "gd-mail-queue"),
    'waiting' => __("Waiting", "gd-mail-queue")
);

$status = gdmaq_queue()->is_paused() ? 'pause' : (gdmaq_queue()->is_running() ? 'working' : 'waiting');
$next = $status != 'pause' ? date('Y-m-d H:i:s', gdmaq()->datetime->timestamp_gmt_to_local(gdmaq_queue()->next_queue_run())) : '/';
$run_in = $status != 'pause' ? gdmaq_queue()->next_queue_run() - time() : '/';
$interval = $status != 'pause' ? gdmaq_settings()->get('cron', 'queue') : '/';

if ($run_in !== '/') {
    $run_in = $run_in < 1 ? __("~ 1 second", "gd-mail-queue") : sprintf(_n("%s second", "%s seconds", $run_in, "gd-mail-queue"), $run_in);
}

if ($interval !== '/') {
    $interval = sprintf(_n("%s minute", "%s minutes", $interval, "gd-mail-queue"), $interval);
}

?>
<div class="d4p-group d4p-group-dashboard-card d4p-group-dashboard-queue">
    <h3><?php esc_html_e("Queue Core", "gd-mail-queue"); ?></h3>
    <div class="d4p-group-stats">
        <ul class="d4p-items-list">
            <li>
                <strong><i class="fa fa-flag fa-fw"></i> <?php esc_html_e("Queue Status", "gd-mail-queue"); ?></strong>
                <span class="gdmaq-label gdmaq-<?php echo $status; ?>"><?php echo $status_labels[$status]; ?></span>
            </li>
        </ul><div class="d4p-clearfix"></div>
    </div>
    <div class="d4p-group-inner">
        <h4><?php esc_html_e("Last Queue Information", "gd-mail-queue"); ?></h4>
        <ul class="d4p-items-list">
            <li>
                <strong><i class="fa fa-clock-o fa-fw"></i> <?php esc_html_e("Next Queue Run", "gd-mail-queue"); ?></strong>
                <span><?php echo esc_html($next); ?></span>
            </li>
            <li>
                <strong><i class="fa fa-arrow-circle-right fa-fw"></i> <?php esc_html_e("Queue will run in", "gd-mail-queue"); ?></strong>
                <span><?php echo esc_html($run_in); ?> (<?php echo sprintf(_x("%s interval", "Queue Interval", "gd-mail-queue"), $interval); ?>)</span>
            </li>
            <li>
                <strong><i class="fa fa-envelope-o fa-fw"></i> <?php esc_html_e("Emails in Queue", "gd-mail-queue"); ?></strong>
                <span><?php echo esc_html(gdmaq_queue()->counts('queue')); ?></span>
            </li>
            <li>
                <strong><i class="fa fa-envelope fa-fw"></i> <?php esc_html_e("Locked in Queue", "gd-mail-queue"); ?></strong>
                <span><?php echo esc_html(gdmaq_queue()->counts('waiting')); ?></span>
            </li>
        </ul>
    </div>
    <div class="d4p-group-footer">
        <a href="admin.php?page=gd-mail-queue-settings&panel=queue" class="button-primary"><?php esc_html_e("Queue Settings", "gd-mail-queue"); ?></a>
    </div>
</div>
