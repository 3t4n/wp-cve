<?php
/**
 * Copyright (c) 2016 Ultra Community (http://www.ultracommunity.com)
 */

namespace InvisibleReCaptcha\MchLib\Utils;

final class MchValidator
{
	public static function isNullOrEmpty($value, $allowZeroValue = false)
	{
		return $allowZeroValue ? (empty($value) && !\is_numeric($value)) : empty($value);
	}

	public static function isNumeric($value)
	{
		return \is_numeric($value);
	}

	public static function isInteger($value, $strict = false)
	{
		if(false === \filter_var($value, FILTER_VALIDATE_INT))
			return false;

		return $strict ? $value === (int)$value : true;
	}

	public static function isEmail($value)
	{
		function_exists('is_email') || require_once( ABSPATH . WPINC . '/formatting.php' );
		return is_email($value);
	}

	public static function isURL($strUrl)
	{
		return (false !== \filter_var($strUrl, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED | FILTER_FLAG_HOST_REQUIRED )); //|FILTER_FLAG_PATH_REQUIRED
	}

	public static function isHexColor($colorCode)
	{
		$colorCode = ltrim($colorCode, '#');
		return ctype_xdigit($colorCode) && in_array(strlen($colorCode), array(6,3));
	}

}
