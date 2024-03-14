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

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Activation
{
	/**
	 * Plugin tables
	 * 
	 * @var  array
	 */
	private $tables;

	/**
	 * Activation file
	 * 
	 * @var  string
	 */
	private $activation_file;

	public function __construct($data = null)
	{
		$this->tables = isset($data['tables']) ? $data['tables'] : [];
		$this->activation_file = isset($data['activation']) ? $data['activation'] : '';
    }

	/**
	 * Runs once we activate the plugin.
	 * Set plugin version and init db tables
	 * 
	 * @return  void
	 */
	public function pluginActivation()
	{
        // check and initialize tables
		if (!$this->tablesInitialized())
		{
			$this->initTables();
        }
        
        // run plugin-specific callback
        if (method_exists($this, 'onPluginActivation'))
        {
            $this->onPluginActivation();
        }
	}

	/**
	 * Checks whether the tables already exist in the database
	 * 
	 * @return  boolean
	 */
	protected function tablesInitialized()
	{
		if (!is_array($this->tables) && !count($this->tables))
		{
			return;
		}
		
		global $wpdb;
		
		foreach ($this->tables as $table)
		{
			$table = sanitize_text_field($table);
			$table = $wpdb->prefix . $table;
			
			if ($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table)) !== $table)
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Adds the tables in the database
	 * 
	 * @return  void
	 */
	public function initTables()
	{
		require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
		
		if(!is_readable($this->activation_file))
		{
			die('Please make sure that the plugins folder is readable.');
		}

		global $wpdb;

		// grab collate
		$charset_collate = $wpdb->get_charset_collate();
		
		// grab the SQL
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$installation_file = file_get_contents($this->activation_file);

		$this->replaceTablesPrefix($installation_file);
		
		// replace the wp collate
		$installation_file = str_replace('WP_COLLATE', $charset_collate, $installation_file);

		$tables = explode('-----', $installation_file);

		if (count($tables))
		{
			foreach ($tables as $table)
			{
				dbDelta($table);
			}
		}
	}

	/**
	 * Replaces the table prefix found in string
	 * 
	 * @return  void
	 */
	private function replaceTablesPrefix(&$string)
	{
		global $table_prefix;

		// replace the wp prefix
		$string = str_replace('WP_PREFIX', $table_prefix, $string);
	}
}