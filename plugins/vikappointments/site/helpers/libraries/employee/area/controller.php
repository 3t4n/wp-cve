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
 * Employee area controller base class.
 *
 * @since 1.7
 */
class VAPEmployeeAreaController extends VAPControllerAdmin
{
	/**
	 * Constructor.
	 *
	 * @param 	array  $config  An optional associative array of configuration settings.
	 */
	public function __construct($config = array())
	{
		// load administration language, since almost all the used texts are already defined
		VikAppointments::loadLanguage(JFactory::getLanguage()->getTag(), JPATH_ADMINISTRATOR);

		// invoke parent
		parent::__construct($config);
	}

	/**
	 * Set a URL for browser redirection.
	 *
	 * @param 	string  $url   URL to redirect to.
	 * @param 	string  $msg   Message to display on redirect. Optional, defaults to value set internally by controller, if any.
	 * @param 	string  $type  Message type. Optional, defaults to 'message' or the type set by a previous call to setMessage.
	 *
	 * @return 	static  This object to support chaining.
	 */
	public function setRedirect($url, $msg = null, $type = null)
	{
		// check whether the specified URL starts with "index.php"
		if (preg_match("/^index\.php/", $url))
		{
			// extract selected menu item from request
			$itemid = JFactory::getApplication()->input->getUint('Itemid');

			if ($itemid)
			{
				if (preg_match("/\?/", $url))
				{
					// append to query string
					$url .= '&';
				}
				else
				{
					// create query string
					$url .= '?';
				}

				// preserve menu item
				$url .= 'Itemid=' . $itemid;
			}

			// finally rewrite the URL
			$url = JRoute::rewrite($url, false);
		}

		// invoke parent set the URL correctly
		return parent::setRedirect($url, $msg, $type);
	}
}
