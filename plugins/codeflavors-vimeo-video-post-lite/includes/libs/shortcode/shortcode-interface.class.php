<?php

namespace Vimeotheque\Shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Interface Shortcode_Interface
 * @package Vimeotheque\Shortcode
 * @ignore
 */
interface Shortcode_Interface {

	/**
	 * Shortcode_Interface constructor.
	 *
	 * @param string $shortcode_name
	 */
	public function __construct( $shortcode_name );

	/**
	 * The shortcode output
	 *
	 * @param $atts
	 * @param $content
	 *
	 * @return string
	 */
	public function get_output( $atts, $content );
}