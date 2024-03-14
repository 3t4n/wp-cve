<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Models;
defined('ABSPATH') or die();

class PointsLedger extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->table = self::$db->prefix . 'wlr_points_ledger';
        $this->primary_key = 'id';
        $this->fields = array(
            'user_email' => '%s',
            'action_type' => '%s',
            'action_process_type' => '%s',
            'credit_points' => '%s',
            'debit_points' => '%s',
            'note' => '%s',
            'created_at' => '%s'
        );
    }

    function beforeTableCreation()
    {
    }

    function runTableCreation()
    {
        $create_table_query = "CREATE TABLE IF NOT EXISTS {$this->table} (
				 `{$this->getPrimaryKey()}` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                 `user_email` varchar(180) DEFAULT NULL,
                 `action_type` varchar(180) DEFAULT NULL,
                 `action_process_type` varchar(180) DEFAULT NULL,
                 `credit_points` BIGINT DEFAULT 0,
                 `debit_points` BIGINT DEFAULT 0,
                 `note` TEXT DEFAULT NULL,
                 `created_at` BIGINT DEFAULT 0,
                 PRIMARY KEY (`{$this->getPrimaryKey()}`)
			)";
        $this->createTable($create_table_query);
    }

    function afterTableCreation()
    {
        if ($this->checkTableExists()) {
            $existing_columns = $this->getTableFields();
            if (!in_array('action_process_type', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN action_process_type varchar(180) DEFAULT NULL"
                );
            }
            if (!in_array('note', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN note TEXT DEFAULT NULL"
                );
            }
        }
        $index_fields = array('created_at', 'action_type', 'user_email', 'action_process_type');
        $this->insertIndex($index_fields);
    }

}