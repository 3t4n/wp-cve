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

VAPLoader::import('libraries.customfields.control');

/**
 * Employee area field control renderer.
 *
 * @since 1.7
 */
class VAPEmployeAreaFieldControl implements VAPCustomFieldControl
{
	/**
	 * Returns the HTML of the field.
	 *
	 * @param 	array   $data   An array of display data.
	 * @param 	string  $input  The HTML of the input to wrap.
	 *
	 * @return  string  The HTML of the input.
	 */
	public function render($data, $input = null)
	{
		$vik = VAPApplication::getInstance();

		// define control attributes
		$attrs = array(
			'id' => @$data['id'],
		);

		$label = @$data['label'];

		// add required character
		if ($label && !empty($data['required']))
		{
			$label .= '*';
		}

		// add description tooltip
		if (!empty($data['description']))
		{
			$label .= $vik->createPopover(array(
				'title'   => @$data['label'],
				'content' => $data['description'],
			));
		}

		// open control
		return $vik->openControl($label, @$data['class'], $attrs)
			. $input
			. $vik->closeControl();
	}
}