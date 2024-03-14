<?php

/**
 * This class handles stocklevels
 *
 * @package   WooCommerce_iZettle_Integration
 * @author    BjornTech <info@bjorntech.com>
 * @license   GPL-3.0
 * @link      http://bjorntech.com
 * @copyright 2017-2020 BjornTech - BjornTech AB
 */

defined('ABSPATH') || exit;

if (!class_exists('WC_iZettle_Stocklevel_Handler', false)) {

    class WC_iZettle_Stocklevel_Handler
    {

        /**
         * iZettle stocklevel locations
         */
        private $locations;

        public function __construct()
        {

            /**
             * Queue actions
             */
            add_action('izettle_received_inventory_balance_changed_add', array($this, 'received_inventory_balance_changed_add'), 10, 3);
            add_action('izettle_received_inventory_tracking_started_add', array($this, 'received_inventory_tracking_started_add'), 10, 3);
            add_action('izettle_received_inventory_tracking_stopped_add', array($this, 'received_inventory_tracking_stopped_add'), 10, 3);

            /**
             * Internal actions
             */

            add_action('izettle_received_inventory_balance_changed', array($this, 'received_inventory_balance_changed'));
            add_action('izettle_received_inventory_tracking_started', array($this, 'received_inventory_tracking_started'));
            add_action('izettle_received_inventory_tracking_stopped', array($this, 'received_inventory_tracking_stopped'));
            add_filter('izettle_change_stocklevel_in_woocommerce', array($this, 'change_stocklevel_in_woocommerce'), 10, 6);
            add_filter('izettle_stock_locations', array($this, 'stock_locations'));

            /**
             * WooCommerce actions
             */
            if ('yes' == get_option('izettle_stocklevel_from_woocommerce')) {
                add_action('izettle_update_stocklevel_in_izettle', array($this, 'update_stocklevel_in_izettle'), 10, 3);
                add_action('woocommerce_variation_set_stock', array($this, 'update_stocklevel_in_izettle'), 10, 3);
                add_action('woocommerce_product_set_stock', array($this, 'product_set_stock'));
            }

            add_action('woocommerce_variation_set_stock', array($this, 'on_stock_change_from_zettle'), 10, 3);
            add_action('woocommerce_product_set_stock', array($this, 'on_stock_change_from_zettle'), 10, 3);
            

        }

        public function on_stock_change_from_zettle($product){

            if (!$product) {
                return;
            }

            $product_id = $product->get_id();

            $zettle_triggered = (strpos($_SERVER['REQUEST_URI'], 'izettle/webhook') !== false);

            WC_IZ()->logger->add(sprintf('on_stock_change_from_zettle (%s): Zettle triggered %s',$product_id, ($zettle_triggered ? 'true' : 'false')));

            if ('yes' == get_option('izettle_trigger_stock_notification_emails') && $zettle_triggered) {  
                $no_stock_amount = absint( get_option( 'woocommerce_notify_no_stock_amount', 0 ) );
                $low_stock_amount = absint( wc_get_low_stock_amount( wc_get_product( $product_id ) ) );

                $stock_quantity = $product->get_stock_quantity();

                if ($product->get_manage_stock() &&  $stock_quantity <= $no_stock_amount ) {
                    do_action( 'woocommerce_no_stock', wc_get_product( $product_id ) );
                    WC_IZ()->logger->add(sprintf('on_stock_change_from_zettle (%s): Triggered no stock notification emails', $product_id));  
                } elseif ($product->get_manage_stock() && $stock_quantity <= $low_stock_amount ) {
                    do_action( 'woocommerce_low_stock', wc_get_product( $product_id ) );
                    WC_IZ()->logger->add(sprintf('on_stock_change_from_zettle (%s): Triggered low stock notification email', $product_id));  
                }   
            }

            if ('yes' == get_option('zettle_save_post_on_stockchange') && $zettle_triggered) {
                $post = get_post($product_id);

                WC_IZ()->logger->add(sprintf('on_stock_change_from_zettle (%s): Stockchange triggered save_post action', $product_id));
    
                do_action('save_post',$product_id,$post,true);

                if ($product->is_type('variation')) {
                    $parent = wc_get_product( $product->get_parent_id());
                    $parent_id = $parent->get_id();

                    $parent_post = get_post($parent_id);

                    WC_IZ()->logger->add(sprintf('on_stock_change_from_zettle (%s): Stockchange triggered save_post action on parent %s', $product_id, $parent_id ));

                    $parent->save();
                    do_action('save_post',$parent_id,$parent_post,true);
                }
            }
            
        }


        //wc_update_product_stock

        public function product_set_stock($product)
        {

            if ($product->is_type('variable') && $product->get_manage_stock()) {
                $child_ids = $product->get_children();
                foreach ($child_ids as $child_id) {
                    $child = wc_get_product($child_id);
                    if ('parent' === $child->get_manage_stock()) {
                        $this->update_stocklevel_in_izettle($child);
                    }
                }
                return;
            }

            $this->update_stocklevel_in_izettle($product);

        }

        public function stock_locations($locations)
        {

            $locations = get_site_transient('izettle_locations');

            if (!is_array($locations)) {

                foreach (izettle_api()->get_locations() as $location) {

                    $locations[$location->name] = $location->uuid;
                    WC_IZ()->logger->add(sprintf('stock_locations: Updated stock location %s - %s', $location->name, $location->uuid));

                }

                set_site_transient('izettle_locations', $locations, MONTH_IN_SECONDS);

            }

            return $locations;

        }

        public static function get_variant_stocklevel($store_inventory, $product_uuid, $variant_uuid)
        {
            $iz_level = 0;
            foreach ($store_inventory->variants as $inventory) {
                if ($inventory->variantUuid === $variant_uuid && $inventory->productUuid === $product_uuid) {
                    $iz_level = $inventory->balance;
                }
            }
            return $iz_level;
        }

        /**
         * Update stocklevel in Zettle from a WooCommerce product linked to an Zettle product
         *
         * Call this by using the action 'izettle_update_stocklevel_in_izettle'.
         *
         * @since Unknown
         *
         * @param int/object $product_object_or_id Product id or Product Object
         * @param bool $sync_all Optional. Is the call made in the contect of a full sync of products. Default false.
         * @param bool $from_webhook. True if the call is made from a Webhook (not activly setting it to false). Default true
         */

        public function update_stocklevel_in_izettle($product_object_or_id, $sync_all = false, $from_webhook = true)
        {

            if (is_object($product_object_or_id)) {

                $product = $product_object_or_id;
                $product_id = $product->get_id();

            } else {

                $product_id = $product_object_or_id;
                $product = wc_get_product($product_object_or_id);

            }

            if (!WC_Zettle_Helper::is_syncable($product)) {
                return;
            }

            if (!apply_filters('izettle_is_client_allowed_to_sync', false, $sync_all)) {
                return;
            }

            try {

                $locations = apply_filters('izettle_stock_locations', null);
                if (!$locations) {
                    WC_IZ()->logger->add(sprintf('update_stocklevel_in_izettle (%s): Location data missing', $product_id));
                    return;
                }

                $stock_quantity = $product->get_stock_quantity('view');
                $wc_level = is_numeric($stock_quantity) ? $stock_quantity : 0;

                $product_uuid = $product->get_meta('woocommerce_izettle_product_uuid', true);
                $variant_uuid = $product->get_meta('woocommerce_izettle_variant_uuid', true);

                if (!$product_uuid || !$variant_uuid) {
                    WC_IZ()->logger->add(sprintf('update_stocklevel_in_izettle (%s): UUID metadata not found on product', $product_id));
                    return;
                }

                $store_inventory = izettle_api()->get_location_content('STORE', $product_uuid);

                if (!in_array($product_uuid, $store_inventory->trackedProducts)) {
                    izettle_api()->start_tracking_inventory($product_uuid);
                    WC_IZ()->logger->add(sprintf('update_stocklevel_in_izettle (%s): Starting to track stocklevel in Zettle', $product_id));
                }

                $iz_level = self::get_variant_stocklevel($store_inventory, $product_uuid, $variant_uuid);

                $level_change = abs($iz_level - $wc_level);
                $change = array();

                $external_uuid = IZ_UUID::generate(IZ_UUID::UUID_TIME, IZ_UUID::FMT_STRING, WC_iZettle_Integration::UUID_NODE_ID);
                set_site_transient('izettle_stocklevel_update' . $external_uuid, true, HOUR_IN_SECONDS);

                if ($wc_level > $iz_level) {
                    $change = array(array(
                        "productUuid" => $product_uuid,
                        "variantUuid" => $variant_uuid,
                        "fromLocationUuid" => $locations['SUPPLIER'],
                        "toLocationUuid" => $locations['STORE'],
                        "change" => $level_change,
                    ));
                } else if ($wc_level < $iz_level) {
                    $change = array(array(
                        "productUuid" => $product_uuid,
                        "variantUuid" => $variant_uuid,
                        "fromLocationUuid" => $locations['STORE'],
                        "toLocationUuid" => $locations['SOLD'],
                        "change" => $level_change,
                    ));
                }

                if (!empty($change)) {

                    $new_stock = izettle_api()->set_inventory(array(
                        "changes" => $change,
                        "returnBalanceForLocationUuid" => $locations['STORE'],
                        "externalUuid" => $external_uuid,
                    ));

                    WC_IZ()->logger->add(sprintf('update_stocklevel_in_izettle (%s): Stocklevel is changed from %s to %s', $product_id, $iz_level, $wc_level));

                } else {

                    WC_IZ()->logger->add(sprintf('update_stocklevel_in_izettle (%s): No need to update stocklevel', $product_id));

                }

                if (wc_string_to_bool(get_option('izettle_use_old_inventory_api')) ) {
                    return;
                }

                if (wc_string_to_bool(get_option('izettle_set_custom_low_stock_notification'))) {

                    WC_IZ()->logger->add(sprintf('update_stocklevel_in_izettle (%s): Setting low stock notification', $product_id));

                    $low_stock_amount = absint(get_option('izettle_low_stock_amount', 1));
                    $should_notify = wc_string_to_bool(get_option('izettle_low_stock_notification', 'yes'));

                    foreach ($store_inventory->variants as $inventory) {
                        if (!($inventory->variantUuid === $variant_uuid && $inventory->productUuid === $product_uuid)) {
                            continue;
                        }

                        if ($inventory->lowStockLevel == $low_stock_amount && $inventory->lowStockAlert == $should_notify) {
                            WC_IZ()->logger->add(sprintf('update_stocklevel_in_izettle (%s): No need to update low stock notification', $product_id));
                            break;
                        }

                        izettle_api()->custom_low_stock($product_uuid, $variant_uuid, $locations['STORE'], $should_notify, $low_stock_amount);
                        WC_IZ()->logger->add(sprintf('update_stocklevel_in_izettle (%s): Updated low stock notification to %s and %s', $product_id, $low_stock_amount, $should_notify ? 'enabled' : 'disabled'));
                    }

                }

            } catch (IZ_Integration_API_Exception $e) {
                $e->write_to_logs();
            }

        }

        public function received_inventory_balance_changed_add($change_record, $key, $webhook = false)
        {
            if (false === $webhook || 'yes' == get_option('izettle_queue_webhook_calls')) {
                set_site_transient('sync_purchases_from_izettle_' . $key, $change_record, DAY_IN_SECONDS);
                as_schedule_single_action(as_get_datetime_object(), 'izettle_received_inventory_balance_changed', array((object) array('change_record_key' => $key)));
                WC_IZ()->logger->add(sprintf('received_inventory_balance_changed_add: Added change record %s to queue', $key));
            } else {
                do_action('izettle_received_inventory_balance_changed', $change_record);
            }
        }

        public function received_inventory_tracking_started_add($change_record, $key, $webhook = false)
        {
            if (false === $webhook || 'yes' == get_option('izettle_queue_webhook_calls')) {
                set_site_transient('sync_purchases_from_izettle_' . $key, $change_record, DAY_IN_SECONDS);
                as_schedule_single_action(as_get_datetime_object(), 'izettle_received_inventory_tracking_started', array((object) array('change_record_key' => $key)));
                WC_IZ()->logger->add(sprintf('received_inventory_tracking_started_add: Added change record %s to queue', $key));
            } else {
                do_action('izettle_received_inventory_tracking_started', $change_record);
            }
        }

        public function received_inventory_tracking_stopped_add($change_record, $key, $webhook = false)
        {
            if (false === $webhook || 'yes' == get_option('izettle_queue_webhook_calls')) {
                set_site_transient('sync_purchases_from_izettle_' . $key, $change_record, DAY_IN_SECONDS);
                as_schedule_single_action(as_get_datetime_object(), 'izettle_received_inventory_tracking_stopped', array((object) array('change_record_key' => $key)));
                WC_IZ()->logger->add(sprintf('received_inventory_tracking_stopped_add: Added change record %s to queue', $key));
            } else {
                do_action('izettle_received_inventory_tracking_stopped', $change_record);
            }
        }

        private function set_manage_stock_to($product, $change_to)
        {
            $product_id = $product->get_id();
            if ($change_to !== wc_string_to_bool($product->get_manage_stock())) {
                WC_IZ()->logger->add(sprintf('set_manage_stock_to (%s): Set %s product to %s manage stock', $product_id, $product->get_type(), $change_to ? 'start' : 'stop'));
                $product->set_manage_stock($change_to);
                $product->save();
            }
        }

        public function change_manage_stock($change_record, $change_to)
        {

            try {

                if ('yes' == get_option('izettle_import_stocklevel_as_metadata_value')) {
                    WC_IZ()->logger->add(sprintf('change_manage_stock: Skipping changing stock tracking for product %s - only tracking meta', $change_record->productUuid));
                    return;
                }

                $product = WC_Zettle_Helper::get_wc_product_by_uuid($change_record->productUuid);
                if ($product) {
                    if ($product->get_children()) {
                        $variations = WC_Zettle_Helper::get_all_variations($product);
                        foreach ($variations as $variation) {
                            if (!is_object($variation)) {
                                $variation = wc_get_product($variation['variation_id']);
                            }
                            $this->set_manage_stock_to($variation, $change_to);
                        }
                    } else {
                        $this->set_manage_stock_to($product, $change_to);
                    }
                }

            } catch (IZ_Integration_Exception $e) {

                WC_IZ()->logger->add(sprintf('change_manage_stock: %s when processing inventory tracking change for %s', $e->getMessage()), $change_record->productUuid);

            }

        }

        public function received_inventory_tracking_started($change_record)
        {
            $this->change_manage_stock($change_record, true);
        }

        public function received_inventory_tracking_stopped($change_record)
        {
            $this->change_manage_stock($change_record, false);
        }

        public function received_inventory_balance_changed($change_record)
        {

            do_action('izettle_remove_product_update_actions');

            WC_IZ()->logger->add(sprintf('received_inventory_balance_changed: got balance change %s', json_encode($change_record, JSON_INVALID_UTF8_IGNORE)));

            $change_record = is_object($change_record) ? $change_record : json_decode(json_encode($change_record, JSON_INVALID_UTF8_IGNORE));

            if (isset($change_record->change_record_key)) {
                WC_IZ()->logger->add(sprintf('received_inventory_balance_changed: Starting to process change %s from queue', $change_record->change_record_key));
                $change_record = get_site_transient('sync_purchases_from_izettle_' . $change_record->change_record_key);
                delete_site_transient('sync_purchases_from_izettle_' . $change_record->change_record_key);
            }

            set_site_transient('zettle_inventory_balance_changed_' . $change_record->updated->timestamp, $change_record, HOUR_IN_SECONDS);

            foreach ($change_record->balanceAfter as $balance_after) {

                if(!WC_Zettle_Helper::is_store_inventory_update($balance_after)){
                    break;
                }

                foreach ($change_record->balanceBefore as $balance_before) {

                    if (($balance_before->variantUuid == $balance_after->variantUuid) && ($balance_before->productUuid == $balance_after->productUuid) && ($balance_before->balance != $balance_after->balance)) {

                        try {

                            $product = WC_Zettle_Helper::get_wc_product_by_uuid($balance_before->productUuid, $balance_before->variantUuid);

                            if ($product) {

                                $product_id = $product->get_id();

                                if (isset($change_record->externalUuid) && '' == $change_record->externalUuid) {
                                    WC_IZ()->logger->add(sprintf('received_inventory_balance_changed (%s): Got external UUID "%s" for the change', $product_id, $change_record->externalUuid));
                                }

                                WC_IZ()->logger->add(sprintf('received_inventory_balance_changed (%s): Variant UUID %s found product', $product_id, $balance_before->variantUuid));

                                $manage_stock = $product->get_manage_stock('view');

                                if ('yes' == get_option('izettle_import_stocklevel_as_metadata_value')){
                                    $new_stock = $balance_after->balance;
                                    $current_stock = $product->get_meta('izettle_current_stock_value');
            
                                    if (!$current_stock || ($new_stock != $current_stock)) {
                                        if ('parent' === $manage_stock && ($parent_id = $product->get_parent_id())) {
                                            $product_id = $parent_id;
                                            WC_IZ()->logger->add(sprintf(sprintf('received_inventory_balance_changed (%s): Parent %s sets stock - metadata function', $product->get_id(), $product_id)));
                                            $product = wc_get_product($product_id);
                                        }
            
                                        $product->update_meta_data('izettle_current_stock_value', $new_stock);
                                        $product->save();
                                        WC_IZ()->logger->add(sprintf('received_inventory_balance_changed (%s): Changing WooCommerce meta stock level from %s to %s', $product_id, $current_stock, $new_stock));
                                    }
            
                                    break;
                                }

                                if (!$manage_stock) {
                                    WC_IZ()->logger->add(sprintf('received_inventory_balance_changed (%s): Set product to manage stock', $product_id));
                                    $product->set_manage_stock(true);
                                }

                                $current_level = $product->get_stock_quantity('view');

                                if ($current_level != $balance_after->balance) {

                                    $parent_id = $product->get_parent_id();

                                    if ('parent' === $manage_stock && $parent_id) {
                                        $product_id = $parent_id;
                                        WC_IZ()->logger->add(sprintf('received_inventory_balance_changed (%s): Parent %s sets stock', $product->get_id(), $product_id));
                                        $product = wc_get_product($product_id);
                                    }

                                    $new_stocklevel = wc_update_product_stock($product, $balance_after->balance, 'set');
                                    WC_IZ()->logger->add(sprintf('received_inventory_balance_changed (%s): Set stocklevel from %d to %d', $product_id, $current_level, $new_stocklevel));

                                } else {
                                    WC_IZ()->logger->add(sprintf('received_inventory_balance_changed (%s): No need to set stocklevel %d', $product_id, $current_level));
                                }

                            } else {

                                WC_IZ()->logger->add(sprintf('received_inventory_balance_changed: Zettle UUID %s - %s not found in WooCommerce', $balance_before->productUuid, $balance_before->variantUuid));

                            }

                        } catch (IZ_Integration_Exception $e) {

                            $message = $e->getMessage();
                            WC_IZ()->logger->add(sprintf('received_inventory_balance_changed: %s when processing stocklevel change for %s', $message), $balance_before->productUuid);

                        }

                        break;

                    }

                }

            }

        }

        /**
         * Set stocklevel on WooCommerce product.
         */
        public function change_stocklevel_in_woocommerce($changed, $product, $iz_product, $variant, $dry_run)
        {


            if ('yes' != get_option('izettle_import_stocklevel')) {
                return $changed;
            }

            $store_inventory = izettle_api()->get_location_content('STORE', $iz_product->uuid);

            $product_id = $product ? $product->get_id() : __('*new*', 'woo-izettle-integration');

            if (!in_array($iz_product->uuid, $store_inventory->trackedProducts)) {
                $message = sprintf('change_stocklevel_in_woocommerce (%s): Zettle product %s is not tracked in Zettle', $product_id, $iz_product->uuid);
                WC_IZ()->logger->add(sprintf('%s%s', $dry_run ? 'Dry run: ' : '', $message));
                return $changed;
            }

            $manage_stock = $product->get_manage_stock('view');
            if (!$manage_stock && ('yes' != get_option('izettle_import_stocklevel_as_metadata_value'))) {
                $product->set_manage_stock(true);
                $changed = true;
                WC_IZ()->logger->add(sprintf('%s%s', $dry_run ? 'Dry run: ' : '', sprintf('change_stocklevel_in_woocommerce (%s): Setting product to manage stock', $product_id)));
            }

            foreach ($store_inventory->variants as $inventory) {

                if ($inventory->variantUuid === $variant->uuid && $inventory->productUuid === $iz_product->uuid) {

                    $message = sprintf('change_stocklevel_in_woocommerce (%s): Zettle product UUID %s is tracked in Zettle with a stocklevel of %s', $product_id, $iz_product->uuid, $inventory->balance);
                    WC_IZ()->logger->add(sprintf('%s%s', $dry_run ? 'Dry run: ' : '', $message));

                    if ($dry_run) {
                        return $changed;
                    }

                    if ('yes' == get_option('izettle_import_stocklevel_as_metadata_value')){
                        $new_stock = $inventory->balance;
                        $current_stock = $product->get_meta('izettle_current_stock_value');

                        if (!$current_stock || ($new_stock != $current_stock)) {
                            if ('parent' === $manage_stock && ($parent_id = $product->get_parent_id())) {
                                $product_id = $parent_id;
                                WC_IZ()->logger->add(sprintf(sprintf('change_stocklevel_in_woocommerce (%s): Parent %s sets stock - metadata function', $product->get_id(), $product_id)));
                                $product = wc_get_product($product_id);
                            }

                            $product->update_meta_data('izettle_current_stock_value', $new_stock);
                            WC_IZ()->logger->add(sprintf('change_stocklevel_in_woocommerce (%s): Changing WooCommerce meta stock level from %s to %s', $product_id, $current_stock, $new_stock));
                            $changed = true;
                        }

                        break;
                    }

                    $current_stock = is_numeric($temp_stock = $product->get_stock_quantity('view')) ? $temp_stock : 0;
                    $new_stock = $inventory->balance;

                    if ($new_stock != $current_stock) {

                        if ('parent' === $manage_stock && ($parent_id = $product->get_parent_id())) {
                            $product_id = $parent_id;
                            WC_IZ()->logger->add(sprintf(sprintf('change_stocklevel_in_woocommerce (%s): Parent %s sets stock', $product->get_id(), $product_id)));
                            $product = wc_get_product($product_id);
                        }

                        $new_stocklevel = wc_update_product_stock($product, $new_stock, 'set', true);
                        $changed = true;
                        WC_IZ()->logger->add(sprintf('change_stocklevel_in_woocommerce (%s): Changing stock level from %s to %s', $product_id, $current_stock, $new_stocklevel));

                    }

                    break;

                }

            }

            return $changed;

        }

    }

    new WC_iZettle_Stocklevel_Handler();

}
