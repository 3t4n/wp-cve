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
      $light_box_display_settings_nonce = wp_create_nonce("light_box_display_settings_nonce");
      include WP_LIGHTBOX_BANK_BK_PLUGIN_DIR . "lib/get-wp-lightbox-bank-setting.php";
      ?>
      <form id="frm_wplb_display_settings" class="layout-form wplb-page-width" method="post">
         <div class="fluid-layout">
            <div class="layout-span12 responsive">
               <div class="widget-layout">
                  <div class="widget-layout-title">
                     <h4>
                        <?php _e("Wp Lightbox Bank Display Settings", wp_lightbox_bank); ?>
                     </h4>
                  </div>
                  <div class="widget-layout-body">
                     <div id="update_settings_message" class="message green"
                          style="display: none;">
                        <span> <strong><?php _e("Success! Settings has been updated.", wp_lightbox_bank); ?></strong>
                        </span>
                     </div>
                     <div class="fluid-layout">
                        <div class="layout-span12 responsive">
                           <div class="layout-control-group custom-layout-control-group">
                              <label class="layout-control-label custom-label-width-lightbox"><?php _e("Display Image Titles", wp_lightbox_bank); ?> : <span
                                    class="error">*</span> </label>
                              <div class="layout-controls custom-layout-controls-lightbox wplb-custom-controls-checkbox">
                                 <input type="checkbox" id="ux_chk_imagetitle"
                                        name="ux_image_title"
                                        <?php echo $image_title == "true" ? "checked" : ""; ?> /><label class="wplb-chk-label"><?php _e("Yes, Enable Titles to be displayed on Images using WP Lightbox Bank .", wp_lightbox_bank); ?>  </label>
                              </div>
                           </div>
                           <div class="layout-control-group custom-layout-control-group">
                              <label class="layout-control-label custom-label-width-lightbox"><?php _e("Display Image Captions", wp_lightbox_bank); ?> : <span
                                    class="error">*</span></label>
                              <div class="layout-controls custom-layout-controls-lightbox wplb-custom-controls-checkbox">
                                 <input type="checkbox" id="ux_chk_image_caption"
                                        name="ux_chk_image_caption"
                                        <?php echo $image_caption == "true" ? "checked" : ""; ?> /><label class="wplb-chk-label"><?php _e("Yes, Enable Captions to be displayed on Images using WP Lightbox Bank .", wp_lightbox_bank); ?>  </label>
                              </div>
                           </div>
                           <div class="layout-control-group custom-layout-control-group">
                              <label class="layout-control-label custom-label-width-lightbox"><?php _e("Text Alignment", wp_lightbox_bank); ?> : <span
                                    class="error">*</span> </label>
                              <div class="layout-controls custom-layout-controls-lightbox">
                                 <select id="ux_text_align" class="layout-span4" name="ux_text_align">
                                    <option value="center">Center</option>
                                    <option value="inherit">Inherit</option>
                                    <option value="justify">Justify</option>
                                    <option value="left">Left</option>
                                    <option value="right">Right</option>
                                 </select>
                              </div>
                           </div>
                           <div class="layout-control-group custom-layout-control-group">
                              <div class="layout-controls custom-layout-controls-lightbox">
                                 <input type="submit" class="btn btn-success"
                                        value="<?php _e("Update Changes", wp_lightbox_bank); ?>" />
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </form>
      <script type="text/javascript">
         jQuery(document).ready(function ()
         {
            jQuery("#ux_text_align").val("<?php echo $text_align; ?>");
         });
         jQuery("#frm_wplb_display_settings").validate
                 ({
                    submitHandler: function (form)
                    {
                       jQuery("body,html").animate({
                          scrollTop: jQuery("body,html").position().top}, "slow");
                       jQuery("body").css("opacity", ".5");
                       jQuery("#update_settings_message").css("display", "block");
                       var overlay = jQuery("<div class=\"lightbox_bank_processing\"></div>");
                       jQuery("body").append(overlay);
                       jQuery.post(ajaxurl, jQuery(form).serialize() + "&param=update_display_settings&action=lightbox_settings_library&_wp_nonce=<?php echo $light_box_display_settings_nonce; ?>", function ()
                       {
                          setTimeout(function () {
                             jQuery("#update_settings_message").css("display", "none");
                             jQuery(".lightbox_bank_processing").remove();
                             jQuery("body").css("opacity", "1");
                             window.location.href = "admin.php?page=wplb_display_settings";
                          }, 2000);

                       });
                    }
                 });
      </script>
      <?php
   }
}