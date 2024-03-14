<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
?>
<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpdb, $wppmfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
} 
$statuses = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wppm_task_statuses ORDER BY load_order ASC" );
$status_arr = $wpdb->get_results( "SELECT id FROM {$wpdb->prefix}wppm_task_statuses" );
$status_ids=array();
if(!empty($status_arr)){
	foreach($status_arr as $key=>$val){
		$statuses_array= json_decode(json_encode($val));
		$status_ids[] = $statuses_array->id;
	}
	$status_ids = $wppmfunction->sanitize_array($status_ids);
}
?>
<div id="wppm_task_statuses_container">
	<div class="wppm-help-container">
      <a href="https://taskbuilder.net/docs/task-statuses/" target="_blank"><?php echo esc_attr__( 'Click here', 'taskbuilder' )?></a> <?php echo esc_attr__( 'to see the documentation!', 'taskbuilder' )?>
    </div>
	<span class="wppm-title">
		<?php echo esc_html_e('Task Statuses','taskbuilder');?>
		<span style="margin-left:10px;" class="wppm_btn btn-primary wppm-add-new-btn" onclick="wppm_get_add_task_status();"><?php echo esc_html_e('+Add New','taskbuilder');?></span>
	</span>
	<div class="wppm_padding_space"></div>
	<ul class="wppm-sortable">
	<?php 
	if(!empty($statuses)){
		foreach ($statuses as $status){
			$color=$status->color;
			$background_color=$status->bg_color;
		?>
		<li class="ui-state-default" data-id="<?php echo esc_attr($status->id)?>">
				<div class="wppm-flex-container"style="background-color:<?php echo esc_attr($background_color)?>;color:<?php echo esc_attr($color)?>;">
					<div class="wppm-sortable-handle"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/filter.svg'); ?>" alt="filter"></div>
					<div class="wppm-sortable-label"><?php echo esc_html_e($status->name,'taskbuilder')?></div>
					<div class="wppm-sortable-edit" onclick="wppm_get_edit_task_status(<?php echo esc_attr($status->id)?>);"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/edit1.svg'); ?>" alt="edit"></div>
					<div class="wppm-sortable-delete" onclick="wppm_delete_task_status(<?php echo esc_attr($status->id)?>);"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/trash.svg'); ?>" alt="delete"></div>
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
			var status_order = <?php echo json_encode($status_ids); ?>;
			var result = {};
			for (var i = 0; i < keys.length; i++){
				result[keys[i]] = status_order[i];
			}
			var data = {
				action: 'wppm_set_task_status_order',
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

	function wppm_set_add_task_status(){
		var status_name = jQuery('#wppm_task_status_name').val().trim();
		if (status_name.length == 0) {
			jQuery('#wppm_task_status_name').val('').focus();
			return;
		}
		var status_color = jQuery('#wppm_task_status_color').val().trim();
		if (status_color.length == 0) {
			status_color = '#ffffff';
		}
		var status_bg_color = jQuery('#wppm_task_status_bg_color').val().trim();
		if (status_bg_color.length == 0) {
			status_bg_color = '#1E90FF';
		}
		jQuery('.wppm_popup_action').text('<?php _e('Please wait ...','taskbuilder')?>');
		jQuery('.wppm_popup_action, #wppm_popup_body input').attr("disabled", "disabled");
		var data = {
			action: 'wppm_set_add_task_status',
			status_name : status_name,
			status_color: status_color,
			status_bg_color: status_bg_color
		};
		jQuery.post(wppm_admin.ajax_url, data, function(response_str) {
			wppm_modal_close();
			var response = JSON.parse(response_str);
			wppm_get_task_status_settings();
			if (response.sucess_status=='1') {
				jQuery('#wppm_alert_success .wppm_alert_text').text(response.messege);
				jQuery('#wppm_alert_success').slideDown('fast',function(){});
				setTimeout(function(){ jQuery('#wppm_alert_success').slideUp('fast',function(){}); }, 3000);
				wppm_get_task_status_settings();
			} else {
				jQuery('#wppm_alert_error .wppm_alert_text').text(response.messege);
				jQuery('#wppm_alert_error').slideDown('fast',function(){});
				setTimeout(function(){ jQuery('#wppm_alert_error').slideUp('fast',function(){}); }, 3000);
			}
		})
	}

	function wppm_get_edit_task_status(status_id){
		wppm_modal_open(wppm_admin.edit_status);
		var data = {
			action : 'wppm_get_edit_task_status',
			status_id:status_id
		};
		jQuery.post(wppm_admin.ajax_url, data, function(response_str) {
			var response = JSON.parse(response_str);
			jQuery('#wppm_popup_body').html(response.body);
			jQuery('#wppm_popup_footer').html(response.footer);
			jQuery('#wppm_proj_status_name').focus();
		})
	}

	function wppm_set_edit_task_status(status_id){
		var status_name = jQuery('#wppm_edit_task_status_name').val().trim();
		if (status_name.length == 0) {
			jQuery('#wppm_edit_task_status_name').val('').focus();
			return;
		}
		var status_color = jQuery('#wppm_edit_task_status_color').val().trim();
		if (status_color.length == 0) {
			status_color = '#ffffff';
		}
		var status_bg_color = jQuery('#wppm_edit_task_status_bg_color').val().trim();
		if (status_bg_color.length == 0) {
			status_bg_color = '#1E90FF';
		}
		jQuery('.wppm_popup_action').text('<?php _e('Please wait ...','taskbuilder')?>');
		jQuery('.wppm_popup_action, #wppm_popup_body input').attr("disabled", "disabled");
		var data = {
			action : 'wppm_set_edit_task_status',
			status_id:status_id,
			status_name:status_name,
			status_color: status_color,
			status_bg_color: status_bg_color
		};
		jQuery.post(wppm_admin.ajax_url, data, function(response_str) {
			wppm_modal_close();
			var response = JSON.parse(response_str);
			wppm_get_task_status_settings();
			if (response.sucess_status=='1') {
				jQuery('#wppm_alert_success .wppm_alert_text').text(response.messege);
				jQuery('#wppm_alert_success').slideDown('fast',function(){});
				setTimeout(function(){ jQuery('#wppm_alert_success').slideUp('fast',function(){}); }, 3000);
				wppm_get_task_status_settings();
			} else {
				jQuery('#wppm_alert_error .wppm_alert_text').text(response.messege);
				jQuery('#wppm_alert_error').slideDown('fast',function(){});
				setTimeout(function(){ jQuery('#wppm_alert_error').slideUp('fast',function(){}); }, 3000);
			}
		});
	}
</script>
