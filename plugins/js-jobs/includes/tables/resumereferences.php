<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSresumereferencesTable extends JSJOBStable {

    public $id = '';
    public $resumeid = '';
    public $reference = '';
    public $reference_name = '';
    public $reference_country = '';
    public $reference_state = '';
    public $reference_city = '';
    public $reference_zipcode = '';
    public $reference_address = '';
    public $reference_phone = '';
    public $reference_relation = '';
    public $reference_years = '';
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
        parent::__construct('resumereferences', 'id'); // tablename, primarykey
    }

}

?>