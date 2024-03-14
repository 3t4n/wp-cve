<?php

if (!defined('ABSPATH')) { exit; }

$date = gdmaq_settings()->get('dashboard_errors', 'core');
$total_errors = gdmaq_db()->dashboard_count_errors(get_current_blog_id(), $date);

if ($total_errors > 0) {
    $recently = $date == '0000-00-00 00:00:00';
    $now = gdmaq()->datetime->mysql_date(true);
    $latest_errors = gdmaq_db()->dashboard_latest_errors(get_current_blog_id(), $date);
    $label = $recently ? __("Total number of errors logged recently", "gd-mail-queue") : sprintf(__("Total number of errors logged in the past %s", "gd-mail-queue"), human_time_diff(strtotime($date), strtotime($now)));

    ?>
    <div class="d4p-group d4p-group-dashboard-card d4p-double-card d4p-group-dashboard-queue">
        <h3><?php esc_html_e("Logged Email Sending Errors", "gd-mail-queue"); ?></h3>
        <div class="d4p-group-stats">
            <ul class="d4p-items-list">
                <li>
                    <strong><i class="fa fa-warning fa-fw"></i> <?php echo esc_html( $label); ?></strong>
                    <span><?php echo esc_html($total_errors); ?></span>
                </li>
            </ul><div class="d4p-clearfix"></div>
        </div>
        <div class="d4p-group-inner">
            <h4><?php esc_html_e("Latest logged errors", "gd-mail-queue"); ?></h4>
            <ul class="d4p-items-list">
                <?php foreach ($latest_errors as $error) { ?>
                    <li>
                        <strong><i class="fa fa-times fa-fw"></i> <?php echo gdmaq()->get_engine_label($error->engine); ?></strong> -
                        <?php echo empty(trim($error->message)) ? esc_html__("Unspecified error", "gd-mail-queue") : ucfirst($error->message); ?>
                        <span><?php echo esc_html($error->logged); ?></span>
                    </li>
                <?php } ?>
            </ul><div class="d4p-clearfix"></div>
        </div>
        <div class="d4p-group-footer">
            <a href="admin.php?page=gd-mail-queue-log&filter-status=fail" class="button-primary"><?php esc_html_e("Errors in Log", "gd-mail-queue"); ?></a>

            <a href="admin.php?page=gd-mail-queue-front&gdmaq_handler=getback&dashboard-action=clear-board&_nonce=<?php echo wp_create_nonce("gdmaq-clear-board"); ?>" class="button-secondary gdmaq-button-control"><?php esc_html_e("Clear the board", "gd-mail-queue"); ?></a>
        </div>
    </div>

<?php } ?>