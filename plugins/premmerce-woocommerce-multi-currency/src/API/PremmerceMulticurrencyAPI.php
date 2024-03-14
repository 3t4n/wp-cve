<?php

namespace Premmerce\WoocommerceMulticurrency\API;

use Premmerce\WoocommerceMulticurrency\Model\Model;
use Premmerce\WoocommerceMulticurrency\Frontend\UserPricesHandler;
use Premmerce\WoocommerceMulticurrency\Frontend\UserCurrencyHandler;
use \WC_Product;

class PremmerceMulticurrencyAPI
{
    /**
     * @var Model
     */
    private $model;

    /**
     * @var UserPricesHandler
     */
    private $userPricesHandler;

    /**
     * @var UserCurrencyHandler
     */
    private $userCurrencyHandler;

    /**
     * PremmerceMulticurrencyAPI constructor.
     *
     * @param Model $model
     * @param UserPricesHandler $userPricesHandler
     * @param UserCurrencyHandler $userCurrencyHandler
     */
    public function __construct(Model $model, UserPricesHandler $userPricesHandler, UserCurrencyHandler $userCurrencyHandler)
    {
        $this->model                = $model;
        $this->userPricesHandler    = $userPricesHandler;
        $this->userCurrencyHandler  = $userCurrencyHandler;
    }

    /**
     * Get array with currencies.
     * If $forUsers parameter is true, only available for users on frontend currencies will be returned.
     *
     * @param bool $forUsers
     *
     * @return array
     */
    public function getCurrencies($forUsers = false)
    {
        return apply_filters('premmerce_multicurrency_api_get_currencies', $this->model->getCurrencies($forUsers), $forUsers);
    }

    /**
     * Return main currency id
     *
     * @return string
     */
    public function getMainCurrencyId()
    {
        return apply_filters('premmerce_multicurrency_api_get_main_currency_id', $this->model->getMainCurrencyId());
    }

    /**
     * Return currency id of current user.
     *
     * @return string
     */
    public function getUsersCurrencyId()
    {
        return apply_filters('premmerce_multicurrency_api_get_main_currency_id', $this->userCurrencyHandler->getUserCurrencyId());
    }

    /**
     * Convert given amount from one currency to another
     *
     * @param float     $amount
     * @param int       $currencyIdFrom
     * @param int       $currencyIdTo
     *
     * @return string
     */
    public function convert($amount, $currencyIdFrom, $currencyIdTo)
    {
        $converted = $this->userPricesHandler->calculatePriceInUsersCurrency($amount, $currencyIdFrom, $currencyIdTo);
        return apply_filters('premmerce_multicurrency_api_convert', $converted, $amount, $currencyIdFrom, $currencyIdTo);
    }

    /**
     * Convert amount in main shop currency to current user currency
     *
     * @param float $amount Sum to convert in main shop currency
     * @param bool $formatted Should output be formatted with wc_price()
     *
     * @return string
     *
     */
    public function convertToUserCurrency($amount, $formatted = true)
    {
        $price = $this->userPricesHandler->calculatePriceInUsersCurrency($amount);

        if ($formatted) {
            $price = wc_price($price);
        }

        return apply_filters('premmerce_multicurrency_api_convert_to_user_currency', $price, $amount, $formatted);
    }

    /**
     * @param WC_Product $product
     * @param $priceType
     *
     * @return string
     */
    public function getProductPriceInProductCurrency(WC_Product $product, $priceType)
    {
        $productPriceInProductCurrency = $this->model->getProductPriceInProductCurrency($product, $priceType);

        return apply_filters('premmerce_multicurrency_api_get_product_price_in_product_currency', $productPriceInProductCurrency, $product, $priceType);
    }

    /**
     * Returns product price in base shop currency, if set.
     * If it's not set, then product currency is base shop currency and price will be taken from native woocommerce fields.
     *
     * @param WC_Product $product
     * @param string $priceType
     *
     * @return string
     */
    public function getProductPriceInBaseShopCurrency(WC_Product $product, $priceType)
    {
        $productPriceInProductCurrency = $this->getProductPriceInProductCurrency($product, $priceType);
        $productCurrency    = $this->model->getProductCurrency($product);
        $mainCurrency       = $this->model->getMainCurrencyId();

        $price = $this->userPricesHandler->calculatePriceInUsersCurrency($productPriceInProductCurrency, $productCurrency, $mainCurrency);

        return apply_filters('premmerce_multicurrency_api_get_product_price_in_base_shop_currency', $price, $product, $priceType);
    }

    /**
     * Change product internal currency. Prices will be recalculated.
     * Do not use with variations, use it with parent variable product instead.
     *
     * !!! Important note. Product price will NOT stay the same after using this method.
     * If product price was 300 EUR, it will be 300 USD after currency changing.
     * E.g., this method works the same way as changing product currency on product edit page in admin.
     * Please, use PremmerceMulticurrencyAPI::setProductPrices() method to set actual product prices after changing currency.
     *
     * @todo: Throw an exception or error when using this with variations.
     *
     * @param WC_Product $product
     * @param $currencyId
     */
    public function setProductCurrency(WC_Product $product, $currencyId)
    {
        if ($this->model->currencyExists($currencyId)) {
            $this->model->changeProductCurrency($product, $currencyId);
        }
    }

    /**
     * Set product prices in current product currency.
     * Do not use with variable products, use this with variations instead.
     *
     * @todo: Throw an exception or error when using this with variable products
     *
     * @param WC_Product $product
     * @param $regularPrice
     * @param $salePrice
     */
    public function setProductPrices(WC_Product $product, $regularPrice, $salePrice)
    {
        $this->model->setProductPricesInProductCurrency($product, $regularPrice, $salePrice);
        $this->model->recalculateProductPrices($product);

        if ($parentId = $product->get_parent_id()) {
            $parent = wc_get_product($parentId);
            $this->model->recalculateProductPrices($parent);
        }
    }

    /**
     * Recalculate product prices
     *
     * @param WC_Product $product
     */
    public function recalculateProductPrices(WC_Product $product)
    {
        $this->model->recalculateProductPrices($product);
    }
}
