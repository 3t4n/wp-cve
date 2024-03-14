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
 * After the implementation of the customers notes, the default Notes textarea might result redundant. For this reason,
 * for all the customers that specify some notes, we need to automatically create a new (private) record under the
 * `#__vikappointments_user_notes` table.
 * 
 * The same thing have to be applied to the records under `#__vikappointments_reservation`, which might already have some
 * notes created through the apposite editor.
 *
 * @since 1.7
 */
class VAPUpdateRuleUserNotesMigrator1_7 extends VAPUpdateRule
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

		$this->migrate();

		return true;
	}

	/**
	 * Migrates the deprecated notes columns.
	 *
	 * @return 	void
	 */
	private function migrate()
	{
		$dbo = JFactory::getDbo();

		$model = JModelVAP::getInstance('usernote');

		// take all customers with a note
		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'notes')))
			->from($dbo->qn('#__vikappointments_users'))
			->where($dbo->qn('notes') . ' <> ' . $dbo->q(''));

		$dbo->setQuery($q);
		$dbo->execute();

		if ($dbo->getNumRows())
		{
			foreach ($dbo->loadObjectList() as $user)
			{
				// create a new private user note
				$model->save(array(
					'id_user' => $user->id,
					'content' => $user->notes,
				));
			}
		}

		// do the same with the reservations
		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'notes')))
			->from($dbo->qn('#__vikappointments_reservation'))
			->where($dbo->qn('notes') . ' <> ' . $dbo->q(''));

		$dbo->setQuery($q);
		$dbo->execute();

		if ($dbo->getNumRows())
		{
			foreach ($dbo->loadObjectList() as $app)
			{
				// create a new private user note
				$model->save(array(
					'group'     => 'appointments',
					'id_parent' => $app->id,
					'content'   => $app->notes,
				));
			}
		}

		return true;
	}
}
