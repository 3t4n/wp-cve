<?php
if (!defined('ABSPATH')) {
    exit;
}
include WT_MGDP_PLUGIN_PATH . 'admin/modules/export/views/_schedule_now.php';
$am_or_pm = 'AM';
$hour = '';
$min = '';

$data_size_per_req = isset($advanced_settings['data_size_per_req']) ? $advanced_settings['data_size_per_req'] : '';
$db_record_per_req = isset($advanced_settings['db_record_per_req']) ? $advanced_settings['db_record_per_req'] : '';
$file_per_req = isset($advanced_settings['file_per_req']) ? $advanced_settings['file_per_req'] : '';

$start_time = isset($cron_settings['start_time']) ? date("D, M d, Y h:i A", $cron_settings['start_time']) : 'N/A';
$cron_status = isset($cron_settings['start_time']) && !empty($cron_settings['start_time']) ? 'Enable' : 'Disabled';
$last_run = isset($cron_settings['last_run']) && ($cron_settings['last_run'] != 0 ) ? date("D, M d, Y h:i A", $cron_settings['last_run']) : 'N/A';
$cloud_type = isset($cron_settings['display_data']['cloud_details']) ? $cron_settings['display_data']['cloud_details'] : 'N/A';
$interval = isset($cron_settings['display_data']['interval']) ? $cron_settings['display_data']['interval'] : 'day';

$day_value = isset($cron_settings['display_data']['day_value']) ? $cron_settings['display_data']['day_value'] : 'sun';
$start_time1 = isset($cron_settings['display_data']['start_time']) ? $cron_settings['display_data']['start_time'] : '';
if ($start_time1) {
    $time = explode(" ", trim($start_time1));
    $am_or_pm = isset($time[1]) && !empty($time[1]) ? $time[1] : 'AM';
    if ($time[0]) {
        $exact_time = explode(".", trim($time[0]));
        $hour = isset($exact_time[0]) && !empty($exact_time[0]) ? $exact_time[0] : '';
        $min = isset($exact_time[1]) && !empty($exact_time[1]) ? $exact_time[1] : '';
    }
}

$date_value = isset($cron_settings['display_data']['date_value']) ? $cron_settings['display_data']['date_value'] : 1;

$export_type = isset($cron_settings['display_data']['export_type']) ? $cron_settings['display_data']['export_type'] : 1;
$file_content = "";
$db_content = "";
if ($export_type == 'files_and_db') {
    $file_content = "checked";
    $db_content = "checked";
} elseif ($export_type == 'files') {
    $file_content = "checked";
} elseif ($export_type == 'db') {
    $db_content = "checked";
} else {
    $file_content = "checked";
    $db_content = "checked";
}
$cron_settings_data = get_option('wt_mgdp_cron_settings', null);
$cron_action = '';
$cron_acion_delete = '';
if ($cron_settings_data) {
    $cron_action = '<a name = "wt_mgdp_schedule_export_btn_edit" style="cursor:pointer;">   Edit</a> ';
    $cron_acion_delete = '<a name = "wt_mgdp_schedule_export_delete" style="cursor:pointer;">   Delete</a>';
}
?>
<style type="text/css">
    .wt_mgdp_string{ color:darkgreen; }
    .wt_mgdp_builtin{ color:blue; }
    .wt_mgdp_fn{ color:brown; }
    .wt_mgdp_arg{ color:orange; }
    .wt_mgdp_cmnt{ color:gray; }
    .wt_mgdp_code_example{padding:20px; font-size:14px; background:#f6f6f6; box-shadow:inset 1px 1px 1px 0px #ccc; display:none;}
    .wt_mgdp_code_example_readmore{ cursor:pointer; }
    .wt_mgdp_code_indent{ padding-left:40px; display:inline-block; }
    .wf_export_loader {

        display: inline-block;
        width: 10px;
        height: 10px;
        border: 3px solid rgba(69,89,89,.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
        -webkit-animation: spin 1s ease-in-out infinite;
        margin-left: auto;
        margin-right: 15px;
    }

    @keyframes spin {
        to { -webkit-transform: rotate(360deg); }
    }
    @-webkit-keyframes spin {
        to { -webkit-transform: rotate(360deg); }
    }

    .wt_backups_wrapper {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        /*margin-top: 10px;*/
        /*background: #FFF;*/
        /*justify-items: center;*/
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
    }

    .wt_backup_btn_wrapper {
        width: 30%;
        background: #FFF;
        /*height: 200px;*/
        /* padding: 18px 33px; */
        /*padding: 33px;*/
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }
    .wt_backup_schedule_btn_wrapper {
        width: 63%;
        /*        background: #FFF;
                display: -webkit-box;
                display: -ms-flexbox;*/
        /*display: flex;*/
        /*height: 200px;*/
        /* padding: 18px 33px; */
        /*padding: 33px;*/
        /*-webkit-box-sizing: border-box;*/
        box-sizing: border-box;
    }
    .wt_buttom_arrange{

        display: flex;

    }
    .text_label{
        width: 4%;
        height: 200px;
        padding: 33px;
        background: #f0f0f1;

        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }
    .vertical-center {
        margin: 0;
        position: absolute;
        top: 57%;
        margin-right: 62px;
        margin-left: 70px;
        -ms-transform: translateY(-50%);
        transform: translateY(-50%);
    }
    .backup_btn{
        width: 210px;
        height: 70px;
    }
    .wt_widths{
        width:99%
    }
    .text_width{
        border: 1px solid #ced4da;
        height: 36px;
    }
    .time_class{
        padding-bottom: 15px;
        padding-left: 25px;
        padding-right: 25px;
    }
    /*.wt_mgdp_cron_current_time{float:right; width:auto;}*/
    .wt_mgdp_cron_current_time span{ display:inline-block; width:85px;}
    .time_pos{margin-left: 74px;
              margin-top: 5px}
    .btn_pos{margin-top: 130px;
             margin-right: 64px;
             margin-left: 74px;
              }
    .set_margin_top{margin-top: 50px;}
    .div_header{
        height: 42px;border-bottom: 1px solid #E1E3E6; 
    }
    .div_head{
        padding: 5px;margin-left: 20px;
    }
    .second_div{
        width: 42%;padding: 33px;  border-right: 1px solid #E1E3E6;border-left: 1px solid #E1E3E6;
    }
    .h2_wt {
        width: 83%; 
        /*text-align: center;*/ 
        border-bottom: 1px solid #DDDDDD; 
        line-height: 0.1em;
        margin: 10px 8px 24px; 
    } 

    .h2_wt span { 
        padding:0 10px; 
    }
    .ScrollStyle
    {
        max-height: 220px;
        overflow-y: scroll;
    }
    .stime{
        /* margin-top: 12px; */
        padding-top: 15px;
        color:green;
        margin-left: -318px;
    }
    .accordion-wrapper{
        background: #FFFFFFCC;
    padding: 10px 20px 10px 30px;
    width: 94.2%;
    margin-top: 10px;
    position: relative;
    box-shadow: 0px 2px 16px rgba(0, 0, 0, 0.1);
    }
    .accordion-wrapper:after {
content: '';
display: block;
position: absolute;
top: -12px;
left:165px;
width: 20px;
height: 20px; 
    background: #fdfdfdcc;
    border-right:1px solid #e2e2e2;
    border-bottom:1px solid #e2e2e2;
 -moz-transform:rotate(-45deg);
  -webkit-transform:rotate(225deg);
}
  .accordionsc-wrapper{
        background: #FFFFFFCC;
    padding: 10px 20px 10px 30px;
    width: 94.2%;
    margin-top: 10px;
    position: relative;
    box-shadow: 0px 2px 16px rgba(0, 0, 0, 0.1);
    }
    .accordionsc-wrapper:after {
content: '';
display: block;
position: absolute;
top: -12px;
left:965px;
width: 20px;
height: 20px; 
    background: #fdfdfdcc;
    border-right:1px solid #e2e2e2;
    border-bottom:1px solid #e2e2e2;
 -moz-transform:rotate(-45deg);
  -webkit-transform:rotate(225deg);
}
.post-box-over{
    border:none !important;
    border: 1px solid #E1E4E7 !important;
    box-sizing: border-box !important;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.06)!important;
    border-radius: 3px !important;
    /*box-shadow: inset 0px -1px 0px #E2E4E7 !important;*/
}

</style>

<div class="wt_backups_wrapper ">
    <div class="wt_backup_btn_wrapper postbox post-box-over-content">
        <div class="div_header" ><h3 class="div_head" ><?php _e('Export / Backup', 'wp-migration-duplicator'); ?></h3></div>
        <div class="vertical-center">
            <button name="wt_mgdp_export_click_btn" class="button button-primary backup_btn"style="font-size: 18px;"><?php _e('Export / Backup Now', 'wp-migration-duplicator'); ?></button></div>
        <p style="width: 330px !important;margin: 170px 22px 10px 25px !important;color: #adaaaa"><?php echo esc_attr__('Quick export and use this backup file for migration.', 'wp-migration-duplicator'); ?></p>
    </div>
    <div class="text_label">
        <h3 style="margin-top: 90px;margin-left: -10px;"><?php _e('OR', 'wp-migration-duplicator'); ?></h3>
    </div>
    <div class="wt_backup_schedule_btn_wrapper postbox post-box-over-content">
        <div class="div_header" ><h3 class="div_head" ><?php _e('Backup Schedule', 'wp-migration-duplicator'); ?></h3></div>
        <div class="wt_buttom_arrange">
            <div class="second_div">
                <div class="schedule-block">
                    <p id="wt_schedule_status"><strong><?php _e('Schedule Status:', 'wp-migration-duplicator'); ?> </strong><?php _e($cron_status, 'wp-migration-duplicator'); echo wp_kses_post($cron_acion_delete);?> </p>
                    <div id="wi_schedule_info">
                        <p><strong><?php _e('Cloud Type:', 'wp-migration-duplicator'); ?> </strong><?php _e($cloud_type, 'wp-migration-duplicator'); ?> </p>
                        <p id="wt_next_backup"><span id="wt_last_backup_msg"><strong><?php _e('Next Backup:', 'wp-migration-duplicator'); ?>  </strong><?php _e($start_time, 'wp-migration-duplicator');
                        echo wp_kses_post($cron_action); ?></span> </p> 

                        <p><strong><?php _e('Last Backup:', 'wp-migration-duplicator'); ?> </strong><?php _e($last_run, 'wp-migration-duplicator'); ?></p>
                    </div>
                </div>
            </div>
            <div style="width: 48%">
                <div class="btn_pos ">
                    <button name="wt_mgdp_schedule_export_btn" class="button button-primary backup_btn vertical-center" style ="font-size: 18px;"><?php _e('Schedule Backup', 'wp-migration-duplicator'); ?></button>
                </div>
                <div class="wt_mgdp_cron_current_time time_pos"><b><?php _e('Current Server Time:'); ?></b> <span>--:--:-- --</span></div>
            </div>

        </div>

    </div>


</div>

<div class='accordion-wrapper' id='accordion-wrapper'>
<div class = "wt_backup_data" id ="wt_backup_data">
    <div style="display:flex"> <h2 style="width: 190px;"><span><?php _e('Configuration Options', 'wp-migration-duplicator'); ?></span></h2><h2 class="h2_wt"><span></span></h2> </div>
    <div class="postbox wt_widths post-box-over" id="export_class">
        <div class="wt-migrator-accordion-tab wt-migrator-accordion-exclude-settings" style="border-bottom:.5em;">
            <a  href="#"><?php echo esc_attr__('What to Backup?', 'wp-migration-duplicator'); ?></a>
            <div class="wt-migrator-accordion-content" id='content-div' style ="border-top: 1px solid #E1E4E7; height: 378px; ">
                <p><?php _e('Here you can choose to backup the required data. Only the selected files will be backed up! To exclude the backup of any of the selected files, unselect the required.', 'wp-migration-duplicator'); ?></p>
                <div style="display: flex">
                    <div id = "contents"style="width: 45% ;border-right:1px solid #DDDDDD ; padding-right: 4px;">

                        <div style= "padding: 32px ">
                            <label>
<!--                                <input type="radio" option="backup_default" name="export_type_default" value="files_and_db" checked />
                                <span><?php echo esc_attr__(sprintf('%sDatabase and Files (WordPress Files)%s', '<b>', '</b>'), 'wp-migration-duplicator') ?></span>
                            </label><br><br><br><br>-->
                                <label>
                                    <input type="checkbox" option="backup_default" name="export_type_db" value="db" checked/>
                                    <span><?php echo esc_attr__(' Wordpress Database', 'wp-migration-duplicator') ?></span>
                                </label><br><br><br><br>
                                <label>
                                    <input type="checkbox" option="backup_default" name="export_type_file" id="db_default" value="files" checked/>
                                    <span><?php echo esc_attr__(' Wordpress Files', 'wp-migration-duplicator') ?></span>
                                </label><br><br><br><br></div>
                                </div>
                                <div class="exclude_files" style="width: 50% ; margin-left: 36px; margin-top: -14px; " >
                                    <h2><?php echo esc_attr__('Select the Files to Backup', 'wp-migration-duplicator'); ?></h2>
                                    <div>
                                        <table id="datagrid">
                                            <!-- select all boxes -->
                                            <tr>
                                                <td style="padding: 10px;">
                                                    <a href="#" name = "usrselectall_def" id="usrselectall_def" onclick="return false;" ><?php _e('Select all', 'wp-migration-duplicator') ?></a> &nbsp;/&nbsp;
                                                    <a href="#" id="usrunselectall_def" name = "usrunselectall_def" onclick="return false;"><?php _e('Unselect all', 'wp-migration-duplicator') ?></a>
                                                </td>
                                            </tr>
                                        </table>
                                        <div class="ScrollStyle">
                                            <?php do_action('wt_migrator_exlcude_files'); ?></div>
                                    </div>
                                </div></div>
                        <div id='btn-exp' style="height:30px">
                            <button name="wt_mgdp_export_click_btn" class="button button-primary" style="float:right;margin: 20px -22px;"><?php _e('Backup Now', 'wp-migration-duplicator'); ?></button>

                        </div>
                    </div>

                </div>
            </div>

            <div class="postbox wt_widths post-box-over">
                <div class="wt-migrator-accordion-tab wt-migrator-accordion-export-storage-settings" >
                    <a  href="#"><?php echo esc_attr__('Where to Backup?', 'wp-migration-duplicator'); ?></a>
                    <div class="wt-migrator-accordion-content" style ="border-top: 1px solid #E1E4E7;">
                        <p><?php _e('Select the backup storage location. Choosing “Local” will keep the backups only in the web-server/local-system.', 'wp-migration-duplicator'); ?></p>
                        <!--                        <div>
                                                                <div class="wt_warn_box wt_instruction_box "><p><?php _e(sprintf('%sNote%s :- This storage option is not recommended as failure of the local-system in any manner will make you lose the backup.', '<b>', '</b>')); ?></p></div>
                                                       </div>-->
                        <?php do_action('wt_migrator_after_export_page_content'); ?>
                        <div style="height:30px">
                            <button name="wt_mgdp_export_click_btn" class="button button-primary" style="float:right;margin: 11px -22px;"><?php _e('Backup Now', 'wp-migration-duplicator'); ?></button>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class = "wt_backup_schedule_data" id ="backup_schedule_class" style = "display:none">
            <h2 ><?php _e('Schedule Configuration', 'wp-migration-duplicator'); ?></h2>
            <div class="postbox wt_widths post-box-over" id="schedule_class">
                <div class="wt-migrator-accordion-tab wt-migrator-accordion-export-storage-settings">
                    <a href="#"><?php echo esc_attr__('Schedule Automatic Backup Settings', 'wp-migration-duplicator'); ?></a>
                    <div class="wt-migrator-accordion-content" style ="border-top: 1px solid #E1E4E7;">
                        <?php do_action('wt_migrator_after_export_page_content_schedule'); ?>

                        <div class = "time_class">
                            <table class="" >
                                <tbody>
                                    <tr>
                                        <td style ="width: 224px;padding: 15px 5px;font-size: 15px;">
                                            <div style="margin-top: -32px;"><?php _e('Interval'); ?></div></td><td style="padding: 15px 20px;">
                                            <div class="wt_mgdp_schedule_now_formrow">			
                                                <div class="wt_mgdp_schedule_now_interval_radio_block">
                                                    <label for="wt_mgdp_cron_interval_day"><input type="radio" id="wt_mgdp_cron_interval_day" name="wt_mgdp_cron_interval" value="day" <?php
                                                        if ($interval == 'day') {
                                                            echo "checked=checked";
                                                        }
                                                        ?> > <?php _e('Every Day'); ?></label>&emsp;&emsp;

                                                    <label for="wt_mgdp_cron_interval_week"><input type="radio" id="wt_mgdp_cron_interval_week" name="wt_mgdp_cron_interval" value="week" <?php
                                                        if ($interval == 'week') {
                                                            echo "checked=checked";
                                                        }
                                                        ?> > <?php _e('Every Week'); ?></label>&emsp;&emsp;
                                                    <!-- <label for="wt_mgdp_cron_interval_biweek"><input type="radio" id="wt_mgdp_cron_interval_biweek" name="wt_mgdp_cron_interval" value="biweek"> <?php _e('Biweekly'); ?></label> -->
                                                    <label for="wt_mgdp_cron_interval_month"><input type="radio" id="wt_mgdp_cron_interval_month" name="wt_mgdp_cron_interval" value="month" <?php
                                                        if ($interval == 'month') {
                                                            echo "checked=checked";
                                                        }
                                                        ?> > <?php _e('Every Month'); ?></label>&emsp;&emsp;</br>
                                                    <div class="wt_mgdp_cron_current_time stime" style="float: revert; margin: 0px 0px -8px 2px;"><b><?php _e('Current Server Time:'); ?></b> <span>--:--:-- --</span></div>
                                                 <!--<label for="wt_mgdp_cron_interval_custom"><input type="radio" id="wt_mgdp_cron_interval_custom" name="wt_mgdp_cron_interval" value="custom"> <?php _e('Custom'); ?></label>-->
                                                </div></td></tr><tr><td></td><td style="padding-left:20px">
                                            <div class="wt_mgdp_schedule_now_interval_sub_block wt_mgdp_schedule_custom_interval_block">
                                                <label><?php _e('Custom interval'); ?></label>
                                                <input type="number" step="1" min="1" name="wt_mgdp_cron_interval_val" value="" placeholder="<?php _e('Interval in minutes.'); ?>">
                                                <span class="wt-iew_form_help" style="margin-top:3px;"><?php _e('Recommended: Minimum 2 hour(120 minutes)'); ?></span>
                                            </div>
                                            <div class="wt_mgdp_schedule_now_interval_sub_block wt_mgdp_schedule_day_block" style="line-height: 30px;">
                                                <label><?php _e('Which day?'); ?></label><br>
                                                <div class="wt_mgdp_schedule_now_interval_radio_block" style="margin-bottom: 10px;">				
                                                    <?php
                                                    $days = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
                                                    foreach ($days as $day) {
                                                        $day_vl = strtolower($day);
                                                        $checked = ($day_vl == $day_value ? ' checked="checked"' : '');
                                                        ?>
                                                        <label for="wt_mgdp_cron_day_<?php echo esc_html($day_vl); ?>"><input type="radio" value="<?php echo esc_html($day_vl); ?>" id="wt_mgdp_cron_day_<?php echo esc_html($day_vl); ?>" name="wt_mgdp_cron_day" <?php echo esc_html($checked); ?>> <?php _e($day); ?></label>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="wt_mgdp_schedule_now_interval_sub_block wt_mgdp_schedule_date_block" style="margin-bottom: 10px;">
                                                <label><?php _e('Day of the Month?'); ?></label>
                                                <select name="wt_mgdp_cron_interval_date">
                                                    <?php
                                                    for ($i = 1; $i <= 28; $i++) {
                                                        if ($date_value == $i) {
                                                            ?>
                                                            <option value="<?php echo esc_html($i); ?>" selected><?php echo esc_html($i); ?></option>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <option value="<?php echo esc_html($i); ?>"><?php echo esc_html($i); ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                    <option value="last_day"><?php _e('Last day'); ?></option>
                                                </select>
                                            </div>
                                            <div class="wt_mgdp_schedule_now_interval_sub_block wt_mgdp_schedule_starttime_block">
                                                <label><?php _e('Start time'); ?></label> 
                                                <div style="float:left">
                                                    <input  type="number" step="1" min="1" max="12" name="wt_mgdp_cron_start_val" value=<?php echo esc_attr($hour) ?> />
                                                    <span class="wt-iew_form_help" style="display:block; margin: 1px 2px 2px 4px;">Hour</span>
                                                </div>&nbsp&nbsp
                                                <div style="float:left">
                                                    <span class="wt_mgdp_cron_start_val_min">:</span><input type="number" step="1" min="0" max="59" name="wt_mgdp_cron_start_val_min" value=<?php echo esc_attr($min) ?> onchange="if(parseInt(this.value,10)<10)this.value='0'+this.value;" />
                                                                                                            <span class="wt-iew_form_help" style="display:block;  margin: 1px 2px 2px 8px;">Minute</span>
                                                </div>&nbsp&nbsp
                                                <div style="float:left">
                                                    <select name="wt_mgdp_cron_start_ampm_val">
                                                        <?php
                                                        $am_pm = array('AM', 'PM');
                                                        foreach ($am_pm as $apvl) {
                                                            if ($am_or_pm == $apvl) {
                                                                ?>
                                                                <option selected><?php echo esc_attr($apvl); ?></option>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <option><?php echo esc_attr($apvl); ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>&nbsp&nbsp
                                            </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table></div>
                        <table><tr><td style ="width: 172px"><div style="margin-bottom: 105px; margin-left: 28px;font-size: 14px;"><label><?php _e('Export Content'); ?><label</label></div></td><td>
                                    <div style="padding-left: 105px; " >

                                        <!--                                <label>
                                                                            <input type="checkbox" option="backup" name="export_type" value="files_and_db" <?php
                                        if ($export_type == "files_and_db") {
                                            echo 'checked';
                                        }
                                        ?> />
                                                                            <span><?php echo esc_attr__(sprintf('%sDatabase and Files (WordPress Files)%s', '<b>', '</b>'), 'wp-migration-duplicator') ?></span>
                                                                        </label><br><br><br><br>-->
                                        <label>
                                            <input type="checkbox" option="backup" name="cron_export_type_db" value="db" <?php echo esc_attr($db_content); ?> />
                                            <span><?php echo esc_attr__('Database', 'wp-migration-duplicator') ?></span>
                                        </label><br><br><br><br>
                                        <label>
                                            <input type="checkbox" option="backup" name="cron_export_type_files" value="files" <?php echo esc_attr($file_content); ?> />
                                            <span><?php echo esc_attr__('Files', 'wp-migration-duplicator') ?></span>
                                        </label><br><br><br><br>

                                    </div>
                                </td></tr><tr> </table> <table style="margin-left: 10px;"><td style ="width: 200px;"><div class="exclude_folder" style="margin-bottom: 230px;margin-left: 15px;font-size: 14px;"><?php _e('Select the Files to Backup'); ?> </div></td><td><div class="exclude_folder" style="margin-left: 53px;width: 500px;">
                                    <!--                <div class="wt-migrator-accordion-tab wt-migrator-accordion-exclude-settings postbox" style="border-bottom:1px solid #b7bdc5;width: 70%;
                                        margin-left: 26%;">
                                                        <a  href="#" style="padding: 5px;"><?php echo esc_attr__('Exclude Folders/files', 'wp-migration-duplicator'); ?></a>
                                                        <div class="wt-migrator-accordion-content" style ="border-top: 2px dotted #b6b6b7;">
                                                           
                                                        </div>
                                                    </div>-->
                                    <table id="datagrid">
                                        <!-- select all boxes -->
                                        <tr>
                                            <td>
                                                <a href="#" name = "usrselectall" id="usrselectall" onclick="return false;" ><?php _e('Select all', 'wp-migration-duplicator') ?></a> &nbsp;/&nbsp;
                                                <a href="#" id="usrunselectall" name = "usrunselectall" onclick="return false;"><?php _e('Unselect all', 'wp-migration-duplicator') ?></a>
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="ScrollStyle">
                                        <?php do_action('wt_migrator_exlcude_files_cron'); ?></div></div>
                            </td> </tr> </table>
                        <div style="height: 35px;">
                            <input type="hidden" id="extension_zip_loaded" name="extension_zip_loaded_schedule" value=<?php
                            $extension_zip_loaded = extension_loaded('zip') ? 'enabled' : 'disabled';
                            echo esc_attr($extension_zip_loaded);
                            ?>>
                            <input type="hidden" id="extension_zlib_loaded" name="extension_zlib_loaded_schedule" value=<?php
                            $extension_zlib_loaded = extension_loaded('zlib') ? 'enabled' : 'disabled';
                            echo esc_attr($extension_zlib_loaded);
                            ?>>

                            <button name="wt_mgdp_schedule_btn" class="button button-primary" style="float:right;margin: 12px -19px;"><?php _e('Schedule', 'wp-migration-duplicator'); ?></button>
                            <button name="wt_schedule_cancel_btn" class="button button-secondary" style="float:right;position: relative;margin: 12px 35px;width: 77px;"><?php _e('Cancel', 'wp-migration-duplicator'); ?></button>
                            <span class="spinner spinner-save-sch" style="margin-top:11px;margin: 18px -20px 0px 0px;"></span>
                        </div>
                    </div></div>
            </div>
        </div>
        <div class="postbox wt_widths post-box-over">
            <div class="wt-migrator-accordion-tab wt-migrator-accordion-export-storage-settings" >
                <a  href="#"><?php echo esc_attr__('Advanced Options', 'wp-migration-duplicator'); ?></a>
                <div class="wt-migrator-accordion-content" style ="border-top: 1px solid #E1E4E7;">
                    <p style="font-size:13px;"><?php _e('Advanced backup options. Fill up the fields as per your server performance. For high performance servers, you can specify a greater data size and bigger number of database records and files. Each will be processed per request.', 'wp-migration-duplicator'); ?></p>

                    <table class="form-table" style="margin-left: 20px;">       
                        <tr>
                            <th style="width:400px;font-weight: 400">
                                <label for="dta_size"><?php _e('Data Size Limit per Request', 'wp-migration-duplicator'); ?></label>
                                <span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('The maximum data size in megabytes that the server will backup for every request. For servers with high performance, you can handle a  greater data size per request. Defaulted to 50 mb.', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span>
                            </th>
                            <td>
                                <input type="number" name="data_size_per_req" id="data_size_per_req" placeholder="<?php _e('50', 'wp-migration-duplicator'); ?>" value="<?php echo esc_attr($data_size_per_req); ?>" class="input-text text_width" /><?php _e(' mb', 'wp-migration-duplicator'); ?>
                            </td>
                        </tr>
                        <tr>
                            <th style="font-weight: 400">
                                <label for="db_record_per_req"><?php _e('Number of Database Records per Request', 'wp-migration-duplicator'); ?></label>
                                <span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('The number of database records that the server will backup for every request. With high performance servers, you can handle a greater number of records per request. Defaulted to 10000 records.', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span>
                            </th>
                            <td>
                                <input type="number" name="db_record_per_req" id="db_record_per_req"  value="<?php echo esc_attr($db_record_per_req); ?>"  placeholder="<?php _e('10000', 'wp-migration-duplicator'); ?>" class="input-text text_width" />
                            </td>
                        </tr>
                        <tr>
                            <th style="font-weight: 400">
                                <label for="file_per_req"><?php _e('Number of Files Process per Request', 'wp-migration-duplicator'); ?></label>
                                <span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('The number of files that the server will backup for every request. With high performance servers, you can handle a greater number of files per request. Defaulted to 1000 files.', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span>
                            </th>
                            <td>
                                <input type="number" name="file_per_req" id="file_per_req"  value="<?php echo esc_attr($file_per_req); ?>"  placeholder="<?php _e('1000', 'wp-migration-duplicator'); ?>"class="input-text text_width" />
                            </td>
                        </tr>
                    </table>
                    <div style="height:30px">
                        <button name="wt_mgdp_save_settings_btn" id="wt_mgdp_save_settings_btn" class="button button-primary" style="float:right;width: 80px;margin: 12px -22px;"><?php _e('Save', 'wp-migration-duplicator'); ?></button>
                        <span class="spinner spinner-save-export" style="margin-top:11px;margin: 18px 40px 0px 0px;"></span>
                    </div>

                </div>
            </div>
        </div>
    </div>

  <?php include WT_MGDP_PLUGIN_PATH . '/admin/partials/wt_migrator_upgrade_to_pro.php'; ?>