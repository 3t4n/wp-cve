<?php 
    global $wpdb;
    $table_name = $wpdb->prefix . 'wpc_tracking';
    $sql = "SELECT DISTINCT user_id FROM $table_name";
    $results = $wpdb->get_results($sql);
    $user_count = $wpdb->num_rows;

?>

<?php do_action('wpc_after_dashboard_data'); ?>
<div class="wpc-flex-container">
    <div class="wpc-flex-content wpc-flex-content-large">
        <div class="wpc-flex-container">

            <div class="wpc-flex-4 wpc-material wpc-fade">
                <h2 class="wpc-material-heading" style="text-align: center;">Active Students</h2>
                <div class="wpc-meta-lg wpc-center" style="color: #FCD46C;"><?php echo (int) $user_count; ?></div>
            </div>

            <div class="wpc-flex-4 wpc-material wpc-fade">
                <h2 class="wpc-material-heading" style="text-align: center;">Courses</h2>
                <div class="wpc-meta-lg wpc-center" style="color: #E21772;"><?php echo (int) wp_count_posts('course')->publish; ?></div>
            </div>

            <div class="wpc-flex-4 wpc-material wpc-fade">
                <h2 class="wpc-material-heading" style="text-align: center;">Lessons</h2>
                <div class="wpc-meta-lg wpc-center" style="color: #89D6E2;"><?php echo (int) wp_count_posts('lesson')->publish; ?></div>
            </div>

        </div>

        <div class="wpc-flex-container">

            <div class="wpc-flex-12 wpc-material wpc-fade">
                <h2 class="wpc-material-heading"><?php esc_html_e('Tracking Data', 'wp-courses'); ?></h2>
                <?php include 'charts/chart-tracking-data.php'; ?>
            </div>

            <div class="wpc-flex-6 wpc-material wpc-fade">
                <h2 class="wpc-material-heading"><?php esc_html_e('Popular Courses by % Viewed or Completed', 'wp-courses'); ?></h2>
                <?php include 'charts/chart-popular-courses.php'; ?>
            </div>

            <div class="wpc-flex-6 wpc-material wpc-fade">
                <h2 class="wpc-material-heading"><?php esc_html_e('Most Active Users', 'wp-courses'); ?></h2>
                <?php include 'charts/chart-most-active-users.php'; ?>
            </div>

        </div>

    </div>

</div>




