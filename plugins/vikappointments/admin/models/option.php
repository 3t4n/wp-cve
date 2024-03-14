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
 * VikAppointments option model.
 *
 * @since 1.7
 */
class VikAppointmentsModelOption extends JModelVAP
{
	/**
	 * Checkes whether there's a relation between the specified
	 * option and service.
	 *
	 * @param 	integer  $id_option   The option ID.
	 * @param 	integer  $id_service  The service ID.
	 *
	 * @return 	booelan  True if published, false otherwise.
	 */
	public function exists($id_option, $id_service)
	{
		// create search query
		$pk = array(
			'id_service' => (int) $id_service,
			'id_option'  => (int) $id_option,
		);

		// get existing relation, if any
		return (bool) JModelVAP::getInstance('seroptassoc')->getItem($pk);
	}

	/**
	 * Basic item loading implementation.
	 *
	 * @param   mixed    $pk   An optional primary key value to load the row by, or an array of fields to match.
	 *                         If not set the instance property value is used.
	 * @param   boolean  $new  True to return an empty object if missing.
	 *
	 * @return 	mixed    The record object on success, null otherwise.
	 */
	public function getItem($pk, $new = false)
	{
		if (is_array($pk) && array_key_exists('id_variation', $pk))
		{
			// extract variation filter from query
			$id_var = (int) $pk['id_variation'];
			unset($pk['id_variation']);
		}
		else
		{
			$id_var = 0;
		}

		// get option from parent
		$option = parent::getItem($pk, $new);

		if (!$option)
		{
			// option not found
			return null;
		}

		// register empty variations
		$option->variations = array();

		// do not need to load variations in case of new item
		if ($option->id)
		{
			if ($id_var > 0)
			{
				// load only the specified variation
				$var = JModelVAP::getInstance('optionvar')->getItem($id_var);

				if (!$var)
				{
					// relation not found
					return false;
				}
				else
				{
					// register only this variation
					$option->variations[] = $var;
				}
			}
			else
			{
				$dbo = JFactory::getDbo();

				// load all the assigned variations
				$q = $dbo->getQuery(true)
					->select('v.*')
					->from($dbo->qn('#__vikappointments_option_value', 'v'))
					->where($dbo->qn('v.id_option') . ' = ' . $option->id)
					->order($dbo->qn('v.ordering') . ' ASC');

				$dbo->setQuery($q);
				$option->variations = $dbo->loadObjectList();
			}
		}

		return $option;
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
			->from($dbo->qn('#__vikappointments_lang_option'))
			->where($dbo->qn('id_option') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($lang_ids = $dbo->loadColumn())
		{
			// get translation model
			$model = JModelVAP::getInstance('langoption');
			// delete assigned translations
			$model->delete($lang_ids);
		}

		// load any option-service relation
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_ser_opt_assoc'))
			->where($dbo->qn('id_option') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($assoc_ids = $dbo->loadColumn())
		{
			// get service model
			$model = JModelVAP::getInstance('seroptassoc');
			// delete relations
			$model->delete($assoc_ids);
		}

		// load any children variations
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_option_value'))
			->where($dbo->qn('id_option') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($var_ids = $dbo->loadColumn())
		{
			// get variation model
			$model = JModelVAP::getInstance('optionvar');
			// delete children
			$model->delete($var_ids);
		}

		return true;
	}
}
