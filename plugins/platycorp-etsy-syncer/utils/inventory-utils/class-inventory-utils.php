<?php

namespace platy\utils;
use platy\etsy\NoVariationMatchException;
use platy\etsy\EmptyVariationException;

class InventoryUtils {

    private static function match_variation_recursive($prop_values, $variation, $i = 0) {
        if($i == \count($prop_values)) {
            return true;
        }

        $prop_value = $prop_values[$i];

        try {
            $attr = $variation->get_attribute($prop_value['property_name']);
        }catch(EmptyVariationException $e) {
            return false;
        }
        
        if(empty($attr) || $attr[0] == $prop_value['values'][0]) { // empty attr meens all attributes match
            return InventoryUtils::match_variation_recursive($prop_values, $variation, $i + 1);
        }
        return false;
    }

    private static function match_variation_sku($etsy_product, $variations) {

        foreach($variations as $variation) {

            $sku = $variation->get_product()->get_sku();
            if(empty($sku)) {
                continue;
            }

            $parent_product = $variation->get_parent_product();
            if(empty($parent_product)) {
                continue;
            }
            $parent_sku = $parent_product->get_sku();

            if($parent_sku == $sku) {
                continue;
            }

            if($etsy_product['sku'] == $sku) {
                return $variation;
            }
        }
        
        throw new NoVariationMatchException("No match by sku"); 
    }

    public static function match_variation_to_etsy_product($etsy_product, $variations) {
        if(empty($etsy_product) || empty($variations)) {
            throw new NoVariationMatchException("Empty etsy product or variations");  
        }

        if($variations[0]->get_attr_num() != \count($etsy_product['property_values'])) {
            throw new NoVariationMatchException("No match in attribute count");  
        }
        
        try {
            return InventoryUtils::match_variation_sku($etsy_product, $variations);
        }catch(NoVariationMatchException $e) {

        }

        foreach($variations as $variation) {
            
            if(InventoryUtils::match_variation_recursive($etsy_product['property_values'], $variation)) {
                return $variation;
            }
        }

        throw new NoVariationMatchException("No match by sku or property names");
    }

    public static function calc_price($price_unit){
        if(empty($price_unit['amount'])){
            return 0;
        }
        if(empty($price_unit['divisor'])){
            return $price_unit['amount'];
        }
        return $price_unit['amount'] / $price_unit['divisor'];
    }
}