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
 * Event used to perform a test connection between the caller and this end-point.
 *
 * @since 1.7
 */
class VAPApiEventConnectionPing extends VAPApiEvent
{
	/**
	 * The custom action that the event have to perform.
	 * This method should not contain any exit or die function, 
	 * otherwise the event won't be properly terminated.
	 *
	 * @param 	array           $args      The provided arguments for the event.
	 * @param 	VAPApiResponse  $response  The response object for admin.
	 *
	 * @return 	mixed           The response to output or the error message (VAPApiError).
	 */
	protected function doAction(array $args, VAPApiResponse $response)
	{
		// connection ping done correctly
		$response->setStatus(1);

		// include some details about the program, such as the version and the platform id
		$obj = new stdClass;
		$obj->status   = 1;
		$obj->version  = VIKAPPOINTMENTS_SOFTWARE_VERSION;
		$obj->platform = VersionListener::getPlatform();

		if ($args)
		{
			// send payload back
			$obj->payload = $args;
		}

		// let the application framework safely output the response
		return $obj;
	}

	/**
	 * @override
	 * Returns true if the plugin is always authorised, otherwise false.
	 * When this value is false, the system will need to authorise the plugin 
	 * through the ACL of the user.
	 *
	 * @return 	boolean  Always true.
	 */
	public function alwaysAllowed()
	{
		// the plugin is always allowed
		return true;
	}

	/**
	 * @override
	 * Returns the description of the plugin.
	 *
	 * @return 	string
	 */
	public function getDescription()
	{
		// read the description HTML from a layout
		return JLayoutHelper::render('api.plugins.connection_ping', array('plugin' => $this));
	}
}
