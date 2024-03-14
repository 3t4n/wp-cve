<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
} 
global $current_user,$wpdb,$wppmfunction;
$project_id  = isset($_POST['id']) ? sanitize_text_field($_POST['id']) : '' ;
$public_project=$wppmfunction->get_project_meta($project_id,'public_project',true);
if (!(($current_user->ID && $current_user->has_cap('manage_options')))) {exit;}
?>
<div class="row">
    <div class="col-sm-12">
        <span>
        <label><?php echo esc_html_e('Change Project visibility','taskbuilder');?></label>
        </span><br>
        <input type="checkbox" name="wppm_project_visibility_option" style="margin-top: 0px;" value="0" <?php echo ((esc_attr($public_project))==0 || (esc_attr($public_project)=="")) ?'checked="checked"':'';?> onchange="wppm_select_project_visibility(<?php echo esc_attr($project_id) ?>)">
        <span style="padding-left: 10px;"><?php echo esc_html_e('Private','taskbuilder');?></span>
        <br>
        <input type="checkbox" name="wppm_project_visibility_option" style="margin-top: 0px;" value="1" <?php echo ((esc_attr($public_project))==1) ?'checked="checked"':'';?> onchange="wppm_select_project_visibility(<?php echo esc_attr($project_id) ?>)">
        <span style="padding-left: 10px;"><?php echo esc_html_e('Public','taskbuilder');?></span>
        <br>
    </div>
</div>
<script>
jQuery(document).ready(function(){
  jQuery('input:checkbox').click(function() {
    jQuery('input:checkbox').not(this).prop('checked', false);
  });
});
<?php
$body = ob_get_clean();
ob_start();
?>
<button type="button" class="btn wppm_popup_close"  onclick="wppm_modal_close();"><?php echo esc_html_e('Close','taskbuilder');?></button>
<?php

$footer = ob_get_clean();

$response = array(
   'body'      => $body,
   'footer'    => $footer
);

echo json_encode($response);