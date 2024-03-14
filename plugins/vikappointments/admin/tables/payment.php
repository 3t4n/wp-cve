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
 * VikAppointments payment table.
 *
 * @since 1.7
 */
class VAPTablePayment extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_gpayments', 'id', $db);

		// register required fields
		$this->_requiredFields[] = 'name';
		$this->_requiredFields[] = 'file';
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

		// fetch ordering for new record
		if (empty($src['id']))
		{
			$id_employee = isset($src['id_employee']) ? (int) $src['id_employee'] : 0;

			// use a different ordering for the payments assigned to the employees
			$src['ordering'] = $this->getNextOrder('id_employee = ' . $id_employee);

			$src['id'] = 0;
		}

		// stringify payments params
		if (isset($src['params']) && !is_string($src['params']))
		{
			$src['params'] = json_encode($src['params']);
		}

		if ((empty($src['id']) || isset($src['createdby'])) && empty($src['createdby']))
		{
			// set current user as author
			$src['createdby'] = JFactory::getUser()->id;
		}

		// bind the details before save
		return parent::bind($src, $ignore);
	}

	/**
	 * Method to perform sanity checks on the Table instance properties to
	 * ensure they are safe to store in the database.
	 *
	 * @return  boolean  True if the instance is sane and able to be stored in the database.
	 */
	public function check()
	{
		// check integrity using parent
		if (!parent::check())
		{
			return false;
		}
		
		if (!empty($this->file))
		{
			try
			{
				// obtain config just to make sure the file is supported
				VAPApplication::getInstance()->getPaymentConfig($this->file);
			}
			catch (Exception $e)
			{
				// unsupported file, register error and abort
				$this->setError($e);

				return false;
			}
		}

		return true;
	}
}
