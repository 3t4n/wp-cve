<?php

if(!defined('ABSPATH'))
	die('Restricted Access');

class MJTC_smartrepliesTable extends MJTC_table {

	public $id = '';
	public $title = '';
	public $ticketsubjects = '';
	public $reply = '';
	public $usedby = '';

	function __construct() {
		parent::__construct('smartreplies', 'id'); // tablename, primarykey
	}

}

?>
