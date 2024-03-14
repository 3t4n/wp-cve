<?php

/**
 * This class handles syncing products with izettle
 *
 * @package   WooCommerce_iZettle_Integration
 * @author    BjornTech <info@bjorntech.com>
 * @license   GPL-3.0
 * @link      http://bjorntech.com
 * @copyright 2017-2020 BjornTech - BjornTech AB
 */

defined('ABSPATH') || exit;

if (!class_exists('Woo_iZettle_Integration_Products_From_iZettle_Handler', false)) {

    class Woo_iZettle_Integration_Products_From_iZettle_Handler
    {

        private $match_only;
        private $dry_run;

        private $batch_size;

        private $import_or_webhook;

        public function __construct()
        {

            add_action('izettle_add_iz_products_to_queue', array($this, 'add_iz_products_to_queue'), 10, 2);
            add_action('izettle_handle_images', array($this, 'handle_images'), 10, 2);
            add_action('izettle_handle_variant_images', array($this, 'handle_variant_images'), 10, 2);
            add_action('izettle_handle_additional_images', array($this, 'handle_additional_images'), 10, 2);
            $this->batch_size = get_option('izettle_product_import_batch_size', 500);

            add_action('izettle_sync_products_from_izettle_add', array($this, 'sync_products_from_izettle_add'), 10, 4);
            add_action('izettle_delete_product_from_izettle', array($this, 'delete_product_from_izettle'), 10, 3);

            add_action('izettle_handle_images_action', array($this, 'handle_images_action'), 10, 3);
            add_action('izettle_handle_variant_images_action', array($this, 'handle_variant_images_action'), 10, 3);
            add_action('izettle_handle_additional_images_action', array($this, 'handle_additional_images_action'), 10, 2);

            add_filter('wciz_sync_iz_products_filter', array($this, 'sync_iz_products'), 10, 2);
            add_action('wciz_sync_iz_products_action', array($this, 'sync_iz_products'), 10, 2);
            add_action('wciz_sync_iz_products_process', array($this, 'sync_iz_products_process'), 10, 4);

            add_filter('izettle_import_product', array($this, 'is_syncable'), 10, 2);

        }

        public function add_iz_products_to_queue($offset, $batch_id)
        {
            if ($offset !== false) {

                $all_products = get_site_transient('izettle_all_products_' . $batch_id);

                if (!empty($all_products)) {
                    $total_number = count($all_products);
                    $end = min(array($offset + $this->batch_size, $total_number)) - 1;
                    $added = 0;

                    for ($i = (integer) $offset; $i <= (integer) $end; $i++) {

                        try {
                            do_action('izettle_sync_products_from_izettle_add', $all_products[$i], uniqid() . ' - ' . $all_products[$i]->uuid);
                            $added++;
                        } catch (IZ_Integration_API_Exception $e) {
                            $e->write_to_logs();
                        }

                    }

                    if ($end == $total_number - 1) {
                        delete_site_transient('izettle_all_products_' . $batch_id);
                    }

                    WC_IZ()->logger->add(sprintf('add_iz_products_to_queue: Queuing %d products (%d - %d) from Zettle to processing queue in WooCommerce', $added, $offset, $end));
                }
            }
        }

        public function sync_iz_products($number_synced = 0, $sync_all = false)
        {
            try {

                if ($sync_all) {

                    $batch_id = uniqid();

                    $all_products = izettle_api()->get_products();

                    WC_IZ()->logger->add(sprintf('sync_iz_products: Got %d products from Zettle', count($all_products)));

                    set_site_transient('izettle_all_products_' . $batch_id, $all_products, DAY_IN_SECONDS);

                    if (!empty($all_products)) {

                        if ($this->batch_size <= count($all_products)) {
                            for ($i = 0; $i < count($all_products); $i = $i + $this->batch_size) {
                                as_schedule_single_action(as_get_datetime_object(), 'izettle_add_iz_products_to_queue', array($i, $batch_id), 'wciz_sync_iz_products');
                            }
                        } else {
                            do_action('izettle_add_iz_products_to_queue', 0, $batch_id);
                        }

                        $number_synced = count($all_products);
                        WC_IZ()->logger->add(sprintf('sync_iz_products: Adding %d Zettle products to processing queue in WooCommerce.', $number_synced));

                    } else {

                        WC_IZ()->logger->add(sprintf('sync_iz_products: No Zettle products found to add to WooCommerce.'));

                    }

                }

            } catch (IZ_Integration_API_Exception $e) {
                $e->write_to_logs();
            }

            return $number_synced;

        }

        /**
         * Places an izettle product object or a product update object int queue
         *
         * @since 5.0.0
         *
         * @param object $payload An iZettle product object or product update object
         * @param string $key A unique key for the update
         * @param bool $webhook Set to true if called from a webhook, Default false
         */

        public function sync_products_from_izettle_add($payload, $key, $webhook = false, $create = true)
        {
            if (false === $webhook || 'yes' == get_option('izettle_queue_webhook_calls')) {
                set_site_transient('sync_products_from_izettle_' . $key, $payload, DAY_IN_SECONDS);
                as_schedule_single_action(as_get_datetime_object(), 'wciz_sync_iz_products_process', array(false, $key, $webhook, $create), 'wciz_sync_iz_products');
            } else {
                do_action('wciz_sync_iz_products_process', $payload, false, $webhook, $create);
            }
        }

        /**
         * Get or create a category
         *
         * @since 6.3.0
         *
         * @param string $product_id product id being processed now
         * @param string $category category name to get or create
         *
         * @return string|bool category id or false
         */

        public function maybe_add_category($product_id, $category)
        {

            $slug = sanitize_title($category);

            if ($term = get_term_by('slug', $slug, 'product_cat')) {

                if (is_wp_error($term)) {

                    foreach ($term->errors as $err_code => $message) {
                        $error_message = implode(',', $message);
                        $this->logger(sprintf('maybe_add_category (%s): %s (%s - %s) when trying to update category %s', $product_id, $error_message, $err_code, $term->error_data[$err_code], $category));
                    }
                    return false;

                } else {

                    $this->logger(sprintf('maybe_add_category (%s): Found existing slug %s for category %s using term id %s', $product_id, $slug, $category, $term->term_id), true);
                    return $term->term_id;

                }

            } else {

                $term = wp_insert_term($category, 'product_cat', [
                    'slug' => sanitize_title($slug)]
                );

                if (is_wp_error($term)) {

                    if (array_key_exists('term_exists', $term->errors)) {

                        $this->logger(sprintf('maybe_add_category (%s): Category %s (%s) already exists as term_id %s', $product_id, $category, $slug, $term->error_data['term_exists']));
                        return $term->error_data['term_exists'];

                    } else {

                        foreach ($term->errors as $err_code => $message) {
                            $error_message = implode(',', $message);
                            $this->logger(sprintf('maybe_add_category (%s): %s (%s - %s) when trying to add category %s', $product_id, $error_message, $err_code, $term->error_data[$err_code], $category));
                        }
                        return false;
                    }

                } else {

                    $this->logger(sprintf('maybe_add_category (%s): Category %s (%s) successfully created as category id %s', $product_id, $category, $slug, $term['term_id']));
                    return $term['term_id'];

                }

            }

        }

        private function logger($message)
        {
            WC_IZ()->logger->add(sprintf('%s%s', $this->dry_run ? 'Dry run: ' : '', $message));
        }

        private function maybe_add_jpeg($url)
        {
            if (false === strpos($url, '.', strlen($url) - 5)) {
                $url .= '.jpeg';
            }
            return $url;
        }

        private function get_iz_product($payload)
        {

            return isset($payload->newEntity) ? $payload->newEntity : $payload;

        }

        private function get_old_iz_product($payload)
        {

            return isset($payload->oldEntity) ? $payload->oldEntity : false;

        }

        public function delete_variant_from_izettle($product_uuid, $variant_uuid) {
            $deleted_variant = WC_Zettle_Helper::get_wc_product_by_uuid($product_uuid, $variant_uuid);

            if ($deleted_variant) {
                $deleted_variant_id = $deleted_variant->get_id();
                $deleted_variant->delete(true);
                $this->logger(sprintf('delete_variant_from_izettle (%s): Deleted variant with UUID %s', $deleted_variant_id, $variant_uuid));
            }
        }

        public function delete_product_from_izettle($product_uuid, $force = false)
        {

            try {

                if ($product = WC_Zettle_Helper::get_wc_product_by_uuid($product_uuid)) {

                    $product_id = $product->get_id();

                    // If we're forcing, then delete permanently.
                    if ($force) {
                        if ($product->is_type('variable')) {
                            foreach ($product->get_children() as $child_id) {
                                $child = wc_get_product($child_id);
                                if (!empty($child)) {
                                    $child->delete(true);
                                }
                            }
                        } else {
                            // For other product types, if the product has children, remove the relationship.
                            foreach ($product->get_children() as $child_id) {
                                $child = wc_get_product($child_id);
                                if (!empty($child)) {
                                    $child->set_parent_id(0);
                                    $child->save();
                                }
                            }
                        }

                        $product->delete(true);
                        $this->logger(sprintf('delete_product_from_izettle (%s): Permanently deleted product with UUID %s from WooCommerce', $product_id, $product_uuid));

                    } else {

                        $product->delete();
                        $this->logger(sprintf('delete_product_from_izettle (%s): Deleted product with UUID %s from WooCommerce', $product_id, $product_uuid));

                    }
                }

            } catch (IZ_Integration_Exception $e) {

                $message = $e->getMessage();
                $this->logger(sprintf('sync_iz_products_process: %s when deleting WooCommerce product', $message, $iz_product->uuid));

            }

        }

        public function sync_iz_products_process($payload, $key = false, $webhook = false, $create = true)
        {

            if (false !== $payload) {
                $payload = is_object($payload) ? $payload : json_decode(json_encode($payload, JSON_INVALID_UTF8_IGNORE));
            } else {
                if ($payload = get_site_transient('sync_products_from_izettle_' . $key)) {
                    delete_site_transient('sync_products_from_izettle_' . $key);
                } else {
                    $this->logger('sync_iz_products_process: Zettle product transient removed');
                    return;
                }

            }

            $this->logger(sprintf('sync_iz_products_process: got %s payload %s', $webhook ? 'webhook' : 'import', json_encode($payload, JSON_INVALID_UTF8_IGNORE)));

            if ($create) {
                $this->logger(sprintf('sync_iz_products_process: create set to %s', $create));
            }

            $iz_product = $this->get_iz_product($payload);
            $old_iz_product = $this->get_old_iz_product($payload);

            if (wc_string_to_bool(get_option('izettle_sophisticated_manual_sync', 'yes'))) {
                if (false !== strpos(get_option('izettle_when_changed_in_izettle'), 'create') || get_option('izettle_when_changed_in_izettle') == ''){
                    $create = true;
                    WC_IZ()->logger->add('sync_iz_products_process: Sophisticated manual sync - create set to true');
                } else {
                    $create = false;
                    WC_IZ()->logger->add('sync_iz_products_process: Sophisticated manual sync - create set to false');
                }

            }

            try {

                do_action('bjorntech_remove_product_update_actions');

                if (false === $webhook) {
                    $izettle_import_type = get_option('izettle_import_type', 'merge');
                    $this->dry_run = false !== strpos($izettle_import_type, 'dry');
                    $this->match_only = false !== strpos($izettle_import_type, 'match');
                    $all_new = 'new' == $izettle_import_type;
                    $this->import_or_webhook = 'import';
                } else {
                    $this->dry_run = false;
                    $this->match_only = false;
                    $all_new = false;
                    $this->import_or_webhook = 'webhook';
                }

                $new_product = false;

                if (apply_filters('izettle_import_product', true, $iz_product)) {

                    $changed = false;

                    //Need to delete variants first

                    if (wc_string_to_bool(get_option('izettle_delete_variants'))) {
                        if (false !== strpos(get_option('izettle_when_changed_in_izettle'), 'delete')) {
                            $this->logger(sprintf('sync_iz_products_process (%s): Searching for variants for product', $iz_product->uuid));

                            $new_product_variants = isset($iz_product->variants) ? $iz_product->variants : array();
                            $old_product_variants = isset($old_iz_product->variants) ? $old_iz_product->variants : array();

                            // Get the variant UUIDs of the old product variants
                            $old_variant_uuids = array_map(function($variant) {
                                return $variant->uuid;
                            }, $old_product_variants);

                            $this->logger(sprintf('sync_iz_products_process (%s): Found %s variants in old version of product', $iz_product->uuid, count($old_variant_uuids)));

                            // Get the variant UUIDs of the new product variants
                            $new_variant_uuids = array_map(function($variant) {
                                return $variant->uuid;
                            }, $new_product_variants);

                            $this->logger(sprintf('sync_iz_products_process (%s): Found %s variants in new version of product', $iz_product->uuid, count($new_variant_uuids)));

                            // Find the variant UUIDs that exist in the old variants but not in the new variants
                            $deleted_variant_uuids = array_diff($old_variant_uuids, $new_variant_uuids);

                            // Delete the variants that have been removed
                            foreach ($deleted_variant_uuids as $deleted_uuid) {
                                $this->logger(sprintf('sync_iz_products_process (%s): Deleting variant with UUID %s', $iz_product->uuid, $deleted_uuid));
                                $this->delete_variant_from_izettle($iz_product->uuid, $deleted_uuid);
                            }
                        }

                    }

                    if (count($iz_product->variants) == 1 && !isset($iz_product->variantOptionDefinitions)) {

                        $sku = isset($iz_product->variants[0]->sku) ? $iz_product->variants[0]->sku : '';

                        $product = WC_Zettle_Helper::get_wc_product_by_uuid($iz_product->uuid);

                        if (!$product) {
                            $product = WC_Zettle_Helper::get_wc_product_by_sku($sku);
                        }

                        if (!$all_new && $product) {

                            $product_id = $product->get_id();
                            $product_type = $product->get_type();
                            $parent_id = $product->get_parent_id();

                            if (!$parent_id) {

                                $this->logger(sprintf('sync_iz_products_process (%s): Updating a %s product', $product_id, $product_type));

                            } else {

                                throw new IZ_Integration_Exception(sprintf('Import of Zettle SKU %s mapped a %s product - not allowed if only one variation', $sku, $product_type));

                            }

                        } elseif ($create) {

                            $new_product = true;

                            if (!$this->match_only) {

                                if (!$this->dry_run) {

                                    $product = new WC_Product_Simple();
                                    $product->set_name(sanitize_text_field($iz_product->name));
                                    $product->set_slug(sanitize_title($iz_product->name));
                                    $product->set_status('importing');
                                    $product->update_meta_data('woocommerce_izettle_product_uuid', $iz_product->uuid);
                                    $product->update_meta_data('woocommerce_izettle_variant_uuid', $iz_product->variants[0]->uuid);
                                    $product->save();
                                    $product_id = $product->get_id();

                                } else {

                                    $product_id = 0;

                                }

                                $this->logger(sprintf('sync_iz_products_process (%s): Creating simple product from Zettle', $product_id));

                            } else {

                                throw new IZ_Integration_Exception(sprintf('Simple product not found (SKU: %s)', $sku));

                            }

                        } else {

                            $this->logger(sprintf('sync_iz_products_process: Zettle product UUID %s was not found and is set not to be created', $iz_product->uuid));
                            return;

                        }

                        $changed = $this->create_common_from_variant($changed, $product, $product_id, $iz_product, $iz_product->variants[0], $new_product, $old_iz_product);

                    } else {

                        $sku = isset($iz_product->variants[0]->sku) ? $iz_product->variants[0]->sku : '';

                        $product = WC_Zettle_Helper::get_wc_product_by_uuid($iz_product->uuid);

                        if (!$product) {
                            $product = WC_Zettle_Helper::get_wc_product_by_sku($sku);
                        }

                        if (!$all_new && $product) {

                            $product_id = $product->get_id();
                            $product_type = $product->get_type();

                            if ($product->get_children() || 'variable' == $product_type) {

                                $this->logger(sprintf('sync_iz_products_process (%s): Updating %s product', $product_id, $product_type));

                            } elseif ($parent_product_id = $product->get_parent_id()) {

                                $product = wc_get_product($parent_product_id);
                                $this->logger(sprintf('sync_iz_products_process (%s): Found %s product as parent %s from first variation', $product_id, $product_type, $parent_product_id));
                                $product_id = $parent_product_id;

                            } elseif ('simple' == $product_type && wc_string_to_bool(get_option('zettle_convert_simple_to_variable'))) {

                                $this->logger(sprintf('sync_iz_products_process (%s): Found simple product in WooCommerce while mapping variable product - changing product type to variable', $product_id));

                                $product_classname = WC_Product_Factory::get_product_classname( $product_id, 'variable' );
                                $product = new $product_classname( $product_id );

                                $product->save();

                                $manage_stock = $product->get_manage_stock('view');

                                if ($manage_stock) {
                                    $current_stock = is_numeric($temp_stock = $product->get_stock_quantity('view')) ? $temp_stock : 0;
                                    $product->update_meta_data('woocommerce_izettle_old_simple_stock_value', "$current_stock");

                                    $product->set_manage_stock(false);

                                    $this->import_or_webhook = 'import';

                                    $this->logger(sprintf('sync_iz_products_process (%s): Archived old stockvalue and stopped tracking inventory on main product', $product_id));

                                    $product->save();
                                }
                                
                            } else {

                                throw new IZ_Integration_Exception(sprintf('Product %s found when mapping Zettle product. Import does not support %s products', $product_id, $product_type));

                            }

                        } elseif ($create) {

                            $new_product = true;

                            if (!$this->match_only) {

                                if (!$this->dry_run) {

                                    $product = new WC_Product_Variable();
                                    $product->set_name(sanitize_text_field($iz_product->name));
                                    $product->set_slug(sanitize_title($iz_product->name));
                                    $product->set_status('importing');
                                    $product->update_meta_data('woocommerce_izettle_product_uuid', $iz_product->uuid);
                                    $product->save();
                                    $product_id = $product->get_id();

                                } else {

                                    $product_id = 0;

                                }

                                $this->logger(sprintf('sync_iz_products_process (%s): Creating variable product from Zettle uuid %s', $product_id, $iz_product->uuid));

                            } else {

                                throw new IZ_Integration_Exception(sprintf('Variable product not found (SKU: %s)', $sku));

                            }

                        } else {

                            $this->logger(sprintf('sync_iz_products_process: Zettle product UUID %s was not found and is set not to be created', $iz_product->uuid));
                            return;

                        }

                        if (!$this->match_only) {

                            if ($new_product || ('yes' != get_option('izettle_ignore_attributes_existing_products'))) {
                                $any_array = WC_Zettle_Helper::get_any_array();

                                $attributes = array();
    
    
    
                                $use_global_attributes = ('yes' == get_option('izettle_import_create_global_attributes'));
    
                                if (isset($iz_product->variantOptionDefinitions->definitions)) {
    
                                    foreach ($iz_product->variantOptionDefinitions->definitions as $definition) {
    
                                        $terms = array();
                                        foreach ($definition->properties as $property) {
                                            if (!in_array($property->value, $terms) && !in_array($property->value, $any_array)) {
                                                array_push($terms, trim($property->value));
                                            }
                                        }
    
                                        if (get_option('zettle_sort_terms_alphabetically','yes') === 'yes') {
                                            sort($terms);
                                        }
                                        
                                        $terms = apply_filters('izettle_import_terms', $terms, $iz_product, $product_id);
    
                                        $this->logger(sprintf('sync_iz_products_process (%s): Variation "%s" has "%s" as terms', $product_id, $definition->name, implode(',', $terms)));
    
                                        if ($use_global_attributes) {
                                            $attribute = $this->create_global_attribute($definition->name, $terms);
                                        } else {
                                            $attribute = $this->create_attribute($definition->name, $terms);
                                        }
                                        $attributes[] = $attribute;
    
                                    }
    
                                } else {
    
                                    $terms = array();
                                    foreach ($iz_product->variants as $variant) {
                                        if (!in_array($variant->name, $terms) && !in_array($variant->name, $any_array)) {
                                            array_push($terms, $variant->name);
                                        }
                                    }
    
                                    if (get_option('zettle_sort_terms_alphabetically','yes') === 'yes') {
                                        sort($terms);
                                    }

                                    $terms = apply_filters('izettle_import_terms', $terms, $iz_product, $product_id);
    
                                    if (!$this->dry_run) {
    
                                        if ($use_global_attributes) {
                                            $attribute = $this->create_global_attribute($definition->name, $terms);
                                        } else {
                                            $attribute = $this->create_attribute('unknown', $terms, $this->dry_run);
                                        }
                                        $attributes[] = $attribute;
                                    }
    
                                }
    
                                if (!$this->dry_run) {
    
                                    $product->set_attributes($attributes);
    
                                }
                            }
                        }

                        foreach ($iz_product->variants as $variant) {

                            $sku = isset($variant->sku) ? $variant->sku : '';
                            $current_variation_product = WC_Zettle_Helper::get_wc_product_by_uuid($iz_product->uuid, $variant->uuid);

                            if (!$product) {

                                $current_variation_product = WC_Zettle_Helper::get_wc_product_by_sku($sku);

                            }

                            if (!$all_new && $current_variation_product && $current_variation_product->get_parent_id() == $product_id) {

                                $variation = $current_variation_product;
                                $variation_id = $variation->get_id();

                            } else {

                                if (!$this->match_only) {

                                    if (!$this->dry_run) {

                                        $variation = new WC_Product_Variation();
                                        $variation->set_name($variant->name);
                                        $variation->set_slug(sanitize_title($variant->uuid));
                                        $variation->set_parent_id($product_id);
                                        $variation->update_meta_data('woocommerce_izettle_variant_uuid', $variant->uuid);
                                        $variation->save();
                                        $variation_id = $variation->get_id();

                                    } else {

                                        $variation_id = 0;

                                    }

                                    $this->logger(sprintf('sync_iz_products_process (%s): Creating product variant from Zettle UUID %s', $variation_id, $variant->uuid));

                                } else {

                                    throw new IZ_Integration_Exception(sprintf('Variation not found using sku %s', $sku));

                                }

                            }

                            if (!$this->match_only) {

                                if ($new_product || ('yes' != get_option('izettle_ignore_attributes_existing_products'))) {

                                    $variation_attributes = array();

                                    if (isset($variant->options)) {

                                        foreach ($variant->options as $option) {

                                            if (!$this->dry_run) {

                                                if ($use_global_attributes) {
                                                    $variation_attributes['pa_' . $this->get_attribute_name($option->name)] = wc_sanitize_taxonomy_name($option->value);
                                                } else {
                                                    $variation_attributes[wc_sanitize_taxonomy_name($option->name)] = trim($option->value);
                                                }

                                            }

                                        }

                                    } else {

                                        if ($use_global_attributes) {
                                            $variation_attributes['pa_unknown'] = $variant->name;
                                        } else {
                                            $variation_attributes['unknown'] = $variant->name;
                                        }

                                    }

                                    if (!$this->dry_run) {

                                        $variation->set_attributes($variation_attributes);

                                    }

                                }

                            }

                            $changed = $this->create_common_from_variant($changed, $variation, $variation_id, $iz_product, $variant, $new_product, $old_iz_product);

                            if (!$this->dry_run) {

                                $variation->save();

                                /**
                                 * Import variant images, only used for iZettle web-users using 'izettle_import_variant_images'
                                 */
                                if (wc_string_to_bool(get_option('izettle_import_variant_images')) && isset($variant->presentation->imageUrl)) {

                                    do_action('izettle_handle_variant_images', $variation, $this->maybe_add_jpeg($variant->presentation->imageUrl));

                                }

                            }

                        }

                        if (!$this->dry_run) {

                            if (($exsting_uuid = $product->get_meta('woocommerce_izettle_product_uuid', true, 'edit')) != $iz_product->uuid) {

                                $product->update_meta_data('woocommerce_izettle_product_uuid', $iz_product->uuid);

                                if (!$exsting_uuid) {
                                    $this->logger(sprintf('sync_iz_products_process (%s): Setting product UUID on variable product to %s', $product_id, $iz_product->uuid));
                                } else {
                                    $this->logger(sprintf('sync_iz_products_process (%s): Updating product UUID on variable product from %s to %s', $product_id, $exsting_uuid, $iz_product->uuid));
                                }

                            }

                            if ($exsting_uuid = $product->get_meta('woocommerce_izettle_variant_uuid', true, 'edit')) {

                                $product->delete_meta_data('woocommerce_izettle_variant_uuid');

                                $this->logger(sprintf('sync_iz_products_process (%s): Removed faulty variant UUID %s from variable product', $product_id, $exsting_uuid));

                            }

                        }

                    }

                    if (!$this->dry_run) {

                        $changed = $this->create_product_data($changed, $product, $product_id, $iz_product, $new_product, $old_iz_product);

                        $changed = $this->create_name($changed, $product, $product_id, $iz_product, $new_product, $old_iz_product);

                        if ($product->get_status() == 'importing') {

                            $products_to_status = get_option('izettle_set_products_to_status', 'publish');
                            $product->set_status($products_to_status ? $products_to_status : 'publish');
                            $changed = true;

                        }

                        if ($changed) {
                            $product->save();
                            $this->logger(sprintf('sync_iz_products_process (%s): Saving UUID %s', $product_id, $iz_product->uuid));
                        }

                        /**
                         * Import images uses 'izettle_webhook_images' and 'izettle_import_images'
                         */

                        if ('yes' == get_option('izettle_import_images') && isset($iz_product->presentation->imageUrl)) {

                            do_action('izettle_handle_images', $product, $this->maybe_add_jpeg($iz_product->presentation->imageUrl));

                        }

                        /**
                         * Import images uses 'izettle_import_additional_images'
                         */

                        if ('yes' == get_option('izettle_import_additional_images') && isset($iz_product->online->presentation->additionalImageUrls)) {

                            do_action('izettle_handle_additional_images', $product, $iz_product->online->presentation->additionalImageUrls);

                        }

                    }

                }

            } catch (IZ_Integration_API_Exception $e) {

                $e->write_to_logs();

            } catch (IZ_Integration_Exception $e) {

                $message = $e->getMessage();
                $this->logger(sprintf('sync_iz_products_process: %s when importing Zettle product', $message, $iz_product->uuid));
                IZ_Notice::add(sprintf('%s when importing Zettle product %s', $message, $iz_product->name), 'error');

            }

        }

        private function create_product_data($changed, &$product, $product_id, $iz_product, $new_product, $old_iz_product)
        {

            if (!$this->match_only && !$this->dry_run) {

                /**
                 * Set weight on WooCommerce product using 'izettle_import_weight'
                 */

                if ($description_type = get_option('izettle_import_weight')) {

                    if (isset($iz_product->online->shipping->weightInGrams)) {

                        $new_weight = WC_Zettle_Helper::weight_from_grams($iz_product->online->shipping->weightInGrams);
                        $current_weight = $product->get_weight('edit');

                        if ($current_weight != $new_weight) {
                            $product->set_weight($new_weight);
                            $changed = true;
                            $this->logger(sprintf('create_product_data (%s): Changing weight from %s to %s', $product_id, $current_weight, $new_weight));
                        }

                    }

                }

                /**
                 * Set description on WooCommerce product using 'izettle_webhook_description' and 'izettle_import_description'
                 */

                if ($description_type = get_option('izettle_import_description')) {

                    if (isset($iz_product->online->description)) {

                        $current_description = 'description' == $description_type ? $product->get_description('edit') : $product->get_short_description('edit');

                        if ($iz_product->online->description != $current_description) {

                            if ('description' == $description_type) {
                                $product->set_description($iz_product->online->description);
                            } else {
                                $product->set_short_description($iz_product->online->description);
                            }
                            $changed = true;

                            $this->logger(sprintf('create_product_data (%s): Changing %s from %s to %s', $product_id, $description_type, $current_description, $iz_product->online->description));

                        }
                    }
                }

                /**
                 * Import category from iZettle 'izettle_webhook_category' and 'izettle_import_category'
                 */

                if ('yes' == get_option('izettle_import_category')) {

                    $category_ids = $product->get_category_ids('edit');
                    $categories_changed = false;

                    $new_category_name = isset($iz_product->category) ? $iz_product->category->name : false;
                    $old_category_name = isset($old_iz_product->category) ? $old_iz_product->category->name : false;

                    if ($new_category_name && ($new_category_id = $this->maybe_add_category($product_id, $new_category_name)) && (!in_array($new_category_id, $category_ids))) {

                        $category_ids = array_merge($category_ids, array($new_category_id));
                        $categories_changed = true;
                        $this->logger(sprintf('create_product_data (%s): Product category "%s" added to product (category id:s %s)', $product_id, $new_category_name, implode(',', $category_ids)));

                    }

                    if ($old_category_name && ($new_category_name != $old_category_name) && ($old_category = get_term_by('name', $old_category_name, 'product_cat')) && in_array($old_category->term_id, $category_ids)) {

                        $categories_changed = true;
                        $category_ids = array_diff($category_ids, array($old_category->term_id));
                        $this->logger(sprintf('create_product_data (%s): Product category "%s" (%s) removed from product', $product_id, $old_category_name, $old_category->term_id));

                    }

                    $product->set_category_ids($category_ids);
                    $changed = true;

                }

                if ('yes' == get_option('izettle_import_unit_name')) {
                    $current_unit_name = $product->get_meta('izettle_unit_name_meta');
                    $new_unit_name = $iz_product->unitName;

                    if (empty($new_unit_name) || is_null($new_unit_name)) {
                        $new_unit_name = false;
                    }

                    if (!$new_unit_name && $current_unit_name) {
                        $this->logger(sprintf('create_product_data (%s): No unit name set - removing', $product_id));
                        $product->delete_meta_data('izettle_unit_name_meta');
                        $current_unit_name = $product->get_meta('izettle_unit_name_meta');
                    }

                    if ($new_unit_name != $current_unit_name) {

                        $new_unit_name_log = $new_unit_name ? $new_unit_name : 'false';
                        $current_unit_name_log = $current_unit_name ? $current_unit_name : 'false';

                        $this->logger(sprintf('create_product_data (%s): Changing unit name from %s to %s', $product_id, $current_unit_name_log, $new_unit_name_log));

                        $product->update_meta_data('izettle_unit_name_meta', $new_unit_name);

                        $changed = true;
                    }
                }

            }

            return $changed;

        }

        private function set_sku($changed, &$product, $new_sku)
        {

            $product_id = $product->get_id();
            $sku = $product->get_sku('edit');

            if ($new_sku != $sku) {

                if (!empty($new_sku)) {

                    $unique_sku = wc_product_generate_unique_sku($product_id, $new_sku);

                    $product->set_sku($unique_sku);
                    $changed = true;

                    if ($unique_sku != $new_sku) {
                        $this->logger(sprintf('set_sku (%s): SKU changed from "%s" to "%s" by creating unique SKU from "%s"', $product_id, $sku, $unique_sku, $new_sku));
                    } else {
                        $this->logger(sprintf('set_sku (%s): SKU changed from "%s" to "%s"', $product_id, $sku, $new_sku));
                    }

                } else {

                    $product->set_sku('');
                    $this->logger(sprintf('set_sku (%s): Clearing SKU', $product_id));
                    $changed = true;

                }

            }

            return $changed;
        }

        private function create_name($changed, &$product, $product_id, $iz_product, $new_product, $old_iz_product)
        {

            if (!$this->match_only && !$this->dry_run) {

                /**
                 * Set name on WooCommerce product/variation. Can be set on the product itself or in the "Special name field on the product/variation
                 */

                $current_name = $product->get_name('edit');

                if ('iz_name' == get_option('izettle_import_name')) {

                    $current_name = $product->get_meta('_izettle_product_name', true, 'edit');

                    if ($current_name != $iz_product->name) {

                        $product->update_meta_data('_izettle_product_name', $iz_product->name);
                        $changed = true;

                        $this->logger(sprintf('create_name (%s): Zettle name changed on product from %s to %s', $product_id, $current_name, $iz_product->name));
                    }

                } else if ($current_name != $iz_product->name) {

                    $product->set_name($iz_product->name);
                    $changed = true;

                    $this->logger(sprintf('create_name (%s): Product name changed from "%s" to "%s"', $product_id, $current_name, $iz_product->name));

                }

            }

            return $changed;

        }

        private function create_common_from_variant($changed, &$product, $product_id, $iz_product, $variant, $new_product, $old_iz_product)
        {

            if (!$this->match_only && !$this->dry_run) {

                /**
                 * Set Price on WooCommerce product/variation
                 */

                $price_option = get_option('izettle_import_price');

                if ('iz_price' == $price_option) {

                    $price = ($value = $product->get_meta('_izettle_special_price', true, 'edit')) ? $value : 0;
                    $new_price = WC_Zettle_Helper::maybe_remove_vat($variant, $iz_product->vatPercentage, 'price');

                    if ($price * 100 != $new_price) {

                        $new_price = $new_price / 100;

                        if (!$this->dry_run) {
                            $product->update_meta_data('_izettle_special_price', $new_price);
                            $changed = true;
                        }

                        $this->logger(sprintf('create_common_from_variant (%s): Changing Zettle price on product from "%s" to "%s"', $product_id, $price, $new_price));

                    }

                } elseif ('wc_price' == $price_option) {

                    $price = ($value = $product->get_regular_price('edit')) ? $value : 0;
                    $new_price = WC_Zettle_Helper::maybe_remove_vat($variant, $iz_product->vatPercentage, 'price');

                    if ($price * 100 != $new_price) {

                        $new_price = $new_price / 100;

                        if (!$this->dry_run) {
                            $product->set_regular_price($new_price);
                            $changed = true;
                        }

                        $this->logger(sprintf('create_common_from_variant (%s): Changing product price from %s to %s', $product_id, $price, $new_price));

                    }

                }

                /**
                 * Set cost price on WooCommerce product
                 */

                if ('yes' == get_option('izettle_import_cost_price')) {

                    $price = ($value = $product->get_meta('_izettle_cost_price', true, 'edit')) ? $value : 0;
                    $new_price = WC_Zettle_Helper::maybe_remove_vat($variant, $iz_product->vatPercentage);

                    if ($price * 100 != $new_price) {

                        $new_price = $new_price / 100;

                        if (!$this->dry_run) {
                            $product->update_meta_data('_izettle_cost_price', $new_price);
                            $changed = true;
                        }

                        $this->logger(sprintf('create_common_from_variant (%s): Zettle cost price changed on product from %s to %s', $product_id, $price, $new_price));

                    }

                }

                if (wc_tax_enabled()) {

                    $tax_class = '';
                    if (isset($iz_product->taxRates) && !empty($iz_product->taxRates)) {
                        $tax_rate = WC_Zettle_Helper::zettle_tax_rates(reset($iz_product->taxRates));
                        $tax_class = WC_Zettle_Helper::get_tax_class($tax_rate);
                    } elseif (isset($iz_product->vatPercentage)) {
                        $tax_class = WC_Zettle_Helper::get_tax_class($iz_product->vatPercentage);
                    }

                    $this->logger(sprintf('create_common_from_variant (%s): Setting tax class "%s" on product', $product_id, $tax_class));

                    if (!$this->dry_run) {
                        $product->set_tax_class($tax_class);
                        $changed = true;
                    }

                }

                /**
                 * Set barcode on WooCommerce product using 'izettle_webhook_barcode' and 'izettle_import_barcode'
                 */

                if ($barcode_meta = apply_filters('izettle_set_barcode_meta', get_option('izettle_import_barcode'))) {

                    $iz_barcode = isset($variant->barcode) ? $variant->barcode : '';

                    if ('sku' == $barcode_meta) {

                        if (!$this->dry_run && 'yes' != get_option('izettle_import_sku')) {
                            $changed = $this->set_sku($changed, $product, $iz_barcode);
                        }

                    } else {

                        /**
                         * Check if barcode creation is selected and update barcode if needed
                         */

                        $barcode = $product->get_meta($barcode_meta, true, 'edit');
                        if ($iz_barcode != $barcode) {
                            if (!$this->dry_run) {
                                $this->logger(sprintf('create_common_from_variant (%s): Barcode meta "%s" changed from "%s" to "%s"', $product_id, $barcode_meta, $barcode, $iz_barcode));
                                $product->update_meta_data($barcode_meta, $iz_barcode);
                                $changed = true;
                            }
                        }

                    }

                }

                /**
                 * Set SKU on WooCommerce product/variation (only applied if the SKU field is not used by the barcode) using 'izettle_import_sku' and 'izettle_webhook_sku'
                 */

                if (isset($variant->sku) && 'yes' == get_option('izettle_import_sku')) {

                    if (!$this->dry_run) {
                        $changed = $this->set_sku($changed, $product, $variant->sku);
                    }

                }

                if ('import' == $this->import_or_webhook || wc_string_to_bool(get_option('zettle_force_change_stocklevel_in_woocommerce'))) {
                    $changed = apply_filters('izettle_change_stocklevel_in_woocommerce', $changed, $product, $iz_product, $variant, $this->dry_run);
                }

            }

            if (!$this->dry_run) {

                if (($existing_uuid = $product->get_meta('woocommerce_izettle_variant_uuid', true, 'edit')) != $variant->uuid) {

                    $product->update_meta_data('woocommerce_izettle_variant_uuid', $variant->uuid);

                    if (!$existing_uuid) {
                        $this->logger(sprintf('create_common_from_variant (%s): Setting variant UUID metadata to %s', $product_id, $variant->uuid));
                    } else {
                        $this->logger(sprintf('create_common_from_variant (%s): Changing variant UUID metadata from %s to %s', $product_id, $existing_uuid, $variant->uuid));
                    }

                    $changed = true;

                }

                if (($existing_uuid = $product->get_meta('woocommerce_izettle_product_uuid', true, 'edit')) != $iz_product->uuid) {

                    $product->update_meta_data('woocommerce_izettle_product_uuid', $iz_product->uuid);

                    if (!$existing_uuid) {
                        $this->logger(sprintf('create_common_from_variant (%s): Setting product UUID metadata to %s', $product_id, $iz_product->uuid));
                    } else {
                        $this->logger(sprintf('create_common_from_variant (%s): Changing product UUID metadata from %s to %s', $product_id, $existing_uuid, $iz_product->uuid));
                    }

                    $changed = true;

                }

            }

            return $changed;
        }

        public function create_attribute($raw_name, $terms)
        {
            if (!$this->dry_run) {
                $attribute = new WC_Product_Attribute();
                $attribute->set_id(0);
                $attribute->set_name($raw_name);
                $attribute->set_position(1);
                $attribute->set_visible(false);
                $attribute->set_variation(true);
                $attribute->set_options($terms);
            } else {
                $attribute = false;
            }

            $this->logger(sprintf('Created %s as local attribute using %s as terms', $raw_name, implode(',', $terms)));

            return $attribute;

        }

        public function get_attribute_name($raw_name)
        {
            // Make sure caches are clean.
            delete_transient('wc_attribute_taxonomies');
            WC_Cache_Helper::invalidate_cache_group('woocommerce-attributes');

            // These are exported as labels, so convert the label to a name if possible first.
            $attribute_labels = wp_list_pluck(wc_get_attribute_taxonomies(), 'attribute_label', 'attribute_name');
            $attribute_name = array_search($raw_name, $attribute_labels, true);

            if (!$attribute_name) {
                $attribute_name = wc_sanitize_taxonomy_name($raw_name);
            }

            return $attribute_name;
        }

        public function create_global_attribute($raw_name, $terms)
        {
            global $wpdb;

            $attribute_name = $this->get_attribute_name($raw_name);

            if ($attribute_taxonomy_id = wc_attribute_taxonomy_id_by_name($attribute_name)) {
                $attribute = wc_get_attribute($attribute_taxonomy_id);
            } elseif (!$this->dry_run) {
                $create_attribute_response = wc_create_attribute(
                    array(
                        'name' => $raw_name,
                        'slug' => $attribute_name,
                        'type' => 'select',
                        'order_by' => 'menu_order',
                        'has_archives' => 0,
                    )
                );

                if (is_wp_error($create_attribute_response)) {
                    throw new IZ_Integration_Exception($create_attribute_response->get_error_message());
                } else {
                    $attribute = wc_get_attribute($create_attribute_response);
                }

                $taxonomy_name = wc_attribute_taxonomy_name($attribute_name);

                if (!taxonomy_exists($taxonomy_name)) {
                    register_taxonomy(
                        $taxonomy_name,
                        apply_filters('woocommerce_taxonomy_objects_' . $taxonomy_name, array('product')),
                        apply_filters(
                            'woocommerce_taxonomy_args_' . $taxonomy_name,
                            array(
                                'labels' => array(
                                    'name' => $raw_name,
                                ),
                                'hierarchical' => false,
                                'show_ui' => false,
                                'query_var' => true,
                                'rewrite' => false,
                            )
                        )
                    );
                }
            } else {
                $attribute = false;
            }

            if ($attribute) {
                foreach ($terms as $term) {
                    if (!term_exists($term, $attribute->slug)) {
                        if (!$this->dry_run) {
                            wp_insert_term($term, $attribute->slug);
                        }
                    }
                }
            }

            if (!$this->dry_run) {
                $return_attribute = new WC_Product_Attribute();
                $return_attribute->set_id($attribute->id);
                $return_attribute->set_name($attribute->slug);
                $return_attribute->set_visible(false);
                $return_attribute->set_variation(true);
                $return_attribute->set_options($terms);
                return $return_attribute;
            } else {
                return false;
            }

        }

        public function get_image_id_from_url($url)
        {

            try {
                return WC_Zettle_Helper::get_post_id_by_metadata($url, 'attachment', '_source_url', 1, 'inherit');
            } catch (IZ_Integration_Exception $e) {
                return null;
            }

        }

        public function is_image_on_product($product, $new_image_id)
        {
            return $new_image_id ? $new_image_id == $product->get_image_id() : $new_image_id;
        }

        public function get_product_image($product, $url)
        {

            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';
            require_once ABSPATH . 'wp-admin/includes/image.php';

            $product_id = $product->get_id();

            if ($url) {

                $image_id = $this->get_image_id_from_url($url);

                if (null === $image_id) {

                    $image_id = media_sideload_image($url, 0, null, 'id');

                    if (is_wp_error($image_id)) {
                        WC_IZ()->logger->add(sprintf('get_product_image (%s): Could not upload image "%s", error: %s', $product_id, $url, $image_id->get_error_message()));
                        return false;
                    }

                    WC_IZ()->logger->add(sprintf('get_product_image (%s): Image "%s" successfully uploaded from Zettle with id %s', $product_id, $url, $image_id));

                } else {

                    WC_IZ()->logger->add(sprintf('get_product_image (%s): Image "%s" was already uploaded from Zettle with id %s', $product_id, $url, $image_id));

                }
            }

            return $image_id;

        }

        public function handle_images_action($product_id, $url, $image_id)
        {

            do_action('bjorntech_remove_product_update_actions');

            if (!$this->dry_run) {

                $product = wc_get_product($product_id);

                if ((false != $image_id || (false !== ($image_id = $this->get_product_image($product, $url)))) && (!$this->is_image_on_product($product, $image_id))) {

                    $product->set_image_id($image_id);
                    $product->update_meta_data('_izettle_image_lookup_key', $url);
                    $product->save();
                    $this->logger(sprintf('handle_images_action (%s): Adding main image %s with id %s', $product->get_id(), $url, $image_id));

                }

            }

        }

        public function handle_images($product, $url)
        {

            $image_id = $this->get_image_id_from_url($url);

            if (!WC_Zettle_Helper::is_image_in_meta($product, $url) && (!$image_id || !$this->is_image_on_product($product, $image_id))) {
                as_schedule_single_action(as_get_datetime_object(), 'izettle_handle_images_action', array($product->get_id(), $url, $image_id), 'izettle-iz-images');
            }

        }

        public function handle_variant_images_action($product_id, $url, $image_id)
        {

            do_action('bjorntech_remove_product_update_actions');

            if (!$this->dry_run) {

                $product = wc_get_product($product_id);

                if (($image_id || ($image_id = $this->get_product_image($product, $url))) && (!$this->is_image_on_product($product, $image_id))) {
                    $product->set_image_id($image_id);
                    $product->update_meta_data('_izettle_variation_image_url', $url);
                    $product->save();
                    $this->logger(sprintf('handle_variant_images_action (%s): Adding variant image %s with id %s', $product->get_id(), $url, $image_id));
                }

            }

        }

        public function handle_variant_images($product, $url)
        {

            $image_id = $this->get_image_id_from_url($url);

            if (!$image_id || !$this->is_image_on_product($product, $image_id)) {
                $this->logger(sprintf('handle_variant_images (%s): Queuing variant adding of image %s', $product->get_id(), $url));
                as_schedule_single_action(as_get_datetime_object(), 'izettle_handle_variant_images_action', array($product->get_id(), $url, $image_id), 'izettle-iz-images');
            }

        }

        public function handle_additional_images_action($product_id, $additional_images)
        {

            do_action('bjorntech_remove_product_update_actions');

            $product = wc_get_product($product_id);

            $gallery_image_ids = $product->get_gallery_image_ids('edit');

            if (!$this->dry_run) {

                foreach ($additional_images as $additional_image) {

                    $image_id = $this->get_product_image($product, $additional_image);

                    if ($image_id && !in_array($image_id, $gallery_image_ids)) {
                        array_push($gallery_image_ids, $image_id);
                        $product->set_gallery_image_ids($gallery_image_ids);
                        $product->add_meta_data('_izettle_image_lookup_key', $additional_image);
                        $product->save();
                        $this->logger(sprintf('handle_additional_images_action (%s): Setting additional image %s on product to image id %s', $product_id, $additional_images));
                    } elseif ($image_id) {
                        $this->logger(sprintf('handle_additional_images_action (%s): Image %s with id %s already present as additional image', $product_id, $additional_images, $image_id));
                    }

                }

            }

        }

        public function handle_additional_images($product, $additional_images)
        {

            $gallery_image_ids = $product->get_gallery_image_ids('edit');

            $images_to_process = array();

            foreach ($additional_images as $additional_image) {

                $image_to_process = $this->maybe_add_jpeg($additional_image);

                $image_id = $this->get_image_id_from_url($image_to_process);

                if (!$image_id || !in_array($image_id, $gallery_image_ids)) {
                    array_push($images_to_process, $image_to_process);
                }

            }

            if (!empty($images_to_process)) {
                as_schedule_single_action(as_get_datetime_object(), 'izettle_handle_additional_images_action', array($product->get_id(), $images_to_process), 'izettle-iz-images');
            }

        }

        public function is_syncable($sync_product, $iz_product){

            $iz_category_obj = is_null($iz_product->category) ? false : $iz_product->category;

            $iz_category = false;
            $iz_category_name = false;

            if ($iz_category_obj) {
                $iz_category = $iz_category_obj->uuid;
                $iz_category_name = $iz_category_obj->name;

            }

            if (($included_categories = get_option('izettle_products_import_include_categories', []))) {
                if (!in_array($iz_category,$included_categories)){
                    $this->logger(sprintf('is_syncable (%s): Category "%s" (%s) is not included in list "%s" - skipping', $iz_product->uuid, ($iz_category_name ? $iz_category_name : 'false'), ($iz_category ? $iz_category : 'false'), implode(',',$included_categories)));
                    $sync_product = false;
                }
            }


            if (($excluded_categories = get_option('izettle_products_import_exclude_categories', []))) {
                if (in_array($iz_category,$excluded_categories)){
                    $this->logger(sprintf('is_syncable (%s): Category "%s" (%s) is part of exclusion list "%s" - skipping', $iz_product->uuid, ($iz_category_name ? $iz_category_name : 'false'), ($iz_category ? $iz_category : 'false'), implode(',',$excluded_categories)));
                    $sync_product = false;
                }
            }

            return $sync_product;

        }

    }

    new Woo_iZettle_Integration_Products_From_iZettle_Handler();
}
