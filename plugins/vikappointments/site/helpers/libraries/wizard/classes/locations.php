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
 * Implement the wizard step used to configure the
 * locations and the Google API Key.
 *
 * @since 1.7.1
 */
class VAPWizardStepLocations extends VAPWizardStep
{
	/**
	 * Returns the step title.
	 * Used as a very-short description.
	 *
	 * @return 	string  The step title.
	 */
	public function getTitle()
	{
		return JText::translate('VAPMENULOCATIONS');
	}

	/**
	 * Returns the step description.
	 *
	 * @return 	string  The step description.
	 */
	public function getDescription()
	{
		return JText::translate('VAP_WIZARD_STEP_LOCATIONS_DESC');
	}

	/**
	 * Returns an optional step icon.
	 *
	 * @return 	string  The step icon.
	 */
	public function getIcon()
	{
		return '<i class="fas fa-map-marker-alt"></i>';
	}

	/**
	 * Return the group to which the step belongs.
	 *
	 * @return 	string  The group name.
	 */
	public function getGroup()
	{
		// belongs to APPOINTMENTS group
		return JText::translate('VAPMENUTITLEHEADER2');
	}

	/**
	 * Returns the completion progress in percentage.
	 *
	 * @return 	integer  The percentage progress (always rounded).
	 */
	public function getProgress()
	{
		$progress = 0;

		if ($this->getGoogleAK() !== '')
		{
			// API Key specified or ignored, increase progress
			$progress += 50;
		}

		if ($this->getLocations())
		{
			// created locations, increase progress
			$progress += 50;
		}

		return $progress;
	}

	/**
	 * Checks whether the step has been completed.
	 *
	 * @return 	boolean  True if completed, false otherwise.
	 */
	public function isCompleted()
	{
		// look for 100% completion progress
		return $this->getProgress() == 100;
	}

	/**
	 * Returns the button used to process the step.
	 *
	 * @return 	string  The HTML of the button.
	 */
	public function getExecuteButton()
	{
		if ($this->getGoogleAK() !== '')
		{
			// point to the controller for creating a new location
			return '<a href="index.php?option=com_vikappointments&task=location.add" class="btn btn-success">' . JText::translate('VAPNEW') . '</a>';
		}

		// use the default save button otherwise
		$btn = parent::getExecuteButton();

		// also append a button to skip avoid adding a Google API Key
		$btn .= '<button type="button" class="btn" data-role="process.skip" style="float: right; margin-right: 4px;">' . JText::translate('VAPWIZARDBTNNOTNOW') . '</button>';

		return $btn;
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
		// fetch the specified Google API Key
		$apikey = $data->get('googleapikey');

		if ($apikey)
		{
			// update configuration value
			VAPFactory::getConfig()->set('googleapikey', $apikey);
		}
		else
		{
			// missing API Key, register "skipped" flag
			JFactory::getSession()->set('locations_gak_skipped', 1, 'vikappointments.wizard');
		}

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
	 * Returns a list of created locations.
	 *
	 * @return 	array  A list of locations.
	 */
	public function getLocations()
	{
		static $locations = null;

		// get locations only once
		if (is_null($locations))
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select($dbo->qn(array('id', 'name')))
				->from($dbo->qn('#__vikappointments_employee_location'))
				->order($dbo->qn('id') . ' ASC');

			$dbo->setQuery($q);
			$locations = $dbo->loadObjectList();
		}

		return $locations;
	}

	/**
	 * Returns the configured Google API Key.
	 *
	 * @return 	mixed  The google API Key or false if ignored.
	 */
	public function getGoogleAK()
	{
		// check whether the user decided to skip the configuration of the Google API Key
		$ak_ignored = JFactory::getSession()->get('locations_gak_skipped', 0, 'vikappointments.wizard');

		if ($ak_ignored)
		{
			return false;
		}

		// fetch Google API Key from configuration
		return VAPFactory::getConfig()->get('googleapikey');
	}
}
