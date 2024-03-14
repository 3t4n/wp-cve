<?php

/**
 * This class handles syncing purchases with Zettle
 *
 * @package   WooCommerce_iZettle_Integration
 * @author    BjornTech <info@bjorntech.com>
 * @license   GPL-3.0
 * @link      http://bjorntech.com
 * @copyright 2017-2020 BjornTech - BjornTech AB
 */

defined('ABSPATH') || exit;

if (!class_exists('WC_iZettle_Integration_Purchase_Handler', false)) {

    class WC_iZettle_Integration_Purchase_Handler
    {

        static $no_stock_change = false;
        static $order_id = false;

        public function __construct()
        {

            if ('yes' === get_option('zettle_enable_purchase_processing')) {

                // Include admin functions needed to create a WooCommerce order like the admin gui is doing.
                require_once WC_ABSPATH . 'includes/admin/wc-admin-functions.php';

                add_action('izettle_add_purchase_to_queue', array($this, 'add_purchase_to_queue'), 10, 2);
                add_action('izettle_process_incoming_purchase', array($this, 'process_incoming_purchase'));

                // Poll Zettle for changes if the sync model exists. Do not poll if the sync model is realtime and webhooks are present
                add_action('init', array($this, 'schedule_sync_izettle_purchases'));

                // Action to perform when pressing the button in the settings page
                add_filter('wciz_sync_iz_purchases_filter', array($this, 'sync_iz_purchases_filter'), 50, 2);
                add_action('wciz_sync_iz_purchases_action', array($this, 'sync_iz_purchases_action'), 50, 1);
                add_action('wciz_sync_iz_purchases_action_daily', array($this, 'sync_iz_purchases_action'), 50, 1);

                // Alternatives for processing, selected by alternatives in batch processing
                add_action('izettle_process_fortnox', array($this, 'process_purchase_fortnox'), 50, 1);
                add_action('izettle_process_wc_order', array($this, 'process_purchase_wc_order'), 50, 1);
                add_action('izettle_process_wc_order_update', array($this, 'process_purchase_wc_order_update'), 50, 1);
                add_action('izettle_process_wc_stockchange', array($this, 'process_purchase_wc_stockchange'), 50, 1);
                add_action('izettle_process_wc_stockchange_reverse', array($this, 'process_purchase_wc_stockchange_reverse'), 50, 1);
                add_action('izettle_process_remove_processed', array($this, 'remove_processed'), 50, 1);

                add_filter('woocommerce_payment_complete_reduce_order_stock', array($this, 'payment_complete_reduce_order_stock'), 10, 2);

                if ('yes' == (get_option('zettle_process_purchase_order_no_reduce_stock'))){
                    add_filter('woocommerce_prevent_adjust_line_item_product_stock', array($this, 'adjust_line_item_product_stock'), 10, 3);
                }

                if ('yes' == (get_option('zettle_no_new_order_email'))){
                    add_filter('woocommerce_email_recipient_new_order', array($this, 'filter_new_order_email'),999,3);
                }
            }

        }

        public function schedule_sync_izettle_purchases()
        {

            $sync_model = get_option('izettle_purchase_sync_model');

            if ($sync_model && (1 != $sync_model) && (1440 != $sync_model)) {

                $shcedule_group = 'wciz_sync_iz_purchases_action_interval_' . $sync_model;

                $actions = as_get_scheduled_actions(
                    array(
                        'hook' => 'wciz_sync_iz_purchases_action',
                        'status' => ActionScheduler_Store::STATUS_PENDING,
                        'claimed' => false,
                        'per_page' => -1,
                        'group' => $shcedule_group,
                    ),
                    'ids'
                );

                if (count($actions) > 1) {
                    WC_IZ()->logger->add(sprintf('schedule_sync_izettle_purchases - Found more than one recurring purchase sync - unscheduling all recurring purchase syncs'));

                    try {
                        as_unschedule_action('wciz_sync_iz_purchases_action');
                    } catch (\Throwable$throwable) {
                        WC_IZ()->logger->add(sprintf('schedule_sync_izettle_purchases - No process to unschedule'));
                    }
                }

                if ((1440 != $sync_model) && false === as_has_scheduled_action('wciz_sync_iz_purchases_action')) {

                    WC_IZ()->logger->add(sprintf('schedule_sync_izettle_purchases - Creating recurring schedule for purchase syncs'));
                    as_schedule_recurring_action(time(), $sync_model * MINUTE_IN_SECONDS, 'wciz_sync_iz_purchases_action', array(), $shcedule_group);

                }

            } else {
                if (false !== as_has_scheduled_action('wciz_sync_iz_purchases_action')) {

                    try {
                        WC_IZ()->logger->add(sprintf('schedule_sync_izettle_purchases - Unscheduling all recurring syncs'));
                        as_unschedule_all_actions('wciz_sync_iz_purchases_action');
                    } catch (\Throwable$throwable) {
                        WC_IZ()->logger->add(sprintf('schedule_heartbeat_sync - No process to unschedule'));
                    }
                }
            } 
            
            if (($sync_model && ((1 == $sync_model) || (1440 == $sync_model)))) {

                $actions = as_get_scheduled_actions(
                    array(
                        'hook' => 'wciz_sync_iz_purchases_action_daily',
                        'status' => ActionScheduler_Store::STATUS_PENDING,
                        'claimed' => false,
                        'per_page' => -1                    ),
                    'ids'
                );

                if (count($actions) > 1) {

                    WC_IZ()->logger->add(sprintf('schedule_sync_izettle_purchases - Found more than one daily purchase sync - unscheduling all recurring purchase syncs'));

                    try {
                        as_unschedule_all_actions('wciz_sync_iz_purchases_action_daily');
                    } catch (\Throwable$throwable) {
                        WC_IZ()->logger->add(sprintf('schedule_sync_izettle_purchases - No process to unschedule'));
                    }
                }

                if (false === as_has_scheduled_action('wciz_sync_iz_purchases_action_daily')) {
                    WC_IZ()->logger->add(sprintf('schedule_sync_izettle_purchases - Scheduling daily purchase sync'));
                    as_schedule_cron_action(strtotime('tomorrow 7 am'), '0 7 * * *', 'wciz_sync_iz_purchases_action_daily');
                }

            } else {

                if (false !== as_has_scheduled_action('wciz_sync_iz_purchases_action_daily')) {
                    try {
                        WC_IZ()->logger->add(sprintf('schedule_sync_izettle_purchases - Unscheduling all daily syncs'));
                        as_unschedule_all_actions('wciz_sync_iz_purchases_action_daily');
                    } catch (\Throwable$throwable) {
                        WC_IZ()->logger->add(sprintf('schedule_sync_izettle_purchases - No process to unschedule'));
                    }
                }

            }

        }

        /**
         * Process an Zettle purchase, adding it as post and applying the configured action on it.
         *
         */
        public function process_incoming_purchase($purchase_uuid)
        {
            try {
                if (!is_object($purchase_uuid) && 36 == strlen($purchase_uuid)) {
                    if ($post_id = apply_filters('izettle_insert_post', $purchase_uuid)) {
                        $purchase_action = get_option('izettle_purchase_sync_function');
                        if ($purchase_action) {
                            WC_IZ()->logger->add(sprintf("process_incoming_purchase: Trigger '%s' for purchase UUID %s in post %s", $purchase_action, $purchase_uuid, $post_id));
                            do_action('izettle_process_' . $purchase_action, $post_id);
                        }
                    }
                }
            } catch (IZ_Integration_API_Exception $e) {
                $e->write_to_logs();
            } catch (\Throwable$throwable) {
                WC_IZ()->logger->add(print_r($throwable, true));
            }
        }

        public function type_text($sync_all)
        {
            return $sync_all ? 'Manual' : 'Automatic';
        }

        public function sync_iz_purchases_filter($number_of_synced, $sync_all = false)
        {
            return $this->sync_iz_purchases_action($sync_all);
        }

        public function sync_iz_purchases_action($sync_all = false)
        {

            if (apply_filters('izettle_is_client_allowed_to_sync', false, $sync_all)) {

                $last_purchase_hash = get_option('izettle_last_purchase_hash', false);

                try {
                    $this_sync_time = gmdate('U');
                    $last_sync_done = get_option('izettle_last_purchases_sync_done');

                    if ($sync_all && ($startdate = get_option('izettle_purchase_startdate'))) {
                        $params = array(
                            'startDate' => $startdate,
                        );
                        WC_IZ()->logger->add(sprintf('Manual sync of purchases with startdate %s from Zettle to WooCommerce requested', $startdate));
                    } elseif ($last_purchase_hash) {
                        $params = array(
                            'lastPurchaseHash' => $last_purchase_hash,
                        );
                        WC_IZ()->logger->add(sprintf('%s sync of purchases using %s as last purchase hash', $this->type_text($sync_all), $last_purchase_hash));
                    } else {
                        $sync_from = date('c', $this_sync_time - ($sync_all ? WEEK_IN_SECONDS : DAY_IN_SECONDS));
                        $params = array(
                            'startDate' => $sync_from,
                        );
                        WC_IZ()->logger->add(sprintf('%s sync of purchases starting from %s', $this->type_text($sync_all), $sync_from));
                    }

                    $purchases_array = izettle_api()->get_purchases($params);

                    update_option('izettle_last_purchases_sync_done', $this_sync_time);

                    if (!empty($purchases_array->purchases)) {

                        WC_IZ()->logger->add(sprintf('Adding %d purchases to queue for processing in WooCommerce', count($purchases_array->purchases)));

                        foreach ($purchases_array->purchases as $purchase) {
                            do_action('izettle_add_purchase_to_queue', $purchase->purchaseUUID1, false);
                        }

                        if ($purchases_array->lastPurchaseHash) {
                            update_option('izettle_last_purchase_hash', $purchases_array->lastPurchaseHash);
                        }

                    }

                    if (!empty($purchases_array->purchases)) {
                        return count($purchases_array->purchases);
                    }

                } catch (IZ_Integration_API_Exception $e) {
                    $e->write_to_logs();
                }

            }

            return 0;

        }

        public function add_purchase_to_queue($purchase_uuid, $webhook = false)
        {

            if (false === $webhook || 'yes' == get_option('izettle_queue_webhook_calls')) {
                as_schedule_single_action(as_get_datetime_object(), 'izettle_process_incoming_purchase', array($purchase_uuid), 'izettle-purchases');
                WC_IZ()->logger->add(sprintf('add_purchase_to_queue: Added purchase %s to queue', $purchase_uuid));
            } else {
                do_action('izettle_process_incoming_purchase', $purchase_uuid);
            }

        }

        public function document_changes($post_id, $purchase_id, $by_module, $comment)
        {
            add_post_meta($post_id, '_processing_changes', sprintf('%s %s - %s', date('Y-m-d H:i'), $by_module, $comment));
            WC_IZ()->logger->add(sprintf('Zettle purchase id %s processed by %s - %s', $purchase_id, $by_module, $comment));
        }

        public function remove_processed($post_id)
        {
            $purchase = json_decode(get_post($post_id)->post_content);
            delete_post_meta($post_id, '_processed_timestamp');
            update_post_meta($post_id, '_processed_with', 'remove_processed');
            $this->document_changes($post_id, $purchase->purchaseNumber, 'remove_processed', __('Processing information removed', 'woo-izettle-integration'));
        }

        public function process_purchase_fortnox($post_id)
        {
            $purchase = json_decode(get_post($post_id)->post_content);
            if (!($processed_timestamp = get_post_meta($post_id, '_processed_timestamp', true))) {
                $purchase = json_decode(get_post($post_id)->post_content);
                $all_ok = true;
                $one_ok = false;
                foreach ($purchase->products as $product) {
                    $detailed_product = izettle_api()->get_products($product->productUuid);
                    foreach ($detailed_product->variants as $variant) {
                        if ($variant->uuid == $product->variantUuid) {
                            if (isset($variant->sku) && ($response = $this->change_stock_level_fortnox(false, $variant->sku, $product->quantity, isset($purchase->refund) ? $purchase->refund : false))) {
                                $this->document_changes($post_id, $purchase->purchaseNumber, 'fortnox', $response);
                                $one_ok = true;
                            } else {
                                $this->document_changes($post_id, $purchase->purchaseNumber, 'fortnox', sprintf(__('Stocklevel change for %s %s failed', 'woo-izettle-integration'), $product->productUuid, $product->variantUuid));
                                $all_ok = false;
                            }
                            break;
                        }
                    }
                }

                update_post_meta($post_id, '_processed_timestamp', current_time('timestamp'));
                if (!$all_ok) {
                    if ($one_ok) {
                        update_post_meta($post_id, '_processed_with', 'partial_error');
                    } else {
                        update_post_meta($post_id, '_processed_with', 'error');
                    }
                } else {
                    update_post_meta($post_id, '_processed_with', 'fortnox');
                }

            } else {

                $processed_with = get_post_meta($post_id, '_processed_with', true);
                WC_IZ()->logger->add(sprintf('Trying to change Zettle purchase %s, but it has already been processed at %s by %s', $purchase->purchaseNumber, $processed_timestamp, $processed_with), true);

            }
        }

        public function change_stock_level_fortnox($response, $sku, $quantity, $refund = false)
        {

            if ($article = apply_filters('fortnox_get_article', false, $sku)) {

                if (rest_sanitize_boolean($article['StockGoods'])) {

                    $stock_quantity = $article['QuantityInStock'];

                    $qty = $refund ? $stock_quantity + $quantity : $stock_quantity - $quantity;

                    if (apply_filters('fortnox_update_article', false, $sku, array("QuantityInStock" => $qty))) {

                        WC_IZ()->logger->add(sprintf('change_stock_level_fortnox: Sucessful stockchange for %s from %s to %s', $sku, $stock_quantity, $qty));
                        $response = sprintf(__('Successfully changed stock for Fortnox Article %s from %s to %s', 'woo-izettle-integration'), $sku, $stock_quantity, $qty);

                    } else {

                        WC_IZ()->logger->add(sprintf('Failed to update Article %s in Fortnox, see Fortnox log', $sku));

                    }

                } else {

                    WC_IZ()->logger->add(sprintf('change_stock_level_fortnox: Product %s is not stock goods', $sku));

                }

            } else {

                WC_IZ()->logger->add(sprintf('change_stock_level_fortnox: %s not updated in Fortnox', print_r($article . true)));

            }

            return $response;

        }

        public function process_purchase_wc_stockchange_reverse($post_id)
        {
            $this->process_purchase_wc_stockchange($post_id, true);
        }

        public function process_purchase_wc_stockchange($post_id, $reverse = false)
        {

            $purchase = json_decode(get_post($post_id)->post_content);

            if (!($processed_timestamp = get_post_meta($post_id, '_processed_timestamp', true))) {

                $all_ok = true;
                $one_ok = false;

                foreach ($purchase->products as $purchase_product) {

                    try {

                        if (true == $purchase_product->libraryProduct) {

                            $product = WC_Zettle_Helper::get_wc_product_by_uuid($purchase_product->productUuid, $purchase_product->variantUuid);

                            if (!$product && isset($purchase_product->sku)) {

                                $product = WC_Zettle_Helper::get_wc_product_by_sku($purchase_product->sku);

                            }

                            if ($product) {

                                $product_id = $product->get_id();
                                $product_name = $product->get_name('edit');
                                $manage_stock = $product->get_manage_stock('view');

                                if ($manage_stock) {

                                    $current_stock = $product->get_stock_quantity('view');

                                    if ('parent' === $manage_stock && ($parent_id = $product->get_parent_id())) {
                                        $product_id = $parent_id;
                                        WC_IZ()->logger->add(sprintf('process_purchase_wc_stockchange (%s): Parent %s sets stock', $product->get_id(), $product_id));
                                        $product = wc_get_product($product_id);
                                    }

                                    if (wc_string_to_bool(get_option('izettle_import_stocklevel'))) {

                                        $transient_name = 'zettle_inventory_balance_changed_' . $purchase->timestamp;
                                        if ($inventory = get_site_transient($transient_name)) {

                                            WC_IZ()->logger->add(sprintf('process_purchase_wc_stockchange (%s): Found transient', $product_id));
                                            $stockchange_located = false;
                                            foreach ($inventory->balanceAfter as $balance_after) {
                                                foreach ($inventory->balanceBefore as $balance_before) {
                                                    if (($balance_before->variantUuid == $balance_after->variantUuid) && ($balance_before->productUuid == $balance_after->productUuid) && ($balance_before->balance != $balance_after->balance)) {

                                                        if ($current_stock != $balance_after->balance) {
                                                            $new_stock = wc_update_product_stock($product, $balance_after->balance, 'set');
                                                            $this->document_changes($post_id, $purchase->purchaseNumber, $reverse ? 'reverse' : 'wc_stockchange', sprintf(__('Stocklevel for %s (%s) set from %s to %s', 'woo-izettle-integration'), $product_name, $product_id, $balance_before->balance, $new_stock));
                                                            $stockchange_located = true;
                                                            WC_IZ()->logger->add(sprintf(__('process_purchase_wc_stockchange (%s): Stocklevel for %s (%s) set from %s to %s', 'woo-izettle-integration'), $product_id, $product_name, $product_id, $balance_before->balance, $new_stock));
                                                        } else {
                                                            WC_IZ()->logger->add(sprintf('process_purchase_wc_stockchange (%s): No need to set stocklevel %d', $product_id, $current_stock));
                                                        }
                                                        
                                                        break;
                                                        
                                                    }
                                                }

                                                if($stockchange_located){
                                                    break;
                                                }

                                            }

                                            delete_site_transient($transient_name);

                                        } else {

                                            $store_inventory = izettle_api()->get_location_content('STORE', $purchase_product->productUuid);

                                            if (in_array($purchase_product->productUuid, $store_inventory->trackedProducts)) {

                                                $iz_stock = WC_iZettle_Stocklevel_Handler::get_variant_stocklevel($store_inventory, $purchase_product->productUuid, $purchase_product->variantUuid);

                                                if ($current_stock != $iz_stock) {
                                                    $new_stock = wc_update_product_stock($product, $iz_stock, 'set');
                                                    $this->document_changes($post_id, $purchase->purchaseNumber, $reverse ? 'reverse' : 'wc_stockchange', sprintf(__('Stocklevel for %s (%s) set from %s to %s', 'woo-izettle-integration'), $product_name, $product_id, $current_stock, $new_stock));
                                                } else {
                                                    WC_IZ()->logger->add(sprintf('process_purchase_wc_stockchange (%s): No need to set stocklevel %d', $product_id, $current_stock));
                                                }

                                            } else {

                                                $new_stock = wc_update_product_stock($product, $purchase_product->quantity, $reverse ? 'increase' : 'decrease');
                                                $this->document_changes($post_id, $purchase->purchaseNumber, $reverse ? 'reverse' : 'wc_stockchange', sprintf(__('Product not tracked, stocklevel for %s (%s) changed from %s to %s', 'woo-izettle-integration'), $product_name, $product_id, $current_stock, $new_stock));

                                            }
                                        }

                                    } else {

                                        $new_stock = wc_update_product_stock($product, $purchase_product->quantity, $reverse ? 'increase' : 'decrease');
                                        $this->document_changes($post_id, $purchase->purchaseNumber, $reverse ? 'reverse' : 'wc_stockchange', sprintf(__('Stocklevel for %s (%s) changed from %s to %s', 'woo-izettle-integration'), $product_name, $product_id, $current_stock, $new_stock));

                                    }

                                } else {

                                    $this->document_changes($post_id, $purchase->purchaseNumber, $reverse ? 'reverse' : 'wc_stockchange', sprintf(__('%s is set to not manage stock', 'woo-izettle-integration'), $product_name));

                                }

                                $one_ok = true;

                            } else {

                                $this->document_changes($post_id, $purchase->purchaseNumber, $reverse ? 'reverse' : 'wc_stockchange', sprintf(__('%s not found', 'woo-izettle-integration'), $purchase_product->productUuid));

                                $all_ok = false;

                            }

                        } else {

                            $this->document_changes($post_id, $purchase->purchaseNumber, $reverse ? 'reverse' : 'wc_stockchange', sprintf(__('Not a library product (%s)', 'woo-izettle-integration'), $purchase_product->name));

                            $one_ok = true;

                        }

                    } catch (IZ_Integration_Exception $e) {

                        $message = $e->getMessage();
                        WC_IZ()->logger->add(sprintf('process_purchase_wc_stockchange: %s when processing stockchange Zettle product %s', $message), $purchase_product->productUuid);

                    }

                }

                if (!$all_ok) {

                    if ($one_ok) {
                        update_post_meta($post_id, '_processed_with', 'partial_error');
                    } else {
                        update_post_meta($post_id, '_processed_with', 'error');
                    }

                } else {

                    update_post_meta($post_id, '_processed_with', $reverse ? 'reverse' : 'wc_stockchange');

                }

                update_post_meta($post_id, '_processed_timestamp', current_time('timestamp'));

            } else {

                $processed_with = get_post_meta($post_id, '_processed_with', true);

                WC_IZ()->logger->add(sprintf('Trying to change Zettle purchase %s, but it has already been processed at %s by %s', $purchase->purchaseNumber, $processed_timestamp, $processed_with), true);

            }

        }

        public function get_product_item_discount($purchase_product)
        {

            if (property_exists($purchase_product, 'discount')) {
                $discount = $purchase_product->discount;
                if (property_exists($discount, 'percentage')) {
                    return (($discount->percentage / 100) * $purchase_product->unitPrice * $purchase_product->quantity);
                } elseif (property_exists($discount, 'amount')) {
                    return $discount->amount;
                }
            }
            return 0;
        }

        public function get_tax_amount($amount, $tax_rates)
        {
            $amount_raw = WC_Tax::calc_tax($amount, $tax_rates, true);
            return intval(reset($amount_raw));
        }

        public function calc_tax_inc_tax($amount_inc_tax, $tax_rate)
        {
            return round($amount_inc_tax * (($tax_rate / 100) / (1 + ($tax_rate / 100))));
        }

        public function calc_tax_ex_tax($amount_ex_tax, $tax_rate)
        {
            return round($amount_ex_tax * ($tax_rate / 100));
        }

        public function process_purchase_wc_order_update($post_id)
        {
            try {

                $processed_timestamp = get_post_meta($post_id, '_processed_timestamp', true);
                $processed_with = get_post_meta($post_id, '_processed_with', true);
                $wc_order_id = get_post_meta($post_id, '_wc_order_id', true);

                $post_id = apply_filters('izettle_update_post', $post_id);

                if ($processed_timestamp && in_array($processed_with, array('wc_order', 'wc_order_update')) && $wc_order_id && $post_id) {
                    $this->process_purchase_wc_order($post_id, $wc_order_id);
                }

            } catch (IZ_Integration_API_Exception $e) {
                $e->write_to_logs();
            } catch (\Throwable$throwable) {
                WC_IZ()->logger->add(print_r($throwable, true));
            }

        }

        public function is_free_amount_with_comment ($purchase) {
            if (count ($purchase->products) != 1) {
                WC_IZ()->logger->add(sprintf('is_free_amount_with_comment: More or less than one product in purchase %s', $purchase->purchaseNumber));
                return false;                
            }

            $purchase_product = $purchase->products[0];

            $purchase_type = $purchase_product->type;

            if ($purchase_type != 'CUSTOM_AMOUNT') {
                WC_IZ()->logger->add(sprintf('is_free_amount_with_comment: Purchase %s is not CUSTOM_AMOUNT - is %s', $purchase->purchaseNumber, $purchase_type));
                return false;
            }

            $product_name = $purchase_product->name;

            if (!$product_name) {
                WC_IZ()->logger->add(sprintf('is_free_amount_with_comment: Purchase %s has no product name', $purchase->purchaseNumber));
                return false;
            }

            return true;
        }

        public function get_product_order_id ($purchase) {
            if (count ($purchase->products) == 1) {
                $product_name = $purchase->products[0]->name;
                WC_IZ()->logger->add(sprintf('get_product_order_id: One product in purchase %s - %s', $purchase->purchaseNumber, $product_name));
                return sanitize_text_field( $product_name );
            }

            WC_IZ()->logger->add(sprintf('get_product_order_id: More or less than one product in purchase %s', $purchase->purchaseNumber));

            return false;
        }

        public function get_comment_order_id($purchase)
        {

            foreach ($purchase->products as $purchase_product) {

                if (isset($purchase_product->comment)) {

                    $comments = explode(',', $purchase_product->comment);

                    foreach ($comments as $comment) {

                        if (false !== strpos($comment, '#')) {
                            $order_id = trim($comment, '# ');
                            if (is_numeric($order_id)) {
                                WC_IZ()->logger->add($order_id);
                                return $order_id;
                            }
                        }

                    }

                }
            }

            return false;

        }

        public function get_comment_customer_id($purchase)
        {

            foreach ($purchase->products as $purchase_product) {

                if (isset($purchase_product->comment)) {

                    $comments = explode(',', $purchase_product->comment);
                    foreach ($comments as $comment) {
                        $customer_id = trim($comment);
                        if (is_numeric($customer_id)) {
                            WC_IZ()->logger->add($customer_id);
                            return $customer_id;
                        }
                    }

                }
            }

            return false;

        }

        /**
         * Process comment
         */

        public function process_comment($order, $purchase_product)
        {

            if (isset($purchase_product->comment)) {

                $comments = explode(',', $purchase_product->comment);

                foreach ($comments as $comment) {

                    WC_IZ()->logger->add($comment);

                    if (strpos($comment, '@')) {
                        $order->set_billing_email(strtolower($comment));
                        continue;
                    }

                    if (is_numeric($comment)) {
                        $order->set_billing_phone($comment);
                        continue;
                    }

                    if (false === strpos($comment, '#')) {
                        $name = explode(' ', $comment);

                        if (count($name) == 1) {
                            $order->set_billing_company(reset($name));
                        } else {
                            $order->set_billing_first_name($name[0]);
                            unset($name[0]);
                            $order->set_billing_last_name(implode(' ', $name));
                        }
                    }

                }

            }

            return $order;

        }

        public function woosb_get_bundled($product, $order, $id, $qty)
        {

            $product_id = $product->get_id();

            $woosb_arr = array();
            $woosb_ids = get_post_meta($product_id, 'woosb_ids', true);

            if (!empty($woosb_ids)) {

                $woosb_items = explode(',', $woosb_ids);

                if (is_array($woosb_items) && count($woosb_items) > 0) {

                    foreach ($woosb_items as $woosb_item) {

                        $woosb_item_arr = explode('/', $woosb_item);
                        $woosb_item_id = absint(isset($woosb_item_arr[0]) ? $woosb_item_arr[0] : 0);
                        $woosb_item_qty = isset($woosb_item_arr[1]) ? $woosb_item_arr[1] : 1;

                        $woosb_item_product = wc_get_product($woosb_item_id);

                        $order_item_id = wc_add_order_item($order->get_id(), array(
                            'order_item_name' => $woosb_item_product->get_name(),
                            'order_item_type' => 'line_item',
                        ));

                        wc_add_order_item_meta($order_item_id, '_qty', $woosb_item_qty * $qty, true);

                        if ($parent_id = $woosb_item_product->get_parent_id()) {
                            wc_add_order_item_meta($order_item_id, '_product_id', $parent_id, true);
                            wc_add_order_item_meta($order_item_id, '_variation_id', $woosb_item_id, true);
                        } else {
                            wc_add_order_item_meta($order_item_id, '_product_id', $woosb_item_id, true);
                        }

                        wc_add_order_item_meta($order_item_id, '_line_subtotal', 0, true);
                        wc_add_order_item_meta($order_item_id, '_line_total', 0, true);
                        wc_add_order_item_meta($order_item_id, 'zettle_item_id', strval($id), true);
                        wc_add_order_item_meta($order_item_id, 'bundle_item_qty', $woosb_item_qty, true);

                        WC_IZ()->logger->add(sprintf('woosb_get_bundled: Adding woosb item %s pcs of product id %s', $woosb_item_qty, $woosb_item_id));

                    }
                }
            }

        }

        public function process_purchase_wc_order($post_id, $original_order_id = false)
        {
            try {

                if ($original_order_id) {
                    self::$no_stock_change = true;
                    WC_IZ()->logger->add(sprintf('process_purchase_wc_order: Processing purchase %s with original order id %s', $post_id, $original_order_id));
                    $process = 'wc_order_update';
                } else {
                    self::$no_stock_change = false;
                    WC_IZ()->logger->add(sprintf('process_purchase_wc_order: Processing purchase %s', $post_id));
                    $process = 'wc_order';
                }

                $post = get_post($post_id);

                $post_content = WC_Zettle_Helper::fix_utf8_string($post->post_content);

                $purchase = json_decode($post_content);

                $customer_id = apply_filters('zettle_order_customer_id', get_option('izettle_order_customer', 0), $purchase);

                WC_IZ()->logger->add(sprintf('process_purchase_wc_order: Customer id "%s" found on purchase after filter', $customer_id));

                if (false === $customer_id) {
                    return;
                }

                if (property_exists($purchase, 'refund') && $purchase->refund == true) {
                    $this->process_purchase_wc_refund($post_id);
                    return;
                }

                if ($original_order_id || !($processed_timestamp = get_post_meta($post_id, '_processed_timestamp', true))) {

                    $process_purchase_order_id = wc_string_to_bool(get_option('zettle_process_purchase_order_id'));
                    $process_purchase_order_id_free_amount = wc_string_to_bool(get_option('zettle_process_purchase_order_id_free_amount'));
                    $process_purchase_customer_id = wc_string_to_bool(get_option('zettle_process_purchase_customer_id'));
                    $process_purchase_comments = wc_string_to_bool(get_option('zettle_process_purchase_comments'));

                    // Create initial order
                    $payment_method = $purchase->payments[0]->type;
                    if (false === strpos(strtoupper($payment_method), 'ZETTLE')) {
                        $payment_method = 'ZETTLE_' . $payment_method;
                    }
                    $payment_method_name = ucfirst(strtolower(str_replace('_', ' ', $payment_method)));
                    $timestamp_created = strtotime($purchase->created);

                    if ($original_order_id) {

                        $order = wc_get_order($original_order_id);
                        $order->remove_order_items();

                    } else {

                        if ($process_purchase_order_id && ($order_id = $this->get_comment_order_id($purchase))) {

                            $order = wc_get_order($order_id);
                            if ($order && 'processing' == $order->get_status()) {
                                $order->remove_order_items();
                            } else {
                                $this->document_changes($post_id, $purchase->purchaseNumber, $process, sprintf(__('Order %s not found - Failed when trying to map purchase comment to order', 'woo-izettle-integration'), $order_id));
                                return;
                            }

                        } elseif ($process_purchase_order_id_free_amount && $this->is_free_amount_with_comment($purchase)) {

                            $order_id = $this->get_product_order_id($purchase);
                            $order = wc_get_order($order_id);

                            if (!$order) {
                                $this->document_changes($post_id, $purchase->purchaseNumber, $process, sprintf(__('Order %s not found - Failed when trying to map purchase product comment to order', 'woo-izettle-integration'), $order_id));
                                return;
                            } 

                            $this->complete_order($order, $purchase, $timestamp_created, $order_id, $post_id, $process, $order_id);
                            return;
                            
                        } else {
                            $order = wc_create_order();
                        }

                    }

                    $order->set_created_via('zettle');
                    $order->set_currency($purchase->currency);
                    $order->set_date_created($timestamp_created);
                    $order->set_payment_method($payment_method);
                    $order->set_payment_method_title(ucfirst($payment_method_name));
                    $order->update_meta_data('_izettle_post_id', $post_id);
                    $order->set_prices_include_tax('yes' === get_option('woocommerce_prices_include_tax'));

                    if (!$process_purchase_order_id) {

                        $order->set_customer_ip_address('127.0.0.1');

                        if ($process_purchase_customer_id) {
                            $customer_id = $this->get_comment_customer_id($purchase);
                            WC_IZ()->logger->add(sprintf('process_purchase_wc_order: Customer id "%s" found on purchase', $customer_id));
                        }

                        if ($customer_id) {
                            WC_IZ()->logger->add(sprintf('process_purchase_wc_order: Customer id "%s" found on purchase - creating new customer', $customer_id));
                            $customer = new WC_Customer($customer_id);
                            $this->set_order_customer_data($order, $customer);
                        }

                        if (!$customer_id) {
                            $billing_company = sprintf(__('%s sale %s', 'woo-izettle-integration'), $payment_method_name, $purchase->purchaseNumber);
                            WC_IZ()->logger->add(sprintf('process_purchase_wc_order: No customer id found on purchase, using %s as billing company', $billing_company));
                            $order->set_billing_company($billing_company);

                            $billing_email = get_option('izettle_order_email', get_option('izettle_username'));
                            WC_IZ()->logger->add(sprintf('process_purchase_wc_order: No customer id found on purchase, using %s as billing email', $billing_email));
                            $order->set_billing_email($billing_email);

                            $billing_country = WC()->countries->get_base_country();
                            WC_IZ()->logger->add(sprintf('process_purchase_wc_order: No customer id found on purchase, using %s as billing country', $billing_country));
                            $order->set_billing_country($billing_country);
                        }

                        $order->set_customer_id($customer_id);

                    }

                    $order_id = $order->save();
                    self::$order_id = $order_id;

                    //WC_IZ()->logger->add(sprintf('process_purchase_wc_order: got purchase %s', json_encode($purchase, JSON_INVALID_UTF8_IGNORE)));
                    WC_IZ()->logger->add(sprintf('process_purchase_wc_order: got purchase %s', json_encode($purchase, JSON_INVALID_UTF8_IGNORE)));

                    $order_tax_values = array();

                    foreach ($purchase->products as $purchase_product) {

                        if ($process_purchase_comments) {
                            $order = $this->process_comment($order, $purchase_product);
                        }

                        if (property_exists($purchase_product, 'variantName') && $purchase_product->variantName) {
                            $item_name = $purchase_product->name . (sanitize_text_field($purchase_product->name) ? ' - ' : '') . sanitize_text_field($purchase_product->variantName);
                        } else {
                            $item_name = sanitize_text_field($purchase_product->name);
                        }

                        WC_IZ()->logger->add(sprintf('process_purchase_wc_order: Adding product %s to order', $item_name));

                        $order_item_id = wc_add_order_item($order_id, array(
                            'order_item_name' => $item_name,
                            'order_item_type' => 'line_item',
                        ));

                        $wc_product = false;
                        if (true == $purchase_product->libraryProduct) {

                            try {

                                $wc_product = WC_Zettle_Helper::get_wc_product_by_uuid($purchase_product->productUuid, $purchase_product->variantUuid);

                            } catch (IZ_Integration_Exception $e) {

                                $message = $e->getMessage();
                                WC_IZ()->logger->add(sprintf('process_purchase_wc_order: %s when adding product to order', $message), $purchase_product->productUuid);

                            }

                            if (!$wc_product && isset($purchase_product->sku)) {

                                $wc_product = WC_Zettle_Helper::get_wc_product_by_sku($purchase_product->sku);

                            }

                            if ($wc_product) {

                                if (($parent_id = $wc_product->get_parent_id()) && ($parent = wc_get_product($parent_id)) && !$parent->is_type('grouped')) {
                                    wc_add_order_item_meta($order_item_id, '_product_id', $parent_id, true);
                                    wc_add_order_item_meta($order_item_id, '_variation_id', $wc_product->get_id(), true);
                                } else {
                                    wc_add_order_item_meta($order_item_id, '_product_id', $wc_product->get_id(), true);
                                }

                            } else {

                                if (property_exists($purchase_product, 'type') && $purchase_product->type === 'GIFTCARD') {
                                    WC_IZ()->logger->add(sprintf('process_purchase_wc_order: Giftcard found, adding as product'));
                                    wc_add_order_item_meta($order_item_id, 'izettle_gift_card', get_option('izettle_gift_card_id', 1), true);
                                    
                                } else {
                                    WC_IZ()->logger->add(sprintf('Zettle variant %s does not exist in WooCommerce', $purchase_product->variantUuid));
                                }
                            }

                        } else {

                            WC_IZ()->logger->add(sprintf('Not a library product (%s)', $purchase_product->name));

                        }

                        wc_add_order_item_meta($order_item_id, '_qty', $purchase_product->quantity, true);

                        if (property_exists($purchase_product, 'grossValue')) {
                            $row_gross_value = intval($purchase_product->grossValue);
                        } else {
                            $row_gross_value = intval($purchase_product->quantity * $purchase_product->unitPrice);
                        }

                        if (property_exists($purchase_product, 'actualTaxableValue')) {
                            $actual_taxable_value = intval($purchase_product->actualTaxableValue);
                        } else {
                            $actual_taxable_value = intval($purchase_product->rowTaxableAmount);
                        }

                        if (wc_tax_enabled() && !$purchase_product->taxExempt) {

                            $row_subtotal_amount_tax_sum = 0;
                            $row_total_amount_tax_sum = 0;

                            if (property_exists($purchase_product, 'taxRates') && !empty($purchase_product->taxRates)) {

                                $tax_data_array = array();

                                foreach ($purchase_product->taxRates as $tax_rate) {

                                    if (property_exists($tax_rate, 'percentage')) {

                                        $tax_class = WC_Zettle_Helper::get_tax_class($tax_rate->percentage);
                                        wc_add_order_item_meta($order_item_id, '_tax_class', $tax_class, true);

                                        $tax_rates = WC_Tax::get_base_tax_rates($tax_class);

                                        $tax_rate_id = count($tax_rates) ? array_keys($tax_rates)[0] : null;

                                        $row_subtotal_amount_tax = $this->calc_tax_inc_tax($row_gross_value, $tax_rate->percentage);
                                        $row_subtotal_amount_tax_sum += $row_subtotal_amount_tax;

                                        $row_total_amount_tax = $this->calc_tax_ex_tax($actual_taxable_value, $tax_rate->percentage);
                                        $row_total_amount_tax_sum += $row_total_amount_tax;

                                        $order_tax_values[$tax_rate_id] = isset($order_tax_values[$tax_rate_id]) ? $order_tax_values[$tax_rate_id] + ($row_total_amount_tax / 100) : ($row_total_amount_tax / 100);

                                        $tax_data_array['total'][$tax_rate_id] = strval($row_total_amount_tax / 100);
                                        $tax_data_array['subtotal'][$tax_rate_id] = strval($row_subtotal_amount_tax / 100);

                                        WC_IZ()->logger->add(sprintf('process_purchase_wc_order: Using tax class "%s" and tax_rate id "%s" with "%s%%" tax', $tax_class, $tax_rate_id, $tax_rate->percentage));

                                    }

                                }

                                wc_add_order_item_meta($order_item_id, '_line_subtotal_tax', $row_subtotal_amount_tax_sum / 100, true);
                                wc_add_order_item_meta($order_item_id, '_line_tax', $row_total_amount_tax_sum / 100, true);

                                WC_IZ()->logger->add(sprintf('process_purchase_wc_order: Line subtotal tax %s and total tax %s', $row_subtotal_amount_tax_sum, $row_total_amount_tax_sum));

                                if (!empty($tax_data_array)) {
                                    wc_add_order_item_meta($order_item_id, '_line_tax_data', $tax_data_array, true);
                                    WC_IZ()->logger->add(sprintf('process_purchase_wc_order: Line tax data %s', print_r($tax_data_array, true)));
                                }

                            } else {

                                WC_IZ()->logger->add('No taxrates avaliable');

                            }

                            wc_add_order_item_meta($order_item_id, '_line_subtotal', ($row_gross_value - $row_subtotal_amount_tax_sum) / 100, true);
                            wc_add_order_item_meta($order_item_id, '_line_total', $actual_taxable_value / 100, true);

                            if (wc_string_to_bool(get_option('izettle_add_comment_to_meta'))) {
                                if (property_exists($purchase_product, 'comment')) {
                                    wc_add_order_item_meta($order_item_id, 'zettle_comment', $purchase_product->comment, true);
                                }
                            }


                            WC_IZ()->logger->add(sprintf('process_purchase_wc_order: Line subtotal %s and total tax %s', $row_gross_value - $row_subtotal_amount_tax_sum, $actual_taxable_value));

                            $order->set_prices_include_tax(true);

                        } else {

                            wc_add_order_item_meta($order_item_id, '_line_subtotal', $row_gross_value / 100, true);
                            wc_add_order_item_meta($order_item_id, '_line_total', $actual_taxable_value / 100, true);

                            $order->set_prices_include_tax(true);
                        }

                        // Add item id in order to find item if refunded
                        wc_add_order_item_meta($order_item_id, 'zettle_item_id', strval($purchase_product->id), true);

                        // Get product meta
                        if ($wc_product && ($product_metas = apply_filters('izettle_include_product_meta_in_order', false))) {
                            if (is_array($product_metas)) {
                                $product_id = $wc_product->get_id();
                                $index = 0;
                                foreach ($product_metas as $product_meta) {
                                    if ($metadata = get_post_meta($product_id, $product_meta, true)) {
                                        $index++;
                                        wc_add_order_item_meta($order_item_id, $product_meta, $metadata, true);
                                    }
                                }
                            }
                        }

                        if ($wc_product && $wc_product->get_type() == 'woosb') {
                            $this->woosb_get_bundled($wc_product, $order, $purchase_product->id, $purchase_product->quantity);
                        }

                    }

                    if (!empty($order_tax_values)) {

                        foreach ($order_tax_values as $tax_rate_id => $order_tax_value) {

                            $tax_item_id = wc_add_order_item($order_id, array(
                                'order_item_name' => 'TAX-' . $tax_rate_id,
                                'order_item_type' => 'tax',
                            ));

                            wc_add_order_item_meta($tax_item_id, 'rate_id', $tax_rate_id, true);
                            wc_add_order_item_meta($tax_item_id, 'tax_amount', $order_tax_value, true);
                            wc_add_order_item_meta($tax_item_id, 'rate_code', WC_Tax::get_rate_code($tax_rate_id), true);
                            wc_add_order_item_meta($tax_item_id, 'label', WC_Tax::get_rate_label($tax_rate_id), true);
                            wc_add_order_item_meta($tax_item_id, 'compound', WC_Tax::is_compound($tax_rate_id), true);
                            wc_add_order_item_meta($tax_item_id, 'rate_percent', WC_Tax::get_rate_percent_value($tax_rate_id), true);

                            WC_IZ()->logger->add(sprintf('process_purchase_wc_order: Key %s label %s value %s rate %s', $tax_rate_id, WC_Tax::get_rate_label($tax_rate_id), $order_tax_value, WC_Tax::get_rate_percent_value($tax_rate_id)));

                        }

                    }

                    WC_IZ()->logger->add(sprintf('process_purchase_wc_order: Total amount %s including TAX-amount %s', $purchase->amount / 100, $purchase->vatAmount / 100));

                    // This was previously done as update_post_meta - set cart tax will just update order item tax and shipping tax (which is not relevant in our case)
                    $order->set_total(($purchase->amount) / 100);
                    $order->set_cart_tax(($purchase->vatAmount) / 100);

                    $this->complete_order($order, $purchase, $timestamp_created, $original_order_id, $post_id, $process, $order_id);
                    
                } else {

                    $processed_with = get_post_meta($post_id, '_processed_with', true);
                    WC_IZ()->logger->add(sprintf(__('Trying to change Zettle purchase %s, but it has already been processed at %s by %s', 'woo-izettle-integration'), $purchase->purchaseNumber, $processed_timestamp, $processed_with), true);

                }

            } catch (IZ_Integration_API_Exception $e) {
                $e->write_to_logs();
                $this->document_changes($post_id, $purchase->purchaseNumber, $process, __('Order creation failed', 'woo-izettle-integration'));
                update_post_meta($post_id, '_processed_timestamp', current_time('timestamp'));
                update_post_meta($post_id, '_processed_with', 'error');
            }

        }

        public function complete_order($order, $purchase, $timestamp_created, $original_order_id, $post_id, $process, $order_id) {
            // Handle sequential order number
            if (function_exists('wc_seq_order_number_pro')) {
                wc_seq_order_number_pro()->set_sequential_order_number($order);
                $order_number = $order->get_meta('_order_number', true);
                WC_IZ()->logger->add(sprintf('Sequential order number set to %s on order id %s', $order_number, $order_id));
            }
        
            // There is no customer to receive the completed email
            remove_filter('woocommerce_email_recipient_customer_completed_order', 'conditional_email_notification', 10);
        
            // Process payment complete as WooCommerce do for normal orders
            do_action('woocommerce_pre_payment_complete', $order_id);
        
            $order->set_transaction_id($purchase->purchaseNumber);
            $order->set_date_paid($timestamp_created);
            $order = $this->set_order_status($order);
        
            if ($original_order_id) {
                $note = sprintf(__('Order updated from Zettle purchase %s', 'woo-izettle-integration'), $order_id, $purchase->purchaseNumber);
                $changes = sprintf(__('Order %s updated', 'woo-izettle-integration'), $order_id);
            } else {
                $note = sprintf(__('Order created from Zettle purchase %s', 'woo-izettle-integration'), $order_id, $purchase->purchaseNumber);
                $changes = sprintf(__('Order %s created', 'woo-izettle-integration'), $order_id);
            }
        
            $order->add_order_note($note);
        
            $order->update_meta_data('_izettle_post_id', $post_id);
        
            $order->save();
        
            do_action('woocommerce_payment_complete', $order_id);
        
            // Update post with relevant information
            update_post_meta($post_id, '_processed_timestamp', current_time('timestamp'));
            update_post_meta($post_id, '_processed_with', $process);
            update_post_meta($post_id, '_wc_order_id', $order_id);
        
            if (wc_string_to_bool(get_option('zettle_force_send_new_order_email_to_admin'))) {
                WC_IZ()->logger->add(sprintf('process_purchase_wc_order: Forcing admin email to be sent for purchase', $order_id));
                $this->force_send_new_order_email_to_admin($order_id);
            }
        
            $this->document_changes($post_id, $purchase->purchaseNumber, $process, $changes);
        }

        public function set_order_status($order) {
            if (get_option('izettle_set_order_to_status_no_stock')) {
                WC_IZ()->logger->add(sprintf('set_order_status (%s): Checking stock levels on order items', $order->get_id()));
                //Iterate through all items in the order
                foreach ($order->get_items() as $item_id => $item_data) {
                    if (!$item_data->is_type('line_item')){
                        WC_IZ()->logger->add(sprintf('set_order_status (%s): Skipping item %s', $order->get_id(), $item_id));
                        continue;
                    }

                    if (!($product = $item_data->get_product())) {
                        WC_IZ()->logger->add(sprintf('set_order_status (%s): Skipping item %s - no product found', $order->get_id(), $item_id));
                        continue;
                    }

                    if (!($product->get_manage_stock())) {
                        WC_IZ()->logger->add(sprintf('set_order_status (%s): Skipping item %s - manage stock is not enabled', $order->get_id(), $item_id));
                        continue;
                    }

                    $stock_quantity = $product->get_stock_quantity();

                    if (($stock_quantity - $item_data->get_quantity()) < 0) {
                        WC_IZ()->logger->add(sprintf('set_order_status (%s): Item %s is out of stock - setting order status %s', $order->get_id(), $item_id, get_option('izettle_set_order_to_status_no_stock', 'wc-pending')));
                        $order->set_status(get_option('izettle_set_order_to_status_no_stock', 'wc-pending'));
                        $order->add_order_note(sprintf(__('Order status changed to %s because item %s is out of stock', 'woo-izettle-integration'), get_option('izettle_set_order_to_status_no_stock', 'wc-pending'), $item_id));

                        return $order;
                    }

                    WC_IZ()->logger->add(sprintf('set_order_status (%s): Item %s is in stock', $order->get_id(), $item_id));
                }

                WC_IZ()->logger->add(sprintf('set_order_status (%s): All items are in stock - setting order status %s', $order->get_id(), get_option('izettle_set_order_to_status', 'wc-completed')));

                $order->set_status(get_option('izettle_set_order_to_status', 'wc-completed'));

                return $order;
            }

            $order->set_status(get_option('izettle_set_order_to_status', 'wc-completed'));

            return $order;

        }

        public function filter_new_order_email($recipient,$order,$email) {

            if (!is_null($order) && ($order_id = $order->get_id()) && WC_Zettle_Helper::is_izettle_order($order)){
                WC_IZ()->logger->add(sprintf('filter_new_order_email (%s): Skipping new order email', $order_id));
                $recipient = false;
            }

            return $recipient;
        }

        function force_send_new_order_email_to_admin( $order_id ) {
            $order = wc_get_order( $order_id );
            
            $email_sent = $order->get_meta( '_zettle_new_order_email_sent' );

            if ( empty( $email_sent ) ) {
                $recipient = get_option( 'admin_email' );
                $email = WC()->mailer()->get_emails()['WC_Email_New_Order'];
                $new_subject = $email->get_default_subject();
                $subject = $new_subject ? $new_subject : sprintf( __( 'New Order (#%s) on %s', 'woocommerce' ), $order->get_order_number(), get_bloginfo( 'name' ) );
                $email->setup_locale();
                $email->trigger( $order_id, $order);
                $email->restore_locale();
                $order->update_meta_data( '_zettle_new_order_email_sent', '1' );
                $order->save();
            }
        }

        public function adjust_line_item_product_stock ($prevent_item_stock_adjustment, $item, $item_qty){

            if (($order = $item->get_order()) && WC_Zettle_Helper::is_izettle_order($order)){
                WC_IZ()->logger->add(sprintf('adjust_line_item_product_stock (%s): Preventing line item stock to be adjusted', $order->get_id()));
                $prevent_item_stock_adjustment = true;
            }

            return $prevent_item_stock_adjustment;
        }

        public function payment_complete_reduce_order_stock($reduce, $order_id)
        {
            $order = wc_get_order($order_id);

            if (wc_string_to_bool(get_option('zettle_process_purchase_order_no_reduce_stock'))){

                if (WC_Zettle_Helper::is_izettle_order($order)) {
                    WC_IZ()->logger->add(sprintf('payment_complete_reduce_order_stock (%s): Blocking Zettle order stock change', $order_id));
                    return false;
                }
            }

            if ((self::$order_id == $order_id) || WC_Zettle_Helper::is_izettle_order($order)) {

                if (WC_Zettle_Helper::zettle_changes_stock()) {
                    WC_IZ()->logger->add(sprintf('payment_complete_reduce_order_stock (%s): Stocklevel changed separately from Zettle', $order_id));
                    return false;
                }
            }

            return $reduce;

        }

        private function set_order_customer_data($order, $customer)
        {
            if (is_a($order, 'WC_Order') && is_a($customer, 'WC_Customer')) {

                $order->set_billing_first_name($customer->get_billing_first_name());
                $order->set_billing_last_name($customer->get_billing_last_name());
                $order->set_billing_address_1($customer->get_billing_address_1());
                $order->set_billing_address_2($customer->get_billing_address_2());
                $order->set_billing_phone($customer->get_billing_phone());
                $order->set_billing_country($customer->get_billing_country());
                $order->set_billing_country($customer->get_billing_country());
                $order->set_billing_city($customer->get_billing_city());
                $order->set_billing_postcode($customer->get_billing_postcode());
                $order->set_billing_email($customer->get_billing_email());

                $order->set_shipping_first_name($customer->get_shipping_first_name());
                $order->set_shipping_last_name($customer->get_shipping_last_name());
                $order->set_shipping_address_1($customer->get_shipping_address_1());
                $order->set_shipping_address_2($customer->get_shipping_address_2());
                $order->set_shipping_country($customer->get_shipping_country());
                $order->set_shipping_company($customer->get_shipping_company());
                $order->set_shipping_city($customer->get_shipping_city());
                $order->set_shipping_postcode($customer->get_shipping_postcode());
                $order->set_shipping_address_2($customer->get_shipping_address_2());
            }
        }

        public function process_purchase_wc_refund($post_id)
        {

            $refund = json_decode(get_post($post_id)->post_content);

            if (!($processed_timestamp = get_post_meta($post_id, '_processed_timestamp', true))) {

                $original_post = get_page_by_title($refund->refundsPurchaseUUID1, 'OBJECT', 'izettle_purchase');

                if ($original_post && ($order_id = get_post_meta($original_post->ID, '_wc_order_id', true))) {
                    $order = new WC_Order($order_id);
                    $items = $order->get_items();
                    $changed_line_items = array();
                    foreach ($items as $id => $item) {

                        $izettle_id = $item->get_meta('zettle_item_id', true, 'edit');
                        if (empty($izettle_id) && $izettle_id !== '0') {
                            $izettle_id = $item->get_meta('iZettle item id', true, 'edit');
                        }

                        foreach ($refund->products as $refund_product) {
                            if ($izettle_id == $refund_product->id) {
                                $bundle_item_qty = $item->get_meta('bundle_item_qty', true, 'edit');
                                $qty = abs($bundle_item_qty ? $bundle_item_qty * $refund_product->quantity : $refund_product->quantity);
                                $changed_line_items[$id] = array(
                                    'qty' => $qty,
                                    'refund_total' => ($item->get_total() / $item->get_quantity()) * $qty,
                                    'refund_tax' => (reset($item->get_taxes()['total']) / $item->get_quantity()) * $qty,
                                );
                            }
                        }
                    }

                    $amount = abs($refund->amount) / 100;
                    $wc_refund = wc_create_refund(array(
                        'amount' => abs($refund->amount) / 100,
                        'reason' => 'Zettle refund of order id ' . $order_id,
                        'order_id' => $order_id,
                        'line_items' => $changed_line_items,
                        'refund_payment' => false,
                        'restock_items' => true,
                    ));

                    update_post_meta($post_id, '_processed_timestamp', current_time('timestamp'));
                    update_post_meta($post_id, '_processed_with', 'wc_refund');

                    $this->document_changes($post_id, $refund->purchaseNumber, 'wc_refund', sprintf(__('Refunded %s on order %s', 'woo-izettle-integration'), $amount, $post_id));
                } else {
                    $this->document_changes($post_id, $refund->purchaseNumber, 'wc_refund', __('Original order or purchase not found', 'woo-izettle-integration'));
                }

            } else {
                $processed_with = get_post_meta($post_id, '_processed_with', true);
                WC_IZ()->logger->add(sprintf(__('Trying to process Zettle refund %s, but it has already been processed at %s by %s', 'woo-izettle-integration'), $refund->purchaseNumber, $processed_timestamp, $processed_with), true);
            }
        }

    }
    new WC_iZettle_Integration_Purchase_Handler();
}
