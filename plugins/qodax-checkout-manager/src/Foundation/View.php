<?php

namespace Qodax\CheckoutManager\Foundation;

if ( ! defined('ABSPATH')) {
  exit;
}

final class View
{
    private static $basePath;

    public static function setBasePath(string $path)
    {
        self::$basePath = $path;
    }

	public static function render(string $view, array $data = [])
	{
	    if ( ! self::$basePath) {
	        return '';
        }

		$fileName = self::$basePath . "/$view.php";

	    if ( ! file_exists($fileName)) {
	        return '';
        }

		ob_start();
		extract($data);
		include $fileName;
		$output = ob_get_clean();

		return $output;
	}
}