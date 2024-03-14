<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSemailtemplateconfigTable extends JSJOBStable {

    public $id = '';
    public $emailfor = '';
    public $admin = '';
    public $employer = '';
    public $jobseeker = '';
    public $jobseeker_visitor = '';
    public $employer_visitor = '';

    function __construct() {
        parent::__construct('emailtemplates_config', 'id'); // tablename, primarykey
    }

}

?>