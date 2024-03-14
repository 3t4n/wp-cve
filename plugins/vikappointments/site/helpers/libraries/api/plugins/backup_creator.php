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
 * Event used to create a backup archive of the contents created through VikAppointments.
 *
 * @since 1.7
 */
class VAPApiEventBackupCreator extends VAPApiEvent
{
	/**
	 * The custom action that the event have to perform.
	 * This method should not contain any exit or die function, 
	 * otherwise the event won't be properly terminated.
	 *
	 * @param 	array           $args      The provided arguments for the event.
	 * @param 	VAPApiResponse  $response  The response object for admin.
	 *
	 * @return 	mixed           The response to output or the error message (VAPApiError).
	 */
	protected function doAction(array $args, VAPApiResponse $response)
	{
		$response->setStatus(1);

		$config = VAPFactory::getConfig();

		// create backup model
		$model = JModelVAP::getInstance('backup');

		$obj = new stdClass;
		$obj->created = new stdClass;
		$obj->deleted = [];

		try
		{
			// create back-up
			$archive = $model->save([
				'action' => 'create',
				'prefix' => 'cron_',
			]);

			$obj->created->path = $archive;
			$obj->created->url  = VAPApplication::getInstance()->getUrlFromPath($archive);

			// register response
			$response->setContent("Back-up <span style=\"color:#090;\">created</span> successfully!\n\n")->appendContent('<b>' . $archive . '</b>');
		}
		catch (Exception $e)
		{
			// an error occurred while creating the back-up
			$response->setContent($e->getMessage())->setStatus(0);

			// propagate error
			throw $error;
		}

		// check whether we should limit the back-up creation
		if (isset($args['maxbackup']))
		{
			// sanitize received element
			$args['maxbackup'] = max([1, abs($args['maxbackup'])]);

			// get list of created archives
			$files = JFolder::files(dirname($archive), '^cron_backup_', $recursive = false, $fullpath = true);

			// check whether the number of created backups exceeded the maximum threshold
			$diff = count($files) - $args['maxbackup'];
			
			if ($diff > 0)
			{
				// sort the files by ascending creation date
				usort($files, function($a, $b)
				{
					return filemtime($a) - filemtime($b);
				});

				// take the first N archives to delete
				foreach (array_splice($files, 0, $diff) as $file)
				{
					// delete the files
					if (JFile::delete($file))
					{
						$filename = basename($file);

						$response->appendContent("\n\n<span style=\"color:#900;\">Deleted</span> old archive: <b>{$filename}</b>");
						$obj->deleted[] = $filename;
					}
				}
			}
		}

		// let the application framework safely output the response
		return $obj;
	}

	/**
	 * @override
	 * Returns the description of the plugin.
	 *
	 * @return 	string
	 */
	public function getDescription()
	{
		// read the description HTML from a layout
		return JLayoutHelper::render('api.plugins.backup_creator', array('plugin' => $this));
	}
}
