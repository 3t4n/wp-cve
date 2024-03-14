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
 * Custom fields update adapter rule for 1.7 version.
 *
 * @since 1.7
 */
class VAPUpdateRuleCustomFieldsMapper1_7 extends VAPUpdateRule
{
	/**
	 * Method run during update process.
	 *
	 * @param 	mixed 	 $parent  The parent that calls this method.
	 *
	 * @return 	boolean  True on success, otherwise false to stop the flow.
	 */
	protected function run($parent)
	{
		$this->mapRules();
		$this->encodeSelectOptions();

		return true;
	}

	/**
	 * Since the rules are now extendable, we had to refactor the column used to identify the rules, by
	 * switching the integer ID into the related file name.
	 *
	 * During the installation of the update, we need to map the existing custom fields.
	 *
	 * @return 	void
	 */
	private function mapRules()
	{
		$dbo = JFactory::getDbo();

		// create lookup to assign the correct rule
		$lookup = array(
			1 => 'nominative',
			2 => 'email',
			3 => 'phone',
			4 => 'state',
			5 => 'city',
			6 => 'address',
			7 => 'zip',
			8 => 'company',
			9 => 'vatnum',
		);

		// fetch all the custom fields
		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'rule')))
			->from($dbo->qn('#__vikappointments_custfields'));

		$dbo->setQuery($q);
		$dbo->execute();

		if ($dbo->getNumRows())
		{
			foreach ($dbo->loadObjectList() as $f)
			{
				// make sure the rule is supported
				if (!isset($lookup[$f->rule]))
				{
					continue;
				}

				// assign the new rule alias
				$f->rule = $lookup[$f->rule];
				// finalise the update
				$dbo->updateObject('#__vikappointments_custfields', $f, 'id');
			}
		}
	}

	/**
	 * The options of a select are now encoded within a JSON string, so that we can track the relations
	 * between the options and their translations. For this reason, we must iterate all the existing custom
	 * fields, fetch the options, JSON-encode them and save the records.
	 *
	 * NOTE: the values of the custom fields stored within the database will lose the relation with the
	 * existing options.
	 *
	 * @return 	void
	 */
	private function encodeSelectOptions()
	{
		$dbo = JFactory::getDbo();

		$ids = array();

		// fetch all the "select" custom fields
		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'choose')))
			->from($dbo->qn('#__vikappointments_custfields'))
			->where($dbo->qn('type') . ' = ' . $dbo->q('select'));

		$dbo->setQuery($q);
		$dbo->execute();

		if ($dbo->getNumRows())
		{
			foreach ($dbo->loadObjectList() as $f)
			{
				// JSON encode options
				$f->choose = json_encode(explode(';;__;;', $f->choose));
				
				// finalise the update
				$dbo->updateObject('#__vikappointments_custfields', $f, 'id');

				$ids[] = $f->id;
			}
		}

		if ($ids)
		{
			// do the same for the translations of the custom fields
			$q = $dbo->getQuery(true)
				->select($dbo->qn(array('id', 'choose')))
				->from($dbo->qn('#__vikappointments_lang_customf'))
				->where($dbo->qn('id_customf') . ' IN (' . implode(',', $ids) . ')');

			$dbo->setQuery($q);
			$dbo->execute();

			if ($dbo->getNumRows())
			{
				foreach ($dbo->loadObjectList() as $f)
				{
					// JSON encode options
					$f->choose = json_encode(explode(';;__;;', $f->choose));
					
					// finalise the update
					$dbo->updateObject('#__vikappointments_lang_customf', $f, 'id');
				}
			}
		}
	}
}
