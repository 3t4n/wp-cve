<?php


#namespace WilokeTest\Helpers;


class FunctionHelper
{
	/**
	 * Replacing underscore with upper-case first
	 * @param $func
	 * @return string
	 */
	public static function makeFunc($func): string
	{
		$func = StringHelper::replaceUnderscoreWithUpperCase($func);

		return strpos($func, 'get') === false ? 'get' . ucfirst($func) : $func;
	}
}
