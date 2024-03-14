<?php
namespace BDroppy\Models;

if ( ! defined( 'ABSPATH' ) ) exit;

class Catalog
{

    public static $table = 'dropshipping_catalogs';


    public static function create(array $params)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::TABLE_NAME;
        return $wpdb->insert($table_name,$params);
    }

    public static function getByStatus($status,$limit)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::TABLE_NAME;
        $query  = "SELECT * FROM $table_name WHERE status = '$status'";
        if ( $limit > 0 ) {
            $query .= ' LIMIT ' . $limit;
        }
        return $wpdb->get_results( $query, ARRAY_A );
    }

    public static function getById($name)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::TABLE_NAME;
        $query  = "SELECT * FROM $table_name WHERE catalog = '$name'";
        $res  = $wpdb->get_results( $query,OBJECT );
        if (count($res) > 0){
            return $res[0];
        }
        return null;
    }


    public static function getByIdNot($name)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::TABLE_NAME;
        $query  = "SELECT id,catalog,status,locales,page,imported FROM $table_name WHERE catalog not like '$name'";
        if (count($wpdb->get_results( $query,OBJECT )) > 0)
            return $wpdb->get_results( $query,OBJECT )[0];
        else
            return null;
    }

    public static function update($id,$params)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::TABLE_NAME;
        $wpdb->update($table_name,$params,['id' => $id]);

        return 1;
    }


    public static function delete($id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::TABLE_NAME;
        $wpdb->delete($table_name,['id' => $id]);

        return 1;
    }

}


