<?php
if (!defined("ABSPATH")) {
   exit;
} // Exit if accessed directly
if (!is_user_logged_in()) {
   return;
} else {
//--------------------------------------------------------------------------------------------------------------//
// CODE FOR CREATING MENUS
//---------------------------------------------------------------------------------------------------------------//
   global $wpdb, $current_user;
   if (is_super_admin()) {
      $role = "administrator";
   } else {
      $role = $wpdb->prefix . "capabilities";
      $current_user->role = array_keys($current_user->$role);
      $role = $current_user->role[0];
   }
   switch ($role) {
      case "administrator":
         add_menu_page("WP Lightbox Bank", __("WP Lightbox Bank", wp_lightbox_bank), "read", "wp_lightbox_bank", "", plugins_url("/assets/images/icon.png", dirname(__FILE__)));
         add_submenu_page("wp_lightbox_bank", "General Settings", __("General Settings", wp_lightbox_bank), "read", "wp_lightbox_bank", "wp_lightbox_bank");
         add_submenu_page("wp_lightbox_bank", "Display Settings", __("Display Settings", wp_lightbox_bank), "read", "wplb_display_settings", "wplb_display_settings");
         add_submenu_page("wp_lightbox_bank", "Other Settings", __("Other Settings", wp_lightbox_bank), "read", "wplb_other_settings", "wplb_other_settings");
         add_submenu_page("wp_lightbox_bank", "System Status", __("System Status", wp_lightbox_bank), "read", "wplb_system_status", "wplb_system_status");
         add_submenu_page("wp_lightbox_bank", "Recommendations", __("Recommendations", wp_lightbox_bank), "read", "wplb_recommendation", "wplb_recommendation");
         add_submenu_page("wp_lightbox_bank", "Our Other Services", __("Our Other Services", wp_lightbox_bank), "read", "wplb_other_services", "wplb_other_services");
         break;
      case "editor":
         add_menu_page("WP Lightbox Bank", __("WP Lightbox Bank", wp_lightbox_bank), "read", "light_box", "", plugins_url("/assets/images/icon.png", dirname(__FILE__)));
         add_submenu_page("wp_lightbox_bank", "General Settings", __("General Settings", wp_lightbox_bank), "read", "wp_lightbox_bank", "wp_lightbox_bank");
         add_submenu_page("wp_lightbox_bank", "Display Settings", __("Display Settings", wp_lightbox_bank), "read", "wplb_display_settings", "wplb_display_settings");
         add_submenu_page("wp_lightbox_bank", "Other Settings", __("Other Settings", wp_lightbox_bank), "read", "wplb_other_settings", "wplb_other_settings");
         add_submenu_page("wp_lightbox_bank", "System Status", __("System Status", wp_lightbox_bank), "read", "wplb_system_status", "wplb_system_status");
         add_submenu_page("wp_lightbox_bank", "Recommendations", __("Recommendations", wp_lightbox_bank), "read", "wplb_recommendation", "wplb_recommendation");
         add_submenu_page("wp_lightbox_bank", "Our Other Services", __("Our Other Services", wp_lightbox_bank), "read", "wplb_other_services", "wplb_other_services");
         break;
      case "author":
         add_menu_page("WP Lightbox Bank", __("WP Lightbox Bank", wp_lightbox_bank), "read", "wp_lightbox_bank", "", plugins_url("/assets/images/icon.png", dirname(__FILE__)));
         add_submenu_page("wp_lightbox_bank", "General Settings", __("General Settings", wp_lightbox_bank), "read", "wp_lightbox_bank", "wp_lightbox_bank");
         add_submenu_page("wp_lightbox_bank", "Display Settings", __("Display Settings", wp_lightbox_bank), "read", "wplb_display_settings", "wplb_display_settings");
         add_submenu_page("wp_lightbox_bank", "Other Settings", __("Other Settings", wp_lightbox_bank), "read", "wplb_other_settings", "wplb_other_settings");
         add_submenu_page("wp_lightbox_bank", "System Status", __("System Status", wp_lightbox_bank), "read", "wplb_system_status", "wplb_system_status");
         add_submenu_page("wp_lightbox_bank", "Recommendations", __("Recommendations", wp_lightbox_bank), "read", "wplb_recommendation", "wplb_recommendation");
         add_submenu_page("wp_lightbox_bank", "Our Other Services", __("Our Other Services", wp_lightbox_bank), "read", "wplb_other_services", "wplb_other_services");
         break;
   }
//--------------------------------------------------------------------------------------------------------------//
// CODE FOR CREATING PAGES
//---------------------------------------------------------------------------------------------------------------//
   function wp_lightbox_bank() {
      global $wpdb, $current_user, $user_role_permission;
      if (is_super_admin()) {
         $role = "administrator";
      } else {
         $role = $wpdb->prefix . "capabilities";
         $current_user->role = array_keys($current_user->$role);
         $role = $current_user->role[0];
      }
      include_once WP_LIGHTBOX_BANK_BK_PLUGIN_DIR . "views/wp-lightbox-bank-header.php";
      include_once WP_LIGHTBOX_BANK_BK_PLUGIN_DIR . "views/wp-lightbox-bank-settings.php";
   }
   function wplb_system_status() {
      global $wpdb, $current_user, $user_role_permission, $wp_version;
      if (is_super_admin()) {
         $role = "administrator";
      } else {
         $role = $wpdb->prefix . "capabilities";
         $current_user->role = array_keys($current_user->$role);
         $role = $current_user->role[0];
      }
      include_once WP_LIGHTBOX_BANK_BK_PLUGIN_DIR . "views/wp-lightbox-bank-header.php";
      include_once WP_LIGHTBOX_BANK_BK_PLUGIN_DIR . "views/wp-lightbox-bank-system-status.php";
   }
   function wplb_display_settings() {
      global $wpdb, $current_user, $user_role_permission, $wp_version;
      if (is_super_admin()) {
         $role = "administrator";
      } else {
         $role = $wpdb->prefix . "capabilities";
         $current_user->role = array_keys($current_user->$role);
         $role = $current_user->role[0];
      }

      include_once WP_LIGHTBOX_BANK_BK_PLUGIN_DIR . "views/wp-lightbox-bank-header.php";
      include_once WP_LIGHTBOX_BANK_BK_PLUGIN_DIR . "views/wp-lightbox-bank-display-settings.php";
   }
   function wplb_recommendation() {
      global $wpdb, $current_user, $user_role_permission, $wp_version;
      if (is_super_admin()) {
         $role = "administrator";
      } else {
         $role = $wpdb->prefix . "capabilities";
         $current_user->role = array_keys($current_user->$role);
         $role = $current_user->role[0];
      }
      include_once WP_LIGHTBOX_BANK_BK_PLUGIN_DIR . "views/wp-lightbox-bank-header.php";
      include_once WP_LIGHTBOX_BANK_BK_PLUGIN_DIR . "views/recommended-plugins.php";
   }
   function wplb_other_services() {
      global $wpdb, $current_user, $user_role_permission, $wp_version;
      if (is_super_admin()) {
         $role = "administrator";
      } else {
         $role = $wpdb->prefix . "capabilities";
         $current_user->role = array_keys($current_user->$role);
         $role = $current_user->role[0];
      }
      include_once WP_LIGHTBOX_BANK_BK_PLUGIN_DIR . "views/wp-lightbox-bank-header.php";
      include_once WP_LIGHTBOX_BANK_BK_PLUGIN_DIR . "views/other-services.php";
   }
   function wplb_other_settings() {
      global $wpdb, $current_user, $user_role_permission;
      if (is_super_admin()) {
         $role = "administrator";
      } else {
         $role = $wpdb->prefix . "capabilities";
         $current_user->role = array_keys($current_user->$role);
         $role = $current_user->role[0];
      }
      include_once WP_LIGHTBOX_BANK_BK_PLUGIN_DIR . "views/wp-lightbox-bank-header.php";
      include_once WP_LIGHTBOX_BANK_BK_PLUGIN_DIR . "views/other-settings.php";
   }
}