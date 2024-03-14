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
 * Implement the wizard step used to setup the basic
 * settings of the packages configuration.
 *
 * @since 1.7.1
 */
class VAPWizardStepSyspack extends VAPWizardStep
{
	/**
	 * Returns the step title.
	 * Used as a very-short description.
	 *
	 * @return 	string  The step title.
	 */
	public function getTitle()
	{
		return JText::translate('VAP_WIZARD_STEP_SYSPACK');
	}

	/**
	 * Returns the step description.
	 *
	 * @return 	string  The step description.
	 */
	public function getDescription()
	{
		return JText::translate('VAP_WIZARD_STEP_SYSPACK_DESC');
	}

	/**
	 * Returns an optional step icon.
	 *
	 * @return 	string  The step icon.
	 */
	public function getIcon()
	{
		return '<i class="fas fa-cog"></i>';
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
	 * Implements the step execution.
	 *
	 * @param 	JRegistry  $data  The request data.
	 *
	 * @return 	boolean
	 */
	protected function doExecute($data)
	{
		$config = VAPFactory::getConfig();

		// update configuration with the selected values
		$config->set('enablepackages', $data->get('enablepackages', 0));
		$config->set('packsmandatory', $data->get('packsmandatory', 0));

		return true;
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
	 * Checks whether the packages section is enabled.
	 * 
	 * @return 	boolean
	 */
	public function isEnabled()
	{
		return VAPFactory::getConfig()->getBool('enablepackages');
	}
}
