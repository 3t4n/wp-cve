<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\ProductMap;
use WcMipConnector\Repository\ProductMapRepository;

class ProductMapManager
{
    /** @var ProductMapRepository */
    protected $productMapRepository;

    public function __construct()
    {
        $this->productMapRepository = new ProductMapRepository();
    }

    /**
     * @param array $productMapIds
     *
     * @return array
     */
    public function findByProductMapIdsIndexedByProductMapId(array $productMapIds): array
    {
        return $this->productMapRepository->findByProductMapIdsIndexedByProductMapId($productMapIds);
    }

    /**
     * @param string[] $productIds
     * @return array<string, string>
     */
    public function findProductIdsIndexedByProductShopId(array $productIds): array
    {
      return $this->productMapRepository->findProductIdsIndexedByProductShopId($productIds);
    }

    /**
     * @param array $productMapIds
     *
     * @return array
     */
    public function findVersionsIndexedByProductMapId(array $productMapIds): array
    {
        return $this->productMapRepository->findVersionsIndexedByProductMapId($productMapIds);
    }

    /**
     * @param ProductMap $productMap
     *
     * @return bool
     */
    public function save(ProductMap $productMap): bool
    {
        $data = [
            'product_id' => $productMap->productId,
            'product_shop_id' => $productMap->shopProductId,
            'image_version' => $productMap->imageVersion,
            'message_version' => $productMap->messageVersion,
            'version' => $productMap->version,
            'date_add' => date('Y-m-d H:i:s'),
            'date_update' => null,
        ];

        return $this->productMapRepository->save($data);
    }

    /**
     * @param int $productShopId
     * @param ProductMap $productMap
     *
     * @return bool
     */
    public function update(int $productShopId, ProductMap $productMap): bool
    {
        $data = [
            'product_shop_id' => $productShopId,
            'image_version' => $productMap->imageVersion,
            'message_version' => $productMap->messageVersion,
            'version' => $productMap->version,
            'date_update' => date('Y-m-d H:i:s'),
        ];

        $where = [
            'product_id' => $productMap->productId,
        ];

        return $this->productMapRepository->update($data, $where);
    }

    /**
     * @return array
     */
    public function getProductsShopIndexedByProductShopId(): array
    {
        return $this->productMapRepository->getProductsShopIndexedByProductShopId();
    }

    /**
     * @param array $productShopIds
     * @return array
     */
    public function findImageVersionIndexedByProductMapId(array $productShopIds): array
    {
        return $this->productMapRepository->findImageVersionIndexedByProductMapId($productShopIds);
    }

    /**
     * @param int $productId
     * @param \DateTime $messageVersion
     */
    public function setMessageVersion(int $productId, \DateTime $messageVersion): void
    {
        $data = [
            'message_version' => $messageVersion->format('Y-m-d H:i:s'),
        ];

        $where = [
            'product_id' => $productId,
        ];

        $this->productMapRepository->update($data, $where);
    }

    /**
     * @param array $productIdIndexedByProductId
     * @return array
     */
    public function getMessageVersionIndexedByProductId(array $productIdIndexedByProductId): array
    {
        return $this->productMapRepository->getMessageVersionIndexedByProductId($productIdIndexedByProductId);
    }

    public function cleanTable(): void
    {
        $this->productMapRepository->cleanTable();
    }
}