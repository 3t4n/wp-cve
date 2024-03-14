<?php
/**
*
* Joy_Of_Text options. Processess requests from the admin pages
*
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



final class Joy_Of_Text_Plugin_Options {
 
    /*--------------------------------------------*
     * Constructor
     *--------------------------------------------*/
 
    /**
     * Initializes the plugin 
     */
    public function __construct() {
                                    
                add_action( 'wp_ajax_process_forms',        array( $this, 'process_forms' ) );
                add_action( 'wp_ajax_nopriv_process_forms', array( $this, 'process_forms' ) );
                    
                add_action( 'wp_ajax_process_savemem', array( $this, 'process_save_member' ) );
                add_action( 'wp_ajax_process_deletemem', array( $this, 'process_delete_member' ) );
                add_action( 'wp_ajax_process_addmem', array( $this, 'process_add_member' ) );
                
                // Process CSV download
                add_action( 'admin_post_process_downloadgroup', array( $this, 'process_download_group' ) );
                  
        
    } // end constructor
 
    private static $_instance = null;
        
    public static function instance () {
            if ( is_null( self::$_instance ) )
                self::$_instance = new self();
            return self::$_instance;
    } // End instance()

     
    public function process_forms () {
        
        
        if (!empty($_POST)) {
            $formdata =  $_POST['formdata'] ;
            parse_str($formdata, $output);
            $jot_form_id = sanitize_text_field($output['jot_form_id']);
            
            switch ( $jot_form_id ) {                
                case 'jot-group-invite-form':
                   $this->process_group_invite_form();
                break;
                case 'jot-subscriber-form':
                   $this->process_subscriber_form();
                break;
                case 'jot-group-details-form':
                   $this->process_group_details_form();
                break;
                            
                default:
                # code...
                break;
            }
           
        }
    }
        
    
    
    public function process_group_details_form() {
                
        if ( !current_user_can('manage_options') ) {
              $error=4;  
        }
        
        global $wpdb;
        $error = 0;
        
        $formdata = $_POST['formdata'];
        parse_str($formdata, $output);
       
        $groupfields = $output['jot-plugin-group-list'];
        $jot_grpid        = isset($output['jot_grpid'])             ? sanitize_text_field($output['jot_grpid'])             : "";
        $jot_groupdescupd = isset($groupfields['jot_groupdescupd']) ? sanitize_text_field($groupfields['jot_groupdescupd']) : "";
        $jot_groupnameupd = isset($groupfields['jot_groupnameupd']) ? sanitize_text_field($groupfields['jot_groupnameupd']) : "";
        
        if (str_replace(' ', '',$jot_groupdescupd) == '') {
                $error = 2;
        }
        
        if (str_replace(' ', '',$jot_groupnameupd) == '') {
                $error = 1;
        }
        
        $table = $wpdb->prefix."jot_groups";
              
        $group_exists =$wpdb->get_col( $wpdb->prepare( 
                "
                SELECT    jot_groupid 
                FROM      " . $table . "
                WHERE     jot_groupname = %s
                AND       jot_groupdesc = %s  
                ",
                $jot_groupnameupd,
                $jot_groupdescupd                
        ) ); 
        
        if ($group_exists) {
                $error=3;
        }
        
               
        if ($error===0) {                
                $data = array(
                    'jot_groupname' => $jot_groupnameupd,
                    'jot_groupdesc' => $jot_groupdescupd            
                );                
                $sqlerr=$wpdb->update( $table, $data, array( 'jot_groupid' =>  $jot_grpid));
                
        }        
                      
        switch ( $error ) {
                case 0; // All fine
                       $msg = __("Group details saved successfully", "jot-plugin");
                break;
                case 1; // Group name not set
                       $msg = __("No group name was entered. Please try again.", "jot-plugin");         
                break;
                case 2; // Group description not set
                       $msg = __("No group description was entered. Please try again.", "jot-plugin");         
                break;
                case 3; // Group with same name already exists
                       $msg = __("A group with this name already exists. Please try again.", "jot-plugin");         
                break;
                case 4; // Not an admin
                       $msg = __("You are not an Admin user.", "jot-plugin");     
                break;
                case 5; // Group ID not set
                       $msg = __("Group ID was not provided.", "jot-plugin");     
                break;
                default:
                # code...
                break;
        }         
        
        
        $response = array('errormsg'=> esc_html($msg), 'errorcode' => $error, 'url'=> "", 'sqlerr' => $wpdb->last_error, 'lastid' => ""  );
        echo json_encode($response);
        
        wp_die();
        
    }
    
    public function process_group_invite_form() {
        
        $error = 0;
        
        if ( !current_user_can('manage_options') ) {
              $error=4;  
        }
        
        global $wpdb;
        
        if ($error==0) {
                $formdata = $_POST['formdata'];
                parse_str($formdata, $output);
                      
                $groupfields = $output['jot-plugin-group-list'];              
                
                $jot_grpinvdesc    = isset($groupfields['jot_grpinvdesc'])    ? sanitize_text_field ($groupfields['jot_grpinvdesc'])    : "";
                $jot_grpinvnametxt = isset($groupfields['jot_grpinvnametxt']) ? sanitize_text_field ($groupfields['jot_grpinvnametxt']) : "";
                $jot_grpinvnumtxt  = isset($groupfields['jot_grpinvnumtxt'])  ? sanitize_text_field ($groupfields['jot_grpinvnumtxt'])  : "";
                $jot_grpinvrettxt  = isset($groupfields['jot_grpinvrettxt'])  ? sanitize_textarea_field($groupfields['jot_grpinvrettxt'])  : "";
                $jot_grpid = isset($groupfields['jot_grpid'])  ?  (int) $groupfields['jot_grpid'] : -1;
                
                if (isset($groupfields['jot_grpinvretchk'])) {
                    $jot_grpinvretchk  = sanitize_text_field ($groupfields['jot_grpinvretchk']) === 'true' ? 1:0;
                } else {
                    $jot_grpinvretchk = 0;
                }
                
                $table = $wpdb->prefix."jot_groupinvites";
                $invite_exists =$wpdb->get_col( $wpdb->prepare( 
                        "
                        SELECT    jot_grpid  
                        FROM        " . $table . "
                        WHERE       jot_grpid = %s                 
                        ",
                        $jot_grpid                
                ) ); 
        
                if ( $invite_exists ) {
                        $data = array(
                        'jot_grpinvdesc'    => $jot_grpinvdesc,
                        'jot_grpinvnametxt' => $jot_grpinvnametxt,
                        'jot_grpinvnumtxt'  => $jot_grpinvnumtxt,
                        'jot_grpinvretchk'  => $jot_grpinvretchk,
                        'jot_grpinvrettxt'  => $jot_grpinvrettxt                
                        );
                        
                        $success=$wpdb->update( $table, $data, array( 'jot_grpid' =>  $jot_grpid  ) );                        
                       
                        // Save GDPR notice message
                        $jot_grpgdprtxt = isset($groupfields['jot_grpgdprtxt']) ? sanitize_textarea_field($groupfields['jot_grpgdprtxt']) : "";
                        Joy_Of_Text_Plugin()->settings->save_groupmeta($jot_grpid , 'jot_grpgdprtxt', $jot_grpgdprtxt );
                        
                        // Save GDPR notice checkbox
                        $jot_grpgdprchk = isset($groupfields['jot_grpgdprchk']) ? true : false;
                        Joy_Of_Text_Plugin()->settings->save_groupmeta($jot_grpid , 'jot_grpgdprchk', $jot_grpgdprchk );
                        
                } else {
                        $data = array(
                        'jot_grpid' => (int) $jot_grpid ,
                        'jot_grpinvdesc'    => $jot_grpinvdesc,
                        'jot_grpinvnametxt' => $jot_grpinvnametxt,
                        'jot_grpinvnumtxt'  => $jot_grpinvnumtxt,
                        'jot_grpinvretchk'  => $jot_grpinvretchk,
                        'jot_grpinvrettxt'  => $jot_grpinvrettxt  
                        );
                        $success=$wpdb->insert( $table, $data );                            
                }
        }
        if ($wpdb->last_error !=null) {
            $error = 1;
        }
        switch ( $error ) {
                case 0; // All fine
                       $msg = __("Group details saved successfully", "jot-plugin");
                break;
                case 1; // All fine
                       $msg = __("A database error occurred", "jot-plugin");
                break;
                case 4; // Not an admin
                       $msg = __("You are not an Admin user.", "jot-plugin");     
                break;
                default:
                # code...
                break;
        }             
                
        $response = array('errormsg'=> esc_html($msg), 'errorcode' => $error, 'url'=> "", 'sqlerr' => $wpdb->last_error, 'lastid' => ""  );
        echo json_encode($response);                
        wp_die();
        
    }
    
    public function process_subscriber_form() {     
        
        // Check nonce.
        if (! check_ajax_referer( 'jot_nonce', 'nonce', false ) ) {
            $response = array('errormsg'=> __("Invalid security token provided. Trying refreshing the page.","jot-plugin"), 'errorcode' => 1, 'sqlerr' => '' );
            echo json_encode($response);
            die();
        }
        
        $error = 0;
        $url = '';
        $lastid='';
        $msgerr = "";
        $verified_number = "";
        global $wpdb;
        
        $table = $wpdb->prefix."jot_groupmembers";
        $formdata = $_POST['formdata'];
        parse_str($formdata, $output);
        $jot_subscribe_num = sanitize_text_field($output['jot-subscribe-num']);
        $jot_subscribe_name = sanitize_text_field($output['jot-subscribe-name']);
        $jot_group_id = (int) $output['jot-group-id'];
        
        // Spam bot check
        $jot_subscribe_special = isset($output['jot-subscribe-special']) ? sanitize_text_field($output['jot-subscribe-special']) : "";
                   
        //Strip spaces out of number
        $phone_num = $this->parse_phone_number($jot_subscribe_num);
        
        // Check name is entered
        if (str_replace(' ', '',$jot_subscribe_name) == '') {
                $error = 4;         
        }
                
        // Check phone number
        $removed_plus = false;
        
        // Does phone number start with a plus
        if (preg_match('/^\+/', $phone_num)) {
            $phone_num = substr($phone_num,1);
            $removed_plus = true;
        }
        
        if (!is_numeric($phone_num)) {
             $error = 2;
        }
        
        if ($removed_plus) {
            $phone_num = "+" . $phone_num;
        }
        
        if ($error == 0) {                
                $verified_number = Joy_Of_Text_Plugin()->currentsmsprovider->verify_number($phone_num);
                if ( $verified_number == "") {
                    $error = 5;
                }
        }
        
        if ($this->number_exists($table, $verified_number, $jot_group_id)) {
             $error = 3;
        }
        
        
        if ($jot_subscribe_special != "") {
            // Filled in by a bot so end
            $error = 6;
        }
        
        if ( $error===0)  {
                $data = array(
                    'jot_grpid' => $jot_group_id,
                    'jot_grpmemname' => $jot_subscribe_name,
                    'jot_grpmemnum' =>  $verified_number            
                );
                    
                    
                $success=$wpdb->insert( $table, $data );
                $lastmemid = $wpdb->insert_id;
                
                  // Insert into xref table
                $table = $wpdb->prefix."jot_groupmemxref";
                $data = array(
                       'jot_grpid'       => $jot_group_id,
                       'jot_grpmemid'    => $lastmemid,
                       'jot_grpxrefts'   => current_time('mysql', 0)
                );
                $success=$wpdb->insert( $table, $data );
                
                                
                if ($success === false) {
                        // Insert failed
                        $error=1;
                } else {
                        // Send welcome message if required
                   $msgerr = $this->send_welcome_message($jot_group_id, $verified_number ,$lastmemid);
                }
        } 
        
        switch ( $error ) {
                case 0; // All fine
                       $msg = __('Thank you for subscribing to the group.', 'jot-plugin');
                break;
                case 1; // insert failed
                       $msg = __('An error occurred subscribing to the group.', 'jot-plugin');         
                break;
                case 2; // None numeric phone number
                       $msg = __('The phone number is not numeric. Please try again', 'jot-plugin');         
                break;
                case 3; // Number already exists in this group
                       $msg = __('This phone number is already subscribed to this group.', 'jot-plugin');         
                break;
                case 4; // Member name not set set
                       $msg = __('Please enter your name.', 'jot-plugin');
                break;
                case 5; // Not a valid number
                        $msg = esc_html($phone_num) . __(" - number is not valid. Try again by adding your country code.","jot-plugin");                       
                break;
                case 6; // Spambot completed form?
                        $msg =  __("Form completed by Spam Bot.","jot-plugin");                       
                break; 
                default:
                # code...
                break;
        }
        
        // Action hook fired if subscriber is added successfully.
        if ($error == 0) {
            $allgroups = array();
            $allgroups[] = $output['jot-group-id'];
            $subscriber_args = array('jot_grpid'         => $allgroups,
                                     'jot_grpmemid'      => $lastmemid,
                                     'jot_grpmemname'    => sanitize_text_field (substr($output['jot-subscribe-name'],0,40)),
                                     'jot_grpmemnum'     => $verified_number,                               
                                     'jot_grpmememail'   => '',
                                     'jot_grpmemaddress' => '',
                                     'jot_grpmemcity'    => '',
                                     'jot_grpmemstate'   => '',
                                     'jot_grpmemzip'     => '',
                                     'jot_grpmemts'      => current_time('mysql', 0)
                                     );
            do_action('jot_after_subscriber_added',$subscriber_args);
        }
              
                             
        $response = array('errormsg'=> esc_html($msg), 'errorcode' => $error, 'url'=> "", 'sqlerr' => $wpdb->last_error, 'lastid' => $lastid, 'message_error' => $msgerr );
        echo json_encode($response);
                                    
        wp_die();
        
    }
    
    public function process_add_member($param_memname = null, $param_memnum = null, $param_grpid = null) {
       
        $url ='';
        $errorfield = '';
        $lastmemid = 0;
        $error=0;
        $verified_number = "";
        
        //if ( !current_user_can('manage_options') ) {
        //      $error=4;  
        //}

        global $wpdb;
        $table = $wpdb->prefix."jot_groupmembers";
        
        // From POST or from another class?
        if (is_null($param_grpid) ) {
            $formdata = $_POST['formdata'];
            $jot_grpmemname = sanitize_text_field($formdata['jot_grpmemname']);
            $jot_grpmemnum = sanitize_text_field($formdata['jot_grpmemnum']);
            $jot_grpid = sanitize_text_field($formdata['jot_grpid']);           
        } else {
            $jot_grpmemname = $param_memname;
            $jot_grpmemnum  = $param_memnum;
            $jot_grpid = $param_grpid;           
        }
        
             
        // Check name is entered
         if (!isset($jot_grpmemname) || str_replace(' ', '',$jot_grpmemname) == '') {
                $error = 1;         
        }
        
        // Check phone number
        $removed_plus = false;
        $phone_num = $this->parse_phone_number( $jot_grpmemnum );
        
        // Does phone number start with a plus
        if (preg_match('/^\+/', $phone_num)) {
            $phone_num = substr($phone_num,1);
            $removed_plus = true;
        } 
        if (!is_numeric($phone_num)) {
             $error = 2;
        }
        
        if ($removed_plus) {
            $phone_num = "+" . $phone_num;
        }
        
        if ($error == 0) {
            $verified_number = Joy_Of_Text_Plugin()->currentsmsprovider->verify_number($phone_num);
            if ( $verified_number == "") {
                $error = 5;
            }
        }
        
        if ($this->number_exists($table, $verified_number, $jot_grpid)) {
             $error = 3;
        }
            
        if ( $error==0 ) {
            
                $table = $wpdb->prefix."jot_groupmembers";
                $data = array(
                    'jot_grpid' => $jot_grpid,
                    'jot_grpmemname' => $jot_grpmemname,
                    'jot_grpmemnum' =>  $verified_number           
                );
                    
                
                $success=$wpdb->insert( $table, $data );
                $lastmemid = $wpdb->insert_id;
                
                // Insert into xref table
                $table = $wpdb->prefix."jot_groupmemxref";
                $data = array(
                       'jot_grpid'       => $jot_grpid,
                       'jot_grpmemid'    => $lastmemid,
                       'jot_grpxrefts'   => current_time('mysql', 0)
                );
                $success=$wpdb->insert( $table, $data );
                
        }
        switch ( $error ) {
                case 0; // All fine
                       $msg = __("New member added successfully.", "jot-plugin");
                break;
                case 1; // insert failed
                       $msg = __("Name field is blank. Please enter a name", "jot-plugin");
                       $errorfield = isset($formdata['jot_namefield_id']) ? $formdata['jot_namefield_id'] : "";
                break;
                case 2; // None numeric phone number
                       $msg = __("The phone number is not numeric.", "jot-plugin");
                       $errorfield = isset($formdata['jot_numfield_id']) ? $formdata['jot_numfield_id'] : "";                       
                break;
                case 3; // Number already exists in this group
                       $msg = __("Phone number already exists in this group", "jot-plugin");
                       $errorfield = isset($formdata['jot_numfield_id']) ? $formdata['jot_numfield_id'] : "";
                break;
                case 4; // Not an Admin
                       $msg = __("You are not an Admin user.","jot-plugin");                       
                break;
                case 5; // Not a valid number
                       $msg = esc_html($phone_num) . __(" - number is not valid. Try again by adding your country code.","jot-plugin");                       
                break; 
                default:
                       $msg= "";
                break;
        }
        
        // Action hook fired if subscriber is added successfully.
        if ($error == 0) {
            $allgroups = array();
            $allgroups[] = $jot_grpid;
            $subscriber_args = array('jot_grpid'         => $allgroups,
                                     'jot_grpmemid'      => $lastmemid,
                                     'jot_grpmemname'    => sanitize_text_field (substr($jot_grpmemname,0,40)),
                                     'jot_grpmemnum'     => $verified_number,                               
                                     'jot_grpmememail'   => '',
                                     'jot_grpmemaddress' => '',
                                     'jot_grpmemcity'    => '',
                                     'jot_grpmemstate'   => '',
                                     'jot_grpmemzip'     => '',
                                     'jot_grpmemts'      => current_time('mysql', 0)
                                     );
            do_action('jot_after_member_added',$subscriber_args);
        }       
                
        $response = array('errormsg'=> esc_html($msg), 'errorcode' => $error, 'errorfield' => $errorfield,'url'=> "", 'sqlerr' => $wpdb->last_error, 'lastid'=> $lastmemid, 'verifiednumber' => $verified_number );
               
        // If called from frontend
        if (!isset($param_grpid) ) {
           echo json_encode($response);        
           wp_die();
        } else {
            // If called from bulkadd
           return $response;     
        }
                
    }
    
    
    public function process_save_member() {
                
        if ( !current_user_can('manage_options') ) {
              $error=4;  
        }

        global $wpdb;
        
        $errorfield = "";
        $url = "";
        $error=0;
        
        $formdata = $_POST['formdata'];
        $table = $wpdb->prefix."jot_groupmembers";
        $jot_grpmemname = isset($formdata['jot_grpmemname']) ? sanitize_text_field($formdata['jot_grpmemname']) : "";
        $jot_grpmemnum  = isset($formdata['jot_grpmemnum'])  ? sanitize_text_field($formdata['jot_grpmemnum'])  : "";
        $jot_grpmemid   = isset($formdata['jot_grpmemid'])   ? sanitize_text_field($formdata['jot_grpmemid'])   : "";
        $jot_grpid = (int) $formdata['jot_grpid'];
             
        // Check name is entered
         if (str_replace(' ', '',$jot_grpmemname) == '') {
                $error = 1;         
        }
        
        // Check phone number
        $removed_plus = false;
        $phone_num = $this->parse_phone_number( $jot_grpmemnum );
         
        
        // Does phone number start with a plus
        if (preg_match('/^\+/', $phone_num)) {
            $phone_num = substr($phone_num,1);
            $removed_plus = true;
        } 
        if (!is_numeric($phone_num)) {
             $error = 2;
        }
        
        if ($removed_plus) {
            $phone_num = "+" . $phone_num;
        }
        
        if ($error == 0) {                
                $verified_number = Joy_Of_Text_Plugin()->currentsmsprovider->verify_number($phone_num);
                if ( $verified_number == "") {
                    $error = 5;
                }
        }
                
        if ($this->number_exists_for_member($table, $verified_number, $jot_grpid, $jot_grpmemid)) {
             $error = 3;
        }
            
        if ( $error==0 ) {
                
                $data = array(
                        'jot_grpmemname' => $jot_grpmemname,
                        'jot_grpmemnum' =>  $verified_number
                );
                    
                
                $success=$wpdb->update( $table, $data, array( 'jot_grpid' =>  $jot_grpid,'jot_grpmemid' => $jot_grpmemid ) );
                
        }
        switch ( $error ) {
                case 0; // All fine
                       $msg = __("Details updated successfully.", "jot-plugin");
                break;
                case 1; // insert failed
                       $msg = __("Name field is blank. Please enter a name", "jot-plugin");
                       $errorfield = $formdata['jot_namefield_id'];
                break;
                case 2; // None numeric phone number
                       $msg = __("The phone number is not numeric.", "jot-plugin");
                       $errorfield = $formdata['jot_numfield_id'];                       
                break;
                case 3; // Number already exists in this group
                       $msg = __("Phone number already exists in this group", "jot-plugin");
                       $errorfield = $formdata['jot_numfield_id'];
                break;
                case 4; // Not an Admin
                       $msg = __("You are not an Admin user.", "jot-plugin");                       
                break;
                case 5; // Not a valid number
                       $msg = esc_html($phone_num) . __(" - number is not valid. Try again by adding your country code.","jot-plugin");                       
                break; 
                default:
                       $msg= "";
                break;
        }         
        
                
        $response = array('errormsg'=> esc_html($msg), 'errorcode' => $error, 'errorfield' => $errorfield,'url'=> "", 'sqlerr' => $wpdb->last_error  );
        echo json_encode($response);
        
        wp_die();
                
    }
    
    public function process_delete_member() {
                
        if ( !current_user_can('manage_options') ) {
              $error=4;  
        }

        global $wpdb;
        $error=0;
        
        $formdata = $_POST['formdata'];
        $table_mem  = $wpdb->prefix."jot_groupmembers";        
        $table_xref = $wpdb->prefix."jot_groupmemxref";
   
            
        if ( $error==0 ) {
            
                // Get member details
                $member = Joy_Of_Text_Plugin()->messenger->get_member(sanitize_text_field($formdata['jot_grpmemid']));
                
                // Delete member
                $success=$wpdb->delete( $table_mem,  array( 'jot_grpid' =>  sanitize_text_field($formdata['jot_grpid']),'jot_grpmemid' => sanitize_text_field($formdata['jot_grpmemid']) ) );
                $success=$wpdb->delete( $table_xref, array( 'jot_grpid' =>  sanitize_text_field($formdata['jot_grpid']),'jot_grpmemid' => sanitize_text_field($formdata['jot_grpmemid']) ) );
                
                if ($success > 0) {
                    // Action hook for deleted user                    
                    $group = (array) Joy_Of_Text_Plugin()->settings->get_group_details(sanitize_text_field($formdata['jot_grpid']));
                    do_action('jot_after_member_deletion_from_group',$member, $group);
                }
        }
        
        
        switch ( $error ) {
                case 0; // All fine
                       $msg = __("Member deleted successfully.", "jot-plugin");
                break;
                case 4; // Not an Admin
                       $msg = __("You are not an Admin user.", "jot-plugin");                       
                break; 
                default:
                       $msg= "";
                break;
        }         
        
                
        $response = array('errormsg'=> esc_html($msg), 'errorcode' => $error, 'errorfield' => "",'url'=> "", 'sqlerr' => $wpdb->last_error  );
        echo json_encode($response);
        
        wp_die();
                
    }
    
    public function parse_phone_number($number) {

        $number = str_replace(' ', '', $number);
        $number = str_replace('(', '', $number);
        $number = str_replace(')', '', $number);
        $number = str_replace('-', '', $number);
        $number = str_replace('.', '', $number);
        return sanitize_text_field($number);

    }
    
    public function number_exists($table, $number, $id) {

         global $wpdb;
         $sql = " SELECT jot_grpmemnum  " .
                " FROM " . $table .
                " WHERE jot_grpid = %d " .
                " AND jot_grpmemnum = %s ";
         $sqlprep = $wpdb->prepare($sql, $id, $number);      
         $numexists = $wpdb->get_results( $sqlprep );
         return $numexists;

    }
    
    // Check whether this number being added for a different member
    // In which case, that's an error
    public function number_exists_for_member($table, $number, $grpid, $memid) {

                
         global $wpdb;
         $sql = " SELECT jot_grpmemnum  " .
                " FROM " . $table .
                " WHERE jot_grpid = %d " .
                " AND   jot_grpmemid !=  %d" . 
                " AND jot_grpmemnum = %s ";
         $sqlprep = $wpdb->prepare($sql, $grpid, $id, $number);
         $numexists = $wpdb->get_results( $sqlprep );
                  
         return $numexists;

    }
    
    public function send_welcome_message($id, $number,$jotmemid) {

         global $wpdb;
         $table = $wpdb->prefix."jot_groupinvites";
         $sql = " SELECT jot_grpinvretchk,jot_grpinvrettxt  " .
                " FROM " . $table .
                " WHERE jot_grpid = %d ";               
         $sqlprep = $wpdb->prepare($sql, $id);
         $welchkbox = $wpdb->get_row( $sqlprep );
         
         if ($welchkbox->jot_grpinvretchk) {
                $member = Joy_Of_Text_Plugin()->messenger->get_member($jotmemid);
                $detagged_message = Joy_Of_Text_Plugin()->messenger->get_replace_tags($welchkbox->jot_grpinvrettxt,$member);
                $msgerr = Joy_Of_Text_Plugin()->currentsmsprovider->send_smsmessage($number, $detagged_message);               
         }
         return $msgerr;

    }
    
    
    /*
    *
    * Confirm that the given number is valid by calling Twilio's lookup function.
    *
    */
    public function verify_number($number) {
            
       $verified_number = Joy_Of_Text_Plugin()->currentsmsprovider->verify_number($number);
       return $verified_number;
            
    }
    
    public function process_jot_edd_activate_license() {
            
            $formdata   = $_POST['formdata'];    
            $licence    = isset($formdata['jot-eddlicence']) ? sanitize_text_field($formdata['jot-eddlicence']) : "";
            $product    = isset($formdata['jot-eddproduct']) ? sanitize_text_field($formdata['jot-eddproduct']) : EDD_SL_ITEM_NAME;
            $statuskey  = 'jot-eddlicencestatus';
            $licencekey = 'jot-eddlicence';
            
            $this->process_edd_activate_license($licence,$product,$statuskey,$licencekey);
            
   }
   
   public function process_edd_activate_license($licence,$product,$statuskey,$licencekey) {
            
            
            
            // data to send in our API request
            $api_params = array( 
                    'edd_action'=> 'activate_license', 
                    'license' 	=> $licence, 
                    'item_name' => urlencode( $product ),
                    'url'       => home_url()
            );
            
            
            // Call the custom API.
            $response = wp_remote_post( EDD_SL_STORE_URL_JOTLITE, array(
                    'timeout'   => 15,
                    'sslverify' => false,
                    'body'      => $api_params
            ) );
            
                    

            // make sure the response came back okay
            if ( is_wp_error( $response ) )
                    return false;

            // decode the licence data
            $licence_data = json_decode( wp_remote_retrieve_body( $response ) );
            
            // $licence_data->license will be either "active" or "inactive"      
            Joy_Of_Text_Plugin()->settings->set_smsprovider_settings($statuskey,$licence_data->license);
            Joy_Of_Text_Plugin()->settings->set_smsprovider_settings($licencekey,$licence);
            
        
            echo json_encode(array("activationstatus" => $licence_data->license, "response" => $licence_data));
            wp_die();
   }
   
   
   
   /*
     *
     * Download group members into a CSV file.
     *
     */
     public function process_download_group() {
                   
            $jot_grpid = sanitize_text_field($_GET['grpid']);
            if (!empty($jot_grpid)) {
               //$groupmembers = Joy_Of_Text_Plugin()->messenger->get_groupmembers_only($jot_grpid);
               $groupmembers = Joy_Of_Text_Plugin()->settings->get_all_groups_and_members($jot_grpid);
               
               $data = $this->addQuotes("Member Name") . "," . $this->addQuotes("Member Number") . "\r\n";;
           
               foreach ( $groupmembers as $member ) {
                   $group_name = $member->jot_groupname;
                   //$data .= '"' . $member->jot_grpmemname . '","' . $member->jot_grpmemnum . '"' . "\n";
                   $data .= $this->addQuotes((isset($member->jot_grpmemname)    ? esc_html($member->jot_grpmemname)    : "") ) . "," .
                            $this->addQuotes((isset($member->jot_grpmemnum)     ? esc_html($member->jot_grpmemnum)     : "") ) .
                            "\r\n";
               }
               
               $group_name = str_replace(" ", "", $group_name);
               $filename = "jot-" . $group_name . "-memberlist.csv";
               
               header('Content-Type: application/csv');
               header('Content-Disposition: attachement; filename="' . $filename . '";');
               echo $data;
            }
            exit();
    }
    
    function addQuotes($str){
            $str = isset($str) ? $str : "";
            return '"' . $str . '"';
    }
   
    
} // end class
 