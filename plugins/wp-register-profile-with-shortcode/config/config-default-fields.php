<?php

// plugin default option data 

$wprp_default_options_data = array(

// default fields 
'thank_you_page_after_registration_url' => array( 'sanitization' => 'sanitize_text_field' ),
'username_in_registration' => array( 'sanitization' => 'sanitize_text_field' ),
'password_in_registration' => array( 'sanitization' => 'sanitize_text_field' ),
'firstname_in_registration' => array( 'sanitization' => 'sanitize_text_field' ),
'firstname_in_profile' => array( 'sanitization' => 'sanitize_text_field' ),
'is_firstname_required' => array( 'sanitization' => 'sanitize_text_field' ),
'lastname_in_registration' => array( 'sanitization' => 'sanitize_text_field' ),
'lastname_in_profile' => array( 'sanitization' => 'sanitize_text_field' ),
'is_lastname_required' => array( 'sanitization' => 'sanitize_text_field' ),
'displayname_in_registration' => array( 'sanitization' => 'sanitize_text_field' ),
'displayname_in_profile' => array( 'sanitization' => 'sanitize_text_field' ),
'is_displayname_required' => array( 'sanitization' => 'sanitize_text_field' ),
'userdescription_in_registration' => array( 'sanitization' => 'sanitize_text_field' ),
'userdescription_in_profile' => array( 'sanitization' => 'sanitize_text_field' ),
'is_userdescription_required' => array( 'sanitization' => 'sanitize_text_field' ),
'userurl_in_registration' => array( 'sanitization' => 'sanitize_text_field' ),
'userurl_in_profile' => array( 'sanitization' => 'sanitize_text_field' ),
'is_userurl_required' => array( 'sanitization' => 'sanitize_text_field' ),

// other 
'captcha_in_registration' => array( 'sanitization' => 'sanitize_text_field' ),
'captcha_in_wordpress_default_registration' => array( 'sanitization' => 'sanitize_text_field' ),
'force_login_after_registration' => array( 'sanitization' => 'sanitize_text_field' ),
'default_registration_form_hooks' => array( 'sanitization' => 'sanitize_text_field' ),
'enable_cfws_newsletter_subscription' => array( 'sanitization' => 'sanitize_text_field' ),

// Email
'wprw_admin_email' => array( 'sanitization' => 'sanitize_text_field' ),
'wprw_from_email' => array( 'sanitization' => 'sanitize_text_field' ),
'new_user_register_mail_subject' => array( 'sanitization' => 'sanitize_text_field' ),
'new_user_register_mail_body' => array( 'sanitization' => 'esc_html' ),

// Success Message
'wprw_success_msg' => array( 'sanitization' => 'esc_html' ),
);