<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSsalaryrangeTable extends JSJOBStable {

    public $id = '';
    public $rangevalue = '';
    public $rangestart = '';
    public $rangeend = '';
    public $status = '';
    public $isdefault = '';
    public $ordering = '';
    public $serverid = '';

    function __construct() {
        parent::__construct('salaryrange', 'id'); // tablename, primarykey
    }

}

?>