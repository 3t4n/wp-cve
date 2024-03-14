<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

$is_addons = apply_filters( 'wppm_is_add_on_installed', false );
if($is_addons) {
  $license_messege = __('Enter your add-ons license keys here to receive updates for purchased add-ons. If your license key has expired, please renew your license.','taskbuilder');
} else {
  $license_messege = '<h4>'.sprintf(__('No add-ons installed. See available add-ons - %1$s.','taskbuilder'),'<a href="https://taskbuilder.net/add-ons/" target="_blank">https://taskbuilder.net/add-ons/</a>').'</h4>';
}
?>
<div class="wppm_bootstrap">
  
  <h3>
    <?php echo esc_html_e('License','taskbuilder');?>
  </h3>
  <div class="wppm_padding_space"></div>
  <div class="row" style="margin-bottom:20px;"><?php echo html_entity_decode($license_messege)?></div>
  <div class="row"><?php do_action('wppm_addon_license_area')?></div>
  
  <div id="wppm_alert_success" class="alert alert-success wppm_alert" style="display:none;" role="alert">
    <i class="fa fa-check-circle"></i> <span class="wppm_alert_text"></span>
  </div>
  
  <div id="wppm_alert_error" class="alert alert-danger wppm_alert" style="display:none;" role="alert">
    <i class="fa fa-exclamation-triangle"></i> <span class="wppm_alert_text"></span>
  </div>
  
</div>

<!-- Pop-up snippet start -->
<div id="wppm_popup_background" style="display:none;"></div>
<div id="wppm_popup_container" style="display:none;">
  <div class="wppm_bootstrap">
    <div class="row">
      <div id="wppm_popup" class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
        <div id="wppm_popup_title" class="row"><h3><?php echo esc_html_e('Modal Title','taskbuilder');?></h3></h3></div>
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

<?php
add_action('admin_footer', 'wppm_page_inline_script');
function wppm_page_inline_script(){
  ?>
  <script>
    jQuery(document).ready(function(){
      wppm_get_general_settings();
    });
  </script>
  <?php
}
?>