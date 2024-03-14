<?php
/**
 * The APICredentials class
 *
 * @package		LetAProDoIT.Helpers
 * @filename	APICredentials.php
 * @version		2.0.0
 * @author		Sharron Denice, Let A Pro Do IT! (www.letaprodoit.com)
 * @copyright	Copyright 2016 Let A Pro Do IT! (www.letaprodoit.com). All rights reserved
 * @license		APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 * @brief		Class to store API credential objects
 *
 */
class LAPDI_APICredentials
{
	/**
	 * The API ID
	 *
	 * @var string
	 */
	public $id;
	
	/**
	 * The API secret
	 *
	 * @var string
	 */
	public $secret;
	
	/**
	 * The API key
	 *
	 * @var string
	 */
	public $key;
	
	/**
	 * The API URL
	 *
	 * @var string
	 */
	public $URL;
	
	/**
	 * The API user name
	 *
	 * @var string
	 */
	public $user;

	/**
	 * The API pass
	 *
	 * @var string
	 */
	public $pass;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @param string $id - The API ID
	 * @param string $key - The API key
	 * @param string $secret - The API secret
	 * @param string $url - Optional - The API URL
	 * @param string $user - Optional - The API user name
	 * @param string $pass - Optional - The API pass
	 *
	 * @return void
	 *
	 */
	function __construct($id, $key, $secret = '', $url = '', $user = '', $pass = '')
	{
		$this->id = $id;
		$this->key = $key;
		$this->secret = $secret;
		$this->URL = $url;
		$this->user = $user;
		$this->pass = $pass;
	}// end func
}// end class

/**
 * TSP_APICredentials
 *
 * @since 1.0.0
 *
 * @deprecated 2.0.0 Please use LAPDI_APICredentials instead
 *
 * @return void
 *
 */
class TSP_APICredentials extends LAPDI_APICredentials
{

}// end class