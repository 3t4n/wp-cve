<?php
if (!defined("ABSPATH")) {
   exit;
}//exit if accessed directly
if (!is_user_logged_in()) {
   return;
} else {
   global $wpdb, $current_user;
   switch ($wp_role) {
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

      class update_data_setings {
         function update_data($tbl, $data, $where) {
            global $wpdb;
            $wpdb->update($tbl, $data, $where);
         }
      }
      if (isset($_REQUEST["param"])) {
         switch (esc_attr($_REQUEST["param"])) {
            case "update_lightbox_settings" :
               if (wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "update_lightbox_settings_nonce")) {
                  $update = new update_data_setings();
                  $lightbox_setting_array = array();
                  $setting_value = array();
                  $setting_key = array();
                  $lightbox_setting_array["wp_galleries"] = isset($_REQUEST["ux_chk_galleries"]) ? "1" : "0";
                  $lightbox_setting_array["wp_caption_image"] = isset($_REQUEST["ux_chk_imagecaption"]) ? "1" : "0";
                  $lightbox_setting_array["attachment_image"] = isset($_REQUEST["ux_chk_attachmentimage"]) ? "1" : "0";
                  $lightbox_setting_array["overlay_click"] = isset($_REQUEST["ux_chk_overlayclick"]) ? "true" : "false";
                  $lightbox_setting_array["error_message"] = stripslashes(esc_attr($_REQUEST["ux_cb_errormsg"]));
                  $lightbox_setting_array["show_thumbnail"] = isset($_REQUEST["ux_chk_enable_thumbnail"]) ? "true" : "false";
                  $lightbox_setting_array["lightbox_autoplay"] = isset($_REQUEST["ux_chk_autoplay"]) ? "true" : "false";
                  $lightbox_setting_array["language_direction"] = esc_attr($_REQUEST["ux_rdl_enablelanguage"]);
                  $lightbox_setting_array["disable_other_lightbox"] = isset($_REQUEST["ux_chk_disablelightbox"]) ? "true" : "false";

                  foreach ($lightbox_setting_array as $val => $innerKey) {
                     $setting_value["setting_value"] = $innerKey;
                     $setting_key["setting_key"] = $val;
                     $update->update_data(wp_lightbox_bank_settings(), $setting_value, $setting_key);
                  }
               }
               break;

            case "update_display_settings":
               if (wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "light_box_display_settings_nonce")) {
                  $update = new update_data_setings();
                  $display_setting_array = array();
                  $setting_value = array();
                  $setting_key = array();
                  $display_setting_array["image_title"] = isset($_REQUEST["ux_image_title"]) ? "true" : "false";
                  $display_setting_array["image_caption"] = isset($_REQUEST["ux_chk_image_caption"]) ? "true" : "false";
                  $display_setting_array["text_align"] = esc_attr($_REQUEST["ux_text_align"]);

                  foreach ($display_setting_array as $val => $innerKey) {
                     $setting_value["setting_value"] = $innerKey;
                     $setting_key["setting_key"] = $val;
                     $update->update_data(wp_lightbox_bank_settings(), $setting_value, $setting_key);
                  }
               }
               break;
            case "restore_settings":
               if (wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "restore_lightbox_settings_nonce")) {

                  $sql = "TRUNCATE TABLE " . wp_lightbox_bank_settings();
                  $wpdb->query($sql);

                  include_once (WP_LIGHTBOX_BANK_BK_PLUGIN_DIR . "/lib/include-lightbox-bank-settings.php");
               }
               break;
            case"lightbox_bank_other_settings":
               if (wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "lighbox_other_settings_nonce")) {

                  $lightbox_bank_updates = intval($_REQUEST["lightbox_bank_updates"]);
                  update_option("lightbox-remove-tables-uninstall", $lightbox_bank_updates);
               }
               break;
            case"lightbox_bank_topbar_settings":
               if (wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "topbar_lightbox_settings_nonce")) {

                  $show_topbar_menu = intval($_REQUEST["show_topbar_menu"]);
                  update_option("lightbox-bank-top-bar-menu", $show_topbar_menu);
               }
               break;
         }
         die();
      }
   }
}