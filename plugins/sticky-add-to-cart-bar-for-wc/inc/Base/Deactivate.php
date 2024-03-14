<?php
/**
 * @package  WooCart
 */

namespace WscInc\Base;

class Deactivate
{
	public static function deactivate() {
		flush_rewrite_rules();
	}
}