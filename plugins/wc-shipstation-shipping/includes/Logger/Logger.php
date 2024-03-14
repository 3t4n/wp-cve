<?php
/*********************************************************************/
/* PROGRAM    (C) 2022 FlexRC                                        */
/* PROPERTY   604-1097 View St                                        */
/* OF         Victoria, BC, V8V 0G9                                   */
/*            CANADA                                                 */
/*            Voice (604) 800-7879                                   */
/*********************************************************************/

namespace OneTeamSoftware\WooCommerce\Logger;

defined('ABSPATH') || exit; // Exit if accessed directly

if (!class_exists(__NAMESPACE__ . '\\Logger')):

class Logger
{
	private $id;
	private $enabled;
	private $logger;

	public function __construct($id, $enabled = false)
	{
		$this->id = $id;
		$this->logger = null;
		$this->enabled = $enabled;

		add_action('plugins_loaded', array($this, 'initLogger'), 1);
	}

	public function initLogger()
	{
		if (function_exists('wc_get_logger') && is_null($this->logger)) {
			$this->logger = wc_get_logger();
		}
	}

	public function setEnabled($enabled)
	{
		$this->enabled = $enabled;
	}

	public function log($level, $file, $line, $message)
	{
		$this->initLogger();

		if (!is_object($this->logger) || !$this->enabled) {
			return;
		}
		
		$this->logger->log($level, $this->getMessage($file, $line, $message), array('source' => $this->id));
	}

	public function debug($file, $line, $message)
	{
		$this->log('debug', $file, $line, $message);
	}

	public function info($file, $line, $message)
	{
		$this->log('info', $file, $line, $message);
	}

	public function notice($file, $line, $message)
	{
		$this->log('notice', $file, $line, $message);
	}

	public function warning($file, $line, $message)
	{
		$this->log('warning', $file, $line, $message);
	}

	public function error($file, $line, $message)
	{
		$this->log('error', $file, $line, $message);
	}

	public function critical($file, $line, $message)
	{
		$this->log('critical', $file, $line, $message);
	}

	public function alert($file, $line, $message)
	{
		$this->log('alert', $file, $line, $message);
	}

	public function emergency($file, $line, $message)
	{
		$this->log('emergency', $file, $line, $message);
	}

	private function getMessage($file, $line, $message)
	{
		return sprintf('[%s:%s] %s', basename($file), $line, $message);
	}
}

endif;