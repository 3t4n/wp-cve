<?php
namespace BDroppy\Models;

if ( ! defined( 'ABSPATH' ) ) exit;

class Product extends BaseModel
{
    const TABLE_NAME = 'dropshipping_products';
    public static $table = 'dropshipping_products';

    public static function getActiveCatalog()
    {
        $result = self::select('rewix_catalog_id')->first();
        return empty($result)? null : $result->rewix_catalog_id;
    }

//    public static function create(array $params)
//    {
//        $keys = implode(',',array_keys($params));
//        $values = implode(',',array_values($params));
//
//        global $wpdb;
//        $table_name = $wpdb->prefix . self::TABLE_NAME;
//        $sql = "insert into $table_name ($keys,last_sync_at) values($values,now())";
////        $wpdb->insert($table_name,$params);
//        $wpdb->query($sql);
//    }
//
//    public static function getByStatus($status,$limit = 0)
//    {
//        global $wpdb;
//        $table_name = $wpdb->prefix . self::TABLE_NAME;
//        $query  = "SELECT * FROM $table_name WHERE sync_status = '$status' ";
//        $query  .= "and sync_message is null ";
//        if ( $limit > 0 ) {
//            $query .= ' LIMIT ' . $limit;
//        }
//        return $wpdb->get_results( $query, ARRAY_A );
//    }
//
//    public static function getByLastCheckAndCheckOptions($min,$options,$limit)
//    {
//        global $wpdb;
//        $table_name = $wpdb->prefix . self::TABLE_NAME;
//        $query  = "SELECT * FROM $table_name WHERE DATE_ADD(last_sync_at, INTERVAL $min MINUTE) < NOW() ";
//        $query  .= " and sync_status like 'done' ";
//        $query  .= " and options not like '$options' ";
//        $query  .= " ORDER BY `last_sync_at` ASC ";
//        if ( $limit > 0 ) {
//            $query .= ' LIMIT ' . $limit;
//        }
//        return $wpdb->get_results( $query, ARRAY_A );
//    }
//
//    public static function getByLastCheck($min,$limit)
//    {
//        global $wpdb;
//        $table_name = $wpdb->prefix . self::TABLE_NAME;
//        $query  = "SELECT * FROM $table_name WHERE DATE_ADD(last_sync_at, INTERVAL $min MINUTE) < NOW() ";
//        $query  .= " and sync_status like 'done' ";
//        $query  .= " ORDER BY `last_sync_at` ASC ";
//        if ( $limit > 0 ) {
//            $query .= ' LIMIT ' . $limit;
//        }
//        return $wpdb->get_results( $query, ARRAY_A );
//    }
//
//    public static function getKilledProducts($min,$limit =0)
//    {
//        global $wpdb;
//        $table_name = $wpdb->prefix . self::TABLE_NAME;
//        $query  = "SELECT * FROM $table_name WHERE DATE_ADD(last_sync_at, INTERVAL $min MINUTE) < NOW() ";
//        $query  .= " and sync_message like 'importing' ";
//        if ( $limit > 0 ) {
//            $query .= ' LIMIT ' . $limit;
//        }
//        return $wpdb->get_results( $query, ARRAY_A );
//    }
//
//    public static function getByStatusNot($status,$limit =0)
//    {
//        global $wpdb;
//        $table_name = $wpdb->prefix . self::TABLE_NAME;
//        $query  = "SELECT rewix_product_id FROM $table_name WHERE sync_status not like '$status'";
//        if ( $limit > 0 ) {
//            $query .= ' LIMIT ' . $limit;
//        }
//        return wp_list_pluck( $wpdb->get_results( $query, ARRAY_A ),'rewix_product_id');
//    }
//
//    public static function getBy($name,$value,$limit = 0,$offset =0 )
//    {
//        global $wpdb;
//        $table_name = $wpdb->prefix . self::TABLE_NAME;
//        $query  = "SELECT id,rewix_product_id FROM $table_name WHERE $name = '$value'";
//        if ( $limit > 0 ) {
//            $query .= ' LIMIT ' . $limit;
//        }
//        if ( $offset > 0 ) {
//            $query .= ' OFFSET ' . $limit;
//        }
//        return $wpdb->get_results( $query, ARRAY_A );
//    }
//
//    public static function getAll($limit = 0,$offset =0 )
//    {
//        global $wpdb;
//        $table_name = $wpdb->prefix . self::TABLE_NAME;
//        $query  = "SELECT id,rewix_product_id FROM $table_name";
//        if ( $limit > 0 ) {
//            $query .= ' LIMIT ' . $limit;
//        }
//        if ( $offset > 0 ) {
//            $query .= ' OFFSET ' . $limit;
//        }
//        return $wpdb->get_results( $query, ARRAY_A );
//    }
//
//
//
//    public static function getByRewixIdAndLang($id,$lang)
//    {
//        global $wpdb;
//        $table_name = $wpdb->prefix . self::TABLE_NAME;
//        $query  = "SELECT id,rewix_product_id FROM $table_name WHERE rewix_product_id = '$id' and lang = '$lang' ";
//        return $wpdb->get_results( $query, ARRAY_A );
//    }
//
//    public static function get($limit = 0,$offset =0 )
//    {
//        global $wpdb;
//        $table_name = $wpdb->prefix . self::TABLE_NAME;
//        $query  = "SELECT * FROM $table_name ";
//        if ( $limit > 0 ) {
//            $query .= ' LIMIT ' . $limit;
//        }
//        if ( $offset > 0 ) {
//            $query .= ' OFFSET ' . $offset;
//        }
//        return $wpdb->get_results( $query, OBJECT_K );
//    }
//
//
//    public static function update($where,$params)
//    {
//        global $wpdb;
//        $w = [];
//        foreach ($where as $key =>$value){
//            $w[] = $key . ' LIKE "' . $value . '"';
//        }
//
//        $p = ["last_sync_at = now() "];
//        foreach ( $params as $key  => $value ){
//            if (is_string($value)){
//                $p[] = $key . ' = "' . $value .'"';
//            }elseif (is_null($value)){
//                $p[] = $key . ' = null' ;
//            }else{
//                $p[] = $key . ' = '. $value ;
//            }
//
//        }
//        $p = implode(' , ',$p);
//        $w = implode(' AND ',$w);
//        $table_name = $wpdb->prefix . self::TABLE_NAME;
//        $wpdb->query("UPDATE $table_name SET $p WHERE $w  ");
//        return 1;
//    }
//
//    public static function updateProduct($check)
//    {
//        global $wpdb;
//
//        $table_name = $wpdb->prefix . self::TABLE_NAME;
//        $wpdb->query("UPDATE $table_name
//            SET sync_status = 'updating',last_update_at = '". $check->lastUpdate ."'
//            WHERE rewix_product_id in (". implode(',',$check->ids) .") and (last_update_at not like '".$check->lastUpdate ."' or last_update_at is null ) ");
//        return 1;
//    }
//
//    public static function updateWhereNot($where,$params)
//    {
//        global $wpdb;
//        $w = [];
//        foreach ($where as $key =>$value){
//            $w[] = $key . ' NOT LIKE "' . $value . '"';
//        }
//
//        $p = ["last_sync_at = now()"];
//        foreach ($params as $key =>$value){
//            $p[] = $key . ' = "' . $value .'"';
//        }
//        $p = implode(' , ',$p);
//        $w = implode(' AND ',$w);
//        $table_name = $wpdb->prefix . self::TABLE_NAME;
//        $wpdb->query("UPDATE $table_name SET $p WHERE $w  ");
//        return 1;
//    }
//
//    public static function delete($id)
//    {
//        global $wpdb;
//        $table_name = $wpdb->prefix . self::TABLE_NAME;
//        $wpdb->delete($table_name,['id'=> $id]);
//        return 1;
//    }

}


