<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
?>
<div class="wppm_bootstrap">
  <div id="wppm_project_container">
  </div>
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
            <button type="button" class="btn wppm_popup_close" ><?php echo esc_html_e('Close','taskbuilder');?></button>
            <button type="button" class="btn wppm_popup_action"><?php echo esc_html_e('Save Changes','taskbuilder');?></button>
          </div>
        </div>
      </div>
    </div>
<!-- Pop-up snippet end -->
</div>
<script type="text/javascript">
  jQuery( document ).ready( function( jQuery ) {
     wppm_get_project_list();
  })
</script>