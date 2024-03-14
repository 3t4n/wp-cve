<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSagesTable extends JSJOBStable {

    public $id = '';
    public $title = '';
    public $status = '';
    public $isdefault = '';
    public $ordering = '';
    public $serverid = '';

    function __construct() {
        parent::__construct('ages', 'id'); // tablename, primarykey
    }

}

?>