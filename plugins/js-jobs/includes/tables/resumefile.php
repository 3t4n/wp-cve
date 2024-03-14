<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSresumefileTable extends JSJOBStable {

    public $id = '';
    public $resumeid = '';
    public $filename = '';
    public $filetype = '';
    public $filesize = '';
    public $created = '';
    public $last_modified = '';

    public function check() {
        if ($this->resumeid == '') {
            return false;
        }

        return true;
    }

    function __construct() {
        parent::__construct('resumefiles', 'id'); // tablename, primarykey
    }

}

?>