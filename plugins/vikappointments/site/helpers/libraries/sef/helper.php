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
 * Helper class used to manipulate the alias of the records.
 *
 * @since 1.7
 */
class VAPSefHelper
{
	/**
	 * Method used to make URL-safe any strings.
	 *
	 * @param 	string 	$src  The string to convert.
	 *
	 * @return 	string 	The safe string.
	 */
	public static function stringToAlias($src)
	{
		return JFilterOutput::stringURLSafe($src);
	}

	/**
	 * Returns a valid and unique alias for SEO usages.
	 *
	 * @param 	string 	 $src 	  The source name.
	 * @param 	string 	 $type 	  The type name.
	 * @param 	integer  $id 	  The ID of record to exclude while serching other aliases.
	 * @param 	mixed 	 $parent  An optional parent.
	 *
	 * @return 	string 	 The resulting alias.
	 */
	public static function getUniqueAlias($src, $type = '', $id = null, $parent = null)
	{
		$src = static::stringToAlias($src);
		
		$alias = $src;
		$cont  = 1;
		
		do
		{
			if ($cont > 1)
			{
				$alias = $src . "-" . $cont;
			}
			
			$cont++;

			// check if the fetched alias is already assigned
			// to a different record (repeat again in that case)
			$_id = static::getRecordWithAlias($alias, $type, null, $parent);
		} while ($_id > 0 && $_id != $id);

		return $alias;
	}

	/**
	 * Returns the record that owns the specified alias.
	 *
	 * @param 	string 	 $alias	  The alias to find.
	 * @param 	string 	 $type    The type name.
	 * @param 	mixed 	 $lang    The language tag.
	 * @param 	mixed 	 $parent  An optional parent.
	 *
	 * @return 	mixed 	 The ID of the record found on success, null otherwise.
	 */
	public static function getRecordWithAlias($alias, $type, $lang = null, $parent = null)
	{
		$dbo = JFactory::getDbo();

		$translator = VAPFactory::getTranslator();

		// obtain translation table
		$table = $translator->getTable($type);

		// search for a translation first
		$q = $dbo->getQuery(true)
			->select($dbo->qn('l.' . $table->getForeignKey()))
			->from($dbo->qn($table->getTableName(), 'l'))
			->where($dbo->qn('l.alias') . ' = ' . $dbo->q($alias));

		if ($lang)
		{
			// filter by language only if provided
			$q->where($dbo->qn('l.' . $table->getLangColumn()) . ' = ' . $dbo->q($lang));
		}

		if ($parent)
		{
			// get parent table
			$parentTable = $table->getParentTable();

			// make sure the table supports a parent
			if ($parentTable)
			{
				// get table instance
				$parentTable = $translator->getTable($parentTable);

				// join query to parent
				$q->leftjoin($dbo->qn($parentTable->getTableName(), 'p') . ' ON ' .
					$dbo->qn('p.' . $parentTable->getPrimaryKey()) . ' = ' . $dbo->qn('l.' . $table->getParentKey()));

				// filter by parent ID
				$q->where($dbo->qn('p.' . $parentTable->getForeignKey()) . ' = ' . (int) $parent);
			}
		}

		$dbo->setQuery($q, 0, 1);

		if ($tmp = $dbo->loadResult())
		{
			// return only the record ID
			return (int) $tmp;
		}

		// translation not found, try with the original table
		$q = $dbo->getQuery(true)
			->select($dbo->qn($table->getLinkedPrimaryKey()))
			->from($dbo->qn($table->getLinkedTable()))
			->where($dbo->qn('alias') . ' = ' . $dbo->q($alias));

		if ($parent)
		{
			// get parent column
			$parentCol = $table->getLinkedParentKey();

			// make sure the table supports a parent column
			if ($parentCol)
			{
				$q->where($dbo->qn($parentCol) . ' = ' . (int) $parent);
			}
		}

		$dbo->setQuery($q, 0, 1);

		if ($tmp = $dbo->loadResult())
		{
			// return only the record ID
			return (int) $tmp;
		}

		return null;
	}

	/**
	 * Returns the alias used by the specified record.
	 *
	 * @param 	integer  $id	The ID of the record.
	 * @param 	string 	 $type  The type name.
	 * @param 	mixed 	 $lang 	The current language for translated aliases.
	 *
	 * @return 	mixed 	 The alias of the record found, null otherwise.
	 */
	public static function getRecordAlias($id, $type, $lang = null)
	{
		$translator = VAPFactory::getTranslator();

		// translate specified record
		$tx = $translator->translate($type, $id, $lang);

		if ($tx)
		{
			// record found, return correct alias
			return $tx->alias;
		}

		return null;
	}
}
