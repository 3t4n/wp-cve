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
 * VikAppointments services reports view.
 *
 * @since 1.3
 */
class VikAppointmentsViewreportsser extends JViewVAP
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

		// get services
		$cid = $input->get('cid', array(), 'uint');

		// load view model
		$model = JModelVAP::getInstance('reportsser');

		// extract view filters
		$this->filters = $model->getFiltersFromRequest();

		// build form data
		$this->services = $model->getFormData($cid, $this->filters);
		
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
		JToolBarHelper::title(JText::translate('VAPREPORTSSERTITLE'), 'vikappointments');

		JToolBarHelper::custom('reportsser.download', 'download', 'download', JText::translate('VAPDOWNLOAD'), false);

		$from = JFactory::getApplication()->input->get('from', 'service');

		if ($from == 'calendar')
		{
			JToolBarHelper::cancel('calendar.cancel', 'JTOOLBAR_CLOSE');
		}
		else
		{
			JToolBarHelper::cancel('service.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
