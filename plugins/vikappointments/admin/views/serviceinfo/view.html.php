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
 * VikAppointments service info view.
 *
 * @since 1.0
 */
class VikAppointmentsViewserviceinfo extends JViewVAP
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
		$dbo   = JFactory::getDbo();

		$ids = $input->getUint('cid', array(0), 'uint');
		
		// get options
		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('o.id', 'o.name')))
			->from($dbo->qn('#__vikappointments_option', 'o'))
			->leftjoin($dbo->qn('#__vikappointments_ser_opt_assoc', 'a') . ' ON ' . $dbo->qn('o.id') . ' = ' . $dbo->qn('a.id_option'))
			->where($dbo->qn('a.id_service') . ' = ' . $ids[0]);

		$dbo->setQuery($q);
		$options = $dbo->loadObjectList();

		// get employees
		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('e.id', 'e.nickname')))
			->from($dbo->qn('#__vikappointments_employee', 'e'))
			->leftjoin($dbo->qn('#__vikappointments_ser_emp_assoc', 'a') . ' ON ' . $dbo->qn('e.id') . ' = ' . $dbo->qn('a.id_employee'))
			->where($dbo->qn('a.id_service') . ' = ' . $ids[0])
			->order($dbo->qn('a.ordering') . ' ASC');

		$dbo->setQuery($q);
		$employees = $dbo->loadObjectList();
		
		$this->options   = $options;
		$this->employees = $employees;

		// display the template
		parent::display($tpl);
	}
}
