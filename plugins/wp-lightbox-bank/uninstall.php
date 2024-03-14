<?php
if (!defined("ABSPATH")) {
   exit;
}//exit if accessed directly
if (!is_user_logged_in()) {
   return;
} else {
   if (!defined('WP_UNINSTALL_PLUGIN')) {
      die;
   } else {

      global $wpdb;
      $lightbox_remove_tables_uninstall = get_option("lightbox-remove-tables-uninstall");
      if (isset($lightbox_remove_tables_uninstall) && $lightbox_remove_tables_uninstall == "1") {
         $sql = "DROP TABLE IF EXISTS " . $wpdb->prefix . "lightbox_bank_settings";
         $wpdb->query($sql);

         delete_option("lightbox-bank-pro-edition");
         delete_option("lightbox-remove-tables-uninstall");
      }
   }
}
