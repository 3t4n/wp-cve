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
 * VikAppointments configuration table.
 *
 * @since 1.7
 */
class VAPTableConfiguration extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_config', 'id', $db);

		// register required fields
		$this->_requiredFields[] = 'param';
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

		if (empty($src['param']))
		{
			// prevent creation of new configuration records
			// in case the "param" attribute was not specified
			return false;
		}

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn($this->getTableName()))
			->where($dbo->qn('param') . ' = ' . $dbo->q($src['param']));

		$dbo->setQuery($q);
		
		// overwrite ID for update
		$src['id'] = (int) $dbo->loadResult();

		if (isset($src['setting']) && (is_array($src['setting']) || is_object($src['setting'])))
		{
			// stringify array/object
			$src['setting'] = json_encode($src['setting']);
		}

		// bind the details before save
		return parent::bind($src, $ignore);
	}

	/**
	 * Method to delete one or more records.
	 *
	 * @param   mixed    $ids  Either the record ID or a list of records.
	 *
	 * @return  boolean  True on success.
	 */
	public function delete($ids = null)
	{
		// do not delete configuration records
		return false;
	}
}
