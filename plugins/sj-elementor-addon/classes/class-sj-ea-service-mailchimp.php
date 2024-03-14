<?php

/**
 * Helper class for the MailChimp API.
 *
 * @since 0.1.3
 */
final class SJEaServiceMailChimp extends SJEaService {

	/**
	 * The ID for this service.
	 *
	 * @since 0.1.3
	 * @var string $id
	 */  
	public $id = 'mailchimp';

	/**
	 * @since 0.1.3
	 * @var object $api_instance
	 * @access private
	 */  
	private $api_instance = null;

	/**
	 * Get an instance of the API.
	 *
	 * @since 0.1.3
	 * @param string $api_key A valid API key.
	 * @return object The API instance.
	 */  
	public function get_api( $api_key ) 
	{
		if ( $this->api_instance ) {
			return $this->api_instance;
		}
		if ( ! class_exists( 'Mailchimp' ) ) {
			require_once SJ_EA_DIR . 'includes/vendor/mailchimp/mailchimp.php';
		}
		
		$this->api_instance = new Mailchimp( $api_key );
		
		return $this->api_instance;
	}
	
	/**
	 * Test the API connection.
	 *
	 * @since 0.1.3
	 * @param array $fields {
	 *      @type string $api_key A valid API key.
	 * }
	 * @return array{
	 *      @type bool|string $error The error message or false if no error.
	 *      @type array $data An array of data used to make the connection.
	 * }
	 */  
	public function connect( $fields = array() ) 
	{
		$response = array( 
			'error'  => false,
			'data'   => array()
		);
		
		// Make sure we have an API key.
		if ( ! isset( $fields['api_key'] ) || empty( $fields['api_key'] ) ) {
			$response['error'] = __( 'Error: You must provide an API key.', 'sjea' );
		}
		// Try to connect and store the connection data.
		else {
			
			$api = $this->get_api( $fields['api_key'] );

			try {
				$api->helper->ping();
				$response['data'] = array( 'api_key' => $fields['api_key'] );
			} 
			catch ( Mailchimp_Invalid_ApiKey $e ) {
				$response['error'] = $e->getMessage();
			}
		}
		
		return $response;
	}

	/**
	 * Renders the markup for the connection settings.
	 *
	 * @since 0.1.3
	 * @return string The connection settings markup.
	 */  
	public function render_connect_settings() 
	{
		ob_start();
		
		SJEaModelHelper::render_settings_field( 'api_key', array(
			'row_class'     => 'sjea-service-connect-row',
			'class'         => 'sjea-service-connect-input',
			'type'          => 'text',
			'label'         => __( 'API Key', 'sjea' ),
			'help'          => __( 'Your API key can be found in your MailChimp account under Account > Extras > API Keys.', 'sjea' ),
			'preview'       => array(
				'type'          => 'none'
			)
		)); 
		
		return ob_get_clean();
	}

	/**
	 * Render the markup for service specific fields. 
	 *
	 * @since 0.1.3
	 * @param string $account The name of the saved account.
	 * @param object $settings Saved module settings.
	 * @return array {
	 *      @type bool|string $error The error message or false if no error.
	 *      @type string $html The field markup.
	 * }
	 */  
	public function render_fields( $account, $settings ) 
	{
		// $post_data      = FLBuilderModel::get_post_data();
		$account_data   = $this->get_account_data( $account );
		$api            = $this->get_api( $account_data['api_key'] );
		$response       = array( 
			'error'         => false, 
			'html'          => '' 
		);
		
		// Lists field
		try {
			
			if ( ! isset( $_POST['list_id'] ) ) {
				$lists = $api->lists->getList();
				$response['html'] .= $this->render_list_field( $lists );
			}
		} 
		catch ( Mailchimp_Error $e ) {
			$response['error'] = $e->getMessage();
		}
		
		// Groups field
		try {
			
			if ( isset( $_POST['list_id'] ) ) {
				
				$list_id = $_POST['list_id'];
				
				$groups = $api->lists->interestGroupings( $list_id );
				$response['html'] .= $this->render_groups_field( $list_id, $groups, $settings );
			}
		} 
		catch ( Mailchimp_Error $e ) {}
		
		return $response;
	}

	/**
	 * Render markup for the list field. 
	 *
	 * @since 0.1.3
	 * @param array $lists List data from the API.
	 * @param object $settings Saved module settings.
	 * @return string The markup for the list field.
	 * @access private
	 */  
	private function render_list_field( $lists ) 
	{
		ob_start();
		
		$options = array( '' => __( 'Choose...', 'sjea' ) );
		
		foreach ( $lists['data'] as $list ) {
			$options[ $list['id'] ] = $list['name'];
		}
		
		SJEaModelHelper::render_settings_field( 'list_id', array(
			'row_class'     => 'sjea-service-field-row',
			'class'         => 'sjea-service-list-select sjea-mailchimp-list-select',
			'type'          => 'select',
			'label'         => _x( 'List', 'An email list from a third party provider.', 'sjea' ),
			'options'       => $options,
		)); 
		
		return ob_get_clean();
	}

	/**
	 * Render markup for the groups field. 
	 *
	 * @since 0.1.3
	 * @param string $list_id The ID of the list for this groups.
	 * @param array $groups An array of group data.
	 * @param object $settings Saved module settings.
	 * @return string The markup for the group field.
	 * @access private
	 */  
	private function render_groups_field( $list_id, $groups, $settings ) 
	{
		if ( ! is_array( $groups ) || 0 === count( $groups ) ) {
			return;
		}
		
		ob_start();
		
		$options = array( '' => __( 'No Group', 'sjea' ) );
		
		foreach ( $groups as $group ) {
			foreach ( $group['groups'] as $subgroup ) {
				$options[ $list_id . '_' . $group['id'] . '_' . $subgroup['id'] ] = $group['name'] . ' - ' . $subgroup['name'];
			}
		}
		
		SJEaModelHelper::render_settings_field( 'groups', array(
			'row_class'     => 'sjea-service-field-row',
			'class'         => 'sjea-mailchimp-group-select',
			'type'          => 'select',
			'label'         => _x( 'Groups', 'MailChimp list group.', 'sjea' ),
			'multi-select'	=> true,
			'options'       => $options,
		)); 
		
		return ob_get_clean();
	}

	/** 
	 * Subscribe an email address to MailChimp.
	 *
	 * @since 0.1.3
	 * @param object $settings A module settings object.
	 * @param string $email The email to subscribe.
	 * @param string $name Optional. The full name of the person subscribing.
	 * @return array {
	 *      @type bool|string $error The error message or false if no error.
	 * }
	 */  
	public function subscribe( $settings, $param )
	{
		
		$account_data = $this->get_account_data( $settings['service_account'] );
		$response     = array( 'error' => false );
		
		if ( ! $account_data ) {
			$response['error'] = __( 'There was an error subscribing to MailChimp. The account is no longer connected.', 'sjea' );
		}
		else {
			
			$api     = $this->get_api( $account_data['api_key'] );
			$double  = apply_filters( 'sjea_mailchimp_double_option', true );
			$welcome = apply_filters( 'sjea_mailchimp_welcome', true );
			$email   = array( 'email' => $param['email'] );
			$data    = array();
			
			
			foreach ($param as $key => $value) {
				
				if ( $key == 'email' ) {
					continue;
				}

				$data[$key] = $value;
			}


			if ( isset( $settings['groups'] ) && is_array( $settings['groups'] ) ) {
			
				$settings['groups'] = array_filter( $settings['groups'] );
			}
			
			// Groups
			if ( isset( $settings['groups'] ) && is_array( $settings['groups'] ) ) {
				
				$groups = array();
				$g_count = count( $settings['groups'] );
				// Build the array of saved group data.
				for ( $i = 0; $i < $g_count; $i++ ) {
					
					if ( empty( $settings['groups'][ $i ] ) ) {
						continue;
					}
					
					$group_data = explode( '_', $settings['groups'][ $i ] );
					
					if ( $group_data[0] != $settings['list_id'] ) {
						continue;
					}
					if ( ! isset( $groups[ $group_data[1] ] ) ) {
						$groups[ $group_data[1] ] = array();
					}
					
					$groups[ $group_data[1] ][] = $group_data[2];
				}
				
				// Get the subgroup names from the API and add to the $data array.
				if ( count( $groups ) > 0 ) {
				
					$groups_result = $api->lists->interestGroupings( $settings['list_id'] );
					
					if ( is_array( $groups_result ) && count( $groups_result ) > 0 ) {
						
						foreach ( $groups_result as $group ) {
							
							if ( ! isset( $groups[ $group['id'] ] ) ) {
								continue;
							}
							
							$subgroup_names = array();
							
							foreach ( $group['groups'] as $subgroup ) {
								
								if ( in_array( $subgroup['id'], $groups[ $group['id'] ] ) ) {
									$subgroup_names[] = $subgroup['name'];
								}
							}
							
							if ( 0 === count( $subgroup_names ) ) {
								unset( $groups[ $group['id'] ] );
							}
							else {
								$groups[ $group['id'] ] = $subgroup_names;
							}
						}
						
						$i = 0;
						
						foreach ( $groups as $group_id => $subgroups ) {
							$data['groupings'][ $i ]['id']     = $group_id;
							$data['groupings'][ $i ]['groups'] = $subgroups;
							$i++;
						}
					}
				}
			}
			
			// Subscribe
			try {
				$api->lists->subscribe( $settings['list_id'], $email, $data, 'html', (bool) $double, true, false, (bool) $welcome );
			} 
			catch( Mailchimp_List_AlreadySubscribed $e ) {
				return $response;
			} 
			catch ( Mailchimp_Error $e ) {
				$response['error'] = sprintf(
					__( 'There was an error subscribing to MailChimp. %s', 'sjea' ),
					$e->getMessage()
				);
			}
		}
		
		return $response;
	}
}