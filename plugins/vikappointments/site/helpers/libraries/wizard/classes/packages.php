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
 * Implement the wizard step used to configure the packages.
 *
 * @since 1.7.1
 */
class VAPWizardStepPackages extends VAPWizardStep
{
	/**
	 * Returns the step title.
	 * Used as a very-short description.
	 *
	 * @return 	string  The step title.
	 */
	public function getTitle()
	{
		return JText::translate('VAPMENUPACKAGES');
	}

	/**
	 * Returns the step description.
	 *
	 * @return 	string  The step description.
	 */
	public function getDescription()
	{
		return JText::translate('VAP_WIZARD_STEP_PACKAGES_DESC');
	}

	/**
	 * Returns an optional step icon.
	 *
	 * @return 	string  The step icon.
	 */
	public function getIcon()
	{
		return '<i class="fas fa-gift"></i>';
	}

	/**
	 * Return the group to which the step belongs.
	 *
	 * @return 	string  The group name.
	 */
	public function getGroup()
	{
		// belongs to PACKAGES group
		return JText::translate('VAPMENUPACKAGES');
	}

	/**
	 * Checks whether the specified step can be skipped.
	 * By default, all the steps are mandatory.
	 * 
	 * @return 	boolean  True if skippable, false otherwise.
	 */
	public function canIgnore()
	{
		return true;
	}

	/**
	 * Checks whether the step has been ignored.
	 *
	 * @return 	boolean  True if ignored, false otherwise.
	 */
	public function isIgnored()
	{
		// get system packages dependency
		$system = $this->getDependency('syspack');

		// make sure the packages section is enabled
		if ($system && $system->isCompleted() && $system->isEnabled() === false)
		{
			// packages disabled, auto-ignore this step
			return true;
		}

		// otherwise lean on parent method
		return parent::isIgnored();
	}

	/**
	 * Checks whether the step has been completed.
	 *
	 * @return 	boolean  True if completed, false otherwise.
	 */
	public function isCompleted()
	{
		// check whether at least a package has been created
		return (bool) $this->getPackages();
	}

	/**
	 * Returns the button used to process the step.
	 *
	 * @return 	string  The HTML of the button.
	 */
	public function getExecuteButton()
	{
		// point to the controller for creating a new package
		return '<a href="index.php?option=com_vikappointments&task=package.add" class="btn btn-success">' . JText::translate('VAPNEW') . '</a>';
	}

	/**
	 * Returns a list of created packages.
	 *
	 * @return 	array  A list of packages.
	 */
	public function getPackages()
	{
		static $packages = null;

		// get packages only once
		if (is_null($packages))
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select($dbo->qn(array('id', 'name', 'published')))
				->from($dbo->qn('#__vikappointments_package'))
				->order($dbo->qn('id') . ' ASC');

			$dbo->setQuery($q);
			$packages = $dbo->loadObjectList();
		}

		return $packages;
	}
}
