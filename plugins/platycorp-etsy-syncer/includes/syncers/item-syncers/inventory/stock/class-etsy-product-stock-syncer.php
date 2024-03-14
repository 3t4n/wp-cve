<?php
namespace platy\etsy;
use platy\utils\InventoryUtils;
use platy\etsy\logs\PlatyLogger;
use platy\etsy\logs\PlatySyncerLogger;

class EtsyProductStockSyncer extends EtsyInventorySyncer {
    const EXPERIMENT_IDS = [

    ];
    const LOG_TYPE = "stock_sync";
    public function __construct($etsy_product){
        parent::__construct($etsy_product);
        $this->etsy_product = $etsy_product;

        if(!empty(self::EXPERIMENT_IDS) && !in_array($etsy_product->get_item_id(), self::EXPERIMENT_IDS)){
            throw new NotStockManagedException($etsy_product->get_item_id());
        } 
    }

    private function get_variations() {
        return $this->etsy_product->get_variations();
    }

    public function get_cached_inventory($invalidate = false) {
        $post_id = $this->get_item_id();
        $inventory_cache = wp_cache_get( "plty_etsy_invtry_$post_id");
        if(!empty($inventory_cache) && !$invalidate) {
            return $inventory_cache;
        }
        $inventory_cache = $this->api->getListingInventory([
            'params' =>array(EtsyProduct::LISTING_ID => $this->get_etsy_id())
        ]);
        wp_cache_add("plty_etsy_invtry_$post_id", $inventory_cache, "", 5);
        return $inventory_cache;
    }

    public function mask_stock($stock_to_mask) {
        $inventory = $this->get_cached_inventory();
        $product = $inventory['products'][0];
        return \min($stock_to_mask, $product['offerings'][0]['quantity']);

    }

    public function mask_variation_stock($stock_to_mask, $var_id) {
        $inventory = $this->get_cached_inventory();
        $post_id = $this->get_item_id();
        try {
            $etsy_var_id = $this->data_service->get_etsy_product_id($var_id, $this->shop_id);
        }catch(NoSuchListingException $e) {
            $etsy_var_id = 0;
        }
        
        $etsy_product = null;

        if(!empty($etsy_var_id)) {

            foreach($inventory['products'] as $inv_product) {
                if($inv_product['product_id'] == $etsy_var_id) {
                    $etsy_product = $inv_product;
                }
            }
        }
        if(empty($etsy_product)) {
            $variations = $this->get_variations();
            foreach($inventory['products'] as $inv_product) {
                $var = InventoryUtils::match_variation_to_etsy_product($inv_product, $variations);
                if($var->get_item_id() == $var_id) {
                    $etsy_product = $inv_product;
                    break;
                }
            }
        }

        return \min($stock_to_mask, $etsy_product['offerings'][0]['quantity']);

    }


    private function decrease_stock($etsy_product, $decrease_by) {
        if(!$etsy_product->get_product()->managing_stock()) {
            throw new NotStockManagedException($this->get_item_id());
        }

        $current_stock = $etsy_product->get_product()->get_stock_quantity();
        $decrease_by =  ($current_stock - $decrease_by > 0) ? $decrease_by : $current_stock;
        $decrease_by = \max($decrease_by, 0);
        wc_update_product_stock($etsy_product->get_product(), $decrease_by, 'decrease', true);

        $title = $etsy_product->get_product()->get_title();
        $pid = $etsy_product->get_item_id();
        $logger = PlatyLogger::get_instance();
        $logger->log_general("decreased $pid - '$title' quantity by $decrease_by", EtsyProductStockSyncer::LOG_TYPE);
    }


    public function sync_etsy_transaction($transaction, $shop_id) {
        $logger = PlatyLogger::get_instance();
        $transaction_id = $transaction['transaction_id'];
        $log = $logger->get_log("shop_id='$shop_id' AND etsy_id='$transaction_id' AND status=1 and type='stock_sync_transaction'");
        if(!empty($log)) {
            throw new TransactionAlreadySyncedException($transaction_id);
        }

        $inventory = $this->api->getListingInventory([
            'params' =>array(EtsyProduct::LISTING_ID => $this->get_etsy_id())
        ]);

        try {
            $variations = $this->get_variations();
            $product_id = empty($transaction['product_id']) ? 0 : $transaction['product_id'];

            $var_id = 0;
            try {
                $var_id = $this->get_log_product_id($product_id, $this->shop_id, 'variation');
            }catch(NoSuchPostException | NoSuchListingException $e) {

            }
            
            $variation = null;
            foreach($variations as $var) {
                if($var_id == $var->get_product()->get_id()){
                    $variation = $var;
                }
            }

            if(empty($variation)) {
                $variations = $this->get_variations();
                $transaction_product = null;
                foreach($inventory['products'] as $inv_product) {
                    if($inv_product['product_id'] == @$transaction['product_id']) {
                        $transaction_product = $inv_product;
                    }
                }

                if(!empty($transaction_product)) {
                    $variation = InventoryUtils::match_variation_to_etsy_product($transaction_product, $variations);
                }
            }

            if(empty($variation)) {
                $inv_product = [];
                $inv_product['property_values'] = [];
                foreach($transaction['variations'] as $transaction_var) {
                    $inv_product['property_values'][] = [
                        'property_name' => $transaction_var['formatted_name'],
                        'values' => [$transaction_var['formatted_value']]
                    ];
                }
                $variation = InventoryUtils::match_variation_to_etsy_product($inv_product, $variations);
            }


            $this->decrease_stock($variation, $transaction['quantity']);
        }catch(ProductNotVariableException $e) {
            $this->decrease_stock($this->etsy_product, $transaction['quantity']);
        }
        $this->sync_woo_stock($inventory);
        $logger->log_success("Synced transaction id $transaction_id", 'stock_sync_transaction', $shop_id, null, $transaction_id);
        do_action("platy_etsy_transaction_synced", $transaction_id, $this->get_item_id());
    }

    protected function format_offering($offering) {
        return [
            'price' => InventoryUtils::calc_price($offering['price']),
            'quantity' => $offering['quantity'],
            'is_enabled' => 1
        ];
    }

    protected function format_property_value($property_values) {
        return [
            'property_id' => $property_values['property_id'],
            'property_name' => $property_values['property_name'],
            'values' => $property_values['values']
        ];
    }

    protected function format_product($product) {
        $property_values = [];
        foreach($product['property_values'] as $prop_value) {
            $property_values[] = $this->format_property_value($prop_value);
        }
        return [
            'sku' => $product['sku'],
            'offerings' => [$this->format_offering($product['offerings'][0])],
            'property_values' => $property_values
        ];
    }

    protected function format_inventory($inventory) {
        $products = [];
        foreach($inventory['products'] as $product) {
            $products[] = $this->format_product($product);
        }
        $prop_ids = [];
        foreach($products as $product) {
            $prop_values = empty($product['property_values']) ? [] : $product['property_values'];
            foreach($prop_values as $prop_value) {
                $prop_value_id = @$prop_value['property_id'];
                
                if(empty($prop_value_id)) {
                    continue;
                }

                if(!\in_array($prop_value_id, $prop_ids)) {
                    $prop_ids[]= $prop_value_id;
                }
            }
        }

        $formatted = [
            'products' => $products
        ];

        return $this->add_on_property_vals($formatted, $prop_ids);
    }

    private function verify_stock_managed() {
        try {
            $variations = $this->get_variations();
        
            $stock_managed = false;
            foreach($variations as $variation) {
                if($this->is_stock_manged($variation->get_product())){
                    $stock_managed = true;
                }
            }
            if(!$stock_managed) {
                throw new NotStockManagedException($this->get_item_id());
            }
        }catch(ProductNotVariableException $e) {
            $stock_managed = $this->is_stock_manged($this->etsy_product->get_product());
            if(!$stock_managed) {
                throw new NotStockManagedException($this->get_item_id());
            }
        }
    }

    public function sync_woo_stock_v2($template) {
        $options = $this->data_service->get_options_as_array();
        $this->verify_product_inventory($template, $options);
        try{
            $variation_attributes = $this->get_variation_attributes();
            $ret = $this->update_variations_inventory($variation_attributes, $template, $options);
            $this->log_variations($ret, $this->shop_id);

        }catch(ProductNotVariableException $e) {
            $this->update_main_inventory($options,$template);
        }
    }

    private function deactivate_product() {
        $product_syncer = new EtsyProductSyncer($this->etsy_product);
        $state = $product_syncer->get_listing_state();
        if($state == 'active') {
            $product_syncer->update_state('inactive');
        }
    }

    public function sync_woo_stock($inventory = null) {
        $this->verify_stock_managed();
        try {
            $fast_stock_sync = $this->get_option("enable_v2_stock_sync", false);
            if($fast_stock_sync) {
                $sync_logger = PlatySyncerLogger::get_instance();
                $tid = $sync_logger->get_post_meta($this->get_item_id(), 'template_id', $this->get_shop_id());
                $template = $this->data_service->get_template_metas($tid);
            }

        }catch(NoSuchPostMetaException | NoSuchTemplateException $e) {
            $fast_stock_sync = false;
        }

        $logger = PlatyLogger::get_instance();
        $post_id = $this->get_item_id();
        $listing_id = $this->get_etsy_id();

        try {
            if(!$fast_stock_sync) {
                $this->sync_woo_stock_v1($inventory);
                $logger->log_general("synced $post_id using v1 stock syncing", 
                    EtsyProductStockSyncer::LOG_TYPE);
            }else {
                $this->sync_woo_stock_v2($template);
                $logger->log_general("synced $post_id using v2 stock syncing",
                    EtsyProductStockSyncer::LOG_TYPE);
            }
        }catch(ProductOutOfStockException $e) {
            if($this->get_option("out_of_stock_auto_deactivate" ,true)) {
                $this->deactivate_product();
                $logger->log_general("$post_id out of stock, deactivated Etsy listing $listing_id", 
                    EtsyProductStockSyncer::LOG_TYPE);
            }
            throw $e;
        }


        if($this->get_option("restock_auto_activate", false)){
                
            $product_syncer = new EtsyProductSyncer($this->etsy_product);
            $state = $product_syncer->get_listing_state();

            if(!\in_array($state, ['active', 'draft'])) {
                $product_syncer->update_state('active');
                $logger->log_general("$post_id is inactive on Etsy, reactivated listing $listing_id", 
                    EtsyProductStockSyncer::LOG_TYPE);
            }
        }
    }

    public function sync_woo_stock_v1($inventory = null) {
        if(empty($inventory)) {
            $inventory = $this->api->getListingInventory(array('params' =>
                array(EtsyProduct::LISTING_ID => $this->get_etsy_id())));
        }
        
        try{
            $inventory = $this->sync_variations_stock($inventory);
        }catch(ProductNotVariableException $e) {
            $inventory = $this->sync_simple_stock($inventory);
        }
        $inventory = $this->format_inventory($inventory);

        $this->verify_inventory($inventory);

        $ret = $this->api->updateListingInventory(array('data' => $inventory,
            'params' =>array(EtsyProduct::LISTING_ID => $this->get_etsy_id())));
        try {
            $this->log_variations($ret, $this->shop_id);
        }catch(ProductNotVariableException $e) {
            
        }

    }

    private function is_stock_manged($woo_product) {
        return $woo_product->managing_stock();
    }
    
    private function get_woo_stock_quantitiy($etsy_product) {

        $product = $etsy_product->get_product();
        $product_id = $product->get_id();
        if(!$this->is_stock_manged($product)) {
            throw new NotStockManagedException($product->get_id());
        }

        $options = [
            'stock_management' => "shop-stock",
            'max_quantity' => $this->get_option('max_quantity', EtsyProduct::MAX_STOCK)
        ];
        $woo_stock = $etsy_product->get_quantity($options);
        return max(0, $woo_stock);
    }

    private function sync_simple_stock($inventory) {
        $product = &$inventory['products'][0];
        $new_stock = $this->get_woo_stock_quantitiy($this->etsy_product);
        $product['offerings'][0]['quantity'] = $new_stock;
        $this->etsy_product->get_product()->set_stock_quantity($new_stock);
        return $inventory;
    }

    private function sync_variations_stock($inventory) {
        $product = $this->etsy_product->get_product();    
        $products = &$inventory['products'];
        $variations = $this->get_variations();

        foreach($products as &$inventory_product) {
            
            $var_id = 0;
            try {
                $var_id = $this->get_log_product_id($inventory_product['product_id'], $this->shop_id, 'variation');
            }catch(NoSuchPostException | NoSuchListingException $e) {

            }

            $variation = null;
            foreach($this->get_variations() as $var) {
                if($var_id == $var->get_product()->get_id()){
                    $variation = $var;
                }
            }

            if(empty($var_id) || empty($variation)) {
                $variation = InventoryUtils::match_variation_to_etsy_product($inventory_product, $variations);
            }
            
            try {
                $woo_stock = $this->get_woo_stock_quantitiy($variation);
                $inventory_product['offerings'][0]['quantity'] = $woo_stock;
            }catch(NotStockManagedException $e) {

            }


        }
        return $inventory;

    }
}