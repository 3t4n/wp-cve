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
 * VikAppointments customer table.
 *
 * @since 1.7
 */
class VAPTableCustomer extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_users', 'id', $db);

		// register required fields
		$this->_requiredFields[] = 'billing_name';
		$this->_requiredFields[] = 'billing_mail';
	}

	/**
	 * Method to bind an associative array or object to the Table instance. This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   array|object  $src     An associative array or object to bind to the Table instance.
	 * @param   array|string  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 */
	public function bind($src, $ignore = array())
	{
		$src = (array) $src;

		$user = null;

		// check if the user attribute was passed
		if (isset($src['user']))
		{
			// register user fields to create a new user account
			$user = array();
			$user['usertype']      = array();
			$user['user_name']     = $src['billing_name'];
			$user['user_mail']     = $src['user']['usermail'];
			$user['user_username'] = $src['user']['username'];
			$user['user_pwd1']     = $src['user']['password'];
			$user['user_pwd2']     = $src['user']['confirm'];

			// always unset 'user' attribute before saving an operator
			unset($src['user']);
		}

		// JSON encode custom fields
		if (isset($src['fields']) && !is_string($src['fields']))
		{
			$src['fields'] = json_encode($src['fields']);
		}

		if (isset($src['credit']))
		{
			if (VAPFactory::getConfig()->getBool('usercredit'))
			{
				// user credit cannot be lower than 0
				$src['credit'] = max(array(0, (float) $src['credit']));
			}
			else
			{
				// user credit not supported, unset it
				unset($src['credit']);
			}
		}

		if (isset($src['ssn']))
		{
			// make SSN uppercase
			$src['ssn'] = strtoupper($src['ssn']);
		}

		// bind the details before save
		$return = parent::bind($src, $ignore);

		if ($return && $user)
		{
			try
			{
				// try to create a new Joomla User account
				$this->jid = AppointmentsHelper::createNewJoomlaUser($user);
			}
			catch (Exception $e)
			{
				// an error occurred, register error and abort saving
				$this->setError($e);

				return false;
			}
		}

		return $return;
	}

	/**
	 * Method to store a row in the database from the Table instance properties.
	 *
	 * If a primary key value is set the row with that primary key value will be updated with the instance property values.
	 * If no primary key value is set a new row will be inserted into the database with the properties from the Table instance.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success.
	 */
	public function store($updateNulls = false)
	{
		$is_new = empty($this->id);

		// invoke parent to store the record
		if (!parent::store($updateNulls))
		{
			// do not proceed in case of error
			return false;
		}

		// get customer data
		$args = $this->getProperties();

		// trigger an additional event for backward compatibility with old plugins
		VAPFactory::getEventDispatcher()->trigger('onCustomerSave', array($args, $is_new));

		return true;
	}
}
