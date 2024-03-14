<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

abstract class WADA_Model_BaseReadOnly
{
    public $_last_error;
    public $_data;

    public function __construct($options = array()){
        $this->_data = null;
        $this->loadData($options);
    }

    abstract protected function loadData($options = array());
}