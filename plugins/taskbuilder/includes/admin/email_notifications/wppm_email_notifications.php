<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

global $wpdb,$wppmfunction;
?>
<div class="wppm_bootstrap">
  <span class="wppm-heading-inline wppm_email_notification_header">
    <?php echo esc_html_e('Email Notifications','taskbuilder');?>
  </span>
  <div class="wppm_padding_space"></div>
  <div class="row" style="margin-bottom:20px;">
    <div class="col-sm-4 wppm_setting_col1">
      <ul class="nav nav-pills nav-stacked wppm_setting_pills">
        <li id="wppm_en_setting_general" role="presentation" class="active"><a href="javascript:wppm_get_en_general_setting();"><?php echo esc_html_e('General Settings','taskbuilder');?></a></li>
        <li id="wppm_en_ticket_notifications" role="presentation"><a href="javascript:wppm_get_en_task_notifications();"><?php echo esc_html_e('Project And Task Notifications','taskbuilder');?></a></li>
        <?php do_action('wppm_after_en_setting_pills');?>
      </ul>
    </div>
    <div class="col-sm-8 wppm_setting_col2"></div>
  </div>
  <div id="wppm_alert_success" class="alert alert-success wppm_alert" style="display:none;" role="alert">
    <img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/success.svg'); ?>" alt="success"><span class="wppm_alert_text"></span>
  </div>
  <div id="wppm_alert_error" class="alert alert-danger wppm_alert" style="display:none;" role="alert">
    <img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/warning-triangle.svg'); ?>" alt="warning-triangle"><span class="wppm_alert_text"></span>
  </div>

  <!-- Pop-up snippet start -->
  <div id="wppm_popup_background" style="display:none;"></div>
  <div id="wppm_popup_container" style="display:none;">
  <div class="wppm_bootstrap">
    <div class="row">
      <div id="wppm_popup" class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
        <div id="wppm_popup_title" class="row"><h3><?php echo esc_html_e('Modal Title','taskbuilder');?></h3></div>
        <div id="wppm_popup_body" class="row"><?php echo esc_html_e('I am body!','taskbuilder');?></div>
        <div id="wppm_popup_footer" class="row">
          <button type="button" class="btn wppm_popup_close"><?php echo esc_html_e('Close','taskbuilder');?></button>
          <button type="button" class="btn wppm_popup_action"><?php echo esc_html_e('Save Changes','taskbuilder');?></button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Pop-up snippet end -->
<script>
    jQuery(document).ready(function(){
      wppm_get_en_general_setting();
    });
</script>