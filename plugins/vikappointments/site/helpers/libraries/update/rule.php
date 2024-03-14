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
 * Update rule abstract class.
 *
 * @since 1.7
 */
abstract class VAPUpdateRule
{
	/**
	 * Method run during update process.
	 *
	 * @param 	mixed 	 $parent  The parent that calls this method.
	 *
	 * @return 	boolean  True on success, otherwise false to stop the flow.
	 */
	final public function launch($parent)
	{
		// invoke run method declared by the implementors
		$result = $this->run($parent);

		if ($result !== false)
		{
			// auto-flag task as completed
			$this->complete();
		}

		return $result;
	}

	/**
	 * Method run during update process.
	 *
	 * @param 	mixed 	 $parent  The parent that calls this method.
	 *
	 * @return 	boolean  True on success, otherwise false to stop the flow.
	 */
	abstract protected function run($parent);

	/**
	 * Children classes can override this method to avoid executing the
	 * same rule more than once.
	 *
	 * @return 	boolean  True to skip the rule execution.
	 */
	public function did()
	{
		// get pool of completed tasks
		$tasks = VAPFactory::getConfig()->getArray('updatetasks', array());
		// check whether this rule is contained within the list
		return in_array(strtolower(get_class($this)), $tasks);
	}

	/**
	 * Marks the task as completed.
	 * Children classes can invoke this method at the end of the run
	 * in order to prevent a double execution.
	 *
	 * @return 	void
	 */
	protected function complete()
	{
		$config = VAPFactory::getConfig();

		// get pool of completed tasks
		$tasks = $config->getArray('updatetasks', array());
		// register this task within the list to mark it as completed
		$tasks[] = strtolower(get_class($this));
		$config->set('updatetasks', $tasks);
	}
}
