<?php
/**
 * Its pos vendor model
 *
 * @since: 21/09/2021
 * @author: Sarwar Hasan
 * @version 1.0.0
 * @package VitePos_Lite\Libs
 */

namespace VitePos_Lite\Libs;

/**
 * Class Vendor
 *
 * @package VitePos_Lite\Libs
 */
class Vendor {

	/**
	 * Its property id
	 *
	 * @var int
	 */
	public $id;
	/**
	 * Its property name
	 *
	 * @var string
	 */
	public $name;
	/**
	 * Its property email
	 *
	 * @var string
	 */
	public $email;
	/**
	 * Its property contact_no
	 *
	 * @var int
	 */
	public $contact_no;
	/**
	 * Its property vendor_note
	 *
	 * @var string
	 */
	public $vendor_note;
	/**
	 * Its property status
	 *
	 * @var bool
	 */
	public $status;
	/**
	 * Its property added_by
	 *
	 * @var string
	 */
	public $added_by;
}
