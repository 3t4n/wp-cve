<?php
	
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Defines the class that deals with the notifications management.
 *
 * @since      2.1.7
 */

class SimpleForm_Notifications {

	/**
	 * Class constructor
	 *
	 * @since  2.1.7
	 */

	public function __construct() {

   	    // Update the last message
   	    add_action('sform_before_last_message_updating', array($this, 'update_before_last_message'), 10, 3 );
   	    // Update the last message
   	    add_action('sform_last_message_updating', array($this, 'update_last_message'), 10, 5 );
   	    // Update the submissions number
   	    add_action('sform_entries_updating', array($this, 'update_entries'), 10, 2 );
   	       	    
	}

	/**
	 * Update the submissions number
     *
	 * @since    2.1.7
	 */
	
	public static function update_entries($form_id,$form_data) {
		
	   global $wpdb;
       $relocation = esc_sql($form_data->relocation);
       $moveto = esc_sql($form_data->moveto);
       $to_be_moved = esc_sql($form_data->to_be_moved);
       $onetime_moving = esc_sql($form_data->onetime_moving);
       $moving = $relocation == '1' && $moveto != '0' && $to_be_moved == 'next' && $onetime_moving == '0' ? true : false;
	   	
	   if ( $moving == true ) { 	      
	     $count_entries = $wpdb->get_var( $wpdb->prepare( "SELECT entries FROM {$wpdb->prefix}sform_shortcodes WHERE id = %d", $moveto ) );
  	     $update_entries = $count_entries + 1;
  	     $wpdb->update($wpdb->prefix . 'sform_shortcodes', array('entries' => $update_entries), array('id' => $moveto ) ); 
  	     $update_moved = esc_attr($form_data->moved_entries) + 1;
  	     $wpdb->update($wpdb->prefix . 'sform_shortcodes', array('moved_entries' => $update_moved), array('id' => $form_id ) ); 		    
	   }
	   else { 
  	     $update_entries = esc_attr($form_data->entries) + 1;
  	     $wpdb->update($wpdb->prefix . 'sform_shortcodes', array('entries' => $update_entries), array('id' => $form_id ) ); 
	   }

	}	
    
	/**
	 * Display the submission date according to the used date and time format
     *
	 * @since    2.1.7
	 */

	public static function submission_date($date) {
		
       $tzcity = get_option('timezone_string'); 
       $tzoffset = get_option('gmt_offset');
       
       if ( ! empty($tzcity))  { 
       $current_time_timezone = date_create('now', timezone_open($tzcity));
       $timezone_offset =  date_offset_get($current_time_timezone);
       $submission_timestamp = $date + $timezone_offset; 
       }
       else { 
       $timezone_offset =  $tzoffset * 3600;
       $submission_timestamp = $date + $timezone_offset;  
       }
       
       $submission_date = date_i18n( get_option( 'date_format' ), $submission_timestamp ) . ' ' . __('at', 'simpleform') . ' ' . date_i18n( get_option('time_format'), $submission_timestamp );
       
       return $submission_date;
  
	}

	/**
	 * Build the alert message
     *
	 * @since    2.1.7
	 */

	public static function alert_message($submitter,$email,$phone,$entry_date,$flagged,$subject,$message) {
		
       $from = '<b>'. __('From', 'simpleform') .':</b>&nbsp;&nbsp;' . $submitter;       
       $from .= ! empty($email) ? '&nbsp;&nbsp;&lt;&nbsp;' . $email . '&nbsp;&gt;' : '';
       $from .= ! empty($phone) ? '<br><b>'. __('Phone', 'simpleform') .':</b>&nbsp;&nbsp;' . $phone : '';
              
       $flagged_subject = ! empty($flagged) ? '<br><b>'. __('Subject', 'simpleform') .':</b>&nbsp;&nbsp;' . $flagged : '';
       $submission_subject = ! empty($subject) ? '<br><b>'. __('Subject', 'simpleform') .':</b>&nbsp;&nbsp;' . $flagged . $subject : $flagged_subject;
      
       $alert_message = '<div style="">' . $from . '<br><b>'. __('Sent', 'simpleform') .':</b>&nbsp;&nbsp;' . $entry_date . $submission_subject  . '<p>' . nl2br($message) . '</p></div>';  
      
       return $alert_message;
  
	}
	
	/**
	 * Assemble submission data to show the last message
     *
	 * @since    2.1.7
	 */

	public static function last_message($submitter,$email,$phone,$entry_date,$flagged,$subject,$message) {
		
       $mail_data = ! empty($email) ? '&nbsp;&nbsp;&lt;&nbsp;<a href="mailto:'. $email .'">' . $email . '</a>&nbsp;&gt;' : '';
       $phone_data = ! empty($phone) ? '<tr><td>'.__('Phone', 'simpleform') .':</td><td>' . $phone .'</td></tr>' : '';
	   $flagged_subject = !empty($flagged) ? '<tr><td>'.__('Subject', 'simpleform') .':</td><td>' . $flagged .'</td></tr>' : '';
	   $message_subject = !empty($subject) ? '<tr><td>'.__('Subject', 'simpleform') .':</td><td>' . $flagged . $subject .'</td></tr>' : $flagged_subject;
	   
	   $last_message = '<table class="table-msg"><tbody><tr><td>'. __('From', 'simpleform') .':</td><td>' . $submitter. $mail_data . '</td></tr>';
	   $last_message .= $phone_data;
	   $last_message .= '<tr><td>' . __('Date', 'simpleform') .':</td><td>' . $entry_date . '</td></tr>';
	   $last_message .= $message_subject;
	   $last_message .= '<tr><td class="message">' . __('Message', 'simpleform') . ':</td><td>' .  $message . '</td></tr></tbody></table>';

       $current_last_message = get_option("sform_last_message") != false ? get_option("sform_last_message") : '';
       if ( ! empty($current_last_message) ) {
           update_option('sform_before_last_message', $current_last_message);
       }

       return $last_message;
  
	}	
	
	/**
	 * Update the before last message
     *
	 * @since    2.1.7
	 */
	
	public static function update_before_last_message($form_id,$moving,$moveto) {
		
       // Check if a forwarding is in progress
       if ( $moving == true ) {
         $previous_direct_message = get_option("sform_direct_last_{$form_id}_message") != false ? get_option("sform_direct_last_{$form_id}_message") : '';
         if ( ! empty($previous_direct_message) ) {
           update_option('sform_direct_before_last_'.$form_id.'_message', $previous_direct_message);
         }

         $previous_last_message = get_option("sform_moved_last_{$moveto}_message") != false ? get_option("sform_moved_last_{$moveto}_message") : '';
         if ( ! empty($previous_last_message) ) {
           update_option('sform_moved_before_last_'.$moveto.'_message', $previous_last_message);
         }
       }

       else {
         $previous_last_message = get_option("sform_last_{$form_id}_message") != false ? get_option("sform_last_{$form_id}_message") : '';
         if ( ! empty($previous_last_message) ) {
           update_option('sform_before_last_'.$form_id.'_message', $previous_last_message);
         }
       }
  
	}	

	/**
	 * Update the last message
     *
	 * @since    2.1.7
	 */
	
	public static function update_last_message($form_id,$moving,$moveto,$submission_date,$last_message) {
		
       update_option( 'sform_last_message', $last_message );

       $timestamp = strtotime($submission_date);
       $message_data = $timestamp . '#' . $last_message;
              
       // Check if a forwarding is in progress
       if ( $moving == true ) {
         update_option('sform_direct_last_'.$form_id.'_message', $message_data);
         update_option('sform_moved_last_'.$moveto.'_message', $message_data);
       }

       else {
         update_option('sform_last_'.$form_id.'_message', $message_data);
       }
  
	}	

	/**
	 * Build the auto responder message
     *
	 * @since    2.1.7
	 */

	public static function autoresponder_message($form_id,$moving,$moveto,$name,$lastname,$email,$phone,$object,$message,$reference_number,$notifications_settings) {
		
	   $form = $moving == true && $notifications_settings == true ? $moveto : $form_id;
	   
       if ( $form == '1' ) { 
 	         $settings = get_option('sform_settings');
       } else { 
             $settings_option = get_option('sform_'.$form.'_settings');
             $settings = $settings_option != false ? $settings_option : get_option('sform_settings');
       }
	   
       $code_name = '[name]';
       $autoresponder = ! empty( $settings['autoresponder_message'] ) ? stripslashes(wp_kses_post($settings['autoresponder_message'])) : printf(__( 'Hi %s', 'simpleform' ),$code_name) . ',<p>' . __( 'We have received your request. It will be reviewed soon and we\'ll get back to you as quickly as possible.', 'simpleform' ) . __( 'Thanks,', 'simpleform' ) . __( 'The Support Team', 'simpleform' );  
               
	   $tags = array( '[name]','[lastname]','[email]','[phone]','[subject]','[message]','[submission_id]' );
       $values = array( $name,$lastname,$email,$phone,$object,$message,$reference_number );
       $autoresponder_message = str_replace($tags,$values,$autoresponder);
      
       return $autoresponder_message;
  
	}
	
}

new SimpleForm_Notifications();