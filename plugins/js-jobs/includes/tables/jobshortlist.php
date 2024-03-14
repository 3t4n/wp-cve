<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSjobshortlistTable extends JSJOBStable {

    public $id = '';
    public $uid = '';
    public $jobid = '';
    public $comments = '';
    public $rate = '';
    public $created = '';
    public $status = '';

    public function check() {
        if ($this->jobid == '') {
            return false;
        }

        return true;
    }

    function __construct() {
        parent::__construct('jobshortlist', 'id'); // tablename, primarykey
    }

}

?>