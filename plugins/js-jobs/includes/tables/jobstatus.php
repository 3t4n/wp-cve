<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSjobstatusTable extends JSJOBStable {

    public $id = '';
    public $title = '';
    public $isactive = '';
    public $isdefault = '';
    public $ordering = '';
    public $serverid = '';

    function __construct() {
        parent::__construct('jobstatus', 'id'); // tablename, primarykey
    }

}

?>