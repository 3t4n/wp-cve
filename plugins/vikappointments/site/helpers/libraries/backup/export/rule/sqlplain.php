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

VAPLoader::import('libraries.backup.export.rule');

/**
 * Plain SQL Backup export rule.
 * 
 * @since 1.7.1
 * @since 1.7.4  The rule now directly extends `VAPBackupExportRuleSql` for a better reusability.
 */
class VAPBackupExportRuleSqlplain extends VAPBackupExportRuleSql
{
	/**
	 * An array of SQL statements.
	 * 
	 * @var array
	 */
	protected $queries = [];

	/**
	 * Returns the rule identifier.
	 * 
	 * @return 	string
	 */
	public function getRule()
	{
		// treat as SQL role
		return 'sql';
	}

	/**
	 * Returns the rules instructions.
	 * 
	 * @return 	mixed
	 */
	public function getData()
	{
		return $this->queries;
	}

	/**
	 * Configures the rule to work according to the specified data.
	 * 
	 * @param 	mixed 	$data  Either a query string or an array.
	 * 
	 * @return 	void
	 */
	protected function setup($data)
	{
		// reset all the registered query
		$this->queries = [];
		
		foreach ((array) $data as $query)
		{
			/**
			 * Register query through the apposite helper provided by the parent class.
			 * This way we can prevent the issue that occurs on WordPress while exporting SQL queries
			 * without executing them, namely that a "%" is always escaped with a random hash.
			 * 
			 * @since 1.7.4
			 */
			$this->registerQuery($query);
		}
	}
}
