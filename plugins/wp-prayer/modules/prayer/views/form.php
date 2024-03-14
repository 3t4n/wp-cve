<?php
/**
* Template for Add & Edit Prayer
* @author  Flipper Code <hello@flippercode.com>
* @package Maps
*/
if ( isset( $_REQUEST['_wpnonce'] ) ) {
	$nonce = sanitize_text_field( $_REQUEST['_wpnonce']  );
	if ( ! wp_verify_nonce( $nonce, 'wpgmp-nonce' ) ) {
		die( 'Cheating...' );
	} else {
		$data =  sanitize_text_field($_POST);
	}
}
global $wpdb;
$modelFactory = new FactoryModelWPE();
$prayer_obj = $modelFactory->create_object( 'prayer' );
if ( isset( $_GET['doaction'] ) and 'edit' == $_GET['doaction'] and isset( $_GET['prayer_id'] ) ) {
	$prayer_obj = $prayer_obj->fetch( array( array( 'prayer_id', '=', intval( sanitize_text_field( $_GET['prayer_id'] ) ) ) ) );
	$data = (array) $prayer_obj[0];

} elseif ( ! isset( $_GET['doaction'] ) and isset( $response['success'] ) ) {
	// Reset $_POST object for antoher entry.
	unset( $data );
}
$form  = new FlipperCode_WPE_HTML_Markup();
$form->set_header( __( 'Prayer Information', WPE_TEXT_DOMAIN ), $response, __( 'Manage Prayers', WPE_TEXT_DOMAIN ), 'wpe_manage_prayer' );
$options = array(
'prayer_request' => __( 'Prayer Request',WPE_TEXT_DOMAIN ),
'praise_report' => __( 'Praise Report',WPE_TEXT_DOMAIN ),
);

$form->add_element( 'radio', 'request_type', array(
'label' => __( 'Request Type', WPE_TEXT_DOMAIN ),
'radio-val-label' => $options,
'current' => (isset( $data['request_type'] ) and ! empty( $data['request_type'] )) ? $data['request_type'] : '',
'class' => 'chkbox_class',
'default_value' => 'prayer_request',
'required' => true,
));
$form->add_element( 'text', 'prayer_title', array(
'label' => __( 'IP address', WPE_TEXT_DOMAIN ),
'value' => (isset( $data['prayer_title'] ) and ! empty( $data['prayer_title'] )) ? $data['prayer_title'] : '',
'desc' => __( 'IP address', WPE_TEXT_DOMAIN ),
'required' => true,
'placeholder' => __( 'IP address', WPE_TEXT_DOMAIN ),
'id' => 'prayer_title_row',
));
$form->add_element( 'textarea', 'prayer_messages', array(
'label' => __( 'Prayer Request', WPE_TEXT_DOMAIN ),
'value' => (isset( $data['prayer_messages'] ) and ! empty( $data['prayer_messages'] )) ? stripslashes($data['prayer_messages'])   : '',
'desc' => __( 'Prayer Request', WPE_TEXT_DOMAIN ),
'textarea_rows' => 10,
'required' => true,
'textarea_name' => 'prayer_messages',
'class' => 'form-control',
));
$settings = unserialize(get_option('_wpe_prayer_engine_settings'));
if (isset($settings['wpe_autoemail'])&& $settings['wpe_autoemail'] == 'true') {
$data['prayer_notify']='unchecked';if(isset($data['prayer_lastname']) && ! empty($data['prayer_lastname'])) {$data['prayer_notify'] ='';}
$form->add_element( 'checkbox', 'prayer_notify', array(
'desc' => __( 'Notify', WPE_TEXT_DOMAIN ),    
'value' => (isset( $data['prayer_notify'] ) and ! empty( $data['prayer_notify'] )) ? ($data['prayer_notify'])   : '',
'class' => 'chkbox_class',
));
}
$form->add_element( 'submit', 'save_entity_data', array(
'value' => __( 'Save',WPE_TEXT_DOMAIN ),
));
$form->add_element( 'hidden', 'operation', array(
'value' => 'save',
));
if ( isset( $_GET['doaction'] ) and 'edit' == $_GET['doaction'] ) {
	$form->add_element( 'hidden', 'entityID', array(
	'value' => intval( sanitize_text_field( $_GET['prayer_id'] ) ),
	));
}
$form->render();
