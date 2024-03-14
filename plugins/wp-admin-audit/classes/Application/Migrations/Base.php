<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
 */

if(!defined('ABSPATH')){
    exit;
}

abstract class WADA_Migration_Base{
    protected $wpdb;
    protected $charsetCollate;

    abstract public function isMigrationApplicable();
    abstract public function doMigration();

    public function __construct(){
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->charsetCollate = $wpdb->get_charset_collate();
    }

}