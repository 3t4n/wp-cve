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

VAPLoader::import('libraries.wizard.step');

/**
 * Collection class used to manage the steps needed to complete
 * a basic configuration of VikAppointments.
 *
 * @since 1.7.1
 */
class VAPWizard implements ArrayAccess, IteratorAggregate
{
	/**
	 * Singleton property.
	 *
	 * @var VAPWizard
	 */
	protected static $instance = null;

	/**
	 * A list of wizard steps.
	 *
	 * @var VAPWizardStep[]
	 */
	protected $steps = array();

	/**
	 * Flag used to check whether the wizard has been dismissed.
	 *
	 * @var boolean
	 */
	protected $done;

	/**
	 * Flag used to check whether the wizard has been set up.
	 *
	 * @var boolean
	 */
	protected $setup = false;

	/**
	 * An internal configuration setup.
	 *
	 * @var array 
	 */
	protected $config;

	/**
	 * A list of include paths.
	 *
	 * @var array
	 */
	protected $paths = array();

	/**
	 * Returns the wizard singleton.
	 *
	 * @return 	VAPWizard
	 */
	public static function getInstance()
	{
		if (static::$instance === null)
		{
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Class constructor.
	 * Cannot directly construct the class.
	 */
	protected function __construct()
	{
		$config = VAPFactory::getConfig();

		// check whether the wizard is still active
		$this->done = $config->getBool('wizardstate', false);
		// retrieve wizard config from database
		$this->config = $config->getArray('wizardconfig', array());

		// add default include path
		$this->addIncludePath(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classes');
	}

	/**
	 * Class cloner.
	 */
	protected function __clone()
	{
		// cannot clone the class
	}

	/**
	 * Class destructor.
	 */
	public function __destruct()
	{
		$config = VAPFactory::getConfig();

		// iterate step
		foreach ($this->steps as $step)
		{
			// register step options
			$this->config[$step->getID()] = $step->getOptions();
		}

		// store wizard state into database
		$config->set('wizardstate', (int) $this->done);
		// store wizard config into database
		$config->set('wizardconfig', $this->config);
	}

	/**
	 * Checks whether the wizard has been dismissed.
	 *
	 * @return 	boolean  True if dismissed, false otherwise.
	 */
	public function isDone()
	{
		return $this->done;
	}

	/**
	 * Marks the wizard as completed.
	 *
	 * @return 	self  This object to support chaining.
	 */
	public function done()
	{
		$this->done = true;

		return $this;
	}

	/**
	 * Restores the wizard after completing it.
	 *
	 * @return 	self  This object to support chaining.
	 */
	public function restore()
	{
		$this->done = false;

		// iterate step
		foreach ($this->steps as $step)
		{
			// reset step configuration
			$step->setOptions(array());
		}

		// reset config too
		$this->config = array();
		
		return $this;
	}

	/**
	 * Checks whether the wizard setup has been already invoked.
	 *
	 * @return 	boolean  True if it is no more possible to setup the wizard.
	 */
	public function isSetup()
	{
		return $this->setup;
	}

	/**
	 * Set up the wizard.
	 *
	 * @param 	array    $steps  A list of steps to auto-add.
	 *
	 * @return 	boolean  False in case the setup was already made.
	 */
	public function setup(array $steps = array())
	{
		if ($this->isSetup())
		{
			// cannot setup more than once
			return false;
		}

		// flag setup to avoid entering here again
		$this->setup = true;

		// retrieve event dispatcher
		$dispatcher = VAPFactory::getEventDispatcher();

		/**
		 * Trigger event on wizard setup, useful to preload all the needed resources.
		 *
		 * @param 	VAPWizard  $wizard  The wizard instance.
		 *
		 * @return 	void
		 *
		 * @since 	1.7.1
		 */
		$dispatcher->trigger('onSetupVikAppointmentsWizard', array($this));

		// iterate include paths
		foreach ($this->getIncludePaths() as $path)
		{
			// check if we have a directory
			if (is_dir($path))
			{
				// scan all files contained within the directory
				$files = glob(rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*.php');
			}
			else if (is_file($path) && preg_match("/\.php$/", $path))
			{
				// take only the specified file
				$files = array($path);
			}
			else
			{
				$files = array();
			}

			// iterate files one by one
			foreach ($files as $file)
			{
				// require file only once
				require_once $file;
			}
		}

		$app = JFactory::getApplication();

		// iterate the given steps
		foreach ($steps as $step)
		{
			try
			{
				if (!$step instanceof VAPWizardStep)
				{
					// try to instantiate the step
					$classname = preg_replace("/\.php$/", '', $step);
					$classname = preg_replace("/[-_]+/", ' ', $classname);
					$classname = preg_replace("/\s+/", '', ucfirst($step));
					$classname = 'VAPWizardStep' . $classname;

					// make sure the class exists
					if (!class_exists($classname))
					{
						// throw error
						throw new Exception(sprintf('Wizard step [%s] not found', $classname), 404);
					}

					// instantiate wizard step
					$step = new $classname();
				}

				// try to add the step
				$this->addStep($step);
			}
			catch (Exception $e)
			{
				// catch error, enqueue message and go ahead
				$app->enqueueMessage($e->getMessage(), 'error');
			}
		}

		/**
		 * Trigger event after completing the wizard setup.
		 * This is useful, in example, to rearrange the registered steps.
		 *
		 * @param 	VAPWizard  $wizard  The wizard instance.
		 *
		 * @return 	void
		 *
		 * @since 	1.7.1
		 */
		$dispatcher->trigger('onAfterSetupVikAppointmentsWizard', array($this));
	}

	/**
	 * Registers a new include path in which to search
	 * for the supported wizard steps.
	 *
	 * @param 	mixed  $paths  Either an array or the path to include.
	 *
	 * @return 	self   This object to support chaining.
	 */
	public function addIncludePath($paths)
	{
		$paths = (array) $paths;

		// iterate paths
		foreach ($paths as $path)
		{
			// make sure the paths hasn't been registered yet
			if (!in_array($path, $this->paths))
			{
				$this->paths[] = $path;
			}
		}
	}

	/**
	 * Returns a list of include paths.
	 *
	 * @return 	array  The include paths.
	 */
	public function getIncludePaths()
	{
		return $this->paths;
	}

	/**
	 * Returns a list of steps that haven't been ignored.
	 *
	 * @return 	array
	 */
	public function getActiveSteps()
	{
		$steps = array();

		// iterate steps
		foreach ($this->steps as $step)
		{
			// make sure the step is still active
			if (!$step->isIgnored())
			{
				// push step within the list
				$steps[] = $step;
			}
		}

		return $steps;
	}

	/**
	 * Returns the number of registered steps.
	 *
	 * @return 	integer  The steps count.
	 */
	public function getStepsCount()
	{
		return count($this->steps);
	}

	/**
	 * Returns the step at the specified index.
	 *
	 * @param 	integer  $index  The index to access.
	 *
	 * @return 	mixed 	 The step at the specified index if exists, null otherwise.
	 */
	public function getStep($index)
	{
		// make sure the index is not out of bounds
		if (preg_match("/^\d+$/", $index) && $index >= 0 && $index < $this->getStepsCount())
		{
			return $this->steps[$index];
		}

		return null;
	}

	/**
	 * Sets the step at the specified index.
	 *
	 * @param 	integer        $index  The index to access.
	 * @param 	VAPWizardStep  $step   The step to add.
	 *
	 * @return 	self  This object to support chaining.
	 */
	public function setStep($index, VAPWizardStep $step)
	{
		// make sure the index is not out of bounds (include the array limit for new items)
		if (preg_match("/^\d+$/", $index) && $index >= 0 && $index <= $this->getStepsCount())
		{
			// replace previous step
			$this->steps[$index] = $step;
		}
	}

	/**
	 * Finds the index in which the specified step is stored.
	 *
	 * @param 	mixed  $id  Either the step id or the step itself.
	 *
	 * @return 	mixed  The index of the step if exists, false otherwise.
	 */
	public function indexOf($id)
	{
		// always use step ID to search
		$id = $id instanceof VAPWizardStep ? $id->getID() : (string) $id;

		foreach ($this->steps as $index => $step)
		{
			// compare IDs
			if ($step->getID() == $id)
			{
				// step found, return current index
				return $index;
			}
		}

		// step not found
		return false;
	}

	/**
	 * Adds a new step within the list.
	 *
	 * @param 	VAPWizardStep  $step   The step to add.
	 * @param 	mixed 		   $index  The index in which the step should be stored.
	 * 								   If not specified, the step will be always
	 * 								   pushed at the end of the list.
	 *
	 * @return 	self  This object to support chaining.
	 */
	public function addStep(VAPWizardStep $step, $index = null)
	{
		// check whether the step was already added
		if ($this->indexOf($step) === false)
		{
			$id = $step->getID();

			// fetch step options
			$options = isset($this->config[$id]) ? $this->config[$id] : array();

			// attach options to step
			$step->setOptions($options);

			if ($index === null || $index === false)
			{
				// push step at the end of the list
				$this->steps[] = $step;
			}
			else
			{
				// insert step at the specified position
				array_splice($this->steps, $index, 0, array($step));
			}
		}

		return $this;
	}

	/**
	 * Adds a new step after the specified one.
	 *
	 * @param 	VAPWizardStep  $step  The step to add.
	 * @param 	mixed 		   $id    Either the step ID or the step itself.
	 *
	 * @return 	self  This object to support chaining.
	 */
	public function addStepAfter(VAPWizardStep $step, $id)
	{
		// find index in which the step is located
		$index = $this->indexOf($id);

		if ($index !== false)
		{
			// increase index by one to add the step
			// one slot after
			$index++;
		}

		// Add step at the specified position.
		// In case the step doesn't exist, it will
		// be added at the end of the queue.
		return $this->addStep($step, $index);
	}

	/**
	 * Adds a new step before the specified one.
	 *
	 * @param 	VAPWizardStep  $step  The step to add.
	 * @param 	mixed 		   $id    Either the step ID or the step itself.
	 *
	 * @return 	self  This object to support chaining.
	 */
	public function addStepBefore(VAPWizardStep $step, $id)
	{
		// find index in which the step is located
		$index = $this->indexOf($id);

		// Add step at the specified position.
		// In case the step doesn't exist, it will
		// be added at the end of the queue.
		return $this->addStep($step, $index);
	}

	/**
	 * Removes the specified step from the list.
	 *
	 * @param 	mixed    $step  Either the step ID, the step index or the step itself.
	 *
	 * @return 	boolean  True if removed, false otherwise.
	 */
	public function removeStep($step)
	{
		if (preg_match("/^\d+$/", $step))
		{
			// try to directly access the array as index
			$step = $this->getStep($step);
		}
		else
		{
			// lets recover the index with the given argument
			$step = $this->indexOf($step);
		}

		// make sure the step exists
		if (!is_int($step))
		{
			// step not found
			return false;
		}

		// splice array at the index found
		$splice = array_splice($this->steps, $step, 1);

		// extract removed step from list
		$step = array_shift($splice);

		if ($step)
		{
			// get step ID
			$id = $step->getID();

			// iterate registered steps
			foreach ($this->steps as $tmp)
			{
				// try to detach dependency from removed step
				$tmp->removeDependency($id);
			}
		}

		// step removed
		return true;
	}

	/**
	 * Checks whether all the steps of the wizard has been completed.
	 *
	 * @return 	boolean  True if completed, false otherwise.
	 */
	public function isCompleted()
	{
		// iterate active steps
		foreach ($this->getActiveSteps() as $step)
		{
			// check whether the step has been completed
			if (!$step->isCompleted())
			{
				// step not completed, return false
				return false;
			}
		}

		// all steps have been completed
		return true;
	}

	/**
	 * Calculates the overall progress of the wizard, based
	 * on the active steps.
	 *
	 * @return 	integer  The percentage progress.
	 */
	public function getProgress()
	{
		// get all active steps
		$steps = $this->getActiveSteps();
		$total = 0;

		if (!$steps)
		{
			// return 100% completion in case of no active steps
			return 100;
		}

		// iterate steps
		foreach ($steps as $step)
		{
			// increase progress total
			$total += $step->getProgress();
		}

		// calculate progress AVG
		return round($total / count($steps));
	}

	/**
	 * Checks if the given item exists.
	 *
	 * @param 	mixed 	 $key  Either the step ID or an index.
	 *
	 * @return 	boolean  True if exists, false otherwise.
	 */
	#[ReturnTypeWillChange]
	public function offsetExists($key)
	{
		if (preg_match("/^\d+$/", $key))
		{
			// try to directly access the array as index
			return $this->getStep($key) !== null;
		}
		
		// lets recover the index with the given argument
		return $this->indexOf($key) !== false;
	}

	/**
	 * Returns the value for the specified item.
	 *
	 * @param 	mixed  $key  Either the step ID or an index.
	 *
	 * @return 	mixed  The item value.
	 */
	#[ReturnTypeWillChange]
	public function offsetGet($key)
	{
		if (!preg_match("/^\d+$/", $key))
		{
			// route step ID to index
			$key = $this->indexOf($key);
		}

		// return the step at the specified index
		return $this->getStep($key);
	}

	/**
	 * Sets the given item.
	 *
	 * @param 	mixed 	       $key    Either the step ID or an index.
	 * @param 	VAPWizardStep  $value  The step to add.
	 *
	 * @return 	void
	 */
	#[ReturnTypeWillChange]
	public function offsetSet($key, $value)
	{
		if ($key === null)
		{
			// append step at the end of the array
			$this->addStep($value);
		}
		else
		{
			if (!preg_match("/^\d+$/", $key))
			{
				// route step ID to index
				$key = $this->indexOf($key);
			}

			// adds the step at the specified position
			$this->setStep($key, $value);
		}
	}

	/**
	 * Removes the given item.
	 *
	 * @param 	mixed  $key  Either the step ID or an index.
	 *
	 * @return 	void
	 */
	#[ReturnTypeWillChange]
	public function offsetUnset($key)
	{
		// remove step from the list
		$this->removeStep($key);
	}

	/**
	 * Implements an iterator for the registered steps.
	 *
	 * @return 	ArrayIterator
	 */
	#[ReturnTypeWillChange]
	public function getIterator()
	{
		// return an iterator for the active steps
		return new ArrayIterator($this->steps);
	}
}
