<?php

/**
 * Helper class for connecting to third party services.
 *
 * @since 0.1.3
 */
final class SJEaServices {
	
	const SUCCESS = 'success';
	const ERROR = 'error';
	const FIELD_REQUIRED = 'field_required';
	const INVALID_FORM = 'invalid_form';
	const SERVER_ERROR = 'server_error';

	/**
	 * Data for working with each supported third party service.
	 *
	 * @since 0.1.3
	 * @access private
	 * @var array $services_data
	 */  
	static private $services_data = array(
		'mailchimp'         => array(
			'type'              => 'autoresponder',
			'name'              => 'MailChimp',
			'class'             => 'SJEaServiceMailChimp'
		)
	);

	/**
	 * Get an array of services data of a certain type such as "autoresponder".
	 * If no type is specified, all services will be returned.
	 *
	 * @since 0.1.3
	 * @param string $type The type of service data to return.
	 * @return array An array of services and related data.
	 */  
	static public function get_services_data( $type = null ) 
	{
		$services = array();
		
		// Return all services.
		if ( ! $type ) {
			$services = self::$services_data;
		}
		// Return services of a specific type.
		else {
			
			foreach ( self::$services_data as $key => $service ) {
				if ( $service['type'] == $type ) {
					$services[ $key ] = $service;
				}
			}
		}
		
		return $services;
	}

	/**
	 * Get an instance of a service helper class. 
	 *
	 * @since 0.1.3
	 * @param string $type The type of service.
	 * @return object
	 */  
	static public function get_service_instance( $service ) 
	{
		$services = self::get_services_data();
		$data     = $services[ $service ];
		
		// Make sure the base class is loaded.
		if ( ! class_exists( 'SJEaService' ) ) {
			require_once SJ_EA_DIR . 'classes/class-sj-ea-service.php';
		}
		
		// Make sure the service class is loaded.
		if ( ! class_exists( $data['class'] ) ) {
			require_once SJ_EA_DIR . 'classes/class-sj-ea-service-' . $service . '.php';
		}
		
		return new $data['class']();
	}
	
	/**
	 * Save the API connection of a service and retrieve account settings markup.
	 *
	 * Called via the connect_service frontend AJAX action.
	 *
	 * @since 0.1.3
	 * @return array The response array.
	 */  
	static public function connect_service() 
	{
		$saved_services = SJEaModelHelper::get_services();
		$post_data 		= $_POST;
		$response       = array( 
			'error'         => false, 
			'html'          => '' 
		);
		
		// Validate the service data.
		if ( ! isset( $post_data['service'] ) || empty( $post_data['service'] ) ) {
			$response['error'] = _x( 'Error: Missing service type.', 'Third party service such as MailChimp.', 'sjea' );
		}
		else if ( ! isset( $post_data['fields'] ) || 0 === count( $post_data['fields'] ) ) {
			$response['error'] = _x( 'Error: Missing service data.', 'Connection data such as an API key.', 'sjea' );
		}
		else if ( ! isset( $post_data['fields']['service_account'] ) || empty( $post_data['fields']['service_account'] ) ) {
			$response['error'] = _x( 'Error: Missing account name.', 'Account name for a third party service such as MailChimp.', 'sjea' );
		}
		
		// Get the service data.
		$service         = $post_data['service'];
		$service_account = $post_data['fields']['service_account'];
		
		// Does this account already exist? 
		if ( isset( $saved_services[ $service ][ $service_account ] ) ) {
			$response['error'] = _x( 'Error: An account with that name already exists.', 'Account name for a third party service such as MailChimp.', 'sjea' );
		}
		
		// Try to connect to the service.
		if ( ! $response['error'] ) {
			
			$instance   = self::get_service_instance( $service );
			$connection = $instance->connect( $post_data['fields'] ); 
			
			if ( $connection['error'] ) {
				$response['error'] = $connection['error'];   
			}
			else {
				
				SJEaModelHelper::update_services( 
					$service, 
					$service_account,
					$connection['data'] 
				);
				
				$response['html'] = self::render_account_settings( $service, $service_account );
			}
		}
		
		// Return the response.
		return $response;
	}
	
	/**
	 * Render the connection settings or account settings for a service.
	 *
	 * Called via the render_service_settings frontend AJAX action.
	 *
	 * @since 0.1.3
	 * @return array The response array.
	 */
	static public function render_settings() 
	{
		$saved_services     = SJEaModelHelper::get_services();
		$service            = $_POST['service'];
		$response           = array( 
			'error'             => false, 
			'html'              => '' 
		);
		
		// Render the settings to connect a new account. 
		if ( isset( $_POST['add_new'] ) || ! isset( $saved_services[ $service ] ) ) {
			$response['html'] = self::render_connect_settings( $service );
		}
		// Render the settings to select a connected account.
		else {
			$account = '';
			$response['html'] = self::render_account_settings( $service, $account );
		}

		// Return the response.
		return $response;
	}
	
	/**
	 * Render the settings to connect to a new account.
	 *
	 * @since 0.1.3
	 * @return string The settings markup.
	 */
	static public function render_connect_settings( $service ) 
	{   
		ob_start();
		
		SJEaModelHelper::render_settings_field( 'service_account', array(
			'row_class'     => 'sjea-service-connect-row',
			'class'         => 'sjea-service-connect-input',
			'type'          => 'text',
			'label'         => __( 'Account Name', 'sjea' ),
			'help'          => __( 'Used to identify this connection within the accounts list and can be anything you like.', 'sjea' ),
		)); 
		
		$instance = self::get_service_instance( $service );
		echo $instance->render_connect_settings();
		
		SJEaModelHelper::render_settings_field( 'service_connect_button', array(
			'row_class'     => 'sjea-service-connect-row',
			'class'         => 'sjea-service-connect-button',
			'type'          => 'button',
			'label'         => __( 'Connect', 'sjea' )
		)); 
		
		return ob_get_clean();
	}
	
	/**
	 * Render the account settings for a saved connection.
	 *
	 * @since 0.1.3
	 * @param string $service The service id such as "mailchimp".
	 * @param string $active The name of the active account, if any.
	 * @return string The account settings markup.
	 */ 
	static public function render_account_settings( $service, $active = '' ) 
	{
		ob_start();
		
		$saved_services             = SJEaModelHelper::get_services();
		$settings                   = new stdClass();
		$settings->service_account  = $active;
		$options                    = array( '' => __( 'Choose...', 'sjea' ) );
		
		// Build the account select options. 
		foreach ( $saved_services[ $service ] as $account => $data ) {
			$options[ $account ] = $account;
		}
		
		$options['add_new_account'] = __( 'Add Account...', 'sjea' );
		
		// Render the account select. 
		SJEaModelHelper::render_settings_field( 'service_account', array(
			'row_class'     => 'sjea-service-account-row',
			'class'         => 'sjea-service-account-select',
			'type'          => 'select',
			'label'         => __( 'Account', 'sjea' ),
			'default'		=> $active,
			'options'       => $options,
		));
		
		// Render additional service fields if we have a saved account.
		if ( ! empty( $active ) && isset( $saved_services[ $service ][ $active ] ) ) {
			
			$instance   = self::get_service_instance( $service );
			$response   = $instance->render_fields( $active, $settings );
			
			if ( ! $response['error'] ) {
				echo $response['html'];
			}
		}
		
		return ob_get_clean();
	}
	
	/**
	 * Render the markup for service specific fields. 
	 *
	 * Called via the render_service_fields frontend AJAX action.
	 *
	 * @since 0.1.3
	 * @return array The response array.
	 */ 
	static public function render_fields() 
	{
		$service_provider = esc_attr( $_POST['service'] );
		$service_account = esc_attr( $_POST['account'] );
		$settings = array();

		$instance   = self::get_service_instance( $service_provider );
		$response   = $instance->render_fields( $service_account, $settings );
		
		return $response;
	}
	
	/**
	 * Delete a saved account from the database.
	 *
	 * Called via the delete_service_account frontend AJAX action.
	 *
	 * @since 0.1.3
	 * @return void
	 */ 
	static public function delete_account() 
	{
		if ( ! isset( $_POST['service'] ) || ! isset( $_POST['account'] ) ) {
			return;
		}
		
		SJEaModelHelper::delete_service_account( $_POST['service'], $_POST['account'] );
	}

	/**
	 * Save Campaign in the database.
	 *
	 * Called via the save_mailer_campaign frontend AJAX action.
	 *
	 * @since 0.1.3
	 * @return void
	 */ 
	static public function save_campaign() 
	{
		
		$campaign_name = $_POST['campaign_name'];
		$campaign_data = $_POST['campaign_data'];
			
		return SJEaModelHelper::update_campaign( $campaign_name, $campaign_data );
	}

	/**
	 * Delete a saved account from the database.
	 *
	 * Called via the delete_mailer_campaign frontend AJAX action.
	 *
	 * @since 0.1.3
	 * @return void
	 */ 
	static public function delete_campaign() 
	{
		if ( ! isset( $_POST['campaign_name'] ) ) {
			return;
		}
		
		SJEaModelHelper::delete_campaign( $_POST['campaign_name'] );
	}

	/**
	 * Add Subscriber
	 *
	 * Called via the sjea_add_subscriber frontend AJAX action.
	 *
	 * @since 0.1.3
	 * @return void
	 */ 
	static public function add_subscriber() 
	{
		
		$post_id 		= $_POST['post_id'];
		$form_id 		= $_POST['form_id'];
		$form_campaign 	= $_POST['form_campaign'];
		$param 			= $_POST['param'];
		
		
		$meta = Elementor\Plugin::instance()->db->get_plain_editor( $post_id );

		$form = self::find_element_recursive( $meta, $form_id );

		if ( ! $form || $form_campaign == '' ) {
			$return_array['message'] = self::get_default_message( self::INVALID_FORM, $form['settings'] );
			wp_send_json_error( $return_array );
		}

		if ( empty( $form['templateID'] ) ) {
			$fields = $form['settings']['form_fields'];
		} else {
			$global_meta = Elementor\Plugin::instance()->db->get_plain_editor( $form['templateID'] );
			$form = $global_meta[0];
			$fields = $form['settings']['form_fields'];
		}

		$settings = $form['settings'];

		if ( empty( $fields ) ) {
			$return_array['message'] = self::get_default_message( self::INVALID_FORM, $settings );
			wp_send_json_error( $return_array );
		}
		
		
		$mailer = SJEaModelHelper::get_campaigns( $form_campaign );

		if ( isset( $mailer['error'] ) ) {
			$return_array['message'] = $mailer['error'];
			wp_send_json_error( $return_array );
		}
		
		$instance = self::get_service_instance( $mailer['service'] );
		$response = $instance->subscribe( $mailer, $param );


		// if ( $email_sent || $skip_email ) {
		// } else {
		// }
		

		// Check for an error from the service.
		if ( $response['error'] ) {
			$return_array['message'] = $response['error'];
			wp_send_json_error( $return_array );
		}
		// Setup the success data.
		else {
			
			$return_array['link'] = ''; //$redirect_to;
			$return_array['message'] = self::get_default_message( self::SUCCESS, $settings );
			wp_send_json_success( $return_array );
			
		}

		die();
	}

	/**
	 * Find Element Recursive
	 *
	 *
	 * @since 0.1.3
	 * @return void
	 */
	static private function find_element_recursive( $elements, $form_id ) {
		foreach ( $elements as $element ) {
			if ( $form_id === $element['id'] ) {
				return $element;
			}

			if ( ! empty( $element['elements'] ) ) {
				$element = self::find_element_recursive( $element['elements'], $form_id );

				if ( $element ) {
					return $element;
				}
			}
		}

		return false;
	}

	public static function get_default_messages() {
		return [
			self::SUCCESS => __( 'The message was sent successfully!', 'sjea' ),
			self::ERROR => __( 'Something went wrong... Please fill in the required fields.', 'sjea' ),
			self::FIELD_REQUIRED => __( 'Required', 'sjea' ),
			self::INVALID_FORM => __( 'Something went wrong... Please set mailer.', 'sjea' ),
			self::SERVER_ERROR => __( 'Server error. Form not sent.', 'sjea' ),
		];
	}

	public static function get_default_message( $id, $settings ) {
		$field_id = $id . '_message';
		if ( isset( $settings[ $field_id ] ) ) {
				return $settings[ $field_id ];
		}
		
		$default_messages = self::get_default_messages();

		return isset( $default_messages[ $id ] ) ? $default_messages[ $id ] : __( 'Unknown', 'sjea' );
	}

	/* Submit SUpport FOrm */
	public static function submit_support() {
		
		$to 	 	= "Sandesh <sandesh2643@gmail.com>";
		$from 	 	= sanitize_email( $_POST['email'] );
		$site 	 	= esc_url( $_POST['site_url'] );
		$name 	 	= sanitize_text_field( $_POST['name'] );
		$sub 	 	= sanitize_text_field( $_POST['subject'] );
		$message 	= esc_html( $_POST['message'] );
		$post_url 	= esc_url( $_POST['post_url'] );

		switch ( $sub ) {
			case 'question':
				$subject = "[SJEA] New question received from ".$name;
				break;

			case 'bug':
				$subject = "[SJEA] New bug found by ".$name;
				break;

			case 'help':
				$subject = "[SJEA] New help request received from ".$name;
				break;

			case 'professional':
				$subject = "[SJEA] New service quote request received from ".$name;
				break;

			case 'contribute':
				$subject = "[SJEA] New development contribution request by ".$name;
				break;

			case 'other':
				$subject = "[SJEA] New contact request received from ".$name;
				break;

			default:
				$subject = "[SJEA] Unknown ".$name;
				break;
		}

		$html = '
			<html>
				<head>
				  <title>SJ Elementor Addon</title>
				</head>
				<body>
					<table width="100%" cellpadding="10" cellspacing="10">
						<tr>
							<th colspan="2">SJ Elementor Addon Support</th>
						</tr>
						<tr>
							<td width="22%"> Name : </td>
							<td width="78%"> <strong>'.$name.' </strong></td>
						</tr>
						<tr>
							<td> Email : </td>
							<td> <strong>'.$from.' </strong></td>
						</tr>
						<tr>
							<td> Website : </td>
							<td> <strong>'.$site.' </strong></td>
						</tr>
						<tr>
							<td colspan="2"> Message : </td>
                        </tr>
                        <tr>
							<td colspan="2"> '.$message.' </td>
						</tr>
					</table>
				</body>
			</html>';
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From:'.$name.'<'.$from.'>' . "\r\n";
		
		$response = wp_mail($to,$subject,$html,$headers);

		if ( $response ) {
			$data['msg'] = __( "Thank you!", 'sjea');
			wp_send_json_success( $data );
		}else{
			$data['msg'] = __( "Something went wrong!", 'sjea');
			wp_send_json_error( $data );
		}
	}
}