<?php
defined( 'ABSPATH' ) or die( 'No access' );
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 2016/04/18
 * Time: 7:03 PM
 */

spl_autoload_register(function ($class) {
    $class = 'Lib/' . preg_replace("/^MetaThemeColour\\\/", '', $class) . '.php';
	if (stream_resolve_include_path($class)){
		require_once $class;
	}
});