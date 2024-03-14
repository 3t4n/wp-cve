<div class="wrap aio_admin_wrapper">
    <?php 
    global $post;
    $link = null;
    $logo = plugins_url('/images/logo.png', __FILE__);
    ?>    
        <a href="https://codebangers.com" target="_blank"><img src="<?php echo esc_url($logo); ?>" style="width:15%;"></a>
        <hr>
        <h1><?php echo esc_attr_x('Reports', 'aio-time-clock-lite'); ?></h1>
        <h2 class="nav-tab-wrapper">
            <?php
            $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : "simple_report";
            ?>
            <a href="?page=aio-reports-sub&tab=simple_report" class="nav-tab<?php if ($tab == "simple_report") { echo " nav-tab-active"; }?>">
                <i class="dashicons dashicons-admin-users"></i>
                <?php echo esc_attr_x('Date Range', 'aio-time-clock-lite');?>
            </a>
            <a href="?page=aio-reports-sub&tab=custom_reports" class="nav-tab<?php if ($tab == "custom_reports") { echo " nav-tab-active"; }?>">
                <i class="dashicons dashicons-menu"></i>
                <?php echo esc_attr_x('Advanced', 'aio-time-clock-lite');?>
            </a>
        </h2>
    <?php 
    if ($tab == "simple_report") {
        include("aio-simple-report.php");
    }
    if ($tab == "custom_reports"){
        include("aio-report-wizard.php");
    }
    ?>
</div>