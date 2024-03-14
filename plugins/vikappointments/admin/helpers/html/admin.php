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
 * VikAppointments HTML admin helper.
 *
 * @since 1.7
 */
abstract class VAPHtmlAdmin
{
	/**
	 * Method to sort a column in a grid.
	 *
	 * @param   string  $title          The link title.
	 * @param   string  $order          The order field for the column.
	 * @param   string  $direction      The current direction.
	 * @param   string  $selected       The selected ordering.
	 * @param   string  $task           An optional task override.
	 * @param   string  $new_direction  An optional direction for the new column.
	 * @param   string  $tip            An optional text shown as tooltip title instead of $title.
	 * @param   string  $form           An optional form selector.
	 *
	 * @return  string
	 */
	public static function sort($title, $order, $direction = 'asc', $selected = '', $task = null, $new_direction = 'asc', $tip = '', $form = null)
	{
		if (!$tip && preg_match("/ordering$/i", $order))
		{
			// force "Ordering" as tooltip in order to avoid
			// display the HTML of the icon
			$tip = 'JGRID_HEADING_ORDERING';
		}

		// render grid HTML
		$html = JHtml::fetch('grid.sort', $title, $order, $direction, $selected, $task, $new_direction, $tip, $form);

		// turn off tooltip or popover
		$html = preg_replace("/\bhas(?:Tooltip|Popover)\b/", '', $html);

		return $html;
	}

	/**
	 * Method to sort a column in a grid without using a native function.
	 *
	 * @param   string  $title          The link title.
	 * @param   string  $order          The order field for the column.
	 * @param   string  $direction      The current direction.
	 * @param   string  $selected       The selected ordering.
	 * @param   string  $new_direction  An optional direction for the new column.
	 *
	 * @return  string
	 */
	public static function customsort($title, $order, $direction = 'asc', $selected = '', $new_direction = 'asc')
	{
		// use sort to get HTML
		$html = static::sort($title, $order, $direction, $selected, $new_direction);

		if ($order == $selected)
		{
			// in case the ordering was already selected, reverse direction
			$direction = $direction == 'asc' ? 'desc' : 'asc';
		}
		else
		{
			// in case the ordering is not selected, use the default new direction
			$direction = $new_direction;
		}

		// replace onclick attribute with the specified ordering data
		$html = preg_replace("/\bonclick=\"(.*?)\"/i", 'data-order-col="' . $order . '" data-order-dir="' . $direction . '"', $html);

		return $html;
	}

	/**
	 * Returns a list of supported groups.
	 *
	 * @param 	integer  $type   The group type (1 for services, 2 for employees).
	 * @param 	mixed    $blank  True to include an empty option. Use a string to
	 *                           specify the placeholder of the empty option.
	 *
	 * @return 	array 	 A list of groups.
	 */
	public static function groups($type, $blank = false)
	{
		$options = array();

		if ($blank !== false)
		{
			$blank = is_string($blank) ? $blank : JText::translate('VAPFILTERSELECTGROUP');

			$options[] = JHtml::fetch('select.option', '', $blank);
		}

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);
		$q->select($dbo->qn(array('id', 'name')));
		$q->order($dbo->qn('ordering') . ' ASC');

		if ($type == 1)
		{
			$q->from($dbo->qn('#__vikappointments_group'));
		}
		else
		{
			$q->from($dbo->qn('#__vikappointments_employee_group'));
		}

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $group)
		{
			$options[] = JHtml::fetch('select.option', $group->id, $group->name);
		}

		return $options;
	}

	/**
	 * Returns a list of supported employees.
	 *
	 * @param 	boolean  $strict  True to fetch only the published employees.
	 * @param 	mixed    $blank   True to include an empty option. Use a string to
	 *                            specify the placeholder of the empty option.
	 * @param 	boolean  $group   True to group the options by status.
	 *
	 * @return 	array 	 A list of employees.
	 */
	public static function employees($strict = false, $blank = false, $group = false)
	{
		$options = array();

		if ($blank !== false)
		{
			$blank = is_string($blank) ? $blank : JText::translate('VAPFILTERSELECTEMPLOYEE');

			$options[] = JHtml::fetch('select.option', '', $blank);

			if ($group)
			{
				$options[0] = array($options[0]);
			}
		}

		$no_group = JText::translate('VAPSERVICENOGROUP');

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);
		$q->select($dbo->qn(array('e.id', 'e.nickname')));
		$q->from($dbo->qn('#__vikappointments_employee', 'e'));

		if ($group)
		{
			$q->select($dbo->qn('g.name', 'group_name'));
			$q->leftjoin($dbo->qn('#__vikappointments_employee_group', 'g') . ' ON ' . $dbo->qn('g.id') . ' = ' . $dbo->qn('e.id_group'));
			// sort employees by group first
			$q->order($dbo->qn('g.ordering') . ' ASC');
		}

		$q->order($dbo->qn('e.nickname') . ' ASC');

		if ($strict)
		{
			$q->where($dbo->qn('e.listable') . ' = 1');
		}

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $employee)
		{
			$opt = JHtml::fetch('select.option', $employee->id, $employee->nickname);

			if ($group)
			{
				// create group key
				$key = $employee->group_name ? $employee->group_name : $no_group;

				// create group if not exists
				if (!isset($options[$key]))
				{
					$options[$key] = array();
				}

				// add within group
				$options[$key][] = $opt;
			}
			else
			{
				// add at first level
				$options[] = $opt;
			}
		}

		if ($group && isset($options[$no_group]))
		{
			// always move employees without group at the end of the list
			$tmp = $options[$no_group];
			unset($options[$no_group]);
			$options[$no_group] = $tmp;
		}

		return $options;
	}

	/**
	 * Returns a list of supported services.
	 *
	 * @param 	boolean  $strict  True to fetch only the published services.
	 * @param 	mixed    $blank   True to include an empty option. Use a string to
	 *                            specify the placeholder of the empty option.
	 * @param 	boolean  $group   True to group the options by status.
	 *
	 * @return 	array 	 A list of services.
	 */
	public static function services($strict = false, $blank = false, $group = false)
	{
		$options = array();

		if ($blank !== false)
		{
			$blank = is_string($blank) ? $blank : JText::translate('VAPFILTERSELECTSERVICE');

			$options[] = JHtml::fetch('select.option', '', $blank);

			if ($group)
			{
				$options[0] = array($options[0]);
			}
		}

		$no_group = JText::translate('VAPSERVICENOGROUP');

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);
		$q->select($dbo->qn(array('s.id', 's.name')));
		$q->from($dbo->qn('#__vikappointments_service', 's'));

		if ($group)
		{
			$q->select($dbo->qn('g.name', 'group_name'));
			$q->leftjoin($dbo->qn('#__vikappointments_group', 'g') . ' ON ' . $dbo->qn('g.id') . ' = ' . $dbo->qn('s.id_group'));
			// sort services by group first
			$q->order($dbo->qn('g.ordering') . ' ASC');
			$q->order($dbo->qn('s.ordering') . ' ASC');
		}
		else
		{
			$q->order($dbo->qn('s.name') . ' ASC');
		}

		if ($strict)
		{
			$q->where($dbo->qn('s.published') . ' = 1');
		}

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $service)
		{
			$opt = JHtml::fetch('select.option', $service->id, $service->name);

			if ($group)
			{
				// create group key
				$key = $service->group_name ? $service->group_name : $no_group;

				// create group if not exists
				if (!isset($options[$key]))
				{
					$options[$key] = array();
				}

				// add within group
				$options[$key][] = $opt;
			}
			else
			{
				// add at first level
				$options[] = $opt;
			}
		}

		if ($group && isset($options[$no_group]))
		{
			// always move services without group at the end of the list
			$tmp = $options[$no_group];
			unset($options[$no_group]);
			$options[$no_group] = $tmp;
		}

		return $options;
	}

	/**
	 * Returns a list of supported option groups.
	 *
	 * @param 	mixed    $blank  True to include an empty option. Use a string to
	 *                           specify the placeholder of the empty option.
	 *
	 * @return 	array 	 A list of option groups.
	 */
	public static function optiongroups($blank = false)
	{
		$options = array();

		if ($blank !== false)
		{
			$blank = is_string($blank) ? $blank : JText::translate('VAPFILTERSELECTGROUP');

			$options[] = JHtml::fetch('select.option', '', $blank);
		}

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'name')))
			->from($dbo->qn('#__vikappointments_option_group'))
			->order($dbo->qn('ordering') . ' ASC');

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $group)
		{
			$options[] = JHtml::fetch('select.option', $group->id, $group->name);
		}

		return $options;
	}

	/**
	 * Returns a list of supported options.
	 *
	 * @param 	boolean  $strict  True to fetch only the published options.
	 * @param 	mixed    $blank   True to include an empty option. Use a string to
	 *                            specify the placeholder of the empty option.
	 * @param 	boolean  $group   True to group the options by status.
	 * @param 	mixed    $id_ser  An optional ID to load only the options assigned
	 *                            to the given service.
	 *
	 * @return 	array 	 A list of options.
	 */
	public static function options($strict = false, $blank = false, $group = false, $id_ser = null)
	{
		$options = array();

		if ($blank !== false)
		{
			$blank = is_string($blank) ? $blank : JText::translate('VAPFILTERSELECTOPTION');

			$options[] = JHtml::fetch('select.option', '', $blank);

			if ($group)
			{
				$options[0] = array($options[0]);
			}
		}

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);
		$q->select($dbo->qn(array('o.id', 'o.name', 'o.published')));
		$q->from($dbo->qn('#__vikappointments_option', 'o'));

		if ($id_ser)
		{
			// filter the options by service
			$q->from($dbo->qn('#__vikappointments_ser_opt_assoc', 'a'));
			$q->where($dbo->qn('a.id_option') . ' = ' . $dbo->qn('o.id'));
			$q->where($dbo->qn('a.id_service') . ' = ' . (int) $id_ser);
		}

		if ($group)
		{
			// take published items first when we need to group them
			$q->order($dbo->qn('o.published') . ' DESC');
		}

		$q->order($dbo->qn('o.ordering') . ' ASC');

		if ($strict)
		{
			$q->where($dbo->qn('o.published') . ' = 1');
		}

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $option)
		{
			$opt = JHtml::fetch('select.option', $option->id, $option->name);

			if ($group)
			{
				// create group key
				$key = JText::translate($option->published ? 'JPUBLISHED' : 'JUNPUBLISHED');

				// create status group if not exists
				if (!isset($options[$key]))
				{
					$options[$key] = array();
				}

				// add within group
				$options[$key][] = $opt;
			}
			else
			{
				// add at first level
				$options[] = $opt;
			}
		}

		return $options;
	}

	/**
	 * Returns a list of supported locations.
	 *
	 * @param 	mixed    $id_employee  The ID of the locations owner. Use null to ignore
	 *                                 this filter.
	 * @param 	mixed    $blank        True to include an empty option. Use a string to
	 *                                 specify the placeholder of the empty option.
	 * @param 	boolean  $group        True to group the locations by status.
	 *
	 * @return 	array 	 A list of locations.
	 */
	public static function locations($id_employee = 0, $blank = false, $group = false)
	{
		$options = array();

		if ($blank !== false)
		{
			$blank = is_string($blank) ? $blank : JText::translate('VAPFILTERSELECTLOCATION');

			$options[] = JHtml::fetch('select.option', '', $blank);

			if ($group)
			{
				$options[0] = array($options[0]);
			}
		}

		$dbo = JFactory::getDbo();

		// get employee/global locations

		$q = $dbo->getQuery(true);

		$q->select('l.*')
			->from($dbo->qn('#__vikappointments_employee_location', 'l'));
			
		$q->select($dbo->qn(array('c.country_name', 'c.country_2_code')))
			->leftjoin($dbo->qn('#__vikappointments_countries', 'c') . ' ON ' . $dbo->qn('c.id') . ' = ' . $dbo->qn('l.id_country'));

		$q->select($dbo->qn(array('s.state_name', 'state_2_code')))
			->leftjoin($dbo->qn('#__vikappointments_states', 's') . ' ON ' . $dbo->qn('s.id') . ' = ' . $dbo->qn('l.id_state'));

		$q->select($dbo->qn(array('ci.city_name', 'ci.city_2_code')))
			->leftjoin($dbo->qn('#__vikappointments_cities', 'ci') . ' ON ' . $dbo->qn('ci.id') . ' = ' . $dbo->qn('l.id_city'));

		if ($id_employee !== null)
		{
			$q->where(array(
				$dbo->qn('l.id_employee') . ' = ' . (int) $id_employee,
				$dbo->qn('l.id_employee') . ' <= 0',
			), 'OR');
		}

		// load first the locations of the employee
		$q->order($dbo->qn('l.id_employee') . ' DESC');
		$q->order($dbo->qn('l.name') . ' ASC');

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $l)
		{
			$code = $l->city_name;

			if (empty($code))
			{
				$code = $l->state_2_code;

				if (empty($code))
				{
					$code = $l->country_2_code;
				}
			}

			// create dropdown option
			$opt = JHtml::fetch('select.option', $l->id, $l->name . " ({$l->address}, {$code})");
			
			if ($group)
			{
				// create group key
				$key = $l->id_employee > 0 ? 0 : JText::translate('VAPMENUTITLEHEADER3');

				// create group if not exists
				if (!isset($options[$key]))
				{
					$options[$key] = array();
				}

				// add within group
				$options[$key][] = $opt;
			}
			else
			{
				// add at first level
				$options[] = $opt;
			}
		}

		return $options;
	}

	/**
	 * Returns a list of supported packages groups.
	 *
	 * @param 	mixed    $blank  True to include an empty option. Use a string to
	 *                           specify the placeholder of the empty option.
	 *
	 * @return 	array 	 A list of packages groups.
	 */
	public static function packgroups($blank = false)
	{
		$options = array();

		if ($blank !== false)
		{
			$blank = is_string($blank) ? $blank : JText::translate('VAPFILTERSELECTGROUP');

			$options[] = JHtml::fetch('select.option', '', $blank);
		}

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'title')))
			->from($dbo->qn('#__vikappointments_package_group'))
			->order($dbo->qn('ordering') . ' ASC');

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $group)
		{
			$options[] = JHtml::fetch('select.option', $group->id, $group->title);
		}

		return $options;
	}

	/**
	 * Returns a list of supported packages.
	 *
	 * @param 	boolean  $strict  True to fetch only the published packages.
	 * @param 	mixed    $blank   True to include an empty option. Use a string to
	 *                            specify the placeholder of the empty option.
	 * @param 	boolean  $group   True to group the packages.
	 *
	 * @return 	array 	 A list of packages.
	 */
	public static function packages($strict = false, $blank = false, $group = false)
	{
		$options = array();

		if ($blank !== false)
		{
			$blank = is_string($blank) ? $blank : JText::translate('VAPFILTERSELECTPACKAGE');

			$options[] = JHtml::fetch('select.option', '', $blank);

			if ($group)
			{
				$options[0] = array($options[0]);
			}
		}

		$no_group = JText::translate('VAPSERVICENOGROUP');

		$dbo = JFactory::getDbo();
		$config = VAPFactory::getConfig();

		$q = $dbo->getQuery(true);
		$q->select($dbo->qn(array('p.id', 'p.name', 'p.price', 'p.num_app', 'p.validity')));
		$q->from($dbo->qn('#__vikappointments_package', 'p'));

		if ($group)
		{
			$q->select($dbo->qn('g.title', 'group_name'));
			$q->leftjoin($dbo->qn('#__vikappointments_package_group', 'g') . ' ON ' . $dbo->qn('g.id') . ' = ' . $dbo->qn('p.id_group'));
			// sort packages by group first
			$q->order($dbo->qn('g.ordering') . ' ASC');
		}

		$q->order($dbo->qn('p.ordering') . ' ASC');

		if ($strict)
		{
			$q->where($dbo->qn('p.published') . ' = 1');
		}

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $package)
		{
			$opt = JHtml::fetch('select.option', $package->id, $package->name);

			// include package cost and appointments
			$opt->price    = $package->price;
			$opt->num_app  = $package->num_app;
			$opt->validity = $package->validity ? JHtml::fetch('date', '+' . $package->validity . ' days', $config->get('dateformat') . ' ' . $config->get('timeformat')) : '';

			// build option data
			$opt->data = 'data-price="' . $opt->price . '" data-num-app="' . $opt->num_app . '" data-validity="' . $opt->validity . '"';

			if ($group)
			{
				// create group key
				$key = $package->group_name ? $package->group_name : $no_group;

				// create group if not exists
				if (!isset($options[$key]))
				{
					$options[$key] = array();
				}

				// add within group
				$options[$key][] = $opt;
			}
			else
			{
				// add at first level
				$options[] = $opt;
			}
		}

		if ($group && isset($options[$no_group]))
		{
			// always move packages without group at the end of the list
			$tmp = $options[$no_group];
			unset($options[$no_group]);
			$options[$no_group] = $tmp;
		}

		return $options;
	}

	/**
	 * Returns a list of supported countries.
	 *
	 * @param 	string  $pk     The column to use as value. 
	 * @param 	mixed   $blank  True to include an empty option. Use a string to
	 *                          specify the placeholder of the empty option.
	 *
	 * @return 	array 	A list of countries.
	 */
	public static function countries($pk = 'id', $blank = false)
	{
		$options = array();

		if ($blank !== false)
		{
			$blank = is_string($blank) ? $blank : JText::translate('VAPFILTERSELECTCOUNTRY');

			$options[] = JHtml::fetch('select.option', '', $blank);
		}

		// iterate published countries
		foreach (VAPLocations::getCountries('country_name') as $country)
		{
			// fetch option value
			$value = isset($country[$pk]) ? $country[$pk] : $country['id'];

			$options[] = JHtml::fetch('select.option', $value, $country['country_name']);
		}

		return $options;
	}

	/**
	 * Returns a list of supported states.
	 *
	 * @param 	integer  $country  The country to which the states belong.
	 * @param 	string   $pk       The column to use as value. 
	 * @param 	mixed    $blank    True to include an empty option. Use a string to
	 *                             specify the placeholder of the empty option.
	 *
	 * @return 	array 	 A list of states.
	 */
	public static function states($country, $pk = 'id', $blank = false)
	{
		$options = array();

		if ($blank !== false)
		{
			$blank = is_string($blank) ? $blank : JText::translate('VAPFILTERSELECTSTATE');

			$options[] = JHtml::fetch('select.option', '', $blank);
		}

		if ($country > 0)
		{
			// iterate published states
			foreach (VAPLocations::getStates($country, 'state_name') as $state)
			{
				// fetch option value
				$value = isset($state[$pk]) ? $state[$pk] : $state['id'];

				$options[] = JHtml::fetch('select.option', $value, $state['state_name']);
			}
		}

		return $options;
	}

	/**
	 * Returns a list of supported cities.
	 *
	 * @param 	integer  $state  The state to which the cities belong.
	 * @param 	string   $pk     The column to use as value. 
	 * @param 	mixed    $blank  True to include an empty option. Use a string to
	 *                           specify the placeholder of the empty option.
	 *
	 * @return 	array 	 A list of cities.
	 */
	public static function cities($state, $pk = 'id', $blank = false)
	{
		$options = array();

		if ($blank !== false)
		{
			$blank = is_string($blank) ? $blank : JText::translate('VAPFILTERSELECTCITY');

			$options[] = JHtml::fetch('select.option', '', $blank);
		}

		if ($state > 0)
		{
			// iterate published cities
			foreach (VAPLocations::getCities($state, 'city_name') as $city)
			{
				// fetch option value
				$value = isset($city[$pk]) ? $city[$pk] : $city['id'];

				$options[] = JHtml::fetch('select.option', $value, $city['city_name']);
			}
		}

		return $options;
	}

	/**
	 * Returns a list of supported coupon groups.
	 *
	 * @param 	mixed    $blank  True to include an empty option. Use a string to
	 *                           specify the placeholder of the empty option.
	 *
	 * @return 	array 	 A list of coupon groups.
	 */
	public static function coupongroups($blank = false)
	{
		$options = array();

		if ($blank !== false)
		{
			$blank = is_string($blank) ? $blank : JText::translate('VAPFILTERSELECTGROUP');

			$options[] = JHtml::fetch('select.option', '', $blank);
		}

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'name')))
			->from($dbo->qn('#__vikappointments_coupon_group'))
			->order($dbo->qn('ordering') . ' ASC');

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $group)
		{
			$options[] = JHtml::fetch('select.option', $group->id, $group->name);
		}

		return $options;
	}

	/**
	 * Returns a list of supported coupon codes.
	 *
	 * @param 	mixed    $blank       True to include an empty option. Use a string to
	 *                                specify the placeholder of the empty option.
	 * @param 	boolean  $group       True to group the coupons.
	 * @param 	mixed    $applicable  The section to which the coupons are applicable.
	 *
	 * @return 	array 	 A list of coupons.
	 */
	public static function coupons($blank = false, $group = false, $applicable = null)
	{
		$options = array();

		if ($blank !== false)
		{
			$blank = is_string($blank) ? $blank : JText::translate('VAPFILTERSELECTCOUPON');

			$options[] = JHtml::fetch('select.option', '', $blank);

			if ($group)
			{
				$options[0] = array($options[0]);
			}
		}

		$no_group = JText::translate('VAPSERVICENOGROUP');
		$currency = VAPFactory::getCurrency();

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);
		$q->select($dbo->qn(array('c.code', 'c.percentot', 'c.value')));
		$q->from($dbo->qn('#__vikappointments_coupon', 'c'));

		if ($group)
		{
			$q->select($dbo->qn('g.name', 'group_name'));
			$q->leftjoin($dbo->qn('#__vikappointments_coupon_group', 'g') . ' ON ' . $dbo->qn('g.id') . ' = ' . $dbo->qn('c.id_group'));
			// sort coupons by group first
			$q->order($dbo->qn('g.ordering') . ' ASC');
		}
		
		if ($applicable)
		{
			// restrict to applicable group only
			$q->where(array(
				$dbo->qn('c.applicable') . ' IS NULL',
				$dbo->qn('c.applicable') . ' = ' . $dbo->q(''),
				$dbo->qn('c.applicable') . ' = ' . $dbo->q($applicable),
			), 'OR');
		}

		$q->order($dbo->qn('c.id') . ' ASC');

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $coupon)
		{
			if ($coupon->value <= 0)
			{
				$sfx = '';
			}
			else if ($coupon->percentot == 1)
			{
				$sfx = ' (' . $coupon->value . '%)';
			}
			else
			{
				$sfx = ' (' . $currency->format($coupon->value) . ')';
			}

			$opt = JHtml::fetch('select.option', $coupon->code, $coupon->code . $sfx);

			if ($group)
			{
				// create group key
				$key = $coupon->group_name ? $coupon->group_name : $no_group;

				// create group if not exists
				if (!isset($options[$key]))
				{
					$options[$key] = array();
				}

				// add within group
				$options[$key][] = $opt;
			}
			else
			{
				// add at first level
				$options[] = $opt;
			}
		}

		if ($group && isset($options[$no_group]))
		{
			// always move coupons without group at the end of the list
			$tmp = $options[$no_group];
			unset($options[$no_group]);
			$options[$no_group] = $tmp;
		}

		return $options;
	}

	/**
	 * Returns a list of supported status codes for the given group.
	 *
	 * @param 	string  $group  The group to look for (appointments, packages or subscriptions).
	 * @param 	mixed   $blank  True to include an empty option. Use a string to specify the 
	 *                          placeholder of the blank option.
	 *
	 * @return 	array 	A list of status codes.
	 */
	public static function statuscodes($group = 'appointments', $blank = false)
	{
		$options = array();

		if ($blank !== false)
		{
			$blank = is_string($blank) ? $blank : JText::translate('VAPFILTERSELECTSTATUS');

			$options[] = JHtml::fetch('select.option', '', $blank);
		}

		$where = array();

		if ($group)
		{
			// filter only when a group is specified
			$where[$group] = 1;
		}

		// search status codes
		$codes = JHtml::fetch('vaphtml.status.find', array('code', 'name'), $where);

		// create list
		foreach ($codes as $code)
		{
			// avoid displaying duplicate status codes in case of missing group
			$options[$code->code] = JHtml::fetch('select.option', $code->code, $code->name);
		}

		return array_values($options);
	}

	/**
	 * Returns a list of supported taxes.
	 *
	 * @param 	mixed  $blank  True to include an empty option. Use a string to specify the 
	 *                         placeholder of the blank option.
	 *
	 * @return 	array  A list of taxes.
	 */
	public static function taxes($blank = false)
	{
		$options = array();

		if ($blank !== false)
		{
			$blank = is_string($blank) ? $blank : JText::translate('JGLOBAL_SELECT_AN_OPTION');

			$options[] = JHtml::fetch('select.option', '', $blank);
		}

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'name')))
			->from($dbo->qn('#__vikappointments_tax'))
			->order($dbo->qn('name') . ' ASC');

		$dbo->setQuery($q);
		
		// create list
		foreach ($dbo->loadObjectList() as $tax)
		{
			$options[] = JHtml::fetch('select.option', $tax->id, $tax->name);
		}

		return $options;
	}

	/**
	 * Returns a list of supported payment gateways for the given type.
	 *
	 * @param 	string   $type    The group to look for (appointments, packages or subscriptions).
	 * @param 	mixed    $blank   True to include an empty option. Use a string to specify the 
	 *                            placeholder of the blank option.
	 * @param 	boolean  $group   True to group the payments by status.
	 *
	 * @return 	array 	 A list of payment gateways.
	 */
	public static function payments($type = 'appointments', $blank = false, $group = false)
	{
		$options = array();

		if ($blank !== false)
		{
			$blank = is_string($blank) ? $blank : JText::translate('VAPFILTERSELECTPAYMENT');

			$options[] = JHtml::fetch('select.option', '', $blank);

			if ($group)
			{
				$options[0] = array($options[0]);
			}
		}

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);
		$q->select($dbo->qn(array('id', 'name', 'published', 'charge')));
		$q->from($dbo->qn('#__vikappointments_gpayments'));
		$q->where($dbo->qn('id_employee') . ' <= 0');

		if ($type)
		{
			if ($type == 'appointments')
			{
				// allowed for appointments
				$q->where($dbo->qn('appointments') . ' = 1');
			}
			else
			{
				// allowed for packages
				$q->where($dbo->qn('subscr') . ' = 1');
			}
		}

		if ($group)
		{
			// take published items first when we need to group them
			$q->order($dbo->qn('published') . ' DESC');
		}

		$q->order($dbo->qn('ordering') . ' ASC');

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $payment)
		{
			$opt = JHtml::fetch('select.option', $payment->id, $payment->name);

			// include payment charge too
			$opt->charge     = $payment->charge;
			$opt->dataCharge = 'data-charge="' . $payment->charge . '"';

			if ($group)
			{
				// create group key
				$key = JText::translate($payment->published ? 'JPUBLISHED' : 'JUNPUBLISHED');

				// create status group if not exists
				if (!isset($options[$key]))
				{
					$options[$key] = array();
				}

				// add within group
				$options[$key][] = $opt;
			}
			else
			{
				// add at first level
				$options[] = $opt;
			}
		}

		return $options;
	}

	/**
	 * Returns a list of supported subscriptions.
	 *
	 * @param 	integer  $category  The category to fetch (0 for customers, 1 for employees).
	 * @param 	mixed    $blank     True to include an empty option. Use a string to specify the 
	 *                              placeholder of the blank option.
	 * @param 	boolean  $group     True to group the subscriptions by status.
	 *
	 * @return 	array 	 A list of subscriptions.
	 */
	public static function subscriptions($category = 0, $blank = false, $group = false)
	{
		$options = array();

		if ($blank !== false)
		{
			$blank = is_string($blank) ? $blank : JText::translate('VAPFILTERSELECTSUBSCR');

			$options[] = JHtml::fetch('select.option', '', $blank);

			if ($group)
			{
				$options[0] = array($options[0]);
			}
		}

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);
		$q->select($dbo->qn(array('id', 'name', 'published', 'price')));
		$q->from($dbo->qn('#__vikappointments_subscription'));
		$q->where($dbo->qn('group') . ' = ' . (int) $category);

		if ($group)
		{
			// take published items first when we need to group them
			$q->order($dbo->qn('published') . ' DESC');
		}

		$q->order($dbo->qn('ordering') . ' ASC');

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $subscr)
		{
			$opt = JHtml::fetch('select.option', $subscr->id, $subscr->name);

			// include subscription price too
			$opt->price = $subscr->price;
			$opt->data  = 'data-price="' . $subscr->price . '"';

			if ($group)
			{
				// create group key
				$key = JText::translate($subscr->published ? 'JPUBLISHED' : 'JUNPUBLISHED');

				// create status group if not exists
				if (!isset($options[$key]))
				{
					$options[$key] = array();
				}

				// add within group
				$options[$key][] = $opt;
			}
			else
			{
				// add at first level
				$options[] = $opt;
			}
		}

		return $options;
	}

	/**
	 * Returns a list of supported tags.
	 *
	 * @param 	boolean  $blank  True to include an empty option.
	 *
	 * @return 	array 	 A list of logins.
	 */
	public static function tags($group = null, $blank = false)
	{
		$options = array();

		if ($blank)
		{
			$options[] = JHtml::fetch('select.option', '', JText::translate('VAPFILTERSELECTTAG'));
		}

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select('t.*')
			->select($dbo->qn('t.id', 'value'))
			->select($dbo->qn('t.name', 'text'))
			->from($dbo->qn('#__vikappointments_tag', 't'))
			->order($dbo->qn('t.ordering') . ' ASC');

		if ($group)
		{
			$q->where($dbo->qn('t.group') . ' = ' . $dbo->q($group));
		}

		$dbo->setQuery($q);
		$options = array_merge($options, $dbo->loadObjectList());

		return $options;
	}

	/**
	 * Returns a list of supported payment gateways.
	 *
	 * @param 	boolean  $blank  True to include an empty option. Use a string to specify the 
	 *                           placeholder of the blank option.
	 *
	 * @return 	array 	 A list of drivers.
	 */
	public static function paymentdrivers($blank = false)
	{
		// get payment drivers
		$files = VAPApplication::getInstance()->getPaymentDrivers();

		$options = array();

		if ($blank !== false)
		{
			$blank = is_string($blank) ? $blank : JText::translate('VAPFILTERSELECTFILE');

			$options[] = JHtml::fetch('select.option', '', $blank);
		}

		foreach ($files as $file)
		{
			// get file name
			$value = basename($file);
			// strip file extension
			$text = preg_replace("/\.php$/", '', $value);
			// capitalize driver name
			$text = ucwords(str_replace('_', ' ', $text));

			$options[] = JHtml::fetch('select.option', $value, $text);
		}

		return $options;
	}

	/**
	 * Returns a list of supported SMS providers.
	 *
	 * @param 	boolean  $blank  True to include an empty option. Use a string to specify the 
	 *                           placeholder of the blank option.
	 *
	 * @return 	array 	 A list of drivers.
	 */
	public static function smsdrivers($blank = false)
	{
		// get SMS drivers
		$files = VAPApplication::getInstance()->getSmsDrivers();

		$options = array();

		if ($blank !== false)
		{
			$blank = is_string($blank) ? $blank : JText::translate('VAPFILTERSELECTFILE');

			$options[] = JHtml::fetch('select.option', '', $blank);
		}

		foreach ($files as $file)
		{
			// get file name
			$value = basename($file);
			// strip file extension
			$text = preg_replace("/\.php$/", '', $value);
			// capitalize driver name
			$text = ucwords(str_replace('_', ' ', $text));

			$options[] = JHtml::fetch('select.option', $value, $text);
		}

		return $options;
	}

	/**
	 * Returns a list of created cron jobs.
	 *
	 * @param 	mixed    $blank   True to include an empty option. Use a string to specify the 
	 *                            placeholder of the blank option.
	 * @param 	boolean  $group   True to group the cron jobs by status.
	 *
	 * @return 	array 	 A list of cron jobs.
	 */
	public static function cronjobs($blank = false, $group = false)
	{
		$options = array();

		if ($blank !== false)
		{
			$blank = is_string($blank) ? $blank : JText::translate('JGLOBAL_SELECT_AN_OPTION');

			$options[] = JHtml::fetch('select.option', '', $blank);

			if ($group)
			{
				$options[0] = array($options[0]);
			}
		}

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);
		$q->select($dbo->qn(array('id', 'name', 'published')));
		$q->from($dbo->qn('#__vikappointments_cronjob'));

		if ($group)
		{
			// take published items first when we need to group them
			$q->order($dbo->qn('published') . ' DESC');
		}

		$q->order($dbo->qn('name') . ' ASC');

		$dbo->setQuery($q);

		foreach ($dbo->loadObjectList() as $job)
		{
			$opt = JHtml::fetch('select.option', $job->id, $job->name);

			if ($group)
			{
				// create group key
				$key = JText::translate($job->published ? 'JPUBLISHED' : 'JUNPUBLISHED');

				// create status group if not exists
				if (!isset($options[$key]))
				{
					$options[$key] = array();
				}

				// add within group
				$options[$key][] = $opt;
			}
			else
			{
				// add at first level
				$options[] = $opt;
			}
		}

		return $options;
	}

	/**
	 * Returns a list of supported cron job drivers.
	 *
	 * @param 	boolean  $blank  True to include an empty option. Use a string to specify the 
	 *                           placeholder of the blank option.
	 *
	 * @return 	array 	 A list of drivers.
	 */
	public static function crondrivers($blank = false)
	{
		// load cron framework
		VikAppointments::loadCronLibrary();

		$drivers = array();

		// load all drivers
		foreach (VAPCronDispatcher::includeAll() as $file => $cron)
		{
			$drivers[$file] = $cron;
		}

		// sort by ascending name
		uasort($drivers, function($a, $b)
		{
			return strcasecmp($a->title(), $b->title());
		});

		$options = array();

		if ($blank !== false)
		{
			$blank = is_string($blank) ? $blank : JText::translate('VAPFILTERSELECTFILE');

			$options[] = JHtml::fetch('select.option', '', $blank);
		}

		// create options list
		foreach ($drivers as $file => $cron)
		{
			$opt = JHtml::fetch('select.option', $file, $cron->title());
			// register description too
			$opt->description = $cron->description();

			$options[] = $opt;
		}

		return $options;
	}

	/**
	 * Returns a list of supported e-mail templates.
	 *
	 * @param 	boolean  $blank  True to include an empty option. Use a string to specify the 
	 *                           placeholder of the blank option.
	 *
	 * @return 	array 	 A list of files.
	 */
	public static function mailtemplates($blank = false)
	{
		// get all existing template files
		$all_tmpl_files = glob(VAPHELPERS . DIRECTORY_SEPARATOR . 'mail_tmpls' . DIRECTORY_SEPARATOR . '*.php');

		$options = array();

		if ($blank !== false)
		{
			$blank = is_string($blank) ? $blank : JText::translate('VAPFILTERSELECTFILE');

			$options[] = JHtml::fetch('select.option', '', $blank);
		}

		foreach ($all_tmpl_files as $file)
		{
			$filename = basename($file);

			// remove file extension
			$name = preg_replace("/\.php$/i", '', $filename);
			// remove ending "_mail_tmpl"
			$name = preg_replace("/_?e?mail_?tmpl$/i", '', $name);
			// replace dashes and underscores with spaces
			$name = preg_replace("/[-_]+/", ' ', $name);
			// capitalize words
			$name = ucwords(strtolower($name));

			$opt = JHtml::fetch('select.option', $filename, $name);

			// include file path
			$opt->file = $file;

			$options[] = $opt;
		}

		return $options;
	}

	/**
	 * Returns a list of supported API logins.
	 *
	 * @param 	boolean  $blank  True to include an empty option.
	 *
	 * @return 	array 	 A list of logins.
	 */
	public static function apilogins($blank = false)
	{
		$options = array();

		if ($blank)
		{
			$options[] = JHtml::fetch('select.option', '', JText::translate('VAPFILTERSELECTAPP'));
		}

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn('l.application'))
			->select($dbo->qn('l.username'))
			->select($dbo->qn('l.password'))
			->from($dbo->qn('#__vikappointments_api_login', 'l'))
			->where($dbo->qn('l.active') . ' = 1');

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $r)
		{
			if ($r->application)
			{
				$text = $r->application . ' : ' . $r->username;
			}
			else
			{
				$text = $r->username;
			}

			$value = $r->username . ';' . $r->password;

			$options[] = JHtml::fetch('select.option', $value, $text);
		}

		return $options;
	}

	/**
	 * Returns a list of supported models.
	 *
	 * @param 	boolean  $blank  True to include an empty option.
	 *
	 * @return 	array 	 A list of models.
	 */
	public static function models($usepath = true, $blank = false)
	{
		$options = array();

		if ($blank)
		{
			$options[] = JHtml::fetch('select.option', '', JText::translate('VAPFILTERSELECTFILE'));
		}

		foreach (glob(VAPADMIN . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . '*.php') as $path)
		{
			// take only the file name and remove file extension
			$text = preg_replace("/\.php$/", '', basename($path));

			if ($usepath)
			{
				// use file path as value
				$value = $path;
			}
			else
			{
				// use model name as value
				$value = $text;
			}

			$options[] = JHtml::fetch('select.option', $value, $text);
		}

		return $options;
	}

	/**
	 * Returns the HTML of the handle used to rearrange the table rows.
	 * FontAwesome is required in order to display the handle icon.
	 *
	 * @param 	integer   $ordering  The ordering value.
	 * @param 	boolean   $canEdit   True if the user is allowed to edit the ordering.
	 * @param 	boolean   $canOrder  True if the table is currently sorted by "ordering" column.
	 * @param 	boolean   $input     True if the ordering input should be included in the body.
	 *
	 * @return 	string 	  The HTML of the handle.
	 */
	public static function sorthandle($ordering, $canEdit = true, $canOrder = true, $input = true)
	{
		$icon_class = $icon_title = '';

		if (!$canEdit)
		{
			$icon_class = ' inactive';
		}
		else if (!$canOrder)
		{
			$icon_class = ' inactive tip-top hasTooltip';
			$icon_title = JText::translate('JORDERINGDISABLED');
		}

		$html = '<span class="sortable-handler' . $icon_class . '" title="' . $icon_title . '">
			<i class="fas fa-ellipsis-v medium-big" aria-hidden="true"></i>
		</span>';

		if ($canEdit && $canOrder && $input)
		{
			$html .= '<input type="hidden" name="order[]" value="' . $ordering . '" />';
		}

		return $html;
	}

	/**
	 * Returns a HTML block representing the status of an image.
	 *
	 * @param 	string   $image    The relative path of the image.
	 * @param 	boolean  $preview  True to enable the preview (only if image exists).
	 *
	 * @return 	string   The resulting HTML.
	 */
	public static function imagestatus($image, $preview = true)
	{
		if (empty($image))
		{
			// image not uploaded
			$status = 2;
			$icon   = 'fas fa-image no';
		}
		else if (!is_file(VAPMEDIA . DIRECTORY_SEPARATOR . $image))
		{
			// image not found
			$status = 0;
			$icon   = 'fas fa-eye-slash no';
		}
		else
		{
			// image ok
			$status = 1;
			$icon   = 'fas fa-image ok';
		}

		$title = JText::translate('VAPIMAGESTATUS' . $status);

		// create HTML icon status
		$html = '<i class="' . $icon . ' big-2x" title="' . htmlspecialchars($title) . '"></i>';

		if ($status == 1 && $preview)
		{
			// wrap icon within a link to support preview
			$html = '<a href="' . VAPMEDIA_URI . $image . '" class="modal" target="_blank">' . $html . '</a>';
		}

		return $html;
	}

	/**
	 * Creates an action link to display and toggle the status of a column.
	 *
	 * @param 	mixed   $state  The current state of the column or an array/object
	 *                          holding the state and the publishing dates (start, end).
	 * @param 	mixed   $id     An optional record ID.
	 * @param 	string  $task   The task to reach to perform the status change.
	 * @param 	mixed   $can    The user permissions. Leave null to auto-detect.
	 * @param 	array   $args   An associative array used to extend the query string.
	 * @param 	string  $title  An optional title to attach to the state icon.
	 *
	 * @return  string  The resulting HTML.
	 */
	public static function stateaction($state, $id = null, $task = null, $can = null, array $args = array(), $title = '')
	{
		if ($task && is_null($can))
		{
			// retrieve permissions
			$can = JFactory::getUser()->authorise('core.edit.state', 'com_vikappointments');
		}

		// check if we have an object
		if (is_array($state) || is_object($state))
		{
			$data = new JObject($state);
			
			// try to extract state and publishing dates
			$state = $data->get('state', 0);
			$start = $data->get('start', null);
			$end   = $data->get('end', null);
		}
		else
		{
			$start = $end = null;
		}

		// fetch class status
		if ($state)
		{
			// published
			$state_class = 'fas fa-check-circle ok';

			$now = JDate::getInstance()->toSql();

			// check whether the start publishing is in the future
			if (!VAPDateHelper::isNull($start) && $now < $start)
			{
				// not yet started
				$state_class = 'fas fa-minus-circle warn';
				// override title
				$start = JHtml::fetch('date', $start, 'DATE_FORMAT_LC3');
				$title = JText::sprintf('VAP_PUBL_START_ON', $start);
			}
			else if (!VAPDateHelper::isNull($end) && $now > $end)
			{
				// expired
				$state_class = 'fas fa-minus-circle warn';
				// override title
				$end   = JHtml::fetch('date', $end, 'DATE_FORMAT_LC3');
				$title = JText::sprintf('VAP_PUBL_END_ON', $end);
			}
		}
		else
		{
			// unpublished
			$state_class = 'fas fa-dot-circle no';
		}

		$class = $state_class . ' big';

		if ($title)
		{
			// define title attribute
			$title  = ' title="' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '"';
			$class .= ' hasTooltip';
		}

		// create badge icon
		$html = '<i class="' . $class . '"' . $title . '></i>';

		if ($can && $task && $id)
		{
			// create action URL
			$url = 'index.php?option=com_vikappointments&task=';

			if (preg_match("/(^|\.)publish$/i", $task))
			{
				// we are using the native "publish" and "unpublish" tasks
				if ($state)
				{
					// replace "publish" with "unpublish"
					$task = preg_replace("/(^|\.)publish$/i", '$1unpublish', $task);
				}
				
				$url .= $task;
			}
			else
			{
				// we are using a custom task, so we need to defin the new state
				$url .= $task . '&state=' . ($state ? 0 : 1);
			}

			// append record ID
			$url .= '&cid[]=' . $id;

			if ($args)
			{
				// extend the query string with the given parameters
				$url .= '&' . http_build_query($args);
			}

			// append CSRF token
			$url = VAPApplication::getInstance()->addUrlCSRF($url, $xhtml = true);

			// wrap badge within a link to implement status change
			$html = '<a href="' . $url . '">' . $html . '</a>';
		}
		
		return $html;
	}
}
