<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSjobtempTable extends JSJOBStable {

    public $localid = '';
    public $id = '';
    public $title = '';
    public $aliasid = '';
    public $companyaliasid = '';
    public $companylogo = '';
    public $country = '';
    public $state = '';
    public $city = '';
    public $jobdays = '';
    public $companyid = '';
    public $companyname = '';
    public $jobcategory = '';
    public $cat_title = '';
    public $symbol = '';
    public $salaryfrom = '';
    public $salaryto = '';
    public $salaytype = '';
    public $jobtype = '';
    public $jobstatus = '';
    public $cityname = '';
    public $statename = '';
    public $countryname = '';
    public $noofjobs = '';
    public $created = '';

    function __construct() {
        parent::__construct('jobtemp', 'id'); // tablename, primarykey
    }

}

?>