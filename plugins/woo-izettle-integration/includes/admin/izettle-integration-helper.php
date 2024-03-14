<?php

/**
 * WC_Zettle_Helper
 *
 * @class           WC_Zettle_Helper
 * @since           2.12.0
 * @package         WC_iZettle_Integration
 * @category        Class
 * @author          bjorntech
 */

defined('ABSPATH') || exit;

if (!class_exists('WC_Zettle_Helper', false)) {

    class WC_Zettle_Helper
    {

        static $all_products;
        static $wpml_default_language;
        static $post_type;

        public static function get_variations($product)
        {
            $available_variations = array();

            foreach ($product->get_visible_children() as $child_id) {
                $variation = wc_get_product($child_id);

                if (!$variation || !$variation->exists()) {
                    continue;
                }

                $avaliable_variation = $product->get_available_variation($variation);

                if ($avaliable_variation) {
                    $available_variations[] = $avaliable_variation;
                }
            }

            return $available_variations;
        }

        public static function update_izettle_product_cashe()
        {
            self::$all_products = izettle_api()->get_products();
        }

        public static function maybe_remove_vat($variant, $tax_rate = false, $type = 'cost_price')
        {

            if ('cost_price' == $type) {
                $price = isset($variant->costPrice) ? $variant->costPrice->amount : 0 / 100;
            } else {
                $price = isset($variant->price) ? $variant->price->amount : 0 / 100;
            }


            if (wc_string_to_bool(get_option('izettle_use_new_us_tax_settings'))) {
                WC_IZ()->logger->add(sprintf('izettle_variant_array (%s): Using new tax settings', $variant->uuid));
                if ((false !== self::get_tax_settings()) && ($tax_rate === false || empty($tax_rate) || is_null($tax_rate))) {
                    WC_IZ()->logger->add(sprintf('maybe_remove_vat (%s): %s will be set to %s - tax settings found - ', $variant->uuid, (('cost_price' == $type) ? 'Cost price' : 'Price'), $price));
                    return $price;
                }
            } else {
                WC_IZ()->logger->add(sprintf('izettle_variant_array (%s): Using old tax settings', $variant->uuid));
            }

            $tax_rate = $tax_rate ? $tax_rate : $variant->vatPercentage;
            if (wc_tax_enabled() && 'yes' != get_option('woocommerce_prices_include_tax')) {
                if (false !== $tax_rate) {
                    $tax_multiplier = 1 + ($tax_rate / 100);
                    WC_IZ()->logger->add(sprintf('maybe_remove_vat (%s): %s changing from %s to %s', $variant->uuid, (('cost_price' == $type) ? 'Cost price' : 'Price'), $price, ($price / $tax_multiplier)));
                    $price = $price / $tax_multiplier;
                }
            }

            WC_IZ()->logger->add(sprintf('maybe_remove_vat (%s): %s will be set to %s', $variant->uuid, (('cost_price' == $type) ? 'Cost price' : 'Price'), $price));

            return $price;
        }

        public static function compare_id($product_id, $sku)
        {
            if ((strlen($sku) > 3) && ('ID:' == substr($sku, 0, 3))) {
                if ($product_id == trim(substr($sku, 3))) {
                    return true;
                }
            }
            return false;
        }

        public static function compare_legacy_sku($product_id, $sku)
        {
            if (strlen($sku) > 4 && 'SKU:' == substr($sku, 0, 4)) {
                $wc_sku = trim(substr($sku, 4));
                if ($product_id == wc_get_product_id_by_sku($wc_sku)) {
                    return true;
                }
            }
            return false;
        }

        public static function compare_sku($product_id, $sku)
        {
            if ($sku && ($product_id == wc_get_product_id_by_sku($sku))) {
                return true;
            }
            return false;
        }

        public static function compare_variant_uuid($product_id, $uuid, $variant_uuid)
        {
            if ($uuid == $variant_uuid) {
                return true;
            }
            return false;
        }

        public static function get_wc_product_by_sku($sku)
        {

            $sku = trim($sku);

            if ($sku) {

                if ((strlen($sku) > 3) && ('ID:' == substr($sku, 0, 3))) {
                    $product_id = trim(substr($sku, 3));
                    if ($product = wc_get_product($product_id)) {
                        WC_IZ()->logger->add(sprintf('get_wc_product_by_sku (%s): Found %s product using product id "%s"', $product->get_id(), $product->get_type(), $sku));
                        return $product;
                    }
                } elseif (strlen($sku) > 4 && 'SKU:' == substr($sku, 0, 4)) {
                    $wc_sku = trim(substr($sku, 4));
                    $product_id = wc_get_product_id_by_sku($wc_sku);
                    if ($product = wc_get_product($product_id)) {
                        WC_IZ()->logger->add(sprintf('get_wc_product_by_sku (%s): Found %s product using SKU %s', $product->get_id(), $product->get_type(), $sku));
                        return $product;
                    }
                } elseif ($sku && ($product_id = wc_get_product_id_by_sku($sku))) {
                    if ($product = wc_get_product($product_id)) {
                        WC_IZ()->logger->add(sprintf('get_wc_product_by_sku (%s): Found %s product using SKU %s', $product->get_id(), $product->get_type(), $sku));
                        return $product;
                    }
                }
            }

            return false;
        }

        public static function match_product_variations($variant, $product_id)
        {

            $variant_uuid = get_post_meta($product_id, 'woocommerce_izettle_variant_uuid', true);

            if (('yes' != get_option('izettle_do_not_match_with_uuid')) && $variant->uuid == $variant_uuid) {
                WC_IZ()->logger->add(sprintf('match_product_variations: Product %s matched variant UUID %s', $product_id, $variant->uuid));
                return true;
            }

            if (!$variant_uuid) {

                $sku = trim($variant->sku);
                $generated_uuid = ($wc_variant = wc_get_product($product_id)) ? IZ_UUID_V2::get_uuid_from_wc_product($wc_variant) : false;

                WC_IZ()->logger->add(sprintf('match_product_variations (%s): Variant recieved UUID %s', $product_id, $variant->uuid));
                WC_IZ()->logger->add(sprintf('match_product_variations (%s): Variant generated UUID %s', $product_id, $generated_uuid));

                if (self::compare_id($product_id, $sku)) {
                    WC_IZ()->logger->add(sprintf('match_product_variations (%s): Product matched ID:%s', $product_id, $product_id));
                    return true;
                } elseif (self::compare_legacy_sku($product_id, $sku)) {
                    WC_IZ()->logger->add(sprintf('match_product_variations (%s): Product matched %s', $product_id, $sku));
                    return true;
                } elseif (self::compare_sku($product_id, $sku)) {
                    WC_IZ()->logger->add(sprintf('match_product_variations (%s): Product matched %s', $product_id, $sku));
                    return true;
                } elseif ($variant->uuid == $generated_uuid) {
                    WC_IZ()->logger->add(sprintf('match_product_variations (%s): Product new UUID %s matched with received UUID %s', $product_id, $generated_uuid, $variant->uuid));
                    return true;
                }
            }

            return false;
        }

        public static function fix_utf8_string($string)
        {
            $string = preg_replace('/(?<!\\\\)u00/', '\\u00', $string);

            return $string;
        }
        public static function get_id($id)
        {

            if (false !== ($pos = strpos($id, 'ID:'))) {
                $product_id = substr($id, $pos + 3);
            } else {
                $product_id = wc_get_product_id_by_sku($id);
            }

            return trim($product_id);
        }

        public static function get_wcpbc_pricing_zone_id($zone)
        {
            if (method_exists('WCPBC_Pricing_Zone', 'get_zone_id')) {
                return $zone->get_zone_id();
            } else {
                return $zone->get_id();
            }
        }

        public static function get_post_id_by_metadata($meta_value, $post_type = '', $meta_key = '', $max_posts = -1, $post_status = false, $taxonomy = false)
        {
            if ($meta_value) {

                $all_statuses = get_post_stati();
                if (isset($all_statuses['trash'])) {
                    unset($all_statuses['trash']);
                }

                $args = array(
                    'posts_per_page' => -1,
                    'post_type' => array($post_type),
                    'meta_key' => $meta_key,
                    'meta_value' => $meta_value,
                    'fields' => 'ids',
                    'post_status' => ((false === $post_status) ? $all_statuses : $post_status),
                );

                if (false !== $taxonomy) {
                    $args['tax_query'] = $taxonomy;
                }

                $query = new WP_Query($args);

                $wpml_default_language = get_option('zettle_wpml_default_language', apply_filters('wpml_default_language', null));

                if ($query->have_posts()) {

                    $posts = $query->get_posts();

                    WC_IZ()->logger->add(sprintf('get_post_id_by_metadata: Got "%s" as posts using max posts %s for UUID %s', implode(',', $posts), $max_posts, $meta_value));

                    if (1 == $max_posts && 1 == count($posts)) {
                        return apply_filters('wpml_object_id', reset($posts), $post_type, true, $wpml_default_language);
                    }

                    if (-1 == $max_posts) {
                        return $posts;
                    }

                    if ($max_posts >= count($posts)) {
                        return $posts;
                    }

                    if ($wpml_default_language) {

                        $unique_id_array = array();

                        foreach ($posts as $post) {
                            $new_product_id = apply_filters('wpml_object_id', $post, $post_type, true, $wpml_default_language);
                            if ($new_product_id) {
                                $unique_id_array[] = $new_product_id;
                            }
                        }

                        if (empty($unique_id_array)) {
                            return null;
                        }

                        $unique_id_array = array_unique($unique_id_array);

                        WC_IZ()->logger->add(sprintf('get_post_id_by_metadata: Cleaning WPML, processed posts to be "%s"', implode(',', $unique_id_array)));

                        if (count($unique_id_array) == 1) {

                            if (1 == $max_posts) {
                                return reset($unique_id_array);
                            }

                            if (-1 == $max_posts) {
                                return $posts;
                            }
                        }
                    }

                    WC_IZ()->logger->add(sprintf('get_post_id_by_metadata: %s %s found on more posts than expected "%s" using max posts %s', $meta_key, $meta_value, implode(',', $posts), $max_posts));

                    throw new IZ_Integration_Exception(sprintf('%s %s found on more posts than expected (%s).', $meta_key, $meta_value, implode(',', $posts)), ZETTLE_DUPLICATE_POST_ERROR);
                }
            }

            return null;
        }

        public static function get_product_id_by_metadata($meta_value, $post_type = 'product', $meta_key = '', $max_posts = -1, $post_status = false, $taxonomy = false)
        {
            if ($meta_value) {

                $all_statuses = get_post_stati();

                if ($trash_key = array_search('trash', $all_statuses)) {
                    unset($all_statuses[$trash_key]);
                }

                $args = array(
                    'limit' => -1,
                    'type' => $post_type,
                    'meta_key' => $meta_key,
                    'meta_value' => $meta_value,
                    'return' => 'ids',
                    'status' => ((false === $post_status) ? array_keys($all_statuses) : $post_status),
                );

                if (false !== $taxonomy) {
                    $args['tax_query'] = $taxonomy;
                }

                $posts = wc_get_products($args);

                $wpml_default_language = get_option('zettle_wpml_default_language', apply_filters('wpml_default_language', null));

                if (!empty($posts)) {

                    WC_IZ()->logger->add(sprintf('get_product_id_by_metadata: Got "%s" as posts using max posts %s for UUID %s', implode(',', $posts), $max_posts, $meta_value));

                    if (1 == $max_posts && 1 == count($posts)) {
                        return apply_filters('wpml_object_id', reset($posts), $post_type, true, $wpml_default_language);
                    }

                    if (-1 == $max_posts) {
                        return $posts;
                    }

                    if ($max_posts >= count($posts)) {
                        return $posts;
                    }

                    if ($wpml_default_language) {

                        $unique_id_array = array();

                        foreach ($posts as $post) {
                            $new_product_id = apply_filters('wpml_object_id', $post, $post_type, true, $wpml_default_language);
                            if ($new_product_id) {
                                $unique_id_array[] = $new_product_id;
                            }
                        }

                        if (empty($unique_id_array)) {
                            return null;
                        }

                        $unique_id_array = array_unique($unique_id_array);

                        WC_IZ()->logger->add(sprintf('get_product_id_by_metadata: Cleaning WPML, processed posts to be "%s"', implode(',', $unique_id_array)));

                        if (count($unique_id_array) == 1) {

                            if (1 == $max_posts) {
                                return reset($unique_id_array);
                            }

                            if (-1 == $max_posts) {
                                return $posts;
                            }
                        }
                    }

                    WC_IZ()->logger->add(sprintf('get_product_id_by_metadata: %s %s found on more posts than expected "%s" using max posts %s', $meta_key, $meta_value, implode(',', $posts), $max_posts));

                    throw new IZ_Integration_Exception(sprintf('%s %s found on more posts than expected (%s).', $meta_key, $meta_value, implode(',', $posts)), ZETTLE_DUPLICATE_POST_ERROR);
                }
            }

            return null;
        }

        /*
        public static function get_iz_product_by_uuid_fallback($product_uuid) {
            try{

                WC_IZ()->logger->add(sprintf('get_iz_product_by_uuid_fallback: Looking for Zettle UUID %s in WooCommerce', $product_uuid));

                $iz_product = izettle_api()->get_products($product_uuid);

                $sku = isset($iz_product->variants[0]->sku) ? $iz_product->variants[0]->sku : '';

                WC_IZ()->logger->add(sprintf('get_iz_product_by_uuid_fallback: Zettle UUID %s has SKU "%s"', $product_uuid, $sku));

                $product = self::get_wc_product_by_sku($sku);

                if ($product) {
                    $product_id = $product->get_id();
                    WC_IZ()->logger->add(sprintf('get_iz_product_by_uuid_fallback: Zettle UUID %s matched with product "%s"', $product_uuid, $product_id));
                    return $product->get_id();
                } else {

                }

            } catch(IZ_Integration_API_Exception $e) {
                if (404 == $e->getCode()) {
                    WC_IZ()->logger->add(sprintf('get_iz_product_by_uuid_fallback: Zettle UUID %s not found in Zettle', $product_uuid));
                } else {
                    throw new IZ_Integration_API_Exception($e);
                }
            }

            return false;
        }*/

        public static function get_wc_product_by_uuid($product_uuid, $variant_uuid = false)
        {

            if ('yes' == get_option('izettle_do_not_match_with_uuid')) {
                return null;
            }

            if (!empty($product_uuid) && (!empty($variant_uuid) || false === $variant_uuid)) {

                $product_id = false;

                if (wc_string_to_bool(get_option('izettle_use_new_product_matching'))) {
                    $product_id = self::get_product_id_by_metadata($product_uuid, array_keys(wc_get_product_types()), 'woocommerce_izettle_product_uuid', 1);
                } else {
                    $product_id = self::get_post_id_by_metadata($product_uuid, 'product', 'woocommerce_izettle_product_uuid', 1);
                }

                if ($product_id) {

                    $product = wc_get_product($product_id);

                    if (!$variant_uuid) {
                        return $product;
                    }

                    $has_children = $product->get_children();

                    if ($has_children) {

                        $variations = WC_Zettle_Helper::get_all_variations($product);

                        foreach ($variations as $variation) {

                            if (!is_object($variation)) {
                                $variation = wc_get_product($variation['variation_id']);
                            }

                            if ($variant_uuid === $variation->get_meta('woocommerce_izettle_variant_uuid', true)) {
                                return $variation;
                            }
                        }

                        WC_IZ()->logger->add(sprintf('get_wc_product (%s): Zettle variant UUID %s not found as variation', $product_id, $variant_uuid));
                    } elseif ($variant_uuid === $product->get_meta('woocommerce_izettle_variant_uuid', true)) {

                        return $product;
                    }
                } else {

                    WC_IZ()->logger->add(sprintf('get_wc_product: Zettle product UUID %s not found as product id', $product_uuid));
                }
            } else {

                throw new IZ_Integration_Exception(sprintf('Product UUID "%s" or Variant UUID "%s" was empty, contact BjornTech support.', $product_uuid, $variant_uuid), ZETTLE_EMPTY_UUID);
            }

            return null;
        }

        public static function get_product_categories()
        {
            $cat_args = array(
                'orderby' => 'name',
                'order' => 'asc',
                'hide_empty' => false,
            );
            return get_terms('product_cat', $cat_args);
        }

        public static function get_category_names($ids)
        {
            $product_categories = self::get_product_categories();
            $categories = array();
            foreach ($product_categories as $product_category) {
                foreach ($ids as $id) {
                    if (($product_category->term_id == $id) && ($product_category->name != _x('Uncategorized', 'Default category slug', 'woocommerce'))) {
                        $categories[] = $product_category->name;
                    }
                }
            }
            return $categories;
        }

        public static function get_tax_rate($product, $iz_variant)
        {

            if ($iz_variant && isset($iz_variant->vatPercentage)) {
                $current_tax_rate = $iz_variant->vatPercentage;
            } else {
                $current_tax_rate = 0;
            }

            $product_id = $product->get_id();
            $tax_rate_option = get_option('izettle_handle_tax_rate');

            if (wc_tax_enabled() && !($tax_rate_option)) {

                $calculate_tax_for = array(
                    'country' => WC()->countries->get_base_country(),
                    'state' => WC()->countries->get_base_state(),
                    'city' => WC()->countries->get_base_city(),
                    'postcode' => WC()->countries->get_base_postcode(),
                    'tax_class' => $product->get_tax_class(),
                );

                $tax_rates = WC_Tax::find_rates($calculate_tax_for);
                $taxes = WC_Tax::calc_tax(100, $tax_rates, false);

                if ($taxes) {
                    //  $tax_rate = round(reset($tax_rates)['rate']);
                    $tax_rate = reset($taxes);

                    if ($tax_rate != $current_tax_rate) {
                        WC_IZ()->logger->add(sprintf('get_tax_rate (%s): Tax rate changed from %s to %s', $product_id, $current_tax_rate, $tax_rate));
                    }

                    return (string) $tax_rate;
                } else {
                    WC_IZ()->logger->add(sprintf('get_tax_rate (%s): Product has no tax rate set. Setting tax rate to 0.', $product_id));
                }
            } elseif ('never_update' == $tax_rate_option) {

                WC_IZ()->logger->add(sprintf('get_tax_rate (%s): Not updating tax rate', $product_id));
                return false;
            }

            WC_IZ()->logger->add(sprintf('get_tax_rate (%s): Tax not enabled. Setting tax rate to 0', $product_id));
            return "0";
        }

        public static function update_product_data()
        {
            return 'yes' == get_option('izettle_update_name_from_webhook') || 'yes' == get_option('izettle_update_barcode_from_webhook') || 'yes' == get_option('izettle_update_price_from_webhook') || 'yes' == get_option('izettle_update_cost_price_from_webhook');
        }

        public static function wc_version_check($version = '4.0')
        {
            if (class_exists('WooCommerce')) {
                global $woocommerce;
                if (version_compare(self::wc_version(), $version, ">=")) {
                    return true;
                }
            }
            return false;
        }

        public static function wc_version()
        {
            global $woocommerce;
            return $woocommerce->version;
        }

        public static function is_plugin_installed($plugin)
        {

            if (defined('WP_PLUGIN_DIR')) {
                $plugin_path = WP_PLUGIN_DIR . '/' . $plugin;

                return file_exists($plugin_path);
            }

            return false;
        }

        public static function is_store_inventory_update($balance_after)
        {
            $locations = apply_filters('izettle_stock_locations', null);

            if (!$locations) {
                WC_IZ()->logger->add(sprintf('is_store_inventory_update (%s): Location data missing', $balance_after->variantUuid));
                return false;
            }

            if (!array_key_exists('STORE', $locations)) {
                WC_IZ()->logger->add(sprintf('is_store_inventory_update (%s): Store location doesnt exist', $balance_after->variantUuid));
                return false;
            }

            if ($balance_after->locationUuid != $locations['STORE']) {
                WC_IZ()->logger->add(sprintf('is_store_inventory_update (%s): Inventory update not recieved from STORE', $balance_after->variantUuid));
                return false;
            }

            return true;
        }

        public static function object_diff(stdClass $obj1, stdClass $obj2): bool
        {
            $array1 = json_decode(json_encode($obj1, JSON_INVALID_UTF8_IGNORE), true);
            $array2 = json_decode(json_encode($obj2, JSON_INVALID_UTF8_IGNORE), true);
            return self::array_diff($array1, $array2);
        }

        public static function array_diff(array $array1, array $array2, bool $length_compare = false, string $length_compare_key = ''): bool
        {
            if ($length_compare) {
                if (count($array1) !== count($array2)) {
                    WC_IZ()->logger->add(sprintf('array_diff: Array {%s} size difference - "%s" do not match "%s" ', $length_compare_key, count($array1), count($array2)));
                    return true;
                }
            }

            foreach ($array1 as $key => $value) {
                if (array_key_exists($key, $array2)) {
                    if ($value instanceof stdClass) {
                        $r = self::object_diff((object) $value, (object) $array2[$key]);
                        if ($r === true) {
                            return true;
                        }
                    } else if (is_array($value)) {
                        $should_compare_length = false;

                        if (($key == 'variants') || ($key == 'properties') || ($key == 'definitions')) {
                            $should_compare_length = true;
                        }

                        $r = self::array_diff((array) $value, (array) $array2[$key], (bool) $should_compare_length, (string) "$key");

                        if ($r === true) {
                            return true;
                        }
                    } else if (is_double($value)) {
                        // required to avoid rounding errors due to the
                        // conversion from string representation to double
                        if (0 !== bccomp($value, $array2[$key], 12)) {
                            WC_IZ()->logger->add(sprintf('array_diff: Key {%s} was changed from "%s" to "%s"', $key, $array2[$key], $value));
                            return true;
                        }
                    } else {
                        if ($value != $array2[$key]) {
                            WC_IZ()->logger->add(sprintf('array_diff: Key {%s} was changed from "%s" to "%s"', $key, $array2[$key], $value));
                            return true;
                        }
                    }
                } else {
                    WC_IZ()->logger->add(sprintf('array_diff: Key {%s} does not exist in old data', $array1[$key]));
                    return true;
                }
            }

            return false;
        }

        /**
         * Returns the product id or the string 'n/a' if the parameter is empty or not a number
         *
         * @since 6.0.0
         *
         * @param string $product_id
         *
         * @return string
         */
        public static function id_or_not($product_id)
        {
            if (empty($product_id) || !is_numeric($product_id)) {
                return 'n/a';
            }
            return $product_id;
        }

        /**
         * Returns the product id or the string 'n/a' if the parameter is empty or not a number
         *
         * @since 6.0.0
         *
         * @param string $product_id
         *
         * @return string
         */
        public static function true_or_false($boolean)
        {
            if (!is_bool($boolean)) {
                return 'not bool';
            }
            return $boolean ? 'true' : 'false';
        }

        /**
         * Creates a hash based on a product object after the etag has been removed
         *
         * @since 6.0.0
         *
         * @param array $object
         *
         * @return string
         */
        public static function create_hash($object)
        {
            if (!is_array($object)) {
                $object = json_decode(json_encode($object, JSON_INVALID_UTF8_IGNORE), true);
            }
            unset($object['etag']);
            unset($object['updated']);
            unset($object['updatedBy']);
            self::ksort_recursive($object);
            WC_IZ()->logger->add(print_r(json_encode($object, JSON_INVALID_UTF8_IGNORE), true));
            return md5(json_encode((object) $object));
        }

        /**
         * Sorts an array based on keys
         *
         * @since 6.0.0
         *
         * @param array $array
         *
         * @return bool
         */
        public static function ksort_recursive(&$array)
        {
            if (is_array($array)) {
                ksort($array);
                array_walk($array, 'self::ksort_recursive');
            }
        }

        public static function get_term_by_slug($slug)
        {
            $term = get_term_by('slug', $slug, 'product_cat');
            return $term->term_id ? $term->term_id : '';
        }

        public static function is_syncable($product)
        {

            if (!$product) {
                WC_IZ()->logger->add(sprintf('is_syncable: Can not sync an empty product'));
                return false;
            }

            $product_id = $product->get_id();

            if ('yes' == $product->get_meta('_izettle_nosync', true, 'edit')) {
                WC_IZ()->logger->add(sprintf('is_syncable (%s): Product is set to not sync', $product_id));
                return false;
            }

            if (($parent_id = $product->get_parent_id()) && ($parent = wc_get_product($parent_id)) && !$parent->is_type('grouped')) {
                WC_IZ()->logger->add(sprintf('is_syncable (%s): Changed check to product parent %s', $product_id, $parent_id));
                if ('yes' == $parent->get_meta('_izettle_nosync', true, 'edit')) {
                    WC_IZ()->logger->add(sprintf('is_syncable (%s): Product parent %s is set to not sync', $product_id, $parent_id));
                    return false;
                }
            } else {
                $parent_id = $product_id;
                $parent = $product;
            }

            //If virtual product
            if (wc_string_to_bool(get_option('izettle_do_not_sync_virtual')) && $parent->is_virtual()) {
                WC_IZ()->logger->add(sprintf('is_syncable (%s): Product is virtual - skipping', $product_id));
                return false;
            }

            $product_type = $parent->get_type();
            $products_include = get_option('izettle_products_include', array('simple', 'variable'));
            if (!in_array($product_type, $products_include)) {
                WC_IZ()->logger->add(sprintf('is_syncable (%s): Product type "%s" is not within "%s"', $product_id, $product_type, implode(',', $products_include)));
                return false;
            }

            $product_statuses = ($product_status = get_option('izettle_product_status', array('draft', 'pending', 'private', 'publish'))) ? $product_status : array('draft', 'pending', 'private', 'publish');
            $status = $parent->get_status('edit');
            if (!in_array($status, $product_statuses)) {
                WC_IZ()->logger->add(sprintf('is_syncable (%s): Product status "%s" is not within "%s"', $product_id, $status, implode(',', $product_statuses)));
                return false;
            }

            $category_ids = $parent->get_category_ids('edit');

            $product_categories = !($product_categories_raw = get_option('izettle_product_categories')) ? array() : array_map('self::get_term_by_slug', $product_categories_raw);

            if (wc_string_to_bool(get_option('zettle_product_category_export_filter_v2'))) {
                if (!empty($product_categories)) {
                    // Get all child categories
                    $child_categories = array();
                    foreach ($product_categories as $parent_category) {
                        $child_categories = array_merge($child_categories, get_term_children($parent_category, 'product_cat'));
                    }
                    // Merge parent and child categories
                    $all_categories = array_merge($product_categories, $child_categories);

                    if (empty(array_intersect($category_ids, $all_categories))) {
                        WC_IZ()->logger->add(sprintf('is_syncable (%s): Product categories "%s" is not within "%s"', $product_id, implode(',', $category_ids), implode(',', $all_categories)));
                        return false;
                    }
                }
            } else {
                if (!empty($product_categories) && empty(array_intersect($category_ids, $product_categories))) {
                    WC_IZ()->logger->add(sprintf('is_syncable (%s): Product categories "%s" is not within "%s"', $product_id, implode(',', $category_ids), implode(',', $product_categories)));
                    return false;
                }
            }

            //Create a filter to exclude products from sync based on the product categories
            $product_categories = !($product_categories_raw = get_option('izettle_exclude_product_categories')) ? array() : array_map('self::get_term_by_slug', $product_categories_raw);

            if (wc_string_to_bool(get_option('zettle_product_category_export_filter_v2'))) {
                if (!empty($product_categories)) {
                    // Get all child categories
                    $child_categories = array();
                    foreach ($product_categories as $parent_category) {
                        $child_categories = array_merge($child_categories, get_term_children($parent_category, 'product_cat'));
                    }
                    // Merge parent and child categories
                    $all_categories = array_merge($product_categories, $child_categories);

                    if (!empty(array_intersect($category_ids, $all_categories))) {
                        WC_IZ()->logger->add(sprintf('is_syncable (%s): Product categories "%s" is within exclusion filter "%s"', $product_id, implode(',', $category_ids), implode(',', $all_categories)));
                        return false;
                    }
                }
            } else {
                if (!empty($product_categories) && !empty(array_intersect($category_ids, $product_categories))) {
                    WC_IZ()->logger->add(sprintf('is_syncable (%s): Product categories "%s" is within exclusion filter "%s"', $product_id, implode(',', $category_ids), implode(',', $product_categories)));
                    return false;
                }
            }


            $in_stock_only = 'yes' == get_option('izettle_sync_in_stock_only');
            if ($in_stock_only && !$product->is_in_stock('edit')) {
                WC_IZ()->logger->add(sprintf('is_syncable (%s): Product has no stock and sync_stock_only is "%s"', $product_id, self::true_or_false($in_stock_only)));
                return false;
            }

            return true;
        }

        public static function get_any_text()
        {

            switch (get_option('izettle_any_text_selection')) {
                case 'untranslated':
                    return 'Any';
                    break;
                case 'alternate':
                    if ($alternate_any_text = get_option('izettle_alternate_any_text')) {
                        return $alternate_any_text;
                    }
                default:
                    return __('Any', 'woo-izettle-integration');
            }
        }

        public static function get_any_array()
        {

            return array_merge(array('Any'), array(__('Any', 'woo-izettle-integration')), (($alternate_any_text = get_option('izettle_alternate_any_text')) ? array($alternate_any_text) : array()));
        }

        public static function get_processing_queue($group)
        {
            return as_get_scheduled_actions(
                array(
                    'group' => $group,
                    'status' => ActionScheduler_Store::STATUS_PENDING,
                    'claimed' => false,
                    'per_page' => -1,
                ),
                'ids'
            );
        }

        public static function display_name($id)
        {
            switch ($id) {
                case 'wciz_sync_wc_products':
                    return __('WooCommerce products', 'woo-izettle-integration');
                    break;
                case 'wciz_sync_iz_products':
                    return __('Zettle products', 'woo-izettle-integration');
                    break;
                case 'wciz_sync_iz_purchases':
                    return __('Zettle purchases', 'woo-izettle-integration');
                    break;
            }
            return '';
        }

        public static function display_sync_button($id, $class = '')
        {

            if (!empty($processing_queue = self::get_processing_queue($id))) {
                echo '<div id=' . $id . '_status name="' . $id . '" class="wciz_processing_status" ></div>';
                $button_text = __('Cancel', 'woo-izettle-integration');
            } else {
                $button_text = __('Start', 'woo-izettle-integration');
            }

            echo '<div id=' . $id . '_titledesc>';
            echo '<tr valign="top">';
            echo '<th scope="row" class="titledesc ' . $class . '">';
            echo '<label for="' . $id . '">' . __('Action', 'woo-izettle-integration') . '</label>';
            echo '</th>';
            echo '<td class="forminp forminp-button">';
            echo '<button id="' . $id . '" class="button wciz_processing_button">' . $button_text . '</button>';
            echo '</td>';
            echo '</tr>';
            echo '</div>';
        }

        /**
         * Find a tax class based on a tax rate
         *
         * @param string $rate
         *
         * @return string
         */
        public static function get_tax_class($rate)
        {

            $tax_classes = WC_Tax::get_tax_class_slugs();

            $tax_classes_incl_standard = array_merge($tax_classes, array(''));

            foreach ($tax_classes_incl_standard as $tax_class) {
                $class_rate = get_option('izettle_tax_class_mapping_' . $tax_class);
                if ($class_rate !== '' && $class_rate == $rate) {
                    WC_IZ()->logger->add(sprintf('get_tax_class: Found tax class "%s" with rate %s in settings', $tax_class, $rate));
                    return $tax_class;
                }
            }

            foreach ($tax_classes as $tax_class) {
                $base_tax_rates = WC_Tax::get_base_tax_rates($tax_class);
                if ($base_tax_rates && is_array($base_tax_rates) && reset($base_tax_rates)['rate'] == $rate) {
                    WC_IZ()->logger->add(sprintf('get_tax_class: Found tax class "%s" with rate %s by rate-search', $tax_class, $rate));
                    return $tax_class;
                }
            }

            WC_IZ()->logger->add('get_tax_class: Using tax class "" as default');
            return ''; // Default to standard rate

        }

        public static function is_image_in_meta($product, $url)
        {

            $image_lookup_key = get_post_meta($product->get_id(), '_izettle_image_lookup_key', true);

            if (empty($url) || empty($image_lookup_key) || false === strpos($url, $image_lookup_key)) {
                return false;
            }

            return true;
        }

        /**
         * Get an array of available variations for the current product.
         * Use our own to get all variations regardless of filtering
         *
         * @return array
         */
        public static function get_all_variations($product)
        {
            $available_variations = array();

            foreach ($product->get_children() as $child_id) {
                $variation = wc_get_product($child_id);

                $available_variations[] = $product->get_available_variation($variation);
            }
            $available_variations = array_values(array_filter($available_variations));

            return $available_variations;
        }

        public static function weight_from_grams($weight)
        {

            $unit = get_option('woocommerce_weight_unit', 'kg');

            $response = $weight;

            if (is_numeric($weight)) {

                switch ($unit) {
                    case 'kg':
                        $response = $weight / 1000;
                        break;
                    case 'lbs':
                        $response = $weight / 453.59237;
                        break;
                    case 'oz':
                        $response = $weight / 28.3495231;
                        break;
                    case 'g':
                    default:
                        $response = $weight;
                }
            }

            return $response;
        }

        public static function izettle_wp_button_input($field)
        {
            global $thepostid, $post;

            $thepostid = empty($thepostid) ? $post->ID : $thepostid;
            $field['button_text'] = isset($field['button_text']) ? $field['button_text'] : '';
            $field['button_class'] = isset($field['button_class']) ? $field['button_class'] : '';
            $field['button_name'] = isset($field['button_name']) ? $field['button_name'] : '';
            $field['placeholder'] = isset($field['placeholder']) ? $field['placeholder'] : '';
            $field['class'] = isset($field['class']) ? $field['class'] : 'short';
            $field['style'] = isset($field['style']) ? $field['style'] : '';
            $field['wrapper_class'] = isset($field['wrapper_class']) ? $field['wrapper_class'] : '';
            $field['value'] = isset($field['value']) ? $field['value'] : get_post_meta($thepostid, $field['id'], true);
            $field['name'] = isset($field['name']) ? $field['name'] : $field['id'];
            $field['type'] = isset($field['type']) ? $field['type'] : 'text';
            $field['desc_tip'] = isset($field['desc_tip']) ? $field['desc_tip'] : false;
            $data_type = empty($field['data_type']) ? '' : $field['data_type'];

            switch ($data_type) {
                case 'price':
                    $field['class'] .= ' wc_input_price';
                    $field['value'] = wc_format_localized_price($field['value']);
                    break;
                case 'decimal':
                    $field['class'] .= ' wc_input_decimal';
                    $field['value'] = wc_format_localized_decimal($field['value']);
                    break;
                case 'stock':
                    $field['class'] .= ' wc_input_stock';
                    $field['value'] = wc_stock_amount($field['value']);
                    break;
                case 'url':
                    $field['class'] .= ' wc_input_url';
                    $field['value'] = esc_url($field['value']);
                    break;

                default:
                    break;
            }

            // Custom attribute handling
            $custom_attributes = array();

            if (!empty($field['custom_attributes']) && is_array($field['custom_attributes'])) {

                foreach ($field['custom_attributes'] as $attribute => $value) {
                    $custom_attributes[] = esc_attr($attribute) . '="' . esc_attr($value) . '"';
                }
            }

            echo '<p class="form-field ' . esc_attr($field['id']) . '_field ' . esc_attr($field['wrapper_class']) . '">
                <label for="' . esc_attr($field['id']) . '">' . wp_kses_post($field['label']) . '</label>';

            if (!empty($field['description']) && false !== $field['desc_tip']) {
                echo wc_help_tip($field['description']);
            }

            echo '<input type="' . esc_attr($field['type']) . '" class="' . esc_attr($field['class']) . '" style="' . esc_attr($field['style']) . '" name="' . esc_attr($field['name']) . '" id="' . esc_attr($field['id']) . '" value="' . esc_attr($field['value']) . '" placeholder="' . esc_attr($field['placeholder']) . '" ' . implode(' ', $custom_attributes) . ' /> ';

            echo '<button type="button" class="button ' . esc_attr($field['button_class']) . '" name="' . esc_attr($field['button_name']) . '" id="' . esc_attr('button' . $field['id']) . '">' . wp_kses_post($field['button_text']) . '</button>';

            if (!empty($field['description']) && false === $field['desc_tip']) {
                echo '<span class="description">' . wp_kses_post($field['description']) . '</span>';
            }

            echo '</p>';
        }

        public static function debug_string_backtrace()
        {
            ob_start();
            debug_print_backtrace();
            $trace = ob_get_contents();
            ob_end_clean();

            // Remove first item from backtrace as it's this function which
            // is redundant.
            $trace = preg_replace('/^#0\s+' . __FUNCTION__ . "[^\n]*\n/", '', $trace, 1);

            // Renumber backtrace items.
            $trace = preg_replace('/^#(\d+)/me', '\'#\' . ($1 - 1)', $trace);

            return $trace;
        }

        public static function zettle_changes_stock()
        {
            return ('yes' == get_option('izettle_import_stocklevel') && get_option('izettle_when_changed_in_izettle'));
        }

        public static function product_language($product_id, $wpml_default_language = false)
        {

            $wpml_default_language = $wpml_default_language ? $wpml_default_language : get_option('zettle_wpml_default_language', apply_filters('wpml_default_language', null));
            $lang = apply_filters('wpml_post_language_details', $wpml_default_language, $product_id);
            if ($lang && isset($lang['language_code'])) {
                return $lang['language_code'];
            }
        }

        private static function get_rates()
        {

            try {

                $tax_rates = get_site_transient('zettle_tax_rates');

                if (false === $tax_rates) {
                    $tax_rates = izettle_api()->get_tax_rates();
                    WC_IZ()->logger->add(sprintf('get_rates: "%s"', json_encode($tax_rates, JSON_INVALID_UTF8_IGNORE)));
                    set_site_transient('zettle_tax_rates', $tax_rates, DAY_IN_SECONDS);
                }

                return $tax_rates === 'false' ? false : $tax_rates;
            } catch (IZ_Integration_API_Exception $e) {

                $error_code = $e->getCode();
                if ($error_code == 403) {
                    set_site_transient('zettle_tax_rates', 'false', DAY_IN_SECONDS);
                    return false;
                } else {
                    throw new $e($e->getMessage(), $error_code, $e);
                }
            }
        }

        private static function create_tax_rate($label, $percentage, $default = false)
        {

            $payload = array(
                "taxRates" => array(
                    array(
                        "uuid" => IZ_UUID::generate(IZ_UUID::UUID_TIME, IZ_UUID::FMT_STRING, WC_iZettle_Integration::UUID_NODE_ID),
                        "label" => $label,
                        "percentage" => $percentage,
                        "default" => $default,
                    ),
                ),
            );
        }

        public static function zettle_tax_rates($id)
        {

            $tax_rates = self::get_rates();

            if (false === $tax_rates) {
                return false;
            }

            $tax_table = array_column($tax_rates->taxRates, 'percentage', 'uuid');
            return isset($tax_table[$id]) ? $tax_table[$id] : false;
        }

        public static function zettle_tax_id($rate)
        {

            $tax_rates = self::get_rates();

            if (false === $tax_rates) {
                return false;
            }

            $tax_table = array_column($tax_rates->taxRates, 'uuid', 'percentage');
            return isset($tax_table[$rate]) ? $tax_table[$rate] : false;
        }

        public static function get_tax_settings()
        {

            try {

                $tax_settings = get_site_transient('zettle_tax_settings');

                if (false === $tax_settings) {
                    $tax_settings = izettle_api()->get_tax_settings();
                    WC_IZ()->logger->add(sprintf('get_tax_settings: "%s"', json_encode($tax_settings, JSON_INVALID_UTF8_IGNORE)));
                    set_site_transient('zettle_tax_settings', $tax_settings, DAY_IN_SECONDS);
                }

                return $tax_settings === 'false' ? false : $tax_settings;
            } catch (IZ_Integration_API_Exception $e) {

                $error_code = $e->getCode();
                if ($error_code == 403) {
                    set_site_transient('zettle_tax_settings', 'false', DAY_IN_SECONDS);
                    return false;
                } else {
                    throw new $e($e->getMessage(), $error_code, $e);
                }
            }
        }

        public static function retrieve_zettle_categories()
        {
            $raw_categories = get_site_transient('izettle_categories');
            if (!is_array($raw_categories)) {
                $raw_categories = izettle_api()->get_categories()->categories;
                set_site_transient('izettle_categories', $raw_categories, DAY_IN_SECONDS);
            }

            return $raw_categories;
        }

        /**
         * Get categories.
         */
        public static function get_categories($product)
        {

            $found_categories = array();
            $ok_ids = array();

            $args = array(
                'taxonomy' => array('product_cat'),
                'orderby' => 'id',
                'order' => 'ASC',
                'hide_empty' => false,
                'fields' => 'all',
            );

            $terms = get_terms($args);

            if ($terms) {

                foreach ($terms as $term) {

                    $formatted_name = '';

                    if ($term->slug === 'uncategorized') {
                        continue;
                    }

                    if ($term->parent) {
                        $ancestors = array_reverse(get_ancestors($term->term_id, 'product_cat'));
                        foreach ($ancestors as $ancestor) {
                            $ancestor_term = get_term($ancestor, 'product_cat');
                            if ($ancestor_term) {
                                $formatted_name .= $ancestor_term->name . ' > ';
                            }
                        }
                    }

                    $formatted_name .= $term->name;
                    $found_categories[$term->term_id] = $formatted_name;
                }
            }

            $cat_ids = $product->get_category_ids('edit');

            foreach ($cat_ids as $key => $cat_id) {
                $ok_ids[$cat_id] = $cat_id;
            }

            $product_categories = array_intersect_key($found_categories, $ok_ids);

            $product_categories = array('' => 'No specific category selected') + $product_categories;

            return $product_categories;
        }

        public static function is_izettle_order($order)
        {
            if (self::is_order_type($order, 'shop_order_refund')) {
                $parent_id = $order->get_parent_id();
                $parent = wc_get_order($parent_id);
                return in_array($parent->get_created_via(), array('izettle', 'zettle'));
            } else {
                return in_array($order->get_created_via(), array('izettle', 'zettle'));
            }
        }

        public static function is_order_type($order, $order_type)
        {
            if (class_exists('Automattic\WooCommerce\Utilities\OrderUtil')) {
                return $order_type == \Automattic\WooCommerce\Utilities\OrderUtil::get_order_type($order->get_id());
            } else {
                return $order_type == $order->get_type();
            }
        }
    }
}
