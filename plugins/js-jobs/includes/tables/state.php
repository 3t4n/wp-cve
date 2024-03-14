<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSstateTable extends JSJOBStable {

    public $id = '';
    public $name = '';
    public $shortRegion = '';
    public $countryid = '';
    public $enabled = '';
    public $serverid = '';

    function __construct() {
        parent::__construct('states', 'id'); // tablename, primarykey
    }

}

?>