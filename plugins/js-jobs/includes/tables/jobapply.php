<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSjobapplyTable extends JSJOBStable {

    public $id = '';
    public $jobid = '';
    public $uid = '';
    public $cvid = '';
    public $apply_date = '';
    public $resumeview = '';
    public $comments = '';
    public $coverletterid = '';
    public $action_status = '';
    public $serverstatus = '';
    public $serverid = '';
    public $socialapplied = '';
    public $socialprofileid = '';

    public function check() {
        if ($this->jobid == '') {
            return false;
        }

        return true;
    }

    function __construct() {
        parent::__construct('jobapply', 'id'); // tablename, primarykey
    }

}

?>