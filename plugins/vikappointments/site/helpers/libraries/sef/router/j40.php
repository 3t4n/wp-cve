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

use Joomla\CMS\Component\Router\RouterBase;

VAPLoader::import('libraries.sef.router');

/**
 * Routing class for com_vikappointments component.
 * Compatible with Joomla 4.0 or higher.
 *
 * @since 1.7
 */
class VikAppointmentsRouter extends RouterBase
{
	/**
	 * Use trait for router helping functions.
	 */
	use VAPSefRouter;

	/**
	 * The current language tag.
	 *
	 * @var string
	 */
	protected $langtag;

	/**
	 * Class constructor.
	 *
	 * @param   JApplicationCms  $app   Application-object that the router should use.
	 * @param   JMenu            $menu  Menu-object that the router should use.
	 */
	public function __construct($app = null, $menu = null)
	{
		// invoke parent constructor
		parent::__construct($app, $menu);

		$this->langtag = JFactory::getLanguage()->getTag();

		VAPLoader::import('libraries.sef.helper');
	}

	/**
	 * Prepare-method for URLs.
	 * This method is meant to validate and complete the URL parameters.
	 * For example it can add the Itemid or set a language parameter.
	 * This method is executed on each URL, regardless of SEF mode switched
	 * on or not.
	 *
	 * @param   array  $query  An associative array of URL arguments.
	 *
	 * @return  array  The URL arguments to use to assemble the subsequent URL.
	 */
	public function preprocess($query)
	{
		if (!empty($query['lang']))
		{
			// always use the specified language
			$this->langtag = $query['lang'];
		}
		else
		{
			// force the currently set language
			$this->langtag = JFactory::getLanguage()->getTag();
		}

		$dbo = JFactory::getDbo();

		$active = $this->menu->getActive();

		if (!isset($query['view']))
		{
			// view not set, do not go ahead
			return $query;
		}

		$itemid = null;

		// services list

		if ($query['view'] == 'serviceslist')
		{
			if (isset($query['service_group']))
			{
				// try to obtain the proper Itemid that belong to the serviceslist view with the given group
				$itemid = $this->getProperItemID('serviceslist', array('service_group' => $query['service_group']));
			}

			if (!$itemid)
			{
				// attempt to load a default list of services without category
				$itemid = $this->getProperItemID('serviceslist', array(), array('service_group'));
			}

			if ($itemid)
			{
				// overwrite the Itemid set in the query in order
				// to rewrite the base URI
				$query['Itemid'] = $itemid;
			}
		}

		// employees list

		else if ($query['view'] == 'employeeslist')
		{
			if (isset($query['employee_group']))
			{
				// try to obtain the proper Itemid that belong to the employeeslist view with the given group
				$itemid = $this->getProperItemID('employeeslist', array('employee_group' => $query['employee_group']));
			}

			if (!$itemid)
			{
				// attempt to load a default list of employees without category
				$itemid = $this->getProperItemID('employeeslist', array(), array('employee_group'));
			}

			if ($itemid)
			{
				// overwrite the Itemid set in the query in order
				// to rewrite the base URI
				$query['Itemid'] = $itemid;
			}
		}

		// service details

		else if ($query['view'] == 'servicesearch')
		{
			// backward compatibility for old query string
			if (!isset($query['id_service']) && isset($query['id_ser']))
			{
				$query['id_service'] = $query['id_ser'];
			}

			// build URL for service details
			if (isset($query['id_service']))
			{
				// arguments used to check if the active menu item
				// matches the values set in query string
				$args = array(
					'view'       => 'servicesearch',
					'id_service' => $query['id_service'],
				);

				/**
				 * Make sure the ID of the service is not set within the query of the menu item.
				 * This because the link may be a self redirect, causing duplicated aliases.
				 * For example, if we have something like:
				 * /services/service-name/
				 * we need to avoid pushing the alias of the service.
				 */
				if (!$this->matchItemArguments($active, $args))
				{
					// try to obtain the proper Itemid that belong directly to the service details view
					$itemid = $this->getProperItemID('servicesearch', array('id_service' => $query['id_service']));

					if (!$itemid)
					{
						// try with the old notation too
						$itemid = $this->getProperItemID('servicesearch', array('id_ser' => $query['id_service']));
					}

					if (!$itemid)
					{
						// get parent group
						$q = $dbo->getQuery(true)
							->select($dbo->qn('id_group'))
							->from($dbo->qn('#__vikappointments_service'))
							->where($dbo->qn('id') . ' = ' . (int) $query['id_service']);

						$dbo->setQuery($q, 0, 1);

						if ($id_group = (int) $dbo->loadResult())
						{
							// try to obtain the proper Itemid that belong to the serviceslist view with the given group
							$itemid = $this->getProperItemID('serviceslist', array('service_group' => $id_group));
						}

						if (!$itemid)
						{
							// fallback to obtain the proper Itemid that belong to the serviceslist view
							$itemid = $this->getProperItemID('serviceslist', array(), array('service_group'));
						}
					}

					if ($itemid)
					{
						// overwrite the Itemid set in the query in order
						// to rewrite the base URI
						$query['Itemid'] = $itemid;
					}
				}
			}
		}

		// employee details

		else if ($query['view'] == 'employeesearch')
		{
			// build URL for employee details
			if (isset($query['id_employee']))
			{
				// arguments used to check if the active menu item
				// matches the values set in query string
				$args = array(
					'view'       => 'employeesearch',
					'id_employee' => $query['id_employee'],
				);

				/**
				 * Make sure the ID of the employee is not set within the query of the menu item.
				 * This because the link may be a self redirect, causing duplicated aliases.
				 * For example, if we have something like:
				 * /employees/employee-name/
				 * we need to avoid pushing the alias of the employee.
				 */
				if (!$this->matchItemArguments($active, $args))
				{
					// try to obtain the proper Itemid that belong directly to the employee details view
					$itemid = $this->getProperItemID('employeesearch', array('id_employee' => $query['id_employee']));

					if (!$itemid)
					{
						// get parent group
						$q = $dbo->getQuery(true)
							->select($dbo->qn('id_group'))
							->from($dbo->qn('#__vikappointments_employee'))
							->where($dbo->qn('id') . ' = ' . (int) $query['id_employee']);

						$dbo->setQuery($q, 0, 1);

						if ($id_group = (int) $dbo->loadResult())
						{
							// try to obtain the proper Itemid that belong to the employeeslist view with the given group
							$itemid = $this->getProperItemID('employeeslist', array('employee_group' => $id_group));
						}

						if (!$itemid)
						{
							// fallback to obtain the proper Itemid that belong to the employeeslist view
							$itemid = $this->getProperItemID('employeeslist', array(), array('employee_group'));
						}
					}

					if ($itemid)
					{
						// overwrite the Itemid set in the query in order
						// to rewrite the base URI
						$query['Itemid'] = $itemid;
					}
				}
			}
		}

		// order | allorders

		else if (in_array($query['view'], array('allorders', 'order', 'packorders', 'packagesorder', 'subscrhistory', 'subscrpayment', 'userprofile')))
		{
			// try to obtain the proper Itemid that belong to the specified view
			$itemid = $this->getProperItemID($query['view']);
			

			if (!$itemid)
			{
				// fallback to obtain the proper Itemid that belong to the allorders view
				$itemid = $this->getProperItemID('allorders');	
			}

			if ($itemid)
			{
				// overwrite the Itemid set in the query in order
				// to rewrite the base URI
				$query['Itemid'] = $itemid;
			}
		}

		// fallback

		else
		{
			// check whether the requested view owns an Item ID
			$itemid = $this->getProperItemID($query['view']);

			// if itemid doesn't exist and the current view is different 
			// than the specified one, push the view within the segments
			if (empty($itemid) && (!$active || $query['view'] != $active->query['view']))
			{
				if (substr($query['view'], 0, 3) == 'emp')
				{
					// fallback to obtain a parent item for employees area views
					$itemid = $this->getProperItemID('emplogin');
				}
			}

			if ($itemid)
			{
				// set new Item ID
				$query['Itemid'] = $itemid;
			}
		}

		return $query;
	}

	/**
	 * Build method for URLs.
	 * This method is meant to transform the query parameters into a more human
	 * readable form. It is only executed when SEF mode is switched on.
	 *
	 * @param   array  &$query  An array of URL arguments.
	 *
	 * @return  array  The URL arguments to use to assemble the subsequent URL.
	 */
	public function build(&$query)
	{
		$dbo = JFactory::getDbo();

		$active = $this->menu->getActive();
		
		$segments = array();

		if (!isset($query['view']) || !$this->isActive())
		{
			// view not set or router is disabled
			return $segments;
		}

		// services list

		if ($query['view'] == 'serviceslist')
		{
			if (isset($query['service_group']))
			{
				// try to obtain the proper Itemid that belong to the serviceslist view with the given group
				$itemid = $this->getProperItemID('serviceslist', array('service_group' => $query['service_group']));

				if ($itemid)
				{
					// page found, unset service group from query
					unset($query['service_group']);
				}
			}

			// unset the view from the query
			unset($query['view']);
		}

		// employees list

		else if ($query['view'] == 'employeeslist')
		{
			if (isset($query['employee_group']))
			{
				// try to obtain the proper Itemid that belong to the employeeslist view with the given group
				$itemid = $this->getProperItemID('employeeslist', array('employee_group' => $query['employee_group']));

				if ($itemid)
				{
					// page found, unset employee group from query
					unset($query['employee_group']);
				}
			}

			// unset the view from the query
			unset($query['view']);
		}

		// service details

		else if ($query['view'] == 'servicesearch')
		{
			// backward compatibility for old query string
			if (!isset($query['id_service']) && isset($query['id_ser']))
			{
				$query['id_service'] = $query['id_ser'];
			}

			// build URL for service details
			if (isset($query['id_service']))
			{
				// arguments used to check if the active menu item
				// matches the values set in query string
				$args = array(
					'view'       => 'servicesearch',
					'id_service' => $query['id_service'],
				);

				/**
				 * Make sure the ID of the service is not set within the query of the menu item.
				 * This because the link may be a self redirect, causing duplicated aliases.
				 * For example, if we have something like:
				 * /services/service-name/
				 * we need to avoid pushing the alias of the service.
				 */
				if (!$this->matchItemArguments($active, $args))
				{
					// try to obtain the proper Itemid that belong directly to the service details view
					$itemid = $this->getProperItemID('servicesearch', array('id_service' => $query['id_service']));

					if (!$itemid)
					{
						// try with the old notation too
						$itemid = $this->getProperItemID('servicesearch', array('id_ser' => $query['id_service']));
					}

					if (!$itemid)
					{
						// fetch service alias
						$alias = VAPSefHelper::getRecordAlias($query['id_service'], 'service', $this->langtag);

						if ($alias)
						{
							// alias found, push it within the segments array
							$segments[] = $alias;
						}
					}
				}

				// unset service ID from query
				unset($query['id_service'], $query['id_ser']);
			}

			// unset the view from the query
			unset($query['view']);
		}

		// employee details

		else if ($query['view'] == 'employeesearch')
		{
			// build URL for employee details
			if (isset($query['id_employee']))
			{
				// arguments used to check if the active menu item
				// matches the values set in query string
				$args = array(
					'view'        => 'employeesearch',
					'id_employee' => $query['id_employee'],
				);

				/**
				 * Make sure the ID of the employee is not set within the query of the menu item.
				 * This because the link may be a self redirect, causing duplicated aliases.
				 * For example, if we have something like:
				 * /employees/employee-name/
				 * we need to avoid pushing the alias of the employee.
				 */
				if (!$this->matchItemArguments($active, $args))
				{
					// try to obtain the proper Itemid that belong directly to the employee details view
					$itemid = $this->getProperItemID('employeesearch', array('id_employee' => $query['id_employee']));

					if (!$itemid)
					{
						// fetch employee alias
						$alias = VAPSefHelper::getRecordAlias($query['id_employee'], 'employee', $this->langtag);

						if ($alias)
						{
							// alias found, push it within the segments array
							$segments[] = $alias;
						}
					}
				}

				// unset employee ID from query
				unset($query['id_employee']);
			}

			// unset the view from the query
			unset($query['view']);
		}

		// order | allorders

		else if (in_array($query['view'], array('allorders', 'order', 'packorders', 'packagesorder', 'subscrhistory', 'subscrpayment', 'userprofile')))
		{
			// try to obtain the proper Itemid that belong to the specified view
			$itemid = $this->getProperItemID($query['view']);

			if (!$itemid)
			{
				// fallback to obtain the proper Itemid that belong to the allorders view
				$itemid = $this->getProperItemID('allorders');	
			}

			if ($itemid)
			{
				// prepend view name to differentiate orders and reservations
				switch ($query['view'])
				{
					case 'packorders':
					case 'packagesorder':
						$segments[] = VAPSefHelper::stringToAlias(JText::translate('VAP_SEF_PACKAGES'));
						break;

					case 'subscrhistory':
					case 'subscrpayment':
						$segments[] = VAPSefHelper::stringToAlias(JText::translate('VAP_SEF_SUBSCRIPTIONS'));
						break;

					case 'userprofile':
						$segments[] = VAPSefHelper::stringToAlias(JText::translate('VAP_SEF_USERPROFILE'));
						break;
				}

				// build URL for order details
				if (isset($query['ordnum']) && isset($query['ordkey']))
				{
					// ordnum and ordkey must be set
					$segments[] = VAPSefHelper::stringToAlias(intval($query['ordnum']) . "-" . $query['ordkey']);

					// unset ord num and ord key
					unset($query['ordnum']);
					unset($query['ordkey']);
				}

				// unset the view from the query
				unset($query['view']);
			}
		}

		/**
		 * The code below is used to push the view name within the $segments array in
		 * case that view is not related to the current menu item.
		 *
		 * The resulting segment will look like:
		 * /emplogin/empeditprofile/
		 * instead of:
		 * /emplogin?view=empeditprofile
		 */

		else
		{
			// check whether the requested view owns an Item ID
			$itemid = $this->getProperItemID($query['view']);

			// if itemid doesn't exist and the current view is different 
			// than the specified one, push the view within the segments
			if (empty($itemid) && (!$active || $query['view'] != $active->query['view']))
			{
				if (substr($query['view'], 0, 3) == 'emp')
				{
					// fallback to obtain a parent item for employees area views
					$itemid = $this->getProperItemID('emplogin');

					if ($itemid)
					{
						$segments[] = $query['view'];
					}
				}
			}

			if ($itemid)
			{
				// unset the view from the query
				unset($query['view']);
			}
		}

		return $segments;
	}

	/**
	 * Parse method for URLs.
	 * This method is meant to transform the human readable URL back into
	 * query parameters. It is only executed when SEF mode is switched on.
	 *
	 * @param   array  &$segments  The segments of the URL to parse.
	 *
	 * @return  array  The URL attributes to be used by the application.
	 */
	public function parse(&$segments)
	{
		$total  	= count($segments);
		$active 	= $this->menu->getActive();		
		$query_view = empty($active->query['view']) ? '' : $active->query['view'];
		$vars 		= array();

		if (!$total || !$this->isActive())
		{
			// no vars or router is disabled
			return $vars;
		}

		// load site language because some keywords might be
		// translated through the component
		VikAppointments::loadLanguage($this->langtag);

		// order details

		if ($segments[0] == 'order')
		{
			$vars['view'] = 'order';

			if (count($segments) > 1)
			{
				// make sure the order number and the order key are set
				$exp = explode('-', $segments[1]);

				if (count($exp) == 2)
				{
					$vars['ordnum'] = $exp[0];
					$vars['ordkey'] = $exp[1];
				}
			}
		}

		// packages history

		else if ($segments[0] == VAPSefHelper::stringToAlias(JText::translate('VAP_SEF_PACKAGES')))
		{
			// list view
			$vars['view'] = 'packorders';

			if ($total > 1)
			{
				// make sure the order number and the order key are set
				$exp = explode('-', $segments[1]);

				if (count($exp) == 2)
				{
					$vars['ordnum'] = $exp[0];
					$vars['ordkey'] = $exp[1];

					// access order details
					$vars['view'] = 'packagesorder';
				}
			}
		}

		// subscriptions history

		else if ($segments[0] == VAPSefHelper::stringToAlias(JText::translate('VAP_SEF_SUBSCRIPTIONS')))
		{
			// list view
			$vars['view'] = 'subscrhistory';

			if ($total > 1)
			{
				// make sure the order number and the order key are set
				$exp = explode('-', $segments[1]);

				if (count($exp) == 2)
				{
					$vars['ordnum'] = $exp[0];
					$vars['ordkey'] = $exp[1];

					// access order details
					$vars['view'] = 'subscrpayment';
				}
			}
		}

		// user profile

		else if ($segments[0] == VAPSefHelper::stringToAlias(JText::translate('VAP_SEF_USERPROFILE')))
		{
			// list view
			$vars['view'] = 'userprofile';
		}

		// order | all orders

		else if ($query_view == 'allorders' || $query_view == 'order')
		{
			// fallback to check if we passed the ordnum and ordkey to retrieve the order
			if (preg_match("/[\d]+-[a-z0-9]{16,16}$/", $segments[0]))
			{
				list($ordnum, $ordkey) = explode('-', $segments[0]);

				$vars['view'] 	= 'order';
				$vars['ordnum'] = $ordnum;
				$vars['ordkey'] = $ordkey;
			}
			else
			{
				$vars['view'] = $segments[0];
			}
		}

		// services list

		else if ($segments[0] == 'serviceslist')
		{
			$vars['view'] = 'serviceslist';

			$itemid = $this->getProperItemID($vars['view']);

			if (!empty($itemid))
			{
				$vars['Itemid'] = $itemid;
			}
		}

		// employees list 

		else if ($segments[0] == 'employeeslist')
		{
			$vars['view'] = 'employeeslist';

			$itemid = $this->getProperItemID($vars['view']);
			
			if (!empty($itemid))
			{
				$vars['Itemid'] = $itemid;
			}
		}

		// employee login

		else if ($segments[0] == 'emplogin')
		{
			$vars['view'] = 'emplogin';

			$itemid = $this->getProperItemID($vars['view']);

			if (!empty($itemid))
			{
				$vars['Itemid'] = $itemid;
			}
		}

		// waiting list

		else if ($segments[0] == 'pushwl')
		{
			/**
			 * The view name is contained within the segments array.
			 * Without this block, the view name "pushwl" would be
			 * considered as an alias for the service/employee to get.
			 */
			$vars['view'] = $segments[0];
		}

		// service search

		else if ($segments[0] == 'servicesearch' || $query_view == 'serviceslist')
		{
			// find the index in which the alias should be stored
			$pos   = $total == 1 ? 0 : 1;
			$alias = $segments[$pos];

			$id = VAPSefHelper::getRecordWithAlias($alias, 'service');

			if ($id)
			{
				$vars['id_service'] = (int) $id;
				$vars['view']       = 'servicesearch';
			}
		}

		// employee search

		else if ($segments[0] == 'employeesearch' || $query_view == 'employeeslist')
		{
			// find the index in which the alias should be stored
			$pos   = $total == 1 ? 0 : 1;
			$alias = $segments[$pos];

			$id = VAPSefHelper::getRecordWithAlias($alias, 'employee');

			if ($id)
			{
				$vars['id_employee'] = (int) $id;
				$vars['view']        = 'employeesearch';
			}
		}

		// fallback

		else
		{
			// the view name is contained within the segments array
			$vars['view'] = $segments[0];
		}

		$segments = array();

		return $vars;
	}
}
