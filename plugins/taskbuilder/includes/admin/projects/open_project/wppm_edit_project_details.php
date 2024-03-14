<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $wpdb,$wppmfunction,$current_user;
$id  = isset($_POST['id']) ? sanitize_text_field($_POST['id']) : '' ;
if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_project_permission('change_project_details',$id))) {exit;}
$project_data = $wppmfunction->get_project($id);
$category_id = sanitize_text_field($project_data['cat_id']);
$project = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wppm_project where id = $id" );
$categories = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wppm_project_categories");
$wppm_project_time = get_option('wppm_project_time');
ob_start();
?>
<form id="frm_get_edit_project_deatils" method="post">
    <div class="row" style="padding-left:2px;">
		<div class="col-sm-12">
    	    <label class="wppm_edit_project_label" for="wppm_edit_project_label"><?php echo esc_html_e('Project','taskbuilder') ?> </label>
	    </div>
    </div>
    <div class="row" style="padding-left:2px;">
		<div class="col-sm-12">
    	    <input type="text" class="wppm_edit_project_label form-control" name="wppm_edit_project_label" id="wppm_edit_project_label" value="<?php echo esc_attr($project_data['project_name']) ?>">
	    </div>
    </div>
    <div class="row" style="padding-left:2px;">
		<div class="col-sm-12">
    	    <label class="wppm_edit_project_start_date" for="wppm_edit_project_start_date"><?php echo esc_html_e('Start Date','taskbuilder') ?> </label>
	    </div>
    </div>
    <div class="row" style="padding-left:2px;">
        <div class="col-sm-12">
            <input name="wppm_edit_project_start_date" class="form-control" id="wppm_edit_project_start_date" type="text" value="<?php echo esc_attr($project_data['start_date']) ?>" size="40" aria-required="true"><br>
        </div>
    </div>
    <div class="row" style="padding-left:2px;">
		<div class="col-sm-12">
    	    <label class="wppm_edit_project_end_date" for="wppm_edit_project_end_date"><?php echo esc_html_e('End Date','taskbuilder') ?> </label>
	    </div>
    </div>
    <div class="row" style="padding-left:2px;">
        <div class="col-sm-12">
            <input name="wppm_edit_project_end_date" class="form-control" id="wppm_edit_project_end_date" type="text" value="<?php echo esc_attr($project_data['end_date']) ?>" size="40" aria-required="true"><br>
        </div>
    </div>
    <div class="row" style="padding-left:2px;">
		<div class="col-sm-12">
            <label class="wppm_edit_project_category_label" for="wppm_edit_project_category_label" id="wppm_edit_project_category_label"><?php esc_html_e('Project Category','taskbuilder');?></label>
	    </div>
    </div>
    <div class="row" style="padding-left:2px;">
        <div class="col-sm-12">              
            <select class="form-control" name="wppm_edit_project_category" id="wppm_edit_project_category">
                <option></option>
                <?php
                if(!empty($categories)){
                    foreach ( $categories as $category ) :
                        $selected = $category_id == $category->id ? 'selected="selected"' : '';
                        echo '<option '.esc_attr($selected).' value="'.esc_attr($category->id).'">'.esc_html($category->name).'</option>';
                    endforeach;
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row" style="padding-left:2px;">
        <div class="col-sm-12">
            <label class="wppm_edit_project_description_label" id="wppm_edit_project_description_label">
                <?php esc_html_e('Description','taskbuilder');?>
            </label>
        </div>
    </div>
    <div class="row" style="padding-left:2px;">
        <div class="col-sm-12">
            <textarea id="wppm_edit_project_description" class="form-control" name="wppm_edit_project_description">
                <?php echo stripslashes(htmlspecialchars_decode(esc_textarea($project_data['description']),ENT_QUOTES)); ?>
            </textarea>
        </div>
    </div>
    
    <input type="hidden" name="action" value="wppm_set_change_project_details" />
    <input type="hidden" name="project_id" value="<?php echo htmlentities(esc_attr($id)) ?>" />
</form>
<script>
jQuery( document ).ready( function( jQuery ) {

    jQuery("#wppm_edit_project_start_date").flatpickr({
        enableTime: <?php echo ($wppm_project_time == 1) ? 'true' : 'false' ?>,
        dateFormat: "Y-m-d <?php echo ($wppm_project_time == 1) ? 'H:i': '' ?>",
    });
    jQuery("#wppm_edit_project_end_date").flatpickr({
        enableTime: <?php echo ($wppm_project_time==1) ? 'true' : 'false' ?>,
        dateFormat: "Y-m-d <?php echo ($wppm_project_time==1) ? 'H:i': '' ?>"
        //minDate: startdate
    });
    tinymce.remove();
    tinymce.init({ 
        selector:'#wppm_edit_project_description',
        body_id: 'wppm_edit_project_description',
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
</script>
<?php
$body = ob_get_clean();

ob_start();
?>
<button type="button" class="btn wppm_popup_close" onclick="wppm_modal_close();"><?php echo esc_html_e('Close','taskbuilder');?></button>
<button type="button" class="btn wppm_popup_action" onclick="wppm_set_change_project_details(<?php echo htmlentities(esc_attr($id))?>);"><?php echo esc_html_e('Save','taskbuilder');?></button>
<?php
$footer = ob_get_clean();

$output = array(
  'body'   => $body,
  'footer' => $footer
);
echo json_encode($output);
?>
