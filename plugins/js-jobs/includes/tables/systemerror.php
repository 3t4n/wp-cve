<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSsystemerrorTable extends JSJOBStable {

    public $id = '';
    public $uid = '';
    public $error = '';
    public $isview = '';
    public $created = '';

    function __construct() {
        parent::__construct('system_errors', 'id'); // tablename, primarykey
    }

}

?>