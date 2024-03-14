<?php
if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Plugin_Command_Cron' ) ) {
	return;
}

class WC_Payever_Plugin_Command_Cron {

	/**
	 * Executes plugin commands
	 */
	public static function execute_plugin_commands() {
		try {
			WC_Payever_Api::get_instance()->get_plugins_api_client()->registerPlugin();
			WC_Payever_Api::get_instance()
						->get_plugin_command_manager()
						->executePluginCommands( get_option( WC_Payever_Helper::KEY_PLUGIN_COMMAND_TIMESTAMP ) );

			update_option( WC_Payever_Helper::KEY_PLUGIN_COMMAND_TIMESTAMP, time() );
		} catch ( \Exception $exception ) {
			WC_Payever_Api::get_instance()->get_logger()->warning(
				sprintf( 'Plugin command execution failed: %s', $exception->getMessage() )
			);
		}
	}
}
