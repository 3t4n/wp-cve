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
 * VikAppointments API user implementors.
 * This class is used from the framework to connect the users and to authorise the events.
 *
 * @see VAPApiUser
 * @see VAPApiEvents
 *
 * @since 1.7
 */
class VAPApiLogin extends VAPApiUser
{
	/**
	 * Class constructor.
	 *
	 * @param 	string 	$username 	The username of the user for login.
	 * @param 	string 	$password 	The password of the user for login.
	 * @param 	string 	$ip 		The IP address from which the user is trying to login.
	 *
	 * @uses 	isUsernameAccepted()  Validate if the provided username mets the structure requirements.
	 * @uses 	isPasswordAccepted()  Validate if the provided password mets the structure requirements.
	 * @uses 	hashMask() 			  Mask the password with the chosen hash algorithm.
	 */
	public function __construct($username, $password, $ip = null)
	{
		// tries to recover username and password through HTTP BASIC AUTH headers
		if (!$username && !$password)
		{
			// access server superglobal
			$server = JFactory::getApplication()->input->server;

			// try to extract username and password from headers
			$username = $server->getString('PHP_AUTH_USER');
			$password = $server->getString('PHP_AUTH_PW');
		}

		// dispatch parent
		parent::__construct($username, $password, $ip);
	}

	/**
	 * Check if the user is able to perform the event provided.
	 * The authorisations of the users are stored in the database.
	 *
	 * @param 	VAPApiEvent  $event  The event to authorise.
	 *
	 * @return 	boolean      True if the event can be performed, otherwise false.
	 */
	public function authorise(VAPApiEvent $event)
	{
		// if user is not connected and the event is null : not authorised
		if (!$this->id() || $event === null)
		{
			return false;
		}

		// if the event is always allowed (see connection ping) : authorise
		if ($event->alwaysAllowed())
		{
			return true;
		}

		// otherwise check rules in user settings

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		$q->select($dbo->qn('denied'))
			->from($dbo->qn('#__vikappointments_api_login'))
			->where($dbo->qn('id') . ' = ' . $dbo->q($this->id()));

		$dbo->setQuery($q, 0, 1);
		$denied_plugins = $dbo->loadResult();

		if ($denied_plugins === null)
		{
			return false;
		}

		// the database contains only the denied plugins
		$denied_plugins = $denied_plugins ? json_decode($denied_plugins) : [];

		// make sure the specified event is not included in the list of the denied plugins
		return !in_array($event->getName(), $denied_plugins);
	} 

	/**
	 * Return true if the given username owns a valid structure.
	 * The provided username is valid whether all the conditions below are verified:
	 * - it can contain only letters, numbers, underscores or dots (no white spaces)
	 * - its length is between 3 and 128 characters
	 *
	 * @param 	string 	 $username 	The username to check.
	 *
	 * @return 	boolean  True in case the username is valid.
	 */
	protected function isUsernameAccepted($username)
	{
		// [0-9A-Za-z._]	- accepted characters
		// {3,128}			- have to be 3-128 characters
		// 0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz._
		return preg_match("/^[0-9A-Za-z._]{3,128}$/", $username);
	}

	/**
	 * Return true if the given password owns a valid structure.
	 * The provided password is valid whether all the conditions below are verified:
	 * - it can contain only letters, numbers or these !?@#$%{}[]()_-. symbols
	 * - its length is between 8 and 128 characters
	 * - it contains at least one number
	 * - it contains at least one letter
	 *
	 * @param 	string 	 $password 	The password to check.
	 *
	 * @return 	boolean  True in case the password is valid.
	 */
	protected function isPasswordAccepted($password)
	{
		// (?=.*\d) 					- at least one number
		// (?=.*[A-Za-z]) 				- at least one letter
		// [0-9A-Za-z!@#$%{}[]()_-.]	- accepted characters
		// {8,128}						- have to be 8-128 characters
		// 0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz!?@#$%{}[]()_-.
		return preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!?@#$%_.\-{\[()\]}]{8,128}$/', $password);
	}

	/**
	 * Return the specified password without changes.
	 * Do NOT apply any hash because the password must be stored as it is.
	 *
	 * @param 	string 	$password 	The password to mask.
	 *
	 * @return 	string 	The same provided password.
	 */
	protected function hashMask($password)
	{
		// example return md5($password);

		// no hash mask is applied
		return $password;
	}
}
