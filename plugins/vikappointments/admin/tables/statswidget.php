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
 * VikAppointments statistics widget table.
 *
 * @since 1.7
 */
class VAPTableStatswidget extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_stats_widget', 'id', $db);

		// register required fields
		$this->_requiredFields[] = 'id_user';
		$this->_requiredFields[] = 'widget';
		$this->_requiredFields[] = 'position';
		$this->_requiredFields[] = 'location';
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

		if (isset($src['id_user']) && !$src['id_user'])
		{
			// We are probably editing the default widget.
			// Unset ID to create a new widget and assign it
			// to the current user.
			$src['id']      = 0;
			$src['id_user'] = JFactory::getUser()->id;
		}

		// fetch ordering for new widgets that doesn't
		// specify a position index
		if (empty($src['id']) && empty($src['ordering']))
		{
			$src['ordering'] = $this->getNextOrder(@$src['location']);
		}

		// JSON encode parameters in case of array/object
		if (isset($src['params']) && !is_scalar($src['params']))
		{
			$src['params'] = json_encode($src['params']);
		}

		// bind the details before save
		return parent::bind($src, $ignore);
	}
}
