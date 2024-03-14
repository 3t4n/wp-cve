<?php
namespace Vimeotheque\Blocks;

use Vimeotheque\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 *
 * @ignore
 */
interface Block_Interface{
	/**
	 * Returns handle for block main script
	 *
	 * @return string
	 */
	public function get_script_handle();
}