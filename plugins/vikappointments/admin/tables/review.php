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
 * VikAppointments review table.
 *
 * @since 1.7
 */
class VAPTableReview extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_reviews', 'id', $db);

		// register required fields
		$this->_requiredFields[] = 'title';
		$this->_requiredFields[] = 'name';
		$this->_requiredFields[] = 'email';
		$this->_requiredFields[] = 'timestamp';
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

		if (isset($src['rating']))
		{
			// the rating must be in the range [1,5]
			$src['rating'] = min(array(5, (int) $src['rating']));
			$src['rating'] = max(array(1, (int) $src['rating']));
		}

		if ((empty($src['id']) || isset($src['timestamp'])) && (empty($src['timestamp']) || VAPDateHelper::isNull($src['timestamp'])))
		{
			// use current date and time
			$src['timestamp'] = JDate::getInstance()->toSql();
		}

		if ((empty($src['id']) || isset($src['langtag'])) && empty($src['langtag']))
		{
			// use current lang tag if empty or missing while creating a new review
			$src['langtag'] = JFactory::getLanguage()->getTag();
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

		// when creating a new review, make sure at least one between
		// service and employee have been selected
		if (empty($this->id) && (int) $this->id_service <= 0 && (int) $this->id_employee <= 0)
		{
			return false;
		}

		return true;
	}
}
