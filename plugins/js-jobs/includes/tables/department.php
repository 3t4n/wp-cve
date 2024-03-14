<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSdepartmentTable extends JSJOBStable {

    public $id = '';
    public $uid = '';
    public $companyid = '';
    public $name = '';
    public $alias = '';
    public $description = '';
    public $status = '';
    public $created = '';
    public $serverstatus = '';
    public $serverid = '';

    public function check() {
        if ($this->companyid == '') {
            return false;
        }

        return true;
    }

    function __construct() {
        parent::__construct('departments', 'id'); // tablename, primarykey
    }

}

?>