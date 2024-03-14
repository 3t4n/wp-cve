<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBScareerlevelsTable extends JSJOBStable {

    public $id = '';
    public $title = '';
    public $status = '';
    public $isdefault = '';
    public $ordering = '';
    public $serverid = '';

    function __construct() {
        parent::__construct('careerlevels', 'id'); // tablename, primarykey
    }

}

?>