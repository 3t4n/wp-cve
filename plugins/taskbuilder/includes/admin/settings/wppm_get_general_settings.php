<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user,$wpdb,$wppmfunction;
$wppm_default_task_list_view = get_option('wppm_default_task_list_view');
$wppm_default_project_date = get_option('wppm_default_project_date');
$wppm_default_task_date = get_option('wppm_default_task_date');
$wppm_project_time = get_option('wppm_project_time');
$wppm_task_time = get_option('wppm_task_time');
$wppm_edit_tasks_permission = get_option('wppm_default_edit_tasks_permission');
?>
<form id="wppm_frm_general_settings" method="post" action="javascript:wppm_set_general_settings();">
    <div class="wppm-help-container">
      <a href="https://taskbuilder.net/docs/general-setting/" target="_blank"><?php echo esc_attr__( 'Click here', 'taskbuilder' )?></a> <?php echo esc_attr__( 'to see the documentation!', 'taskbuilder' )?>
    </div>
    <span>
      <label><?php echo esc_html_e('Task List View','taskbuilder');?></label>
    </span><br>
    <p class="help-block"><?php echo esc_html_e('This selected view get applied on task list table','taskbuilder');?></p>
    <input type="radio" name="wppm_task_list_view" style="margin-top: 0px;" value="1" <?php echo ((esc_attr($wppm_default_task_list_view))==1) ?'checked="checked"':'';?>>
    <span style="padding-left: 10px;"><?php echo esc_html_e('List View','taskbuilder');?></span>
    <br>
    <input type="radio" name="wppm_task_list_view" value="0" <?php echo ((esc_attr($wppm_default_task_list_view))==0)?'checked="checked"':'';?>>
    <span style="padding-left: 10px;"><?php echo esc_html_e('Card View','taskbuilder');?></span>
    <hr>
    <span>
      <label><?php echo esc_html_e('Time in project start date and end date','taskbuilder');?></label>
    </span><br>
    <p class="help-block"><?php echo esc_html_e('Default show/hide time in start and end date of project.','taskbuilder');?></p>
    <select class="form-control" name="wppm_project_time" id="wppm_project_time">
				<?php
				$selected = $wppm_project_time == '1' ? 'selected="selected"' : '';
				echo '<option '.$selected.' value="1">'.__('Show','taskbuilder').'</option>';
				$selected = $wppm_project_time == '0' ? 'selected="selected"' : '';
				echo '<option '.$selected.' value="0">'.__('Hide','taskbuilder').'</option>';
				?>
    </select>
    <hr>
    <span>
      <label><?php echo esc_html_e('Time in task start date and end date','taskbuilder');?></label>
    </span><br>
    <p class="help-block"><?php echo esc_html_e('Default show/hide time in start and end date of task.','taskbuilder');?></p>
    <select class="form-control" name="wppm_task_time" id="wppm_task_time">
				<?php
				$selected = $wppm_task_time == '1' ? 'selected="selected"' : '';
				echo '<option '.$selected.' value="1">'.__('Show','taskbuilder').'</option>';
				$selected = $wppm_task_time == '0' ? 'selected="selected"' : '';
				echo '<option '.$selected.' value="0">'.__('Hide','taskbuilder').'</option>';
				?>
    </select>
    <hr>
    <span>
      <label><?php echo esc_html_e('Project start date and end date','taskbuilder');?></label>
    </span><br>
    <p class="help-block"><?php echo esc_html_e('Default show/hide start date and end date of project.','taskbuilder');?></p>
    <input type="radio" name="wppm_default_project_date" style="margin-top: 0px;" value="1" <?php echo ((esc_attr($wppm_default_project_date))==1) ?'checked="checked"':'';?>>
    <span style="padding-left: 10px;"><?php echo esc_html_e('Show','taskbuilder');?></span>
    <br>
    <input type="radio" name="wppm_default_project_date" style="margin-top: 0px;" value="0" <?php echo ((esc_attr($wppm_default_project_date))==0) ?'checked="checked"':'';?>>
    <span style="padding-left: 10px;"><?php echo esc_html_e('Hide','taskbuilder');?></span>
    <br>
    <hr>
    <span>
      <label><?php echo esc_html_e('Task start date and end date','taskbuilder');?></label>
    </span><br>
    <p class="help-block"><?php echo esc_html_e('Default show/hide start date and end date of task.','taskbuilder');?></p>
    <input type="radio" name="wppm_default_task_date" style="margin-top: 0px;" value="1" <?php echo ((esc_attr($wppm_default_task_date))==1) ?'checked="checked"':'';?>>
    <span style="padding-left: 10px;"><?php echo esc_html_e('Show','taskbuilder');?></span>
    <br>
    <input type="radio" name="wppm_default_task_date" style="margin-top: 0px;" value="0" <?php echo ((esc_attr($wppm_default_task_date))==0) ?'checked="checked"':'';?>>
    <span style="padding-left: 10px;"><?php echo esc_html_e('Hide','taskbuilder');?></span>
    <br>
    <hr>
    <span>
      <label><?php echo esc_html_e('Allow co-workers to edit tasks','taskbuilder');?></label>
    </span><br>
    <p class="help-block"><?php echo esc_html_e('Default enable/disable permission for co-workers to edit tasks.','taskbuilder');?></p>
    <select class="form-control" name="wppm_edit_tasks_permission" id="wppm_edit_tasks_permission">
				<?php
				$selected = $wppm_edit_tasks_permission == '1' ? 'selected="selected"' : '';
				echo '<option '.$selected.' value="1">'.__('Enable','taskbuilder').'</option>';
				$selected = $wppm_edit_tasks_permission == '0' ? 'selected="selected"' : '';
				echo '<option '.$selected.' value="0">'.__('Disable','taskbuilder').'</option>';
				?>
    </select>
    <hr>
    <button type="submit" class="wppm-submit-btn"><?php echo esc_html_e('Save Changes','taskbuilder');?></button>
    <span class="wppm_submit_wait" style="display:none;"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/loading_buffer.svg'); ?>" alt="edit"></span>  
    <input type="hidden" name="action" value="wppm_set_general_settings" />
</form>