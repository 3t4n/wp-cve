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

VAPLoader::import('libraries.backup.export.type');

/**
 * FULL Backup export type.
 * 
 * @since 1.7.1
 */
class VAPBackupExportTypeFull implements VAPBackupExportType
{
	/**
	 * Returns a readable name of the export type.
	 * 
	 * @return 	string
	 */
	public function getName()
	{
		return JText::translate('VAP_BACKUP_EXPORT_TYPE_FULL');
	}

	/**
	 * Returns a readable description of the export type.
	 * 
	 * @return 	string
	 */
	public function getDescription()
	{
		return JText::translate('VAP_BACKUP_EXPORT_TYPE_FULL_DESCRIPTION');
	}

	/**
	 * Configures the backup director.
	 * 
	 * @param 	VAPBackupExportDirector  $director
	 * 
	 * @return 	void
	 */
	public function build(VAPBackupExportDirector $director)
	{
		// fetch database tables to export
		$tables = $this->getDatabaseTables();

		// iterate all database tables
		foreach ($tables as $table)
		{
			// create SQL export rule
			$director->createRule('sqlfile', $table);
		}

		// register the UPDATE queries for the configuration table
		$director->createRule('sqlplain', $this->getConfigSQL());

		// fetch folders to export
		$folders = $this->getFolders();

		// iterate all folders to copy
		foreach ($folders as $folder)
		{
			// create FOLDER export rule
			$director->createRule('folder', $folder);
		}
	}

	/**
	 * Returns an array of database tables to export.
	 * 
	 * @return 	array
	 */
	protected function getDatabaseTables()
	{
		$dbo = JFactory::getDbo();

		// load all the installed database tables
		$tables = $dbo->getTableList();

		// get current database prefix
		$prefix = $dbo->getPrefix();

		// replace prefix with placeholder
		$tables = array_map(function($table) use ($prefix)
		{
			return preg_replace("/^{$prefix}/", '#__', $table);
		}, $tables);

		// remove all the tables that do not belong to VikAppointments
		$tables = array_values(array_filter($tables, function($table)
		{
			if ($table === '#__vikappointments_config')
			{
				// exclude the configuration table, which will be handled in a different way
				return false;
			}

			return preg_match("/^#__vikappointments_/", $table);
		}));

		return $tables;
	}

	/**
	 * Returns an associative array of folders to export, where the key is equals
	 * to the path to copy and the value is the relative destination path.
	 * 
	 * @return 	array
	 */
	protected function getFolders()
	{
		return [
			'media' => [
				'source'      => VAPMEDIA,
				'destination' => 'media/normal',
				'target'      => 'VAPMEDIA',
			],
			'media@small' => [
				'source'      => VAPMEDIA_SMALL,
				'destination' => 'media/small',
				'target'      => 'VAPMEDIA_SMALL',
			],
			'mailattach' => [
				'source'      => VAPMAIL_ATTACHMENTS,
				'destination' => 'mail/attachments',
				'target'      => 'VAPMAIL_ATTACHMENTS',
			],
			'mailtmpl' => [
				'source'      => VAPMAIL_TEMPLATES,
				'destination' => 'mail/tmpl',
				'target'      => 'VAPMAIL_TEMPLATES',
			],
			'invoices' => [
				'source'      => VAPINVOICE,
				'destination' => 'invoices',
				'target'      => 'VAPINVOICE',
				'recursive'   => true,
			],
			'uploads' => [
				'source'      => VAPCUSTOMERS_UPLOADS,
				'destination' => 'customers/uploads',
				'target'      => 'VAPCUSTOMERS_UPLOADS',
			],
			'avatar' => [
				'source'      => VAPCUSTOMERS_AVATAR,
				'destination' => 'customers/avatar',
				'target'      => 'VAPCUSTOMERS_AVATAR',
			],
			'documents' => [
				'source'      => VAPCUSTOMERS_DOCUMENTS,
				'destination' => 'customers/documents',
				'target'      => 'VAPCUSTOMERS_DOCUMENTS',
				'recursive'   => true,
			],
			'customcss' => [
				'source'      => JPath::clean(VAPBASE . '/assets/css/vap-custom.css'),
				'destination' => 'css',
				'target'      => ['VAPBASE', 'assets/css'],
			],
			'envcss' => [
				'source'      => VAP_CSS_CUSTOMIZER,
				'destination' => 'css/customizer',
				'target'      => 'VAP_CSS_CUSTOMIZER',
			],
		];
	}

	/**
	 * Returns an array of queries used to keep the configuration up-to-date.
	 * 
	 * @return 	array
	 */
	protected function getConfigSQL()
	{
		$dbo = JFactory::getDbo();

		$sql = [];

		// prepare update statement
		$update = $dbo->getQuery(true)->update($dbo->qn('#__vikappointments_config'));

		// define list of parameters to ignore
		$exclude = [
			'version',
			'bcv',
			'subversion',
			'update_extra_fields',
			'webhookslogspath',
			'backupfolder',
		];

		// fetch all configuration settings
		$q = $dbo->getQuery(true)
			->select($dbo->qn(['param', 'setting']))
			->from($dbo->qn('#__vikappointments_config'))
			->where($dbo->qn('param') . ' NOT IN (' . implode(',', array_map([$dbo, 'q'], $exclude)) . ')');

		$dbo->setQuery($q);
		
		// iterate all settings
		foreach ($dbo->loadObjectList() as $row)
		{
			// clear update
			$update->clear('set')->clear('where');
			// define value to set
			$update->set($dbo->qn('setting') . ' = ' . $dbo->q($row->setting));
			// define parameter to update
			$update->where($dbo->qn('param') . ' = ' . $dbo->q($row->param));

			$sql[] = (string) $update;
		}

		return $sql;
	}
}
