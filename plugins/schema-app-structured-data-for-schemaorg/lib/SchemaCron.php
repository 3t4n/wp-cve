<?php

/**
 * Handle all cron related functionalities.
 */
class SchemaCron {

	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'hook_plugins_loaded' ) );
	}


	/**
	 * Common hooked function to run when all plugins are loaded.
	 *
	 * 1) Schedule cron job to fetch schema data from API. Hook on plugins_loaded so that all WP and plugin functions are available within hooked function.
	 */
	public function hook_plugins_loaded() {
		// $accepted_args = 1 ( resource uri passed from scheduled event )
		add_action( 'schema_app_cron_resource_from_api', array( $this, 'resource_from_api' ), 10, 1 );
	}


	/**
	 * Fetch schema data through API.
	 *
	 * @uses SchemaServer()
	 */
	public function resource_from_api( $uri = '' ) {
		if ( empty( $uri ) ) {
			return;
		}

		$schema_server = new SchemaServer();
		$schema_server->getResourceFromAPI( $uri );
	}

}

?>