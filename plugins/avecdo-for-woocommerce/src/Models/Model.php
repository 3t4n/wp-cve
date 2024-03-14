<?php

namespace Avecdo\Woocommerce\Models;

use Avecdo\SDK\POPO\Category;
use Avecdo\SDK\POPO\Product;
use Avecdo\SDK\POPO\Product\StockStatus;
use Avecdo\SDK\POPO\Product\Combination;
use Avecdo\Woocommerce\Classes\Option;
use WC_Shipping_Zone;
use WC_Shipping_Zones;

if (!defined('ABSPATH')) {
    exit;
}

class Model extends WooQueries
{
    private static $weight_unit    = null;
    private static $dimension_unit = null;
    /**
     * Holds the product attributes when fetched.
     * @var mixed
     */
    private static $_product_attributes = null;

    /**
     * @return Model
     */
    public static function make()
    {
        return new static();
    }

    /**
     * Class constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    // @todo check if wee need this for products thats set to allow back orders
    private function assignAvecdoProductStock($productId, $avecdoProduct)
    {
        //
        // Works fine but we do not set default stock value
        // so no need for extra sql calls
        //
        //$productId = (int)$productId;
        //global $wpdb;
        //$meta_query   = "SELECT
        //                     t1.post_id,
        //                     t1.meta_value as stock,
        //                     t2.meta_value as backorders,
        //                     t3.meta_value as manage_stock
        //                FROM ". $wpdb->prefix ."postmeta AS t1
        //                INNER JOIN  ". $wpdb->prefix ."postmeta AS t2
        //                ON          t2.post_id=t1.post_id
        //                AND         t2.meta_key='_backorders'
        //
        //                INNER JOIN  ". $wpdb->prefix ."postmeta AS t3
        //                ON          t3.post_id=t1.post_id
        //                AND         t3.meta_key='_manage_stock'
        //
        //                WHERE t1.post_id = {$productId}
        //                AND   t1.meta_key='_stock'";
        //if($query_result = $wpdb->get_results($meta_query, OBJECT)) {
        //    $query_result = $query_result[0];
        //
        //    $avecdoProduct->setStockQuantity($query_result->stock);
        //    if((int)$query_result->stock<=0 && $query_result->manage_stock == "no") {
        //        /* if the shop do not manage stock then set default of 20. */
        //        $avecdoProduct->setStockQuantity(20);
        //    } else if((int)$query_result->stock<=0 && $query_result->backorders != "no") {
        //        /* if the shop manage stock but allow backorders then set default of 20. */
        //        $avecdoProduct->setStockQuantity(20);
        //    }
        //}
    }

    /**
     * Assign categories and tags to avecdo product object
     * @param int $productId
     * @param Product $avecdoProduct
     * @param int[] $categoryIds added for better performance when fetching related products
     * @param int[] $tagIds added for better performance when fetching related products
     * @return void
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.1.2
     */
    private function assignAvecdoProductCategoriesAndTags($productId, Product $avecdoProduct, array &$categoryIds, array &$tagIds)
    {
        $cats = array();

        foreach ($this->getCategoriesTagsAndShippingClasses($productId) as $postmeta) {
            if ($postmeta->taxonomy == "product_tag") {
                $tagIds[] = $postmeta->term_id;
                $avecdoProduct->addToTags($postmeta->name);
            } else if ($postmeta->taxonomy == "product_cat") {
                $categoryIds[]            = $postmeta->term_id;
                $cats[$postmeta->term_id] = array(
                    'categoryId' => $postmeta->term_id,
                    'name'       => $postmeta->name,
                    'parent'     => $postmeta->parent
                );
            }
        }

        if (!empty($cats)) {
            foreach ($cats as $cat) {
                $fullName                     = array();
                $fullName[$cat['categoryId']] = $cat['name'];
                $parent                       = (int) $cat['parent'];
                while ($parent != 0) {
                    if (isset($cats[$parent])) {
                        $fullName[$cats[$parent]['categoryId']] = $cats[$parent]['name'];
                        $parent                                 = (int) $cats[$parent]['parent'];
                    } else {
                        $parent = 0;
                    }
                }
                ksort($fullName);
                $avecdoProduct->addToCategories($cat['categoryId'], $cat['parent'], implode(" > ", $fullName));
            }
        }
    }

    /**
     * Returns whether multi currency is enabled.
     * @return bool
     * @since 1.3.12
     * @author Nikolai Straarup <nikolai@modified.dk>
     */
    private function isMultiCurrencyEnabled()
    {
        global $woocommerce_wpml;
        return isset($woocommerce_wpml) && wcml_is_multi_currency_on();
    }

    /**
     * Returns an array of non-default-currency prices.
     * Based on WPML function "append_product_secondary_prices" from
     * "wp-content\plugins\woocommerce-multilingual\classes\rest-api-support\class-wcml-rest-api-support.php"
     * @return array
     * @since 1.3.12
     * @author Woocommerce Multilingual authors, Nikolai Straarup <nikolai@modified.dk>
     */
    private function getMultiCurrencyPricesForProduct($productId) {
        global $woocommerce_wpml;

        $result = array();

        $custom_prices_on = get_post_meta($productId, '_wcml_custom_prices_status', true);

        foreach ($woocommerce_wpml->settings['currencies_order'] as $currency) {

            if ($currency != get_option('woocommerce_currency')) {

                if ($custom_prices_on) {

                    $custom_prices = (array) $woocommerce_wpml->multi_currency->custom_prices->get_product_custom_prices($productId, $currency);
                    foreach ( $custom_prices as $key => $price){
                        $result[$currency][preg_replace('#^_#', '', $key)] = $price;
                    }
                } else {
                    $result[$currency]['regular_price'] =
                        $woocommerce_wpml->multi_currency->prices->raw_price_filter($product_data->data['regular_price'], $currency);
                    if (!empty($product_data->data['sale_price'])) {
                        $result[$currency]['sale_price'] =
                            $woocommerce_wpml->multi_currency->prices->raw_price_filter($product_data->data['sale_price'], $currency);
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Returns an array of multi-currency prices for a translation parent product.
     * This is used when Product Translation Interface is "Native" instead of "WPML Translation Editor".
     * @return array
     * @since 1.3.12
     * @author Nikolai Straarup <nikolai@modified.dk>
     */
    private function getTranslationParentCurrencies($productId)
    {
        $productTranslationInfo = $this->getTranslationInfoOfProduct($productId);

        if (isset($productTranslationInfo[0]->source_language_code)) {
            $parentTranslationInfo = $this->getTranslationInfoOfProductWithTridAndLang(
                $productTranslationInfo[0]->trid,
                $productTranslationInfo[0]->source_language_code
            );

            if (isset($parentTranslationInfo[0]->element_id)) {
                return $this->getMultiCurrencyPricesForProduct(
                    $parentTranslationInfo[0]->element_id
                );
            }
        }
        return array();
    }

    /**
     * Returns true if meta data array has object that contains sale price.
     * Else returns false.
     */
    private function metadataHasSalePrice($metadatas)
    {
        foreach ($metadatas as $metadata) {
            if (array_key_exists('_sale_price', $metadata)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Add an object with meta key "_sale_price" to the metadatas array.
     */
    private function metadataAddEmptySalePrice(&$metadatas)
    {
        $metadatas = array_merge($metadatas, [(object)["meta_key"=>"_sale_price"]]);
    }

    /**
     * Assign all metadata to avecdo product object
     * @param int $productId
     * @param Product|Combination $avecdoProduct
     * @param int[] $imagesIds output all image ids for this product.
     * @param bool $hasMultiCurrency
     * @param string $avecdoCurrency
     * @param string $shopDefaultCurrency
     * @return void
     * @author Christian M. Jensen <christian@modified.dk>, Nikolai Straarup <nikolai@modified.dk>
     * @since 1.1.2
     */
    private function assignAvecdoProductMetaData($productId, $avecdoProduct, array &$imagesIds, $hasMultiCurrency, $avecdoCurrency, $shopDefaultCurrency)
    {
        global $woocommerce_wpml;

        if ('yes' !== get_option( 'woocommerce_prices_include_tax')) {
            $product = wc_get_product($productId);
        }
        $galleryImages = array();
        $thumbnailImages = array();
        $method = "noneExistingMethod";
        $metadatas = $this->getMetaData($productId);

        /* If multicurrency is enabled, and chosen currency is not the default
           one (i.e. we are using a multilingual currency),
           we must add an empty metadata sale field, in order to set the sale
           price correctly.
           If this always runs, it would set sale price = regular price.
        */
        if ($hasMultiCurrency &&
            $avecdoCurrency != $shopDefaultCurrency &&
            !$this->metadataHasSalePrice($metadatas)) {
            $this->metadataAddEmptySalePrice($metadatas);
        }

        foreach ($metadatas as $metaRow) {
            switch ($metaRow->meta_key) {
                case "_tax_class":
                case "_tax_status":
                case "_purchase_note":
                case "_price":
                case "_sale_price_dates_from":
                case "_sale_price_dates_to":
                case "_wp_attached_file":
                case "_wp_attachment_metadata":
                case "_sold_individually":
                case "total_sales":
                case "_crosssell_ids":
                case "_upsell_ids":
                case "_wp_old_slug":
                case "_wp_page_template":
                /* @todo see 'assignAvecdoProductStock' */
                case "_backorders":
                case "_manage_stock":
                    break;
                case "_regular_price":
                    $method  = "setPrice";

                    // If multi currency.
                    if ($hasMultiCurrency && $avecdoCurrency != $shopDefaultCurrency) {

                        // Extra currencies will return prices for non-default currencies.
                        $extraCurrencies = $this->getMultiCurrencyPricesForProduct($productId);

                        // If there is no price in chosen currency, maybe we'll find it in translation parent product.
                        // This is the case if Product Translation Interface is "Native" instead of "WPML Translation Editor".
                        if (empty($extraCurrencies[$avecdoCurrency]['regular_price'])) {
                            $extraCurrencies = $this->getTranslationParentCurrencies($productId);
                        }

                        if (!empty($extraCurrencies[$avecdoCurrency]['regular_price'])) {
                            $metaValue = (float) $extraCurrencies[$avecdoCurrency]['regular_price'];
                        } else {
                            // If there is no custom price, convert from default currency.
                            $metaValue = apply_filters( 'wcml_raw_price_amount', $metaRow->meta_value, $avecdoCurrency);
                        }
                    } else {
                        $metaValue = (float) $metaRow->meta_value;
                    }

                    // If prices don't include tax, get price with tax.
                    if ('no' === get_option( 'woocommerce_prices_include_tax') && $product) {
                        $metaValue = (float) $this->getPriceIncludingTax($product, $metaValue, 1);
                    }
                    break;
                case "_sku":
                    $method    = "setSku";
                    $metaValue = $metaRow->meta_value;
                    break;
                case "_product_image_gallery":
                    if (!empty($metaRow->meta_value)) {
                        $_metaValue = array_map('intval', explode(',', $metaRow->meta_value));
                        foreach($_metaValue as $_mv) {
                            if(intval($_mv)>0) {
                                $galleryImages[] = (int) $_mv;
                            }
                        }
                    }
                    break;
                case "_thumbnail_id":
                    if(intval($metaRow->meta_value)>0) {
                        $thumbnailImages[] = (int) $metaRow->meta_value;
                    }
                    break;
                case "_sale_price":
                    $method      = "setPriceSale";
                    // If multi currency.
                    if ($hasMultiCurrency && $avecdoCurrency != $shopDefaultCurrency) {
                        // Extra currencies will return prices for non-default currencies.
                        $extraCurrencies = $this->getMultiCurrencyPricesForProduct($productId);

                        // If there is no price in chosen currency, maybe we'll find it in translation parent product.
                        // This is the case if Product Translation Interface is "Native" instead of "WPML Translation Editor".
                        if (empty($extraCurrencies[$avecdoCurrency]['sale_price'])) {
                            $extraCurrencies = $this->getTranslationParentCurrencies($productId);
                        }

                        // Since current product might be a translation of another product,
                        // we need to ask wpml to return the id of the original product,
                        // since this is the product that has the _wcml_custom_prices_status
                        // meta data field.
                        $originalProductId = $woocommerce_wpml->products->get_original_product_id($productId);

                        // Auto convert currency if custom prices are disabled.
                        // This is necessary because $extraCurrencies doesn't contain
                        // a sale price for a multi currency when currencies are
                        // calculated automatically.
                        $autoCurrencyConvertEnabled = get_post_meta($originalProductId, '_wcml_custom_prices_status', true) != 1;

                        if ($autoCurrencyConvertEnabled) {
                            // If there is no custom price, convert from default currency.
                            $salePrice = $this->getSalePrice($productId);
                            $metaValue = apply_filters( 'wcml_raw_price_amount', $salePrice, $avecdoCurrency);
                        } else {
                            $metaValue = $extraCurrencies[$avecdoCurrency]['sale_price'];
                        }

                    } else {
                        $metaValue = (float) $this->getSalePrice($productId);
                    }

                    if ('no' === get_option( 'woocommerce_prices_include_tax') && $product) {
                        $metaValue = (float) $this->getPriceIncludingTax($product, $metaValue, 1);
                    }
                    break;
                case "_weight":
                    $method      = "setWeight";
                    $metaValue   = (float) $metaRow->meta_value;
                    break;
                case "_height":
                    $method      = "setDimensionHeight";
                    $metaValue   = (float) $metaRow->meta_value;
                    break;
                case "_length":
                    $method      = "setDimensionDepth";
                    $metaValue   = (float) $metaRow->meta_value;
                    break;
                case "_width":
                    $method      = "setDimensionWidth";
                    $metaValue   = (float) $metaRow->meta_value;
                    break;
                case "_stock":
                    $metaValue   = (float) $metaRow->meta_value;
                    $method      = "setStockQuantity";
                    break;
                case "_stock_status":
                    $method    = "setStockStatus";
                    $metaValue = ($metaRow->meta_value == 'instock' ? StockStatus::IN_STOCK : StockStatus::OUT_OF_STOCK);
                    break;
                case "_default_attributes":
                    /* default attribute for product with combinations */
                    //$_metaValue = unserialize($metaRow->meta_value);
                    // eg: pa_color=>black
                    break;
                case "_product_attributes":
                    /* indicates that the product has attributes */
                    static::$_product_attributes = unserialize($metaRow->meta_value);
                    $method = "no_no_we_moved";
                    break;
                case "_avecdo_brand":
                    $method    = "setBrand";
                    $metaValue = $metaRow->meta_value;
                    break;
                case "_avecdo_mpn":
                    $method    = "setMpn";
                    $metaValue = $metaRow->meta_value;
                    break;
                case "_avecdo_upc":
                    $method    = "setUpc";
                    $metaValue = $metaRow->meta_value;
                    break;
                case "_avecdo_ean":
                    $method    = "setEan";
                    $metaValue = $metaRow->meta_value;
                    break;
                case "_avecdo_isbn":
                    $method    = "setIsbn";
                    $metaValue = $metaRow->meta_value;
                    break;
                case "_avecdo_jan":
                    $method    = "setJan";
                    $metaValue = $metaRow->meta_value;
                    break;
                case "hwp_product_gtin":
                case "hwp_var_gtin":
                    $avecdoProduct->setEan($metaRow->meta_value);
                    break;
                default:
                    /*
                      _max_variation_price
                      _max_variation_regular_price
                      _max_variation_sale_price
                      _min_variation_price
                      _min_variation_regular_price
                      _min_variation_sale_price
                     */
                    // starts with 'attribute_'
                    $this->assignAvecdoProductMetaData_Attribute($metaRow, $avecdoProduct);
                    break;
            }

            if (method_exists($avecdoProduct, $method)) {
                if(!empty($metaValue)) {
                    $avecdoProduct->{$method}($metaValue);
                }
                // @todo see 'assignAvecdoProductStock'
                if (strpos($method, 'Quantity') !== false) {
                    $this->assignAvecdoProductStock($productId, $avecdoProduct);
                }
            }
        }
        // Set the Discount Rule sale price if any.
        $this->setDiscountRuleSalePrice($productId, $avecdoProduct);

        $imagesIds = array_merge($imagesIds, $thumbnailImages, $galleryImages);
    }

    private function getPriceIncludingTax($product, $price, $qty = 1)
    {
        if (function_exists('wc_get_price_including_tax')) {
            return (float) wc_get_price_including_tax($product, array(
                'price' => (float) $price,
                'qty'   => $qty
            ));
        }
        // Woo < 3.0.0
        if (method_exists($product, 'get_price_including_tax')) {
            return (float) $product->get_price_including_tax($qty, $price);
        }
        // fallback
        return (float) $price;
    }

    public function getProducts($page, $limit, $lastRun, $multiLanguageOptions = null, $activeKey = null)
    {
        $offset   = ((((int) $page == 0 ? 1 : (int) $page) - 1) * (int) $limit);
        $products = array();

        $shopCurrencyLanguageSettings = $this->getMultiLangOptions($multiLanguageOptions,$activeKey);
        $_language  = $shopCurrencyLanguageSettings['language'];
        $_currency  = $shopCurrencyLanguageSettings['currency'];

        /*
            If we have enabled woocommerce multi currency, "get_woocommerce_currency()"
            will return the currency for the chosen default language under WPML -> Site Languages.

            "get_option("woocommerce_currency")" will always return the default
            woocommerce currency. The default prices in the product metadata will be in this currency.
        */
        $shopDefaultCurrency = get_option('woocommerce_currency');

        $hasMultiCurrency = $this->isMultiCurrencyEnabled();
        $avecdoCurrency = $_currency;

        // If multicurrency is disabled in shop, but avecdo is set to use
        // a non-default currency, we change the currency to shop default.
        if (!$hasMultiCurrency && $avecdoCurrency != $shopDefaultCurrency) {
            $avecdoCurrency = $shopDefaultCurrency;
        }

        $allProducts = array();

        if ($hasMultiCurrency) {
            $language = $_language;
            global $sitepress;
            if ($sitepress) {
                $sitepress->switch_lang($language);     // I found it was necessary for retrieving the correct product url for the language.
            }
            $allProducts = $this->getSimpleProductListInLanguage((int) $offset, (int) $limit, $lastRun, $language);
        } else {
            $allProducts = $this->getSimpleProductList((int) $offset, (int) $limit, $lastRun);
        }

        foreach ($allProducts as $row) {

            $productId = intval($row->productId);
            $productVairations = array();

            if ($hasMultiCurrency) {
                $productVairations = $this->getProductVairationsByProductIdInLanguage((int) $productId, $language);
            } else {
                $productVairations = $this->getProductVairationsByProductId((int) $productId);
            }

            if (count($productVairations)>0) {
                $this->getProductChildren($productVairations, $row, $products, $avecdoCurrency, $shopDefaultCurrency);
            } else {

                $avecdoProduct        = new Product();
                $avecdoProduct
                    ->setInternalId($productId)
                    ->setProductId($productId)
                    ->setParentId(null)
                    ->setName($this->cleanupDescrictionTitle($row->name))
                    ->setDescription(($this->cleanupDescrictionTitle($this->getDescription($row))))
                    ->setWeightUnit($this->getWeightUnit())
                    ->setDimensionUnit($this->getDimensionUnit())
                    ->setCurrency($avecdoCurrency)
                    ->setStockStatus($this->get_stock($productId))
                    ->setUrl(get_permalink($productId))
                    ->setShippingCost($this->getShippingPrice($productId));
                static::$_product_attributes = null;
                $this->assignProductMetaData($productId, $productId, $avecdoProduct, $avecdoCurrency, $shopDefaultCurrency);

                // Fetch all post meta data related to the product
                foreach (get_post_meta($productId) as $key => $value) {
                    $avecdoProduct->addToAttributes(-2, $key, $value[0]);
                }

                $products[] = $avecdoProduct->getAll();
            }
        }
        return $products;
    }

    /**
     * Get product stock
     * @since 1.4.3
     */
    private function get_stock( $id ){
        $status=$this->get_attribute_value($id,"_stock_status");
        if ($status) {
            if ($status == 'instock') {
                return "in stock";
            } elseif ($status == 'outofstock') {
                return "out of stock";
            }
        }
        return "out of stock";
    }

    /**
     * Get attribute value
     * @since 1.4.3
     */
    public function get_attribute_value( $id, $name ){
        if (strpos($name, 'attribute_pa') !== false) {
            $taxonomy = str_replace("attribute_","",$name);
            $meta = get_post_meta($id,$name, true);
            $term = get_term_by('slug', $meta, $taxonomy);
            return $term->name;
        } else {
            $blaat = get_post_meta($id, $name, true);
            return get_post_meta($id, $name, true);
        }
    }

    /**
     * Cleanup description and titles
     * @param string $string
     * @return string
     * @since 1.4.3
     */

    private function cleanupDescrictionTitle($string)
    {
        // Strip HTML from (short) description
        $string = $this->rip_tags($string);
        // Strip out Visual Composer short codes
        $string = preg_replace( '/\[(.*?)\]/', ' ', $string );
        // Strip out the non-line-brake character
        $string = str_replace("&#xa0;", "", $string);
        return strip_tags($string);

    }

    /**
     * Clean tags from various fields.
     * @param string $string
     * @return string
     * @since 1.4.3
     */
    public function rip_tags( $string ) {
        // ----- remove HTML TAGs -----
        $string = preg_replace ('/<[^>]*>/', ' ', $string);

        // ----- remove control characters -----
        $string = str_replace("\r", '', $string);    // --- replace with empty space
        $string = str_replace("\n", ' ', $string);   // --- replace with space
        $string = str_replace("\t", ' ', $string);   // --- replace with space

        // ----- remove multiple spaces -----
        $string = trim(preg_replace('/ {2,}/', ' ', $string));

        return $string;
    }

    /**
     * Get the brand for a product
     * @param integer $postId
     * @param integer $parrentPostID
     * @return string
     * @since 1.3.11
     */
    private function getBrand($postId, $parrentPostID)
    {
        $brand = get_post_meta($postId, '_avecdo_brand', true);
        if (empty($brand)) {
            $brand = get_post_meta($parrentPostID, '_avecdo_brand', true);
        }
        return $brand;
    }

    /**
     * Get the ean for a product
     * @param integer $postId
     * @param integer $parrentPostID
     * @return string
     * @since 1.3.11
     */
    private function getEAN($postId, $parrentPostID)
    {
        $ean = get_post_meta($postId, '_avecdo_ean', true);
        if (empty($ean)) {
            $ean = get_post_meta($parrentPostID, '_avecdo_ean', true);
        }
        return $ean;
    }


    /**
     * Get product desction based on user settings
     * @param object $product
     * @return string
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.2.12
     */
    private function getDescription($product) {
        switch (Option::get('use_description')) {
            case 'short':
                return $product->description_short;
            case 'long':
                return $product->description_long;
            default:
                return $product->description;
        }
        return "";
    }


    /**
     * Get products that has children.
     * @param array $productVairations
     * @param object $product the parent product object.
     * @param string $avecdoCurrency
     * @param string $shopDefaultCurrency
     * @return void
     * @author Christian M. Jensen <christian@modified.dk>, Nikolai Straarup <nikolai@modified.dk>, tomc@modified.dk
     * @since 1.2.5
     */
    private function getProductChildren($productVairations, $product, & $products, $avecdoCurrency, $shopDefaultCurrency)
    {
        foreach ($productVairations as $child) {

            $avecdoProduct        = new Product();
            $avecdoProduct
                ->setInternalId($child->productId)
                ->setProductId($child->parentId.'-'.$child->productId)
                ->setParentId($child->parentId)
                ->setName($product->name)
                ->setVariationName($child->name)
                ->setDescription($this->cleanupDescrictionTitle($this->getDescription($product)))
                ->setWeightUnit($this->getWeightUnit())
                ->setDimensionUnit($this->getDimensionUnit())
                ->setCurrency($avecdoCurrency)
                ->setStockStatus($this->get_stock($child->productId))
                ->setUrl(get_permalink($child->parentId))
                ->setShippingCost($this->getShippingPrice($child->productId));
            static::$_product_attributes = null;
            $this->assignProductMetaData($child->parentId, $child->productId, $avecdoProduct, $avecdoCurrency, $shopDefaultCurrency);

            // Fetch all post meta data related to the child product
            foreach (get_post_meta($child->productId) as $key => $value) {
                $avecdoProduct->addToAttributes(-2, $key, $value[0]);
            }

            $products[] = $avecdoProduct->getAll();
        }
    }

    /**
     * Set metadata for a product.
     * @param integer $parentProductId
     * @param integer $productId
     * @param Object $avecdoProduct
     * @param string $avecdoCurrency
     * @param string $shopDefaultCurrency
     * @return void
     * @author Christian M. Jensen <christian@modified.dk>, Nikolai Straarup <nikolai@modified.dk>
     * @since 1.2.5
     */
    private function assignProductMetaData($parentProductId, $productId, & $avecdoProduct, $avecdoCurrency, $shopDefaultCurrency)
    {
        $imagesIds = array();
        $hasMultiCurrency = $this->isMultiCurrencyEnabled();

        // set product metadata and output image ids.
        $this->assignAvecdoProductMetaData($productId, $avecdoProduct, $imagesIds, $hasMultiCurrency, $avecdoCurrency, $shopDefaultCurrency);

        // loop all product attributes.
        $brand = null;
        $ean   = null;
        $upc   = null;
        $isbn  = null;
        $jan   = null;
        if (is_array(static::$_product_attributes)) {
            foreach (static::$_product_attributes as $attribute) {
                $isEan   = preg_match("/^(ean|pa_ean).*/i", $attribute['name']);
                $isBrand = preg_match("/^(brand|pa_brand).*/i", $attribute['name']);
                $isUpc   = preg_match("/^(upc|pa_upc).*/i", $attribute['name']);
                $isIsbn  = preg_match("/^(isbn|pa_isbn).*/i", $attribute['name']);
                $isJan   = preg_match("/^(jan|pa_jan).*/i", $attribute['name']);


                /* if value is empty maybe the value is hiding in other woocommerce attributes. */
                if (empty($attribute['value'])) {
                    if (function_exists('wc_get_product_terms')){
                        $attrTerm = wc_get_product_terms($productId, $attribute['name'], array('fields' => 'names'));
                        if(!empty($attrTerm)) {
                            $attribute['value'] = array_shift($attrTerm);
                        }
                    } else {
                        $attrTerm = get_post_meta($productId, $attribute['name'], true);
                        $attribute['value'] = !empty($attrTerm) ? $attrTerm : $attribute['value'];
                    }
                }
                /* if value is empty and $parentProductId is not the same as $productId we try using that */
                if(empty($attribute['value']) && $parentProductId != $productId) {
                    if (function_exists('wc_get_product_terms')){
                        $attrTerm = wc_get_product_terms($parentProductId, $attribute['name'], array('fields' => 'names'));
                        if(!empty($attrTerm)) {
                            $attribute['value'] = array_shift($attrTerm);
                        }
                    } else {
                        $attrTerm = get_post_meta($parentProductId, $attribute['name'], true);
                        $attribute['value'] = !empty($attrTerm) ? $attrTerm : $attribute['value'];
                    }
                }

                // collect the values where we need them
                if ($isEan && !empty($attribute['value'])) {
                    $ean = $attribute['value'];
                } else if ($isBrand && !empty($attribute['value'])) {
                    $brand = $attribute['value'];
                } else if ($isUpc && !empty($attribute['value'])) {
                    $upc = $attribute['value'];
                } else if ($isIsbn && !empty($attribute['value'])) {
                    $isbn = $attribute['value'];
                } else if ($isJan && !empty($attribute['value'])) {
                    $jan = $attribute['value'];
                } else if (!empty($attribute['value'])) {
                    /* not en attribute we got special fields for so just ad it. */
                    $avecdoProduct->addToAttributes(null, $attribute['name'], $attribute['value']);
                }
            }
        }

        // set brand.
        if (!empty($brand) && !avecdoHasBrandsPluginInstalled()) {
            $avecdoProduct->setBrand($brand);
        } else {
            // brand not found so we look for it elsewhere
            $newBrand = $this->getBrand($productId, $parentProductId);
            if(!empty($newBrand) && !avecdoHasBrandsPluginInstalled()) {
                $avecdoProduct->setBrand($newBrand);
            } else {
                /* try plugins */
                include_once(ABSPATH.'wp-admin/includes/plugin.php');

                /*
                 * Plugin:
                 * Perfect WooCommerce Brands
                 * https://wordpress.org/plugins/perfect-woocommerce-brands/
                 */
                if (empty($brand) && (\is_plugin_active('perfect-woocommerce-brands/main.php') || \is_plugin_active('perfect-woocommerce-brands/perfect-woocommerce-brands.php'))) {
                    $brand = wp_get_post_terms($parentProductId, 'pwb-brand');
                    if(is_array($brand) && count($brand)>0){
                        $brand = array_shift($brand);
                        $brand = isset($brand->name) ? $brand->name : null;
                    } else {
                        $brand = null;
                    }
                }
                /*
                 * Plugin:
                 * Ultimate WooCommerce Brands PRO
                 * http://codecanyon.net/item/ultimate-woocommerce-brands-plugin/9433984
                 *
                 * Plugin:
                 * Ultimate WooCommerce Brands
                 * http://magniumthemes.com/
                 */
                if (empty($brand) && (
                        \is_plugin_active('mgwoocommercebrands/mgwoocommercebrands.php') ||
                        \is_plugin_active('mgwoocommercebrands/mgwoocommercebrands-light.php'))) {
                    $brand = wp_get_object_terms($parentProductId, 'product_brand');
                    if(is_array($brand) && count($brand)>0){
                        $brand = array_shift($brand);
                        $brand = isset($brand->name) ? $brand->name : null;
                    } else {
                        $brand = null;
                    }
                }

                /*
                 * Plugin:
                 * WooCommerce Brands
                 * https://woocommerce.com/products/brands/
                 */
                if (empty($brand) && \is_plugin_active('woocommerce-brands/woocommerce-brands.php')) {

                    $brand = wp_get_post_terms($parentProductId, 'product_brand');
                    if(is_array($brand) && count($brand)>0){
                        $brand = array_shift($brand);
                        $brand = isset($brand->name) ? $brand->name : null;
                    } else {
                        $brand = null;
                    }
                }


                if (!empty($brand)) {
                    $avecdoProduct->setBrand($brand);
                }
            }
        }
        // Set EAN, UPC, JAN, ISBN..
        // Edited 1.4.10
        if(!is_null($ean) && !empty($ean)) {
            $avecdoProduct->setEan($ean);
        } else {

            $newEAN = $this->getEAN($productId, $parentProductId);            // IMPORTANT need getean
            if(!empty($newEAN) && !avecdoHasEANPluginInstalled()) {
                $avecdoProduct->setEan($newEAN);
            } else {
                /* try plugins */
                include_once(ABSPATH.'wp-admin/includes/plugin.php');
                /*
                 * Plugin:
                 * Product GTIN (EAN, UPC, ISBN) for WooCommerce
                 * https://wordpress.org/plugins/product-gtin-ean-upc-isbn-for-woocommerce/
                 */

                if ((empty($ean) || is_null($ean) ) && \is_plugin_active('product-gtin-ean-upc-isbn-for-woocommerce/product-gtin-ean-upc-isbn-for-woocommerce.php')) {
                    //add_action('init', 'product-gtin-ean-upc-isbn-for-woocommerce');
                    foreach ($this->getMetaData($productId) as $metaRow) {
                        switch ($metaRow->meta_key) {
                            case "_wpm_gtin_code":
                                if (!empty($metaRow->meta_value)) {
                                    $avecdoProduct->setEan($metaRow->meta_value);
                                }
                                break;
                        }
                    }

                }
            }
        }
        if(!is_null($upc) && !empty($upc)) {
            $avecdoProduct->setUpc($upc);
        }
        if(!is_null($isbn) && !empty($isbn)) {
            $avecdoProduct->setIsbn($isbn);
        }
        if(!is_null($jan) && !empty($jan)) {
            $avecdoProduct->setJan($jan);
        }

        // get images from parent product if none is assigned to this variations of the product.
        if ($productId != $parentProductId && empty($imagesIds)) {
            $this->getImageIds($parentProductId, $imagesIds);
        }

        // set product images
        $this->assignAvecdoProductImages($imagesIds, $avecdoProduct, $parentProductId);

        // set categories and tags.
        $categoryIds = array();
        $tagIds      = array();
        $this->assignAvecdoProductCategoriesAndTags($parentProductId, $avecdoProduct, $categoryIds, $tagIds);


        /* Removed for now avecdo do not use this value so spare the resources... */
//        // related products
//        foreach ($this->getRelatedProductsByCategoriesAndTags($categoryIds, $tagIds) as $relatedProduct) {
//            if ($relatedProduct->productId == $productId) {
//                continue;
//            }
//            $avecdoProduct->addToRelatedProducts($relatedProduct->productId, $relatedProduct->name);
//        }
    }

    /**
     * Get all image ids associated with a product
     * @param integer $productId
     * @param array $imagesIds
     * @return void
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.2.5
     */
    private function getImageIds($productId, & $imagesIds)
    {
        $galleryImages = array();
        $thumbnailImages = array();

        foreach ($this->getMetaData($productId) as $metaRow) {
            switch ($metaRow->meta_key) {
                case "_product_image_gallery":
                    if (!empty($metaRow->meta_value)) {
                        $_metaValue = array_map('intval', explode(',', $metaRow->meta_value));
                        foreach ($_metaValue as $_mv) {
                            if (intval($_mv) > 0) {
                                $galleryImages[] = (int) $_mv;
                            }
                        }
                    }
                    break;
                case "_thumbnail_id":
                    if (intval($metaRow->meta_value) > 0) {
                        $thumbnailImages[] = (int) $metaRow->meta_value;
                    }
                    break;
            }
        }

        $imagesIds = array_merge($imagesIds, $thumbnailImages, $galleryImages);
    }

    /**
     * Assign product images to avecdo product object
     * @param int[] $imagesIds
     * @param Product|Combination $avecdoProduct
     * @return void
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.1.2
     */
    private function assignAvecdoProductImages($imagesIds, $avecdoProduct, $productId)
    {
        if (!is_array($imagesIds) || empty($imagesIds)) {

            $post_thumbnail_id = get_post_thumbnail_id($productId);
            $thumbnail_size    = apply_filters('woocommerce_product_thumbnails_large_size', 'full');
            $full_size_image   = wp_get_attachment_image_src($post_thumbnail_id, $thumbnail_size);
            $image_title       = get_post_field('post_excerpt', $post_thumbnail_id);
            if (empty($image_title)) {
                $image_title = get_post_field('post_title', $post_thumbnail_id);
            }
            if(!empty($full_size_image[0])) {
                $avecdoProduct->addToImages($full_size_image[0], $image_title);
            }
            return;
        }

        $imgsAdded = array();

        $mainImageId = get_post_thumbnail_id($productId);

        foreach ($imagesIds as $imageid) {

            $postmeta = get_post_meta($imageid);
            if (in_array($imageid, $imgsAdded)) {
                continue;
            }
            $imgsAdded[] = $imageid;
            $url         = wp_get_attachment_image_src($imageid, 'full');
            if ($url && isset($url[0])) {
                $text = $postmeta ? get_post_meta($imageid, '_wp_attachment_image_alt', TRUE) : '';
                $main = ($mainImageId == $imageid) ? true : false;
                $avecdoProduct->addToImages($url[0], $text, $imageid, 0, $main);
            }
        }

    }

    // should this still exists.
    private function getImageMetaById($id, $imageMetaData) {
        foreach($imageMetaData as $meta) {
            if($meta->post_id == $id) {
                return $meta;
            }
        }
    }

    /**
     * Assign attribute to avecdo product
     * @param object $metaRow must contain [meta_key, meta_value]
     * @param Product|Combination $avecdoProduct
     * @return void
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.1.2
     */
    private function assignAvecdoProductMetaData_Attribute($metaRow, $avecdoProduct)
    {
        if (strpos($metaRow->meta_key, 'attribute_') !== false) {
            // Taxonomy-based attributes are prefixed with pa_, otherwise simply attribute_.
            $taxonomyBase = (0 === strpos($metaRow->meta_key, 'attribute_pa_'));
            if ($taxonomyBase) {
                $name = str_replace('attribute_pa_', '', $metaRow->meta_key);
                if (preg_match("/^brand.*/i", $name)) {
                    $avecdoProduct->setBrand($metaRow->meta_value);
                }
                $attrObject = $this->getAttributeTaxonomyByName($name);
                $id         = is_array($attrObject) && isset($attrObject[0]->id) ? $attrObject[0]->id : 0;
                $avecdoProduct->addToAttributes($id, $name, $metaRow->meta_value);
            } else {
                $avecdoProduct->addToAttributes(0, str_replace('attribute_', '', $metaRow->meta_key), $metaRow->meta_value);
            }
        }
    }

    /**
     * create an avecdo category from $categpry object.
     *
     * -- Christian M. Jensen <christian@modified.dk>
     * Added category description, image and url.
     *
     * @param array $category
     * @return array
     */
    public function createAvecdoCategory($category)
    {
        if (!is_array($category)) {
            return;
        }
        $avecdoCategory = new Category();
        $avecdoCategory
            ->setCategoryId($category['categoryId'])
            ->setParent($category['parent'])
            ->setFullName($category['fullName'])
            ->setDepth($category['depth'])
            ->setName($category['name'])
            ->setDescription($category['description'])
            ->setImage($category['image'])
            ->setUrl($category['url']);
        return $avecdoCategory->getAll();
    }

    /**
     * Get value to use for units in weight
     * @return string
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.1.2
     */
    private function getWeightUnit()
    {
        if (is_null(static::$weight_unit)) {
            return static::$weight_unit = get_option('woocommerce_weight_unit', '');
        }
        return static::$weight_unit;
    }

    /**
     * Get value to use for units in dimension
     * @return string
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.1.2
     */
    private function getDimensionUnit()
    {
        if (is_null(static::$dimension_unit)) {
            return static::$dimension_unit = get_option('woocommerce_dimension_unit', '');
        }
        return static::$dimension_unit;
    }

    /**
     * Get all product categories
     * @param int $number Maximum number of terms to return. Accepts 0 (all) or any positive number. Default 0 (all).
     * @param int $offset The number by which to offset the terms query.
     * @param bool|int $hide_empty Whether to hide terms not assigned to any posts. Accepts 1|true or 0|false. Default 1|true.
     * @param bool $hierarchical Whether to include terms that have non-empty descendants (even if $hide_empty is set to true). Default true.
     * @param bool $pad_counts Whether to pad the quantity of a term's children in the quantity of each term's "count" object variable. Default false.
     * @param string $orderby Field(s) to order terms by. Accepts term fields ('name', 'slug', 'term_group', 'term_id', 'id', 'description'), 'count' for term taxonomy count, 'include' to match the 'order' of the $include param, 'meta_value', 'meta_value_num', the value of $meta_key, the array keys of $meta_query, or 'none' to omit the ORDER BY clause. Defaults to 'term_id'.
     * @param string $order Whether to order terms in ascending or descending order. Accepts 'ASC' (ascending) or 'DESC' (descending). Default 'ASC'.
     * @return array
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.1.2
     */
    private function loadCategories($number = 0, $offset = 0, $hide_empty = false, $hierarchical = true, $pad_counts = false, $orderby = 'term_id', $order = 'ASC')
    {
        $results        = array();
        $taxonomy       = 'product_cat';
        $args           = array(
            'taxonomy'     => $taxonomy,
            'orderby'      => $orderby,
            'order'        => $order,
            'parent'       => 0,
            'pad_counts'   => $pad_counts,
            'hierarchical' => $hierarchical,
            'hide_empty'   => $hide_empty,
            'number'       => $number,
            'offset'       => $offset,
        );
        $all_categories = get_categories($args);
        if (is_wp_error($all_categories)) {
            return array();
        }
        foreach ($all_categories as $cat) {
            if (is_wp_error($cat)) {
                continue;
            }
            $results += $this->getCategoryLoopItem($cat, '', $args, 1);
        }
        return $results;
    }
    /*
     * Load categories that has '$category_id' as parent
     * @param int $category_id parent category id
     * @param string $fullname the full name of the parrent categories
     * @param array $args ans array of arguments to use with function get_categories
     * @param int $depth current category depth
     * @return array
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.1.2
     */

    private function loadSubCategories($category_id, $fullname, $args, $depth = 1)
    {
        $results         = array();
        $subQuery        = array('parent' => $category_id);
        $args2           = array_merge($args, $subQuery);
        $args2['number'] = 0;
        $args2['offset'] = 0;
        $sub_cats        = get_categories($args2);
        if (is_wp_error($sub_cats)) {
            return array();
        }
        foreach ($sub_cats as $cat) {
            if (is_wp_error($cat)) {
                continue;
            }
            $results += $this->getCategoryLoopItem($cat, $fullname, $args, $depth);
        }
        return $results;
    }

    /**
     * get category item from category object or array
     * @param array|object $cat
     * @param string $fullname
     * @param array $args
     * @param int $depth
     * @return array
     */
    private function getCategoryLoopItem($cat, $fullname, $args, $depth = 1)
    {
        $results  = array();
        $fullname = $this->getCategoryFullName($fullname, $cat);
        if (is_object($cat)) {
            $term_link = get_term_link($cat->term_id, 'product_cat');
            if (is_wp_error($term_link)) {
                $term_link = "";
            }
            $thumbnail_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
            $image        = !is_null($thumbnail_id) ? wp_get_attachment_url($thumbnail_id) : null;

            $results[$cat->term_id] = $this->createAvecdoCategory(array(
                'categoryId'  => $cat->term_id,
                'name'        => !empty($cat->name) ? $cat->name : $cat->cat_name,
                'fullName'    => $fullname,
                'description' => !empty($cat->description) ? $cat->description : $cat->category_description,
                'parent'      => !empty($cat->parent) ? $cat->parent : $cat->category_parent,
                'url'         => $term_link,
                'depth'       => $depth,
                'image'       => $image
            ));
            // loop over any nested categories
            $results                += $this->loadSubCategories($results[$cat->term_id]['categoryId'], $results[$cat->term_id]['fullName'], $args, ++$depth);
        } else if (is_array($cat)) {
            $term_id   = (int) $cat['term_id'];
            $term_link = get_term_link($term_id, 'product_cat');
            if (is_wp_error($term_link)) {
                $term_link = "";
            }
            $thumbnail_id      = get_term_meta($term_id, 'thumbnail_id', true);
            $image             = !is_null($thumbnail_id) ? wp_get_attachment_url($thumbnail_id) : null;
            $results[$term_id] = $this->createAvecdoCategory(array(
                'categoryId'  => $term_id,
                'name'        => !empty($cat['name']) ? $cat['name'] : $cat['cat_name'],
                'fullName'    => $fullname,
                'description' => !empty($cat['description']) ? $cat['description'] : $cat['category_description'],
                'parent'      => !empty($cat['parent']) ? $cat['parent'] : $cat['category_parent'],
                'url'         => $term_link,
                'depth'       => $depth,
                'image'       => $image
            ));
            // loop over any nested categories
            $results           += $this->loadSubCategories($results[$term_id]['categoryId'], $results[$term_id]['fullName'], $args, ++$depth);
        }
        return $results;
    }

    /**
     * get the full name of the category
     * @param string $fullname
     * @param array|object $cat
     * @return string
     */
    private function getCategoryFullName($fullname, $cat)
    {
        if (!empty($fullname) && is_object($cat)) {
            return $fullname.' > '.(!empty($cat->name) ? $cat->name : $cat->cat_name);
        } else if (empty($fullname) && is_object($cat)) {
            return (!empty($cat->name) ? $cat->name : $cat->cat_name);
        } else if (!empty($fullname) && is_array($cat)) {
            return $fullname.' > '.(!empty($cat['name']) ? $cat['name'] : $cat['cat_name']);
        } else if (empty($fullname) && is_array($cat)) {
            return (!empty($cat['name']) ? $cat['name'] : $cat['cat_name']);
        }
        return $fullname;
    }

    /**
     * Get all categories in the shop.
     * @return array
     */
    public function getCategories($multiLanguageOptions = null, $activeKey = null)
    {
        $hasMultiCurrency = $this->isMultiCurrencyEnabled();

        $shopCurrencyLanguageSettings = $this->getMultiLangOptions($multiLanguageOptions,$activeKey);
        $_language  = $shopCurrencyLanguageSettings['language'];

        if ($hasMultiCurrency) {
            $language = $_language;
            global $sitepress;
            if ($sitepress) {
                $sitepress->switch_lang($language);     // This is necessary for retrieving categories in the chosen language.
            }
        }
        return $this->loadCategories();
    }

    /**
     * @param $productId
     * @return int
     * @author Aske Merci <asme@modified.dk>
     * @date 6/10/20 - 3:07 PM
     *
     * Loop through all shipping classes, and return number_formatted decimal, using the decimals sat in config.
     */
    public function getShippingPrice($productId)
    {
        $productData = wc_get_product($productId);
        $shippingId = $productData->get_shipping_class_id();
        // Get all your existing shipping zones IDS

        $zone_ids = array_keys( array('') + WC_Shipping_Zones::get_zones() );
        $rate = null;
        // Loop through shipping Zones IDs
        foreach ( $zone_ids as $zone_id )
        {
            // Get the shipping Zone object
            $shipping_zone = new WC_Shipping_Zone($zone_id);

            // Get all shipping method values for the shipping zone
            $shipping_methods = $shipping_zone->get_shipping_methods( true, 'values' );

            // Loop through each shipping methods set for the current shipping zone
            foreach ( $shipping_methods as $instance_id => $shipping_method )
            {
                // Check if the shop has a free shipping method. If it has, check if the min_amount is less or equal to product price
                if($shipping_method->id == 'free_shipping')
                {
                    if($shipping_method->min_amount <= $productData->get_price()) {
                        $rate = 0.00;
                    }
                }

                // We had an error, where the cost wouldn't get included. The reason is that "Warehouse Pickup" was enabled.
                // In Woocommerce, the warehouse pickup is an empty string in instance settings. So this is just to avoid it.
                if(!isset($shipping_method->instance_settings["cost"]) || $shipping_method->instance_settings["cost"] == "") {
                    continue;
                }
                // If the shippingId is sat to 0, then we will use global costs, else we add the shipping price, with the class price.
                if($shippingId == 0) {
                    if(isset($shipping_method->instance_settings["no_class_cost"])){
                        $rate = (float)$shipping_method->instance_settings["cost"] + (float)$shipping_method->instance_settings["no_class_cost"];
                    } else {
                        $rate = (float)$shipping_method->instance_settings["cost"];
                    }
                } else {
                    $rate = (float)$shipping_method->instance_settings["cost"] + (float)$shipping_method->instance_settings["class_cost_".$shippingId];
                }
            }
        }
        return (string)number_format($rate, wc_get_price_decimals()) ." ". get_woocommerce_currency();
    }

    public function getMultiLangOptions($multiLanguageOptions = null, $activeKey = null) {
        if($multiLanguageOptions !== null && $activeKey !== null){
            $publicKey = $activeKey->getPublicKey();
            foreach($multiLanguageOptions as $code => $shop){
                if($shop['public_key'] === $publicKey){
                    $_currency = $shop['currency_id'];
                    $_language = $shop['lang_code'];
                    break;
                }
            }
        }

        $_currency  = !isset($_currency) ? Option::get('currency') : $_currency;
        $_language  = !isset($_language) ? Option::get('language') : $_language;

        return ['currency' => $_currency, 'language' => $_language];
    }
}