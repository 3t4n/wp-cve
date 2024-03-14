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
 * VikAppointments employees reports view.
 *
 * @since 1.3
 */
class VikAppointmentsViewreportsemp extends JViewVAP
{
	/**
	 * VikAppointments view display method.
	 *
	 * @return 	void
	 */
	function display($tpl = null)
	{	
		$input = JFactory::getApplication()->input;
		
		// set the toolbar
		$this->addToolbar();

		// get employees
		$cid = $input->get('cid', array(), 'uint');

		// load view model
		$model = JModelVAP::getInstance('reportsemp');

		// extract view filters
		$this->filters = $model->getFiltersFromRequest();

		// build form data
		$this->employees = $model->getFormData($cid, $this->filters);

		// get return task
		$this->from = $input->get('from', 'employees');
		
		// display the template (default.php)
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar.
	 *
	 * @return 	void
	 */
	protected function addToolBar()
	{
		// add menu title and some buttons to the page
		JToolBarHelper::title(JText::translate('VAPREPORTSEMPTITLE'), 'vikappointments');

		JToolBarHelper::custom('reportsemp.download', 'download', 'download', JText::translate('VAPDOWNLOAD'), false);

		JToolBarHelper::cancel('employee.cancel', 'JTOOLBAR_CLOSE');
	}
}
