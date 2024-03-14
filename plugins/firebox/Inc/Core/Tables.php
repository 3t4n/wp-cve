<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Tables
{
	/**
	 * Caches the FireBox tables.
	 * 
	 * @var  array
	 */
	private $tables = [];
	
	public function __get($table = '')
	{
		if (isset($this->tables[$table]))
		{
			return $this->tables[$table];
		}
		
        $table_class = '\FireBox\Core\Tables\\' . ucfirst(strtolower($table));

        if (!class_exists($table_class))
        {
            return false;
        }

		$class = new $table_class();
		
		$this->tables[$table] = $class;

		return $this->tables[$table];
	}
}