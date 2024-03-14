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
 * Concrete representation of a translatable table.
 *
 * @since 1.7
 */
class VAPLanguageTable
{
	/**
	 * The XML instance.
	 *
	 * @var SimpleXMLElement
	 */
	protected $xml;

	/**
	 * Translations pool.
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 * Class constructor.
	 *
	 * @param 	mixed 	$node 	Either the file path of the XML to load or
	 * 							a XML string or a SimpleXMLElement instance.
	 */
	public function __construct($node)
	{
		if ($node instanceof SimpleXMLElement)
		{
			// assign XML instance
			$this->xml = $node;
		}
		else if (is_file($node))
		{
			// load XML from file
			$this->xml = @simplexml_load_file($node);
		}
		else
		{
			// load XML from given string
			$this->xml = @simplexml_load_string($node);
		}

		// check if we received a string
		if (!$this->xml)
		{
			throw new Exception(sprintf('Unable to parse the translation driver: %s', (string) $node), 500);
		}
	}

	/**
	 * Sets the translation for the given record.
	 * The translation must be an object containing the
	 * ID property to identify the related record.
	 *
	 * @param 	array|object  $translation  The translated record.
	 *
	 * @return 	self 		  This object to support chaining.
	 */
	public function setTranslation($translation)
	{
		$translation = (object) $translation;

		// make sure the ID and langtag are available
		if (!empty($translation->id) && !empty($translation->langtag))
		{
			if (!isset($this->data[$translation->id]))
			{
				$this->data[$translation->id] = array();
			}

			$this->data[$translation->id][strtolower($translation->langtag)] = $translation;
		}

		return $this;
	}

	/**
	 * Returns the translation for the given record.
	 *
	 * @param 	integer  $id   The translated product ID.
	 * @param 	mixed 	 $tag  If specified, the translation must
	 * 						   match the given one.
	 *
	 * @return  mixed    The translation if exists, null otherwise.
	 */
	public function getTranslation($id, $tag = null)
	{
		if ($this->hasTranslation($id, $tag))
		{
			if ($tag)
			{
				// return specific tag translation
				return $this->data[$id][strtolower($tag)];
			}

			// return all available translations
			return $this->data[$id];
		}

		return null;
	}

	/**
	 * Checks whether the given product owns a translation.
	 *
	 * @param 	integer  $id   The product ID.
	 * @param 	mixed 	 $tag  If specified, the translation must
	 * 						   match the given one.
	 *
	 * @return  boolen   True if exists, false otherwise.
	 */
	public function hasTranslation($id, $tag = null)
	{
		if (!isset($this->data[$id]))
		{
			// missing translation
			return false;
		}

		// if tag filter is specified but the translation doesn't
		// match the given language, return false
		if ($tag && !isset($this->data[$id][strtolower($tag)]))
		{
			return false;
		}

		return true;
	}

	/**
	 * Returns the table identifier.
	 *
	 * @return 	string
	 */
	public function getID()
	{
		return (string) $this->xml->attributes()->id;
	}

	/**
	 * Returns the table name.
	 *
	 * @return 	string
	 */
	public function getTableName()
	{
		return (string) $this->xml->attributes()->table;
	}

	/**
	 * Returns the original table linked to this translation table.
	 *
	 * @return 	string
	 */
	public function getLinkedTable()
	{
		$link = (string) $this->xml->attributes()->link;

		if (!$link)
		{
			// link not provided, use standard notation
			$link = preg_replace("/#__vikappointments_lang_/i", '#__vikappointments_', $this->getTableName());
		}

		return $link;
	}

	/**
	 * Returns a list of supported columns.
	 *
	 * @return 	array
	 */
	public function getColumns()
	{
		return $this->searchColumns('*');
	}

	/**
	 * Returns the primary key column.
	 *
	 * @return 	string  The PK column.
	 */
	public function getPrimaryKey()
	{	
		$columns = $this->searchColumns('pk');

		if ($columns)
		{
			return $columns[0];
		}

		// fallback to ID
		return 'id';
	}

	/**
	 * Returns the primary key column of the linked table.
	 *
	 * @return 	string  The PK column.
	 */
	public function getLinkedPrimaryKey()
	{
		// search for LINK attribute in FK column
		$columns = array_filter($this->searchColumns('fk', 'rule', 'link'));

		if ($columns)
		{
			return $columns[0];
		}

		// fallback to ID
		return 'id';
	}

	/**
	 * Returns the foreign key column linked to the original table.
	 *
	 * @return 	string  The FK column if exists.
	 *
	 * @throws 	Exception
	 */
	public function getForeignKey()
	{	
		$columns = $this->searchColumns('fk');

		if (!$columns)
		{
			throw new Exception(sprintf('Missing foreign key for [%s] table', __CLASS__), 400);
		}

		return $columns[0];
	}

	/**
	 * Returns the XML id of the parent table.
	 *
	 * @return 	mixed  The table ID if exists, false otherwise.
	 */
	public function getParentTable()
	{	
		$columns = $this->searchColumns('parent', 'rule', 'link');

		if ($columns)
		{
			return $columns[0];
		}

		return false;
	}

	/**
	 * Returns the foreign key column linked to the parent table.
	 *
	 * @return 	mixed  The FK column if exists, false otherwise.
	 */
	public function getParentKey()
	{	
		$columns = $this->searchColumns('parent');

		if ($columns)
		{
			return $columns[0];
		}

		return false;
	}

	/**
	 * Returns the foreign key column linked to the parent table
	 * of the original table.
	 *
	 * @return 	mixed  The FK column if exists, false otherwise.
	 */
	public function getLinkedParentKey()
	{	
		$columns = $this->searchColumns('parent', 'rule', 'original');

		if ($columns)
		{
			return $columns[0];
		}

		return false;
	}

	/**
	 * Returns the language tag column.
	 *
	 * @return 	string  The lang column if exists.
	 *
	 * @throws 	Exception
	 */
	public function getLangColumn()
	{	
		$columns = $this->searchColumns('language');

		if (!$columns)
		{
			throw new Exception(sprintf('Missing language tag for [%s] table', __CLASS__), 400);
		}

		return $columns[0];
	}

	/**
	 * Returns a list of translatable columns.
	 *
	 * @param 	boolean  $link  True to return an associative array containing
	 * 							the columns of the translation table (key) and the
	 * 							columns of the linked original table (value).
	 *
	 * @return 	array
	 */
	public function getContentColumns($link = false)
	{
		// get content columns
		$columns = $this->searchColumns('content');

		if (!$link)
		{
			return $columns;
		}

		// get related columns of linked table
		$link = $this->searchColumns('content', 'rule', 'link');

		$assoc = array();

		for ($i = 0; $i < count($columns); $i++)
		{
			$assoc[$columns[$i]] = $link[$i] ? $link[$i] : $columns[$i];
		}

		return $assoc;
	}

	/**
	 * Helper method used to scan the table columns information.
	 *
	 * @param 	string 	$search  The term to search for. Use '*' to skip search.
	 * @param 	string 	$key     The attribute in which to search the term.
	 * @param 	string 	$return  The attribute to return.
	 *
	 * @return 	array   A list of matching columns.
	 */
	protected function searchColumns($search, $key = 'rule', $return = 'name')
	{
		$results = array();

		foreach ($this->xml->columns->column as $column)
		{
			$attrs = $column->attributes();

			if ($search == '*' || preg_match("/^{$search}$/i", $attrs->{$key}))
			{
				if ($return == '*')
				{
					$results[] = $attrs;
				}
				else
				{
					$results[] = (string) $attrs->{$return};
				}
			}
		}

		// do not return empty values
		return $results;
	}
}
