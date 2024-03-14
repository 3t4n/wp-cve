<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\WCTag;
use WcMipConnector\Enum\WooCommerceApiMethodTypes;
use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Model\WCBatchRequest;
use WcMipConnector\Service\WoocommerceApiAdapterService;
use WcMipConnector\Repository\TagRepository;

class TagManager
{
    /** @var WoocommerceApiAdapterService */
    protected $apiAdapterService;
    /** @var TagRepository */
    protected $repository;
    /** @var TagMapManager */
    protected $tagMapManager;

    public function __construct()
    {
        $this->apiAdapterService = new WoocommerceApiAdapterService();
        $this->repository = new TagRepository();
        $this->tagMapManager = new TagMapManager();
    }

    /**
     * @param array $tagMapIds
     *
     * @return array
     */
    public function findByTagShopIdsIndexedByTagId(array $tagMapIds): array
    {
        return $this->repository->findByTagShopIdsIndexedByTagId($tagMapIds);
    }

    /**
     * @param int $tagId
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function delete(int $tagId): array
    {
        $tagRequest = ['id' => $tagId];

        return $this->apiAdapterService->deleteItem(WooCommerceApiMethodTypes::TYPE_PRODUCT_TAGS, $tagRequest, ['force' => true]);
    }

    /**
     * @param array $tagIds
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function deleteCollection(array $tagIds): array
    {
        if (!$tagIds) {
            return [];
        }

        $request = new WCBatchRequest();
        $request->delete = $tagIds;

        return $this->apiAdapterService->batchItems(WooCommerceApiMethodTypes::TYPE_PRODUCT_TAGS, $request);
    }

    /**
     * @param WCTag[] $tags
     *
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function updateCollection(array $tags): array
    {
        if (!$tags) {
            return [];
        }

        $request = new WCBatchRequest();
        $request->update = $tags;

        return $this->apiAdapterService->batchItems(WooCommerceApiMethodTypes::TYPE_PRODUCT_TAGS, $request);
    }

    /**
     * @param WCTag[] $tags
     *
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function createCollection(array $tags): array
    {
        if (!$tags) {
            return [];
        }

        $request = new WCBatchRequest();
        $request->create = $tags;

        return $this->apiAdapterService->batchItems(WooCommerceApiMethodTypes::TYPE_PRODUCT_TAGS, $request);
    }

    /**
     * @param array $productIds
     * @return array
     */
    public function getProductTagsIndexedByProductIds(array $productIds): array
    {
        $productTags = $this->repository->getProductTagsIndexedByProductIds($productIds);
        $productTagsIndexedByProductId = [];

        foreach ($productTags as $productTag) {
            $productTagsIndexedByProductId[$productTag['object_id']][] = $productTag['term_id'];
        }

        return $productTagsIndexedByProductId;
    }

    /**
     * @param string $slug
     * @return string
     */
    public function findTagShopIdBySlug(string $slug): ?string
    {
        return $this->repository->findTagShopIdBySlug($slug);
    }

    /**
     * @param array $tagsSlug
     * @return array
     */
    public function findTagsShopIdIndexedBySlug(array $tagsSlug): array
    {
        return $this->repository->findTagsShopIdIndexedBySlug($tagsSlug);
    }

    /**
     * @return array
     */
    public function findUnusedTagShopIds(): array
    {
        return $this->repository->findUnusedTagShopIds();
    }

    /**
     * @param string $tagName
     */
    public function deleteByName(string $tagName) : void
    {
        $tagId = $this->repository->findByName($tagName);

        if (!$tagId) {
            return;
        }

        $this->deleteRelationshipById($tagId);
        $this->delete($tagId);
        $this->tagMapManager->delete($tagId);
    }

    /**
     * @param int $tagId
     */
    private function deleteRelationshipById(int $tagId): void
    {
        $this->repository->deleteRelationshipById($tagId);
    }
}