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
 * VikAppointments special restriction table.
 *
 * @since 1.7
 */
class VAPTableRestriction extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_special_restrictions', 'id', $db);

		// register required fields
		$this->_requiredFields[] = 'name';
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

		if (empty($src['id']) && empty($src['createdon']))
		{
			// set creation date
			$src['createdon'] = JDate::getInstance()->toSql();
		}

		if (isset($src['maxapp']))
		{
			// cannot be lower than 1
			$src['maxapp'] = max(array(1, (int) $src['maxapp']));
		}

		if (isset($src['usergroups']) && is_array($src['usergroups']))
		{
			// stringify selected user groups
			$src['usergroups'] = implode(',', $src['usergroups']);
		}

		// bind the details before save
		return parent::bind($src, $ignore);
	}
}
