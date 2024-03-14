<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}
?>
<style type="text/css">
.wt_pf_history_page{ padding:15px; }
.history_list_tb td, .history_list_tb th{ text-align:left; }
.history_list_tb tr th:first-child{ text-align:left; }
.wt_pf_delete_history, .wt_pf_delete_log{ cursor:pointer; }
.wt_pf_history_settings{  float:left; width:100%; padding:15px; background:#fff; border:solid 1px #ccd0d4; box-sizing:border-box; margin-bottom:15px; }
.wt_pf_history_settings_hd{ float:left; width:100%; font-weight:bold; font-size:13px; }
.wt_pf_history_settings_form_group_box{ float:left; width:100%; box-sizing:border-box; padding:10px; padding-bottom:0px; height:auto; font-size:12px; }
.wt_pf_history_settings_form_group{ float:left; width:auto; margin-right:3%; min-width:200px;}
.wt_pf_history_settings_form_group label{ font-size:12px; font-weight:bold; }
.wt_pf_history_settings_form_group select, .wt_pf_history_settings_form_group input[type="text"]{ height:20px; }
.wt_pf_history_no_records{float:left; width:100%; margin-bottom:55px; margin-top:20px; text-align:center; background:#fff; padding:15px 0px; border:solid 1px #ccd0d4;}
.wt_pf_bulk_action_box{ float:left; width:auto; margin:10px 0px; }
select.wt_pf_bulk_action{ float:left; width:auto; height:20px; margin-right:10px; }
.wt_pf_view_log_btn{ cursor:pointer; }
.wt_pf_view_log{  }
.wt_pf_log_loader{ width:100%; height:200px; text-align:center; line-height:150px; font-size:14px; font-style:italic; }
.wt_pf_log_container{ padding:25px; }
.wt_pf_raw_log{ text-align:left; font-size:14px; }
.log_view_tb th, .log_view_tb td{ text-align:center; }
.log_list_tb .log_file_name_col{ text-align:left; }
</style>

<?php
	include plugin_dir_path(__FILE__)."/_history_list.php";	
?>