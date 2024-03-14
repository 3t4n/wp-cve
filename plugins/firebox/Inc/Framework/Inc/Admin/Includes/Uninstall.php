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

class Uninstall
{
	/**
	 * Plugin tables
	 * 
	 * @var  array
	 */
	private $tables;

	/**
	 * Uninstall file
	 * 
	 * @var  string
	 */
	private $uninstall_file;

	public function __construct($data = [])
	{
		$this->uninstall_file = isset($data['uninstall']) ? $data['uninstall'] : '';
    }

	/**
	 * Runs once we uninstall the plugin.
	 * 
	 * @return  void
	 */
	public function pluginUninstall()
	{
        $this->doUninstall();
        
        // run plugin-specific callback
        if (method_exists($this, 'onPluginUninstall'))
        {
            $this->onPluginUninstall();
        }
	}

	/**
	 * Replaces the table prefix found in string
	 * 
	 * @return  void
	 */
	public function replaceTablesPrefix(&$string)
	{
		global $table_prefix;

		// replace the wp prefix
		$string = str_replace('WP_PREFIX', $table_prefix, $string);
	}

	/**
	 * Deletes tables from the database
	 * 
	 * @return  void
	 */
	public function doUninstall()
	{
		if (!$this->uninstall_file)
		{
			return;
		}
		
		require_once(ABSPATH . '/wp-admin/includes/upgrade.php');

		if(!is_readable($this->uninstall_file))
		{
			die(fpframework()->_('FPF_ENSURE_PLUGIN_FOLDER_IS_READABLE'));
		}

		global $wpdb;

		// grab collate
		$charset_collate = $wpdb->get_charset_collate();

		// grab the SQL
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$uninstall_file_contents = file_get_contents($this->uninstall_file);

		$this->replaceTablesPrefix($uninstall_file_contents);
		
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$wpdb->query($uninstall_file_contents);
	}
}