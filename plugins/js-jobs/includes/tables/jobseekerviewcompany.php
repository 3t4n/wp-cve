<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSjobseekerviewcompanyTable extends JSJOBStable {

    public $id = '';
    public $uid = '';
    public $companyid = '';
    public $status = '';
    public $created = '';

    function __construct() {
        parent::__construct('jobseeker_view_company', 'id'); // tablename, primarykey
    }

}

?>