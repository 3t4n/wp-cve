<?php

namespace platy\etsy;

use platy\etsy\logs\PlatyLogger;
use platy\etsy\orders\EtsyOrdersSyncer;

class EtsyStockSyncer extends EtsySyncer {

    private $stock_update_queue;

    public function __construct($shop_id = 0){
        parent::__construct($shop_id);
        $this->stock_update_queue = [];
    }

    public function on_stock_meta_update($meta_id, $post_id, $meta_key, $meta_value) {
        if($meta_key != "_stock") {
            return;
        }

        $type = get_post_type( $post_id );
        if(!\in_array($type, ['product', 'product_variation'])) {
            return;
        }

        $this->add_to_stock_queue($post_id);
    }

    public function remove_from_stock_queue($transaction_id, $post_id) {
        $logger = PlatyLogger::get_instance();
        $logger->log_general("removing $post_id from stock queue", EtsyProductStockSyncer::LOG_TYPE);
        if (($key = array_search($post_id, $this->stock_update_queue)) !== false) {
            unset($this->stock_update_queue[$key]);
        }
    }

    public function add_to_stock_queue($post_id) {
        $parent_id = wp_get_post_parent_id($post_id);
        if(!empty($parent_id)) {
            $post_id = $parent_id;
        }

        if(\in_array($post_id, $this->stock_update_queue)) {
            return; 
        }
        $logger = PlatyLogger::get_instance();
        $logger->log_general("adding $post_id to stock queue", EtsyProductStockSyncer::LOG_TYPE);
        $this->stock_update_queue[] = $post_id;
    }

    public function sync_stock_update_queue() {
        $logger = PlatyLogger::get_instance();
        foreach($this->stock_update_queue as $post_id) {
            try {
                $shop_id = $this->data_service->get_current_shop_id();
                $this->sync_stock($post_id);
                $logger->log_success("synced post id $post_id", 
                    EtsyProductStockSyncer::LOG_TYPE, $shop_id, $post_id);
            }catch(\Exception | \Error $e) {
                $error = $e->getMessage();
                $logger->log_error("error for post $post_id on stock queue - $error", 
                    EtsyProductStockSyncer::LOG_TYPE, $shop_id, $post_id);
            }
        }
    }


    
    private static function sync_etsy_orders($shop_id, $min_created) {
        $orders_syncer = new EtsyOrdersSyncer($shop_id);
        $data_service = EtsyDataService::get_instance();
        $logger = PlatyLogger::get_instance();
        $limit = 25;
        $offset = 0;
        $recipts = [];
        do {
            $receipts = $orders_syncer->get_etsy_orders_by_date($min_created, $limit, $offset, "create_timestamp");
            foreach($receipts as $receipt) {
                $receipt_id = $receipt['receipt_id'];

                try {
                    EtsyStockSyncer::sync_etsy_receipt($receipt, $shop_id);

                }catch(NotStockManagedException | NoSuchListingException $e){
                    $logger->log_error("Failed to sync receipt $receipt_id: " . $e->getMessage(), 'stock_sync_receipt', $shop_id, $product_id, $receipt_id);
                }catch(\Exception | \Error $e) {
                    $logger->log_error("Failed to sync receipt $receipt_id: " . $e->getMessage() . "\n" . 
                        $e->getTraceAsString(), 'stock_sync_receipt', $shop_id, $product_id, $receipt_id);
                    continue;
                }
                
            }
            $offset += \count($receipts);
            $logger->log_general("Synced " . \count($receipts) . " Etsy receipts", EtsyProductStockSyncer::LOG_TYPE);
        }while(\count($recipts) == $limit);
    }

    public static function sync_etsy_receipt($receipt, $shop_id) {
        $logger = PlatyLogger::get_instance();
        $receipt_id = $receipt['receipt_id'];
        // $log = $logger->get_log("shop_id='$shop_id' AND etsy_id='$receipt_id' AND status=1 AND type='stock_sync_receipt'");
        // if(!empty($log)) {
        //     return;
        // }

        if(!$receipt['is_paid']){
            throw new EtsySyncerException("Recipt $receipt_id  is not paid");
        }
        
        $data_service = EtsyDataService::get_instance();
        $transactions = $receipt['transactions'];
        $synced_transactions = 0;
        foreach($transactions as $transaction) {
            $listing_id = $transaction['listing_id'];
            $transaction_id = $transaction['transaction_id'];
            $product_id = 0;
            try {
                $product_id = $data_service->get_log_product_id($listing_id,$shop_id);
                $etsy_product = new EtsyProduct($product_id, $listing_id, $shop_id);
                $stock_syncer = new EtsyProductStockSyncer($etsy_product);
                $stock_syncer->sync_etsy_transaction($transaction, $shop_id);
                $synced_transactions += 1;
            }catch(EtsySyncerException $e) {
                $logger->log_error("Failed to sync transaction $transaction_id: " . $e->getMessage(), 'stock_sync_transaction', 
                    $shop_id, $product_id, $transaction_id);
            }      
            
        }

        if($synced_transactions > 0) {
            $logger->log_success("Synced order id $receipt_id", 'stock_sync_receipt', $shop_id, null, $receipt_id);
        }
    }

    public static function sync_stock_by_orders($shop_id) {
        $logger = PlatyLogger::get_instance();
        $logger->log_general("Starting stock sync", EtsyProductStockSyncer::LOG_TYPE);

        $data_service = EtsyDataService::get_instance();
        $time = time();
        $last_sync_time = $data_service->get_option("last_2w_stock_sync_date", $time, $shop_id) - 24*3600;
        try {
            EtsyStockSyncer::sync_etsy_orders($shop_id, $time - $last_sync_time);
        }catch(\Exception | \Error $e) {
            $logger->log_error("sync failure " . $e->getMessage(), EtsyProductStockSyncer::LOG_TYPE, $shop_id);
        }

        $data_service->save_option("last_2w_stock_sync_date", $time, $shop_id, "stock_management");
    }
    
    public static function sync_stock_from_cron($shop_id) {
        if(!EtsyStockSyncer::is_auto_stock_managed($shop_id)) {
            return;
        }

        try {
            EtsyStockSyncer::sync_stock_by_orders($shop_id);
        }catch(\Exception | \Error $e) {
            $logger = PlatyLogger::get_instance();
            $logger->log_general($e->getMessage(), EtsyProductStockSyncer::LOG_TYPE);
        }
    }
    
    public static function is_auto_stock_managed($shop_id = 0) {
        $data_service = EtsyDataService::get_instance();

        if($data_service->get_option('stock_management', "fixed", $shop_id) != 'shop-stock') {
            return false;
        }

        if($data_service->get_option('2w_stock_sync', "none", $shop_id) == "none") {
            return false;
        }
        
        return true;
    }

    public static function is_cron_status_ok($shop_id) {
        $cron = wp_get_scheduled_event('platy_etsy_stock_cron_hook', [$shop_id]);
        if($cron === false) {
            return false;
        }

        return true;
    }

    public function safeguard_checkout_stock() {
        global $woocommerce;
        add_filter("woocommerce_product_get_stock_quantity", [$this, 'mask_product_stock'], 10 ,2);
        add_filter("woocommerce_product_variation_get_stock_quantity", [$this, 'mask_product_stock'], 10 ,2);

        $items = $woocommerce->cart->get_cart();

        foreach($items as $item => $values) { 
            $product =  wc_get_product( $values['data']->get_id()); 
            if($product->managing_stock() && $values['quantity'] > $product->get_stock_quantity()) {
                $title = $product->get_title();
                throw new EtsySyncerException("Product $title is currently out of stock");
            }
        } 
    }

    public function mask_product_stock_on_view() {
        $etsy_syncer = $this;
        
        add_filter("woocommerce_product_get_stock_quantity", [$etsy_syncer, 'mask_product_stock'], 10 ,2);
        add_action("woocommerce_after_single_product_summary", function() use(&$etsy_syncer){
            $success = remove_filter( "woocommerce_product_get_stock_quantity", [$etsy_syncer, 'mask_product_stock'], 10 );
        });

        add_filter("woocommerce_product_variation_get_stock_quantity", [$etsy_syncer, 'mask_product_stock'], 10 ,2);
        add_action("woocommerce_after_single_product_summary", function() use(&$etsy_syncer){
            $success = remove_filter( "woocommerce_product_variation_get_stock_quantity", [$etsy_syncer, 'mask_product_stock'], 10 );
        });

    }

    public function mask_product_stock($stock_to_mask, $data) {
        $post_id = $data->get_id();
        $var_id = 0;

        if(get_post_type($post_id) == 'product_variation') {
            $var_id = $post_id;
            $post_id = wp_get_post_parent_id($post_id);
        }

        
        try {
            
            $shop_id = $this->data_service->get_current_shop_id();
            $etsy_listing_id = $this->data_service->get_etsy_product_id($post_id, $shop_id);
            $etsy_product = new EtsyProduct($post_id, $etsy_listing_id, $shop_id);
            $stock_syncer = new EtsyProductStockSyncer($etsy_product);

            if(empty($var_id)) {
                return $stock_syncer->mask_stock($stock_to_mask);
            }else {
                return $stock_syncer->mask_variation_stock($stock_to_mask, $var_id);
            }
        }catch(\Exception | \Error $e) {
            
        }
        return $stock_to_mask;
    }

    public function log_order_status_change($order_id, $old_status, $new_status) {
        $logger = PlatyLogger::get_instance();
        $logger->log_general("order id $order_id status change from $old_status to $new_status", EtsyProductStockSyncer::LOG_TYPE);
    }

    public function sync_stock_from_order_status_change($order_id, $old_status, $new_status) {
        $logger = PlatyLogger::get_instance();
        try{
            $order = wc_get_order( $order_id );
            $items = $order->get_items();
            foreach ( $items as $item ) {
                $product_id = $item['product_id'];
                $this->sync_stock($product_id);
            }
    
            $shop_id = $this->data_service->get_current_shop_id();
            $logger->log_success("synced order id $order_id from status change from 
                $old_status to $new_status", EtsyProductStockSyncer::LOG_TYPE, $shop_id, $order_id);
        }catch(NotStockManagedException $e) {
        
        }catch(\Exception | \Error $e) {
            $error = $e->getMessage();
            $logger->log_error("error for order id $order_id from status change from 
                $old_status to $new_status - $error", EtsyProductStockSyncer::LOG_TYPE, $shop_id, $post_id);
        }
 
    }

    public function sync_stock_from_save($post_id) {

        if(get_post_type($post_id) != 'product') {
            return;
        }

        $logger = PlatyLogger::get_instance();

        try {
            $shop_id = $this->data_service->get_current_shop_id();
            $this->sync_stock($post_id);
            $product = wc_get_product($post_id);
            $title = $product->get_title();
            $logger->log_success("synced post id $post_id - '$title' from user update", 
                EtsyProductStockSyncer::LOG_TYPE, $shop_id, $post_id);
        }catch(NotStockManagedException $e) {
        
        }catch(\Exception | \Error $e) {
            $error = $e->getMessage();
            $logger->log_error("error for post $post_id on user update - $error", 
                EtsyProductStockSyncer::LOG_TYPE, $shop_id, $post_id);
        }
    }

    private function sync_stock($post_id) {


        $shop_id = $this->data_service->get_current_shop_id();
        $etsy_listing_id = $this->data_service->get_etsy_product_id($post_id, $shop_id);
        
        if(empty($etsy_listing_id)) {
            return;
        }

        $etsy_product = new EtsyProduct($post_id, $etsy_listing_id, $shop_id);
        $stock_syncer = new EtsyProductStockSyncer($etsy_product);
        $stock_syncer->sync_woo_stock();

    }

    public static function update_stock_sync($settings, $shop_id) {
        if(!EtsyStockSyncer::is_auto_stock_managed($shop_id)) {
            wp_clear_scheduled_hook('platy_etsy_stock_cron_hook', [$shop_id]);
            return;
        }

        $schedule = $settings['2w_stock_sync'];
        $timestamp = wp_next_scheduled( 'platy_etsy_stock_cron_hook', [$shop_id] );
        wp_unschedule_event( $timestamp, 'platy_etsy_stock_cron_hook', [$shop_id] );
        wp_schedule_event( time(), $schedule, "platy_etsy_stock_cron_hook", [$shop_id]);
    }


}