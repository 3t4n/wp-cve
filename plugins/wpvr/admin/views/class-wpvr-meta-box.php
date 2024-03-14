<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * The abstract class for maintaing custom meta box
 *
 * @link       http://rextheme.com/
 * @since      8.0.0
 *
 * @package    Wpvr
 * @subpackage Wpvr/admin/views
 */

abstract class WPVR_Meta_Box {

	/**
	 * Defination of custom meta box register method
	 * 
	 * @param string $data
	 * 
	 * @return void
	 * @since 8.0.0
	 */
	abstract public function register($data);


	/**
	 * Defination of custom meta box render method
	 * 
	 * @param object $data
	 * 
	 * @return void
	 * @since 8.0.0
	 */
	abstract public function render($data);

}
