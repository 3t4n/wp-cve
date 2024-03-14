<?php

namespace WilokeEmailCreator\DataFactory\Controllers;

use Exception;
use WilokeEmailCreator\DataFactory\Interfaces\IDataFactory;

class DataFactoryController
{
	private static string $callDataWithPlatform = 'DataServer';

	/**
	 * @throws Exception
	 */
	public static function setPlatform(string $platform = ''): IDataFactory
	{
		try {

			if (empty($platform)) {
				$platform = self::$callDataWithPlatform;
			}
			$aConfigDataFactory = include plugin_dir_path(__FILE__) . '../Configs/DataFactory.php';
			if (array_key_exists($platform, $aConfigDataFactory) && class_exists
				($className = $aConfigDataFactory[$platform])) {
				return new $className;
			} else {
				throw new Exception(esc_html__("Sorry, the platform not exist",  "emailcreator"));
			}
		}
		catch (Exception $exception) {
			throw new Exception($exception->getMessage(), $exception->getCode());
		}
	}
}
