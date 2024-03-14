<?php


#namespace WilokeTest\Helpers;


class StringHelper
{
	public static function replaceUpperCaseWithUnderscore($string): string {
		return preg_replace_callback('/\B([A-Z])/', function ($aMatches) {
			return '_'.strtolower($aMatches[1]);
		}, $string);
	}

	public static function replaceUnderscoreWithUpperCase($string): string {
		return preg_replace_callback('/_([a-zA-Z0-9])/', function ($aMatches) {
			return ucfirst($aMatches[1]);
		}, $string);
	}
}
