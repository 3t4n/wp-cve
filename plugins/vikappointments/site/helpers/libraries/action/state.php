<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Wraps the information of the action state variation.
 * 
 * @since 1.7.3
 */
class VAPActionState extends JObject
{
	/**
	 * Flag used to check whether the propagation should stop.
	 * 
	 * @var boolean
	 */
	private $stopPropagation = false;

	/**
	 * Checks whether the action propagation has been stopped.
	 * 
	 * @return  boolean
	 */
	public function isPropagationStopped()
	{
		return $this->stopPropagation;
	}

	/**
	 * Stops the action propagation.
	 * 
	 * @return  void
	 */
	public function stopPropagation()
	{
		$this->stopPropagation = true;
	}
}
