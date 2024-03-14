<?php

namespace platy\etsy\orders;
use platy\etsy\EtsyDataService;
use platy\etsy\EtsySyncerException;

class EtsyOrderItem {

    private $item;
    private $properties;
    private $transaction_id;
    public function __construct($item, $transaction_id = 0 ) {
        $this->item = $item;
        $this->properties = [];
        if(!empty($transaction_id)){
            $this->transaction_id = $transaction_id;
        }else{

            if(!empty($item->get_id())){
                $this->transaction_id = wc_get_order_item_meta($item->get_id(), "_etsy_transaction_id", true);
            }
        }
    }

    public function get_woo_item() {
        return $this->item;
    }

    public function __call($name, $arguments) {
        if(!empty($this->item)) {
            return \call_user_func_array([$this->item, $name], $arguments);
        }
    }

    public function set_etsy_property($prop_name, $prop_value){
        $this->properties[$prop_name] = $prop_value;
    }

    public function add_etsy_meta_data(){
        foreach($this->properties as $prop_name => $prop_value){
            $this->item->add_meta_data($prop_name, $prop_value, true);
        }
        $this->item->add_meta_data("_is_platy_etsy_order_item", "true", true);
        $this->item->add_meta_data("_etsy_transaction_id", $this->transaction_id, true);
    }

    public function get_id() {
        return $this->item->get_id();
    }

    public function get_transaction_id(){
        return $this->transaction_id;
    }

    public function get_thumbnail(){
        return wc_get_order_item_meta($this->get_id(), "_etsy_image_src_75x75", true);
    }

    public function get_etsy_link(){
        $listing_id = wc_get_order_item_meta($this->get_id(), "_etsy_listing_id", true);
        return "https://etsy.com/listing/$listing_id";
    }

    private function get_woo_id() {
        $item_id = $this->get_id();
        $etsy_product_id = wc_get_order_item_meta($item_id, "_etsy_product_id", true);
        $etsy_shop_id = wc_get_order_item_meta($item_id, "_etsy_shop_id", true);
        $etsy_listing_id = wc_get_order_item_meta($item_id, "_etsy_listing_id", true);
        $data_service = EtsyDataService::get_instance();
        try {
            return $data_service->get_log_product_id($etsy_listing_id, $etsy_shop_id, 'product');
        }catch(EtsySyncerException $e) {

        }

        try {
            return $data_service->get_log_product_id($etsy_product_id, $etsy_shop_id, 'variation');
        }catch(EtsySyncerException $e) {

        }

        $etsy_sku = wc_get_order_item_meta($item_id, "_etsy_sku", true);
        return wc_get_product_id_by_sku($etsy_sku);
    }

    public function get_woo_link() {
        $woo_id = $this->get_woo_id();
        if(empty($woo_id)) {
            return null;
        }

        $parent_id = wp_get_post_parent_id( $woo_id );
        $woo_id = empty($parent_id) ? $woo_id : $parent_id;
        return get_edit_post_link( $woo_id );
    }

    public function get_item_variation_props(){
        $metadata = $this->get_formatted_meta_data('', true);

        $ret = [];
        foreach($metadata as $meta){
            if(\substr($meta->key, 0, 6) != "_prop_"){
                continue;
            }
            $prop_key = \substr($meta->key, 6);
            $ret[$prop_key] = $meta->value;
            
        }

        return $ret;
    }
}