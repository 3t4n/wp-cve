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

VAPLoader::import('libraries.language.table');

/**
 * Helper class used to handle component translations.
 *
 * @since 1.7
 */
class VAPLanguageTranslator
{
	/**
	 * The translator instance.
	 *
	 * @var VAPLanguageTranslator
	 */
	protected static $instance = null;

	/**
	 * A list of tables with the related columns.
	 *
	 * @var array
	 */
	protected $tables = null;

	/**
	 * Returns the instance of the translator object.
	 * A new instance is created only if it is not yet available.
	 *
	 * @return 	VAPLanguageTranslator 	The instance of the translator.
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
	 * Loads the translations for the given records.
	 *
	 * @param 	string 	 $table   The translation table ID.
	 * @param 	mixed 	 $id      Either the ID or an array of IDS of the
	 * 						      records to translate.
	 * @param 	mixed 	 $tag     The language tag to use. If not specified,
	 * 						      the current one will be used.
	 *
	 * @return 	VAPLanguageTable  The object containing the translations.
	 *
	 * @throws 	Exception
	 */
	public function load($table, $id, $tag = null)
	{
		// make sure the table is supported
		$table = $this->getTable($table);

		if (!$tag)
		{
			// get current language tag if not specified
			$tag = JFactory::getLanguage()->getTag();
		}

		// always treat the ID as an array
		// $id = array_map('intval', (array) $id);
		$id = (array) $id;

		foreach ($id as $k => $tmp)
		{
			// check if we already have a loaded translation for the
			// given product in the specified language
			if ($table->hasTranslation($tmp, $tag))
			{
				// translation available, unset ID
				unset($id[$k]);
			}
		}

		if (!$id)
		{
			return $table;
		}

		// restore linear array
		$id = array_values($id);

		$dbo = JFactory::getDbo();

		// Get all the table columns that can be translated.
		// Use the reverse lookup to obtain both the columns of
		// the translation table and the original one.
		$contents = $table->getContentColumns($lookup = true);

		// get lang tag column
		$tagcol = $table->getLangColumn();

		$q = $dbo->getQuery(true);

		// select translation columns
		foreach ($contents as $lang => $link)
		{
			// select language translation
			$q->select($dbo->qn('t.' . $lang));
			// select original content
			$q->select($dbo->qn('o.' . $link, 'original_' . $link));
		}

		// select link primary key
		$q->select($dbo->qn('o.' . $table->getLinkedPrimaryKey()));
		// select language tag
		$q->select($dbo->qn('t.' . $tagcol));

		// load original table
		$q->from($dbo->qn($table->getLinkedTable(), 'o'));
		// join translations with original records
		$q->leftjoin(
			$dbo->qn($table->getTableName(), 't') .
			' ON ' . $dbo->qn('o.' . $table->getLinkedPrimaryKey()) . ' = ' . $dbo->qn('t.' . $table->getForeignKey()) .
			' AND ' . $dbo->qn('t.' . $tagcol) . ' = ' . $dbo->q($tag)
		);
		
		// retrieve only the translations for the given ID(s)
		if (count($id) == 1)
		{
			$q->where($dbo->qn('o.' . $table->getLinkedPrimaryKey()) . ' = ' . $dbo->q($id[0]));
		}
		else
		{
			$q->where($dbo->qn('o.' . $table->getLinkedPrimaryKey()) . ' IN (' . implode(',', array_map(array($dbo, 'q'), $id)) . ')');
		}

		$dbo->setQuery($q);
		$original = $dbo->loadObjectList();

		if (!$original)
		{
			return $table;
		}

		$locales = array();

		// load translations and original records
		foreach ($original as $result)
		{
			// get linked primary key
			$fk = $result->{$table->getLinkedPrimaryKey()};

			if (!isset($locales[$fk]))
			{
				$locales[$fk] = new stdClass;
				$locales[$fk]->id      = $fk;
				$locales[$fk]->langtag = $tag;
			}

			// iterate language contents
			foreach ($contents as $lang => $link)
			{
				$k = 'original_' . $link;

				// pre-fill locale with default column value
				if (empty($locales[$fk]->{$link}))
				{
					$locales[$fk]->{$link} = $result->{$k};
				}

				// in case of translation available use it
				if (!empty($result->{$lang}))
				{
					$locales[$fk]->{$link} = $result->{$lang};
					$locales[$fk]->langtag = $result->{$tagcol};
				}
			}
		}

		foreach ($locales as $locale)
		{
			// register translation within language table
			$table->setTranslation($locale);
		}

		return $table;
	}

	/**
	 * Returns the translation of the given record.
	 *
	 * @param 	string 	 $table   The translation table ID.
	 * @param 	integer  $id      The product ID to translate.
	 * @param 	mixed 	 $tag     The language tag to use. If not specified,
	 * 						      the current one will be used.
	 *
	 * @return 	mixed 	 The translated object if exists, false otherwise.
	 *
	 * @throws 	Exception
	 */
	public function translate($table, $id, $tag = null)
	{
		if (!$tag)
		{
			// get current language tag if not specified
			$tag = JFactory::getLanguage()->getTag();
		}

		// make sure the translation is already loaded
		$table = $this->load($table, $id, $tag);

		// return translated object
		return $table->getTranslation($id, $tag);
	}

	/**
	 * Returns a list of suggestions based on the translations
	 * that have been already made for the given language tag.
	 *
	 * @param 	string 	$str  The original string to search for.
	 * @param 	mixed 	$tag  The language tag to use. If not specified,
	 * 						  the current one will be used.
	 *
	 * @return 	mixed 	A list of suggestions on success, false otherwise.
	 */
	public function getSuggestions($str, $tag = null)
	{
		// Abort in case the string is not valid.
		// Do not use /[a-zA-Z]/ regex because languages based
		// on unicode (e.g. Chinese) will never go ahead.
		if (empty($str) || is_numeric($str))
		{
			return false;
		}

		if (!$tag)
		{
			// get current language tag if not specified
			$tag = JFactory::getLanguage()->getTag();
		}

		$dbo = JFactory::getDbo();

		// get all supported language database tables
		$tables = $this->getTables();

		if (!$tables)
		{
			// no available tables, abort
			return false;
		}

		$suggestions = array();

		// iterate all the tables to start searching for a matching translation
		foreach ($tables as $table)
		{
			// Get all the table columns that can be translated.
			// Use the reverse lookup to obtain both the columns of
			// the translation table and the original one.
			$contents = $table->getContentColumns($lookup = true);

			$q = $dbo->getQuery(true);

			// select translation columns
			foreach ($contents as $lang => $link)
			{
				// select language translation
				$q->select($dbo->qn('t.' . $lang));
				// select original content
				$q->select($dbo->qn('o.' . $link, 'original_' . $link));
			}

			// select language primary key
			$q->select($dbo->qn('t.' . $table->getPrimaryKey()));

			// load translation table
			$q->from($dbo->qn($table->getTableName(), 't'));
			// load original table
			$q->from($dbo->qn($table->getLinkedTable(), 'o'));

			// filter the translations by language
			$q->where($dbo->qn('t.' . $table->getLangColumn()) . ' = ' . $dbo->q($tag));
			// join translations with original records
			$q->where($dbo->qn('o.' . $table->getLinkedPrimaryKey()) . ' = ' . $dbo->qn('t.' . $table->getForeignKey()));

			$where = array();

			// join content columns
			foreach ($contents as $link)
			{
				$where[] = $dbo->qn('o.' . $link) . ' = ' . $dbo->q($str);
			}

			if ($where)
			{
				// search for suggested translations
				$q->andWhere($where, 'OR');
			}

			$dbo->setQuery($q);
			
			// iterate records fetched
			foreach ($dbo->loadObjectList() as $record)
			{
				// iterate translatable columns
				foreach ($contents as $lang => $link)
				{
					// define original assoc key
					$k = 'original_' . $link;

					// check if the term is equals to the original column and the
					// related translation is not empty
					if (!strcasecmp($record->{$k}, $str) && !empty($record->{$lang}))
					{
						// push the related translation within the suggestions list
						$suggestions[] = $record->{$lang};
					}
				}
			}
		}

		// do not return duplicate values (array_values doesn't preserve assoc keys)
		return array_values(array_unique($suggestions));
	}

	/**
	 * Returns all the languages that owns a translation for the specified record.
	 * The default language will be always included at the beginning of the list.
	 *
	 * @param 	string 	 $table   The translation table ID.
	 * @param 	mixed    $id      Either the product ID or an array.
	 * @param 	mixed    $def     The default language tag. Accepts 3 different values:
	 *                            - null    uses the default system language;
	 *                            - false   do not include the default language;
	 *                            - string  include the specified language as default.
	 *
	 * @return 	array    A list of supported languages.
	 *
	 * @throws 	Exception
	 */
	public function getAvailableLang($table, $id, $def = null)
	{
		// make sure the table is supported
		$table = $this->getTable($table);

		if (!$id)
		{
			return $id;
		}

		$was_array = is_array($id);

		// always treat ID as an array
		$id = (array) $id;

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		$fk = $table->getForeignKey();

		// select FK and language tag
		$q->select($dbo->qn('t.' . $fk, 'id'));
		$q->select('GROUP_CONCAT(' . $dbo->qn('t.' . $table->getLangColumn()) . ') AS ' . $dbo->qn('tag'));

		// load translations table
		$q->from($dbo->qn($table->getTableName(), 't'));
		
		// retrieve only the translations for the given ID(s)
		if (count($id) == 1)
		{
			$q->where($dbo->qn('t.' . $fk) . ' = ' . $dbo->q($id[0]));
		}
		else
		{
			$q->where($dbo->qn('t.' . $fk) . ' IN (' . implode(',', array_map(array($dbo, 'q'), $id)) . ')');
		}

		$q->group($dbo->qn('t.' . $fk));

		$dbo->setQuery($q);

		$rows = [];
		
		// create a lookup of ID/languages
		foreach ($dbo->loadObjectList() as $row)
		{
			$rows[$row->id] = explode(',', $row->tag);
		}

		$map = array();

		if (is_null($def))
		{
			// get default language
			$def = VikAppointments::getDefaultLanguage();
		}

		// create a lookup of ID/languages starting from
		// the specified list of IDs
		foreach ($id as $v)
		{
			// assign the languages found to the ID, if any
			$map[$v] = isset($rows[$v]) ? $rows[$v] : array();

			// check whether the list already contains the default language
			$index = array_search($def, $map[$v]);

			if ($index !== false)
			{
				// always remove the translation
				array_splice($map[$v], $index, 1);
			}

			// add the default language at the beginning of the list
			array_unshift($map[$v], $def);
		}

		if (!$was_array)
		{
			// immediately return the available languages in case
			// the method received only one ID
			return reset($map);
		}

		return $map;
	}
	
	/**
	 * Returns all the supported language tables with the related colums.
	 *
	 * @return 	array
	 *
	 * @throws 	Exception
	 */
	public function getTables()
	{
		if ($this->tables === null)
		{
			$this->tables = array();

			// get all XML drivers
			$drivers = glob(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'tables' . DIRECTORY_SEPARATOR . '*.xml');

			foreach ($drivers as $driver)
			{
				// create language table
				$table = new VAPLanguageTable($driver);
				// push table within the list
				$this->tables[$table->getID()] = $table;
			}
		}

		return $this->tables;
	}

	/**
	 * Returns the requested language table.
	 *
	 * @param 	string 	The language table to get.
	 *
	 * @return 	VAPLanguageTable
	 *
	 * @throws 	Exception
	 */
	public function getTable($id)
	{
		// first of all get tables
		$tables = $this->getTables();

		if (!isset($tables[$id]))
		{
			// table not found, raise error
			throw new Exception(sprintf('Language table [%s] is not supported', $id), 500);
		}

		return $tables[$id];
	}
}
