<?php defined('ABSPATH') or die;

/* This file is property of Designinvento. You may NOT copy, or redistribute
 * it. Please see the license that came with your copy for more information.
 */

/**
 * @package    direviews
 * @category   core
 * @author     Designinvento Team
 * @copyright  (c) 2013, Designinvento
 */
interface DiRV {

	/**
	 * @return array errors
	 */
	function validate($input);

	/**
	 * @param string rule
	 * @return string error message
	 */
	function error_message($rule);

} # interface
