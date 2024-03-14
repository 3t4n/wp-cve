<?php

function getresponse_object( $api_key ) {
	
	if( ! class_exists( 'GetResponseApiOptinCat' ) ) {
		require_once FCA_EOI_PLUGIN_DIR . "providers/getresponse/GetResponseAPI.class.php";
	}

	if ( !empty( $api_key ) ) {
		return new GetResponseApiOptinCat( $api_key );
	} else {
		return false;
	}
}


function getresponse_get_lists( $api_key ) {
	
	$lists_formatted = array();
		
	if( empty( $api_key ) ) {
		return $lists_formatted;
	}
	
	$args = array(
		'method' => 'GET',
		'timeout'     => 15,
		'redirection' => 15,
		'headers' => array ( "Content-Type" => "application/json", "X-Auth-Token" => "api-key $api_key" ),
	);
		
	$url = "https://api.getresponse.com/v3/campaigns";
	
	$response = wp_remote_request( $url, $args );

	if( is_wp_error( $response ) ) {
		return $lists_formatted;
	}
	
	if ( !empty ( $response['body'] ) ) {
		$campaigns = json_decode( $response['body'], true);
		
		if ( empty ( $campaigns['message'] ) ) {
			
			foreach ( $campaigns as $list ) {
				$lists_formatted[ $list['campaignId'] ] = $list['name'];
			}
		}
	}
	
	return $lists_formatted;
	
}

function getresponse_ajax_get_lists() {

	$api_key = empty ( $_POST['getresponse_api_key'] ) ? '' : esc_textarea( $_POST['getresponse_api_key'] );

	$lists_formatted = array( '' => 'Not set' );
		
	$args = array(
		'method' => 'GET',
		'timeout'     => 15,
		'redirection' => 15,
		'headers' => array ( 
			"Content-Type" => "application/json",
			"X-Auth-Token" => "api-key $api_key" 
		),
	);
		
	$url = "https://api.getresponse.com/v3/campaigns";
	
	$response = wp_remote_request( $url, $args );


	if ( !empty ( $response['body'] ) ) {
		$campaigns = json_decode( $response['body'], true );
		
		if ( empty ( $campaigns['message'] ) ) {
			
			foreach ( $campaigns as $id => $list ) {
				$lists_formatted[ $list['campaignId'] ] = $list['name'];
			}
		}
	}
	echo json_encode( $lists_formatted );
	exit;
		
}

function getresponse_add_user( $settings, $user_data, $list_id ) {

	$name = K::get_var( 'name', $user_data );
	$email = K::get_var( 'email', $user_data );
	$form_meta = get_post_meta ( $user_data['form_id'], 'fca_eoi', true );
	$api_key = $form_meta['getresponse_api_key'];
	$list_id = $form_meta['getresponse_list_id'];
	
	$body = array (
		'email' => $email,
		'dayOfCycle' => 0,
		'campaign' => array(
			'campaignId' => $list_id,
		),
	);
	if ( !empty( $name ) ) {
		$body['name'] = $name;
	}
		
	$args = array(
		'method' => 'POST',
		'timeout'     => 15,
		'redirection' => 15,
		'headers' => array ( "Content-Type" => "application/json", "X-Auth-Token" => "api-key $api_key" ),
		'body' => json_encode ( $body ),
	);
	
	$url = "https://api.getresponse.com/v3/contacts";
	
	$response = wp_remote_request( $url, $args);


	if( !is_wp_error( $response ) ) {
		if ( $response['response']['code'] == 202 ) { //200 is too mainstream
			return true;
		}
	}
	
	if ( isSet( $response['response']['code'] ) ) {
		return $response['response']['code'];
	}
	
	return false;
	
}

function getresponse_admin_notices( $errors ) {

	/* Provider errors can be added here */

	return $errors;
}

function getresponse_string( $def_str ) {

	$strings = array(
		'Form integration' => __( 'GetResponse Integration' ),
	);

	return K::get_var( $def_str, $strings, $def_str );
}

function getresponse_integration( $settings ) {

	global $post;
	$fca_eoi = get_post_meta( $post->ID, 'fca_eoi', true );

	// Remember old Getresponse settings if we are in a new form
	$last_form_meta = get_option( 'fca_eoi_last_form_meta', '' );
	$suggested_api = empty($last_form_meta['getresponse_api_key']) ? '' : $last_form_meta['getresponse_api_key'];
	$suggested_list = empty($last_form_meta['getresponse_list_id']) ? '' : $last_form_meta['getresponse_list_id'];

	$list = K::get_var( 'getresponse_list_id', $fca_eoi, $suggested_list );
	$api_key = K::get_var( 'getresponse_api_key', $fca_eoi, $suggested_api );
	
	$lists_formatted = getresponse_get_lists( $api_key );

	K::fieldset( getresponse_string( 'Form integration' ) ,
		array(
			array( 'input', 'fca_eoi[getresponse_api_key]',
				array( 
					'class' => 'regular-text',
					'value' => $api_key,
				),
				array( 'format' => '<p><label>API Key<br />:input</label><br /><em><a tabindex="-1" href="https://app.getresponse.com/manage_api.html" target="_blank">[Get my GetResponse API Key]</a></em></p>' ),
			),
			array( 'select', 'fca_eoi[getresponse_list_id]',
				array(
					'class' => 'select2',
					'style' => 'width: 27em;',
				),
				array(
					'format' => '<p id="getresponse_list_id_wrapper"><label>List to subscribe to<br />:select</label></p>',
					'options' => $lists_formatted,
					'selected' => $list,
				),
			),
		),
		array(
			'id' => 'fca_eoi_fieldset_form_getresponse_integration',
		)
	);
}
