<?php
/**
 * @package Free Education Helper
 */
namespace Inc\Base;

class Activate
{
	public static function activate() {
		flush_rewrite_rules();
	}
}