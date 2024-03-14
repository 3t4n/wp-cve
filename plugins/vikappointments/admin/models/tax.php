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

VAPLoader::import('libraries.mvc.model');

/**
 * VikAppointments tax model.
 *
 * @since 1.7
 */
class VikAppointmentsModelTax extends JModelVAP
{
	/**
	 * Extend duplicate implementation to clone any related records
	 * stored within a separated table.
	 *
	 * @param   mixed    $ids     Either the record ID or a list of records.
	 * @param 	mixed    $src     Specifies some values to be used while duplicating.
	 * @param 	array    $ignore  A list of columns to skip.
	 *
	 * @return 	mixed    The ID of the records on success, false otherwise.
	 */
	public function duplicate($ids, $src = array(), $ignore = array())
	{
		$new_ids = array();

		// do not copy creation date
		$ignore[] = 'createdon';

		$dbo = JFactory::getDbo();

		// get taxes rules children model
		$ruleModel = JModelVAP::getInstance('taxrule');

		// DO NOT copy translation because of the complexity
		// between the tax-rule and taxlang-rulelang
		// relations

		foreach ($ids as $id_tax)
		{
			// start by duplicating the whole record
			$new_id = parent::duplicate($id_tax, $src, $ignore);

			if ($new_id)
			{
				$new_id = array_shift($new_id);

				// register copied
				$new_ids[] = $new_id;

				// load any children rule
				$q = $dbo->getQuery(true)
					->select($dbo->qn('id'))
					->from($dbo->qn('#__vikappointments_tax_rule'))
					->where($dbo->qn('id_tax') . ' = ' . (int) $id_tax);

				$dbo->setQuery($q);

				if ($duplicate = $dbo->loadColumn())
				{
					$rule_data = array();
					$rule_data['id_tax'] = $new_id;

					// duplicate rules by using the new tax ID
					$ruleModel->duplicate($duplicate, $rule_data);
				}
			}
		}

		return $new_ids;
	}

	/**
	 * Extend delete implementation to delete any related records
	 * stored within a separated table.
	 *
	 * @param   mixed    $ids  Either the record ID or a list of records.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function delete($ids)
	{
		// only int values are accepted
		$ids = array_map('intval', (array) $ids);

		// invoke parent first
		if (!parent::delete($ids))
		{
			// nothing to delete
			return false;
		}

		$dbo = JFactory::getDbo();

		// load any assigned translation
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_lang_tax'))
			->where($dbo->qn('id_tax') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($lang_ids = $dbo->loadColumn())
		{
			// get translation model
			$model = JModelVAP::getInstance('langtax');
			// delete assigned translations
			$model->delete($lang_ids);
		}

		// load any children rules
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_tax_rule'))
			->where($dbo->qn('id_tax') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($rule_ids = $dbo->loadColumn())
		{
			// get rule model
			$model = JModelVAP::getInstance('taxrule');
			// delete children
			$model->delete($rule_ids);
		}

		return true;
	}
}
