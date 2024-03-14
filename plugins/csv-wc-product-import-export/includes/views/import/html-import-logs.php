<?php
global $wpdb;
if($log_file!=""){
	?><p class="submit"><a class="docs button-primary" href="admin.php?page=piecfw_import_export&tab=logs"><?php _e('< Back to Logs', PIECFW_TRANSLATE_NAME); ?></a><?php
}
?>
<div class="tool-box">
	<div class="import_form_details">
		<?php
		if($log_file!=""){
			?>
			<h3 class="title"><img src="<?php _e(PIECFW_PLUGIN_DIR_URL);?>assets/images/log.png" />&nbsp;<?php _e('View import logs', PIECFW_TRANSLATE_NAME); ?></h3>
			
			<div id="datalog_list_table"></div>
			<input type="hidden" id="datalog_file" value="<?php _e($log_file);?>" />
			<input type="hidden" id="datalog_sort" value="" />
			<input type="hidden" id="datalog_order" value="" />
			<?php
		}else{
			?>
			<h3 class="title"><img src="<?php _e(PIECFW_PLUGIN_DIR_URL);?>assets/images/log.png" />&nbsp;<?php _e('Logs', PIECFW_TRANSLATE_NAME); ?></h3>
			
			<div id="filelog_list_table"></div>
			<input type="hidden" id="filelog_sort" value="" />
			<input type="hidden" id="filelog_order" value="" />
			<?php
		}
		?>
	</div>
</div>