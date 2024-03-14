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

VAPLoader::import('libraries.menu.custom');

/**
 * Extends the CustomShape class to display a button to check the Joomla software version.
 *
 * @since 1.5
 * @since 1.6.3 Renamed from LeftBoardMenuVersion to LeftboardCustomShapeVersion.
 */
class LeftboardCustomShapeVersion extends CustomShape
{
	/**
	 * @override
	 * Builds and returns the html structure of the custom menu item.
	 * This method must be implemented to define a specific graphic of the custom item.
	 *
	 * @return 	string 	The html of the custom item.
	 */
	public function buildHtml()
	{
		$model = JModelVAP::getInstance('updateprogram');

		// check if VikUpdater is available
		$callable  = $model->isSupported();

		// prepare display data
		$data = array(
			'newupdate'  => false,
			'vikupdater' => (bool) $callable,
			'connect'    => false,
			'url'        => $this->get('url'),
			'label'      => $this->get('label'),
			'title' 	 => '',
		);

		// search for a cached update
		$result = $model->getVersionDetails();

		if ($result)
		{ 
			if ($result->status)
			{
				if ($result->response->status)
				{
					$data['label'] = $result->response->shortTitle;
					$data['title'] = $result->response->title;

					if ($result->response->compare == 1)
					{
						$data['newupdate'] = 1;
					}
				}
				else
				{
					$data['label'] = JText::translate('ERROR');
					$data['title'] = $result->response->error;
				}
			}
			else
			{
				$data['label'] = JText::translate('ERROR');
			}
		}

		$data['connect'] = !$result;

		$layout = new JLayoutFile('menu.leftboard.custom.version');
		
		return $layout->render($data);
	}
}
