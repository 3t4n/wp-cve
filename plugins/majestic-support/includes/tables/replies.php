<?php

if(!defined('ABSPATH'))
	die('Restricted Access');

class MJTC_repliesTable extends MJTC_table {

	public $id = '';
	public $uid = '';
	public $ticketid = '';
	public $name = '';
	public $message = '';
	public $staffid = '';
	public $rating = '';
	public $status = '';
	public $created = '';
	public $ticketviaemail = '';

	function __construct() {
		parent::__construct('replies', 'id'); // tablename, primarykey
	}

}

?>