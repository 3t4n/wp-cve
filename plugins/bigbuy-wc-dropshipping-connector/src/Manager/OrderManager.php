<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Enum\WooCommerceApiMethodTypes;
use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Repository\OrderLogRepository;
use WcMipConnector\Service\WoocommerceApiAdapterService;

class OrderManager
{
    /** @var WoocommerceApiAdapterService */
    protected $apiAdapterService;
    /** @var OrderLogRepository  */
    protected $orderLogRepository;

    public function __construct()
    {
        $this->apiAdapterService = new WoocommerceApiAdapterService();
        $this->orderLogRepository = new OrderLogRepository();
    }

    /**
     * @param array|string[] $status
     * @param string $orderBy
     * @param int $page
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function getOrders(array $status = ['Processing'], string $orderBy = 'date_created_gmt', int $page = 1): array
    {
        $filters = ['orderby' => $orderBy, 'page' => $page, 'status' => $status,];

        return $this->apiAdapterService->getItems(WooCommerceApiMethodTypes::TYPE_ORDERS, $filters);
    }

    /**
     * @param int $id
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function getById(int $id): array
    {
        return $this->apiAdapterService->getItem(WooCommerceApiMethodTypes::TYPE_ORDERS, (string)$id);
    }

    /**
     * @param int $orderId
     * @param string $status
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function updateStatus(int $orderId, string $status): array
    {
        $order = [
            'id' => $orderId,
            'status' => $status
        ];

        return $this->apiAdapterService->updateItem(WooCommerceApiMethodTypes::TYPE_ORDERS, $order);
    }

    /**
     * @param int $orderId
     * @param array $orderNote
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function addOrderNote(int $orderId, array $orderNote): array
    {
        $orderNote['order_id'] = $orderId;

        return $this->apiAdapterService->createItem(WooCommerceApiMethodTypes::TYPE_ORDER_NOTES, $orderNote);
    }

    /**
     * @param int $orderId
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function findOrderNotesById(int $orderId): array
    {
        $filters['order_id'] = $orderId;

        return $this->apiAdapterService->getItems(WooCommerceApiMethodTypes::TYPE_ORDER_NOTES, $filters);
    }
}