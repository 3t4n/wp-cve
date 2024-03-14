<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBScategoriesTable extends JSJOBStable {

    public $id = '';
    public $cat_value = '';
    public $cat_title = '';
    public $alias = '';
    public $isactive = '';
    public $isdefault = '';
    public $ordering = '';
    public $parentid = '';
    public $serverid = '';

    function __construct() {
        
        parent::__construct('categories', 'id'); // tablename, primarykey
    
    }

}

?>