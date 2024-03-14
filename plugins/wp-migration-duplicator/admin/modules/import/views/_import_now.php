<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<style type="text/css">
    .wt_mgdp_schedule_now{ width:600px; text-align:left; }
    .wt_mgdp_schedule_now_box_import{width: 610px;;max-width: 610px; padding: 40px 20px 20px 20px; box-sizing:border-box;height: 500px;max-height: 500px;display: none}
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
    .wt_mgdp_cron_current_time{float:left; width:auto;}
    .wt_mgdp_cron_current_time span{ display:inline-block; width:85px; }
    /* popup */
    .wt_mgdp_overlay{ position:fixed; z-index:100000000; width:100%; height:100%; background-color:rgba(0,0,0,.5); left:0px; top:0px; display:none;}
    .wt_mgdp_cron_popup_import{position:fixed; z-index:100000001; background:#fff; border:solid 1px #eee; box-shadow:0px 2px 5px #333; left:30%;top: 25%;}
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
.wt-migrator-import-section{
    margin-top: 10px;
}
.import_popup_third{
    display: none;
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
</style>
<div class="wt_mgdp_schedule_now wt_mgdp_cron_popup">
   
    <div class="wt_mgdp_cron_popup_import">
    <div class = "import_popup_second wt_mgdp_schedule_now_box_import" >
            <div class="import_info" >
    <p><b><?php _e("The restore operation has begun. Do not close this page until it reports itself as having finished."); ?></b></p>
        <div class="wt_mgdp_warn_box"><p><?php _e(sprintf('%sNote%s :- The current user will be logged out of the site after import if the same credentials does not exist in the imported database. In this case, use your login credentials from the imported site to log in successfully.', '<b>', '</b>')); ?></p></div>

</div>

                        <div class="wt-migrator-import-section">
                    <div class="wt_mgdp_import_log_main"></div>
                    <div class="wt_mgdp_import_loglist_main">
                        <div class="wt_mgdp_import_loglist_inner">

                        </div>
                    </div>
                </div>
    </div>
    <div class ="import_popup_third wt_mgdp_schedule_now_box_import ">
        <img style="margin: 100px 0px 0px 232px;" src = <?php echo esc_url(plugins_url(basename(plugin_dir_path(WT_MGDP_PLUGIN_FILENAME))) . '/admin/images/finish.svg'); ?>  >          <br>   
        <h2 style="color:green;text-align: center"><?php _e(" Import completed "); ?></h2></br> <br>        
        <button type="button" name="" class="button-secondary wt_import_mgdp_popup_cancel" style="margin-left: 250px;">
            <?php _e('Close'); ?> 
        </button>
    </div>
   </div>
</div>