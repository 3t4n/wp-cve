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

JLoader::import('adapter.mvc.models.form');

/**
 * VikAppointments plugin Shortcodes model.
 *
 * @since 1.0
 * @see   JModelForm
 */
class VikAppointmentsModelShortcodes extends JModelForm
{
	/**
	 * Returns a list of shortcodes.
	 * 
	 * @param 	mixed 	$columns  Either a column or a list of columns to select.
	 *                            Use '*' to load all the columns (default).
	 * 
	 * @return 	array   An array of records.
	 */
	public function all($columns = '*')
	{
		$dbo = JFactory::getDbo();

		if ($columns != '*')
		{
			if (is_string($columns))
			{
				$columns = $dbo->qn($columns);
			}
			else
			{
				$columns = implode(', ', array_map(array($dbo, 'qn'), $columns));
			}
		}

		$q = "SELECT {$columns} FROM `#__vikappointments_wpshortcodes` ORDER BY `id` ASC";

		$dbo->setQuery($q);
		$dbo->execute();

		if ($dbo->getNumRows())
		{
			return $dbo->loadObjectList();
		}

		return array();
	}

	/**
	 * Finds the best post_id for the passed view(s).
	 * Rather than using all() to get the first post_id,
	 * with this method we can get the exact post_id for
	 * the type of shortcode passed along with the views.
	 * 
	 * @param 	mixed 	$views 	string or array of strings
	 * @param 	string 	$lang 	the language attribute to look for
	 *
	 * @return 	mixed 	integer for the post_id, null otherwise
	 * 
	 * @uses 	all()
	 * @since 	1.0.14
	 */
	public function best($views, $lang = null)
	{
		if (empty($views))
		{
			return null;
		}

		if (is_scalar($views))
		{
			// always convert the views to look for into an array
			$views = array($views);
		}

		// get all shortcodes of any type
		$shortcodes = $this->all();

		// current language
		$lang = is_null($lang) ? JFactory::getLanguage()->getTag() : $lang;

		if (!count($shortcodes))
		{
			return null;
		}

		// loop through the shortcodes and look for the requested views
		do
		{
			// the view to look for
			$reqview = array_shift($views);

			// highest score counter
			$last_count = 0;
			
			foreach ($shortcodes as $k => $v)
			{
				// start the score counter
				$count = 0;

				if (!strcasecmp($v->type, $reqview))
				{
					// view found, increment best score
					$count++;

					if ($lang == $v->lang) {
						// same language, increment best score
						$count++;
					}
				}

				if ($count > $last_count)
				{
					// update counters
					$last_count = $count;
					$code_index = $k;
				}
			}

			if ($last_count > 0)
			{
				// best view found, return this post_id
				return $shortcodes[$code_index]->post_id;
			}

		} while (count($views));

		// return the first post_id available in the shortcodes
		return $shortcodes[0]->post_id;
	}

	/**
	 * Method to get a table object.
	 *
	 * @param   string  $name     The table name.
	 * @param   string  $prefix   The class prefix.
	 * @param   array   $options  Configuration array for table.
	 *
	 * @return  JTable  A table object.
	 *
	 * @since   1.2
	 */
	public function getTable($name = '', $prefix = 'JTable', $options = array())
	{
		if (!$name)
		{
			$name   = 'shortcode';
			$prefix = 'VAPTable';
		}

		return parent::getTable($name, $prefix, $options);
	}
}
