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
 * VikAppointments services reports controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerReportsser extends VAPControllerAdmin
{
	/**
	 * Task used to download the services reports data.
	 *
	 * @return 	void
	 */
	public function download()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;

		// get services
		$cid = $input->get('cid', array(), 'uint');

		// get controller model
		$model = $this->getModel();

		// download reports
		$model->download($cid);

		// terminate session
		$app->close();
	}
}
