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
 * Abstract representation of a wizard step.
 *
 * @since 1.7.1
 */
abstract class VAPWizardStep
{
	/**
	 * An options registry.
	 *
	 * @var JObject
	 */
	protected $options;

	/**
	 * A list of dependencies.
	 *
	 * @var VAPWizardStep[]
	 */
	protected $dependencies = array();

	/**
	 * Class constructor.
	 *
	 * @param 	mixed 	$options  Either an array or an object of options.
	 */
	public function __construct($options = array())
	{
		if (is_array($options) || $options instanceof stdClass)
		{
			// wrap data in a registry
			$this->options = new JObject($options);
		}
		else if (!$options instanceof JObject)
		{
			// use empty registry
			$this->options = new JObject();
		}
	}

	/**
	 * Returns the step unique identifier.
	 * By default, it is always based on the classname.
	 *
	 * @return 	string  The step ID.
	 */
	public function getID()
	{
		// get class name
		$id = get_class($this);

		// strip initial base class
		$id = preg_replace("/^VAPWizardStep/i", '', $id);

		// add an underscore before every camel case
		$id = preg_replace("/([a-z])([A-Z])/", '$1_$2', $id);

		// always use lower-case letters
		return strtolower($id);
	}

	/**
	 * Returns the step title.
	 * Used as a very-short description.
	 *
	 * @return 	string  The step title.
	 */
	abstract public function getTitle();

	/**
	 * Returns the step description.
	 *
	 * @return 	string  The step description.
	 */
	public function getDescription()
	{
		return '';
	}

	/**
	 * Returns an optional step icon.
	 *
	 * @return 	string  The step icon.
	 */
	public function getIcon()
	{
		return '';
	}

	/**
	 * Return the group to which the step belongs.
	 *
	 * @return 	string  The group name.
	 */
	abstract public function getGroup();

	/**
	 * Returns the step options.
	 *
	 * @return 	array  The options.
	 */
	final public function getOptions()
	{
		return $this->options->getProperties();
	}

	/**
	 * Sets the step options.
	 *
	 * @param 	mixed  $options  Either an array or an object.
	 *
	 * @return 	self   This object to support chaining.
	 */
	public function setOptions($options)
	{
		$this->options->setProperties($options);

		return $this;
	}

	/**
	 * Checks whether the step is supported by the
	 * current configuration progress.
	 *
	 * @return 	boolean  True if supported, false otherwise.
	 */
	public function isSupported()
	{
		return true;
	}

	/**
	 * Returns the completion progress in percentage.
	 *
	 * @return 	integer  The percentage progress (always rounded).
	 */
	public function getProgress()
	{
		// return by default 100% progress if completed, 0 otherwise
		return $this->isCompleted() ? 100 : 0;
	}

	/**
	 * Returns a list of steps to which this class depends.
	 * The step will be completed only once all the 
	 * dependencies are completed.
	 *
	 * @return 	array
	 */
	final public function getDependencies()
	{
		return $this->dependencies;
	}

	/**
	 * Returns the searched dependency.
	 *
	 * @param 	string  $id  The step identifier.
	 *
	 * @return 	mixed   The step instance if exists, null otherwise.
	 */
	final public function getDependency($id)
	{
		// iterate dependencies
		foreach ($this->dependencies as $dep)
		{
			// compare ID
			if ($dep->getID() == $id)
			{
				// return dependency
				return $dep;
			}
		}

		return null;
	}

	/**
	 * Set up a list of dependencies.
	 *
	 * @param 	array  $steps  A list of steps.
	 *
	 * @return 	self   This object to support chaining.
	 */
	final public function setDependencies(array $steps)
	{
		// add steps one by one
		foreach ($steps as $step)
		{
			// make sure we are handling a valid class
			if ($step instanceof VAPWizardStep)
			{
				// register dependency
				$this->addDependency($step);
			}
		}

		return $this;
	}

	/**
	 * Adds a new dependency to this step.
	 *
	 * @param 	mixed  ...$args  An undefined number of steps to link.
	 *
	 * @return 	self   This object to support chaining.
	 */
	final public function addDependency()
	{
		// iterate list of arguments
		foreach (func_get_args() as $step)
		{
			// add only if not already in list
			if ($step instanceof VAPWizardStep && !$this->hasDependency($step))
			{
				$this->dependencies[] = $step;
			}
		}

		return $this;
	}

	/**
	 * Removes the specified dependency.
	 *
	 * @param 	mixed    $step  Either an array or a step to add as dependency.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	final public function removeDependency($step)
	{
		if ($step instanceof VAPWizardStep)
		{
			// extract ID from step
			$step = $step->getID();
		}

		// iterate dependencies
		foreach ($this->dependencies as $i => $dep)
		{
			// compare ID
			if ($dep->getID() == $step)
			{
				// splice array at the index found
				array_splice($this->dependencies, $i, 1);

				return true;
			}
		}

		// nothing to remove
		return false;
	}

	/**
	 * Checks whether this step owns a dependency with the given one.
	 *
	 * @param 	VAPWizardStep  $step  The step to check.
	 *
	 * @return 	boolean  True in case of dependency, false otherwise
	 */
	final public function hasDependency(VAPWizardStep $step)
	{
		return in_array($step, $this->dependencies, true);
	}

	/**
	 * Returns the HTML to display description and actions
	 * needed to complete the step.
	 *
	 * @return 	string  The HTML of the step.
	 */
	public function display()
	{
		// always try to search for a layout related to this step
		return JLayoutHelper::render('wizard.steps.' . $this->getID(), array('step' => $this));
	}

	/**
	 * Checks whether the step can be processed by checking
	 * all the registered dependencies.
	 *
	 * @return 	boolean  True if executable, false otherwise.
	 */
	public function canExecute()
	{
		// iterate steps
		foreach ($this->dependencies as $step)
		{
			// make sure the step has been already completed
			if (!$step->isCompleted())
			{
				// step not yet completed, then cannot execute this one yet
				return false;
			}
		}

		// step can be executed
		return true;
	}

	/**
	 * Checks whether the step has been completed.
	 *
	 * @return 	boolean  True if completed, false otherwise.
	 */
	public function isCompleted()
	{
		// check whether the step has been completed
		return (bool) $this->options->get('completed', false);
	}

	/**
	 * Processes the step according to the given data.
	 *
	 * @param 	mixed  $data  The request data.
	 *
	 * @return 	void
	 */
	final public function execute($data = array())
	{
		if (is_array($data) || $data instanceof stdClass)
		{
			// wrap data in a registry
			$data = new JRegistry($data);
		}
		else if (!$data instanceof JRegistry)
		{
			// use empty registry
			$data = new JRegistry();
		}

		// make sure the step can be executed
		if ($this->canExecute())
		{
			// execute step
			$status = $this->doExecute($data);

			if ($status)
			{
				// register completed state
				$this->options->set('completed', true);
			}
		}
	}

	/**
	 * Implements the step execution.
	 *
	 * @param 	JRegistry  $data  The request data.
	 *
	 * @return 	boolean    True to mark the step as completed.
	 */
	protected function doExecute($data)
	{
		// do nothing here

		return true;
	}

	/**
	 * Returns the button used to process the step.
	 *
	 * @return 	string  The HTML of the button.
	 */
	public function getExecuteButton()
	{
		// use by default the standard save button
		return '<button type="button" class="btn btn-success" data-role="process">' . JText::translate('VAPSAVE') . '</button>';
	}

	/**
	 * Checks whether the specified step can be skipped.
	 * By default, all the steps are mandatory.
	 * 
	 * @return 	boolean  True if skippable, false otherwise.
	 */
	public function canIgnore()
	{
		return false;
	}

	/**
	 * Checks whether the step has been ignored.
	 *
	 * @return 	boolean  True if ignored, false otherwise.
	 */
	public function isIgnored()
	{
		// iterate dependencies
		foreach ($this->dependencies as $step)
		{
			// check whether the dependency was ignored
			if ($step->isIgnored())
			{
				// then ignore also this step
				return true;
			}
		}

		// check whether the step has been ignored
		return (bool) $this->options->get('ignored', false);
	}

	/**
	 * Ignores the step.
	 *
	 * @return 	self  This object to support chaining.
	 */
	final public function ignore()
	{
		// make sure the step can be ignored
		if ($this->canIgnore())
		{
			// register ignored state
			$this->options->set('ignored', true);
		}

		return $this;
	}

	/**
	 * Checks whether the step has been dismissed.
	 *
	 * @return 	boolean  True if dismissed, false otherwise.
	 */
	public function isDismissed()
	{
		// check whether the step has been dismissed
		return (bool) $this->options->get('dismissed', false);
	}

	/**
	 * Dismisses the step.
	 *
	 * @return 	self  This object to support chaining.
	 */
	final public function dismiss()
	{
		// make sure the step can be dismissed (only if completed)
		if ($this->isCompleted())
		{
			// register dismissed state
			$this->options->set('dismissed', true);
		}

		return $this;
	}

	/**
	 * Checks whether the step should is visible.
	 * A step is visible only in case it has never been
	 * ignored or dismissed.
	 *
	 * @return 	boolean  True if visible, false otherwise.
	 */
	final public function isVisible()
	{
		return !$this->isIgnored() && !$this->isDismissed();
	}
}
