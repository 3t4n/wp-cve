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

VAPLoader::import('libraries.mvc.controllers.admin');

/**
 * VikAppointments export controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerExport extends VAPControllerAdmin
{
	/**
	 * Downloads a sample file for the requested import type.
	 *
	 * @return 	void
	 */
	public function download()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;

		/**
		 * Added token validation.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken())
		{
			// raise error, missing CSRF-proof token
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}

		$type  = $input->getString('import_type');
		$class = $input->getString('export_class');
		$name  = $input->getString('filename');

		VAPLoader::import('libraries.import.factory');

		// get object handler
		$handler = ImportFactory::getObject($type);

		if (!$handler)
		{
			throw new Exception('Import type not supported.', 404);
		}

		/**
		 * Load list of columns to export and raw format.
		 *
		 * @since 1.7
		 */
		$options = array();
		$options['columns'] = $input->getString('columns', array());
		$options['raw']     = $input->getBool('raw', false);

		// Inject driver parameters within options array.
		// Drivers must take care of not using any reserved keys.
		$options = array_merge($options, $input->get('import_args', array(), 'array'));

		// get export handler
		$exportable = ImportFactory::getExportable($class, $options);

		if (!$exportable)
		{
			throw new Exception('Export handler not supported.', 404);
		}

		// finalise export using the specified class
		$handler->export($exportable, $name);

		// terminate request
		$app->close();
	}

	/**
	 * Returns a form including the parameters of the requested export driver.
	 *
	 * @return 	void
	 */
	public function params()
	{
		$app 	= JFactory::getApplication();
		$input 	= $app->input;

		/**
		 * Added token validation.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken())
		{
			// missing CSRF-proof token
			UIErrorFactory::raiseError(403, JText::translate('JINVALID_TOKEN'));
		}

		$type 	= $input->getString('import_type');
		$class  = $input->getString('export_class');

		VAPLoader::import('libraries.import.factory');

		// get object handler
		$handler = ImportFactory::getObject($type);

		if (!$handler)
		{
			throw new Exception('Import type not supported.', 404);
		}

		// get export handler
		$form = ImportFactory::getExportableForm($class);

		$html = '';

		if ($form)
		{
			$html = $form->renderFieldset(null);
		}

		// send form to caller
		$this->sendJSON(json_encode($html));
	}
}
