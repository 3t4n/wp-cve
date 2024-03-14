<?php
namespace platy\etsy;
use platy\utils\InventoryUtils;

/**
 * Incharge of inventory syncing.
 */
class EtsyInventorySyncer extends EtsyItemSyncer
{
    protected $etsy_product;

    public function __construct($etsy_product){
        parent::__construct($etsy_product);
        $this->etsy_product = $etsy_product;
    }

    protected function get_etsy_listing_id() {
        return $this->get_etsy_id();
    }

    protected function get_variation_attributes(){
        if($this->etsy_product->get_product()->is_type('variable')){
            /**
             * 
             * @var \WC_Product_Variable $variable_product
             */
            $variable_product = $this->etsy_product->get_product();
            $attributes = $variable_product->get_variation_attributes();
            $curr_id = 513;
            $ids = [];
            foreach($attributes as $attr_name => $attr){
                $ids[$attr_name] = $curr_id;
                ++$curr_id;
            }
            return $ids;
        }
        throw new ProductNotVariableException('Product is not variable');
    }

    protected function prepare_variations_inventory($variation_attributes_ids,$template,$options){
        
        $inventory = [];
        $inventory['json'] = [];
        $product_variations = $this->etsy_product->get_variations();
        foreach($product_variations as $variation){
            /**
             *
             * @var \WC_Product_Variation $product_variation
             */
            $product_variation = $variation->get_product();
            $property_values = [];
            $property_values['property_values'] = [];
            $price_on_property = [];

            foreach($this->etsy_product->get_product()->get_variation_attributes() as $attr => $val){
                $property_values['property_values'][] = [
                    'property_id' => $variation_attributes_ids[$attr],
                    'property_name' => wc_attribute_label($attr,$product_variation),
                    'values' => [$variation->get_attrs()[$attr]]
                ];
                $price_on_property[] =  $variation_attributes_ids[$attr];
            }
            $offering = $variation->to_offering_array($options,$template);
            $offering['is_enabled'] = 1;
            $final_product = [];
            $final_product['offerings'] = [$offering];
            $final_product['property_values'] = $property_values['property_values'];
            $final_product['sku'] = !empty($variation->get_product_sku()) ?  
                    $variation->get_product_sku() : $this->get_product_sku();
            $inventory['json'][]= $final_product;

        }

        $inventory = array(
            'products' => $inventory['json']
        );


        $inventory = $this->add_on_property_vals($inventory, $price_on_property);

        return apply_filters("platy_syncer_variations_inventory", $inventory, $this->get_item_id());
    }

    protected function add_on_property_vals($inventory, $on_property) {

        $inventory['quantity_on_property'] = $on_property;
        $woo_product = $this->etsy_product->get_product();
        if($woo_product->managing_stock()) {
            $aggregate = $this->data_service->get_option('aggregate_quantity',
                 false, $this->get_shop_id());
            if($aggregate) {
                unset($inventory['quantity_on_property']);
            }
        }

        $inventory['price_on_property'] = $on_property;
        $inventory['sku_on_property'] = $on_property;

        return $inventory;
    }

        
    public function to_offering_array($options, $template){
       
        return $this->etsy_product->to_offering_array($options, $template);

    }



    protected function update_variations_inventory($variation_attributes,$template,$options){
        $inventory = $this->prepare_variations_inventory($variation_attributes,$template,$options);
        $ret = $this->api->updateListingInventory(array('data' => $inventory,
            'params' =>array(EtsyProduct::LISTING_ID => $this->get_etsy_listing_id())));
        return $ret;
    }
    

    
    public function verify_product_inventory($template,$options) {
        try {
            $variation_attributes = $this->get_variation_attributes();
            $inventory = $this->prepare_variations_inventory($variation_attributes,$template,$options);
            $this->verify_inventory($inventory);
            return;
        }catch(ProductNotVariableException $e){

        }
        // treat as simple product
        $offering = $this->to_offering_array($options, $template);
        $this->verify_offering($offering);
    }

    protected function verify_inventory($inventory) {
        $products = $inventory['products'];
        $all_zero = true;
        foreach($products as $product) {
            $offering = $product['offerings'][0];
            if($offering['quantity'] > 0) {
                $all_zero = false;
            }
        }
        if($all_zero) {
            throw new ProductOutOfStockException();
        }

        return;
    }

    protected function verify_offering($offering) {
        if($offering['quantity'] <= 0) {
            throw new ProductOutOfStockException();
        }
    }

    protected function log_variations($inventory, $shop_id) {
        $variations = $this->etsy_product->get_variations();
        foreach($inventory['products'] as $product) {
            try {
                $var = InventoryUtils::match_variation_to_etsy_product($product, $variations);
                $this->item_logger->log_success($var->get_item_id(), $product['product_id'],
                     $shop_id, "variation", $this->get_item_id());
            }catch(NoVariationMatchException $e) {

            }
        }
    }

    public function upload_inventory($options,$template) {
        $inventory = $this->update_main_inventory($options,$template);
        try{
            $variation_attributes = $this->get_variation_attributes();
            $inventory = $this->update_variations_inventory($variation_attributes,$template,$options);
            $this->log_variations($inventory, $this->shop_id);
        }catch(ProductNotVariableException $e){

        }

    }

    protected function get_product_sku(){
       return $this->etsy_product->get_product_sku();
    }

    protected function update_main_inventory($options,$template){
        $offering = $this->to_offering_array($options,$template);
        $offering['is_enabled'] = true;
        $inventory = [['sku' => $this->get_product_sku(), 'offerings' => [$offering], 'property_values' => []]];

        $inventory = array(
            'products' => $inventory
        );
        $this->api->updateListingInventory(array('data' => $inventory,
            'params' =>array(EtsyProduct::LISTING_ID => $this->get_etsy_listing_id())));
        return $inventory;

    }

}