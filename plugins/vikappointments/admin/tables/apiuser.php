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
 * VikAppointments API user table.
 *
 * @since 1.7
 */
class VAPTableApiuser extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_api_login', 'id', $db);

		// register required fields
		$this->_requiredFields[] = 'username';
		$this->_requiredFields[] = 'password';
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

		// properly format "denied plugins" list
		if (isset($src['denied']) && is_array($src['denied']))
		{
			$src['denied'] = json_encode($src['denied']);
		}

		// properly format "allowed IPs" list
		if (isset($src['ips']) && is_array($src['ips']))
		{
			$src['ips'] = json_encode($src['ips']);
		}

		if (isset($src['last_login']))
		{
			// last ping specified, fetch current date and time
			if ($src['last_login'] === 'now' || $src['last_login'] === true || $src['last_login'] === 1)
			{
				$src['last_login'] = JFactory::getDate()->toSql();
			}
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

		// make sure the username doesn't already exist
		if (isset($this->username))
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select(1)
				->from($dbo->qn($this->getTableName()))
				->where($dbo->qn('username') . ' = ' . $dbo->q($this->username));

			if ($this->id)
			{
				$q->where($dbo->qn('id') . ' <> ' . (int) $this->id);
			}
			
			$dbo->setQuery($q, 0, 1);
			$dbo->execute();

			if ($dbo->getNumRows())
			{
				// register error message
				$this->setError(JText::translate('VAPAPIUSERUSERNAMEEXISTS'));

				// invalid start date
				return false;
			}
		}

		return true;
	}
}
