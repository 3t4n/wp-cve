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
 * VikAppointments employees list view model.
 *
 * @since 1.7
 */
class VikAppointmentsModelEmployeeslist extends JModelVAP
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
	 * Retrieves the filters set in request and sanitizes them to prevent XSS.
	 * 
	 * @return  array  The query filters.
	 * 
	 * @since   1.7.4
	 */
	public function getActiveFilters()
	{
		// obtain all filters
		$filters = JFactory::getApplication()->input->get('filters', [], 'array');

		$inputFilter = JFilterInput::getInstance();

		foreach ($filters as $k => $v)
		{
			// make string safe
			$filters[$k] = $inputFilter->clean($v, 'string');
		}

		return $filters;
	}

	/**
	 * Loads a list of employees to be displayed within the
	 * employees list site view.
	 *
	 * @param 	array  &$filters  An array of filters.
	 * @param 	array  &$options  An array of options, such as the ordering mode.
	 *
	 * @return 	array  A list of employees.
	 */
	public function getItems(array &$filters = array(), array &$options = array())
	{
		// always reset pagination and total count
		$this->pagination = null;
		$this->total      = 0;

		// validate filters and options
		$this->validateRequest($filters, $options);

		$dispatcher = VAPFactory::getEventDispatcher();

		$dbo = JFactory::getDbo();

		$employees = array();

		$q = $dbo->getQuery(true);

		if (empty($options['locations']))
		{
			// extended select
			$q->select('SQL_CALC_FOUND_ROWS e.*');
			$q->select($dbo->qn('eg.name', 'group_name'));
			$q->select($dbo->qn('eg.description', 'group_description'));
			$q->select('(' . $this->getRatingQuery($dbo) . ') AS ' . $dbo->qn('ratingAVG'));
			$q->select('(' . $this->getReviewsQuery($dbo) . ') AS ' . $dbo->qn('reviewsCount'));

			// group by employee
			$q->group($dbo->qn('e.id'));
		}
		else
		{
			// minified select
			$q->select($dbo->qn(array('e.id', 'e.nickname')));
			$q->select(array(
				$dbo->qn('l.id', 'id_location'),
				$dbo->qn('l.latitude'),
				$dbo->qn('l.longitude'),
				$dbo->qn('l.name', 'locname'),
				$dbo->qn('l.address', 'locaddress'),
				$dbo->qn('l.zip', 'loczip'),
				$dbo->qn('l.id_employee'),
			));

			// group by location
			$q->group($dbo->qn('l.id'));
		}

		$q->from($dbo->qn('#__vikappointments_employee', 'e'));
		$q->leftjoin($dbo->qn('#__vikappointments_employee_group', 'eg') . ' ON ' . $dbo->qn('e.id_group') . ' = ' . $dbo->qn('eg.id'));

		if (!empty($filters['employee_group']))
		{
			$q->where($dbo->qn('e.id_group') . ' = ' . (int) $filters['employee_group']);
		}

		// take only listable employees
		$q->where($dbo->qn('e.listable') . ' = 1');
		// take only those employees with lifetime license or that are not expired
		$q->andWhere(array(
			$dbo->qn('e.active_to') . ' = -1',
			$dbo->qn('e.active_to_date') . ' >= ' . $dbo->q(JFactory::getDate()->toSql()),
		), 'OR');

		// apply filters to query
		$this->buildQueryFilters($q, $filters, $options, $dbo);

		// apply the given ordering
		switch ($options['ordering'])
		{
			case 1:
				// alphabetically a..Z
				$q->order(array(
					$dbo->qn('e.lastname') . ' ASC',
					$dbo->qn('e.firstname') . ' ASC',
				));
				break;

			case 2:
				// alphabetically Z..a
				$q->order(array(
					$dbo->qn('e.lastname') . ' DESC',
					$dbo->qn('e.firstname') . ' DESC',
				));
				break;

			case 3:
				// newest 
				$q->order($dbo->qn('e.id') . ' DESC');
				break;

			case 4:
				// oldest 
				$q->order($dbo->qn('e.id') . ' ASC');
				break;

			case 5:
				// most popular
				$q->leftjoin($dbo->qn('#__vikappointments_reservation', 'r') . ' ON ' . $dbo->qn('e.id') . ' = ' . $dbo->qn('r.id_employee'));
				$q->order('COUNT(' . $dbo->qn('r.id') . ') DESC');
				break;

			case 6:
				// highest rating 
				$q->order(array(
					$dbo->qn('ratingAVG') . ' DESC',
					$dbo->qn('reviewsCount') . ' DESC',
				));
				break;

			case 7:
				// lowest price
				$q->order($dbo->qn('a.rate') . ' ASC');
				break;

			case 8:
				// highest price
				$q->order($dbo->qn('a.rate') . ' DESC');
				break;
		}

		/**
		 * Trigger hook to manipulate the query at runtime. Third party plugins
		 * can extend the query by applying further conditions or selecting
		 * additional data.
		 *
		 * @param 	mixed  &$query    Either a query builder or a query string.
		 * @param 	array  $filters   An array of filters.
		 * @param 	array  &$options  An array of options.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onBuildEmployeesListQuery', array(&$q, $filters, &$options));
		
		$dbo->setQuery($q, $options['start'], $options['limit']);

		if ($rows = $dbo->loadObjectList())
		{
			// fetch pagination
			$this->getPagination($filters, $options);

			$employees = $this->buildEmployeesData($rows, $filters, $options);
		}

		if (empty($options['locations']))
		{
			// translate employees
			$this->translate($employees);
		}

		/**
		 * Trigger hook to manipulate the query response at runtime. Third party
		 * plugins can alter the resulting list of employees.
		 *
		 * @param 	array   &$employees  An array of fetched employees.
		 * @param 	JModel  $model       The current model.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onBuildEmployeesListData', array(&$employees, $this));

		return $employees;
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
				/**
				 * Appends only filters that own a value as it doesn't
				 * make sense to populate the URL using empty variables.
				 *
				 * @since 1.6.2
				 */
				if ($v)
				{
					$this->pagination->setAdditionalUrlParam("filters[$k]", $v);
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

	/**
	 * Loads the groups that contain at least an employee.
	 *
	 * @return 	array  The groups.
	 */
	public function getGroups()
	{	
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('g.id', 'g.name')))
			->from($dbo->qn('#__vikappointments_employee_group', 'g'))
			->order($dbo->qn('g.ordering') . ' ASC');

		// create a new inner query to count
		// the employees assigned to the groups
		$inner = $dbo->getQuery(true)
			->select('COUNT(1)')
			->from($dbo->qn('#__vikappointments_employee', 'e'))
			->where(array(
				$dbo->qn('e.id_group') . ' = ' . $dbo->qn('g.id'),
			));

		// add inner query to obtain the number of employees assigned to this group
		$q->select('(' . $inner . ') AS ' . $dbo->qn('count'));
		// get only the groups that own at least an employee
		$q->having($dbo->qn('count') . ' > 0');

		$dbo->setQuery($q);
		
		// load groups
		$groups = $dbo->loadObjectList();

		if (!$groups)
		{
			return array();
		}

		/**
		 * Ignore translation in case the multilingual feature is disabled.
		 * 
		 * DO NOT move the groups translations within the `translate` method provided by this
		 * model because other classes might want to retrieve the groups. That's why the 
		 * translation process has been merged within the method used to fetch them.
		 * 
		 * @since 1.7.4
		 */
		if (VAPFactory::getConfig()->getBool('ismultilang'))
		{
			$langtag = JFactory::getLanguage()->getTag();

			// get translator
			$translator = VAPFactory::getTranslator();

			// get all group IDs
			$ids = array_map(function($g)
			{
				return $g->id;
			}, $groups);

			// pre-load employees groups translations
			$groupLang = $translator->load('empgroup', array_unique($ids), $langtag);

			foreach ($groups as $k => $g)
			{
				// translate group for the given language
				$grp_tx = $groupLang->getTranslation($g->id, $langtag);

				if ($grp_tx)
				{
					$groups[$k]->name        = $grp_tx->name;
					$groups[$k]->description = $grp_tx->description;
				}
			}
		}

		return $groups;
	}

	/**
	 * Calculates the resulting availability timeline according
	 * to the specified search options.
	 *
	 * @param 	array 	$options  An array of options.
	 *
	 * @return 	mixed 	The resulting renderer.
	 */
	public function getTimeline($options)
	{
		VAPLoader::import('libraries.availability.manager');
		VAPLoader::import('libraries.availability.timeline.factory');

		if (VAPDateHelper::isNull($options['date']))
		{
			// use current date if not specified
			$options['date'] = JFactory::getDate()->format('Y-m-d');
		}

		if ($options['id_ser'] <= 0)
		{
			// get the first available service assigned to the specified employee
			$service = $this->getFirstAvailableService($options['id_emp'], $options['date']);

			if ($service)
			{
				// update the service ID with the one found
				$options['id_ser'] = $service->id;

				if (empty($options['people']) && $service->min_per_res > 1)
				{
					// update also the number of guests to be compliant with the
					// configuration of the service
					$options['people'] = $service->min_per_res;
				}
			}
		}

		if (empty($options['people']))
		{
			// use default number of participants
			$options['people'] = 1;
		}

		// create availability search instance
		$search = VAPAvailabilityManager::getInstance($options['id_ser'], $options['id_emp'], $options);

		try
		{
			// create timeline parser instance
			$parser = VAPAvailabilityTimelineFactory::getParser($search);
		}
		catch (Exception $e)
		{
			// register exception as error
			$this->setError($e);

			return false;
		}

		$table = array();

		/**
		 * This value determines the number of columns 
		 * to show within the table. The value should be
		 * in the range of [2-5].
		 *
		 * @var integer
		 */
		$num_iter = 4;

		/**
		 * Trigger hook to alter the number of columns to display within the timeline displayed by the employees list page.
		 *
		 * @param 	integer  $columns  The default number of columns.
		 * @param 	array    $options  An array of search options.
		 *
		 * @return 	integer  The number of columns to display. It is suggested to use a value between 2 and 5.
		 *
		 * @since 	1.7
		 */
		$result = VAPFactory::getEventDispatcher()->numbers('onCountTimesTableColumns', array($num_iter, $options));

		if ($result)
		{
			// override default amount (take the first returned value)
			$num_iter = (int) abs($result[0]);
		}

		// create date instance
		$date = new JDate($options['date']);

		for ($i = 1; $i <= $num_iter; $i++)
		{
			// elaborate timeline
			$timeline = $parser->getTimeline($date->format('Y-m-d'), $options['people']);

			// register day timeline
			$table[$date->format('Y-m-d')] = $timeline;

			// go to next day
			$date->modify('+1 day');
		}

		try
		{
			// create timeline renderer instance
			$renderer = VAPAvailabilityTimelineFactory::getRenderer($table, 'table');
		}
		catch (Exception $e)
		{
			// register exception as error
			$this->setError($e);

			return false;
		}

		return $renderer; 
	}

	/**
	 * Returns the first available service assigned to the
	 * given employee.
	 *
	 * @param 	integer  $id_emp  The employee ID.
	 * @param 	string 	 $date    The check-in date.
	 * 
	 * @return 	mixed    The service object on success, null otherwise.
	 */
	public function getFirstAvailableService($id_emp, $date)
	{
		// get all published services assigned to this employee
		$services = JModelVAP::getInstance('employee')->getServices($id_emp, $strict = true);

		if (!$services)
		{
			// no available services
			return null;
		}

		VAPLoader::import('libraries.availability.manager');
		// create search instance
		$search = VAPAvailabilityManager::getInstance(0, $id_emp);

		foreach ($services as $service)
		{
			// update service ID
			$search->set('id_service', $service->id);

			// make sure the service is published
			if ($search->isServicePublished($date))
			{
				// get service details
				return JModelVAP::getInstance('serempassoc')->getOverrides($service->id, $id_emp);
			}
		}

		return null;
	}

	////////////////////////////////////////
	//////////// HELPER METHODS ////////////
	////////////////////////////////////////

	/**
	 * Validates the selected ordering against the supported listing modes
	 * and filters set in request.
	 *
	 * @param 	array  $filters   An array of filters.
	 * @param 	array  &$options  An array of options, such as the ordering mode.
	 *
	 * @return 	self   This object to support chaining.
	 */
	protected function validateRequest(array &$filters = array(), array &$options = array())
	{
		// load all supported types of ordering
		$available_orderings = VikAppointments::getEmployeesAvailableOrderings();
		$default_ordering    = VikAppointments::getEmployeesListingMode();

		// make sure there's a sorting more and it is supported
		if (empty($options['ordering']) || !in_array($options['ordering'], $available_orderings))
		{
			// empty or not supported, use the default one
			$options['ordering'] = $default_ordering;
		}

		// check if the service is set in the request
		if (!isset($filters['service']))
		{
			// since the service is not specified, we cannot sort
			// the employees depending on the given rate
			if ($options['ordering'] == 7 || $options['ordering'] == 8)
			{
				// use the default one
				$options['ordering'] = $default_ordering;
			}

			// if the listing mode is still set to "price",
			// it means that the default mode cannot be used
			if ($options['ordering'] == 7 || $options['ordering'] == 8)
			{
				// fallback to a..Z
				$options['ordering'] = 1;
			}
		}

		if (!array_key_exists('start', $options))
		{
			// start from the beginning
			$options['start'] = 0;
		}

		if (!array_key_exists('limit', $options))
		{
			// use the default configuration limit
			$options['limit'] = VAPFactory::getConfig()->getUint('emplistlim');
		}

		return $this;
	}

	/**
	 * Returns the inner query that should be used to calculate the
	 * average rating of the employees.
	 *
	 * @param 	mixed 	$dbo  The database object.
	 *
	 * @return 	mixed 	The database query.
	 */
	protected function getRatingQuery($dbo)
	{
		return $dbo->getQuery(true)
			->select('AVG(' . $dbo->qn('re.rating') . ')')
			->from($dbo->qn('#__vikappointments_reviews', 're'))
			->where(array(
				$dbo->qn('e.id') . ' = ' . $dbo->qn('re.id_employee'),
				$dbo->qn('re.published') . ' = 1',
			));
	}

	/**
	 * Returns the inner query that should be used to calculate the
	 * number of reviews of the employees.
	 *
	 * @param 	mixed 	$dbo  The database object.
	 *
	 * @return 	mixed 	The database query.
	 */
	protected function getReviewsQuery($dbo)
	{
		return $dbo->getQuery(true)
			->select('COUNT(' . $dbo->qn('re.rating') . ')')
			->from($dbo->qn('#__vikappointments_reviews', 're'))
			->where(array(
				$dbo->qn('e.id') . ' = ' . $dbo->qn('re.id_employee'),
				$dbo->qn('re.published') . ' = 1',
			));
	}

	/**
	 * Extends the search query by applying the filters set.
	 *
	 * @param 	mixed 	&$q        The query builder object.
	 * @param 	array 	&$filters  The associative array of filters.
	 * @param 	array 	$options   The associative array of options.
	 * @param 	mixed 	$dbo       The database object.
	 *
	 * @return 	self 	This object to support chaining.
	 */
	protected function buildQueryFilters(&$q, array &$filters, $options, $dbo)
	{
		$locations_table_loaded = false;

		if (!empty($options['locations']))
		{
			// extend query by loading the employee working times
			$q->leftjoin($dbo->qn('#__vikappointments_emp_worktime', 'w') . ' ON ' . $dbo->qn('w.id_employee') . ' = ' . $dbo->qn('e.id'));
			// extend query by loading the employee locations
			$q->leftjoin($dbo->qn('#__vikappointments_employee_location', 'l') . ' ON ' . $dbo->qn('w.id_location') . ' = ' . $dbo->qn('l.id'));

			// take only locations with specified coordinates
			$q->where($dbo->qn('l.latitude') . ' IS NOT NULL');

			$locations_table_loaded = true;
		}

		if (!empty($filters['group']) || !empty($filters['service']) || !empty($filters['price']))
		{
			// extend query by fetching the assigned services
			$q->leftjoin($dbo->qn('#__vikappointments_ser_emp_assoc', 'a') . ' ON ' . $dbo->qn('a.id_employee') . ' = ' . $dbo->qn('e.id'));
			$q->leftjoin($dbo->qn('#__vikappointments_service', 's') . ' ON ' . $dbo->qn('a.id_service') . ' = ' . $dbo->qn('s.id'));
			
			if (!empty($filters['group']))
			{
				// filter by service group
				$q->where($dbo->qn('s.id_group') . ' = ' . (int) $filters['group']);
			}

			if (!empty($filters['service']))
			{
				// filter by service and select the rate
				$q->select($dbo->qn('a.rate'));
				$q->where($dbo->qn('s.id') . ' = ' . (int) $filters['service']);

				if (!empty($options['locations']))
				{
					/**
					 * Filter the working days by service to ignore the locations assigned
					 * to the working days that don't match the specified service.
					 *
					 * @since 1.7
					 */
					$q->where($dbo->qn('w.id_service') . ' = ' . (int) $filters['service']);
				}

				/**
				 * Retrieve only the services that belong to the view
				 * access level of the current user.
				 *
				 * @since 1.6
				 */
				$levels = JFactory::getUser()->getAuthorisedViewLevels();

				if ($levels)
				{
					$q->where($dbo->qn('s.level') . ' IN (' . implode(', ', $levels) . ')');
				}
			}

			if (!empty($filters['price']))
			{
				// price set in request, extract the given range
				$range = explode(':', $filters['price']);

				if (count($range) != 2)
				{
					$range = array(0, 0);
				}
				else
				{
					$range = array_map('intval', $range);
				}

				// filter by price range
				$q->where($dbo->qn('a.rate') . ' BETWEEN ' . implode(' AND ', $range));
			}
		}
		
		if (!empty($filters['country']) || !empty($filters['state']) || !empty($filters['city'])
			|| !empty($filters['zip']) || !empty($filters['nearby']) || !empty($filters['id_location']))
		{
			if (!$locations_table_loaded)
			{
				// extend query by loading the employee locations
				$q->leftjoin($dbo->qn('#__vikappointments_emp_worktime', 'w') . ' ON ' . $dbo->qn('w.id_employee') . ' = ' . $dbo->qn('e.id'));
				$q->leftjoin($dbo->qn('#__vikappointments_employee_location', 'l') . ' ON ' . $dbo->qn('w.id_location') . ' = ' . $dbo->qn('l.id'));
			}

			if (empty($filters['nearby']))
			{
				if (!empty($filters['country']))
				{
					// filter by country ID
					$q->where($dbo->qn('l.id_country') . ' = ' . (int) $filters['country']);
				}

				if (!empty($filters['state']))
				{
					// filter by state/province ID
					$q->where($dbo->qn('l.id_state') . ' = ' . (int) $filters['state']);
				}

				if (!empty($filters['city']))
				{
					// filter by city ID
					$q->where($dbo->qn('l.id_city') . ' = ' . (int) $filters['city']);
				}

				if (!empty($filters['zip']))
				{
					// filter by ZIP code
					$q->where($dbo->qn('l.zip') . ' = ' . $dbo->q($filters['zip']));
				}
			}
			// make sure the browser has been authorised to obtain the coordinates
			else if (!empty($filters['base_coord']))
			{
				// get query for geodetica
				$distance = (int) $filters['distance'];
				$coord    = explode(',', $filters['base_coord']);
				
				if (count($coord) < 2)
				{
					// Invalid coordinates... Use dummy data to
					// avoid breaking the query.
					$coord = array(0, 0);
				}

				list($lat, $lng) = array_map('floatval', $coord);

				/**
				 * Convert distance to km for query as the Earth radius
				 * is specified in kilometers.
				 *
				 * @since 1.6
				 */
				$distance = VikAppointments::convertDistanceToKilometers($distance, $filters);

				// apply nearby query
				$q->where($this->getNearbyWhereQuery($lat, $lng, $distance));

				// copy coordinates within filters array
				$filters['latitude']  = $lat;
				$filters['longitude'] = $lng;
			}

			/**
			 * Filter the employees also by location ID.
			 *
			 * @since 1.6
			 */
			if (!empty($filters['id_location']))
			{
				$q->where($dbo->qn('w.id_location') . ' = ' . (int) $filters['id_location']);
			}
		}

		/**
		 * When specified, take only the given employee.
		 *
		 * @since 1.7
		 */
		if (!empty($filters['id_employee']))
		{
			$q->where($dbo->qn('e.id') . ' = ' . (int) $filters['id_employee']);
		}
		
		/**
		 * Extend query by using the employees custom fields.
		 *
		 * @since 1.6
		 */
		return $this->extendWithCustomFilters($q, $filters, $dbo);
	}

	/**
	 * Returns the WHERE statement used to filter the employees
	 * in the nearby of the specified coordinates.
	 *
	 * @param 	float 	 $lat 		The center latitude.
	 * @param 	float 	 $lng 		The center longitude.
	 * @param 	integer  $distance 	The circle radius (in km).
	 *
	 * @return 	string 	 The query WHERE statement.
	 */
	protected function getNearbyWhereQuery($lat, $lng, $distance)
	{
		$lat = $lat * pi() / 180.0;
		$lng = $lng * pi() / 180.0;

		/** 
		 * Distance between 2 coordinates.
		 *
		 * R = 6371 (Earth radius ~6371 km)
		 *
		 * Coordinates in radians
		 * lat1, lng1, lat2, lng2
		 *
		 * Calculate the included angle fi
		 * fi = abs( lng1 - lng2 );
		 *
		 * Calculate the third side of the spherical triangle
		 * p = acos( 
		 *      sin(lat2) * sin(lat1) + 
		 *      cos(lat2) * cos(lat1) * 
		 *      cos( fi ) 
		 * )
		 * 
		 * Multiply the third side per the Earth radius (distance in km)
		 * D = p * R;
		 *
		 * MINIFIED EXPRESSION
		 *
		 * acos( 
		 *      sin(lat2) * sin(lat1) + 
		 *      cos(lat2) * cos(lat1) * 
		 *      cos( abs(lng1 - lng2) ) 
		 * ) * R
		 */

		return "`l`.`latitude` IS NOT NULL AND (ACOS(
				SIN(RADIANS(`l`.`latitude`)) * SIN($lat) +
				COS(RADIANS(`l`.`latitude`)) * COS($lat) *
				COS(ABS($lng - RADIANS(`l`.`longitude`)))
			) * 6371) < $distance";
	}

	/**
	 * Extends the search query using the custom filters.
	 *
	 * @param 	mixed  &$q       The query builder object.
	 * @param 	array  $filters  The associative array of filters.
	 * @param 	mixed  $dbo      The database object.
	 *
	 * @return 	self   This object to support chaining.
	 */
	protected function extendWithCustomFilters(&$q, array $filters, $dbo)
	{
		$lookup = array();

		foreach ($filters as $k => $v)
		{
			if ($v && strpos($k, 'field_') === 0)
			{
				$lookup[] = substr($k, 6);
			}
		}

		if (!$lookup)
		{
			// no custom filters
			return $this;
		}

		$lookup = array_map(array($dbo, 'q'), $lookup);

		$q2 = $dbo->getQuery(true)
			->select($dbo->qn('formname'))
			->from($dbo->qn('#__vikappointments_custfields'))
			->where(array(
				$dbo->qn('group') . ' = 1',
				$dbo->qn('formname') . ' IN (' . implode(',', $lookup) . ')',
			));

		$dbo->setQuery($q2);
		$fields = $dbo->loadColumn();

		if (!$fields)
		{
			// no custom fields, possible hack attempt
			return $this;
		}

		foreach ($fields as $field)
		{
			$key = 'field_' . $field;

			$q->where($dbo->qn('e.' . $key) . ' = ' . $dbo->q($filters[$key]));
		}

		return $this;
	}

	/**
	 * Applies additional queries to fill the employees list
	 * with other data, such as the supported locations.
	 *
	 * @param 	array  $list     The employees list.
	 * @param 	array  $filters  An array of filters.
	 * @param 	array  $options  An array of options.
	 *
	 * @return 	array  The resulting employees list.
	 */
	protected function buildEmployeesData($list, $filters, $options)
	{
		if (!empty($options['locations']))
		{
			// do not need to manipulate the fetched rows
			return $list;
		}

		$employees = array();

		// get employee search model
		$empSearchModel = JModelVAP::getInstance('employeesearch');

		// check if we have a service within the filters
		if (!empty($filters['service']))
		{
			$id_service = (int) $filters['service'];
		}
		else
		{
			$id_service = 0;
		}

		foreach ($list as $e)
		{
			// round rating to the closest .0 or .5
			$e->rating = VikAppointments::roundHalfClosest($e->ratingAVG);

			// fetch employee locations (and filter by service if needed)
			$e->locations = $empSearchModel->getLocations($e->id, $id_service);

			if ($e->id_group)
			{
				// register group data in a different object
				$group = new stdClass;
				$group->id          = $e->id_group;
				$group->name        = $e->group_name;
				$group->description = $e->group_description;

				$e->group = $group;
			}
			else
			{
				// no assigned group
				$e->group = null;
			}

			// get rid of duplicates
			unset($e->id_group);
			unset($e->group_name);
			unset($e->group_description);

			$employees[] = $e;
		}

		return $employees;
	}

	/**
	 * Translates the groups and the employees.
	 *
	 * @param 	array  &$rows  The rows to translate.
	 *
	 * @return 	void
	 */
	protected function translate(&$rows)
	{
		/**
		 * Ignore translation in case the multilingual feature is disabled.
		 * 
		 * @since 1.7.4
		 */
		if (VAPFactory::getConfig()->getBool('ismultilang') == false)
		{
			return;
		}

		$langtag = JFactory::getLanguage()->getTag();

		// get translator
		$translator = VAPFactory::getTranslator();

		$employee_ids = array();
		$group_ids    = array();

		foreach ($rows as $employee)
		{
			$employee_ids[] = $employee->id;

			if ($employee->group)
			{
				$group_ids[] = $employee->group->id;
			}
		}

		// pre-load employees translations
		$empLang = $translator->load('employee', array_unique($employee_ids), $langtag);
		// pre-load employees groups translations
		$groupLang = $translator->load('empgroup', array_unique($group_ids), $langtag);

		foreach ($rows as $k => $employee)
		{
			// translate employee for the given language
			$emp_tx = $empLang->getTranslation($employee->id, $langtag);

			if ($emp_tx)
			{
				$rows[$k]->nickname = $emp_tx->nickname;
				$rows[$k]->note     = $emp_tx->note;
			}

			if ($employee->group)
			{
				// translate group for the given language
				$grp_tx = $groupLang->getTranslation($employee->group->id, $langtag);

				if ($grp_tx)
				{
					$rows[$k]->group->name        = $grp_tx->name;
					$rows[$k]->group->description = $grp_tx->description;
				}
			}
		}
	}
}
