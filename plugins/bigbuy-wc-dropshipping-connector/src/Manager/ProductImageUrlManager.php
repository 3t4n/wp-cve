<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Repository\ProductImageUrlRepository;

class ProductImageUrlManager
{
    /** @var ProductImageUrlRepository */
    private $productImageUrlRepository;

    /**
     * ProductImageUrlManager constructor.
     */
    public function __construct()
    {
        $this->productImageUrlRepository = new ProductImageUrlRepository();
    }

    /**
     * @param array $productImages
     * @return void
     * @throws \Exception
     */
    public function updateProductImageUrl(array $productImages): void
    {
        if (empty($productImages)) {
            return;
        }

        $dateNow = new \DateTime('now', new \DateTimeZone('UTC'));

        foreach ($productImages as $productImage) {
            $data = [
                'product_shop_id' => $productImage['post_parent'],
                'id_image' => $productImage['ID'],
                'date_add' => $dateNow->format('Y-m-d H:i:s'),
                'date_update' => $dateNow->format('Y-m-d H:i:s'),
            ];

            $this->productImageUrlRepository->updateProductImageUrl($data);
        }
    }

    /**
     * @param \DateTime $date
     * @return array|object|null
     */
    public function getImagesUrls(\DateTime $date)
    {
        return $this->productImageUrlRepository->getImagesUrls($date);
    }

    public function cleanTable(): void
    {
        $this->productImageUrlRepository->cleanTable();
    }
}