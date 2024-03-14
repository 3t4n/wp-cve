<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
		exit;
} 
ob_start();
?>
<div class="table-responsive">
	<table id="wppm_tbl_templates"   class="table table-striped table-bordered"  cellspacing="5" cellpadding="5">
        <thead>
            <tr>
                <th><?php echo esc_html_e('Tag','taskbuilder')?></th>
                <th><?php echo esc_html_e('Field Name','taskbuilder')?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td id="wppm_tag_project_id_td" class="wppm_tag_project_id_td" onclick="wppm_insert_editor_text('{project_id}')">{<?php echo esc_attr('project_id') ?>}</td>
                <td><?php echo esc_html_e('Project Id','taskbuilder'); ?></td>
            </tr>
            <tr>
                <td id="wppm_tag_project_name_td" class="wppm_tag_project_name_td" onclick="wppm_insert_editor_text('{project_name}')">{<?php echo esc_attr('project_name') ?>}</td>
                <td><?php echo esc_html_e('Project Name','taskbuilder'); ?></td>
            </tr>
            <tr>
                <td id="wppm_tag_project_description_td" class="wppm_tag_project_description_td" onclick="wppm_insert_editor_text('{project_description}')">{<?php echo esc_attr('project_description') ?>}</td>
                <td><?php echo esc_html_e('Project Description','taskbuilder'); ?></td>
            </tr>
            <tr>
                <td id="wppm_tag_project_status_td" class="wppm_tag_project_status_td" onclick="wppm_insert_editor_text('{project_status}')">{<?php echo esc_attr('project_status') ?>}</td>
                <td><?php echo esc_html_e('Project Status','taskbuilder') ?></td>
            </tr>
            <tr>
                <td id="wppm_tag_project_category_td" class="wppm_tag_project_category_td" onclick="wppm_insert_editor_text('{project_category}')">{<?php echo esc_attr('project_category') ?>}</td>
                <td><?php echo esc_html_e('Project Category','taskbuilder') ?></td>
            </tr>
            <tr>
                <td id="wppm_tag_project_start_date" class="wppm_tag_project_start_date" onclick="wppm_insert_editor_text('{project_start_date}')">{<?php echo esc_attr('project_start_date') ?>}</td>
                <td><?php echo esc_html_e('Project Start Date','taskbuilder') ?></td>
            </tr>
            <tr>
                <td id="wppm_tag_project_end_date" class="wppm_tag_project_end_date" onclick="wppm_insert_editor_text('{project_end_date}')">{<?php echo esc_attr('project_end_date') ?>}</td>
                <td><?php echo esc_html_e('Project End Date','taskbuilder') ?></td>
            </tr>
            <tr>
                <td id="wppm_tag_project_assign_user" class="wppm_tag_project_assign_user" onclick="wppm_insert_editor_text('{project_assigned_users}')">{<?php echo esc_attr('project_assigned_users') ?>}</td>
                <td><?php echo esc_html_e('Project Assigned Users','taskbuilder') ?></td>
            </tr>
            <tr>
                <td id="wppm_tag_task_id" class="wppm_tag_task_id" onclick="wppm_insert_editor_text('{task_id}')">{<?php echo esc_attr('task_id') ?>}</td>
                <td><?php echo esc_html_e('Task Id','taskbuilder'); ?></td>
            </tr>
            <tr>
                <td id="wppm_tag_task_name" class="wppm_tag_task_name" onclick="wppm_insert_editor_text('{task_name}')">{<?php echo esc_attr('task_name') ?>}</td>
                <td><?php echo esc_html_e('Task Name','taskbuilder'); ?></td>
            </tr>
            <tr>
                <td id="wppm_tag_task_description" class="wppm_tag_task_description" onclick="wppm_insert_editor_text('{task_description}')">{<?php echo esc_attr('task_description') ?>}</td>
                <td><?php echo esc_html_e('Task Description','taskbuilder'); ?></td>
            </tr>
            <tr>
                <td id="wppm_tag_task_status" class="wppm_tag_task_status" onclick="wppm_insert_editor_text('{task_status}')">{<?php echo esc_attr('task_status') ?>}</td>
                <td><?php echo esc_html_e('Task Status','taskbuilder') ?></td>
            </tr>
            <tr>
                <td id="wppm_tag_task_priority" class="wppm_tag_task_priority" onclick="wppm_insert_editor_text('{task_priority}')">{<?php echo esc_attr('task_priority') ?>}</td>
                <td><?php echo esc_html_e('Task Priority','taskbuilder') ?></td>
            </tr>
            <tr>
                <td id="wppm_tag_task_start_date" class="wppm_tag_task_start_date" onclick="wppm_insert_editor_text('{task_start_date}')">{<?php echo esc_attr('task_start_date') ?>}</td>
                <td><?php echo esc_html_e('Task Start Date','taskbuilder') ?></td>
            </tr>
            <tr>
                <td id="wppm_tag_task_end_date" class="wppm_tag_task_end_date" onclick="wppm_insert_editor_text('{task_end_date}')">{<?php echo esc_attr('task_end_date') ?>}</td>
                <td><?php echo esc_html_e('Task End Date','taskbuilder') ?></td>
            </tr>
            <tr>
                <td id="wppm_tag_assigned_user" class="wppm_tag_assigned_user" onclick="wppm_insert_editor_text('{task_assigned_users}')">{<?php echo esc_attr('task_assigned_users') ?>}</td>
                <td><?php echo esc_html_e('Task Assigned Users','taskbuilder') ?></td>
            </tr>
            <?php 
            do_action('wppm_after_macro_templates'); ?>
        </tbody>
    </table>
</div>
<script>
    function wppm_insert_editor_text(element){
        wppm_modal_close();
        tinymce.activeEditor.execCommand('mceInsertContent', false, element);
    }
</script>
<?php 
$body = ob_get_clean();
ob_start();
?>
<button type="button" class="btn wppm_popup_close"  onclick="wppm_modal_close();"><?php echo esc_html_e('Close','taskbuilder');?></button>
<?php 
$footer = ob_get_clean();

$output = array(
  'body'   => $body,
  'footer' => $footer
);

echo json_encode($output);
?>