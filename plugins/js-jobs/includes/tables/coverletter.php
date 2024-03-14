<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBScoverletterTable extends JSJOBStable {

    public $id = '';
    public $uid = '';
    public $title = '';
    public $alias = '';
    public $description = '';
    public $hits = '';
    public $published = '';
    public $searchable = '';
    public $status = '';
    public $created = '';
    public $packageid = '';
    public $paymenthistoryid = '';
    public $serverstatus = '';
    public $serverid = '';

    function __construct() {
        parent::__construct('coverletters', 'id'); // tablename, primarykey
    }

}

?>