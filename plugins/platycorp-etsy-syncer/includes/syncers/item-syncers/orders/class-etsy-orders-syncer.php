<?php
namespace platy\etsy\orders;
use platy\etsy\EtsySyncerException;
use platy\etsy\NoSuchPostException;
use platy\etsy\NoSuchListingException;
use platy\etsy\EtsySyncer;
use platy\etsy\EmptyVariationException;
use platy\etsy\ProductNotVariableException;
use platy\etsy\EtsyProduct;
use platy\etsy\EtsyDataService;
use platy\etsy\api\OAuthException;
use platy\etsy\logs\PlatySyncerLogger;
use platy\utils\InventoryUtils;
use platy\etsy\logs\PlatyLogger;

class EtsyOrdersSyncer extends EtsySyncer{
    const IS_ETSY_ORDER = "is_platy_etsy_order";
    const IS_ETSY_ORDER_REFUND = "is_platy_etsy_order_refund";
    const LOG_TYPE = "order_sync";
    private $emails_unhooked;


    function __construct($shop_id = 0){
        parent::__construct($shop_id);
        $this->emails_unhooked = false;
    }

    public function update_cron_shcedule($schedule){
        $shop_id = $this->get_shop_id();
        $cleared_num = wp_clear_scheduled_hook(  'platy_etsy_orders_cron_hook', [$shop_id]);
        $success = $schedule == "none";
        if($schedule != "none"){
            $success = wp_schedule_event( time(), $schedule, "platy_etsy_orders_cron_hook", [$shop_id]);
        }
        if(!$success) {
            throw new EtsySyncerException("Timer failure, please try again");
        }

    }

    public static function get_new_orders_to_sync($syncer, $option) {
        $schedules = wp_get_schedules(  );
        $offset = 0;
        $limit = 25;
        $interval = \max(2*$schedules[$option]['interval'], 24*3600);
        $tmp = $syncer->get_etsy_orders_by_date($interval, $limit, $offset);
        $receipts = [];
        while(!empty($tmp)){
            $receipts = array_merge($receipts, $tmp);
            $offset += $limit;
            $tmp = $syncer->get_etsy_orders_by_date($interval, $limit, $offset);
        }
        return $receipts;
    }

    public static function is_auto_orders_managed($shop_id = 0) {
        $data_service = EtsyDataService::get_instance();

        if($data_service->get_option('auto_sync_orders', "none", $shop_id) == "none") {
            return false;
        }
        
        return true;
    }

    public static function is_cron_status_ok($shop_id) {
        $cron = wp_get_scheduled_event('platy_etsy_orders_cron_hook', [$shop_id]);
        if($cron === false) {
            return false;
        }

        return true;
    }

    public static function do_cron_task($shop_id){
        $syncer = new EtsyOrdersSyncer($shop_id);
        $schedules = wp_get_schedules(  );

        $option = $syncer->get_option("auto_sync_orders", "none");
        if($option=="none" || !isset($schedules[$option])){
            return;
        }
        $syncer->debug_logger->log_general("Starting cron task", self::LOG_TYPE);

        try {
            $receipts = EtsyOrdersSyncer::get_new_orders_to_sync($syncer, $option);
        }catch(\Exception | \Error $e) {
            $syncer->debug_logger->log_error("fatal error: " . $e->getMessage() . " " . $e->getTraceAsString(), self::LOG_TYPE);
            return;
        }

        $syncer->debug_logger->log_general("cron task on " . count($receipts) . " receipts with shop_id $shop_id", self::LOG_TYPE);

        foreach($receipts as $receipt){
            try{
                $receipt_id = $receipt['receipt_id'];
                $syncer->sync_order($receipt_id);
            }catch(EtsySyncerException $e){
                $syncer->debug_logger->log_error("Sync error: " . $e->getMessage(), self::LOG_TYPE);
            }catch(\Exception | \Error $e) {
                $syncer->debug_logger->log_error("fatal error $receipt_id: " . $e->getMessage() . " " . $e->getTraceAsString(), self::LOG_TYPE);
            }
        }
    }

    public function get_etsy_orders_by_date($min_created, $limit, $offset,
         $filter_by = "update_timestamp"){
        $data = [];
        $params = [];
        $data['limit'] = (int) $limit;
        $data['offset'] = (int) $offset;
        // $data['min_last_modified'] = $mini_created;
        $params['shop_id'] = $this->get_shop_id();
        $results = $this->api->getShopReceipts(array('params' => $params,
            'data' => $data));
        $results = $results['results'];
        $min_created_offset = $min_created > 0 ? \time() - $min_created : 0;
        $ret = [];
        foreach($results as $receipt){
            if(((int) $receipt[$filter_by]) > $min_created_offset){
                $ret[] = $receipt;
            }
        }
        return $ret;
    }

    private function get_etsy_order_refund($refund_id){
        $query = array(
            'fields' => "ids",
            'post_type' => "shop_order_refund",
            'post_status' => 'any',
            "meta_query" => array(
                'relation' => "AND",
                [
                    'key'     => 'etsy_refund_id',
                    'value'   => $refund_id,
                    'compare' => '='
                ],
                [
                    'key'     => self::IS_ETSY_ORDER_REFUND,
                    'value'   => "true",
                    'compare' => '='
                ]
            )
        );

        if(\Platy_Syncer_Etsy_Order_Admin::legacy_orders_table_enabled()) {
            $orders = (new \WP_Query(
                $query
            ))->posts;
        }else {
            $orders = wc_get_orders(
                $query
            );
        }

        if(!empty($orders)){
            return new \WC_Order_Refund($orders[0]);
        }
        return new \WC_Order_Refund();
    }

    private function get_etsy_order($receipt_id){
        $query = array(
            'fields' => "ids",
            'post_type' => "shop_order",
            'post_status' => 'any',
            "meta_query" => array(
                'relation' => "AND",
                [
                    'key'     => 'etsy_receipt_id',
                    'value'   => $receipt_id,
                    'compare' => '='
                ],
                [
                    'key'     => self::IS_ETSY_ORDER,
                    'value'   => "true",
                    'compare' => '='
                ]
            )
        );
        
        if(\Platy_Syncer_Etsy_Order_Admin::legacy_orders_table_enabled()) {
            $orders = (new \WP_Query(
                $query
            ))->posts;
        }else {
            $orders = wc_get_orders(
                $query
            );
        }

        if(!empty($orders)){
            return new EtsyOrder($orders[0]);
        }
        return new EtsyOrder();
    }

    public function complete_order($receipt_id, $post_id, $tracking_num = "", $carrier = ""){
        $this->maybe_switch_off_emails();
        $shop_id = $this->get_shop_id();
        try{
            $this->api->createReceiptShipment([
                'params' => [
                    'shop_id' => $shop_id,
                    'receipt_id' => $receipt_id
                ],
                'data' => [
                    'tracking_code' => $tracking_num,
                    'carrier_name' => $carrier
                ]
            ]);
        
        }catch(OAuthException $e){
            if($e->get_status_code() == 403){
                throw new EtsySyncerException("Failure. You may need to reauthenticate your shop for this to work (no data will be lost)");
            }
            throw $e;
        }

        $this->sync_order($receipt_id);

    }

    private function calc_price($price_unit) {
        return InventoryUtils::calc_price($price_unit);
    }

    private function sync_address($order, $receipt){
        $country = $receipt['country_iso'];
        $address = [];
        $address['first_name'] = $receipt['name'];
        $address['email'] = $receipt['buyer_email'];
        $address['address_1'] = $receipt['first_line'];
        $address['address_2'] = $receipt['second_line'];
        $address['country']  = $country;
        $address['state']    = $receipt['state'];
        $address['postcode'] = $receipt['zip'];
        $address['city']     = $receipt['city'];

        $order->set_address($address, "billing");
        $order->set_address($address, "shipping");
    }
    
    private function get_order_item($order, $transaction_id){
        try{
            $item = $order->get_etsy_order_item($transaction_id);
        }catch(NoSuchEtsyItemException $e){
            $item = new EtsyOrderItem(new \WC_Order_Item_Product(0), $transaction_id);        
        }
        if(empty($item)){
            throw new EtsySyncerException("Empty order item");
        }
        return $item;
    }

    private function sync_item_image($item, $transaction, $shop_id){
        if(!empty($transaction['listing_image_id'])){
            $image = $this->api->getListingImage(array('params' => 
                array('listing_id' => $transaction['listing_id'],  'shop_id' => $shop_id,
                    'listing_image_id' => $transaction['listing_image_id'])));
            $image_src = $image['url_75x75'];
            $item->set_etsy_property("_etsy_image_src_75x75", esc_url($image_src));
        }
    }

    private function get_etsy_sku($transaction, $shop_id){
        $listing_id = $transaction['listing_id'];
        $etsy_product_id = $transaction['product_id'];

        try {
            $etsy_product = $this->api->getListingProduct(array('params' => 
                array('listing_id' => $listing_id, "product_id" => $etsy_product_id)));
            
        }catch(EtsySyncerException $e) {
            throw new NoSuchPostException($etsy_product_id);
        }
        
        if(empty($etsy_product['sku'])) {
            throw new NoSuchPostException($etsy_product_id);
        }

        return $etsy_product['sku'];
    }

    private function sync_order_item($item, $transaction, $shop_id){
        $quantity = $transaction['quantity'];
        $item->set_total($this->calc_price($transaction['price']) * $quantity);

        $item->set_quantity($quantity);
        $item->add_meta_data( '_reduced_stock', $quantity, true );
        $item->set_name($transaction['title']);
        foreach($transaction['variations'] as $prop){
            $item->set_etsy_property("_prop_" . $prop['formatted_name'], $prop['formatted_value']);

            // this will cause this meta value to appear in invoices and such.
            $item->set_etsy_property($prop['formatted_name'], $prop['formatted_value']);
        }
        $item->set_etsy_property("_etsy_shipping_method", @$transaction['shipping_method']);
        $item->set_etsy_property("_etsy_shipping_upgrade", @$transaction['shipping_upgrade']);
        $item->set_etsy_property("_etsy_listing_id", $transaction['listing_id']);
        $item->set_etsy_property("_etsy_product_id", $transaction['product_id']);
        $item->set_etsy_property("_etsy_shop_id", $shop_id);

        try {
            $sku = $this->get_etsy_sku($transaction, $shop_id);
            $item->set_etsy_property("_etsy_sku", $sku);

        }catch(NoSuchPostException $e) {

        }
        
        try {
            $this->sync_item_image($item, $transaction, $shop_id);
        }catch(EtsySyncerException $e) {
            $message = $e->getMessage();
            $this->debug_logger->log_error("Failed syncing transaction image: $message"
                            , self::LOG_TYPE);   
        }

        $woo_id = $this->get_product_id($transaction['listing_id'], $transaction['product_id'],
             $shop_id, isset($sku) ? $sku :  "");
        $item->set_product_id($woo_id);
        try {
            $var_id = $this->get_variation_id($transaction['listing_id'], $transaction['product_id'],
                $shop_id, isset($sku) ? $sku :  "", $transaction['variations']);
            $item->set_variation_id($var_id);
        }catch(ProductNotVariableException $e) {

        }

        $item->add_etsy_meta_data();

    }

    private function get_order_tax_item($order, $tax_name){
        try{
            $tax_item = $order->get_etsy_order_tax_item($tax_name);
        }catch(NoSuchEtsyItemException $e){
            $tax_item = new \WC_Order_Item_Tax();
            $tax_item->set_name($tax_name);
            $order->add_item($tax_item);
        }
        if(empty($tax_item)){
            throw new EtsySyncerException("Empty order tax item");
        }
        return $tax_item;
    }

    private function sync_tax_item($tax_item, $receipt, $receipt_key = 'total_tax_cost'){
        $tax_item->set_tax_total($this->calc_price($receipt[$receipt_key]));
    }

    private function sync_etsy_discount_item($order, $discount){
        try{
            $discount_item = $order->get_etsy_discount_item();
        }catch(NoSuchEtsyItemException $e){
            $discount_item = new \WC_Order_Item_Coupon();
            $discount_item->set_discount($discount);
            $order->add_item($discount_item);
        }
        if(empty($discount_item)){
            throw new EtsySyncerException("Empty order discount item");
        }
        return $discount_item;
    }

    private function get_order_shipping_item($order, $receipt_shipping_id){
        try{
            $shipping_item = $order->get_etsy_order_shipping_item($receipt_shipping_id);
        }catch(NoSuchEtsyItemException $e){
            $shipping_item = new EtsyOrderShippingItem(0, $receipt_shipping_id);
            $order->add_item($shipping_item);
        }
        if(empty($shipping_item)){
            throw new EtsySyncerException("Empty order shipping item");
        }
        return $shipping_item;
    }

    private function sync_shipping_item($shipping_item, $shipment, $total_cost){
        $shipping_item->set_total($this->calc_price($total_cost));
        if(is_array($shipment) && !empty($shipment['carrier_name'])){
            $name = $shipment['carrier_name'];
            $name .= empty($shipment['tracking_code']) ? "" : " " . $shipment['tracking_code'];
            $shipping_item->set_name($name);
        }else {
            if(is_string($shipment)) {
                $shipping_item->set_name($shipment);
            }
        }
    }

    private function sync_order_status($order, $status){
        $this->maybe_switch_off_emails();
        $order->set_status(apply_filters('platy_etsy_order_status', $status));
    }

    static function unhook_those_pesky_emails( $callback, $email_class ) {
        

    }

    private function maybe_switch_off_emails(){
        if($this->emails_unhooked){
            return;
        }
        $no_emails = $this->get_option("no_order_emails", true);
        if($no_emails){
            $this->emails_unhooked = true;
            add_filter( 'woocommerce_mail_callback',function($callback, $email_class){
                return "platy\\etsy\\orders\\EtsyOrdersSyncer::unhook_those_pesky_emails";
            }, 30 , 2 );
        }
    }

    public function sync_order($receipt_id){
        $this->debug_logger->log_general("Trying to sync order $receipt_id", self::LOG_TYPE);

        $order = $this->get_etsy_order($receipt_id);

        if(empty($order->get_id())) {
            $this->debug_logger->log_general("receipt $receipt_id is a new order", self::LOG_TYPE);
        }
        
        $this->maybe_switch_off_emails();
        try{
            $shop_id = $this->get_shop_id();
            $order_id = $this->sync_order_by_receipt($order, $receipt_id, $shop_id);
            $this->sync_order_refund($order, $receipt_id, $shop_id);
            $this->item_logger->log_success($order_id, $receipt_id, $shop_id, 'order');
            $this->debug_logger->log_success("Synced order $receipt_id,", self::LOG_TYPE);
            return $order_id;
        }catch(EtsySyncerException $e){
            $message = $e->getMessage();
            $this->debug_logger->log_error("error syncing order $receipt_id: $message", self::LOG_TYPE);

        }

        $order_id = $order->get_id();
        if(!empty($order_id)){
            $this->item_logger->log_error($order_id,$message,$receipt_id,$this->get_shop_id(),'order');
        }
        throw new EtsySyncerException($message);
    }

    protected function get_remote_receipt($receipt_id, $shop_id) {
        return $this->api->getShopReceipt(array('params' => 
            array('receipt_id' => $receipt_id, "shop_id" => $shop_id)));
    }

    private function sync_order_by_receipt($order, $receipt_id, $shop_id){
        if(empty($receipt_id)){
            throw new EtsySyncerException("receipt id empty");
        }
        $receipt_json = $this->get_remote_receipt($receipt_id, $shop_id);
        $receipt = $receipt_json;
        $transactions_json = $receipt['transactions'];
        $this->sync_address($order, $receipt);

        foreach($transactions_json as $transaction){
            $item = $this->get_order_item($order, $transaction['transaction_id']);
            $this->sync_order_item($item, $transaction, $shop_id);
            $order->add_item($item->get_woo_item());

        }

        if(!empty($receipt['total_shipping_cost'])){
            $order->set_shipping_total($this->calc_price($receipt['total_shipping_cost']));
        }

        foreach ($transactions_json as $transaction) {
            $method = $transaction['shipping_method'];
            $shipping_method = $method ? $method : "";
            $shipping_upgrade = $transaction['shipping_upgrade'];
            $shipping_upgrade = $shipping_upgrade ? $shipping_upgrade : "";
            $shipping_method .= " $shipping_upgrade";
            if($shipping_method) {
                $shipping_item = $this->get_order_shipping_item($order, $transaction['shipping_profile_id']);
                $this->sync_shipping_item($shipping_item, $shipping_method, $receipt['total_shipping_cost']);
            }
        }

        if(!empty($receipt['shipments'])){
            foreach($receipt['shipments'] as $shipment){
                $shipping_item = $this->get_order_shipping_item($order, $shipment['receipt_shipping_id']);
                $this->sync_shipping_item($shipping_item, $shipment, $receipt['total_shipping_cost']);
            }
        }

        if(!empty($receipt['total_tax_cost'])){
            $tax_item = $this->get_order_tax_item($order, "total_tax");
            $tax_item->set_label("Total Tax");
            $this->sync_tax_item($tax_item, $receipt);
        }

        if(!empty($receipt['total_vat_cost'])){
            $tax_item = $this->get_order_tax_item($order, "vat");
            $tax_item->set_label("VAT");            
            $tax_item->set_rate_code("vat");
            $this->sync_tax_item($tax_item, $receipt, 'total_vat_cost');
        }

        if(!empty($receipt['discount_amt'])){
            $this->sync_etsy_discount_item($order, 
                $this->calc_price($receipt['discount_amt']));
            $order->set_discount_total($this->calc_price($receipt['discount_amt']));
        }

        $order->set_currency($receipt['grandtotal']['currency_code']);
        $order->set_total($this->calc_price($receipt['grandtotal']));
        // $order->set_shipping_total($receipt['total_shipping_cost']);
        $order->set_date_created($receipt['create_timestamp']);
        if($receipt['is_paid']){
            $order->set_date_paid($receipt['create_timestamp']);
            $payment_method = $receipt['payment_method'];
            $order->set_payment_method_title($payment_method . ' via Etsy');
            $order->set_payment_method($payment_method);
            $order->set_transaction_id($receipt_id);
            $this->sync_order_status($order, 'wc-processing');
        }
        if($receipt['is_shipped']){
            $this->sync_order_status($order, 'wc-completed');
        }
        
        try {
            $error_msage = "unknown issue";
            $order->update_meta_data( "etsy_receipt_id", $receipt['receipt_id'] );
            $order->update_meta_data( self::IS_ETSY_ORDER, "true" );
            $order->update_meta_data( 'etsy_shop_id', $this->shop_id );
            $order->save();
        }catch(\Exception $e) {
            $error_msage = $e->getMessage();
        }catch(\Error $e) {
            $error_msage = $e->getMessage();
        }
        
        $order_id = $order->get_id();
        if(empty($order_id)) {
            throw new EtsySyncerException("could not save order $error_msage");
        }
        return $order_id;
    }

    private function get_variation_id($etsy_listing_id, $etsy_product_id, $shop_id, $sku, $etsy_variations) {
        $pid = empty($sku) ? 0 : wc_get_product_id_by_sku($sku);
        if(get_post_type( $pid ) == "product_variation") {
            return $pid;
        }

        $data_service = EtsyDataService::get_instance();
        try {
            return $data_service->get_log_product_id($etsy_product_id, $shop_id, 'variation'); 
        }catch(EtsySyncerException $e) {

        }

        $parent_id = $this->get_product_id($etsy_listing_id, $etsy_product_id, $shop_id, $sku);

        if($parent_id == 0) {
            return 0;
        }

        $etsy_product = new EtsyProduct($parent_id, $etsy_listing_id, $shop_id);

        if($etsy_product->get_product_type() != "variable") {
            throw new ProductNotVariableException();
        }
        $variation_products = $etsy_product->get_variations();
        foreach($variation_products as $variation) {
            
            $match = true;
            foreach($etsy_variations as $etsy_var) {
                try {
                    $attr = $variation->get_attribute($etsy_var['formatted_name']);
                    if(empty($attr) || $attr[0] == $etsy_var['formatted_value']) {
                        continue;
                    }
                }catch(EmptyVariationException $e) {

                }
                $match = false;
                break;
            }

            if($match) {
                return $variation->get_item_id();
            }
        }
        
        return 0;
    }

    private function get_product_id($etsy_listing_id, $etsy_product_id, $shop_id, $sku) {

        $pid = empty($sku) ? 0 : wc_get_product_id_by_sku($sku);
        if(get_post_type( $pid ) == "product") {
            return $pid;
        }

        $data_service = EtsyDataService::get_instance();

        try {
            return $data_service->get_log_product_id($etsy_listing_id, $shop_id);
        }catch(EtsySyncerException $e) {
           
        }

        try {
            $var_id = $data_service->get_log_product_id($etsy_product_id, $shop_id, 'variation');
            return wp_get_post_parent_id($var_id);
        }catch(EtsySyncerException $e) {

        }

        return 0;
    }

    protected function sync_order_refund($order, $receipt_id, $shop_id){
        $payments = $this->api->getShopPaymentByReceiptId( [
            "params" => [
                'receipt_id' => $receipt_id,
                'shop_id' => $shop_id
            ]
        ]);

        if (empty($payments["results"])) {
            return;
        }

        $payment = $payments['results'][0];
        $adjustments = $payment['payment_adjustments'];

        foreach($adjustments as $adjustment) {
            if(empty($adjustment['is_success'])) {
                continue;
            }
            $adjustment_id = $adjustment['payment_adjustment_id'];
            
            $args = [
                'order_id' => $order->get_id(),
                'reason' => $adjustment['reason_code'],
                'amount' => $adjustment['total_adjustment_amount'] / 100,
                'date_created' => $adjustment['create_timestamp']
            ];

            $refund = $this->get_etsy_order_refund($adjustment_id);
            $refund->set_currency( $order->get_currency() );
            $refund->set_amount( $args['amount'] );
            $refund->set_parent_id( absint( $args['order_id'] ) );
            $refund->set_refunded_by( get_current_user_id() ? get_current_user_id() : 1 );
            $refund->set_prices_include_tax( $order->get_prices_include_tax() );
            if ( ! is_null( $args['reason'] ) ) {   
                $refund->set_reason( $args['reason'] );
            }
            $refund->update_taxes();
            $refund->calculate_totals( false );
            $refund->set_total( $args['amount'] * -1 );

            // foreach($order->get_items() as $item_id => $item_obj) {
            //     $refund->add_item($item_obj);
            // }
    
            // this should remain after update_taxes(), as this will save the order, and write the current date to the db
            // so we must wait until the order is persisted to set the date.
            if ( isset( $args['date_created'] ) ) {
                $refund->set_date_created( $args['date_created'] );
            }
            $refund->update_meta_data( "etsy_refund_id",$adjustment_id );
            $refund->update_meta_data( self::IS_ETSY_ORDER_REFUND, "true" );
            $refund->save();
            $remaining_refund_amount = $order->get_remaining_refund_amount();
            if($remaining_refund_amount <= 0) {
                $order->update_status( 'refunded' );
            }

        }

        
    }

    static function is_post_etsy_order($post_id){
        $order = wc_get_order($post_id);
        return $order && $order->get_meta( EtsyOrdersSyncer::IS_ETSY_ORDER, true ) == "true";
    }

    static function get_etsy_order_receipt_id($post_id){
        $order = wc_get_order($post_id);
        return $order && $order->get_meta( "etsy_receipt_id", true );
    }
}