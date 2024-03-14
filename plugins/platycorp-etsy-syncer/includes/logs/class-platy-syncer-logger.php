<?php

namespace  platy\etsy\logs;
use platy\etsy\EtsySyncerException;
use platy\etsy\NoSuchListingException;
use platy\etsy\NoSuchPostException;
use platy\etsy\NoSuchPostMetaException;

class PlatySyncerLogger{
    private static $sInstance = null;
    private function __construct(){

    }

    public static function get_instance(){
        if(PlatySyncerLogger::$sInstance == null){
            PlatySyncerLogger::$sInstance = new PlatySyncerLogger();
        }
        return PlatySyncerLogger::$sInstance;
    }

    
    public function get_product_count($shop_id) {
        global $wpdb;
        $product_tbl = \Platy_Syncer_Etsy::PRODUCT_TABLE_NAME;
        $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}$product_tbl WHERE shop_id=$shop_id AND type='product'");
        return $count;
    }

    public function get_etsy_item_data($post_id, $shop_id){
        global $wpdb;

        $product_tbl = \Platy_Syncer_Etsy::PRODUCT_TABLE_NAME;

        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}$product_tbl WHERE post_id=$post_id AND shop_id=$shop_id", ARRAY_A);
        if(count($results) == 0) throw new NoSuchListingException();
        return $results[0];
    }

    public function get_product_data($listing_id, $shop_id, $type = 'product'){
        global $wpdb;

        $product_tbl = \Platy_Syncer_Etsy::PRODUCT_TABLE_NAME;

        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}$product_tbl WHERE etsy_id='$listing_id'
             AND shop_id='$shop_id' AND (type='$type' OR type='legacy')", ARRAY_A);
        if(empty($results)) throw new NoSuchListingException();

        $post_id = $results[0]['post_id'];
        if(!get_post_status( $post_id )) {
            throw new NoSuchPostException($post_id);
        }

        return $results[0];
    }



    public function delete_log($post_id, $shop_id){
        global $wpdb;
        $product_tbl = \Platy_Syncer_Etsy::PRODUCT_TABLE_NAME;

        $wpdb->delete("{$wpdb->prefix}$product_tbl", ['post_id' => $post_id, 'shop_id' => $shop_id]);
    }

    public function log_success($post_id, $etsy_id, $shop_id, $type = 'legacy', $parent_id = 0){
        if(empty($post_id)){
            throw new EtsySyncerException("Trying to log success for bad post id");
        }
        if(empty($etsy_id)){
            throw new EtsySyncerException("Trying to log success for bad listing id with post id $post_id");
        }

        $row = [
            'post_id' => $post_id, 
            'etsy_id' => $etsy_id, 
            'status' => true, 
            'error' => "", 
            'shop_id' => $shop_id,
            'type' => $type,
            'parent_id' => $parent_id
        ];
        $this->log($row);
    }

    public function log_error($post_id, $error, $etsy_id, $shop_id, $type = 'legacy', $parent_id = 0){
        if(empty($post_id)){
            throw new EtsySyncerException("Trying to log error for bad post id");
        }

        $row = [
            'post_id' => $post_id, 
            'etsy_id' => empty($etsy_id) ? 0 : $etsy_id, 
            'status' => false, 
            'error' => $error,
            'shop_id' => $shop_id,
            'type' => $type,
            'parent_id' => $parent_id
        ];
        $this->log($row);

    }

    public function log($row){
        global $wpdb;

        $post_id = $row['post_id'];
        $shop_id = $row['shop_id'];
        if(empty($post_id)){
            wp_die( new \WP_Error( 'platy_syncer_error', "Trying to log bad post id", array( 'status' => 403 ) ));
        }
        $product_tbl = \Platy_Syncer_Etsy::PRODUCT_TABLE_NAME;

        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}$product_tbl WHERE post_id=$post_id AND shop_id='$shop_id'");
        if(count($results) == 0){
            $wpdb->insert("{$wpdb->prefix}$product_tbl", $row);
        }else{
            $wpdb->update("{$wpdb->prefix}$product_tbl", $row, ['post_id' => $post_id, 'shop_id' => $shop_id]);
        }
    }


    public function link_product($post_id, $etsy_id, $shop_id, $type = 'legacy', $parent_id = 0) {
        if(empty($post_id)){
            throw new EtsySyncerException("Trying to link to bad post id");
        }

        if(empty($etsy_id)){
            throw new EtsySyncerException("Trying to link with bad etsy id");
        }

        $row = [
            'post_id' => $post_id, 
            'etsy_id' => $etsy_id, 
            'status' => true, 
            'error' => "",
            'shop_id' => $shop_id,
            'type' => $type,
            'parent_id' => $parent_id
        ];

        $this->link($row);
    }

    private function link($prod_row) {
        global $wpdb;

        $etsy_id = $prod_row['etsy_id'];
        $shop_id = $prod_row['shop_id'];

        $product_tbl = \Platy_Syncer_Etsy::PRODUCT_TABLE_NAME;

        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}$product_tbl WHERE etsy_id='$etsy_id' AND shop_id='$shop_id'");
        if(count($results) == 0){
            $wpdb->insert("{$wpdb->prefix}$product_tbl", $prod_row);
        }else{
            $wpdb->update("{$wpdb->prefix}$product_tbl", $prod_row, ['etsy_id' => $etsy_id, 'shop_id' => $shop_id]);
        }
    }

    public function delete_child_logs($parent_id, $shop_id) {
        global $wpdb;
        $product_tbl = \Platy_Syncer_Etsy::PRODUCT_TABLE_NAME;

        $wpdb->delete("{$wpdb->prefix}$product_tbl", ['parent_id' => $parent_id, 'shop_id' => $shop_id]);
    }
    
    private function get_meta($where, $exception) {
        global $wpdb;
        
        $product_tbl = \Platy_Syncer_Etsy::PRODUCT_META_TABLE_NAME;
        $select = "SELECT * FROM {$wpdb->prefix}$product_tbl WHERE $where";
        $results = $wpdb->get_results($select, ARRAY_A);
        
        if(count($results) == 0){
            throw $exception;
        };

        return $results;
    }

    public function get_post_meta_by_regex($post_id, $shop_id, $regex) {
        $where = "shop_id='$shop_id' AND post_id=$post_id AND meta_key LIKE '$regex'";

        $results = $this->get_meta($where, 
            new NoSuchPostMetaException($shop_id, $post_id, $regex));
        
        $results = array_values($results);
        $ret = [];

        foreach($results as $res) {
            $ret[$res['meta_key']] = maybe_unserialize($res['meta_value']);
        }

        return $ret;
    }

    public function get_post_meta($post_id, $shop_id, $meta_key) {
        $where = "shop_id='$shop_id' AND post_id=$post_id AND meta_key='$meta_key'";

        $results = $this->get_meta($where, 
            new NoSuchPostMetaException($shop_id, $post_id, $meta_key));

        return maybe_unserialize($results[0]['meta_value']);
    }

    public function delete_meta_logs($post_id, $shop_id) {
        global $wpdb;
        $product_tbl = \Platy_Syncer_Etsy::PRODUCT_META_TABLE_NAME;

        $wpdb->delete("{$wpdb->prefix}$product_tbl", ['post_id' => $post_id, 'shop_id' => $shop_id]);
    }

    public function log_meta($post_id, $shop_id, $meta_key, $meta_value) {
        global $wpdb;

        $meta_value = maybe_serialize($meta_value);
        $tbl = \Platy_Syncer_Etsy::PRODUCT_META_TABLE_NAME;
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}$tbl WHERE post_id=$post_id AND shop_id=$shop_id AND meta_key='$meta_key'");
        $row = ['post_id' => $post_id, 'shop_id' => $shop_id, "meta_key" => $meta_key, "meta_value" => $meta_value];
        if(count($results) == 0){
            $wpdb->insert("{$wpdb->prefix}$tbl", $row);
        }else{
            $wpdb->update("{$wpdb->prefix}$tbl", $row, ['post_id' => $post_id, 'shop_id' => $shop_id, "meta_key" => $meta_key]);
        }
    }

}