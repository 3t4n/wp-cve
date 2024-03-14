<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSshiftTable extends JSJOBStable {

    public $id = '';
    public $title = '';
    public $isactive = '';
    public $isdefault = '';
    public $ordering = '';
    public $status = '';
    public $serverid = '';

    function __construct() {
        parent::__construct('shifts', 'id'); // tablename, primarykey
    }

}

?>