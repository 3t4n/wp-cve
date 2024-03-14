<?php
/** 
 * @package     VikAppointments - Libraries
 * @subpackage  wizard
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Implement the wizard step used to setup the shortcodes
 * to display VikAppointments within the front-end.
 *
 * @since 1.2.3
 */
class VAPWizardStepShortcodes extends VAPWizardStep
{
	/**
	 * Returns the step title.
	 * Used as a very-short description.
	 *
	 * @return 	string  The step title.
	 */
	public function getTitle()
	{
		return __('Shortcodes', 'vikappointments');
	}

	/**
	 * Returns the step description.
	 *
	 * @return 	string  The step description.
	 */
	public function getDescription()
	{
		return __('<p>The shortcodes are used to display the plugin within the front-end. You need to create at least a shortcode, which should then be included within an apposite post/page.</p><p>After creating a shortcode, go back to the list and click the <i class="fas fa-plus-square"></i> button, under the <b>Post</b> column, to automatically create a page. The created page will include the shortcode previously generated.</p>', 'vikappointments');
	}

	/**
	 * Returns an optional step icon.
	 *
	 * @return 	string  The step icon.
	 */
	public function getIcon()
	{
		return '<i class="fas fa-quote-left"></i>';
	}

	/**
	 * Return the group to which the step belongs.
	 *
	 * @return 	string  The group name.
	 */
	public function getGroup()
	{
		// belongs to GLOBAL group
		return JText::translate('VAPMENUTITLEHEADER3');
	}

	/**
	 * Returns the completion progress in percentage.
	 *
	 * @return 	integer  The percentage progress (always rounded).
	 */
	public function getProgress()
	{
		$progress = [];

		foreach (['appointments', 'packages', 'subscriptions'] as $group)
		{
			// make sure the group is configurable
			if ($this->groupExists($group))
			{
				// check whether the shortcode should be created
				if ($this->needShortcode($group))
				{
					// not yet created
					$progress[] = 0;	
				}
				else
				{
					// already created
					$progress[] = 100;
				}
			}
		}

		// calculate the progress average
		$avg = $progress ? array_sum($progress) / count($progress) : 100;

		// ignore decimals
		return round($avg, 0);
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
		// point to the controller to create a new shortcode
		return '<a href="admin.php?page=vikappointments&task=shortcodes.add" class="btn btn-success">' . JText::translate('VAPNEW') . '</a>';
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
		return JLayoutHelper::render('html.wizard.shortcodes', array('step' => $this));
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
	 * Returns a list of created shortcodes.
	 *
	 * @return 	array  A list of shortcodes.
	 */
	public function getShortcodes()
	{
		static $shortcodes = null;

		// get shortcodes only once
		if (is_null($shortcodes))
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select($dbo->qn(array('type', 'title', 'name')))
				->from($dbo->qn('#__vikappointments_wpshortcodes'))
				->order($dbo->qn('id') . ' ASC');

			$dbo->setQuery($q);
			$dbo->execute();

			if ($dbo->getNumRows())
			{
				$shortcodes = $dbo->loadObjectList();
			}
			else
			{
				$shortcodes = array();
			}
		}

		return $shortcodes;
	}

	/**
	 * Checks whether the specified group should be considered
	 * while fetching the overall progress.
	 * 
	 * @param 	string   $group  The group to look for.
	 * 
	 * @return 	boolean  True if observable, false otherwise.
	 */
	public function groupExists($group)
	{
		if ($group === 'appointments')
		{
			$exists = true;
		}
		else if ($group === 'packages')
		{
			// get packages step dependency
			$section = $this->getDependency('syspack');

			// the step must exists and should NOT be ignored
			$exists = $section && !$section->isIgnored();
		}
		else if ($group === 'subscriptions')
		{
			// get subscriptions step dependency
			$section = $this->getDependency('syssubscr');

			// the step must exists and should NOT be ignored
			$exists = $section && !$section->isIgnored();
		}

		return $exists;
	}

	/**
	 * Checks whether the specified group needs the
	 * creation of a shortcode.
	 *
	 * @param 	string   $group  The group to look for.
	 *
	 * @return 	boolean  True if a shortcode is needed, false otherwise.
	 */
	public function needShortcode($group)
	{
		// the step is completed after creating at least a shortcode
		// for each active section
		$types = array_map(function($shortcode)
		{
			return $shortcode->type;
		}, $this->getShortcodes());

		// check if the group is enabled
		if ($group == 'appointments')
		{
			// appointments always enabled
			$enabled = true;

			// define list of supported views
			$lookup = ['serviceslist', 'employeeslist', 'servicesearch', 'employeesearch'];
		}
		else if ($group == 'packages')
		{
			// get packages step dependency
			$section = $this->getDependency('syspack');
			// Check whether the packages section is enabled.
			// Always consider has enabled in case the step was not yet completed.
			$enabled = $section && ($section->isEnabled() || !$section->isCompleted());

			// define list of supported views
			$lookup = ['packages'];
		}
		else if ($group == 'subscriptions')
		{
			// get subscriptions step dependency
			$section = $this->getDependency('syssubscr');
			// Check whether the subscriptions section is enabled.
			// Always consider has enabled in case the step was not yet completed.
			$enabled = $section && ($section->isEnabled() || !$section->isCompleted());

			// define list of supported views
			$lookup = ['subscriptions'];
		}
		else
		{
			// unknown group, not allowed
			$enabled = false;
		}

		// in case the group is active, check whether the list
		// of created types intersects at least one element of
		// the fetched lookup
		return $enabled && !array_intersect($types, $lookup);
	}
}
