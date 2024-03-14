<?php

namespace S2WPImporter;

class VariationsLog extends AbstractDB
{
    public $table_name;

    public $table_name_suffix = 'variations_log';

    public function __construct()
    {
        parent::__construct();

        $this->table_name = $this->db_prefix . $this->table_name_suffix;
        $this->primary_key = 'id';
    }

    public function getColumns()
    {
        return [
            'id' => '%d',
            'old_id' => '%d',
            'new_id' => '%d',
        ];
    }

    public function getColumnDefaults()
    {
        return [
            'id' => 0,
            'old_id' => 0,
            'new_id' => 0,
        ];
    }

    /**
     * Creates the table.
     *
     * @see dbDelta()
     */
    public function createTable()
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $sql = "CREATE TABLE {$this->table_name} (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			old_id bigint(20) NOT NULL,
			new_id bigint(20) NOT NULL,
			PRIMARY KEY (id)
			) CHARACTER SET utf8 COLLATE utf8_general_ci;";

        dbDelta($sql);
    }

    /*
    -------------------------------------------------------------------------------
    Helpers
    -------------------------------------------------------------------------------
    */
    public function getNewId($oldId){
        return (int) $this->getColumnBy('new_id', 'old_id', (int)$oldId);
    }

}
