<?php
namespace BDroppy\Models;

if ( ! defined( 'ABSPATH' ) ) exit;

class ProductModel extends BaseModel
{
    public static $table = 'dropshipping_product_models';

//    public static function create(array $params)
//    {
//        global $wpdb;
//        $table_name = $wpdb->prefix . self::TABLE_NAME;
//        $wpdb->insert($table_name,$params);
//    }
//
//    public static function getByRewixProductId($id,$model = null)
//    {
//        global $wpdb;
//        $table_name = $wpdb->prefix . self::TABLE_NAME;
//        $query  = "SELECT * FROM $table_name WHERE rewix_product_id = '$id'";
//        if (!empty($model)){
//            $query .= " and rewix_model_id = '$model'";
//        }
//
//        return $wpdb->get_results( $query, ARRAY_A );
//    }
//    public static function delete($id)
//    {
//        global $wpdb;
//        $table_name = $wpdb->prefix . self::TABLE_NAME;
//        $wpdb->delete($table_name,['id'=> $id]);
//        return 1;
//    }

}