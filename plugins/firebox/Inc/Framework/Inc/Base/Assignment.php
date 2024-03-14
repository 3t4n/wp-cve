<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Base;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

/**
 *  Assignment Class
 */
class Assignment
{
	/**
	 *  Date Object
	 *
	 *  @var  object
	 */
    public $date;
    
	/**
	 *  User Object
	 *
	 *  @var  object
	 */
	public $user;

	/**
	 *  Assignment Selection
	 *
	 *  @var  mixed
	 */
	public $selection;

	/**
	 *  Assignment Parameters
	 *
	 *  @var  mixed
	 */
	public $params;

	/**
	 *  Assignment State (Include|Exclude)
	 *
	 *  @var  string
	 */
    public $assignment;
    
    /**
     *  Framework factory object
     */
    public $factory;

	/**
	 *  Class constructor
	 *
	 *  @param  object  $assignment
	 *  @param  object  $request
	 *  @param  object  $date
	 */
	public function __construct($options = null, $factory = null)
	{
        // Save the factory object
		$this->factory = is_null($factory) ? new \FPFramework\Base\Factory() : $factory;

		// Set General WP Objects
		$this->user = $this->factory->getUser();

		// Assignment options object is optional as there are Assignments such as the Component-based, which don't rely
		// on user selection. For instance, in the SmartTags\Article, we need to check whether the user sees an Article, 
		// so we don't need any user selection.
		if ($options)
		{
			$this->selection        = isset($options->selection) ? $options->selection : '';
			$this->assignment_state = isset($options->assignment_state) ? $options->assignment_state : 'include';
			$this->params           = isset($options->params) ? $options->params : null;
		}
    }
    
    /**
     *  Base assignment check
     * 
     *  @return bool
     */
	public function pass()
	{
    	return $this->passSimple($this->value(), $this->selection);
	}

	/**
	 *  Checks if a value (needle) exists in an array (haystack)
	 *
	 *  @param   mixed   $needle     The searched value.
	 *  @param   array   $haystack   The array
	 *
	 *  @return  bool
	 */
	public function passSimple($needle, $haystack)
	{
		if (empty($haystack))
		{
			return false;
		}
		
		$needle = $this->makeArray($needle);
		$pass   = false;

		foreach ($needle as $value)
		{
			if (in_array(strtolower($value), array_map('strtolower', $haystack)))
			{
				$pass = true;
				break;
			}
		}

		return $pass;
	}
	
	/**
	 *  Returns all parent rows
	 *
	 *  @param   integer  $id      Row primary key
	 *  @param   string   $table   Table name
	 *  @param   string   $parent  Parent column name
	 *  @param   string   $child   Child column name
	 *
	 *  @return  array             Array with IDs
	 */
	public function getParentIds($id = 0, $table = 'term_taxonomy', $parent = 'parent', $child = 'term_id')
	{
		if (!$id)
		{
			return [];
		}

		// cache key
		$hash = md5('getParentIds_' . $id . '_' . $table . '_' . $parent . '_' . $child);

		// check cache
		if ($parent_ids = wp_cache_get($hash))
		{
			return $parent_ids;
		}
		
		global $wpdb;
		
		$parent_ids = array();

		$table = $wpdb->prefix . $table;
		while ($id)
		{
			$id = $wpdb->get_row($wpdb->prepare("SELECT t." . esc_sql($parent) . " FROM " . esc_sql($table) . " as t WHERE t." . esc_sql($child) . " = %d", esc_sql((int) $id)));
			
			if (!isset($id->parent))
			{
				break;
			}
			$id = $id->parent;

			// Break if no parent is found or parent already found before for some reason
			if (!$id || in_array($id, $parent_ids))
			{
				break;
			}

			$parent_ids[] = $id;
		}

		// set cache
		wp_cache_set($hash, $parent_ids);

		return $parent_ids;
	}

	/**
	 *  Makes array from object
	 *
	 *  @param   object  $object  
	 *
	 *  @return  array
	 */
	public function makeArray($object)
	{
		if (is_array($object))
		{
			return $object;
		}

		if (!is_array($object))
		{
			$x = explode(' ', $object);
			return $x;
		}
	}
    
    /**
     *  Splits a keyword string on commas and newlines
     *
     *  @param string $keywords
     *  @return array
     */
    protected function splitKeywords($keywords)
    {
        if (empty($keywords) || !is_string($keywords))
        {
            return [];
        }

        // replace newlines with commas
        $keywords = str_replace("\r\n", ',', $keywords);

        // split keywords on commas
        $keywords = explode(',', $keywords);
        
        // trim entries
        $keywords = array_map(function($str)
        {
            return trim($str);
        }, $keywords);

        // filter out empty strings and return the resulting array
        return array_filter($keywords, function($str)
        {
            return !empty($str);
        });
	}
}