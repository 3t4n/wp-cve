<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_deactivation {

    static function MJTC_deactivate() {
      wp_clear_scheduled_hook('majesticsupport_updateticketstatus');
      wp_clear_scheduled_hook('majesticsupport_ticketviaemail');
      $timestamp = wp_next_scheduled( 'ms_delete_expire_session_data' );
      wp_unschedule_event( $timestamp, 'ms_delete_expire_session_data' );
      $id = majesticsupport::getPageid();
      majesticsupport::$_db->get_var("UPDATE `" . majesticsupport::$_db->prefix . "posts` SET post_status = 'draft' WHERE ID = ".esc_sql($id));

      //Delete capabilities
      $role = get_role( 'administrator' );
      $role->remove_cap( 'ms_support_ticket' );
    }

    static function MJTC_tables_to_drop() {
        global $wpdb;
        $tables = array(
           $wpdb->prefix."mjtc_support_fieldsordering",
           $wpdb->prefix."mjtc_support_faqs",
           $wpdb->prefix."mjtc_support_departments",
           $wpdb->prefix."mjtc_support_attachments",
           $wpdb->prefix."mjtc_support_config",
           $wpdb->prefix."mjtc_support_email",
           $wpdb->prefix."mjtc_support_emailtemplates",
           $wpdb->prefix."mjtc_support_priorities",
           $wpdb->prefix."mjtc_support_replies",
           $wpdb->prefix."mjtc_support_system_errors",
           $wpdb->prefix."mjtc_support_tickets",
           $wpdb->prefix."mjtc_support_erasedatarequests",
           $wpdb->prefix."mjtc_support_users",
           $wpdb->prefix."mjtc_support_multiform",
           $wpdb->prefix."mjtc_support_slug",
           $wpdb->prefix."mjtc_support_smartreplies",
        );
        return $tables;
    }

}

?>
