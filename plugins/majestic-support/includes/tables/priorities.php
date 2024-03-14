<?php

if(!defined('ABSPATH'))
	die('Restricted Access');

class MJTC_prioritiesTable extends MJTC_table {

	public $id = '';
	public $priority = '';
	public $prioritycolour = '';
	public $priorityurgency = '';
	public $overduetypeid = '';
	public $overdueinterval = '';
	public $ispublic = '';
	public $ordering = '';
	public $isdefault = '';
	public $status = '';

	function __construct() {
		parent::__construct('priorities', 'id'); // tablename, primarykey
	}

}

?>