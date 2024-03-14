<?php
/**
 * Packlink PRO Shipping WooCommerce Integration.
 *
 * @package Packlink
 */

namespace Packlink\WooCommerce\Components\Utility;

use Logeecom\Infrastructure\Logger\Logger;
use Logeecom\Infrastructure\ServiceRegister;
use Logeecom\Infrastructure\TaskExecution\Exceptions\QueueStorageUnavailableException;
use Logeecom\Infrastructure\TaskExecution\Interfaces\TaskRunnerWakeup;
use Logeecom\Infrastructure\TaskExecution\QueueService;
use Logeecom\Infrastructure\TaskExecution\Task;
use Packlink\WooCommerce\Components\Services\Config_Service;

/**
 * Class Task_Queue
 *
 * @package Packlink\WooCommerce\Components\Utility
 */
class Task_Queue {
	/**
	 * Enqueues a task to the queue.
	 *
	 * @param Task $task Task to be enqueued.
	 * @param bool $throw_exception If functions should rethrow an error, set to true.
	 *
	 * @return int Queue item id.
	 * @throws QueueStorageUnavailableException Queue storage unavailable.
	 */
	public static function enqueue( Task $task, $throw_exception = false ) {
		$result = 0;
		try {
			/**
			 * Configuration service.
			 *
			 * @var Config_Service $config_service
			 */
			$config_service = ServiceRegister::getService( Config_Service::CLASS_NAME );
			$access_token   = $config_service->getAuthorizationToken();
			if ( null !== $access_token ) {
				/**
				 * Queue service.
				 *
				 * @var QueueService $queue_service
				 */
				$queue_service = ServiceRegister::getService( QueueService::CLASS_NAME );
				$queue_item    = $queue_service->enqueue( $config_service->getDefaultQueueName(), $task );
				$result        = $queue_item->getId();
			}
		} catch ( QueueStorageUnavailableException $ex ) {
			Logger::logDebug(
				'Failed to enqueue task ' . $task->getType(),
				'Integration',
				array(
					'ExceptionMessage' => $ex->getMessage(),
					'ExceptionTrace'   => $ex->getTraceAsString(),
					'TaskData'         => serialize( $task ),
				)
			);
			if ( $throw_exception ) {
				throw $ex;
			}
		}

		return $result;
	}

	/**
	 * Calls the wakeup on task runner.
	 */
	public static function wakeup() {
		if ( Shop_Helper::is_plugin_active_for_current_site() && Shop_Helper::is_curl_enabled() ) {
			/**
			 * Wakeup service.
			 *
			 * @var TaskRunnerWakeup $wakeup_service
			 */
			$wakeup_service = ServiceRegister::getService( TaskRunnerWakeup::CLASS_NAME );
			$wakeup_service->wakeup();
		}
	}
}
