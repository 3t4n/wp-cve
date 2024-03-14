<?php

/**
 * This class used to manage settings page in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Forms
 */

if ( isset( $_REQUEST['_wpnonce'] ) ) {
	$nonce = sanitize_text_field(  $_REQUEST['_wpnonce']  );
	if ( ! wp_verify_nonce( $nonce, 'wpgmp-nonce' ) ) {
		die( 'Cheating...' );
	} else {
		$data = $_POST;
	}
}

$link=$_SERVER["SERVER_NAME"];$link1= '<a href="https://www.'.$link.'">Visit '.$link.'</a>';
// Setting up defaults for email
$default = array(
		'wpe_email_req_subject' => 'Prayer request confirmation',
		'wpe_email_praise_subject' => 'Praise report confirmation',
		'wpe_email_admin_subject' => 'New {request_type} received',
        'wpe_email_prayed_subject' => 'Someone prayed for you',
	);

$default['wpe_email_req_messages'] = '
Hello {prayer_author_name},<br />

<p>Thank you for submitting your prayer request. We welcome all requests and we delight in lifting you and your requests up to God in prayer. God Bless you, and remember, God knows the prayers that are coming and hears them even before they are spoken.</p>
<p>Request: {prayer_messages}</p>

Blessings, <br />
Prayer Team <br />
'.$link1;

$default['wpe_email_praise_messages'] = '
Hello {prayer_author_name},<br />

<p>Thank you for submitting your praise report. We welcome all requests and we delight in lifting you and your requests up to God in prayer. God Bless you, and remember, God knows the prayers that are coming and hears them even before they are spoken.</p>
<p>Praise: {prayer_messages}</p>

Blessings,<br />
Prayer Team <br />
'.$link1;

$default['wpe_email_admin_messages'] = '
Hello,<br />

<p>You have received a new {request_type} to moderate with following details :</p>
<p>Name : {prayer_author_name}</p>
<p>Email: {prayer_author_email}</p>
<p>Request: {prayer_messages}</p>

Blessings, <br />
Prayer Team <br />
'.$link1;

$default['wpe_email_prayed_messages'] = '
Hello {prayer_author_name},<br />

<p>Someone prayed for you.</p>
<p>Request: {prayer_messages}</p>

Blessings, <br />
Prayer Team <br />
'.$link1;
	
$data = unserialize(get_option( '_wpe_prayer_engine_email_settings' ));

//print_r($data);
//die();


$form  = new FlipperCode_WPE_HTML_Markup();

$form->set_header( __( 'Email Settings', WPE_TEXT_DOMAIN ), $response );

$form->add_element( 'div', 'sender_email_heading', array(
			'class'	=> 'col-md-12 no-padding',
			'value' => '<h4>'.__('Sender Email',WPE_TEXT_DOMAIN). '</h4><hr />',
	));


$wp_admin_email = get_option('admin_email');

$form->add_element( 'text','prayer_req_admin_email', array(
			'label'	=> __('Admin Email', WPE_TEXT_DOMAIN),
			'value' => (isset($data['prayer_req_admin_email']) and !empty($data['prayer_req_admin_email'])) ? $data['prayer_req_admin_email'] : $wp_admin_email,
			'required' => true,
			'placeholder' => __('emailaddress@domain.com', WPE_TEXT_DOMAIN),
			'id' 	=> 'prayer_req_admin_email',
			'class'	=> 'form-control',
	));

$form->add_element( 'text','wpe_email_cc', array(
			'label'	=> __('Add CC admin email address, separate each with a comma', WPE_TEXT_DOMAIN),
			'value' => (isset($data['wpe_email_cc']) and !empty($data['wpe_email_cc'])) ? $data['wpe_email_cc'] : "",
			'required' => false,
			'id' 	=> 'wpe_email_cc',
			'class'	=> 'form-control',
	));

$wp_admin_from = get_option('blogname');

$form->add_element( 'text','wpe_email_from', array(
			'label'	=> __('From Name', WPE_TEXT_DOMAIN),
			'value' => (isset($data['wpe_email_from']) and !empty($data['wpe_email_from'])) ? $data['wpe_email_from'] : $wp_admin_from,
			'required' => true,
			'placeholder' => __('Pray Team', WPE_TEXT_DOMAIN),
			'id' 	=> 'wpe_email_from',
			'class'	=> 'form-control',
	));

$illegal_chars_username = array('(', ')', '<', '>', ',', ';', ':', '\\', '"', '[', ']', '@', "'", ' ');
$domain = preg_replace('#^www.#', '', strtolower($_SERVER['SERVER_NAME']));
$username = str_replace ($illegal_chars_username, "", $wp_admin_from);

$form->add_element( 'text','wpe_email_user', array(
			'label'	=> __('From email address', WPE_TEXT_DOMAIN),
			'value' => (isset($data['wpe_email_user']) and !empty($data['wpe_email_user'])) ? $data['wpe_email_user'] : $username.'@'.$domain,
			'required' => true,
			'placeholder' => __('emailaddress@domain.com', WPE_TEXT_DOMAIN),
			'id' 	=> 'wpe_email_user',
			'class'	=> 'form-control',
	));

/***
 *  Prayer Request settings 
 */
$form->add_element( 'div', 'prayer_req_heading', array(
			'class'	=> 'col-md-12 no-padding',
			'value' => '<h4>'.__('Prayer Request',WPE_TEXT_DOMAIN). '</h4><hr />',
	));



$form->add_element( 'text', 'wpe_email_req_subject', array(
	'label' => __( 'email subject', WPE_TEXT_DOMAIN ),
	'value' => (isset( $data['wpe_email_req_subject'] ) and ! empty( $data['wpe_email_req_subject'] )) ? $data['wpe_email_req_subject'] : $default['wpe_email_req_subject'],
	'required' => false,
	'placeholder' => __( 'email subject', WPE_TEXT_DOMAIN ),
	'id' => 'wpe_email_req_subject',
	'class' => 'form-control ',
));

$form->add_element( 'textarea', 'wpe_email_req_messages', array(
	'label' => __( 'Message', WPE_TEXT_DOMAIN ),
	'value' => (isset( $data['wpe_email_req_messages'] ) and ! empty( $data['wpe_email_req_messages'] )) ?  $data['wpe_email_req_messages']  : $default['wpe_email_req_messages'],

	'textarea_rows' => 15,
	'required' => false,
	'textarea_name' => 'wpe_email_req_messages',
	'class' => 'form-control email-msg editor',
));

/***
 *  Praise Report settings 
 */
$form->add_element( 'div', 'praise_report_heading', array(
			'class'	=> 'col-md-12 no-padding',
			'value' => '<h4>'.__('Praise Report',WPE_TEXT_DOMAIN). '</h4><hr />',
	));

$form->add_element( 'text', 'wpe_email_praise_subject', array(
	'label' => __( 'email subject', WPE_TEXT_DOMAIN ),
	'value' => (isset( $data['wpe_email_praise_subject'] ) and ! empty( $data['wpe_email_praise_subject'] )) ? $data['wpe_email_praise_subject'] : $default['wpe_email_praise_subject'],
	'required' => false,
	'placeholder' => __( 'email subject', WPE_TEXT_DOMAIN ),
	'id' => 'wpe_email_praise_subject',
));
$form->add_element( 'textarea', 'wpe_email_praise_messages', array(
	'label' => __( 'Message', WPE_TEXT_DOMAIN ),
	'value' => (isset( $data['wpe_email_praise_messages'] ) and ! empty( $data['wpe_email_praise_messages'] )) ?  $data['wpe_email_praise_messages']  : $default['wpe_email_praise_messages'],

	'textarea_rows' => 15,
	'required' => false,
	'textarea_name' => 'wpe_email_praise_messages',
	'class' => 'form-control email-msg editor',
));

/***
 *  Admin Email Settings 
 */
$form->add_element( 'div', 'admin_email_heading', array(
			'class'	=> 'col-md-12 no-padding',
			'value' => '<h4>'.__('Admin Email Message',WPE_TEXT_DOMAIN). '</h4><hr />',
	));

$form->add_element( 'text', 'wpe_email_admin_subject', array(
	'label' => __( 'email subject', WPE_TEXT_DOMAIN ),
	'value' => (isset( $data['wpe_email_admin_subject'] ) and ! empty( $data['wpe_email_admin_subject'] )) ? $data['wpe_email_admin_subject'] : $default['wpe_email_admin_subject'],
	'required' => false,
	'placeholder' => 'New {request_type} received',
	'id' => 'wpe_email_admin_subject',
));

$form->add_element( 'textarea', 'wpe_email_admin_messages', array(
	'label' => __( 'Message', WPE_TEXT_DOMAIN ),
	'value' => (isset( $data['wpe_email_admin_messages'] ) and ! empty( $data['wpe_email_admin_messages'] )) ?  $data['wpe_email_admin_messages']  : $default['wpe_email_admin_messages'],

	'textarea_rows' => 15,
	'required' => false,
	'textarea_name' => 'wpe_email_admin_messages',
	'class' => 'form-control email-msg editor',
));

/***
 *  Someone prayed for you 
 */
$form->add_element( 'div', 'prayer_prayed_heading', array(
			'class'	=> 'col-md-12 no-padding',
			'value' => '<h4>'.__('Someone prayed for you message',WPE_TEXT_DOMAIN). '</h4><hr />',
	));

$form->add_element( 'text', 'wpe_email_prayed_subject', array(
	'label' => __( 'email subject', WPE_TEXT_DOMAIN ),
	'value' => (isset( $data['wpe_email_prayed_subject'] ) and ! empty( $data['wpe_email_prayed_subject'] )) ? $data['wpe_email_prayed_subject'] : $default['wpe_email_prayed_subject'],
	'required' => false,
	'placeholder' => __( 'Someone prayed for you', WPE_TEXT_DOMAIN ),
	'id' => 'wpe_email_prayed_subject',
	'class' => 'form-control ',
));

$form->add_element( 'textarea', 'wpe_email_prayed_messages', array(
	'label' => __( 'Message', WPE_TEXT_DOMAIN ),
	'value' => (isset( $data['wpe_email_prayed_messages'] ) and ! empty( $data['wpe_email_prayed_messages'] )) ?  $data['wpe_email_prayed_messages']  : $default['wpe_email_prayed_messages'],

	'textarea_rows' => 15,
	'required' => false,
	'textarea_name' => 'wpe_email_prayed_messages',
	'class' => 'form-control email-msg editor',
));

$form->add_element( 'submit', 'save_entity_data', array(
	'value' => __( 'Save Changes',WPE_TEXT_DOMAIN ),
	'class' => 'btn btn-success',
));

$form->add_element( 'hidden', 'operation', array(
	'value' => 'save',
));

$form->render();