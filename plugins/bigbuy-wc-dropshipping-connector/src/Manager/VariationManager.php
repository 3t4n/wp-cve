<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\WCVariation;
use WcMipConnector\Enum\WooCommerceApiMethodTypes;
use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Model\VariationReport;
use WcMipConnector\Model\WCBatchRequest;
use WcMipConnector\Repository\VariationRepository;
use WcMipConnector\Service\WoocommerceApiAdapterService;

class VariationManager
{
    /** @var WoocommerceApiAdapterService */
    protected $apiAdapterService;
    /** @var VariationRepository */
    protected $variationRepository;

    public function __construct()
    {
        $this->apiAdapterService = new WoocommerceApiAdapterService();
        $this->variationRepository = new VariationRepository();
    }

    /**
     * @param WCVariation[] $variations
     * @param int $variationParentId
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function createCollection(array $variations, int $variationParentId): array
    {
        if (!$variations) {
            return [];
        }

        $request = new WCBatchRequest();
        $request->create = $variations;
        $queryParams = ['product_id' => $variationParentId];

        return $this->apiAdapterService->batchItems(
            WooCommerceApiMethodTypes::TYPE_PRODUCT_VARIATIONS,
            $request,
            $queryParams
        );
    }

    /**
     * @param array $variationRequest
     * @param int $variationParentId
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function updateItem(array $variationRequest, int $variationParentId): array
    {
        $variationRequest['product_id'] = $variationParentId;

        return $this->apiAdapterService->updateItem(
            WooCommerceApiMethodTypes::TYPE_PRODUCT_VARIATIONS,
            $variationRequest
        );
    }

    /**
     * @param WCVariation[] $variations
     * @param int $variationParentId
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function updateCollection(array $variations, int $variationParentId): array
    {
        if (!$variations) {
            return [];
        }

        $request = new WCBatchRequest();
        $request->update = $variations;
        $queryParams = ['product_id' => $variationParentId];

        return $this->apiAdapterService->batchItems(
            WooCommerceApiMethodTypes::TYPE_PRODUCT_VARIATIONS,
            $request,
            $queryParams
        );
    }

    /**
     * @return VariationReport
     */
    public function getVariationReport(): VariationReport
    {
        $response = new VariationReport();

        $totalProducts = $this->variationRepository->getTotalVariations();
        $response->TotalProductVariations = (int)$totalProducts['total'];
        $activeProducts = $this->variationRepository->getActiveVariations();
        $response->ActiveProductVariations = (int)$activeProducts['totalActive'];
        $productVariations = $this->variationRepository->getVariations();
        $response->ProductVariations = [];

        foreach ($productVariations as $productVariation) {
            $productVariation['Active'] = $productVariation['Active'] === 'publish';
            $productVariation['Stock'] = (int)$productVariation['Stock'];
            $response->ProductVariations[] = $productVariation;
        }

        return $response;
    }

    /**
     * @param array $variationShopIdsIndexedByProductId
     * @return array
     */
    public function getVariationsUrlIndexedByProductShopId(array $variationShopIdsIndexedByProductId): array
    {
        return $this->variationRepository->getVariationsUrlIndexedByProductShopId($variationShopIdsIndexedByProductId);
    }
}