<?php
if(!defined('WP_DEBUG')) { exit; }

class MSBDDELCOM_Form_Handler {

    /*
     * 
     * */     
    public function __construct() {
        add_action('admin_init',    array( $this, 'update_options'));
    }

    public function update_options() {
        if ( !isset($_POST['msbd_btn_confirm_comments_delete']) || $_POST['action']!='action-confirm-comments-delete' ) {
            return;
        }

        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'msbd-nonce-confirm-comments-delete' ) ) {
            die( __( 'Something goes wrong! Hope you are not cheating?', 'msbddelcom' ) );
        }

        if ( ! current_user_can( 'read' ) ) {
            wp_die( __( 'Permission Denied!', 'msbddelcom' ) );
        }
        
        global $wpdb;
        
        $messages = array();
        $page_url = admin_url( 'admin.php?page=msbd-delete-comments' );

        $comment_statuses = explode(",", msbd_sanitization($_POST['msbd_comment_statuses']));
        $filter="";
        foreach($comment_statuses as $val) {
            $filter .= empty($filter) ? " WHERE comment_approved='$val'" : " OR comment_approved='$val'";
        }

        if (!empty($filter)) {
            $sel_query = "SELECT comment_ID FROM ". $wpdb->comments . $filter;
            $str_query = "DELETE FROM $wpdb->comments $filter";
            
            $comments_meta_to_be_delete = $wpdb->get_results($sel_query);
            
            if ($wpdb->query($str_query) != FALSE) {
                $messages['success'] = urlencode(__( 'Succesfully saved!', 'msbddelcom' ));
                
                /* Delete Meta of Comment */
                msbd_update_option('msbddelcom_delete_meta_for_comment', $comments_meta_to_be_delete);
                $delete_comment_count = 0;
                $csv_ids = array();
                foreach ($comments_meta_to_be_delete as $row) {
                    $delete_comment_count++;
                    $csv_ids[] = $row->comment_ID;
                    
                    if ($delete_comment_count>999) {
                        $del_query = "DELETE FROM $wpdb->commentmeta WHERE comment_id IN (".implode(",", $csv_ids).")";
                        $wpdb->query($del_query);
                        $delete_comment_count = 0;
                        $csv_ids = array();
                    }
                }
                
                if ($delete_comment_count>0) {
                    msbd_update_option('msbddelcom_delete_meta_for_comment', "");
                    $del_query = "DELETE FROM $wpdb->commentmeta WHERE comment_id IN (".implode(",", $csv_ids).")";
                    $wpdb->query($del_query);
                    $delete_comment_count = 0;
                    $csv_ids = array();
                }

                /* Optimize Tables*/
                $wpdb->query("OPTIMIZE TABLE $wpdb->comments");
                $wpdb->query("OPTIMIZE TABLE $wpdb->commentmeta");
                
            } else {
                $messages['error'] = urlencode(__( 'Something Went Wrong, Please Try Again!', 'msbddelcom' ));
            }
        }
        
        $redirect_to = add_query_arg($messages, $page_url); 
        wp_safe_redirect( $redirect_to );
        exit;
    }
}

new MSBDDELCOM_Form_Handler();
