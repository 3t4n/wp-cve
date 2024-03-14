<?php

/**
 * Base class for third party services.
 *
 * @since 0.1.3
 */
abstract class SJEaService {

	/**
	 * The ID for this service such as aweber or mailchimp.
	 *
	 * @since 0.1.3
	 * @var string $id
	 */  
	public $id = '';
	
	/**
	 * Test the API connection.
	 *
	 * @since 0.1.3
	 * @param array $fields
	 * @return array{
	 *      @type bool|string $error The error message or false if no error.
	 *      @type array $data An array of data used to make the connection.
	 * }
	 */  
	abstract public function connect( $fields = array() );

	/**
	 * Renders the markup for the connection settings.
	 *
	 * @since 0.1.3
	 * @return string The connection settings markup.
	 */  
	abstract public function render_connect_settings();

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
	abstract public function render_fields( $account, $settings );

	/**
	 * Get the saved data for a specific account.
	 *
	 * @since 0.1.3
	 * @param string $account The account name.
	 * @return array|bool The account data or false if it doesn't exist.
	 */  
	public function get_account_data( $account ) 
	{
		$saved_services = get_option( '_sjea_mailer_services', array() );
				
		if ( isset( $saved_services[ $this->id ] ) && isset( $saved_services[ $this->id ][ $account ] ) ) {
			return $saved_services[ $this->id ][ $account ];
		}
		
		return false;
	}
}