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

VAPLoader::import('libraries.mvc.model');

/**
 * VikAppointments employee area services list view model.
 *
 * @since 1.7
 */
class VikAppointmentsModelEmpserviceslist extends JModelVAP
{
	/**
	 * The list view pagination object.
	 *
	 * @var JPagination
	 */
	protected $pagination = null;

	/**
	 * The total number of fetched rows.
	 *
	 * @var integer
	 */
	protected $total = 0;

	/**
	 * Loads a list of services to be displayed within the
	 * employees area view.
	 *
	 * @param 	array  &$filters  An array of filters.
	 * @param 	array  &$options  An array of options, such as the ordering mode.
	 *
	 * @return 	array  A list of services.
	 */
	public function getItems(array &$filters = array(), array &$options = array())
	{
		// always reset pagination and total count
		$this->pagination = null;
		$this->total      = 0;

		$auth = VAPEmployeeAuth::getInstance();

		if (!$auth->isEmployee())
		{
			// raise error in case of no employee
			throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
		}

		$dbo = JFactory::getDbo();

		$rows = array();

		$q = $dbo->getQuery(true)
			->select('SQL_CALC_FOUND_ROWS s.*')
			->select(array(
				$dbo->qn('a.rate'),
				$dbo->qn('a.duration', 'override_duration'),
				$dbo->qn('a.sleep', 'override_sleep'),
				$dbo->qn('g.name', 'group_name'),
			))
			->from($dbo->qn('#__vikappointments_service', 's'))
			->leftjoin($dbo->qn('#__vikappointments_ser_emp_assoc', 'a') . ' ON ' . $dbo->qn('s.id') . ' = ' . $dbo->qn('a.id_service'))
			->leftjoin($dbo->qn('#__vikappointments_group', 'g') . ' ON ' . $dbo->qn('g.id') . ' = ' . $dbo->qn('s.id_group'))
			->where($dbo->qn('a.id_employee') . ' = ' . $auth->id)
			->order($dbo->qn('s.ordering') . ' ASC');
		
		$dbo->setQuery($q, $options['start'], $options['limit']);
		$rows = $dbo->loadObjectList();

		if ($rows)
		{
			// fetch pagination
			$this->getPagination($filters, $options);
		}

		return $rows;
	}

	/**
	 * Returns the list pagination.
	 *
	 * @param 	array  $filters  An array of filters.
	 * @param 	array  $options  An array of options.
	 *
	 * @return  JPagination
	 */
	public function getPagination(array $filters = array(), array $options = array())
	{
		if (!$this->pagination)
		{
			jimport('joomla.html.pagination');
			$dbo = JFactory::getDbo();
			$dbo->setQuery('SELECT FOUND_ROWS();');
			$this->total = (int) $dbo->loadResult();

			$this->pagination = new JPagination($this->total, $options['start'], $options['limit']);

			foreach ($filters as $k => $v)
			{
				// append only filters that own a value as it doesn't
				// make sense to populate the URL using empty variables
				if ($v)
				{
					$this->pagination->setAdditionalUrlParam($k, $v);
				}
			}
		}

		return $this->pagination;
	}

	/**
	 * Returns the total number of employees matching the search query.
	 *
	 * @return 	integer
	 */
	public function getTotal()
	{
		return $this->total;
	}
}
