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
      $update_lightbox_settings_nonce = wp_create_nonce("update_lightbox_settings_nonce");
      $restore_lightbox_settings_nonce = wp_create_nonce("restore_lightbox_settings_nonce");
      $topbar_lightbox_settings_nonce = wp_create_nonce("topbar_lightbox_settings_nonce");
      include WP_LIGHTBOX_BANK_BK_PLUGIN_DIR . "lib/get-wp-lightbox-bank-setting.php";
      ?>
      <form id="frm_wp_lightbox" class="layout-form wplb-page-width" method="post">
         <div class="fluid-layout">
            <div class="layout-span12 responsive">
               <div class="widget-layout">
                  <div class="widget-layout-title">
                     <h4>
                        <?php _e("Wp Lightbox Bank General Settings", wp_lightbox_bank); ?>
                     </h4>
                  </div>
                  <div class="widget-layout-body">
                     <div id="update_settings_message" class="message green"
                          style="display: none;">
                        <span> <strong><?php _e("Success! Settings has been updated.", wp_lightbox_bank); ?></strong>
                        </span>
                     </div>
                     <div id="restore_lightbox_message" class="message green"
                          style="display: none;">
                        <span> <strong><?php _e("Success! Settings has been restored.", wp_lightbox_bank); ?></strong>
                        </span>
                     </div>
                     <div class="fluid-layout">
                        <div class="layout-span12 responsive">
                           <div class="layout-control-group custom-layout-control-group">
                              <label class="layout-control-label custom-label-width-lightbox"><?php _e("WordPress Galleries", wp_lightbox_bank); ?> : <span
                                    class="error">*</span></label>
                              <div class="layout-controls custom-layout-controls-lightbox wplb-custom-controls-checkbox">
                                 <input type="checkbox" id="ux_chk_galleries"
                                        name="ux_chk_galleries"
                                        <?php echo $wp_galleries == "1" ? "checked" : ""; ?> /><label class="wplb-chk-label"><?php _e("Yes, Enable WP Lightbox Bank for all WordPress image galleries.", wp_lightbox_bank); ?>  </label>
                              </div>
                           </div>
                           <div class="layout-control-group custom-layout-control-group">
                              <label class="layout-control-label custom-label-width-lightbox"><?php _e("WordPress Image With Captions", wp_lightbox_bank); ?> : <span
                                    class="error">*</span></label>
                              <div class="layout-controls custom-layout-controls-lightbox wplb-custom-controls-checkbox">
                                 <input type="checkbox" id="ux_chk_imagecaption" value="1"
                                        name="ux_chk_imagecaption"
                                        <?php echo $wp_caption_image == "1" ? "checked" : ""; ?> /><label class="wplb-chk-label"><?php _e("Yes, Enable WP Lightbox Bank for all WordPress images that have captions .", wp_lightbox_bank); ?>  </label>
                              </div>
                           </div>
                           <div class="layout-control-group custom-layout-control-group">
                              <label class="layout-control-label custom-label-width-lightbox"><?php _e("Attachment Images", wp_lightbox_bank); ?> : <span
                                    class="error">*</span></label>
                              <div class="layout-controls custom-layout-controls-lightbox wplb-custom-controls-checkbox">
                                 <input type="checkbox" id="ux_chk_attachmentimage"
                                        name="ux_chk_attachmentimage"
                                        <?php echo $attachment_image == "1" ? "checked" : ""; ?> /><label class="wplb-chk-label"><?php _e("Yes, Enable WP Lightbox Bank for all media images included in posts or pages .", wp_lightbox_bank); ?>  </label>
                              </div>
                           </div>
                           <div class="layout-control-group custom-layout-control-group">
                              <label class="layout-control-label custom-label-width-lightbox"><?php _e("Close on Overlay Click", wp_lightbox_bank); ?> : <span
                                    class="error">*</span></label>
                              <div class="layout-controls custom-layout-controls-lightbox wplb-custom-controls-checkbox">
                                 <input type="checkbox" id="ux_chk_overlayclick"
                                        name="ux_chk_overlayclick"
                                        <?php echo $overlay_click == "true" ? "checked" : ""; ?> /><label class="wplb-chk-label"><?php _e("Yes, Close WP Lightbox Bank when the modal overlay is clicked .", wp_lightbox_bank); ?>  </label>
                              </div>
                           </div>
                           <div class="layout-control-group custom-layout-control-group">
                              <label class="layout-control-label custom-label-width-lightbox"><?php _e("Error Message", wp_lightbox_bank); ?> : <span
                                    class="error">*</span> </label>
                              <div class="layout-controls custom-layout-controls-lightbox">
                                 <input type="text" class="layout-span10" id="ux_cb_errormsg"
                                        name="ux_cb_errormsg" value="<?php echo $error_message; ?>" />
                              </div>
                              <div class="layout-controls custom-layout-controls-lightbox">
                                 <p class="wplb-desc-italic"><?php _e(" The error message to be shown when image can not be loaded .", wp_lightbox_bank); ?> </p>
                              </div>
                           </div>
                           <div class="layout-control-group custom-layout-control-group">
                              <label class="layout-control-label custom-label-width-lightbox"><?php _e("Language Direction", wp_lightbox_bank); ?> : <span
                                    class="error">*</span> </label>
                              <div class="layout-controls rdl_lightbox">
                                 <?php
                                 if (esc_attr($language_direction) == "rtl") {
                                    ?>
                                    <input type="radio"
                                           id="ux_rdl_disablelanguage" name="ux_rdl_enablelanguage"
                                           value="ltr" /> <?php _e("Left to Right", wp_lightbox_bank); ?>
                                    <input type="radio" id="ux_rdl_enablelanguage" style="margin-left: 10px;"
                                           name="ux_rdl_enablelanguage" checked="checked" value="rtl" /> <?php _e("Right to Left", wp_lightbox_bank); ?>
                                           <?php
                                        } else {
                                           ?>
                                    <input type="radio"
                                           id="ux_rdl_disablelanguage" checked="checked"
                                           name="ux_rdl_enablelanguage" value="ltr" /> <?php _e("Left to Right", wp_lightbox_bank); ?>
                                    <input type="radio" id="ux_rdl_enablelanguage" style="margin-left: 10px;"
                                           name="ux_rdl_enablelanguage" value="rtl" /> <?php _e("Right to Left", wp_lightbox_bank); ?>
                                           <?php
                                        }
                                        ?>
                              </div>
                           </div>
                           <div class="layout-control-group custom-layout-control-group">
                              <label class="layout-control-label custom-label-width-lightbox"><?php _e("Disable Other Lightboxes", wp_lightbox_bank); ?>: <span
                                    class="error">*</span></label>
                              <div class="layout-controls wplb-custom-controls-checkbox custom-layout-controls-lightbox">
                                 <input type="checkbox" id="ux_chk_disablelightbox"
                                        name="ux_chk_disablelightbox"
                                        <?php echo $disable_other_lightbox == "true" ? "checked=\"checked\"" : ""; ?> /><label class="wplb-chk-label"><?php _e("Certain themes and plugins use a hard-coded lightbox, which make it very difficult to override.<br>By enabling the setting, we inject a small amount of javascript onto the page which attempts to get around this issue but note this is not guaranteed, as we cannot account for every lightbox solution out there.", wp_lightbox_bank); ?>  </label>
                              </div>
                           </div>
                           <div class="layout-control-group custom-layout-control-group">
                              <label class="layout-control-label custom-label-width-lightbox"><?php _e("Show Top Bar Menu", wp_lightbox_bank); ?> :<span
                                    class="error">*</span></label>
                              <div class="layout-controls wplb-custom-controls-checkbox custom-layout-controls-lightbox">
                                 <?php $show_top_bar_menu = get_option("lightbox-bank-top-bar-menu"); ?>
                                 <input type="checkbox" id="ux_chk_top_bar" name="ux_chk_top_bar" onclick="wp_lightbox_top_bar_settings(this);" <?php echo $show_top_bar_menu == 1 ? "checked=\"checked\"" : ""; ?> value="1" />
                              </div>
                           </div>
                           <div class="layout-control-group custom-layout-control-group">
                              <div class="layout-controls custom-layout-controls-lightbox">
                                 <input type="submit" class="btn btn-success"
                                        value="<?php _e("Update Changes", wp_lightbox_bank); ?>" /> <input
                                        type="button" class="btn btn-danger"
                                        onclick="restore_lightbox();"
                                        value="<?php _e("Restore Settings", wp_lightbox_bank); ?>" />
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
         jQuery("#frm_wp_lightbox").validate
                 ({
                    rules:
                            {
                               ux_cb_errormsg:
                                       {
                                          required: true
                                       }
                            },
                    errorPlacement: function (error, element)
                    {
                       jQuery(element).css("background-color", "#FFCCCC");
                       jQuery(element).css("border", "1px solid red");
                    },
                    submitHandler: function (form)
                    {
                       jQuery("body,html").animate({
                          scrollTop: jQuery("body,html").position().top}, "slow");
                       jQuery("#update_settings_message").css("display", "block");
                       jQuery("body").css("opacity", ".5");
                       var overlay = jQuery("<div class=\"lightbox_bank_processing\"></div>");
                       jQuery("body").append(overlay);
                       jQuery.post(ajaxurl, jQuery(form).serialize() + "&param=update_lightbox_settings&action=lightbox_settings_library&_wp_nonce=<?php echo $update_lightbox_settings_nonce; ?>", function (data)
                       {
                          setTimeout(function ()
                          {
                             jQuery("#update_settings_message").css("display", "none");
                             jQuery(".lightbox_bank_processing").remove();
                             jQuery("body").css("opacity", "1");
                             window.location.href = "admin.php?page=wp_lightbox_bank";
                          }, 2000);

                       });
                    }
                 });
         function restore_lightbox()
         {
            var r = confirm("<?php _e("Are you sure you want to restore settings?", wp_lightbox_bank); ?>");
            if (r == true) {
               jQuery("#restore_lightbox_message").css("display", "block");
               jQuery("body").css("opacity", ".5");
               var overlay = jQuery("<div class=\"lightbox_bank_processing\"></div>");
               jQuery("body").append(overlay);
               jQuery.post(ajaxurl, "&param=restore_settings&action=lightbox_settings_library&_wp_nonce=<?php echo $restore_lightbox_settings_nonce; ?>", function (data) {
                  setTimeout(function () {
                     jQuery("#restore_lightbox_message").css("display", "none");
                     jQuery(".lightbox_bank_processing").remove();
                     jQuery("body").css("opacity", "1");
                     window.location.href = "admin.php?page=wp_lightbox_bank";
                  }, 2000);
               });
            }
         }

         function wp_lightbox_top_bar_settings(control)
         {
            var top_bar_menu = jQuery(control).prop("checked");
            var show_topbar_menu = (top_bar_menu == true ? 1 : 0);
            jQuery.post(ajaxurl, "show_topbar_menu=" + show_topbar_menu + "&param=lightbox_bank_topbar_settings&action=lightbox_settings_library&_wp_nonce=<?php echo $topbar_lightbox_settings_nonce; ?>", function ()
            {
            });
         }
      </script>
      <?php
   }
}