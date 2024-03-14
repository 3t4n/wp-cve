<?php

if(!defined('ABSPATH'))
	die('Restricted Access');

class MJTC_attachmentsTable extends MJTC_table {

	public $id = '';
	public $ticketid = '';
	public $replyattachmentid = '';
	public $filesize = '';
	public $filename = '';
	public $filekey = '';
	public $deleted = '';
	public $status = '';
	public $created = '';

	function __construct() {
		parent::__construct('attachments', 'id'); // tablename, primarykey
	}

}

?>