<?php

if(!defined('ABSPATH'))
	die('Restricted Access');

class MJTC_configTable extends MJTC_table {

	public $configname = '';
	public $configvalue = '';
	public $configfor = '';
	public $addon = '';

	function __construct() {
		parent::__construct('config', 'configname'); // tablename, primarykey
	}

}

?>
