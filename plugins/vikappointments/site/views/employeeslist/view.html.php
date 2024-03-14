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
 * VikAppointments employees view.
 *
 * @since 1.0
 */
class VikAppointmentsViewemployeeslist extends JViewVAP
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

		// get employee group from request
		$this->empGroup = $input->getInt('employee_group', 0);

		/**
		 * Load employees through the view model.
		 *
		 * @since 1.7
		 */
		$model = JModelVAP::getInstance('employeeslist');

		$options = array();

		// set initial pagination offset
		$options['start'] = $input->getUint('limitstart', 0);

		// get selected ordering
		$options['ordering'] = $app->getUserStateFromRequest('employees.ordering', 'ordering', null, 'uint');

		// search query (filters in request)
		$filters = $model->getActiveFilters();
		$filters_in_request = (bool) $filters;

		// inject employee ID within the filters
		$filters['employee_group'] = $this->empGroup;
		
		// load employees
		$this->employees = $model->getItems($filters, $options);

		// unset employee group from filters
		unset($filters['employee_group']);

		if ($this->employees)
		{
			// get pagination HTML
			$this->navbut = $model->getPagination()->getPagesLinks();
		}
		else
		{
			$this->navbut = '';
		}

		// get employees groups
		$this->groups = $model->getGroups();

		// get AJAX search mode from configuration
		$this->ajaxSearch = VAPFactory::getConfig()->getUint('empajaxsearch');	

		if ($this->ajaxSearch == 2)
		{
			// if AJAX seach should be used only with filters, make sure
			// there is at least a filter set
			$this->ajaxSearch = (int) $filters_in_request;
		}
		
		// register selected employee for auto-scroll
		$this->selEmployee = $input->getInt('id_emp', 0);;
		
		$this->hasFilters = $filters_in_request;
		$this->filters    = $filters;
		$this->options    = $options;
		
		$this->employeesCount = $model->getTotal();

		// fetch current menu item ID
		$this->itemid = $input->getUint('Itemid');

		if ($this->ajaxSearch)
		{
			// include script needed to support AJAX search
			$this->addJS();
		}

		// prepare page content
		VikAppointments::prepareContent($this);
		
		// display the template
		parent::display($tpl);
	}

	/**
	 * Helper method used to include the JS functions
	 * required for the AJAX search tool.
	 *
	 * @return 	void
	 *
	 * @since 	1.6
	 */
	private function addJS()
	{
		$vik = VAPApplication::getInstance();

		// build AJAX end-point
		$url = $vik->ajaxUrl('index.php?option=com_vikappointments&task=employeeslist.availtableajax' . ($this->itemid ? '&Itemid=' . $this->itemid : ''), false);

		// setup display data for layout
		$data = array(
			'url' => $url,
		);

		/**
		 * The javascript functions needed by the time table are declared by the layout below:
		 * /components/com_vikappointments/layouts/javascript/timeline/table.php
		 * 
		 * If you need to change something from this layout, just create
		 * an override of this layout by following the instructions below:
		 * - open the back-end of your Joomla
		 * - visit the Extensions > Templates > Templates page
		 * - edit the active template
		 * - access the "Create Overrides" tab
		 * - select Layouts > com_vikappointments > javascript
		 * - start editing the timeline/table.php file on your template to create your own layout
		 *
		 * @since 1.6
		 */
		$js = JLayoutHelper::render('javascript.timeline.table', $data);

		$this->document->addScriptDeclaration($js);
	}
}
