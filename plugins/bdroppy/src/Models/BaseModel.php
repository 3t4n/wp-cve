<?php
namespace BDroppy\Models;

if ( ! defined( 'ABSPATH' ) ) exit;

class BaseModel
{
    public static $table;
    private static $where = [];
    private static $select = [];
    private static $insert = [];
    private static $values = [];
    private static $limit = [];
    private static $update = [];
    private static $order;

    private static function getStringValue($value)
    {
        if(in_array($value,['now','null'])){
            return strtoupper($value);
        }elseif(is_array($value)){
            if (count($value) > 0){
                $value = array_map([self::class,'getStringValue'],$value);
                return "(". implode(',',$value) . ")";
            }else{
                return "(null)";
            }
        }elseif(is_string($value)){
            return "'" . $value .  "'";
        }else{
            return $value;
        }
    }

    public static function where(...$where)
    {
        if (count(self::$where) > 0){
            $and_OR = "AND";
            if (end($where) === "OR"){
                $and_OR = "OR";
                array_pop($where);
            }
            self::$where[] = $and_OR;
        }
        if (is_callable($where[0])){
            $Gwhere = self::$where;
            self::$where = [];
            $result = $where[0](new self)::whereString();
            self::$where = $Gwhere;
            self::$where[] = "(" . $result . ")";

        }elseif(count($where) == 1){
            self::$where[] = $where[0];
        }elseif (count($where) == 3){
            $where[2] = self::getStringValue($where[2]);
            self::$where[] = implode(' ',$where);
        }elseif (count($where) == 2){
            self::$where[] = $where[0] . " = " . self::getStringValue($where[1]);
        }
        return new static;
    }

    public static function orderBy($by ,$order = "ASC")
    {
        self::$order = " ORDER BY " . $by . " " . $order . ' ';
        return new static;
    }

    public static function orWhere(...$where)
    {
        return self::where(...array_merge($where,["OR"]));
    }

    public static function whereIn($param,array $in,$NOT = '')
    {
        return self::where(...[$param,$NOT .' IN',$in]);
    }

    public static function whereNotIn($param,array $in)
    {
        return self::whereIn($param,$in,'NOT');
    }

    private static function whereString()
    {
        return (count(self::$where) > 0)? implode(' ',self::$where):"1" ;
    }

    public static function select(...$select)
    {
        self::$select = $select;
        return new static;
    }

    private static function selectString()
    {
        return (count(self::$select) > 0)? implode(',',self::$select):"*" ;
    }

    private static function getQueryString($prefix,$type= null)
    {
        if (is_null(static::$table)) {
            return;
        }
        switch ($type){
            case 'update':
                $sql = "UPDATE " . $prefix . static::$table . " SET " . self::updateString() . " WHERE " . self::whereString() . self::$order . self::limitString();
                break;
            case 'insert':
                $sql = "INSERT INTO " . $prefix . static::$table . " " . self::insertString() . "  VALUES  " . self::valuesString();
                break;
            case 'delete':
                $sql = "DELETE FROM ". $prefix . static::$table." WHERE " . self::whereString() . self::limitString();
                break;
            case 'select':
            default :
                $sql = "SELECT " .self::selectString() . " FROM " . $prefix . static::$table . " WHERE " . self::whereString() .self::$order. self::limitString() ;
        }
        return $sql;

    }

    private static function initial()
    {
        self::$table ;
        self::$where = [];
        self::$select = [];
        self::$insert = [];
        self::$values = [];
        self::$limit= [];
        self::$update = [];
        self::$order = null;
    }

    public static function get()
    {
        return self::runQuery('select',1);
    }

    public static function pluck($pluck)
    {
        return array_column(self::get(),$pluck);
    }

    public static function count()
    {
        $result = self::select("COUNT(*) AS count")->first();
        return is_null($result)? 0 : $result->count;
    }

    public static function first()
    {
        $result = self::limit(1)->get();
        return (is_array($result) &&  count($result) > 0) ? $result[0] : null;
    }

    public static function update($update)
    {
        foreach ( $update as $key  => $value ){
            if (in_array(strtolower($value),['now','now()'])){
                self::$update[] = $key . ' = ' . strtoupper($value)  .'()';
            }elseif (is_string($value)){
                self::$update[] = $key . ' = "' . $value .'"';
            }elseif (is_null($value)){
                self::$update[] = $key . ' = NULL' ;
            }else{
                self::$update[] = $key . ' = '. $value ;
            }
        }
        return self::runQuery('update');
    }

    private static function updateString()
    {
        return implode(',',self::$update);
    }

    public static function create($insert)
    {
        if (bdroppy_is_multi_array($insert)){

            self::$insert = array_keys($insert[0]);
            foreach ($insert as $ins){
                self::$values[] = self::valueString($ins);
            }
        }else{
            self::$insert = array_keys($insert);
            self::$values[] = self::valueString($insert);
        }
        return self::runQuery('insert');
    }

    private static function runQuery($query,$hasResult = false)
    {
        global $wpdb;
        $query = self::getQueryString($wpdb->prefix,$query);

        self::initial();
        if ($hasResult){
            return $wpdb->get_results($query,object);
        }else{
            return $wpdb->query($query);
        }
    }

    private static function insertString()
    {
        return "(".implode(',',self::$insert) . ")";
    }

    private static function valueString($vs)
    {
        $values = [];
        foreach ($vs as $v){
            $values[] = self::getStringValue($v);
        }
        return "(". implode(',',array_values($values)) .")";
    }

    private static function valuesString()
    {
        return implode(',',self::$values);
    }

    public static function delete()
    {
        return self::runQuery('delete');
    }

    public static function limit($limit)
    {
        self::$limit[1] = $limit;
        return new static;
    }

    public static function offset($offset)
    {
        self::$limit[0] = $offset;
        return new static;
    }

    private static function limitString()
    {
        if (count(self::$limit)){
            ksort(self::$limit);
            return " LIMIT ". implode(',',self::$limit );
        }
        return "";
    }

}


