<?php
if (!defined("ABSPATH")) {
   exit;
}//exit if accessed directly
if (!is_user_logged_in()) {
   return;
} else {
   switch ($role) {
      case "administrator":
         $user_role_permission = "manage_options";
         break;
      case "editor":
         $user_role_permission = "publish_pages";
         break;
      case "author":
         $user_role_permission = "publish_posts";
         break;
   }

   if (!current_user_can($user_role_permission)) {
      return;
   } else {
      $lighbox_other_settings_nonce = wp_create_nonce("lighbox_other_settings_nonce");
      ?>
      <form id="frm_wplb_other_settings" class="layout-form wplb-page-width" method="post">
         <div class="fluid-layout">
            <div class="layout-span12 responsive">
               <div class="widget-layout">
                  <div class="widget-layout-title">
                     <h4>
                        <?php _e("Wp Lightbox Bank Other Settings", wp_lightbox_bank); ?>
                     </h4>
                  </div>
                  <div class="widget-layout-body">
                     <div class="fluid-layout">
                        <div class="layout-span12 responsive">
                           <div class="layout-control-group" style="margin: 10px 0 0 0 ;">
                              <label class="layout-control-label"><?php _e("Remove Tables at Uninstall", wp_lightbox_bank); ?> : </label>
                              <div class="layout-controls-radio">
                                 <?php $other_settings = get_option("lightbox-remove-tables-uninstall"); ?>
                                 <input type="radio" name="ux_lightbox_update" id="ux_enable_update" onclick="wp_lightbox_bank_other_settings(this);" <?php echo $other_settings == "1" ? "checked=\"checked\"" : ""; ?> value="1"><label style="vertical-align: baseline;"><?php _e("Enable", wp_lightbox_bank); ?></label>
                                 <input type="radio" name="ux_lightbox_update" id="ux_disable_update" onclick="wp_lightbox_bank_other_settings(this);" <?php echo $other_settings == "0" ? "checked=\"checked\"" : ""; ?> style="margin-left: 10px;" value="0"><label style="vertical-align: baseline;"><?php _e("Disable", wp_lightbox_bank); ?></label>
                              </div>
                           </div>
                           <div class="layout-control-group" style="margin:10px 0 10px 0 ;">
                              <i>If you would like to remove tables during uninstallation of plugin then you would need to choose enable or vice versa.<br/></i>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </form>
      <script type="text/javascript">
         function wp_lightbox_bank_other_settings(control)
         {
            var lightbox_bank_updates = jQuery(control).val();
            jQuery.post(ajaxurl, "lightbox_bank_updates=" + lightbox_bank_updates + "&param=lightbox_bank_other_settings&action=lightbox_settings_library&_wp_nonce=<?php echo $lighbox_other_settings_nonce; ?>", function ()
            {
            });
         }

      </script>
      <?php
   }
}