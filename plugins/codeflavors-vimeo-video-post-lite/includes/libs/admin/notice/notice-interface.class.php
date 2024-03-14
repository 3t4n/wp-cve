<?php

namespace Vimeotheque\Admin\Notice;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Interface Notice_Interface
 * @package Vimeotheque
 * @ignore
 */
interface Notice_Interface{
	/**
	 * Returns notice content
	 * @return mixed
	 */
	public function get_notice();

	/**
	 * @return mixed
	 */
	public function dismiss_notice();
}