<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSresumesearchTable extends JSJOBStable {

    public $id = '';
    public $uid = '';
    public $searchname = '';
    public $searchparams = '';
    public $created = '';
    public $status = '';
    public $params = '';

    function __construct() {
        parent::__construct('resumesearches', 'id'); // tablename, primarykey
    }

}

?>