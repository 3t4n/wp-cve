<?php

class ContactListAdminOperations {

  public function operations() {

    if (isset($_POST) && isset($_POST['_contact_list_empty_mail_log']) && isset($_REQUEST['_wpnonce']) && is_super_admin()) {

      $wp_nonce = sanitize_text_field( $_REQUEST['_wpnonce'] );

      if ( wp_verify_nonce($wp_nonce, '_contact-list-empty-mail-log')) {

        global $wpdb;
        $delete = $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}cl_sent_mail_log");
       
        $goto_url = get_admin_url(null, './edit.php?post_type=' . CONTACT_LIST_CPT . '&page=contact-list-mail-log&mail_log_emptied=1');
       
        wp_safe_redirect( esc_url_raw( $goto_url ) );
  
        exit;
        
      } else {
        
        $goto_url = get_admin_url(null, './edit.php?post_type=' . CONTACT_LIST_CPT . '&page=contact-list-mail-log&mail_log_emptied_error=1');
        
        wp_safe_redirect( esc_url_raw( $goto_url ) );
        
      }

    }
    
  }

}
