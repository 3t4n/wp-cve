<?php

function campaignmonitor_require_library() {
	$base_class = 'CS_REST_Wrapper_Base';

	$classes = array(
		'CS_REST_Administrators' => 'csrest_administrators',
		'CS_REST_Campaigns'      => 'csrest_campaigns',
		'CS_REST_Clients'        => 'csrest_clients',
		'CS_REST_General'        => 'csrest_general',
		'CS_REST_Lists'          => 'csrest_lists',
		'CS_REST_People'         => 'csrest_people',
		'CS_REST_Segments'       => 'csrest_segments',
		'CS_REST_Subscribers'    => 'csrest_subscribers',
		'CS_REST_Templates'      => 'csrest_templates'
	);

	if ( class_exists( $base_class ) ) {
		$base_class = new ReflectionClass( $base_class );
		$base_dir   = realpath( dirname( $base_class->getFileName() ) . '/..' );

		foreach ( array( 'CS_REST_General', 'CS_REST_Clients', 'CS_REST_Subscribers' ) as $class ) {
			if ( ! class_exists( $class ) ) {
				require_once $base_dir . '/' . $classes[ $class ] . '.php';
			}
		}
	} else {
		foreach ( $classes as $class => $file ) {
			if ( ! class_exists( $class ) ) {
				require_once FCA_EOI_PLUGIN_DIR . 'providers/campaignmonitor/campaignmonitor/' . $file . '.php';
			}
		}
	}
}

function campaignmonitor_add_user( $settings, $user_data, $list_id ) {
	
	$form_meta = get_post_meta ( $user_data['form_id'], 'fca_eoi', true );
	$api_key = $form_meta['campaignmonitor_api_key'];
	
	
	if ( empty ( $api_key ) ) {
		return 'Missing API key';
	}
	campaignmonitor_require_library();

	// Subscribe user
	$auth = array( 'api_key' => $api_key );
	$wrap = new CS_REST_Subscribers( $list_id, $auth );
	$result = $wrap->add( array(
		'EmailAddress' => K::get_var( 'email', $user_data ),
		'Name' => K::get_var( 'name', $user_data ),
		'Resubscribe' => true,
	) );

	return $result->was_successful() ? true : $result->http_status_code;
}

function campaignmonitor_get_lists( $api_key, $client_id ) {
	
	$lists_formatted = array( '' => 'Not set' );
	
	// Make call and add lists if any
	if ( $api_key && $client_id ) {

		campaignmonitor_require_library();

		$auth = array( 'api_key' => $api_key );
		$wrap = new CS_REST_Clients( $client_id, $auth );
		$results = json_decode( json_encode( $wrap->get_lists() ), true );
		
		if ( isset( $results[ 'response' ] ) && $results[ 'http_status_code' ] == 200 ) {
			foreach ( $results[ 'response' ] as $result ) {
				$lists_formatted[ $result['ListID'] ] = $result['Name'];
			}
		}
	}

	return $lists_formatted;
}

function campaignmonitor_ajax_get_lists() {

	// Validate the API key
	$api_key = K::get_var( 'campaignmonitor_api_key', $_POST );
	$client_id = K::get_var( 'campaignmonitor_client_id', $_POST );
	$lists_formatted = array( '' => 'Not set!' );

	// Make call and add lists if any
	if ( $api_key && $client_id ) {

		campaignmonitor_require_library();

		$auth = array( 'api_key' => $api_key );
		$wrap = new CS_REST_Clients( $client_id, $auth );
		$results = json_decode( json_encode( $wrap->get_lists() ), true );
				
		if ( isset( $results[ 'response' ] ) && $results[ 'http_status_code' ] == 200 ) {
			foreach ( $results[ 'response' ] as $result ) {
				$lists_formatted[ $result['ListID'] ] = $result['Name'];
			}
		}
	}

	echo json_encode( $lists_formatted );
	exit;
}

function campaignmonitor_admin_notices( $errors ) {

	/* Provider errors can be added here */

	return $errors;
}

function campaignmonitor_string( $def_str ) {

	$strings = array(
		'Form integration' => __( 'Campaign Monitor Integration' ),
	);

	return K::get_var( $def_str, $strings, $def_str );
}

function campaignmonitor_integration( $settings ) {

	global $post;
	$fca_eoi = get_post_meta( $post->ID, 'fca_eoi', true );

	// Remember old Campaign Monitor settings if we are in a new form
	$last_form_meta = get_option( 'fca_eoi_last_form_meta', '' );
	
	$suggested_api = empty($last_form_meta['campaignmonitor_api_key']) ? '' : $last_form_meta['campaignmonitor_api_key'];
	$suggested_id = empty($last_form_meta['campaignmonitor_client_id']) ? '' : $last_form_meta['campaignmonitor_client_id'];
	$suggested_list = empty($last_form_meta['campaignmonitor_list_id']) ? '' : $last_form_meta['campaignmonitor_list_id'];
	
	$list = empty( $fca_eoi['campaignmonitor_list_id'] ) ? $suggested_list : $fca_eoi['campaignmonitor_list_id'];
	$api = K::get_var( 'campaignmonitor_api_key', $fca_eoi, $suggested_api );
	$client_id = K::get_var( 'campaignmonitor_client_id', $fca_eoi, $suggested_id );

	$lists_formatted = campaignmonitor_get_lists( $api, $client_id );

	K::fieldset( campaignmonitor_string( 'Form integration' ) ,
		array(
			array( 'input', 'fca_eoi[campaignmonitor_api_key]',
				array(
					'class' => 'regular-text',
					'value' => $api,
				),
				array( 'format' => '<p><label>API Key<br />:input</label><br /><em>Where can I find <a tabindex="-1" href="http://help.campaignmonitor.com/topic.aspx?t=206" target="_blank">my Campaign Monitor Api Key</a>?</em></p>' )
			),
			array( 'input', 'fca_eoi[campaignmonitor_client_id]',
				array(
					'class' => 'regular-text',
					'value' => $client_id,
				),
				array( 'format' => '<p><label>Client ID<br />:input</label><br /><em>Where can I find <a tabindex="-1" href="http://www.campaignmonitor.com/api/getting-started/#clientid" target="_blank">my Campaign Monitor Client ID</a>?</em></p>' )
			),
			array( 'select', 'fca_eoi[campaignmonitor_list_id]',
				array(
					'class' => 'select2',
					'style' => 'width: 27em;',
				),
				array(
					'format' => '<p id="campaignmonitor_list_id_wrapper"><label>List to subscribe to<br />:select</label></p>',
					'options' => $lists_formatted,
					'selected' => $list,
				),
			),
		),
		array(
			'id' => 'fca_eoi_fieldset_form_campaignmonitor_integration',
		)
	);
}
