<?php
/**
 * Container component.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

interface ContainerInterface {
	/**
	 * ContainerInterface constructor.
	 */
	public function __construct();
}
