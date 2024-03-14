<?php

namespace Wdr\App\Controllers;

use Wdr\App\Helpers\Helper;
use Wdr\App\Helpers\Rule;
use Wdr\App\Helpers\Woocommerce;
use Wdr\App\Models\DBTable;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class ManageDiscount extends Base
{
    public static $apply_as_coupon_values = array(), $available_rules = array(), $calculator, $on_sale_products = array(), $calculated_cart_item_discount = array(), $calculated_cart_discount = array(), $calculated_product_discount = array(), $cart_discounts = array(), $set_total_quantity = 0, $categories_slug = array(), $cart_tot_qty = array(), $applied_cart_coupon_discounts = array();
    public $free_shipping = false, $shipping_obj;

    /**
     * ManageDiscount constructor.
     */
    function __construct()
    {
        parent::__construct();
        // set available rules to static variables
        $this->getDiscountRules();
    }

    /**
     * get available rules
     * @return array|object|null
     */
    function getDiscountRules()
    {
        if (empty(self::$available_rules)) {
            $rule_helper = new Rule();
            self::$available_rules = $rule_helper->getAvailableRules($this->getAvailableConditions());
        }
        self::$calculator = new DiscountCalculator(self::$available_rules);
        return self::$available_rules;
    }

    /**
     * load required styles and scripts needed for site
     */
    function loadAssets()
    {
        $bulk_table_on_off = self::$config->getConfig('show_bulk_table', 0);
        $ajax_update_price = self::$config->getConfig('show_strikeout_when', 'show_when_matched');
        $minified_text = '';
        $compress_css_and_js = self::$config->getConfig('compress_css_and_js', 0);
        if($compress_css_and_js) $minified_text = '.min';
        // This have been added from v2.3.9. As it is added inline(Because it has only one css).
        //wp_enqueue_style(WDR_SLUG . '-customize-table-ui-css', WDR_PLUGIN_URL . 'Assets/Css/customize-table'.$minified_text.'.css', array(), WDR_VERSION);
        wp_enqueue_script('awdr-main', WDR_PLUGIN_URL . 'Assets/Js/site_main'.$minified_text.'.js', array('jquery'), WDR_VERSION, true);
        $awdr_front_end_script = array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => Helper::create_nonce('awdr_ajax_front_end'),
            'enable_update_price_with_qty' => $ajax_update_price,
            'refresh_order_review' => self::$config->getConfig('refresh_order_review', 0),
            'custom_target_simple_product' => apply_filters('advanced_woo_discount_rules_custom_target_for_simple_product_on_qty_update', ""),
            'custom_target_variable_product' => apply_filters('advanced_woo_discount_rules_custom_target_for_variable_product_on_qty_update', ""),
            'js_init_trigger' => apply_filters('advanced_woo_discount_rules_update_discount_price_init_trigger', ""),
            'awdr_opacity_to_bulk_table' => apply_filters('advanced_woo_discount_rules_opacity_to_bulk_table', ""),
            'awdr_dynamic_bulk_table_status' => $bulk_table_on_off,
            'awdr_dynamic_bulk_table_off' => apply_filters('advanced_woo_discount_rules_disable_load_dynamic_bulk_table', "on"),
            'custom_simple_product_id_selector' => apply_filters('advanced_woo_discount_rules_custom_simple_product_id_selector', ""),
            'custom_variable_product_id_selector' => apply_filters('advanced_woo_discount_rules_custom_variable_product_id_selector', ""),
        );
        wp_enqueue_script('awdr-dynamic-price', WDR_PLUGIN_URL . 'Assets/Js/awdr-dynamic-price'.$minified_text.'.js', array('jquery'), WDR_VERSION, true);
        wp_localize_script('awdr-main', 'awdr_params', $awdr_front_end_script);
    }

    /**
     * Check the product is in sale
     * @param $on_sale
     * @param $product
     * @return bool
     */
    function isProductInSale($on_sale, $product)
    {
        if(is_null($product)){
            return $on_sale;
        }
        remove_filter('woocommerce_product_is_on_sale', array($this, 'isProductInSale'), 100);
        //Need to check if conditions also passed
        $show_on_sale_badge = self::$config->getConfig('show_on_sale_badge', 'disabled');
        if ($show_on_sale_badge == 'when_condition_matches') {
            if (self::$woocommerce_helper->productTypeIs($product, 'variable')) {
                $price_html = $this->getVariablePriceHtml('', $product);
            } else {
                // pass empty string as first argument in 'woocommerce_get_price_html' method to check if rule has been applied
                // empty result means that price was not change
                $price_html = $this->getPriceHtml('', $product);
            }
        } else {
            $price_html = self::$calculator->saleBadgeDisplayChecker($product, true);
        }
        if($price_html !== '' && $price_html === false){
            $product_id = Woocommerce::getProductId($product);
            self::updateProductsAsOnSale($product_id);
        }
        add_filter('woocommerce_product_is_on_sale', array($this, 'isProductInSale'), 100, 2);
        return $on_sale OR $price_html;
    }

    /**
     * Set product is on sale in static variable for avoid multiple run
     * This helps for sale badge text override
     * */
    protected static function updateProductsAsOnSale($product_id){
        if(!empty(self::$on_sale_products)){
            if(!in_array($product_id, self::$on_sale_products)){
                self::$on_sale_products[] = $product_id;
            }
        } else {
            self::$on_sale_products[] = $product_id;
        }

    }

    /**
     * Is product on sale through our plugin
     * This helps for sale badge text override
     * */
    protected static function isProductOnSale($product_id){
        if(!in_array($product_id, self::$on_sale_products)){
            return true;
        }

        return false;
    }

    /**
     * Replace sale tag text
     * */
    public function replaceSaleTagText($html, $post, $product){
        if(!is_object($product)){
            return $html;
        }
        $use_sale_badge_customize = apply_filters('advanced_woo_discount_rules_use_sale_badge_customization', false, $post, $product);
        $use_sale_badge_percentage_customize = apply_filters('advanced_woo_discount_rules_use_sale_badge_percentage_customization', true, $post, $product);
        $product_id = Woocommerce::getProductId($product);
        if(self::isProductOnSale($product_id) || $use_sale_badge_customize){
            $display_percentage_on_sale_badge = self::$config->getConfig('display_percentage_on_sale_badge', '');
            if($display_percentage_on_sale_badge == 1 && $use_sale_badge_percentage_customize === true){
                if (Woocommerce::productTypeIs($product, array('variable'))) {
                    $variation_product = Woocommerce::getFirstChildOfVariableProduct($product);
                    if ($variation_product) {
                        $product = $variation_product;
                    }
                }
                $discount = self::calculateProductDiscountPrice(false, $product, 1, 0, 'all', true, false);
                if($discount !== false && is_array($discount)){
                    $percentage_value = $discount_value = 0;
                    if (!empty($discount['initial_price']) && isset($discount['discounted_price'])) {
                        $percentage_value = (($discount['initial_price'] - $discount['discounted_price']) / $discount['initial_price'])  * 100;
                    }
                    $percentage = apply_filters('advanced_woo_discount_rules_percentage_value_on_sale_badge', round($percentage_value, 2), $percentage_value, $product);
                    if (isset($discount['initial_price_with_tax']) && isset($discount['discounted_price_with_tax'])) {
                        $discount_value = $discount['initial_price_with_tax'] - $discount['discounted_price_with_tax'];
                    }
                    $discount_value_to_display = Woocommerce::formatPrice(($discount_value));
                    $on_sale_badge_percentage_html = self::$config->getConfig('on_sale_badge_percentage_html', '<span class="onsale">{{percentage}}%</span>');
                    $translate = __('<span class="onsale">{{percentage}}%</span>', 'woo-discount-rules');
                    $on_sale_badge_percentage_html = Helper::getCleanHtml($on_sale_badge_percentage_html);
                    $html = __($on_sale_badge_percentage_html, 'woo-discount-rules');
                    $html = str_replace('{{percentage}}', $percentage, $html);
                    $html = str_replace('{{discount_value}}', $discount_value_to_display, $html);
                    $html = apply_filters('advanced_woo_discount_rules_on_sale_badge_html', $html, $post, $product);

                    return $html;
                }
            }
            $on_sale_badge_html = self::$config->getConfig('on_sale_badge_html', '<span class="onsale">Sale!</span>');
            $translate = __('<span class="onsale">Sale!</span>', 'woo-discount-rules');
            $on_sale_badge_html = Helper::getCleanHtml($on_sale_badge_html);
            $html = __($on_sale_badge_html, 'woo-discount-rules');
            $html = apply_filters('advanced_woo_discount_rules_on_sale_badge_html', $html, $post, $product);
        }

        return $html;
    }

    /**
     * get product sale price
     * @param $value
     * @param $product
     * @return mixed
     */
    function getProductSalePrice($value, $product)
    {
        $prices = self::calculateInitialAndDiscountedPrice($product, 1);
        if (!empty($prices['discounted_price_with_tax']))
            $value = $prices['discounted_price_with_tax'];
        return $value;
    }

    /**
     * get product regular price
     * @param $value
     * @param $product
     * @return mixed
     */
    function getProductRegularPrice($value, $product)
    {
        $prices = self::calculateInitialAndDiscountedPrice($product, 1);
        if (!empty($prices['initial_price_with_tax']))
            $value = $prices['initial_price_with_tax'];
        return $value;
    }

    function doProcessStrikeOut($price_html, $product, $quantity = 1, $ajax_price = false){
        if(isset($_REQUEST['action'])){
            $blocked_actions = array('inline-save');
            if(in_array($_REQUEST['action'], $blocked_actions)){
                return false;
            }
        }
        return true;
    }

    /**
     * Modify the product's price when discount applicable.
     * @param $price_html
     * @param $product
     * @param int $quantity
     * @param boolean $ajax_price
     * @return mixed
     */
    function getPriceHtml($price_html, $product, $quantity = 1, $ajax_price = false)
    {
        if(is_null($product)){
            return $price_html;
        }
        if($this->doProcessStrikeOut($price_html, $product, $quantity, $ajax_price)){
            if (empty(self::$available_rules)) {
                if($ajax_price){
                    return false;
                }
                return $price_html;
            }
            $disable_strikeout_for_product_types = apply_filters('advanced_woo_discount_rules_disable_strikeout_for_product_types', array('grouped'), $product, $ajax_price);
            if (self::$woocommerce_helper->productTypeIs($product, $disable_strikeout_for_product_types)) {
                return $price_html;
            }

            // $initial_price_html = $price_html;
            $initial_price_html = $this->getCalculateDiscountPriceFrom($product, $price_html, $ajax_price);

            $modify_price = apply_filters('advanced_woo_discount_rules_modify_price_html', true, $price_html, $product, $quantity);
            if (!$modify_price) {
                if($ajax_price){
                    return false;
                }
                return $price_html;
            }

            if(empty(self::$config->getConfig('modify_price_at_product_page', 1)) && empty(self::$config->getConfig('modify_price_at_shop_page', 1)) && empty(self::$config->getConfig('modify_price_at_category_page', 1))){
                if($ajax_price){
                    return false;
                }
                return $price_html;
            }

            if (is_product() && empty(self::$config->getConfig('modify_price_at_product_page', 1))) {
                if($ajax_price){
                    return false;
                }
                return $price_html;
            }
            if (is_shop() && empty(self::$config->getConfig('modify_price_at_shop_page', 1))) {
                if($ajax_price){
                    return false;
                }
                return $price_html;
            }
            if (is_product_category() && empty(self::$config->getConfig('modify_price_at_category_page', 1))) {
                if($ajax_price){
                    return false;
                }
                return $price_html;
            }
            if ($ajax_price) {
                $price_html = $initial_price_html;
                $quantity = $this->input->post('qty', '');
                if(!$price_html || empty($quantity)){
                    return false;
                }
            }

            //Check the product object is from WC_Product Class
            if (is_a($product, 'WC_Product')) {
                if(!$ajax_price) {
                    if (self::$woocommerce_helper->productTypeIs($product, array('variable'))) {
                        return $price_html;
                    }
                }
                $product_id = Woocommerce::getProductId($product);
                //Calculate the product price
                $prices = self::calculateInitialAndDiscountedPrice($product, $quantity, $is_cart = false, $ajax_price);
                $apply_as_cart_rule = isset($prices['apply_as_cart_rule']) ? $prices['apply_as_cart_rule'] : array('no');
                if(!empty($apply_as_cart_rule)){
                    if(!in_array('no', $apply_as_cart_rule)){
                        return $price_html;
                    }
                }
                if($ajax_price){
                    $discount_details = (isset($prices['total_discount_details'])) ? $prices['total_discount_details'] : false;
                    if($discount_details) {
                        // $initial_price = (isset($prices['initial_price_with_tax'])) ? $prices['initial_price_with_tax'] : 0;
                        //$discounted_price = (isset($prices['discounted_price_with_tax'])) ? $prices['discounted_price_with_tax'] : 0;
                        $initial_price = isset($prices['initial_price']) ? $prices['initial_price'] : 0;
                        $discounted_price = isset($prices['discounted_price']) ? $prices['discounted_price'] : 0;
                        if(empty($initial_price) || empty($discounted_price) || empty($discount_details)){
                            return $price_html;
                        }
                        $strikeout_html = $this->getSetDiscountItemPriceHtml($discount_details, $initial_price, $discounted_price, $product, $price_html = true, $quantity, $ajax_price);
                        return apply_filters('advanced_woo_discount_rules_dynamic_price_html_update', $strikeout_html, $product, $initial_price, $discounted_price, $discount_details, $prices);
                        //return $this->getSetDiscountItemPriceHtml($discount_details, $initial_price, $discounted_price, $product, $price_html = true, $quantity, $ajax_price);
                    }else{
                        return false;
                    }
                }else {
                    if (!$prices) {
                        return $price_html;
                    }
                    self::$calculated_product_discount[$product_id] = $prices;
                    $initial_price_with_tax_call = $discounted_price_with_tax_call = 0;
                    $calculator = self::$calculator;
                    $initial_price = isset($prices['initial_price']) ? $prices['initial_price'] : 0;
                    $discounted_price = isset($prices['discounted_price']) ? $prices['discounted_price'] : 0;
                    if(!empty($initial_price)){
                        $initial_price_with_tax_call = $calculator->mayHaveTax($product, $initial_price);
                    }
                    if(!empty($discounted_price)){
                        $discounted_price_with_tax_call = $calculator->mayHaveTax($product, $discounted_price);
                    }
                    $price_html = $this->getStrikeoutPrice($initial_price_with_tax_call, $discounted_price_with_tax_call, true, false, $initial_price_html);
                    $price_html = $price_html.Woocommerce::getProductPriceSuffix($product, $discounted_price_with_tax_call, $prices);
                }

            }
        }

        return $price_html;
    }

    /**
     * getCalculateDiscountPriceFrom
     *
     * @param $product
     * @param $price_html
     * @param $ajax_price
     * @return string
     */
    function getCalculateDiscountPriceFrom($product, $price_html, $ajax_price){
        $calculate_discount_from = self::$config->getConfig('calculate_discount_from', 'sale_price');
        if ($calculate_discount_from == 'regular_price') {
            $product_price = self::$woocommerce_helper->getProductRegularPrice($product);
            $calculator = self::$calculator;
            if(!empty($product_price)){
                $product_price = $calculator->mayHaveTax($product, $product_price);
            }
            $price_html = self::$woocommerce_helper->formatPrice($product_price);
            return $price_html.Woocommerce::getProductPriceSuffix($product, $product_price);
        }
        if($ajax_price){
            $product_price = self::$woocommerce_helper->getProductPrice($product);
            $price_html = self::$woocommerce_helper->formatPrice($product_price);
            return $price_html.Woocommerce::getProductPriceSuffix($product, $product_price);
        }
        return $price_html;
    }

    /**
     * Modify the product's price before modified by our plugin to adjust sale price.
     *
     * @param $price_html
     * @param $product
     * @param int $quantity
     * @return mixed
     * */
    function getPriceHtmlSalePriceAdjustment($price_html, $product, $quantity = 1)
    {
        if(is_null($product)){
            return $price_html;
        }
        if (empty(self::$available_rules)) {
            return $price_html;
        }
        $show_on_sale_badge = self::$config->getConfig('show_on_sale_badge', 'disabled');

        if($show_on_sale_badge != 'at_least_has_any_rules' ){
            return $price_html;
        }

        $modify_price = apply_filters('advanced_woo_discount_rules_modify_sale_price_adjustment_html', true, $price_html, $product, $quantity);
        if (!$modify_price) {
            return $price_html;
        }
        $excluded_product_type = apply_filters('advanced_woo_discount_rules_exclude_product_type_for_sale_price_strikeout_adjustment', array('variable', 'subscription_variation', 'variable-subscription', 'grouped', 'composite'), $product);
        if (is_array($excluded_product_type) && !empty($excluded_product_type)) {
            if (!Woocommerce::productTypeIs($product, $excluded_product_type)) {
                $sale_price = Woocommerce::getProductSalePrice($product);
                $regular_price = Woocommerce::getProductPrice($product);
                if ($sale_price <= 0) {
                    if($regular_price > 0){
                        $regular_price = get_option('woocommerce_tax_display_shop') == 'excl' ? Woocommerce::getExcludingTaxPrice($product, 1, $regular_price) : Woocommerce::getIncludingTaxPrice($product, 1, $regular_price);
                        $price_to_display = Woocommerce::formatPrice($regular_price);
                        $price_html = (($price_to_display) . Woocommerce::getProductPriceSuffix($product));
                    }
                    return $price_html;
                }
            }
        }
        return $price_html;
    }

    /**
     * Remove duplicate strikeout price
     * */
    function removeDuplicateStrikeoutPrice($item_price){
        $del_pattern = "/<del>(.*?)<\/del>/s";
        preg_match($del_pattern, $item_price, $matches);
        $del_content = isset($matches[1]) ? $matches[1] : '';
        if($del_content === ''){
            $del_pattern = '/<del aria-hidden="true">(.*?)<\/del>/s';
            preg_match($del_pattern, $item_price, $matches);
            $del_content = isset($matches[1]) ? $matches[1] : '';
        }
        $del_content = trim(strip_tags($del_content));
        $ins_pattern = "/<ins>(.*?)<\/ins>/s";
        preg_match($ins_pattern, $item_price, $matches);
        $ins_content_org = isset($matches[1]) ? $matches[1] : '';
        $ins_content = trim(strip_tags($ins_content_org));

        if(!empty($del_content) && !empty($ins_content)){
            if($del_content == $ins_content){
                $item_price = $ins_content_org;
            }
        }

        return $item_price;
    }

    /**
     * Variable product
     *
     * @param $price_html
     * @param $product
     * @param int $quantity
     * @return mixed|string
     */
    function getVariablePriceHtml($price_html, $product, $quantity = 1)
    {
        if(is_null($product)){
            return $price_html;
        }
        if($this->doProcessStrikeOut($price_html, $product, $quantity)){
            if (empty(self::$available_rules)) {
                return $price_html;
            }
            $modify_price = apply_filters('advanced_woo_discount_rules_modify_price_html', true, $price_html, $product, $quantity);
            if (!$modify_price) {
                return $price_html;
            }

            if(empty(self::$config->getConfig('modify_price_at_product_page', 1)) && empty(self::$config->getConfig('modify_price_at_shop_page', 1)) && empty(self::$config->getConfig('modify_price_at_category_page', 1))){
                return $price_html;
            }

            if (is_product() && empty(self::$config->getConfig('modify_price_at_product_page', 1))) {
                return $price_html;
            }
            if (is_shop() && empty(self::$config->getConfig('modify_price_at_shop_page', 1))) {
                return $price_html;
            }
            if (is_product_category() && empty(self::$config->getConfig('modify_price_at_category_page', 1))) {
                return $price_html;
            }

            $original_prices_list = $sale_prices_lists = $discount_prices_lists = array();
            $available_variations = Woocommerce::getProductChildren($product);

            if (!empty($available_variations)) {
                $consider_out_of_stock_variants = apply_filters('advanced_woo_discount_rules_do_strikeout_for_out_of_stock_variants', false);
                $variants_check_limit = (int) apply_filters('advanced_woo_discount_rules_check variants_limit_for_variable_strikeout', 20);

                $variations = [];
                if (count($available_variations) > $variants_check_limit) {
                    $variation_prices = self::$woocommerce_helper->getVariationPrices($product);
                    $calculate_discount_from = self::$config->getConfig('calculate_discount_from', 'sale_price');
                    $original_prices = ($calculate_discount_from) == 'regular_price' ? $variation_prices['regular_price'] : $variation_prices['price'];
                    if (!empty($original_prices)){
                        $variations[] = array_keys($original_prices, min($original_prices))[0];
                        $variations[] = array_keys($original_prices, max($original_prices))[0];
                    }
                } else {
                    $variations = $available_variations;
                }
                foreach ($variations as $variation_id) {
                    if (empty($variation_id)) {
                        continue;
                    }
                    $variation = Woocommerce::getProduct($variation_id);
                    if(!Woocommerce::variationIsVisible($variation)){
                        continue;
                    }
                    if(!Woocommerce::isProductHasStock($variation) && !$consider_out_of_stock_variants) {
                        continue;
                    }
                    $prices = self::calculateInitialAndDiscountedPrice($variation, $quantity);
                    if (!isset($prices['initial_price']) || !isset($prices['discounted_price'])) {
                        $original_prices_list[] = Woocommerce::getProductRegularPrice($variation);
                        $sale_prices_lists[] = Woocommerce::getProductPrice($variation);
                        continue;
                    }
                    $original_prices_list[] = $prices['initial_price'];
                    $discount_prices_lists[] = $prices['discounted_price'];
                }
            }
            $discount_prices_lists = array_unique($discount_prices_lists);
            $original_prices_list = array_unique($original_prices_list);
            if(empty($discount_prices_lists)){
                return $this->removeDuplicateStrikeoutPrice($price_html);
            }
            if(empty($original_prices_list)){
                return $this->removeDuplicateStrikeoutPrice($price_html);
            }
            $discount_prices_lists = array_merge($discount_prices_lists, $sale_prices_lists);
            $min_price = min($discount_prices_lists);
            $max_price = max($discount_prices_lists);
            $min_original_price = min($original_prices_list);
            $max_original_price = max($original_prices_list);
            $calculator = self::$calculator;
            if(!empty($min_original_price)){
                $min_original_price = $calculator->mayHaveTax($product, $min_original_price);
            }
            if(!empty($max_original_price)){
                $max_original_price = $calculator->mayHaveTax($product, $max_original_price);
            }
            if(!empty($min_price)){
                $min_price = $calculator->mayHaveTax($product, $min_price);
            }
            if(!empty($max_price)){
                $max_price = $calculator->mayHaveTax($product, $max_price);
            }
            $min_original_price_range_suffix = self::$woocommerce_helper->getProductPriceSuffix($product, $min_original_price);
            if ($min_original_price == $max_original_price) {
                $price_html = self::$woocommerce_helper->formatPrice($min_original_price) . $min_original_price_range_suffix;
            } elseif ($min_original_price < $max_original_price) {
                $price_html = self::$woocommerce_helper->formatPriceRange($min_original_price, $max_original_price, true) . $min_original_price_range_suffix;
            }
            $min_price_range_suffix = self::$woocommerce_helper->getProductPriceSuffix($product, $min_price);
            if ($min_price == $max_price) {
                $price_html_discounted = self::$woocommerce_helper->formatPrice($min_price) . $min_price_range_suffix;
                return $this->getStrikeoutPrice($price_html, $price_html_discounted, false, true);
            } elseif ($min_price < $max_price) {
                $price_html_discounted = self::$woocommerce_helper->formatPriceRange($min_price, $max_price, false) . $min_price_range_suffix;
                return $this->getStrikeoutPrice($price_html, $price_html_discounted, false, true);
            }
        }

        return $price_html;
    }

    /**
     * override original price html with discounted price html
     * @param $original_price
     * @param $discounted_price
     * @param $format_price
     * @param $is_variable_product
     * @param $initial_price_html
     * @return string
     */
    function getStrikeoutPrice($original_price, $discounted_price, $format_price = true, $is_variable_product = false, $initial_price_html=false)
    {
        $separator = ($is_variable_product) ? '<br>' : '&nbsp;';
        $original_price_raw = $original_price;
        $discounted_price_raw = $discounted_price;
        if ($original_price == $discounted_price) {
            if ($format_price) {
                $discounted_price = self::$woocommerce_helper->formatPrice($discounted_price);
            }
            $html = '<ins>' . $discounted_price . '</ins>';
        } else {
            if ($format_price) {
                $original_price = self::$woocommerce_helper->formatPrice($original_price);
                $discounted_price = self::$woocommerce_helper->formatPrice($discounted_price);
            }

            if($initial_price_html){
                $initial_price_html = preg_replace('/<del>.*<\/del>/', '', $initial_price_html);
                $initial_price_html = preg_replace('/<del aria-hidden="true">.*<\/del>/', '', $initial_price_html);
                $html = '<del>' . $initial_price_html . '</del>' . $separator . '<ins>' . $discounted_price . '</ins>';
            }else{
                $html = '<del>' . $original_price . '</del>' . $separator . '<ins>' . $discounted_price . '</ins>';
            }
        }
        return apply_filters('advanced_woo_discount_rules_strikeout_price_html', $html, $original_price, $discounted_price, $is_variable_product, $initial_price_html, $separator, $original_price_raw, $discounted_price_raw);
    }

    /**
     * override original price html with set discounted price html
     *
     * @param int $original_price
     * @param array $partially_qualified_sets
     * @param $other_discounts
     * @param $total_discount
     * @param $total_quantity
     * @param $product_obj
     * @param $return_html
     * @param $current_product_quantity
     * @param $discount_operator
     * @param $partially_qualified_set_amount_duplicate
     * @return bool|string
     */
    function getSetStrikeoutPrice($original_price, $partially_qualified_sets, $total_discount, $other_discounts, $total_quantity = 0, $product_obj='', $return_html = true, $current_product_quantity=0, $discount_operator=0, $partially_qualified_set_amount_duplicate=array())
    {
        $discounted_price = null;
        if (!empty($original_price) && is_array($partially_qualified_sets) && !empty($partially_qualified_sets) && !empty($total_quantity)) {
            $counts = count($partially_qualified_sets);
            if (!empty($partially_qualified_set_amount_duplicate)) {
                $duplicate_counts = count($partially_qualified_set_amount_duplicate);
                $total_counts = $counts + $duplicate_counts;
                $counts = $total_counts;
                $total_counts_inc = $total_counts;
                $partially_qualified_set = array();
                foreach ($partially_qualified_sets as $amount_key => $quantity_key) {
                    $total_counts_inc++;
                    $partially_qualified_set[$amount_key . '-' . $total_counts_inc] = $quantity_key;
                }
                $partially_qualified_sets = array_merge($partially_qualified_set_amount_duplicate, $partially_qualified_set);
            }
            asort($partially_qualified_sets);

            $price_html = '';
            $calculate_product_price = array();
            $applied_quantity = $discounted_price_for_set = $not_applied = $total_applied = 0;
            $calculator = self::$calculator;
            for ($i = 1; $i <= $counts; $i++) {
                if (is_array($partially_qualified_sets) && !empty($partially_qualified_sets)) {
                    //get smallest discount quantity
                    $min_applied_quantity = min($partially_qualified_sets);
                    //calculate next smallest quantity
                    if (!empty($applied_quantity)) {
                        $last_applied = $min_applied_quantity - $applied_quantity;
                    } else {
                        $last_applied = $min_applied_quantity;
                    }
                    $applied_quantity = $min_applied_quantity;
                    //calculate total applied quantity
                    $total_applied += $last_applied;
                    //Discount price calculation for set
                    if ($i == 1) {
                        $discounted_price = $original_price - $total_discount;
                    } else {
                        foreach ($partially_qualified_sets as $price => $quantity) {
                            if (!empty($partially_qualified_set_amount_duplicate)) {
                                $price_array = explode("-", $price);
                                $price = $price_array[0];
                            }
                            $discounted_price_for_set += $price;
                        }
                        $discounted_price = ($original_price - ($discounted_price_for_set + $other_discounts));
                    }
                    if ($discounted_price <= 0) {
                        $discounted_price = 0;
                    }
                    $original_price_with_tax_call = $discounted_price_with_tax_call = 0;
                    if(!empty($original_price)){
                        $original_price_with_tax_call = $calculator->mayHaveTax($product_obj, $original_price);
                    }
                    if(!empty($discounted_price)){
                        $discounted_price_with_tax_call = $calculator->mayHaveTax($product_obj, $discounted_price);
                    }
                    if (!empty($discounted_price)) {
                        $calculate_product_price[] = $discounted_price * $last_applied;
                    }
                    $price_suffix = Woocommerce::getProductPriceSuffix($product_obj, $discounted_price);
                    $price_html .= '<del>' . self::$woocommerce_helper->formatPrice($original_price_with_tax_call) . '</del>&nbsp;<ins>' . self::$woocommerce_helper->formatPrice($discounted_price_with_tax_call) .$price_suffix. '&nbsp;x&nbsp;' . $last_applied . '</ins><br/>';
                    //remove calculated set
                    $min_applied_range_keys = array_keys($partially_qualified_sets, $min_applied_quantity);
                    foreach ($min_applied_range_keys as $key) {
                        unset($partially_qualified_sets[$key]);
                    }
                }
            }
            $original_price_with_tax_call = $discounted_price_with_tax_call = 0;
            //get original price quantity
            if ($discount_operator === 'total_qty_in_cart') {
                $total_quantity = $current_product_quantity;
            }
            $not_applied = (int)$total_quantity - (int)$total_applied;

            //calculate original price quantities discount.

            if (!empty($other_discounts)) {
                $calculate_product_price_total = $original_price * $not_applied;
                $discounted_price = $calculate_product_price_total - $other_discounts;
                //$discounted_price = $calculator->mayHaveTax($product_obj, $discounted_price);
                if ($discounted_price < 0) {
                    $discounted_price = 0;
                }
                $calculate_product_price[] = $discounted_price;
                if(!empty($original_price)){
                    $original_price_with_tax_call = $calculator->mayHaveTax($product_obj, $original_price);
                }
                if(!empty($discounted_price)){
                    $discounted_price_with_tax_call = $calculator->mayHaveTax($product_obj, $discounted_price);
                }
                $price_suffix = Woocommerce::getProductPriceSuffix($product_obj, $discounted_price_with_tax_call);
                $price_html .= '<del>' . self::$woocommerce_helper->formatPrice($original_price_with_tax_call) . '</del>&nbsp;<ins>' . self::$woocommerce_helper->formatPrice($discounted_price_with_tax_call) .$price_suffix. '&nbsp;x&nbsp;' . $not_applied . '</ins>';
            } else {
                if(!empty($original_price)){
                    $original_price_with_tax_call = $calculator->mayHaveTax($product_obj, $original_price);
                }
                $price_suffix = Woocommerce::getProductPriceSuffix($product_obj, $original_price_with_tax_call);
                $calculate_product_price[] = $original_price * $not_applied;
                $price_html .= '<ins>' . self::$woocommerce_helper->formatPrice($original_price_with_tax_call) .$price_suffix. '&nbsp;x&nbsp;' . $not_applied . '</ins>';
            }
            if ($return_html) {
                return apply_filters('advanced_woo_discount_rules_strikeout_for_set_discount_price_html', $price_html, $original_price, $discounted_price, $total_discount, $other_discounts, $not_applied);
            } else {
                $total_product_price = array_sum($calculate_product_price);
                $single_product_price = $total_product_price;
                if($total_quantity != 0){
                    $single_product_price = $total_product_price / $total_quantity;
                }
                return $single_product_price;
            }
        }
        return false;
    }

    /**
     * Apply cart discount fees
     * @param $cart
     */
    function applyCartDiscount($cart)
    {
        $discount_apply_type = self::$config->getConfig('apply_cart_discount_as', 'coupon');
        if($discount_apply_type == "fee"){
            $combine_all_discounts = self::$config->getConfig('combine_all_cart_discounts', 0);
            $total_combined_discounts = 0;
            $apply_as_cart_fee_details = DiscountCalculator::$price_discount_apply_as_cart_discount;
            $flat_in_subtotal = array();
            $discount_coupons = array();
            if(!empty($apply_as_cart_fee_details)){
                foreach ($apply_as_cart_fee_details as $rule_id => $product_id){
                    $discount_value = 0;
                    foreach ($product_id as $detail) {
                        if($detail['discount_type'] == 'wdr_cart_discount' && $detail['apply_type'] == 'flat_in_subtotal'){
                            if(!isset($flat_in_subtotal[$rule_id])){
                                $flat_in_subtotal[$rule_id]['value'] = $detail['discounted_price'];
                                $flat_in_subtotal[$rule_id]['label'] = $detail['discount_label'];
                                if (!isset($discount_coupons[$rule_id])) {
                                    $discount_coupons[$rule_id] = array('discount_label' => $detail['discount_label'], 'discount_value' => $detail['discounted_price']);
                                }
                            }
                        }else{
                            $discount_value += $detail['discounted_price'];
                            $label = (isset($detail['discount_label']) && !empty($detail['discount_label'])) ? $detail['discount_label'] : $detail['rule_name'];
                            if (!isset($discount_coupons[$rule_id])) {
                                $discount_coupons[$rule_id] = array('discount_label' => $label, 'discount_value' => $detail['discounted_price']);
                            } else {
                                $discount_coupons[$rule_id]['discount_value'] += $detail['discounted_price'];
                            }
                        }
                    }
                    if ($discount_value > 0) {
                        if (empty($combine_all_discounts)) {
                            $discount_value = -1 * $discount_value;
                            $fee_name = apply_filters('advanced_woo_discount_rules_additional_fee_label', $label, $cart);
                            $fee_amount = apply_filters('advanced_woo_discount_rules_additional_fee_amount', $discount_value, $cart);
                            self::setCartCouponDiscountDetails($fee_name, $label, -1 * $fee_amount, array($rule_id), $discount_coupons);
                            Woocommerce::addCartFee($cart, $fee_name, $fee_amount);
                        }else{
                            $total_combined_discounts += $discount_value;
                        }
                        self::$calculated_cart_discount['discount'][] = array('price' => $discount_value, 'label' => $label);
                    }
                }
            }
            if (!empty($flat_in_subtotal)) {
                foreach ($flat_in_subtotal as $rule_id => $discount){
                    if(empty($combine_all_discounts)){
                        $discount_value = -1 * $discount['value'];
                        $label = $discount['label'];
                        $fee_name = apply_filters('advanced_woo_discount_rules_additional_fee_label', $label, $cart);
                        $fee_amount = apply_filters('advanced_woo_discount_rules_additional_fee_amount', $discount_value, $cart);
                        self::setCartCouponDiscountDetails($fee_name, $label, -1 * $fee_amount, array($rule_id), $discount_coupons);
                        Woocommerce::addCartFee($cart, $fee_name, $fee_amount);
                    }else{
                        $total_combined_discounts += $discount['value'];
                    }
                }
            }

            //Combine all discounts and add as single discounts
            if (!empty($total_combined_discounts) && !empty($combine_all_discounts)) {
                $label = self::$config->getConfig('discount_label_for_combined_discounts', __('Cart discount', 'woo-discount-rules'));
                $label = Helper::getCleanHtml($label);
                if ($discount_apply_type == 'fee') {
                    $total_combined_discounts = -1 * $total_combined_discounts;
                    $fee_name = apply_filters('advanced_woo_discount_rules_additional_fee_label', $label, $cart);
                    $fee_amount = apply_filters('advanced_woo_discount_rules_additional_fee_amount', $total_combined_discounts, $cart);
                    self::setCartCouponDiscountDetails($fee_name, $label, -1 * $fee_amount, array_keys($discount_coupons), $discount_coupons);
                    Woocommerce::addCartFee($cart, $fee_name, $fee_amount);
                }
            }
            DiscountCalculator::$price_discount_apply_as_cart_discount = array();
        }
    }

    /**
     * change the coupon label
     * @param $label
     * @param $coupon
     * @return mixed
     */
    function overwriteCouponLabel($label, $coupon)
    {
        $coupon_code = self::$woocommerce_helper->getCouponCode($coupon);
        if (!empty($coupon_code) && isset(self::$calculated_cart_discount['discount'][$coupon_code]['label'])) {
            return self::$calculated_cart_discount['discount'][$coupon_code]['label'];
        }
        return $label;
    }

    /**
     * Manage the virtual coupon provided for cart
     * @param $response
     * @param $coupon_code
     * @return array
     */
    function manageVirtualCoupon($response, $coupon_code)
    {
        if (isset(self::$calculated_cart_discount['discount'][$coupon_code])) {
            return array(
                'id' => 321123 . rand(2, 9),
                'amount' => self::$calculated_cart_discount['discount'][$coupon_code]['price'],
                'individual_use' => false,
                'product_ids' => array(),
                'exclude_product_ids' => array(),
                'usage_limit' => '',
                'usage_limit_per_user' => '',
                'limit_usage_to_x_items' => '',
                'usage_count' => '',
                'date_created' => apply_filters('advanced_woo_discount_rules_custom_coupon_date_created', date('Y-m-d')),
                'expiry_date' => '',
                'apply_before_tax' => 'yes',
                'free_shipping' => false,
                'product_categories' => array(),
                'exclude_product_categories' => array(),
                'exclude_sale_items' => false,
                'minimum_amount' => '',
                'maximum_amount' => '',
                'customer_email' => '',
                'discount_type' => (self::$calculated_cart_discount['discount'][$coupon_code]['type'] == 'flat') ? 'fixed_cart' : 'percent'
            );
        }
        return $response;
    }

    /**
     * Apply the custom coupon for create coupon condition
     * @param $response
     * @param $coupon_code
     * @return array
     */
    function checkCouponToApply($response, $coupon_code)
    {
        if($coupon_code !== false && $coupon_code !== 0){
            $rule_helper = new Rule();
            $available_coupons = $rule_helper->getAllDynamicCoupons();
            if (in_array($coupon_code, $available_coupons)) {
                $amount = 0;
                $coupon = array(
                    'id' => time() . rand(2, 9),
                    'amount' => $amount,
                    'individual_use' => false,
                    'product_ids' => array(),
                    'exclude_product_ids' => array(),
                    'usage_limit' => '',
                    'usage_limit_per_user' => '',
                    'limit_usage_to_x_items' => '',
                    'usage_count' => '',
                    'date_created' => apply_filters('advanced_woo_discount_rules_custom_coupon_date_created', date('Y-m-d')),
                    'expiry_date' => '',
                    'apply_before_tax' => 'yes',
                    'free_shipping' => false,
                    'product_categories' => array(),
                    'exclude_product_categories' => array(),
                    'exclude_sale_items' => false,
                    'minimum_amount' => '',
                    'maximum_amount' => '',
                    'customer_email' => '',
                    'discount_type' => 'percent'
                );
                return $coupon;
            }
        }

        return $response;
    }

    /**
     * To change the discount name/code in front end
     * */
    public function changeCouponLabelInFrontEnd($text, $coupon)
    {
        if(!empty(self::$apply_as_coupon_values)){
            $coupon_code = Woocommerce::getCouponCode($coupon);
            $coupon_codes = array_keys(self::$apply_as_coupon_values);
            if(in_array($coupon_code, $coupon_codes)){
                $text = self::$apply_as_coupon_values[$coupon_code]['display_text'];
            }
        }

        return $text;
    }

    /**
     * Set coupon values
     * */
    public static function setCartCouponValues($label, $discount_value, $cart_item_keys, $rule_ids, $discount_details){
        $coupon_code = apply_filters('woocommerce_coupon_code', $label);
        $discount_value = apply_filters('advanced_woo_discount_rules_coupon_value', $discount_value, $label, $cart_item_keys);
        self::$apply_as_coupon_values[$coupon_code]['value'] = $discount_value;
        self::$apply_as_coupon_values[$coupon_code]['cart_item_keys'] = $cart_item_keys;
        self::$apply_as_coupon_values[$coupon_code]['display_text'] = $label;

        self::setCartCouponDiscountDetails($coupon_code, $label, $discount_value, $rule_ids, $discount_details);
    }

    /**
     * Set discount coupons details
     */
    public static function setCartCouponDiscountDetails($name, $label, $value, $rule_ids, $discount_details)
    {
        $rules = [];
        foreach ($rule_ids as $rule_id) {
            if (isset(self::$available_rules[$rule_id])) {
                $data = [
                    'id' => $rule_id,
                    'title' => self::$available_rules[$rule_id]->getTitle(),
                ];
                if (isset($discount_details[$rule_id])) {
                    $data['discount'] = [
                        'discount_label' => $discount_details[$rule_id]['discount_label'],
                        'discount_value' => round($discount_details[$rule_id]['discount_value'], 4),
                    ];
                }
                $rules[] = $data;
            }
        }
        if (!empty($rules)) {
            self::$applied_cart_coupon_discounts[] = ['name' => $name, 'value' => round($value, 4), 'rules' => $rules];
        }
    }

    /**
     * Remove duplicate values from multi dimension array
     * */
    protected static function removeDuplicateValues($apply_as_cart_fee_details){
        if(!empty($apply_as_cart_fee_details)){
            foreach ($apply_as_cart_fee_details as $key => $apply_as_cart_fee_detail){
                $apply_as_cart_fee_details[$key] = array_map("unserialize", array_unique(array_map("serialize", $apply_as_cart_fee_detail)));
            }
        }
        return $apply_as_cart_fee_details;

    }

    /**
     * Apply dynamic coupon for cart rules
     * */
    function applyVirtualCouponForCartRules(){
        $discount_apply_type = self::$config->getConfig('apply_cart_discount_as', 'coupon');
        if($discount_apply_type == "coupon"){
            remove_action('woocommerce_after_calculate_totals', array($this, 'applyVirtualCouponForCartRules'), 10);
            $combine_all_discounts = self::$config->getConfig('combine_all_cart_discounts', 0);
            $total_combined_discounts = 0;
            $combined_discounts_cart_items = array();
            if(function_exists('WC')){
                if(apply_filters('advanced_woo_discount_rules_recalculate_discount_before_apply_coupon', true)){
                    $cart_items = Woocommerce::getCart();
                    $loop_limit = 1;
                    if(!empty($cart_items) && is_array($cart_items)){
                        $loop_limit = count($cart_items)+1;
                    }
                    if ( $loop_limit >= did_action( 'advanced_woo_discount_rules_before_recalculate_discount_on_apply_coupon' ) ) {
                        do_action('advanced_woo_discount_rules_before_recalculate_discount_on_apply_coupon');
                        $this->applyCartProductDiscount(WC()->cart, true);
                    }
                }
            }
            $apply_as_cart_fee_details = DiscountCalculator::$price_discount_apply_as_cart_discount;
            DiscountCalculator::$price_discount_apply_as_cart_discount = array();
            self::$applied_cart_coupon_discounts = array();
            $apply_as_cart_fee_details = self::removeDuplicateValues($apply_as_cart_fee_details);
            $flat_in_subtotal = array();
            $discount_coupons = array();
            if(!empty($apply_as_cart_fee_details)){
                foreach ($apply_as_cart_fee_details as $rule_id => $product_id){
                    $cart_item_keys = array();
                    $discount_value = 0;
                    foreach ($product_id as $detail) {
                        if($detail['discount_type'] == 'wdr_cart_discount' && $detail['apply_type'] == 'flat_in_subtotal'){
                            if(!isset($flat_in_subtotal[$rule_id])){
                                $flat_in_subtotal[$rule_id]['value'] = $detail['discounted_price'];
                                $flat_in_subtotal[$rule_id]['label'] = $detail['discount_label'];
                                $flat_in_subtotal[$rule_id]['cart_item_keys'][] = $detail['cart_item_key'];
                                if (!isset($discount_coupons[$rule_id])) {
                                    $discount_coupons[$rule_id] = array('discount_label' => $detail['discount_label'], 'discount_value' => $detail['discounted_price']);
                                }
                            } else {
                                $flat_in_subtotal[$rule_id]['cart_item_keys'][] = $detail['cart_item_key'];
                            }
                        }else{
                            $discount_value += $detail['discounted_price'];
                            $label = (isset($detail['discount_label']) && !empty($detail['discount_label'])) ? $detail['discount_label'] : $detail['rule_name'];
                            $cart_item_keys[] = $detail['cart_item_key'];
                            if (!isset($discount_coupons[$rule_id])) {
                                $discount_coupons[$rule_id] = array('discount_label' => $label, 'discount_value' => $detail['discounted_price']);
                            } else {
                                $discount_coupons[$rule_id]['discount_value'] += $detail['discounted_price'];
                            }
                        }
                    }
                    if ($discount_value > 0) {
                        if (empty($combine_all_discounts)) {
                            $label = __($label, 'woo-discount-rules');
                            self::setCartCouponValues($label, $discount_value, $cart_item_keys, array($rule_id), $discount_coupons);
                            $this->applyFakeCouponsForCartRules($label);
                        }else{
                            $total_combined_discounts += $discount_value;
                            $combined_discounts_cart_items = array_merge($combined_discounts_cart_items, $cart_item_keys);
                        }
                        self::$calculated_cart_discount['discount'][] = array('price' => $discount_value, 'label' => $label, 'cart_item_keys' => $cart_item_keys);
                    }
                }
            }
            if (!empty($flat_in_subtotal)) {
                foreach ($flat_in_subtotal as $rule_id => $discount){
                    if(empty($combine_all_discounts)){
                        $discount_value = $discount['value'];
                        $label = $discount['label'];
                        $label = __($label, 'woo-discount-rules');
                        self::setCartCouponValues($label, $discount_value, $discount['cart_item_keys'], array($rule_id), $discount_coupons);
                        $this->applyFakeCouponsForCartRules($label);
                    }else{
                        $total_combined_discounts += $discount['value'];
                        $combined_discounts_cart_items = array_merge($combined_discounts_cart_items, $discount['cart_item_keys']);
                    }
                }
            }

            //Combine all discounts and add as single discounts
            if (!empty($total_combined_discounts) && !empty($combine_all_discounts)) {
                $label = self::$config->getConfig('discount_label_for_combined_discounts', __('Cart discount', 'woo-discount-rules'));
                $label = Helper::getCleanHtml($label);
                if(empty($label)){
                    $label = __('Cart discount', 'woo-discount-rules');
                }
                $label = __($label, 'woo-discount-rules');
                self::setCartCouponValues($label, $total_combined_discounts, $combined_discounts_cart_items, array_keys($discount_coupons), $discount_coupons);
                $this->applyFakeCouponsForCartRules($label);
            }
            add_action('woocommerce_after_calculate_totals', array($this, 'applyVirtualCouponForCartRules'), 10);
        }
    }

    /**
     * Apply dynamic coupon for cart rules
     * */
    function applyFakeCouponsForCartRules($coupon_code){
        global $woocommerce;
        $coupon_code = apply_filters('woocommerce_coupon_code', $coupon_code);

        // Validating the Coupon as Valid and discount status.
        if(isset($woocommerce->cart)){
            if(is_object($woocommerce->cart) && method_exists($woocommerce->cart, 'has_discount')){
                if (!$woocommerce->cart->has_discount($coupon_code)) {
                    // Do not apply coupon with individual use coupon already applied
                    if ($woocommerce->cart->applied_coupons) {
                        foreach ($woocommerce->cart->applied_coupons as $code) {
                            $coupon =  new \WC_Coupon($coupon_code);
                            if ($coupon->get_individual_use() == true) {
                                return false;
                            }
                        }
                    }

                    // Add coupon
                    $woocommerce->cart->applied_coupons[] = $coupon_code;
                    do_action('woocommerce_applied_coupon', $coupon_code);

                    return true;
                }
            }
        }
    }

	/**
	 * Apply url coupon
	 */
	function applyUrlCoupon() {
		global $woocommerce;

		if (isset($_GET['wdr_coupon']) && !empty($_GET['wdr_coupon']) && isset($woocommerce->cart)) {
			if ( is_object($woocommerce->cart) && method_exists( $woocommerce->cart, 'has_discount' ) && method_exists( $woocommerce->cart, 'add_discount' ) ) {
				$rule_helper           = new Rule();
				$available_url_coupons = $rule_helper->getAllUrlCoupons();
                $available_url_coupons = array_map('\Wdr\App\Helpers\Woocommerce::formatStringToLower', $available_url_coupons);
				$coupons = explode(",", $_GET['wdr_coupon']);
                if (isset($woocommerce->session) && is_object($woocommerce->session) && method_exists($woocommerce->session, 'has_session')) {
                    if ( ! $woocommerce->session->has_session() && method_exists($woocommerce->session, 'set_customer_session_cookie')) {
                        $woocommerce->session->set_customer_session_cookie( true );
                    }
                }
				foreach ( $coupons as $coupon ) {
					$coupon_code = rawurldecode( $coupon );
                    $coupon_code = Woocommerce::formatStringToLower($coupon_code);
					if ( in_array( $coupon_code, $available_url_coupons ) ) {
						if ( ! $woocommerce->cart->has_discount( $coupon_code ) ) {
							$woocommerce->cart->add_discount( $coupon_code );
						}
					} else {
						Woocommerce::wc_add_notice( sprintf( __( 'Coupon "%s" is currently not available!', 'woo-discount-rules' ), $coupon_code ), 'error' );
					}
				}
			}
		}
	}

    /**
     * Get product ids from cart key
     * */
    protected static function getProductIdsFromCartKey($cart_item_keys){
        $product_ids = array();
        $cart = Woocommerce::getCart();
        foreach ($cart as $cart_item){
            if(in_array($cart_item['key'], $cart_item_keys)){
                $product_ids[] = $cart_item['product_id'];
            }
        }
        if(!empty($product_ids)) $product_ids = array_unique($product_ids);
        return $product_ids;
    }

    /**
     * Get percentage amount from selected cart item
     * */
    protected static function getPercentageFromCartKey($product_ids, $discountPrice){
        $total_price = 0;
        $cart = Woocommerce::getCart();
        foreach ($cart as $cart_item){
            if(in_array($cart_item['product_id'], $product_ids)){
                $total_price += $cart_item['quantity']*$cart_item['data']->get_price();
            }
        }
        if($total_price > 0){
            return ($discountPrice*100)/$total_price;
        }
        return $total_price;
    }

    /**
     * Apply the custom coupon for create coupon condition
     * @param $response
     * @param $coupon_code
     * @return array
     */
    function validateVirtualCouponForCartRules($response, $coupon_data)
    {
        if($coupon_data !== false && $coupon_data !== 0){
            $discount_apply_type = self::$config->getConfig('apply_cart_discount_as', 'coupon');
            if($discount_apply_type == "coupon"){
                if(empty(self::$apply_as_coupon_values)){
                    remove_filter('woocommerce_get_shop_coupon_data', array($this, 'validateVirtualCouponForCartRules'), 10);
                    $this->applyVirtualCouponForCartRules();
                    add_filter('woocommerce_get_shop_coupon_data', array($this, 'validateVirtualCouponForCartRules'), 10, 2);
                }
                $product_ids = array();
                if(!empty(self::$apply_as_coupon_values)){
                    if(array_key_exists($coupon_data, self::$apply_as_coupon_values)){
                        $coupon_code = $coupon_data;
                        $amount = self::$apply_as_coupon_values[$coupon_code]['value'];
                        $cart_item_keys = self::$apply_as_coupon_values[$coupon_code]['cart_item_keys'];
                        if(apply_filters('advanced_woo_discount_rules_apply_coupon_for_products_based_on_filters', true, $coupon_code)){
                            $product_ids = self::getProductIdsFromCartKey($cart_item_keys);
                            $discount_in_percentage = self::getPercentageFromCartKey($product_ids, $amount);
                        }
                    } else {
                        return $response;
                    }
                } else {
                    add_filter('woocommerce_coupon_error', '\Wdr\App\Helpers\Helper::removeErrorMessageForOurCoupons', 10, 3);
                    return $response;
                }

                // Getting Coupon Remove status from Session.
                if(isset(WC()->session) && is_object(WC()->session) && method_exists(WC()->session, 'get')) {
                    $is_removed = WC()->session->get('woo_coupon_removed', '');
                    // If Both are same, then it won't added.
                    if(!empty($is_removed)){
                        if ($coupon_code == $is_removed) return $response;
                    }
                }

                if ($coupon_data == $coupon_code || wc_strtolower($coupon_data) == wc_strtolower($coupon_code)) {

                    //if ($this->postData->get('remove_coupon', false) == $coupon_code) return false;
                    if(empty($product_ids)){
                        $discount_type = 'fixed_cart';
                    } else {
                        $discount_type = apply_filters('advanced_woo_discount_rules_coupon_discount_type_percentage', 'percent');
                        $amount = $discount_in_percentage;
                    }

                    $coupon = array(
                        'id' => time().rand(2, 9),
                        'amount' => $amount,
                        'individual_use' => false,
                        'product_ids' => $product_ids,
                        'exclude_product_ids' => array(),
                        'usage_limit' => '',
                        'usage_limit_per_user' => '',
                        'limit_usage_to_x_items' => '',
                        'usage_count' => '',
                        'expiry_date' => '',
                        'apply_before_tax' => 'yes',
                        'free_shipping' => false,
                        'product_categories' => array(),
                        'exclude_product_categories' => array(),
                        'exclude_sale_items' => false,
                        'minimum_amount' => '',
                        'maximum_amount' => '',
                        'customer_email' => '',
                    );
                    $coupon['discount_type'] = $discount_type;
                    return $coupon;
                }
            }
        }

        return $response;
    }

    /**
     * Has third party coupon
     *
     * @return boolean
     * */
    function isCartContainsAnyThirdPartyCoupon(){
        $has_third_party_coupon = false;
        $used_coupons = DiscountCalculator::getUsedCoupons();
        $applied_coupons = Woocommerce::getAppliedCoupons();
        if(!empty($applied_coupons) && is_array($applied_coupons) && !empty($used_coupons)){
            $used_coupons = array_map('\Wdr\App\Helpers\Woocommerce::formatStringToLower', $used_coupons);
            foreach ($applied_coupons as $applied_coupon){
                if(!in_array($applied_coupon, $used_coupons)){
                    $has_third_party_coupon = true;
                    break;
                }
            }
        }elseif(!empty($applied_coupons) && is_array($applied_coupons) && empty($used_coupons)){
            $has_third_party_coupon = true;
        }

        return $has_third_party_coupon;
    }

    /**
     * Remove third party coupon when rule applied
     *
     * @param $calculated_cart_item_discount array
     * @param $processed_rule boolean
     * */
    function removeThirdPartyCouponIfRequired($calculated_cart_item_discount, $processed_rule){
        if($processed_rule === true){
            add_action('woocommerce_after_calculate_totals', array($this, 'removeThirdPartyCoupon'), 20);
        }
    }

    /**
     * Remove third party coupon if exists
     * */
    function removeThirdPartyCoupon(){
        $disable_coupon_when_rule_applied = self::$config->getConfig('disable_coupon_when_rule_applied', 'run_both');//run_both, disable_coupon, disable_rules
        if($disable_coupon_when_rule_applied == 'disable_coupon'){
            $used_coupons = DiscountCalculator::getUsedCoupons();
            $applied_coupons = Woocommerce::getAppliedCoupons();

            if(!empty($applied_coupons) && is_array($applied_coupons)){
                if(!empty($used_coupons)){
                    $used_coupons = array_map('\Wdr\App\Helpers\Woocommerce::formatStringToLower', $used_coupons);
                }
                foreach ($applied_coupons as $applied_coupon){
                    if(empty($used_coupons) || !in_array($applied_coupon, $used_coupons)){
                        $exclude_coupon = apply_filters('advanced_woo_discount_rules_exclude_coupon_while_remove_third_party_coupon', false, $applied_coupon);
                        if ($exclude_coupon === false) {
                            $this->removeAppliedCoupon($applied_coupon);
                        }
                    }
                }
            }
        }
    }

    /**
     * Remove applied message for the coupon we are going to remove
     * */
    function removeAppliedMessageOfThirdPartyCoupon($msg, $msg_code, $coupon){
        if(!empty($coupon)){
            $disable_coupon_when_rule_applied = self::$config->getConfig('disable_coupon_when_rule_applied', 'run_both');//run_both, disable_coupon, disable_rules
            /**
             * Added fix for coupon not applied in checkout page( when disable coupon discount rules will work)
             * added check for condition isset(self::$calculated_cart_item_discount) && !empty(self::$calculated_cart_item_discount)
             * @since 2.3.4
             */
            if($disable_coupon_when_rule_applied == 'disable_coupon' && isset(self::$calculated_cart_item_discount) && !empty(self::$calculated_cart_item_discount)){
                $used_coupons = DiscountCalculator::getUsedCoupons();
                $applied_coupons = Woocommerce::getAppliedCoupons();

                if(!empty($applied_coupons) && is_array($applied_coupons)){
                    if(!empty($used_coupons)){
                        $used_coupons = array_map('\Wdr\App\Helpers\Woocommerce::formatStringToLower', $used_coupons);
                    }
                    foreach ($applied_coupons as $applied_coupon){
                        if(empty($used_coupons) || !in_array($applied_coupon, $used_coupons)){
                            $msg = '';
                        }
                    }
                }
            }
        }
        return $msg;
    }

    /**
     * Remove applied coupon
     *
     * @param $coupon_code string
     * */
    function removeAppliedCoupon($coupon_code){
        $msg = sprintf(__('Sorry, it is not possible to apply coupon <b>"%s"</b> as you already have a discount applied in cart.', 'woo-discount-rules'), $coupon_code);

        $msg = apply_filters('advanced_woo_discount_rules_notice_on_remove_coupon_while_having_a_discount', $msg, $coupon_code);

        //Remove message: Coupon code applied successfully.
        $this->removeCouponAppliedMessage();
        Woocommerce::remove_coupon($coupon_code);
        Woocommerce::wc_add_notice( $msg, 'notice' );
    }

    /**
     * Remove message: Coupon code applied successfully.
     * */
    function removeCouponAppliedMessage(){
        $msg = __( 'Coupon code applied successfully.', 'woocommerce' );
        $msg = apply_filters('advanced_woo_discount_rules_remove_coupon_applied_message_text', $msg);
        Woocommerce::removeSpecificNoticeFromSession($msg);
    }

    /**
     * Do apply discounts
     *
     * @return boolean
     * */
    function doApplyDiscount($cart_object){
        $run_rule = true;
        $disable_coupon_when_rule_applied = self::$config->getConfig('disable_coupon_when_rule_applied', 'run_both');//run_both, disable_coupon, disable_rules
        if($disable_coupon_when_rule_applied == 'disable_rules'){
            $has_third_party_coupon = $this->isCartContainsAnyThirdPartyCoupon();
            if($has_third_party_coupon === true){
                do_action('advanced_woo_discount_rules_remove_applied_rules_on_coupon_applied');
                $calc = self::$calculator;
                $calc::$applied_rules = array();
                $run_rule = false;
            }
        }

        return apply_filters('advanced_woo_discount_rules_run_discount_rules', $run_rule, $cart_object);
    }

    /**
     * Apply discount for the products in cart
     * @param $cart_object
     * @return boolean
     */
    function applyCartProductDiscount($cart_object, $on_coupon_validate = false)
    {
        remove_action('woocommerce_cart_calculate_fees', array($this, 'applyCartDiscount'));
        Helper::clearPromotionMessages();
        $do_apply_discount = $this->doApplyDiscount($cart_object);
        if($do_apply_discount){
            $calc = self::$calculator;
            $calc::$applied_rules = array();
            $this->calculateCartPageDiscounts($on_coupon_validate);
            $processed_rule = false;
            if (!empty(self::$calculated_cart_item_discount)) {
                if (isset($cart_object->cart_contents) && !empty($cart_object->cart_contents)) {
                    foreach ($cart_object->cart_contents as $key => $cart_item) {
                        if (array_key_exists($key, self::$calculated_cart_item_discount)) {
                            $processed_rule = true;
                            $product_id = isset($cart_item['product_id']) ? $cart_item['product_id'] : 0;
                            if (isset($cart_item['variation_id']) && !empty($cart_item['variation_id'])) {
                                $product_id = $cart_item['variation_id'];
                            }
                            if (empty($product_id)) {
                                return false;
                            }
                            $product_obj = isset($cart_item['data']) ? $cart_item['data'] : $cart_item;
                            $item_quantity = isset($cart_item['quantity']) ? $cart_item['quantity'] : 0;
                            //product price
                            $initial_price = self::$calculated_cart_item_discount[$key]['initial_price'];
                            $discounted_price = self::$calculated_cart_item_discount[$key]['discounted_price'];
                            $calculator = self::$calculator;
                            $total_discounts = $calculator::$total_discounts;
                            $price = $discounted_price;
                            $price = apply_filters('advanced_woo_discount_rules_discounted_price_of_cart_item', $price, $cart_item, $cart_object, self::$calculated_cart_item_discount[$key]);
                            $do_apply_price_rules = apply_filters('advanced_woo_discount_rules_do_apply_price_discount', true, $price, $cart_item, $cart_object, self::$calculated_cart_item_discount[$key]);
                            if($do_apply_price_rules){
                                self::$woocommerce_helper->setCartProductPrice($product_obj, $price);
                                $product_obj->awdr_product_original_price = $initial_price;
                            }

                        }
                    }
                }
            }

            // Disable third party coupon when rule applied
            $this->removeThirdPartyCouponIfRequired(self::$calculated_cart_item_discount, $processed_rule);

            do_action('advanced_woo_discount_rules_after_apply_discount', $cart_object, self::$calculated_cart_item_discount);
        }
        add_action('woocommerce_cart_calculate_fees', array($this, 'applyCartDiscount'));
    }

    /**
     * Calculate cart discounts
     * @return bool
     */
    function calculateCartPageDiscounts($on_coupon_validate = false)
    {
        $cart_items = self::$woocommerce_helper->getCart();
        if (!empty($cart_items)) {
            foreach ($cart_items as $cart_key => $cart_item) {
                $product_id = self::$woocommerce_helper->getProductId(isset($cart_item['data']) ? $cart_item['data'] : $cart_item);
                $product = self::$woocommerce_helper->getProductFromCartItem($cart_item, $product_id);
                $quantity = $cart_item['quantity'];
                $calculate_discount_for_item = apply_filters('advanced_woo_discount_rules_calculate_discount_for_cart_item', true, $cart_item);
                if($calculate_discount_for_item){
                    $prices = self::calculateInitialAndDiscountedPrice($product, $quantity, true, false, $cart_item, $on_coupon_validate);
                    if ($prices) {
                        //add the cart quantity
                        //Here discounts are calculated for per item
                        $prices['cart_quantity'] = $quantity;
                        $prices['product_id'] = $product_id;
                        $apply_discount = $this->didAppliedDiscountAlready($cart_key, $prices);
                        if ($apply_discount) {
                            self::$calculated_cart_item_discount[$cart_key] = apply_filters('advanced_woo_discount_rules_cart_item_discount_prices', $prices, $cart_item, $product);
                        }
                    }
                }

            }
        }
        return true;
    }

    /**
     * Did applied discount already
     *
     * @param $cart_key string
     * @param $prices array
     * @return boolean
     * */
    public function didAppliedDiscountAlready($cart_key, $prices)
    {
        $apply_discount = true;
        if (isset(self::$calculated_cart_item_discount[$cart_key])) {
            if (isset(self::$calculated_cart_item_discount[$cart_key]['discounted_price'])) {
                if (self::$calculated_cart_item_discount[$cart_key]['discounted_price'] > $prices['discounted_price']) {
                    $apply_discount = false;
                }
            }
        }
        return apply_filters('advanced_woo_discount_rules_did_discount_applied_already', $apply_discount, self::$calculated_cart_item_discount, $cart_key, $prices);
    }

    /**
     * laod the bulk table discount message manually
     * @param $product
     * @param false $get_variable_product_table
     * @return false|string
     */
    function showBulkTableInPositionManually($product, $get_variable_product_table = false)
    {
        if (!empty($product)) {
            $bulk_discounts_ranges = wp_unslash(self::$calculator->getDefaultLayoutMessagesByRules($product, $get_variable_product_table));
            if(empty($bulk_discounts_ranges)){
                $bulk_discounts_ranges['layout']['type'] = 'default';
                $bulk_discounts_ranges['layout']['bulk_variant_table'] = 'default_variant_empty';
            }
            $override_path = get_theme_file_path('advanced_woo_discount_rules/discount_table.php');
            $bulk_table_template_path = WDR_PLUGIN_PATH . 'App/Views/Templates/discount_table.php';
            if (file_exists($override_path)) {
                $bulk_table_template_path = $override_path;
            }
            if($get_variable_product_table){
                return self::$template_helper->setPath($bulk_table_template_path)->setData(array('ranges' => $bulk_discounts_ranges, 'woocommerce' => self::$woocommerce_helper, 'base' => $this))->render();
            }
            self::$template_helper->setPath($bulk_table_template_path)->setData(array('ranges' => $bulk_discounts_ranges, 'woocommerce' => self::$woocommerce_helper, 'base' => $this))->display();
        }
    }

    /**
     * Show the bulk table discount message
     */
    function showBulkTableInPosition()
    {
        global $product;
        if (!empty($product)) {
            $this->showBulkTableInPositionManually($product);
        }
    }

    /**
     * load the advanced table discount message manually
     */
    function showAdvancedTableInPositionManually($product)
    {
        if (!empty($product)) {
            $bulk_discounts_ranges = wp_unslash(self::$calculator->getAdvancedLayoutMessagesByRules($product));
            $override_path = get_theme_file_path('advanced_woo_discount_rules/discount_table.php');
            $bulk_table_template_path = WDR_PLUGIN_PATH . 'App/Views/Templates/discount_table.php';
            if (file_exists($override_path)) {
                $bulk_table_template_path = $override_path;
            }
            self::$template_helper->setPath($bulk_table_template_path)->setData(array('ranges' => $bulk_discounts_ranges, 'woocommerce' => self::$woocommerce_helper))->display();
        }
    }

    /**
     * Show the advanced table discount message
     */
    function showAdvancedTableInPosition()
    {
        global $product;
        if (!empty($product)) {
            $this->showAdvancedTableInPositionManually($product);
        }
    }

    /**
     * save discounts of order for future
     *
     * @param $order_id
     * @param $items
     * @return void
     */
    function orderItemsSaved($order_id, $items)
    {
        $order = self::$woocommerce_helper->getOrder($order_id);
        if (empty($order)) {
            return;
        }

        $free_shipping = false;
        if (self::$woocommerce_helper->orderHasShippingMethod($order, 'wdr_free_shipping')) {
            $free_shipping = true;
        }

        $cart_discounts = [];
        if (!empty(self::$applied_cart_coupon_discounts)) {
            $cart_discounts = [
                'applied_as' => self::$config->getConfig('apply_cart_discount_as', 'coupon'),
                'combine_all_discounts' => (bool) self::$config->getConfig('combine_all_cart_discounts', 0),
                'applied_coupons' => self::$applied_cart_coupon_discounts,
            ];
        }

        $product_discount_total = $product_discount_total_with_tax = $cart_discount_total = 0;
        foreach (self::$woocommerce_helper->getOrderItems($order) as $item_id => $item) {
            if ($details = self::$woocommerce_helper->getOrderItemMeta($item, '_wdr_discounts')) {
                $item_data = self::$woocommerce_helper->getOrderItemData($item);
                if (empty($item_data)) {
                    continue;
                }
                $quantity = $item_data['quantity'];
                $product_id = $item_data['variation_id'] > 0 ? $item_data['variation_id'] : $item_data['product_id'];
                $initial_price = isset($details['initial_price_based_on_tax_settings']) ? $details['initial_price_based_on_tax_settings'] : 0;
                $discounted_price = isset($details['discounted_price_based_on_tax_settings']) ? $details['discounted_price_based_on_tax_settings'] : 0;
                $product_discount_total += isset($details['saved_amount']) ? $details['saved_amount'] : 0;
                $product_discount_total_with_tax += isset($details['saved_amount_based_on_tax_settings']) ? $details['saved_amount_based_on_tax_settings'] : 0;
                if (isset($details['applied_rules']) && !empty($details['applied_rules'])) {
                    foreach ($details['applied_rules'] as $rule) {
                        $simple_discount = $bulk_discount = $set_discount = $other_discount = 0;
                        $discount = $rule['discount'];
                        if ($discount['applied_in'] == 'product_level') {
                            switch ($rule['type']) {
                                case 'simple_discount':
                                    $simple_discount = $discount['discount_price'] * $quantity;
                                    break;
                                case 'bulk_discount':
                                    $bulk_discount = $discount['discount_price'] * $quantity;
                                    break;
                                case 'set_discount':
                                    $set_discount = $discount['discount_price'] * $discount['discount_quantity'];
                                    break;
                                default:
                                    $other_discount = $discount['discount_price'] * $quantity;
                            }
                            $discount_price = $simple_discount + $bulk_discount + $set_discount + $other_discount;
                            DBTable::saveOrderItemDiscounts($order_id, $item_id, $product_id, $initial_price, $discounted_price, $discount_price, $quantity, $rule['id'], $simple_discount, $bulk_discount, $set_discount, 0, $other_discount, null);
                        }
                    }
                }
            }
        }

        foreach (self::$applied_cart_coupon_discounts as $coupon) {
            foreach ($coupon['rules'] as $rule) {
                $cart_discount = $rule['discount']['discount_value'];
                $cart_discount_label = $rule['discount']['discount_label'];
                $cart_discount_total += $cart_discount;
                DBTable::saveOrderItemDiscounts($order_id, 0, 0, 0, 0, 0, 0, $rule['id'], 0, 0, 0, $cart_discount, 0, $cart_discount_label);
            }
        }

        $available_custom_coupons = (new Rule())->getAllDynamicCoupons();
        $applied_coupons = self::$woocommerce_helper->getAppliedCoupons();
        $applied_custom_coupons = array_intersect($applied_coupons, $available_custom_coupons);
        if (!empty($applied_custom_coupons)) {
            foreach ($applied_custom_coupons as $custom_coupon_label) {
                DBTable::saveOrderItemDiscounts($order_id, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, $custom_coupon_label);
            }
        }

        $calc = self::$calculator;
        if (!empty($calc::$applied_rules)) {
            foreach ($calc::$applied_rules as $rule) {
                $used_limits = intval($rule->getUsedLimits()) + 1;
                DBTable::updateRuleUsedCount($rule->getId(), $used_limits);
                if ($free_shipping && $rule->getRuleDiscountType() == 'wdr_free_shipping') {
                    DBTable::saveOrderItemDiscounts($order_id, 0, 0, 0, 0, 0, 0, $rule->getId(), 0, 0, 0, 0, 0, null, true);
                }
            }
        }

        $discount_details = [];
        if (!empty(self::$calculated_cart_discount)) {
            if (isset(self::$calculated_cart_discount["discount"]) && !empty(self::$calculated_cart_discount["discount"])) {
                $discount_details = self::$calculated_cart_discount["discount"];
            }
        }
        if (!empty($discount_details) || $free_shipping) {
            $discount_details = json_encode($discount_details);
            $has_free_shipping = $free_shipping ? 'yes' : 'no';
            DBTable::saveOrderDiscounts($order_id, $has_free_shipping, $discount_details);
        }

        $order_discount_info = [
            'free_shipping' => $free_shipping,
            'cart_discounts' => $cart_discounts,
            'saved_amount' => [
                'product_level' => round($product_discount_total, 4),
                'product_level_based_on_tax_settings' => round($product_discount_total_with_tax, 4),
                'cart_level' => round($cart_discount_total, 4),
                'total' => round($product_discount_total + $cart_discount_total, 4),
                'total_based_on_tax_settings' => round($product_discount_total_with_tax + $cart_discount_total, 4),
            ]
        ];
        if (!empty($order_discount_info['free_shipping']) || !empty($order_discount_info['saved_amount']['total'])) {
            self::$woocommerce_helper->setOrderMeta($order, '_wdr_discounts', $order_discount_info);
        }
    }

    /**
     * Bxgx free product amount data
     * @param $buy_x_get_x_free_discounts
     * @param $order_id
     * @param $items
     */
    function orderItemsSavedForBXGXFree($buy_x_get_x_free_discounts, $order_id, $items){
        if(!empty($buy_x_get_x_free_discounts)){
            foreach ($buy_x_get_x_free_discounts as $buy_x_get_x_free_key => $buy_x_get_x_free_discount){
                $simple_discount = $bulk_discount = $set_discount = $cart_discount = 0;
                $rule_id = isset($buy_x_get_x_free_discount['rule_id']) ? $buy_x_get_x_free_discount['rule_id'] : 0;
                $variant_item_id = isset($buy_x_get_x_free_discount['variation_id']) ? $buy_x_get_x_free_discount['variation_id'] : '';
                $item_id = isset($buy_x_get_x_free_discount['product_id']) ? $buy_x_get_x_free_discount['product_id'] : 0;
                if(!empty($variant_item_id)){
                    $item_id = $variant_item_id;
                }
                $free_product = isset($buy_x_get_x_free_discount['cart_item']['data']) ? $buy_x_get_x_free_discount['cart_item']['data'] : '';
                $discount = Woocommerce::getProductPrice($free_product);
                $discount_quantity = isset($buy_x_get_x_free_discount['discount_quantity']) ? $buy_x_get_x_free_discount['discount_quantity'] : 0;
                $discount_price = $discount * $discount_quantity;
                $cart_shipping = 'no';
                $cart_discount_label =  '';
                if($discount_price != 0){
                    $applied_rules[] = $rule_id;
                }
                DBTable::saveOrderItemDiscounts($order_id, $item_id, 0, 0, $discount_price, $discount_quantity, $rule_id, $simple_discount, $bulk_discount, $set_discount, $cart_discount, $cart_discount_label, $cart_shipping);
            }
        }
    }

    /**
     * Bxgy free product amount data
     * @param $buy_x_get_y_free_discounts
     * @param $order_id
     * @param $items
     */
    function orderItemsSavedForBXGYFree($buy_x_get_y_free_discounts, $order_id, $items){
        if(!empty($buy_x_get_y_free_discounts)){
            foreach ($buy_x_get_y_free_discounts as $buy_x_get_y_free_key => $buy_x_get_y_free_discount){
                $simple_discount = $bulk_discount = $set_discount = $cart_discount = $discount_quantity = 0;
                $rule_id = isset($buy_x_get_y_free_discount['rule_id']) ? $buy_x_get_y_free_discount['rule_id'] : 0;
                $variant_item_id = isset($buy_x_get_y_free_discount['variation_id']) ? $buy_x_get_y_free_discount['variation_id'] : 0;
                $item_id = isset($buy_x_get_y_free_discount['product_id']) ? $buy_x_get_y_free_discount['product_id'] : 0;
                if(!empty($variant_item_id)){
                    $item_id = $variant_item_id;
                }
                if($item_id != 0){
                    $free_product = isset($buy_x_get_y_free_discount['cart_item']['data']) ? $buy_x_get_y_free_discount['cart_item']['data'] : '';
                    $discount = Woocommerce::getProductPrice($free_product);
                    $discount_quantity = isset($buy_x_get_y_free_discount['discount_quantity']) ? $buy_x_get_y_free_discount['discount_quantity'] : 0;
                    $discount_price = $discount * $discount_quantity;
                }else{
                    $discount_price = 0;
                    $discount_products = isset($buy_x_get_y_free_discount['discount_products']) ? $buy_x_get_y_free_discount['discount_products'] : array();
                    if(!empty($discount_products)){
                        foreach ($discount_products as $product_id){
                            $free_product = Woocommerce::getProduct((int)$product_id);
                            $discount = Woocommerce::getProductPrice($free_product);
                            $discount_quantity = isset($buy_x_get_y_free_discount['discount_quantity']) ? $buy_x_get_y_free_discount['discount_quantity'] : 0;
                            $discount_price += $discount * $discount_quantity;
                        }
                    }
                }
                $cart_shipping = 'no';
                $cart_discount_label =  '';

                if($discount_price != 0){
                    $applied_rules[] = $rule_id;
                }
                DBTable::saveOrderItemDiscounts($order_id, $item_id, 0, 0, $discount_price, $discount_quantity, $rule_id, $simple_discount, $bulk_discount, $set_discount, $cart_discount, $cart_discount_label, $cart_shipping);
            }
        }
    }

    /**
     * Show the discount promotion message
     */
    function showAppliedRulesMessages()
    {
        $message = self::$config->getConfig('applied_rule_message', __('Discount <strong>{{title}}</strong> has been applied to your cart.', 'woo-discount-rules'));
        $message = Helper::getCleanHtml($message);
        $message = __($message, 'woo-discount-rules');
        $calc = self::$calculator;
        $applied_rules = $calc::$applied_rules;
        if (!empty($applied_rules)) {
            foreach ($applied_rules as $rule) {
                $title = $rule->getTitle();
                $title = __($title, 'woo-discount-rules');
                $message_to_display = str_replace('{{title}}', $title, $message);
                $message_to_display = apply_filters('advanced_woo_discount_rules_message_to_display_when_rules_applied', $message_to_display, $rule);
                self::$woocommerce_helper->printNotice($message_to_display, 'success');
            }
        }
    }

    /**
     * Display promotional message in check out while processing order review
     * */
    public function displayPromotionMessagesInCheckout(){
        echo "<div id='awdr_checkout_promotion_messages_data'>";
        $this->showAppliedRulesMessages();
        echo "</div>";
        echo "<script>";
        echo "jQuery('#awdr_checkout_promotion_messages').html(jQuery('#awdr_checkout_promotion_messages_data').html());jQuery('#awdr_checkout_promotion_messages_data').remove()";
        echo "</script>";
    }

    /**
     * Load outer div for displaying promotional message in check out
     * */
    public function displayPromotionMessagesInCheckoutContainer(){
        echo "<div id='awdr_checkout_promotion_messages'>";
        echo "</div>";
    }

    /**
     * Show the modified price on cart
     * @param $item_price
     * @param $cart_item
     * @param $cart_item_key
     * @return mixed|void
     */
    function getCartPriceHtml($item_price, $cart_item, $cart_item_key){
        $show_strikeout_on_cart = self::$config->getConfig('show_strikeout_on_cart', 1);
        if (!empty($show_strikeout_on_cart)) {
            $new_item_price_html = '';
            if (isset(self::$calculated_cart_item_discount[$cart_item_key])) {
                $initial_price = self::$calculated_cart_item_discount[$cart_item_key]['initial_price'];
                $discounted_price = self::$calculated_cart_item_discount[$cart_item_key]['discounted_price'];
                $discount_lines = self::$calculated_cart_item_discount[$cart_item_key]['discount_lines'];
                if(!empty($discount_lines)){
                    $calculator = self::$calculator;
                    $product_obj = isset($cart_item['data']) ? $cart_item['data'] : $cart_item;
                    if(is_null($product_obj)){
                        return $item_price;
                    }
                    $initial_price_with_tax_call = $discounted_price_with_tax_call = 0;
                    if(!empty($initial_price)){
                        $initial_price_with_tax_call = $calculator->mayHaveTax($product_obj, $initial_price);
                    }
                    $has_non_applied_lines = false;
                    if(isset($discount_lines['non_applied']) && !empty($discount_lines['non_applied'])){
                        if(isset($discount_lines['non_applied']['quantity']) && $discount_lines['non_applied']['quantity'] > 0){
                            $has_non_applied_lines = true;
                        }
                    }
                    $discount_line_count = count($discount_lines);
                    $has_multiple_strikeout_lines = false;
                    foreach ($discount_lines as $key => $discount_line){
                        if($key !== 'non_applied'){
                            $has_multiple_strikeout_lines = true;
                            $discounted_price = $discount_line['discounted_price'];
                            $discounted_qty = $discount_line['quantity'];
                            $discounted_price_with_tax_call = $calculator->mayHaveTax($product_obj, $discounted_price);
                            if($has_non_applied_lines == false && $discount_line_count === 2){
                                $new_item_price_html .= '<div class="awdr_cart_strikeout_line">'.$this->getStrikeoutPrice($initial_price_with_tax_call, $discounted_price_with_tax_call).'</div>';
                            } else {
                                $item_quantity_html = apply_filters('advanced_woo_discount_rules_cart_strikeout_quantity_html', '&nbsp;x&nbsp;'.$discounted_qty, $discounted_qty);
                                $new_item_price_html .= '<div class="awdr_cart_strikeout_line">'.$this->getStrikeoutPrice($initial_price_with_tax_call, $discounted_price_with_tax_call).$item_quantity_html.'</div>';
                            }
                        }
                    }
                    if($has_multiple_strikeout_lines){
                        if(isset($discount_lines['non_applied']) && !empty($discount_lines['non_applied'])){
                            if(isset($discount_lines['non_applied']['quantity']) && $discount_lines['non_applied']['quantity'] > 0){
                                $discounted_qty = $discount_lines['non_applied']['quantity'];
                                $item_quantity_html = apply_filters('advanced_woo_discount_rules_cart_strikeout_quantity_html', '&nbsp;x&nbsp;'.$discounted_qty, $discounted_qty);
                                $new_item_price_html .= '<div class="awdr_cart_strikeout_line">'.self::$woocommerce_helper->formatPrice($initial_price_with_tax_call).$item_quantity_html.'</div>';
                            }
                        }
                    }
                }
                if($new_item_price_html !== ''){
                    return apply_filters('advanced_woo_discount_rules_cart_strikeout_price_html', $new_item_price_html, $item_price, $cart_item, $cart_item_key);
                }
            }
        }

        return $item_price;
    }

    /**
     * Show the modified price on cart
     * @param $item_subtotal_price
     * @param $cart_item
     * @param $cart_item_key
     * @return string
     */
    function getCartProductSubtotalPriceHtml($item_subtotal_price, $cart_item, $cart_item_key)
    {
        if (isset(self::$calculated_cart_item_discount[$cart_item_key]) && !empty(self::$calculated_cart_item_discount[$cart_item_key])) {
            $discount = $this->getDiscountPerItem(self::$calculated_cart_item_discount[$cart_item_key]);
            if($discount > 0){
                $total_discount = self::$woocommerce_helper->formatPrice($discount);
                $subtotal_additional_text = '<br>' . $this->getYouSavedText($total_discount);
                $item_subtotal_price .= apply_filters('advanced_woo_discount_rules_line_item_subtotal_saved_text', $subtotal_additional_text, $total_discount, $discount, $cart_item, $cart_item_key);
            }
        }
        return $item_subtotal_price;
    }

    /**
     * get You saved text
     * @param $discount
     * @return string|null
     */
    function getYouSavedText($discount)
    {
        if (!empty($discount)) {
            $text = self::$config->getConfig('you_saved_text', __("You saved {{total_discount}}", 'woo-discount-rules'));
            $text = __($text, 'woo-discount-rules');
            $text = Helper::getCleanHtml($text);
            $message = str_replace('{{total_discount}}', $discount, $text);
            return '<div class="awdr-you-saved-text" style="color: green">' . $message . '</div>';
        }
        return NULL;
    }

    /**
     * Get the discount per item
     * @param $discount_details
     * @return float|int
     */
    function getDiscountPerItem($discount_details)
    {
        /**
         * Field name changed. For backward compatible we have to check the key exists
         * saved_amount_with_tax => saved_amount_based_on_tax_settings
         * initial_price_with_tax => initial_price_based_on_tax_settings (only for orders)
         * discounted_price_with_tax => discounted_price_based_on_tax_settings (only for orders)
         * */
        $saved_amount_with_tax_field_name = 'saved_amount_with_tax';
        $initial_price_with_tax_field_name = 'initial_price_with_tax';
        $discounted_price_with_tax_field_name = 'discounted_price_with_tax';
        if(isset($discount_details['saved_amount_based_on_tax_settings'])){
            $saved_amount_with_tax_field_name = 'saved_amount_based_on_tax_settings';
        }
        if(isset($discount_details['initial_price_based_on_tax_settings'])){
            $initial_price_with_tax_field_name = 'initial_price_based_on_tax_settings';
        }
        if(isset($discount_details['discounted_price_based_on_tax_settings'])){
            $discounted_price_with_tax_field_name = 'discounted_price_based_on_tax_settings';
        }

        if(isset($discount_details[$saved_amount_with_tax_field_name])){
            if ($discount_details[$saved_amount_with_tax_field_name] > 0) {
                return $discount_details[$saved_amount_with_tax_field_name];
            }
        }
        $discounted_price = isset($discount_details[$discounted_price_with_tax_field_name]) ? $discount_details[$discounted_price_with_tax_field_name] : 0;
        $initial_price = isset($discount_details[$initial_price_with_tax_field_name]) ? $discount_details[$initial_price_with_tax_field_name] : 0;
        $cart_quantity = isset($discount_details['cart_quantity']) ? $discount_details['cart_quantity'] : 0;
        $total_discount_per_quantity = $initial_price - $discounted_price;
        if ($discounted_price >= 0 && $total_discount_per_quantity > 0) {
            if (empty($total_discount_per_quantity)) {
                $total_discount_per_quantity = $initial_price;
            }
            $total_discount_per_quantity = $total_discount_per_quantity * $cart_quantity;
            return $total_discount_per_quantity;
        }
        return 0;
    }

    /**
     * Show the modified price on cart
     * @param $cart_total_price
     * @return string
     */
    function getCartTotalPriceHtml($cart_total_price)
    {
        if (!empty(self::$calculated_cart_item_discount)) {
            $total_discount = 0;
            foreach (self::$calculated_cart_item_discount as $discount_details) {
                if (!empty($discount_details)) {
                    $total_discount += $this->getDiscountPerItem($discount_details);
                }
            }
            if (empty($total_discount)) {
                return $cart_total_price;
            }
            $total_discount = self::$woocommerce_helper->formatPrice($total_discount);
            $cart_total_price .= '<br>' . $this->getYouSavedText($total_discount);
        }
        return $cart_total_price;
    }

    /**
     * Adding the discount per order item in item meta
     * @param $item
     * @param $cart_item_key
     * @param $values
     * @param $order
     */
    function onCreateWoocommerceOrderLineItem($item, $cart_item_key, $values, $order)
    {
        $meta_discount_details = isset(self::$calculated_cart_item_discount[$cart_item_key]) ? self::$calculated_cart_item_discount[$cart_item_key] : array();
        if(!empty($meta_discount_details)){
            $item_quantity = isset($item->legacy_values['quantity']) ? $item->legacy_values['quantity'] : 0;
            $product = isset($item->legacy_values['data']) ? $item->legacy_values['data'] : array();
            $item_product_id = Woocommerce::getProductId($product);
            $item_cart_discount_details = isset($meta_discount_details['cart_discount_details']) ? $meta_discount_details['cart_discount_details'] : array();
            if(!empty($item_cart_discount_details)){
                foreach ($item_cart_discount_details as $key => $detail){
                    $rule_applied_product_id = isset($detail['applied_product_ids']) ? $detail['applied_product_ids'] : array();
                    $cart_discount_price = isset( $meta_discount_details['cart_discount_details'][$key]['cart_discount_product_price'][$item_product_id][$key])? $meta_discount_details['cart_discount_details'][$key]['cart_discount_product_price'][$item_product_id][$key] : 0;
                    $meta_discount_details['cart_discount_details'][$key]['cart_discount_price'] = $cart_discount_price;
                    if($item_quantity != 0){
                        $cart_discount_price = $cart_discount_price / $item_quantity;
                    }
                    $meta_discount_details['cart_discount_details'][$key]['cart_discount_product_price'] = $cart_discount_price;
                    unset($meta_discount_details['cart_discount_details'][$key]['applied_product_ids']);
                    if(!in_array($item_product_id,$rule_applied_product_id)){
                        unset( $meta_discount_details['cart_discount_details'][$key]);
                    }
                }
            }
            if(isset($meta_discount_details['initial_price_with_tax'])){
                $meta_discount_details['initial_price_based_on_tax_settings'] = $meta_discount_details['initial_price_with_tax'];
                unset($meta_discount_details['initial_price_with_tax']);
            }
            if(isset($meta_discount_details['discounted_price_with_tax'])){
                $meta_discount_details['discounted_price_based_on_tax_settings'] = $meta_discount_details['discounted_price_with_tax'];
                unset($meta_discount_details['discounted_price_with_tax']);
            }

            self::$woocommerce_helper->setOrderItemMeta($item, '_advanced_woo_discount_item_total_discount', $meta_discount_details);
        }

        // to store improved discount info to order item meta
        self::setDiscountInfoToOrderItemMeta($item, $cart_item_key, $values, $order);
    }

    private static function setDiscountInfoToOrderItemMeta($order_item, $cart_item_key, $cart_item, $order)
    {
        $product_id = $cart_item['variation_id'] > 0 ? $cart_item['variation_id'] : $cart_item['product_id'];
        $cart_item_discounts = isset(self::$calculated_cart_item_discount[$cart_item_key]) ? self::$calculated_cart_item_discount[$cart_item_key] : array();
        if (empty($cart_item_discounts) && $free_item_discounts = apply_filters('advanced_woo_discount_rules_get_auto_add_discount_details_from_cart_item', array(), $cart_item, $cart_item_key)) {
            $cart_item_discounts = $free_item_discounts;
        }
        if (!empty($cart_item_discounts)) {
            self::$woocommerce_helper->setOrderItemMeta($order_item, '_wdr_discounts', self::prepareDiscountDetails($cart_item_discounts, $product_id, $cart_item['quantity'], $cart_item_key));
        }
    }

    /**
     * Returns structured discount details
     */
    private static function prepareDiscountDetails($discount_details, $product_id = 0, $quantity = 1, $cart_item_key = '')
    {
        $details = [];
        $details['initial_price'] = (float) $discount_details['initial_price'];
        $details['discounted_price'] = (float) $discount_details['discounted_price'];
        $details['initial_price_based_on_tax_settings'] = round($discount_details['initial_price_with_tax'], 4);
        $details['discounted_price_based_on_tax_settings'] = round($discount_details['discounted_price_with_tax'], 4);
        $details['applied_rules'] = [];
        if (!empty($cart_item_key) && !empty($discount_details['total_discount_details'][$cart_item_key])) {
            $details['applied_rules'] = array_merge($details['applied_rules'], self::getAppliedRuleDiscountsFromTotalDiscountDetails($discount_details['total_discount_details'][$cart_item_key]));
        } elseif (empty($product_id) && !empty($discount_details['total_discount_details'])) {
            $details['applied_rules'] = array_merge($details['applied_rules'], self::getAppliedRuleDiscountsFromTotalDiscountDetails($discount_details['total_discount_details']));
        }
        if (!empty($discount_details['cart_discount_details'])) {
            $details['applied_rules'] = array_merge($details['applied_rules'], self::getAppliedRuleDiscountsFromCartDiscountDetails($discount_details['cart_discount_details'], $product_id));
        }
        $details['saved_amount'] = round(($discount_details['initial_price'] - $discount_details['discounted_price']) * $quantity, 4);
        $details['saved_amount_based_on_tax_settings'] = round(($discount_details['initial_price_with_tax'] - $discount_details['discounted_price_with_tax']) * $quantity, 4);
        if (!empty($product_id)) {
            $details['is_free_product'] = isset($discount_details['is_free_product']) && $discount_details['is_free_product'];
        }
        return $details;
    }

    /**
     * Get applied discount details form total discount details.
     */
    private static function getAppliedRuleDiscountsFromTotalDiscountDetails($total_discount_details) {
        $applied_rule_discounts = [];
        if (is_array($total_discount_details)) {
            foreach ($total_discount_details as $rule_id => $details) {
                if (!isset(self::$available_rules[$rule_id])) { continue; }
                $rule = self::$available_rules[$rule_id];
                $rule_type = substr($rule->getRuleDiscountType(), 4); // without prefix (wdr_)
                if ($rule_type == 'buy_x_get_y_discount') {
                    if (isset($details['buy_x_get_y_cheapest_in_cart_discount'])) {
                        $rule_type = 'buy_x_get_y_cheapest_in_cart_discount';
                    } elseif (isset($details['buy_x_get_y_cheapest_from_products_discount'])) {
                        $rule_type = 'buy_x_get_y_cheapest_from_products_discount';
                    } elseif (isset($details['buy_x_get_y_cheapest_from_categories_discount'])) {
                        $rule_type = 'buy_x_get_y_cheapest_from_categories_discount';
                    }
                }
                if (isset($details[$rule_type])) {
                    $discount_info = $details[$rule_type];
                } elseif (isset($details['discount_type'])) {
                    $discount_info = $details;
                }
                if (empty($discount_info) || !is_array($discount_info)) {
                    continue;
                }
                $discount = [
                    'applied_in' => 'product_level',
                    'discount_type' => isset($discount_info['discount_type']) ? $discount_info['discount_type'] : '',
                    'discount_value' => isset($discount_info['discount_value']) ?  round($discount_info['discount_value'], 4) : 0,
                    'discount_quantity' => isset($discount_info['discount_quantity']) ? round($discount_info['discount_quantity'], 4) : 0,
                    'discount_price' => isset($discount_info['discount_price']) ? round($discount_info['discount_price'], 4) : 0,
                ];
                $applied_rule_discounts[] = [
                    'id' => $rule_id,
                    'title' => $rule->getTitle(),
                    'type' => $rule_type,
                    'discount' => $discount,
                ];
            }
        }
        return $applied_rule_discounts;
    }

    /**
     * Get applied discount details form cart discount details.
     */
    private static function getAppliedRuleDiscountsFromCartDiscountDetails($cart_discount_details, $product_id = 0)
    {
        $applied_rule_discounts = [];
        if (is_array($cart_discount_details)) {
            foreach ($cart_discount_details as $rule_id => $details) {
                if (!isset(self::$available_rules[$rule_id])) { continue; }
                $rule = self::$available_rules[$rule_id];
                $rule_type = substr($rule->getRuleDiscountType(), 4); // without prefix (wdr_)
                $discount = [
                    'applied_in' => 'cart_level',
                    'discount_type' => isset($details['cart_discount_type']) ? $details['cart_discount_type'] : '',
                    'discount_value' => isset($details['cart_discount']) ? round($details['cart_discount']) : 0,
                    'discount_label' => isset($details['cart_discount_label']) ? $details['cart_discount_label'] : '',
                    'discount_price' => isset($details['cart_discount_product_price'][$product_id][$rule_id])
                        ? round($details['cart_discount_product_price'][$product_id][$rule_id], 4)
                        : 0,
                ];
                $applied_rule_discounts[] = [
                    'id' => $rule_id,
                    'title' => $rule->getTitle(),
                    'type' => $rule_type,
                    'discount' => $discount,
                ];
            }
        }
        return $applied_rule_discounts;
    }

    /**
     * Calculate the product's initial and discount price
     * @param $product
     * @param $quantity
     * @param $is_cart
     * @param $ajax_price
     * @return array|bool
     */
    static function calculateInitialAndDiscountedPrice($product, $quantity, $is_cart = false, $ajax_price = false, $cart_item = array(), $on_coupon_validate = false)
    {
        return self::$calculator->getProductPriceToDisplay($product, $quantity, $is_cart, $ajax_price, $cart_item, $on_coupon_validate);
    }

    /**
     * Re-calculate cart total
     * */
    public static function reCalculateCartTotal()
    {
        if (function_exists('WC')) {
            if(isset(WC()->cart) && WC()->cart != null){
                if (is_object(WC()->cart) && method_exists(WC()->cart, 'calculate_totals')) {
                    WC()->cart->calculate_totals();
                }
            }
        }
    }

    /**
     * Calculate cart totals if is not calculated already
     * */
    public static function calculateCartTotalIfIsNotCalculated()
    {
        if (!did_action('woocommerce_before_calculate_totals')) {
            self::reCalculateCartTotal();
        }
    }

    /**
     * @param $discount_details
     * @param $initial_price
     * @param $discounted_price
     * @param $product_obj
     * @param $price_html
     * @param $cart_item_qty
     * @param $ajax_price
     * @return bool|string
     */
    function getSetDiscountItemPriceHtml($discount_details, $initial_price, $discounted_price, $product_obj, $price_html, $cart_item_qty=0, $ajax_price = false)
    {
        if (is_array($discount_details) && !empty($discount_details)) {
            $bogo_cheapest_aditional = array();
            $calculator = self::$calculator;
            $other_discounts = $total_discount_price = $total_quantity = $current_product_quantity = $discount_operator = $bogo_cheapest_aditional_sum = 0;
            $partially_qualified_set = array();
            $partially_qualified_set_amount_duplicate = array();
            $apply_full_set = false;
            $int_increment = 0;
            foreach ($discount_details as $detail_key => $detail) {
                $bogo_cheapest_discount = $bogo_cheapest_quantity = 0;
                //BOGO cheapest discount
                $bogo_cheap_in_cart = isset($detail['buy_x_get_y_cheapest_in_cart_discount']) ? $detail['buy_x_get_y_cheapest_in_cart_discount'] : '';
                $bogo_cheap_in_products = isset($detail['buy_x_get_y_cheapest_from_products_discount']) ? $detail['buy_x_get_y_cheapest_from_products_discount'] : '';
                $bogo_cheap_in_category = isset($detail['buy_x_get_y_cheapest_from_categories_discount']) ? $detail['buy_x_get_y_cheapest_from_categories_discount'] : '';
                $bogo_auto_add = isset($detail['buy_x_get_y_discount']) ? $detail['buy_x_get_y_discount'] : '';
                $bogo_getx = isset($detail['buy_x_get_x_discount']) ? $detail['buy_x_get_x_discount'] : '';
                $buy_x_get_y_cheapest_additional = isset($detail['buy_x_get_y_cheapest_in_cart_discount']['additional_discounts']) ? $detail['buy_x_get_y_cheapest_in_cart_discount']['additional_discounts'] : '';
                if(!empty($buy_x_get_y_cheapest_additional)){
                    $all_matched_rule =  self::$config->getConfig('apply_product_discount_to', 'biggest_discount');//all
                    foreach ($buy_x_get_y_cheapest_additional as $aditional){
                        if($all_matched_rule != 'all' && $price_html){
                            $price_html = $this->buy_x_get_y_cheapest_additional_strike_out($product_obj, $bogo_cheap_in_cart, $initial_price, $cart_item_qty);
                            if(!empty($price_html)){
                                return $price_html;
                            }
                        }
                        $bogo_cheapest_discount_aditional = isset($aditional['discount_price_per_quantity']) ? $aditional['discount_price_per_quantity'] : 0;
                        $bogo_cheapest_quantity_aditional = isset($aditional['discount_quantity']) ? $aditional['discount_quantity'] : 0;
                        $bogo_cheapest_aditional[] = $bogo_cheapest_discount_aditional*$bogo_cheapest_quantity_aditional;
                    }
                    if($all_matched_rule == 'all' && !empty($cart_item_qty)){
                        $bogo_cheapest_discount_extra = isset($bogo_cheap_in_cart['discount_price_per_quantity']) ? $bogo_cheap_in_cart['discount_price_per_quantity'] : 0;
                        $bogo_cheapest_quantity_extra = isset($bogo_cheap_in_cart['discount_quantity']) ? $bogo_cheap_in_cart['discount_quantity'] : 0;
                        $bogo_cheapest_aditional_discount =  array_sum($bogo_cheapest_aditional);
                        if($bogo_cheapest_quantity_extra > 1){
                            $bogo_cheapest_quantity_extra = (int)$bogo_cheapest_quantity_extra - 1;
                            $bogo_cheapest_aditional_discount += $bogo_cheapest_discount_extra*$bogo_cheapest_quantity_extra;
                        }
                        if($cart_item_qty != 0){
                            $bogo_cheapest_aditional_discount = $bogo_cheapest_aditional_discount / $cart_item_qty;
                        }
                        $discounted_price = $discounted_price-$bogo_cheapest_aditional_discount;
                        $bogo_cheap_in_cart = array();
                    }else{
                        $bogo_cheapest_aditional_sum = array_sum($bogo_cheapest_aditional);
                    }
                }
                if(!empty($bogo_cheap_in_cart)){
                    $bogo_cheapest_discount = isset($bogo_cheap_in_cart['discount_price_per_quantity']) ? $bogo_cheap_in_cart['discount_price_per_quantity'] : 0;
                    $bogo_cheapest_quantity = isset($bogo_cheap_in_cart['discount_quantity']) ? $bogo_cheap_in_cart['discount_quantity'] : 0;
                }elseif(!empty($bogo_cheap_in_products)){
                    $bogo_cheapest_discount = isset($bogo_cheap_in_products['discount_price_per_quantity']) ? $bogo_cheap_in_products['discount_price_per_quantity'] : 0;
                    $bogo_cheapest_quantity = isset($bogo_cheap_in_products['discount_quantity']) ? $bogo_cheap_in_products['discount_quantity'] : 0;
                }elseif (!empty($bogo_cheap_in_category)){
                    $bogo_cheapest_discount = isset($bogo_cheap_in_category['discount_price_per_quantity']) ? $bogo_cheap_in_category['discount_price_per_quantity'] : 0;
                    $bogo_cheapest_quantity = isset($bogo_cheap_in_category['discount_quantity']) ? $bogo_cheap_in_category['discount_quantity'] : 0;
                }elseif(!empty($bogo_auto_add)){
                    $bogo_cheapest_discount = isset($bogo_auto_add['discount_price_per_quantity']) ? $bogo_auto_add['discount_price_per_quantity'] : 0;
                    $bogo_cheapest_quantity = isset($bogo_auto_add['discount_quantity']) ? $bogo_auto_add['discount_quantity'] : 0;
                }else{
                    $bogo_cheapest_discount = isset($bogo_getx['discount_price_per_quantity']) ? $bogo_getx['discount_price_per_quantity'] : 0;
                    $bogo_cheapest_quantity = isset($bogo_getx['discount_quantity']) ? $bogo_getx['discount_quantity'] : 0;
                }
                $bogo_partial_set = '';
                if(!empty($bogo_cheapest_quantity) && $bogo_cheapest_quantity < $cart_item_qty){
                    $bogo_partial_set = 'yes';
                }elseif($bogo_cheapest_quantity == $cart_item_qty){
                    $bogo_cheapest_full_matched_set = $bogo_cheapest_discount;
                }

                $int_increment++;
                //per product quantity for set discount
                $fully_qualified_set = $discounted_price_per_set = $bogo_cheapest_full_matched_set = 0;
                $total_quantity = $cart_item_qty;
                $current_product_quantity = $cart_item_qty;
                if(!empty($detail['set_discount'])){
                    $total_quantity = isset($detail['set_discount']['total_quantity']) ? $detail['set_discount']['total_quantity'] : 0;
                    $discount_operator = isset($detail['set_discount']['discount_operator']) ? $detail['set_discount']['discount_operator'] : "";
                    //original price quantity for set discount
                    $original_price_quantity = isset($detail['set_discount']['original_price_quantity']) ? $detail['set_discount']['original_price_quantity'] : 0;
                    //min set quantity
                    $discounted_price_quantity = isset($detail['set_discount']['discounted_price_quantity']) ? $detail['set_discount']['discounted_price_quantity'] : 0;
                    //set discounted price
                    $discounted_price_per_set = isset($detail['set_discount']['discount_value']) ? $detail['set_discount']['discount_value'] : 0;
                    $discounted_price_quantities = isset($detail['set_discount']['discounted_price_quantity']) ? $detail['set_discount']['discounted_price_quantity'] : 0;
                    if (!empty($total_quantity)) {
                        self::$set_total_quantity = $total_quantity;
                    }
                    if (empty($total_quantity)) {
                        $total_quantity = self::$set_total_quantity;
                    }
                }

                //simple discounted price
                if(isset($detail['simple_discount']) && is_array($detail['simple_discount'])){
                    $simple_discount = isset($detail['simple_discount']['discount_price']) ? $detail['simple_discount']['discount_price'] : 0;
                } else {
                    $simple_discount = isset($detail['simple_discount']) ? $detail['simple_discount'] : 0;
                }
                //bulk discounted price
                if(isset($detail['bulk_discount']) && is_array($detail['bulk_discount'])){
                    $bulk_discounts = isset($detail['bulk_discount']['discount_price']) ? $detail['bulk_discount']['discount_price'] : 0;
                } else {
                    $bulk_discounts = isset($detail['bulk_discount']) ? $detail['bulk_discount'] : 0;
                }


                //set discounted price quantity
                $total_discount_price += $discounted_price_per_set + $simple_discount + $bulk_discounts + $bogo_cheapest_discount;
                if (empty($original_price_quantity) && empty($discounted_price_quantity) && empty($bogo_partial_set)) {
                    if (!empty($discounted_price_per_set)) {
                        $fully_qualified_set = $discounted_price_per_set;
                    }
                } else {
                    if(!empty($detail['set_discount']) && !empty($discounted_price_per_set) && !empty($discounted_price_quantities)){
                        if (isset($partially_qualified_set[$discounted_price_per_set]) && !empty($partially_qualified_set[$discounted_price_per_set])) {
                            $partially_qualified_set_amount_duplicate[$discounted_price_per_set . '-' . $int_increment] = $discounted_price_quantities;
                        } else {
                            $partially_qualified_set[$discounted_price_per_set] = $discounted_price_quantities;
                        }
                    }
                    if(!empty($bogo_partial_set)){
                        $int_increment++;
                        if (isset($partially_qualified_set[$bogo_cheapest_discount]) && !empty($partially_qualified_set[$bogo_cheapest_discount])) {
                            $partially_qualified_set_amount_duplicate[$bogo_cheapest_discount . '-' . $int_increment] = $bogo_cheapest_quantity;
                        } else {
                            $partially_qualified_set[$bogo_cheapest_discount] = $bogo_cheapest_quantity;
                        }
                    }

                }
                $other_discounts += $simple_discount + $bulk_discounts + $fully_qualified_set + $bogo_cheapest_full_matched_set + $bogo_cheapest_aditional_sum;
            }
            $initial_price_with_tax_call = $discounted_price_with_tax_call = 0;
            if (empty($partially_qualified_set) || $apply_full_set == true) {
                if ($price_html) {
                    if($ajax_price){
                        $discounted_price = $initial_price - $discounted_price;
                        if($discounted_price < 0){
                            $discounted_price = 0;
                        }

                        if(!empty($initial_price)){
                            $initial_price_with_tax_call = $calculator->mayHaveTax($product_obj, $initial_price);
                        }
                        if(!empty($discounted_price)){
                            $discounted_price_with_tax_call = $calculator->mayHaveTax($product_obj, $discounted_price);
                        }
                        $item_price = $this->getStrikeoutPrice($initial_price_with_tax_call, $discounted_price_with_tax_call);
                        return $item_price.Woocommerce::getProductPriceSuffix($product_obj, $discounted_price_with_tax_call);
                    }
                    if(!empty($initial_price)){
                        $initial_price_with_tax_call = $calculator->mayHaveTax($product_obj, $initial_price);
                    }
                    if(!empty($discounted_price)){
                        $discounted_price_with_tax_call = $calculator->mayHaveTax($product_obj, $discounted_price);
                    }
                    $item_price = $this->getStrikeoutPrice($initial_price_with_tax_call, $discounted_price_with_tax_call);

                    return $item_price.Woocommerce::getProductPriceSuffix($product_obj, $discounted_price_with_tax_call);
                } else {
                    if($discounted_price < 0){
                        $discounted_price = 0;
                    }
                    return $discounted_price;
                    ///return false;
                }
            } else {
                $original_item_price_html = $this->getSetStrikeoutPrice($initial_price, $partially_qualified_set, $total_discount_price, $other_discounts, $total_quantity, $product_obj, $price_html, $current_product_quantity, $discount_operator, $partially_qualified_set_amount_duplicate);
                return $original_item_price_html;
            }
        } else {
            return false;
        }
    }

    /**
     * buy_x_get_y_cheapest_additional_strike_out
     * @param $product_obj
     * @param $bogo_cheap_in_cart
     * @param $initial_price
     * @param $cart_item_qty
     * @return string
     */
    public function buy_x_get_y_cheapest_additional_strike_out($product_obj, $bogo_cheap_in_cart, $initial_price, $cart_item_qty)
    {
        $price_html = $original_price_with_tax_call = $discounted_price_with_tax_call = '';
        $bogo_cheap_in_cart_first_discount = isset($bogo_cheap_in_cart['discount_price_per_quantity']) ? $bogo_cheap_in_cart['discount_price_per_quantity'] : 0;
        $bogo_cheap_in_cart_first_discount_qty = isset($bogo_cheap_in_cart['discount_quantity']) ? $bogo_cheap_in_cart['discount_quantity'] : 0;
        $discounted_price = $bogo_cheap_in_cart_first_discount * $bogo_cheap_in_cart_first_discount_qty;
        $discounted_price = $initial_price - $discounted_price;
        if(!empty($initial_price)){
            $original_price_with_tax_call = self::$calculator->mayHaveTax($product_obj, $initial_price);
        }
        if(!empty($discounted_price)){
            $discounted_price_with_tax_call = self::$calculator->mayHaveTax($product_obj, $discounted_price);
        }
        $price_suffix = Woocommerce::getProductPriceSuffix($product_obj, $discounted_price_with_tax_call);
        $price_html .= '<del>' . self::$woocommerce_helper->formatPrice($original_price_with_tax_call) . '</del>&nbsp;<ins>' . self::$woocommerce_helper->formatPrice($discounted_price_with_tax_call) .$price_suffix. '&nbsp;x&nbsp;' . $bogo_cheap_in_cart_first_discount_qty . '</ins><br/>';
        $bogo_cheap_in_cart_total_discount_qty[] = $bogo_cheap_in_cart_first_discount_qty;
        $bogo_cheap_in_cart_additional_discounts = $bogo_cheap_in_cart['additional_discounts'];
        foreach ($bogo_cheap_in_cart_additional_discounts as $additional_discount){
            $bogo_cheapest_discount_aditional = isset($additional_discount['discount_price_per_quantity']) ? $additional_discount['discount_price_per_quantity'] : 0;
            $bogo_cheapest_quantity_aditional = isset($additional_discount['discount_quantity']) ? $additional_discount['discount_quantity'] : 0;

            $discounted_price = $bogo_cheapest_discount_aditional * $bogo_cheapest_quantity_aditional;
            $discounted_price = $initial_price - $discounted_price;
            if(!empty($discounted_price)){
                $discounted_price_with_tax_call = self::$calculator->mayHaveTax($product_obj, $discounted_price);
            }
            $price_suffix = Woocommerce::getProductPriceSuffix($product_obj, $discounted_price_with_tax_call);
            $price_html .= '<del>' . self::$woocommerce_helper->formatPrice($original_price_with_tax_call) . '</del>&nbsp;<ins>' . self::$woocommerce_helper->formatPrice($discounted_price_with_tax_call) .$price_suffix. '&nbsp;x&nbsp;' . $bogo_cheapest_quantity_aditional . '</ins><br/>';

            $bogo_cheap_in_cart_total_discount_qty[] = $bogo_cheapest_quantity_aditional;
        }
        $discount_applied_qty = array_sum($bogo_cheap_in_cart_total_discount_qty);
        if($discount_applied_qty < $cart_item_qty){
            $not_applied = $cart_item_qty - $discount_applied_qty;
            $price_suffix = Woocommerce::getProductPriceSuffix($product_obj, $original_price_with_tax_call);
            $price_html .= '<ins>' . self::$woocommerce_helper->formatPrice($original_price_with_tax_call) .$price_suffix. '&nbsp;x&nbsp;' . $not_applied . '</ins>';
        }
        return $price_html;
    }

    /**
     * Calculate discount for product or calculate discount from custom price
     * @param $price
     * @param $product
     * @param int $quantity
     * @param int $custom_price
     * @param string $get_only
     * @return array|float|false
     */
    static function calculateProductDiscountPrice($price, $product, $quantity = 1, $custom_price = 0, $get_only = 'discounted_price', $manual_request = false, $is_cart = true)
    {
        if (!is_a($product, 'WC_Product')) {
            if (is_numeric($product)) {
                $product = self::$woocommerce_helper->getProduct($product);
            } else {
                return false;
            }
        }
        if (!$product) {
            return false;
        }
        $discounts = self::$calculator->mayApplyPriceDiscount($product, $quantity, $custom_price, false, array(), $is_cart, $manual_request);

        if ($discounts) {
            switch ($get_only) {
                case 'all':
                    $product_id = self::$woocommerce_helper->getProductId($product);
                    if (isset($discounts['total_discount_details'][$product_id])) {
                        $discounts['total_discount_details'] = $discounts['total_discount_details'][$product_id];
                    }
                    if (isset($discounts['cart_discount_details'])) {
                        unset($discounts['cart_discount_details']);
                    }
                    $price = $discounts;
                    break;
                default:
                case 'discounted_price':
                    $price = isset($discounts['discounted_price']) ? $discounts['discounted_price'] : $price;
                    break;
            }
        } else {
            return false;
        }
        return $price;
    }

    /**
     * Get discount price of a product
     * @param int|float|false $product_price
     * @param int|\WC_Product $product_or_id
     * @param int|float $quantity
     * @param int|float $custom_price
     * @return float|false
     * */
    static function getDiscountPriceOfAProduct($product_price, $product_or_id, $quantity = 1, $custom_price = 0)
    {
        $details = self::getDiscountDetailsOfAProduct(false, $product_or_id, $quantity, $custom_price);
        if ($details !== false && isset($details['discounted_price'])) {
            return (float) $details['discounted_price'];
        }
        return $product_price;
    }

    /**
     * Get discount details of a product
     * @param array|false $discount_details
     * @param int|\WC_Product $product_or_id
     * @param int|float $quantity
     * @param int|float $custom_price
     * @return array|false
     * */
    static function getDiscountDetailsOfAProduct($discount_details, $product_or_id, $quantity = 1, $custom_price = 0)
    {
        $product = $product_or_id;
        if (is_numeric($product)) {
            $product = self::$woocommerce_helper->getProduct($product);
        }
        if (!is_object($product) || !is_a($product, 'WC_Product')) {
            return false;
        }

        if (Woocommerce::productTypeIs($product, array('variable'))) {
            $variation_product = Woocommerce::getFirstChildOfVariableProduct($product);
            if (is_object($variation_product)) {
                $product = $variation_product;
            }
        }

        $discounts = self::calculateProductDiscountPrice(false, $product, $quantity, $custom_price, 'all', true, false);
        if ($discounts !== false && is_array($discounts)) {
            return self::prepareDiscountDetails($discounts);
        }
        return $discount_details;
    }

    /**
     * Get discount percentage of a product
     * @param int|float|false $discount_percentage
     * @param int|\WC_Product $product_or_id
     * @return float|false
     * */
    static function getDiscountPercentageOfAProduct($discount_percentage, $product_or_id)
    {
        $details = self::getDiscountDetailsOfAProduct(false, $product_or_id);
        if ($details !== false && is_array($details) && !empty($details['initial_price']) && isset($details['discounted_price'])) {
            return round((($details['initial_price'] - $details['discounted_price']) / $details['initial_price'])  * 100, 4);
        }
        return $discount_percentage;
    }

    /**
     * Get save amount of a product
     * @param int|float|false $save_amount
     * @param int|\WC_Product $product_or_id
     * @return float|false
     * */
    static function getSaveAmountOfAProduct($save_amount, $product_or_id)
    {
        if ($details = self::getDiscountDetailsOfAProduct(false, $product_or_id)) {
            if (is_array($details) && !empty($details['initial_price_based_on_tax_settings']) && isset($details['discounted_price_based_on_tax_settings'])) {
                return round($details['initial_price_based_on_tax_settings'] - $details['discounted_price_based_on_tax_settings'], 4);
            }
        }
        return $save_amount;
    }

    /**
     * Get discount price of a product in cart
     * @param int|float|false $product_price
     * @param string|array $cart_item_or_key
     * @return float|false
     * */
    static function getDiscountPriceFromCartItem($product_price, $cart_item_or_key)
    {
        if ($data = self::getDiscountDetailsFromCartItem(false, $cart_item_or_key)) {
            if (is_array($data) && isset($data['discounted_price'])) {
                return (float) $data['discounted_price'];
            }
        }
        return $product_price;
    }

    /**
     * Get discount details of a product in cart
     * @param array|false $discount_details
     * @param string|array $cart_item_or_key
     * @return array|false
     * */
    static function getDiscountDetailsFromCartItem($discount_details, $cart_item_or_key)
    {
        if (is_array($cart_item_or_key) && isset($cart_item_or_key['key'])) {
            $cart_item = $cart_item_or_key;
            $key = (string) $cart_item['key'];
        } elseif (is_string($cart_item_or_key)) {
            $key = $cart_item_or_key;
            $cart_item = self::$woocommerce_helper->getCartItem($key);
        }

        if (empty($key) || empty($cart_item) || !is_array($cart_item)) {
            return false;
        }

        $product_id = $cart_item['variation_id'] > 0 ? $cart_item['variation_id'] : $cart_item['product_id'];
        if (isset(self::$calculated_cart_item_discount[$key])) {
            $cart_item_discounts = self::$calculated_cart_item_discount[$key];
        }
        if (empty($cart_item_discounts) && $free_item_discounts = apply_filters('advanced_woo_discount_rules_get_auto_add_discount_details_from_cart_item', array(), $cart_item, $key)) {
            $cart_item_discounts = $free_item_discounts;
        }

        if (!empty($cart_item_discounts)) {
            return self::prepareDiscountDetails($cart_item_discounts, $product_id, 1, $key);
        }
        return $discount_details;
    }

    /**
     * Get save amount of a product in cart
     * @param int|float|false $saved_amount
     * @param string|array $cart_item_or_key
     * @return float|false
     * */
    static function getSavedAmountFromCartItem($saved_amount, $cart_item_or_key)
    {
        if ($details = self::getDiscountDetailsFromCartItem(false, $cart_item_or_key)) {
            if (is_array($details) && !empty($details['initial_price_based_on_tax_settings']) && isset($details['discounted_price_based_on_tax_settings'])) {
                return round($details['initial_price_based_on_tax_settings'] - $details['discounted_price_based_on_tax_settings'], 4);
            }
        }
        return $saved_amount;
    }

    /**
     * Get discount of a product in order
     * @param int|float|false $product_price
     * @param int|\WC_Order_Item $order_item_or_id
     * @return float|false
     * */
    static function getDiscountPriceFromOrderItem($product_price, $order_item_or_id)
    {
        if ($details = self::getDiscountDetailsFromOrderItem(false, $order_item_or_id)) {
            if (is_array($details) && isset($details['discounted_price'])) {
                return (float) $details['discounted_price'];
            }
        }
        return $product_price;
    }

    /**
     * Get discount details of a product in order
     * @param array|false $discount_details
     * @param int|\WC_Order_Item $order_item_or_id
     * @return array|false
     * */
    static function getDiscountDetailsFromOrderItem($discount_details, $order_item_or_id)
    {
        if (is_object($order_item_or_id) && is_a($order_item_or_id, '\WC_Order_Item')) {
            $order_item = $order_item_or_id;
        } elseif (is_numeric($order_item_or_id)) {
            $order_item = self::$woocommerce_helper->getOrderItem($order_item_or_id);
        } else {
            return false;
        }

        if (is_object($order_item) && method_exists($order_item, 'get_meta')) {
            $data = $order_item->get_meta('_wdr_discounts');
            if (empty($data)) {
                $data = $order_item->get_meta('_advanced_woo_discount_item_total_discount');
                if (!empty($data) && is_array($data)) {
                    $data['initial_price_with_tax'] = $data['initial_price_based_on_tax_settings'];
                    $data['discounted_price_with_tax'] = $data['discounted_price_based_on_tax_settings'];
                    return self::prepareDiscountDetails($data);
                }
            }
            if (!empty($data) && is_array($data)) {
                return $data;
            }
            return $discount_details;
        }
        return false;
    }

    /**
     * Get save amount of a product in order
     * @param int|float|false $saved_amount
     * @param int|\WC_Order_Item $order_item_or_id
     * @return float|false
     * */
    static function getSavedAmountFromOrderItem($saved_amount, $order_item_or_id)
    {
        if ($details = self::getDiscountDetailsFromOrderItem(false, $order_item_or_id)) {
            if (is_array($details) && !empty($details['initial_price_based_on_tax_settings']) && isset($details['discounted_price_based_on_tax_settings'])) {
                return round($details['initial_price_based_on_tax_settings'] - $details['discounted_price_based_on_tax_settings'], 4);
            }
        }
        return $saved_amount;
    }

    /**
     * Get discount details from order
     * @param array|false $discount_details
     * @param int|\WC_Order $order_or_id
     * @return array|false
     * */
    static function getDiscountDetailsFromOrder($discount_details, $order_or_id)
    {
        if (is_object($order_or_id) && is_a($order_or_id, '\WC_Order')) {
            $order = $order_or_id;
        } elseif (is_numeric($order_or_id)) {
            $order = self::$woocommerce_helper->getOrder($order_or_id);
        } else {
            return false;
        }

        if (is_object($order) && method_exists($order, 'get_meta')) {
            $data = $order->get_meta('_wdr_discounts');
            if (!empty($data) && is_array($data)) {
                return $data;
            }
            return $discount_details;
        }
        return false;
    }

    /**
     * Get save amount from order
     * @param int|float|false $saved_amount
     * @param int|\WC_Order $order_or_id
     * @return float|false
     * */
    static function getSavedAmountFromOrder($saved_amount, $order_or_id)
    {
        if ($details = self::getDiscountDetailsFromOrder(false, $order_or_id)) {
            if (is_array($details) && isset($details['saved_amount']['total_based_on_tax_settings'])) {
                return $details['saved_amount']['total_based_on_tax_settings'];
            }
        }
        return $saved_amount;
    }

    /**
     * Change the default template for sale badge
     *
     * @param $located string
     * @param $template_name string
     * @param $args array
     * @param $template_path string
     * @param $default_path string
     * @return string
     * */
    public static function changeTemplateForSaleTag($located, $template_name, $args, $template_path, $default_path){
        if($template_name == 'single-product/sale-flash.php'){
            $located = Helper::getTemplatePath('sale-flash.php', WDR_PLUGIN_PATH . 'App/Views/Templates/single-product/sale-flash.php', 'single-product');
        } else if($template_name == 'loop/sale-flash.php'){
            $located = Helper::getTemplatePath('sale-flash.php', WDR_PLUGIN_PATH . 'App/Views/Templates/loop/sale-flash.php', 'loop');
        }

        return $located;
    }

    /**
     * Remove on sale flash except our hooks
     * */
    function removeOnSaleFlashEvent(){
        $allowed_hooks = array(
            //Filters
            "woocommerce_sale_flash" => array( "Wdr\App\Controllers\ManageDiscount|replaceSaleTagText" ),
        );

        $this->removeOtherEvents($allowed_hooks);
    }

    /**
     * Suppress third party discount plugins from accessing the discount data
     */
    function suppressOtherDiscountPlugins()
    {
        $allowed_hooks = array(
            //Filters
            "woocommerce_get_price_html" => array("Wdr\App\Controllers\ManageDiscount|getPriceHtml", "Wdr\App\Controllers\ManageDiscount|getPriceHtmlSalePriceAdjustment"),
            "woocommerce_product_is_on_sale" => array("Wdr\App\Controllers\ManageDiscount|isProductInSale"),
            "woocommerce_product_get_sale_price" => array(),
            "woocommerce_product_get_regular_price" => array(),
            "woocommerce_variable_price_html" => array("Wdr\App\Controllers\ManageDiscount|getVariablePriceHtml"),
            "woocommerce_cart_item_price" => array("Wdr\App\Controllers\ManageDiscount|getCartPriceHtml"),
            "woocommerce_cart_item_subtotal" => array("Wdr\App\Controllers\ManageDiscount|getCartProductSubtotalPriceHtml"),
            //Actions
            "woocommerce_checkout_order_processed" => array(),
            "woocommerce_before_calculate_totals" => array("Wdr\App\Controllers\ManageDiscount|applyCartProductDiscount"), //nothing allowed!
        );
        $allowed_hooks = apply_filters('advanced_woo_discount_rules_exclude_hooks_from_removing', $allowed_hooks);
        $this->removeOtherEvents($allowed_hooks);
    }

    /**
     * Remove methods from the event
     * Exclude from the list
     *
     * @param $allowed_hooks array
     * */
    function removeOtherEvents($allowed_hooks){
        global $wp_filter;
        foreach ($wp_filter as $hook_name => $hook_obj) {
            if (preg_match('#^woocommerce_#', $hook_name)) {
                if (isset($allowed_hooks[$hook_name])) {
                    $wp_filter[$hook_name] = $this->removeWrongCallbacks($hook_obj, $allowed_hooks[$hook_name]);
                }
            }
        }
    }

    /**
     * Remove the third party call backs
     * @param $hook_obj
     * @param $allowed_hooks
     * @return mixed
     */
    function removeWrongCallbacks($hook_obj, $allowed_hooks)
    {
        if (method_exists($hook_obj, 'offsetSet') && method_exists($hook_obj, 'offsetUnset')) {
            foreach ($hook_obj->callbacks as $priority => $callbacks) {
                $priority_callbacks = array();
                foreach ($callbacks as $idx => $callback_details) {
                    if ($this->isCallbackMatch($callback_details, $allowed_hooks)) {
                        $priority_callbacks[$idx] = $callback_details;
                    }
                }
                $hook_obj->offsetUnset($priority);
                if ($priority_callbacks) {
                    $hook_obj->offsetSet($priority, $priority_callbacks);
                }
            }
        } else {
            $new_callbacks = array();
            foreach ($hook_obj->callbacks as $priority => $callbacks) {
                $priority_callbacks = array();
                foreach ($callbacks as $idx => $callback_details) {
                    if ($this->isCallbackMatch($callback_details, $allowed_hooks)) {
                        $priority_callbacks[$idx] = $callback_details;
                    }
                }
                if ($priority_callbacks) {
                    $new_callbacks[$priority] = $priority_callbacks;
                }
            }
            $hook_obj->callbacks = $new_callbacks;
        }
        return $hook_obj;
    }

    /**
     * check the call back matched or not
     * @param $callback_details
     * @param $allowed_hooks
     * @return bool
     */
    function isCallbackMatch($callback_details, $allowed_hooks)
    {
        $result = false;
        foreach ($allowed_hooks as $callback_name) {
            list($class_name, $func_name) = explode("|", $callback_name);
            if(empty($callback_details['function'])){
                continue;
            }
            if(is_string($callback_details['function'])){
                continue;
            }

            if(is_object($callback_details['function'])){
                if($this->is_closure($callback_details['function'])){
                    continue;
                }
            }

            if (count($callback_details['function']) != 2) {
                continue;
            }

            if(!is_object($callback_details['function'][0])){
                continue;
            }

            if ($class_name == get_class($callback_details['function'][0]) AND $func_name == $callback_details['function'][1]) {
                $result = true;
                break;// done!
            }
        }
        return $result;
    }

    function is_closure($t) {
        if(class_exists('\Closure')){
            return $t instanceof \Closure;
        } else {
            return false;
        }
    }

    /**
     * Display you saved text in order pages
     * @param $total
     * @param $order
     * @return string
     */
    function displayTotalSavingsInOrderAfterOrderTotal($total, $order)
    {
        if (!is_object($order)) {
            if (!empty($order) && is_int($order)) {
                $order = self::$woocommerce_helper->getOrder($order);
            }
        }
        $items = $order->get_items();
        $total_discount = $this->getItemTotalDiscount($items);
        $save_text = NULL;
        if (!empty($total_discount)) {
            $total_discounted_price = self::$woocommerce_helper->formatPrice($total_discount, array('currency' => self::$woocommerce_helper->getOrderCurrency($order)));
            $subtotal_additional_text = $this->getYouSavedText($total_discounted_price);
            $save_text = apply_filters('advanced_woo_discount_rules_order_saved_text', $subtotal_additional_text, $total_discounted_price, $total_discount);
        }
        return $total . $save_text;
    }

    /**
     * Calculate items total discount
     * @param $items
     * @return float|int
     */
    function getItemTotalDiscount($items)
    {
        $total_discount = 0;
        if (!empty($items)) {
            foreach ($items as $key => $item) {
                $discount_details = $item->get_meta('_advanced_woo_discount_item_total_discount');
                if (!empty($discount_details)) {
                    $total_discount += $this->getDiscountPerItem($discount_details);
                }
            }
        }
        return $total_discount;
    }

    /**
     * order line item saved text
     * @param $subtotal
     * @param $item
     * @param $order
     * @return string
     */
    function orderSubTotalDiscountDetails($subtotal, $item, $order)
    {
        $discount_details = self::$woocommerce_helper->getOrderItemMeta($item, '_advanced_woo_discount_item_total_discount');
        if (!empty($discount_details)) {
            $total_discount = $this->getDiscountPerItem($discount_details);
            if (!empty($total_discount)) {
                $total_discounted_price = self::$woocommerce_helper->formatPrice($total_discount, array('currency' => self::$woocommerce_helper->getOrderCurrency($order)));
                $subtotal_additional_text = $this->getYouSavedText($total_discounted_price);
                $subtotal .= apply_filters('advanced_woo_discount_rules_order_saved_text', $subtotal_additional_text, $total_discounted_price, $total_discount);
            }
        }
        return $subtotal;
    }

    /**
     * Show order discount details
     * @param $item_id
     * @param $item
     * @param $product
     */
    public function orderItemMetaDiscountDetails($item_id, $item, $product)
    {
        $discount_details = self::$woocommerce_helper->getOrderItemMeta($item, '_advanced_woo_discount_item_total_discount');
        if (!empty($discount_details)) {
            $total_discount = $this->getDiscountPerItem($discount_details);
            $order = self::$woocommerce_helper->getOrderByItem($item);
            if (!empty($order) && !empty($total_discount)) {
                $total_discounted_price = self::$woocommerce_helper->formatPrice($total_discount, array('currency' => self::$woocommerce_helper->getOrderCurrency($order)));
                $subtotal_additional_text = $this->getYouSavedText($total_discounted_price);
                echo apply_filters('advanced_woo_discount_rules_order_saved_text', $subtotal_additional_text, $total_discounted_price, $total_discount);
            }
        }
    }

    /**
     * Hide Zero Coupon Value
     * @param $coupon_html
     * @param $coupon
     * @return mixed
     */
    public function hideZeroCouponValue($coupon_html, $coupon)
    {
        $hide_zero_value_coupon = apply_filters('advanced_woo_discount_rules_hide_zero_value_coupon', true, $coupon);
        if ($hide_zero_value_coupon) {
            $rule_helper = new Rule();
            $original_coupon_html = $coupon_html;
            $all_coupon_codes = $rule_helper->getCouponsFromDiscountRules();
            $coupon_code = self::$woocommerce_helper->getCouponCode($coupon);
            $virtual_coupon = (isset($all_coupon_codes['custom_coupons']) && !empty($all_coupon_codes['custom_coupons'])) ? $all_coupon_codes['custom_coupons'] : array();
            $woo_coupons = (isset($all_coupon_codes['woo_coupons'][0]) && !empty($all_coupon_codes['woo_coupons'][0])) ? $all_coupon_codes['woo_coupons'][0] : array();
            if (!empty($woo_coupons) && in_array($coupon_code, $woo_coupons)) {
                $zero_price_html = '-' . self::$woocommerce_helper->formatPrice(0);
                $coupon_html = str_replace($zero_price_html, '', $coupon_html);
            } else if (!empty($virtual_coupon) && in_array($coupon_code, $virtual_coupon)) {
                $zero_price_html = '-' . self::$woocommerce_helper->formatPrice(0);
                $coupon_html = str_replace($zero_price_html, '', $coupon_html);
            }
            $coupon_html = apply_filters('advanced_woo_discount_rules_hide_zero_value_coupon_html', $coupon_html, $original_coupon_html, $coupon);
        }
        return $coupon_html;
    }

    /**
     * Display promotional messages
     * */
    public function displayPromotionMessages(){
        $cart = Woocommerce::getCart();
        $messages = Helper::getPromotionMessages();
        $messages = empty($cart) ? '' : $messages;
        if(!empty($messages) && is_array($messages)) {
            foreach ($messages as $message) {
                self::$woocommerce_helper->printNotice($message, "notice");
            }
        }
    }

    /**
     * Load outer div for displaying promotional message in check out
     * */
    public function displaySubTotalPromotionMessagesInCheckoutContainer(){
        echo "<div id='awdr_checkout_subtotal_promotion_messages'>";
        echo "</div>";
    }

    /**
     * Display promotional message in check out while processing order review
     * */
    public function  displaySubTotalPromotionMessagesInCheckout(){
        echo "<div id='awdr_checkout_subtotal_promotion_messages_data'>";
        $this->displayPromotionMessages();
        echo "</div>";
        echo "<script>";
        echo "jQuery('#awdr_checkout_subtotal_promotion_messages').html(jQuery('#awdr_checkout_subtotal_promotion_messages_data').html());jQuery('#awdr_checkout_subtotal_promotion_messages_data').remove()";
        echo "</script>";
    }


    /**
     * Export Data via CSV
     */
    public function awdrExportCsv(){
        if (isset($_POST['wdr-export']) && isset($_POST['security'])) {
            if(wp_verify_nonce($_POST['security'],'awdr_export_rules')) {
                ob_end_clean();
                $rule_helper = new Rule();
                $rules = $rule_helper->exportRuleByName('all');
                $file_name = 'advanced-discount-rules-' . date("Y-m-d-h-i-a") . '.csv';
                $file = fopen('php://output', 'w');
                header('Content-type: application/csv');
                header('Content-Disposition: attachment; filename=' . $file_name);
                $export_csv_separator = apply_filters('advanced_woo_discount_rules_csv_import_export_separator', ',');
                fputcsv($file, array('id', 'enabled', 'deleted', 'exclusive', 'title', 'priority', 'apply_to', 'filters', 'conditions', 'product_adjustments', 'cart_adjustments', 'buy_x_get_x_adjustments', 'buy_x_get_y_adjustments', 'bulk_adjustments', 'set_adjustments', 'other_discounts', 'date_from', 'date_to', 'usage_limits', 'rule_language', 'used_limits', 'additional', 'max_discount_sum', 'advanced_discount_message', 'discount_type', 'used_coupons', 'created_by', 'created_on', 'modified_by', 'modified_on'), $export_csv_separator);
                foreach ($rules as $rule_row) {
                    $row_data = (array)$rule_row;
                    fputcsv($file, $row_data, $export_csv_separator);
                }
                exit;
            }
        }
    }

    /**
     * Display total savings in order
     * @param $order
     */
    public function displayTotalSavingsThroughDiscountInOrder($order){
        if (!is_object($order)) {
            if (!empty($order) && is_int($order)) {
                $order = self::$woocommerce_helper->getOrder($order);
            }
        }
        $items = $order->get_items();
        $total_discount = $this->getItemTotalDiscount($items);

        $save_text = NULL;

        if (!empty($total_discount)) {
            $total_discounted_price = self::$woocommerce_helper->formatPrice($total_discount, array('currency' => self::$woocommerce_helper->getOrderCurrency($order)));
            $subtotal_additional_text = $this->getYouSavedText($total_discounted_price);
            $save_text = apply_filters('advanced_woo_discount_rules_order_saved_text', $subtotal_additional_text, $total_discounted_price, $total_discount);
        }
        echo $save_text;
    }

    /**
     * Include tax in fee
     * */
    public function applyTaxInFee($fee, $cart){
        if(Woocommerce::isTaxEnabled()){
            if(Woocommerce::isEnteredPriceIncludeTax()){
                if(class_exists('\WC_Tax')){
                    $fee_taxs = \WC_Tax::calc_tax( $fee, \WC_Tax::get_rates( '', \WC()->cart->get_customer() ), true );
                    if(!empty($fee_taxs)){
                        foreach ($fee_taxs as $key => $val){
                            $fee = $fee - $val;
                        }
                    }
                }
            }
        }

        return $fee;
    }
}
