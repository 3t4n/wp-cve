<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBShighesteducationTable extends JSJOBStable {

    public $id = '';
    public $title = '';
    public $isactive = '';
    public $isdefault = '';
    public $ordering = '';
    public $serverid = '';

    function __construct() {
        parent::__construct('heighesteducation', 'id'); // tablename, primarykey
    }

}

?>