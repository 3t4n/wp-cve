<?php
/**
 * The UserLogin class
 *
 * @package		LetAProDoIT.Helpers
 * @filename	UserLogin.php
 * @version		2.0.0
 * @author		Sharron Denice, Let A Pro Do IT! (www.letaprodoit.com)
 * @copyright	Copyright 2016 Let A Pro Do IT! (www.letaprodoit.com). All rights reserved
 * @license		APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 * @brief		Class to store user login objects
 */
class LAPDI_UserLogin
{
	/**
	 * The user name
	 *
	 * @var string
	 */
	public $name;
	/**
	 * The user password
	 *
	 * @var string
	 */
	public $pass;
	/**
	 * The login URL
	 *
	 * @var string
	 */
	public $URL;
	
	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @param string $name - The user name
	 * @param string $pass - The user password
	 * @param string $url - The login URL
	 *
	 * @return void
	 *
	 */
	function __construct($name, $pass, $url)
	{
		$this->name = $name;
		$this->pass = $pass;	
		$this->URL = $url;
	}// end func
}// end class

/**
 * TSP_UserLogin
 *
 * @since 1.0.0
 *
 * @deprecated 2.0.0 Please use LAPDI_UserLogin instead
 *
 * @return void
 *
 */
class TSP_UserLogin extends LAPDI_UserLogin
{

}// end class