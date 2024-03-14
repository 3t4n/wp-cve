<?php

if(!defined('ABSPATH'))
	die('Restricted Access');

class MJTC_fieldsorderingTable extends MJTC_table {

	public $id = '';
	public $field = '';
	public $fieldtitle = '';
	public $ordering = '';
	public $section = '';
	public $fieldfor = '';
	public $published = '';
	public $sys = '';
	public $cannotunpublish = '';
	public $required = '';
	public $size = '';
	public $maxlength = '';
	public $cols = '';
	public $rows = '';
	public $isuserfield = '';
	public $userfieldtype = '';
	public $depandant_field = '';
	public $visible_field = '';
	public $showonlisting = '';
	public $cannotshowonlisting = '';
	public $search_user = '';
	public $cannotsearch = '';
	public $isvisitorpublished = '';
	public $userfieldparams = '';
	public $multiformid = '';
	public $visibleparams = '';

	function __construct() {
		parent::__construct('fieldsordering', 'id'); // tablename, primarykey
	}

}

?>
