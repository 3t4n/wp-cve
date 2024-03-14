<?php

class Advanced_Form_Integration_Log extends Advanced_Form_Integration_DB {

    /*
    * The constructor function
    */
    public function __construct() {
        global $wpdb;

        $this->db    = $wpdb;
        $this->table = $this->db->prefix . 'adfoin_log';
    }
}