<?php

use Logeecom\Infrastructure\Logger\Logger;
use Logeecom\Infrastructure\ServiceRegister;
use Logeecom\Infrastructure\TaskExecution\Exceptions\QueueStorageUnavailableException;
use Logeecom\Infrastructure\TaskExecution\Exceptions\TaskRunnerStatusStorageUnavailableException;
use Packlink\BusinessLogic\User\UserAccountService;
use Packlink\BusinessLogic\Utility\Php\Php55;
use Packlink\WooCommerce\Components\Services\Config_Service;
use Packlink\WooCommerce\Components\Tasks\Upgrade_Packlink_Order_Details;
use Packlink\WooCommerce\Components\Utility\Database;
use Packlink\WooCommerce\Components\Utility\Shop_Helper;
use Packlink\WooCommerce\Components\Utility\Task_Queue;

global $wpdb;

$database = new Database( $wpdb );
// This section will be triggered when upgrading from 1.0.2 to 2.0.0 or later version of plugin.
if ( ! $database->plugin_already_initialized() ) {
	Shop_Helper::create_log_directory();
	$database->install();

	/**
	 * Configuration service.
	 *
	 * @var Config_Service $config_service
	 */
	$config_service = ServiceRegister::getService( Config_Service::CLASS_NAME );

	try {
		$config_service->setTaskRunnerStatus( '', null );
		$statuses = array(
			'processing' => 'wc-processing',
			'delivered'  => 'wc-completed',
		);

		$config_service->setOrderStatusMappings( $statuses );
	} catch ( TaskRunnerStatusStorageUnavailableException $e ) {
		Logger::logError( $e->getMessage(), 'Integration' );
	}
}

try {
	$api_key = get_option( 'wc_settings_tab_packlink_api_key' );
	if ( $api_key ) {
		/**
		 * User account service.
		 *
		 * @var UserAccountService $user_service
		 */
		$user_service = ServiceRegister::getService( UserAccountService::CLASS_NAME );
		$user_service->login( $api_key );
		delete_option( 'wc_settings_tab_packlink_api_key' );

		try {

			$order_posts = $wpdb->get_results(
				"SELECT `post_id` as `ID` FROM {$wpdb->postmeta} WHERE `meta_key` = '_packlink_draft_reference'",
				ARRAY_A
			);

			Task_Queue::enqueue(new Upgrade_Packlink_Order_Details(Php55::arrayColumn($order_posts , 'ID')));
		} catch ( Exception $e ) {
			Logger::logError( 'Migration of order shipments failed.', 'Integration' );
		}
	}
} catch ( QueueStorageUnavailableException $e ) {
	Logger::logError( 'Migration of users API key failed.', 'Integration' );
}

return array();
