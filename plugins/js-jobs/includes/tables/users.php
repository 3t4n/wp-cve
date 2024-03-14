<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSusersTable extends JSJOBStable {

    public $id = '';
    public $uid = '';
    public $roleid = '';
    public $first_name = '';
    public $last_name = '';
    public $emailaddress = '';
    public $socialid = '';
    public $status = '';
    public $created = '';

    function __construct() {
        parent::__construct('users', 'id'); // tablename, primarykey
    }

}

?>