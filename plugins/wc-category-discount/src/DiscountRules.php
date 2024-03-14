<?php

namespace Wcd\DiscountRules;
if (!defined('ABSPATH')) exit;

class DiscountRules
{
    static $variant_product_price = array();
    protected $discount_types = array(), $discounts = array(), $discounted_items = array(), $app_prefix, $wc_functions, $prefix = 'Wcd_';

    function __construct()
    {
        $this->getDiscountTypes();
        $this->wc_functions = new WcFunctions();
    }

    /**
     * Search folder to get the discount types
     */
    function getDiscountTypes()
    {
        $types_list_directory = __DIR__ . '/Types';
        $types = array();
        $type_list = array_slice(scandir($types_list_directory), 2);
        if (!empty($type_list)) {
            foreach ($type_list as $type) {
                $class_name = basename($type, '.php');
                if (!in_array($class_name, array('AbstractType')))
                    $types[] = '\Wcd\DiscountRules\Types\\' . $class_name;
            }
        }
        $this->discount_types = $types;
    }

    /**
     * Add settings link
     * @param $links
     * @return array
     */
    function pluginActionLinks($links)
    {
        $action_links = array(
            'settings' => '<a href="' . admin_url('admin.php?page=category_discount') . '">' . __('Settings', WCD_TEXT_DOMAIN) . '</a>',
        );
        return array_merge($action_links, $links);
    }

    /**
     * Init the required functions.
     */
    function init()
    {
        $this->registerDiscountTypes()->initDiscountTypes();
    }

    /**
     * Intiate all Discount types
     * @return $this
     */
    function initDiscountTypes()
    {
        foreach ($this->discount_types as $discount_type) {
            $discount_type->init();
            $this->app_prefix = $discount_type->prefix;
        }
        return $this;
    }

    /**
     * Register all discount types.
     * @return $this
     */
    function registerDiscountTypes()
    {
        $types = array_map(function ($type) {
            return new $type;
        }, $this->discount_types);
        $this->discount_types = $types;
        return $this;
    }

    /**
     * Override the Item price in product page
     *
     * @param $item_price - price
     * @param $item - product object
     * @return mixed|string|string[]|null
     */
    function overrideItemPrice($item_price, $item)
    {
        if (empty($item) || empty($item_price)) return NULL;
        if ($this->wc_functions->isItemHasChildren($item)) {
            $variant_prices = array();
            $variants = $this->wc_functions->getItemChildren($item);
            if (!empty($variants)) {
                foreach ($variants as $variant_id) {
                    $item = $this->wc_functions->getItem($variant_id);
                    if ($item->exists()) {
                        $discount_price = $this->getFinalDiscount($item);
                        if ($discount_price > 0) {
                            $variant_prices['discount'][] = $discount_price;
                            $variant_prices['original'][] = $this->wc_functions->getItemPrice($item);
                        }
                    }
                }
            }
            if (!empty($variant_prices)) {
                $min_price = array('discount' => min($variant_prices['discount']), 'original' => min($variant_prices['original']));
                $max_price = array('discount' => max($variant_prices['discount']), 'original' => max($variant_prices['original']));
                if ($min_price['discount'] == $max_price['discount'])
                    return $this->itemPriceTmpl($item, $max_price['discount'], $item_price);
                else
                    return $this->variableItemPriceTmpl($item, array('min' => $min_price, 'max' => $max_price), $item_price);
            } else {
                return $item_price;
            }
        } else {
            $discount_price = $this->getFinalDiscount($item);
            return $this->itemPriceTmpl($item, $discount_price, $item_price);
        }
    }

    /**
     * Decide which discount need to apply
     *
     * @param $item - product object
     * @return mixed - discounted price
     */
    function getFinalDiscount($item)
    {
        if (empty($item)) return NULL;
        $discounted_price = array($this->wc_functions->getItemPrice($item));
        foreach ($this->discount_types as $discount_type) {
            $discounted_price[] = $discount_type->calculateDiscount($item);
            $this->discounts = $discount_type->getAppliedDiscounts();
        }
        //Can be modified from general settings
        return min($discounted_price);
    }

    /**
     * Replace the Woo-commerce  price HTML with ours
     * @param $item - product object
     * @param $price - price
     * @param $item_price - price template
     * @return mixed|string|string[]|null
     */
    function itemPriceTmpl($item, $price, $item_price)
    {
        //print_r($item_price);
        $original_price = $this->wc_functions->getItemPrice($item);
        $discounted_price = (float)($original_price - $price);
        $discounted_price = get_option('woocommerce_tax_display_shop') == 'excl' ? $this->wc_functions->get_price_excluding_tax($item, 1, $discounted_price) : $this->wc_functions->get_price_including_tax($item, 1, $discounted_price);

        if ($original_price > $discounted_price) {
            $item_price = preg_replace('/<del>.*<\/del>/', '', $item_price);
            $item_price = apply_filters('woo_discount_rules_price_strikeout_before_discount_price', $item_price, $item);
            $item_price = '<del>' . $item_price . '</del> <ins>' . $this->wc_functions->makePrice($discounted_price) . $this->wc_functions->getPriceSuffix($item, $price) . '</ins>';
            $item_price = apply_filters('woo_discount_rules_price_strikeout_after_discount_price', $item_price, $item);
        }
        return $item_price;
    }

    /**
     * @param $item - item
     * @param $price
     * @param $item_price
     * @return mixed|string|string[]|null
     */
    function variableItemPriceTmpl($item, $price, $item_price)
    {
        //echo "<pre>";
        //print_r($price);die;
        if (count($price) == 2) {


            $min_discounted_price = (float)($price['min']['original'] - $price['min']['discount']);
            $min_discounted_price = get_option('woocommerce_tax_display_shop') == 'excl' ? $this->wc_functions->get_price_excluding_tax($item, 1, $min_discounted_price) : $this->wc_functions->get_price_including_tax($item, 1, $min_discounted_price);
            $max_discounted_price = (float)($price['max']['original'] - $price['max']['discount']);
            $max_discounted_price = get_option('woocommerce_tax_display_shop') == 'excl' ? $this->wc_functions->get_price_excluding_tax($item, 1, $max_discounted_price) : $this->wc_functions->get_price_including_tax($item, 1, $max_discounted_price);
            if ($price['min']['original'] > $min_discounted_price && $max_discounted_price < $price['max']['original']) {
                $item_price = preg_replace('/<del>.*<\/del>/', '', $item_price);
                $item_price = apply_filters('woo_discount_rules_price_strikeout_before_discount_price', $item_price, $item);
                $item_price = '<del>' . $item_price . '</del> <ins>' . $this->wc_functions->makePrice($min_discounted_price) . ' - ' . $this->wc_functions->makePrice($max_discounted_price) . $this->wc_functions->getPriceSuffix($item, $price) . '</ins>';
                $item_price = apply_filters('woo_discount_rules_price_strikeout_after_discount_price', $item_price, $item);
            }
        }
        return $item_price;
    }

    /**
     * Override the cart Item Price
     *
     * @param $item_price - price
     * @param $item - product object
     * @return string
     */
    function overrideCartItemPrice($item_price, $item)
    {
        if (empty($item)) return NULL;
        $discount_message = "";
        $product_id = $this->wc_functions->getItemId($item['data']);
        if (array_key_exists($product_id, $this->discounted_items)) {
            if ($this->discounted_items[$product_id]['discount_price'] > 0) {
                /*$cart_discount_price = ($discount_price = $this->discounted_items[$product_id]['original_price']) - ($this->discounted_items[$product_id]['discount_price']);
                $cart_discount_price = max($cart_discount_price, 0);*/
                $cart_original_price = $this->discounted_items[$product_id]['original_price'];
                //$cart_original_price = get_option('woocommerce_tax_display_shop') == 'excl' ? $this->wc_functions->get_price_excluding_tax($item, 1, $cart_original_price) : $this->wc_functions->get_price_including_tax($item, 1, $cart_original_price);
                $item_price = '<del>' . $this->wc_functions->makePrice($cart_original_price) . '</del> <ins>' . $item_price . '</ins>';
                //$discount_message = __('Saved ', WCD_TEXT_DOMAIN) . $this->wc_functions->makePrice($this->discounted_items[$product_id]['discount_price'] * $item['quantity']);
            }
        }
        return $item_price . '<p style="color:green">' . $discount_message . '</p>';
    }

    /**
     * Initiate te discount adjustments for product
     */
    function initDiscountAdjustments()
    {
        global $woocommerce;
        $cart_items = $woocommerce->cart->cart_contents;
        foreach ($cart_items as $cart_item_key => $cart_item) {
            $product_id = $this->wc_functions->getItemId($cart_item['data']);
            $item = $this->wc_functions->getItem($product_id);
            $discount_price = (isset($cart_item['category_discount_details']['item_discounted_price'])) ? $cart_item['category_discount_details']['item_discounted_price'] : $this->getFinalDiscount($cart_item['data']);
            //print_r($discount_price);die;
            $item_price = (isset($cart_item['category_discount_details']['item_original_price'])) ? $cart_item['category_discount_details']['item_original_price'] : $this->wc_functions->getItemPrice($cart_item['data']);
            $this->applyDiscount($cart_item_key, $item_price, $discount_price, $product_id);

            $discount_price = get_option('woocommerce_tax_display_shop') == 'excl' ? $this->wc_functions->get_price_excluding_tax($item, 1, $discount_price) : $this->wc_functions->get_price_including_tax($item, 1, $discount_price);
            $this->discounted_items[$product_id]['discount_price'] = $discount_price;
        }
    }

    /**
     * Apply the discount to product
     *
     * @param $item_key - cart Item key
     * @param $original_price - item original price
     * @param $item_id - Variant or product id
     * @param $price - calculated discount price
     * @return bool
     */
    function applyDiscount($item_key, $original_price, $price, $item_id)
    {
        global $woocommerce;
        if (!isset($woocommerce->cart->cart_contents[$item_key])) {
            return false;
        }

        if ($original_price <= $price) {
            $discounted_price = 0;
        } else {
            $discounted_price = (float)($original_price - $price);
        }
        //$discounted_price = (float)($original_price - $price);
        $woo_product = $woocommerce->cart->cart_contents[$item_key]['data'];
        $this->wc_functions->setItemPrice($woo_product, $discounted_price);
        $category_discount = array('is_category_discount_applied' => 1, 'item_original_price' => $original_price, 'item_discounted_price' => $price);
        $woocommerce->cart->cart_contents[$item_key]['category_discount_details'] = $category_discount;

        $item = $this->wc_functions->getItem($item_id);
        $original_price = get_option('woocommerce_tax_display_shop') == 'excl' ? $this->wc_functions->get_price_excluding_tax($item, 1, $original_price) : $this->wc_functions->get_price_including_tax($item, 1, $original_price);
        $this->discounted_items[$item_id]['original_price'] = $original_price;
        return true;
    }

    /**
     * Display the notice message for applied coupons
     */
    function displayAppliedDiscountMessage()
    {
        global $woocommerce;
        $cart_items = $woocommerce->cart->cart_contents;
        foreach ($cart_items as $cart_item_key => $cart_item) {
            if (!empty($cart_item))
                $this->getFinalDiscount(isset($cart_item['data']) ? $cart_item['data'] : $cart_item);
        }
        if (!empty($this->discounts)) {
            $temp_array = array();
            foreach ($this->discounts as $cart_discount_messages) {
                foreach ($cart_discount_messages as $discount_messages) {
                    if (isset($discount_messages['show']) && $discount_messages['show']) {
                        $message = isset($discount_messages['discount_message']) ? $discount_messages['discount_message'] : '{{discount_name}} was applied in this cart.';
                        $prefix = isset($discount_messages['app_prefix']) ? $discount_messages['app_prefix'] : '';
                        if (isset($discount_messages['rules']) && !empty($discount_messages['rules'])) {
                            foreach ($discount_messages['rules'] as $applied_rules) {
                                if (!isset($applied_rules['rule']) || empty($applied_rules['rule']))
                                    continue;
                                if (in_array($applied_rules['rule_id'], $temp_array))
                                    continue;
                                $applied_rule_details = $applied_rules['rule'];
                                if (isset($applied_rule_details[$prefix . 'discount_range_repeater']) && !empty($applied_rule_details[$prefix . 'discount_range_repeater'])) {
                                    $discount_name = (isset($applied_rule_details[$prefix . 'discount_name']) && !empty($applied_rule_details[$prefix . 'discount_name'])) ? $applied_rule_details[$prefix . 'discount_name'] : 'Discount';
                                    foreach ($applied_rule_details[$prefix . 'discount_range_repeater'] as $range_repeater) {
                                        $discount = (isset($range_repeater['amount']) && !empty($range_repeater['amount'])) ? $range_repeater['amount'] : '';
                                        $max = (isset($range_repeater['maximum-product']) && !empty($range_repeater['maximum-product'])) ? $range_repeater['maximum-product'] : 'empty';
                                        $min = (isset($range_repeater['minimum-product']) && !empty($range_repeater['minimum-product'])) ? $range_repeater['minimum-product'] : 'empty';
                                        $applied_min = (isset($applied_rules['if_min']) && !empty($applied_rules['if_min'])) ? $applied_rules['if_min'] : '0';
                                        $applied_max = (isset($applied_rules['if_max']) && !empty($applied_rules['if_max'])) ? $applied_rules['if_max'] : '0';
                                        $applied_type = (isset($applied_rules['if_type']) && !empty($applied_rules['if_type'])) ? $applied_rules['if_type'] : '';
                                        $discount_type = (isset($range_repeater['discount-type']) && !empty($range_repeater['discount-type'])) ? $range_repeater['discount-type'] : '';
                                        $discount_msg = str_replace(array('{{discount_name}}', '{{discount_amount}}', '{{discount_type}}'), array($discount_name, $discount, $discount_type), $message);
                                        if ($applied_min == $min && $applied_max == $max && $applied_type == $discount_type) {
                                            $this->wc_functions->displayMessage($discount_msg, 'success');
                                        } elseif ($min == 'empty' && $max == 'empty') {
                                            $this->wc_functions->displayMessage($discount_msg, 'success');
                                        }
                                        array_push($temp_array, $applied_rules['rule_id']);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @param $item_id
     * @return null
     * @throws \Exception
     */
    function addDiscountsToMetaData($item_id)
    {
        if (empty($item_id)) return NULL;
        $this->wc_functions->addToOrderMeta($item_id, '_' . $this->app_prefix . 'applied_discount_rules', json_encode($this->discounts));
        $this->wc_functions->addToOrderMeta($item_id, '_' . $this->app_prefix . 'applied_discount_amounts', json_encode($this->discounted_items));
    }

    /**
     * Show discount details in user order page
     * @param $item_id - item id
     * @param $item - product object
     * @return bool
     * @throws \Exception
     */
    function showDiscountDetailsInOrders($item_id, $item)
    {
        if (empty($item) || empty($item_id)) return NULL;
        $prefix = '_' . $this->app_prefix;
        $product_id = $this->wc_functions->getVariantId($item);
        if (empty($product_id)) {
            $product_id = $this->wc_functions->getProductId($item);
        }
        $item_quantity = $this->wc_functions->getOrderItemQuantity($item);
        $order_meta = $this->wc_functions->getOrderItemMeta($item_id);
        $saved_price = 0;
        if ($order_meta) {
            if (isset($order_meta[$prefix . 'applied_discount_amounts'])) {
                foreach ($order_meta[$prefix . 'applied_discount_amounts'] as $applied_discount_amount) {
                    $discounts = json_decode($applied_discount_amount, true);
                    if (!empty($discounts)) {
                        if (isset($discounts[$product_id])) {
                            $saved_price = $discounts[$product_id]['discount_price'] * $item_quantity;
                        }
                    }
                }
            }
        }
        if ($saved_price > 0) {
            /*$discount_message = __('Saved ', 'woocommerce-category-discount') . $this->wc_functions->makePrice($saved_price);
            echo '<p style="color:green">' . $discount_message . '</p>';*/
        }
    }

    /**
     * Hide custom meta details in admin page
     * @param $arr - to hide meta keys
     * @return array
     */
    function hideMetaDetailsOnAdminOrderPage($arr)
    {
        $arr[] = '_' . $this->app_prefix . 'applied_discount_rules';
        $arr[] = '_' . $this->app_prefix . 'applied_discount_amounts';
        return $arr;
    }

    /**
     * Add admin js
     */
    function adminEnqueueJs()
    {
        $js_file_path = plugin_dir_url(__DIR__) . 'src/js/';
        $file_name = array('category_discount_admin.js');
        foreach ($file_name as $file) {
            $this->wc_functions->adminEnqueueJs($js_file_path . $file);
        }
    }

    /**
     * @param $order_id
     * @return null
     * @throws \Exception
     */
    function adminOrderItemDiscountDetails($order_id)
    {
        if (empty($order_id)) return NULL;
        $order = $this->wc_functions->getOrder($order_id);
        $saved_price = 0;
        if ($order) {
            $order_items = $this->wc_functions->getOrderItems($order);
            if (!empty($order_items)) {
                $prefix = '_' . $this->app_prefix;
                foreach ($order_items as $order_item_id => $item) {
                    $product_id = $this->wc_functions->getVariantId($item);
                    if (empty($product_id)) {
                        $product_id = $this->wc_functions->getProductId($item);
                    }
                    $item_quantity = $this->wc_functions->getOrderItemQuantity($item);
                    $order_meta = $this->wc_functions->getOrderItemMeta($order_item_id);
                    if ($order_meta) {
                        if (isset($order_meta[$prefix . 'applied_discount_amounts'])) {
                            foreach ($order_meta[$prefix . 'applied_discount_amounts'] as $applied_discount_amount) {
                                $discounts = json_decode($applied_discount_amount, true);
                                if (!empty($discounts)) {
                                    if (isset($discounts[$product_id]['discount_price'])) {
                                        $saved_price += $discounts[$product_id]['discount_price'] * $item_quantity;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if ($saved_price > 0) {
            $excluded_discount_amount = $this->wc_functions->makePrice($saved_price);
            ?>
            <tr>
                <td class="label"><?php esc_html_e('Category discount:', 'woocommerce-category-discount'); ?></td>
                <td width="1%"></td>
                <td class="total">
                    <?php echo $excluded_discount_amount ?>
                </td>
            </tr>
            <?php
        }
    }

    /**
     * display discount rules from table format
     */
    public function discountPriceTable()
    {
        global $product;
        foreach ($this->discount_types as $discount_type) {
            $category_discount_rules = $discount_type->getRules();
            $category_general_settings = $discount_type->getGeneralSettings();
        }
        if (is_admin())
            return 0;
        $all_category_rules = isset($category_discount_rules[$this->prefix . 'category_discount_rules_group']) ? $category_discount_rules[$this->prefix . 'category_discount_rules_group'] : array();
        if (empty($all_category_rules))
            return 0;
        $product_categories = $this->wc_functions->getItemCategories($product);
        if (empty($product_categories))
            return 0;
        $category_rules_data = '';
        foreach ($all_category_rules as $rule_key => $rule) {
            if (isset($rule[$this->prefix . 'discount_range_repeater']) && !empty($rule[$this->prefix . 'discount_range_repeater'])) {
                $rule_name = ($rule[$this->prefix . 'discount_name']) ? $rule[$this->prefix . 'discount_name'] : 'Discount Rule';
                $enable_discount_rule = (isset($rule[$this->prefix . 'enable_discount_rule'])) ? $rule[$this->prefix . 'enable_discount_rule'] : 1;
                $discount_categories = (isset($rule[$this->prefix . 'discount_category']) && !empty($rule[$this->prefix . 'discount_category'])) ? $rule[$this->prefix . 'discount_category'] : array();
                if (empty($discount_categories) || !$enable_discount_rule)
                    continue;
                if (array_intersect($discount_categories, $product_categories)) {
                    $rule_rows = count($rule[$this->prefix . 'discount_range_repeater']);
                    $discount_starts_on = isset($rule[$this->prefix . 'discount_start_date']) ? $rule[$this->prefix . 'discount_start_date'] : '';
                    $discount_ends_on = isset($rule[$this->prefix . 'discount_end_date']) ? $rule[$this->prefix . 'discount_end_date'] : '';
                    if (!empty($discount_starts_on) && (strtotime(date('Y-m-d')) < strtotime($discount_starts_on)) || $rule_rows == 0 || !empty($discount_ends_on) && (strtotime(date('Y-m-d')) > strtotime($discount_ends_on)))
                        continue;
                    $i = 1;
                    foreach ($rule[$this->prefix . 'discount_range_repeater'] as $discount_range) {
                        $minimum_product_rule = (!empty($discount_range['minimum-product'])) ? $discount_range['minimum-product'] : 0;
                        $maximum_product_rule = (!empty($discount_range['maximum-product'])) ? $discount_range['maximum-product'] : 'above';
                        $discount_type = (!empty($discount_range['discount-type'])) ? $discount_range['discount-type'] : '';
                        $discount_price = (!empty($discount_range['amount'])) ? $discount_range['amount'] : 0;
                        if (($minimum_product_rule !== 0 || $maximum_product_rule !== 'above') && ($discount_type === '' || $discount_price === 0)) {
                            $rule_rows = $rule_rows - 1;
                            continue;
                        }
                        if ($discount_type != '' && $discount_type == 'flat') {
                            $final_price = $this->wc_functions->makePrice($discount_price);
                        } else {
                            $final_price = $discount_price . '%';
                        }
                        $rule_name_row_span = ($i == 1) ? '<td class="wcd_td_body_title" rowspan="' . $rule_rows . '">' . $rule_name . '</td>' : '';
                        $category_rules_data .= '<tr class="wcd_tr_body">' . $rule_name_row_span . '
                            <td class="wcd_td_body_range">' . $minimum_product_rule . ' - ' . $maximum_product_rule . '</td>
                            <td class="wcd_td_body_discount">' . $final_price . '</td>
                        </tr>';
                        $i++;
                    }
                }
            }
        }
        if (!empty($category_rules_data)) {
            //print_r($category_general_settings);die;
            ?>
            <table class="woocommerce_category_discount_rule_table">
            <thead>
            <tr class="wcd_tr_head">
                <th class="wcd_td_head_title"><?php if (!empty($category_general_settings['Wcd_discount_table_head']['rule_name'])) {
                        echo $category_general_settings['Wcd_discount_table_head']['rule_name'];
                    } else {
                        esc_html_e('Name', WCD_TEXT_DOMAIN);
                    } ?></th>
                <th class="wcd_td_head_range"><?php if (!empty($category_general_settings['Wcd_discount_table_head']['discount_range'])) {
                        echo $category_general_settings['Wcd_discount_table_head']['discount_range'];
                    } else {
                        esc_html_e('Range', WCD_TEXT_DOMAIN);
                    } ?></th>
                <th class="wcd_td_head_discount"><?php if (!empty($category_general_settings['Wcd_discount_table_head']['discount_value'])) {
                        echo $category_general_settings['Wcd_discount_table_head']['discount_value'];
                    } else {
                        esc_html_e('Discount', WCD_TEXT_DOMAIN);
                    } ?></th>
            </tr>
            </thead>
            <tbody><?php
            echo $category_rules_data; ?>
            </tbody>
            </table><?php
        }
    }

    public function minMaxDiscountRule()
    {
        global $woocommerce;
        $cart_obj = $woocommerce->cart;
        $cart_obj_content = $cart_obj->cart_contents;
        if (!is_object($cart_obj) && !is_array($cart_obj_content) && empty($cart_obj_content))
            return false;
        /**
         * get category rules
         */
        foreach ($this->discount_types as $discount_type) {
            $category_discount_rules = $discount_type->getRules();
        }
        $product_categories = array();
        /**
         *list the all categories
         */
        foreach ($cart_obj_content as $cart_item_key => $cart_item) {
            $product_categories_details = $this->get_product_categories($cart_item['data']);
            $product_id = $this->wc_functions->getItemId($cart_item['data']);
            $product_categories[$product_id] = array('quantity' => $cart_item['quantity'], 'categories' => $product_categories_details);
        }
        /**
         *check by each product
         */
        foreach ($cart_obj_content as $cart_item_key => $cart_item) {
            $matched_rules = array();
            $category_based_array = array();
            $product_categories_array = $this->get_product_categories($cart_item['data']);
            $product_id = $this->wc_functions->getItemId($cart_item['data']);
            $product_price = $this->wc_functions->getOriginalPrice($cart_item['data']);
            if (empty($product_categories_array))
                continue;
            if (!empty($category_discount_rules[$this->prefix . 'category_discount_rules_group'])) {
                foreach ($category_discount_rules[$this->prefix . 'category_discount_rules_group'] as $range_group_key => $range_group) {
                    if (isset($range_group[$this->prefix . 'discount_range_repeater']) && !empty($range_group[$this->prefix . 'discount_range_repeater'])) {
                        $enable_discount_rule = (isset($range_group[$this->prefix . 'enable_discount_rule'])) ? $range_group[$this->prefix . 'enable_discount_rule'] : 1;
                        $discount_starts_on = isset($range_group[$this->prefix . 'discount_start_date']) ? $range_group[$this->prefix . 'discount_start_date'] : '';
                        $discount_ends_on = isset($range_group[$this->prefix . 'discount_end_date']) ? $range_group[$this->prefix . 'discount_end_date'] : '';
                        if ($enable_discount_rule === 0 || !empty($discount_starts_on) && (strtotime(date('Y-m-d')) < strtotime($discount_starts_on)) || !empty($discount_ends_on) && (strtotime(date('Y-m-d')) > strtotime($discount_ends_on)))
                            continue;
                        $selected_admin_categories = $range_group[$this->prefix . 'discount_category'];
                        if (!empty($selected_admin_categories)) {
                            foreach ($selected_admin_categories as $selected_admin_category) {
                                $category_based_array[$selected_admin_category]['quantity'] = array();
                                if (!empty($product_categories)) {
                                    foreach ($product_categories as $pro_id => $details) {
                                        if (is_array($details['categories']) && in_array($selected_admin_category, $details['categories']) && in_array($selected_admin_category, $product_categories_array)) {// && !in_array($product_id, $cat_product_id)
                                            $quantity = (isset($category_based_array[$selected_admin_category]['quantity']) && !empty($category_based_array[$selected_admin_category]['quantity'])) ? $category_based_array[$selected_admin_category]['quantity'] : 0;
                                            $category_based_array[$selected_admin_category]['quantity'] = $quantity + $details['quantity'];
                                            //$cat_product_id[] = $product_id;
                                        }
                                    }
                                }
                                foreach ($product_categories_array as $cat_id) {
                                    if (is_array($category_based_array) && array_key_exists($cat_id, $category_based_array) && array_key_exists($selected_admin_category, $category_based_array)) {
                                        $cat_quantity = ($category_based_array[$cat_id]['quantity']) ? $category_based_array[$cat_id]['quantity'] : array();
                                        if (empty($cat_quantity))
                                            continue;
                                        foreach ($range_group[$this->prefix . 'discount_range_repeater'] as $discount_range) {
                                            $minimum_product_rule = (!empty($discount_range['minimum-product'])) ? $discount_range['minimum-product'] : 0;
                                            $maximum_product_rule = (!empty($discount_range['maximum-product'])) ? $discount_range['maximum-product'] : 'above';
                                            if ($minimum_product_rule === 0 && $maximum_product_rule === 'above')
                                                continue;
                                            $discount_type = (!empty($discount_range['discount-type'])) ? $discount_range['discount-type'] : '';
                                            $discount_price = (!empty($discount_range['amount'])) ? $discount_range['amount'] : 0;
                                            $product = $this->wc_functions->getItem($product_id);
                                            $product_price = ($this->wc_functions->getOriginalPrice($product)) ? $this->wc_functions->getOriginalPrice($product) : 0;
                                            if ($discount_type === '' || $discount_price === 0 || empty($product) || empty($product_price))
                                                continue;
                                            switch ($discount_type) {
                                                case 'percentage':
                                                    $discounted_price = (($discount_price / 100) * $product_price);
                                                    break;
                                                case 'flat':
                                                default:
                                                    $discounted_price = $discount_price;
                                                    break;
                                            }
                                            if (($minimum_product_rule <= $cat_quantity && $maximum_product_rule >= $cat_quantity) || ($minimum_product_rule != 0 && $minimum_product_rule <= $cat_quantity && $maximum_product_rule == 'above') || ($maximum_product_rule != 'above' && $maximum_product_rule >= $cat_quantity && $minimum_product_rule == 0)) {
                                                $matched_rules[] = array('price' => $discounted_price, 'rule' => $range_group, 'rule_id' => $range_group_key, 'if_min' => $minimum_product_rule, 'if_max' => $maximum_product_rule, 'if_type' => $discount_type);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            foreach ($this->discount_types as $discount_type) {
                if (empty($matched_rules) && empty($product_id) && empty($product_price))
                    continue;
                $discount_price = $discount_type->minMaxMatchedRule($matched_rules, $product_id);
                if ($product_price <= $discount_price) {
                    $cat_discounted_price = 0;
                } else {
                    $cat_discounted_price = (float)($product_price - $discount_price);
                }
                $woo_product = $cart_obj->cart_contents[$cart_item_key]['data'];
                $this->wc_functions->setItemPrice($woo_product, $cat_discounted_price);
                $category_discount = array('is_category_discount_applied' => 1, 'item_original_price' => $product_price, 'item_discounted_price' => $discount_price, 'applied_product_id' => $product_id);
                $cart_obj->cart_contents[$cart_item_key]['wcd_category_discount_details'] = $category_discount;

                $item = $this->wc_functions->getItem($product_id);
                $discount_price = get_option('woocommerce_tax_display_shop') == 'excl' ? $this->wc_functions->get_price_excluding_tax($item, 1, $discount_price) : $this->wc_functions->get_price_including_tax($item, 1, $discount_price);
                $product_price = get_option('woocommerce_tax_display_shop') == 'excl' ? $this->wc_functions->get_price_excluding_tax($item, 1, $product_price) : $this->wc_functions->get_price_including_tax($item, 1, $product_price);
                $this->discounted_items[$product_id]['discount_price'] = $discount_price;
                $this->discounted_items[$product_id]['original_price'] = $product_price;
                return $discounted_price;
            }
        }
    }

    public function get_product_categories($product)
    {
        $product_categories_array = $this->wc_functions->getItemCategories($product);
        if (empty($product_categories_array)) {
            $product_id = $this->wc_functions->getParentId($product);
            if (empty($product_id))
                return 0;
            $parent_item = $this->wc_functions->getItem($product_id);
            $product_categories_array = $this->wc_functions->getItemCategories($parent_item);
        }
        return $product_categories_array;
    }
}