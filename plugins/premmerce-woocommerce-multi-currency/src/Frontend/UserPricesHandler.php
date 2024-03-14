<?php

namespace Premmerce\WoocommerceMulticurrency\Frontend;

use Premmerce\SDK\V2\FileManager\FileManager;
use Premmerce\WoocommerceMulticurrency\Model\Model;
use \WC_Coupon;
use \WC_Product;
use \WP_Query;

//todo: rename this class to UserPricesManager
class UserPricesHandler
{
    /**
     * @var float
     */
    private $userCurrencyRate;

    /**
     * @var array
     */
    private $availableCurrencies;

    /**
     * @var string
     */
    private $shopCurrencyID;

    /**
     * @var Model
     */
    private $model;

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @var UserCurrencyHandler
     */
    private $userCurrencyHandler;

    /**
     * @var bool
     */
    private $ajaxPricesEnabled;

    /**
     * @var bool
     */
    private $filtersActive;

    /**
     * @var array
     */
    private $fieldsToReplace;

    /**
     * UserPricesHandler constructor.
     *
     * @param $fileManager
     * @param $model
     * @param $userCurrencyHandler
     */
    public function __construct(FileManager $fileManager, Model $model, $userCurrencyHandler)
    {
        $this->fileManager = $fileManager;
        $this->model = $model;
        $this->userCurrencyHandler = $userCurrencyHandler;
        $this->filtersActive = false;

        $this->fieldsToReplace = array(
            '_regular_price' => '_product_currency_regular_price',
            '_sale_price'    => '_product_currency_sale_price',
            '_price'         => ''
        );

        $this->shopCurrencyID = get_option(Model::MAIN_CURRENCY_OPTION_NAME);
        $this->availableCurrencies = $this->model->getCurrencies(false);

        $this->userCurrencyRate = (float) $this->userCurrencyHandler->getUserCurrencyField('rate');
        $this->ajaxPricesEnabled = (bool) get_option('premmerce_multicurrency_ajax_prices_redraw');
    }

    /**
     * @return bool
     *
     * @todo: add possibility to use this method from API.
     */
    public function isFiltersActive()
    {
        return $this->filtersActive;
    }

    /**
     * Filter prices and currency data on frontend.
     * Another part of frontend filters and actions see in Frontend class
     *
     * @todo: Avoid using post_meta to support coming WC separate product and order tables
     */
    public function setFilters()
    {
        add_filter('get_post_metadata', array($this, 'replacePrice'), 10, 4);


        //Manage Woocommerce cached prices
        add_filter('woocommerce_get_variation_prices_hash', array($this, 'manageWoocommercePricesHash'), 10, 3);

        //Filter Woocommerce coupons, taxes and shipping amounts.
        //@todo: replace this with named function so this filter can be removed by another plugins
        add_filter('woocommerce_coupon_get_amount', function ($amount, WC_Coupon $coupon) {
            if (!$coupon->is_type('percent')) {
                $amount = $this->calculatePriceInUsersCurrency($amount);
            }

            return $amount;
        }, 10, 2);

        add_filter('woocommerce_package_rates', function ($rates) {
            foreach ($rates as $name => $rate) {
                $rates[$name]->cost = $this->calculatePriceInUsersCurrency($rate->cost);
            }

            return $rates;
        });

        add_filter('woocommerce_calc_shipping_tax', function ($shippingPrices) {
            foreach ($shippingPrices as $index => $price) {
                $shippingPrices[$index] = $this->calculatePriceInUsersCurrency($price);
            }

            return $shippingPrices;
        });

        if (isset($_GET['min_price']) || isset($_GET['max_price'])) {
            add_action('plugins_loaded', function () {
                if (version_compare(wc()->version, '3.6', '>=')) {
                    add_filter('posts_clauses', array($this, 'fixProductQueryWithPriceFilter'), 9, 2);
                } else {
                    add_filter('woocommerce_product_query_meta_query', function ($meta_query) {
                        if (isset($meta_query['price_filter']['value'])) {
                            if ($this->userCurrencyRate != 1) {
                                $meta_query['price_filter']['value'][0] = $meta_query['price_filter']['value'][0] * $this->userCurrencyRate;
                                $meta_query['price_filter']['value'][1] = $meta_query['price_filter']['value'][1] * $this->userCurrencyRate;
                            }
                        }
                        return $meta_query;
                    });
                }
            });
        }


        //Woocommerce price filter
        add_filter('woocommerce_price_filter_widget_max_amount', function ($maxAmount) {
            return ceil($this->calculatePriceInUsersCurrency($maxAmount));
        });
        add_filter('woocommerce_price_filter_widget_min_amount', function ($minAmount) {
            return floor($this->calculatePriceInUsersCurrency($minAmount));
        });

        //Add span with current product id for AJAX prices redrawing

        add_filter('woocommerce_get_price_html', array($this, 'addPriceSpan'), 100, 2);

        if ($this->ajaxPricesEnabled) {
            add_action('wp_footer', array($this, 'outputCurrencyIdSpan'), 999);

            //Fix for unchangeable currency symbol when WP Rocket is enabled
            delete_transient('rocket_get_refreshed_fragments_cache');
        }

        add_filter('premmerce_wholesale_pricing_get_price', function ($price, WC_Product $product) {
            return $this->calculatePriceInUsersCurrency($price, $this->model->getProductCurrency($product));
        }, 10, 2);


        /**
         * @todo: remove this in future
         *
         * @see UserPricesHandler::applyGetPriceForUserFilter
         */
        add_action('plugins_loaded', function () {
            if (has_filter('premmerce_multicurrency_get_price_for_user')) {
                $priceFilters = array(
                    'woocommerce_product_get_price',
                    'woocommerce_product_get_regular_price',
                    'woocommerce_product_get_sale_price',
                    'woocommerce_product_variation_get_price',
                    'woocommerce_product_variation_get_regular_price',
                    'woocommerce_product_variation_get_sale_price',
                    'woocommerce_variation_prices_price',
                    'woocommerce_variation_prices_regular_price',
                    'woocommerce_variation_prices_sale_price'
                );

                foreach ($priceFilters as $filter) {
                    add_filter($filter, array($this, 'applyGetPriceForUserFilter'), 100, 2);
                }
            }
        });

        $this->filtersActive = true;
    }

    /**
     * If this is request to price meta fields, return price from custom price meta fields instead of native
     *
     * @param null              $originalValue
     * @param int               $objectId
     * @param string            $metaKey
     * @param bool              $single
     *
     * @return mixed
     *
     * @todo: Find better solution and replace this.
     */
    public function replacePrice($originalValue, $objectId, $metaKey, $single)
    {
        if ($metaKey && ! array_key_exists($metaKey, $this->fieldsToReplace)) {
            return $originalValue;
        }

        $postType = get_post_field('post_type', $objectId);

        $value = $originalValue;

        if (in_array($postType, array('product', 'product_variation'))) {
            remove_filter(current_filter(), array($this, 'replacePrice'));

            $value = get_metadata('post', $objectId, $metaKey, $single);

            if (version_compare(wc()->version, '3.6', '>=')) {
                if ($metaKey) {
                    $value = $this->replaceSinglePriceField($value, $objectId, $metaKey);
                } else {
                    $value = $this->replacePriceFieldsInArray($value, $objectId) ?: $originalValue;
                }
            } elseif ($single) {
                $value = $this->replaceSinglePriceField($value, $objectId, $metaKey);
            }

            add_filter(current_filter(), array($this, 'replacePrice'), 10, 4);
        }



        return $single ? $value : (array) $value;
    }

    /**
     * Replace product prices for Woocommerce below 3.6
     *
     * @param mixed     $value
     * @param int       $objectId
     * @param string    $metaKey
     *
     * @return mixed
     */
    private function replaceSinglePriceField($value, $objectId, $metaKey)
    {
        $fieldsToReplace = $this->getFieldsToReplace($objectId);

        if (isset($fieldsToReplace[$metaKey])) {
            $currencyId = $this->getProductCurrencyByProductId($objectId);
            $value = $this->getPriceForProductField($objectId, $fieldsToReplace[$metaKey], $metaKey, $currencyId);
        }

        return $value;
    }

    /**
     * Replace product prices for Woocommerce 3.6+
     *
     * @param array     $metaData
     * @param int       $objectId
     *
     * @return array|null
     */
    private function replacePriceFieldsInArray($metaData, $objectId)
    {
        foreach ($metaData as $key => $value) {
            if (array_key_exists($key, $this->fieldsToReplace)) {
                $currencyId = $this->getProductCurrencyByProductId($objectId);
                $fieldsToReplace = $this->getFieldsToReplace($objectId);
                $metaData[$key] = array($this->getPriceForProductField($objectId, $fieldsToReplace[$key], $key, $currencyId));
            }
        }

        return $metaData;
    }

    /**
     * @param $productId
     * @param $fieldName
     * @param $nativeFieldName
     * @param $currencyId
     * @return string|null
     *
     * @todo: Avoid using post_meta to support coming WC separate product and order tables
     */
    private function getPriceForProductField($productId, $fieldName, $nativeFieldName, $currencyId)
    {
        if (metadata_exists('post', $productId, $fieldName)) {
            $productPriceInProductCurrency = get_post_meta($productId, $fieldName, true);
        } else {
            //Below we get price from native WC field. This field always stores price in main shop currency.
            $currencyId = $this->model->getMainCurrencyId();
            $productPriceInProductCurrency = get_post_meta($productId, $nativeFieldName, true);
        }

        if ($productPriceInProductCurrency) {
            $value = $this->calculatePriceInUsersCurrency($productPriceInProductCurrency, $currencyId);
            $isSalePrice = '_product_currency_sale_price' === $fieldName;
            $value = apply_filters('premmerce_multicurrency_product_currency_price', $value, $productId, $isSalePrice);
        }

        return isset($value) ? $value : null;
    }

    /**
     * Create cached price hash based on user id and currency.
     * User id is needed for Premmerce Wholesale Price plugin and other plugins with user-based prices.
     *
     * @param array         $hash
     * @param WC_Product   $product
     * @param bool          $forDisplay
     *
     * @return array
     */
    public function manageWoocommercePricesHash($hash, WC_Product $product, $forDisplay)
    {
        $userId = (string) get_current_user_id();
        $hash[] = wc_tax_enabled() . $forDisplay . $this->userCurrencyHandler->getUserCurrencyId() . $userId;

        return $hash;
    }

    /**
     * Now it's needed only to apply premmerce_multicurrency_get_price_for_user filter which used in one or more projects.
     * It will be great to delete this in future.
     *
     *
     * @param             $price
     * @param \WC_Product $product
     *
     * @return string
     */
    public function applyGetPriceForUserFilter($price, WC_Product $product)
    {
        if (strpos(current_filter(), '_sale_price') !== false) {
            $priceType = 'sale';
        } elseif (strpos(current_filter(), '_regular_price') !== false) {
            $priceType = 'regular';
        } else {
            $priceType = $product->is_on_sale('edit') ? 'sale' : 'regular';
        }

        return apply_filters('premmerce_multicurrency_get_price_for_user', $price, $product, $priceType);
    }

    /**
     * Wrap product price span in another span with product ID. Needed for AJAX prices redrawing where this option enabled.
     *
     * @param $html
     * @param \WC_Product $product
     *
     * @return string
     */
    public function addPriceSpan($html, WC_Product $product)
    {
        if (!$this->ajaxPricesEnabled || empty($product->get_price())) {
            return $html;
        }

        return '<span class="premmerce-multicurrency-data" data-product-id="' . $product->get_id() . '">' . $html . '</span>';
    }

    /**
     * Calculate price of given product in selected currency
     *
     * @param $price
     * @param $priceCurrencyId
     * @param $convertToCurrencyId
     *
     * @return string
     */
    public function calculatePriceInUsersCurrency($price, $priceCurrencyId = '', $convertToCurrencyId = '')
    {
        if (!$priceCurrencyId) {
            $priceCurrencyId = $this->shopCurrencyID;
        }

        if (!$convertToCurrencyId) {
            $convertToCurrencyId = $this->userCurrencyHandler->getUserCurrencyId();
        }

        $fromCurrencyRate   = (float) wc_format_decimal($this->availableCurrencies[$priceCurrencyId]['rate']);
        $toCurrencyRate     = (float) wc_format_decimal($this->availableCurrencies[$convertToCurrencyId]['rate']);
        $price              = (float) wc_format_decimal($price);
        if (!$toCurrencyRate) {
            return '';
        }


        $fromCurrencyRateToCurrencyRateRelation = $fromCurrencyRate / $toCurrencyRate;
        $price = $price * $fromCurrencyRateToCurrencyRateRelation;


        //Convert to string because we get string at input
        return (string) apply_filters(
            'premmerce_multicurrency_price_in_users_currency',
            $price,
            $priceCurrencyId,
            $convertToCurrencyId
        );
    }

    /**
     * Send to front prices in html, users currency and filter min/max values on ajax request
     */
    public function sendPricesForAjax()
    {
        $pricesHtmlArray = array();

        if (isset($_GET['productsIds'])) {
            foreach ($_GET['productsIds'] as $id) {
                $pricesHtmlArray[$id] = wc_get_product(intval($id))->get_price_html();
            }
        }


        $filterData = array(
            'rangeMin' => (int)$_GET['filterValues']['rangeMin'],
            'rangeMax' => (int)$_GET['filterValues']['rangeMax'],
            'min' => (int)$_GET['filterValues']['min'],
            'max' => (int)$_GET['filterValues']['max']
        );

        $fromPricesCurrencyId = (int)$_GET['originalPageCurrency'];

        foreach ($filterData as $key => $value) {
            $value = $this->calculatePriceInUsersCurrency($value, $fromPricesCurrencyId);
            $value = in_array($key, array('min', 'minRange')) ? floor($value) : ceil($value);
            $filterData[$key] = $value;
        }

        $data = array(
            'prices'    => $pricesHtmlArray,
            'currency'  => $this->userCurrencyHandler->getUserCurrencyData(),
            'filter'    => $filterData
        );

        wp_send_json(apply_filters('premmerce_multicurrency_json_data', $data));
    }

    /**
     * Output span with currency id in data attribute. Used to detect original page currency when updating price filters values.
     */
    public function outputCurrencyIdSpan()
    {
        echo '<span class="premmerce-multicurrency original-page-currency" style="display:none;" data-page_original_currency_id="' . $this->userCurrencyHandler->getUserCurrencyId() . '"></span>';
    }

    /**
     * Check if product with given id is on sale.
     * Replacement for \WC_Product::is_on_sale() method. This method does the same, but without \WC_Product.
     *
     * @see WC_Product::is_on_sale()
     *
     * @param $productId
     * @return bool
     *
     * @todo: Avoid using post_meta to support coming WC separate product and order tables.
     *
     * @todo: Look at InternalProductCurrencyHandler::isProductOnSaleMethod. Maybe move this to Model end use from both classes.
     */
    public function isProductOnSale($productId)
    {
        $regularPrice = isset(get_post_meta($productId, '_regular_price')[0]) ? get_post_meta($productId, '_regular_price')[0] : '';
        $salePrice = isset(get_post_meta($productId, '_sale_price')[0]) ? get_post_meta($productId, '_sale_price')[0] : '';
        $onSale = false;

        if ('' !== $salePrice && $regularPrice > $salePrice) {
            $onSaleFrom = get_post_meta($productId, '_sale_price_dates_from', true);
            $onSaleTo = get_post_meta($productId, '_sale_price_dates_to', true);
            $now = current_time('timestamp', true);
            $onSale = true;


            if ($onSaleFrom && $onSaleFrom > $now || $onSaleTo && $onSaleTo + DAY_IN_SECONDS < $now) {
                $onSale = false;
            }
        }

        return $onSale;
    }

    /**
     * Change price filter request values to get products in requested price range
     *
     * @param array $args
     * @param WP_Query $query
     * @return array|void
     */
    public function fixProductQueryWithPriceFilter(array $args, WP_Query $query)
    {
        if (has_filter('posts_clauses', array(wc()->query, 'price_filter_post_clauses'))) {
            remove_filter('posts_clauses', array(wc()->query, 'price_filter_post_clauses'));
            $args = $this->filterWcQueryArgsForPriceFilter($args, $query);

            /**
             * Remove this filter like Woocommerce does. @see \WC_Query::remove_product_query_filters.
             */
            add_filter('the_posts', function ($posts) {
                remove_filter('posts_clauses', __FUNCTION__, 9);
                return $posts;
            });
        }

        return $args;
    }

    /**
     * Generate query args using Woocommerce native methods and recalculated min and max prices
     *
     * @param array $args
     * @param WP_Query $query
     * @return array
     */
    public function filterWcQueryArgsForPriceFilter(array $args, WP_Query $query)
    {
        $userCurrencyId = $this->userCurrencyHandler->getUserCurrencyId();
        $mainCurrencyId =$this->model->getMainCurrencyId();
        if (isset($_GET['min_price'])) {
            $originalMinPrice = $_GET['min_price'];
            $recalculatedMinPrice = $this->calculatePriceInUsersCurrency($originalMinPrice, $userCurrencyId, $mainCurrencyId);
            $_GET['min_price'] = $recalculatedMinPrice;
        }

        if (isset($_GET['max_price'])) {
            $originalMaxPrice = $_GET['max_price'];
            $recalculatedMaxPrice = $this->calculatePriceInUsersCurrency($originalMaxPrice, $userCurrencyId, $mainCurrencyId);
            $_GET['max_price'] = $recalculatedMaxPrice;
        }

        $args = wc()->query->price_filter_post_clauses($args, $query);

        if (isset($_GET['min_price'])) {
            $_GET['min_price'] = $originalMinPrice;
        }

        if (isset($_GET['max_price'])) {
            $_GET['max_price'] = $originalMaxPrice;
        }

        return $args;
    }

    /**
     * Return product meta fields needed to be replaced.
     * '_price' field sets dynamically for each product depending on is product on sale.
     *
     * @param   int       $productId
     *
     * @return array
     */
    private function getFieldsToReplace($productId)
    {
        $fieldsToReplace = $this->fieldsToReplace;
        $fieldsToReplace['_price'] = $this->isProductOnSale($productId) ? '_product_currency_sale_price' : '_product_currency_regular_price';

        return $fieldsToReplace;
    }

    /**
     * Return product currency id
     *
     * @param $productId
     *
     * @return string
     *
     * @todo: Avoid using post_meta to support coming WC separate product and order tables
     */
    private function getProductCurrencyByProductId($productId)
    {
        return get_post_meta(wp_get_post_parent_id($productId) ?: $productId, '_product_currency', true) ?: $this->model->getMainCurrencyId();
    }
}
