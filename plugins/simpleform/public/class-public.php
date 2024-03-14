<?php

/**
 * Defines the public-specific functionality of the plugin.
 *
 * @since 1.0
 */

class SimpleForm_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since 1.0
	 */

	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since 1.0
	 */

	private $version;

	/**
	 * Initialize the class and set its properties for later use.
	 *
	 * @since 1.0
	 */

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 1.0
	 */

	public function enqueue_styles() {

        wp_register_style('sform-public-style', plugins_url( 'css/public-min.css', __FILE__ ),[], filemtime( plugin_dir_path( __FILE__ ) . 'css/public-min.css' ) );

        $settings = get_option('sform_settings');
        $stylesheet = ! empty( $settings['stylesheet'] ) ? esc_attr($settings['stylesheet']) : 'false';
        $cssfile = ! empty( $settings['stylesheet_file'] ) ? esc_attr($settings['stylesheet_file']) : 'false';
   	    $additionalStyle = get_option('sform_additional_style') != false ? get_option('sform_additional_style') : '';
 	    $blockStyle = get_option('sform_block_style') != false ? get_option('sform_block_style') : '';

        // Attach extra styles
        if ( $stylesheet == 'false' ) {
          wp_add_inline_style( 'sform-public-style', $additionalStyle );
          wp_add_inline_style( 'sform-public-style', $blockStyle );
        }

        else {
	      wp_register_style( 'simpleform-style', plugins_url( 'css/simpleform-style.css', __FILE__ ) );
          wp_add_inline_style( 'simpleform-style', $additionalStyle );
          wp_add_inline_style( 'simpleform-style', $blockStyle );
          // Register custom style and attach extra styles
          if ( $cssfile == 'true' && file_exists( get_theme_file_path( '/simpleform/custom-style.css' ) ) ) {
            wp_register_style( 'sform-custom-style', get_theme_file_uri( '/simpleform/custom-style.css'), __FILE__ );
          }
        }

    }

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since 1.0
	 */

	public function enqueue_scripts() {

 	    wp_register_script( 'sform_form_script', plugins_url( 'js/script-min.js', __FILE__ ), array( 'jquery' ), filemtime( plugin_dir_path( __FILE__ ) . 'js/script-min.js' ) );
 	    wp_register_script( 'sform_public_script', plugins_url( 'js/public-min.js', __FILE__ ), array( 'jquery' ), filemtime( plugin_dir_path( __FILE__ ) . 'js/public-min.js' ) ); 	    
	    wp_localize_script( 'sform_public_script', 'ajax_sform_processing', array('ajaxurl' => admin_url('admin-ajax.php')) );

        // Register custom script
        if ( file_exists( get_theme_file_path( '/simpleform/custom-script.js' ) ) ) {
	      wp_register_script( 'sform-custom-script', get_theme_file_uri( '/simpleform/custom-script.js'), array( 'jquery' ), '', true );
	    }

        // Attach extra scripts
  	    $additionalScript = get_option('sform_additional_script') != false ? get_option('sform_additional_script') : '';

        if ( ! empty($additionalScript) ) {
          wp_add_inline_script( 'sform_form_script', $additionalScript, 'before' );
	    }

    }

	/**
	 * Apply shortcode and return the form.
	 *
	 * @since 1.0
	 */

    public function sform_shortcode($atts) {

        $atts_array = shortcode_atts( array( 'id' => '1', 'type' => '' ), $atts );
        $util = new SimpleForm_Util();
        $settings = $util->sform_settings($atts_array['id']);
        $attributes = $util->sform_attributes($atts_array['id']);
        $users = ! empty( $attributes['show_for'] ) ? esc_attr($attributes['show_for']) : 'all';
        $role = ! empty( $attributes['user_role'] ) ? esc_attr($attributes['user_role']) : 'any';
        $current_user = wp_get_current_user();
        if ( $users == 'out' ) { $form_user = '<b>' . __( 'logged-out users','simpleform') . '</b>'; $form_user_role = ''; }
        elseif ( $users == 'in' ) { $form_user = '<b>' . __( 'logged-in users','simpleform') . '</b>'; $form_user_role = '&nbsp;' . __( 'with the role of','simpleform') . '&nbsp;<b>' . translate_user_role(ucfirst($role)) . '</b>'; }
        else { $form_user = __( 'everyone','simpleform'); $form_user_role = ''; }
        $hiding = ( $users == 'out' && is_user_logged_in() ) || ( $users == 'in' && ! is_user_logged_in() ) || ( $users == 'in' && is_user_logged_in() && $role != 'any' && ! in_array( $role, (array) $current_user->roles ) ) ? true : false;

        if ( $hiding == true && ! current_user_can('manage_options') ) { $form = ''; }

        else {

          include 'partials/form-variables.php';
          $template = ! empty( $settings['form_template'] ) ? esc_attr($settings['form_template']) : 'default';
          $stylesheet = ! empty( $settings['stylesheet'] ) ? esc_attr($settings['stylesheet']) : 'false';
          $cssfile = ! empty( $settings['stylesheet_file'] ) ? esc_attr($settings['stylesheet_file']) : 'false';
          $ajax = ! empty( $settings['ajax_submission'] ) ? esc_attr($settings['ajax_submission']) : 'false';
          $javascript = ! empty( $settings['javascript'] ) ? esc_attr($settings['javascript']) : 'false';
          $above_form = isset( $_GET['sending'] ) && $_GET['sending'] == 'success' && isset( $_GET['form'] ) && $_GET['form'] == $atts_array['id'] ? '' : '<div id="sform-introduction-'.$atts_array['id'].'" class="sform-introduction '.$class_direction.'">'.$introduction_text.'</div>';
          $below_form = isset( $_GET['sending'] ) && $_GET['sending'] == 'success' && isset( $_GET['form'] ) && $_GET['form'] == $atts_array['id'] ? '' : '<div id="sform-bottom-'.$atts_array['id'].'" class="sform-bottom '.$class_direction.'">'.$bottom_text.'</div>';
          $is_gb_editor = defined( 'REST_REQUEST' ) && REST_REQUEST;
          $frontend_notice = ! empty( $settings['frontend_notice'] ) ? esc_attr($settings['frontend_notice']) : 'true';
          $admin_message = '<div id="sform-admin-message"><p class="heading">'. __('SimpleForm Admin Notice', 'simpleform') . '</p>'. __('The form is visible only for ', 'simpleform') . $form_user . $form_user_role . '. ' . __( 'Your role does not allow you to see it!','simpleform') .'</div>';

          // Form template
          $template_directory = $template == 'customized' && file_exists( get_theme_file_path( '/simpleform/custom-template.php' ) ) ? get_theme_file_path( '/simpleform/custom-template.php' ) : 'partials/template.php';
          include $template_directory; 

          // Form style
          if ( $stylesheet == 'false' ) {
	        wp_enqueue_style( 'sform-public-style' );
          }
          else {
	        wp_enqueue_style( 'simpleform-style' );
	        if ( $cssfile == 'true' && file_exists( get_theme_file_path( '/simpleform/custom-style.css' ) ) ) {
              wp_enqueue_style( 'sform-custom-style' );
            }
          }

          // Form scripts
          wp_enqueue_script( 'sform_form_script' );
          if ( $ajax == 'true' ) {
            wp_enqueue_script( 'sform_public_script' );
          }
          if ( $javascript == 'true' && file_exists( get_theme_file_path( '/simpleform/custom-script.js' ) ) ) {
	        wp_enqueue_script( 'sform-custom-script' );
	      }
          
	      // Display form
   	      if ( $hiding == true && ! is_admin() && ! $is_gb_editor && ! is_customize_preview() ) {
  	        $form = $frontend_notice == 'true' ? $admin_message : '';
	      }
 	      else {
  	        $form = $atts_array['type'] != '' ? $contact_form : $above_form . $contact_form . $below_form;
          }

        }

        return $form;

    }

	/**
	 * Validate the form data after submission without Ajax
	 *
	 * @since 1.0
     * @version 2.1.7
	 */

    public function formdata_validation($data) {

        $validation = new SimpleForm_Validation();
        $values = $validation->data_sanitization(); 
        $form_id = $values['form'];
        $name = $values['name'];
        $lastname = $values['lastname'];
        $email = $values['email'];
        $phone = $values['phone'];
        $subject = $values['subject'];
        $message = $values['message'];
        $consent = $values['consent'];
        $captcha_one = $values['captcha_one'];
        $captcha_two = $values['captcha_two'];
        $captcha_question = $values['captcha_question'];
        $captcha_answer = $values['captcha_answer'];
        $honeyurl = $values['honeyurl'];
        $honeytel = $values['honeytel'];
        $honeycheck = $values['honeycheck'];
        $util = new SimpleForm_Util();
        $settings = $util->sform_settings($form_id);
	    $ajax = ! empty( $settings['ajax_submission'] ) ? esc_attr($settings['ajax_submission']) : 'false';

        if ( $ajax == 'false' && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submission']) && isset($_POST['sform_nonce']) && wp_verify_nonce($_POST['sform_nonce'],'sform_nonce_action') ) {

		  $errors = '';

          // Make Akismet validation first
          $flagged = '';
          if ( has_filter('akismet_validation') ) { $errors = apply_filters('akismet_validation',$errors,$form_id,$name,$email,$message); }
	      if( has_filter('akismet_action') ) { $flagged = apply_filters('akismet_action',$flagged,$name,$email,$message); }

          // Make form validation
          $errors = $validation->form_errors($form_id,$settings,$name,$lastname,$email,$phone,$subject,$message,$consent,$captcha_question,$captcha_answer,$honeyurl,$honeytel,$honeycheck);

          // Process the submitted data
	      $errors = apply_filters('sform_send_email',$errors,$form_id,$settings,$name,$lastname,$email,$phone,$subject,$message,$flagged);

          // Remove duplicate Form ID
          $errors = implode(';',array_unique(explode(';', $errors)));

 		  $data = array( 'form' => $form_id, 'name' => $name,'lastname' => $lastname,'email' => $email,'phone' => $phone,'subject' => $subject,'message' => $message,'consent' => $consent,'captcha' => $captcha_answer,'captcha_one' => $captcha_one,'captcha_two' => $captcha_two,'url' => $honeyurl,'telephone' => $honeytel,'fakecheckbox' => $honeycheck,'error' => $errors );

	    }

        else {

          $data = array( 'form' => $form_id, 'name' => '','lastname' => '','email' => '','phone' =>'','subject' => '','message' => '','consent' => '','captcha' => '','captcha_one' => '','captcha_two' => '','url' => '','telephone' => '','fakecheckbox' => '' );

		}

        return $data;

	}

	/**
	 * Modify the HTTP response header (buffer the output so that nothing gets written until you explicitly tell to do it)
	 *
	 * @since 1.8.1
	 */

    public function ob_start_cache($errors) {

 	  $form_id = isset($_POST['form-id']) ? absint($_POST['form-id']) : '1';
      $util = new SimpleForm_Util();
      $settings = $util->sform_settings($form_id);
      $ajax = ! empty( $settings['ajax_submission'] ) ? esc_attr($settings['ajax_submission']) : 'true';

      if( $ajax != 'true' && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submission']) && isset( $_POST['sform_nonce'] ) && wp_verify_nonce( $_POST['sform_nonce'], 'sform_nonce_action' ) ) {
	    if ( $errors == '' ) { ob_start(); }
      }

    }

	/**
	 * Process the form data after submission with post callback function
	 *
	 * @since 1.0
     * @version 2.1.7
	 */

    public function formdata_processing($errors,$form_id,$settings,$name,$lastname,$email,$phone,$subject,$message,$flagged) {

	  $validation = new SimpleForm_Validation();
      $submitter_data = $validation->submitter_data($name,$lastname,$email);
      $submitter = $submitter_data['submitter'];
      $email = $submitter_data['email'];

	  // Prevent double submission: change of name or email allowed
      $errors = apply_filters('sform_block_duplicate',$errors,$form_id,$submitter,$email,$message);

	  if ( empty($errors) ) {

        $mailing = '';
        $success_action = ! empty( $settings['success_action'] ) ? esc_attr($settings['success_action']) : 'message';
        $thanks_url = ! empty( $settings['thanks_url'] ) ? esc_url($settings['thanks_url']) : '';
	    $redirect_to = $success_action != 'message' && ! empty($thanks_url) ? esc_url_raw($thanks_url) : esc_url_raw(add_query_arg( array('sending' => 'success','form' => $form_id), $_SERVER['REQUEST_URI'] ));
        $date = time();
        $submission_date = date('Y-m-d H:i:s');
	    global $wpdb;
        $requester_type  = is_user_logged_in() ? 'registered' : 'anonymous';
        $user_ID = is_user_logged_in() ? get_current_user_id() : '0';
        $form_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}sform_shortcodes WHERE id = %d", $form_id) );
        $moveto = esc_sql($form_data->moveto);
        $to_be_moved = esc_sql($form_data->to_be_moved);
        $notifications_settings = esc_sql($form_data->override_settings);
        $moving = $moveto != '0' && $to_be_moved == 'next' ? true : false;
        $save_as = $moving == true ? $moveto : $form_id;
        $moved_from = $moving == true ? $form_id : '0';
        $sform_default_values = array( "form" => $save_as, "moved_from" => $moved_from, "requester_type" => $requester_type, "requester_id" => $user_ID, "date" => $submission_date );
        $extra_fields = array('notes' => '');
        $submitter_name = $submitter_data['name'] != '' ? $submitter_data['name'] : __( 'Anonymous', 'simpleform' );
        $submitter_lastname = $submitter_data['lastname'];
        $sform_extra_values = array_merge($sform_default_values, apply_filters( 'sform_storing_values', $extra_fields, $save_as, $submitter_name, $submitter_lastname, $email, $phone, $subject, $message, $flagged ));
        $success = $wpdb->insert( $wpdb->prefix . 'sform_submissions', $sform_extra_values );

        if ( $success ) {

	      $reference_number = $wpdb->insert_id;
          $notifications = new SimpleForm_Notifications();
	      $form = $moving == true && $notifications_settings == true ? $moveto : $form_id;
          $util = new SimpleForm_Util();
          $form_settings = $util->sform_settings($form);
          $notification = ! empty( $form_settings['notification'] ) ? esc_attr($form_settings['notification']) : 'true';
          $confirmation = ! empty( $form_settings['autoresponder'] ) ? esc_attr($form_settings['autoresponder']) : 'false';
          do_action( 'sform_entries_updating', $form_id, $form_data);

          if ( $notification == 'true') {
            $entry_date = $notifications->submission_date($date);
            $alert_message = $notifications->alert_message($submitter,$email,$phone,$entry_date,$flagged,$subject,$message);
            $last_message = $notifications->last_message($submitter,$email,$phone,$entry_date,$flagged,$subject,$message);
	        $mailing = apply_filters('sform_alert', $mailing, $form_settings, $reference_number, $submitter, $email, $subject, $flagged, $alert_message );
            do_action( 'sform_before_last_message_updating', $form_id, $moving, $moveto );
            do_action( 'sform_last_message_updating', $form_id, $moving, $moveto, $submission_date, $last_message );
	      }

	      if ( $confirmation == 'true' && ! empty($email) ) {
            $autoresponder_message = $notifications->autoresponder_message($form_id,$moving,$moveto,$name,$lastname,$email,$phone,$subject,$message,$reference_number,$notifications_settings);
            do_action( 'sform_autoreply', $form_settings, $email, $autoresponder_message );
	      }

          if ( ! has_filter('sform_post_message') ) {
            if ( $mailing == 'true' ) {
	          header('Location: '. $redirect_to);
	          ob_end_flush();
              exit();
            }
	        else {
		      $errors = $form_id.';server_error';
	        }
	      }

	      else {
		    $errors = apply_filters( 'sform_post_message', $form_id, $mailing );
		    // $errors = apply_filters( 'sform_post_message',$errors,$form_id,$mailing );
            if ( $errors == '' ) {
	          header('Location: '. $redirect_to);
              ob_end_flush();
              exit();
            }
	      }

        }

        else { $errors = $form_id.';server_error'; }

      }

      return $errors;

    }

	/**
	 * Process the form data after submission with Ajax callback function
	 *
	 * @since 1.0
     * @version 2.1.7
	 */

    public function formdata_ajax_processing() {

      if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || ! wp_verify_nonce( $_POST['sform_nonce'], 'sform_nonce_action' ) )  { die ( 'Security checked!'); }

      else {

        $validation = new SimpleForm_Validation();
        $values = $validation->data_sanitization(); 
        $form_id = $values['form'];
        $name = $values['name'];
        $lastname = $values['lastname'];
        $email = $values['email'];
        $phone = $values['phone'];
        $subject = $values['subject'];
        $message = $values['message'];
        $consent = $values['consent'];
        $captcha_question = $values['captcha_question'];
        $captcha_answer = $values['captcha_answer'];
        $honeyurl = $values['honeyurl'];
        $honeytel = $values['honeytel'];
        $honeycheck = $values['honeycheck'];
        $util = new SimpleForm_Util();
        $settings = $util->sform_settings($form_id);

        // Make Akismet validation first
        $flagged = '';
        if ( has_action('akismet_spam_checking') ) { do_action( 'akismet_spam_checking', $name, $email, $message ); }
        if ( has_filter('akismet_action') ) { $flagged = apply_filters('akismet_action', $flagged, $name, $email, $message ); }

        // Make form validation
        $errors = $validation->form_errors($form_id,$settings,$name,$lastname,$email,$phone,$subject,$message,$consent,$captcha_question,$captcha_answer,$honeyurl,$honeytel,$honeycheck);

        // Make customized validation ( Only AJAX - beta feature )
        if ( has_action( 'spam_check_execution' ) ) { do_action( 'spam_check_execution' ); }

		$submitter_data = $validation->submitter_data($name,$lastname,$email);
        $submitter = $submitter_data['submitter'];
        $email = $submitter_data['email'];

	    // Prevent double submission: a change of name or email is allowed
        $errors = apply_filters('sform_block_duplicate',$errors,$form_id,$submitter,$email,$message);

	    if ( empty($errors) ) {

          $mailing = '';
          $success_action = ! empty( $settings['success_action'] ) ? esc_attr($settings['success_action']) : 'message';
          $confirmation_img = plugins_url( 'img/confirmation.png', __FILE__ );
          $thank_you_message = ! empty( $settings['success_message'] ) ? stripslashes(wp_kses_post($settings['success_message'])) : '<div class="form confirmation" tabindex="-1"><h4>' . __( 'We have received your request!', 'simpleform' ) . '</h4><br>' . __( 'Your message will be reviewed soon, and we\'ll get back to you as quickly as possible.', 'simpleform' ) . '</br><img src="'.$confirmation_img.'" alt="message received"></div>';
          $thanks_url = ! empty( $settings['thanks_url'] ) ? esc_url($settings['thanks_url']) : '';
		  $redirect = $success_action == 'message' ? false : true;
		  $redirect_url = $success_action == 'message' ? '' : $thanks_url;
          $server_error = ! empty( $settings['server_error'] ) ? stripslashes(esc_attr($settings['server_error'])) : __( 'Error occurred during processing data. Please try again!', 'simpleform' );
          $date = time();
          $submission_date = date('Y-m-d H:i:s');
	      global $wpdb;
          $requester_type = is_user_logged_in() ? 'registered' : 'anonymous';
          $user_ID = is_user_logged_in() ? get_current_user_id() : '0';
          $form_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}sform_shortcodes WHERE id = %d", $form_id) );
          $moveto = esc_sql($form_data->moveto);
          $to_be_moved = esc_sql($form_data->to_be_moved);
          $notifications_settings = esc_sql($form_data->override_settings);
          $moving = $moveto != '0' && $to_be_moved == 'next' ? true : false;
          $save_as = $moving == true ? $moveto : $form_id;
          $moved_from = $moving == true ? $form_id : '0';
          $sform_default_values = array( "form" => $save_as, "moved_from" => $moved_from, "requester_type" => $requester_type, "requester_id" => $user_ID, "date" => $submission_date );
          $extra_fields = array('notes' => '');
          $submitter_name = $submitter_data['name'] != '' ? $submitter_data['name'] : __( 'Anonymous', 'simpleform' );
          $submitter_lastname = $submitter_data['lastname'];
          $sform_extra_values = array_merge($sform_default_values, apply_filters( 'sform_storing_values', $extra_fields, $save_as, $submitter_name, $submitter_lastname, $email, $phone, $subject, $message, $flagged ));
          // Save customized data ( Only AJAX - beta feature )
          $sform_additional_values = array_merge($sform_extra_values, apply_filters( 'sform_testing', $extra_fields ));
          $success = $wpdb->insert( $wpdb->prefix . 'sform_submissions', $sform_additional_values );

          if ( $success ) {

	        $reference_number = $wpdb->insert_id;
            $notifications = new SimpleForm_Notifications();
	        $form = $moving == true && $notifications_settings == true ? $moveto : $form_id;
            $form_settings = $util->sform_settings($form);
            $notification = ! empty( $form_settings['notification'] ) ? esc_attr($form_settings['notification']) : 'true';
            $confirmation = ! empty( $form_settings['autoresponder'] ) ? esc_attr($form_settings['autoresponder']) : 'false';
            do_action( 'sform_entries_updating', $form_id, $form_data);
            // Make customized action
            if ( has_action('spam_check_activation') ) { do_action( 'spam_check_activation' ); }

            if ( $notification == 'true' ) {
              $entry_date = $notifications->submission_date($date);
              $alert_message = $notifications->alert_message($submitter,$email,$phone,$entry_date,$flagged,$subject,$message);
              $last_message = $notifications->last_message($submitter,$email,$phone,$entry_date,$flagged,$subject,$message);
	          $mailing = apply_filters('sform_alert', $mailing, $form_settings, $reference_number, $submitter, $email, $subject, $flagged, $alert_message );
              do_action( 'sform_before_last_message_updating', $form_id, $moving, $moveto );
              do_action( 'sform_last_message_updating', $form_id, $moving, $moveto, $submission_date, $last_message );
	        }

	        if ( $confirmation == 'true' && ! empty($email) ) {
              $autoresponder_message = $notifications->autoresponder_message($form_id,$moving,$moveto,$name,$lastname,$email,$phone,$subject,$message,$reference_number,$notifications_settings);
              do_action( 'sform_autoreply', $form_settings, $email, $autoresponder_message );
	        }

            if ( ! has_action('sform_ajax_message') ) {
              if ( $mailing ) {
                $errors = array('error' => false, 'redirect' => $redirect, 'redirect_url' => $redirect_url, 'notice' => $thank_you_message );
	          }
	          else {
                $errors = array('error' => true, 'showerror' => true, 'field_focus' => false, 'notice' => $server_error );
              }
	        }

	        else { do_action( 'sform_ajax_message', $form_id, $mailing, $redirect, $redirect_url, $thank_you_message, $server_error ); }

          }

          else {
            $errors = array('error' => true, 'showerror' => true, 'field_focus' => false, 'notice' => $server_error );
          }

	    }

        echo json_encode($errors);
        wp_die();

      }

    }

	/**
	 * Send alert email
	 *
	 * @since 2.1.8
	 */

    public function alert_sending($mailing,$form_settings,$reference_number,$submitter,$email,$subject,$flagged,$alert_message) {

       $notification_reply = ! empty( $form_settings['notification_reply'] ) ? esc_attr($form_settings['notification_reply']) : 'true';
       $to = ! empty( $form_settings['notification_recipient'] ) ? explode(',', esc_attr($form_settings['notification_recipient'])) : esc_attr( get_option( 'admin_email' ) );
       $bcc = ! empty( $form_settings['bcc'] ) ? esc_attr($form_settings['bcc']) : '';
       $submission_number = ! empty( $form_settings['submission_number'] ) ? esc_attr($form_settings['submission_number']) : 'visible';
       $subject_type = ! empty( $form_settings['notification_subject'] ) ? esc_attr($form_settings['notification_subject']) : 'request';
       $subject_text = ! empty( $form_settings['custom_subject'] ) ? stripslashes(esc_attr($form_settings['custom_subject'])) : __('New Contact Request', 'simpleform');
       $request_subject = ! empty($subject) ? $subject : __( 'No Subject', 'simpleform' );
       $notification_subject = $subject_type == 'request' ? $request_subject : $subject_text;
       $admin_subject = $submission_number == 'visible' && empty($flagged) ? '#' . $reference_number . ' - ' . $notification_subject : $flagged . $notification_subject;
	   $headers = "Content-Type: text/html; charset=UTF-8" .  "\r\n";
       if ( ! empty($email) && $notification_reply == 'true' ) { $headers .= "Reply-To: ".$submitter." <".$email.">" . "\r\n"; }
       if ( ! empty($bcc) ) { $headers .= "Bcc: ".$bcc. "\r\n"; }
       do_action('check_smtp');
       add_filter( 'wp_mail_from_name', array ( $this, 'alert_name' ) );
       add_filter( 'wp_mail_from', array ( $this, 'alert_email' ) );
	   $mailing = wp_mail($to, $admin_subject, $alert_message, $headers);
       remove_filter( 'wp_mail_from_name', array ( $this, 'alert_name' ) );
       remove_filter( 'wp_mail_from', array ( $this, 'alert_email' ) );

       return $mailing;

    }

	/**
	 * Send auto-reply
	 *
	 * @since 2.1.8
	 */

    public function autoreply_sending($form_settings,$email,$autoresponder_message) {

	   $from = ! empty( $form_settings['autoresponder_email'] ) ? esc_attr($form_settings['autoresponder_email']) : esc_attr( get_option( 'admin_email' ) );
       $confirmation_subject = ! empty( $form_settings['autoresponder_subject'] ) ? stripslashes(esc_attr($form_settings['autoresponder_subject'])) : __( 'Your request has been received. Thanks!', 'simpleform' );
       $reply_to = ! empty( $form_settings['autoresponder_reply'] ) ? esc_attr($form_settings['autoresponder_reply']) : $from;
	   $headers = "Content-Type: text/html; charset=UTF-8" . "\r\n";
	   $headers .= "Reply-To: <".$reply_to.">" . "\r\n";
       do_action('check_smtp');
       add_filter( 'wp_mail_from_name', array ( $this, 'autoreply_name' ) );
       add_filter( 'wp_mail_from', array ( $this, 'autoreply_email' ) );
	   wp_mail($email, $confirmation_subject, $autoresponder_message, $headers);
       remove_filter( 'wp_mail_from_name', array ( $this, 'autoreply_name' ) );
       remove_filter( 'wp_mail_from', array ( $this, 'autoreply_email' ) );

    }

	/**
	 * Force "From Name" in alert email
	 *
	 * @since 1.0
     * @version 2.1.7
	 */

    public function alert_name() {

	  $form_id = isset( $_POST['form-id'] ) ? absint($_POST['form-id']) : '1';

	  global $wpdb;
      $form_data = $wpdb->get_row( $wpdb->prepare( "SELECT name, moveto, to_be_moved, override_settings FROM {$wpdb->prefix}sform_shortcodes WHERE id = %d", $form_id ) );
      $form_name = esc_sql($form_data->name);
      $moveto = esc_sql($form_data->moveto);
      $to_be_moved = esc_sql($form_data->to_be_moved);
      $notifications_settings = esc_sql($form_data->override_settings);
      $moving = $moveto != '0' && $to_be_moved == 'next' ? true : false;
      $form = $moving == true && $notifications_settings == true ? $moveto : $form_id;
      $util = new SimpleForm_Util();
      $settings = $util->sform_settings($form);
      $sender = ! empty( $settings['notification_name'] ) ? esc_attr($settings['notification_name']) : 'requester';
      $custom_sender = ! empty( $settings['custom_sender'] ) ? esc_attr($settings['custom_sender']) : esc_attr( get_bloginfo( 'name' ) );

      if ( $sender == 'requester') {
	     $name = isset($_POST['sform-name']) ? sanitize_text_field($_POST['sform-name']) : '';
	     $lastname = isset($_POST['sform-lastname']) ? ' ' . sanitize_text_field($_POST['sform-lastname']) : '';
         $full_name = $name . $lastname;
	     if ( !empty(trim($full_name)) ) {
		    $sender_name = $full_name;
		 }
         else {
		   if ( is_user_logged_in() ) {
		     global $current_user;
		     $name = ! empty($current_user->user_name) ? $current_user->user_name : $current_user->display_name;
		     $lastname = ! empty($current_user->user_lastname) ? ' ' . $current_user->user_lastname : '';
             $sender_name = trim($name . $lastname);
	       }
	       else {
             $sender_name = esc_attr__( 'Anonymous', 'simpleform' );
	       }
         }
	  }

      elseif ( $sender == 'custom') { $sender_name = $custom_sender; }

      else { $sender_name = $form_name; }

      return $sender_name;

    }

	/**
	 * Force "From Email" in alert email
	 *
	 * @since 1.0
     * @version 2.1.7
	 */

    public function alert_email() {

	  $form_id = isset( $_POST['form-id'] ) ? absint($_POST['form-id']) : '1';

	  global $wpdb;
      $form_data = $wpdb->get_row( $wpdb->prepare( "SELECT moveto, to_be_moved, override_settings FROM {$wpdb->prefix}sform_shortcodes WHERE id = %d", $form_id ) );
      $moveto = esc_sql($form_data->moveto);
      $to_be_moved = esc_sql($form_data->to_be_moved);
      $notifications_settings = esc_sql($form_data->override_settings);
      $moving = $moveto != '0' && $to_be_moved == 'next' ? true : false;
      $form = $moving == true && $notifications_settings == true ? $moveto : $form_id;
      $util = new SimpleForm_Util();
      $settings = $util->sform_settings($form);
      $notification_email = ! empty( $settings['notification_email'] ) ? esc_attr($settings['notification_email']) : esc_attr( get_option( 'admin_email' ) );

      return $notification_email;

    }

	/**
	 * Force "From Name" in auto-reply email
	 *
	 * @since 1.0
     * @version 2.1.7
	 */

    public function autoreply_name() {

	  $form_id = isset( $_POST['form-id'] ) ? absint($_POST['form-id']) : '1';

	  global $wpdb;
      $form_data = $wpdb->get_row( $wpdb->prepare( "SELECT moveto, to_be_moved, override_settings FROM {$wpdb->prefix}sform_shortcodes WHERE id = %d", $form_id ) );
      $moveto = esc_sql($form_data->moveto);
      $to_be_moved = esc_sql($form_data->to_be_moved);
      $notifications_settings = esc_sql($form_data->override_settings);
      $moving = $moveto != '0' && $to_be_moved == 'next' ? true : false;
      $form = $moving == true && $notifications_settings == true ? $moveto : $form_id;
      $util = new SimpleForm_Util();
      $settings = $util->sform_settings($form);
	  $sender_name = ! empty( $settings['autoresponder_name'] ) ? esc_attr($settings['autoresponder_name']) : esc_attr( get_bloginfo( 'name' ) );

	  return $sender_name;

    }

	/**
	 * Force "From Email" in auto-reply email
	 *
	 * @since 1.0
     * @version 2.1.7
	 */

    public function autoreply_email() {

	  $form_id = isset( $_POST['form-id'] ) ? absint($_POST['form-id']) : '1';

	  global $wpdb;
      $form_data = $wpdb->get_row( $wpdb->prepare( "SELECT moveto, to_be_moved, override_settings FROM {$wpdb->prefix}sform_shortcodes WHERE id = %d", $form_id ) );
      $moveto = esc_sql($form_data->moveto);
      $to_be_moved = esc_sql($form_data->to_be_moved);
      $notifications_settings = esc_sql($form_data->override_settings);
      $moving = $moveto != '0' && $to_be_moved == 'next' ? true : false;
      $form = $moving == true && $notifications_settings == true ? $moveto : $form_id;
      $util = new SimpleForm_Util();
      $settings = $util->sform_settings($form);
	  $from = ! empty( $settings['autoresponder_email'] ) ? esc_attr($settings['autoresponder_email']) : esc_attr( get_option( 'admin_email' ) );

      return $from;

    }

}