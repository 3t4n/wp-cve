<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Repository\ProductUrlRepository;

class ProductUrlManager
{
    /** @var ProductUrlRepository */
    private $productUrlRepository;

    public function __construct()
    {
        $this->productUrlRepository = new ProductUrlRepository();
    }

    /**
     * @param array $productsUrlIndexedByProductShopId
     * @param array $variationsUrlIndexedByProductShopId
     * @return void
     * @throws WooCommerceApiExceptionInterface
     */
    public function updateProductsUrl(array $productsUrlIndexedByProductShopId, array $variationsUrlIndexedByProductShopId): void
    {
        if (empty($productsUrlIndexedByProductShopId)) {
            return;
        }

        $dateNow = new \DateTime('now', new \DateTimeZone('UTC'));

        $languageManager = new LanguageReportManager();
        $language = explode('_', $languageManager->getDefaultLanguageIsoCode());
        $isoCode = $language[1];

        foreach ($productsUrlIndexedByProductShopId as $productUrl) {
            if (!empty($variationsUrlIndexedByProductShopId) && array_key_exists($productUrl['ID'], $variationsUrlIndexedByProductShopId)) {
                foreach ($variationsUrlIndexedByProductShopId[$productUrl['ID']] as $variation) {
                    $data = [
                        'product_shop_id' => $productUrl['ID'],
                        'variation_shop_id' => $variation['variationShopId'],
                        'iso_code' => $isoCode,
                        'url' => $variation['url'],
                        'date_add' => $dateNow->format('Y-m-d H:i:s'),
                        'date_update' => $dateNow->format('Y-m-d H:i:s'),
                    ];

                    $this->productUrlRepository->updateProductsUrl($data);
                }
            }

            $data = [
                'product_shop_id' => $productUrl['ID'],
                'variation_shop_id' => '0',
                'iso_code' => $isoCode,
                'url' => $productUrl['guid'],
                'date_add' => $dateNow->format('Y-m-d H:i:s'),
                'date_update' => $dateNow->format('Y-m-d H:i:s'),
            ];

            $this->productUrlRepository->updateProductsUrl($data);
        }
    }

    /**
     * @param \DateTime $date
     * @return array|object|null
     */
    public function getProductUrls(\DateTime $date)
    {
        return $this->productUrlRepository->getProductUrls($date);
    }

    /**
     * @param \DateTime $date
     * @return array|object|null
     */
    public function getVariationsUrls(\DateTime $date)
    {
        return $this->productUrlRepository->getVariationsUrls($date);
    }

    public function cleanTable(): void
    {
        $this->productUrlRepository->cleanTable();
    }
}