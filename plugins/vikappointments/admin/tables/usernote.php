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
 * VikAppointments user note table.
 *
 * @since 1.7
 */
class VAPTableUsernote extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_user_notes', 'id', $db);
	}

	/**
	 * Method to bind an associative array or object to the Table instance. This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   array|object  $src     An associative array or object to bind to the Table instance.
	 * @param   array|string  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 */
	public function bind($src, $ignore = array())
	{
		$src = (array) $src;

		$now = JFactory::getDate()->toSql();

		if (empty($src['id']))
		{
			// set creation date and author
			$src['createdon'] = $now;
			$src['author'] = JFactory::getUser()->id;
		}
		else
		{
			if (!isset($src['modifiedon']))
			{
				// auto-fill modification date
				$src['modifiedon'] = $now;
			}
		}

		if (empty($src['title']))
		{
			// missing title, use a default text "Y-m-d H:i:s"
			$src['title'] = JHtml::fetch('date', $now, JText::translate('DATE_FORMAT_LC6'));
		}

		if (isset($src['attachments']))
		{
			if (!is_array($src['attachments']))
			{
				// convert JSON string into an array
				$src['attachments'] = json_decode($src['attachments']);
			}

			// get rid of the base upload path to save space
			$src['attachments'] = array_map(function($path)
			{
				return str_replace(VAPCUSTOMERS_DOCUMENTS . DIRECTORY_SEPARATOR, '', $path);
			}, $src['attachments']);

			if ($src['attachments'] && empty($src['secret']))
			{
				// extract secret key from last uploaded file
				$src['secret'] = basename(dirname(end($src['attachments'])));
			}

			// stringify attachments in JSON
			$src['attachments'] = json_encode($src['attachments']);
		}

		if (isset($src['tags']) && !is_string($src['tags']))
		{
			// stringify tags in a comma-separated list
			$src['tags'] = implode(',', $src['tags']);
		}

		// bind the details before save
		return parent::bind($src, $ignore);
	}
}
