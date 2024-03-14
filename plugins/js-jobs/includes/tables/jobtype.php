<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSjobtypeTable extends JSJOBStable {

    public $id = '';
    public $title = '';
    public $isactive = '';
    public $isdefault = '';
    public $ordering = '';
    public $status = '';
    public $serverid = '';
    public $alias = '';

    function __construct() {
        parent::__construct('jobtypes', 'id'); // tablename, primarykey
    }

}

?>