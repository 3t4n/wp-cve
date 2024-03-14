<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSresumelanguagesTable extends JSJOBStable {

    public $id = '';
    public $resumeid = '';
    public $language = '';
    public $language_reading = '';
    public $language_writing = '';
    public $language_understanding = '';
    public $language_where_learned = '';
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
        parent::__construct('resumelanguages', 'id'); // tablename, primarykey
    }

}

?>