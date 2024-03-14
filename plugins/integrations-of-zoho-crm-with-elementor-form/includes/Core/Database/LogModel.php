<?php
/**
 * Provides Base Model Class
 */
namespace FormInteg\IZCRMEF\Core\Database;

/**
 * Undocumented class
 */
use FormInteg\IZCRMEF\Core\Database\Model;

class LogModel extends Model
{
    protected static $table = 'izcrmef_log';

    public function autoLogDelete($condition)
    {
        global $wpdb;
        if (
            !\is_null($condition)
        ) {
            $tableName = $wpdb->prefix . static::$table;

            $result = $this->app_db->get_results("DELETE FROM $tableName WHERE $condition", OBJECT_K);

            return $result;
        }
    }
}
