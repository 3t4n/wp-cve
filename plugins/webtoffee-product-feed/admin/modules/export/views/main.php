<?php
/**
 * Main view file of export section
 *
 * @link            
 *
 * @package  Webtoffee_Product_Feed_Sync
 */
if (!defined('ABSPATH')) {
    exit;
}
?>
<?php
do_action('wt_pf_exporter_before_head');
$wf_admin_view_path=plugin_dir_path(WT_PRODUCT_FEED_PLUGIN_FILENAME).'admin/views/';
?>
<style type="text/css">
.wt_pf_export_step{ display:none; }
.wt_pf_export_step_loader{ width:100%; height:400px; text-align:center; line-height:400px; font-size:14px; }
.wt_pf_export_step_main{ float:left; box-sizing:border-box; padding:15px; padding-bottom:0px; width:95%; margin:30px 2.5%; background:#fff; box-shadow:0px 2px 2px #ccc; border:solid 1px #efefef; }
.wt_pf_export_main{ padding:20px 0px; }
.wt_pf_file_ext_info_td{ vertical-align:top !important; }
.wt_pf_file_ext_info{ display:inline-block; margin-top:3px; }


.wt_pf_export_progress_wrapper{
	position: fixed;
	width: 400px;
	height: 200px;
	left: 40%;
	top: 35%;
	padding-left: 25px;
	display: none;
	box-shadow: 2px 2px 4px 2px #ccc;
	z-index: 99999;
	background-color: #fff;
}
.wt_pf_exporting_progress_dot{

	margin-left: 4px;
	margin-right: 4px;
    color: #ccc;
}
.wt_pf_exporting_progress_percent{
	font-weight: bold;
}
.wt_pf_exporting_progress_num{

}
.wt_pf_exporting_progress_bar_wrap{
	padding-top: 20px;
    padding-bottom: 20px;
}
.wt_pf_exporting_progress_bar{
	width: 422px;
	height: 18px;
	background: #D9D9D9;
	border-radius: 19px;
}
.wt_pf_exporting_progress_cancel{
	text-align: center;
}

</style>
<?php
Wt_Pf_IE_Basic_Helper::debug_panel($this->module_base);
?>
<?php include WT_PRODUCT_FEED_PLUGIN_PATH."/admin/views/_save_template_popup.php"; ?>



<h2 class="wt_pf_page_hd" style="padding-left:35px;padding-right: 20px;"><?php _e('Product Feed'); ?><span class="wt_pf_post_type_name"></span>

	<span class="wt-webtoffee-icon" style="float: <?php echo (!is_rtl()) ? 'right' : 'left'; ?>;">
    
		<span style="font-size:14px;"><?php esc_html_e('Developed by'); ?></span>
		<a target="_blank" href="https://www.webtoffee.com">
        <img src="<?php echo WT_PRODUCT_FEED_PLUGIN_URL.'/assets/images/webtoffee-logo_small.png';?>" style="max-width:100px;">
    </a>
</span>
	
</h2>
<hr>

<?php
	if($requested_rerun_id>0 && $this->rerun_id==0)
	{
		?>
		<div class="wt_pf_warn wt_pf_rerun_warn">
			<?php _e('Unable to handle Re-Run request.');?>
		</div>
		<?php
	}
?>

<div class="wt_pf_loader_info_box"></div>
<div class="wt_pf_overlayed_loader"></div>



<div class="wt_pf_export_progress_wrapper">
	<h3 class="wt_pf_export_progress_head"><?php _e('Generating feed...');?> </h3>
	<div class="wt_pf_exporting_progress_percent"> 
	<span class="wt_pf_exporting_progress_done">1</span>  <?php _e('out of');?> <span class="wt_pf_exporting_progress_total">100</span>
	</div>
	<div class="wt_pf_exporting_progress_bar_wrap">
		<div class="progressa" style="height:30px;margin-left: 0px;margin-bottom: 10px;">
			<div class="progressab" style="background-color: rgb(178, 222, 75);width:5px; "></div>
		</div>
	</div>
	<div class="wt_pf_exporting_progress_cancel"><button class="button button-secondary wt_pf_export_popup_cancel_btn"> <?php _e('Cancel');?> </button></div>
</div>


<div class="wt_pf_export_step_main" style = "width:68%">
	<?php
	foreach($this->steps as $stepk=>$stepv)
	{
		?>
		<div class="wt_pf_export_step wt_pf_export_step_<?php echo $stepk;?>" data-loaded="0"></div>
		<?php
	}
	?>
</div>
<?php
include $wf_admin_view_path."market.php";
/*
<script type="text/javascript">
/* external modules can hook 
function wt_pf_exporter_validate(action, action_type, is_previous_step)
{
	var is_continue=true;
	<?php
	do_action('wt_pf_exporter_validate');
	?>
	return is_continue;
}
</script> */ ?>


<div class="wt-pf-help-tip">
    <span>        
        <?php echo sprintf(__(' %s Documentation %s '), "<a href='https://www.webtoffee.com/woocommerce-product-feed-sync-manager-setup-guide/' target='blank'>", '</a>'); ?><br/>
        <hr>
        <?php echo sprintf(__(' %s Video tutorial %s '), "<a href='https://www.youtube.com/watch?v=ys9NeQgCHLE' target='blank'>", '</a>'); ?><br/>
        <hr>        
        <?php echo sprintf(__(' %s Contact Support %s '), "<a href='https://www.webtoffee.com/contact/' target='blank'>", '</a>'); ?><br/>
    </span>
</div>