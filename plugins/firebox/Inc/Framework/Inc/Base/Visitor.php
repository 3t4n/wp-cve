<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Base;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Visitor
{
/**
	 * The name of the cookie used to identify that a visitor is persistent.
	 * 
	 * @var  string
	 */
	private $persistent_cookie_name = 'fpfvp';

	/**
	 * Represents the maximum age of the visitor's persistent cookie in seconds.
	 * 
	 * Default value set to 1 year.
	 *
	 * @var  int
	 */
	private $persistent_cookie_expire = 31536000;

	/**
	 * The name of the cookie used to identify that a visitor is new.
	 * 
	 * @var  string
	 */
	private $session_cookie_name = 'fpfvs';
	
	/**
	 * Represents the maximum age of the visitor's session cookie in seconds.
	 *
	 * Default value set to 20 minutes.
	 * 
	 * @var  int
	 */
	private $session_cookie_expire = 1200;

	/**
	 * The Cookies instance.
	 * 
	 * @var  object
	 */
	private $cookies;
	
	public function __construct($cookies = null)
	{
		if (!$cookies)
		{
			$cookies = new \FPFramework\Libs\Cookie();
		}
        $this->cookies = $cookies;
	}

	/**
	 * Creates or updates cookies of the visitor.
	 * 
	 * - It will only create & update the fpfvs (visitor session cookie) when the user is considered new.
	 * - It will always update the fpfvp (visitor persistent cookie).
	 * 
	 * @return  void
	 */
	public function createOrUpdateCookie()
	{
		if ($this->isNew())
		{
			// Update the session cookie
			$this->cookies->set($this->session_cookie_name, 1, time() + $this->session_cookie_expire, '/', '', true);
		}

		// Update the persistent cookie
		$this->cookies->set($this->persistent_cookie_name, 1, time() + $this->persistent_cookie_expire, '/', '', true);
	}

	/**
	 * Checks whether the user is considered new.
	 * 
	 * A user is considered new when the following criteria are met:
	 * 
	 * - visitor persistent and session cookies are not met
	 * OR
	 * - visitor session cookie is set
	 * 
	 * @return  bool
	 */
	public function isNew()
	{
		$fpfvp = $this->cookies->get($this->persistent_cookie_name);
		$fpfvs = $this->cookies->get($this->session_cookie_name);

		return (!$fpfvp && !$fpfvs) || $fpfvs;
	}
}