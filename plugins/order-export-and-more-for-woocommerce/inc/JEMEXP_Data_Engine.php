<?php
if (!defined('ABSPATH')) {
    die('Do not open this file directly.');
}


class JEMEXP_Data_Engine{

    /**
     * Gets the list of valuse for this field for an order
     * @param $post - the santized $_POST var
     * @return array - list of fields, empty array if nothing found
     */
    public function order($post){
        global $wpdb;


        if( !isset($post['field'])) {
            return array();
        }

        $field = $post['field'];

        //ok let's get all the values for this field
        $sql = $wpdb->prepare("SELECT DISTINCT meta_value FROM  {$wpdb->postmeta} m inner join {$wpdb->posts} p on m.post_id=p.ID where p.post_type='shop_order' and m.meta_key = %s  ", $field);

        $result = $wpdb->get_col($sql);

        apply_filters('jemx_get_order_filter_values', $result);

        return $result;
    }

    /**
     * Gets the list of products that start with the parameter passed in
     * @param $post - the santized $_POST var
     * @return array - list of products with ID's
     */
    public function product($post){
        global $wpdb;

        //make sure the param is passed
        if( !isset($post['q'])) {
            return array();
        }

        $query = $wpdb->esc_like( $post['q'] );

        //$query = '%' . $query . '%';

        //ok let's get all the values for this field
        $sql = $wpdb->prepare("SELECT ID as id, post_title as text FROM {$wpdb->posts} WHERE post_type='product' and post_title LIKE %s",'%' .$wpdb->esc_like( $post['q'] ) . '%');

        $result = $wpdb->get_results($sql);

        apply_filters('jemx_get_product_filter_values', $result);

        return $result;
    }

    /**
     * Gets the list of product categories that start with the parameter passed in
     * @param $post - the santized $_POST var
     * @return array - list of products with ID's
     */
    public function product_categories($post){
        global $wpdb;

        $cats = array();

        $args = array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'number' => 100,
            'name__like' => $post['q']
        );

        $ret = get_terms($args);

        foreach($ret as $item){
            $cats[] = array(
                'id' => $item->term_id,
                'text' => $item->name
            );

        }

        return $cats;

    }


    /**
     * Gets the list of coupons that start with the parameter passed in
     * @param $post - the santized $_POST var
     * @return array - list of products with ID's
     */
    public function all_coupons($post){
        global $wpdb;

        $coupons = array();

        $args = array(
            'posts_per_page'   => -1,
            'orderby'          => 'title',
            'order'            => 'asc',
            'post_type'        => 'shop_coupon',
            'post_status'      => 'publish',
        );

        $ret = get_posts( $args );

        foreach($ret as $item){
            $coupons[] = array(
                'id' => $item->ID,
                'text' => $item->post_title
            );

        }

        return $coupons;

    }

    /**
     * Gets the basic meta from postmeta for orders
     * @return mixed|void
     */
    public function get_order_basic_meta(){
        global $wpdb;

        $fields = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->posts} INNER JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id WHERE post_type = 'shop_order'");

        natsort($fields);

        $ret = apply_filters('jemxp_get_order_basic_meta', $fields);

        return $ret;

    }


    /**
     * Gets the WooCommerce Order Item Meta for LINE ITEMS
     */
    public function get_woo_order_item_meta(){

        global $wpdb;

        $fields = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->prefix}woocommerce_order_itemmeta a INNER JOIN {$wpdb->prefix}woocommerce_order_items b ON a.order_item_id = b.order_item_id WHERE b.order_item_type='line_item'");

        sort($fields, SORT_NATURAL | SORT_FLAG_CASE);

        $ret = apply_filters('jemxp_get_woo_order_item_meta', $fields);

        return $ret;
    }


    /**
     * Gets any line item meta for an item
     * @param $lineItem
     */
    public function get_order_line_item_meta($lineItem){


    }

    /**
     * Gets all the meta data variations for products and product variations
     * @return mixed|void
     */
    public function get_order_product_meta(){
        global $wpdb;

        $fields = $wpdb->get_col( "select distinct meta_key from {$wpdb->postmeta} m INNER JOIN {$wpdb->posts} p on m.post_id = p.ID WHERE post_type IN ('product','product_variation')" );

        sort($fields);

        $ret = apply_filters('jemxp_get_order_product_meta', $fields);

        return $ret;
    }

    /**
     * Gets the fields from the USER and related tables
     */
    public function get_order_user_fields(){
        global $wpdb;

        $fields = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->posts} p INNER JOIN {$wpdb->usermeta} m ON p.post_author = m.user_id WHERE p.post_type = 'shop_order'" );
        sort($fields);

        $ret = apply_filters('jemxp_get_order_user_fields', $fields);

        return $ret;

    }

    /**
     * Gets the coupon fields & meta
     */
    public function get_order_coupon_fields(){
        global $wpdb;

        $fields = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->posts} p INNER JOIN  {$wpdb->postmeta} m ON p.ID=m.post_id  WHERE post_type = 'shop_coupon'" );
        sort($fields);

        $ret = apply_filters('jemxp_get_order_coupon_fields', $fields);

        return $ret;

    }

    /**
     * Gets all the postmeta data for a specific order
     * @param $id
     * @return mixed|void
     */
    public function get_meta_for_order($id){
        global $wpdb;

        $rows = $wpdb->get_results($wpdb->prepare("SELECT m.* FROM {$wpdb->postmeta} m, {$wpdb->posts} p  WHERE p.ID =%d and m.post_id = p.ID", $id), ARRAY_A);

        $ret = apply_filters('jemxp_get_meta_for_order', $rows);

        return $ret;

    }

    //***********************************************************
    // Ok here is the main query SQL - this is non-trivial
    //***********************************************************

    public static function generate_base_sql($params){
        global $wpdb;

        //First lets do the postmeta args (if there are any)
        $found = false;
        foreach($params['orderParams']['order_filters_fba'] as $key=>$val){
            if($val['datatype'] == "postmeta"){
                $mq = array();
                $found = true;
                break;
            }
        }

        if( $found ){
            $mq = self::generate_postmeta_subquery($params);

        }

        //what do we want to select??

        $select = "select posts.* ";

        //we always join with postmeta cos we might need to use postmeta in the where clause
        $from = " from {$wpdb->posts} posts LEFT join {$wpdb->postmeta} postmeta on post.ID = postmeta.post_id";


        //Now the WHERE clause
        $where = array();


        //now let's get all the WHERE clauses from FBA

        //First what postmeta do we want??
        foreach($params['orderParams']['order_filters_fba'] as $key => $val){
                if($val['datatype'] == "postmeta"){

                //construct the equality statement
                $eq = 'postmeta.' . $val['name'];
                $eq .= " " . $val['select'] . " ";


                if($val['type'] == 'text'){
                    $eq .= "'" . $val['value'] . "'";
                } else {
                    $eq .= $val['value'];

                }

                $where[] = $eq;
            }
        }

        $whereSQL = implode(' OR ', $where);
        //Only get shop orders
        $whereSQL =  ' posts.post_type = "shop_order" AND ( ' . $whereSQL . ' )';

        //OK mow construct the sql...
        $sql = $select . $from . " WHERE " . $whereSQL;

        return $sql;

    }

    //This generates the meta_query piece of the args
    //returns the array
    public static function generate_postmeta_subquery($params){
        global $wpdb;

        //Let's set up the basic sql

        $mq = array();

        foreach($params['orderParams']['order_filters_fba'] as $key=>$val){
            
            //this is only for postmeta fields so skip if not
            if($val['datatype'] != 'postmeta'){
                continue;
            }

            //Create the where clause
            $mq[]= array(
                'key'       =>  $val['name'],
                'value'     =>  $val['value'],
                'compare'   =>  $val['select']
            );


        }

        return $mq;
    }

}