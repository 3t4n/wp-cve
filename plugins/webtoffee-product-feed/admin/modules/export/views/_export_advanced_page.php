<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wt_pf_export_main">
	<p><?php echo $step_info['description']; ?></p>
	<form class="wt_pf_export_advanced_form">
		<table class="form-table wt-pfd-form-table wt-pfd-export-filter-table">
			<?php
			Webtoffee_Product_Feed_Sync_Common_Helper::field_generator($advanced_screen_fields, $advanced_form_data);
			?>
		</table>
	</form>
</div>
<?php

$file_int_field_tr_arr=array();
$file_int_field_tr_arr=apply_filters('wt_pf_exporter_file_into_fields_row_id_basic', $file_int_field_tr_arr);
?>
<script type="text/javascript">
var file_int_field_tr_arr=<?php echo json_encode($file_int_field_tr_arr); ?>;
/* remote file modules can hook */
function wt_pf_set_file_into_fields(file_into)
{
	/* first hide all */
	if(file_int_field_tr_arr.length>0)
	{	
		jQuery(file_int_field_tr_arr.join(', ')).hide();
	}
//	wt_pf_toggle_schedule_btn(0); //hide scheduler btn if exists
	<?php
	do_action('wt_pf_exporter_file_into_js_fn');
	?>
}
<?php /*
function wt_pf_toggle_schedule_btn(state) /* show/hide cron button 
{
	<?php
	do_action('wt_pf_toggle_schedule_btn');
	?>
} */ ?>

/* custom action: other than export, save, update. Eg: schedule */
function wt_pf_custom_action_basic(ajx_dta, action, id)
{
	ajx_dta['item_type']=ajx_dta['to_export'];
	<?php
	do_action('wt_pf_custom_action_basic');
	?>
}
</script>