<?php

namespace SG_Email_Marketing\Services\Background_Process;

use WP_Background_Process;
use SG_Email_Marketing\Services\Cron\Cron;
/**
 * Provides functionallity to fire off non-blocking asynchronous requests as a background processes.
 */
class Background_Process extends WP_Background_Process {

	/**
	 * Prefix.
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	protected $prefix = 'sg_email_marketing';

	/**
	 * Action.
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	protected $action = 'sg_background_process';

	/**
	 * Action.
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	protected $mailer_api;

	/**
	 * Initiate new background process.
	 *
	 * @since 1.0.0
	 *
	 * @param Mailer_Api $mailer_api The Mailer_Api object.
	 */
	public function __construct( $mailer_api ) {
		parent::__construct();

		$this->mailer_api = $mailer_api;
	}

	/**
	 * Task handler.
	 *
	 * @since 1.0.0
	 *
	 * @param array $item  The item array that needs to be done.
	 *
	 * @return bool        Return true on Exception, false when removed.
	 */
	protected function task( $item ) {
		try {
			$this->mailer_api->send_data( $item );
		} catch ( \Exception $e ) {
			return true;
		}

		// Remove the process from queue.
		return false;
	}

	/**
	 * Complete.
	 *
	 * @since 1.0.0
	 */
	protected function complete() {
		Cron::delete_meta_data();
		parent::complete();
	}
}
