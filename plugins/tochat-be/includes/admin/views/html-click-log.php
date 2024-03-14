<?php defined( 'ABSPATH' ) || exit; ?>
<div class="wrap">
    <h1>Click Log <a href="<?php echo wp_nonce_url( '?tochatbe_export_click_log=yes', 'tochatbe_export_click_log', 'tochatbe_export_click_log' ); ?>" class="button button-primary">Export CSV</a></h1>
    <hr>
    <div>
        <p><strong>Today:</strong> <?php echo TOCHATBE_Log::get_total_day_click(); ?> | <strong>This Week:</strong> <?php echo TOCHATBE_Log::get_this_week_click(); ?></p>
    </div>
    <?php
        $table = new TOCHATBE_Admin_Log_Table;
        $table->prepare_items();
    ?>
    <form method="post" action="#">
        <?php $table->search_box( 'Search', 'tochatbe-search' ); ?>
    </form>
    <?php
        $table->display();
    ?>
</div>

<?php require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/views/notifications/html-purchase-premium.php'; ?>