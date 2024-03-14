<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSresumeaddressesTable extends JSJOBStable {

    public $id = '';
    public $resumeid = '';
    public $address = '';
    public $address_country = '';
    public $address_state = '';
    public $address_city = '';
    public $address_zipcode = '';
    public $longitude = '';
    public $latitude = '';
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
        parent::__construct('resumeaddresses', 'id'); // tablename, primarykey
    }

}

?>