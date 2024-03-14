<?php

namespace platy\etsy;

use platy\etsy\logs\PlatySyncerLogger;
class ProductAttributesService extends DataService {
    private static  $instance = null;

    private function __construct() {
        parent::__construct(\Platy_Syncer_Etsy::PRODUCT_ATTRIBUTES_TABLE_NAME);
    }

    public static function get_instance() {
        if(ProductAttributesService::$instance == null) {
            ProductAttributesService::$instance = new ProductAttributesService();
        }
        return ProductAttributesService::$instance;
    }

    private function unserialize_attribute($attr) {
        foreach($attr as $key => $value) {
            $attr[$key] = maybe_unserialize($value);
        }
        $attr['enabled'] = $attr['enabled'] == 1;
        return $attr;
    }

    private function load_attribute_from_meta($metas) {
        $attrs = [];
        foreach($metas as $meta_name => $meta_value) {
            $split = \explode("-", $meta_name);
            $prop_id = $split[1];
            $attrs[$prop_id] = ['property_id' => $prop_id];
        }

        unset($meta_name);

        foreach($metas as $meta_name => $meta_value) {
            $split = \explode("-", $meta_name);
            $prop_id = $split[1];
            $meta = $split[4];
            $attrs[$prop_id][$meta] = $meta_value;
        }
        return $attrs;
    }

    private function load_attributes_from_meta($pid, $shop_id, $tax_id) {
        $meta_service = PlatySyncerLogger::get_instance();
        try {
            $results = 
                $meta_service->get_post_meta_by_regex($pid, $shop_id, "attr-%-tax-$tax_id-%"); 
        }catch(NoSuchPostMetaException $e) {
            throw new NoAttributesException($tax_id);
        }
        
        $attrs = $this->load_attribute_from_meta($results);
        return $attrs;
    }

    private function load_attributes_from_table($shop_id, $tax_id) {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}{$this->tbl_name}
            WHERE tax_id='$tax_id' AND shop_id='$shop_id'", ARRAY_A);
    
        if(count($results) == 0) {
            throw new NoAttributesException($tax_id);
        }
    
        $ret = [];
        foreach($results as $key => $attr) {
            $ret[$attr['property_id']] = $this->unserialize_attribute($attr);
        }
        
        return $ret;
    }

    /**
     * second array takes precedent
     */
    private function merge_attrs($shop_id, $tax_id, $post_id) {
        
        try {
            $attrs_from_meta = $this->load_attributes_from_meta($post_id, $shop_id, $tax_id);
        }catch(NoAttributesException $e) {
            $attrs_from_meta = [];
        }
    
        try {
            $attrs_from_table = $this->load_attributes_from_table($shop_id, $tax_id);
        }catch(NoAttributesException $e) {
            $attrs_from_table = [];
        }

        $attrs1 = $attrs_from_table;
        $attrs2 = $attrs_from_meta;
        $ret = [];

        foreach($attrs1 as $prop_id => $attr) {
            $ret[$prop_id] = $attr;
        }
        foreach($attrs2 as $prop_id => $attr) {
            if($attr['enabled']) {
                $ret[$prop_id] = $attr;  
            }
        }

        return $ret;
    } 

    public function load_attributes($shop_id, $tax_id, $post_id, $merge) {
        if($merge) {
            return $this->merge_attrs($shop_id, $tax_id, $post_id);
        }

        if(!empty($post_id)) {
            return $this->load_attributes_from_meta($post_id, $shop_id, $tax_id); 
        }

        return $this->load_attributes_from_table($shop_id, $tax_id);
    }

    public function update($attribute, $tax_id, $shop_id) {
        global $wpdb;

        foreach($attribute as $key => $value) {
            $attribute[$key] = maybe_serialize($value);
        }

        $attribute['tax_id'] = $tax_id;
        $attribute['shop_id'] = $shop_id;

        $prop_id = $attribute['property_id'];
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}{$this->tbl_name}
            WHERE tax_id='$tax_id' AND property_id='$prop_id' AND shop_id='$shop_id'");
        
        if(count($results)==0){
            $wpdb->insert($wpdb->prefix . $this->tbl_name,$attribute);
        }else{
            $wpdb->update($wpdb->prefix . $this->tbl_name,$attribute, [
                    'tax_id' => $tax_id, 
                    "property_id" => $prop_id,
                    'shop_id' => $shop_id
                ]
            );
        }
    }

    public function delete($shop_id, $tax_id, $prop_id){
        global $wpdb;

        $connections_tbl = $this->tbl_name;
        $wpdb->delete($wpdb->prefix . $this->tbl_name, [
            'tax_id' => $tax_id, 
            "property_id" => $prop_id,
            'shop_id' => $shop_id
        ]
    );
    }
}