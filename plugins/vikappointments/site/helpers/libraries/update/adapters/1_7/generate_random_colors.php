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
 * The services created prior the 1.7 version might not have a defined color.
 * For this reason, after th eupdate we should iterate all the existing services and
 * automatically assign a color for a better visibility.
 *
 * @since 1.7
 */
class VAPUpdateRuleGenerateRandomColors1_7 extends VAPUpdateRule
{
	/**
	 * Method run during update process.
	 *
	 * @param 	mixed 	 $parent  The parent that calls this method.
	 *
	 * @return 	boolean  True on success, otherwise false to stop the flow.
	 */
	protected function run($parent)
	{
		$config = VAPFactory::getConfig();

		$this->update();

		return true;
	}

	/**
	 * Generates random colors for the services.
	 *
	 * @return 	void
	 */
	private function update()
	{
		$dbo = JFactory::getDbo();

		$model = JModelVAP::getInstance('service');

		// take all services without a color
		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id')))
			->from($dbo->qn('#__vikappointments_service'))
			->where(array(
				$dbo->qn('color') . ' = ' . $dbo->q(''),
				$dbo->qn('color') . ' IS NULL',
			), 'OR');

		$dbo->setQuery($q);
		$dbo->execute();

		if ($dbo->getNumRows())
		{
			// get list of preset colors
			$colors = JHtml::fetch('vaphtml.color.preset', $list = true, $group = false);

			foreach ($dbo->loadColumn() as $i => $service)
			{
				// extract progressive color
				$color = $colors[$i % count($colors)];

				// assign the color to the service
				$model->save(array(
					'id'    => (int) $service,
					'color' => $color,
				));
			}
		}

		return true;
	}
}
