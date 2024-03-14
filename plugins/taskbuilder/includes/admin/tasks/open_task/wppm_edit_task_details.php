<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $wpdb,$wppmfunction,$current_user;
$task_id  = isset($_POST['task_id']) ? intval(sanitize_text_field($_POST['task_id'])) : '' ;
$proj_id = isset($_POST['proj_id']) ? intval(sanitize_text_field($_POST['proj_id'])) : '' ;
if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_permission('change_task_details',$task_id))) {exit;}
$task_data = $wppmfunction->get_task($task_id);
$project_data = $wppmfunction->get_project(intval(sanitize_text_field($task_data['project'])));
if($current_user->has_cap('manage_options')){
    $projects = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wppm_project ORDER BY project_name" );
}else{
    $query = ("SELECT Proj.*
              FROM {$wpdb->prefix}wppm_project AS Proj
              Left join {$wpdb->prefix}wppm_project_meta proj_meta ON Proj.id = proj_meta.project_id
              where (FIND_IN_SET($current_user->ID,Proj.users)) OR (Proj.id = proj_meta.project_id AND proj_meta.meta_key='public_project' AND proj_meta.meta_value=1) Group by Proj.id ORDER BY project_name");
    $projects = $wpdb->get_results($query);
}
$priority_id = sanitize_text_field($task_data['priority']);
$project_id = sanitize_text_field($task_data['project']);

$priorities = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wppm_task_priorities");
ob_start();
?>
<form id="frm_get_edit_task_deatils" method="post">
    <div class="row" style="padding-left:2px;">
		<div class="col-sm-12">
    	    <label class="wppm_edit_task_label" for="wppm_edit_task_label"><?php echo esc_html_e('Task','taskbuilder') ?> </label>
	    </div>
    </div>
    <div class="row" style="padding-left:2px;">
		<div class="col-sm-12">
    	    <input type="text" class="wppm_edit_task_label form-control" name="wppm_edit_task_label" id="wppm_edit_task_label" value="<?php echo isset($task_data['task_name']) ? esc_attr($task_data['task_name']) :"" ?>">
	    </div>
    </div>
	<div class="row" style="padding-left:2px;">
		<div class="col-sm-12">
    	    <label class="wppm_edit_project_label" for="wppm_edit_project_label"><?php esc_html_e('Project:','taskbuilder') ?> </label>
        </div>
    </div>
    <div class="row" style="padding-left:2px;padding-bottom: 10px;">
		<div class="col-sm-12">
            <select searchable="search here" onchange="wppm_select_project()" class="form-control" size="40" name="wppm_task_project" id="wppm_task_project">
            <?php 
                if(!empty($projects)){
                    foreach($projects as $proj) { 
                        ?>
                        <option <?php echo esc_attr($proj->id)==esc_attr($project_id) ?'selected="selected"':''?> value="<?php echo esc_attr($proj->id)?>"><?php echo esc_html_e($proj->project_name,'taskbuilder');?></option>
                <?php } 
                } ?>
            </select>
        </div>
    </div>
    <div class="row" style="padding-left:2px;">
		<div class="col-sm-12">
    	    <label class="wppm_edit_task_start_date" for="wppm_edit_task_start_date"><?php echo esc_html_e('Start Date','taskbuilder') ?> </label>
	    </div>
    </div>
    <div class="row" style="padding-left:2px;">
        <div class="col-sm-12">
            <input name="wppm_edit_task_start_date" class="form-control" id="wppm_edit_task_start_date" type="text" value="<?php echo isset($task_data['start_date'])? esc_attr($task_data['start_date']):""?>" size="40" aria-required="true"><br>
        </div>
    </div>
    <div class="row" style="padding-left:2px;">
		<div class="col-sm-12">
    	    <label class="wppm_edit_task_end_date" for="wppm_edit_task_end_date"><?php echo esc_html_e('End Date','taskbuilder') ?> </label>
	    </div>
    </div>
    <div class="row" style="padding-left:2px;">
        <div class="col-sm-12">
            <input name="wppm_edit_task_end_date" class="form-control" id="wppm_edit_task_end_date" type="text" value="<?php echo isset($task_data['end_date']) ? esc_attr($task_data['end_date']):"" ?>" size="40" aria-required="true"><br>
        </div>
    </div>
    <div class="row" style="padding-left:2px;">
		<div class="col-sm-12">
            <label class="wppm_edit_task_priority_label" for="wppm_edit_task_priority_label" id="wppm_edit_task_priority_label"><?php echo esc_html_e('Task Priority','taskbuilder');?></label>
	    </div>
    </div>
    <div class="row" style="padding-left:2px;">
        <div class="col-sm-12">              
            <select class="form-control" name="wppm_edit_task_priority" id="wppm_edit_task_priority">
                <option></option>
                <?php
                if(!empty($priorities)){
                    foreach ( $priorities as $priority ) :
                        $selected = $priority_id == $priority->id ? 'selected="selected"' : '';
                        echo '<option '.esc_attr($selected).' value="'.esc_attr($priority->id).'">'.esc_html($priority->name).'</option>';
                    endforeach;
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row" style="padding-left:2px;">
        <div class="col-sm-12">
            <label class="wppm_edit_task_description_label" id="wppm_edit_task_description_label">
                <?php echo esc_html_e('Description','taskbuilder');?>
            </label>
        </div>
    </div>
    <div class="row" style="padding-left:2px;">
        <div class="col-sm-12">
            <textarea id="wppm_edit_task_description" class="form-control" name="wppm_edit_task_description">
                <?php echo isset($task_data['description']) ? stripslashes(htmlspecialchars_decode(esc_textarea($task_data['description']),ENT_QUOTES)) :""?>
            </textarea>
        </div>
    </div>
    <?php do_action('wppm_edit_task_details',$task_id,$project_id);?>
    <input type="hidden" name="action" value="wppm_set_change_task_details" />
    <input type="hidden" name="user_id" id="user_id" value="">
    <input type="hidden" name="task_id" value="<?php echo esc_attr($task_id) ?>" />
</form>
<script>
jQuery( document ).ready( function( jQuery ) {
    wppm_select_project();
    jQuery("#wppm_edit_task_start_date").flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i",
    });
    jQuery("#wppm_edit_task_end_date").flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i"
        //minDate: startdate
    });
    tinymce.remove();
    tinymce.init({ 
        selector:'#wppm_edit_task_description',
        body_id: 'wppm_edit_task_description',
        directionality : '<?php //echo 'rtl'; ?>',
        menubar: false,
        statusbar: false,
        height : '200',
        width  : '100%', 
        plugins: [
            'lists link image directionality paste'
        ],
        image_advtab: true,
        toolbar: 'bold italic underline blockquote | alignleft aligncenter alignright | bullist numlist | rtl | link image',
        branding: false,
        autoresize_bottom_margin: 20,
        browser_spellcheck : true,
        relative_urls : false,
        remove_script_host : false,
        convert_urls : true,
        paste_as_text: true,
        setup: function (editor) {
        }
    });
});
jQuery('#wppm_task_project').select2();
function wppm_select_project(){
  jQuery('.wppm_filter_display_element').hide();
  <?php do_action('wppm_display_cf_by_project_in_edit_task_details') ?>
}
jQuery('#wppm_edit_project_label').select2();

</script>
<?php
$body = ob_get_clean();

ob_start();
?>
<button type="button" class="btn wppm_popup_close" onclick="wppm_modal_close();"><?php echo esc_html_e('Close','taskbuilder');?></button>
<button type="button" class="btn wppm_popup_action" onclick="wppm_set_change_task_details(<?php echo esc_attr($task_id)?>,<?php echo esc_attr($proj_id) ?>);"><?php echo esc_html_e('Save','taskbuilder');?></button>
<?php
$footer = ob_get_clean();

$output = array(
  'body'   => $body,
  'footer' => $footer
);
echo json_encode($output);
?>
