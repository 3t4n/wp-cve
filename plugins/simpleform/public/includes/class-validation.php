<?php
	
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Defines the class that deals with the form validation.

 *
 * @since 2.1.7
 */

class SimpleForm_Validation {

	/**
	 * Class constructor
	 *
	 * @since  2.1.7
	 */

	public function __construct() {

   	    // Prevent duplicate form submission
   	    add_filter('sform_block_duplicate', array($this, 'block_duplicate'), 10, 5 );

	}

	/**
	 * Sanitaze form data
     *
	 * @since 2.1.7
	 */
	 
	public static function data_sanitization() {
		
       $values = array();
       
 	   $values['form'] = isset($_POST['form-id']) ? absint($_POST['form-id']) : '1';
       $values['name'] = isset($_POST['sform-name']) ? sanitize_text_field($_POST['sform-name']) : '';
       $values['lastname'] = isset($_POST['sform-lastname']) ? sanitize_text_field($_POST['sform-lastname']) : '';
       $values['email'] = isset($_POST['sform-email']) ? sanitize_email($_POST['sform-email']) : '';
       $values['phone'] = isset($_POST['sform-phone']) ? sanitize_text_field($_POST['sform-phone']) : '';
       $values['subject'] = isset($_POST['sform-subject']) ? sanitize_text_field(str_replace("\'", "’", $_POST['sform-subject'])) : ''; // WHY str_replace ..... ?
       $values['message'] = isset($_POST['sform-message']) ? sanitize_textarea_field($_POST['sform-message']) : '';
       $values['consent'] = isset($_POST['sform-consent']) ? 'true' : 'false';
       $values['captcha_one'] = isset($_POST['captcha_one']) ? absint($_POST['captcha_one']) : 0;
       $values['captcha_two'] = isset($_POST['captcha_two']) ? absint($_POST['captcha_two']) : 0;
       $values['captcha_question'] = $values['captcha_one'] != 0 && $values['captcha_two'] != 0 ? $values['captcha_one'] + $values['captcha_two'] : '';
       $values['captcha_answer'] = isset($_POST['sform-captcha']) ? absint($_POST['sform-captcha']) : '';
       $values['honeyurl'] = isset($_POST['url-site']) ? sanitize_text_field($_POST['url-site']) : '';
       $values['honeytel'] = isset($_POST['hobbies']) ? sanitize_text_field($_POST['hobbies']) : '';
       $values['honeycheck'] = isset($_POST['contact-phone']) ? 'true' : 'false';
      
       return $values;
  
	}

	/**
	 * Extract submitter data
     *
	 * @since 2.1.7
	 */
	 
	public static function submitter_data($name,$lastname,$email) {
		
       $submitter_data = array();
       
       if ( ! empty($name) ) { $requester_name = $name; }
       else {
	     if ( is_user_logged_in() ) {
		   global $current_user;
		   $requester_name = ! empty($current_user->user_name) ? $current_user->user_name : $current_user->display_name;
         }
         else { $requester_name = ''; }
       }
            
       if ( ! empty($lastname) ) { $requester_lastname = ' ' . $lastname; }
       else {
	     if ( is_user_logged_in() ) {
		   global $current_user;
		   $requester_lastname = ! empty($current_user->user_lastname) ? ' ' . $current_user->user_lastname : '';
         }
         else { $requester_lastname = ''; }
       }

       $submitter = $requester_name != '' || $requester_lastname != '' ? trim($requester_name . $requester_lastname) : __( 'Anonymous', 'simpleform' );
      
	   if ( empty($email) ) {     	      
	     if ( is_user_logged_in() ) {
		   global $current_user;
		   $email = $current_user->user_email;
         }
         else { $email = ''; }
       }
      
       $submitter_data['name'] = $requester_name; 
       $submitter_data['lastname'] = $requester_lastname; 
       $submitter_data['submitter'] = $submitter; 
       $submitter_data['email'] = $email; 
      
       return $submitter_data;
  
	}

	/**
	 * Prevent duplicate form submission
     *
	 * @since 2.1.7
	 */
	
	public static function block_duplicate($errors,$form_id,$submitter,$email,$message) {
		
       $util = new SimpleForm_Util();
       $settings = $util->sform_settings($form_id);
	   $ajax = ! empty( $settings['ajax_submission'] ) ? esc_attr($settings['ajax_submission']) : 'false'; 
	   $duplicate = ! empty( $settings['duplicate'] ) ? esc_attr($settings['duplicate']) : 'true';	
       $last_request = get_option("sform_last_message") != false ? get_option("sform_last_message") : '';
      
       if ( $duplicate == 'true' && ! empty($last_request) ) { 	
	            
	     $separator = '<tr><td class="message">' . __('Message', 'simpleform') . ':</td><td>';
	     $previous_request = isset(explode($separator, $last_request)[1]) ? str_replace('</td></tr></tbody></table>', '', explode($separator, $last_request)[1]) : '';	  
		 
		 if ( $previous_request == $message ) {
	       
	       $string1 = '<table class="table-msg"><tbody><tr><td>'. __('From', 'simpleform') .':</td><td>';
		   $string2 = '</td></tr>';  
	       $submitter_data = explode($string2,str_replace($string1, '', explode($separator, $last_request)[0]))[0];
	      
		   if ( strpos($submitter_data, $submitter) !== false && strpos($submitter_data, $email) !== false ) {
	         
	         $error = ! empty( $settings['duplicate_error'] ) ? stripslashes(esc_attr($settings['duplicate_error'])) : __('The form has already been submitted. Thanks!', 'simpleform');
	         
 	         if ( $ajax != 'false' ) {
 	           $errors['error'] = true;
               $errors['showerror'] = true;
               $errors['field_focus'] = false; 
               $errors['notice'] = $error; 
	         }
	         else {
		       $errors .= $form_id.';duplicate_form;';
	         }
	       
	       }
	       
	     }
	     
	   }
	   
       return $errors;
  
	}	
	
	/**
	 * Form fields validation
     *
	 * @since 2.1.7
	 */

	public static function form_errors($form_id,$settings,$name,$lastname,$email,$phone,$subject,$message,$consent,$captcha_question,$captcha_answer,$honeyurl,$honeytel,$honeycheck) {
	   
       $util = new SimpleForm_Util();
       $attributes = $util->sform_attributes($form_id);
	   $ajax = ! empty( $settings['ajax_submission'] ) ? esc_attr($settings['ajax_submission']) : 'false'; 
       $errors = $ajax == 'false' ? '' : array();
       
       // Make honeypot fields validation first 
       if ( ! empty($honeyurl) || ! empty($honeytel) || $honeycheck == 'true' ) { 
	      $message = ! empty( $settings['honeypot_error'] ) ? stripslashes(esc_attr($settings['honeypot_error'])) : __('Error occurred during processing data', 'simpleform');
 	      if ( $ajax != 'false' ) {
 	        $errors['error'] = true;
            $errors['showerror'] = true;
            $errors['field_focus'] = false; 
            $errors['notice'] = $message; 
	      }
	      else {
		    $errors .= $form_id.';form_honeypot;';		      
	      }
	   }
	   
	   // Continue with the fields validation
	   if ( empty($errors) ) {
	   
	     $outside_error = ! empty( $settings['outside_error'] ) ? esc_attr($settings['outside_error']) : 'bottom';
         $showerror = $outside_error == 'top' || $outside_error == 'bottom' ? true : false;
         $empty_fields = ! empty( $settings['empty_fields'] ) ? stripslashes(esc_attr($settings['empty_fields'])) : __( 'There were some errors that need to be fixed', 'simpleform' );
         $characters_length = ! empty( $settings['characters_length'] ) ? esc_attr($settings['characters_length']) : 'true';
         
         // Name validation
         $name_field = ! empty( $attributes['name_field'] ) ? esc_attr($attributes['name_field']) : 'visible';
         $name_requirement = ! empty( $attributes['name_requirement'] ) ? esc_attr($attributes['name_requirement']) : 'required';

         if ( $name_field == 'visible' || $name_field == 'registered' && is_user_logged_in() || $name_field == 'anonymous' && ! is_user_logged_in() ) {  
          
           $name_length = isset( $attributes['name_minlength'] ) ? esc_attr($attributes['name_minlength']) : '2';
           $name_regex = '#[0-9]+#';
           $name_numeric_error = $characters_length == 'true' && ! empty( $settings['incomplete_name'] ) && preg_replace('/[^0-9]/', '', $settings['incomplete_name']) == $name_length ? stripslashes(esc_attr($settings['incomplete_name'])) : sprintf( __('Please enter at least %d characters', 'simpleform' ), $name_length );
           $name_generic_error = $characters_length != 'true' && ! empty( $settings['incomplete_name'] ) && preg_replace('/[^0-9]/', '', $settings['incomplete_name']) == '' ? stripslashes(esc_attr($settings['incomplete_name'])) : __('Please type your full name', 'simpleform' );
           $error_name_label = $characters_length == 'true' ? $name_numeric_error : $name_generic_error;
           $error_invalid_name_label = ! empty( $settings['invalid_name'] ) ? stripslashes(esc_attr($settings['invalid_name'])) : __( 'The name contains invalid characters', 'simpleform' );
           $error = ! empty( $settings['name_error'] ) ? stripslashes(esc_attr($settings['name_error'])) : __('Error occurred validating the name', 'simpleform');
           $error_type = ! empty($name) && preg_match($name_regex,$name) ? $error_invalid_name_label : $error_name_label;
           $error_code = ! empty($name) && preg_match($name_regex,$name) ? ';name_invalid;' : ';name;';

           if ( ( $name_requirement == 'required' && empty($name) ) || ( ! empty($name) && ( strlen($name) < $name_length || preg_match($name_regex,$name) ) ) ) {
 	         if ( $ajax != 'false' ) {
               $errors['error'] = true;
               $errors['showerror'] = $showerror;
	           $errors['name'] = $error_type;
               $errors['notice'] = !isset($errors['error']) ? $error : $empty_fields;
	         }
	         else {
		       $errors .= $form_id.$error_code;
	         }
           }
        
         }
 
         // Lastname validation
         $lastname_field = ! empty( $attributes['lastname_field'] ) ? esc_attr($attributes['lastname_field']) : 'hidden';
         $lastname_requirement = ! empty( $attributes['lastname_requirement'] ) ? esc_attr($attributes['lastname_requirement']) : 'optional';

         if ( $lastname_field == 'visible' || $lastname_field == 'registered' && is_user_logged_in() || $lastname_field == 'anonymous' && ! is_user_logged_in() ) {  

           $lastname_length = isset( $attributes['lastname_minlength'] ) ? esc_attr($attributes['lastname_minlength']) : '2';
           $lastname_regex = '#[0-9]+#';
           $error_invalid_lastname_label = ! empty( $settings['invalid_lastname'] ) ? stripslashes(esc_attr($settings['invalid_lastname'])) : __( 'The last name contains invalid characters', 'simpleform' );
           $error = ! empty( $settings['lastname_error'] ) ? stripslashes(esc_attr($settings['lastname_error'])) : __('Error occurred validating the last name', 'simpleform');
           $lastname_numeric_error = $characters_length == 'true' && ! empty( $settings['incomplete_lastname'] ) && preg_replace('/[^0-9]/', '', $settings['incomplete_lastname']) == $lastname_length ? stripslashes(esc_attr($settings['incomplete_lastname'])) : sprintf( __('Please enter at least %d characters', 'simpleform' ), $lastname_length );
           $lastname_generic_error = $characters_length != 'true' && ! empty( $settings['incomplete_lastname'] ) && preg_replace('/[^0-9]/', '', $settings['incomplete_lastname']) == '' ? stripslashes(esc_attr($settings['incomplete_lastname'])) : __('Please type your full last name', 'simpleform' );
           $error_lastname_label = $characters_length == 'true' ? $lastname_numeric_error : $lastname_generic_error;
           $error_type = ! empty($lastname) && preg_match($lastname_regex,$lastname) ? $error_invalid_lastname_label : $error_lastname_label;
           $error_code = ! empty($lastname) && preg_match($lastname_regex,$lastname) ? ';lastname_invalid;' : ';lastname;';

           if ( ( $lastname_requirement == 'required' && empty($lastname) ) || ( ! empty($lastname) && ( strlen($lastname) < $lastname_length || preg_match($lastname_regex,$lastname) ) ) ) {
 	         if ( $ajax != 'false' ) {
               $errors['error'] = true;
               $errors['showerror'] = $showerror;
	           $errors['lastname'] = $error_type;
               $errors['notice'] = !isset($errors['error']) ? $error : $empty_fields; 
	         }
	         else {
		       $errors .= $form_id.$error_code;
	         }
           }
        
         }
         
         // Email validation
         $email_field = ! empty( $attributes['email_field'] ) ? esc_attr($attributes['email_field']) : 'visible';
         $email_requirement = ! empty( $attributes['email_requirement'] ) ? esc_attr($attributes['email_requirement']) : 'required';

         if ( $email_field == 'visible' || $email_field == 'registered' && is_user_logged_in() || $email_field == 'anonymous' && ! is_user_logged_in() ) {  

           $error_email_label = ! empty( $settings['invalid_email'] ) ? stripslashes(esc_attr($settings['invalid_email'])) : __( 'Please enter a valid email', 'simpleform' );
           $error = ! empty( $settings['email_error'] ) ? stripslashes(esc_attr($settings['email_error'])) : __('Error occurred validating the email', 'simpleform');

           if ( ( $email_requirement == 'required' && empty($email) ) || ( ! empty($email) && ! is_email($email) ) ) {
  	         if ( $ajax != 'false' ) {
               $errors['error'] = true;
               $errors['showerror'] = $showerror;
	           $errors['email'] = $error_email_label;
               $errors['notice'] = !isset($errors['error']) ? $error : $empty_fields; 
	         }
	         else {
		       $errors .= $form_id.';email;';
	         }
           }
        
         }
         
         // Phone validation
         $phone_field = ! empty( $attributes['phone_field'] ) ? esc_attr($attributes['phone_field']) : 'hidden';
         $phone_requirement = ! empty( $attributes['phone_requirement'] ) ? esc_attr($attributes['phone_requirement']) : 'optional';  
         
         if ( $phone_field == 'visible' || $phone_field == 'registered' && is_user_logged_in() || $phone_field == 'anonymous' && ! is_user_logged_in() ) {
           
           $empty_phone = ! empty( $settings['empty_phone'] ) ? stripslashes(esc_attr($settings['empty_phone'])) : __( 'Please provide your phone number', 'simpleform' );
           $error_phone_label = ! empty( $settings['invalid_phone'] ) ? stripslashes(esc_attr($settings['invalid_phone'])) : __( 'The phone number contains invalid characters', 'simpleform' );
           $error = ! empty( $settings['phone_error'] ) ? stripslashes(esc_attr($settings['phone_error'])) : __( 'Error occurred validating the phone number', 'simpleform' );
           $phone_regex = '/^[0-9\-\(\)\/\+\s]*$/'; // allowed characters: -()/+ and space
           $error_type = ! empty($phone) && ! preg_match($phone_regex,$phone) ? $error_phone_label : $empty_phone;
           $error_code = ! empty($phone) && ! preg_match($phone_regex, $phone) ? ';phone_invalid;' : ';phone;';
      
           if ( ( $phone_requirement == 'required' && empty($phone) ) || ( ! empty($phone) && ! preg_match($phone_regex,$phone) ) ) {
  	         if ( $ajax != 'false' ) {
               $errors['error'] = true;
               $errors['showerror'] = $showerror;
	           $errors['phone'] = $error_type;
               $errors['notice'] = !isset($errors['error']) ? $error : $empty_fields; 
	         }
	         else {
		       $errors .= $form_id.$error_code;
	         }
           }
        
         }
         
         // Subject validation
         $subject_field = ! empty( $attributes['subject_field'] ) ? esc_attr($attributes['subject_field']) : 'visible';
         $subject_requirement = ! empty( $attributes['subject_requirement'] ) ? esc_attr($attributes['subject_requirement']) : 'required';
                   
         if ( $subject_field == 'visible' || $subject_field == 'registered' && is_user_logged_in() || $subject_field == 'anonymous' && ! is_user_logged_in() ) { 
                     
           $subject_length = isset( $attributes['subject_minlength'] ) ? esc_attr($attributes['subject_minlength']) : '5';
           $subject_regex = '/^[^#$%&=+*{}|<>]+$/';
           $error_invalid_subject_label = ! empty( $settings['invalid_subject'] ) ? stripslashes(esc_attr($settings['invalid_subject'])) : esc_attr__( 'Enter only alphanumeric characters and punctuation marks', 'simpleform' );
           $error = ! empty( $settings['subject_error'] ) ? stripslashes(esc_attr($settings['subject_error'])) : __('Error occurred validating the subject', 'simpleform');
           $subject_numeric_error = $characters_length == 'true' && ! empty( $settings['incomplete_subject'] ) && preg_replace('/[^0-9]/', '', $settings['incomplete_subject']) == $subject_length ? stripslashes(esc_attr($settings['incomplete_subject'])) : sprintf( __('Please enter a subject at least %d characters long', 'simpleform' ), $subject_length );
           $subject_generic_error = $characters_length != 'true' && ! empty( $settings['incomplete_subject'] ) && preg_replace('/[^0-9]/', '', $settings['incomplete_subject']) == '' ? stripslashes(esc_attr($settings['incomplete_subject'])) : __('Please type a short and specific subject', 'simpleform' );
           $error_subject_label = $characters_length == 'true' ? $subject_numeric_error : $subject_generic_error;
           $error_type = ! empty($subject) && ! preg_match($subject_regex,$subject) ? $error_invalid_subject_label : $error_subject_label;
           $error_code = ! empty($subject) && ! preg_match($subject_regex,$subject) ? ';subject_invalid;' : ';subject;';

           if ( ( $subject_requirement == 'required' && empty($subject) ) || ( ! empty($subject) && ( strlen($subject) < $subject_length || ! preg_match($subject_regex,$subject) ) ) ) {
  	         if ( $ajax != 'false' ) {
               $errors['error'] = true;
               $errors['showerror'] = $showerror;
	           $errors['subject'] = $error_type;
               $errors['notice'] = !isset($errors['error']) ? $error : $empty_fields; 
	         }
	         else {
		       $errors .= $form_id.$error_code;
	         }
           }
        
         }
         
         // Message validation
         $message_length = isset( $attributes['message_minlength'] ) ? esc_attr($attributes['message_minlength']) : '10';
         $message_regex = '/^[^#$%&=+*{}|<>]+$/';
         $error_invalid_message_label = ! empty( $settings['invalid_message'] ) ? stripslashes(esc_attr($settings['invalid_message'])) : esc_attr__( 'Enter only alphanumeric characters and punctuation marks', 'simpleform' );
         $error = ! empty( $settings['message_error'] ) ? stripslashes(esc_attr($settings['message_error'])) : __('Error occurred validating the message', 'simpleform');
         
         $message_numeric_error = $characters_length == 'true' && ! empty( $settings['incomplete_message'] ) && preg_replace('/[^0-9]/', '', $settings['incomplete_message']) == $message_length ? stripslashes(esc_attr($settings['incomplete_message'])) : sprintf( __('Please enter a message at least %d characters long', 'simpleform' ), $message_length );
         $message_generic_error = $characters_length != 'true' && ! empty( $settings['incomplete_message'] ) && preg_replace('/[^0-9]/', '', $settings['incomplete_message']) == '' ? stripslashes(esc_attr($settings['incomplete_message'])) : __('Please type a clearer message so we can respond appropriately', 'simpleform' );
         $error_message_label = $characters_length == 'true' ? $message_numeric_error : $message_generic_error;
         $error_type = ! empty($message) && ! preg_match($message_regex,$message) ? $error_invalid_message_label : $error_message_label;
         $error_code = ! empty($message) && ! preg_match($message_regex,$message) ? ';message_invalid;' : ';message;';

         if ( empty($message) || ( ! empty($message) && ( strlen($message) < $message_length || ! preg_match($message_regex,$message) ) ) ) {
  	         if ( $ajax != 'false' ) {
               $errors['error'] = true;
               $errors['showerror'] = $showerror;
  	           $errors['message'] = $error_type;
               $errors['notice'] = !isset($errors['error']) ? $error : $empty_fields; 
 	         }
	         else {
		       $errors .= $form_id.$error_code;
	         }
        }
  
         // Consent validation
         $consent_field = ! empty( $attributes['consent_field'] ) ? esc_attr($attributes['consent_field']) : 'visible';
         $consent_requirement = ! empty( $attributes['consent_requirement'] ) ? esc_attr($attributes['consent_requirement']) : 'required'; 

         if ( $consent_field == 'visible' || $consent_field == 'registered' && is_user_logged_in() || $consent_field == 'anonymous' && ! is_user_logged_in() ) {  

           $error = ! empty( $settings['consent_error'] ) ? stripslashes(esc_attr($settings['consent_error'])) : __( 'Please accept our privacy policy before submitting form', 'simpleform' );

           if ( $consent_requirement == 'required' && $consent == "false" ) {
  	         if ( $ajax != 'false' ) {
               $errors['error'] = true;
               $errors['showerror'] = $showerror;
	           $errors['consent'] = $error;
               $errors['notice'] = !isset($errors['error']) ? $error : $empty_fields; 
  	         }
	         else {
		       $errors .= $form_id.';consent;';
	         }
          }
        
         }
         
         // Captcha validation
         $captcha_field = ! empty( $attributes['captcha_field'] ) ? esc_attr($attributes['captcha_field']) : 'hidden';   
	     if ( $captcha_field == 'visible' || $captcha_field == 'registered' && is_user_logged_in() || $captcha_field == 'anonymous' && ! is_user_logged_in() ) { 
    
  	       if ( has_filter('recaptcha_challenge') ) {
             $errors = apply_filters('recaptcha_challenge',$errors,$form_id,$captcha_question,$captcha_answer);
           }
           
	       else {
             $error_captcha_label = ! empty( $settings['invalid_captcha'] ) ? stripslashes(esc_attr($settings['invalid_captcha'])) : esc_attr__( 'Please enter a valid captcha value', 'simpleform' );
	         $error = ! empty( $settings['captcha_error'] ) ? stripslashes(esc_attr($settings['captcha_error'])) : __('Error occurred validating the captcha', 'simpleform');
	         
	         if ( ! empty($captcha_question) && ( empty($captcha_answer) || $captcha_question != $captcha_answer ) ) { 
  	           if ( $ajax != 'false' ) {
                 $errors['error'] = true;
                 $errors['showerror'] = $showerror;
                 $errors['captcha'] = $error_captcha_label;       		               
                 $errors['notice'] = !isset($errors['error']) ? $error : $empty_fields;
                 //$errors['notice'] = count($errors) < 5 ? $error : $empty_fields;
	           }
	           else {
		         $errors .= $form_id.';captcha;';
	           }
	           
             }
	       }
         
         }
         
	   }
	   
       return $errors;
  
	}	
		
}

new SimpleForm_Validation();