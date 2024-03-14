<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\WCDimension;
use WcMipConnector\Entity\WCProduct;
use WcMipConnector\Entity\WCProductLang;
use WcMipConnector\Entity\WCSrc;
use WcMipConnector\Service\SupplierService;
use WcMipConnector\Service\TaxesService;

class ProductFactory
{
    private const PUBLISH_STATUS = 'publish';
    private const VISIBILITY_STATUS = 'visible';
    private const VARIABLE = 'variable';
    private const SIMPLE = 'simple';

    /** @var TaxesService */
    private $taxesService;
    /** @var SupplierService */
    private $supplierService;

    public function __construct()
    {
        $this->taxesService = new TaxesService();
        $this->supplierService = new SupplierService();
    }

    /**
     * @param array $product
     * @param array $tagIds
     * @param array $categoryIds
     * @param array $brandIds
     * @param array $brandPluginIds
     * @param array $attributesIds
     * @param string $languageIsoCode
     * @param array $taxes
     * @return WCProduct
     */
    public function create(
        array $product,
        array $tagIds,
        array $categoryIds,
        array $brandIds,
        array $brandPluginIds,
        array $attributesIds,
        string $languageIsoCode,
        array $taxes
    ): WCProduct {
        $productModel = new WCProduct();

        $productLangModel = $this->createLang($product['ProductLangs'], $languageIsoCode);

        $productModel->name = $productLangModel->title;
        $productModel->description = $productLangModel->description;
        $productModel->sku = $product['SKU'];
        $productModel->price = $product['Price'];
        $productModel->regular_price = (string)$product['Price'];
        $productModel->sale_price = (string)$product['Price'];
        $productModel->type = self::SIMPLE;

        if ($product['Tax']['TaxID']) {
            $taxClass = $this->taxesService->getTaxClassByTaxId($taxes, (int)$product['Tax']['TaxID']);
            $productModel->tax_class = $taxClass;
        }

        if ($product['Variations']) {
            $productModel->type = self::VARIABLE;
        }

        if ($product['Stock'] > 0) {
            $productModel->in_stock = true;
        }

        $productModel->manage_stock = true;
        $productModel->stock_quantity = $product['Stock'];
        $productModel->weight = (string)$product['Weight'];

        $dimensionModel = new WCDimension();
        $dimensionModel->length = (string)$product['Length'];
        $dimensionModel->width = (string)$product['Width'];
        $dimensionModel->height = (string)$product['Height'];

        $productModel->status = self::PUBLISH_STATUS;
        $productModel->catalog_visibility = self::VISIBILITY_STATUS;

        $productModel->dimensions = $dimensionModel;
        $productModel->images = $this->createImage($product['Images'], $product['SKU']);
        $productModel->tags = $tagIds;
        $productModel->categories = $categoryIds;

        $supplier = $this->supplierService->getAttribute();
        $attributes = \array_merge($attributesIds, [$brandIds], [$supplier]);

        $productModel->attributes = $attributes;
        $productModel->id = null;
        $productModel->brands = $brandPluginIds;

        return $productModel;
    }

    /**
     * @param array $productLangs
     * @param string $languageIsoCode
     * @return WCProductLang
     */
    private function createLang(array $productLangs, string $languageIsoCode): WCProductLang
    {
        $productLangModel = new WCProductLang();

        foreach ($productLangs as $productLang) {
            if (strtolower($productLang['IsoCode']) === strtolower($languageIsoCode)) {
                $productLangModel->title = $productLang['Title'];
                $productLangModel->description = $productLang['Description'];

                return $productLangModel;
            }
        }

        return $productLangModel;
    }

    /**
     * @param array $productImages
     * @param string $sku
     * @return WCSrc[]
     */
    private function createImage(array $productImages, string $sku): array
    {
        $images = [];

        foreach ($productImages as $key => $productImage) {
            $srcModel = new WCSrc();
            $srcModel->position = $key;
            $srcModel->name = $sku.'_'.$key;
            $srcModel->src = $productImage['ImageURL'];
            $srcModel->alt = $sku.'_'.$key;
            $images[] = $srcModel;
        }

        return $images;
    }

    /**
     * @param int $productStock
     *
     * @return int
     */
    private function checkStock(int $productStock): int
    {
        if ($productStock > StockFactory::MAXIMUM_AVAILABLE_STOCK) {
            return StockFactory::MAXIMUM_AVAILABLE_STOCK;
        }

        return $productStock;
    }

    /**
     * @param array $tags
     * @param WCProduct $product
     * @return array
     */
    public function setTags(array $tags, WCProduct $product): array
    {
        foreach ($tags as $tag) {
            $product->tags[] = ['id' => (int)$tag];
        }

        return $product->tags;
    }
}