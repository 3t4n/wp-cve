<?php
/**
 * @package Channelize Shopping
 */
namespace Includes\Base;
defined( 'ABSPATH' ) || exit;

class CHLSActivate
{
	public static function activate() {
		flush_rewrite_rules();
	}
}