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

VAPLoader::import('libraries.update.rule');

/**
 * Update adapter base class.
 *
 * @since 1.7
 */
abstract class VAPUpdateAdapter
{
	/**
	 * An array of rules to execute, grouped by method.
	 *
	 * @var VAPUpdateRule[]
	 */
	protected $rules = array();

	/**
	 * Method run during update process.
	 *
	 * @param 	mixed 	 $parent  The parent that calls this method.
	 *
	 * @return 	boolean  True on success, otherwise false to stop the flow.
	 */
	final public function update($parent)
	{
		// process "update" tasks pool
		return $this->executeRules('update', $parent);
	}

	/**
	 * Method run during postflight process.
	 *
	 * @param 	mixed 	 $parent  The parent that calls this method.
	 *
	 * @return 	boolean  True on success, otherwise false to stop the flow.
	 */
	final public function finalise($parent)
	{
		// process "finalise" tasks pool
		return $this->executeRules('finalise', $parent);
	}

	/**
	 * Method run before executing VikAppointments for the first time
	 * after the update completion.
	 *
	 * @param 	mixed 	 $parent  The parent that calls this method.
	 *
	 * @return 	void
	 */
	final public function afterupdate($parent)
	{
		// process "afterupdate" tasks pool
		$success = $this->executeRules('afterupdate', $parent);

		if ($success)
		{
			// update BC version to the current one
			VAPFactory::getConfig()->set('bcv', $this->getVersion($safe = false));
		}

		return $success;
	}

	/**
	 * Executes all the rules attached to the specified action.
	 *
	 * @param 	string   $action  The action to launch.
	 * @param 	mixed    $parent  The parent that calls this method.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	private function executeRules($action, $parent)
	{
		if (!isset($this->rules[$action]))
		{
			// nothing to execute
			return true;
		}

		// iterate all rules
		foreach ($this->rules[$action] as $rule)
		{
			// do not run the same rule more than once
			if (!$rule->did())
			{
				// trigger the rule
				if ($rule->launch($parent) === false)
				{
					// something went wrong, do not go ahead
					return false;
				}
			}
		}

		// all the rules went fine
		return true;
	}

	/**
	 * Attaches a new rule to execute.
	 * It is possible to pass either a VAPUpdateRule instance or a string,
	 * which will be used as identifier to auto-load the rule class.
	 * 
	 * In example, in case of "foo_bar", the system will search for a class named
	 * "VAPUpdateRuleFooBar[VERSION]", with a path similar to this one:
	 * /libraries/update/adapters/[VERSION]/foo_bar.php
	 *
	 * The version has to be safely reported by replacing the dots with the underscores:
	 * "1.7" becomes "1_7".
	 *
	 * @param 	string  $action  The action to launch.
	 * @param 	mixed 	$rule    Either a rule class or a string.
	 *
	 * @return 	self    This object to support chaining.
	 *
	 * @throws  RuntimeException
	 */
	final protected function attachRule($action, $rule)
	{
		if (!isset($this->rules[$action]))
		{
			// init action pool first
			$this->rules[$action] = array();
		}

		if (!$rule instanceof VAPUpdateRule)
		{
			// string received, auto-load it from the version folder
			$version = $this->getVersion();

			if (!$version)
			{
				// version not contained within the classname
				throw new RuntimeException('Unable to detect the version', 500);
			}

			// attempt to load the file holding the rule
			if (!VAPLoader::import('libraries.update.adapters.' . $version . '.' . $rule))
			{
				// rule not found
				throw new RuntimeException(sprintf('Update rule [%s] not found for version [%s]', $rule, $version), 404);
			}

			// refactor rule alias to be compliant with the class name
			$sfx = preg_replace("/_/", ' ', $rule);
			$sfx = ucwords($sfx);
			$sfx = preg_replace("/\s+/", '', $sfx);

			// build rule classname
			$classname = 'VAPUpdateRule' . $sfx . $version;

			// make sure the class exists
			if (!class_exists($classname))
			{
				// class not found
				throw new RuntimeException(sprintf('Update rule class [%s] not found', $classname), 404);
			}

			// instantiate rule
			$rule = new $classname();
		}

		// append the rule
		$this->rules[$action][] = $rule;

		return $this;
	}

	/**
	 * Returns the adapter version.
	 *
	 * @param 	string  $safe  False to replace underscores with dots.
	 *
	 * @return 	string
	 */
	protected function getVersion($safe = true)
	{
		// get the classname of the current class
		$classname = get_class($this);

		// make sure the adapter includes the version within the name
		if (preg_match("/^VAPUpdateAdapter([0-9_]+)$/", $classname, $match))
		{
			$version = end($match);

			if (!$safe)
			{
				// replaces underscores with dots
				$version = preg_replace("/_+/", '.', $version);
			}
			
			return $version;
		}

		// unable to detect the version
		return null;
	}
}
