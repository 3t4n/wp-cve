<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBScountryTable extends JSJOBStable {

    public $id = '';
    public $name = '';
    public $shortCountry = '';
    public $continentID = '';
    public $dialCode = '';
    public $enabled = '';
    public $serverid = '';

    function __construct() {
        parent::__construct('countries', 'id'); // tablename, primarykey
    }

}

?>