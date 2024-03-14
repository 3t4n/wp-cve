<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
* Joy_Of_Text_Plugin_Messenger Class
*
*/


final class Joy_Of_Text_Plugin_Messenger {
 
             /*--------------------------------------------*
              * Constructor
              *--------------------------------------------*/
          
             /**
              * Initializes the plugin 
              */
             function __construct() {
                          add_action( 'wp_ajax_send_message', array( &$this, 'send_message_callback' ) );
                          add_action( 'wp_ajax_nopriv_send_message', array( &$this, 'send_message_callback' ) );
                          add_action( 'wp_ajax_queue_message', array( &$this, 'queue_message' ) );
             } // end constructor
          
             private static $_instance = null;
                
             public static function instance () {
                     if ( is_null( self::$_instance ) )
                         self::$_instance = new self();
                     return self::$_instance;
             } // End instance()
          
              /**
              * Queue messages in table before being processed
              */
             public function queue_message() {
             
                      global $wpdb;
                      $error = 0;
                      $scheduled = false;
                     
                      $formdata = $_POST['formdata'];
                                 
                      parse_str($formdata['jot-allform'], $output);            
                      $message              = isset($output['jot-plugin-messages']['jot-message'])           ? sanitize_textarea_field($output['jot-plugin-messages']['jot-message'])           : "";
                      $mess_type            = isset($output['jot-plugin-messages']['jot-message-type'])      ? sanitize_text_field($output['jot-plugin-messages']['jot-message-type'])      : "";
                      $mess_suffix          = isset($output['jot-plugin-messages']['jot-message-suffix'])    ? sanitize_text_field($output['jot-plugin-messages']['jot-message-suffix'])    : "";
                      $mess_audioid         = isset($output['jot-plugin-messages']['jot-message-audioid'])   ? sanitize_text_field($output['jot-plugin-messages']['jot-message-audioid'])   : "";
                      $mess_mmsimageid      = isset($output['jot-plugin-messages']['jot-message-mms-image']) ? sanitize_text_field($output['jot-plugin-messages']['jot-message-mms-image']) : "";
                      $mess_senderid        = isset($output['jot-plugin-messages']['jot-message-senderid'])  ? sanitize_text_field($output['jot-plugin-messages']['jot-message-senderid'])  : "";
                      $schedule_description = isset($output['jot-plugin-messages']['jot-scheddesc'])         ? sanitize_text_field($output['jot-plugin-messages']['jot-scheddesc'])         : "";
                      //echo ">>>" . $message . " >>" . $mess_type . " >>" . $mess_suffix. " >>" . $mess_audioid . " >>" . $mess_mmsimageid . " >>" . $mess_senderid;
                                            
                      // Get selected schedule time and date                   
                      $schedule_input_timestamp = array('jot-scheddate' => isset($output['jot-scheddate']) ? sanitize_text_field($output['jot-scheddate']) : "",
                                                     'jot-schedtime' => isset($output['jot-schedtime']) ? sanitize_text_field($output['jot-schedtime']) : ""
                                                    );
                   
                      // Get schedule repeats                   
                      $schedule_input_repeat = array('jot-sched-repeats-interval' => isset($output['jot-sched-repeats-interval']) ? sanitize_text_field($output['jot-sched-repeats-interval']) : "",
                                                  'jot-sched-repeats-unit' => isset($output['jot-sched-repeats-unit']) ? sanitize_text_field($output['jot-sched-repeats-unit']) : ""
                                                  );
                                   
                      if ($mess_type=='jot-sms' && empty($message)) {
                         // Empty message
                         $error = 3;       
                      }
                                                       
                      if ($mess_type=='jot-call' && empty($message) && (empty($mess_audioid) || $mess_audioid == 'default' )) {
                         // Empty audio message
                         $error = 6;       
                      }
                               
                      $selected_provider = Joy_Of_Text_Plugin()->currentsmsprovidername;            
                                 
                      if ($selected_provider == 'default' || empty($selected_provider)) {
                          $error = 1;
                      }
                     
                     
                      if ($error == 0) {
                      
                                   // Save message type
                                   $smsmessage =  get_option('jot-plugin-messages');
                                   $smsmessage['jot-message-type'] = $mess_type;
                                      
                                   // Save message suffix
                                   $smsmessage['jot-message-suffix'] = $mess_suffix;
                                     
                                   // Save message content
                                   $smsmessage['jot-message'] = $message;
                                   
                                   // Save audio file ID
                                   $smsmessage['jot-message-audioid'] = $mess_audioid;
                                                          
                                   update_option('jot-plugin-messages',$smsmessage);
                                     
                                   // Append Message suffix
                                   if (!empty($mess_suffix)) {
                                         $fullmessage = $message . " " . $mess_suffix ;                     
                                   } else {
                                         $fullmessage = $message;    
                                   }
                                      
                                   // Batch id for this set of messages
                                   $batchid = uniqid(rand(), false);                    
                                  
                                   $mess_memsel_json = stripslashes($formdata['jot-message-grouplist']);
                                   $mess_memsel = json_decode($mess_memsel_json,true);
                     
                                   // Set schedule timestamp
                                   $temp_schedule_timestamp = '2000-01-01 00:00:01';
                                   $temp_schedule_timestamp = apply_filters('jot_queue_message_schedule',$temp_schedule_timestamp,$schedule_input_timestamp );
                                      
                                   if ($temp_schedule_timestamp == '2000-01-01 00:00:01') {
                                                // Not scheduled
                                                $schedule_timestamp = $temp_schedule_timestamp;
                                                $message_status = "P";
                                                $scheduled = false;
                                   } else {
                                                // Scheduled
                                                $schedule_timestamp = $temp_schedule_timestamp;
                                                $message_status = "S";
                                                $scheduled = true;                                       
                                   }
                                   
                                                                     
                                   foreach ($mess_memsel as $memsel ) {             
                                               
                                                list($jotgrpid,$jotmemid) = explode("-", $memsel, 2);
                                               
                                                $member = $this->get_member($jotmemid);
         
                                                 // Replace tags in message
                                                $finalmessage = "";
                                                $finalmessage = $this->get_replace_tags($fullmessage,$member);
                                                $finalmessage = apply_filters('jot-queue-message',$finalmessage);
                                                
                                                // Truncate message if over 640 characters
                                                if (strlen($finalmessage) > 640) {
                                                    $finalmessage = substr($finalmessage,0,640);
                                                }  
                                                
                                                switch ( $mess_type ) {
                                                             case 'jot-sms';
                                                                   $message_type = "S";
                                                                   $media_id = "";
                                                             break;
                                                             case 'jot-call';
                                                                   $message_type = "c";
                                                                   $media_id = $mess_audioid;
                                                             break;                                         
                                                }
                                               
                                                $data = array(
                                                           'jot_messqbatchid' => $batchid,
                                                           'jot_messqgrpid'   => $jotgrpid,
                                                           'jot_messqmemid'   => (int) $jotmemid,
                                                           'jot_messqcontent' => sanitize_text_field ($finalmessage),
                                                           'jot_messqtype'    => $message_type,
                                                           'jot_messqaudio'   => $media_id,
                                                           'jot_messqstatus'  => $message_status,
                                                           'jot_messsenderid' => $mess_senderid,
                                                           'jot_messqschedts' => $schedule_timestamp,
                                                           'jot_messqts'      => current_time('mysql', 1)
                                                           
                                                );
                                                                                       
                                                $table = $wpdb->prefix."jot_messagequeue";
                                                $success=$wpdb->insert( $table, $data );              
                                   }
                                   
                                   // if this is a scheduled queue then add record to schedule history.
                                   if ($scheduled == true) {
                                       
                                       // Write schedule history header.
                                       do_action("jot_sched_add_schedule",$batchid, $schedule_timestamp, $message_status, $message, count($mess_memsel), $schedule_description, $message_type);
                                      
                                       // If this is a repeated schedule, then schedule the repeats.       
                                       do_action("jot_sched_add_repeats", $batchid, $mess_memsel, $data, $schedule_input_timestamp, $schedule_input_repeat, false,$fullmessage, $schedule_description);
                                   }
                      }
                     
                     
                      switch ( $error ) {
                         case 0; // All fine
                                $msg = "";
                         break;
                         case 1; // No SMS provider set
                                $msg = __("Please select and configure an SMS provider.", "jot-plugin");         
                         break;
                         case 3; // Message is empty
                                $msg = __("Please enter a message.", "jot-plugin");         
                         break;
                         case 6; // No audio file selected.
                                $msg = __("Please enter a message or select an audio file for a call message", "jot-plugin");
                         break;
                         default;
                                $msg = "";
                         break;
                      }              
                    
                     $response = array('errormsg'=> esc_html($msg), 'errorcode' => $error, 'batchid' => $batchid, 'fullbatchsize' => count($mess_memsel), 'scheduled' => $scheduled);
                     echo json_encode($response);        
                     
                     die(); // this is required to terminate immediately and return a proper response            
                      
             }
         
          
             /**
              * JavaScript callback used to send the message entered by the admin user via Twilio
              */
             public function send_message_callback() {             
                   
                     $error = 0;                                  
                     
                     $formdata = $_POST['formdata'];
                     parse_str($formdata, $output);
                     $message     = sanitize_textarea_field($output['jot-plugin-messages']['jot-message']);
                     $mess_type   = sanitize_text_field($output['jot-plugin-messages']['jot-message-type']);
                     $mess_suffix = sanitize_text_field($output['jot-plugin-messages']['jot-message-suffix']);
                     $jotmemkey = sanitize_text_field($_POST['jotmemid']);
                     list($jotgrpid,$jotmemid) = explode("-", $jotmemkey, 2);
                     $member = $this->get_member($jotmemid);
                        
                     if (empty($message)) {
                         // Empty message
                         $error = 3;       
                     }                             
                     
                     if ($error == 0) {
                                   if (Joy_Of_Text_Plugin()->currentsmsprovider) {
                             
                                         // Save message type
                                         $smsmessage =  get_option('jot-plugin-messages');
                                         $smsmessage['jot-message-type'] = $mess_type;
                                            
                                         // Save message suffix
                                         $smsmessage['jot-message-suffix'] = $mess_suffix;
                                           
                                         // Save message content
                                         $smsmessage['jot-message'] = $message;
                                         
                                         update_option('jot-plugin-messages',$smsmessage);
                                           
                                         // Replace tags in message
                                         $message = $this->get_replace_tags($message,$member);
                                            
                                         // Append Message suffix
                                         if (!empty($mess_suffix)) {
                                               $fullmessage = $message . " " . $mess_suffix ;                     
                                         } else {
                                               $fullmessage = $message;    
                                         }
                                            
                                         $fullmessage = apply_filters('jot-send-message-messagetext',$fullmessage);
                             
                                         if (!empty($member)) {
                                                $message_type = sanitize_text_field($output['jot-plugin-messages']['jot-message-type']);
                                                switch ( $message_type  ) {
                                                   case 'jot-sms';
                                                      $message_error = Joy_Of_Text_Plugin()->currentsmsprovider->send_smsmessage($member['jot_grpmemnum'],$fullmessage);
                                                   break;
                                                   case 'jot-call';
                                                      $message_error = Joy_Of_Text_Plugin()->currentsmsprovider->send_callmessage($member['jot_grpmemnum'],$fullmessage);
                                                   break;
                                                }
                                         }
                                         if ($message_error['send_message_errorcode'] != 0) {
                                                //An error occurred sending the message
                                                $error = 999;
                                         }
                                         $all_send_errors[] = $message_error;
                                
                                            
                                   } else {
                                             $error = 1;
                                   }
                     }
                     switch ( $error ) {
                         case 0; // All fine
                                $msg = "";
                         break;
                         case 1; // No SMS provider set
                                $msg = __("Please select and configure an SMS provider.", "jot-plugin");         
                         break;
                         case 2; // No from number selected
                                $msg = __("Please select a 'from' number on the SMS provider tab.", "jot-plugin");         
                         break;
                         case 3; // Message is empty
                                $msg = __("Please enter a message.", "jot-plugin");         
                         break;
                         case 4; // No message recipients selectedMessage is empty
                                $msg = __("Please select your message recipients.", "jot-plugin");         
                         break;
                         case 5; // Error inserting message into database.
                                $msg = __("Error inserting call message into database", "jot-plugin");
                         break;
                         default;
                                $msg = "";
                         break;
                      }
              
                      //if ($error != 0 ) {
                      //    // Cleanup saved messages
                      //    $this->delete_saved_message($messageid);
                      //}
               
                     $response = array('errormsg'=> esc_html($msg), 'errorcode' => $error, 'send_errors'=>$all_send_errors );                     
                     echo json_encode($response);
                 
                     
                     die(); // this is required to terminate immediately and return a proper response
                 
          
               
          
             } // end send_message_callback
             
             function get_replace_tags($message,$member) {
                         
                   if (isset($member['jot_grpmemname'])) $message = str_replace('%name%',$member['jot_grpmemname'], $message);
                   if (isset($member['jot_grpmemnum']))  $message = str_replace('%number%',$member['jot_grpmemnum'], $message);
                   $message = str_replace('%lastpost%',$this->get_last_post(), $message);
                       
                   return apply_filters('jot_get_replace_tags',$message);   
             }
             
             function get_last_post() {
                    $args = array( 'numberposts' => '1' );
                    $recent_posts = wp_get_recent_posts( $args );
                    foreach( $recent_posts as $recent ){
                        return get_permalink($recent["ID"]);
                    }     
             }
             
             public function get_member($jotmemid) {
                 
                     //Get member details for given memberid
                     global $wpdb;
                     
                     $jotmemid = (int) $jotmemid;
                    
                     $table_members = $wpdb->prefix."jot_groupmembers";
                     $sql = " SELECT jot_grpmemid, jot_grpmemname, jot_grpmemnum " .
                            " FROM " . $table_members  .
                            " WHERE jot_grpmemid = %d";
                     $sqlprep = $wpdb->prepare($sql,$jotmemid);
                     $member = $wpdb->get_row( $sqlprep );
                     
                     if ($member) {
                        $memarr = array("jot_grpmemid" => $jotmemid, "jot_grpmemname" => $member->jot_grpmemname, "jot_grpmemnum" => $member->jot_grpmemnum );
                     } else {
                        $memarr = array("jot_grpmemid" => $jotmemid, "jot_grpmemname" => "Name Not Found", "jot_grpmemnum" => 0 );
                     }
                     return apply_filters('jot_get_member',$memarr);
             }
             
             public function save_call_message($messageid, $fullmessage) {
                   
                      global $wpdb;          
                      
                      $table = $wpdb->prefix."jot_messages";
                      $data = array(
                             'jot_messageid'   => sanitize_text_field ($messageid),
                             'jot_messagecontent' =>sanitize_text_field ($fullmessage)
                      );
                      $success=$wpdb->insert( $table, $data ); 
                      if ($wpdb->last_error !=null) {
                          $this->log_to_file(__METHOD__,"*** In save_call_message *** " . $messageid . " SQL error : " . $wpdb->last_error);   
                          return 5;
                      } else {
                          return 0;     
                      }
                                           
             }
             
             public function get_saved_message($messageid) {
                 
                     //Get message which will be played as a voice call
                     global $wpdb;
                     
                     $table = $wpdb->prefix."jot_messages";
                     $sql = " SELECT  jot_messagecontent " .
                            " FROM " . $table  .
                            " WHERE jot_messageid = '" . $messageid . "'";
                 
                     $message = $wpdb->get_row( $sql );
                     $messagecontent = $message->jot_messagecontent;
                                   
                     return apply_filters('jot_saved_message',$messagecontent);
             }
             
             /*
              *
              * Get groups for display in drop downs
              *
              */
             public function get_display_groups() {
                 
                                 //Get all groups that have been set as auto subscribe
                                 global $wpdb;
                                                                     
                                 $table = $wpdb->prefix."jot_groups";
                                 $sql = " SELECT  jot_groupid, jot_groupname" .
                                        " FROM " . $table  .
                                        " ORDER BY 2" ;   
                                
                                 $grplist = $wpdb->get_results( $sql );         
                                 
                                 return apply_filters('jot_get_jot_groups',$grplist);
                         
             }
             
             public function delete_saved_message($messageid) {
                 
                     //Delete saved message after voice call
                     global $wpdb;
                     $table = $wpdb->prefix."jot_messages";
                     $success=$wpdb->delete( $table, array( 'jot_messageid' => $messageid ) );
                     if ($wpdb->last_error != 0) {
                        $this->log_to_file(__METHOD__,"Error deleting saved messageid:" . $messageid . " " . $wpdb->last_error);
                     }
             }
             
             public function log_to_file($method,$text) {
                 
                $file = WP_PLUGIN_DIR. "/joy-of-text/log/jot-twilio-calls.log";
                        
                 if(!file_exists(dirname($file))) {
                     mkdir(dirname($file), 0755, true);            
                 } else {
                     file_put_contents($file, "==" . date('m/d/Y h:i:s a', time()) . " " . $method . "||" . $text . "\r\n"  ,FILE_APPEND);
                 }        
             }
    
   
    
             function call_curl($url,$data, $request_type) {
                     
                            
                          $TwilioAuth = get_option('jot-plugin-smsprovider');
                          $selected_provider = Joy_Of_Text_Plugin()->currentsmsprovidername;
                     
                          $sid = isset($TwilioAuth['jot-accountsid-' . $selected_provider]) ? $TwilioAuth['jot-accountsid-' . $selected_provider] : ""; 
                          $token = isset($TwilioAuth['jot-authsid-' . $selected_provider]) ? $TwilioAuth['jot-authsid-' . $selected_provider] : "";               
                         
                          
                          $args = array(
                                       'headers' => array(
                                         'Authorization' => 'Basic ' . base64_encode( $sid . ':' . $token )
                                       )
                                     );                                                                       
                                  
                          switch ( strtolower($request_type) ) {
                                       case 'post'; // post request                                                   
                                                    $args['body'] = $data;
                                                    $request = wp_remote_post( $url, $args );
                                       break;
                                       case 'get'; // get request                                                    
                                                    $request = wp_remote_get( $url, $args );
                                       break;                                      
                          }
                         
                          if( is_wp_error( $request ) ) {
                                       return false; // Request Error
                          }

                          $body = wp_remote_retrieve_body( $request );
                          
                          return $body;                        
                    
             }
    
    
             public function get_jot_groupname($jot_groupid) {
			
			//Get group name
			global $wpdb;
							    
			$table = $wpdb->prefix."jot_groups";
			$sql = " SELECT  jot_groupname" .
			       " FROM " . $table  .
			       " WHERE jot_groupid = %d";
			        
		       
		        $sqlprep = $wpdb->prepare($sql,$jot_groupid);
			$grp = $wpdb->get_row( $sqlprep );         
			
			if (!empty($grp->jot_groupname)) {
			    $grpname = $grp->jot_groupname;
			} else {
			    $grpname = "";
			}
			
			return apply_filters('jot_get_jot_groupname',$grpname);
			
             }

             // Check if curl is installed
             function is_curl_installed() {
                 if  (in_array  ('curl', get_loaded_extensions())) {
                    return true;
                 } else {
                    return false;
                 }
             }
             
             
             /*
             *
             * Get oldest - used in Get Started examples
             *
             */
            public function get_oldest_groups() {
                                
                                global $wpdb;
                                                                    
                                $table = $wpdb->prefix."jot_groups";
                                $sql = " SELECT  min(jot_groupid) as jot_groupid" .
                                       " FROM " . $table  ;   
                               
                                $grp = $wpdb->get_row( $sql );         
                                
                                return apply_filters('jot_get_oldest_group',$grp->jot_groupid);
                        
            }
            
            
            
            /*
             *
             *  Count number of messages still to be processed in the given batch
             *
             */
             public function count_queue_batch($batchid, $status) {             
                                  
                      global $wpdb;           
                                   
                      $table = $wpdb->prefix."jot_messagequeue";            
                      
                      $sql = " SELECT count(*) as messcount" .
                            " FROM " . $table  .
                            " WHERE jot_messqstatus <> %s " . 
                            " AND jot_messqbatchid = %s";
                      
                      
                      $sqlprep = $wpdb->prepare($sql, $status, $batchid);
                      
                      $batchcount = $wpdb->get_row( $sqlprep );  
                              
                      $remaining_count = isset($batchcount->messcount) ? $batchcount->messcount : -1;
                                   
                      return apply_filters('jot_count_queue_batch',$remaining_count);  
            
             }
             
             
             /*
             *    
             * Update status of processed message 
             *
             */
             public function update_queue_status($queueid, $status) {
                     
                    global $wpdb;
                    $response = "";                          
                    $table = $wpdb->prefix."jot_messagequeue";
                    
                    $data = array(
                              'jot_messqstatus'   => $status                             
                              );
                    $wpdb->update( $table, $data, array( 'jot_messqid' =>  $queueid ) );
                  
                    
             }
            

 
} // end class
 
