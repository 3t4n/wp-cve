<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSresumeemployersTable extends JSJOBStable {

    public $id = '';
    public $resumeid = '';
    public $employer = '';
    public $employer_position = '';
    public $employer_resp = '';
    public $employer_pay_upon_leaving = '';
    public $employer_supervisor = '';
    public $employer_from_date = '';
    public $employer_to_date = '';
    public $employer_leave_reason = '';
    public $employer_country = '';
    public $employer_state = '';
    public $employer_city = '';
    public $employer_zip = '';
    public $employer_phone = '';
    public $employer_address = '';
    public $created = '';
    public $last_modified = '';
    public $params = '';

    public function check() {
        if ($this->resumeid == '') {
            return false;
        }

        return true;
    }

    function __construct() {
        parent::__construct('resumeemployers', 'id'); // tablename, primarykey
    }

}

?>