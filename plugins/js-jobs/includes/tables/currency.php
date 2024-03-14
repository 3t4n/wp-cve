<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBScurrencyTable extends JSJOBStable {

    public $id = '';
    public $title = '';
    public $symbol = '';
    public $code = '';
    public $status = '';
    public $default = '';
    public $ordering = '';
    public $serverid = '';

    function __construct() {
        parent::__construct('currencies', 'id'); // tablename, primarykey
    }

}

?>