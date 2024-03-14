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
 * The new translation system moved the translation of the variations within a separated table.
 * For this reason, we should attempt to decode the stored variations and create new records.
 *
 * @since 1.7
 */
class VAPUpdateRuleOptionVarTranslations1_7 extends VAPUpdateRule
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
		$this->migrate();

		return true;
	}

	/**
	 * Moves the translations into a different table.
	 *
	 * @return 	void
	 */
	private function migrate()
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select('*')
			->from($dbo->qn('#__vikappointments_lang_option'));

		$dbo->setQuery($q);
		$dbo->execute();

		if ($dbo->getNumRows())
		{
			foreach ($dbo->loadObjectList() as $lang)
			{
				// extract variations from translations
				$values = $lang->vars_json ? json_decode($lang->vars_json, true) : array();

				if (!$values)
				{
					// no variations to migrate
					continue;
				}

				// iterate all the variations
				foreach ($values as $var_id => $var_name)
				{
					// create variation translation
					$var = new stdClass;
					$var->id           = 0;
					$var->name         = $var_name;
					$var->tag          = $lang->tag;
					$var->id_variation = $var_id;
					$var->id_parent    = $lang->id_option;

					// insert record
					$dbo->insertObject('#__vikappointments_lang_option_value', $var, 'id');
				}
			}
		}
	}
}
