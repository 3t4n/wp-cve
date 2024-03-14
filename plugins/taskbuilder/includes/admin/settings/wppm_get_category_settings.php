<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpdb,$wppmfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
} 
$categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wppm_project_categories ORDER BY load_order ASC" );
$cat_arr = array();
$cat_arr = $wpdb->get_results( "SELECT id FROM {$wpdb->prefix}wppm_project_categories" );
if(!empty($cat_arr)){
	foreach($cat_arr as $key=>$val){
		$categories_array= json_decode(json_encode($val));
		$cat_ids[] = $categories_array->id;
	}
	$cat_ids = $wppmfunction->sanitize_array($cat_ids);
}
?>
<div id="wppm_category_container">
	<div class="wppm-help-container">
      <a href="https://taskbuilder.net/docs/project-categories/" target="_blank"><?php echo esc_attr__( 'Click here', 'taskbuilder' )?></a> <?php echo esc_attr__( 'to see the documentation!', 'taskbuilder' )?>
    </div>
	<span class="wppm-title">
		<?php echo esc_html_e('Project Categories','taskbuilder');?>
		<span style="margin-left:10px;" class="wppm-add-new-btn wppm_btn btn-primary" onclick="wppm_get_add_category()"><?php echo esc_html_e('+Add New','taskbuilder');?></span>
	</span>
	<div class="wppm_padding_space"></div>
	<ul class="wppm-sortable">
		<?php if(!empty($categories)){
				foreach ($categories as $key=>$value){ ?>
				<li class="ui-state-default" data-id="<?php echo intval(esc_attr($value->id))?>">
					<div class="wppm-flex-container" style="border:1px solid #ddd">
						<div class="wppm-sortable-handle"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/filter.svg'); ?>" alt="filter"></div>
						<div class="wppm-sortable-label"><?php echo esc_html_e($value->name,'taskbuilder') ?></div>
						<div class="wppm-sortable-edit" onclick="wppm_get_edit_category(<?php echo esc_attr($value->id) ?>);"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/edit1.svg'); ?>" alt="edit"></div>
						<div class="wppm-sortable-delete" onclick="wppm_delete_category(<?php echo esc_attr($value->id) ?>);"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/trash.svg'); ?>" alt="delete"></div>
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
			var order = <?php echo (!empty($cat_ids)) ? json_encode($cat_ids):"0" ?>;
			var result = {};
			for (var i = 0; i < keys.length; i++){
				result[keys[i]] = order[i];
			}
			var data = {
				action: 'wppm_set_category_order',
				load_orders : result
			};
			jQuery.post(wppm_admin.ajax_url, data, function(response_str) {
				var response = JSON.parse(response_str);
				if (response.sucess_status==1) {
					jQuery('#wppm_alert_success').show();
					jQuery('#wppm_alert_success .wppm_alert_text').text(response.messege);
				}
				jQuery('#wppm_alert_success').slideDown('fast',function(){});
				setTimeout(function(){ jQuery('#wppm_alert_success').slideUp('fast',function(){}); }, 2000);
		  	});
		});
	});

	function wppm_get_add_category(){
		wppm_modal_open('Add Category'); 
		var data = {
			action: 'wppm_get_add_category'
		};
		jQuery.post(wppm_admin.ajax_url, data, function(response_str) {
			var response = JSON.parse(response_str);
			jQuery('#wppm_popup_body').html(response.body);
			jQuery('#wppm_popup_footer').html(response.footer);
			jQuery('#wppm_cat_name').focus();
		});
	}

	function wppm_set_add_category(){
		var cat_name = jQuery('#wppm_cat_name').val().trim();
		if (cat_name.length == 0) {
			jQuery('#wppm_cat_name').val('').focus();
			return;
		}
		jQuery('.wppm_popup_action').text('<?php _e('Please wait ...','taskbuilder')?>');
		jQuery('.wppm_popup_action, #wppm_popup_body input').attr("disabled", "disabled");
		var data = {
			action: 'wppm_set_add_category',
			cat_name : cat_name
		};
		jQuery.post(wppm_admin.ajax_url, data, function(response_str) {
			wppm_modal_close();
			var response = JSON.parse(response_str);
			wppm_get_category_settings();
			if (response.sucess_status=='1') {
				jQuery('#wppm_alert_success .wppm_alert_text').text(response.messege);
				jQuery('#wppm_alert_success').slideDown('fast',function(){});
				setTimeout(function(){ jQuery('#wppm_alert_success').slideUp('fast',function(){}); }, 3000);
				wppm_get_category_settings();
			} else {
				jQuery('#wppm_alert_error .wppm_alert_text').text(response.messege);
				jQuery('#wppm_alert_error').slideDown('fast',function(){});
				setTimeout(function(){ jQuery('#wppm_alert_error').slideUp('fast',function(){}); }, 3000);
			}
		});
	}

	function wppm_get_edit_category(cat_id){
		wppm_modal_open(wppm_admin.edit_category);
		var data = {
			action : 'wppm_get_edit_category',
			cat_id:cat_id
		};
		jQuery.post(wppm_admin.ajax_url, data, function(response_str) {
			var response = JSON.parse(response_str);
			jQuery('#wppm_popup_body').html(response.body);
			jQuery('#wppm_popup_footer').html(response.footer);
			jQuery('#wppm_cat_name').focus();
		});
	}

	function wppm_set_edit_category(cat_id){
		var cat_name = jQuery('#wppm_edit_cat_name').val().trim();
		if (cat_name.length == 0) {
			jQuery('#wppm_edit_cat_name').val('').focus();
			return;
		}
		jQuery('.wppm_popup_action').text('<?php _e('Please wait ...','taskbuilder')?>');
		jQuery('.wppm_popup_action, #wppm_popup_body input').attr("disabled", "disabled");
		var data = {
			action : 'wppm_set_edit_category',
			cat_id:cat_id,
			cat_name:cat_name
		};
		jQuery.post(wppm_admin.ajax_url, data, function(response_str) {
			wppm_modal_close();
			var response = JSON.parse(response_str);
			wppm_get_category_settings();
			if (response.sucess_status=='1') {
				jQuery('#wppm_alert_success .wppm_alert_text').text(response.messege);
				jQuery('#wppm_alert_success').slideDown('fast',function(){});
				setTimeout(function(){ jQuery('#wppm_alert_success').slideUp('fast',function(){}); }, 3000);
				wppm_get_category_settings();
			} else {
				jQuery('#wppm_alert_error .wppm_alert_text').text(response.messege);
				jQuery('#wppm_alert_error').slideDown('fast',function(){});
				setTimeout(function(){ jQuery('#wppm_alert_error').slideUp('fast',function(){}); }, 3000);
			}
		});
	}
</script>