<?php 
global $wpdb;
$table_name = $wpdb->prefix . 'ultimate_subscribe';
$total          = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
$today          = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE DATE(created) = CURDATE()");
$this_weak      = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE DATE(created) > DATE_SUB(NOW(), INTERVAL 1 WEEK)");
$this_month     = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE DATE(created) > DATE_SUB(NOW(), INTERVAL 1 MONTH)");
?>
<div class="wrap">
    <div id="icon-users" class="icon32"><br/></div>
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <div class="us-dashboard" style="background-color: #FFF; padding: 20px; min-height: 500px;">
        <h2><?php _e('Subscriber Overview', 'ultimate-subscribe'); ?></h2>
        <div class="devider"></div>
        <h3 style="float: left; padding: 10px; color: #FFF; background-color: green;"><?php _e('Total', 'ultimate-subscribe'); ?>: <?php echo absint($total); ?></h3>
        <div style="clear: both;"></div>
        <div class="dash-block">
            <div class="dash-heading">
                <h3 class="dash-title"><?php _e('Today', 'ultimate-subscribe'); ?></h3>
            </div>
            <div class="dash-info">
                <?php echo absint($today); ?>
            </div>
        </div>
        <div class="dash-block">
            <div class="dash-heading">
                <h3 class="dash-title"><?php _e('This Week', 'ultimate-subscribe'); ?></h3>
            </div>
            <div class="dash-info">
                <?php echo absint($this_weak); ?>
            </div>
        </div>
        <div class="dash-block">
            <div class="dash-heading">
                <h3 class="dash-title"><?php _e('This Month', 'ultimate-subscribe'); ?></h3>
            </div>
            <div class="dash-info">
                <?php echo absint($this_month); ?>
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>
</div>