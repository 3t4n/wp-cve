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

if (!class_exists('WC_iZettle_Integration_Product_Handler', false)) {

    class WC_iZettle_Integration_Product_Handler
    {

        private $location;
        private $sync_model;

        public function __construct()
        {

            add_action('wciz_sync_wc_products_action', array($this, 'sync_wc_products_action'), 50, 1);
            add_filter('wciz_sync_wc_products_filter', array($this, 'sync_wc_products_filter'), 50, 2);
            add_action('wciz_sync_wc_products_process', array($this, 'sync_wc_products_process'));

            add_action('wc_after_products_starting_sales', array($this, 'update_products_when_sales_price_is_changed'));
            add_action('wc_after_products_ending_sales', array($this, 'update_products_when_sales_price_is_changed'));

            add_action('zettle_clean_orphans', array($this, 'clean_orphans'));
            add_action('zettle_clear_meta_data', array($this, 'clear_meta_data'));

            add_action('wciz_remove_product_data', array($this, 'remove_product_data'));
            add_action('wciz_remove_product_data_final', array($this, 'remove_product_data_final'));

            /**
             * Queue actions
             */

            add_action('izettle_add_product_to_queue', array($this, 'add_product_to_queue'));

            /**
             * WooCommerce filters and actions
             */
            add_filter('woocommerce_duplicate_product_exclude_meta', array($this, 'duplicate_product_exclude_meta'), 10, 2);
            add_action('woocommerce_after_product_object_save', array($this, 'after_product_object_save'), 100, 2);
            add_action('woocommerce_before_product_object_save', array($this, 'before_product_object_save'), 100, 2);


            /**
             * Wordpress actions
             */

            add_action('wp_trash_post', array($this, 'wp_trash_post'));
            add_action('untrashed_post', array($this, 'untrashed_post'));
            add_action('delete_post', array($this, 'delete_post'));
            add_action("update_post_meta", array($this, 'check_if_product_image_changed'), 10, 4);
            add_action("delete_post_meta", array($this, 'check_if_product_image_deleted'), 10, 4);
            add_action("future_to_publish", array($this, 'maybe_publish_delayed_product'), 10, 1);

            /**
             * Actions to control product updates
             */
            add_action('bjorntech_remove_product_update_actions', array($this, 'remove_product_update_actions'));
            add_action('izettle_remove_product_update_actions', array($this, 'remove_product_update_actions'));
            add_action('izettle_add_product_update_actions', array($this, 'add_product_update_actions'));

            /**
             * Use hooks if realtime updates and init if not
             */

            do_action('izettle_add_product_update_actions');

            /**
             * Polylang filters
             */

            add_filter('pll_copy_post_metas', array($this, 'polylang_copy_post_metas'), 100, 5);
            add_filter('pllwc_copy_post_metas', array($this, 'polylang_copy_post_metas'), 100, 5);

        }

        public function add_product_update_actions()
        {

            if ($this->sync_model = get_option('izettle_product_sync_model')) {

                if (('yes' == get_option('izettle_product_sync_model_force_daily')) && (1 == $this->sync_model)) {

                    add_action('woocommerce_update_product', array($this, 'woocommerce_update_product'), 500, 2);
                    add_action('woocommerce_new_product', array($this, 'wc_product_was_created'), 500, 2);
                    add_action('init', array($this, 'sync_wc_products_init'), 99);

                } elseif (1 == $this->sync_model) {

                    add_action('woocommerce_update_product', array($this, 'woocommerce_update_product'), 500, 2);
                    add_action('woocommerce_new_product', array($this, 'wc_product_was_created'), 500, 2);

                } elseif ($this->sync_model) {

                    add_action('init', array($this, 'sync_wc_products_init'), 99);

                }
            }

        }

        public function before_product_object_save($product,$data_store){

            if (!$product->meta_exists('woocommerce_izettle_product_uuid')){
                WC_IZ()->logger->add(sprintf('before_product_object_save: Meta data not found on product - skipping'));
                return $product;
            }
    
            if(!$product->get_id()){
                WC_IZ()->logger->add(sprintf('before_product_object_save: Product not found - cleaning meta data'));
                $this->clean_zettle_metadata($product);
            }

            return $product;
        }

        public function after_product_object_save($product,$data_store){

            if('yes' == get_option('zettle_save_post_on_order')){
                $id = $product->get_id();
                $post = get_post($id);

                WC_IZ()->logger->add(sprintf('after_product_object_save (%s): Product triggered save_post action', $id));
    
                do_action('save_post',$id,$post,true);
            }
        }

        public function remove_product_update_actions()
        {
            remove_action('woocommerce_update_product', array($this, 'woocommerce_update_product'), 500);
            remove_action('woocommerce_new_product', array($this, 'wc_product_was_created'), 500);
        }

        public function get_name($product, $existing_product)
        {
            $product_id = $product->get_id();

            $name = '';
            if ($alternate_name = $product->get_meta('_izettle_product_name', true, 'edit')) {
                $name = $alternate_name;
            } else {
                $name = $product->get_name('edit');
                if ('yes' == get_option('izettle_add_sku_to_name') && ($sku = $product->get_sku())) {
                    if (wc_string_to_bool(get_option('izettle_put_sku_first'))) {
                        $name = $sku . ' - ' . $name;
                    } else {
                        $name .= ' - ' . $sku;
                    }
                }
            }

            if ($existing_product) {
                if ($name !== $existing_product->name) {
                    WC_IZ()->logger->add(sprintf('get_name (%s): Product name changed from "%s" to "%s"', $product_id, $existing_product->name, $name));
                }
            } else {
                WC_IZ()->logger->add(sprintf('get_name (%s): Product name set to "%s"', $product_id, $name));
            }

            return substr($name, 0, 64);
        }

        public function is_using_any($attribute, $variants, $any_array)
        {
            foreach ($variants as $variant) {
                if (array_key_exists('options', $variant)) {
                    foreach ($variant['options'] as $option) {
                        if ($attribute == $option['name'] && in_array($option['value'], $any_array)) {
                            return true;
                        }
                    }
                }
            }
            return false;
        }

        public function handle_unit_name($product_id)
        {

            if (class_exists('Woo_Advanced_Qty')) {

                if (!empty($post_setting = get_post_meta($product_id, '_advanced-qty-quantity-suffix', true))) {
                    WC_IZ()->logger->add(sprintf('handle_unit_name (s%): Product has %s set as advanced quantity', $product_id, $post_setting));
                    return $post_setting;
                }

                $terms = get_the_terms($product_id, 'product_cat');

                $term_setting = '';

                if (!empty($terms)) {

                    foreach ($terms as $term) {
                        $term_option = get_option('product-category-advanced-qty-quantity-suffix-' . $term->term_id);
                        if (!empty($term_option) && $term_option != 'global-input') {
                            $term_setting = $term_option;
                        }
                    }
                    if (!empty($term_setting)) {
                        WC_IZ()->logger->add(sprintf('handle_unit_name (%s): Product category %s has %s set as advanced quantity', $product_id, $term->term_id, $term_setting));
                        return $term_setting;
                    }

                }

                if (!empty($shop_setting = get_option('woo-advanced-qty-quantity-suffix'))) {
                    WC_IZ()->logger->add(sprintf('Global settings has %s set as advanced quantity', $shop_setting));
                    return $shop_setting;
                }

                WC_IZ()->logger->add(sprintf('handle_unit_name (%s): No settings found for advanced quantity', $product_id));

            }

            return false;

        }

        public function clean_izettle_meta($product_id)
        {
            delete_post_meta($product_id, 'woocommerce_izettle_product_uuid');
            delete_post_meta($product_id, 'woocommerce_izettle_variant_uuid');
            delete_post_meta($product_id, '_zettle_product_cat_id');
            delete_post_meta($product_id, '_izettle_updated');
            delete_post_meta($product_id, '_izettle_product_etag');
            delete_post_meta($product_id, '_izettle_image_lookup_key');
            delete_post_meta($product_id, '_izettle_image');
            delete_post_meta($product_id, '_izettle_variation_image_url');
            WC_IZ()->logger->add(sprintf('Data removed from product: %s', $product_id));

        }

        public function remove_product_data($product_id)
        {

            $product = wc_get_product($product_id);

            WC_IZ()->logger->add(sprintf('Cleaning product: %s', $product_id));
            $this->clean_izettle_meta($product_id);

            if ($product->is_type('variable')) {

                $variations = array();

                if (class_exists('WC_iZettle_Helper', false)) {
                    $variations = WC_iZettle_Helper::get_all_variations($product);
                } else if (class_exists('WC_Zettle_Helper', false)) {
                    $variations = WC_Zettle_Helper::get_all_variations($product);
                }

                foreach ($variations as $variation) {

                    if (!is_object($variation)) {
                        $variation = wc_get_product($variation['variation_id']);
                    }

                    $variation_id = $variation->get_id();

                    WC_IZ()->logger->add(sprintf('Cleaning variation: %s', $variation_id));
                    $this->clean_izettle_meta($variation_id);

                }

            }

        }

        public function remove_product_data_final()
        {
            WC_IZ()->logger->add(sprintf('remove_product_data_final: Ready'));
        }

        public function izettle_product_array($product, $variants, $image_lookup_keys, $uuid, $categories, $has_ok_number_of_variations, $existing_product)
        {
            $product_id = $product->get_id();

            $options = array();

            if ('variable' == $product->get_type() && $has_ok_number_of_variations) {

                $definitions = array();

                $any_array = WC_Zettle_Helper::get_any_array();

                foreach ($product->get_attributes('edit') as $attribute) {

                    $properties = array();
                    $property_value = array();

                    if ($attribute->get_variation()) {

                        if ($attribute->is_taxonomy()) {

                            foreach ($attribute->get_terms() as $option) {

                                $value = substr($option->name, 0, 256);

                                $properties[] = array(
                                    "value" => $value,
                                );
                                array_push($property_value, $value);

                            }

                            $attribute_name = substr(wc_get_attribute(wc_attribute_taxonomy_id_by_name($attribute->get_name()))->name, 0, 30);

                        } else {

                            foreach ($attribute->get_options() as $option) {

                                $value = substr($option, 0, 256);

                                $properties[] = array(
                                    "value" => $value,
                                );
                                array_push($property_value, $value);

                            }

                            $attribute_name = substr($attribute->get_name(), 0, 30);

                        }

                        if (empty(array_intersect($any_array, $property_value)) && $this->is_using_any($attribute_name, $variants, $any_array)) {
                            $properties[] = array(
                                "value" => WC_Zettle_Helper::get_any_text(),
                            );
                        }

                        $definitions[] = array(
                            "name" => $attribute_name,
                            "properties" => $properties,
                        );

                    }

                }

                if (count($definitions) > 0) {
                    $options = array(
                        "variantOptionDefinitions" => array(
                            "definitions" => $definitions,
                        ),
                    );
                }
            }

            $iz_product = array(
                "name" => $this->get_name($product, $existing_product),
                "categories" => $categories,
                "imageLookupKeys" => $image_lookup_keys,
                "variants" => $variants,
                "uuid" => $uuid,
                "externalReference" => $this->create_external_reference($product, isset($existing_product->externalReference) ? $existing_product->externalReference : ''),
            );

            if (false !== ($handle_unit_name = apply_filters('izettle_unit_name', $this->handle_unit_name($product_id), $product_id))) {
                $iz_product["unitName"] = $handle_unit_name;
            }

            if ('yes' == get_option('izettle_update_category_on_izproduct')) {
                $iz_product["category"] = $this->get_category($product, $existing_product);
            }

            if ('yes' == get_option('izettle_online_sales_active')) {

                $iz_product["online"] = array(
                    "status" => "ACTIVE",
                );

                $description_type = get_option('izettle_online_sales_description');
                if ($description_type && ($description_text = ($description_type == 'description') ? $product->get_description() : $product->get_short_description())) {

                    $description = array(
                        "description" => substr($description_text, 0, 3500),
                    );

                    if (array_key_exists("online", $iz_product)) {
                        $iz_product["online"] = array_merge($iz_product["online"], $description);
                    } else {
                        $iz_product["online"] = $description;
                    }
                }

            }

            $tax_rate = WC_Zettle_Helper::get_tax_rate($product, $existing_product);
            if (false !== $tax_rate) {
                if (false === WC_Zettle_Helper::get_tax_settings()) {
                    $iz_product["vatPercentage"] = $tax_rate;
                } elseif (wc_tax_enabled() && ($tax_rate_id = WC_Zettle_Helper::zettle_tax_id($tax_rate))) {
                    $iz_product["taxRates"] = [$tax_rate_id];
                }
            }

            if (!empty($options)) {
                return array_merge($iz_product, $options);
            } else {
                return $iz_product;
            }

        }

        public function get_currency()
        {

            $currency = get_woocommerce_currency();

            if (class_exists('WC_Aelia_CurrencySwitcher')) {
                $currency = WC_Aelia_CurrencySwitcher::instance()->base_currency();
                WC_IZ()->logger->add(sprintf('Using %s as currency from Aelia base currency', $currency));
            }

            if (class_exists('WOOMULTI_CURRENCY_F_Data')) {
                $currency = WOOMULTI_CURRENCY_F_Data::get_ins()->get_default_currency();
                WC_IZ()->logger->add(sprintf('Using %s as currency from Curcy default currency', $currency));
            }

            if (function_exists('wcml_get_woocommerce_currency_option')) {
                $currency = wcml_get_woocommerce_currency_option();
                WC_IZ()->logger->add(sprintf('Using %s as currency from WPML currency option', $currency));
            }

            $preferred_pricelist = trim(get_option('izettle_product_pricelist'));
            if (strstr($preferred_pricelist, 'wcpbc_')) {
                $preferred_pricelist = trim($preferred_pricelist, 'wcpbc_');
                if ($pricing_zone = WCPBC_Pricing_Zones::get_zone_by_id($preferred_pricelist)) {
                    $currency = $pricing_zone->get_currency();
                    WC_IZ()->logger->add(sprintf('Using %s as currency from wcpbc pricing zone %s', $currency, $pricing_zone->get_name()));
                }
            }

            if (get_option('izettle_force_product_currency')) {
                $currency = get_option('izettle_force_product_currency');
                WC_IZ()->logger->add(sprintf('Using %s as currency from Zettle product currency option', $currency));
            }

            return apply_filters('izettle_wc_product_currency', $currency);

        }

        public function get_cost_price($product_id, $tax_rate)
        {

            if ('yes' == get_option('izettle_product_cost_price', 'yes')) {

                $price = 0;

                if ($meta_price = get_post_meta($product_id, '_izettle_cost_price', true)) {

                    $price = str_replace(',', '.', $meta_price);



                    if ($price && wc_tax_enabled() && 'yes' != get_option('woocommerce_prices_include_tax')) {
                        if (false !== $tax_rate) {
                            $tax_multiplier = 1 + ($tax_rate / 100);
                            $price = $price * $tax_multiplier;
                        }
                    }

                }

            } else {

                $price = false;

            }

            return $price;
        }

        public function get_price($product, $tax_rate)
        {

            $product_id = $product->get_id();

            if (!is_numeric($price = $product->get_regular_price('edit'))) {
                $price = false;
            }

            $preferred_pricelist = get_option('izettle_product_pricelist');

            // use Zettle special price
            if ($preferred_pricelist == '_special' && ($meta_price = $product->get_meta('_izettle_special_price', true, 'edit'))) {

                $price = str_replace(',', '.', $meta_price);
                WC_IZ()->logger->add(sprintf('get_price (%s): Using special price %s', $product_id, $price));

            } else {

                // Do not set the price
                if ('_no_price' == $preferred_pricelist) {

                    $price = false;

                } else {

                    // Use Zettle if available - sale price if not - regular price if not
                    if ('_sale_special' == $preferred_pricelist) {
                        if (($meta_price = $product->get_meta('_izettle_special_price', true, 'edit'))) {
                            $price = str_replace(',', '.', $meta_price);
                            WC_IZ()->logger->add(sprintf('get_price (%s): Using special price %s', $product_id, $price));
                        } elseif ($product->is_on_sale('edit')) {
                            $sale_price = $product->get_sale_price('edit');
                            WC_IZ()->logger->add(sprintf('get_price (%s): Using sale price %s instead of price %s', $product_id, $sale_price, $price));
                            $price = $sale_price;
                        }
                    }

                    // Use the sale price if it is avaliable
                    elseif ('_sale' == $preferred_pricelist) {

                        if ($product->is_on_sale('edit')) {
                            $sale_price = $product->get_sale_price('edit');
                            WC_IZ()->logger->add(sprintf('get_price (%s): Using sale price %s instead of price %s', $product_id, $sale_price, $price));
                            $price = $sale_price;
                        }

                        // Use Fortnox price if avaliable
                    } elseif (strstr($preferred_pricelist, 'fortnox_')) {

                        $preferred_pricelist = trim($preferred_pricelist, 'fortnox_');
                        try {
                            $fortnox_price = apply_filters('fortnox_get_price', $price, $product, $preferred_pricelist);
                            if ($fortnox_price && $fortnox_price != $price) {
                                WC_IZ()->logger->add(sprintf('get_price (%s): Using Fortnox price %s from pricelist %s instead of price %s', $product_id, $fortnox_price, $preferred_pricelist, $price));
                                $price = $fortnox_price;
                            }
                        } catch (Fortnox_API_Exception $e) {
                            if (404 != $e->getCode()) {
                                throw new $e($e->getMessage(), $e->getCode(), $e);
                            }
                            WC_IZ()->logger->add(sprintf('get_price (%s): Fortnox pricelist %s does not exist, price not changed', $product_id, $preferred_pricelist));
                        }

                        // Use price from wcpbc if avaliable
                    } elseif (strstr($preferred_pricelist, 'wcpbc_')) {

                        $preferred_pricelist = trim($preferred_pricelist, 'wcpbc_');
                        if ($pricing_zone = WCPBC_Pricing_Zones::get_zone_by_id($preferred_pricelist)) {
                            $price = $pricing_zone->get_post_price($product_id, '_regular_price');
                            WC_IZ()->logger->add(sprintf('get_price (%s): Using %s as price from wcpbc pricing zone %s', $product_id, $price, $pricing_zone->get_name()));
                        }

                    }

                }

            }

            $price = apply_filters('izettle_wc_product_price_before_tax_calculation', $price, $product, $tax_rate);

            if ($price && wc_tax_enabled() && 'yes' != get_option('woocommerce_prices_include_tax')) {

                if (false !== $tax_rate) {
                    $tax_multiplier = 1 + ($tax_rate / 100);
                    $price = $price * $tax_multiplier;
                }

            }

            return apply_filters('izettle_wc_product_price_after_tax_calculation', $price, $product, $tax_rate);

        }

        private function format_variant_price($price, $currency)
        {
            return array(
                "amount" => round($price * 100),
                "currencyId" => $currency,
            );
        }

        private function create_sku($sku, $product)
        {

            $new_sku = $product->get_sku('edit');

            if ($new_sku != $sku) {
                WC_IZ()->logger->add(sprintf('create_sku (%s): SKU changed from "%s" to "%s"', $product->get_id(), $sku, $new_sku));
                $sku = $new_sku;
            }

            if (strlen($sku) > 64) {
                throw new IZ_Integration_Exception(sprintf('SKU %s longer than 64 bytes', $sku));
            }

            return $sku;

        }

        public function izettle_variant_array($product, $existing_variant = null)
        {

            if ($existing_variant) {
                $uuid = $existing_variant->uuid;
                $barcode = $existing_variant->barcode;
            } else {
                $product_uuid = $product->get_meta('woocommerce_izettle_variant_uuid', true, 'edit');
                $uuid = $product_uuid ? $product_uuid : false;
                $barcode = null;
            }

            $product_sku = $product->get_sku('edit');
            $product_id = $product->get_id();

            $name_array = array();

            if (($parent_id = $product->get_parent_id()) && ($parent = wc_get_product($parent_id)) && !$parent->is_type('grouped')) {

                $attribute_values = $product->get_attributes('edit');

                $product_attributes = array();
                $options = array();

                foreach ($parent->get_attributes('edit') as $attribute) {

                    if (!$attribute->get_variation()) {
                        continue;
                    }

                    $selected_value = isset($attribute_values[sanitize_title($attribute->get_name())]) ? $attribute_values[sanitize_title($attribute->get_name())] : '';

                    $found = false;
                    $value = '';

                    if ($attribute->is_taxonomy()) {

                        $attribute_name = substr(wc_get_attribute(wc_attribute_taxonomy_id_by_name($attribute->get_name()))->name, 0, 30);

                        foreach ($attribute->get_terms() as $option) {

                            if (sanitize_title($option->slug) === sanitize_title($selected_value)) {
                                $value = substr($option->name, 0, 256);
                                $options[] = array(
                                    "name" => $attribute_name,
                                    "value" => $value,
                                );
                                $found = true;
                                break;
                            }
                        }

                    } else {

                        $attribute_name = substr($attribute->get_name(), 0, 30);

                        foreach ($attribute->get_options() as $option) {
                            if (sanitize_title($option) === sanitize_title($selected_value)) {
                                $value = substr($option, 0, 256);
                                $options[] = array(
                                    "name" => $attribute_name,
                                    "value" => $value,
                                );
                                $found = true;
                                break;
                            }
                        }

                    }

                    if (!$found) {

                        $options[] = array(
                            "name" => $attribute_name,
                            "value" => WC_Zettle_Helper::get_any_text(),
                        );

                    }

                    $product_attributes[] = $value;

                }

                $name_array = array(
                    "name" => implode(", ", $product_attributes),
                    "options" => $options,
                );

            }

            $tax_rate = WC_Zettle_Helper::get_tax_rate($product, $existing_variant);

            if ($uuid === false) {

                $uuid = IZ_UUID::generate(IZ_UUID::UUID_TIME, IZ_UUID::FMT_STRING, WC_iZettle_Integration::UUID_NODE_ID);
                WC_IZ()->logger->add(sprintf('izettle_variant_array (%s): Creating variation with UUID %s', $product_id, $uuid));

            }

            $variant = array(
                "uuid" => $uuid,
                "barcode" => apply_filters('izettle_process_barcode', $barcode, $product_id),
                "presentation" => array(
                    "imageUrl" => apply_filters('izettle_get_image', null, $product_id),
                    "backgroundColor" => isset($existing_variant->presentation) && $existing_variant->presentation->backgroundColor ? $existing_variant->presentation->backgroundColor : null,
                    "textColor" => isset($existing_variant->presentation) && $existing_variant->presentation->textColor ? $existing_variant->presentation->textColor : null,
                ),
            );

            if (wc_string_to_bool(get_option('zettle_update_sku', 'yes'))) {
                $variant["sku"] = $this->create_sku(isset($existing_variant->sku) ? $existing_variant->sku : '', $product);
            }

            $currency = $this->get_currency();

            $existing_currency = isset($existing_variant->price) ? $existing_variant->price->currencyId : '';

            if (wc_string_to_bool(get_option('izettle_use_new_us_tax_settings'))) {
                WC_IZ()->logger->add(sprintf('izettle_variant_array (%s): Using new tax settings', $product_id));
                if (false !== $tax_rate) {
                    if (false === WC_Zettle_Helper::get_tax_settings()) {
                        $variant["vatPercentage"] = $tax_rate;
                    } elseif (wc_tax_enabled() && ($tax_rate_id = WC_Zettle_Helper::zettle_tax_id($tax_rate))) {
                        $tax_rate = false;
                    }
                }
            }

            $existing_price = isset($existing_variant->price) ? $existing_variant->price->amount / 100 : 0;
            $price = $this->get_price($product, $tax_rate);
            if ((false !== $price && $existing_price != $price) || $existing_currency != $currency) {
                WC_IZ()->logger->add(sprintf('izettle_variant_array (%s): Price was changed from %s to %s', $product_id, $existing_price, $price));
                $variant["price"] = $this->format_variant_price($price, $currency);
            }

            $existing_cost_currency = isset($existing_variant->costPrice) ? $existing_variant->costPrice->currencyId : '';

            $existing_cost_price = isset($existing_variant->costPrice) ? $existing_variant->costPrice->amount / 100 : 0;
            $cost_price = $this->get_cost_price($product_id, $tax_rate);
            if ((false !== $cost_price && $existing_cost_price != $cost_price) || $existing_cost_currency != $currency) {
                WC_IZ()->logger->add(sprintf('izettle_variant_array (%s): Cost price was changed from %s %s to %s %s', $product_id, $existing_cost_currency, $existing_cost_price, $currency, $cost_price));
                $variant["costPrice"] = $this->format_variant_price($cost_price, $currency);
            }
            if (!wc_string_to_bool(get_option('izettle_use_new_us_tax_settings'))) {
                WC_IZ()->logger->add(sprintf('izettle_variant_array (%s): Using old tax settings', $product_id));
                if (false !== $tax_rate) {
                    if (false === WC_Zettle_Helper::get_tax_settings()) {
                        $variant["vatPercentage"] = $tax_rate;
                    }
                }
            }

            $variant = array_merge($variant, $name_array);

            return $variant;
        }

        public function is_product_changed($new_product, $old_product)
        {
            return WC_Zettle_Helper::object_diff((object) $new_product, $old_product);
        }

        public function get_izettle_product($product_uuid, $product_id)
        {

            $product = WC_Zettle_Helper::get_wc_product_by_uuid($product_uuid);

            if ($product) {

                if ($product_id == $product->get_id()) {

                    return izettle_api()->get_products($product_uuid);

                } else {

                    WC_IZ()->logger->add(sprintf('get_izettle_product (%s): Alternate product id %s found for UUID %s', $product_id, $product->get_id(), $product_uuid));
                    throw new IZ_Integration_Exception(sprintf('Mismatch, product id %s found when searching for product id %s for UUID %s', $product->get_id(), $product_id, $product_uuid), ZETTLE_DUPLICATE_POST_ERROR);

                }

            } else if (($product = wc_get_product($product_id)) && ($new_product_uuid = IZ_UUID_V2::get_uuid_from_wc_product($product)) && $product_uuid == $new_product_uuid) {

                return izettle_api()->get_products($product_uuid);

            } else {

                WC_IZ()->logger->add(sprintf('get_izettle_product (%s): No syncable product found for UUID %s', $product_id, $product_uuid));

            }

            return null;
        }

        public function get_current_product($product)
        {

            $product_id = $product->get_id();
            $product_uuid = $product->get_meta('woocommerce_izettle_product_uuid', true, 'edit');

            try {

                if ($product_uuid && ($izettle_product = $this->get_izettle_product($product_uuid, $product_id))) {
                    WC_IZ()->logger->add(sprintf('get_current_product (%s): Existing product with UUID %s found in Zettle', $product_id, $izettle_product->uuid));
                    return $izettle_product;
                }

                $izettle_product = $this->external_reference_match_product($product);
                if ($izettle_product && !($existing_product = WC_Zettle_Helper::get_wc_product_by_uuid($izettle_product->uuid))) {
                    WC_IZ()->logger->add(sprintf('get_current_product (%s): Existing product with UUID %s found in by externalReference in Zettle', $product_id, $izettle_product->uuid));
                    return $izettle_product;
                }

                if (!$product_uuid && $product_id && ($new_product_uuid = IZ_UUID_V2::get_uuid_from_wc_product($product)) && ($izettle_product = $this->get_izettle_product($new_product_uuid, $product_id))) {
                    WC_IZ()->logger->add(sprintf('get_current_product (%s): Existing product with UUID (version 2) %s found in Zettle', $product_id, $izettle_product->uuid));
                    return $izettle_product;
                }

                WC_IZ()->logger->add(sprintf('get_current_product (%s): No UUID found on product', $product_id));

            } catch (IZ_Integration_API_Exception $e) {
                if (404 == $e->getCode()) {
                    WC_IZ()->logger->add(sprintf('get_current_product (%s): Zettle UUID was found on product but not found in Zettle', $product_id));
                } else {
                    throw new IZ_Integration_API_Exception($e);
                }
            }

            return null;

        }

        public function external_reference_match_product($wc_product)
        {

            if ('yes' == get_option('izettle_do_not_match_external_reference')) {
                return null;
            }

            $all_products = izettle_api()->get_products();

            $sku = $wc_product->get_sku();
            $product_id = $wc_product->get_id();

            foreach ($all_products as $iz_product) {

                if ($iz_product->externalReference) {

                    if ($iz_product->externalReference == 'ID:' . $product_id) {
                        return $iz_product;
                    }

                    if ($sku && $iz_product->externalReference == $sku) {
                        return $iz_product;
                    }

                    $sku_legacy = $sku != '' ? 'SKU:' . $sku : $sku;
                    if ($sku_legacy && $iz_product->externalReference == $sku_legacy) {
                        return $iz_product;
                    }

                }

            }

            return null;

        }

        public function maybe_set_product_uuid($return, &$product, $new_product_uuid)
        {

            if (($product_uuid = $product->get_meta('woocommerce_izettle_product_uuid', true, 'edit')) != $new_product_uuid) {
                $product->update_meta_data('woocommerce_izettle_product_uuid', $new_product_uuid);
                if (!empty($product_uuid)) {
                    WC_IZ()->logger->add(sprintf('maybe_set_product_uuid (%s): Updating product uuid meta from %s to %s', $product->get_id(), $product_uuid, $new_product_uuid));
                } else {
                    WC_IZ()->logger->add(sprintf('maybe_set_product_uuid (%s): Setting product uuid meta to %s', $product->get_id(), $new_product_uuid));
                }
                return true;
            }

            return $return;
        }

        public function maybe_set_variant_uuid($return, &$variation, $new_variant_uuid)
        {

            if (($variant_uuid = $variation->get_meta('woocommerce_izettle_variant_uuid', true, 'edit')) != $new_variant_uuid) {
                $variation->update_meta_data('woocommerce_izettle_variant_uuid', $new_variant_uuid);
                if (!empty($variant_uuid)) {
                    WC_IZ()->logger->add(sprintf('maybe_set_variant_uuid (%s): Updating variant uuid meta from %s to %s', $variation->get_id(), $variant_uuid, $new_variant_uuid));
                } else {
                    WC_IZ()->logger->add(sprintf('maybe_set_variant_uuid (%s): Setting variant uuid meta to %s', $variation->get_id(), $new_variant_uuid));
                }
                return true;
            }

            return $return;

        }

        public function sync_wc_products_process($task_object)
        {

            if (class_exists('WOOCS')) {
                global $WOOCS;
                $WOOCS->set_currency('');
            }

            $task_object = is_object($task_object) ? $task_object : json_decode(json_encode($task_object, JSON_INVALID_UTF8_IGNORE));

            $product_id = $task_object->product_id;
            $sync_all = isset($task_object->sync_all) ? $task_object->sync_all : false;
            $new_product = isset($task_object->new_product) ? $task_object->new_product : false;

            if ($product_id && apply_filters('izettle_is_client_allowed_to_sync', false, $sync_all)) {

                WC_IZ()->logger->add(sprintf('sync_wc_products_process (%s): %s product in a %s sync', $product_id, $new_product ? 'Creating' : 'Updating', $sync_all ? 'full' : 'single'));

                do_action('bjorntech_remove_product_update_actions');

                try {

                    $this->changed = false;

                    $product = wc_get_product($product_id);

                    if (!WC_Zettle_Helper::is_syncable($product)) {
                        $this->maybe_delete_izettle_product($product_id);
                        return;
                    }

                    if (!$new_product) {
                        $existing_product = $this->get_current_product($product);
                    } else {
                        $existing_product = null;
                    }

                    $maxvariations = ($configured_variations = intval(get_option('izettle_number_of_variations', 99))) > 99 ? 99 : $configured_variations;

                    $categoryUUIDs = array();
                    $has_ok_number_of_variations = true;
                    $variations = array();
                    $iz_product = array();
                    $product_type = $product->get_type();

                    $sync_in_stock_only = wc_string_to_bool(get_option('izettle_sync_in_stock_only'));

                    if ('variable' == $product_type) {

                        if ($sync_in_stock_only) {
                            $variations = $product->get_available_variations();
                        } else {
                            $variations = WC_Zettle_Helper::get_all_variations($product);
                        }

                        $number_of_variants = count($variations);

                        if ($number_of_variants > $maxvariations) {
                            $has_ok_number_of_variations = false;
                            WC_IZ()->logger->add(sprintf('sync_wc_products_process (%s): WooCommerce variable product has %s variations treating it as simple product', $product_id, $number_of_variants));
                        }

                        if ($number_of_variants == 0) {
                            WC_IZ()->logger->add(sprintf('sync_wc_products_process (%s): Can not sync WooCommerce variable product with 0 variations', $product_id));
                            $this->maybe_delete_izettle_product($product_id);
                            return;
                        }

                    }

                    if ('variable' == $product_type && $has_ok_number_of_variations) {

                        $image_lookup_keys = apply_filters('izettle_get_image_keys', $existing_product === null ? array() : $existing_product->imageLookupKeys, $product, $sync_all);

                        if ($existing_product === null) {

                            $variants = array();
                            WC_IZ()->logger->add(sprintf('sync_wc_products_process (%s): Creating new Zettle product from WooCommerce variable product', $product_id));
                            $product_uuid = IZ_UUID::generate(IZ_UUID::UUID_TIME, IZ_UUID::FMT_STRING, WC_iZettle_Integration::UUID_NODE_ID);

                            foreach ($variations as $variation) {

                                $changed_variation = false;

                                if (!is_object($variation)) {
                                    $variation = wc_get_product($variation['variation_id']);
                                }
                                $variation_id = $variation->get_id();

                                if ($variation->get_meta('_izettle_nosync', true, 'edit') == 'yes') {

                                    WC_IZ()->logger->add(sprintf('add_or_update_izettle_product (%s): Variation %s marked not to sync to Zettle', $product_id, $variation_id));

                                } elseif ($sync_in_stock_only && !$variation->is_in_stock('edit')) {

                                    WC_IZ()->logger->add(sprintf('add_or_update_izettle_product (%s): Variation %s had no stock and will not sync', $product_id, $variation_id));

                                } else {

                                    $variant_array = $this->izettle_variant_array(
                                        $variation
                                    );
                                    $variants[] = $variant_array;

                                    $changed_variation = $this->maybe_set_product_uuid($changed_variation, $variation, $product_uuid);
                                    $changed_variation = $this->maybe_set_variant_uuid($changed_variation, $variation, $variant_array['uuid']);
                                }

                                if (true === $changed_variation) {
                                    $variation->save();
                                    WC_IZ()->logger->add(sprintf('sync_wc_products_process (%s): Created variation %s with UUID %s', $product_id, $variation_id, $variant_array['uuid']));
                                }

                            }

                            $this->changed = $this->maybe_set_product_uuid($this->changed, $product, $product_uuid);

                            $iz_product = $this->izettle_product_array(
                                $product,
                                $variants,
                                $image_lookup_keys,
                                $product_uuid,
                                $categoryUUIDs,
                                $has_ok_number_of_variations,
                                null
                            );

                            WC_IZ()->logger->add(sprintf('sync_wc_products_process (%s): Create variable "%s"', $product_id, json_encode($iz_product, JSON_INVALID_UTF8_IGNORE)));

                            $product_response = izettle_api()->create_product(
                                $iz_product
                            );

                            $product->update_meta_data('_izettle_updated', time());
                            $this->changed = true;

                        } else {

                            WC_IZ()->logger->add(sprintf('sync_wc_products_process (%s): Updating variable product from product', $product_id));

                            $variants = array();

                            foreach ($variations as $variation) {

                                $changed_variation = false;

                                if (!is_object($variation)) {
                                    $variation = wc_get_product($variation['variation_id']);
                                }
                                $variation_id = $variation->get_id();

                                if ($variation->get_meta('_izettle_nosync', true, 'edit') == 'yes') {

                                    WC_IZ()->logger->add(sprintf('add_or_update_izettle_product (%s): Variation %s marked not to sync to Zettle', $product_id, $variation_id));

                                } elseif ($sync_in_stock_only && !$variation->is_in_stock('edit')) {

                                    WC_IZ()->logger->add(sprintf('add_or_update_izettle_product (%s): Variation %s had no stock and will not sync', $product_id, $variation_id));

                                } else {

                                    $found = false;

                                    foreach ($existing_product->variants as $iz_variant_variation) {
                                        if (WC_Zettle_Helper::match_product_variations($iz_variant_variation, $variation_id)) {
                                            $found = true;
                                            $variant_array = $this->izettle_variant_array(
                                                $variation,
                                                $iz_variant_variation
                                            );
                                            break;
                                        }
                                    }

                                    if ($found === false) {

                                        WC_IZ()->logger->add(sprintf('sync_wc_products_process (%s): Product not found as existing Zettle product variant', $variation_id));
                                        $variant_array = $this->izettle_variant_array(
                                            $variation
                                        );

                                    }

                                    $variants[] = $variant_array;
                                    $changed_variation = $this->maybe_set_product_uuid($changed_variation, $variation, $existing_product->uuid);
                                    $changed_variation = $this->maybe_set_variant_uuid($changed_variation, $variation, $variant_array['uuid']);

                                }

                                if (true === $changed_variation) {
                                    $variation->save();
                                    WC_IZ()->logger->add(sprintf('sync_wc_products_process (%s): Updated variation %s with UUID %s', $product_id, $variation->get_id(), $variant_array['uuid']));
                                }

                            }

                            $this->changed = $this->maybe_set_product_uuid($this->changed, $product, $existing_product->uuid);

                            $iz_product = $this->izettle_product_array(
                                $product,
                                $variants,
                                $image_lookup_keys,
                                $existing_product->uuid,
                                $categoryUUIDs,
                                $has_ok_number_of_variations,
                                $existing_product
                            );

                            if ($this->is_product_changed($iz_product, $existing_product)) {

                                WC_IZ()->logger->add(sprintf('sync_wc_products_process (%s): Update variable "%s"', $product_id, json_encode($iz_product, JSON_INVALID_UTF8_IGNORE)));

                                $product_response = izettle_api()->update_product(
                                    $iz_product,
                                    $existing_product->uuid,
                                    $existing_product->etag
                                );

                                if (isset($product_response->etag)) {
                                    $product->update_meta_data('_izettle_product_etag', $product_response->etag);
                                    $product->update_meta_data('_izettle_updated', time());
                                    $this->changed = true;
                                    WC_IZ()->logger->add(sprintf('sync_wc_products_process (%s): Updating etag %s', $product_id, $product_response->etag));
                                }

                            } else {

                                WC_IZ()->logger->add(sprintf('sync_wc_products_process (%s): No data changed compared to the existing Zettle product', $product_id));

                            }

                        }

                    } elseif (in_array($product_type, get_option('izettle_products_include', array('simple', 'variable')))) {

                        $image_lookup_keys = apply_filters('izettle_get_image_keys', $existing_product === null ? array() : $existing_product->imageLookupKeys, $product, $sync_all);

                        if (null === $existing_product) {

                            WC_IZ()->logger->add(sprintf('sync_wc_products_process (%s): Creating new Zettle product from WooCommerce %s product', $product_id, $product_type));
                            $product_uuid = IZ_UUID::generate(IZ_UUID::UUID_TIME, IZ_UUID::FMT_STRING, WC_iZettle_Integration::UUID_NODE_ID);

                            $variant_array = $this->izettle_variant_array(
                                $product
                            );

                            $iz_product = $this->izettle_product_array(
                                $product,
                                array($variant_array),
                                $image_lookup_keys,
                                $product_uuid,
                                $categoryUUIDs,
                                $has_ok_number_of_variations,
                                null
                            );

                            $this->changed = $this->maybe_set_product_uuid($this->changed, $product, $product_uuid);
                            $this->changed = $this->maybe_set_variant_uuid($this->changed, $product, $variant_array['uuid']);

                            WC_IZ()->logger->add(sprintf('sync_wc_products_process (%s): Create simple "%s"', $product_id, json_encode($iz_product, JSON_INVALID_UTF8_IGNORE)));

                            $product_response = izettle_api()->create_product(
                                $iz_product
                            );

                            $product->update_meta_data('_izettle_updated', time());
                            $this->changed = true;

                        } else {

                            WC_IZ()->logger->add(sprintf('sync_wc_products_process (%s): Updating simple product', $product_id));

                            $variant_array = $this->izettle_variant_array(
                                $product,
                                $existing_product->variants[0]
                            );

                            $iz_product = $this->izettle_product_array(
                                $product,
                                array($variant_array),
                                $image_lookup_keys,
                                $existing_product->uuid,
                                $categoryUUIDs,
                                $has_ok_number_of_variations,
                                $existing_product
                            );

                            $this->changed = $this->maybe_set_product_uuid($this->changed, $product, $iz_product['uuid']);
                            $this->changed = $this->maybe_set_variant_uuid($this->changed, $product, $variant_array['uuid']);

                            if ($this->is_product_changed($iz_product, $existing_product)) {

                                WC_IZ()->logger->add(sprintf('sync_wc_products_process (%s): Update simple "%s"', $product_id, json_encode($iz_product, JSON_INVALID_UTF8_IGNORE)));

                                $product_response = izettle_api()->update_product(
                                    $iz_product,
                                    $existing_product->uuid,
                                    $existing_product->etag
                                );

                                if (isset($product_response->etag)) {
                                    $product->update_meta_data('_izettle_product_etag', $product_response->etag);
                                    $product->update_meta_data('_izettle_updated', time());
                                    $this->changed = true;
                                    WC_IZ()->logger->add(sprintf('sync_wc_products_process (%s): Updating etag %s', $product_id, $product_response->etag));
                                }

                            } else {

                                WC_IZ()->logger->add(sprintf('sync_wc_products_process (%s): No data changed compared to the existing Zettle product', $product_id));

                            }

                        }

                    }

                    if (true === $this->changed) {
                        $product->save();
                        WC_IZ()->logger->add(sprintf('sync_wc_products_process (%s): Saved product', $product_id, $iz_product['uuid']));
                    }

                    if ('variable' == $product_type && $has_ok_number_of_variations) {

                        foreach ($variations as $variation) {

                            if (!is_object($variation)) {
                                $variation = wc_get_product($variation['variation_id']);
                            }

                            if ($variation->get_manage_stock('view')) {
                                if ('yes' != $variation->get_meta('_izettle_nosync', true, 'edit')) {
                                    do_action('izettle_update_stocklevel_in_izettle', $variation->get_id(), $sync_all, false);
                                }
                            }

                        }

                    } elseif (in_array($product_type, get_option('izettle_products_include', array('simple', 'variable')))) {

                        if ($product->get_manage_stock('view')) {
                            if ('yes' != $product->get_meta('_izettle_nosync', true, 'edit')) {
                                do_action('izettle_update_stocklevel_in_izettle', $product->get_id(), $sync_all, false);
                            }
                        }

                    }

                    $product->update_meta_data('_izettle_updated', time());
                    $product->save();

                } catch (IZ_Integration_API_Exception $e) {
                    $message = $e->getMessage();
                    $e->write_to_logs();

                    $zettle_error_prefix = '[Zettle error]';
                    
                    if (strncmp($message, $zettle_error_prefix, strlen($zettle_error_prefix)) === 0) {
                        IZ_Notice::add(sprintf('%s when creating/updating Zettle product from WooCommerce product %s', $message, $product_id), 'error');
                    } else {
                        IZ_Notice::add(sprintf('Communication error with Zettle when creating/updating Zettle product from WooCommerce product %s. Please try to sync the product to Zettle again by clicking Update in the WooCommerce product view.', $product_id), 'error');
                    }

                } catch (IZ_Integration_Exception $e) {
                    $message = $e->getMessage();
                    WC_IZ()->logger->add(sprintf('sync_wc_products_process (%s): "%s" when creating Zettle product', $product_id, $message));
                    IZ_Notice::add(sprintf('%s when creating Zettle product from WooCommerce product %s', $message, $product_id), 'error');
                }

            }

        }

        public function process_product_wpml($original_product_id)
        {

            if ($wpml_default_language = get_option('zettle_wpml_default_language', apply_filters('wpml_default_language', null))) {

                $product_id = apply_filters('wpml_object_id', $original_product_id, 'product', false, $wpml_default_language);

                if (!$product_id) {
                    WC_IZ()->logger->add(sprintf('process_product_wpml (%s): No product found for language "%s"', $original_product_id, $wpml_default_language));
                    return null;
                }

                if ($original_product_id != $product_id) {

                    $language = WC_Zettle_Helper::product_language($original_product_id, $wpml_default_language);

                    if ($product_id) {
                        WC_IZ()->logger->add(sprintf('process_product_wpml (%s): Product has language "%s", the default language "%s" copy is %s', $original_product_id, $language, $wpml_default_language, $product_id));
                    } else {
                        WC_IZ()->logger->add(sprintf('process_product_wpml (%s): Product has language "%s", no default language "%s" "copy exists', $original_product_id, $language, $wpml_default_language));
                    }

                    return $product_id;
                }

            }

            return $original_product_id;

        }

        public function wc_product_was_created($product_id, $product = null)
        {

            $wpml_product_id = $this->process_product_wpml($product_id);

            if ($wpml_product_id && $wpml_product_id == $product_id) {

                WC_IZ()->logger->add(sprintf('wc_product_was_created (%s): Product was created', $product_id));

                do_action('izettle_add_product_to_queue', (object) array(
                    'product_id' => $product_id,
                    'sync_all' => false,
                    'new_product' => true,
                ));

            } else {

                WC_IZ()->logger->add(sprintf('wc_product_was_created (%s): Product update received from WooCommerce but product is a language copy to %s', $product_id, $wpml_product_id));

            }

        }

        public function woocommerce_update_product($product_id, $product = null)
        {

            $wpml_product_id = $this->process_product_wpml($product_id);

            if ($wpml_product_id && $wpml_product_id == $product_id) {

                WC_IZ()->logger->add(sprintf('woocommerce_update_product (%s): Product update received from WooCommerce', $product_id));

                do_action('izettle_add_product_to_queue', (object) array(
                    'product_id' => $product_id,
                    'sync_all' => false,
                ));

            } else {

                WC_IZ()->logger->add(sprintf('woocommerce_update_product (%s): Product update received from WooCommerce but product is a language copy to %s', $product_id, $wpml_product_id));

            }

        }

        public function clean_orphans()
        {

            try {

                $all_products = izettle_api()->get_products();

                WC_IZ()->logger->add(sprintf('clean_orphans: Got %s products from Zettle', count($all_products)));

                $cleaned = 0;

                foreach ($all_products as $iz_product) {

                    try {

                        $new_product_id = $this->process_product_wpml(WC_Zettle_Helper::get_post_id_by_metadata($iz_product->uuid, 'product', 'woocommerce_izettle_product_uuid', 1));
                        if (!$new_product_id) {

                            $cleaned++;
                            WC_IZ()->logger->add(sprintf('clean_orphans: Zettle UUID %s not found in WooCommerce.', $iz_product->uuid));
                            $this->delete_izettle_product($iz_product->uuid, $new_product_id);

                        } else {

                            $product = wc_get_product($new_product_id);

                            if ($product && !WC_Zettle_Helper::is_syncable($product)) {

                                $cleaned++;
                                WC_IZ()->logger->add(sprintf('clean_orphans: Product %s set to not sync, deleting in Zettle', $new_product_id));
                                $this->delete_izettle_product($iz_product->uuid, $product->get_id());

                            }

                        }

                    } catch (IZ_Integration_Exception $e) {

                        WC_IZ()->logger->add($e->getMessage());

                        $product_ids = WC_Zettle_Helper::get_post_id_by_metadata($iz_product->uuid, 'product', 'woocommerce_izettle_product_uuid');

                        foreach ($product_ids as $new_product_id) {

                            $wpml_product = $this->process_product_wpml($new_product_id);

                            if ($wpml_product == $new_product_id) {

                                WC_IZ()->logger->add(sprintf('Product %s was the product for the default language', $new_product_id));

                            } else {

                                WC_IZ()->logger->add(sprintf('Product %s is a language duplicate for product for %s, UUID will be cleaned', $new_product_id, $wpml_product));
                                $this->clean_product_meta($new_product_id);

                            }
                        }

                    }

                }

                WC_IZ()->logger->add(sprintf('clean_orphans: Cleaned %s products from Zettle', $cleaned));

            } catch (IZ_Integration_API_Exception $e) {

                $e->write_to_logs();

            }

        }

        private function zettle_metas()
        {

            return array(
                'woocommerce_izettle_variant_uuid',
                'woocommerce_izettle_product_uuid',
                '_izettle_barcode',
                '_izettle_updated',
                '_izettle_product_etag',
                '_izettle_image_lookup_key',
                '_izettle_image',
                '_izettle_nosync',
                '_izettle_cost_price',
                '_izettle_special_price',
                '_izettle_product_name',
                '_izettle_variation_image_url',
            );

        }

        public function clean_product_meta(&$product)
        {   
            if(!is_object($product)){
                $product = wc_get_product($product);
            }

            $product->delete_meta_data('woocommerce_izettle_product_uuid');
            $product->delete_meta_data('woocommerce_izettle_variant_uuid');
            //cat
            $product->delete_meta_data('_zettle_product_cat_id');
            $product->delete_meta_data('_izettle_updated');
            $product->delete_meta_data('_izettle_product_etag');
            $product->delete_meta_data('_izettle_image_lookup_key');
            $product->delete_meta_data('_izettle_image');
            $product->delete_meta_data('_izettle_variation_image_url');

            WC_IZ()->logger->add(sprintf('clean_product_meta (%s): Metadata cleaned', $product->get_id() ? $product->get_id() : 'NEW'));
        }

        public function duplicate_product_exclude_meta($meta_to_exclude, $existing_meta = array())
        {

            return array_merge($meta_to_exclude, $this->zettle_metas());

        }

        public function polylang_copy_post_metas($metas, $sync, $from, $to, $lang)
        {

            foreach ($this->zettle_metas() as $zettle_meta) {
                $key = array_search($zettle_meta, $metas);
                if ($key) {
                    unset($metas[$key]);
                }
            }

            return $metas;

        }

        public function check_if_product_image_deleted($meta_id, $object_id, $meta_key, $meta_value)
        {

            if ('_thumbnail_id' === $meta_key) {

                $post_type = get_post_type($object_id);

                if ('product' === $post_type) {
                    WC_IZ()->logger->add(sprintf('check_if_product_image_deleted (%s): Deleting product main image info', $object_id));
                    delete_post_meta($object_id, '_izettle_image_lookup_key');
                }

                if ('product_variation' === $post_type) {
                    WC_IZ()->logger->add(sprintf('check_if_product_image_deleted (%s): Deleting product variation image info', $object_id));
                    delete_post_meta($object_id, '_izettle_image');
                }

            }

        }

        public function maybe_update_categories()
        {

            $raw_categories = get_site_transient('izettle_categories');
            if (!is_array($raw_categories)) {
                WC_IZ()->logger->add('maybe_update_categories: Fetching categories from Zettle');
                $raw_categories = izettle_api()->get_categories()->categories;
                set_site_transient('izettle_categories', $raw_categories, DAY_IN_SECONDS);
            } else {
                WC_IZ()->logger->add('maybe_update_categories: Using cached categories');
            }

            $iz_categories = array_map(function ($category) {
                return $category->name;
            }, $raw_categories);

            $wc_categories = get_terms(
                array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => true,
                    'fields' => 'names',
                )
            );



            $wc_categories = array_unique($wc_categories);

            $categories = array_diff($wc_categories, $iz_categories);

            if (!empty($categories)) {

                $new_categories = array();
                foreach ($categories as $category) {
                    if ($category != _x('Uncategorized', 'Default category slug', 'woocommerce')) {
                        $new_categories[] = array(
                            "uuid" => IZ_UUID::generate(IZ_UUID::UUID_TIME, IZ_UUID::FMT_STRING, WC_iZettle_Integration::UUID_NODE_ID),
                            "name" => $category,
                        );
                        delete_site_transient('izettle_categories');
                    }
                }

                WC_IZ()->logger->add(sprintf('maybe_update_categories: Creating %s new categories in Zettle - %s', count($new_categories), json_encode($new_categories)));

                izettle_api()->create_category(
                    array(
                        "categories" => $new_categories,
                    )
                );

                $raw_categories = array_merge($raw_categories, $new_categories);

            }

            return $raw_categories;

        }

        /**
         * Get the category from the product
         *
         * @param WC_Product $product
         * @param array $iz_product
         * @return array|null
         */
        public function get_category($product, $iz_product)
        {
            WC_IZ()->logger->add(sprintf('get_category (%s): Getting category for product', $product->get_id()));
            $turn_off_product_zettle_categories = wc_string_to_bool(get_option('izettle_turn_off_product_zettle_categories'));
            $category_id = $product->get_meta('_zettle_product_cat_id', true, 'edit');
            if (!$category_id || $turn_off_product_zettle_categories) {
                if ($turn_off_product_zettle_categories) {
                    WC_IZ()->logger->add(sprintf('get_category (%s): Turn off product Zettle categories is on', $product->get_id()));
                }

                WC_IZ()->logger->add(sprintf('get_category (%s): No category ID found in product - fetching', $product->get_id()));
                $category_ids = $product->get_category_ids('edit');
                if (empty($category_ids)) {
                    WC_IZ()->logger->add(sprintf('get_category (%s): No category IDs found in product', $product->get_id()));
                    return null;
                }
                WC_IZ()->logger->add(sprintf('get_category (%s): Found category IDs in product - %s', $product->get_id(), json_encode($category_ids)));

                $category_id = reset($category_ids);

                WC_IZ()->logger->add(sprintf('get_category (%s): Using category ID in product - %s', $product->get_id(), $category_id));
            } else {
                WC_IZ()->logger->add(sprintf('get_category (%s): Existing category ID %s found in product', $product->get_id(), $category_id));
            }

            $existing_term = get_term_by('id', $category_id, 'product_cat');

            if (!$existing_term && $product->meta_exists('_zettle_product_cat_id', 'edit')) {
                WC_IZ()->logger->add(sprintf('get_category (%s): No existing term found for category ID %s - rerunning method', $product->get_id(), $category_id));
                $product->delete_meta_data('_zettle_product_cat_id');
                return $this->get_category($product, $iz_product);
            }

            if (!$existing_term) {
                WC_IZ()->logger->add(sprintf('get_category (%s): No existing term found for category ID %s', $product->get_id(), $category_id));
                return null;
            }

            if ($existing_term->slug === 'uncategorized') {
                WC_IZ()->logger->add(sprintf('get_category (%s): Existing term found for category ID %s is uncategorized', $product->get_id(), $category_id));
                return null;
            }

            WC_IZ()->logger->add(sprintf('get_category (%s): Existing term found for category ID %s is %s', $product->get_id(), $category_id, $existing_term->name));

            $categories = $this->maybe_update_categories();

            //I want to log the categories but only the name
            $log_categories = array_map(function ($category) {
                return $category->name;
            }, $categories);
            
            foreach ($categories as $category) {
                if (($category->name == $existing_term->name)) {
                    WC_IZ()->logger->add(sprintf('get_category (%s): Found matching category "%s" for category ID %s', $product->get_id(), $category->name ,$category_id));
                    return array(
                        "uuid" => $category->uuid,
                        "name" => $category->name,
                    );
                }
            }

            WC_IZ()->logger->add(sprintf('get_category (%s): No matching category found for category ID %s', $product->get_id(), $category_id));

            return null;

        }

        public function check_if_product_image_changed($meta_id, $object_id, $meta_key, $meta_value)
        {

            if ('_thumbnail_id' === $meta_key) {

                $prev_value = get_post_meta($object_id, '_thumbnail_id', true);

                if ($prev_value != $meta_value) {

                    $post_type = get_post_type($object_id);

                    if ('product' === $post_type) {
                        WC_IZ()->logger->add(sprintf('check_if_product_image_changed (%s): Deleting product main image info', $object_id));
                        delete_post_meta($object_id, '_izettle_image_lookup_key');
                    }

                    if ('product_variation' === $post_type) {
                        WC_IZ()->logger->add(sprintf('check_if_product_image_changed (%s): Deleting product variation image info', $object_id));
                        delete_post_meta($object_id, '_izettle_image');
                    }

                }

            }

        }

        public function clean_zettle_metadata(&$product)
        {
            if(!is_object($product)){
                $product = wc_get_product($product);
            }

            if ($product->get_children() && !$product->is_type('grouped')) {

                $variations = WC_Zettle_Helper::get_all_variations($product);
                foreach ($variations as $variation) {

                    if (!is_object($variation)) {
                        $variation = wc_get_product($variation['variation_id']);
                    }

                    $this->clean_product_meta($variation->get_id());
                }

            }

            $this->clean_product_meta($product);
        }

        public function maybe_delete_izettle_product($product_id, $clean_meta = false)
        {

            if (wc_string_to_bool(get_option('izettle_delete_izettle_products')) && ($product_uuid = get_post_meta($product_id, 'woocommerce_izettle_product_uuid', true))) {

                if (apply_filters('izettle_is_client_allowed_to_sync', false, false)) {
                    $this->delete_izettle_product($product_uuid, $product_id);
                }
                $clean_meta = true;

            }

            if ($clean_meta) {

                $this->clean_zettle_metadata($product_id);

            }

        }

        public function wp_trash_post($id)
        {

            if (!$id) {
                return;
            }

            $post_type = get_post_type($id);

            if ('product' === $post_type) {
                WC_IZ()->logger->add(sprintf('wp_trash_post (%s): Product was trashed in WooCommerce', $id));
                $this->maybe_delete_izettle_product($id, true);
            }

        }

        public function delete_post($id)
        {
            if (!$id) {
                return;
            }

            $post_type = get_post_type($id);

            if ('product' === $post_type) {
                WC_IZ()->logger->add(sprintf('delete_post (%s): Product was deleted in WooCommerce', $id));
                $this->maybe_delete_izettle_product($id, true);
            }
        }

        public function untrashed_post($id)
        {
            if (!$id) {
                return;
            }

            $post_type = get_post_type($id);

            if ('product' === $post_type) {
                WC_IZ()->logger->add(sprintf('untrashed_post (%s): Product was untrashed in WooCommerce', $id));
                $this->wc_product_was_created($id);
            }
        }

        public function maybe_publish_delayed_product($post)
        {

            if (!$post) {
                return;
            }

            if ('product' === $post->post_type) {
                WC_IZ()->logger->add(sprintf('maybe_publish_delayed_product (%s): Delayed publishing done', $post->ID));
                $this->wc_product_was_created($post->ID);
            }

        }

        public function delete_izettle_product($uuid, $product_id = 0)
        {
            try {
                izettle_api()->delete_product($uuid);
                WC_IZ()->logger->add(sprintf('delete_izettle_product (%s): Zettle product with UUID "%s" was deleted from Zettle', $product_id, $uuid));
            } catch (IZ_Integration_API_Exception $e) {
                WC_IZ()->logger->add(sprintf('delete_izettle_product (%s): Zettle product with UUID "%s" was not found when trying to delete.', $product_id, $uuid));
            }
        }

        /**
         * Create external reference for Zettle products
         *
         * @since 6.0.0
         *
         * @param object $product WooCommerce product being synced
         * @param string $existing_external_reference Existing externalReference in the Zettle product
         *
         * @return string New external reference for the Zettle product
         */
        public function create_external_reference($product, $existing_external_reference)
        {

            $product_id = $product->get_id();

            $sku = wc_string_to_bool(get_option('zettle_sku_as_externalreference', 'yes')) ? $product->get_sku('edit') : false;

            $external_reference = $sku ? $sku : 'ID:' . $product_id;

            if ($external_reference != $existing_external_reference) {
                WC_IZ()->logger->add(sprintf('create_external_reference (%s): Changed external reference from "%s" to "%s"', $product_id, $existing_external_reference, $external_reference));
            }

            return $external_reference;

        }

        public function add_product_to_queue($task_object)
        {

            $task_object = is_object($task_object) ? $task_object : json_decode(json_encode($task_object, JSON_INVALID_UTF8_IGNORE));

            if (is_admin() && 'yes' == get_option('izettle_do_not_queue_admin_updates')) {

                do_action('wciz_sync_wc_products_process', $task_object);

            } else {

                $scheduled_actions = as_get_scheduled_actions(
                    array(
                        'hook' => 'wciz_sync_wc_products_process',
                        'args' => array($task_object),
                        'status' => ActionScheduler_Store::STATUS_PENDING,
                        'claimed' => false,
                    ),
                    'ids'
                );

                if (empty($scheduled_actions)) {

                    WC_IZ()->logger->add(sprintf('add_product_to_queue (%s): Adding product to queue', $task_object->product_id));
                    as_schedule_single_action(as_get_datetime_object(), 'wciz_sync_wc_products_process', array($task_object), !empty($task_object->product_id) ? 'wciz_sync_wc_products' : '');

                } else {

                    WC_IZ()->logger->add(sprintf('add_product_to_queue (%s): Product already in queue', $task_object->product_id));

                }

            }

        }

        public function sync_wc_products_filter($number_synced, $sync_all = false)
        {
            return $this->sync_wc_products_action($sync_all);
        }

        public function sync_wc_products_init()
        {
            $microtime = microtime(true);
            if (!($transient = (float) get_site_transient('izettle_last_product_sync')) || ($transient < $microtime)) {
                set_site_transient('izettle_last_product_sync', MINUTE_IN_SECONDS + $microtime);

                $sync_model = $this->sync_model;

                if (('yes' == get_option('izettle_product_sync_model_force_daily')) && $sync_model == 1) {
                    $sync_model = 1440;
                }

                if (apply_filters('izettle_is_it_time_to_check_sync', false, 'izettle_last_product_sync', $sync_model, false, $microtime)) {
                    $action = $this->sync_wc_products_action(false);
                }
            }
        }

        public function sync_wc_products_action($sync_all)
        {
            $args = array(
                'limit' => -1,
                'return' => 'ids',
                'type' => get_option('izettle_products_include', array('simple', 'variable')),
                'status' => ($product_status = get_option('izettle_product_status', array('draft', 'pending', 'private', 'publish'))) ? $product_status : array('draft', 'pending', 'private', 'publish'),
                'category' => empty($categories = get_option('izettle_product_categories', array())) ? array() : $categories,
                'stock_status' => 'yes' == get_option('izettle_sync_in_stock_only') ? 'instock' : '',
            );

            $this_sync_time = gmdate('U');

            if (!$sync_all) {
                if (($last_sync_done = get_site_transient('izettle_upgraded_sync_from'))) {
                    delete_site_transient('izettle_upgraded_sync_from');
                } else {
                    $last_sync_done = get_option('izettle_last_product_sync_done', $this_sync_time);
                }
                if ($last_sync_done) {
                    $args['date_modified'] = $last_sync_done . '...' . $this_sync_time;
                }
                update_option('izettle_last_product_sync_done', $this_sync_time);
            }

            $wpml_default_language = get_option('zettle_wpml_default_language', apply_filters('wpml_default_language', null));

            if ($wpml_default_language) {
                $args['suppress_filters'] = true;
                WC_IZ()->logger->add(sprintf('WMPL or Polylang detected, using products with language code %s when syncing products', $wpml_default_language));
            }

            WC_IZ()->logger->add(sprintf('sync_wc_products: Using product args %s', json_encode($args, JSON_INVALID_UTF8_IGNORE), true));

            $products_ids = wc_get_products($args);

            WC_IZ()->logger->add(sprintf('sync_wc_products: Get product id:s %s', json_encode($products_ids, JSON_INVALID_UTF8_IGNORE), true));

            $total_to_sync = count($products_ids);

            $products_added = array();

            if ($total_to_sync > 0) {

                WC_IZ()->logger->add(sprintf('sync_wc_products: Got %d products from WooCommerce', $total_to_sync));

                foreach ($products_ids as $original_product_id) {

                    if ('yes' != get_post_meta($original_product_id, '_izettle_nosync', true)) {

                        if ($wpml_default_language) {

                            $product_id = apply_filters('wpml_object_id', $original_product_id, 'product', false, $wpml_default_language);

                            if ($product_id && !in_array($product_id, $products_added)) {

                                do_action('izettle_add_product_to_queue', (object) array(
                                    'product_id' => $product_id,
                                    'sync_all' => $sync_all,
                                ));

                                $products_added[] = $product_id;

                                if ($product_id != $original_product_id) {
                                    WC_IZ()->logger->add(sprintf('Added product id %s to the sync queue instead of product id %s as the default language is %s', $product_id, $original_product_id, $wpml_default_language));
                                }

                            } elseif (!$product_id) {

                                WC_IZ()->logger->add(sprintf('Skipping product id %s as no translation exists for %s', $original_product_id, $wpml_default_language));

                            } else {

                                WC_IZ()->logger->add(sprintf('Skipping product id %s as it was a language duplicate for product id %s', $original_product_id, $product_id));

                            }

                        } else {

                            do_action('izettle_add_product_to_queue', (object) array(
                                'product_id' => $original_product_id,
                                'sync_all' => $sync_all,
                            ));
                            $products_added[] = $original_product_id;

                        }
                    }
                }

                WC_IZ()->logger->add(sprintf('Added %d products to queue for updating Zettle', count($products_added)));

            }

            if ($sync_all && 'yes' == get_option('izettle_delete_izettle_products')) {
                as_schedule_single_action(as_get_datetime_object(), 'zettle_clean_orphans');
            }

            if (!empty($products_added)) {
                return count($products_added);
            } else {
                return 0;
            }

        }

        /**
         * Trigger product updates when sales price has changed on a product
         *
         * @param array $product_ids product ids that was changed due to sale
         *
         * @since 6.5.0
         */
        public function update_products_when_sales_price_is_changed($product_ids)
        {

            $parents = array();

            foreach ($product_ids as $product_id) {

                $product = wc_get_product($product_id);

                if ($product && ($parent_id = $product->get_parent_id())) {
                    if (!in_array($parent_id,$parents)){
                        array_push($parents,$parent_id);
                        $product_id = $parent_id;
                    }else{
                        continue;
                    }
                }

                do_action('izettle_add_product_to_queue', (object) array(
                    'product_id' => $product_id,
                    'sync_all' => false,
                ));

            }

        }

    }

    new WC_iZettle_Integration_Product_Handler();

}
