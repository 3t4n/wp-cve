<?php
/**
 * The UserContact class
 *
 * @package		LetAProDoIT.Helpers
 * @filename	UserContact.php
 * @version		2.0.0
 * @author		Sharron Denice, Let A Pro Do IT! (www.letaprodoit.com)
 * @copyright	Copyright 2016 Let A Pro Do IT! (www.letaprodoit.com). All rights reserved
 * @license		APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 * @brief		Class to store user contact objects
 */
class LAPDI_UserContact
{
	/**
	 * The user's first name
	 *
	 * @var string
	 */
	public $fname;
	/**
	 * The user's last name
	 *
	 * @var string
	 */
	public $lname;
	/**
	 * The user password
	 *
	 * @var string
	 */
	public $email;
	/**
	 * The user phone
	 *
	 * @var string
	 */
	public $phone;
	/**
	 * The user's company
	 *
	 * @var string
	 */
	public $company;
	
	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @param string $fname - The user first name
	 * @param string $lname - The user last name
	 * @param string $email - The user email
	 * @param string $phone - Optional The user phone
     * @param string $company - Optional The user phone
	 *
	 * @return void
	 *
	 */
	function __construct($fname, $lname, $email, $phone = '', $company = '')
	{
		$this->fname = $fname;
		$this->lname = $lname;
		$this->email = $email;	
		$this->phone = $phone;	
		$this->company = $company;	
	}// end func
}// end class

/**
 * TSP_UserContact
 *
 * @since 1.0.0
 *
 * @deprecated 2.0.0 Please use LAPDI_UserContact instead
 *
 * @return void
 *
 */
class TSP_UserContact extends LAPDI_UserContact
{

}// end class