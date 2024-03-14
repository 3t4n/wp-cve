<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user,$wpdb,$wppmfunction;
$wppm_public_projects_permission = get_option('wppm_public_projects_permission');
?>
<form id="wppm_frm_advanced_settings" method="post" action="javascript:wppm_set_advanced_settings();">
    <div class="wppm-help-container">
      <a href="https://taskbuilder.net/help/" target="_blank"><?php echo esc_attr__( 'Click here', 'taskbuilder' )?></a> <?php echo esc_attr__( 'to see the documentation!', 'taskbuilder' )?>
    </div>
    <span>
      <label><?php echo esc_html_e('Public Projects','taskbuilder');?></label>
    </span><br>
    <p class="help-block"><?php echo esc_html_e('Default enable/disable permission for create public project.','taskbuilder');?></p>
    <select class="form-control" name="wppm_public_projects_permission" id="wppm_public_projects_permission">
				<?php
				$selected = $wppm_public_projects_permission == '1' ? 'selected="selected"' : '';
				echo '<option '.$selected.' value="1">'.__('Enable','taskbuilder').'</option>';
				$selected = $wppm_public_projects_permission == '0' ? 'selected="selected"' : '';
				echo '<option '.$selected.' value="0">'.__('Disable','taskbuilder').'</option>';
				?>
    </select>
    <hr>
    <button type="submit" class="wppm-submit-btn"><?php echo esc_html_e('Save Changes','taskbuilder');?></button>
    <span class="wppm_submit_wait" style="display:none;"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/loading_buffer.svg'); ?>" alt="edit"></span>  
    <input type="hidden" name="action" value="wppm_set_advanced_settings" />
</form>