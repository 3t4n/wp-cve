<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user, $wpdb, $wppmfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
} 
$priorities = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wppm_task_priorities ORDER BY load_order ASC" );
$priority_arr = $wpdb->get_results( "SELECT id FROM {$wpdb->prefix}wppm_task_priorities" );
$priority_ids = array();
if(!empty($priority_arr)){	
	foreach($priority_arr as $key=>$val){
		$priorities_array= json_decode(json_encode($val));
		$priority_ids[] = $priorities_array->id;
	}
	$priority_ids = $wppmfunction->sanitize_array($priority_ids);
}
?>
<div id="wppm_task_priorities_container">
	<div class="wppm-help-container">
      <a href="https://taskbuilder.net/docs/task-priorities/" target="_blank"><?php echo esc_attr__( 'Click here', 'taskbuilder' )?></a> <?php echo esc_attr__( 'to see the documentation!', 'taskbuilder' )?>
    </div>
	<span class="wppm-title">
		<?php echo esc_html_e('Task Priorities','taskbuilder');?>
		<span style="margin-left:10px;" class="wppm-add-new-btn wppm_btn btn-primary" onclick="wppm_add_task_priority();"><?php echo esc_html_e('+Add New','taskbuilder');?></span>
	</span>
	<div class="wppm_padding_space"></div>
	<ul class="wppm-sortable">
		<?php 
		if(!empty($priorities)){
			foreach ($priorities as $priority){
				$color=$priority->color;
				$background_color=$priority->bg_color;
			?>
			<li class="ui-state-default" data-id="<?php echo esc_attr($priority->id)?>">
				<div class="wppm-flex-container"style="background-color:<?php echo esc_attr($background_color)?>;color:<?php echo esc_attr($color)?>;">
					<div class="wppm-sortable-handle"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/filter.svg'); ?>" alt="filter"></div>
					<div class="wppm-sortable-label"><?php echo esc_html_e($priority->name,'taskbuilder') ?></div>
					<div class="wppm-sortable-edit" data-toggle="tooltip" title="Edit" data-placement="top" onclick="wppm_get_edit_task_priority(<?php echo esc_attr($priority->id)?>);"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/edit1.svg'); ?>" alt="edit"></div>
					<div class="wppm-sortable-delete" data-toggle="tooltip" title="Delete" data-placement="top" onclick="wppm_delete_task_priority(<?php echo esc_attr($priority->id)?>);"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/trash.svg'); ?>" alt="delete"></div>
				</div>
			</li>

		<?php } 
		}
		?>
	</ul>
<div>
<script>
	jQuery(function(){
    jQuery( ".wppm-sortable" ).sortable({ handle: '.wppm-sortable-handle' });
		jQuery( ".wppm-sortable" ).on("sortupdate",function(event,ui){
			var keys = jQuery(this).sortable( "toArray", {attribute: 'data-id'} );
			var priority_order = <?php echo json_encode($priority_ids); ?>;
			var result = {};
			for (var i = 0; i < keys.length; i++){
				result[keys[i]] = priority_order[i];
			}
			var data = {
				action: 'wppm_set_priority_order',
				load_orders : result
			};
			jQuery.post(wppm_admin.ajax_url, data, function(response_str) {
				var response = JSON.parse(response_str);
				if (response.sucess_status==1) {
					jQuery('#wppm_alert_success .wppm_alert_text').text(response.messege);
				}
				jQuery('#wppm_alert_success').slideDown('fast',function(){});
				setTimeout(function(){ jQuery('#wppm_alert_success').slideUp('fast',function(){}); }, 3000);
		  	});
		});
	});

	function wppm_set_add_priority(){
		var priority_name = jQuery('#wppm_priority_name').val().trim();
		if (priority_name.length == 0) {
			jQuery('#wppm_priority_name').val('').focus();
			return;
		}
		var priority_color = jQuery('#wppm_priority_color').val().trim();
		if (priority_color.length == 0) {
			priority_color = '#ffffff';
		}
		var priority_bg_color = jQuery('#wppm_priority_bg_color').val().trim();
		if (priority_bg_color.length == 0) {
			priority_bg_color = '#1E90FF';
		}
		jQuery('.wppm_popup_action').text('<?php _e('Please wait ...','taskbuilder')?>');
		jQuery('.wppm_popup_action, #wppm_popup_body input').attr("disabled", "disabled");
		var data = {
			action: 'wppm_set_add_priority',
			priority_name : priority_name,
			priority_color: priority_color,
			priority_bg_color: priority_bg_color
		};
		jQuery.post(wppm_admin.ajax_url, data, function(response_str) {
			wppm_modal_close();
			var response = JSON.parse(response_str);
			wppm_get_task_prioriy_settings();
			if (response.sucess_status=='1') {
				jQuery('#wppm_alert_success .wppm_alert_text').text(response.messege);
				jQuery('#wppm_alert_success').slideDown('fast',function(){});
				setTimeout(function(){ jQuery('#wppm_alert_success').slideUp('fast',function(){}); }, 3000);
				wppm_get_task_prioriy_settings();
			} else {
				jQuery('#wppm_alert_error .wppm_alert_text').text(response.messege);
				jQuery('#wppm_alert_error').slideDown('fast',function(){});
				setTimeout(function(){ jQuery('#wppm_alert_error').slideUp('fast',function(){}); }, 3000);
			}
		})
	}

	function wppm_get_edit_task_priority(priority_id){
		wppm_modal_open(wppm_admin.edit_status);
		var data = {
			action : 'wppm_get_edit_priority',
			priority_id:priority_id
		};
		jQuery.post(wppm_admin.ajax_url, data, function(response_str) {
			var response = JSON.parse(response_str);
			jQuery('#wppm_popup_body').html(response.body);
			jQuery('#wppm_popup_footer').html(response.footer);
			jQuery('#wppm_proj_status_name').focus();
		})
	}

	function wppm_set_edit_priority(priority_id){
		var priority_name = jQuery('#wppm_edit_priority_name').val().trim();
		if (priority_name.length == 0) {
			jQuery('#wppm_edit_priority_name').val('').focus();
			return;
		}
		var priority_color = jQuery('#wppm_edit_priority_color').val().trim();
		if (priority_color.length == 0) {
			priority_color = '#ffffff';
		}
		var priority_bg_color = jQuery('#wppm_edit_priority_bg_color').val().trim();
		if (priority_bg_color.length == 0) {
			priority_bg_color = '#1E90FF';
		}
		jQuery('.wppm_popup_action').text('<?php _e('Please wait ...','taskbuilder')?>');
		jQuery('.wppm_popup_action, #wppm_popup_body input').attr("disabled", "disabled");
		var data = {
			action : 'wppm_set_edit_priority',
			priority_id:priority_id,
			priority_name:priority_name,
			priority_color: priority_color,
			priority_bg_color: priority_bg_color
		};
		jQuery.post(wppm_admin.ajax_url, data, function(response_str) {
			wppm_modal_close();
			var response = JSON.parse(response_str);
			wppm_get_task_prioriy_settings();
			if (response.sucess_status=='1') {
				jQuery('#wppm_alert_success .wppm_alert_text').text(response.messege);
				jQuery('#wppm_alert_success').slideDown('fast',function(){});
				setTimeout(function(){ jQuery('#wppm_alert_success').slideUp('fast',function(){}); }, 3000);
				wppm_get_task_prioriy_settings();
			} else {
				jQuery('#wppm_alert_error .wppm_alert_text').text(response.messege);
				jQuery('#wppm_alert_error').slideDown('fast',function(){});
				setTimeout(function(){ jQuery('#wppm_alert_error').slideUp('fast',function(){}); }, 3000);
			}
		});
	}
</script>