<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * 
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @copyright Copyright (c) ifeelweb.de
 * @version   $Id: Db.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 * @package   
 */ 
class IfwPsn_Wp_Proxy_Db 
{
    /**
     * @var array
     */
    protected static $_tableStore = array();



    /**
     * Convenience method to get code completion in IDE
     * @return wpdb
     */
    public static function getObject()
    {
        global $wpdb;
        return $wpdb;
    }

    /**
     * Retrieves the database name
     * @return string
     */
    public static function getName()
    {
        return DB_NAME;
    }

    /**
     * @return string
     */
    public static function getPrefix()
    {
        global $table_prefix;
        return $table_prefix;
    }

    /**
     * Get the table name with prefix
     * @param $table
     * @return string
     */
    public static function getTableName($table)
    {
        if (strpos($table, self::getPrefix()) !== 0) {
            return self::getPrefix() . $table;
        }

        return $table;
    }

    /**
     * @param $table
     * @return array
     */
    public static function getTableFieldNames($table)
    {
        $result = array();

        $describeResult = self::describe($table);

        if (is_array($describeResult)) {
            foreach ($describeResult as $field) {
                array_push($result, $field->Field);
            }
        }

        return $result;
    }

    /**
     * Get the result of DESCRIBE $table
     *
     * @param $table
     * @return mixed
     */
    public static function describe($table)
    {
        if (!array_key_exists($table, self::$_tableStore)) {
            $sql = sprintf('DESCRIBE `%s`', self::getPrefix() . $table);
            self::$_tableStore[$table] = self::getObject()->get_results($sql);
        }
        return self::$_tableStore[$table];
    }

    /**
     * Checks if a column in a table exists
     *
     * @param $table
     * @param $column
     * @return bool
     */
    public static function columnExists($table, $column)
    {
        return in_array($column, self::getTableFieldNames($table));
    }

    /**
     * @param $table
     * @param $column
     * @return bool
     */
    public static function indexExists($table, $column)
    {
        $sql = sprintf('SHOW INDEX FROM `%s` WHERE Column_name = "%s"', $table, $column);
        $result = self::getObject()->get_row($sql);
        return !empty($result);
    }

    /**
     * @param $operator
     * @return string
     */
    public static function translateMetaQueryCompareOperator($operator)
    {
        return strtoupper(strtr(strtolower($operator), array(
            'gt' => '>',
            'gte' => '>=',
            'lt' => '<',
            'lte' => '<=',
        )));
    }

    /**
     * @param $operator
     * @return bool
     */
    public static function isValidMetaQueryCompareOperator($operator)
    {
        return in_array(strtoupper($operator), array('=', '!=', '>', '>=', '<', '<=', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN', 'NOT EXISTS', 'REGEXP', 'NOT REGEXP', 'RLIKE'));
    }

    /**
     * @param $type
     * @return bool
     */
    public static function isValidMetaQueryType($type)
    {
        return in_array(strtoupper($type), array('NUMERIC', 'BINARY', 'CHAR', 'DATE', 'DATETIME', 'DECIMAL', 'SIGNED', 'TIME', 'UNSIGNED'));
    }
}
