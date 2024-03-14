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

VAPLoader::import('libraries.mail.template');

/**
 * Factory class used to instantiate the right notification provider.
 *
 * @since 1.7
 */
abstract class VAPMailFactory
{
	/**
	 * Returns an instance of the notification provider.
	 *
	 * @param 	string 	$id 	  The id/alias of the provider to load.
	 * @param 	mixed 	$args...  Some additional arguments to use when
	 * 							  instantiating the provider.
	 *
	 * @return 	VAPMailTemplate
	 */
	public static function getInstance($id)
	{
		// try to load the file
		if (!VAPLoader::import('libraries.mail.templates.' . $id))
		{
			// file not found
			throw new Exception(sprintf('Mail template [%s] not found', $id), 404);
		}

		// fetch class name
		$classname = 'VAPMailTemplate' . ucfirst($id);

		// make sure the class exists
		if (!class_exists($classname))
		{
			// class not found
			throw new Exception(sprintf('Mail template [%s] class not found', $classname), 404);
		}

		// fetch arguments to pass to the constructor by excluding the first argument
		$args = func_get_args();
		$args = array_splice($args, 1);

		// create reflection of the provider
		$class = new ReflectionClass($classname);
		// instantiate provider class
		$obj = $class->newInstanceArgs($args);

		// make sure the class is a valid instance
		if (!$obj instanceof VAPMailTemplate)
		{
			// not a valid instance
			throw new Exception(sprintf('Mail template [%s] class is not a valid instance', $classname), 400);
		}

		return $obj;
	}

	/**
	 * Helper method used to trigger a plugin event before sending the e-mail.
	 * Any other parameter specified after the target will be included as
	 * argument for the plugin event.
	 *
	 * @param 	string 	 $id 		The ID of the mail handler (filename).
	 * @param 	string 	 $what 		Either "subject", "content" or "attachment",
	 *                              depending on what it is needed to edit.
	 * @param 	mixed 	 &$target 	The content of the target to be edited.
	 *
	 * @return 	boolean  False in case the e-mail sending has been prevented, true otherwise.
	 */
	public static function letPluginsManipulateMail($id, $what, &$target)
	{
		// get event dispatcher
		$dispatcher = VAPFactory::getEventDispatcher();

		// fetch event name based on what we need to fetch and mail alias
		$event = 'onBeforeSendMail' . ucfirst($what) . ucfirst($id);

		// get all arguments
		$args = func_get_args();
		// keep only the additional arguments
		$args = array_splice($args, 3);

		// merge target within arguments
		$args = array_merge(array(&$target), $args);

		try
		{
			/**
			 * Triggers an event to let the plugins be able to handle the subject of
			 * the e-mail and the HTML contents of the related template.
			 *
			 * The event name is built as:
			 * onBeforeSendMail[Subject|Content|Attachment][Class]
			 *
			 * The event might specified additional arguments, such as the details of
			 * the reservation/order.
			 *
			 * When registering an attachment, the file path should be set as key of
			 * the array and the value should be "0" to auto-delete the file after
			 * sending the e-mail or "1" to always keep it.
			 *
			 * @param 	mixed 	 &$target  Either the subject, the HTML content, or an
			 *                             array or e-mail attachments, depending on the
			 *                             $what argument that was passed to this method.
			 *
			 * @return 	boolean  Return false to prevent e-mail sending.
			 *
			 * @since 	1.7
			 */
			$res = $dispatcher->trigger($event, $args);
		}
		catch (Exception $e)
		{
			// do not break the process because of a plugin error
			$res = array();
		}

		// check if at least a plugin returned FALSE to prevent e-mail sending 
		return !in_array(false, $res, true);
	}
}
