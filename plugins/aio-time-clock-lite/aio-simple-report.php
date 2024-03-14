<?php
global $wpdb;
global $post;
global $current_user;
$update_reports_file = plugin_dir_url(__FILE__) . '/inc/update_aio_reports.php';
?>
<div class="controlDiv">
    <h2><?php echo esc_attr_x('Date Range', 'aio-time-clock-lite'); ?></h2>
    <?php $aio_pp_start_date = date($this->prettyDateTime, strtotime("-2 weeks")); ?>
    <strong><?php echo esc_attr_x('From', 'aio-time-clock-lite'); ?>: </strong><input type="text" id="aio_pp_start_date" name="aio_pp_start_date" class="adminInputDate" placeholder="Start Date" value="<?php echo esc_attr($aio_pp_start_date); ?>"> <strong><?php echo esc_attr_x('Through', 'aio-time-clock-lite'); ?>: </strong> 
    <?php $aio_pp_end_date = date($this->prettyDateTime, strtotime("+1 day")); ?>    
    <input type="text" id="aio_pp_end_date" class="adminInputDate" name="aio_pp_end_date" placeholder="End Date" value="<?php echo esc_attr($aio_pp_end_date); ?>" >
    <label><strong><?php echo esc_attr_x('Employee', 'aio-time-clock-lite'); ?> : </strong></label>
    <select name= "employee" id="employee">
        <option value=""><?php echo esc_attr_x('Show All', 'aio-time-clock-lite'); ?></option>
        <?php echo $this->getEmployeeSelect(); ?>
    </select>
    <a id="aio_generate_report" href="<?php echo esc_url($link); ?>" class="button-primary"><?php echo esc_attr_x('Submit', 'aio-time-clock-lite'); ?></a>
</div>
<div id="report-response" style="display:none;padding:40px;"></div>
<div id="aio-reports-results" style="display:none;"></div>
<input type="hidden" name="wage_enabled" id="wage_enabled" value="<?php echo esc_attr(get_option("aio_wage_manage")); ?>">