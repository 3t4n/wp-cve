<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSfieldsorderingTable extends JSJOBStable {

    public $id = '';
    public $field = '';
    public $fieldtitle = '';
    public $ordering = '';
    public $section = '';
    public $fieldfor = '';
    public $published = '';
    public $isvisitorpublished = '';
    public $sys = '';
    public $cannotunpublish = '';
    public $required = '';
    public $cannotsearch = '';
    public $search_ordering = '';
    public $isuserfield = '';
    public $userfieldtype = '';
    public $userfieldparams = '';
    public $search_user = '';
    public $search_visitor = '';
    public $showonlisting = '';
    public $depandant_field = '';
    public $j_script = '';
    public $size = '';
    public $maxlength = '';
    public $cols = '';
    public $rows = '';
    public $readonly = '';

    function __construct() {
        parent::__construct('fieldsordering', 'id'); // tablename, primarykey
    }

}

?>
