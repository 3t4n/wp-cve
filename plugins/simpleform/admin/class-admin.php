<?php

/**
 * Defines the admin-specific functionality of the plugin.
 *
 * @since      1.0
 */
	 
class SimpleForm_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0
	 */
	
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0
	 */
	
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0
	 */
	
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0
     */  
     
    public function sform_admin_menu() {
	    
      $contacts = __('Contacts', 'simpleform');           
      $contacts_bubble = apply_filters( 'sform_notification_bubble', $contacts );
      $hook = add_menu_page($contacts, $contacts_bubble,'manage_options','sform-entries', array($this,'display_submissions'),'dashicons-email-alt', 24 );      
   
      global $sform_entries;
      $submissions = __('Entries','simpleform');
      $sform_entries = add_submenu_page('sform-entries', $submissions, $submissions, 'manage_options', 'sform-entries', array($this,'display_submissions'));

      global $sform_forms;
      $forms = __('Forms', 'simpleform');
      $sform_forms = add_submenu_page('sform-entries', $forms, $forms, 'manage_options', 'sform-forms', array($this,'display_forms'));
	  // Add screen option tab
      add_action("load-$sform_forms", array ($this, 'forms_list_options') );

      global $sform_form_page;
      $form = __('Form', 'simpleform');
      $sform_form_page = add_submenu_page(null, $form, $form, 'manage_options', 'sform-form', array($this,'form_page'));

      global $sform_new;
      $new = __('Add New', 'simpleform');
      $sform_new = add_submenu_page(null, $new, $new, 'manage_options', 'sform-new', array($this,'display_new'));
      
      global $sform_editor;
      /* translators: Used to indicate the form editor not user role */
      $editor = __('Editor', 'simpleform');
      $sform_editor = add_submenu_page('sform-entries', $editor, $editor, 'manage_options', 'sform-editor', array($this,'display_editor'));

      global $sform_settings;
      $settings = __('Settings', 'simpleform');
      $sform_settings = add_submenu_page('sform-entries', $settings, $settings, 'manage_options', 'sform-settings', array($this,'display_settings'));

	  global $sform_support;
	  $support = __('Support','simpleform-contact-form-submissions');
      $sform_support = add_submenu_page('sform-entries', $support, $support, 'manage_options', 'sform-support', array ($this, 'support_page') );

      do_action('load_submissions_table_options');
      do_action('sform_submissions_submenu');

   }
  
    /**
     * Render the submissions page for this plugin.
     *
     * @since    1.0
     */
     
    public function display_submissions() {
      
      include_once('partials/entries.php');
   
    }

    /**
     * Render the editing page for forms.
     *
     * @since    1.0
     */
    
    public function display_editor() {
     
      include_once('partials/editor.php');
    
    }
    
    /**
     * Render the page for a new form.
     *
     * @since    2.0
     */
    
    public function display_new() {
     
      include_once('partials/new.php');
    
    }
    
    /**
     * Render the forms page for this plugin.
     *
     * @since    2.1
     */
     
    public function display_forms() {
      
      include_once('partials/forms.php');
   
    }

    /**
     * Render the form management page for this plugin.
     *
     * @since    2.1
     */
     
    public function form_page() {
      
      include_once('partials/form.php');
   
    }

    /**
     * Render the settings page for forms.
     * @since    1.0
     */
    
    public function display_settings() {
      
      include_once('partials/settings.php');
    
    }

    /**
     * Render the submitted message page for this plugin.
     *
     * @since    2.0.1
     */
     
    public function support_page() {
      
      include_once( 'partials/support.php' );
    }

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0
	 */
    
    public function enqueue_styles($hook) {
	    		
	 wp_register_style('sform-style', plugins_url( 'css/admin-min.css', __FILE__ ),[], filemtime( plugin_dir_path( __FILE__ ) . 'css/admin-min.css' ) );
	 
     global $sform_entries;
     global $sform_forms;
     global $sform_form_page;
     global $sform_editor;
     global $sform_new;
     global $sform_settings;
	 global $sform_support;
     global $pagenow;
	   
     if( $hook != $sform_entries && $hook != $sform_forms && $hook != $sform_form_page && $hook != $sform_editor && $hook != $sform_settings && $hook != $sform_new && $hook != $sform_support && $pagenow != 'widgets.php' && $pagenow != 'customize.php' ) 
     return;

	 wp_enqueue_style('sform-style'); 
	      
	}
	
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0
	 */
	
	public function enqueue_scripts($hook){
	    		
     global $sform_entries;
     global $sform_forms;
     global $sform_form_page;
     global $sform_editor;
     global $sform_settings;
     global $sform_new;
     global $pagenow;

     if( $hook != $sform_entries && $hook != $sform_forms && $hook != $sform_form_page && $hook != $sform_editor && $hook != $sform_settings && $hook != $sform_new && $pagenow != 'widgets.php' && $pagenow != 'customize.php' ) 
     return;     
     
     $settings = get_option('sform_settings'); 
     $attributes = get_option('sform_attributes');
     $name_length = isset( $attributes['name_minlength'] ) ? esc_attr($attributes['name_minlength']) : '2';
     $name_numeric_error = ! empty( $settings['incomplete_name'] ) && preg_replace('/[^0-9]/', '', $settings['incomplete_name']) == $name_length ? stripslashes(esc_attr($settings['incomplete_name'])) : sprintf( __('Please enter at least %d characters', 'simpleform' ), $name_length );
     $name_generic_error = ! empty( $settings['incomplete_name'] ) && preg_replace('/[^0-9]/', '', $settings['incomplete_name']) == '' ? stripslashes(esc_attr($settings['incomplete_name'])) : __('Please type your full name', 'simpleform' );
     $lastname_length = isset( $attributes['lastname_minlength'] ) ? esc_attr($attributes['lastname_minlength']) : '2';
     $lastname_numeric_error = ! empty( $settings['incomplete_lastname'] ) && preg_replace('/[^0-9]/', '', $settings['incomplete_lastname']) == $lastname_length ? stripslashes(esc_attr($settings['incomplete_lastname'])) : sprintf( __('Please enter at least %d characters', 'simpleform' ), $lastname_length );
     $lastname_generic_error = ! empty( $settings['incomplete_lastname'] ) && preg_replace('/[^0-9]/', '', $settings['incomplete_lastname']) == '' ? stripslashes(esc_attr($settings['incomplete_lastname'])) : __('Please type your full last name', 'simpleform' );
     $subject_length = isset( $attributes['subject_minlength'] ) ? esc_attr($attributes['subject_minlength']) : '5';
     $subject_numeric_error = ! empty( $settings['incomplete_subject'] ) && preg_replace('/[^0-9]/', '', $settings['incomplete_subject']) == $subject_length ? stripslashes(esc_attr($settings['incomplete_subject'])) : sprintf( __('Please enter a subject at least %d characters long', 'simpleform' ), $subject_length );
     $subject_generic_error = ! empty( $settings['incomplete_subject'] ) && preg_replace('/[^0-9]/', '', $settings['incomplete_subject']) == '' ? stripslashes(esc_attr($settings['incomplete_subject'])) : __('Please type a short and specific subject', 'simpleform' );
     $message_length = isset( $attributes['message_minlength'] ) ? esc_attr($attributes['message_minlength']) : '10';
     $message_numeric_error = ! empty( $settings['incomplete_message'] ) && preg_replace('/[^0-9]/', '', $settings['incomplete_message']) == $message_length ? stripslashes(esc_attr($settings['incomplete_message'])) : sprintf( __('Please enter a message at least %d characters long', 'simpleform' ), $message_length );
     $message_generic_error = ! empty( $settings['incomplete_message'] ) && preg_replace('/[^0-9]/', '', $settings['incomplete_message']) == '' ? stripslashes(esc_attr($settings['incomplete_message'])) : __('Please type a clearer message so we can respond appropriately', 'simpleform' );
     $privacy_string = __( 'privacy policy','simpleform');
     /* translators: Used in place of %s in the string: "Please enter an error message to be displayed on %s of the form" */
     $top_position = __('top', 'simpleform');
     /* translators: Used in place of %s in the string: "Please enter an error message to be displayed on %s of the form" */
     $bottom_position = __('bottom', 'simpleform');
     /* translators: Used in place of %1$s in the string: "%1$s or %2$s the page content" */
     $edit = __( 'Edit','simpleform');
     /* translators: Used in place of %2$s in the string: "%1$s or %2$s the page content" */
     $view = __( 'view','simpleform');
     $page_links = sprintf( __('%1$s or %2$s the page content', 'simpleform'), $edit, $view); 
     $smtp_notes = __('Uncheck if you want to use a dedicated plugin to take care of outgoing email', 'simpleform' );
     $storing_notice = '<span style="margin-top: -7px; margin-bottom: -7px; color: #32373c; padding: 7px 20px 7px 10px; border-width: 0 0 0 5px; border-style: solid; background: #e5f5fa; border-color: #00a0d2;">' . __('The list of entries refers only to forms for which data storage has been enabled','simpleform') .'</span>';
         
 	 wp_enqueue_script('sform_saving_options', plugins_url( 'js/admin-min.js', __FILE__ ), array( 'jquery' ), filemtime( plugin_dir_path( __FILE__ ) . 'js/admin-min.js' ) );

     wp_localize_script( 'sform_saving_options', 'ajax_sform_settings_options_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 	'copy' => __( 'Copy shortcode', 'simpleform' ), 'copied' => __( 'Shortcode copied', 'simpleform' ), 'saving' => __( 'Saving data in progress', 'simpleform' ), 'loading' => __( 'Saving settings in progress', 'simpleform' ), 'notes' => __( 'Create a directory inside your active theme\'s directory, name it "simpleform", copy one of the template files, and name it "custom-template.php"', 'simpleform' ), 'bottomnotes' => __( 'Display an error message on bottom of the form in case of one or more errors in the fields','simpleform'), 'topnotes' => __( 'Display an error message above the form in case of one or more errors in the fields','simpleform'), 'nofocus' => __( 'Do not move focus','simpleform'), 'focusout' => __( 'Set focus to error message outside','simpleform'), 'builder' => __( 'Change easily the way your contact form is displayed. Choose which fields to use and who should see them:', 'simpleform' ), 'appearance' => __( 'Tweak the appearance of your contact form to match it better to your site.', 'simpleform' ), 'adminurl' => admin_url(), 'pageurl' => site_url(), 'status' => __( 'Page in draft status not yet published','simpleform'), 'publish' =>  __( 'Publish now','simpleform'), 'edit' => $edit, 'view' => $view, 'pagelinks' => $page_links, 'show' => __( 'Show Configuration Warnings', 'simpleform' ), 'hide' => esc_html__( 'Hide Configuration Warnings', 'simpleform' ), 'cssenabled' => __( 'Create a directory inside your active theme\'s directory, name it "simpleform", add your CSS stylesheet file, and name it "custom-style.css"', 'simpleform' ), 'cssdisabled' => __( 'Keep unchecked if you want to use your personal CSS code and include it somewhere in your theme\'s code without using an additional file', 'simpleform' ), 'jsenabled' => __( 'Create a directory inside your active theme\'s directory, name it "simpleform", add your JavaScript file, and name it "custom-script.js"', 'simpleform' ), 'jsdisabled' => __( 'Keep unchecked if you want to use your personal JavaScript code and include it somewhere in your theme\'s code without using an additional file', 'simpleform' ), 'showcharacters' => __('Keep unchecked if you want to use a generic error message without showing the minimum number of required characters', 'simpleform' ), 'hidecharacters' => __('Keep checked if you want to show the minimum number of required characters and you want to make sure that\'s exactly the number you set for that specific field', 'simpleform' ), 'numnamer' => $name_numeric_error, 'gennamer' => $name_generic_error, 'numlster' => $lastname_numeric_error, 'genlster' => $lastname_generic_error, 'numsuber' => $subject_numeric_error, 'gensuber' => $subject_generic_error, 'nummsger' => $message_numeric_error, 'genmsger' => $message_generic_error, 'privacy' => $privacy_string, 'top' => $top_position, 'bottom' => $bottom_position, 'smtpnotes' => $smtp_notes, 'required' =>  __( '(required)','simpleform'), 'optional' =>  __( '(optional)','simpleform'), 'storing_notice' => $storing_notice )); 
	      
	}
	
	/**
	 * Enable SMTP server for outgoing emails
	 *
	 * @since    1.0
	 */

	public function check_smtp_server() {
		
       $settings = get_option('sform_settings');
       $server_smtp = ! empty( $settings['server_smtp'] ) ? esc_attr($settings['server_smtp']) : 'false';
       if ( $server_smtp == 'true' ) { add_action( 'phpmailer_init', array($this,'sform_enable_smtp_server') ); }
       else { remove_action( 'phpmailer_init', 'sform_enable_smtp_server' ); }
   
   }

	/**
	 * Save SMTP server configuration.
	 *
	 * @since    1.0
	 */
	
    public function sform_enable_smtp_server( $phpmailer ) {
   
      $settings = get_option('sform_settings');
      $smtp_host = ! empty( $settings['smtp_host'] ) ? esc_attr($settings['smtp_host']) : '';
      $smtp_encryption = ! empty( $settings['smtp_encryption'] ) ? esc_attr($settings['smtp_encryption']) : '';
      $smtp_port = ! empty( $settings['smtp_port'] ) ? esc_attr($settings['smtp_port']) : '';
      $smtp_authentication = isset( $settings['smtp_authentication'] ) ? esc_attr($settings['smtp_authentication']) : '';
      $smtp_username = ! empty( $settings['smtp_username'] ) ? esc_attr($settings['smtp_username']) : '';
      $smtp_password = ! empty( $settings['smtp_password'] ) ? esc_attr($settings['smtp_password']) : '';
      $username = defined( 'SFORM_SMTP_USERNAME' ) ? SFORM_SMTP_USERNAME : $smtp_username;
      $password = defined( 'SFORM_SMTP_PASSWORD' ) ? SFORM_SMTP_PASSWORD : $smtp_password;
      $phpmailer->isSMTP();
      $phpmailer->Host       = $smtp_host;
      $phpmailer->SMTPAuth   = $smtp_authentication;
      $phpmailer->Port       = $smtp_port;
      $phpmailer->SMTPSecure = $smtp_encryption;
      $phpmailer->Username   = $username;
      $phpmailer->Password   = $password;

    }

	/**
	 * Edit the contact form fields.
	 *
	 * @since    1.0
	 */
	
    public function shortcode_costruction() {

      if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || ! wp_verify_nonce( $_POST['verification_nonce'], "ajax-verification-nonce") || ! current_user_can('manage_options') ) { die ( 'Security checked!'); }
   
      else { 
       global $wpdb; 
       $table_shortcodes = $wpdb->prefix . 'sform_shortcodes';
       $form_id = isset( $_POST['form-id'] ) ? absint($_POST['form-id']) : '1';
       $form_attributes = get_option("sform_{$form_id}_attributes") != false ? get_option("sform_{$form_id}_attributes") : get_option("sform_attributes");
       $contact_form_name = ! empty( $form_attributes['form_name'] ) ? esc_attr($form_attributes['form_name']) : '';
       $target = ! empty( $form_attributes['show_for'] ) ? esc_attr($form_attributes['show_for']) : 'all';
       $form_name_value = isset($_POST['form-name']) ? sanitize_text_field($_POST['form-name']) : '';
       $default_name_value = $form_id == '1' ? __( 'Contact Us Page','simpleform') : '';
       $form_name = $form_name_value == '' ? $default_name_value : $form_name_value;
       $form_name_list = $wpdb->get_col( "SELECT name FROM $table_shortcodes WHERE id != $form_id" );
       $newform = isset($_POST['newform']) && $_POST['newform'] == 'true' ? 'true' : 'false';     
       $embed_in = isset($_POST['embed-in']) ? absint($_POST['embed-in']) : '';     
       $widget_id = isset( $_POST['widget-id'] ) ? absint($_POST['widget-id']) : '';
       
       if ( empty($widget_id) ) {
       $show_for = isset($_POST['show-for']) ? sanitize_text_field($_POST['show-for']) : 'all';
       $user_role = isset($_POST['user-role']) && $show_for == 'in' ? sanitize_text_field($_POST['user-role']) : 'any';
       }
       else {
	   $sform_widget = get_option('widget_sform_widget');
       if ( in_array($widget_id, array_keys($sform_widget)) ) { 
       $show_for = ! empty($sform_widget[$widget_id]['sform_widget_audience']) ? $sform_widget[$widget_id]['sform_widget_audience'] : 'all';
       $user_role = ! empty($sform_widget[$widget_id]['sform_widget_role']) ? $sform_widget[$widget_id]['sform_widget_role'] : 'any';
       }
       }
 
       if ( $show_for == 'out' ) {
	      $name_field = isset($_POST['name-field']) ? 'hidden' : 'anonymous';
	      $lastname_field = isset($_POST['lastname-field']) ? 'hidden' : 'anonymous';
          $email_field = isset($_POST['email-field']) ? 'hidden' : 'anonymous';
          $phone_field = isset($_POST['phone-field']) ? 'hidden' : 'anonymous';
          $subject_field = isset($_POST['subject-field']) ? 'hidden' : 'anonymous';
          $consent_field = isset($_POST['consent-field']) ? 'hidden' : 'anonymous';
          $captcha_field = isset($_POST['captcha-field']) ? 'hidden' : 'anonymous';
       }
       elseif ( $show_for == 'in' ) {
	      $name_field = isset($_POST['name-field']) ? 'hidden' : 'registered';
	      $lastname_field = isset($_POST['lastname-field']) ? 'hidden' : 'registered';
          $email_field = isset($_POST['email-field']) ? 'hidden' : 'registered';
          $phone_field = isset($_POST['phone-field']) ? 'hidden' : 'registered';
          $subject_field = isset($_POST['subject-field']) ? 'hidden' : 'registered';
          $consent_field = isset($_POST['consent-field']) ? 'hidden' : 'registered';
          $captcha_field = isset($_POST['captcha-field']) ? 'hidden' : 'registered';
       }
       else {
          $name_field = isset($_POST['name-field']) ? sanitize_text_field($_POST['name-field']) : 'visible';
          $lastname_field = isset($_POST['lastname-field']) ? sanitize_text_field($_POST['lastname-field']) : 'visible';
          $email_field = isset($_POST['email-field']) ? sanitize_text_field($_POST['email-field']) : 'visible';
          $phone_field = isset($_POST['phone-field']) ? sanitize_text_field($_POST['phone-field']) : 'visible';
          $subject_field = isset($_POST['subject-field']) ? sanitize_text_field($_POST['subject-field']) : 'visible';
          $consent_field = isset($_POST['consent-field']) ? sanitize_text_field($_POST['consent-field']) : 'visible';
          $captcha_field = isset($_POST['captcha-field']) ? sanitize_text_field($_POST['captcha-field']) : 'hidden';
       }
       
       $form = empty($widget_id) ? $form_id : '0';
       $introduction_text = isset($_POST['introduction-text']) ? wp_kses_post(trim($_POST['introduction-text'])) : '';
       $bottom_text = isset($_POST['bottom-text']) ? wp_kses_post(trim($_POST['bottom-text'])) : '';    
       $name_visibility = isset($_POST['name-visibility']) ? 'hidden' : 'visible';
       $name_label = isset($_POST['name-label']) ? sanitize_text_field(trim($_POST['name-label'])) : '';
       $name_placeholder = isset($_POST['name-placeholder']) ? sanitize_text_field($_POST['name-placeholder']) : '';
       $name_minlength = isset($_POST['name-minlength']) ? intval($_POST['name-minlength']) : '2';
       $name_maxlength = isset($_POST['name-maxlength']) ? intval($_POST['name-maxlength']) : '0';       
       $name_requirement = isset($_POST['name-requirement']) ? 'required' : 'optional';
       $lastname_visibility = isset($_POST['lastname-visibility']) ? 'hidden' : 'visible';
       $lastname_label = isset($_POST['lastname-label']) ? sanitize_text_field(trim($_POST['lastname-label'])) : '';
       $lastname_placeholder = isset($_POST['lastname-placeholder']) ? sanitize_text_field($_POST['lastname-placeholder']) : '';
       $lastname_minlength = isset($_POST['lastname-minlength']) ? intval($_POST['lastname-minlength']) : '2';
       $lastname_maxlength = isset($_POST['lastname-maxlength']) ? intval($_POST['lastname-maxlength']) : '0';       
       $lastname_requirement = isset($_POST['lastname-requirement']) ? 'required' : 'optional';
       $email_visibility = isset($_POST['email-visibility']) ? 'hidden' : 'visible';
       $email_label = isset($_POST['email-label']) ? sanitize_text_field(trim($_POST['email-label'])) : '';
       $email_placeholder = isset($_POST['email-placeholder']) ? sanitize_text_field($_POST['email-placeholder']) : '';
       $email_requirement = isset($_POST['email-requirement']) ? 'required' : 'optional';
       $phone_visibility = isset($_POST['phone-visibility']) ? 'hidden' : 'visible';
       $phone_label = isset($_POST['phone-label']) ? sanitize_text_field(trim($_POST['phone-label'])) : '';
       $phone_placeholder = isset($_POST['phone-placeholder']) ? sanitize_text_field($_POST['phone-placeholder']) : '';
       $phone_requirement = isset($_POST['phone-requirement']) ? 'required' : 'optional';
       $subject_visibility = isset($_POST['subject-visibility']) ? 'hidden' : 'visible';
       $subject_label = isset($_POST['subject-label']) ? sanitize_text_field(trim($_POST['subject-label'])) : '';
       $subject_placeholder = isset($_POST['subject-placeholder']) ? sanitize_text_field($_POST['subject-placeholder']) : '';
       $subject_minlength = isset($_POST['subject-minlength']) ? intval($_POST['subject-minlength']) : '5';
       $subject_maxlength = isset($_POST['subject-maxlength']) ? intval($_POST['subject-maxlength']) : '0';       
       $subject_requirement = isset($_POST['subject-requirement']) ? 'required' : 'optional';
       $message_visibility = isset($_POST['message-visibility']) ? 'hidden' : 'visible';
       $message_label = isset($_POST['message-label']) ? sanitize_text_field(trim($_POST['message-label'])) : '';
       $message_placeholder = isset($_POST['message-placeholder']) ? sanitize_text_field($_POST['message-placeholder']) : '';
       $message_minlength = isset($_POST['message-minlength']) ? intval($_POST['message-minlength']) : '10';
       $message_maxlength = isset($_POST['message-maxlength']) ? intval($_POST['message-maxlength']) : '0';  
       $consent_label = isset($_POST['consent-label']) ? wp_kses_post(trim($_POST['consent-label'])) : '';    
       $privacy_url = isset($_POST['privacy-page']) && intval($_POST['privacy-page']) > 0 ? get_page_link($_POST['privacy-page']) : '';
       /* translators: Used within the string "I have read and consent to the %s". It can be replaced with the hyperlink to the privacy policy page */       
       $privacy_string = __( 'privacy policy','simpleform');
       $link = $privacy_url != '' ? '<a href="' . $privacy_url . '" target="_blank">' . $privacy_string . '</a>' : '';
       $privacy_page = isset($_POST['privacy-page']) ? intval($_POST['privacy-page']) : '0';         
       $privacy_link = isset($_POST['privacy-link']) && $privacy_page != '0' && strpos($consent_label, $link) !== false ? 'true' : 'false';
       $consent_requirement = isset($_POST['consent-requirement']) ? 'required' : 'optional';
       $captcha_label = isset($_POST['captcha-label']) ? sanitize_text_field(trim($_POST['captcha-label'])) : '';
       $submit_label = isset($_POST['submit-label']) ? sanitize_text_field(trim($_POST['submit-label'])) : '';
       $label_position = isset($_POST['label-position']) ? sanitize_key($_POST['label-position']) : 'top';
       $label_size = isset($_POST['label-size']) ? sanitize_text_field($_POST['label-size']) : 'default';
       $required_sign = isset($_POST['required-sign']) ? 'true' : 'false';
       $required_word = isset($_POST['required-word']) ? sanitize_text_field(trim($_POST['required-word'])) : '';
       $word_position = isset($_POST['word-position']) ? sanitize_key($_POST['word-position']) : 'required';
       $lastname_alignment = isset($_POST['lastname-alignment']) ? sanitize_key($_POST['lastname-alignment']) : 'name';
       $phone_alignment = isset($_POST['phone-alignment']) ? sanitize_key($_POST['phone-alignment']) : 'alone';
       $submit_position = isset($_POST['submit-position']) ? sanitize_text_field($_POST['submit-position']) : 'centred';
       $form_direction = isset($_POST['form-direction']) ? sanitize_key($_POST['form-direction']) : 'ltr';
       $css_code = isset($_POST['additional-css']) ? strip_tags($_POST['additional-css']) : '';
       $additional_css = htmlspecialchars($css_code, ENT_HTML5 | ENT_NOQUOTES | ENT_SUBSTITUTE, 'utf-8');
              
       if ( !empty($newform) && $form_name_value == '' ) {
            echo json_encode(array('error' => true, 'update' => false, 'message' => __( 'Enter a name for this form', 'simpleform' ) ));
	        exit;
       }
       
       if ( in_array($form_name, $form_name_list) )  { 
            $message = __('The name has already been used for another form, please use another one', 'simpleform' );
	        echo json_encode(array('error' => true, 'update' => false, 'message' => $message ));
	        exit; 
       }
       
       $update_result = '';
       
       if( $newform == 'false' ) {
         // Detects a modification of form name
         if ( $form_name != $contact_form_name || $show_for != $target ) {
           if ( $form_id == '1' ) {
             $update_shortcode = $wpdb->update($table_shortcodes, array('name' => $form_name, 'target' => $show_for ), array('shortcode' => 'simpleform'));
             $update_result = $update_shortcode ? 'done' : '';
           }
           else {
             $update_shortcode = $wpdb->update($table_shortcodes, array('name' => $form_name, 'target' => $show_for ), array('shortcode' => 'simpleform id="'.$form_id.'"' ));
             $update_result = $update_shortcode ? 'done' : '';
           }
         }     
       }
       
       else {
         $rows = $wpdb->get_row(" SHOW TABLE STATUS LIKE '$table_shortcodes' ");
         $shortcode_id = $rows->Auto_increment;		  
         $update_shortcode = $wpdb->insert($table_shortcodes, array('name' => $form_name_value, 'shortcode' => 'simpleform id="'.$shortcode_id.'"', 'target' => $show_for, 'status' => 'draft' ));         
         $update_result = $update_shortcode ? 'done' : '';
       }

       if ( $privacy_link == 'false' ) { 
	       $privacy_page = '0'; 
           $pattern = '/<a [^>]*>'.$privacy_string.'<\/a>/i';              
           $consent_label = preg_replace($pattern,$privacy_string,html_entity_decode($consent_label));
       }

       if ( $name_maxlength <= $name_minlength && $name_maxlength != 0 ) {
       echo json_encode(array('error' => true, 'update' => false, 'message' => __( 'The maximum name length must not be less than the minimum name length', 'simpleform' ) ));
	   exit;
       }
       
       if ( $name_minlength == 0 && $name_requirement == 'required' ) {
       echo json_encode(array('error' => true, 'update' => false, 'message' => __( 'You cannot set up a minimum length equal to 0 if the name field is required', 'simpleform' ) ));
	   exit;
       }
       
       if ( $lastname_maxlength <= $lastname_minlength && $lastname_maxlength != 0 ) {
       echo json_encode(array('error' => true, 'update' => false, 'message' => __( 'The maximum last name length must not be less than the minimum last name length', 'simpleform' ) ));
	   exit;
       }

       if ( $lastname_minlength == 0 && $lastname_requirement == 'required' ) {
       echo json_encode(array('error' => true, 'update' => false, 'message' => __( 'You cannot set up a minimum length equal to 0 if the last name field is required', 'simpleform' ) ));
	   exit;
       }

       if ( $subject_maxlength <= $subject_minlength && $subject_maxlength != 0 ) {
       echo json_encode(array('error' => true, 'update' => false, 'message' => __( 'The maximum subject length must not be less than the minimum subject length', 'simpleform' ) ));
	   exit;
       }

       if ( $subject_minlength == 0 && $subject_requirement == 'required' ) {
       echo json_encode(array('error' => true, 'update' => false, 'message' => __( 'You cannot set up a minimum length equal to 0 if the subject field is required', 'simpleform' ) ));
	   exit;
       }

       if ( $message_maxlength <= $message_minlength && $message_maxlength != 0 ) {
       echo json_encode(array('error' => true, 'update' => false, 'message' => __( 'The maximum message length must not be less than the minimum message length', 'simpleform' ) ));
	   exit;
       }

       if ( ( $name_visibility == 'hidden' ||  $lastname_visibility == 'hidden' || $email_visibility == 'hidden' || $phone_visibility == 'hidden' || $subject_visibility == 'hidden' || $message_visibility == 'hidden' ) && $label_position == 'inline' ) {	       
	   $message = $form_direction == 'ltr' ? __( 'Labels cannot be left aligned if you have set a field label as hidden', 'simpleform' ) : __( 'Labels cannot be right aligned if you have set a field label as hidden', 'simpleform' );    
       echo json_encode(array('error' => true, 'update' => false, 'message' => $message ));
	   exit;
       }
       
       $attributes = array( 'form' => $form, 'form_name' => $form_name, 'show_for' => $show_for, 'user_role' => $user_role, 'introduction_text' => $introduction_text, 'bottom_text' => $bottom_text, 'name_field' => $name_field, 'name_visibility' => $name_visibility, 'name_label' => $name_label, 'name_placeholder' => $name_placeholder, 'name_minlength' => $name_minlength, 'name_maxlength' => $name_maxlength, 'name_requirement' => $name_requirement, 'lastname_field' => $lastname_field, 'lastname_visibility' => $lastname_visibility, 'lastname_label' => $lastname_label, 'lastname_placeholder' => $lastname_placeholder, 'lastname_minlength' => $lastname_minlength, 'lastname_maxlength' => $lastname_maxlength, 'lastname_requirement' => $lastname_requirement, 'email_field' => $email_field, 'email_visibility' => $email_visibility, 'email_label' => $email_label, 'email_placeholder' => $email_placeholder, 'email_requirement' => $email_requirement, 'phone_field' => $phone_field, 'phone_visibility' => $phone_visibility, 'phone_label' => $phone_label, 'phone_placeholder' => $phone_placeholder, 'phone_requirement' => $phone_requirement, 'subject_field' => $subject_field, 'subject_visibility' => $subject_visibility, 'subject_label' => $subject_label, 'subject_placeholder' => $subject_placeholder, 'subject_minlength' => $subject_minlength, 'subject_maxlength' => $subject_maxlength, 'subject_requirement' => $subject_requirement, 'message_visibility' => $message_visibility, 'message_label' => $message_label, 'message_placeholder' => $message_placeholder, 'message_minlength' => $message_minlength, 'message_maxlength' => $message_maxlength, 'consent_field' => $consent_field, 'consent_label' => $consent_label, 'privacy_link' => $privacy_link, 'privacy_page' => $privacy_page, 'consent_requirement' => $consent_requirement, 'captcha_field' => $captcha_field, 'captcha_label' => $captcha_label, 'submit_label' => $submit_label, 'label_position' => $label_position, 'lastname_alignment' => $lastname_alignment, 'phone_alignment' => $phone_alignment, 'submit_position' => $submit_position, 'label_size' => $label_size, 'required_sign' => $required_sign, 'required_word' => $required_word, 'word_position' => $word_position,  'form_direction' => $form_direction, 'additional_css' => $additional_css );     

       $extra_fields = array('extra_fields' => '');
       $sform_attributes = array_merge($attributes, apply_filters( 'sform_recaptcha_attributes', $extra_fields ));
          
       if ( $newform == 'false' ) {          
            if ( $form_id == '1' ) {
	          $id = '1';
              $update_attributes = update_option('sform_attributes', $sform_attributes);   
            }
            else {
	          $id = $form_id;
	          $update_attributes = update_option('sform_'.$form_id.'_attributes', $sform_attributes);   
            }
       }
       else {
	          $id = $shortcode_id;
              $update_attributes = update_option('sform_'.$shortcode_id.'_attributes', $sform_attributes);   
       }           
       
       if ($update_attributes) { $update_result .= 'done'; }
 
       if ( $update_result ) {
	       
         // Update additional style to enqueue    
         $util = new SimpleForm_Util();
         $util->additional_style($id,$additional_css);

	     if ( $newform == 'false' ) {
              echo json_encode(array('error' => false, 'update' => true, 'message' => __( 'The contact form has been updated', 'simpleform' ) ));
	          exit;
         }
         else {
	          $post = !empty($embed_in) ? '&post='.$embed_in : '';     
	          $url = admin_url('admin.php?page=sform-settings&form=').$shortcode_id.'&status=new'.$post;
	          set_transient( 'sform_action_newform', $shortcode_id, 30 );
              echo json_encode(array('error' => false, 'update' => true, 'redirect' => true, 'url' => $url, 'message' => __( 'The contact form has been created', 'simpleform' ) ));
	          exit;
         }
	    
       }
   
       else {
        echo json_encode(array('error' => false, 'update' => false, 'message' => __( 'The contact form has already been updated', 'simpleform' ) ));
	    exit;
       }
      
       die();
       
      }

    }
   
	/**
	 * Edit settings
	 *
	 * @since 1.0
     * @version  2.1.8.2
	 */

    public function sform_edit_options() {

      if ( ! current_user_can('manage_options') || 'POST' !== $_SERVER['REQUEST_METHOD'] || ! wp_verify_nonce( $_POST['verification_nonce'], "ajax-verification-nonce") ) { die ( 'Security checked!' ); }

      else {
	      
       $form_id = isset( $_POST['form-id'] ) ? absint($_POST['form-id']) : '1';
	   $main_settings = get_option('sform_settings'); 
	   $admin_notices = isset($_POST['admin-notices']) ? 'true' : 'false';
       $frontend_notice = isset($_POST['frontend-notice']) ? 'true' : 'false';
       $admin_color = isset($_POST['admin-color']) ? sanitize_text_field($_POST['admin-color']) : 'default';       
       $ajax_submission = isset($_POST['ajax-submission']) ? 'true' : 'false';
       $html5_validation = isset($_POST['html5-validation']) ? 'true' : 'false';
       $focus = isset($_POST['focus']) ? sanitize_key($_POST['focus']) : 'field';
       $spinner = isset($_POST['spinner']) && $ajax_submission == 'true' ? 'true' : 'false';
       $template = isset($_POST['form-template']) ? sanitize_text_field($_POST['form-template']) : 'default';
       $stylesheet = isset($_POST['stylesheet']) ? 'true' : 'false';
       $cssfile = isset($_POST['stylesheet-file']) && $stylesheet != 'false' ? 'true' : 'false';
       $javascript = isset($_POST['javascript']) ? 'true' : 'false';
       $uninstall = isset($_POST['deletion']) ? 'true' : 'false';
       $multiple_spaces = isset($_POST['multiple-spaces']) ? 'true' : 'false';
       $outside_error = isset($_POST['outside-error']) ? sanitize_text_field($_POST['outside-error']) : 'bottom';
       $characters_length = isset($_POST['characters-length']) ? 'true' : 'false';
       $empty_fields = isset($_POST['empty-fields']) ? sanitize_text_field(trim($_POST['empty-fields'])) : '';
       $empty_name = isset($_POST['empty-name']) ? sanitize_text_field(trim($_POST['empty-name'])) : '';
       $empty_lastname = isset($_POST['empty-lastname']) ? sanitize_text_field(trim($_POST['empty-lastname'])) : '';
       $empty_phone = isset($_POST['empty-phone']) ? sanitize_text_field(trim($_POST['empty-phone'])) : '';
       $empty_email = isset($_POST['empty-email']) ? sanitize_text_field(trim($_POST['empty-email'])) : '';
       $empty_subject = isset($_POST['empty-subject']) ? sanitize_text_field(trim($_POST['empty-subject'])) : '';
       $empty_message = isset($_POST['empty-message']) ? sanitize_text_field(trim($_POST['empty-message'])) : '';
       $empty_captcha = isset($_POST['empty-captcha']) ? sanitize_text_field(trim($_POST['empty-captcha'])) : '';
       $incomplete_name = isset($_POST['incomplete-name']) ? sanitize_text_field(trim($_POST['incomplete-name'])) : '';
       $invalid_name = isset($_POST['invalid-name']) ? sanitize_text_field(trim($_POST['invalid-name'])) : '';
       $name_error = isset($_POST['name-error']) ? sanitize_text_field(trim($_POST['name-error'])) : '';
       $incomplete_lastname = isset($_POST['incomplete-lastname']) ? sanitize_text_field(trim($_POST['incomplete-lastname'])) : '';
       $invalid_lastname = isset($_POST['invalid-lastname']) ? sanitize_text_field(trim($_POST['invalid-lastname'])) : '';
       $lastname_error = isset($_POST['lastname-error']) ? sanitize_text_field(trim($_POST['lastname-error'])) : '';
       $invalid_email = isset($_POST['invalid-email']) ? sanitize_text_field(trim($_POST['invalid-email'])) : '';
       $email_error = isset($_POST['email-error']) ? sanitize_text_field(trim($_POST['email-error'])) : '';       
       $invalid_phone = isset($_POST['invalid-phone']) ? sanitize_text_field(trim($_POST['invalid-phone'])) : '';
       $phone_error = isset($_POST['phone-error']) ? sanitize_text_field(trim($_POST['phone-error'])) : '';
       $incomplete_subject = isset($_POST['incomplete-subject']) ? sanitize_text_field(trim($_POST['incomplete-subject'])) : '';
       $invalid_subject = isset($_POST['invalid-subject']) ? sanitize_text_field(trim($_POST['invalid-subject'])) : '';
       $subject_error = isset($_POST['subject-error']) ? sanitize_text_field(trim($_POST['subject-error'])) : '';
       $incomplete_message = isset($_POST['incomplete-message']) ? sanitize_text_field(trim($_POST['incomplete-message'])) : '';
       $invalid_message = isset($_POST['invalid-message']) ? sanitize_text_field(trim($_POST['invalid-message'])) : '';
       $message_error = isset($_POST['message-error']) ? sanitize_text_field(trim($_POST['message-error'])) : '';
       $consent_error = isset($_POST['consent-error']) ? sanitize_text_field(trim($_POST['consent-error'])) : '';
       $invalid_captcha = isset($_POST['invalid-captcha']) ? sanitize_text_field(trim($_POST['invalid-captcha'])) : '';
       $captcha_error = isset($_POST['captcha-error']) ? sanitize_text_field(trim($_POST['captcha-error'])) : '';
       $honeypot_error = isset($_POST['honeypot-error']) ? sanitize_text_field(trim($_POST['honeypot-error'])) : '';
       $server_error = isset($_POST['server-error']) ? sanitize_text_field(trim($_POST['server-error'])) : '';
       $duplicate_error = isset($_POST['duplicate-error']) ? sanitize_text_field(trim($_POST['duplicate-error'])) : '';
       $ajax_error = isset($_POST['ajax-error']) ? sanitize_text_field(trim($_POST['ajax-error'])) : '';
       $success_action =  isset($_POST['success-action']) ? sanitize_key($_POST['success-action']) : '';
       $success_message = isset($_POST['success-message']) ? wp_kses_post(trim($_POST['success-message'])) : '';
       $confirmation_page = isset($_POST['confirmation-page']) ? sanitize_text_field($_POST['confirmation-page']) : '';
       $thanks_url = ! empty($confirmation_page) ? esc_url_raw(get_the_guid( $confirmation_page )) : ''; 
       $server_smtp = isset($_POST['server-smtp']) ? 'true' : 'false';
       $smtp_host = isset($_POST['smtp-host']) ? sanitize_text_field(trim($_POST['smtp-host'])) : '';
       $smtp_encryption = isset($_POST['smtp-encryption']) ? sanitize_key($_POST['smtp-encryption']) : '';
       $smtp_port = isset($_POST['smtp-port']) ? sanitize_text_field(trim($_POST['smtp-port'])) : '';
       $smtp_authentication = isset($_POST['smtp-authentication']) ? 'true' : 'false';
       $smtp_username = isset($_POST['smtp-username']) ? sanitize_text_field(trim($_POST['smtp-username'])) : '';
       $smtp_password = isset($_POST['smtp-password']) ? sanitize_text_field(trim($_POST['smtp-password'])) : '';
       $username = defined( 'SFORM_SMTP_USERNAME' ) ? SFORM_SMTP_USERNAME : $smtp_username;
       $password = defined( 'SFORM_SMTP_PASSWORD' ) ? SFORM_SMTP_PASSWORD : $smtp_password;
       $notification = isset($_POST['notification']) ? 'true' : 'false';       
       $notification_recipient = isset($_POST['notification-recipient']) ? sanitize_text_field(trim($_POST['notification-recipient'])) : '';
       $notification_recipients = str_replace(' ', '', $notification_recipient);
       $bcc = isset($_POST['bcc']) ? sanitize_text_field(trim($_POST['bcc'])) : '';      
       $notification_bcc = str_replace(' ', '', $bcc);
       $notification_email = isset($_POST['notification-email']) ? sanitize_text_field(trim($_POST['notification-email'])) : '';
       $notification_name = isset($_POST['notification-name']) ? sanitize_key($_POST['notification-name']) : '';
       $custom_sender = isset($_POST['custom-sender']) ? sanitize_text_field(trim($_POST['custom-sender'])) : '';
       $notification_subject = isset($_POST['notification-subject']) ? sanitize_key($_POST['notification-subject']) : '';
       $custom_subject = isset($_POST['custom-subject']) ? sanitize_text_field(trim($_POST['custom-subject'])) : '';
       // $notification_message = isset($_POST['notification-message']) ? wp_kses_post(trim($_POST['notification-message'])) : '';
       $notification_reply = isset($_POST['notification-reply']) ? 'true' : 'false';       
       $submission_number = isset($_POST['submission-number']) ? 'hidden' : 'visible';
       $autoresponder = isset($_POST['autoresponder']) ? 'true' : 'false';
       $autoresponder_email = isset($_POST['autoresponder-email']) ? sanitize_text_field(trim($_POST['autoresponder-email'])) : '';
       $autoresponder_name = isset($_POST['autoresponder-name']) ? sanitize_text_field(trim($_POST['autoresponder-name'])) : '';
       $autoresponder_subject = isset($_POST['autoresponder-subject']) ? sanitize_text_field(trim($_POST['autoresponder-subject'])) : '';
       $autoresponder_message = isset($_POST['autoresponder-message']) ? wp_kses_post(trim($_POST['autoresponder-message'])) : '';
       $autoresponder_reply = isset($_POST['autoresponder-reply']) ? sanitize_text_field(trim($_POST['autoresponder-reply'])) : '';
	   $form_pageid = ! empty( $main_settings['form_pageid'] ) && get_post_status($main_settings['form_pageid']) ? absint($main_settings['form_pageid']) : '';  
	   $confirmation_pageid = ! empty( $main_settings['confirmation_pageid'] ) && get_post_status($main_settings['confirmation_pageid']) ? absint($main_settings['confirmation_pageid']) : '';	 
	   $duplicate = isset($_POST['duplicate']) ? 'true' : 'false';	
 
       if ( $success_action == 'message' ) { $confirmation_page = ''; }
       
       if ( $form_id != '1' ) {
	     $admin_notices = $main_settings['admin_notices']; 
		 $frontend_notice =	$main_settings['frontend_notice'];
		 $admin_color =	$main_settings['admin_color'];
		 $stylesheet = $main_settings['stylesheet'];
		 $cssfile =	$main_settings['stylesheet_file'];
		 $javascript = $main_settings['javascript'];
		 $uninstall = $main_settings['deletion_data'];
		 $server_smtp =	$main_settings['server_smtp'];
		 $smtp_host = $main_settings['smtp_host'];
		 $smtp_encryption =	$main_settings['smtp_encryption'];
		 $smtp_port = $main_settings['smtp_port'];
		 $smtp_authentication =	$main_settings['smtp_authentication'];
		 $smtp_username = $main_settings['smtp_username'];
		 $smtp_password = $main_settings['smtp_password'];
		 $duplicate = $main_settings['duplicate'];
       }

       if ( has_action('sform_validate_akismet_settings') ) { do_action('sform_validate_akismet_settings');	}

       if ( has_action('sform_validate_recaptcha_settings')) { do_action('sform_validate_recaptcha_settings'); }

       if ( $html5_validation == 'false' && $focus == 'alert' )  { 
	        echo json_encode(array('error' => true, 'update' => false, 'message' => __( 'Focus is automatically set to first invalid field if HTML5 validation is not disabled', 'simpleform' ) ));
	        exit; 
       }

       if ( $server_smtp == 'true' && $notification == 'false' && $autoresponder == 'false' )  { 
	        echo json_encode(array('error' => true, 'update' => false, 'message' => __( 'The SMTP server for outgoing email cannot be enabled if the notification or confirmation email is not enabled', 'simpleform' ) ));
	        exit; 
       }
        
	   if (  $server_smtp == 'true' && empty($smtp_host) ) {
            echo json_encode(array('error' => true, 'update' => false, 'message' => __( 'Please enter the SMTP address', 'simpleform' ) ));
	        exit; 
       }

	   if (  $server_smtp == 'true' && empty($smtp_encryption) ) {
            echo json_encode(array('error' => true, 'update' => false, 'message' => __( 'Please enter the encryption type to relay outgoing email to the SMTP server', 'simpleform' )  ));
	        exit; 
       }

	   if (  $server_smtp == 'true' && empty($smtp_port) ) {
            echo json_encode(array('error' => true, 'update' => false, 'message' => __( 'Please enter the port to relay outgoing email to the SMTP server', 'simpleform' )  ));
	        exit; 
       }
        
	   if (  $server_smtp == 'true' && ! ctype_digit(strval($smtp_port)) ) {
            echo json_encode(array('error' => true, 'update' => false, 'message' => __( 'Please enter a valid port to relay outgoing email to the SMTP server', 'simpleform' ) ));
	        exit; 
       }

	   if (  $server_smtp == 'true' && $smtp_authentication == 'true' && empty( $username ) ) { 
            echo json_encode(array('error' => true, 'update' => false, 'message' => __( 'Please enter username to log in to SMTP server', 'simpleform' )  ));
	        exit; 
       }
	
	   if (  $server_smtp == 'true' && $smtp_authentication == 'true' &&  ! empty($username) && ! is_email( $username ) ) {
            echo json_encode(array('error' => true, 'update' => false, 'message' => __( 'Please enter a valid email address to log in to SMTP server', 'simpleform' )  ));
	        exit; 
       }
        
	   if (  $server_smtp == 'true' && $smtp_authentication == 'true' && empty( $password ) ) {
            echo json_encode(array('error' => true, 'update' => false, 'message' => __( 'Please enter password to log in to SMTP server', 'simpleform' )  ));
	        exit; 
       }
 
       if (has_action('sforms_validate_submissions_settings')):
	       do_action('sforms_validate_submissions_settings');	
	   else:
       if ( $notification == 'false' )  { 
 	        echo json_encode(array('error' => true, 'update' => false, 'message' => __( 'You need to enable the notification email', 'simpleform' ) ));
	        exit; 
       }
	   endif;
	   
       $settings = array(
	             'admin_notices' => $admin_notices,
	             'frontend_notice' => $frontend_notice,
                 'admin_color' => $admin_color,
                 'ajax_submission' => $ajax_submission,
                 'spinner' => $spinner,
	             'html5_validation' => $html5_validation,
	             'focus' => $focus,
                 'form_template' => $template,
                 'stylesheet' => $stylesheet,
                 'stylesheet_file' => $cssfile, 
                 'javascript' => $javascript,
                 'deletion_data' => $uninstall,
                 'multiple_spaces' => $multiple_spaces,
                 'outside_error' => $outside_error,
                 'empty_fields' => $empty_fields,
                 'characters_length' => $characters_length,
                 'empty_name' => $empty_name,
                 'incomplete_name' => $incomplete_name, 
                 'invalid_name' => $invalid_name, 
                 'name_error' => $name_error,      
                 'empty_lastname' => $empty_lastname,
                 'incomplete_lastname' => $incomplete_lastname, 
                 'invalid_lastname' => $invalid_lastname, 
                 'lastname_error' => $lastname_error,      
                 'empty_email' => $empty_email,
                 'invalid_email' => $invalid_email,  
                 'email_error' => $email_error,  
                 'empty_phone' => $empty_phone,
                 'invalid_phone' => $invalid_phone, 
                 'phone_error' => $phone_error,      
                 'empty_subject' => $empty_subject,
                 'incomplete_subject' => $incomplete_subject, 
                 'invalid_subject' => $invalid_subject,  
                 'subject_error' => $subject_error,                    
                 'empty_message' => $empty_message,
                 'incomplete_message' => $incomplete_message,    
                 'invalid_message' => $invalid_message,
                 'message_error' => $message_error,
                 'consent_error' => $consent_error,
                 'empty_captcha' => $empty_captcha,
                 'invalid_captcha' => $invalid_captcha,    
                 'captcha_error' => $captcha_error,
                 'honeypot_error' => $honeypot_error,    
                 'duplicate_error' => $duplicate_error,
                 'ajax_error' => $ajax_error,        
                 'server_error' => $server_error,
                 'success_action' => $success_action,         
                 'success_message' => $success_message, 
                 'confirmation_page' => $confirmation_page,        
                 'thanks_url' => $thanks_url,
                 'server_smtp' => $server_smtp,
                 'smtp_host' => $smtp_host,
                 'smtp_encryption' => $smtp_encryption,
                 'smtp_port' => $smtp_port,
                 'smtp_authentication' => $smtp_authentication,
                 'smtp_username' => $smtp_username,
                 'smtp_password' => $smtp_password,
                 'notification' => $notification,
                 'notification_recipient' => $notification_recipients,
                 'bcc' => $notification_bcc,
                 'notification_email' => $notification_email,
                 'notification_name' => $notification_name,
                 'custom_sender' => $custom_sender,
                 'notification_subject' => $notification_subject,
                 'custom_subject' => $custom_subject,
                 // 'notification_message' => $notification_message,
                 'notification_reply' => $notification_reply,
                 'submission_number' => $submission_number,  
                 'autoresponder' => $autoresponder, 
                 'autoresponder_email' => $autoresponder_email,
                 'autoresponder_name' => $autoresponder_name,
                 'autoresponder_subject' => $autoresponder_subject,
                 'autoresponder_message' => $autoresponder_message,
                 'autoresponder_reply' => $autoresponder_reply,
                 'duplicate' => $duplicate,             
	             'form_pageid' => $form_pageid,
	             'confirmation_pageid' => $confirmation_pageid,	
                 ); 

       $extra_fields = array('additional_fields' => '');
       $submissions_sform_settings = array_merge($settings, apply_filters( 'sform_submissions_settings_filter', $extra_fields ));
       $additional_sform_settings = array_merge($submissions_sform_settings, apply_filters( 'sform_akismet_settings_filter', $extra_fields ));
       $extra_sform_settings = array_merge($additional_sform_settings, apply_filters( 'sform_recaptcha_settings', $extra_fields ));
       $update_result = $form_id == '1' ? update_option('sform_settings', $extra_sform_settings) : update_option("sform_{$form_id}_settings", $extra_sform_settings); 
                  
       if ( $update_result ) {
	       
         // Update additional scripts to enqueue    
         $util = new SimpleForm_Util();
         $util->additional_script($form_id,$settings);
	       
	     echo json_encode( array( 'error' => false, 'update' => true, 'message' => __( 'Settings were successfully saved', 'simpleform' ) ) ); 
	     exit; 
       }
      
       else {
	     echo json_encode( array( 'error' => false, 'update' => false, 'message' => __( 'Settings have already been saved', 'simpleform' ) ) );
	     exit; 	   
       }
  	         
      die();
      
      }

    }  
    
	/**
	 * Return shortcode properties
	 *
	 * @since    1.0
	 */
	
    public function sform_form_filter($attribute) { 
		
     global $wpdb;
     
     if ($attribute == '') {
     $form_values = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}sform_shortcodes", ARRAY_A );  
     }
     else {
     $form_values = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}sform_shortcodes WHERE shortcode = %s", $attribute), ARRAY_A );
     }
     
     return $form_values;
     
    } 

    /**
     * Deleting the table whenever a single site into a network is deleted.
     *
     * @since    1.2
     */

    public function on_delete_blog($tables) {
      
      global $wpdb;
      $tables[] = $wpdb->prefix . 'sform_submissions';
      return $tables;
			
    }
    
	/**
	 * Add the link to the Privacy Policy page in the consent label.
	 *
	 * @since    1.9.2
	 */
	
    public function setting_privacy() {

      if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || ! wp_verify_nonce( $_POST['verification_nonce'], "ajax-verification-nonce") || ! current_user_can('manage_options') ) { die ( 'Security checked!'); }
 
      else { 
        $page = isset($_POST['page-id']) ? absint($_POST['page-id']) : 0;   
        $privacy_label = isset($_POST['consent-label']) ? wp_kses_post(trim($_POST['consent-label'])): ''; 
        /* translators: Used within the string "I have read and consent to the %s". It can be replaced with the hyperlink to the privacy policy page */       
        $privacy_string = __( 'privacy policy','simpleform');
        if ( $page > 0 ) {
    	   $link = '<a href="' . get_page_link($page) . '" target="_blank">' . $privacy_string . '</a>';
    	   $url = get_page_link($page);
	       // If the consent label still contains the original string
	       if (  strpos($privacy_label, $privacy_string) !== false ) { 
              // Check if a link to privacy policy page already exists, and remove it:
              $pattern = '/<a [^>]*>'.$privacy_string.'<\/a>/i';              
              if( preg_match($pattern,$privacy_label) ) {
	          $label = preg_replace($pattern,$link,html_entity_decode($privacy_label));
              } else {
              // If a link to privacy policy page not exists:
	          $label = str_replace($privacy_string,$link,html_entity_decode($privacy_label));	    
              }
              echo json_encode( array( 'error' => false, 'label' => $label, 'url' => $url ) );
	          exit;
           } 
           // If the consent label not contains the original string
           else {
              /* translators: %s: privacy policy, it can contain the hyperlink to the page */
    	      $label = sprintf( __( 'I have read and consent to the %s', 'simpleform' ), $link );	
              echo json_encode( array( 'error' => false, 'label' => $label, 'url' => $url ) );
	          exit;
           }
        }   
        else {
           echo json_encode( array( 'error' => false ));
	       exit;
        }
        die();
      }

    }
    
	/**
	 * Return an update message if there's a release waiting
	 *
	 * @since    1.9.2
	 */
	
    public function update_message() { 
		
     $updates = (array) get_option( '_site_transient_update_plugins' );
     if ( isset( $updates['response'] ) && array_key_exists( SIMPLEFORM_BASENAME, $updates['response'] ) ) {
            $update_message = '<span class="admin-notices update"><a href="'.self_admin_url('plugins.php').'" target="_blank">'. __('There is a new version of SimpleForm available. Get the latest features and improvements!', 'simpleform') .'</a></span>';
	 } 
	 else { $update_message = ''; } 
	 	 
     return $update_message;
     
    } 
    	
    /**
	 * Add support links in the plugin meta row
	 *
	 * @since    1.10
	 */
	
    public function plugin_meta( $plugin_meta, $file ) {

     /* translators: %1$s: native language name, %2$s: URL to translate.wordpress.org */
      $message = __('SimpleForm is not translated into %1$s yet. <a href="%2$s">Help translate it!</a>', 'simpleform' );
      $translation_message = __('Help improve the translation', 'simpleform' );
      $claim = __('Contact form made simple', 'simpleform' );
      $claim2 = __('Amazingly easy, surprisingly powerful', 'simpleform' );

      if ( strpos( $file, SIMPLEFORM_BASENAME ) !== false ) {
		$plugin_meta[] = '<a href="https://wordpress.org/support/plugin/simpleform/" target="_blank">'.__('Support', 'simpleform').'</a>';
		}
		
	  return $plugin_meta;

	}
	
    /**
	 * Display additional action links in the plugins list table  
	 *
	 * @since    1.10
	 */
	
    public function plugin_links( $plugin_actions, $plugin_file ){ 
    
      $new_actions = array();
	  if ( SIMPLEFORM_BASENAME === $plugin_file ) { 
		
		if ( is_multisite() ) {   
		  $url = network_admin_url('plugin-install.php?tab=search&type=tag&s=simpleform-addon');
		} else {
		  $url = admin_url('plugin-install.php?tab=search&type=tag&s=simpleform-addon');
		} 
		  
      $new_actions['sform_settings'] = '<a href="' . menu_page_url( 'sform-entries', false ) . '">' . __('Dashboard', 'simpleform') . '</a> | <a href="' . menu_page_url( 'sform-editor', false ) . '">' . __('Editor', 'simpleform') . '</a> | <a href="' . menu_page_url( 'sform-settings', false ) . '">' . __('Settings', 'simpleform') . '</a> | <a href="'.$url.'" target="_blank">' . __('Addons', 'simpleform') . '</a>';
  	  }
     
      return array_merge( $new_actions, $plugin_actions );

    }

	/**
	 * Fallback for database table updating if plugin is already active.
	 *
	 * @since    1.10
	 */
    
    public function db_version_check() {
    
        $current_db_version = SIMPLEFORM_DB_VERSION; 
        $installed_version = get_option('sform_db_version');
    
        if ( $installed_version != $current_db_version ) {
          require_once SIMPLEFORM_PATH . 'includes/class-activator.php';
	      SimpleForm_Activator::create_db();
		  SimpleForm_Activator::default_data_entry();          
 		  SimpleForm_Activator::enqueue_additional_code();          
       }

    }
    
	/**
	 * Clean up the post content of any removed or duplicated form
     *
	 * @since    2.1.4
	 */
	
    public function forms_cleaning($post_id,$post,$form_ids,$used_forms) {
        
        $cleanup = '';
        $content = $post->post_content;
        $removed = array_diff($used_forms, $form_ids);
        $duplicated = array_unique(array_diff_assoc($used_forms, array_unique($used_forms)));
        $block_pattern = '/<!-- wp:simpleform(.*)\/-->/';
        $shortcode_pattern = '/<!-- wp:shortcode -->([^>]*)<!-- \/wp:shortcode -->/';
        $pattern = '/\[simpleform(.*?)\]/';

        if ( ! empty($removed) ) {
          $cleanup = 'true';
          foreach ($removed as $id) {	
     	    $search_blockId = '"formId":"'.$id.'"';  
     	    $search_shortcodeId = '[simpleform id="'.$id.'"]';  
            preg_match_all($block_pattern, $content, $matches_block);     
            if ( $matches_block ) {
              foreach ( $matches_block[0] as $block ) {
  		        if ( strpos($block, $search_blockId ) !== false ) {
                  $content = str_replace($block, '', $content);
                }
              }
            }
            preg_match_all($shortcode_pattern, $content, $matches_shortcode);
            if ( $matches_shortcode ) {
              foreach ( $matches_shortcode[0] as $block ) {
	            if ( strpos($block,$search_shortcodeId) !== false ) { 
                  $content = str_replace($block, '', $content);
                }
              }
            }
            preg_match_all($pattern, $content, $matches_pattern);     
            if ( $matches_pattern ) {
              foreach ( $matches_pattern[0] as $shortcode ) {
		         if ( $shortcode === $search_shortcodeId ) { 
                   $content = str_replace($shortcode, '', $content);
                 }
              }
            }
          }
        }       
        
        if ( ! empty($duplicated) ) {
          $cleanup = 'true';
          $matching = '';
          foreach ($duplicated as $id) {	
     	    $search_blockId = '"formId":"'.$id.'"';  
     	    $search_shortcodeId = $id != '1' ? '[simpleform id="'.$id.'"]' : '[simpleform]';  
            preg_match_all($block_pattern, $content, $matchesBlock);     
            if ( $matchesBlock ) {
              foreach ( $matchesBlock[0] as $block ) {
  			    if ( strpos($block, $search_blockId ) !== false ) {
	  		      $matching .= '1';
                  $splitted_content = explode($block,$content,2);
                  // Keep only the last one and delete the other duplicates
                  if ( ! empty($splitted_content[1]) && strpos($splitted_content[1], $search_blockId ) !== false  ) {
                    $content= implode('',$splitted_content);
                  }
                  else { $content = $content; }
                }
              }
            }
            preg_match_all($shortcode_pattern, $content, $matchesShortcode);
            if ( $matchesShortcode ) {
              foreach ( $matchesShortcode[0] as $block ) {
		        if ( strpos($block,$search_shortcodeId) !== false ) { 
	  	  		  if ( empty($matching) ) {
	  		        $matching .= '1';
                    $splitted_content = explode($block,$content,2);
                    // Keep only the last one and delete the other duplicates
                    if ( ! empty($splitted_content[1]) && strpos($splitted_content[1], $search_shortcodeId ) !== false  ) {
                      $content= implode('',$splitted_content);
                    }
                    else { $content = $content; }
  	   		      }
  	   		      // Delete all shortcodes and keep the block
	  			  else { $content = str_replace($block, '', $content); }
                }
              }
            }
            preg_match_all($pattern, $content, $matchesPattern);     
            if ( $matchesPattern ) {
              foreach ( $matchesPattern[0] as $shortcode ) {
		         if ( $shortcode === $search_shortcodeId ) { 
	  			   if ( empty($matching) ) {
	  		         $matching .= '1';
                     $splitted_content = explode($block,$content,2);
                     // Keep only the last one and delete the other duplicates
                     if ( ! empty($splitted_content[1]) && strpos($splitted_content[1], $search_shortcodeId ) !== false  ) {
                       $content= implode('',$splitted_content);
                     }
                     else { $content = $content; }
  		           }
  		           // Delete all shortcodes and keep the block
	  			   else { $content = str_replace($shortcode,'',$content); }
                 }
              }
            }
          }
        }
        
        // Avoid infinite loop in save_post callback
        if ( !empty($cleanup) ) {
          remove_action( 'save_post', 'sform_pages_list' );
          wp_update_post( array( 'ID' => $post_id, 'post_content' => $content ) );
          add_action( 'save_post', 'sform_pages_list' );
        }
           
    }

	/**
	 * Update pages list containing a form when a page is edited
     *
	 * @since    2.0.5
     * @version  2.1.3
	 */

	public function sform_pages_list( $post_id, $post ) {

      // Return if not yet published
      if ( $post->post_status !== 'publish' )
      return;
      
      $post_id = intval($post_id);
      
      // If this is a revision, get real post ID
      if ( $parent_id = wp_is_post_revision($post_id) )
      $post_id = intval($parent_id);
      $id = array($post_id);
      
      // Search simpleform in the post content
      $util = new SimpleForm_Util();      
      $used_forms = $util->used_forms($post->post_content,$type = 'all');
      
      // If the post content contains simpleform
      if ( ! empty($used_forms) ) {
        global $wpdb;
        $form_ids = $util->sform_ids();
        
        // Clean up the post content of any non-existent and ..........redundant form
        do_action( 'forms_cleaning', $post_id, $post, $form_ids, $used_forms ); 
        
        foreach ($form_ids as $form_id) {	       
          $form_pages = $wpdb->get_row( $wpdb->prepare( "SELECT form_pages, form_widgets FROM {$wpdb->prefix}sform_shortcodes WHERE id = %d", $form_id ) );
          $form_pages_ids = ! empty($form_pages->form_pages) ? explode(',',$form_pages->form_pages) : array();
          if ( in_array($form_id,$used_forms) ) {
              if ( !in_array($post_id,$form_pages_ids) ) {
                $new_form_pages = implode(',', array_unique(array_merge($id,$form_pages_ids))); 
                $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->prefix}sform_shortcodes SET form_pages = '%s', status = 'published' WHERE id = %d", $new_form_pages, $form_id) );
              }
	      }	
		  else {
            if ( in_array($post_id,$form_pages_ids) ) {
              $updated_form_pages = array_diff($form_pages_ids,$id);
              $new_form_pages = implode(",", $updated_form_pages); 
              $form_status = !empty($form_pages->form_pages) || !empty($form_pages->form_widgets) ? 'published' : 'draft';
              $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->prefix}sform_shortcodes SET form_pages = '%s', status = '%s' WHERE id = %d", $new_form_pages, $form_status, $form_id) );
            }
	      }
        }
        $sform_pages = get_option('sform_pages') != false ? get_option('sform_pages') : $util->form_pages($form_id = '0');     
        if ( !in_array($post_id,$sform_pages) ) {
	      $updated_sform_pages = array_unique(array_merge($id,$sform_pages)); 
	      update_option('sform_pages',$updated_sform_pages);
        }
      }
      
      // If the post content does not contains simpleform
      else {
        $sform_pages = get_option('sform_pages') != false ? get_option('sform_pages') : $util->form_pages($form_id = '0');     
        if ( in_array($post_id,$sform_pages) ) {
          global $wpdb;
          $form_ids = $util->sform_ids();
          foreach ($form_ids as $form_id) {
            $form_pages = $wpdb->get_row( $wpdb->prepare( "SELECT form_pages, form_widgets FROM {$wpdb->prefix}sform_shortcodes WHERE id = %d", $form_id ) );
            $form_pages_ids = ! empty($form_pages->form_pages) ? explode(',',$form_pages->form_pages) : array();
            if ( in_array($post_id,$form_pages_ids) ) {
              $updated_form_pages = array_diff($form_pages_ids,$id);
              $new_form_pages = implode(",", $updated_form_pages); 
              $form_status = !empty($form_pages->form_pages) || !empty($form_pages->form_widgets) ? 'published' : 'draft';
              $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->prefix}sform_shortcodes SET form_pages = %s, status = %s WHERE id = %d", $new_form_pages, $form_status, $form_id) );
	        }
 	      }
	      $updated_sform_pages = array_diff($sform_pages,$id); 
	      update_option('sform_pages',$updated_sform_pages);
	      	      
        }
      }
                
    }
            
	/**
	 * Change Admin Color Scheme.
	 *
	 * @since    2.0
	 */
	
    public function admin_color_scheme() {

      if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || ! wp_verify_nonce( $_POST['verification_nonce'], "ajax-verification-nonce") || ! current_user_can('manage_options') ) { die ( 'Security checked!'); }
      
      else { 
        $admin_color = isset($_POST['admin-color']) && in_array($_POST['admin-color'], array('default', 'light', 'modern', 'blue', 'coffee', 'ectoplasm', 'midnight', 'ocean', 'sunrise', 'foggy', 'polar' )) ? sanitize_text_field($_POST['admin-color']) : '';
        if ( !empty($admin_color) ) {
	       $main_settings = get_option('sform_settings');
	       $main_settings['admin_color'] = $admin_color;
           $update = update_option('sform_settings', $main_settings);                       
	       if ( $update ) {
              global $wpdb;
              $shortcodes_table = $wpdb->prefix . 'sform_shortcodes';
              $ids = $wpdb->get_col("SELECT id FROM `$shortcodes_table` WHERE id != '1'");	
              if ( $ids ) {
	           foreach ( $ids as $id ) {
	             $form_settings = get_option('sform_'.$id.'_settings');
                 if ( $form_settings != false ) {
	             $form_settings['admin_color'] = $admin_color;
                 update_option('sform_'.$id.'_settings', $form_settings); 
                 }
               }
              }
              echo json_encode( array( 'error' => false, 'color' => $admin_color ) );
	          exit;
           } 
           else {
              echo json_encode( array( 'error' => true ) );
	          exit;
           }
        } 
        else {
           echo json_encode( array( 'error' => true ));
	       exit;
        }
        die();
      }

    }
        
	/**
	 * Delete form.
	 *
	 * @since    2.0.4
	 */
	
    public function sform_delete_form() {

      if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || ! wp_verify_nonce( $_POST['sform_nonce'], "sform_nonce_deletion") || ! current_user_can('manage_options') ) { die ( 'Security checked!'); }
      
      else {
        $form_id = isset( $_POST['form-id'] ) ? absint($_POST['form-id']) : '1';
        $form_name = isset($_POST['form-name']) ? sanitize_text_field($_POST['form-name']) : '';
        global $wpdb; 
        $submission_table = "{$wpdb->prefix}sform_submissions";    
        $where_submissions = defined('SIMPLEFORM_SUBMISSIONS_NAME') ? "AND object != '' AND object != 'not stored'" : '';
 		$entries = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions WHERE form = '$form_id' $where_submissions");
        $confirmation = isset($_POST['confirmation']) ? sanitize_text_field($_POST['confirmation']) : '';
 		$hidden_input = '<input type="hidden" id="confirmation" name="confirmation" value="true">';
        $entries_message = sprintf( _n( 'The form contains %s message.', 'The form contains %s messages.', $entries ), $entries ) . '&nbsp;' . __( 'By Proceeding, all messages will be hidden from list tables.', 'simpleform' ) . '&nbsp;' . __( 'If it is permanently deleted, all messages will also be permanently deleted.', 'simpleform' ) . '&nbsp;' . __( 'Are you sure you don’t want to move them to another form first?', 'simpleform' );        
       
     	if ( $entries && $confirmation == '' ) {
            echo json_encode(array('error' => true, 'message' => $entries_message, 'confirm' => $hidden_input ));
	        exit; 
         }
        
       	if ( !$entries || ( $entries && $confirmation == 'true' ) ) {
        
        $table_post = $wpdb->prefix . 'posts';
	    $form_pages_list = $wpdb->get_var( "SELECT form_pages FROM {$wpdb->prefix}sform_shortcodes WHERE id = {$form_id}" );
	    $widget = $wpdb->get_var( "SELECT widget FROM {$wpdb->prefix}sform_shortcodes WHERE id = {$form_id} AND widget != 0" );
	    // $widget_id = $wpdb->get_var( "SELECT widget_id FROM {$wpdb->prefix}sform_shortcodes WHERE id = {$form_id}" );
	    $widget_id = $wpdb->get_var( "SELECT form_widgets FROM {$wpdb->prefix}sform_shortcodes WHERE id = {$form_id}" );
	    
        $form_pages = $form_pages_list ? explode(',',$form_pages_list) : array();
	    $deletion = $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->prefix}sform_shortcodes SET relocation = '0', moveto = '0', to_be_moved = '', onetime_moving = '1', previous_status = status, status = 'trash', deletion = '1' WHERE id = '%d'", $form_id) );	
        $img = '<span class="dashicons dashicons-saved"></span>';
       
        if ( $deletion ) {
	      $post_cleaning = '';
          // $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->prefix}sform_submissions SET previous_status = status, status = 'trash', hidden = '1' WHERE form = %d", $form_id) );
          $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->prefix}sform_submissions SET hidden = '1' WHERE form = %d", $form_id) );
          if ( $form_pages ) {
            foreach ($form_pages as $postID) {
	          $post = get_post($postID);
	          $content = $post->post_content;
	          $search_shortcode = $form_id != '1' ? '[simpleform id="'.$form_id.'"]' : '[simpleform]';
	          if ( has_blocks($content) ) {
               $plugin_block = '/<!-- wp:simpleform(.*)\/-->/';
               preg_match_all($plugin_block, $content, $matches_block);     
               if ( $matches_block ) {
      		    foreach ( $matches_block[0] as $block ) {
       		      if ( strpos($block, '"formId":"'.$form_id.'"') !== false ) {
                     $content = str_replace($block, '', $content);
                  }
                }
               }
	           $shortcode_block = '/<!-- wp:shortcode([^>]*)-->(.*?)<!-- \/wp:shortcode -->/s';
               preg_match_all($shortcode_block, $content, $matches_shortcode);
               if ( $matches_shortcode ) {
		        foreach ( $matches_shortcode[0] as $shortcode ) {
		          if ( strpos($shortcode,$search_shortcode) !== false ) { 
	    	        $content = str_replace($shortcode, '', $content);
                    break;
                  }
                }
               }
              }
              // Remove shortcode not included in a block
		      if ( strpos($content, $search_shortcode) !== false ) {
                $content = str_replace($search_shortcode, '', $content);
              } 
              $cleaning = $wpdb->update( $table_post, array( 'post_content' => $content ), array( 'ID' => $postID ) );
              if ( $cleaning ) { $post_cleaning .= 'done'; }
            }  
          }
          if ( $widget ) { 
            $sform_widget = get_option('widget_sform_widget');         
            unset($sform_widget[$widget]);
            update_option('widget_sform_widget', $sform_widget);
            $sidebars_widgets = get_option('sidebars_widgets');
            foreach ( $sidebars_widgets as $sidebar => $widgets ) {
	          if ( is_array( $widgets ) ) {
		        foreach ( $widgets as $index => $widget_id ) {
			      if ( $widget_id == 'sform_widget-'.$widget ) {
                    unset($sidebars_widgets[$sidebar][$index]);
                    $cleaning = update_option('sidebars_widgets', $sidebars_widgets);
                    if ( $cleaning ) { $post_cleaning .= 'done'; }
                  }
                }
              }
            }
          }
          if ( $widget_id ) { 
            $widget_block = get_option('widget_block');         
            if ( !empty($widget_block) ) {
              foreach ($widget_block as $key => $value ) {
                if ( is_array($value) ) {   
	               $string = implode('',$value);
                   if ( strpos($string, 'wp:simpleform/form-selector' ) !== false ) { 
	                  $split_id = ! empty($string) ? explode('formId":"', $string) : '';
	                  $id = isset($split_id[1]) ? explode('"', $split_id[1])[0] : '';
	                  if ( $id == $form_id ) {
			            unset($widget_block[$key]);
			            update_option('widget_block', $widget_block);
                      }
                   }
                   if ( ( strpos($string,'wp:shortcode') && strpos($string,'[simpleform') ) !== false ) { 
	                 $split_shortcode = ! empty($string) ? explode('[simpleform', $string) : '';
	                 $split_id = isset($split_shortcode[1]) ? explode(']', $split_shortcode[1])[0] : '';
	                 $id = empty($split_id) ? '1' : filter_var($split_id, FILTER_SANITIZE_NUMBER_INT);
	                 if ( $id == $form_id ) {
			           unset($widget_block[$key]);
			           update_option('widget_block', $widget_block);
	                 }
                   }
                }
              }
            }
            $sidebars_widgets = get_option('sidebars_widgets');
            foreach ( $sidebars_widgets as $sidebar => $widgets ) {
	          if ( is_array( $widgets ) ) {
		        foreach ( $widgets as $index => $id ) {
			      if ( $id == $widget_id ) {
                    unset($sidebars_widgets[$sidebar][$index]);
                    $cleaning = update_option('sidebars_widgets', $sidebars_widgets);
                    if ( $cleaning ) { $post_cleaning .= 'done'; }
                  }
                }
              }
            }
          }
          if ( ! empty($post_cleaning) ) {
	        $wpdb->update($wpdb->prefix . 'sform_shortcodes', array('shortcode_pages' => '', 'block_pages' => '', 'form_pages' => '', 'form_widgets' => '', 'widget' => '0', 'widget_id' => ''), array('id' => $form_id ));
	        $message = sprintf( __( 'Form "%s" moved to trash. All pages containing the form have been cleaned up.', 'simpleform' ), $form_name );
	        echo json_encode(array('error' => false, 'message' => $message, 'img' => $img, 'redirect_url' => admin_url('admin.php?page=sform-forms') ));
	        exit;
	      }
	      else {
	        echo json_encode(array('error' => false, 'message' => sprintf( __( 'Form "%s" moved to trash', 'simpleform' ), $form_name ), 'img' => $img, 'redirect_url' => admin_url('admin.php?page=sform-forms') ));
	        exit;
	      }
        }
        
        else {
	        echo json_encode(array('error' => true, 'message' => __( 'Oops!', 'simpleform' ) .'<br>'. __( 'Error occurred deleting the form. Try again!', 'simpleform' ) ));
	        exit; 
        }
        
        }
        die();
      }

    }  
    
	/**
	 * Setup function that registers the screen option.
	 *
	 * @since    1.0
	 */

    public function forms_list_options() {
	    
      global $sform_forms;
      $screen = get_current_screen();      
           
      if(!is_object($screen) || $screen->id != $sform_forms)
      return;
      $option = 'per_page';
      $args = array( 'label' => esc_attr__('Number of forms per page', 'simpleform'),'default' => 10,'option' => 'edit_form_per_page');
      
      add_screen_option( $option, $args );
      $table = new SimpleForm_Forms_List(); 
        
    }

	/**
	 * Save screen options.
	 *
	 * @since    2.1
	 */

    public function forms_screen_option($status, $option, $value) {
      
     if ( 'edit_form_per_page' == $option ) return $value;
     return $status;
    
    }
    
	/**
	 * Register a post type for change the pagination in Screen Options tab.
	 *
	 * @since    2.1
	 */

    public function form_post_type() {
	
	    $args = array();
	    register_post_type( 'form', $args );
	    
    }
    
     /**
	 * Show the parent menu active for hidden sub-menu item
	 *
	 * @since    2.1
	 */
	
    public function contacts_menu_open($parent_file) {

      global $plugin_page;

      if ( $plugin_page === 'sform-form' || $plugin_page === 'sform-new' ) {
        $plugin_page = 'sform-forms';
      } 
    
      return $parent_file;
      
    }
    
	/**
	 * Edit the form card
	 *
	 * @since    2.1
	 */
	
    public function form_update() {
	    
      if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || ! wp_verify_nonce( $_POST['verification_nonce'], "ajax-verification-nonce") || ! current_user_can('manage_options') ) { die ( 'Security checked!'); }
   
      else { 
       
       $form_id = isset( $_POST['form-id'] ) ? absint($_POST['form-id']) : '1';
       $util = new SimpleForm_Util();      
       $form_ids = $util->sform_ids();

       if ( ! in_array($form_id, $form_ids) )  { 
	      echo json_encode(array('error' => true, 'redirect' => true,  'url' => admin_url('admin.php?page=sform-forms'), 'message' => __('The form has been permanently deleted', 'simpleform' ) ));
	      exit; 
       }
       
       else  { 

         global $wpdb; 
         $table_submissions = $wpdb->prefix . 'sform_submissions';
         $table_shortcodes = $wpdb->prefix . 'sform_shortcodes';
         $relocation = isset($_POST['relocation']) ? true : false;
         $moveto = isset($_POST['moveto']) ? intval($_POST['moveto']) : '0';       
         $to_be_moved = isset($_POST['starting']) ? sanitize_text_field($_POST['starting']) : '';       
         $onetime = isset($_POST['onetime']) ? true : false;
         $restoration = isset($_POST['restore']) ? true : false;
         $deletion = isset($_POST['deletion-form']) ? true : false;
         $settings = isset($_POST['settings']) ? true : false;
         $form_to_name = isset($_POST['form-to']) ? sanitize_text_field($_POST['form-to']) : '';
         $submissions = isset($_POST['submissions']) ? intval($_POST['submissions']) : '0';       
         $moved_submissions = isset($_POST['moved-submissions']) ? intval($_POST['moved-submissions']) : '0';
         $form_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_shortcodes WHERE id = '%d'", $form_id) );
         $where_day = 'AND date >= UTC_TIMESTAMP() - INTERVAL 24 HOUR';
         $where_week = 'AND date >= UTC_TIMESTAMP() - INTERVAL 7 DAY';
         $where_month = 'AND date >= UTC_TIMESTAMP() - INTERVAL 30 DAY';
         $where_year = 'AND date >= UTC_TIMESTAMP() - INTERVAL 1 YEAR';
         $where_submissions = defined('SIMPLEFORM_SUBMISSIONS_NAME') ? "AND object != '' AND object != 'not stored'" : '';
         
       	 if ( esc_attr($form_data->status) == 'trash' ) {
            echo json_encode(array('error' => true, 'message' => __( 'It is not allowed to make changes on a trashed form', 'simpleform' ) ));
	        exit; 
         }

    	 if ( $relocation == true && $moveto != '0' && $to_be_moved == '' ) {
            echo json_encode(array('error' => true, 'message' => __( 'Select messages to be moved', 'simpleform' )  ));
	        exit; 
         }
         
      	 if ( $relocation == true && $moveto != '0' && $to_be_moved != '' && $to_be_moved != 'next' && $restoration == true ) {
            echo json_encode(array('error' => true, 'message' => __( 'It is not allowed to move and restore messages at the same time', 'simpleform' ) ));
	        exit; 
         }
         
         // Not ovverride settings when the moving is not sheduled
         if ( $relocation == false || $moveto == '0' || $to_be_moved == '' || ( $to_be_moved != 'next' && $onetime == true ) ) {
            $settings = false;
         }

	     // Check if a moving is running 
         if ( $relocation == true && $moveto != '0' && $to_be_moved != '' && $restoration == false ) {	         
        
           $update = '';
           
           if ( $to_be_moved != 'next' ) {
	           
		     switch ($to_be_moved) {
               case $to_be_moved == 'lastyear':
               $where = $where_year;
               $timestamp_msg = strtotime("-1 year");
               break;
               case $to_be_moved == 'lastmonth':
               $where = $where_month;
               $timestamp_msg = strtotime("-1 month");
               break;
               case $to_be_moved == 'lastweek':
               $where = $where_week;
               $timestamp_msg = strtotime("-1 week");
               break;
               case $to_be_moved == 'lastday':
               $where = $where_day;
               $timestamp_msg = strtotime("-1 day");
               break;
               default:
               $where = '';
               $timestamp_msg = '';
             }
             
	         $moving = $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->prefix}sform_submissions SET form = '%d', moved_from = '%d' WHERE form = '%d' $where", $moveto, $form_id, $form_id ) );	         
	         $update .= $moving ? 'done' : '';
		     $message = sprintf( __( 'Messages successfully moved to %s', 'simpleform' ), $form_to_name );
		                
           }

           else {
             $schedule = $wpdb->update($table_shortcodes, array('relocation' => '1', 'moveto' => $moveto, 'to_be_moved' => 'next', 'onetime_moving' => '0', 'deletion' => $deletion, 'override_settings' => $settings ), array('id' => $form_id ));
		     $update .= $schedule ? 'done' : '';
		     $message = sprintf( __( 'Moving to %s successfully scheduled', 'simpleform' ), $form_to_name );
           }
	
           if ( $update ) {
	         
	         if ( $to_be_moved != 'next' ) {
		       $count_all = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions WHERE form = '$form_id' $where_submissions");
               $count_last_day = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions WHERE form = '$form_id' $where_submissions $where_day");
               $count_last_week = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions WHERE form = '$form_id' $where_submissions $where_week");
               $count_last_month = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions WHERE form = '$form_id' $where_submissions $where_month");
               $count_last_year = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions WHERE form = '$form_id' $where_submissions $where_year");
               $option_all = $count_all != $count_last_year /* || ($year + $month + $week + $day) > 1 */ ? '<option value="all">'.__( 'All', 'simpleform' ).'</option>' : '';
               $option_year = $count_last_year > 0 && $count_last_year != $count_last_month ? '<option value="lastyear">'.__( 'Last year', 'simpleform' ).'</option>' : '';
               $option_month = $count_last_month > 0 && $count_last_month != $count_last_week ? '<option value="lastmonth">'.__( 'Last month', 'simpleform' ).'</option>' : '';
               $option_week = $count_last_week > 0 && $count_last_week != $count_last_day ? '<option value="lastweek">'.__( 'Last week', 'simpleform' ).'</option>' : '';
               $option_day = $count_last_day > 0 ? '<option value="lastday">'.__( 'Last day', 'simpleform' ).'</option>' : '';
               $select = '<option value="" selected="selected">'.__( 'Select messages', 'simpleform' ).'</option>'.$option_all.$option_year.$option_month.$option_week.$option_day.'<option value="next">'. __( 'Not received yet', 'simpleform' ).'</option>';
		       $count_updated_from = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions WHERE form = '$form_id' $where_submissions");    
		       $updated_moved_entries = ($submissions - $count_updated_from) + $moved_submissions;
		       // Check if messages had already been moved before for updating data
               $forms = $wpdb->get_results( "SELECT id, entries, moveto, moved_entries FROM {$wpdb->prefix}sform_shortcodes WHERE id != '$form_id'", 'ARRAY_A' );
               
               if ( $forms )  {
                 foreach($forms as $form) {
	               $id = esc_sql(intval($form['id'])); 
	               $entries = esc_sql($form['entries']); 
	               $moved = esc_sql($form['moved_entries']);
	               $form_to = esc_sql($form['moveto']);	 		       
	               $count_moved = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions WHERE moved_from = %d $where_submissions", $id ) );
	               $count_entries = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions WHERE form = %d $where_submissions", $id ) );
                   $update_to = $count_moved == '0' ? '0' : $form_to;
                   $update_moved = $count_moved == '0' ? '0' : $count_moved;
  	               $wpdb->update($table_shortcodes, array('entries' => $count_entries, 'moveto' => $update_to, 'moved_entries' => $update_moved ), array('id' => $id ) ); 
	             }
	           }
               $be_moved = $onetime == false ? 'next' : '';
               $onetime = $to_be_moved == 'next' || $onetime == false ? false : true;
               $moving_value = $to_be_moved == 'next' || $onetime == false ? '1' : '0';
               $wpdb->update($table_shortcodes, array('relocation' => $moving_value, 'moveto' => $moveto, 'to_be_moved' => $be_moved, 'onetime_moving' => $onetime, 'entries' => $count_updated_from, 'moved_entries' => $updated_moved_entries, 'deletion' => $deletion, 'override_settings' => $settings ), array('id' => $form_id ) );
               $last_message_from = get_option("sform_last_{$form_id}_message");
               $last_message_from_timestamp = $last_message_from != false ? explode('#',$last_message_from)[0] : '';
               $before_last_message_from = get_option("sform_before_last_{$form_id}_message");
               $before_last_message_from_timestamp = $before_last_message_from != false ? explode('#',$before_last_message_from)[0] : '';
               $last_message_to = get_option("sform_moved_last_{$moveto}_message");
               $last_message_to_timestamp = $last_message_to != false ? explode('#',$last_message_to)[0] : '';
               $before_last_message_to = get_option("sform_moved_before_last_{$moveto}_message");
               $before_last_message_to_timestamp = $before_last_message_to != false ? explode('#',$before_last_message_to)[0] : '';
               
               if ( $last_message_from_timestamp > $last_message_to_timestamp ) {
	             update_option("sform_moved_last_{$moveto}_message", $last_message_from);
               }
               
	           if ( ( $before_last_message_from_timestamp > $timestamp_msg ) && ( $before_last_message_from_timestamp > $before_last_message_to_timestamp ) && ( $before_last_message_from_timestamp > $last_message_to_timestamp ) ) {
                  update_option("sform_moved_before_last_{$moveto}_message", $before_last_message_from);            
               }
               else {
	             if ( $last_message_to_timestamp ) {
                   update_option("sform_moved_before_last_{$moveto}_message", $last_message_to);
                 }
               }

               $message = $onetime == false ? sprintf( __( 'Messages moved to %s and successfully scheduled', 'simpleform' ), $form_to_name ) : sprintf( __( 'Messages successfully moved to %s', 'simpleform' ), $form_to_name );

	           echo json_encode(array('error' => false, 'update' => true, 'moving' => true, 'onetime' => $onetime, 'messages' => $count_updated_from, 'moved' => $updated_moved_entries, 'select' => $select, 'message' => $message ));
	           exit;
		     }
			 
			 else {
               echo json_encode(array('error' => false, 'update' => true, 'moving' => false, 'onetime' => false, 'message' => $message ));
	           exit;
		     }
	         
           }
           
           else {
             
             if ( $to_be_moved != 'next' ) {
		       $entries = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions WHERE form = '$form_id' $where_submissions $where");
			   if ( $entries == '0' ) {   
	             echo json_encode(array('error' => false, 'update' => false, 'message' => __('Messages have already been moved', 'simpleform' ) ));
	             exit; 
	           }
	           else {
	             echo json_encode(array('error' => true, 'message' => __('Error occurred moving the messages. Try again!', 'simpleform' ) ));
	             exit;
	           }
	         }
	          
	         else {
		       $to_be_moved = esc_attr($form_data->to_be_moved);
		       $onetime_moving = esc_attr($form_data->onetime_moving);
			   if ( $to_be_moved == 'next' && $onetime_moving == '0' ) {   
	             echo json_encode(array('error' => false, 'update' => false, 'message' => __('Moving has already been scheduled', 'simpleform' ) ));
	             exit; 
	           }
	           else {
	             echo json_encode(array('error' => true, 'message' => __('Error occurred scheduling the moving. Try again!', 'simpleform' ) ));
	             exit;
	           }
	         }
		     
           }
	         
         }
         
 	     // Check if a restoration is running 
         elseif ( $restoration == true ) {
	         
		   $all_forms_to = $wpdb->get_col( "SELECT DISTINCT form FROM {$wpdb->prefix}sform_submissions WHERE moved_from = '$form_id'" );
	       $updated_messages = $wpdb->update($table_submissions, array('form' => $form_id, 'moved_from' => '0' ), array('moved_from' => $form_id ) );
	       // $last_restored_date = $wpdb->get_var("SELECT date FROM {$wpdb->prefix}sform_submissions WHERE form = '$form_id' AND moved_from = '0' ORDER BY date DESC LIMIT 1");
	       	
	       if ( $updated_messages ) {
		       
	         foreach( $all_forms_to as $restored_from ) {		        
	            $count_moved = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions WHERE form = %d $where_submissions", $restored_from ) );
	            $wpdb->update($table_shortcodes, array( 'entries' => $count_moved ), array('id' => $restored_from ) );
             }
		     $count_restored = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions WHERE form = '$form_id' $where_submissions");
             $count_all = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions WHERE form = '$form_id'");
             $count_last_day = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions WHERE form = '$form_id' $where_submissions $where_day");
             $count_last_week = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions WHERE form = '$form_id' $where_submissions $where_week");
             $count_last_month = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions WHERE form = '$form_id' $where_submissions $where_month");
             $count_last_year = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions WHERE form = '$form_id' $where_submissions $where_year");
             $option_all = $count_all != $count_last_year /* || ($year + $month + $week + $day) > 1 */ ? '<option value="all">'.__( 'All', 'simpleform' ).'</option>' : '';
             $option_year = $count_last_year > 0 && $count_last_year != $count_last_month ? '<option value="lastyear">'.__( 'Last year', 'simpleform' ).'</option>' : '';
             $option_month = $count_last_month > 0 && $count_last_month != $count_last_week ? '<option value="lastmonth">'.__( 'Last month', 'simpleform' ).'</option>' : '';
             $option_week = $count_last_week > 0 && $count_last_week != $count_last_day ? '<option value="lastweek">'.__( 'Last week', 'simpleform' ).'</option>' : '';
             $option_day = $count_last_day > 0 ? '<option value="lastday">'.__( 'Last day', 'simpleform' ).'</option>' : '';
             $selected = $relocation == true && $moveto != '0' && $to_be_moved == 'next' ? 'selected="selected"' : ''; 
             $select = '<option value="">'.__( 'Select messages', 'simpleform' ).'</option>'.$option_all.$option_year.$option_month.$option_week.$option_day.'<option value="next" '.$selected.'>'. __( 'Not received yet', 'simpleform' ).'</option>';
	         
	         // Check if a restoration with sheduling is running 
	         if ( $relocation == true && $moveto != '0' && $to_be_moved == 'next' ) {
		       $wpdb->update($table_shortcodes, array('relocation' => '1', 'moveto' => $moveto, 'to_be_moved' => 'next', 'entries' => $count_restored, 'moved_entries' => '0', 'onetime_moving' => '0', 'deletion' => $deletion, 'override_settings' => $settings ), array('id' => $form_id ));
		       $message = esc_attr($form_data->onetime_moving) == '0' ? __( 'Messages successfully restored', 'simpleform' ) : sprintf( __( 'Messages restored and moving to %s successfully scheduled', 'simpleform' ), $form_to_name );
	           echo json_encode(array('error' => false, 'update' => true, 'moving' => false, 'restore' => true, 'onetime' => false, 'messages' => $count_restored, 'moved' => '0', 'select' => $select, 'message' => $message ));
	           exit;
		     }
		     
		     else {
	           $wpdb->update($table_shortcodes, array( 'relocation' => $relocation, 'moveto' => '0', 'to_be_moved' => '', 'entries' => $count_restored, 'moved_entries' => '0', 'deletion' => $deletion ), array('id' => $form_id ) );
	           echo json_encode(array('error' => false, 'update' => true, 'moving' => false, 'restore' => true, 'messages' => $count_restored, 'moved' => '0', 'select' => $select, 'message' =>  __( 'Messages successfully restored', 'simpleform' ) ));
	           exit;
			 }
			 
           }
           
  	       else {
	         $check_moved = $wpdb->get_var( $wpdb->prepare( "SELECT moveto FROM {$wpdb->prefix}sform_shortcodes WHERE id = %d", $form_id) );
	         $to_be_moved = $wpdb->get_var( $wpdb->prepare( "SELECT to_be_moved FROM {$wpdb->prefix}sform_shortcodes WHERE id = %d", $form_id) );
	         $onetime_moving = $wpdb->get_var( $wpdb->prepare( "SELECT onetime_moving FROM {$wpdb->prefix}sform_shortcodes WHERE id = %d", $form_id) );
	         if ( $check_moved == '0' || ( $check_moved != '0' && isset($to_be_moved) && $to_be_moved == 'next' && $onetime_moving == '0' ) ) {
	            echo json_encode(array('error' => false, 'update' => false, 'message' => __('Messages have already been restored', 'simpleform' ) ));
	            exit; 
	         }
	         else { 
                echo json_encode(array('error' => true, 'message' => __('Error occurred restoring the messages. Try again!', 'simpleform' ) ));
	            exit;
	         }
           }
           
         }

	     // It is not running any moving or any restoration  
	     else {
		     
           $moveto = $relocation == false ? '0' : esc_attr($form_data->moveto);
           $update_form_data = $wpdb->update($table_shortcodes, array('relocation' => $relocation, 'moveto' => $moveto, /* 'to_be_moved' => '', 'onetime_moving' => '1', */ 'deletion' => $deletion ), array('id' => $form_id ));
       
           if ( $update_form_data ) {
             echo json_encode(array('error' => false, 'update' => true, 'message' => __( 'Settings were successfully saved', 'simpleform' ) ));
	         exit;
           }
           else {
             echo json_encode(array('error' => false, 'update' => false, 'message' => __( 'Settings have already been saved', 'simpleform' ) ));
	         exit;
           }
           
         }
       
       }
      
       die();
       
      }

    }
       
	/**
	 * Remove all unnecessary parameters leaving the original URL used before performing an action
	 *
	 * @since    
	 */
    
    public function url_cleanup() {
	    
      global $sform_forms;
      $screen = get_current_screen();      
      if(!is_object($screen) || $screen->id != $sform_forms)
      return;
      
      $sform_list_table = new SimpleForm_Forms_List();
      $doaction = $sform_list_table->current_action();
      
      if ( $doaction ) {

		  $referer_url = wp_get_referer();
		  if ( ! $referer_url ) {
		  $referer_url = admin_url( 'admin.php?page=sform-forms' );
	      }
	      
		  $view_arg = explode('&view=', $referer_url)[1];
		  $view = isset($view_arg) ? explode('&', $view_arg)[0] : 'all';
          $sform_list_table->prepare_items();
          if ( $view == 'all' ) { $filter_by_view = "status != 'trash'"; }
          if ( $view == 'published' ) { $filter_by_view = "status = 'published'"; }
          if ( $view == 'draft' ) { $filter_by_view = "status = 'draft'"; }
          if ( $view == 'trash' ) { $filter_by_view = "status = 'trash'"; }
          global $wpdb;
          $count = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_shortcodes WHERE $filter_by_view");
          $paged = isset( $_REQUEST['paged'] ) ? absint($_REQUEST['paged']) : '1';        
          $per_page = $sform_list_table->get_items_per_page('edit_form_per_page', 10);
          $total_pages = ceil( $count / $per_page );
          if ( $paged > $total_pages ) { $pagenum = $total_pages; } 
		  else { $pagenum = $paged; }

          $url = remove_query_arg( array('view', 'paged', 'action', 'action2', 'id', '_wpnonce', '_wp_http_referer'), $referer_url );

          if ( $count > 0 ) { 
            $url = add_query_arg( 'view', $view, $url );
          } 

          if ( $pagenum > 1 ) { 
            $url = add_query_arg( 'paged', $pagenum, $url );
          } 

          wp_redirect($url);
          exit(); 
      
      }
      
    }
           
}