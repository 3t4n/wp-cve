<?php

if(!defined('ABSPATH'))
	die('Restricted Access');

class MJTC_multilanguageemailtemplateTable extends MJTC_table {

	public $id = '';
	public $language_id = '';
	public $templatefor = '';
	public $subject = '';
	public $body = '';
	public $status = '';
	public $created = '';


	function __construct() {
		parent::__construct('multilanguageemailtemplate', 'id'); // tablename, primarykey
	}

}

?>