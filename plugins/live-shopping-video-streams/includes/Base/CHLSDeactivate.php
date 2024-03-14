<?php

/**
 * @package  Channelize Shopping
 */


namespace Includes\Base;

defined('ABSPATH') || exit;

class CHLSDeactivate
{
	public static function deactivate()
	{
		flush_rewrite_rules();
	}
}
