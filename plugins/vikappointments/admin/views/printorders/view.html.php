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

VAPLoader::import('libraries.order.factory');

/**
 * VikAppointments print orders view.
 *
 * @since 1.1
 */
class VikAppointmentsViewprintorders extends JViewVAP
{
	/**
	 * VikAppointments view display method.
	 *
	 * @return 	void
	 */
	function display($tpl = null)
	{
		$input = JFactory::getApplication()->input;
		$input->set('tmpl', 'component');

		$ids = $input->getUint('cid', array());

		// get current language tag
		$tag = JFactory::getLanguage()->getTag();
		
		$this->rows = array();

		foreach ($ids as $id)
		{
			// load order details into the current language
			$this->rows[] = VAPOrderFactory::getAppointments($id, $tag);
		}
	
		// display the template
		parent::display($tpl);
	}
}
