<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBScompanycitiesTable extends JSJOBStable {

    public $id = '';
    public $companyid = '';
    public $cityid = '';
    public $serverid = '';

    function __construct() {
        parent::__construct('companycities', 'id'); // tablename, primarykey
    }

    public function check() {
        if ($this->companyid == '') {
            return false;
        }

        return true;
    }

}

?>