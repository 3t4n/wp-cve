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
 * VikAppointments services view.
 *
 * @since 1.0
 */
class VikAppointmentsViewserviceslist extends JViewVAP
{
	/**
	 * VikAppointments view display method.
	 *
	 * @return 	void
	 */
	function display($tpl = null)
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		
		/**
		 * Load services through the view model.
		 *
		 * @since 1.7
		 */
		$model = JModelVAP::getInstance('serviceslist');

		// prepare query filters
		$filters = array();
		$filters['id_group'] = $input->getUint('service_group', null);

		// register groups and services
		$this->groups = $model->getItems($filters);
		
		// fetch current menu item ID
		$this->itemid = $input->getUint('Itemid');

		// prepare page content
		VikAppointments::prepareContent($this);
		
		// display the template
		parent::display($tpl);
	}
}
