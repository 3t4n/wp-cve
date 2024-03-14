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
 * VikAppointments language employee table.
 *
 * @since 1.7
 */
class VAPTableLangemployee extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_lang_employee', 'id', $db);

		// register required fields
		$this->_requiredFields[] = 'id_employee';
		$this->_requiredFields[] = 'tag';
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

		if (empty($src['id']))
		{
			$src['id'] = 0;
		}

		// check alias only if not empty
		if (!empty($src['alias']))
		{
			VAPLoader::import('libraries.sef.helper');
			// make sure the alias is unique
			$src['alias'] = VAPSefHelper::getUniqueAlias($src['alias'], 'employee', $src['id_employee']);
		}

		// bind the details before save
		return parent::bind($src, $ignore);
	}
}
