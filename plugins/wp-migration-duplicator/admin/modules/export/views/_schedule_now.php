<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<style type="text/css">
    .wt_mgdp_schedule_now{ width:600px; text-align:left; }
    .wt_mgdp_schedule_now_box{ width:100%; padding: 75px 65px 75px 75px; box-sizing:border-box;height: 500px;max-height: 500px; line-height: 55px;}
    .wt_mgdp_schedule_now_formrow{float:left; width:100%; margin-bottom:15px; padding-left:5px; box-sizing:border-box;}	
    .wt_mgdp_schedule_now_interval_radio_block{float:left; width:100%; margin:0px; padding:0px; margin-top:2px; }		
    .wt_mgdp_schedule_now_box label{ width:100%; float:left; text-align:left; font-weight:bold; }
    .wt_mgdp_schedule_now_interval_radio_block label{width:auto; float:left; margin-right:10px; margin-bottom:5px; text-align:left; font-weight:normal; }	
    .wt_mgdp_schedule_now_box select, .wt_mgdp_schedule_now_box input[type="text"]{ width:auto; text-align:left; }	
    .wt_mgdp_schedule_now .wt_mgdp_popup_footer{ margin-top:10px; float:left; margin-bottom:20px; }
    .wt_mgdp_schedule_type_desc{ margin-top:0px; padding-left:5px; margin-bottom:0px; }
    .wt_mgdp_schedule_type_box_single{ float:left; margin-top:5px; margin-bottom:10px;}
    .wt_mgdp_schedule_type_box_single label{ color:#666; }
    .wt_mgdp_schedule_now_trigger_url, .wt_mgdp_schedule_day_block, .wt_mgdp_schedule_custom_interval_block, .wt_mgdp_schedule_starttime_block{ display:none; }
    .wt_mgdp_schedule_now_interval_sub_block{ float:left; width:100%; margin-top:3px; }
    .wt_mgdp_cron_current_time{float:right; width:auto;}
    .wt_mgdp_cron_current_time span{ display:inline-block; width:85px; }
    /* popup */
    .wt_mgdp_overlay{ position:fixed; z-index:100000000; width:100%; height:100%; background-color:rgba(0,0,0,.5); left:0px; top:0px; display:none;}
    .wt_mgdp_cron_popup_new{position:fixed; z-index:100000001; background:#fff; border:solid 1px #eee;left:30%; top: 25%;width: 600px;}
    .wt_mgdp_popup_hd{display:inline-block; width:100%; box-sizing:border-box; font-weight:bold; background-color:#f3f3f3; height:60px; text-align:left; line-height:56px; padding:0px 20px;}
    .wt_mgdp_cron_popup_close {
        float: right;
        width: 60px;
        height: 60px;
        text-align: right;
        line-height: 57px;
        cursor: pointer;
        margin-right: 28px;
        font-weight: 900;
    }
    .wt_mgdp_popup_footer{width:100%; text-align:right; margin-top:10px;}
    ul {
        list-style: none;
    }
    .ul_class{margin: 32px 16px 16px 16px; }

    .wt_mgdp_popup_hd_label{
        font-size: 16px;
    }
    .ul_class li:before {
        content: '✓ ';
        color: green;
        font-weight: bold;
        font-size: 16px;
    }
    .wt_mgdp_save_schedule{
        width: 100px;
    }
    .wt_mgdp_popup_cancel{
        margin-left: 80px;
        cursor: pointer;
        font-size: 16px;
    }
    .popup_common{
        width:99%; box-sizing:border-box; padding:110px 15px 30px 12px; margin-bottom:0px; height: 500px; display:none;
    }
    .popup-div{
        display: flex;
        padding-left: 1px;
        margin-top: 6px;
    }
    .popup-div p{ font-size: 16px; }
    .underline-on-hover:hover {
        text-decoration: underline;
    }

    .wt_mgdp_cron_popup {
        position: fixed;
        top: 0px;
        left: 0px;
        z-index: 999;
        height: 100%;
        width: 100%;
        background: rgba(0, 0, 0, 0.2);
        display: none;
    }
    .wf_progress_bar_label{
        font-size: 16px;
    }
    .wt_mgdp_export_download_btn{
         font-size: 16px;
    }
    .wt_center{
        margin: 0 auto;
    display: block;
    }
</style>
<div class="wt_mgdp_schedule_now wt_mgdp_cron_popup">
    <div class="wt_mgdp_cron_popup_new">

        <div class = "popup_second popup_common">

            <div class="wf_progress_bar_label"></div>

            <div class="wf_export_main" style="display:none;" >
                <div style=" margin-top: 20px; display: flex"><div class="wf_progress_bar_label" ></div><div id="loading" class="wf_export_loader"></div> 
                </div>
                <div class="wf_progress_bar" style="margin-top: 20px;">
                    <div class="wf_progress_bar_inner">
                        0%
                    </div>
                </div>
            </div>

            <div class="wf_export_sub" style="display:none; ">

                <div style=" margin-top: 20px; display: flex"><div class="wf_progress_bar_label" style=" margin-top: 15px;"></div><div id="loading" class="wf_export_loader" style="margin-top: 18px;"></div> </div> 
                <div class="wf_progress_bar" style="margin-top: 20px;">
                    <div class="wf_progress_bar_inner">
                        0%
                    </div>
                </div>
            </div>
            <button name="wt_mgdp_export_stop_btn" class="button button-secondary" style="margin: 100px 0px 10px 250px;font-size: 16px;"><?php _e('Stop export', 'wp-migration-duplicator'); ?></button>

        </div>
        <div class ="popup_third popup_common">
            <img class="wt_center" src = <?php echo esc_url(plugins_url(basename(plugin_dir_path(WT_MGDP_PLUGIN_FILENAME))) . '/admin/images/finish.svg'); ?>  >          <br>   
            <h2 style="color:green;text-align: center;font-size: 18px"><?php _e(" Export completed "); ?></h2></br> <br>        
            <a class="button button-primary wt_mgdp_export_download_btn wt_center" name="wt_mgdp_export_download_btn" id="wt_mgdp_export_download_btn" target="_blank" href="" style="height: 48px;width: 200px;text-align: center;padding: 6px; font-size: 16px;margin-left: 185px;" ><?php _e('Download Backup'); ?></a></br></br> 

            <button type="button" name="" class="button-secondary wt_mgdp_finish_popup_cancel " style="font-size: 16px; margin: 0 auto; display: block;">
                <?php _e('Close'); ?> 
            </button>
        </div>
        <div class = "popup_first">
            <div class="wt_mgdp_cron_popup_close">&#x2715</div>

            <div class="wt_mgdp_schedule_now_box">
                <div>
                    <label style="font-size:20px;font-weight: 600;margin-bottom: 4px;"><?php _e('Based on your selection :'); ?></label>
                </div>

                <div class="popup-div">
                    <span class="dashicons dashicons-info-outline" style="color: #87CEFA;margin-top: 17px"></span>&emsp;<p id="export_size"></p></div>

                <div class="popup-div export_content">
                    <span class="dashicons dashicons-info-outline" style="color: #87CEFA;margin-top: 17px"></span>&emsp;<p id="export_content"></p></div>
                <div class="popup-div export_location">
                    <span class="dashicons dashicons-info-outline" style="color: #87CEFA;margin-top: 17px"></span>&emsp;<p id="export_location"></p></div>

                <div style="margin-top: 28px;">

                    <input type="hidden" id="extension_zip_loaded" name="extension_zip_loaded" value=<?php
                    $extension_zip_loaded = extension_loaded('zip') ? 'enabled' : 'disabled';
                    echo esc_attr($extension_zip_loaded);
                    ?>>
                    <input type="hidden" id="extension_zlib_loaded" name="extension_zlib_loaded" value=<?php
                    $extension_zlib_loaded = extension_loaded('zlib') ? 'enabled' : 'disabled';
                    echo esc_attr($extension_zlib_loaded);
                    ?>>
                    <button type="button" name="wt_mgdp_export_btn" class="button-primary wt_mgdp_save_schedule" style="width: 450px;height: 50px;font-size: 16px;"><?php _e('Create Backup'); ?></button>	
                </div>
                <div >
                    <p class="underline-on-hover wt_mgdp_popup_cancel" style="margin-left: 80px"><?php _e('Close the pop-up and change selections', 'wp-migration-duplicator'); ?></a><br/><br/>

                </div>
            </div>
        </div>
    </div>
</div>