<?php


namespace DataPeen\FaceAuth;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * Handle common logging function
 * Class Flog
 * @package DataPeen\FaceAuth
 */
class Flog {


	private static $log;
	const LOG_NAME = 'DP_FF';

	private static function getLog()
	{
		if (is_null(self::$log))
		{
			self::$log = new Logger(self::LOG_NAME);
		}

		return self::$log;
	}

	private static function get_path()
	{
		return plugin_dir_path(__FILE__) . 'dp-ff.log';
	}



	public static function write($message)
	{
		// create a log channel
		self::getLog()->pushHandler(new StreamHandler(self::get_path(), Logger::WARNING));

		// add records to the log
		self::getLog()->warning($message);
	}


}