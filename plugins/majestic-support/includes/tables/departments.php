<?php

if(!defined('ABSPATH'))
	die('Restricted Access');

class MJTC_departmentsTable extends MJTC_table {

	public $id = '';
	public $emailtemplateid = '';
	public $emailid = '';
	public $autoresponceemailid = '';
	public $managerid = '';
	public $departmentname = '';
	public $departmentsignature = '';
	public $ispublic = '';
	public $ticketautoresponce = '';
	public $messageautoresponce = '';
	public $canappendsignature = '';
	public $ordering = '';
	public $updated = '';
	public $created = '';
	public $status = '';
	public $isdefault = '';
	public $sendmail = '';

	function __construct() {
		parent::__construct('departments', 'id'); // tablename, primarykey
	}

}

?>