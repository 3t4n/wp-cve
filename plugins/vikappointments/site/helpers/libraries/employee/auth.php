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

VAPLoader::import('libraries.employee.area.manager');

/**
 * Class used to check the authorisations of the attached employee.
 *
 * @since 1.2
 * @since 1.7  Renamed from EmployeeAuth.
 */
class VAPEmployeeAuth
{
	/**
	 * A list of instances.
	 *
	 * @var array
	 */
	protected static $instances = array();

	/**
	 * An associative array containing the details of the employee.
	 *
	 * @var array
	 */
	protected $employee = null;

	/**
	 * An associative array holding the employee preferences.
	 *
	 * @var object
	 */
	protected $settings = null;

	/**
	 * Configuration class handler.
	 *
	 * @var VAPConfig
	 */
	protected $config;
	
	/**
	 * Class constructor.
	 *
	 * @param 	integer    $id      The user ID.
	 * @param 	VAPConfig  $config  The config handler.
	 */
	public function __construct($id, VAPConfig $config = null)
	{
		if (is_null($config))
		{
			$this->config = VAPFactory::getConfig();
		}
		else
		{
			$this->config = $config;
		}

		$this->loadEmployee($id);
	}

	/**
	 * Provides the instance of the employee auth object,
	 * only creating it if it doesn't already exist.
	 *
	 * @param 	integer    $id      The user ID.
	 * @param 	VAPConfig  $config  The config handler.
	 *
	 * @return 	self 	  A new instance.
	 *
	 * @since 	1.6
	 */
	public static function getInstance($id = null, $config = null)
	{
		if (is_null($id))
		{
			$id = JFactory::getUser()->id;
		}

		if (!isset(static::$instances[$id]))
		{
			static::$instances[$id] = new static($id, $config);
		}

		return static::$instances[$id];
	}

	/**
	 * Method used to load the details of the employee
	 * assigned to the specified user ID.
	 *
	 * @param 	integer  $user_id 	The Joomla user ID.
	 *
	 * @return 	void
	 *
	 * @since 	1.6
	 */
	protected function loadEmployee($user_id)
	{
		if ($user_id <= 0)
		{
			return;
		}

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select('*')
			->from($dbo->qn('#__vikappointments_employee'))
			->where($dbo->qn('jid') . ' = ' . (int) $user_id);

		$dbo->setQuery($q, 0, 1);
		$this->employee = $dbo->loadAssoc();
	}

	/**
	 * Magic method to access the properties of the employee.
	 *
	 * @param 	string 	$name 	The property name.
	 *
	 * @return 	mixed 	The property value if exists, otherwise null.
	 *
	 * @since 	1.6
	 */
	public function __get($name)
	{
		if ($this->isEmployee() && isset($this->employee[$name]))
		{
			if ($name === 'timezone' && !$this->employee['timezone'])
			{
				// get system timezone if not specified
				return JFactory::getApplication()->get('offset', 'UTC');
			}

			return $this->employee[$name];
		}

		return null;
	}

	/**
	 * Checks if the current user is an employee.
	 *
	 * @return 	boolean
	 *
	 * @since 	1.6
	 */
	public function isEmployee()
	{
		return $this->employee !== null;
	}

	/**
	 * Returns the employee details.
	 *
	 * @return 	mixed 	An array if exists, otherwise null.
	 *
	 * @since 	1.6
	 */
	public function getEmployee()
	{
		return $this->employee;
	}

	/**
	 * Returns the settings of the employee.
	 *
	 * @return 	object  The employee settings.
	 *
	 * @since 	1.7
	 */
	public function getSettings()
	{
		if ($this->isEmployee() && is_null($this->settings))
		{
			$model = JModelVAP::getInstance('empsettings');
			
			// attempt to load existing settings or use the default ones
			$this->settings = $model->getItem([
				'id_employee' => $this->id
			], $blank = true);
		}
		
		return $this->settings;
	}

	/**
	 * Checks if an employee can manage its profile.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 */
	public function manage()
	{
		if (!$this->isEmployee())
		{
			return false;
		}

		// get default configuration value
		$setting = $this->config->getBool('empmanage');

		// allow plugins to override this setting
		return (bool) VAPEmployeeAreaManager::override('profile.manage', $setting, $this);
	}

	/**
	 * Checks if an employee can create a new service.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 *
	 * @deprecated 1.8  Use createService() instead.
	 */
	public function create()
	{
		return $this->createService();
	}
	
	/**
	 * Checks if an employee can create a new service.
	 *
	 * @param 	boolean  $count  True to count the created services
	 *                           against the maximum threshold.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 *
	 * @since 	1.7
	 */
	public function createService($count = false)
	{
		if (!$this->isEmployee())
		{
			return false;
		}

		// get default configuration value
		$setting = $this->config->getBool('empcreate');

		// allow plugins to override this setting
		$setting = (bool) VAPEmployeeAreaManager::override('service.create', $setting, $this);

		if ($count && $setting)
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select('COUNT(1)')
				->from($dbo->qn('#__vikappointments_service', 's'))
				->leftjoin($dbo->qn('#__vikappointments_ser_emp_assoc', 'a') . ' ON ' . $dbo->qn('s.id') . ' = ' . $dbo->qn('a.id_service'))
				->where(array(
					$dbo->qn('a.id_employee') . ' = ' . $this->id,
					// consider only the services created by the employee
					$dbo->qn('s.createdby') . ' = ' . $this->jid,
				));
			
			$dbo->setQuery($q, 0, 1);
			$count = (int) $dbo->loadResult();

			// can create only in case the number of existing services is lower
			// than the maximum threshold
			$setting = $count < $this->getServicesMaximumNumber();
		}

		return $setting;
	}

	/**
	 * Checks if an employee can remove an existing service.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 *
	 * @deprecated 1.8  Use removeService() instead.
	 */
	public function remove()
	{
		return $this->removeService();
	}

	/**
	 * Checks if an employee can remove an existing service.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 *
	 * @since 	1.7
	 */
	public function removeService()
	{
		if (!$this->isEmployee())
		{
			return false;
		}

		// get default configuration value
		$setting = $this->config->getBool('empremove');

		// allow plugins to override this setting
		return (bool) VAPEmployeeAreaManager::override('service.remove', $setting, $this);
	}

	/**
	 * Returns the maximum number of services that an employee
	 * can own simultaneously.
	 *
	 * @return 	integer  The maximum number of services.
	 */
	public function getServicesMaximumNumber()
	{
		if (!$this->isEmployee())
		{
			return 0;
		}

		// get default configuration value
		$setting = $this->config->getInt('empmaxser');

		// allow plugins to override this setting
		return (int) VAPEmployeeAreaManager::override('service.max', $setting, $this);
	}
	
	/**
	 * Checks if an employee can update an existing service.
	 *
	 * @param 	mixed    $service   The service details of the service ID.
	 * @param 	boolean  $readOnly  True to check whether there's a relation.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 */
	public function manageServices($service = array(), $readOnly = false)
	{
		if (!$this->isEmployee())
		{
			return false;
		}

		$dbo = JFactory::getDbo();

		/**
		 * Only checks whether there's a relation between the specified
		 * service and the current employee.
		 *
		 * @since 1.7
		 */
		if ($readOnly)
		{
			if (!empty($service) && is_scalar($service))
			{
				$id_service = $service;
			}
			else
			{
				$id_service = (array) $service;
				$id_service = @$id_service['id'];
			}

			$q = $dbo->getQuery(true)
				->select(1)
				->from($dbo->qn('#__vikappointments_ser_emp_assoc'))
				->where($dbo->qn('id_employee') . ' = ' . $this->id)
				->where($dbo->qn('id_service') . ' = ' . (int) $id_service);

			$dbo->setQuery($q, 0, 1);
			$dbo->execute();

			return $dbo->getNumRows();
		}
		
		if (!empty($service) && is_scalar($service))
		{
			// the service is an ID, load it from the database
			$q = $dbo->getQuery(true)
				->select($dbo->qn('createdby'))
				->from($dbo->qn('#__vikappointments_service'))
				->where($dbo->qn('id') . ' = ' . (int) $service);

			$dbo->setQuery($q, 0, 1);
			$service = $dbo->loadAssoc();
		}

		$service = (array) $service;

		// check if the service has been created by this employee
		if (!empty($service['createdby']) && $service['createdby'] == $this->jid)
		{
			// in this case, we don't need to check the configuration
			return true;
		}

		// get default configuration value
		$setting = $this->config->getBool('empmanageser');

		// allow plugins to override this setting
		return (bool) VAPEmployeeAreaManager::override('service.manage', $setting, $this);
	}

	/**
	 * Checks if the employee can create relationships with global services.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 *
	 * @since 	1.6
	 */
	public function attachServices()
	{
		if (!$this->isEmployee())
		{
			return false;
		}

		// get default configuration value
		$setting = $this->config->getBool('empattachser');

		// allow plugins to override this setting
		return (bool) VAPEmployeeAreaManager::override('service.assign', $setting, $this);
	}
	
	/**
	 * Checks if an employee can update the service rates.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 */
	public function manageServicesRates()
	{
		if (!$this->isEmployee())
		{
			return false;
		}

		// get default configuration value
		$setting = $this->config->getBool('empmanagerate');

		// allow plugins to override this setting
		return (bool) VAPEmployeeAreaManager::override('service.override', $setting, $this);
	}
	
	/**
	 * Checks if an employee can create, edit and remove payments.
	 * If the payment ID is provided, checks if the payment is
	 * owned by the current employee.
	 *
	 * @param 	integer  $id 	The payment ID.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 */
	public function managePayments($id = 0)
	{
		if (!$this->isEmployee())
		{
			return false;
		}

		// check if the employee is the owner
		if ($id > 0)
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select(1)
				->from($dbo->qn('#__vikappointments_gpayments'))
				->where(array(
					$dbo->qn('id_employee') . ' = ' . $this->id,
					$dbo->qn('id') . ' = ' . (int) $id,
				));

			$dbo->setQuery($q, 0, 1);
			$dbo->execute();

			if (!$dbo->getNumRows())
			{
				return false;
			}
		}

		// get default configuration value
		$setting = $this->config->getBool('empmanagepay');

		// allow plugins to override this setting
		return (bool) VAPEmployeeAreaManager::override('payment.manage', $setting, $this);
	}

	/**
	 * Checks if an employee can create, edit and remove coupons.
	 * If the coupon ID is provided, checks if the coupon is
	 * owned by the current employee.
	 *
	 * @param 	integer  $id 	The coupon ID.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 */
	public function manageCoupons($id = 0)
	{
		if (!$this->isEmployee())
		{
			return false;
		}

		// check if the employee is the owner
		if ($id > 0)
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select(1)
				->from($dbo->qn('#__vikappointments_coupon_employee_assoc'))
				->where(array(
					$dbo->qn('id_employee') . ' = ' . $this->id,
					$dbo->qn('id_coupon') . ' = ' . (int) $id,
				));

			$dbo->setQuery($q, 0, 1);
			$dbo->execute();

			if (!$dbo->getNumRows())
			{
				return false;
			}
		}

		// get default configuration value
		$setting = $this->config->getBool('empmanagecoupon');

		// allow plugins to override this setting
		return (bool) VAPEmployeeAreaManager::override('coupon.manage', $setting, $this);
	}

	/**
	 * Checks if an employee can create, edit and remove custom fields.
	 * If the custom field ID is provided, checks if it is
	 * owned by the current employee.
	 *
	 * @param 	integer  $id 	The field ID.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 */
	public function manageCustomFields($id = 0)
	{
		if (!$this->isEmployee())
		{
			return false;
		}

		// check if the employee is the owner
		if ($id > 0)
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select(1)
				->from($dbo->qn('#__vikappointments_custfields'))
				->where(array(
					$dbo->qn('id_employee') . ' = ' . $this->id,
					$dbo->qn('id') . ' = ' . (int) $id,
				));

			$dbo->setQuery($q, 0, 1);
			$dbo->execute();

			if (!$dbo->getNumRows())
			{
				return false;
			}
		}

		// get default configuration value
		$setting = $this->config->getBool('empmanagecustfield');

		// allow plugins to override this setting
		return (bool) VAPEmployeeAreaManager::override('field.manage', $setting, $this);
	}
	
	/**
	 * Checks if an employee can create, edit and remove working days.
	 *
	 * @param 	integer  $id 	The working day ID.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 */
	public function manageWorkDays($id = 0)
	{
		if (!$this->isEmployee())
		{
			return false;
		}

		// check if the employee is the owner
		if ($id > 0)
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select(1)
				->from($dbo->qn('#__vikappointments_emp_worktime'))
				->where(array(
					$dbo->qn('id_employee') . ' = ' . $this->id,
					$dbo->qn('id') . ' = ' . (int) $id,
				));

			$dbo->setQuery($q, 0, 1);
			$dbo->execute();

			if (!$dbo->getNumRows())
			{
				return false;
			}
		}

		// get default configuration value
		$setting = $this->config->getBool('empmanagewd');

		// allow plugins to override this setting
		return (bool) VAPEmployeeAreaManager::override('worktime.manage', $setting, $this);
	}
	
	/**
	 * Checks if an employee can create, edit and remove locations.
	 * If the location ID is provided, checks if the location is
	 * owned by the current employee.
	 *
	 * @param 	integer  $id 	    The location ID.
	 * @param 	boolean  $readOnly  True to include also the global locations.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 */
	public function manageLocations($id = 0, $readOnly = false)
	{
		if (!$this->isEmployee())
		{
			return false;
		}

		// check if the employee is the owner
		if ($id > 0)
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select(1)
				->from($dbo->qn('#__vikappointments_employee_location'))
				->where($dbo->qn('id') . ' = ' . (int) $id);

			if ($readOnly)
			{
				$q->andWhere(array(
					$dbo->qn('id_employee') . ' = ' . $this->id,
					$dbo->qn('id_employee') . ' <= 0',
				));
			}
			else
			{
				$q->where($dbo->qn('id_employee') . ' = ' . $this->id);
			}

			$dbo->setQuery($q, 0, 1);
			$dbo->execute();

			if (!$dbo->getNumRows())
			{
				return false;
			}

			/**
			 * In case of read only, immediately return true without
			 * checking whether the employee is allowed to manage the
			 * locations.
			 *
			 * @since 1.7.2
			 */
			if ($readOnly)
			{
				return true;
			}
		}

		// get default configuration value
		$setting = $this->config->getBool('empmanageloc');

		// allow plugins to override this setting
		return (bool) VAPEmployeeAreaManager::override('location.manage', $setting, $this);
	}

	/**
	 * Checks if an employee can create new reservations.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 *
	 * @deprecated 1.8  Use createReservation() instead.
	 */
	public function rescreate()
	{
		return $this->createReservation();
	}

	/**
	 * Checks if an employee can create new reservations.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 *
	 * @since 	1.7
	 */
	public function createReservation()
	{
		if (!$this->isEmployee())
		{
			return false;
		}

		// get default configuration value
		$setting = $this->config->getBool('emprescreate');

		// allow plugins to override this setting
		return (bool) VAPEmployeeAreaManager::override('reservation.create', $setting, $this);
	}

	/**
	 * Checks if an employee can update existing reservations.
	 * If the reservation ID is provided, checks if the reservation is
	 * owned by the current employee.
	 *
	 * @param 	integer  $id 	The reservation ID.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 *
	 * @deprecated 1.8  Use manageReservation() instead.
	 */
	public function resmanage($id = null)
	{
		return $this->manageReservation($id);
	}
	
	/**
	 * Checks if an employee can update existing reservations.
	 * If the reservation ID is provided, checks if the reservation is
	 * owned by the current employee.
	 *
	 * @param 	integer  $id 	    The reservation ID.
	 * @param 	boolean  $readOnly  True to check whether there's a relation.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 *
	 * @since 	1.7
	 */
	public function manageReservation($id = null, $readOnly = false)
	{
		if (!$this->isEmployee())
		{
			return false;
		}

		// check if the employee is the owner
		if ($id > 0)
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select(1)
				->from($dbo->qn('#__vikappointments_reservation'))
				->where(array(
					$dbo->qn('id_employee') . ' = ' . $this->id,
					$dbo->qn('id') . ' = ' . (int) $id,
				));

			$dbo->setQuery($q, 0, 1);
			$dbo->execute();

			if (!$dbo->getNumRows())
			{
				return false;
			}

			/**
			 * In case of read only, immediately return true without
			 * checking whether the employee is allowed to manage the
			 * appointments.
			 *
			 * @since 1.7
			 */
			if ($readOnly)
			{
				return true;
			}
		}

		// get default configuration value
		$setting = $this->config->getBool('empresmanage');

		// allow plugins to override this setting
		return (bool) VAPEmployeeAreaManager::override('reservation.manage', $setting, $this);
	}

	/**
	 * Checks if an employee can confirm existing reservations.
	 * If the reservation ID is provided, checks if the reservation is
	 * owned by the current employee.
	 *
	 * @param 	integer  $id 	The reservation ID.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 *
	 * @since 	1.6
	 * @deprecated 1.8  Use confirmReservation() instead.
	 */
	public function resconfirm($id = null)
	{
		return $this->confirmReservation($id);
	}

	/**
	 * Checks if an employee can confirm existing reservations.
	 * If the reservation ID is provided, checks if the reservation is
	 * owned by the current employee.
	 *
	 * @param 	integer  $id 	The reservation ID.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 *
	 * @since 	1.7
	 */
	public function confirmReservation($id = null)
	{
		if (!$this->isEmployee())
		{
			return false;
		}

		$status = null;

		// check if the employee is the owner
		if ($id > 0)
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select($dbo->qn('status'))
				->from($dbo->qn('#__vikappointments_reservation'))
				->where(array(
					$dbo->qn('id_employee') . ' = ' . $this->id,
					$dbo->qn('id') . ' = ' . (int) $id,
				));

			$dbo->setQuery($q, 0, 1);
			
			// get the reservation status
			$status = $dbo->loadResult();

			if (!$status)
			{
				return false;
			}

			// reservations that are already confirmed are always allowed, 
			// even if the confirmation rule is turned off
			if (JHtml::fetch('vaphtml.status.isapproved', 'appointments', $status))
			{
				return true;
			}
		}

		// get default configuration value
		$setting = $this->config->getBool('empresconfirm');

		// allow plugins to override this setting
		return (bool) VAPEmployeeAreaManager::override('reservation.confirm', $setting, $this);
	}

	/**
	 * Checks if an employee can remove existing reservations.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 *
	 * @deprecated 1.8  Use removeReservation() instead.
	 */
	public function resremove()
	{
		return $this->removeReservation();
	}
	
	/**
	 * Checks if an employee can remove existing reservations.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 *
	 * @since 	1.7
	 */
	public function removeReservation()
	{
		if (!$this->isEmployee())
		{
			return false;
		}

		// get default configuration value
		$setting = $this->config->getBool('empresremove');

		// allow plugins to override this setting
		return (bool) VAPEmployeeAreaManager::override('reservation.remove', $setting, $this);
	}

	/**
	 * Checks if the administrator should be notified every
	 * time an employee removes a reservation.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 */
	public function isNotifyOnReservationDelete()
	{
		if (!$this->isEmployee())
		{
			return false;
		}

		// get default configuration value
		$setting = $this->config->getBool('empresnotify');

		// allow plugins to override this setting
		return (bool) VAPEmployeeAreaManager::override('reservation.remove.notify', $setting, $this);
	}

	/**
	 * Checks if a user can register a new account.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 *
	 * @deprecated 1.8  Use VAPEmployeeAreaManager::canRegister() instead.
	 */
	public function register()
	{
		return VAPEmployeeAreaManager::canRegister();
	}
	
	/**
	 * Returns the default status of an employee after its registration.
	 *
	 * @return 	string  The default status.
	 *
	 * @deprecated 1.8  Use VAPEmployeeAreaManager::getSignUpStatus() instead.
	 */
	public function getSignUpStatus()
	{
		return VAPEmployeeAreaManager::getSignUpStatus();
	}
	
	/**
	 * Returns the default user group assigned to the employee.
	 *
	 * @return 	integer  The default user group.
	 *
	 * @deprecated 1.8  Use VAPEmployeeAreaManager::getSignUpUserGroup() instead.
	 */
	public function getSignUpUserGroup()
	{
		return VAPEmployeeAreaManager::getSignUpUserGroup();
	}

	/**
	 * Returns the list of all the services to auto-assign to the employee.
	 *
	 * @return 	array  The default assigned services.
	 *
	 * @deprecated 1.8  Use VAPEmployeeAreaManager::getServicesToAssign() instead.
	 */
	public function getServicesToAssign()
	{
		return VAPEmployeeAreaManager::getServicesToAssign();
	}
}

/**
 * Keep support for the old class name until it gets definitively removed.
 *
 * @deprecated 1.8  Use VAPEmployeeAuth instead.
 */
if (!class_exists('EmployeeAuth'))
{
	class_alias('VAPEmployeeAuth', 'EmployeeAuth');
}
